@extends('layout.app')

@section('title', 'Payments')

@section('content')
<div class="flex flex-col md:flex-row md:items-center justify-between mb-8">
    <div>
        <h1 class="text-2xl font-bold text-gray-800">Payments</h1>
        <p class="text-gray-600">View and manage customer payments</p>
    </div>
</div>

<!-- Payments Stats -->
<div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
    <div class="bg-white rounded-xl p-6 shadow-sm border border-gray-100">
        <div class="flex items-center">
            <div class="w-12 h-12 rounded-lg bg-green-100 flex items-center justify-center mr-4">
                <i class="fas fa-rupee-sign text-green-600 text-xl"></i>
            </div>
            <div>
                <p class="text-gray-500 text-sm">Total Received</p>
                <h3 class="text-2xl font-bold text-gray-800">₹{{ number_format($paymentStats['total_received']) }}</h3>
            </div>
        </div>
    </div>
    
    <div class="bg-white rounded-xl p-6 shadow-sm border border-gray-100">
        <div class="flex items-center">
            <div class="w-12 h-12 rounded-lg bg-blue-100 flex items-center justify-center mr-4">
                <i class="fas fa-credit-card text-blue-600 text-xl"></i>
            </div>
            <div>
                <p class="text-gray-500 text-sm">This Month</p>
                <h3 class="text-2xl font-bold text-gray-800">₹{{ number_format($paymentStats['monthly']) }}</h3>
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
                <h3 class="text-2xl font-bold text-gray-800">₹{{ number_format($paymentStats['pending']) }}</h3>
            </div>
        </div>
    </div>
    
    <div class="bg-white rounded-xl p-6 shadow-sm border border-gray-100">
        <div class="flex items-center">
            <div class="w-12 h-12 rounded-lg bg-red-100 flex items-center justify-center mr-4">
                <i class="fas fa-times-circle text-red-600 text-xl"></i>
            </div>
            <div>
                <p class="text-gray-500 text-sm">Failed Payments</p>
                <h3 class="text-2xl font-bold text-gray-800">₹{{ number_format($paymentStats['failed']) }}</h3>
            </div>
        </div>
    </div>
</div>

<!-- Payments Table -->
<div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead class="bg-gray-50">
                <tr>
                    <th class="py-3 px-6 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Payment ID</th>
                    <th class="py-3 px-6 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Customer</th>
                    <th class="py-3 px-6 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                    <th class="py-3 px-6 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Amount</th>
                    <th class="py-3 px-6 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Payment Method</th>
                    <th class="py-3 px-6 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                    <th class="py-3 px-6 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                @foreach($payments as $payment)
                <tr>
                    <td class="py-3 px-6">
                        <span class="font-medium text-gray-800">{{ $payment->payment_id }}</span>
                    </td>
                    <td class="py-3 px-6">
                        <span class="text-gray-800">{{ $payment->customer->name }}</span>
                    </td>
                    <td class="py-3 px-6">
                        <span class="text-gray-800">{{ $payment->created_at->format('Y-m-d') }}</span>
                    </td>
                    <td class="py-3 px-6">
                        <span class="font-medium text-gray-800">₹{{ number_format($payment->total_amount) }}</span>
                    </td>
                    <td class="py-3 px-6">
                        <span class="text-gray-800">{{ ucfirst(str_replace('_', ' ', $payment->payment_method)) }}</span>
                    </td>
                    <td class="py-3 px-6">
                        <span class="status-badge {{ $payment->status === 'completed' ? 'status-active' : ($payment->status === 'pending' ? 'status-pending' : 'status-expired') }}">
                            {{ ucfirst($payment->status) }}
                        </span>
                    </td>
                    <td class="py-3 px-6">
                        @if($payment->status === 'pending')
                        <form action="{{ route('payments.mark-paid', $payment->id) }}" method="POST" class="inline">
                            @csrf
                            <button type="submit" class="p-1 text-green-600 hover:text-green-800" title="Mark as Paid">
                                <i class="fas fa-check"></i>
                            </button>
                        </form>
                        @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <div class="px-6 py-4 border-t border-gray-200">
        {{ $payments->links() }}
    </div>
</div>
@endsection