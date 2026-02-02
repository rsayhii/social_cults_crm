@extends('components.layout')

@section('content')
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6 gap-4">
            <div>
                <h1 class="text-xl sm:text-2xl font-bold text-gray-900">My Support Tickets</h1>
                <p class="text-sm text-gray-500">Manage and track your support requests</p>
            </div>
            <button onclick="document.getElementById('createTicketModal').classList.remove('hidden')"
                class="w-full sm:w-auto bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-4 rounded-lg flex items-center justify-center shadow-sm transition-all">
                <i class="fas fa-plus mr-2"></i> New Ticket
            </button>
        </div>

        @if(session('success'))
            <div class="bg-green-50 border-l-4 border-green-500 p-4 mb-6">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <i class="fas fa-check-circle text-green-500"></i>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm text-green-700">{{ session('success') }}</p>
                    </div>
                </div>
            </div>
        @endif

        <div class="bg-white rounded-xl shadow-sm overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Ticket ID
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Subject
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Status
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Last
                                Updated</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Action
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($tickets as $ticket)
                                        <tr class="hover:bg-gray-50 transition-colors">
                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-blue-600">
                                                #{{ $ticket->ticket_id }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="text-sm text-gray-900 font-medium">{{ Str::limit($ticket->title, 40) }}</div>
                                                <div class="text-xs text-gray-500">{{ $ticket->category }}</div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <span
                                                    class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                                                                                                                            {{ $ticket->status === 'open' ? 'bg-blue-100 text-blue-800' :
                            ($ticket->status === 'in-progress' ? 'bg-yellow-100 text-yellow-800' :
                                ($ticket->status === 'completed' ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800')) }}">
                                                    {{ ucfirst($ticket->status) }}
                                                </span>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                {{ $ticket->updated_at->diffForHumans() }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                                <a href="{{ route('user.support.ticket.show', $ticket->id) }}"
                                                    class="text-blue-600 hover:text-blue-900">View Details</a>
                                            </td>
                                        </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-12 text-center">
                                    <div class="flex flex-col items-center">
                                        <div class="h-12 w-12 rounded-full bg-blue-50 flex items-center justify-center mb-3">
                                            <i class="fas fa-ticket-alt text-blue-400 text-xl"></i>
                                        </div>
                                        <h3 class="text-lg font-medium text-gray-900">No tickets found</h3>
                                        <p class="text-sm text-gray-500 mt-1">Need help? Create a new support ticket.</p>
                                    </div>
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

    <!-- Create Ticket Modal -->
    <div id="createTicketModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden overflow-y-auto h-full w-full z-50">
        <div
            class="relative top-4 sm:top-20 mx-auto p-4 sm:p-5 border w-[calc(100%-2rem)] sm:w-full max-w-2xl shadow-lg rounded-2xl bg-white">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-base sm:text-lg font-bold text-gray-900">Create New Ticket</h3>
                <button onclick="document.getElementById('createTicketModal').classList.add('hidden')"
                    class="text-gray-400 hover:text-gray-600 p-2">
                    <i class="fas fa-times"></i>
                </button>
            </div>

            <form action="{{ route('user.support.ticket.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Subject</label>
                        <input type="text" name="title" required
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm border p-2">
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Category</label>
                            <select name="category" required
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm border p-2">
                                <option value="website">Website Issue</option>
                                <option value="social-media">Social Media</option>
                                <option value="ads-manager">Ads Manager</option>
                                <option value="billing">Billing</option>
                                <option value="others">Other</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Priority</label>
                            <select name="priority" required
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm border p-2">
                                <option value="low">Low</option>
                                <option value="medium">Medium</option>
                                <option value="high">High</option>
                                <option value="urgent">Urgent</option>
                            </select>
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700">Description</label>
                        <textarea name="description" rows="4" required
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm border p-2"></textarea>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700">Attachments</label>
                        <div
                            class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-md">
                            <div class="space-y-1 text-center">
                                <i class="fas fa-cloud-upload-alt text-gray-400 text-3xl mb-2"></i>
                                <div class="flex text-sm text-gray-600">
                                    <label for="file-upload"
                                        class="relative cursor-pointer bg-white rounded-md font-medium text-blue-600 hover:text-blue-500 focus-within:outline-none focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-blue-500">
                                        <span>Upload a file</span>
                                        <input id="file-upload" name="attachments[]" type="file" multiple class="sr-only">
                                    </label>
                                    <p class="pl-1">or drag and drop</p>
                                </div>
                                <p class="text-xs text-gray-500">PNG, JPG, PDF up to 10MB</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="mt-6 flex flex-col-reverse sm:flex-row justify-end gap-2">
                    <button type="button" onclick="document.getElementById('createTicketModal').classList.add('hidden')"
                        class="w-full sm:w-auto bg-gray-200 text-gray-700 px-4 py-2 rounded-lg hover:bg-gray-300">Cancel</button>
                    <button type="submit"
                        class="w-full sm:w-auto bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700">Submit
                        Ticket</button>
                </div>
            </form>
        </div>
    </div>
@endsection