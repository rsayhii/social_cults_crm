@extends('superadmin.layout.app')

@section('title', 'Dashboard')

@section('content')
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

            <!-- Main Chat & Reply Area -->
            <div class="lg:col-span-2">
                <div class="bg-white rounded-xl shadow-sm overflow-hidden mb-6">
                    <!-- Header -->
                    <div class="p-4 sm:p-6 border-b border-gray-100 bg-gray-50 flex justify-between items-start">
                        <div>
                            <div class="flex items-center gap-3 mb-2">
                                <span class="text-xs font-bold text-gray-500 uppercase tracking-wider">Ticket
                                    #{{ $ticket->ticket_id }}</span>
                            </div>
                            <h1 class="text-xl font-bold text-gray-900">{{ $ticket->title }}</h1>
                        </div>
                        <div class="text-right">
                            <div class="text-sm font-medium text-gray-900">{{ $ticket->client->name ?? 'Unknown User' }}
                            </div>
                            <div class="text-xs text-gray-500">{{ $ticket->client->email ?? '' }}</div>
                        </div>
                    </div>

                    <!-- Chat Area -->
                    <div class="p-4 sm:p-6 bg-gray-50 min-h-[400px] max-h-[600px] overflow-y-auto space-y-6"
                        id="messages-container">
                        @foreach($ticket->conversations as $message)
                            <div class="flex {{ $message['user_id'] == auth()->id() ? 'justify-end' : 'justify-start' }}">
                                <div
                                    class="max-w-[90%] sm:max-w-[85%] {{ $message['is_internal'] ? 'bg-yellow-50 border border-yellow-200' : ($message['user_id'] == auth()->id() ? 'bg-blue-600 text-white' : 'bg-white border border-gray-200') }} rounded-2xl p-4 shadow-sm relative group">

                                    <!-- Internal Tag -->
                                    @if($message['is_internal'])
                                        <div
                                            class="absolute -top-3 left-4 bg-yellow-100 text-yellow-800 text-[10px] font-bold px-2 py-0.5 rounded border border-yellow-200 uppercase tracking-wide">
                                            Internal Note
                                        </div>
                                    @endif
@php
    $msgUser = \App\Models\User::find($message['user_id']);
@endphp
                                    <div
                                        class="flex items-center gap-2 mb-1 text-xs {{ $message['user_id'] == auth()->id() && !$message['is_internal'] ? 'text-blue-100' : 'text-gray-500' }}">
                                        <span
                                            class="font-bold">{{ $msgUser ? $msgUser->name : 'System' }}</span>
                                        <span>{{ \Carbon\Carbon::parse($message['created_at'])->format('M d, h:i A') }}</span>
                                    </div>

                                    <p
                                        class="whitespace-pre-wrap text-sm leading-relaxed {{ $message['is_internal'] ? 'text-gray-800 italic' : '' }}">
                                        {{ $message['message'] }}
                                    </p>

                                    @if(isset($message['attachments']) && count($message['attachments']) > 0)
                                        <div
                                            class="mt-3 pt-3 border-t {{ $message['user_id'] == auth()->id() && !$message['is_internal'] ? 'border-blue-500' : 'border-gray-200' }}">
                                            @foreach($message['attachments'] as $file)
                                                <a href="{{ Storage::url($file['path']) }}" target="_blank"
                                                    class="flex items-center gap-2 text-xs {{ $message['user_id'] == auth()->id() && !$message['is_internal'] ? 'text-blue-100 hover:text-white' : 'text-gray-600 hover:text-blue-600' }} transition-colors py-1">
                                                    <i class="fas fa-paperclip"></i>
                                                    {{ $file['name'] }}
                                                </a>
                                            @endforeach
                                        </div>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <!-- Reply Form -->
                    <div class="p-4 sm:p-6 bg-white border-t border-gray-100">
                        <form action="{{ route('ticket.record.reply', $ticket->id) }}" method="POST"
                            enctype="multipart/form-data">
                            @csrf

                            <!-- Internal Note Toggle -->
                            <div
                                class="flex items-center mb-4 bg-yellow-50 p-2 rounded-lg border border-yellow-100 w-fit max-w-full">
                                <input type="checkbox" id="is_internal" name="is_internal" value="1"
                                    class="rounded border-gray-300 text-yellow-600 shadow-sm focus:border-yellow-300 focus:ring focus:ring-yellow-200 focus:ring-opacity-50">
                                <label for="is_internal"
                                    class="ml-2 block text-sm text-gray-700 cursor-pointer font-medium select-none truncate">
                                    <i class="fas fa-lock text-yellow-500 mr-1"></i> Send as Internal Note
                                </label>
                            </div>

                            <div class="mb-4">
                                <textarea name="message" rows="4" placeholder="Type your reply here..."
                                    class="w-full rounded-lg border-gray-300 border p-3 focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-shadow outline-none"
                                    required></textarea>
                            </div>

                            <div class="flex flex-col sm:flex-row justify-between items-center gap-3">
                                <label
                                    class="cursor-pointer text-gray-500 hover:text-blue-600 transition-colors flex items-center gap-2 text-sm w-full sm:w-auto justify-center sm:justify-start">
                                    <i class="fas fa-paperclip"></i>
                                    <span>Attach Files</span>
                                    <input type="file" name="attachments[]" multiple class="hidden">
                                </label>
                                <button type="submit"
                                    class="w-full sm:w-auto bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg font-medium transition-colors shadow-sm">
                                    Send Reply
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Sidebar / Tools -->
            <div class="lg:col-span-1">
                <div class="bg-white rounded-xl shadow-sm p-4 sm:p-6 mb-6">
                    <h3 class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-4">Management</h3>
                    <form action="{{ route('ticket.record.update', encrypt($ticket->id)) }}" method="POST" class="space-y-4">
                        @csrf
                        @method('PUT')

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                            <select name="status"
                                class="w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 p-2 border">
                                <option value="open" {{ $ticket->status == 'open' ? 'selected' : '' }}>Open</option>
                                <option value="in-progress" {{ $ticket->status == 'in-progress' ? 'selected' : '' }}>In
                                    Progress</option>
                                <option value="completed" {{ $ticket->status == 'completed' ? 'selected' : '' }}>Completed
                                </option>
                                <option value="closed" {{ $ticket->status == 'closed' ? 'selected' : '' }}>Closed</option>
                            </select>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Priority</label>
                            <select name="priority"
                                class="w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 p-2 border">
                                <option value="low" {{ $ticket->priority == 'low' ? 'selected' : '' }}>Low</option>
                                <option value="medium" {{ $ticket->priority == 'medium' ? 'selected' : '' }}>Medium</option>
                                <option value="high" {{ $ticket->priority == 'high' ? 'selected' : '' }}>High</option>
                                <option value="urgent" {{ $ticket->priority == 'urgent' ? 'selected' : '' }}>Urgent</option>
                            </select>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Assigned Team</label>
                            <select name="assigned_team"
                                class="w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 p-2 border">
                                <option value="">-- Unassigned --</option>
                                <option value="Support Team" {{ $ticket->assigned_team == 'Support Team' ? 'selected' : '' }}>
                                    Support Team</option>
                                <option value="Tech Team" {{ $ticket->assigned_team == 'Tech Team' ? 'selected' : '' }}>Tech
                                    Team</option>
                                <option value="Billing Team" {{ $ticket->assigned_team == 'Billing Team' ? 'selected' : '' }}>
                                    Billing Team</option>
                            </select>
                        </div>

                        <button type="submit"
                            class="w-full bg-gray-800 hover:bg-gray-900 text-white font-medium py-2 rounded-lg transition-colors">
                            Update Ticket
                        </button>
                    </form>

                    <div class="mt-6 pt-6 border-t border-gray-100">
                        <form action="{{ route('ticket.record.destroy', encrypt($ticket->id)) }}" method="POST"
                            onsubmit="return confirm('Are you sure you want to delete this ticket? This action cannot be undone and will delete all attachments.');">
                            @csrf
                            @method('DELETE')
                            <button type="submit"
                                class="w-full bg-white border border-red-200 text-red-600 hover:bg-red-50 font-medium py-2 rounded-lg transition-colors flex items-center justify-center gap-2">
                                <i class="fas fa-trash-alt"></i>
                                Delete Ticket
                            </button>
                        </form>
                    </div>
                </div>

                <!-- Meta Info -->
                <div class="bg-white rounded-xl shadow-sm p-6 space-y-6">
                    <h3 class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-2">Ticket Info</h3>
                    <dl class="space-y-4 text-sm">
                        <div class="flex justify-between border-b border-gray-50 pb-2">
                            <dt class="text-gray-500">Tickets ID</dt>
                            <dd class="font-medium text-gray-900">#{{ $ticket->ticket_id }}</dd>
                        </div>
                        <div class="flex justify-between border-b border-gray-50 pb-2">
                            <dt class="text-gray-500">Category</dt>
                            <dd
                                class="font-medium text-gray-900 px-2 py-0.5 rounded bg-blue-50 text-blue-700 text-xs uppercase tracking-wide">
                                {{ ucfirst($ticket->category) }}
                            </dd>
                        </div>
                        @if(is_array($ticket->issue_permissions) && count($ticket->issue_permissions) > 0)
                        <div class="border-b border-gray-50 pb-2">
                            <dt class="text-gray-500">Issue-Facing Items</dt>
                            <dd class="mt-2">
                                <div class="flex flex-wrap gap-2">
                                    @foreach($ticket->issue_permissions as $perm)
                                        <span class="px-2 py-1 text-xs rounded-lg bg-gray-100 text-gray-700">
                                            {{ ucwords(str_replace(['-', '_'], ' ', $perm)) }}
                                        </span>
                                    @endforeach
                                </div>
                            </dd>
                        </div>
                        @endif
                        <div class="flex justify-between border-b border-gray-50 pb-2">
                            <dt class="text-gray-500">Company</dt>
                            <dd class="font-medium text-gray-900 text-right truncate pl-4">
                                {{ $ticket->client->company->name ?? 'N/A' }}
                            </dd>
                        </div>
                        <div class="flex justify-between border-b border-gray-50 pb-2">
                            <dt class="text-gray-500">Created</dt>
                            <dd class="font-medium text-gray-900">{{ $ticket->created_at->format('M d, Y') }}</dd>
                        </div>
                        <div class="flex justify-between border-b border-gray-50 pb-2">
                            <dt class="text-gray-500">Last Update</dt>
                            <dd class="font-medium text-gray-900">{{ $ticket->updated_at->diffForHumans() }}</dd>
                        </div>
                        <div class="flex justify-between border-b border-gray-50 pb-2">
                            <dt class="text-gray-500">Due Date</dt>
                            <dd
                                class="font-medium {{ $ticket->sla_due_at->isPast() && $ticket->status != 'closed' ? 'text-red-600' : 'text-green-600' }}">
                                {{ $ticket->sla_due_at->format('M d, Y') }}
                            </dd>
                        </div>
                        @if($ticket->resolved_at)
                            <div class="flex justify-between border-b border-gray-50 pb-2">
                                <dt class="text-gray-500">Resolved</dt>
                                <dd class="font-medium text-gray-900">{{ $ticket->resolved_at->format('M d, Y') }}</dd>
                            </div>
                        @endif
                        <div class="border-t border-gray-100 pt-4 mt-2">
                            <dt class="text-gray-500 text-xs font-bold uppercase tracking-wider mb-3">
                                Attachments ({{ count($ticket->attachments ?? []) }})
                            </dt>
                            <dd class="font-medium text-gray-900">
                                @if(count($ticket->attachments ?? []) > 0)
                                    <ul class="space-y-2">
                                        @foreach($ticket->attachments as $file)
                                            <li>
                                                <a href="{{ Storage::url($file['file_path']) }}" target="_blank"
                                                    class="flex items-center gap-2 text-xs text-gray-600 hover:text-blue-600 hover:underline transition-colors group">
                                                    <div
                                                        class="w-6 h-6 rounded bg-gray-100 flex items-center justify-center group-hover:bg-blue-50 group-hover:text-blue-600 text-gray-400">
                                                        <i class="fas fa-paperclip"></i>
                                                    </div>
                                                    <span class="truncate max-w-[180px]" title="{{ $file['file_name'] }}">
                                                        {{ $file['file_name'] }}
                                                    </span>
                                                </a>
                                            </li>
                                        @endforeach
                                    </ul>
                                @else
                                    <div class="text-center py-2 bg-gray-50 rounded border border-gray-100 border-dashed">
                                        <span class="text-gray-400 text-xs">No attachments found</span>
                                    </div>
                                @endif
                            </dd>
                        </div>
                    </dl>
                </div>
            </div>
        </div>
    </div>

    {{-- Toaster Notification --}}
    @if(session('success'))
        <div id="toast-success"
            class="fixed top-5 right-5 z-50 flex items-center w-full max-w-xs p-4 text-gray-500 bg-white rounded-xl shadow-xl border border-gray-100 animate-slide-in-right"
            role="alert">
            <div class="inline-flex items-center justify-center flex-shrink-0 w-8 h-8 text-green-500 bg-green-100 rounded-lg">
                <svg class="w-5 h-5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor"
                    viewBox="0 0 20 20">
                    <path
                        d="M10 .5a9.5 9.5 0 1 0 9.5 9.5A9.51 9.51 0 0 0 10 .5Zm3.707 8.207-4 4a1 1 0 0 1-1.414 0l-2-2a1 1 0 0 1 1.414-1.414L9 10.586l3.293-3.293a1 1 0 0 1 1.414 1.414Z" />
                </svg>
                <span class="sr-only">Check icon</span>
            </div>
            <div class="ms-3 text-sm font-semibold text-gray-800">{{ session('success') }}</div>
            <button type="button"
                class="ms-auto -mx-1.5 -my-1.5 bg-white text-gray-400 hover:text-gray-900 rounded-lg focus:ring-2 focus:ring-gray-300 p-1.5 hover:bg-gray-100 inline-flex items-center justify-center h-8 w-8"
                data-dismiss-target="#toast-success" aria-label="Close"
                onclick="document.getElementById('toast-success').remove()">
                <span class="sr-only">Close</span>
                <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6" />
                </svg>
            </button>
        </div>
        <script>
            setTimeout(function () {
                const toast = document.getElementById('toast-success');
                if (toast) {
                    toast.style.transition = 'all 0.5s ease-out';
                    toast.style.opacity = '0';
                    toast.style.transform = 'translateY(-20px)';
                    setTimeout(() => toast.remove(), 500);
                }
            }, 4000);
        </script>
        <style>
            @keyframes slideInRight {
                from {
                    transform: translateX(100%);
                    opacity: 0;
                }

                to {
                    transform: translateX(0);
                    opacity: 1;
                }
            }

            .animate-slide-in-right {
                animation: slideInRight 0.5s cubic-bezier(0.2, 0, 0.2, 1);
            }
        </style>
    @endif

    <script>
        const messageContainer = document.getElementById('messages-container');
        messageContainer.scrollTop = messageContainer.scrollHeight;
    </script>
@endsection
