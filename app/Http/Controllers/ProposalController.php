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
        $proposals = Proposal::with('client', 'user')->latest()->get();
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
}
