@extends('superadmin.layout.app')

@section('title', 'Dashboard')

@section('content')
<div class="flex flex-col md:flex-row md:items-center justify-between mb-8">
    <div>
        <h1 class="text-2xl font-bold text-gray-800">Dashboard Overview</h1>
        <p class="text-gray-600">Manage your CRM customers, subscriptions, and revenue</p>
    </div>
</div>

<!-- Summary Cards -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
    <div class="bg-white rounded-xl p-6 shadow-sm border border-gray-100 card-hover">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-gray-500 text-sm">Total Customers</p>
                <h3 class="text-2xl font-bold text-gray-800 mt-1">{{ $totalCustomers ?? 0 }}</h3>
            </div>
            <div class="w-12 h-12 rounded-lg bg-blue-100 flex items-center justify-center">
                <i class="fas fa-users text-blue-600 text-xl"></i>
            </div>
        </div>
    </div>
    
    <div class="bg-white rounded-xl p-6 shadow-sm border border-gray-100 card-hover">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-gray-500 text-sm">Active Trials</p>
                <h3 class="text-2xl font-bold text-gray-800 mt-1">{{ $activeTrials ?? 0 }}</h3>
            </div>
            <div class="w-12 h-12 rounded-lg bg-purple-100 flex items-center justify-center">
                <i class="fas fa-clock text-purple-600 text-xl"></i>
            </div>
        </div>
    </div>
    
    <div class="bg-white rounded-xl p-6 shadow-sm border border-gray-100 card-hover">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-gray-500 text-sm">Paid Members</p>
                <h3 class="text-2xl font-bold text-gray-800 mt-1">{{ $paidMembers ?? 0 }}</h3>
            </div>
            <div class="w-12 h-12 rounded-lg bg-green-100 flex items-center justify-center">
                <i class="fas fa-id-card text-green-600 text-xl"></i>
            </div>
        </div>
    </div>
    
    <div class="bg-white rounded-xl p-6 shadow-sm border border-gray-100 card-hover">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-gray-500 text-sm">Total Revenue</p>
                <h3 class="text-2xl font-bold text-gray-800 mt-1">₹{{ number_format($totalRevenue ?? 0) }}</h3>
            </div>
            <div class="w-12 h-12 rounded-lg bg-indigo-100 flex items-center justify-center">
                <i class="fas fa-rupee-sign text-indigo-600 text-xl"></i>
            </div>
        </div>
    </div>
</div>

<!-- Revenue Graph -->
<div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 mb-8">
    <div class="flex items-center justify-between mb-6">
        <div>
            <h2 class="text-lg font-semibold text-gray-800">Revenue Overview</h2>
            <p class="text-gray-600 text-sm">Monthly revenue comparison</p>
        </div>
        <div class="flex space-x-2">
            <button id="thisYearBtn" class="px-3 py-1 text-sm bg-blue-100 text-blue-600 rounded-lg">This Year</button>
            <button id="lastYearBtn" class="px-3 py-1 text-sm text-gray-600 hover:bg-gray-100 rounded-lg">Last Year</button>
        </div>
    </div>
    <div class="h-64">
        <canvas id="revenueChart"></canvas>
    </div>
</div>

<!-- Customer Growth Graph -->
<div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 mb-8">
    <div class="flex items-center justify-between mb-6">
        <div>
            <h2 class="text-lg font-semibold text-gray-800">Customer Growth</h2>
            <p class="text-gray-600 text-sm">New customers over time</p>
        </div>
        <div class="flex space-x-2">
            <button id="monthlyBtn" class="px-3 py-1 text-sm bg-blue-100 text-blue-600 rounded-lg">Monthly</button>
            <button id="quarterlyBtn" class="px-3 py-1 text-sm text-gray-600 hover:bg-gray-100 rounded-lg">Quarterly</button>
        </div>
    </div>
    <div class="h-64">
        <canvas id="customerChart"></canvas>
    </div>
</div>

<!-- Subscription Distribution & Recent Customers -->
<div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
    <!-- Subscription Distribution -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
        <div class="flex items-center justify-between mb-6">
            <div>
                <h2 class="text-lg font-semibold text-gray-800">Customer Distribution</h2>
                <p class="text-gray-600 text-sm">Paid vs Trial vs Other</p>
            </div>
        </div>
        <div class="h-64">
            <canvas id="customerPieChart"></canvas>
        </div>
        <div class="grid grid-cols-3 gap-4 mt-6">
            <div class="text-center">
                <div class="w-8 h-8 rounded-lg bg-green-100 flex items-center justify-center mx-auto mb-2">
                    <i class="fas fa-id-card text-green-600"></i>
                </div>
                <p class="text-sm text-gray-600">Paid</p>
                <p class="font-semibold text-gray-800">{{ $paidMembers ?? 0 }}</p>
                <p class="text-xs text-gray-500">
                    @if($totalCustomers > 0)
                    {{ round(($paidMembers / $totalCustomers) * 100, 1) }}%
                    @else
                    0%
                    @endif
                </p>
            </div>
            <div class="text-center">
                <div class="w-8 h-8 rounded-lg bg-purple-100 flex items-center justify-center mx-auto mb-2">
                    <i class="fas fa-clock text-purple-600"></i>
                </div>
                <p class="text-sm text-gray-600">Trial</p>
                <p class="font-semibold text-gray-800">{{ $activeTrials ?? 0 }}</p>
                <p class="text-xs text-gray-500">
                    @if($totalCustomers > 0)
                    {{ round(($activeTrials / $totalCustomers) * 100, 1) }}%
                    @else
                    0%
                    @endif
                </p>
            </div>
            <div class="text-center">
                <div class="w-8 h-8 rounded-lg bg-gray-100 flex items-center justify-center mx-auto mb-2">
                    <i class="fas fa-user text-gray-600"></i>
                </div>
                <p class="text-sm text-gray-600">Other</p>
                @php
                    $otherCustomers = $totalCustomers - $paidMembers - $activeTrials;
                @endphp
                <p class="font-semibold text-gray-800">{{ $otherCustomers }}</p>
                <p class="text-xs text-gray-500">
                    @if($totalCustomers > 0)
                    {{ round(($otherCustomers / $totalCustomers) * 100, 1) }}%
                    @else
                    0%
                    @endif
                </p>
            </div>
        </div>
    </div>

    <!-- Recent Customers -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
        <div class="flex items-center justify-between mb-6">
            <div>
                <h2 class="text-lg font-semibold text-gray-800">Recent Customers</h2>
                <p class="text-gray-600 text-sm">Latest customer registrations</p>
            </div>
            <a href="{{ route('superadmin.customers.index') }}" class="text-sm text-blue-600 hover:text-blue-800">View All</a>
        </div>
        <div class="space-y-4">
            @forelse($recentCustomers as $customer)
            <div class="flex items-center justify-between p-3 hover:bg-gray-50 rounded-lg">
                <div class="flex items-center">
                    <div class="w-10 h-10 rounded-full bg-gradient-to-r from-blue-500 to-purple-500 mr-3"></div>
                    <div>
                        <p class="font-medium text-gray-800">{{ $customer->name }}</p>
                        <p class="text-sm text-gray-500">{{ $customer->user->name ?? 'No Admin' }}</p>
                    </div>
                </div>
                <div class="text-right">
                    <span class="px-2 py-1 text-xs rounded-full {{ 
                        $customer->status === 'active' ? 'bg-green-100 text-green-800' : 
                        ($customer->status === 'trial' ? 'bg-purple-100 text-purple-800' : 
                        'bg-gray-100 text-gray-800') 
                    }}">
                        {{ ucfirst($customer->status) }}
                    </span>
                    <p class="text-xs text-gray-500 mt-1">{{ $customer->created_at->format('M d') }}</p>
                </div>
            </div>
            @empty
            <div class="text-center py-8 text-gray-500">
                <i class="fas fa-users text-2xl mb-2 block"></i>
                <p>No customers yet</p>
            </div>
            @endforelse
        </div>
        
        <!-- Quick Stats -->
        <div class="mt-8 pt-6 border-t border-gray-200">
            <h3 class="text-sm font-medium text-gray-800 mb-4">Quick Stats</h3>
            <div class="grid grid-cols-2 gap-3">
                <div class="p-3 bg-blue-50 rounded-lg">
                    <p class="text-sm text-gray-600">Monthly Revenue</p>
                    <p class="font-semibold text-gray-800">₹{{ number_format($monthlyRevenue) }}</p>
                </div>
                <div class="p-3 bg-green-50 rounded-lg">
                    <p class="text-sm text-gray-600">Active Subscriptions</p>
                    <p class="font-semibold text-gray-800">{{ $paidMembers ?? 0 }}</p>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Chart.js Script -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Get PHP data for charts
    const revenueLabels = @json($revenueData['labels'] ?? []);
    const revenueData = @json($revenueData['data'] ?? []);
    
    const customerLabels = @json($customerGrowthData['labels'] ?? []);
    const customerData = @json($customerGrowthData['data'] ?? []);
    
    // Revenue Chart - Dynamic from PHP data
    const revenueCtx = document.getElementById('revenueChart').getContext('2d');
    const revenueChart = new Chart(revenueCtx, {
        type: 'line',
        data: {
            labels: revenueLabels,
            datasets: [{
                label: 'Revenue (₹)',
                data: revenueData,
                borderColor: '#4F46E5',
                backgroundColor: 'rgba(79, 70, 229, 0.1)',
                borderWidth: 3,
                fill: true,
                tension: 0.4
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: false
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    grid: {
                        drawBorder: false
                    },
                    ticks: {
                        callback: function(value) {
                            return '₹' + value.toLocaleString();
                        }
                    }
                },
                x: {
                    grid: {
                        display: false
                    }
                }
            }
        }
    });

    // Customer Growth Chart - Dynamic from PHP data
    const customerCtx = document.getElementById('customerChart').getContext('2d');
    const customerChart = new Chart(customerCtx, {
        type: 'bar',
        data: {
            labels: customerLabels,
            datasets: [{
                label: 'New Customers',
                data: customerData,
                backgroundColor: '#10B981',
                borderRadius: 8,
                borderSkipped: false,
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: false
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    grid: {
                        drawBorder: false
                    },
                    ticks: {
                        stepSize: 5
                    }
                },
                x: {
                    grid: {
                        display: false
                    }
                }
            }
        }
    });

    // Customer Distribution Pie Chart
    @php
        $otherCustomers = $totalCustomers - $paidMembers - $activeTrials;
    @endphp
    
    const customerPieCtx = document.getElementById('customerPieChart').getContext('2d');
    const customerPieChart = new Chart(customerPieCtx, {
        type: 'doughnut',
        data: {
            labels: ['Paid Members', 'Active Trials', 'Other'],
            datasets: [{
                data: [{{ $paidMembers ?? 0 }}, {{ $activeTrials ?? 0 }}, {{ $otherCustomers }}],
                backgroundColor: [
                    '#10B981',
                    '#8B5CF6',
                    '#6B7280'
                ],
                borderWidth: 0,
                hoverOffset: 15
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            cutout: '70%',
            plugins: {
                legend: {
                    position: 'bottom',
                    labels: {
                        padding: 20,
                        usePointStyle: true,
                        pointStyle: 'circle'
                    }
                }
            }
        }
    });

    // Revenue chart button handlers
    document.getElementById('thisYearBtn').addEventListener('click', function() {
        // In real app, fetch this year data via AJAX
        document.getElementById('thisYearBtn').classList.add('bg-blue-100', 'text-blue-600');
        document.getElementById('thisYearBtn').classList.remove('text-gray-600', 'hover:bg-gray-100');
        document.getElementById('lastYearBtn').classList.remove('bg-blue-100', 'text-blue-600');
        document.getElementById('lastYearBtn').classList.add('text-gray-600', 'hover:bg-gray-100');
    });

    document.getElementById('lastYearBtn').addEventListener('click', function() {
        // In real app, fetch last year data via AJAX
        document.getElementById('lastYearBtn').classList.add('bg-blue-100', 'text-blue-600');
        document.getElementById('lastYearBtn').classList.remove('text-gray-600', 'hover:bg-gray-100');
        document.getElementById('thisYearBtn').classList.remove('bg-blue-100', 'text-blue-600');
        document.getElementById('thisYearBtn').classList.add('text-gray-600', 'hover:bg-gray-100');
    });

    // Customer growth chart button handlers
    document.getElementById('monthlyBtn').addEventListener('click', function() {
        document.getElementById('monthlyBtn').classList.add('bg-blue-100', 'text-blue-600');
        document.getElementById('monthlyBtn').classList.remove('text-gray-600', 'hover:bg-gray-100');
        document.getElementById('quarterlyBtn').classList.remove('bg-blue-100', 'text-blue-600');
        document.getElementById('quarterlyBtn').classList.add('text-gray-600', 'hover:bg-gray-100');
    });

    document.getElementById('quarterlyBtn').addEventListener('click', function() {
        document.getElementById('quarterlyBtn').classList.add('bg-blue-100', 'text-blue-600');
        document.getElementById('quarterlyBtn').classList.remove('text-gray-600', 'hover:bg-gray-100');
        document.getElementById('monthlyBtn').classList.remove('bg-blue-100', 'text-blue-600');
        document.getElementById('monthlyBtn').classList.add('text-gray-600', 'hover:bg-gray-100');
    });
});
</script>
@endsection

@push('styles')
<style>
.card-hover {
    transition: transform 0.2s ease, box-shadow 0.2s ease;
}
.card-hover:hover {
    transform: translateY(-2px);
    box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
}
</style>
@endpush