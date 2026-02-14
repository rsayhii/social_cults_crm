<?php

namespace App\Http\Controllers;

use App\Models\Link;
use Illuminate\Http\Request;

class LinkAndRemarkController extends Controller
{
    /** Load page */
    public function index()
    {
        return view('admin.linkandremark');
    }

    /** Fetch all links (company scoped) */
    public function fetchAll()
    {
        return response()->json(
            Link::where('company_id', auth()->user()->company_id)->get()
        );
    }

    /** Store new link */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'type' => 'required',
            'category' => 'required|in:marketing,support,internal',
            'title' => 'required|string|max:255',
            'url' => 'required|url',
            'note' => 'nullable|string',
            'status' => 'required|in:active,inactive'
        ]);

        $validated['clicks'] = rand(10, 1000);
        $validated['engagement'] = rand(1, 200) / 10;

        $link = Link::create($validated);

        return response()->json(['message' => 'Link added successfully']);
    }

    /** Update link */
    public function update(Request $request, $id)
    {
        $link = Link::where('company_id', auth()->user()->company_id)
            ->findOrFail($id);

        $this->authorize('manage', $link);

        $validated = $request->validate([
            'type' => 'sometimes|required',
            'category' => 'sometimes|required|in:marketing,support,internal',
            'title' => 'sometimes|required|string|max:255',
            'url' => 'sometimes|required|url',
            'note' => 'sometimes|nullable|string',
            'status' => 'sometimes|required|in:active,inactive',
            'clicks' => 'sometimes|integer|min:0',
            'engagement' => 'sometimes|numeric|min:0'
        ]);

        $link->update($validated);

        return response()->json(['message' => 'Link updated successfully']);
    }

    /** Delete link */
    public function destroy($id)
    {
        $link = Link::where('company_id', auth()->user()->company_id)
            ->findOrFail($id);

        $this->authorize('manage', $link);

        $link->delete();

        return response()->json(['message' => 'Link deleted successfully']);
    }

    /** Show single link */
    public function show($id)
    {
        $link = Link::where('company_id', auth()->user()->company_id)
            ->findOrFail($id);

        $this->authorize('manage', $link);

        return response()->json($link);
    }
}
