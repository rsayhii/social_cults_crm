@extends('components.layout')
@section('title', 'Edit Client')
@section('header-title', 'Edit Client')
@section('content')
<meta name="csrf-token" content="{{ csrf_token() }}">
<div class="p-4 lg:p-8 pb-24 lg:pb-8">
    <div class="max-w-2xl mx-auto space-y-6">
        <!-- Breadcrumb (Optional) -->
        <nav class="flex" aria-label="Breadcrumb">
            <ol class="inline-flex items-center space-x-1 md:space-x-3">
                <li class="inline-flex items-center">
                    <a href="{{ route('clients.index') }}" class="inline-flex items-center text-sm font-medium text-gray-700 hover:text-blue-600">
                        <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z"></path>
                        </svg>
                        Clients
                    </a>
                </li>
                <li>
                    <div class="flex items-center">
                        <svg class="w-6 h-6 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
                        </svg>
                        <a href="#" class="ml-1 text-sm font-medium text-gray-500 md:ml-2">Edit</a>
                    </div>
                </li>
            </ol>
        </nav>

        <!-- Form -->
        <form id="client-form" method="POST" action="{{ route('clients.update', $client) }}" class="space-y-6">
            @csrf
            @method('PUT')
            <input type="hidden" id="client_id" name="client_id" value="{{ $client->id }}">

            <div class="grid md:grid-cols-2 gap-4">
                <!-- Company Name -->
                <div class="space-y-2">
                    <label for="company_name" class="text-sm font-medium text-gray-700">Company Name *</label>
                    <input id="company_name" name="company_name" type="text" value="{{ old('company_name', $client->company_name) }}" required
                           class="w-full h-10 px-3 py-2 border rounded-md text-sm focus:ring-2 focus:ring-indigo-500 focus:outline-none @error('company_name') border-red-500 @enderror" />
                    @error('company_name')
                        <p class="text-xs text-red-500">{{ $message }}</p>
                    @enderror
                </div>
                <!-- Contact Person -->
                <div class="space-y-2">
                    <label for="contact_person" class="text-sm font-medium text-gray-700">Contact Person *</label>
                    <input id="contact_person" name="contact_person" type="text" value="{{ old('contact_person', $client->contact_person) }}" required
                           class="w-full h-10 px-3 py-2 border rounded-md text-sm focus:ring-2 focus:ring-indigo-500 focus:outline-none @error('contact_person') border-red-500 @enderror" />
                    @error('contact_person')
                        <p class="text-xs text-red-500">{{ $message }}</p>
                    @enderror
                </div>
                <!-- Email -->
                <div class="space-y-2">
                    <label for="email" class="text-sm font-medium text-gray-700">Email *</label>
                    <input id="email" name="email" type="email" value="{{ old('email', $client->email) }}" required
                           class="w-full h-10 px-3 py-2 border rounded-md text-sm focus:ring-2 focus:ring-indigo-500 focus:outline-none @error('email') border-red-500 @enderror" />
                    @error('email')
                        <p class="text-xs text-red-500">{{ $message }}</p>
                    @enderror
                </div>
                <!-- Phone -->
                <div class="space-y-2">
                    <label for="phone" class="text-sm font-medium text-gray-700">Phone</label>
                    <input id="phone" name="phone" type="tel" value="{{ old('phone', $client->phone) }}"
                           class="w-full h-10 px-3 py-2 border rounded-md text-sm focus:ring-2 focus:ring-indigo-500 focus:outline-none @error('phone') border-red-500 @enderror" />
                    @error('phone')
                        <p class="text-xs text-red-500">{{ $message }}</p>
                    @enderror
                </div>
                <!-- Status -->
                <div class="space-y-2">
                    <label for="status" class="text-sm font-medium text-gray-700">Status</label>
                    <select id="status" name="status"
                            class="w-full h-10 px-3 py-2 border rounded-md text-sm focus:ring-2 focus:ring-indigo-500 focus:outline-none @error('status') border-red-500 @enderror">
                        <option value="lead" {{ old('status', $client->status) == 'lead' ? 'selected' : '' }}>Lead</option>
                        <option value="qualified" {{ old('status', $client->status) == 'qualified' ? 'selected' : '' }}>Qualified</option>
                        <option value="proposal" {{ old('status', $client->status) == 'proposal' ? 'selected' : '' }}>Proposal</option>
                        <option value="negotiation" {{ old('status', $client->status) == 'negotiation' ? 'selected' : '' }}>Negotiation</option>
                        <option value="client" {{ old('status', $client->status) == 'client' ? 'selected' : '' }}>Client</option>
                        <option value="lost" {{ old('status', $client->status) == 'lost' ? 'selected' : '' }}>Lost</option>
                    </select>
                    @error('status')
                        <p class="text-xs text-red-500">{{ $message }}</p>
                    @enderror
                </div>
                <!-- Priority -->
                <div class="space-y-2">
                    <label for="priority" class="text-sm font-medium text-gray-700">Priority</label>
                    <select id="priority" name="priority"
                            class="w-full h-10 px-3 py-2 border rounded-md text-sm focus:ring-2 focus:ring-indigo-500 focus:outline-none @error('priority') border-red-500 @enderror">
                        <option value="low" {{ old('priority', $client->priority) == 'low' ? 'selected' : '' }}>Low</option>
                        <option value="medium" {{ old('priority', $client->priority) == 'medium' ? 'selected' : '' }}>Medium</option>
                        <option value="high" {{ old('priority', $client->priority) == 'high' ? 'selected' : '' }}>High</option>
                    </select>
                    @error('priority')
                        <p class="text-xs text-red-500">{{ $message }}</p>
                    @enderror
                </div>
                <!-- Industry -->
                <div class="space-y-2">
                    <label for="industry" class="text-sm font-medium text-gray-700">Industry</label>
                    <input id="industry" name="industry" type="text" value="{{ old('industry', $client->industry) }}"
                           class="w-full h-10 px-3 py-2 border rounded-md text-sm focus:ring-2 focus:ring-indigo-500 focus:outline-none @error('industry') border-red-500 @enderror" />
                    @error('industry')
                        <p class="text-xs text-red-500">{{ $message }}</p>
                    @enderror
                </div>
                <!-- Source -->
                <div class="space-y-2">
                    <label for="source" class="text-sm font-medium text-gray-700">Lead Source</label>
                    <select id="source" name="source"
                            class="w-full h-10 px-3 py-2 border rounded-md text-sm focus:ring-2 focus:ring-indigo-500 focus:outline-none @error('source') border-red-500 @enderror">
                        <option value="website" {{ old('source', $client->source) == 'website' ? 'selected' : '' }}>Website</option>
                        <option value="referral" {{ old('source', $client->source) == 'referral' ? 'selected' : '' }}>Referral</option>
                        <option value="cold_outreach" {{ old('source', $client->source) == 'cold_outreach' ? 'selected' : '' }}>Cold Outreach</option>
                        <option value="social_media" {{ old('source', $client->source) == 'social_media' ? 'selected' : '' }}>Social Media</option>
                        <option value="event" {{ old('source', $client->source) == 'event' ? 'selected' : '' }}>Event</option>
                        <option value="other" {{ old('source', $client->source) == 'other' ? 'selected' : '' }}>Other</option>
                    </select>
                    @error('source')
                        <p class="text-xs text-red-500">{{ $message }}</p>
                    @enderror
                </div>
            </div>
            <!-- Notes -->
          <!-- Notes -->
<div class="space-y-2">
    <label for="notes" class="text-sm font-medium text-gray-700">Notes</label>
    <textarea id="notes" name="notes" rows="3"
              class="w-full min-h-[80px] px-3 py-2 border rounded-md text-sm focus:ring-2 focus:ring-indigo-500 focus:outline-none @error('notes') border-red-500 @enderror">{{ old('notes', $client->notes) }}</textarea>
    @error('notes')
        <p class="text-xs text-red-500">{{ $message }}</p>
    @enderror
</div>
            <!-- Buttons -->
            <div class="flex justify-end gap-3 pt-4">
                <a href="{{ route('clients.index') }}" class="px-4 py-2 border rounded-md text-sm font-medium hover:bg-gray-100 focus:ring-2 focus:ring-indigo-500">
                    Cancel
                </a>
                <button type="submit" id="submit-btn"
                        class="px-4 py-2 bg-indigo-600 text-white rounded-md text-sm font-medium hover:bg-indigo-700 focus:ring-2 focus:ring-indigo-500">
                    Update Client
                </button>
            </div>
        </form>
    </div>
</div>
@endsection