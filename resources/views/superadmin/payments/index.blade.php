@extends('superadmin.layout.app')

@section('title', 'Payments')

@section('content')
<div class="flex flex-col md:flex-row md:items-center justify-between mb-8">
    <div>
        <h1 class="text-2xl font-bold text-gray-800">Payments</h1>
        <p class="text-gray-600">View and manage customer payments</p>
    </div>
    {{-- <div class="mt-4 md:mt-0">
        <a href="{{ route('payments.create') }}" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
            <i class="fas fa-plus mr-2"></i> Add Payment
        </a>
    </div> --}}
</div>

<!-- Payment Statistics -->
<div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
    <div class="bg-gradient-to-r from-green-500 to-emerald-600 rounded-xl p-6 text-white">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-green-100">Total Revenue</p>
                <h3 class="text-2xl font-bold mt-1">₹{{ number_format($summary['total_amount']) }}</h3>
            </div>
            <i class="fas fa-rupee-sign text-2xl opacity-80"></i>
        </div>
        <p class="text-green-100 text-sm mt-2">{{ $summary['total_payments'] }} total payments</p>
    </div>
    
    <div class="bg-white rounded-xl p-6 shadow-sm border border-gray-100">
        <div class="flex items-center">
            <div class="w-12 h-12 rounded-lg bg-blue-100 flex items-center justify-center mr-4">
                <i class="fas fa-check-circle text-blue-600 text-xl"></i>
            </div>
            <div>
                <p class="text-gray-500 text-sm">Completed Payments</p>
                <h3 class="text-2xl font-bold text-gray-800">{{ $summary['completed_payments'] }}</h3>
            </div>
        </div>
    </div>
    
    <div class="bg-white rounded-xl p-6 shadow-sm border border-gray-100">
        <div class="flex items-center">
            <div class="w-12 h-12 rounded-lg bg-yellow-100 flex items-center justify-center mr-4">
                <i class="fas fa-clock text-yellow-600 text-xl"></i>
            </div>
            <div>
                <p class="text-gray-500 text-sm">Pending Payments</p>
                <h3 class="text-2xl font-bold text-gray-800">{{ $summary['pending_payments'] }}</h3>
            </div>
        </div>
    </div>
</div>

<!-- Search and Filter Section -->
<div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4 mb-6">
    <form action="{{ route('superadmin.payments.index') }}" method="GET" class="flex flex-col md:flex-row gap-4">
        <div class="flex-1">
            <input type="text" name="search" placeholder="Search by Payment ID, Customer Name, Transaction ID..." 
                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                value="{{ request('search') }}">
        </div>
        
        <div class="flex gap-4">
            <select name="status" class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                <option value="all" {{ request('status') == 'all' ? 'selected' : '' }}>All Status</option>
                <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Completed</option>
                <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                <option value="failed" {{ request('status') == 'failed' ? 'selected' : '' }}>Failed</option>
            </select>
            
            <select name="payment_method" class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                <option value="all" {{ request('payment_method') == 'all' ? 'selected' : '' }}>All Methods</option>
                <option value="credit_card" {{ request('payment_method') == 'credit_card' ? 'selected' : '' }}>Credit Card</option>
                <option value="bank_transfer" {{ request('payment_method') == 'bank_transfer' ? 'selected' : '' }}>Bank Transfer</option>
                <option value="upi" {{ request('payment_method') == 'upi' ? 'selected' : '' }}>UPI</option>
                <option value="cash" {{ request('payment_method') == 'cash' ? 'selected' : '' }}>Cash</option>
            </select>
            
            <button type="submit" class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                <i class="fas fa-search mr-2"></i> Search
            </button>
            
            <a href="{{ route('superadmin.payments.index') }}" class="px-6 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50">
                <i class="fas fa-redo mr-2"></i> Reset
            </a>
        </div>
    </form>
</div>

<!-- Payments Table -->
<div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
    @if($payments->count() > 0)
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead class="bg-gray-50">
                <tr>
                    <th class="py-3 px-6 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Payment ID</th>
                    <th class="py-3 px-6 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Customer</th>
                    <th class="py-3 px-6 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                    <th class="py-3 px-6 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Amount</th>
                    <th class="py-3 px-6 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Method</th>
                    <th class="py-3 px-6 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                    <th class="py-3 px-6 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Transaction ID</th>
                    <th class="py-3 px-6 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                @foreach($payments as $payment)
                <tr>
                    <td class="py-3 px-6">
                        <span class="font-medium text-gray-800">{{ $payment->payment_id }}</span>
                        @if(isset($payment->payment_type) && $payment->payment_type === 'subscription')
                        <span class="ml-2 px-2 py-1 text-xs bg-purple-100 text-purple-800 rounded-full">Subscription</span>
                        @endif
                    </td>
                    <td class="py-3 px-6">
                        <div class="flex items-center">
                            <div class="w-8 h-8 rounded-full bg-gradient-to-r from-blue-500 to-purple-500 mr-3"></div>
                            <div>
                                <p class="font-medium text-gray-800">{{ $payment->customer->name ?? 'N/A' }}</p>
                                <p class="text-sm text-gray-500">{{ $payment->customer->business_name ?? '' }}</p>
                            </div>
                        </div>
                    </td>
                    <td class="py-3 px-6">
                        <span class="text-gray-800">{{ $payment->payment_date ? \Carbon\Carbon::parse($payment->payment_date)->format('Y-m-d') : $payment->created_at->format('Y-m-d') }}</span>
                    </td>
                    <td class="py-3 px-6">
                        <span class="font-medium text-gray-800">₹{{ number_format($payment->total_amount, 2) }}</span>
                    </td>
                    <td class="py-3 px-6">
                        <span class="text-gray-800">{{ ucfirst(str_replace('_', ' ', $payment->payment_method)) }}</span>
                    </td>
                    <td class="py-3 px-6">
                        <span class="px-2 py-1 text-xs font-medium rounded-full {{ 
                            $payment->status === 'completed' ? 'bg-green-100 text-green-800' : 
                            ($payment->status === 'pending' ? 'bg-yellow-100 text-yellow-800' : 
                            ($payment->status === 'failed' ? 'bg-red-100 text-red-800' : 
                            'bg-gray-100 text-gray-800')) 
                        }}">
                            {{ ucfirst($payment->status) }}
                        </span>
                    </td>
                    <td class="py-3 px-6">
                        <span class="text-gray-800 text-sm">{{ $payment->transaction_id ?? 'N/A' }}</span>
                    </td>
                    <td class="py-3 px-6">
                        <div class="flex space-x-2">
                            <a href="{{ route('superadmin.payments.show', $payment) }}" class="p-1 text-blue-600 hover:text-blue-800" title="View Details">
                                <i class="fas fa-eye"></i>
                            </a>
                            @if($payment->status === 'pending')
                            <form action="{{ route('payments.process', $payment) }}" method="POST" class="inline">
                                @csrf
                                <button type="submit" class="p-1 text-green-600 hover:text-green-800" title="Mark as Completed">
                                    <i class="fas fa-check"></i>
                                </button>
                            </form>
                            @endif
                            <a href="{{ route('superadmin.payments.edit', $payment) }}" class="p-1 text-yellow-600 hover:text-yellow-800" title="Edit">
                                <i class="fas fa-edit"></i>
                            </a>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <div class="px-6 py-4 border-t border-gray-200">
        {{ $payments->links() }}
    </div>
    @else
    <div class="text-center py-12">
        <div class="text-gray-400 mb-4">
            <i class="fas fa-credit-card text-4xl"></i>
        </div>
        <h3 class="text-lg font-medium text-gray-700 mb-2">No payments found</h3>
        <p class="text-gray-500 mb-6">There are no payment records in the system yet.</p>
        <a href="{{ route('superadmin.payments.create') }}" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
            <i class="fas fa-plus mr-2"></i> Create Your First Payment
        </a>
        <p class="text-gray-500 text-sm mt-4">Or wait for customers to purchase subscriptions</p>
    </div>
    @endif
</div>

<!-- Information Box -->
@if($payments->count() === 0)
<div class="mt-6 bg-blue-50 border border-blue-200 rounded-xl p-6">
    <div class="flex items-start">
        <div class="flex-shrink-0">
            <i class="fas fa-info-circle text-blue-500 text-xl"></i>
        </div>
        <div class="ml-3">
            <h3 class="text-lg font-medium text-blue-800">How to see subscription payments?</h3>
            <div class="mt-2 text-blue-700">
                <p class="mb-2">Subscription payments will appear here automatically when:</p>
                <ul class="list-disc pl-5 space-y-1">
                    <li>A customer purchases a paid subscription through the Customers page</li>
                    <li>You create a payment manually using the "Add Payment" button</li>
                    <li>A subscription is renewed</li>
                </ul>
                <p class="mt-3">Check that your CustomerController is creating payment records when customers subscribe.</p>
            </div>
        </div>
    </div>
</div>
@endif

@endsection

@push('styles')
<style>
    .status-badge {
        @apply px-3 py-1 rounded-full text-xs font-medium;
    }
    .status-active {
        @apply bg-green-100 text-green-800;
    }
    .status-pending {
        @apply bg-yellow-100 text-yellow-800;
    }
    .status-expired {
        @apply bg-red-100 text-red-800;
    }
</style>
@endpush