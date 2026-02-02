@extends('superadmin.layout.app')

@section('title', 'Payment Details')

@section('content')
<div class="container mx-auto px-4 py-8">
    <!-- Back Button and Header -->
    <div class="flex items-center justify-between mb-8">
        <div class="flex items-center">
            <a href="{{ route('superadmin.payments.index') }}" class="text-gray-600 hover:text-gray-800 mr-4">
                <i class="fas fa-arrow-left"></i>
            </a>
            <div>
                <h1 class="text-2xl font-bold text-gray-800">Payment Details</h1>
                <p class="text-gray-600">Payment ID: {{ $payment->payment_id }}</p>
            </div>
        </div>
        <div class="flex space-x-2">
            {{-- <a href="{{ route('payments.edit', $payment) }}" class="px-4 py-2 bg-yellow-500 text-white rounded-lg hover:bg-yellow-600">
                <i class="fas fa-edit mr-2"></i> Edit
            </a> --}}
            @if($payment->status === 'pending')
            <form action="{{ route('superadmin.payments.process', $payment) }}" method="POST" class="inline">
                @csrf
                <button type="submit" class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700">
                    <i class="fas fa-check mr-2"></i> Mark as Paid
                </button>
            </form>
            @endif
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Left Column: Payment Details -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Payment Info Card -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                <h2 class="text-lg font-semibold text-gray-800 mb-4">Payment Information</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <p class="text-sm text-gray-500 mb-1">Payment ID</p>
                        <p class="font-medium text-gray-800">{{ $payment->payment_id }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500 mb-1">Transaction ID</p>
                        <p class="font-medium text-gray-800">{{ $payment->transaction_id ?? 'N/A' }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500 mb-1">Date</p>
                        <p class="font-medium text-gray-800">
                            {{ $payment->payment_date ? \Carbon\Carbon::parse($payment->payment_date)->format('F d, Y') : $payment->created_at->format('F d, Y') }}
                        </p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500 mb-1">Status</p>
                        <span class="px-3 py-1 text-sm font-medium rounded-full {{ 
                            $payment->status === 'completed' ? 'bg-green-100 text-green-800' : 
                            ($payment->status === 'pending' ? 'bg-yellow-100 text-yellow-800' : 
                            ($payment->status === 'failed' ? 'bg-red-100 text-red-800' : 
                            'bg-gray-100 text-gray-800')) 
                        }}">
                            {{ ucfirst($payment->status) }}
                        </span>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500 mb-1">Payment Type</p>
                        <p class="font-medium text-gray-800">
                            {{ isset($payment->payment_type) ? ucfirst(str_replace('_', ' ', $payment->payment_type)) : 'One Time' }}
                        </p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500 mb-1">Payment Gateway</p>
                        <p class="font-medium text-gray-800">{{ $payment->payment_gateway ?? 'N/A' }}</p>
                    </div>
                </div>
            </div>

            <!-- Amount Card -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                <h2 class="text-lg font-semibold text-gray-800 mb-4">Amount Details</h2>
                <div class="space-y-4">
                    <div class="flex justify-between items-center">
                        <span class="text-gray-600">Subtotal</span>
                        <span class="font-medium text-gray-800">₹{{ number_format($payment->amount, 2) }}</span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-gray-600">Tax Amount</span>
                        <span class="font-medium text-gray-800">₹{{ number_format($payment->tax_amount, 2) }}</span>
                    </div>
                    <div class="flex justify-between items-center border-t border-gray-200 pt-4">
                        <span class="text-lg font-semibold text-gray-800">Total Amount</span>
                        <span class="text-2xl font-bold text-gray-800">₹{{ number_format($payment->total_amount, 2) }}</span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-gray-600">Currency</span>
                        <span class="font-medium text-gray-800">{{ $payment->currency }}</span>
                    </div>
                </div>
            </div>

            <!-- Method Card -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                <h2 class="text-lg font-semibold text-gray-800 mb-4">Payment Method</h2>
                <div class="flex items-center">
                    @php
                        $methodIcons = [
                            'credit_card' => 'fa-credit-card',
                            'bank_transfer' => 'fa-university',
                            'upi' => 'fa-mobile-alt',
                            'cash' => 'fa-money-bill',
                            'cheque' => 'fa-file-invoice-dollar',
                            'online' => 'fa-globe',
                            'wallet' => 'fa-wallet'
                        ];
                        $methodColors = [
                            'credit_card' => 'bg-blue-100 text-blue-600',
                            'bank_transfer' => 'bg-green-100 text-green-600',
                            'upi' => 'bg-purple-100 text-purple-600',
                            'cash' => 'bg-yellow-100 text-yellow-600',
                            'cheque' => 'bg-red-100 text-red-600',
                            'online' => 'bg-indigo-100 text-indigo-600',
                            'wallet' => 'bg-pink-100 text-pink-600'
                        ];
                        $icon = $methodIcons[$payment->payment_method] ?? 'fa-credit-card';
                        $color = $methodColors[$payment->payment_method] ?? 'bg-gray-100 text-gray-600';
                    @endphp
                    <div class="w-12 h-12 rounded-lg {{ $color }} flex items-center justify-center mr-4">
                        <i class="fas {{ $icon }} text-xl"></i>
                    </div>
                    <div>
                        <p class="font-medium text-gray-800 text-lg">{{ ucfirst(str_replace('_', ' ', $payment->payment_method)) }}</p>
                        <p class="text-gray-600">Payment completed via {{ $payment->payment_method }}</p>
                    </div>
                </div>
            </div>

            <!-- Notes Card -->
            @if($payment->notes)
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                <h2 class="text-lg font-semibold text-gray-800 mb-4">Notes</h2>
                <div class="bg-gray-50 rounded-lg p-4">
                    <p class="text-gray-700 whitespace-pre-line">{{ $payment->notes }}</p>
                </div>
            </div>
            @endif
        </div>

        <!-- Right Column: Customer and Actions -->
        <div class="space-y-6">
            <!-- Customer Card -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                <h2 class="text-lg font-semibold text-gray-800 mb-4">Customer Information</h2>
                <div class="flex items-center mb-4">
                    <div class="w-12 h-12 rounded-full bg-gradient-to-r from-blue-500 to-purple-500 mr-3"></div>
                    <div>
                        <p class="font-medium text-gray-800 text-lg">{{ $payment->customer->name ?? 'N/A' }}</p>
                        <p class="text-gray-600">{{ $payment->customer->business_name ?? '' }}</p>
                    </div>
                </div>
                <div class="space-y-3">
                    <div class="flex items-center">
                        <i class="fas fa-envelope text-gray-400 mr-3"></i>
                        <span class="text-gray-700">{{ $payment->customer->email ?? 'N/A' }}</span>
                    </div>
                    <div class="flex items-center">
                        <i class="fas fa-phone text-gray-400 mr-3"></i>
                        <span class="text-gray-700">{{ $payment->customer->phone ?? 'N/A' }}</span>
                    </div>
                    <div class="flex items-center">
                        <i class="fas fa-map-marker-alt text-gray-400 mr-3"></i>
                        <span class="text-gray-700">{{ $payment->customer->address ?? 'N/A' }}</span>
                    </div>
                </div>
                <div class="mt-4 pt-4 border-t border-gray-200">
                    <a href="{{ route('superadmin.customers.show', $payment->customer) }}" class="w-full px-4 py-2 bg-blue-50 text-blue-600 rounded-lg hover:bg-blue-100 flex items-center justify-center">
                        <i class="fas fa-user mr-2"></i> View Customer Profile
                    </a>
                </div>
            </div>

            <!-- Subscription Card (if applicable) -->
            @if($payment->subscription_id && $payment->subscription)
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                <h2 class="text-lg font-semibold text-gray-800 mb-4">Subscription Details</h2>
                <div class="space-y-3">
                    <div>
                        <p class="text-sm text-gray-500 mb-1">Plan Name</p>
                        <p class="font-medium text-gray-800">{{ $payment->subscription->plan_name }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500 mb-1">Billing Cycle</p>
                        <p class="font-medium text-gray-800">{{ ucfirst($payment->subscription->billing_cycle) }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500 mb-1">Start Date</p>
                        <p class="font-medium text-gray-800">{{ \Carbon\Carbon::parse($payment->subscription->start_date)->format('F d, Y') }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500 mb-1">End Date</p>
                        <p class="font-medium text-gray-800">{{ \Carbon\Carbon::parse($payment->subscription->end_date)->format('F d, Y') }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500 mb-1">Status</p>
                        <span class="px-2 py-1 text-xs font-medium rounded-full {{ 
                            $payment->subscription->status === 'active' ? 'bg-green-100 text-green-800' : 
                            ($payment->subscription->status === 'expired' ? 'bg-red-100 text-red-800' : 
                            'bg-gray-100 text-gray-800') 
                        }}">
                            {{ ucfirst($payment->subscription->status) }}
                        </span>
                    </div>
                </div>
            </div>
            @endif

            <!-- Invoice Card (if applicable) -->
            @if($payment->invoice_id && $payment->invoice)
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                <h2 class="text-lg font-semibold text-gray-800 mb-4">Invoice Details</h2>
                <div class="space-y-3">
                    <div>
                        <p class="text-sm text-gray-500 mb-1">Invoice Number</p>
                        <p class="font-medium text-gray-800">{{ $payment->invoice->invoice_number }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500 mb-1">Invoice Date</p>
                        <p class="font-medium text-gray-800">{{ \Carbon\Carbon::parse($payment->invoice->invoice_date)->format('F d, Y') }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500 mb-1">Due Date</p>
                        <p class="font-medium text-gray-800">{{ \Carbon\Carbon::parse($payment->invoice->due_date)->format('F d, Y') }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500 mb-1">Invoice Amount</p>
                        <p class="font-medium text-gray-800">₹{{ number_format($payment->invoice->total_amount, 2) }}</p>
                    </div>
                </div>
            </div>
            @endif

            <!-- Actions Card -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                <h2 class="text-lg font-semibold text-gray-800 mb-4">Actions</h2>
                <div class="space-y-3">
                    @if($payment->status === 'pending')
                    <form action="{{ route('superadmin.payments.process', $payment) }}" method="POST" class="w-full">
                        @csrf
                        <button type="submit" class="w-full px-4 py-3 bg-green-600 text-white rounded-lg hover:bg-green-700 flex items-center justify-center mb-3">
                            <i class="fas fa-check-circle mr-2"></i> Mark as Completed
                        </button>
                    </form>
                    @endif
                    
                    @if($payment->status === 'completed')
                    <button onclick="printPayment()" class="w-full px-4 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 flex items-center justify-center mb-3">
                        <i class="fas fa-print mr-2"></i> Print Receipt
                    </button>
                    @endif
                    
                    {{-- <a href="{{ route('payments.edit', $payment) }}" class="w-full px-4 py-3 bg-yellow-500 text-white rounded-lg hover:bg-yellow-600 flex items-center justify-center mb-3">
                        <i class="fas fa-edit mr-2"></i> Edit Payment
                    </a> --}}
                    
                    <form action="{{ route('superadmin.payments.destroy', $payment) }}" method="POST" class="w-full" onsubmit="return confirm('Are you sure you want to delete this payment?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="w-full px-4 py-3 bg-red-600 text-white rounded-lg hover:bg-red-700 flex items-center justify-center">
                            <i class="fas fa-trash mr-2"></i> Delete Payment
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Timeline Card -->
    <div class="mt-6 bg-white rounded-xl shadow-sm border border-gray-100 p-6">
        <h2 class="text-lg font-semibold text-gray-800 mb-4">Payment Timeline</h2>
        <div class="space-y-4">
            <div class="flex items-start">
                <div class="flex-shrink-0 w-8 h-8 rounded-full bg-green-100 flex items-center justify-center mr-3">
                    <i class="fas fa-check text-green-600"></i>
                </div>
                <div>
                    <p class="font-medium text-gray-800">Payment Created</p>
                    <p class="text-gray-600 text-sm">{{ $payment->created_at->format('F d, Y h:i A') }}</p>
                </div>
            </div>
            
            @if($payment->status === 'completed' && $payment->payment_date)
            <div class="flex items-start">
                <div class="flex-shrink-0 w-8 h-8 rounded-full bg-green-100 flex items-center justify-center mr-3">
                    <i class="fas fa-check-circle text-green-600"></i>
                </div>
                <div>
                    <p class="font-medium text-gray-800">Payment Completed</p>
                    <p class="text-gray-600 text-sm">{{ \Carbon\Carbon::parse($payment->payment_date)->format('F d, Y h:i A') }}</p>
                </div>
            </div>
            @endif
            
            @if($payment->updated_at != $payment->created_at)
            <div class="flex items-start">
                <div class="flex-shrink-0 w-8 h-8 rounded-full bg-blue-100 flex items-center justify-center mr-3">
                    <i class="fas fa-sync-alt text-blue-600"></i>
                </div>
                <div>
                    <p class="font-medium text-gray-800">Last Updated</p>
                    <p class="text-gray-600 text-sm">{{ $payment->updated_at->format('F d, Y h:i A') }}</p>
                </div>
            </div>
            @endif
        </div>
    </div>
</div>

<!-- Print Receipt Script -->
<script>
function printPayment() {
    // Create a print-friendly version
    const printContent = `
        <!DOCTYPE html>
        <html>
        <head>
            <title>Payment Receipt - {{ $payment->payment_id }}</title>
            <style>
                body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; max-width: 800px; margin: 0 auto; padding: 20px; }
                .header { text-align: center; margin-bottom: 30px; border-bottom: 2px solid #4F46E5; padding-bottom: 20px; }
                .company-name { font-size: 24px; font-weight: bold; color: #4F46E5; }
                .receipt-title { font-size: 18px; margin: 10px 0; }
                .details { margin: 20px 0; }
                .detail-row { display: flex; justify-content: space-between; margin: 8px 0; }
                .detail-label { font-weight: bold; color: #666; }
                .amount { font-size: 28px; font-weight: bold; color: #10B981; text-align: center; margin: 30px 0; }
                .footer { margin-top: 40px; text-align: center; color: #666; font-size: 12px; border-top: 1px solid #ddd; padding-top: 20px; }
                .status-badge { padding: 4px 12px; border-radius: 20px; font-size: 12px; font-weight: bold; }
                .status-completed { background-color: #D1FAE5; color: #065F46; }
            </style>
        </head>
        <body>
            <div class="header">
                <div class="company-name">SocialCuits CRM</div>
                <div class="receipt-title">Payment Receipt</div>
                <div>Payment ID: {{ $payment->payment_id }}</div>
            </div>
            
            <div class="details">
                <div class="detail-row">
                    <span class="detail-label">Date:</span>
                    <span>{{ \Carbon\Carbon::parse($payment->payment_date)->format('F d, Y') }}</span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">Transaction ID:</span>
                    <span>{{ $payment->transaction_id ?? 'N/A' }}</span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">Customer:</span>
                    <span>{{ $payment->customer->name ?? 'N/A' }}</span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">Payment Method:</span>
                    <span>{{ ucfirst(str_replace('_', ' ', $payment->payment_method)) }}</span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">Status:</span>
                    <span class="status-badge status-completed">Completed</span>
                </div>
            </div>
            
            <div class="amount">₹{{ number_format($payment->total_amount, 2) }}</div>
            
            <div class="details">
                <div class="detail-row">
                    <span class="detail-label">Subtotal:</span>
                    <span>₹{{ number_format($payment->amount, 2) }}</span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">Tax:</span>
                    <span>₹{{ number_format($payment->tax_amount, 2) }}</span>
                </div>
                <div class="detail-row" style="border-top: 1px solid #ddd; padding-top: 10px; font-weight: bold;">
                    <span>Total Amount:</span>
                    <span>₹{{ number_format($payment->total_amount, 2) }}</span>
                </div>
            </div>
            
            <div class="footer">
                <p>Thank you for your payment!</p>
                <p>For any queries, please contact support@socialcuits.com</p>
                <p>Printed on: {{ now()->format('F d, Y h:i A') }}</p>
            </div>
        </body>
        </html>
    `;
    
    const printWindow = window.open('', '_blank');
    printWindow.document.write(printContent);
    printWindow.document.close();
    printWindow.print();
}
</script>
@endsection