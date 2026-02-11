@extends('superadmin.layout.app')

@section('title', 'Active Subscriptions')

@section('content')
<div class="flex flex-col md:flex-row md:items-center justify-between mb-8">
    <div>
        <h1 class="text-2xl font-bold text-gray-800">Active Subscriptions</h1>
        <p class="text-gray-600">Manage all active paid subscriptions</p>
    </div>
</div>

<!-- Active Subscriptions Stats -->
<div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
    <div class="bg-gradient-to-r from-green-500 to-emerald-600 rounded-xl p-6 text-white">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-green-100">Active Subscriptions</p>
                <h3 class="text-2xl font-bold mt-1">{{ $subscriptionStats['total'] }}</h3>
            </div>
            <i class="fas fa-id-card text-2xl opacity-80"></i>
        </div>
    </div>
    
    <div class="bg-white rounded-xl p-6 shadow-sm border border-gray-100">
        <div class="flex items-center">
            <div class="w-12 h-12 rounded-lg bg-blue-100 flex items-center justify-center mr-4">
                <i class="fas fa-rupee-sign text-blue-600 text-xl"></i>
            </div>
            <div>
                <p class="text-gray-500 text-sm">Monthly Recurring Revenue</p>
                <h3 class="text-2xl font-bold text-gray-800">₹{{ number_format($subscriptionStats['mrr']) }}</h3>
            </div>
        </div>
    </div>
    
    <div class="bg-white rounded-xl p-6 shadow-sm border border-gray-100">
        <div class="flex items-center">
            <div class="w-12 h-12 rounded-lg bg-purple-100 flex items-center justify-center mr-4">
                <i class="fas fa-calendar-alt text-purple-600 text-xl"></i>
            </div>
            <div>
                <p class="text-gray-500 text-sm">Avg. Subscription Length</p>
                <h3 class="text-2xl font-bold text-gray-800">{{ round($subscriptionStats['avg_length']) }} months</h3>
            </div>
        </div>
    </div>
</div>

<!-- Active Subscriptions Table -->
<div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead class="bg-gray-50">
                <tr>
                    <th class="py-3 px-6 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Customer</th>
                    <th class="py-3 px-6 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Subscription Start</th>
                    <th class="py-3 px-6 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Expiry Date</th>
                    <th class="py-3 px-6 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Days Remaining</th>
                    <th class="py-3 px-6 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
@foreach($activeSubscriptions as $company)

@php
    // Assuming 1 year paid validity (change later if needed)
    $startDate = $company->updated_at;
    $endDate = $company->updated_at->copy()->addYear();
    $daysRemaining = now()->diffInDays($endDate, false);
@endphp

<tr>
    <td class="py-3 px-6">
        <div class="flex items-center">
            <img src="{{ $company->logo_url }}" class="w-8 h-8 rounded-full mr-3">
            <div>
                <p class="font-medium text-gray-800">{{ $company->name }}</p>
                <p class="text-sm text-gray-500">{{ $company->email }}</p>
            </div>
        </div>
    </td>

    <td class="py-3 px-6">
        {{ $startDate->format('d M Y') }}
    </td>

    <td class="py-3 px-6">
        {{ $endDate->format('d M Y') }}
    </td>

   <td class="py-3 px-6">
    @php
        // Paid subscription end date (1 year from start – adjust if needed)
        $subscriptionEnd = $company->updated_at->copy()->addYear();

        $diff = now()->diff($subscriptionEnd, false);
        $days = $diff->days;
        $hours = $diff->h;
    @endphp

    @if(now()->lt($subscriptionEnd))
        <span class="font-medium
            {{ $days > 30 ? 'text-green-600' : ($days > 0 ? 'text-yellow-600' : 'text-red-600') }}">
            Expires in {{ $days }} days {{ $hours }} hours
        </span>
    @else
        <span class="font-medium text-red-600">
            Expired
        </span>
    @endif
</td>


    <td class="py-3 px-6">
        <span class="inline-flex items-center px-3 py-1 rounded-full
            bg-green-100 text-green-700 text-sm font-semibold">
            Active
        </span>
    </td>
</tr>

@endforeach
</tbody>

        </table>
    </div>
    <div class="px-6 py-4 border-t border-gray-200">
        {{ $activeSubscriptions->links() }}
    </div>
</div>
@endsection