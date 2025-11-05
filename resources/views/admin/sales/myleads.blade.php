@extends('components.layout')

@section('content')
<div class="min-h-screen bg-gray-50 py-8">
    <div class="max-w-6xl mx-auto px-4">
        <h2 class="text-2xl font-bold text-gray-800 mb-6">My Leads</h2>

        @if($myleads->count())
            <div class="overflow-x-auto bg-white shadow rounded-lg">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-100">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-600 uppercase">#</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-600 uppercase">Client Name</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-600 uppercase">Response</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-600 uppercase">Follow-Up</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-600 uppercase">Next Follow-Up</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-600 uppercase">Status</th>
                            <th class="px-6 py-3 text-center text-xs font-medium text-gray-600 uppercase">Action</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-100">
                        @foreach($myleads as $index => $lead)
                            <tr>
                                <td class="px-6 py-4 text-sm text-gray-700">{{ $index + 1 }}</td>
                                <td class="px-6 py-4 text-sm text-gray-900">
                                    {{ $lead->client->company_name ?? 'N/A' }}
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-700">{{ $lead->response }}</td>
                                <td class="px-6 py-4 text-sm text-gray-700">{{ $lead->follow_up_time }}</td>
                                <td class="px-6 py-4 text-sm text-gray-700">
                                    {{ $lead->next_follow_up ? $lead->next_follow_up->format('d M, Y') : '—' }}
                                </td>
                                <td class="px-6 py-4 text-sm">
                                    <span class="px-2 py-1 rounded-full text-xs font-medium 
                                        @if($lead->status == 'connected') bg-green-100 text-green-700
                                        @elseif($lead->status == 'missed') bg-red-100 text-red-700
                                        @else bg-yellow-100 text-yellow-700 @endif">
                                        {{ ucfirst(str_replace('_', ' ', $lead->status)) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-600">
    <a href="{{ route('myleads.show', $lead->id) }}" 
       class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg transition duration-200">
        View
    </a> &nbsp;
    <a href="{{ route('myleads.edit', $lead->id) }}" 
       class="px-4 py-2 bg-green-600 hover:bg-green-700 text-white font-medium rounded-lg transition duration-200">
        Edit 
    </a>

&nbsp;
  
      @if( $lead->client->phone ?? 'N/A' )
                        <!-- WhatsApp -->
                        <div class="relative group inline-block">
                            <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '',  $lead->client->phone ?? 'N/A') }}" 
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
                            <a href="tel:{{  $lead->client->phone ?? 'N/A' }}" 
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



                        @if( $lead->client->email ?? 'N/A')
                        <!-- Email -->
                        <div class="relative group inline-block">
                            <a href="mailto:{{ $lead->client->email ?? 'N/A' }}" 
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
                        @if( $lead->client->phone ?? 'N/A')
                        <div class="relative group inline-block">
                            <a href="sms:{{  $lead->client->phone ?? 'N/A' }}" 
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
















    
</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <p class="text-gray-600">You haven’t taken action on any leads yet.</p>
        @endif
    </div>
</div>
@endsection
