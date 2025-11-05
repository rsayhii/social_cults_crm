@extends('components.layout')

@section('title', 'Clients')

@section('header-title', 'Clients')

@section('content')
<meta name="csrf-token" content="{{ csrf_token() }}">
    <div class="p-4 lg:p-8 pb-24 lg:pb-8">
        <div class="max-w-7xl mx-auto space-y-4 lg:space-y-6">
            <!-- Page Header -->
            <div class="flex flex-col lg:flex-row justify-between items-start lg:items-center gap-4">
    <div>
        <h2 class="text-2xl lg:text-3xl font-bold text-slate-900">Clients & Leads</h2>
        <p class="text-slate-500 mt-2">Manage your client relationships and pipeline</p>
    </div>
    <div class="flex flex-col sm:flex-row gap-3 w-full lg:w-auto">
        <button id="import-client-btn" class="inline-flex items-center justify-center gap-2 whitespace-nowrap rounded-md text-sm font-medium h-10 px-4 py-2 bg-green-600 hover:bg-green-700 shadow-lg shadow-green-500/30 text-white w-full sm:w-auto">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-4 h-4 mr-2">
                <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path>
                <polyline points="7 10 12 15 17 10"></polyline>
                <line x1="12" y1="15" x2="12" y2="3"></line>
            </svg>
            Import from Excel
        </button>
        <button id="add-client-btn" class="inline-flex items-center justify-center gap-2 whitespace-nowrap rounded-md text-sm font-medium h-10 px-4 py-2 bg-indigo-600 hover:bg-indigo-700 shadow-lg shadow-indigo-500/30 text-white w-full sm:w-auto">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-4 h-4 mr-2">
                <path d="M5 12h14"></path>
                <path d="M12 5v14"></path>
            </svg>
            Add New Client
        </button>
    </div>
</div>

<!-- Import Modal -->
<div id="import-modal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 hidden">
    <div class="bg-white rounded-lg shadow-xl w-full max-w-md mx-4">
        <div class="flex items-center justify-between p-6 border-b">
            <h3 class="text-lg font-semibold text-slate-900">Import Clients from Excel</h3>
            <button id="close-import-modal" class="text-slate-400 hover:text-slate-600">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-5 h-5">
                    <path d="M18 6 6 18"></path>
                    <path d="m6 6 12 12"></path>
                </svg>
            </button>
        </div>
        
        <form id="import-form" enctype="multipart/form-data">
            @csrf
            <div class="p-6 space-y-4">
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-2">Excel/CSV File</label>
                    <input type="file" name="excel_file" id="excel_file" accept=".xlsx,.xls,.csv" 
                           class="block w-full text-sm text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100" required>
                    <p class="text-xs text-slate-500 mt-2">Supported formats: .xlsx, .xls, .csv</p>
                </div>
                
                <div class="bg-slate-50 p-4 rounded-lg">
                    <h4 class="text-sm font-medium text-slate-700 mb-2">Expected Columns:</h4>
                    <ul class="text-xs text-slate-600 space-y-1">
                        <li><strong>Required:</strong> company_name, contact_person, email</li>
                        <li><strong>Optional:</strong> phone, status, priority, industry, budget, source, next_follow_up, notes</li>
                    </ul>
                </div>
            </div>
            
            <div class="flex justify-end gap-3 p-6 border-t">
                <button type="button" id="cancel-import" class="px-4 py-2 text-sm font-medium text-slate-700 hover:text-slate-900">
                    Cancel
                </button>
                <button type="submit" id="submit-import" class="inline-flex items-center px-4 py-2 bg-green-600 hover:bg-green-700 text-white text-sm font-medium rounded-md shadow-lg shadow-green-500/30">
                    <svg id="import-spinner" class="hidden animate-spin -ml-1 mr-2 h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    Import Clients
                </button>
            </div>
        </form>
    </div>
</div>
            <!-- Search and Filter -->
            <div class="flex flex-col lg:flex-row gap-3 lg:gap-4">
                <div class="relative flex-1">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="absolute left-3 top-1/2 transform -translate-y-1/2 w-5 h-5 text-slate-400">
                        <circle cx="11" cy="11" r="8"></circle>
                        <path d="m21 21-4.3-4.3"></path>
                    </svg>
                    <input id="search-input" class="flex h-10 w-full rounded-md border border-slate-200 px-3 py-2 pl-10 bg-white/80 backdrop-blur-sm focus:outline-none focus:ring-2 focus:ring-indigo-500" placeholder="Search clients..." value="">
                </div>
                <div class="w-full lg:w-auto">
                    <div class="h-10 items-center justify-center rounded-md p-1 bg-white/80 backdrop-blur-sm w-full lg:w-auto grid grid-cols-5 lg:flex">
                        <button class="filter-btn active inline-flex items-center justify-center whitespace-nowrap rounded-sm px-3 py-1.5 text-sm font-medium bg-white text-slate-900 shadow-sm" data-status="all">All</button>
                        <button class="filter-btn inline-flex items-center justify-center whitespace-nowrap rounded-sm px-3 py-1.5 text-sm font-medium text-slate-600" data-status="lead">Leads</button>
                        <button class="filter-btn inline-flex items-center justify-center whitespace-nowrap rounded-sm px-3 py-1.5 text-sm font-medium text-slate-600" data-status="qualified">Qualified</button>
                        <button class="filter-btn inline-flex items-center justify-center whitespace-nowrap rounded-sm px-3 py-1.5 text-sm font-medium text-slate-600" data-status="proposal">Proposal</button>
                        <button class="filter-btn inline-flex items-center justify-center whitespace-nowrap rounded-sm px-3 py-1.5 text-sm font-medium text-slate-600" data-status="client">Clients</button>
                    </div>
                </div>
            </div>

           <!-- Client Cards Grid -->
<div id="clients-container" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 lg:gap-6">
    @foreach($clients as $client)
    <div class="client-card rounded-lg border border-slate-200/60 bg-white/80 backdrop-blur-sm hover:shadow-xl transition-all duration-300 group" data-status="{{ $client->status }}">
        <div class="flex flex-col space-y-1.5 p-6 pb-3">
            <div class="flex items-start justify-between">
                <div class="flex items-start gap-3 flex-1">
                    <div class="w-12 h-12 bg-gradient-to-br from-indigo-400 to-purple-400 rounded-xl flex items-center justify-center shadow-lg">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-6 h-6 text-white">
                            <path d="M6 22V4a2 2 0 0 1 2-2h8a2 2 0 0 1 2 2v18Z"></path>
                            <path d="M6 12H4a2 2 0 0 0-2 2v6a2 2 0 0 0 2 2h2"></path>
                            <path d="M18 9h2a2 2 0 0 1 2 2v9a2 2 0 0 1-2 2h-2"></path>
                            <path d="M10 6h4"></path>
                            <path d="M10 10h4"></path>
                            <path d="M10 14h4"></path>
                            <path d="M10 18h4"></path>
                        </svg>
                    </div>
                    <div class="flex-1 min-w-0">
                        <h3 class="font-semibold text-slate-900 text-lg truncate">{{ $client->company_name }}</h3>
                        <p class="text-sm text-slate-500 truncate">{{ $client->contact_person }}</p>
                    
                    
                    </div>
                </div>
                <div class="flex items-center gap-1">
                   

                    <!-- Edit Button -->
                    @can('edit lead')
                    {{-- <div class="relative group inline-block">
                        <button
                            class="inline-flex items-center justify-center h-10 w-10 rounded-full bg-gray-100 hover:bg-gray-200 transition edit-client-btn"
                            data-client-id="{{ $client->id }}">
                            <svg xmlns="http://www.w3.org/2000/svg"
                                width="24" height="24"
                                viewBox="0 0 24 24"
                                fill="none" stroke="currentColor"
                                stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                class="w-4 h-4 text-gray-700">
                                <circle cx="12" cy="12" r="1"></circle>
                                <circle cx="12" cy="5" r="1"></circle>
                                <circle cx="12" cy="19" r="1"></circle>
                            </svg>
                        </button>
                        <span class="absolute left-1/2 -translate-x-1/2 -bottom-8 px-2 py-1 text-xs text-white bg-gray-800 rounded opacity-0 group-hover:opacity-100 transition-opacity duration-200 pointer-events-none whitespace-nowrap">
                            Edit
                        </span>
                    </div>     --}}


                    
<div class="relative inline-block text-left">
    <!-- Toggle Button -->
    <button
        id="toggle-client-options-{{ $client->id }}"
        aria-expanded="false"
        aria-haspopup="true"
        onclick="toggleDropdown('{{ $client->id }}')"
        class="inline-flex items-center justify-center h-10 w-10 rounded-full bg-gray-100 hover:bg-gray-200 transition"
    >
        <svg xmlns="http://www.w3.org/2000/svg"
            width="24" height="24"
            viewBox="0 0 24 24"
            fill="none" stroke="currentColor"
            stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
            class="w-4 h-4 text-gray-700">
            <circle cx="12" cy="12" r="1"></circle>
            <circle cx="12" cy="5" r="1"></circle>
            <circle cx="12" cy="19" r="1"></circle>
        </svg>
    </button>

    <!-- Dropdown Menu -->
    <div
        id="client-options-menu-{{ $client->id }}"
        class="absolute right-0 mt-2 w-40 rounded-md shadow-lg bg-white ring-1 ring-black ring-opacity-5 z-10 hidden"
        role="menu"
        aria-orientation="vertical"
        aria-labelledby="toggle-client-options-{{ $client->id }}"
    >
        <div class="py-1" role="none">
            <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 edit-client-btn" data-client-id="{{ $client->id }}" role="menuitem">
                Edit Lead
            </a>
            @if($client->leadAction)
            <a href="{{ route('myleads.edit', $client->leadAction->id) }}" 
   class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100" 
   role="menuitem">
    Edit Taken Action
</a>
            @endif
        </div>
    </div>
</div>



                    @endcan
                </div>
            </div>
        </div>
        <div class="p-6 pt-0 space-y-3">
            <div class="flex flex-wrap gap-2">
                <div class="status-badge inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-semibold capitalize 
                    @if($client->status == 'lead') bg-gray-100 text-gray-700 border-gray-300 border
                    @elseif($client->status == 'qualified') bg-blue-100 text-blue-700 border-blue-300 border
                    @elseif($client->status == 'proposal') bg-purple-100 text-purple-700 border-purple-300 border
                    @elseif($client->status == 'negotiation') bg-yellow-100 text-yellow-700 border-yellow-300 border
                    @elseif($client->status == 'client') bg-green-100 text-green-700 border-green-300 border
                    @else bg-red-100 text-red-700 border-red-300 border @endif">
                    {{ $client->status }}
                </div>
                <div class="priority-badge inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-semibold 
                    @if($client->priority == 'low') bg-green-100 text-green-700
                    @elseif($client->priority == 'medium') bg-yellow-100 text-yellow-700
                    @else bg-orange-100 text-orange-700 @endif">
                    {{ $client->priority }} priority
                </div>
            </div>
            <div class="space-y-2 text-sm">
                <div class="flex items-center gap-2 text-slate-600">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-4 h-4 flex-shrink-0">
                        <rect width="20" height="16" x="2" y="4" rx="2"></rect>
                        <path d="m22 7-8.97 5.7a1.94 1.94 0 0 1-2.06 0L2 7"></path>
                    </svg>
                    <span class="truncate">{{ $client->email }}</span>
                </div>
                @if($client->phone)
                <div class="flex items-center gap-2 text-slate-600">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-4 h-4 flex-shrink-0">
                        <path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"></path>
                    </svg>
                    <span>{{ $client->phone }}</span>
                </div>
                @endif
                @if($client->next_follow_up)
                <div class="flex items-center gap-2 text-slate-600">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-4 h-4 flex-shrink-0">
                        <path d="M8 2v4"></path>
                        <path d="M16 2v4"></path>
                        <rect width="18" height="18" x="3" y="4" rx="2"></rect>
                        <path d="M3 10h18"></path>
                    </svg>
                    <span>Follow-up: {{ \Carbon\Carbon::parse($client->next_follow_up)->format('M j, Y') }}</span>
                </div>
                @endif
            </div>
             <!-- Communication Options -->
                    <div class="flex items-center gap-1 mr-4">
                        @if($client->phone)
                        <!-- WhatsApp -->
                        <div class="relative group inline-block">
                            <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $client->phone) }}" 
                               target="_blank"
                               class="inline-flex items-center justify-center h-8 w-8 rounded-full bg-green-50 hover:bg-green-100 transition-all duration-200 hover:scale-110">
                                <svg class="w-4 h-4 text-green-600" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893-.001-3.189-1.262-6.209-3.553-8.485"/>
                                </svg>
                            </a>
                            {{-- <span class="absolute left-1/2 -translate-x-1/2 -bottom-8 px-2 py-1 text-xs text-white bg-gray-800 rounded opacity-0 group-hover:opacity-100 transition-opacity duration-200 pointer-events-none whitespace-nowrap">
                                WhatsApp
                            </span> --}}
                        </div>

                        <!-- Call -->
                        <div class="relative group inline-block">
                            <a href="tel:{{ $client->phone }}" 
                               class="inline-flex items-center justify-center h-8 w-8 rounded-full bg-blue-50 hover:bg-blue-100 transition-all duration-200 hover:scale-110">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-4 h-4 text-blue-600">
                                    <path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"></path>
                                </svg>
                            </a>
                            {{-- <span class="absolute left-1/2 -translate-x-1/2 -bottom-8 px-2 py-1 text-xs text-white bg-gray-800 rounded opacity-0 group-hover:opacity-100 transition-opacity duration-200 pointer-events-none whitespace-nowrap">
                                Call
                            </span> --}}
                        </div>
                        @endif

                        @if($client->email)
                        <!-- Email -->
                        <div class="relative group inline-block">
                            <a href="mailto:{{ $client->email }}" 
                               class="inline-flex items-center justify-center h-8 w-8 rounded-full bg-red-50 hover:bg-red-100 transition-all duration-200 hover:scale-110">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-4 h-4 text-red-600">
                                    <rect width="20" height="16" x="2" y="4" rx="2"></rect>
                                    <path d="m22 7-8.97 5.7a1.94 1.94 0 0 1-2.06 0L2 7"></path>
                                </svg>
                            </a>
                            {{-- <span class="absolute left-1/2 -translate-x-1/2 -bottom-8 px-2 py-1 text-xs text-white bg-gray-800 rounded opacity-0 group-hover:opacity-100 transition-opacity duration-200 pointer-events-none whitespace-nowrap">
                                Email
                            </span> --}}
                        </div>
                        @endif

                        <!-- SMS -->
                        @if($client->phone)
                        <div class="relative group inline-block">
                            <a href="sms:{{ $client->phone }}" 
                               class="inline-flex items-center justify-center h-8 w-8 rounded-full bg-purple-50 hover:bg-purple-100 transition-all duration-200 hover:scale-110">
                                <svg class="w-4 h-4 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"/>
                                </svg>
                            </a>
                            {{-- <span class="absolute left-1/2 -translate-x-1/2 -bottom-8 px-2 py-1 text-xs text-white bg-gray-800 rounded opacity-0 group-hover:opacity-100 transition-opacity duration-200 pointer-events-none whitespace-nowrap">
                                Message
                            </span> --}}
                        </div>
                        @endif
                    </div>
            <div class="pt-3 border-t border-slate-200">
                <p class="text-xs text-slate-500">Lead Source</p>
                <p class="text-lg font-semibold text-slate-900">{{ $client->source }}</p>
            </div>

        <!-- Take Action Button -->
@if($client->leadAction)
  <!-- Button disabled if already actioned -->
  <button
    class="block w-full text-center px-4 py-2 text-sm font-medium border border-gray-400 text-gray-400 rounded-lg cursor-not-allowed bg-gray-100"
    disabled>
    Action Taken
  </button>

  <div class="mt-2 px-3 py-1 bg-green-50 border border-green-300 rounded-md text-green-700 text-sm">
    ✅ Action taken by <strong>{{ $client->leadAction->user->name ?? 'Unknown User' }} , &nbsp; {{ \Carbon\Carbon::parse($client->leadAction->created_at)
    ->setTimezone('Asia/Kolkata')
    ->diffForHumans() }}


</strong>
  </div>

@else
  <!-- Button active if no action yet -->
  <button
    onclick="openActionModal({{ $client->id }})"
    class="block w-full text-center px-4 py-2 text-sm font-medium border border-blue-600 text-blue-600 rounded-lg hover:bg-blue-600 hover:text-white transition-all duration-200">
    Take Action
  </button>

  <div class="mt-2 px-3 py-1 bg-gray-50 border border-gray-300 rounded-md text-gray-600 text-sm">
    ⏳ No action taken yet
  </div>
@endif




        </div>
    </div>
    @endforeach
</div>
        </div>
    </div>


    {{-- take action form --}}
    <!-- Take Action Modal -->
<!-- ====================== MODAL ====================== -->
  <div id="actionModal"
       class="fixed inset-0 bg-black/50 hidden items-center justify-center z-50">
    <div class="bg-white rounded-xl shadow-2xl w-full max-w-lg p-6 relative">

      <button id="closeModal"
              class="absolute top-3 right-3 text-gray-500 hover:text-gray-800 text-2xl">
        &times;
      </button>

      <h3 class="text-lg font-semibold mb-4 text-gray-800">Submit Lead Response</h3>

      <form id="actionForm" method="POST" action="{{ route('myleads.store') }}">
    <!-- Laravel style token – delete if you don’t use it -->
    <input type="hidden" name="_token" value="{{ csrf_token() }}">

    <input type="hidden" name="client_id" id="client_id">

    <div class="mb-3">
        <label class="block text-sm font-medium text-gray-700 mb-1">Response / Feedback</label>
        <textarea name="response" rows="3"
                  class="w-full border border-gray-300 rounded-lg p-2 focus:ring focus:ring-blue-200"
                  required></textarea>
    </div>

    <div class="mb-3">
        <label class="block text-sm font-medium text-gray-700 mb-1">Next Follow-Up Date</label>
        <input type="date" name="next_follow_up"
               class="w-full border border-gray-300 rounded-lg p-2 focus:ring focus:ring-blue-200">
    </div>

    <!-- New Field: Follow-up Time -->
    <div class="mb-3">
        <label class="block text-sm font-medium text-gray-700 mb-1">Follow-Up Time</label>
        <input type="time" name="follow_up_time"
               class="w-full border border-gray-300 rounded-lg p-2 focus:ring focus:ring-blue-200">
    </div>

    <!-- New Field: Project Type -->
    <div class="mb-3">
        <label name="project_type" class="block text-sm font-medium text-gray-700 mb-1">Project Type</label>
        <select name="project_type" class="w-full border border-gray-300 rounded-lg p-2 focus:ring focus:ring-blue-200">
            <option value="">-- Select Project Type --</option>
            <option value="web_development">Web Development</option>
            <option value="mobile_app">Mobile App</option>
            <option value="ecommerce">E-commerce</option>
            <option value="ui_ux_design">UI/UX Design</option>
            <option value="digital_marketing">Digital Marketing</option>
            <option value="seo">SEO</option>
            <option value="custom_software">Custom Software</option>
            <option value="other">Other</option>
        </select>
    </div>

    <div class="mb-3">
        <label class="block text-sm font-medium text-gray-700 mb-1">Lead Status</label>
        <select name="status" class="w-full border border-gray-300 rounded-lg p-2 focus:ring focus:ring-blue-200">
            <option>--select--</option>
            <option value="interested">Interested</option>
            <option value="not interested">Not Interested</option>
            <option value="missed booked">Meeting Booked</option>
            <option value="proposal">Proposal</option>
            <option value="negotiating">Negotiating</option>
            <option value="purchased">Purchased</option>
            <option value="will call back">Will Call Back</option>
        </select>
    </div>

    <button type="submit"
            class="w-full mt-4 bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 rounded-lg transition-all">
        Save Response
    </button>
</form>
    </div>
  </div>
  <!-- ====================== END MODAL ====================== -->
  {{-- take action form --}}
  
  
  
  <!-- ====================== EDIT LEAD MODAL ====================== -->
  <!-- Edit Action Modal -->
{{-- <div id="editActionModal" class="fixed inset-0 bg-black/50 hidden items-center justify-center z-50">
    <div class="bg-white rounded-xl shadow-2xl w-full max-w-lg p-6 relative">
        <button id="closeEditModal" class="absolute top-3 right-3 text-gray-500 hover:text-gray-800 text-2xl">
            &times;
        </button>

        <h3 class="text-lg font-semibold mb-4 text-gray-800">Edit Lead Response</h3>

        <form id="editActionForm" method="POST">
            @csrf
            @method('PUT')
            <input type="hidden" name="action_id" id="edit_action_id">

            <div class="mb-3">
                <label class="block text-sm font-medium text-gray-700 mb-1">Response / Feedback</label>
                <textarea name="response" id="edit_response" rows="3"
                          class="w-full border border-gray-300 rounded-lg p-2 focus:ring focus:ring-blue-200"
                          required></textarea>
            </div>

            <div class="mb-3">
                <label class="block text-sm font-medium text-gray-700 mb-1">Next Follow-Up Date</label>
                <input type="date" name="next_follow_up" id="edit_next_follow_up"
                       class="w-full border border-gray-300 rounded-lg p-2 focus:ring focus:ring-blue-200">
            </div>

            <div class="mb-3">
                <label class="block text-sm font-medium text-gray-700 mb-1">Lead Status</label>
                <select name="status" id="edit_status" class="w-full border border-gray-300 rounded-lg p-2 focus:ring focus:ring-blue-200">
                    <option value="interested">Interested</option>
                    <option value="not interested">Not Interested</option>
                    <option value="meeting booked">Meeting Booked</option>
                    <option value="proposal">Proposal</option>
                    <option value="negotiating">Negotiating</option>
                    <option value="purchased">Purchased</option>
                    <option value="will call back">Will Call Back</option>
                </select>
            </div>

            <button type="submit"
                    class="w-full mt-4 bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 rounded-lg transition-all">
                Update Response
            </button>
        </form>
    </div>
</div> --}}
  <!-- ====================== END EDIT LEAD MODAL ====================== -->





    <!-- Edit Client Modal -->
    <div id="client-modal" class="fixed inset-0 flex items-center justify-center z-50 bg-black/40 hidden">
        <div class="bg-white rounded-lg shadow-lg max-w-2xl w-full max-h-[90vh] overflow-y-auto p-6 relative">
            
            <!-- Close Button -->
            <button type="button" id="close-modal" class="absolute right-4 top-4 text-gray-500 hover:text-gray-700">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>

            <!-- Header -->
            <div class="mb-6 text-center sm:text-left">
                <h2 id="modal-title" class="text-2xl font-bold text-gray-800">Edit Client</h2>
            </div>

            <!-- Form -->
            <form id="client-form" class="space-y-6">
                @csrf
                <input type="hidden" id="client_id" name="client_id">
                
                <div class="grid md:grid-cols-2 gap-4">
                    <!-- Company Name -->
                    <div class="space-y-2">
                        <label for="company_name" class="text-sm font-medium text-gray-700">Company Name *</label>
                        <input id="company_name" name="company_name" type="text" required
                            class="w-full h-10 px-3 py-2 border rounded-md text-sm focus:ring-2 focus:ring-indigo-500 focus:outline-none" />
                    </div>

                    <!-- Contact Person -->
                    <div class="space-y-2">
                        <label for="contact_person" class="text-sm font-medium text-gray-700">Contact Person *</label>
                        <input id="contact_person" name="contact_person" type="text" required
                            class="w-full h-10 px-3 py-2 border rounded-md text-sm focus:ring-2 focus:ring-indigo-500 focus:outline-none" />
                    </div>

                    <!-- Email -->
                    <div class="space-y-2">
                        <label for="email" class="text-sm font-medium text-gray-700">Email *</label>
                        <input id="email" name="email" type="email" required
                            class="w-full h-10 px-3 py-2 border rounded-md text-sm focus:ring-2 focus:ring-indigo-500 focus:outline-none" />
                    </div>

                    <!-- Phone -->
                    <div class="space-y-2">
                        <label for="phone" class="text-sm font-medium text-gray-700">Phone</label>
                        <input id="phone" name="phone" type="tel"
                            class="w-full h-10 px-3 py-2 border rounded-md text-sm focus:ring-2 focus:ring-indigo-500 focus:outline-none" />
                    </div>

                    <!-- Status -->
                    <div class="space-y-2">
                        <label for="status" class="text-sm font-medium text-gray-700">Status</label>
                        <select id="status" name="status"
                            class="w-full h-10 px-3 py-2 border rounded-md text-sm focus:ring-2 focus:ring-indigo-500 focus:outline-none">
                            <option value="lead">Lead</option>
                            <option value="qualified">Qualified</option>
                            <option value="proposal">Proposal</option>
                            <option value="negotiation">Negotiation</option>
                            <option value="client">Client</option>
                            <option value="lost">Lost</option>
                        </select>
                    </div>

                    <!-- Priority -->
                    <div class="space-y-2">
                        <label for="priority" class="text-sm font-medium text-gray-700">Priority</label>
                        <select id="priority" name="priority"
                            class="w-full h-10 px-3 py-2 border rounded-md text-sm focus:ring-2 focus:ring-indigo-500 focus:outline-none">
                            <option value="low">Low</option>
                            <option value="medium">Medium</option>
                            <option value="high">High</option>
                        </select>
                    </div>

                    <!-- Industry -->
                    <div class="space-y-2">
                        <label for="industry" class="text-sm font-medium text-gray-700">Industry</label>
                        <input id="industry" name="industry" type="text"
                            class="w-full h-10 px-3 py-2 border rounded-md text-sm focus:ring-2 focus:ring-indigo-500 focus:outline-none" />
                    </div>

                    <!-- Budget -->
                    {{-- <div class="space-y-2">
                        <label for="budget" class="text-sm font-medium text-gray-700">Monthly Budget (₹)</label>
                        <input id="budget" name="budget" type="number" step="0.01"
                            class="w-full h-10 px-3 py-2 border rounded-md text-sm focus:ring-2 focus:ring-indigo-500 focus:outline-none" />
                    </div> --}}

                    <!-- Lead Source -->
                    <div class="space-y-2">
                        <label for="source" class="text-sm font-medium text-gray-700">Lead Source</label>
                        <select id="source" name="source"
                            class="w-full h-10 px-3 py-2 border rounded-md text-sm focus:ring-2 focus:ring-indigo-500 focus:outline-none">
                            <option value="website">Website</option>
                            <option value="referral">Referral</option>
                            <option value="cold_outreach">Cold Outreach</option>
                            <option value="social_media">Social Media</option>
                            <option value="event">Event</option>
                            <option value="other">Other</option>
                        </select>
                    </div>

                    <!-- Next Follow-up -->
                    {{-- <div class="space-y-2">
                        <label for="next_follow_up" class="text-sm font-medium text-gray-700">Next Follow-up</label>
                        <input id="next_follow_up" name="next_follow_up" type="date"
                            class="w-full h-10 px-3 py-2 border rounded-md text-sm focus:ring-2 focus:ring-indigo-500 focus:outline-none" />
                    </div> --}}
                </div>

                <!-- Notes -->
                <div class="space-y-2">
                    <label for="notes" class="text-sm font-medium text-gray-700">Notes</label>
                    <textarea id="notes" name="notes" rows="3"
                        class="w-full min-h-[80px] px-3 py-2 border rounded-md text-sm focus:ring-2 focus:ring-indigo-500 focus:outline-none"></textarea>
                </div>

                <!-- Buttons -->
                <div class="flex justify-end gap-3 pt-4">
                    <button type="button" id="cancel-btn"
                        class="px-4 py-2 border rounded-md text-sm font-medium hover:bg-gray-100 focus:ring-2 focus:ring-indigo-500">
                        Cancel
                    </button>
                    <button type="submit" id="submit-btn"
                        class="px-4 py-2 bg-indigo-600 text-white rounded-md text-sm font-medium hover:bg-indigo-700 focus:ring-2 focus:ring-indigo-500">
                        Update Client
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Success Toast -->
    <div id="success-toast" class="fixed top-4 right-4 bg-green-500 text-white px-6 py-3 rounded-lg shadow-lg transform translate-x-full transition-transform duration-300 z-50 hidden">
        <div class="flex items-center gap-2">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
            </svg>
            <span id="toast-message">Operation completed successfully!</span>
        </div>
    </div>

    


{{-- @section('scripts') --}}
<script>
document.addEventListener('DOMContentLoaded', function() {
    const modal = document.getElementById('client-modal');
    const closeModalBtn = document.getElementById('close-modal');
    const cancelBtn = document.getElementById('cancel-btn');
    const addClientBtn = document.getElementById('add-client-btn');
    const modalTitle = document.getElementById('modal-title');
    const submitBtn = document.getElementById('submit-btn');
    const clientForm = document.getElementById('client-form');
    const searchInput = document.getElementById('search-input');
    const filterBtns = document.querySelectorAll('.filter-btn');
    const clientsContainer = document.getElementById('clients-container');
    const successToast = document.getElementById('success-toast');
    const toastMessage = document.getElementById('toast-message');

    let currentFilter = 'all';
    let currentSearch = '';

    // Show toast notification
    function showToast(message) {
        toastMessage.textContent = message;
        successToast.classList.remove('hidden');
        successToast.classList.remove('translate-x-full');
        
        setTimeout(() => {
            hideToast();
        }, 3000);
    }

    function hideToast() {
        successToast.classList.add('translate-x-full');
        setTimeout(() => {
            successToast.classList.add('hidden');
        }, 300);
    }

    // Filter and search clients
    function filterClients() {
        const clientCards = document.querySelectorAll('.client-card');
        const searchTerm = currentSearch.toLowerCase();
        
        clientCards.forEach(card => {
            const clientText = card.textContent.toLowerCase();
            const clientStatus = card.getAttribute('data-status');
            const matchesSearch = clientText.includes(searchTerm);
            const matchesFilter = currentFilter === 'all' || clientStatus === currentFilter;
            
            if (matchesSearch && matchesFilter) {
                card.style.display = 'block';
            } else {
                card.style.display = 'none';
            }
        });
    }

    // Search functionality
    searchInput.addEventListener('input', function() {
        currentSearch = this.value;
        filterClients();
    });

    // Filter functionality
    filterBtns.forEach(btn => {
        btn.addEventListener('click', function() {
            filterBtns.forEach(b => b.classList.remove('active', 'bg-white', 'text-slate-900', 'shadow-sm'));
            filterBtns.forEach(b => b.classList.add('text-slate-600'));
            
            this.classList.add('active', 'bg-white', 'text-slate-900', 'shadow-sm');
            this.classList.remove('text-slate-600');
            
            currentFilter = this.getAttribute('data-status');
            filterClients();
        });
    });

    // Open modal for editing client
    // Use event delegation on document (works for dynamic elements)
document.addEventListener('click', function(e) {
    const editBtn = e.target.closest('.edit-client-btn');
    if (!editBtn) return;

    e.preventDefault(); // Prevent # href navigation

    const clientId = editBtn.getAttribute('data-client-id');
    if (!clientId) return;

    fetch(`/clients/${clientId}`)
        .then(response => {
            if (!response.ok) throw new Error('Client not found');
            return response.json();
        })
        .then(client => {
            // Populate form
            document.getElementById('client_id').value = client.id;
            document.getElementById('company_name').value = client.company_name || '';
            document.getElementById('contact_person').value = client.contact_person || '';
            document.getElementById('email').value = client.email || '';
            document.getElementById('phone').value = client.phone || '';
            document.getElementById('status').value = client.status || 'lead';
            document.getElementById('priority').value = client.priority || 'medium';
            document.getElementById('industry').value = client.industry || '';
            document.getElementById('source').value = client.source || 'website';
            document.getElementById('next_follow_up').value = client.next_follow_up || '';
            document.getElementById('notes').value = client.notes || '';

            // Update UI
            document.getElementById('modal-title').textContent = 'Edit Client';
            document.getElementById('submit-btn').textContent = 'Update Client';

            // Show modal
            document.getElementById('client-modal').classList.remove('hidden');
        })
        .catch(err => {
            console.error('Error:', err);
            showToast('Failed to load client data');
        });
});

    // Open modal for adding new client
    addClientBtn.addEventListener('click', function() {
        // Clear form
        clientForm.reset();
        document.getElementById('client_id').value = '';
        
        // Update modal title and submit button
        modalTitle.textContent = 'Add New Client';
        submitBtn.textContent = 'Add Client';
        
        // Show modal
        modal.classList.remove('hidden');
    });

    // Close modal
    function closeModal() {
        modal.classList.add('hidden');
    }

    closeModalBtn.addEventListener('click', closeModal);
    cancelBtn.addEventListener('click', closeModal);

    // Close modal when clicking outside
    modal.addEventListener('click', function(e) {
        if (e.target === modal) {
            closeModal();
        }
    });

    // Form submission
    clientForm.addEventListener('submit', function(e) {
    e.preventDefault();
    
    const formData = new FormData(this);
    const clientId = formData.get('client_id');
    const isEditing = clientId !== '';
    
    const url = isEditing ? `/clients/${clientId}` : '/clients';
    
    // Always send as POST, spoof PUT if editing
    if (isEditing) {
        formData.append('_method', 'PUT');
    }

    fetch(url, {
        method: 'POST',
        body: formData,
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }
    })
    .then(response => {
        if (!response.ok) {
            return response.json().then(err => { throw err; });
        }
        return response.json();
    })
    .then(data => {
        if (data.success) {
            showToast(data.message);
            closeModal();
            setTimeout(() => location.reload(), 1000);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        let msg = 'Operation failed';
        if (error.errors) {
            msg = Object.values(error.errors).flat().join(', ');
        } else if (error.message) {
            msg = error.message;
        }
        showToast('Error: ' + msg);
    });
});
});
</script>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const importBtn = document.getElementById('import-client-btn');
    const importModal = document.getElementById('import-modal');
    const closeImportModal = document.getElementById('close-import-modal');
    const cancelImport = document.getElementById('cancel-import');
    const importForm = document.getElementById('import-form');
    const submitImport = document.getElementById('submit-import');
    const importSpinner = document.getElementById('import-spinner');

    // Open import modal
    importBtn.addEventListener('click', function() {
        importModal.classList.remove('hidden');
    });

    // Close import modal
    [closeImportModal, cancelImport].forEach(btn => {
        btn.addEventListener('click', function() {
            importModal.classList.add('hidden');
            importForm.reset();
        });
    });

    // Handle form submission
    importForm.addEventListener('submit', function(e) {
        e.preventDefault();
        
        const formData = new FormData(this);
        const fileInput = document.getElementById('excel_file');
        
        if (!fileInput.files[0]) {
            alert('Please select a file to import.');
            return;
        }

        // Show loading state
        submitImport.disabled = true;
        importSpinner.classList.remove('hidden');
        submitImport.innerHTML = 'Importing...';

        fetch('{{ route("clients.import") }}', {
            method: 'POST',
            body: formData,
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                let message = `Successfully imported ${data.imported_count} clients.`;
                if (data.skipped_count > 0) {
                    message += ` ${data.skipped_count} rows were skipped.`;
                    if (data.skipped_rows.length > 0) {
                        message += '\n\nSkipped rows:\n' + data.skipped_rows.join('\n');
                    }
                }
                alert(message);
                importModal.classList.add('hidden');
                importForm.reset();
                location.reload(); // Refresh to show new data
            } else {
                alert('Error: ' + data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('An error occurred while importing the file.');
        })
        .finally(() => {
            // Reset button state
            submitImport.disabled = false;
            importSpinner.classList.add('hidden');
            submitImport.innerHTML = 'Import Clients';
        });
    });
});
</script>




<!-- ====================== SCRIPT ====================== -->
  <script>
    const modal   = document.getElementById('actionModal');
    const closeBtn = document.getElementById('closeModal');

    // Public function – call it from any "Take Action" button
    window.openActionModal = function (clientId) {
      document.getElementById('client_id').value = clientId;
      modal.classList.remove('hidden');
      modal.classList.add('flex');           // makes the overlay visible & centered
    };

    // Close button
    closeBtn.addEventListener('click', () => closeModal());

    // Click outside the white box
    modal.addEventListener('click', (e) => {
      if (e.target === modal) closeModal();
    });

    // ESC key
    document.addEventListener('keydown', (e) => {
      if (e.key === 'Escape' && !modal.classList.contains('hidden')) closeModal();
    });

    function closeModal() {
      modal.classList.add('hidden');
      modal.classList.remove('flex');
      // optional: reset form
      document.getElementById('actionForm').reset();
      document.getElementById('client_id').value = '';
    }
  </script>
  <!-- ====================== END SCRIPT ====================== -->



  <script>
    // Toggle dropdown for specific client
    function toggleDropdown(clientId) {
        const menu = document.getElementById(`client-options-menu-${clientId}`);
        const allMenus = document.querySelectorAll('[id^="client-options-menu-"]');

        // Close all other menus
        allMenus.forEach(m => {
            if (m.id !== `client-options-menu-${clientId}`) {
                m.classList.add('hidden');
            }
        });

        // Toggle this one
        if (menu) {
            menu.classList.toggle('hidden');
        }
    }

    // Close dropdown when clicking outside
    document.addEventListener('click', function(event) {
        document.querySelectorAll('[id^="client-options-menu-"]').forEach(menu => {
            const buttonId = menu.getAttribute('aria-labelledby');
            const button = document.getElementById(buttonId);
            if (button && !button.contains(event.target) && !menu.contains(event.target)) {
                menu.classList.add('hidden');
            }
        });
    });
</script>



<script>
    // Edit Action Functionality
document.addEventListener('DOMContentLoaded', function() {
    const editActionModal = document.getElementById('editActionModal');
    const closeEditModal = document.getElementById('closeEditModal');
    const editActionForm = document.getElementById('editActionForm');

    // Event delegation for edit action buttons
    document.addEventListener('click', function(e) {
        const editBtn = e.target.closest('.edit-action-btn');
        if (!editBtn) return;

        e.preventDefault();
        const actionId = editBtn.getAttribute('data-action-id');
        openEditActionModal(actionId);
    });

    // Open edit action modal
    window.openEditActionModal = function(actionId) {
        fetch(`/myleads/${actionId}/edit`)
            .then(response => {
                if (!response.ok) throw new Error('Action not found');
                return response.json();
            })
            .then(action => {
                // Populate form with existing data
                document.getElementById('edit_action_id').value = action.id;
                document.getElementById('edit_response').value = action.response || '';
                document.getElementById('edit_next_follow_up').value = action.next_follow_up || '';
                document.getElementById('edit_status').value = action.status || 'interested';

                // Set form action
                editActionForm.action = `/myleads/${action.id}`;

                // Show modal
                editActionModal.classList.remove('hidden');
                editActionModal.classList.add('flex');
            })
            .catch(err => {
                console.error('Error:', err);
                showToast('Failed to load action data');
            });
    };

    // Close edit modal
    closeEditModal.addEventListener('click', closeEditActionModal);

    // Click outside to close
    editActionModal.addEventListener('click', (e) => {
        if (e.target === editActionModal) closeEditActionModal();
    });

    // ESC key to close
    document.addEventListener('keydown', (e) => {
        if (e.key === 'Escape' && !editActionModal.classList.contains('hidden')) {
            closeEditActionModal();
        }
    });

    function closeEditActionModal() {
        editActionModal.classList.add('hidden');
        editActionModal.classList.remove('flex');
        editActionForm.reset();
    }

    // Handle edit form submission
    editActionForm.addEventListener('submit', function(e) {
        e.preventDefault();
        
        const formData = new FormData(this);
        const actionId = document.getElementById('edit_action_id').value;

        fetch(this.action, {
            method: 'POST', // Laravel will handle PUT via _method
            body: formData,
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        })
        .then(response => {
            if (!response.ok) {
                return response.json().then(err => { throw err; });
            }
            return response.json();
        })
        .then(data => {
            if (data.success) {
                showToast(data.message);
                closeEditActionModal();
                setTimeout(() => location.reload(), 1000);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            let msg = 'Update failed';
            if (error.errors) {
                msg = Object.values(error.errors).flat().join(', ');
            } else if (error.message) {
                msg = error.message;
            }
            showToast('Error: ' + msg);
        });
    });
});
</script>
@endsection