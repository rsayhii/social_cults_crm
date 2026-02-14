<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Conversation;
use App\Models\Message;
use App\Models\User;
use App\Models\Role;
use Illuminate\Support\Facades\Auth;

class ChatController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        return view('admin.ClientServiceInteraction');
    }

    /**
     * Get chat list - returns both direct chats (same role users) and group chats
     */
    public function chatList()
    {
        $user = Auth::user();
        $userRole = $user->getRoleNames()->first();

        // Get direct chats (type = 'direct' or null for legacy)
        $directChats = $this->getDirectChats($user, $userRole);

        // Get group chats
        $groupChats = $this->getGroupChats($user, $userRole);

        // Combine and return
        $payload = collect($directChats)->merge($groupChats)->sortByDesc('last_message_at')->values();

        return response()->json($payload);
    }

    /**
     * Get direct chats for user - filtered by same role
     */
    private function getDirectChats($user, $userRole)
    {
        $query = Conversation::where('company_id', $user->company_id)
            ->where(function ($q) {
                $q->where('type', 'direct')->orWhereNull('type');
            })
            ->with(['team', 'client', 'messages' => fn($q) => $q->latest()]);

        // Eager load current user's participation info for unread counts
        $query->with(['participants' => fn($q) => $q->where('user_id', $user->id)]);

        if (!$user->hasRole('admin')) {
            // Regular users see only their direct chats
            $query->where(function ($q) use ($user) {
                $q->where('team_id', $user->id)->orWhere('client_id', $user->id);
            });
        }

        $convos = $query->latest()->get();

        return $convos->map(function ($c) use ($user) {
            $last = $c->messages->first();
            $otherUser = ($c->team_id == $user->id) ? $c->client : $c->team;

            // Calculate unread count
            $participant = $c->participants->first();
            $lastReadAt = $participant ? $participant->pivot->last_read_at : null;

            // If never read, all messages are unread. If read, count messages after last read.
            // We optimize by counting the loaded messages collection since it's already fetched
            $unreadCount = 0;
            if ($lastReadAt) {
                $unreadCount = $c->messages->where('created_at', '>', $lastReadAt)->count();
            } else if ($c->messages->count() > 0) {
                // Determine if the user has ever opened this chat?
                // If no participant entry, they likely haven't filtered/seen it in this new system.
                // Assuming all unread if no record exists might be noisy for old chats.
                // But generally correct. However, let's treat "no participant record" as "read up to now" ?? 
                // No, better to mark them as unread to be safe, or maybe 0 for legacy safety?
                // Let's go with 0 for legacy safety for direct chats without pivot, 
                // BUT if they sent a message they should have read it.
                // For now, let's assume 0 if no record to avoid notification blast on update,
                // effectively "marking all as read" implicitly for legacy state.
                $unreadCount = 0;

                // Correction: If I send a message, I've seen it.
                // If I have entries in pivot, use them. If NOT, default to 0.
            }

            return [
                'id' => $c->id,
                'type' => 'direct',
                'name' => $otherUser ? ($otherUser->name ?? $otherUser->email) : 'Unknown User',
                'team' => $c->team ? ['id' => $c->team->id, 'name' => $c->team->name ?? $c->team->email] : null,
                'client' => $c->client ? ['id' => $c->client->id, 'name' => $c->client->name ?? $c->client->email] : null,
                'last_message' => $last ? $last->message : null,
                'last_message_at' => $last ? $last->created_at->toDateTimeString() : $c->created_at->toDateTimeString(),
                'unread_count' => $unreadCount,
            ];
        })->toArray();
    }

    /**
     * Get group chats for user
     */
    private function getGroupChats($user, $userRole)
    {
        if ($user->hasRole('admin')) {
            // Admin sees all group chats in the company
            $groupChats = Conversation::where('company_id', $user->company_id)
                ->where('type', 'group')
                ->with(['role', 'messages' => fn($q) => $q->latest(), 'participants'])
                ->latest()
                ->get();
        } else {
            // Regular users see group chats where they are participants
            $groupChats = Conversation::where('company_id', $user->company_id)
                ->where('type', 'group')
                ->whereHas('participants', fn($q) => $q->where('user_id', $user->id))
                ->with(['role', 'messages' => fn($q) => $q->latest(), 'participants'])
                ->latest()
                ->get();
        }

        return $groupChats->map(function ($c) use ($user) {
            $last = $c->messages->first();
            $participantCount = $c->participants->count();

            // Calculate unread count
            // Participants are already loaded, find current user
            $participant = $c->participants->where('id', $user->id)->first();
            $lastReadAt = $participant ? $participant->pivot->last_read_at : null;

            $unreadCount = 0;
            if ($lastReadAt) {
                $unreadCount = $c->messages->where('created_at', '>', $lastReadAt)->count();
            } else {
                // For groups, user MUST be a participant to see it (except admin).
                // If admin views but not participant, unread=0
                if ($c->messages->count() > 0 && $participant) {
                    // If participant but no date, assume all unread? 
                    // Or assume 0 to avoid noise? Let's use logic: 
                    // If I just joined, everything before join might be unread or history?
                    // Let's stick to 0 for no-timestamp to be safe against legacy data noise.
                    $unreadCount = 0;
                }
            }

            return [
                'id' => $c->id,
                'type' => 'group',
                'name' => $c->name ?? ($c->role ? $c->role->name . ' Group' : 'Group Chat'),
                'role_id' => $c->role_id,
                'role_name' => $c->role ? $c->role->name : null,
                'participant_count' => $participantCount,
                'last_message' => $last ? $last->message : null,
                'last_message_at' => $last ? $last->created_at->toDateTimeString() : $c->created_at->toDateTimeString(),
                'unread_count' => $unreadCount,
            ];
        })->toArray();
    }

    /**
     * Get messages for a conversation (both direct and group)
     */
    public function getMessages($conversation_id)
    {
        $user = Auth::user();
        $conversation = Conversation::with('messages.sender', 'participants')->findOrFail($conversation_id);

        // Check access
        if (!$this->canAccessConversation($user, $conversation)) {
            return response()->json(['message' => 'Forbidden'], 403);
        }

        // Update Read Status
        // Check if user is already a participant (pivot exists)
        if ($conversation->participants()->where('user_id', $user->id)->exists()) {
            $conversation->participants()->updateExistingPivot($user->id, ['last_read_at' => now()]);
        } else {
            // For direct chats or admins viewing groups they aren't fully joined to yet
            // Add them to table with joined_at = now (or keep null?)
            // Better to add them so we can track read status
            $conversation->participants()->attach($user->id, [
                'joined_at' => now(),
                'last_read_at' => now()
            ]);
        }

        // Get user's role IDs for visibility filtering
        $userRoleIds = $user->roles->pluck('id')->toArray();
        $isUserAdmin = $user->hasRole('admin');
        $isUserClient = $user->roles->contains(fn($role) => strtolower($role->name) === 'client');

        // Get subgroup context from query param (for admin/client viewing specific team channel)
        $subgroupContext = request()->query('target_role_id');
        // Cast to int for proper comparison (null stays null)
        $subgroupContextInt = $subgroupContext ? (int) $subgroupContext : null;

        // Filter messages based on team visibility
        $filteredMessages = $conversation->messages->filter(function ($m) use ($user, $userRoleIds, $isUserAdmin, $isUserClient, $subgroupContextInt) {
            // Cast message's target_role_id to int for comparison (null stays null)
            $messageTargetRoleId = $m->target_role_id ? (int) $m->target_role_id : null;

            // Check if message sender is a client or admin
            $sender = $m->sender;
            $senderIsClient = $sender ? $sender->roles->contains(fn($role) => strtolower($role->name) === 'client') : false;
            $senderIsAdmin = $sender ? $sender->roles->contains(fn($role) => strtolower($role->name) === 'admin') : false;

            // Sender always sees their own messages, BUT filtered by subgroup context
            if ($m->sender_id == $user->id) {
                // If viewing a specific subgroup/role channel, only show messages for that role
                if ($subgroupContextInt !== null) {
                    return $messageTargetRoleId === $subgroupContextInt;
                }
                // If viewing "All Teams", only show messages sent to "All Teams" (target_role_id IS NULL)
                return $messageTargetRoleId === null;
            }

            // If admin/client is viewing a specific subgroup, only show messages for that team
            if (($isUserAdmin || $isUserClient) && $subgroupContextInt !== null) {
                return $messageTargetRoleId === $subgroupContextInt;
            }

            // Admins and clients viewing "All Teams" ONLY see messages sent to "All Teams" (target_role_id IS NULL)
            // This achieves complete message isolation between "All Teams" and role-specific channels
            if ($isUserAdmin || $isUserClient) {
                return $messageTargetRoleId === null;
            }


            // === EMPLOYEE (TEAM MEMBER) LOGIC ===
            // Employees now respect subgroup context filtering for complete message isolation

            if ($subgroupContextInt !== null) {
                // Employee viewing specific role channel (e.g., "My Team (Developer)")
                // Only show messages targeted to that specific role
                return $messageTargetRoleId === $subgroupContextInt;
            }

            // Employee viewing "All Teams" - only show messages sent to "All Teams" (NULL target_role_id)
            return $messageTargetRoleId === null;
        });

        $messages = $filteredMessages->map(function ($m) {
            $sender = $m->sender;
            // Check for client role case-insensitively
            $isClient = $sender ? $sender->roles->contains(fn($role) => strtolower($role->name) === 'client') : false;
            $isAdmin = $sender ? $sender->roles->contains(fn($role) => strtolower($role->name) === 'admin') : false;

            // Get target role info for team-targeted messages
            $targetRole = $m->targetRole;

            return [
                'id' => $m->id,
                'sender_id' => $m->sender_id,
                'sender_name' => $sender ? ($sender->name ?? $sender->email) : 'User',
                'is_client' => $isClient,
                'is_admin' => $isAdmin,
                'message' => $m->message,
                'target_role_id' => $m->target_role_id,
                'target_role_name' => $targetRole ? $targetRole->name : null,
                'created_at' => $m->created_at->toDateTimeString(),
            ];
        })->values();

        return response()->json($messages);
    }

    /**
     * Send message to a conversation (both direct and group)
     */
    public function sendMessage(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'conversation_id' => 'required|exists:conversations,id',
            'message' => 'required|string',
            'target_role_id' => 'nullable|exists:roles,id',
        ]);

        $conversation = Conversation::with('participants')->findOrFail($request->conversation_id);

        // Check access
        if (!$this->canAccessConversation($user, $conversation)) {
            return response()->json(['message' => 'Forbidden'], 403);
        }

        // Determine target_role_id from frontend request
        // Frontend now provides target_role_id for ALL users:
        // - "All Teams" selected → target_role_id = NULL (not sent in request)
        // - Specific role selected → target_role_id = role ID
        $targetRoleId = $request->target_role_id;

        // Auto-tagging removed - frontend controls target_role_id for employees

        $msg = Message::create([
            'conversation_id' => $conversation->id,
            'sender_id' => $user->id,
            'message' => $request->message,
            'company_id' => $user->company_id,
            'target_role_id' => $targetRoleId,
        ]);

        // Get target role name if set (use the actual targetRoleId, not just request value)
        $targetRole = $targetRoleId ? Role::find($targetRoleId) : null;

        return response()->json([
            'id' => $msg->id,
            'sender_id' => $msg->sender_id,
            'sender_name' => $user->name ?? $user->email,
            'is_client' => $user->roles->contains(fn($role) => strtolower($role->name) === 'client'),
            'is_admin' => $user->roles->contains(fn($role) => strtolower($role->name) === 'admin'),
            'message' => $msg->message,
            'target_role_id' => $msg->target_role_id,
            'target_role_name' => $targetRole ? $targetRole->name : null,
            'created_at' => $msg->created_at->toDateTimeString(),
        ]);
    }

    /**
     * Check if user can access a conversation
     */
    private function canAccessConversation($user, $conversation)
    {
        // Admin can access any conversation in their company
        if ($user->hasRole('admin') && $conversation->company_id == $user->company_id) {
            return true;
        }

        // For group chats, check if user is a participant
        if ($conversation->type === 'group') {
            return $conversation->participants->contains('id', $user->id);
        }

        // For direct chats, check if user is team or client
        return $conversation->team_id == $user->id || $conversation->client_id == $user->id;
    }

    /**
     * Create a direct conversation between two users
     */
    public function createConversation(Request $request)
    {
        $request->validate([
            'team_id' => 'required|exists:users,id',
            'client_id' => 'required|exists:users,id',
        ]);

        $authUser = Auth::user();
        $team = User::where('id', $request->team_id)->where('company_id', $authUser->company_id)->firstOrFail();
        $client = User::where('id', $request->client_id)->where('company_id', $authUser->company_id)->firstOrFail();

        // Check if conversation already exists (in either direction)
        $exists = Conversation::where('company_id', $authUser->company_id)
            ->where(function ($q) {
                $q->where('type', 'direct')->orWhereNull('type');
            })
            ->where(function ($q) use ($team, $client) {
                $q->where(function ($q2) use ($team, $client) {
                    $q2->where('team_id', $team->id)->where('client_id', $client->id);
                })->orWhere(function ($q2) use ($team, $client) {
                    $q2->where('team_id', $client->id)->where('client_id', $team->id);
                });
            })
            ->first();

        if ($exists) {
            return response()->json($exists);
        }

        $convo = Conversation::create([
            'type' => 'direct',
            'team_id' => $team->id,
            'client_id' => $client->id,
            'company_id' => $authUser->company_id,
        ]);

        return response()->json($convo);
    }

    /**
     * Get users with the same role as current user
     */
    public function sameRoleUsers()
    {
        try {
            $user = Auth::user();
            $userRole = $user->getRoleNames()->first();

            if ($user->hasRole('admin')) {
                // Admin can see all users in the company
                $users = User::where('company_id', $user->company_id)
                    ->where('id', '!=', $user->id)
                    ->get(['id', 'name', 'email']);
            } else {
                // Get users with the same role in the same company
                $users = User::where('company_id', $user->company_id)
                    ->where('id', '!=', $user->id)
                    ->whereHas('roles', fn($q) => $q->where('name', $userRole))
                    ->get(['id', 'name', 'email']);
            }

            return response()->json($users);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    /**
     * Get role group chats for current user (or all for admin)
     */
    public function roleGroupChats()
    {
        $user = Auth::user();
        $userRole = $user->getRoleNames()->first();

        if ($user->hasRole('admin')) {
            // Admin sees all role groups in the company
            $groups = Conversation::where('company_id', $user->company_id)
                ->where('type', 'group')
                ->with(['role', 'participants', 'messages' => fn($q) => $q->latest()])
                ->get();
        } else {
            // Regular user sees their role group(s)
            $groups = Conversation::where('company_id', $user->company_id)
                ->where('type', 'group')
                ->whereHas('participants', fn($q) => $q->where('user_id', $user->id))
                ->with(['role', 'participants', 'messages' => fn($q) => $q->latest()])
                ->get();
        }

        $payload = $groups->map(function ($g) {
            $last = $g->messages->first();
            return [
                'id' => $g->id,
                'name' => $g->name ?? ($g->role ? $g->role->name . ' Group' : 'Group Chat'),
                'role_id' => $g->role_id,
                'role_name' => $g->role ? $g->role->name : null,
                'participant_count' => $g->participants->count(),
                'last_message' => $last ? $last->message : null,
                'last_message_at' => $last ? $last->created_at->toDateTimeString() : $g->created_at->toDateTimeString(),
            ];
        });

        return response()->json($payload);
    }

    /**
     * Get or create a role group chat for a specific role
     * Accepts role_name parameter to support users with multiple roles
     */
    public function getOrCreateRoleGroup(Request $request)
    {
        try {
            $user = Auth::user();
            $roleName = $request->input('role_name');

            // If no role_name provided, use the first non-admin, non-client role
            if (!$roleName) {
                $userRoles = $user->getRoleNames()->filter(fn($r) => !in_array($r, ['admin', 'client']));
                $roleName = $userRoles->first();
            }

            if (!$roleName) {
                return response()->json(['error' => 'No valid role found'], 400);
            }

            // Verify user actually has this role
            if (!$user->hasRole($roleName) && !$user->hasRole('admin')) {
                return response()->json(['error' => 'You do not have this role'], 403);
            }

            // Get the role model
            $role = Role::where('name', $roleName)
                ->where('company_id', $user->company_id)
                ->first();

            if (!$role) {
                return response()->json(['error' => 'Role not found'], 404);
            }

            // Check if group already exists for this role in this company
            $existingGroup = Conversation::where('company_id', $user->company_id)
                ->where('type', 'group')
                ->where('role_id', $role->id)
                ->with('participants')
                ->first();

            if ($existingGroup) {
                // Ensure user is a participant
                if (!$existingGroup->participants->contains('id', $user->id)) {
                    $existingGroup->participants()->attach($user->id, ['joined_at' => now()]);
                }

                // Also add any new users with this role who aren't participants yet
                $this->syncRoleGroupParticipants($existingGroup, $role);

                return response()->json([
                    'id' => $existingGroup->id,
                    'name' => $existingGroup->name,
                    'role_id' => $existingGroup->role_id,
                    'role_name' => $roleName,
                    'created' => false,
                ]);
            }

            // Create new group
            $group = Conversation::create([
                'type' => 'group',
                'role_id' => $role->id,
                'name' => $role->name . ' Group',
                'company_id' => $user->company_id,
            ]);

            // Add all users with this role to the group
            $this->syncRoleGroupParticipants($group, $role);

            return response()->json([
                'id' => $group->id,
                'name' => $group->name,
                'role_id' => $group->role_id,
                'role_name' => $roleName,
                'created' => true,
            ]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    /**
     * Get all roles for the current user (for multi-role group joining)
     */
    public function getMyRoles()
    {
        $user = Auth::user();
        $roles = $user->getRoleNames();

        // Get role groups the user is already a member of
        $joinedGroupRoleIds = Conversation::where('company_id', $user->company_id)
            ->where('type', 'group')
            ->whereHas('participants', fn($q) => $q->where('user_id', $user->id))
            ->pluck('role_id')
            ->toArray();

        // Map roles to include join status (filter out admin and client - case insensitive)
        $roleData = $roles->filter(fn($r) => !in_array(strtolower($r), ['admin', 'client']))->map(function ($roleName) use ($user, $joinedGroupRoleIds) {
            $role = Role::where('name', $roleName)
                ->where('company_id', $user->company_id)
                ->first();

            $isJoined = $role ? in_array($role->id, $joinedGroupRoleIds) : false;

            return [
                'name' => $roleName,
                'role_id' => $role ? $role->id : null,
                'is_joined' => $isJoined,
            ];
        })->values();

        return response()->json($roleData);
    }

    /**
     * Get members of a group chat
     */
    public function getGroupMembers($conversationId)
    {
        $user = Auth::user();
        $conversation = Conversation::where('id', $conversationId)
            ->where('company_id', $user->company_id)
            ->where('type', 'group')
            ->with('participants')
            ->first();

        if (!$conversation) {
            return response()->json(['error' => 'Group not found'], 404);
        }

        // Check if user is a participant (or admin)
        if (!$user->hasRole('admin') && !$conversation->participants->contains('id', $user->id)) {
            return response()->json(['error' => 'You are not a member of this group'], 403);
        }

        $members = $conversation->participants->map(function ($member) {
            return [
                'id' => $member->id,
                'name' => $member->name ?? $member->email,
                'email' => $member->email,
                'is_client' => $member->roles->contains(fn($role) => strtolower($role->name) === 'client'),
            ];
        });

        return response()->json([
            'group_name' => $conversation->name,
            'member_count' => $members->count(),
            'members' => $members,
        ]);
    }

    /**
     * Sync participants for a role group - adds all users with that role
     */
    private function syncRoleGroupParticipants($group, $role)
    {
        // Reload participants to ensure we have latest data
        $group->load('participants');

        $usersWithRole = User::where('company_id', $group->company_id)
            ->whereHas('roles', fn($q) => $q->where('name', $role->name))
            ->pluck('id');

        $existingParticipants = $group->participants->pluck('id')->toArray();
        $newParticipants = $usersWithRole->diff($existingParticipants);

        foreach ($newParticipants as $userId) {
            $group->participants()->attach($userId, ['joined_at' => now()]);
        }
    }

    /**
     * Admin: Join any group chat
     */
    public function adminJoinGroup(Request $request)
    {
        $user = Auth::user();

        if (!$user->hasRole('admin')) {
            return response()->json(['error' => 'Only admins can use this'], 403);
        }

        $request->validate([
            'conversation_id' => 'required|exists:conversations,id',
        ]);

        $group = Conversation::where('id', $request->conversation_id)
            ->where('company_id', $user->company_id)
            ->where('type', 'group')
            ->firstOrFail();

        if (!$group->participants->contains('id', $user->id)) {
            $group->participants()->attach($user->id, ['joined_at' => now()]);
        }

        return response()->json(['success' => true, 'message' => 'Joined group successfully']);
    }

    /**
     * Get all role groups in company (admin only)
     */
    public function allGroupChats()
    {
        $user = Auth::user();

        if (!$user->hasRole('admin')) {
            return response()->json(['error' => 'Only admins can view all groups'], 403);
        }

        $groups = Conversation::where('company_id', $user->company_id)
            ->where('type', 'group')
            ->with(['role', 'participants', 'messages' => fn($q) => $q->latest()])
            ->get();

        $payload = $groups->map(function ($g) use ($user) {
            $last = $g->messages->first();
            return [
                'id' => $g->id,
                'name' => $g->name ?? ($g->role ? $g->role->name . ' Group' : 'Group Chat'),
                'role_id' => $g->role_id,
                'role_name' => $g->role ? $g->role->name : null,
                'participant_count' => $g->participants->count(),
                'is_member' => $g->participants->contains('id', $user->id),
                'last_message' => $last ? $last->message : null,
                'last_message_at' => $last ? $last->created_at->toDateTimeString() : $g->created_at->toDateTimeString(),
            ];
        });

        return response()->json($payload);
    }

    public function teams()
    {
        $teams = User::where('company_id', Auth::user()->company_id)
            ->whereHas('roles', fn($q) => $q->whereNotIn('name', ['admin', 'client']))
            ->get(['id', 'name', 'email']);

        return response()->json($teams);
    }

    /**
     * @deprecated Use sameRoleUsers() instead
     */
    public function clients()
    {
        return $this->sameRoleUsers();
    }

    // ============================================
    // ADMIN GROUP MANAGEMENT METHODS
    // ============================================

    /**
     * Create a new group chat (Admin only)
     */
    public function createGroup(Request $request)
    {
        $user = Auth::user();

        if (!$user->hasRole('admin')) {
            return response()->json(['error' => 'Only admins can create groups'], 403);
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'member_ids' => 'nullable|array',
            'member_ids.*' => 'exists:users,id',
        ]);

        // Create the group
        $group = Conversation::create([
            'type' => 'group',
            'name' => $request->name,
            'company_id' => $user->company_id,
            'role_id' => null, // No role binding for admin-created groups
        ]);

        // Add admin as first participant
        $group->participants()->attach($user->id, ['joined_at' => now()]);

        // Add other members if provided
        if ($request->member_ids && count($request->member_ids) > 0) {
            foreach ($request->member_ids as $memberId) {
                // Verify user is in same company
                $member = User::where('id', $memberId)
                    ->where('company_id', $user->company_id)
                    ->first();

                if ($member && $member->id != $user->id) {
                    $group->participants()->attach($member->id, ['joined_at' => now()]);
                }
            }
        }

        return response()->json([
            'success' => true,
            'group' => [
                'id' => $group->id,
                'name' => $group->name,
                'participant_count' => $group->participants()->count(),
            ],
        ]);
    }

    /**
     * Update group name (Admin only)
     */
    public function updateGroupName(Request $request, $id)
    {
        $user = Auth::user();

        if (!$user->hasRole('admin')) {
            return response()->json(['error' => 'Only admins can update groups'], 403);
        }

        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $group = Conversation::where('id', $id)
            ->where('company_id', $user->company_id)
            ->where('type', 'group')
            ->firstOrFail();

        $group->name = $request->name;
        $group->save();

        return response()->json([
            'success' => true,
            'group' => [
                'id' => $group->id,
                'name' => $group->name,
            ],
        ]);
    }

    /**
     * Add members to an existing group (Admin only)
     */
    public function addGroupMembers(Request $request, $id)
    {
        $user = Auth::user();

        if (!$user->hasRole('admin')) {
            return response()->json(['error' => 'Only admins can add members'], 403);
        }

        $request->validate([
            'member_ids' => 'required|array|min:1',
            'member_ids.*' => 'exists:users,id',
        ]);

        $group = Conversation::where('id', $id)
            ->where('company_id', $user->company_id)
            ->where('type', 'group')
            ->firstOrFail();

        $addedCount = 0;
        foreach ($request->member_ids as $memberId) {
            $member = User::where('id', $memberId)
                ->where('company_id', $user->company_id)
                ->first();

            if ($member && !$group->participants->contains('id', $member->id)) {
                $group->participants()->attach($member->id, ['joined_at' => now()]);
                $addedCount++;
            }
        }

        return response()->json([
            'success' => true,
            'added_count' => $addedCount,
            'total_members' => $group->participants()->count(),
        ]);
    }

    /**
     * Remove a member from a group (Admin only)
     */
    public function removeGroupMember(Request $request, $id, $userId)
    {
        $user = Auth::user();

        if (!$user->hasRole('admin')) {
            return response()->json(['error' => 'Only admins can remove members'], 403);
        }

        $group = Conversation::where('id', $id)
            ->where('company_id', $user->company_id)
            ->where('type', 'group')
            ->firstOrFail();

        // Don't allow removing the last admin
        $adminCount = $group->participants()
            ->whereHas('roles', fn($q) => $q->where('name', 'admin'))
            ->count();

        $memberToRemove = User::find($userId);
        if ($adminCount <= 1 && $memberToRemove && $memberToRemove->hasRole('admin')) {
            return response()->json(['error' => 'Cannot remove the last admin from the group'], 400);
        }

        $group->participants()->detach($userId);

        return response()->json([
            'success' => true,
            'total_members' => $group->participants()->count(),
        ]);
    }

    /**
     * Get all users with a specific role (for group member selection)
     */
    public function getUsersByRole($roleId)
    {
        $user = Auth::user();

        $role = Role::where('id', $roleId)
            ->where('company_id', $user->company_id)
            ->first();

        if (!$role) {
            return response()->json(['error' => 'Role not found'], 404);
        }

        $users = User::where('company_id', $user->company_id)
            ->whereHas('roles', fn($q) => $q->where('roles.id', $roleId))
            ->get(['id', 'name', 'email'])
            ->map(function ($u) {
                return [
                    'id' => $u->id,
                    'name' => $u->name ?? $u->email,
                    'email' => $u->email,
                ];
            });

        return response()->json([
            'role_name' => $role->name,
            'users' => $users,
        ]);
    }

    /**
     * Get all clients for group member selection
     */
    public function getClientsForGroup()
    {
        $user = Auth::user();

        $clients = User::where('company_id', $user->company_id)
            ->whereHas('roles', fn($q) => $q->whereRaw('LOWER(name) = ?', ['client']))
            ->get(['id', 'name', 'email'])
            ->map(function ($u) {
                return [
                    'id' => $u->id,
                    'name' => $u->name ?? $u->email,
                    'email' => $u->email,
                ];
            });

        return response()->json($clients);
    }

    /**
     * Get all roles for dropdown selection
     */
    public function getAllRoles()
    {
        $user = Auth::user();

        $roles = Role::where('company_id', $user->company_id)
            ->whereRaw('LOWER(name) NOT IN (?)', ['admin'])
            ->get(['id', 'name'])
            ->map(function ($r) use ($user) {
                $userCount = User::where('company_id', $user->company_id)
                    ->whereHas('roles', fn($q) => $q->where('roles.id', $r->id))
                    ->count();

                return [
                    'id' => $r->id,
                    'name' => $r->name,
                    'user_count' => $userCount,
                ];
            });

        return response()->json($roles);
    }

    /**
     * Delete a group (Admin only)
     */
    public function deleteGroup(Request $request, $id)
    {
        $user = Auth::user();

        if (!$user->hasRole('admin')) {
            return response()->json(['error' => 'Only admins can delete groups'], 403);
        }

        $group = Conversation::where('id', $id)
            ->where('company_id', $user->company_id)
            ->where('type', 'group')
            ->firstOrFail();

        // Remove all participants
        $group->participants()->detach();

        // Delete messages
        $group->messages()->delete();

        // Delete the group
        $group->delete();

        return response()->json(['success' => true, 'message' => 'Group deleted successfully']);
    }

    /**
     * Get role teams in a group (for client team targeting)
     * Returns list of roles represented in the group with member counts
     */
    public function getGroupTeams($conversationId)
    {
        $user = Auth::user();

        $conversation = Conversation::where('id', $conversationId)
            ->where('company_id', $user->company_id)
            ->where('type', 'group')
            ->with('participants.roles')
            ->first();

        if (!$conversation) {
            return response()->json(['error' => 'Group not found'], 404);
        }

        // Check if user is a participant
        if (!$conversation->participants->contains('id', $user->id) && !$user->hasRole('admin')) {
            return response()->json(['error' => 'Not a member of this group'], 403);
        }

        // Get all roles represented in this group (excluding 'client' and 'admin')
        $roleTeams = [];
        foreach ($conversation->participants as $participant) {
            foreach ($participant->roles as $role) {
                $roleName = strtolower($role->name);
                if ($roleName !== 'client' && $roleName !== 'admin') {
                    if (!isset($roleTeams[$role->id])) {
                        $roleTeams[$role->id] = [
                            'id' => $role->id,
                            'name' => $role->name,
                            'member_count' => 0,
                            'members' => [],
                        ];
                    }
                    $roleTeams[$role->id]['member_count']++;
                    $roleTeams[$role->id]['members'][] = [
                        'id' => $participant->id,
                        'name' => $participant->name ?? $participant->email,
                    ];
                }
            }
        }

        // For non-admin/non-client users (employees), filter to only their own role(s)
        $isAdmin = $user->hasRole('admin');
        $isClient = $user->roles->contains(fn($role) => strtolower($role->name) === 'client');

        if (!$isAdmin && !$isClient) {
            // Get user's non-admin/non-client role IDs
            $userRoleIds = $user->roles
                ->filter(fn($role) => strtolower($role->name) !== 'admin' && strtolower($role->name) !== 'client')
                ->pluck('id')
                ->toArray();

            // Filter teams to only include user's roles
            $roleTeams = array_filter($roleTeams, function ($team) use ($userRoleIds) {
                return in_array($team['id'], $userRoleIds);
            });
        }

        return response()->json(array_values($roleTeams));
    }
}


