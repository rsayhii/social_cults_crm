<?php
namespace App\Http\Controllers\Sales;
use App\Http\Controllers\Controller;
use App\Models\Mylead;
use App\Models\MyleadHistory; // Add this import
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
class MyLeadsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    private function baseQuery()
{
    return Mylead::where('company_id', auth()->user()->company_id);
}

  public function index()
{
    $myleads = $this->baseQuery()
        ->with('client')
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

    $lead = Mylead::create([
        'user_id' => Auth::id(),
        'client_id' => $request->client_id,
        'response' => $request->response,
        'next_follow_up' => $request->next_follow_up,
        'follow_up_time' => $request->follow_up_time,
        'project_type' => $request->project_type,
        'status' => $request->status,
    ]);

    $this->logHistory($lead, null, $request->all());

    return back()->with('success', 'Lead response saved successfully!');
}

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
       $lead = $this->baseQuery()
    ->where('user_id', Auth::id())
    ->findOrFail($id);

$this->authorize('manage', $lead);

    return view('admin.sales.myleads-show', compact('lead'));
    }

    /**
     * Display the history for the specified resource.
     */
    public function history(string $id)
    {
       $lead = $this->baseQuery()
    ->where('user_id', Auth::id())
    ->findOrFail($id);

$this->authorize('manage', $lead);


        return view('admin.sales.myleadshistory', compact('lead'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $lead = $this->baseQuery()
    ->where('user_id', Auth::id())
    ->findOrFail($id);

$this->authorize('manage', $lead);

       
        return view('admin.sales.myleads-edit', compact('lead'));
    }
    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
       $lead = $this->baseQuery()
    ->where('user_id', Auth::id())
    ->findOrFail($id);

$this->authorize('manage', $lead);

        
        // Capture old values for history
        $oldData = $lead->getOriginal();

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

        // Log history after update
        $this->logHistory($lead, $oldData, $request->all());

        // Optional: Redirect with success message
        return redirect()->route('myleads')
            ->with('success', 'Lead updated successfully!');
    }

    /**
     * Helper method to log history.
     */
    private function logHistory($lead, $oldData, $newData)
    {
        $changes = [];
        $fields = ['response', 'next_follow_up', 'follow_up_time', 'project_type', 'status'];

        foreach ($fields as $field) {
            if (isset($oldData[$field]) && $oldData[$field] != ($newData[$field] ?? $lead->getAttribute($field))) {
                $changes[$field] = [
                    'old' => $oldData[$field] ?? 'N/A',
                    'new' => $newData[$field] ?? $lead->getAttribute($field)
                ];
            }
        }

        if (!empty($changes)) {
           MyleadHistory::create([
    'company_id' => auth()->user()->company_id,
    'mylead_id' => $lead->id,
    'user_id' => Auth::id(),
    'changes' => json_encode($changes),
    'response' => $newData['response'] ?? null,
]);

        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
  
}