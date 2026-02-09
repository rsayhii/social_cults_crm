<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Proposal;
use App\Models\Client;
use Illuminate\Support\Facades\Auth;

class ProposalController extends Controller
{
    public function index()
    {
        $proposals = Proposal::with('client', 'user')
            ->where('company_id', Auth::user()->company_id)
            ->latest()
            ->get();
        return view('admin.sales.proposal', compact('proposals'));
    }

    public function create()
    {
        $clients = Client::all();
        return view('admin.sales.addproposal', compact('clients'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'client_id' => 'required|exists:clients,id',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'amount' => 'nullable|numeric',
            'file' => 'nullable|file|mimes:pdf,doc,docx|max:2048',
        ]);

        $filePath = null;
        if ($request->hasFile('file')) {
            $filePath = $request->file('file')->store('proposals', 'public');
        }

        Proposal::create([
            'user_id' => Auth::id(),
            'client_id' => $request->client_id,
            'title' => $request->title,
            'description' => $request->description,
            'amount' => $request->amount,
            'status' => 'draft',
            'file_path' => $filePath,
        ]);

        return redirect()->route('proposals.index')->with('success', 'Proposal created successfully!');
    }

    public function edit(Proposal $proposal)
    {
        $clients = Client::all();
        return view('admin.proposals.edit', compact('proposal', 'clients'));
    }

    public function update(Request $request, Proposal $proposal)
    {
        $request->validate([
            'client_id' => 'required|exists:clients,id',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'amount' => 'nullable|numeric',
            'status' => 'required|string',
            'file' => 'nullable|file|mimes:pdf,doc,docx|max:2048',
        ]);

        $filePath = $proposal->file_path;
        if ($request->hasFile('file')) {
            $filePath = $request->file('file')->store('proposals', 'public');
        }

        $proposal->update([
            'client_id' => $request->client_id,
            'title' => $request->title,
            'description' => $request->description,
            'amount' => $request->amount,
            'status' => $request->status,
            'file_path' => $filePath,
        ]);

        return redirect()->route('proposals.index')->with('success', 'Proposal updated successfully!');
    }

    public function destroy(Proposal $proposal)
    {
        $proposal->delete();
        return back()->with('success', 'Proposal deleted successfully!');
    }

    public function backgroundSave(Request $request)
    {
        $request->validate([
            'content' => 'required',
            'client_name' => 'required|string',
            'client_company' => 'required|string',
            'template_key' => 'nullable|string',
            'settings' => 'nullable|array'
        ]);

        $user = Auth::user();

        // Find or create client
        $client = Client::where('contact_person', $request->client_name)
            ->where('company_name', $request->client_company)
            ->first();

        if (!$client) {
            $client = Client::create([
                'company_id' => $user->company_id, // Assuming user has company_id
                'company_name' => $request->client_company,
                'contact_person' => $request->client_name,
                'email' => 'unknown@example.com', // Placeholder
                'phone' => '0000000000', // Placeholder
                'status' => 'lead',
                'priority' => 'medium', // Lowercase to be safe
                'source' => 'other'
            ]);
        }

        if ($request->proposal_id) {
            $proposal = Proposal::find($request->proposal_id);
            if ($proposal) {
                $proposal->update([
                    'content' => $request->content,
                    'settings' => $request->settings,
                    'client_id' => $client->id,
                    // 'title' => $request->title // Optional update title
                ]);
                return response()->json(['success' => true, 'proposal_id' => $proposal->id]);
            }
        }

        $proposal = Proposal::create([
            'user_id' => $user->id,
            'company_id' => $user->company_id,
            'client_id' => $client->id,
            'title' => $request->title ?? ($request->template_name ?? 'Untitled Proposal'),
            'description' => 'Created via Proposal Builder',
            'content' => $request->content,
            'settings' => $request->settings,
            'status' => 'draft',
            'amount' => 0
        ]);

        return response()->json(['success' => true, 'proposal_id' => $proposal->id]);
    }
}
