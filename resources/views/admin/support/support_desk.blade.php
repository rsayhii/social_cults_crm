@extends('superadmin.layout.app')

@section('title', 'Dashboard')

@section('content')
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">

        <!-- Stats Overview -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
            <div class="bg-white rounded-xl shadow-sm p-6 hover:shadow-md transition-shadow">
                <p class="text-sm text-gray-500 font-medium">Total Tickets</p>
                <p class="text-3xl font-bold text-gray-900 mt-2">{{ $stats['total'] }}</p>
            </div>
            <div class="bg-white rounded-xl shadow-sm p-6 hover:shadow-md transition-shadow">
                <p class="text-sm text-gray-500 font-medium">Open</p>
                <p class="text-3xl font-bold text-gray-900 mt-2">{{ $stats['open'] }}</p>
            </div>
            <div class="bg-white rounded-xl shadow-sm p-6 hover:shadow-md transition-shadow">
                <p class="text-sm text-gray-500 font-medium">Pending</p>
                <p class="text-3xl font-bold text-gray-900 mt-2">{{ $stats['pending'] }}</p>
            </div>
            <div class="bg-white rounded-xl shadow-sm p-6 hover:shadow-md transition-shadow">
                <p class="text-sm text-gray-500 font-medium">Resolved</p>
                <p class="text-3xl font-bold text-gray-900 mt-2">{{ $stats['resolved'] }}</p>
            </div>
        </div>

        <!-- Issue-Facing Items (Recurring Problems) -->
        <div class="bg-white rounded-xl shadow-sm p-6 mb-8 border-l-4 border-blue-600">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-bold text-gray-900 flex items-center gap-2">
                    <i class="fas fa-exclamation-triangle text-amber-500"></i>
                    Issue-Facing Items
                </h3>
                <span class="text-xs font-medium text-gray-500 bg-gray-100 px-2 py-1 rounded">Top Recurring Issues</span>
            </div>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                @forelse($issueStats as $issue => $count)
                    <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg border border-gray-100 hover:border-blue-200 transition-colors">
                        <span class="text-sm font-medium text-gray-700 truncate pr-2" title="{{ $issue }}">
                            {{ $issue }}
                        </span>
                        <div class="flex items-center gap-2">
                            <span class="text-xs font-bold text-blue-600 bg-blue-50 px-2 py-0.5 rounded-full border border-blue-100">
                                {{ $count }} {{ Str::plural('ticket', $count) }}
                            </span>
                        </div>
                    </div>
                @empty
                    <div class="col-span-full py-4 text-center text-gray-500 italic text-sm">
                        No recurring issues tracked yet.
                    </div>
                @endforelse
            </div>
        </div>

        <!-- Filters & List -->
        <div class="bg-white rounded-xl shadow-sm overflow-hidden">
            <div class="p-6 border-b border-gray-100 flex flex-col md:flex-row justify-between items-center gap-4">
                <h2 class="text-lg font-bold text-gray-900">All Support Tickets</h2>

                <div class="flex flex-wrap gap-2 w-full md:w-auto">
                    <select onchange="window.location.href=this.value"
                        class="text-sm border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 w-full md:w-auto">
                        <option value="{{ route('ticket.record.index') }}">All Status</option>
                        <option value="{{ route('ticket.record.index', ['status' => 'open']) }}" {{ request('status') == 'open' ? 'selected' : '' }}>Open</option>
                        <option value="{{ route('ticket.record.index', ['status' => 'in-progress']) }}" {{ request('status') == 'in-progress' ? 'selected' : '' }}>In Progress</option>
                        <option value="{{ route('ticket.record.index', ['status' => 'completed']) }}" {{ request('status') == 'completed' ? 'selected' : '' }}>Completed</option>
                    </select>

                    <select onchange="window.location.href=this.value"
                        class="text-sm border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 w-full md:w-auto">
                        <option value="{{ route('ticket.record.index') }}">All Priority</option>
                        <option value="{{ route('ticket.record.index', ['priority' => 'urgent']) }}" {{ request('priority') == 'urgent' ? 'selected' : '' }}>Urgent</option>
                        <option value="{{ route('ticket.record.index', ['priority' => 'high']) }}" {{ request('priority') == 'high' ? 'selected' : '' }}>High</option>
                    </select>
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Ticket
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Client
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Status
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Priority
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Created
                            </th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Action
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($tickets as $ticket)
                                        <tr class="hover:bg-gray-50 transition-colors">
                                            <td class="px-6 py-4">
                                                <div class="flex flex-col">
                                                    <span class="text-sm font-medium text-blue-600 mb-1">#{{ $ticket->ticket_id }}</span>
                                                    <span
                                                        class="text-sm text-gray-900 font-medium">{{ Str::limit($ticket->title, 40) }}</span>
                                                    <span class="text-xs text-gray-500">{{ $ticket->category }}</span>
                                                </div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="flex items-center">
                                                    <div
                                                        class="h-8 w-8 rounded-full bg-gray-200 flex items-center justify-center text-xs font-bold text-gray-600 uppercase">
                                                        {{ substr($ticket->client->name ?? 'U', 0, 2) }}
                                                    </div>
                                                    <div class="ml-3">
                                                        <div class="text-sm font-medium text-gray-900">
                                                            {{ $ticket->client->name ?? 'Unknown' }}
                                                        </div>
                                                        <div class="text-xs text-gray-500">{{ $ticket->client->email ?? '' }}</div>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <span
                                                    class="px-2.5 py-1 rounded-full text-xs font-medium 
                                                                                                    {{ $ticket->status === 'open' ? 'bg-blue-100 text-blue-800' :
                            ($ticket->status === 'in-progress' ? 'bg-yellow-100 text-yellow-800' :
                                ($ticket->status === 'completed' ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800')) }}">
                                                    {{ ucfirst($ticket->status) }}
                                                </span>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <span
                                                    class="px-2.5 py-1 rounded-full text-xs font-medium 
                                                                                                    {{ $ticket->priority === 'urgent' ? 'bg-red-100 text-red-800' :
                            ($ticket->priority === 'high' ? 'bg-orange-100 text-orange-800' : 'bg-gray-100 text-gray-800') }}">
                                                    {{ ucfirst($ticket->priority) }}
                                                </span>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                {{ $ticket->created_at->format('M d, H:i') }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                                <a href="{{ route('ticket.record.show', encrypt($ticket->id)) }}"
                                                    class="text-blue-600 hover:text-blue-900">Manage</a>
                                            </td>
                                        </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-12 text-center text-gray-500">
                                    No tickets found matching your filters.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="px-6 py-4 border-t border-gray-200">
                {{ $tickets->links() }}
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
@endsection