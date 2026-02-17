@extends('superadmin.layout.app')

@section('title', 'Trials')

@section('content')
<div class="flex flex-col md:flex-row md:items-center justify-between mb-8">
    <div>
        <h1 class="text-2xl font-bold text-gray-800">Trial Management</h1>
        <p class="text-gray-600">Manage all 30-day trial users and conversions</p>
    </div>
</div>

<!-- Trial Stats -->
<div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
    <div class="bg-white rounded-xl p-6 shadow-sm border border-gray-100">
        <div class="flex items-center">
            <div class="w-12 h-12 rounded-lg bg-blue-100 flex items-center justify-center mr-4">
                <i class="fas fa-user-clock text-blue-600 text-xl"></i>
            </div>
            <div>
                <p class="text-gray-500 text-sm">Active Trials</p>
                <h3 class="text-2xl font-bold text-gray-800">{{ $trialsStats['active'] }}</h3>
            </div>
        </div>
    </div>
    
    <div class="bg-white rounded-xl p-6 shadow-sm border border-gray-100">
        <div class="flex items-center">
            <div class="w-12 h-12 rounded-lg bg-green-100 flex items-center justify-center mr-4">
                <i class="fas fa-exchange-alt text-green-600 text-xl"></i>
            </div>
            <div>
                <p class="text-gray-500 text-sm">Converted This Month</p>
                <h3 class="text-2xl font-bold text-gray-800">{{ $trialsStats['converted'] }}</h3>
            </div>
        </div>
    </div>
    
    <div class="bg-white rounded-xl p-6 shadow-sm border border-gray-100">
        <div class="flex items-center">
            <div class="w-12 h-12 rounded-lg bg-red-100 flex items-center justify-center mr-4">
                <i class="fas fa-times-circle text-red-600 text-xl"></i>
            </div>
            <div>
                <p class="text-gray-500 text-sm">Expired This Month</p>
                <h3 class="text-2xl font-bold text-gray-800">{{ $trialsStats['expired'] }}</h3>
            </div>
        </div>
    </div>
</div>

<!-- Trial Users Table -->
<div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead class="bg-gray-50">
                <tr>
                    <th class="py-3 px-6 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Customer</th>
                    <th class="py-3 px-6 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Trial Start</th>
                    <th class="py-3 px-6 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Trial End</th>
                    <th class="py-3 px-6 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Days Remaining</th>
                    <th class="py-3 px-6 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Active Status</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
               @foreach($activeTrials as $company)

               @php
    $daysRemaining = now()->diffInDays($company->trial_ends_at, false);
@endphp

                <tr>
                    <td class="py-3 px-6">
    <div class="flex items-center">
        <!-- <img src="{{ $company->logo_url }}" class="w-8 h-8 rounded-full mr-3"> -->
        <div>
            <p class="font-medium text-gray-800">{{ $company->name }}</p>
            <p class="text-sm text-gray-500">{{ $company->email }}</p>
        </div>
    </div>
</td>

<td class="py-3 px-6">
    {{ $company->created_at->format('d M Y') }}
</td>

<td class="py-3 px-6">
    {{ $company->trial_ends_at->format('d M Y') }}
</td>

<td class="py-3 px-6">
    @if($company && !$company->is_paid && now()->lt($company->trial_ends_at))

        @php
            $diff = now()->diff($company->trial_ends_at);
            $days = $diff->days;
            $hours = $diff->h;
        @endphp

        <span class="font-medium
            {{ $days > 7 ? 'text-green-600' : ($days > 0 ? 'text-yellow-600' : 'text-red-600') }}">
            Trial ends in {{ $days }} days {{ $hours }} hours
        </span>

    @else
        <span class="font-medium text-red-600">
            Expired
        </span>
    @endif
</td>


<td class="py-3 px-6">
    @if(now()->lt($company->trial_ends_at))
        <span class="inline-flex items-center px-3 py-1 rounded-full
            bg-green-100 text-green-700 text-sm font-semibold">
            Trial Active
        </span>
    @else
        <span class="inline-flex items-center px-3 py-1 rounded-full
            bg-red-100 text-red-700 text-sm font-semibold">
            Expired
        </span>
    @endif
</td>


                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <div class="px-6 py-4 border-t border-gray-200">
        {{ $activeTrials->links() }}
    </div>
</div>
@endsection