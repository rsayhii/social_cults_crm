@extends('superadmin.layout.app')

@section('title', 'Invoice - ' . $invoice->invoice_number)

@section('content')
<div class="max-w-7xl mx-auto p-6">
    <!-- Header -->
    <div class="flex flex-col md:flex-row md:items-center justify-between mb-6">
        <div class="mb-4 md:mb-0">
            <h1 class="text-2xl font-bold text-gray-800">Invoice #{{ $invoice->invoice_number }}</h1>
            <p class="text-gray-600">Generated on {{ $invoice->invoice_date->format('F d, Y') }}</p>
        </div>
        <div class="flex flex-wrap gap-3">
            <a href="{{ route('superadmin.invoices.download', $invoice) }}" class="px-4 py-2 bg-gradient-to-r from-green-600 to-emerald-600 text-white rounded-lg hover:from-green-700 hover:to-emerald-700 transition duration-200 font-medium flex items-center">
                <i class="fas fa-download mr-2"></i>
                Download PDF
            </a>
            <a href="{{ route('superadmin.invoices.edit', $invoice) }}" class="px-4 py-2 bg-gradient-to-r from-blue-600 to-indigo-600 text-white rounded-lg hover:from-blue-700 hover:to-indigo-700 transition duration-200 font-medium flex items-center">
                <i class="fas fa-edit mr-2"></i>
                Edit Invoice
            </a>
            <a href="{{ route('superadmin.invoices.index') }}" class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition duration-200 font-medium flex items-center">
                <i class="fas fa-arrow-left mr-2"></i>
                Back to Invoices
            </a>
        </div>
    </div>

    <!-- Status & Info Banner -->
    <div class="mb-6 p-4 rounded-lg {{ $invoice->status === 'paid' ? 'bg-green-50 border border-green-200' : ($invoice->status === 'pending' ? 'bg-yellow-50 border border-yellow-200' : 'bg-red-50 border border-red-200') }}">
        <div class="flex flex-col md:flex-row md:items-center justify-between">
            <div class="flex items-center mb-3 md:mb-0">
                <span class="status-badge status-{{ $invoice->status }} mr-3">{{ ucfirst($invoice->status) }}</span>
                <span class="text-gray-700">Invoice Date: {{ $invoice->invoice_date->format('d M, Y') }}</span>
                @if($invoice->due_date)
                <span class="ml-4 text-gray-700">Due Date: {{ $invoice->due_date->format('d M, Y') }}</span>
                @endif
            </div>
            <div class="text-right">
                <span class="text-2xl font-bold text-gray-800">{{ $invoice->currency }}{{ number_format($invoice->grand_total, 2) }}</span>
                <p class="text-sm text-gray-600">Grand Total</p>
            </div>
        </div>
    </div>

    <!-- Invoice Details -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
        <!-- Company Info -->
        <div class="bg-white rounded-xl p-6 shadow-sm border border-gray-100">
            <h3 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
                <i class="fas fa-building mr-2 text-blue-600"></i>
                From
            </h3>
            <div class="space-y-2">
                <p class="text-lg font-bold text-gray-800">{{ $invoice->company_name }}</p>
                <p class="text-gray-600 whitespace-pre-line">{{ $invoice->company_address }}</p>
                @if($invoice->company_phone)
                <p class="text-gray-600"><i class="fas fa-phone mr-2 text-gray-400"></i> {{ $invoice->company_phone }}</p>
                @endif
                @if($invoice->company_email)
                <p class="text-gray-600"><i class="fas fa-envelope mr-2 text-gray-400"></i> {{ $invoice->company_email }}</p>
                @endif
                @if($invoice->company_gstin)
                <p class="text-gray-600"><i class="fas fa-id-card mr-2 text-gray-400"></i> GSTIN: {{ $invoice->company_gstin }}</p>
                @endif
                @if($invoice->company_bank_details)
                <div class="mt-3 pt-3 border-t border-gray-200">
                    <p class="text-sm font-medium text-gray-700 mb-1">Bank Details:</p>
                    <p class="text-sm text-gray-600 whitespace-pre-line">{{ $invoice->company_bank_details }}</p>
                </div>
                @endif
            </div>
        </div>

        <!-- Client Info -->
        <div class="bg-white rounded-xl p-6 shadow-sm border border-gray-100">
            <h3 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
                <i class="fas fa-user-circle mr-2 text-blue-600"></i>
                Bill To
            </h3>
            <div class="space-y-2">
                <p class="text-lg font-bold text-gray-800">{{ $invoice->client_name }}</p>
                <p class="text-gray-600 whitespace-pre-line">{{ $invoice->client_address }}</p>
                @if($invoice->client_phone)
                <p class="text-gray-600"><i class="fas fa-phone mr-2 text-gray-400"></i> {{ $invoice->client_phone }}</p>
                @endif
                @if($invoice->client_email)
                <p class="text-gray-600"><i class="fas fa-envelope mr-2 text-gray-400"></i> {{ $invoice->client_email }}</p>
                @endif
                @if($invoice->client_gstin)
                <p class="text-gray-600"><i class="fas fa-id-card mr-2 text-gray-400"></i> GSTIN: {{ $invoice->client_gstin }}</p>
                @endif
            </div>
        </div>
    </div>

    <!-- Invoice Items -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden mb-8">
        <div class="p-6 border-b border-gray-200">
            <h3 class="text-lg font-semibold text-gray-800 flex items-center">
                <i class="fas fa-list-alt mr-2 text-blue-600"></i>
                Invoice Items
            </h3>
        </div>
        
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="py-3 px-6 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">#</th>
                        <th class="py-3 px-6 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Description</th>
                        <th class="py-3 px-6 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Service Type</th>
                        <th class="py-3 px-6 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Quantity</th>
                        <th class="py-3 px-6 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Unit Price</th>
                        <th class="py-3 px-6 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Amount</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @if($invoice->items && count($invoice->items) > 0)
                        @foreach($invoice->items as $index => $item)
                        <tr>
                            <td class="py-3 px-6">{{ $index + 1 }}</td>
                            <td class="py-3 px-6">
                                <p class="font-medium text-gray-800">{{ $item['description'] ?? 'N/A' }}</p>
                            </td>
                            <td class="py-3 px-6">
                                <span class="text-gray-700">{{ $item['service_type'] ?? '-' }}</span>
                            </td>
                            <td class="py-3 px-6">
                                <span class="text-gray-700">{{ number_format($item['quantity'] ?? 0, 2) }}</span>
                            </td>
                            <td class="py-3 px-6">
                                <span class="text-gray-700">{{ $invoice->currency }}{{ number_format($item['unit_price'] ?? 0, 2) }}</span>
                            </td>
                            <td class="py-3 px-6">
                                <span class="font-medium text-gray-800">
                                    {{ $invoice->currency }}{{ number_format(($item['quantity'] ?? 0) * ($item['unit_price'] ?? 0), 2) }}
                                </span>
                            </td>
                        </tr>
                        @endforeach
                    @else
                        <tr>
                            <td colspan="6" class="py-8 px-6 text-center text-gray-500">
                                <i class="fas fa-box-open text-2xl mb-2 text-gray-300"></i>
                                <p>No items found in this invoice</p>
                            </td>
                        </tr>
                    @endif
                </tbody>
            </table>
        </div>
        
        <!-- Totals -->
        <div class="p-6 border-t border-gray-200">
            <div class="flex justify-end">
                <div class="w-64 space-y-3">
                    <div class="flex justify-between">
                        <span class="text-gray-600">Subtotal:</span>
                        <span class="font-medium text-gray-800">{{ $invoice->currency }}{{ number_format($invoice->subtotal, 2) }}</span>
                    </div>
                    
                    @if($invoice->tax_rate > 0)
                    <div class="flex justify-between">
                        <span class="text-gray-600">Tax ({{ $invoice->tax_rate }}% {{ strtoupper($invoice->tax_type) }}):</span>
                        <span class="font-medium text-gray-800">{{ $invoice->currency }}{{ number_format($invoice->tax_amount, 2) }}</span>
                    </div>
                    @endif
                    
                    @if($invoice->discount > 0)
                    <div class="flex justify-between">
                        <span class="text-gray-600">Discount:</span>
                        <span class="font-medium text-gray-800">{{ $invoice->currency }}{{ number_format($invoice->discount, 2) }}</span>
                    </div>
                    @endif
                    
                    <hr class="my-2">
                    <div class="flex justify-between text-lg font-bold">
                        <span>Grand Total:</span>
                        <span>{{ $invoice->currency }}{{ number_format($invoice->grand_total, 2) }}</span>
                    </div>
                    
                    @if($invoice->paid_amount > 0)
                    <div class="flex justify-between text-green-600">
                        <span>Paid Amount:</span>
                        <span class="font-bold">{{ $invoice->currency }}{{ number_format($invoice->paid_amount, 2) }}</span>
                    </div>
                    @endif
                    
                    @if($invoice->grand_total - $invoice->paid_amount > 0)
                    <div class="flex justify-between text-red-600">
                        <span>Balance Due:</span>
                        <span class="font-bold">{{ $invoice->currency }}{{ number_format($invoice->grand_total - $invoice->paid_amount, 2) }}</span>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Additional Information -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
        <!-- Payment Information -->
        @if($invoice->paid_amount > 0 || $invoice->status === 'paid')
        <div class="bg-white rounded-xl p-6 shadow-sm border border-gray-100">
            <h3 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
                <i class="fas fa-credit-card mr-2 text-green-600"></i>
                Payment Information
            </h3>
            <div class="space-y-3">
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <p class="text-sm font-medium text-gray-700">Status</p>
                        <p class="text-lg font-bold {{ $invoice->status === 'paid' ? 'text-green-600' : 'text-yellow-600' }}">
                            {{ ucfirst($invoice->status) }}
                        </p>
                    </div>
                    @if($invoice->paid_amount > 0)
                    <div>
                        <p class="text-sm font-medium text-gray-700">Paid Amount</p>
                        <p class="text-lg font-bold text-gray-800">{{ $invoice->currency }}{{ number_format($invoice->paid_amount, 2) }}</p>
                    </div>
                    @endif
                </div>
                
                @if($invoice->payment_date)
                <div>
                    <p class="text-sm font-medium text-gray-700">Payment Date</p>
                    <p class="text-gray-800">{{ $invoice->payment_date->format('d M, Y') }}</p>
                </div>
                @endif
                
                @if($invoice->payment_method)
                <div>
                    <p class="text-sm font-medium text-gray-700">Payment Method</p>
                    <p class="text-gray-800">{{ ucfirst(str_replace('_', ' ', $invoice->payment_method)) }}</p>
                </div>
                @endif
                
                @if($invoice->transaction_id)
                <div>
                    <p class="text-sm font-medium text-gray-700">Transaction ID</p>
                    <p class="text-gray-800 font-mono text-sm">{{ $invoice->transaction_id }}</p>
                </div>
                @endif
            </div>
        </div>
        @endif

        <!-- Terms & Conditions -->
        @if($invoice->terms_conditions)
        <div class="bg-white rounded-xl p-6 shadow-sm border border-gray-100">
            <h3 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
                <i class="fas fa-file-contract mr-2 text-blue-600"></i>
                Terms & Conditions
            </h3>
            <div class="text-gray-600 whitespace-pre-line">{{ $invoice->terms_conditions }}</div>
        </div>
        @endif
    </div>

    <!-- Notes & Description -->
    @if($invoice->description)
    <div class="bg-white rounded-xl p-6 shadow-sm border border-gray-100 mb-8">
        <h3 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
            <i class="fas fa-sticky-note mr-2 text-blue-600"></i>
            Description
        </h3>
        <div class="text-gray-600 whitespace-pre-line">{{ $invoice->description }}</div>
    </div>
    @endif

    <!-- Action Buttons -->
    <div class="flex flex-wrap justify-between items-center pt-6 border-t border-gray-200">
        <div class="text-sm text-gray-500 mb-4 md:mb-0">
            <p>Created: {{ $invoice->created_at->format('d M Y, h:i A') }}</p>
            @if($invoice->updated_at != $invoice->created_at)
            <p>Last Updated: {{ $invoice->updated_at->format('d M Y, h:i A') }}</p>
            @endif
        </div>
        <div class="flex flex-wrap gap-3">
            <a href="{{ route('superadmin.invoices.index') }}" class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition duration-200 font-medium flex items-center">
                <i class="fas fa-list mr-2"></i>
                View All Invoices
            </a>
            <a href="{{ route('superadmin.invoices.edit', $invoice) }}" class="px-4 py-2 bg-gradient-to-r from-blue-600 to-indigo-600 text-white rounded-lg hover:from-blue-700 hover:to-indigo-700 transition duration-200 font-medium flex items-center">
                <i class="fas fa-edit mr-2"></i>
                Edit Invoice
            </a>
            @if($invoice->status !== 'paid')
            <button onclick="markAsPaid()" class="px-4 py-2 bg-gradient-to-r from-green-600 to-emerald-600 text-white rounded-lg hover:from-green-700 hover:to-emerald-700 transition duration-200 font-medium flex items-center">
                <i class="fas fa-check-circle mr-2"></i>
                Mark as Paid
            </button>
            @endif
        </div>
    </div>
</div>

@section('styles')
<style>
    .status-badge {
        padding: 6px 16px;
        border-radius: 20px;
        font-size: 14px;
        font-weight: 600;
        display: inline-block;
    }
    .status-pending {
        background-color: rgba(245, 158, 11, 0.1);
        color: rgb(146, 64, 14);
        border: 1px solid rgba(245, 158, 11, 0.3);
    }
    .status-paid {
        background-color: rgba(34, 197, 94, 0.1);
        color: rgb(21, 128, 61);
        border: 1px solid rgba(34, 197, 94, 0.3);
    }
    .status-overdue {
        background-color: rgba(239, 68, 68, 0.1);
        color: rgb(185, 28, 28);
        border: 1px solid rgba(239, 68, 68, 0.3);
    }
    .status-cancelled {
        background-color: rgba(107, 114, 128, 0.1);
        color: rgb(55, 65, 81);
        border: 1px solid rgba(107, 114, 128, 0.3);
    }
</style>
@endsection

@section('scripts')
<script>
function markAsPaid() {
    if(confirm('Are you sure you want to mark this invoice as paid?')) {
        fetch('{{ route("superadmin.invoices.mark-paid", $invoice) }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            }
        })
        .then(response => response.json())
        .then(data => {
            if(data.success) {
                alert('Invoice marked as paid successfully!');
                location.reload();
            } else {
                alert('Error: ' + data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('An error occurred. Please try again.');
        });
    }
}
</script>
@endsection