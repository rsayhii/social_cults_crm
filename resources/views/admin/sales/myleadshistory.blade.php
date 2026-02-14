@extends('components.layout')
@section('content')
    <div class="min-h-screen bg-gray-50 py-8">
        <div class="max-w-6xl mx-auto px-4">
            <div class="flex justify-between items-center mb-6">
                <h2 class="text-2xl font-bold text-gray-800">Lead History</h2>
                <a href="{{ route('myleads') }}" class="text-blue-600 hover:underline">← Back to Leads</a>
            </div>
            <div class="bg-white rounded-xl shadow-2xl max-w-4xl p-6 mx-auto">
                @if($lead->histories->count())
                    <div class="space-y-4">
                        @foreach($lead->histories()->latest()->get() as $history)
                            <div class="border-l-4 border-blue-500 pl-4">
                                <div class="flex justify-between items-start mb-2">
                                    <div>
                                        <p class="text-sm text-gray-600">{{ $history->created_at->format('d M Y, H:i') }}</p>
                                        @if($history->user)
                                            <p class="text-xs text-gray-500">By {{ $history->user->name }}</p>
                                        @endif
                                    </div>
                                </div>
                                @if($history->changes)
                                    @php
                                        // Helper function to format datetime values in history
                                        function formatHistoryValue($field, $value)
                                        {
                                            if ($value === 'N/A' || $value === null || $value === '') {
                                                return 'N/A';
                                            }

                                            try {
                                                // Format date fields (next_follow_up)
                                                if ($field === 'next_follow_up') {
                                                    return \Carbon\Carbon::parse($value)->format('d M Y');
                                                }
                                                // Format time fields (follow_up_time)
                                                if ($field === 'follow_up_time') {
                                                    return \Carbon\Carbon::parse($value)->format('h:i A');
                                                }
                                            } catch (\Exception $e) {
                                                // Return original value if parsing fails
                                            }

                                            return $value;
                                        }
                                    @endphp
                                    <div class="bg-gray-50 p-3 rounded">
                                        <h4 class="font-medium text-sm mb-2">Changes:</h4>
                                        <ul class="text-sm space-y-1">
                                            @foreach(json_decode($history->changes, true) as $field => $change)
                                                <li>
                                                    <strong>{{ ucwords(str_replace('_', ' ', $field)) }}:</strong>
                                                    {{ formatHistoryValue($field, $change['old'] ?? 'N/A') }} →
                                                    {{ formatHistoryValue($field, $change['new'] ?? 'N/A') }}
                                                </li>
                                            @endforeach
                                        </ul>
                                    </div>
                                @endif
                                @if($history->response)
                                    <div class="bg-green-50 p-3 rounded mt-2">
                                        <h4 class="font-medium text-sm mb-1">Response:</h4>
                                        <p class="text-sm text-gray-700">{{ $history->response }}</p>
                                    </div>
                                @endif
                            </div>
                        @endforeach
                    </div>
                @else
                    <p class="text-gray-600 text-center py-8">No history available for this lead yet.</p>
                @endif
            </div>
        </div>
    </div>
@endsection