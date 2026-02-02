<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\HelpAndSupport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class TicketController extends Controller
{
    public function index()
    {
        $tickets = HelpAndSupport::where('client_id', Auth::id())
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('admin.user.support.my_tickets', compact('tickets'));
    }

    public function create()
    {
        return view('user.support.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'category' => 'required|in:website,social-media,ads-manager,email,billing,others',
            'priority' => 'required|in:low,medium,high,urgent',
            'attachments.*' => 'file|max:10240',
        ]);

        try {
            // Create ticket
            $ticket = HelpAndSupport::create([
                'title' => $request->title,
                'description' => $request->description,
                'category' => $request->category,
                'priority' => $request->priority,
                'client_id' => Auth::id(),
                'company_id' => Auth::user()->company_id,
                'status' => 'open',
                'sla_due_at' => $this->calculateSLADueDate($request->priority),
            ]);

            // Handle attachments
            $attachmentData = [];
            if ($request->hasFile('attachments')) {
                foreach ($request->file('attachments') as $file) {
                    $path = $file->store('help-support/' . $ticket->ticket_id, 'public');

                    // Add to master attachment list
                    $ticket->addAttachment(
                        $file->getClientOriginalName(),
                        $path,
                        $file->getMimeType(),
                        $file->getSize()
                    );

                    // Prepare for conversation
                    $attachmentData[] = [
                        'name' => $file->getClientOriginalName(),
                        'path' => $path,
                        'size' => $file->getSize(),
                    ];
                }
            }

            // Add initial conversation with attachments
            $ticket->addConversation($request->description, Auth::id(), false, $attachmentData);

            return redirect()->route('user.support.ticket.index')->with('success', 'Ticket created successfully');

        } catch (\Exception $e) {
            return back()->with('error', 'Failed to create ticket: ' . $e->getMessage());
        }
    }

    public function show($id)
    {
        $ticket = HelpAndSupport::where('client_id', Auth::id())->findOrFail($id);
        return view('admin.user.support.view_ticket', compact('ticket'));
    }

    public function reply(Request $request, $id)
    {
        $ticket = HelpAndSupport::where('client_id', Auth::id())->findOrFail($id);

        $request->validate([
            'message' => 'required|string',
            'attachments.*' => 'file|max:10240',
        ]);

        // Handle attachments
        $attachmentPaths = [];
        if ($request->hasFile('attachments')) {
            foreach ($request->file('attachments') as $file) {
                $path = $file->store('help-support/' . $ticket->ticket_id, 'public');
                $attachmentPaths[] = [
                    'name' => $file->getClientOriginalName(),
                    'path' => $path,
                    'size' => $file->getSize(),
                ];

                // Also add to main ticket attachments
                $ticket->addAttachment(
                    $file->getClientOriginalName(),
                    $path,
                    $file->getMimeType(),
                    $file->getSize()
                );
            }
        }

        $ticket->addConversation(
            $request->message,
            Auth::id(),
            false, // Not internal
            $attachmentPaths
        );

        return back()->with('success', 'Reply sent successfully');
    }

    private function calculateSLADueDate($priority)
    {
        $defaultTimes = [
            'urgent' => 4,   // 4 hours
            'high' => 8,     // 8 hours
            'medium' => 24,  // 24 hours
            'low' => 72,     // 72 hours
        ];

        return now()->addHours($defaultTimes[$priority] ?? 24);
    }
}
