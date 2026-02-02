<?php
// Controller: app/Http/Controllers/TaskController.php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Task;
use App\Models\User;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use App\Models\Role;

class TaskController extends Controller
{
    public function index()
    {
        $tasks = Task::with('users', 'role', 'assigner.roles')->recent()->get();
        $users = User::with('roles')->get();
        $roles = Role::forCompany(Auth::user()->company_id)->get();

        // Calculate stats
        $totalTasks = $tasks->count();
        $pending = $tasks->where('status', 'Pending')->count();
        $inProgress = $tasks->where('status', 'In Progress')->count();
        $completed = $tasks->where('status', 'Completed')->count();

        return view('admin.task', compact(
            'tasks',
            'users',
            'roles',
            'totalTasks',
            'pending',
            'inProgress',
            'completed'
        ));
    }

    public function show(Task $task)
{
    $this->authorize('manage', $task);

    $task->load('users', 'role', 'assigner.roles');
    return view('admin.task-show', compact('task'));
}


   public function edit(Task $task)
{
    $this->authorize('manage', $task);

    $task->load('users', 'role', 'assigner.roles');
    $users = User::with('roles')->get();
    $roles = Role::forCompany(Auth::user()->company_id)->get();

    return view('admin.task-edit', compact('task', 'users', 'roles'));
}


    public function store(Request $request)
    {
        // (Unchanged from your original)
        $rules = [
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'category' => 'nullable|string',
            'priority' => 'required|in:Low,Medium,High',
           'due_date' => 'nullable|date|after_or_equal:today',
            'assigned_type' => 'required|in:individual,team',
            'attachments.*' => 'file|mimes:pdf,jpg,png,doc|max:10240',
        ];

        $assignedType = $request->input('assigned_type');
        if ($assignedType === 'individual') {
            $rules['assigned_users'] = 'required|array|min:1';
            $rules['assigned_users.*'] = 'exists:users,id';
        } elseif ($assignedType === 'team') {
            $rules['assigned_role'] = 'required|exists:roles,id';
        }

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed.',
                'errors' => $validator->errors()
            ], 422);
        }

        $validated = $validator->validated();

        $task = Task::create([
            'title' => $validated['title'],
            'description' => $validated['description'] ?? null,
            'category' => $validated['category'] ?? null,
            'priority' => $validated['priority'],
            'status' => 'Pending',
            'due_date' => $validated['due_date'] ?? null,
            'assigned_by' => Auth::id(),
        ]);

        // Handle attachments
        $attachments = [];
        if ($request->hasFile('attachments')) {
            foreach ($request->file('attachments') as $file) {
                $path = $file->store('task_attachments', 'public');
                $attachments[] = $path;
            }
            $task->update(['attachments' => $attachments]);
        }

        // Handle assignment
        if ($assignedType === 'individual') {
            $userIds = $validated['assigned_users'];
            $task->assignToUsers($userIds);
        } else {
            $roleId = $validated['assigned_role'];
            $task->assignToTeam($roleId);
        }

        notifyCompany(auth()->user()->company_id, [
    'title' => 'Task Assigned',
    'message' => 'A new task was assigned',
    'module' => 'task',
    'url' => route('tasks.index'),
    'icon' => 'task',
]);


        return response()->json([
            'success' => true,
            'task' => $task->load('users', 'role', 'assigner.roles')->toArray()
        ]);
    }

   public function update(Request $request, Task $task)
{
    $this->authorize('manage', $task);
    // Similar validation to store, but allow partial updates
    $rules = [
        'title' => 'sometimes|required|string|max:255',
        'description' => 'nullable|string',
        'category' => 'nullable|string',
        'priority' => 'sometimes|required|in:Low,Medium,High',
        'status' => 'sometimes|required|in:Pending,In Progress,Completed',
        'due_date' => 'nullable|date|after_or_equal:today',
        'assigned_type' => 'sometimes|required|in:individual,team',
        'attachments.*' => 'file|mimes:pdf,jpg,png,doc|max:10240',
        'remove_attachments' => 'sometimes|array',
        'remove_attachments.*' => 'string',
    ];

    $assignedType = $request->input('assigned_type', $task->assigned_to_team ? 'team' : 'individual');
    if ($assignedType === 'individual') {
        $rules['assigned_users'] = 'sometimes|required|array|min:1';
        $rules['assigned_users.*'] = 'exists:users,id';
    } elseif ($assignedType === 'team') {
        $rules['assigned_role'] = 'sometimes|required|exists:roles,id';
    }

    $validator = Validator::make($request->all(), $rules);

    if ($validator->fails()) {
        return response()->json([
            'success' => false,
            'message' => 'Validation failed.',
            'errors' => $validator->errors()
        ], 422);
    }

    $validated = $validator->validated();

    // Handle removals first (if provided)
    $removedAttachments = $request->input('remove_attachments', []);
    $existingAttachments = $task->attachments ?? [];
    if (!empty($removedAttachments)) {
        $filteredAttachments = array_filter($existingAttachments, function($path) use ($removedAttachments) {
            return !in_array($path, $removedAttachments);
        });
        // Delete files from storage
        foreach ($removedAttachments as $path) {
            if (Storage::disk('public')->exists($path)) {
                Storage::disk('public')->delete($path);
            }
        }
        $task->update(['attachments' => array_values($filteredAttachments)]);
        $existingAttachments = array_values($filteredAttachments);
    }

    // Update core fields
    $task->update(array_filter([
        'title' => $validated['title'] ?? $task->title,
        'description' => $validated['description'] ?? $task->description,
        'category' => $validated['category'] ?? $task->category,
        'priority' => $validated['priority'] ?? $task->priority,
        'status' => $validated['status'] ?? $task->status,
        'due_date' => $validated['due_date'] ?? $task->due_date,
    ]));

    // Append new attachments
    if ($request->hasFile('attachments')) {
        foreach ($request->file('attachments') as $file) {
            $path = $file->store('task_attachments', 'public');
            $existingAttachments[] = $path;
        }
        $task->update(['attachments' => $existingAttachments]);
    }

    // Re-handle assignment if type provided
    if (isset($validated['assigned_type'])) {
        // Clear existing assignments (assume methods handle this)
        $task->users()->detach();
        $task->role()->dissociate();
        $task->update(['assigned_to_team' => $assignedType === 'team']);

        if ($assignedType === 'individual') {
            $userIds = $validated['assigned_users'];
            $task->assignToUsers($userIds);
        } else {
            $roleId = $validated['assigned_role'];
            $task->assignToTeam($roleId);
        }
    }

    return response()->json([
        'success' => true,
        'task' => $task->load('users', 'role', 'assigner.roles')->toArray()
    ]);
}
    public function destroy(Task $task)
    {
         $this->authorize('manage', $task);
        // Clean up attachments
        if ($task->attachments) {
            foreach ($task->attachments as $attachment) {
                Storage::disk('public')->delete($attachment);
            }
        }

        // Clear assignments
        $task->users()->detach();
        $task->role()->dissociate();

        $task->delete();

        return response()->json(['success' => true, 'message' => 'Task deleted successfully']);
    }
}
