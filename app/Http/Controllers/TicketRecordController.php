<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\HelpAndSupport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use Illuminate\Support\Facades\Storage;

class TicketRecordController extends Controller
{
    public function index(Request $request)
    {
        $query = HelpAndSupport::with(['client', 'assignedAgent']);

        // Stats
        $stats = [
            'total' => HelpAndSupport::count(),
            'open' => HelpAndSupport::where('status', 'open')->count(),
            'pending' => HelpAndSupport::where('status', 'in-progress')->count(),
            'resolved' => HelpAndSupport::whereIn('status', ['completed', 'closed'])->count(),
        ];

        // Filters
        if ($request->has('status') && $request->status !== 'all') {
            $query->where('status', $request->status);
        }
        if ($request->has('priority') && $request->priority !== 'all') {
            $query->where('priority', $request->priority);
        }

        $tickets = $query->orderBy('created_at', 'desc')->paginate(15);

        return view('admin.support.support_desk', compact('tickets', 'stats'));
    }

    public function show($id)
    {
        $ticket = HelpAndSupport::with(['client', 'assignedAgent'])->findOrFail($id);
        return view('admin.support.manage_ticket', compact('ticket'));
    }

    public function update(Request $request, $id)
    {
        $ticket = HelpAndSupport::findOrFail($id);

        $request->validate([
            'status' => 'required|in:open,in-progress,completed,closed',
            'priority' => 'required|in:low,medium,high,urgent',
            'assigned_team' => 'nullable|string'
        ]);

        $oldStatus = $ticket->status;
        $ticket->update([
            'status' => $request->status,
            'priority' => $request->priority,
            'assigned_team' => $request->assigned_team
        ]);

        // Add system note
        if ($oldStatus !== $request->status) {
            $ticket->addConversation(
                "Status changed from {$oldStatus} to {$request->status} by " . Auth::user()->name,
                Auth::id(),
                true // Internal note
            );
        }

        return back()->with('success', 'Ticket updated successfully');
    }

    public function reply(Request $request, $id)
    {
        $ticket = HelpAndSupport::findOrFail($id);

        $request->validate([
            'message' => 'required|string',
            'is_internal' => 'boolean',
            'attachments.*' => 'file|max:10240',
        ]);

        // Handle attachments (Same logic as User controller)
        $attachmentPaths = [];
        if ($request->hasFile('attachments')) {
            foreach ($request->file('attachments') as $file) {
                $path = $file->store('help-support/' . $ticket->ticket_id, 'public');
                $attachmentPaths[] = ['name' => $file->getClientOriginalName(), 'path' => $path, 'size' => $file->getSize()];
                $ticket->addAttachment($file->getClientOriginalName(), $path, $file->getMimeType(), $file->getSize());
            }
        }

        $ticket->addConversation(
            $request->message,
            Auth::id(),
            $request->boolean('is_internal'),
            $attachmentPaths
        );

        return back()->with('success', 'Reply sent successfully');
    }

    public function destroy($id)
    {
        $ticket = HelpAndSupport::findOrFail($id);

        try {
            // Delete attachments from storage

            if ($ticket->attachments && count($ticket->attachments) > 0) {
                foreach ($ticket->attachments as $file) {
                    if (Storage::disk('public')->exists($file['file_path'])) {
                        Storage::disk('public')->delete($file['file_path']);
                    }
                }
            }

            // Just in case there are loose files in the ticket folder (if folder based)
            Storage::disk('public')->deleteDirectory('help-support/' . $ticket->ticket_id);

            $ticket->delete();

            return redirect()->route('ticket.record.index')->with('success', 'Ticket deleted successfully');

        } catch (\Exception $e) {
            return back()->with('error', 'Error deleting ticket: ' . $e->getMessage());
        }
    }
}
