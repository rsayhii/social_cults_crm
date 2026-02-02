@extends('superadmin.layout.app')

@section('title', 'Invoices')

@section('content')
<div class="flex flex-col md:flex-row md:items-center justify-between mb-8">
    <div>
        <h1 class="text-2xl font-bold text-gray-800">Invoices</h1>
        <p class="text-gray-600">Manage and generate customer invoices</p>
    </div>
    <div class="mt-4 md:mt-0">
        <a href="{{ route('superadmin.invoices.create') }}" class="bg-gradient-to-r from-blue-600 to-indigo-600 text-white px-4 py-2 rounded-lg hover:from-blue-700 hover:to-indigo-700 transition duration-200 font-medium flex items-center">
            <i class="fas fa-plus mr-2"></i>
            Create New Invoice
        </a>
    </div>
</div>

<!-- Invoice Filters -->
<div class="bg-white rounded-xl p-4 mb-6 shadow-sm border border-gray-100">
    <div class="flex flex-wrap items-center gap-4">
        <div class="flex-1 min-w-[200px]">
            <input type="text" id="invoiceSearch" placeholder="Search by client or invoice number..." class="w-full px-4 py-2 rounded-lg border border-gray-300 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
        </div>
        <div class="flex space-x-2 overflow-x-auto">
            <button data-filter="all" class="filter-btn px-4 py-2 rounded-lg bg-blue-600 text-white text-sm font-medium">All</button>
            <button data-filter="pending" class="filter-btn px-4 py-2 rounded-lg bg-gray-100 text-gray-700 text-sm font-medium hover:bg-gray-200">Pending</button>
            <button data-filter="paid" class="filter-btn px-4 py-2 rounded-lg bg-gray-100 text-gray-700 text-sm font-medium hover:bg-gray-200">Paid</button>
            <button data-filter="overdue" class="filter-btn px-4 py-2 rounded-lg bg-gray-100 text-gray-700 text-sm font-medium hover:bg-gray-200">Overdue</button>
        </div>
        <div class="flex items-center space-x-2">
            <input type="date" id="dateFrom" class="px-3 py-2 border border-gray-300 rounded-lg text-sm">
            <span class="text-gray-500">to</span>
            <input type="date" id="dateTo" class="px-3 py-2 border border-gray-300 rounded-lg text-sm">
            <button id="filterDate" class="px-4 py-2 bg-blue-600 text-white rounded-lg text-sm hover:bg-blue-700">Filter</button>
        </div>
    </div>
</div>


<!-- Stats Cards -->
<div class="grid grid-cols-1 md:grid-cols-4 gap-6 mt-8">
    <div class="bg-white rounded-xl p-6 shadow-sm border border-gray-100">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-gray-500 text-sm">Total Invoices</p>
                <h3 class="text-2xl font-bold text-gray-800 mt-1">{{ $invoices->total() }}</h3>
            </div>
            <div class="w-12 h-12 rounded-lg bg-blue-100 flex items-center justify-center">
                <i class="fas fa-file-invoice text-blue-600 text-xl"></i>
            </div>
        </div>
    </div>
    
    <div class="bg-white rounded-xl p-6 shadow-sm border border-gray-100">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-gray-500 text-sm">Pending</p>
                <h3 class="text-2xl font-bold text-gray-800 mt-1">
                    {{ \App\Models\Invoice::where('status', 'pending')->count() }}
                </h3>
            </div>
            <div class="w-12 h-12 rounded-lg bg-yellow-100 flex items-center justify-center">
                <i class="fas fa-clock text-yellow-600 text-xl"></i>
            </div>
        </div>
    </div>
    
    <div class="bg-white rounded-xl p-6 shadow-sm border border-gray-100">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-gray-500 text-sm">Paid</p>
                <h3 class="text-2xl font-bold text-gray-800 mt-1">
                    {{ \App\Models\Invoice::where('status', 'paid')->count() }}
                </h3>
            </div>
            <div class="w-12 h-12 rounded-lg bg-green-100 flex items-center justify-center">
                <i class="fas fa-check-circle text-green-600 text-xl"></i>
            </div>
        </div>
    </div>
    
    <div class="bg-white rounded-xl p-6 shadow-sm border border-gray-100">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-gray-500 text-sm">Total Revenue</p>
                <h3 class="text-2xl font-bold text-gray-800 mt-1">
                    ₹{{ number_format(\App\Models\Invoice::where('status', 'paid')->sum('grand_total')) }}
                </h3>
            </div>
            <div class="w-12 h-12 rounded-lg bg-indigo-100 flex items-center justify-center">
                <i class="fas fa-rupee-sign text-indigo-600 text-xl"></i>
            </div>
        </div>
    </div>
</div>



<!-- Invoice Table -->
<div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead class="bg-gray-50">
                <tr>
                    <th class="py-3 px-6 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Invoice #</th>
                    <th class="py-3 px-6 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Client</th>
                    <th class="py-3 px-6 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                    <th class="py-3 px-6 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Due Date</th>
                    <th class="py-3 px-6 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Amount</th>
                    <th class="py-3 px-6 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tax</th>
                    <th class="py-3 px-6 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                    <th class="py-3 px-6 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                @forelse($invoices as $invoice)
                <tr class="hover:bg-gray-50 transition duration-150">
                    <td class="py-3 px-6">
                        <div class="flex items-center">
                            <div class="w-8 h-8 rounded-lg bg-gradient-to-r from-blue-100 to-indigo-100 mr-3 flex items-center justify-center">
                                <i class="fas fa-file-invoice text-blue-600 text-sm"></i>
                            </div>
                            <div>
                                <p class="font-medium text-gray-800">{{ $invoice->invoice_number }}</p>
                                <p class="text-xs text-gray-500">{{ $invoice->company_name }}</p>
                            </div>
                        </div>
                    </td>
                    <td class="py-3 px-6">
                        <div>
                            <p class="font-medium text-gray-800">{{ $invoice->client_name }}</p>
                            <p class="text-sm text-gray-500">{{ $invoice->client_email }}</p>
                        </div>
                    </td>
                    <td class="py-3 px-6">
                        <span class="text-gray-800">{{ $invoice->invoice_date->format('d M Y') }}</span>
                    </td>
                    <td class="py-3 px-6">
                        <span class="text-gray-800">
                            {{ $invoice->due_date ? $invoice->due_date->format('d M Y') : 'N/A' }}
                        </span>
                    </td>
                    <td class="py-3 px-6">
                        <span class="font-medium text-gray-800">{{ $invoice->currency }}{{ number_format($invoice->grand_total, 2) }}</span>
                    </td>
                    <td class="py-3 px-6">
                        <span class="text-gray-800">{{ $invoice->tax_rate }}% {{ strtoupper($invoice->tax_type) }}</span>
                    </td>
                    <td class="py-3 px-6">
                        <span class="status-badge status-{{ $invoice->status }}">{{ ucfirst($invoice->status) }}</span>
                    </td>
                    <td class="py-3 px-6">
                        <div class="flex space-x-2">
                            <a href="{{ route('superadmin.invoices.show', $invoice) }}" class="p-2 text-blue-600 hover:text-blue-800 hover:bg-blue-50 rounded transition duration-150" title="View Invoice">
                                <i class="fas fa-eye"></i>
                            </a>
                            <a href="{{ route('superadmin.invoices.edit', $invoice) }}" class="p-2 text-green-600 hover:text-green-800 hover:bg-green-50 rounded transition duration-150" title="Edit">
                                <i class="fas fa-edit"></i>
                            </a>
                            <a href="{{ route('superadmin.invoices.download', $invoice) }}" class="p-2 text-purple-600 hover:text-purple-800 hover:bg-purple-50 rounded transition duration-150" title="Download PDF">
                                <i class="fas fa-file-pdf"></i>
                            </a>
                            <form action="{{ route('superadmin.invoices.destroy', $invoice) }}" method="POST" class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="p-2 text-red-600 hover:text-red-800 hover:bg-red-50 rounded transition duration-150" title="Delete" onclick="return confirm('Are you sure you want to delete this invoice?')">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" class="py-8 px-6 text-center text-gray-500">
                        <i class="fas fa-file-invoice text-3xl mb-2 text-gray-300"></i>
                        <p>No invoices found. <a href="{{ route('superadmin.invoices.create') }}" class="text-blue-600 hover:text-blue-800 font-medium">Create your first invoice</a></p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($invoices->hasPages())
    <div class="px-6 py-4 border-t border-gray-200">
        {{ $invoices->links() }}
    </div>
    @endif
</div>



@endsection

@section('styles')
<style>
    .status-badge {
        padding: 4px 12px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 500;
        display: inline-block;
    }
    .status-pending {
        background-color: rgba(245, 158, 11, 0.1);
        color: rgb(146, 64, 14);
    }
    .status-paid {
        background-color: rgba(34, 197, 94, 0.1);
        color: rgb(21, 128, 61);
    }
    .status-overdue {
        background-color: rgba(239, 68, 68, 0.1);
        color: rgb(185, 28, 28);
    }
    .status-cancelled {
        background-color: rgba(107, 114, 128, 0.1);
        color: rgb(55, 65, 81);
    }
</style>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Filter functionality
        const filterButtons = document.querySelectorAll('.filter-btn');
        const rows = document.querySelectorAll('tbody tr');
        
        filterButtons.forEach(button => {
            button.addEventListener('click', function() {
                const filter = this.getAttribute('data-filter');
                
                // Update active button
                filterButtons.forEach(btn => {
                    btn.classList.remove('bg-blue-600', 'text-white');
                    btn.classList.add('bg-gray-100', 'text-gray-700', 'hover:bg-gray-200');
                });
                this.classList.remove('bg-gray-100', 'text-gray-700', 'hover:bg-gray-200');
                this.classList.add('bg-blue-600', 'text-white');
                
                // Filter rows
                rows.forEach(row => {
                    if (filter === 'all') {
                        row.style.display = '';
                    } else {
                        const statusCell = row.querySelector('.status-badge');
                        if (statusCell && statusCell.classList.contains(`status-${filter}`)) {
                            row.style.display = '';
                        } else {
                            row.style.display = 'none';
                        }
                    }
                });
            });
        });
        
        // Search functionality
        const searchInput = document.getElementById('invoiceSearch');
        searchInput.addEventListener('input', function() {
            const searchTerm = this.value.toLowerCase();
            
            rows.forEach(row => {
                const invoiceNo = row.querySelector('.font-medium.text-gray-800:first-child')?.textContent.toLowerCase() || '';
                const clientName = row.querySelector('td:nth-child(2) .font-medium')?.textContent.toLowerCase() || '';
                const companyName = row.querySelector('.text-xs.text-gray-500')?.textContent.toLowerCase() || '';
                
                if (invoiceNo.includes(searchTerm) || clientName.includes(searchTerm) || companyName.includes(searchTerm)) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            });
        });
        
        // Date filter
        document.getElementById('filterDate').addEventListener('click', function() {
            const dateFrom = document.getElementById('dateFrom').value;
            const dateTo = document.getElementById('dateTo').value;
            
            if (!dateFrom && !dateTo) return;
            
            rows.forEach(row => {
                const dateCell = row.querySelector('td:nth-child(3) span')?.textContent.trim() || '';
                // Convert date string to Date object
                const rowDate = new Date(dateCell);
                
                let showRow = true;
                if (dateFrom) {
                    const fromDate = new Date(dateFrom);
                    if (rowDate < fromDate) showRow = false;
                }
                if (dateTo) {
                    const toDate = new Date(dateTo);
                    if (rowDate > toDate) showRow = false;
                }
                
                row.style.display = showRow ? '' : 'none';
            });
        });
    });
</script>
@endsection