@extends('superadmin.layout.app')

@section('title', 'Revenue Report')

@section('content')
<div class="flex flex-col md:flex-row md:items-center justify-between mb-8">
    <div>
        <h1 class="text-2xl font-bold text-gray-800">Revenue Report</h1>
        <p class="text-gray-600">Track your revenue, conversions, and customer analytics</p>
    </div>
</div>

<!-- Revenue Stats -->
<div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
    <div class="bg-gradient-to-r from-blue-500 to-indigo-600 rounded-xl p-6 text-white">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-blue-100">Total Yearly Revenue</p>
                <h3 class="text-2xl font-bold mt-1">₹{{ number_format($yearlyRevenue) }}</h3>
            </div>
            <i class="fas fa-rupee-sign text-2xl opacity-80"></i>
        </div>
    </div>
    
    <div class="bg-white rounded-xl p-6 shadow-sm border border-gray-100">
        <div class="flex items-center">
            <div class="w-12 h-12 rounded-lg bg-green-100 flex items-center justify-center mr-4">
                <i class="fas fa-chart-line text-green-600 text-xl"></i>
            </div>
            <div>
                <p class="text-gray-500 text-sm">Revenue This Month</p>
                <h3 class="text-2xl font-bold text-gray-800">₹{{ number_format($monthlyRevenue) }}</h3>
            </div>
        </div>
    </div>
    
    <div class="bg-white rounded-xl p-6 shadow-sm border border-gray-100">
        <div class="flex items-center">
            <div class="w-12 h-12 rounded-lg bg-purple-100 flex items-center justify-center mr-4">
                <i class="fas fa-percentage text-purple-600 text-xl"></i>
            </div>
            <div>
                <p class="text-gray-500 text-sm">Conversion Rate</p>
                <h3 class="text-2xl font-bold text-gray-800">{{ round($conversionRate) }}%</h3>
            </div>
        </div>
    </div>
</div>

<!-- Charts would go here -->
<div class="bg-white rounded-xl p-6 shadow-sm border border-gray-100">
    <h3 class="text-lg font-bold text-gray-800 mb-4">Revenue Analytics</h3>
    <p class="text-gray-600">Revenue and customer statistics</p>
    <!-- Chart implementation would be added here -->
</div>
@endsection