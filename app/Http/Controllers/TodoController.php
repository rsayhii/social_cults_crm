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
    $high = Todo::where('priority', 'high')->latest()->get();
    $medium = Todo::where('priority', 'medium')->latest()->get();
    $low = Todo::where('priority', 'low')->latest()->get();

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

        return redirect()->back()->with('success', 'Task updated successfully!');
    }

    // DELETE TASK (ONLY IF USER IS ASSIGNED)
    public function destroy(Todo $todo)
    {
       $this->authorize('manage', $todo);

        $todo->delete();
        return redirect()->back()->with('success', 'Task deleted successfully!');
    }

    // TOGGLE COMPLETED STATUS (ONLY IF USER IS ASSIGNED)
    public function toggleCompleted(Todo $todo)
    {
        $this->authorize('manage', $todo);

        $todo->completed = !$todo->completed;
        $todo->status = $todo->completed ? 'completed' : 'pending';
        $todo->save();

        return response()->json(['success' => true]);
    }
}
