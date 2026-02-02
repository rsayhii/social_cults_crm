<?php

namespace App\Http\Controllers;

use App\Models\Message;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class MessageController extends Controller
{
    /**
     * Get messages for a specific chat
     */
    public function getMessages(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'team' => 'required|string',
            'client_name' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid parameters',
                'errors' => $validator->errors()
            ], 422);
        }

        $messages = Message::where('team', $request->team)
            ->where('client_name', $request->client_name)
            ->orderBy('created_at', 'asc')
            ->get()
            ->map(function ($message) {
                return [
                    'id' => $message->id,
                    'sender' => $message->sender_role === 'client' ? $message->client_name : $message->sender_team,
                    'message' => $message->message,
                    'timestamp' => $message->created_at->format('h:i A'),
                    'type' => $message->sender_role === 'client' ? 'received' : 'sent'
                ];
            });

        return response()->json([
            'success' => true,
            'messages' => $messages
        ]);
    }

    /**
     * Send a new message
     */
    public function sendMessage(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'team' => 'required|string',
            'client_name' => 'required|string',
            'sender_role' => 'required|string|in:admin,team,client',
            'sender_team' => 'nullable|string',
            'message' => 'required|string|max:1000',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid message data',
                'errors' => $validator->errors()
            ], 422);
        }

        $message = Message::create([
            'team' => $request->team,
            'client_name' => $request->client_name,
            'sender_role' => $request->sender_role,
            'sender_team' => $request->sender_team,
            'message' => $request->message,
            'read_status' => false,
        ]);

        return response()->json([
            'success' => true,
            'message' => [
                'id' => $message->id,
                'sender' => $request->sender_role === 'client' ? $request->client_name : $request->sender_team,
                'message' => $message->message,
                'timestamp' => $message->created_at->format('h:i A'),
                'type' => $request->sender_role === 'client' ? 'received' : 'sent'
            ]
        ]);
    }

    /**
     * Get chat list based on user role
     */
    public function getChatList(Request $request)
    {
        $role = $request->input('role');
        $team = $request->input('team');
        $teamFilter = $request->input('team_filter', 'all');

        if ($role === 'admin') {
            $chats = Message::withTeamFilter($teamFilter)
                ->select('team', 'client_name')
                ->selectRaw('MAX(created_at) as last_message_time')
                ->selectRaw('COUNT(*) as message_count')
                ->groupBy('team', 'client_name')
                ->orderBy('last_message_time', 'desc')
                ->get()
                ->map(function ($chat) {
                    $lastMessage = Message::where('team', $chat->team)
                        ->where('client_name', $chat->client_name)
                        ->orderBy('created_at', 'desc')
                        ->first();

                    return [
                        'team' => $chat->team,
                        'client_name' => $chat->client_name,
                        'last_message' => $lastMessage ? $lastMessage->message : 'No messages yet',
                        'last_message_time' => $lastMessage ? $lastMessage->created_at->format('h:i A') : '',
                        'message_count' => $chat->message_count
                    ];
                });
        } elseif ($role === 'team') {
            $chats = Message::forTeam($team)
                ->select('team', 'client_name')
                ->selectRaw('MAX(created_at) as last_message_time')
                ->selectRaw('COUNT(*) as message_count')
                ->groupBy('team', 'client_name')
                ->orderBy('last_message_time', 'desc')
                ->get()
                ->map(function ($chat) use ($team) {
                    $lastMessage = Message::where('team', $team)
                        ->where('client_name', $chat->client_name)
                        ->orderBy('created_at', 'desc')
                        ->first();

                    return [
                        'team' => $chat->team,
                        'client_name' => $chat->client_name,
                        'last_message' => $lastMessage ? $lastMessage->message : 'No messages yet',
                        'last_message_time' => $lastMessage ? $lastMessage->created_at->format('h:i A') : '',
                        'message_count' => $chat->message_count
                    ];
                });
        } else { // client
            $chats = Message::forClient($team) // using team field for client name in this case
                ->select('team', 'client_name')
                ->selectRaw('MAX(created_at) as last_message_time')
                ->selectRaw('COUNT(*) as message_count')
                ->groupBy('team', 'client_name')
                ->orderBy('last_message_time', 'desc')
                ->get()
                ->map(function ($chat) {
                    $lastMessage = Message::where('team', $chat->team)
                        ->where('client_name', $chat->client_name)
                        ->orderBy('created_at', 'desc')
                        ->first();

                    return [
                        'team' => $chat->team,
                        'client_name' => $chat->client_name,
                        'last_message' => $lastMessage ? $lastMessage->message : 'No messages yet',
                        'last_message_time' => $lastMessage ? $lastMessage->created_at->format('h:i A') : '',
                        'message_count' => $chat->message_count
                    ];
                });
        }

        return response()->json([
            'success' => true,
            'chats' => $chats
        ]);
    }
}