@extends('components.layout')

@section('content')
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4 sm:py-8">
        <!-- Back Button -->
        <div class="mb-4">
            <a href="{{ route('user.support.ticket.index') }}"
                class="inline-flex items-center text-sm text-gray-600 hover:text-gray-900 transition-colors">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                </svg>
                Back to My Tickets
            </a>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-4 sm:gap-8">
            <!-- Ticket Conversation -->
            <div class="lg:col-span-2">
                <div class="bg-white rounded-xl shadow-sm overflow-hidden mb-6">
                    <!-- Header -->
                    <div class="p-4 sm:p-6 border-b border-gray-100 bg-gray-50">
                        <div class="flex flex-col sm:flex-row sm:justify-between sm:items-start gap-3">
                            <div class="flex-1">
                                <div class="flex flex-wrap items-center gap-2 mb-2">
                                    <span class="px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                        #{{ $ticket->ticket_id }}
                                    </span>
                                    <span class="px-2.5 py-0.5 rounded-full text-xs font-medium 
                                                        {{ $ticket->status === 'open' ? 'bg-blue-100 text-blue-800' :
        ($ticket->status === 'in-progress' ? 'bg-yellow-100 text-yellow-800' :
            ($ticket->status === 'completed' ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800')) }}">
                                        {{ ucfirst($ticket->status) }}
                                    </span>
                                </div>
                                <h1 class="text-lg sm:text-xl font-bold text-gray-900 break-words">{{ $ticket->title }}</h1>
                            </div>
                            <div class="text-xs sm:text-sm text-gray-500 whitespace-nowrap">
                                {{ $ticket->created_at->format('M d, Y h:i A') }}
                            </div>
                        </div>
                    </div>

                    <!-- Chat Area -->
                    <div class="p-4 sm:p-6 bg-gray-50 min-h-[400px] max-h-[600px] overflow-y-auto space-y-6"
                        id="messages-container">
                        @foreach($ticket->conversations as $message)
                            @if(!$message['is_internal'])
                                <div class="flex {{ $message['user_id'] == auth()->id() ? 'justify-end' : 'justify-start' }}">
                                    <div
                                        class="max-w-[90%] sm:max-w-[80%] {{ $message['user_id'] == auth()->id() ? 'bg-blue-600 text-white rounded-tr-none' : 'bg-white text-gray-800 border border-gray-200 rounded-tl-none' }} rounded-2xl p-4 shadow-sm">
                                        <div
                                            class="flex items-center gap-2 mb-1 text-xs {{ $message['user_id'] == auth()->id() ? 'text-blue-100' : 'text-gray-500' }}">
                                            <span
                                                class="font-bold">{{ $message['user_id'] == auth()->id() ? 'You' : 'Support Agent' }}</span>
                                            <span>{{ \Carbon\Carbon::parse($message['created_at'])->format('h:i A') }}</span>
                                        </div>
                                        <p class="whitespace-pre-wrap text-sm leading-relaxed">{{ $message['message'] }}</p>

                                        @if(isset($message['attachments']) && count($message['attachments']) > 0)
                                            <div
                                                class="mt-3 pt-3 border-t {{ $message['user_id'] == auth()->id() ? 'border-blue-500' : 'border-gray-100' }}">
                                                @foreach($message['attachments'] as $file)
                                                    <a href="{{ Storage::url($file['path']) }}" target="_blank"
                                                        class="flex items-center gap-2 text-xs {{ $message['user_id'] == auth()->id() ? 'text-blue-100 hover:text-white' : 'text-gray-500 hover:text-blue-600' }} transition-colors py-1">
                                                        <i class="fas fa-paperclip"></i>
                                                        {{ $file['name'] }}
                                                    </a>
                                                @endforeach
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            @else
                                <!-- Hide internal notes from user -->
                            @endif
                        @endforeach
                    </div>

                    <!-- Reply Area -->
                    @if($ticket->status !== 'closed')
                        <div class="p-4 sm:p-6 bg-white border-t border-gray-100">
                            <form action="{{ route('user.support.ticket.reply', $ticket->id) }}" method="POST"
                                enctype="multipart/form-data">
                                @csrf
                                <div class="mb-4">
                                    <textarea name="message" rows="3" placeholder="Type your reply here..."
                                        class="w-full rounded-lg border-gray-300 border p-3 focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-shadow"
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
                    @else
                        <div class="p-4 sm:p-6 bg-gray-50 border-t border-gray-100 text-center text-gray-500">
                            <i class="fas fa-lock mb-2"></i>
                            <p>This ticket is closed and cannot be replied to.</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Sidebar Info -->
            <div class="lg:col-span-1">
                <div class="bg-white rounded-xl shadow-sm p-4 sm:p-6 mb-6">
                    <h3 class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-4">Ticket Details</h3>
                    <dl class="space-y-4">
                        <div>
                            <dt class="text-sm text-gray-500">Category</dt>
                            <dd class="text-sm font-medium text-gray-900 mt-1 flex items-center gap-2">
                                <i class="fas fa-folder text-gray-400"></i> {{ ucfirst($ticket->category) }}
                            </dd>
                        </div>
                        <div>
                            <dt class="text-sm text-gray-500">Priority</dt>
                            <dd class="text-sm font-medium text-gray-900 mt-1 flex items-center gap-2">
                                @if($ticket->priority == 'urgent') <i class="fas fa-flag text-red-500"></i>
                                @elseif($ticket->priority == 'high') <i class="fas fa-flag text-orange-500"></i>
                                @else <i class="fas fa-flag text-blue-500"></i>
                                @endif
                                {{ ucfirst($ticket->priority) }}
                            </dd>
                        </div>
                        <div>
                            <dt class="text-sm text-gray-500">Submitted</dt>
                            <dd class="text-sm font-medium text-gray-900 mt-1">
                                {{ $ticket->created_at->format('M d, Y') }}
                            </dd>
                        </div>
                    </dl>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Scroll to bo    ttom of chat
        const messageContainer = document.getElementById('messages-container');
        messageContainer.scrollTop = messageContainer.scrollHeight;
    </script>
@endsection