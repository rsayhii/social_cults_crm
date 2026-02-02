<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\HelpAndSupport;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class HelpAndSupportController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        
        // Get dashboard statistics
        $stats = [
            'total_tickets' => HelpAndSupport::count(),
            'open_tickets' => HelpAndSupport::where('status', 'open')->count(),
            'in_progress_tickets' => HelpAndSupport::where('status', 'in-progress')->count(),
            'resolved_tickets' => HelpAndSupport::whereIn('status', ['completed', 'closed'])->count(),
            'high_priority_tickets' => HelpAndSupport::whereIn('priority', ['high', 'urgent'])->count(),
            'my_assigned_tickets' => HelpAndSupport::where('assigned_agent_id', $user->id)->count(),
            'sla_overdue_tickets' => HelpAndSupport::where('sla_due_at', '<', now())->whereIn('status', ['open', 'in-progress'])->count(),
        ];

        // Recent tickets for dashboard
        $recentTickets = HelpAndSupport::with(['client', 'assignedAgent'])
            ->orderBy('created_at', 'desc')
            ->take(10)
            ->get();

        // SLA alerts
        $slaAlerts = HelpAndSupport::with('client')
            ->where('sla_due_at', '<', now()->addHours(24))
            ->whereIn('status', ['open', 'in-progress'])
            ->orderBy('sla_due_at', 'asc')
            ->take(5)
            ->get();

        // Team performance (simplified)
        $teamPerformance = [
            (object)['name' => 'SEO Team', 'ticket_count' => HelpAndSupport::where('assigned_team', 'SEO Team')->count()],
            (object)['name' => 'SMM Team', 'ticket_count' => HelpAndSupport::where('assigned_team', 'SMM Team')->count()],
            (object)['name' => 'Ads Team', 'ticket_count' => HelpAndSupport::where('assigned_team', 'Ads Team')->count()],
            (object)['name' => 'Web Team', 'ticket_count' => HelpAndSupport::where('assigned_team', 'Web Team')->count()],
        ];

        return view('admin.helpandsupport', compact('stats', 'recentTickets', 'slaAlerts', 'teamPerformance'));
    }

    public function getTickets(Request $request)
    {
        $query = HelpAndSupport::with(['client', 'assignedAgent']);

        // Apply filters
        if ($request->has('status') && $request->status !== 'all') {
            $query->where('status', $request->status);
        }

        if ($request->has('priority') && $request->priority !== 'all') {
            $query->where('priority', $request->priority);
        }

        if ($request->has('category') && $request->category !== 'all') {
            $query->where('category', $request->category);
        }

        if ($request->has('assigned_to') && $request->assigned_to !== 'all') {
            if ($request->assigned_to === 'me') {
                $query->where('assigned_agent_id', Auth::id());
            }
        }

        if ($request->has('search') && !empty($request->search)) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('ticket_id', 'like', "%{$search}%")
                  ->orWhereHas('client', function($q) use ($search) {
                      $q->where('name', 'like', "%{$search}%");
                  });
            });
        }

        $tickets = $query->orderBy('created_at', 'desc')->paginate(10);

        return response()->json($tickets);
    }

    public function storeTicket(Request $request)
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
                'sla_due_at' => $this->calculateSLADueDate($request->priority),
            ]);

            // Add initial conversation
            $ticket->addConversation($request->description, Auth::id(), false);

            // Handle attachments
            if ($request->hasFile('attachments')) {
                foreach ($request->file('attachments') as $file) {
                    $path = $file->store('help-support/' . $ticket->id, 'public');
                    $ticket->addAttachment(
                        $file->getClientOriginalName(),
                        $path,
                        $file->getMimeType(),
                        $file->getSize()
                    );
                }
            }

            return response()->json([
                'success' => true,
                'message' => 'Ticket created successfully',
                'ticket' => $ticket
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to create ticket: ' . $e->getMessage()
            ], 500);
        }
    }

    public function getTicket($id)
    {
        $ticket = HelpAndSupport::with(['client', 'assignedAgent'])->findOrFail($id);
        return response()->json($ticket);
    }

    public function updateTicket(Request $request, $id)
    {
        $ticket = HelpAndSupport::findOrFail($id);

        $request->validate([
            'assigned_team' => 'nullable|string',
            'priority' => 'required|in:low,medium,high,urgent',
            'status' => 'required|in:open,in-progress,completed,closed',
        ]);

        $oldStatus = $ticket->status;
        $oldPriority = $ticket->priority;

        $updateData = [
            'assigned_team' => $request->assigned_team,
            'priority' => $request->priority,
            'status' => $request->status,
        ];

        // If status is changed to completed, set resolved_at
        if ($request->status === 'completed' && $oldStatus !== 'completed') {
            $updateData['resolved_at'] = now();
        }

        $ticket->update($updateData);

        // Add conversation for changes
        $message = "";
        if ($oldStatus !== $request->status) {
            $message .= "Status changed from " . str_replace('-', ' ', $oldStatus) . " to " . str_replace('-', ' ', $request->status) . ". ";
        }
        if ($oldPriority !== $request->priority) {
            $message .= "Priority changed from {$oldPriority} to {$request->priority}. ";
        }
        if ($request->assigned_team) {
            $message .= "Assigned to {$request->assigned_team} team.";
        }

        if (!empty($message)) {
            $ticket->addConversation($message, Auth::id(), true);
        }

        return response()->json([
            'success' => true,
            'message' => 'Ticket updated successfully',
            'ticket' => $ticket
        ]);
    }

    public function addConversation(Request $request, $ticketId)
    {
        $request->validate([
            'message' => 'required|string',
            'is_internal' => 'boolean',
            'attachments.*' => 'file|max:10240',
        ]);

        $ticket = HelpAndSupport::findOrFail($ticketId);

        try {
            // Handle attachments
            $attachmentPaths = [];
            if ($request->hasFile('attachments')) {
                foreach ($request->file('attachments') as $file) {
                    $path = $file->store('help-support/' . $ticketId, 'public');
                    $attachmentPaths[] = [
                        'name' => $file->getClientOriginalName(),
                        'path' => $path,
                        'size' => $file->getSize(),
                    ];

                    // Add to ticket attachments
                    $ticket->addAttachment(
                        $file->getClientOriginalName(),
                        $path,
                        $file->getMimeType(),
                        $file->getSize()
                    );
                }
            }

            // Add conversation
            $ticket->addConversation(
                $request->message,
                Auth::id(),
                $request->is_internal ?? false,
                $attachmentPaths
            );

            return response()->json([
                'success' => true,
                'message' => 'Message sent successfully'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to send message: ' . $e->getMessage()
            ], 500);
        }
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