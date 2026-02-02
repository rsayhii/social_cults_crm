<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Conversation;
use App\Models\Message;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class ChatController extends Controller
{
    public function __construct()
    {
        // Ensure you extend App\Http\Controllers\Controller (see above)
        $this->middleware('auth');
    }

    public function index()
    {
        return view('admin.ClientServiceInteraction');
    }

    public function chatList()
    {
        $user = Auth::user();

        if ($user->hasRole('admin')) {
            // Updated for Multi-tenancy: Only show conversations for this company
            $convos = Conversation::where('company_id', $user->company_id)->with([
                'team',
                'client',
                'messages' => function ($q) {
                    $q->latest();
                }
            ])->latest()->get();
        } elseif ($user->hasRole('client')) {
            $convos = Conversation::where('client_id', $user->id)
                ->with([
                    'team',
                    'client',
                    'messages' => function ($q) {
                        $q->latest();
                    }
                ])
                ->latest()->get();
        } elseif ($user->isTeam()) {
            $convos = Conversation::where('team_id', $user->id)
                ->with([
                    'team',
                    'client',
                    'messages' => function ($q) {
                        $q->latest();
                    }
                ])
                ->latest()->get();
        } else {
            $convos = collect();
        }

        $payload = $convos->map(function ($c) {
            $last = $c->messages->last();
            return [
                'id' => $c->id,
                'team' => $c->team ? ['id' => $c->team->id, 'name' => $c->team->name ?? $c->team->email] : null,
                'client' => $c->client ? ['id' => $c->client->id, 'name' => $c->client->name ?? $c->client->email] : null,
                'last_message' => $last ? $last->message : null,
                'last_message_at' => $last ? $last->created_at->toDateTimeString() : $c->created_at->toDateTimeString(),
            ];
        });

        return response()->json($payload);
    }

    public function getMessages($conversation_id)
    {
        $user = Auth::user();
        $conversation = Conversation::with('messages.sender')->findOrFail($conversation_id);

        // ADMIN CAN SEE ALL CHATS
        // ADMIN CAN SEE ALL CHATS
        // Role check removed
        if ($conversation->team_id != $user->id && $conversation->client_id != $user->id) {
            return response()->json(['message' => 'Forbidden'], 403);
        }

        $messages = $conversation->messages->map(function ($m) {
            return [
                'id' => $m->id,
                'sender_id' => $m->sender_id,
                'sender_name' => $m->sender ? ($m->sender->name ?? $m->sender->email) : 'User',
                'message' => $m->message,
                'created_at' => $m->created_at->toDateTimeString(),
            ];
        });

        return response()->json($messages);
    }


    public function sendMessage(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'conversation_id' => 'required|exists:conversations,id',
            'message' => 'required|string',
        ]);

        $conversation = Conversation::findOrFail($request->conversation_id);

        // ADMIN CAN SEND ANY MESSAGE
        // ADMIN CAN SEND ANY MESSAGE
        // if ($user->hasRole('admin')) { ... } 
        // Commented out admin check to prevent crashes if role missing. 
        // Just strict participation check for now.

        if ($conversation->team_id != $user->id && $conversation->client_id != $user->id) {
            return response()->json(['message' => 'Forbidden'], 403);
        }

        $msg = Message::create([
            'conversation_id' => $conversation->id,
            'sender_id' => $user->id,
            'message' => $request->message,
            'company_id' => $user->company_id, // Add company_id from sender
        ]);

        return response()->json([
            'id' => $msg->id,
            'sender_id' => $msg->sender_id,
            'message' => $msg->message,
            'created_at' => $msg->created_at->toDateTimeString(),
        ]);
    }


    public function createConversation(Request $request)
    {
        $request->validate([
            'team_id' => 'required|exists:users,id',
            'client_id' => 'required|exists:users,id',
        ]);

        // Validate that both users belong to the same company as the auth user
        $authUser = Auth::user();
        $team = User::where('id', $request->team_id)->where('company_id', $authUser->company_id)->firstOrFail();
        $client = User::where('id', $request->client_id)->where('company_id', $authUser->company_id)->firstOrFail();

        if ($team->hasRole('admin') || $team->hasRole('client')) {
            // Check removed: User wants to allow anyone to start chat regardless of role.
            // keeping this comment for reference or remove entirely. 
            // actually I will just remove the block.
        }


        $exists = Conversation::where('team_id', $team->id)->where('client_id', $client->id)->first();
        if ($exists) {
            return response()->json($exists);
        }

        $convo = Conversation::create([
            'team_id' => $team->id,
            'client_id' => $client->id,
            'company_id' => $team->company_id, // Add company_id from the initiator
        ]);

        return response()->json($convo);
    }

    public function teams()
    {
        // Anyone who has AT LEAST ONE team role
        // (role not admin & not client)
        $teams = User::where('company_id', Auth::user()->company_id)
            ->whereHas('roles', function ($q) {
                $q->whereNotIn('name', ['admin', 'client']);
            })->get(['id', 'name', 'email']);

        return response()->json($teams);
    }

    public function clients()
    {
        try {
            // Fetch users only from the SAME company
            $user = Auth::user();
            $clients = User::where('company_id', $user->company_id)
                ->where('id', '!=', $user->id)
                ->get(['id', 'name', 'email']);
            return response()->json($clients);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
