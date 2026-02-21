<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Todo;

class TodoController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    // SHOW TODO PAGE + FETCH DATA (ONLY AUTH USER)
public function index()
{
    $userId = auth()->id();

    $high = Todo::where('priority', 'high')
        ->whereJsonContains('assigned_users', $userId)
        ->latest()
        ->get();

    $medium = Todo::where('priority', 'medium')
        ->whereJsonContains('assigned_users', $userId)
        ->latest()
        ->get();

    $low = Todo::where('priority', 'low')
        ->whereJsonContains('assigned_users', $userId)
        ->latest()
        ->get();

    return view('admin.todo', compact('high', 'medium', 'low'));
}




    // STORE NEW TASK (ASSIGN TO AUTH USER)
    public function store(Request $request)
    {
        $request->validate([
            'title'       => 'required',
            'description' => 'nullable',
            'priority'    => 'required|in:high,medium,low',
            'due_date'    => 'nullable|date',
            'category'    => 'required',
            'status'      => 'required|in:pending,inprogress,completed,onhold',
        ]);

        Todo::create([
            'title'          => $request->title,
            'description'    => $request->description,
            'priority'       => $request->priority,
            'due_date'       => $request->due_date,
            'category'       => $request->category,
            'status'         => $request->status,
            'starred'        => false,
            'completed'      => false,
            'assigned_users' => [auth()->id()], // ✅ IMPORTANT
        ]);

//         notifyCompany(auth()->user()->company_id, [
//     'title' => 'New Todo Added',
//     'message' =>$request->title . ' was added',
//     'module' => 'todo',
//     'url' => route('todo.index'),
//     'icon' => 'user',
// ]);

        return redirect()->back()->with('success', 'Task created successfully!');
    }

    // UPDATE TASK (ONLY IF USER IS ASSIGNED)
    public function update(Request $request, Todo $todo)
    {
         $this->authorize('manage', $todo);

        $todo->update($request->only([
            'title',
            'description',
            'priority',
            'due_date',
            'category',
            'status',
            'starred',
            'completed',
        ]));

        // notifyCompany(auth()->user()->company_id, [
        //     'title' => 'Todo Updated',
        //     'message' => ($todo->title ?? 'Untitled') . ' was updated',
        //     'module' => 'todo',
        //     'url' => route('todo.index'),
        //     'icon' => 'edit',
        // ]);

        return redirect()->back()->with('success', 'Task updated successfully!');
    }

    // DELETE TASK (ONLY IF USER IS ASSIGNED)
    public function destroy(Todo $todo)
    {
       $this->authorize('manage', $todo);

        $todo->delete();
        // notifyCompany(auth()->user()->company_id, [
        //     'title' => 'Todo Deleted',
        //     'message' => ($todo->title ?? 'Untitled') . ' was deleted',
        //     'module' => 'todo',
        //     'url' => route('todo.index'),
        //     'icon' => 'trash',
        // ]);
        return redirect()->back()->with('success', 'Task deleted successfully!');
    }

    // TOGGLE COMPLETED STATUS (ONLY IF USER IS ASSIGNED)
    public function toggleCompleted(Todo $todo)
    {
        $this->authorize('manage', $todo);

        $todo->completed = !$todo->completed;
        $todo->status = $todo->completed ? 'completed' : 'pending';
        $todo->save();

        // notifyCompany(auth()->user()->company_id, [
        //     'title' => $todo->completed ? 'Todo Completed' : 'Todo Reopened',
        //     'message' => ($todo->title ?? 'Untitled'),
        //     'module' => 'todo',
        //     'url' => route('todo.index'),
        //     'icon' => $todo->completed ? 'check-circle' : 'undo',
        // ]);

        return response()->json(['success' => true]);
    }
}
