@extends('components.layout')

@section('content')
<div class="min-h-screen bg-gray-50 py-2 xs:py-3 sm:py-4 md:py-6 lg:py-8">
    <div class="max-w-full xs:max-w-full sm:max-w-full md:max-w-6xl mx-auto px-2 xs:px-3 sm:px-4 md:px-6 lg:px-8">
        <h2 class="text-lg xs:text-xl sm:text-2xl font-bold text-gray-800 mb-3 xs:mb-4 sm:mb-5 md:mb-6">My Leads</h2>
        
        @if($myleads->count())
            <!-- Mobile Cards View (Hidden on md and above) -->
            <div class="block md:hidden space-y-3 xs:space-y-4">
                @foreach($myleads as $index => $lead)
                    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-3 xs:p-4">
                        <div class="flex justify-between items-start mb-3">
                            <div>
                                <span class="inline-block px-2 py-1 bg-gray-100 text-gray-600 text-xs rounded-md mb-1">
                                    #{{ $index + 1 }}
                                </span>
                                <h3 class="font-semibold text-gray-900 text-sm xs:text-base">
                                    {{ $lead->client->company_name ?? 'N/A' }}
                                </h3>
                            </div>
                            <span class="px-2 py-1 rounded-full text-xs font-medium
                                @if($lead->status == 'connected') bg-green-100 text-green-700
                                @elseif($lead->status == 'missed') bg-red-100 text-red-700
                                @else bg-yellow-100 text-yellow-700 @endif">
                                {{ ucfirst(str_replace('_', ' ', $lead->status)) }}
                            </span>
                        </div>
                        
                        <div class="space-y-2 text-xs xs:text-sm text-gray-600 mb-4">
                            <div class="flex justify-between">
                                <span class="text-gray-500">Response:</span>
                                <span class="font-medium">{{ $lead->response }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-500">Follow-Up:</span>
                                <span class="font-medium">{{ $lead->follow_up_time }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-500">Next Follow-Up:</span>
                                <span class="font-medium">
                                    {{ $lead->next_follow_up ? $lead->next_follow_up->format('d M, Y') : '—' }}
                                </span>
                            </div>
                        </div>
                        
                        <!-- Action Buttons for Mobile -->
                        <div class="flex flex-wrap gap-2 xs:gap-3 pt-3 border-t border-gray-100">
                            <!-- View Button -->
                            <a href="{{ route('myleads.show', $lead->id) }}"
                               class="flex-1 min-w-[70px] xs:min-w-[80px] flex items-center justify-center gap-1.5 xs:gap-2 px-2 xs:px-3 py-1.5 xs:py-2 bg-blue-50 hover:bg-blue-100 text-blue-600 rounded-lg text-xs xs:text-sm font-medium transition-all duration-200 hover:scale-[1.02] active:scale-95">
                                <svg class="w-3 h-3 xs:w-3.5 xs:h-3.5" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M12 4.5C7 4.5 2.73 7.61 1 12c1.73 4.39 6 7.5 11 7.5s9.27-3.11 11-7.5c-1.73-4.39-6-7.5-11-7.5zM12 17c-2.76 0-5-2.24-5-5s2.24-5 5-5 5 2.24 5 5-2.24 5-5 5zm0-8c-1.66 0-3 1.34-3 3s1.34 3 3 3 3-1.34 3-3-1.34-3-3-3z"/>
                                </svg>
                                <span>View</span>
                            </a>
                            
                            <!-- Edit Button -->
                            <a href="{{ route('myleads.edit', $lead->id) }}"
                               class="flex-1 min-w-[70px] xs:min-w-[80px] flex items-center justify-center gap-1.5 xs:gap-2 px-2 xs:px-3 py-1.5 xs:py-2 bg-green-50 hover:bg-green-100 text-green-600 rounded-lg text-xs xs:text-sm font-medium transition-all duration-200 hover:scale-[1.02] active:scale-95">
                                <svg class="w-3 h-3 xs:w-3.5 xs:h-3.5" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M21.7312 2.26884C20.706 1.24372 19.044 1.24372 18.0188 2.26884L16.8617 3.42599L20.574 7.1383L21.7312 5.98116C22.7563 4.95603 22.7563 3.29397 21.7312 2.26884Z"/>
                                    <path d="M19.5133 8.19896L15.801 4.48665L7.40019 12.8875C6.78341 13.5043 6.33002 14.265 6.081 15.101L5.28122 17.7859C5.2026 18.0498 5.27494 18.3356 5.46967 18.5303C5.6644 18.725 5.95019 18.7974 6.21412 18.7188L8.89901 17.919C9.73498 17.67 10.4957 17.2166 11.1125 16.5998L19.5133 8.19896Z"/>
                                    <path d="M5.25 5.24999C3.59315 5.24999 2.25 6.59314 2.25 8.24999V18.75C2.25 20.4068 3.59315 21.75 5.25 21.75H15.75C17.4069 21.75 18.75 20.4068 18.75 18.75V13.5C18.75 13.0858 18.4142 12.75 18 12.75C17.5858 12.75 17.25 13.0858 17.25 13.5V18.75C17.25 19.5784 16.5784 20.25 15.75 20.25H5.25C4.42157 20.25 3.75 19.5784 3.75 18.75V8.24999C3.75 7.42156 4.42157 6.74999 5.25 6.74999H10.5C10.9142 6.74999 11.25 6.41421 11.25 5.99999C11.25 5.58578 10.9142 5.24999 10.5 5.24999H5.25Z"/>
                                </svg>
                                <span>Edit</span>
                            </a>
                            
                            <!-- History Button -->
                            <a href="{{ route('myleads.history', $lead->id) }}"
                               class="flex-1 min-w-[70px] xs:min-w-[80px] flex items-center justify-center gap-1.5 xs:gap-2 px-2 xs:px-3 py-1.5 xs:py-2 bg-gray-50 hover:bg-gray-100 text-gray-600 rounded-lg text-xs xs:text-sm font-medium transition-all duration-200 hover:scale-[1.02] active:scale-95">
                                <svg class="w-3 h-3 xs:w-3.5 xs:h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                <span>History</span>
                            </a>
                            
                            <!-- Communication Buttons -->
                            @if($lead->client->phone ?? 'N/A')
                            <div class="flex gap-2 w-full mt-2">
                                <!-- WhatsApp -->
                                <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $lead->client->phone ?? 'N/A') }}"
                                   target="_blank"
                                   class="flex-1 flex items-center justify-center gap-1.5 xs:gap-2 px-2 xs:px-3 py-1.5 xs:py-2 bg-green-50 hover:bg-green-100 text-green-600 rounded-lg text-xs xs:text-sm font-medium transition-all duration-200 hover:scale-[1.02] active:scale-95">
                                    <svg class="w-3 h-3 xs:w-3.5 xs:h-3.5" fill="currentColor" viewBox="0 0 24 24">
                                        <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893-.001-3.189-1.262-6.209-3.553-8.485"/>
                                    </svg>
                                    <span class="hidden xs:inline">WhatsApp</span>
                                </a>
                                
                                <!-- Call -->
                                <a href="tel:{{ $lead->client->phone ?? 'N/A' }}"
                                   class="flex-1 flex items-center justify-center gap-1.5 xs:gap-2 px-2 xs:px-3 py-1.5 xs:py-2 bg-blue-50 hover:bg-blue-100 text-blue-600 rounded-lg text-xs xs:text-sm font-medium transition-all duration-200 hover:scale-[1.02] active:scale-95">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-3 h-3 xs:w-3.5 xs:h-3.5">
                                        <path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"></path>
                                    </svg>
                                    <span class="hidden xs:inline">Call</span>
                                </a>
                                
                                <!-- SMS -->
                                <a href="sms:{{ $lead->client->phone ?? 'N/A' }}"
                                   class="flex-1 flex items-center justify-center gap-1.5 xs:gap-2 px-2 xs:px-3 py-1.5 xs:py-2 bg-purple-50 hover:bg-purple-100 text-purple-600 rounded-lg text-xs xs:text-sm font-medium transition-all duration-200 hover:scale-[1.02] active:scale-95">
                                    <svg class="w-3 h-3 xs:w-3.5 xs:h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"/>
                                    </svg>
                                    <span class="hidden xs:inline">SMS</span>
                                </a>
                            </div>
                            @endif
                            
                            @if($lead->client->email ?? 'N/A')
                            <div class="w-full mt-2">
                                <!-- Email -->
                                <a href="mailto:{{ $lead->client->email ?? 'N/A' }}"
                                   class="w-full flex items-center justify-center gap-1.5 xs:gap-2 px-2 xs:px-3 py-1.5 xs:py-2 bg-red-50 hover:bg-red-100 text-red-600 rounded-lg text-xs xs:text-sm font-medium transition-all duration-200 hover:scale-[1.02] active:scale-95">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-3 h-3 xs:w-3.5 xs:h-3.5">
                                        <rect width="20" height="16" x="2" y="4" rx="2"></rect>
                                        <path d="m22 7-8.97 5.7a1.94 1.94 0 0 1-2.06 0L2 7"></path>
                                    </svg>
                                    <span class="hidden xs:inline">Email</span>
                                </a>
                            </div>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
            
            <!-- Desktop Table View (Hidden on mobile) -->
            <div class="hidden md:block overflow-x-auto bg-white shadow rounded-lg">
                <div class="table-responsive-wrapper">
                    <div class="table-scroll-container">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-100 sticky-mobile">
                                <tr>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-600 uppercase tracking-wider whitespace-nowrap min-w-[40px]">#</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-600 uppercase tracking-wider whitespace-nowrap min-w-[150px]">Client Name</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-600 uppercase tracking-wider whitespace-nowrap min-w-[120px]">Response</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-600 uppercase tracking-wider whitespace-nowrap min-w-[100px]">Follow-Up</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-600 uppercase tracking-wider whitespace-nowrap min-w-[140px]">Next Follow-Up</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-600 uppercase tracking-wider whitespace-nowrap min-w-[100px]">Status</th>
                                    <th class="px-4 py-3 text-center text-xs font-medium text-gray-600 uppercase tracking-wider whitespace-nowrap min-w-[300px]">Action</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-100">
                                @foreach($myleads as $index => $lead)
                                    <tr class="hover:bg-gray-50 transition-colors duration-150">
                                        <td class="px-4 py-4 text-sm text-gray-700 font-medium">{{ $index + 1 }}</td>
                                        <td class="px-4 py-4 text-sm text-gray-900 font-medium">
                                            {{ $lead->client->company_name ?? 'N/A' }}
                                        </td>
                                        <td class="px-4 py-4 text-sm text-gray-700">{{ $lead->response }}</td>
                                        <td class="px-4 py-4 text-sm text-gray-700">{{ $lead->follow_up_time }}</td>
                                        <td class="px-4 py-4 text-sm text-gray-700 font-medium">
                                            {{ $lead->next_follow_up ? $lead->next_follow_up->format('d M, Y') : '—' }}
                                        </td>
                                        <td class="px-4 py-4 text-sm">
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                                @if($lead->status == 'connected') bg-green-100 text-green-800
                                                @elseif($lead->status == 'missed') bg-red-100 text-red-800
                                                @else bg-yellow-100 text-yellow-800 @endif">
                                                {{ ucfirst(str_replace('_', ' ', $lead->status)) }}
                                            </span>
                                        </td>
                                        <td class="px-4 py-4">
                                            <div class="flex items-center justify-center space-x-2">
                                                <!-- View Button -->
                                                <a href="{{ route('myleads.show', $lead->id) }}"
                                                   class="inline-flex items-center justify-center h-9 w-9 rounded-full bg-blue-50 hover:bg-blue-100 transition-all duration-200 hover:scale-110 active:scale-95 group relative"
                                                   title="View Details">
                                                    <svg class="w-4 h-4 text-blue-600" fill="currentColor" viewBox="0 0 24 24">
                                                        <path d="M12 4.5C7 4.5 2.73 7.61 1 12c1.73 4.39 6 7.5 11 7.5s9.27-3.11 11-7.5c-1.73-4.39-6-7.5-11-7.5zM12 17c-2.76 0-5-2.24-5-5s2.24-5 5-5 5 2.24 5 5-2.24 5-5 5zm0-8c-1.66 0-3 1.34-3 3s1.34 3 3 3 3-1.34 3-3-1.34-3-3-3z"/>
                                                    </svg>
                                                    <span class="absolute -top-8 left-1/2 transform -translate-x-1/2 px-2 py-1 bg-gray-800 text-white text-xs rounded opacity-0 group-hover:opacity-100 transition-opacity duration-200 pointer-events-none whitespace-nowrap">
                                                        View
                                                    </span>
                                                </a>
                                                
                                                <!-- Edit Button -->
                                                <a href="{{ route('myleads.edit', $lead->id) }}"
                                                   class="inline-flex items-center justify-center h-9 w-9 rounded-full bg-green-50 hover:bg-green-100 transition-all duration-200 hover:scale-110 active:scale-95 group relative"
                                                   title="Edit Lead">
                                                    <svg class="w-4 h-4 text-green-600" fill="currentColor" viewBox="0 0 24 24">
                                                        <path d="M21.7312 2.26884C20.706 1.24372 19.044 1.24372 18.0188 2.26884L16.8617 3.42599L20.574 7.1383L21.7312 5.98116C22.7563 4.95603 22.7563 3.29397 21.7312 2.26884Z"/>
                                                        <path d="M19.5133 8.19896L15.801 4.48665L7.40019 12.8875C6.78341 13.5043 6.33002 14.265 6.081 15.101L5.28122 17.7859C5.2026 18.0498 5.27494 18.3356 5.46967 18.5303C5.6644 18.725 5.95019 18.7974 6.21412 18.7188L8.89901 17.919C9.73498 17.67 10.4957 17.2166 11.1125 16.5998L19.5133 8.19896Z"/>
                                                        <path d="M5.25 5.24999C3.59315 5.24999 2.25 6.59314 2.25 8.24999V18.75C2.25 20.4068 3.59315 21.75 5.25 21.75H15.75C17.4069 21.75 18.75 20.4068 18.75 18.75V13.5C18.75 13.0858 18.4142 12.75 18 12.75C17.5858 12.75 17.25 13.0858 17.25 13.5V18.75C17.25 19.5784 16.5784 20.25 15.75 20.25H5.25C4.42157 20.25 3.75 19.5784 3.75 18.75V8.24999C3.75 7.42156 4.42157 6.74999 5.25 6.74999H10.5C10.9142 6.74999 11.25 6.41421 11.25 5.99999C11.25 5.58578 10.9142 5.24999 10.5 5.24999H5.25Z"/>
                                                    </svg>
                                                    <span class="absolute -top-8 left-1/2 transform -translate-x-1/2 px-2 py-1 bg-gray-800 text-white text-xs rounded opacity-0 group-hover:opacity-100 transition-opacity duration-200 pointer-events-none whitespace-nowrap">
                                                        Edit
                                                    </span>
                                                </a>
                                                
                                                <!-- History Button -->
                                                <a href="{{ route('myleads.history', $lead->id) }}"
                                                   class="inline-flex items-center justify-center h-9 w-9 rounded-full bg-gray-50 hover:bg-gray-100 transition-all duration-200 hover:scale-110 active:scale-95 group relative"
                                                   title="View History">
                                                    <svg class="w-4 h-4 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                    </svg>
                                                    <span class="absolute -top-8 left-1/2 transform -translate-x-1/2 px-2 py-1 bg-gray-800 text-white text-xs rounded opacity-0 group-hover:opacity-100 transition-opacity duration-200 pointer-events-none whitespace-nowrap">
                                                        History
                                                    </span>
                                                </a>
                                                
                                                @if($lead->client->phone ?? 'N/A')
                                                <!-- WhatsApp -->
                                                <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $lead->client->phone ?? 'N/A') }}"
                                                   target="_blank"
                                                   class="inline-flex items-center justify-center h-9 w-9 rounded-full bg-green-50 hover:bg-green-100 transition-all duration-200 hover:scale-110 active:scale-95 group relative"
                                                   title="WhatsApp">
                                                    <svg class="w-4 h-4 text-green-600" fill="currentColor" viewBox="0 0 24 24">
                                                        <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893-.001-3.189-1.262-6.209-3.553-8.485"/>
                                                    </svg>
                                                    <span class="absolute -top-8 left-1/2 transform -translate-x-1/2 px-2 py-1 bg-gray-800 text-white text-xs rounded opacity-0 group-hover:opacity-100 transition-opacity duration-200 pointer-events-none whitespace-nowrap">
                                                        WhatsApp
                                                    </span>
                                                </a>
                                                
                                                <!-- Call -->
                                                <a href="tel:{{ $lead->client->phone ?? 'N/A' }}"
                                                   class="inline-flex items-center justify-center h-9 w-9 rounded-full bg-blue-50 hover:bg-blue-100 transition-all duration-200 hover:scale-110 active:scale-95 group relative"
                                                   title="Call">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-4 h-4 text-blue-600">
                                                        <path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"></path>
                                                    </svg>
                                                    <span class="absolute -top-8 left-1/2 transform -translate-x-1/2 px-2 py-1 bg-gray-800 text-white text-xs rounded opacity-0 group-hover:opacity-100 transition-opacity duration-200 pointer-events-none whitespace-nowrap">
                                                        Call
                                                    </span>
                                                </a>
                                                
                                                <!-- SMS -->
                                                <a href="sms:{{ $lead->client->phone ?? 'N/A' }}"
                                                   class="inline-flex items-center justify-center h-9 w-9 rounded-full bg-purple-50 hover:bg-purple-100 transition-all duration-200 hover:scale-110 active:scale-95 group relative"
                                                   title="SMS">
                                                    <svg class="w-4 h-4 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"/>
                                                    </svg>
                                                    <span class="absolute -top-8 left-1/2 transform -translate-x-1/2 px-2 py-1 bg-gray-800 text-white text-xs rounded opacity-0 group-hover:opacity-100 transition-opacity duration-200 pointer-events-none whitespace-nowrap">
                                                        SMS
                                                    </span>
                                                </a>
                                                @endif
                                                
                                                @if($lead->client->email ?? 'N/A')
                                                <!-- Email -->
                                                <a href="mailto:{{ $lead->client->email ?? 'N/A' }}"
                                                   class="inline-flex items-center justify-center h-9 w-9 rounded-full bg-red-50 hover:bg-red-100 transition-all duration-200 hover:scale-110 active:scale-95 group relative"
                                                   title="Email">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-4 h-4 text-red-600">
                                                        <rect width="20" height="16" x="2" y="4" rx="2"></rect>
                                                        <path d="m22 7-8.97 5.7a1.94 1.94 0 0 1-2.06 0L2 7"></path>
                                                    </svg>
                                                    <span class="absolute -top-8 left-1/2 transform -translate-x-1/2 px-2 py-1 bg-gray-800 text-white text-xs rounded opacity-0 group-hover:opacity-100 transition-opacity duration-200 pointer-events-none whitespace-nowrap">
                                                        Email
                                                    </span>
                                                </a>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            
            <!-- Tablet Optimized View (Hidden on mobile and desktop) -->
            <div class="hidden sm:block md:hidden overflow-x-auto bg-white shadow rounded-lg">
                <div class="table-responsive-wrapper">
                    <div class="table-scroll-container">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-100">
                                <tr>
                                    <th class="px-3 py-2.5 text-left text-xs font-medium text-gray-600 uppercase tracking-wider whitespace-nowrap min-w-[40px]">#</th>
                                    <th class="px-3 py-2.5 text-left text-xs font-medium text-gray-600 uppercase tracking-wider whitespace-nowrap min-w-[120px]">Client</th>
                                    <th class="px-3 py-2.5 text-left text-xs font-medium text-gray-600 uppercase tracking-wider whitespace-nowrap min-w-[100px]">Response</th>
                                    <th class="px-3 py-2.5 text-left text-xs font-medium text-gray-600 uppercase tracking-wider whitespace-nowrap min-w-[120px]">Next Follow-Up</th>
                                    <th class="px-3 py-2.5 text-left text-xs font-medium text-gray-600 uppercase tracking-wider whitespace-nowrap min-w-[80px]">Status</th>
                                    <th class="px-3 py-2.5 text-center text-xs font-medium text-gray-600 uppercase tracking-wider whitespace-nowrap min-w-[200px]">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-100">
                                @foreach($myleads as $index => $lead)
                                    <tr>
                                        <td class="px-3 py-3 text-sm text-gray-700 font-medium">{{ $index + 1 }}</td>
                                        <td class="px-3 py-3 text-sm text-gray-900 font-medium truncate max-w-[120px]" title="{{ $lead->client->company_name ?? 'N/A' }}">
                                            {{ $lead->client->company_name ?? 'N/A' }}
                                        </td>
                                        <td class="px-3 py-3 text-sm text-gray-700 truncate max-w-[100px]" title="{{ $lead->response }}">
                                            {{ $lead->response }}
                                        </td>
                                        <td class="px-3 py-3 text-sm text-gray-700 font-medium">
                                            {{ $lead->next_follow_up ? $lead->next_follow_up->format('d M') : '—' }}
                                        </td>
                                        <td class="px-3 py-3 text-sm">
                                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium
                                                @if($lead->status == 'connected') bg-green-100 text-green-800
                                                @elseif($lead->status == 'missed') bg-red-100 text-red-800
                                                @else bg-yellow-100 text-yellow-800 @endif">
                                                {{ strtoupper(substr($lead->status, 0, 1)) }}
                                            </span>
                                        </td>
                                        <td class="px-3 py-3">
                                            <div class="flex items-center justify-center space-x-1.5">
                                                <!-- View Button -->
                                                <a href="{{ route('myleads.show', $lead->id) }}"
                                                   class="inline-flex items-center justify-center h-8 w-8 rounded-full bg-blue-50 hover:bg-blue-100 transition-all duration-200 hover:scale-110"
                                                   title="View">
                                                    <svg class="w-3.5 h-3.5 text-blue-600" fill="currentColor" viewBox="0 0 24 24">
                                                        <path d="M12 4.5C7 4.5 2.73 7.61 1 12c1.73 4.39 6 7.5 11 7.5s9.27-3.11 11-7.5c-1.73-4.39-6-7.5-11-7.5zM12 17c-2.76 0-5-2.24-5-5s2.24-5 5-5 5 2.24 5 5-2.24 5-5 5zm0-8c-1.66 0-3 1.34-3 3s1.34 3 3 3 3-1.34 3-3-1.34-3-3-3z"/>
                                                    </svg>
                                                </a>
                                                
                                                <!-- Edit Button -->
                                                <a href="{{ route('myleads.edit', $lead->id) }}"
                                                   class="inline-flex items-center justify-center h-8 w-8 rounded-full bg-green-50 hover:bg-green-100 transition-all duration-200 hover:scale-110"
                                                   title="Edit">
                                                    <svg class="w-3.5 h-3.5 text-green-600" fill="currentColor" viewBox="0 0 24 24">
                                                        <path d="M21.7312 2.26884C20.706 1.24372 19.044 1.24372 18.0188 2.26884L16.8617 3.42599L20.574 7.1383L21.7312 5.98116C22.7563 4.95603 22.7563 3.29397 21.7312 2.26884Z"/>
                                                        <path d="M19.5133 8.19896L15.801 4.48665L7.40019 12.8875C6.78341 13.5043 6.33002 14.265 6.081 15.101L5.28122 17.7859C5.2026 18.0498 5.27494 18.3356 5.46967 18.5303C5.6644 18.725 5.95019 18.7974 6.21412 18.7188L8.89901 17.919C9.73498 17.67 10.4957 17.2166 11.1125 16.5998L19.5133 8.19896Z"/>
                                                        <path d="M5.25 5.24999C3.59315 5.24999 2.25 6.59314 2.25 8.24999V18.75C2.25 20.4068 3.59315 21.75 5.25 21.75H15.75C17.4069 21.75 18.75 20.4068 18.75 18.75V13.5C18.75 13.0858 18.4142 12.75 18 12.75C17.5858 12.75 17.25 13.0858 17.25 13.5V18.75C17.25 19.5784 16.5784 20.25 15.75 20.25H5.25C4.42157 20.25 3.75 19.5784 3.75 18.75V8.24999C3.75 7.42156 4.42157 6.74999 5.25 6.74999H10.5C10.9142 6.74999 11.25 6.41421 11.25 5.99999C11.25 5.58578 10.9142 5.24999 10.5 5.24999H5.25Z"/>
                                                    </svg>
                                                </a>
                                                
                                                <!-- WhatsApp -->
                                                @if($lead->client->phone ?? 'N/A')
                                                <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $lead->client->phone ?? 'N/A') }}"
                                                   target="_blank"
                                                   class="inline-flex items-center justify-center h-8 w-8 rounded-full bg-green-50 hover:bg-green-100 transition-all duration-200 hover:scale-110"
                                                   title="WhatsApp">
                                                    <svg class="w-3.5 h-3.5 text-green-600" fill="currentColor" viewBox="0 0 24 24">
                                                        <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893-.001-3.189-1.262-6.209-3.553-8.485"/>
                                                    </svg>
                                                </a>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            
        @else
            <div class="bg-white rounded-lg shadow-sm p-6 text-center">
                <svg class="w-16 h-16 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                </svg>
                <p class="text-gray-600 text-lg font-medium mb-2">No Leads Found</p>
                <p class="text-gray-500 text-sm">You haven't taken action on any leads yet.</p>
            </div>
        @endif
    </div>
</div>

<style>
    /* Mobile-first responsive styles */
    @media (max-width: 639px) {
        /* Card view for mobile */
        .mobile-card {
            transition: transform 0.2s ease;
        }
        
        .mobile-card:hover {
            transform: translateY(-2px);
        }
    }
    
    /* Tablet styles */
    @media (min-width: 640px) and (max-width: 767px) {
        /* Tablet optimized table */
        .table-scroll-container {
            -webkit-overflow-scrolling: touch;
            scrollbar-width: none;
        }
        
        .table-scroll-container::-webkit-scrollbar {
            display: none;
        }
    }
    
    /* Desktop styles */
    @media (min-width: 768px) {
        /* Desktop table */
        .table-responsive-wrapper {
            position: relative;
            overflow: hidden;
        }
        
        .table-scroll-container {
            overflow-x: auto;
            max-width: 100%;
        }
        
        .sticky-mobile {
            position: sticky;
            top: 0;
            z-index: 10;
        }
    }
    
    /* Very small screens (≤375px) */
    @media (max-width: 375px) {
        .text-xs {
            font-size: 0.7rem !important;
        }
        
        .px-2 {
            padding-left: 0.5rem !important;
            padding-right: 0.5rem !important;
        }
        
        .py-1\.5 {
            padding-top: 0.375rem !important;
            padding-bottom: 0.375rem !important;
        }
    }
    
    /* Button hover effects */
    .hover-scale {
        transition: transform 0.2s ease;
    }
    
    .hover-scale:hover {
        transform: scale(1.05);
    }
    
    /* Smooth transitions */
    .transition-all {
        transition-property: all;
        transition-timing-function: cubic-bezier(0.4, 0, 0.2, 1);
    }
    
    /* Card shadow */
    .shadow-sm {
        box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.1), 0 1px 2px 0 rgba(0, 0, 0, 0.06);
    }
    
    /* Better scrolling on mobile */
    html, body {
        -webkit-overflow-scrolling: touch;
    }
    
    /* Prevent content shift */
    .content-stable {
        overflow-anchor: none;
    }
    
    /* Responsive table fixes */
    @media (max-width: 767px) {
        table {
            min-width: 100%;
            table-layout: fixed;
        }
        
        td, th {
            padding: 0.5rem 0.25rem !important;
            vertical-align: middle;
        }
    }
</style>

<script>
    // Mobile optimization script
    document.addEventListener('DOMContentLoaded', function() {
        // Handle touch events for better mobile UX
        const buttons = document.querySelectorAll('a');
        buttons.forEach(button => {
            button.addEventListener('touchstart', function() {
                this.classList.add('active:scale-95');
            });
            
            button.addEventListener('touchend', function() {
                setTimeout(() => {
                    this.classList.remove('active:scale-95');
                }, 150);
            });
        });
        
        // Fix for iOS scroll issues
        if (/iPhone|iPad|iPod/.test(navigator.userAgent)) {
            // Prevent elastic scroll
            document.body.style.overscrollBehavior = 'contain';
            
            // Fix 100vh issue
            function setFullHeight() {
                const vh = window.innerHeight * 0.01;
                document.documentElement.style.setProperty('--vh', `${vh}px`);
            }
            
            setFullHeight();
            window.addEventListener('resize', setFullHeight);
            window.addEventListener('orientationchange', setFullHeight);
        }
        
        // Handle tablet view
        function checkScreenSize() {
            const screenWidth = window.innerWidth;
            const isTablet = screenWidth >= 640 && screenWidth <= 767;
            
            if (isTablet) {
                // Add specific tablet optimizations
                document.body.classList.add('tablet-view');
            } else {
                document.body.classList.remove('tablet-view');
            }
        }
        
        checkScreenSize();
        window.addEventListener('resize', checkScreenSize);
        
        // Smooth scrolling for anchor links
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
                const targetId = this.getAttribute('href');
                if (targetId === '#') return;
                
                const targetElement = document.querySelector(targetId);
                if (targetElement) {
                    window.scrollTo({
                        top: targetElement.offsetTop - 80,
                        behavior: 'smooth'
                    });
                }
            });
        });
        
        // Add loading state for buttons
        const actionButtons = document.querySelectorAll('a[href*="myleads"]');
        actionButtons.forEach(button => {
            button.addEventListener('click', function() {
                // Add loading indicator
                const originalHTML = this.innerHTML;
                this.innerHTML = `
                    <svg class="animate-spin h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                `;
                this.classList.add('pointer-events-none', 'opacity-75');
                
                // Restore original after 2 seconds (in case page doesn't load)
                setTimeout(() => {
                    this.innerHTML = originalHTML;
                    this.classList.remove('pointer-events-none', 'opacity-75');
                }, 2000);
            });
        });
    });
    
    // Handle back button and page state
    window.addEventListener('pageshow', function(event) {
        if (event.persisted) {
            // Page was restored from bfcache
            window.scrollTo(0, 0);
        }
    });
</script>
@endsection