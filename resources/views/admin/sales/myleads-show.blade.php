@extends('components.layout')

@section('content')
<div class="min-h-screen bg-gray-50 py-8">
    <div class="max-w-6xl mx-auto px-4">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-2xl font-bold text-gray-800">Lead Details</h2>
            <a href="{{ route('myleads') }}" class="text-blue-600 hover:underline">← Back to Leads</a>
        </div>

        @if($lead)
            <!-- Lead Details Section -->
            <div class="bg-white shadow rounded-lg p-6 mb-6">
                <h3 class="text-xl font-semibold text-gray-800 mb-4">Lead Information</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    <div>
                        <h4 class="text-sm font-medium text-gray-500 mb-1">ID</h4>
                        <p class="text-gray-900">{{ $lead->id }}</p>
                    </div>
                    {{-- <div>
                        <h4 class="text-sm font-medium text-gray-500 mb-1">User ID</h4>
                        <p class="text-gray-900">{{ $lead->user_id }}</p>
                    </div> --}}
                    {{-- <div>
                        <h4 class="text-sm font-medium text-gray-500 mb-1">Client ID</h4>
                        <p class="text-gray-900">{{ $lead->client_id }}</p>
                    </div> --}}
                    <div>
                        <h4 class="text-sm font-medium text-gray-500 mb-1">Response</h4>
                        <p class="text-gray-900">{{ $lead->response ?? 'N/A' }}</p>
                    </div>
                    <div>
                        <h4 class="text-sm font-medium text-gray-500 mb-1">Project Type</h4>
                        <p class="text-gray-900">{{ $lead->project_type ?? 'N/A' }}</p>
                    </div>
                    <div>
                        <h4 class="text-sm font-medium text-gray-500 mb-1">Follow-Up Time</h4>
                        <p class="text-gray-900">{{ \Carbon\Carbon::parse($lead->follow_up_time)->format('H:i') }}</p>
                    </div>
                    <div>
                        <h4 class="text-sm font-medium text-gray-500 mb-1">Next Follow-Up</h4>
                        <p class="text-gray-900">
                            {{ $lead->next_follow_up ? $lead->next_follow_up->format('d M, Y') : '—' }}
                        </p>
                    </div>
                    <div>
                        <h4 class="text-sm font-medium text-gray-500 mb-1">Status</h4>
                        <span class="px-2 py-1 rounded-full text-xs font-medium 
                            @if($lead->status == 'connected') bg-green-100 text-green-700
                            @elseif($lead->status == 'missed') bg-red-100 text-red-700
                            @elseif($lead->status == 'negotiating') bg-yellow-100 text-yellow-700
                            @else bg-blue-100 text-blue-700 @endif">
                            {{ ucfirst(str_replace('_', ' ', $lead->status)) }}
                        </span>
                    </div>
                    <div>
                        <h4 class="text-sm font-medium text-gray-500 mb-1">Created At</h4>
                        <p class="text-gray-900">{{ $lead->created_at ? $lead->created_at->timezone('Asia/Kolkata')->format('d M, Y h:i A') : 'N/A' }}
</p>
                    </div>
                    <div>
                        <h4 class="text-sm font-medium text-gray-500 mb-1">Updated At</h4>
                        <p class="text-gray-900">{{ $lead->updated_at ? $lead->updated_at->timezone('Asia/Kolkata')->format('d M, Y h:i A') : 'N/A' }}
</p>
                    </div>
                </div>
            </div>

            <!-- Client Details Section -->
            <div class="bg-white shadow rounded-lg p-6">
                <h3 class="text-xl font-semibold text-gray-800 mb-4">Client Information</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    <div>
                        <h4 class="text-sm font-medium text-gray-500 mb-1">Company Name</h4>
                        <p class="text-gray-900">{{ $lead->client->company_name ?? 'N/A' }}</p>
                    </div>
                    <div>
                        <h4 class="text-sm font-medium text-gray-500 mb-1">Contact Person</h4>
                        <p class="text-gray-900">{{ $lead->client->contact_person ?? 'N/A' }}</p>
                    </div>
                    <div>
                        <h4 class="text-sm font-medium text-gray-500 mb-1">Email</h4>
                        <p class="text-gray-900">{{ $lead->client->email ?? 'N/A' }}</p>
                    </div>
                    <div>
                        <h4 class="text-sm font-medium text-gray-500 mb-1">Phone</h4>
                        <p class="text-gray-900">{{ $lead->client->phone ?? 'N/A' }}</p>
                    </div>
                    <div>
                        <h4 class="text-sm font-medium text-gray-500 mb-1">Status</h4>
                        <span class="px-2 py-1 rounded-full text-xs font-medium 
                            @if($lead->client->status == 'negotiation') bg-yellow-100 text-yellow-700
                            @elseif($lead->client->status == 'active') bg-green-100 text-green-700
                            @else bg-gray-100 text-gray-700 @endif">
                            {{ ucfirst(str_replace('_', ' ', $lead->client->status)) }}
                        </span>
                    </div>
                    <div>
                        <h4 class="text-sm font-medium text-gray-500 mb-1">Priority</h4>
                        <p class="text-gray-900">{{ $lead->client->priority ?? 'N/A' }}</p>
                    </div>
                    <div>
                        <h4 class="text-sm font-medium text-gray-500 mb-1">Industry</h4>
                        <p class="text-gray-900">{{ $lead->client->industry ?? 'N/A' }}</p>
                    </div>
                    {{-- <div>
                        <h4 class="text-sm font-medium text-gray-500 mb-1">Budget</h4>
                        <p class="text-gray-900">{{ $lead->client->budget ?? 'N/A' }}</p>
                    </div> --}}
                    <div>
                        <h4 class="text-sm font-medium text-gray-500 mb-1">Source</h4>
                        <p class="text-gray-900">{{ $lead->client->source ?? 'N/A' }}</p>
                    </div>
                    {{-- <div>
                        <h4 class="text-sm font-medium text-gray-500 mb-1">Client Next Follow-Up</h4>
                        <p class="text-gray-900">
                            {{ $lead->client->next_follow_up ? $lead->client->next_follow_up->format('d M, Y') : '—' }}
                        </p>
                    </div> --}}
                    <div>
                        <h4 class="text-sm font-medium text-gray-500 mb-1">Notes</h4>
                        <p class="text-gray-900">{{ $lead->client->notes ?? 'N/A' }}</p>
                    </div>
                    {{-- <div>
                        <h4 class="text-sm font-medium text-gray-500 mb-1">Client Created At</h4>
                        <p class="text-gray-900">{{ $lead->client->created_at ? $lead->client->created_at->format('d M, Y H:i') : 'N/A' }}</p>
                    </div> --}}
                    {{-- <div>
                        <h4 class="text-sm font-medium text-gray-500 mb-1">Client Updated At</h4>
                        <p class="text-gray-900">{{ $lead->client->updated_at ? $lead->client->updated_at->format('d M, Y H:i') : 'N/A' }}</p>
                    </div> --}}
                </div>
            </div>
        @else
            <p class="text-gray-600">Lead not found.</p>
        @endif
    </div>
</div>
@endsection