<?php

namespace App\Http\Controllers\Sales;

use App\Http\Controllers\Controller;
use App\Models\Mylead;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;


class MyLeadsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
         $myleads = Mylead::with('client')
            ->where('user_id', Auth::id())
            ->latest()
            ->get();

        return view('admin.sales.myleads', compact('myleads'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
         $request->validate([
            'client_id' => 'required',
            'response' => 'required|string',
            'project_type' => 'required|string',
            'next_follow_up' => 'nullable|date',
            'follow_up_time' => 'nullable',
            'status' => 'required|string',
        ]);


        $lead = new Mylead();
        $lead->user_id = Auth::id();
        $lead->client_id = $request->client_id;
        $lead->response = $request->response;
        $lead->next_follow_up = $request->next_follow_up;
        $lead->project_type = $request->project_type;
        $lead->follow_up_time = $request->follow_up_time;
        $lead->status = $request->status;
        $lead->save();

        return back()->with('success', 'Lead response saved successfully!');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $lead = Mylead::with('client')
        ->where('user_id', Auth::id())
        ->findOrFail($id);
 

    return view('admin.sales.myleads-show', compact('lead'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $lead = Mylead::with('client')
            ->where('user_id', Auth::id())
            ->findOrFail($id);

         

        return view('admin.sales.myleads-edit', compact('lead'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
         $lead = Mylead::with('client')
            ->where('user_id', Auth::id())
            ->findOrFail($id);

        // Validation rules
        $request->validate([
            'response' => 'required|string|max:1000',
            'next_follow_up' => 'nullable|date|after_or_equal:today',
            'project_type' => 'nullable|string',
            'status' => 'required|string',
        ]);


        // Update the lead
      $lead->update([
    'response' => $request->response,
    'next_follow_up' => $request->next_follow_up ? $request->next_follow_up : null,
    'follow_up_time' => $request->follow_up_time,
    'project_type' => $request->project_type,
    'status' => $request->status,
]);


        // Optional: Redirect with success message
        return redirect()->route('myleads')
            ->with('success', 'Lead updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
    
}
