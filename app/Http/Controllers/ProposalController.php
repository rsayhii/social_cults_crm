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
            'company_id' => Auth::user()->company_id,
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
        if ($proposal->company_id !== Auth::user()->company_id) {
            abort(403);
        }
        $clients = Client::where('company_id', Auth::user()->company_id)->get();
        return view('admin.proposals.edit', compact('proposal', 'clients'));
    }

    public function update(Request $request, Proposal $proposal)
    {
        if ($proposal->company_id !== Auth::user()->company_id) {
            abort(403);
        }

        $request->validate([
            'client_id' => [
                'required',
                \Illuminate\Validation\Rule::exists('clients', 'id')->where(function ($query) {
                    return $query->where('company_id', Auth::user()->company_id);
                }),
            ],
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
        if ($proposal->company_id !== Auth::user()->company_id) {
            abort(403);
        }
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
        $client = Client::where('company_id', $user->company_id)
            ->where('contact_person', $request->client_name)
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
            $proposal = Proposal::where('id', $request->proposal_id)
                ->where('company_id', $user->company_id)
                ->first();
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

    /**
     * Get hidden templates for the authenticated company
     */
    public function getHiddenTemplates()
    {
        $user = Auth::user();
        $company = \App\Models\Company::find($user->company_id);

        $hiddenTemplates = $company->hidden_proposal_templates ?? [];

        return response()->json(['hidden_templates' => $hiddenTemplates]);
    }

    /**
     * Hide a default template for the authenticated company
     */
    public function hideTemplate(Request $request)
    {
        $request->validate([
            'template_key' => 'required|string'
        ]);

        $user = Auth::user();
        $company = \App\Models\Company::find($user->company_id);

        $hiddenTemplates = $company->hidden_proposal_templates ?? [];

        // Add template key if not already hidden
        if (!in_array($request->template_key, $hiddenTemplates)) {
            $hiddenTemplates[] = $request->template_key;
            $company->hidden_proposal_templates = $hiddenTemplates;
            $company->save();
        }

        return response()->json(['success' => true, 'hidden_templates' => $hiddenTemplates]);
    }

    /**
     * Unhide a template for the authenticated company
     */
    public function unhideTemplate(Request $request)
    {
        $request->validate([
            'template_key' => 'required|string'
        ]);

        $user = Auth::user();
        $company = \App\Models\Company::find($user->company_id);

        $hiddenTemplates = $company->hidden_proposal_templates ?? [];

        // Remove template key from hidden list
        $hiddenTemplates = array_values(array_filter($hiddenTemplates, function ($key) use ($request) {
            return $key !== $request->template_key;
        }));

        $company->hidden_proposal_templates = $hiddenTemplates;
        $company->save();

        return response()->json(['success' => true, 'hidden_templates' => $hiddenTemplates]);
    }
}
