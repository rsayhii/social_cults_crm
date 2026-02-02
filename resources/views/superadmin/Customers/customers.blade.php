@extends('superadmin.layout.app')

@section('title', 'Customers')

@section('content')
<div class="flex flex-col md:flex-row md:items-center justify-between mb-8">
    <div>
        <h1 class="text-2xl font-bold text-gray-800">Customer Management</h1>
        <p class="text-gray-600">View and manage all your CRM customers</p>
    </div>
    <div class="mt-4 md:mt-0">
        <a href="{{ route('superadmin.customers.create') }}" class="bg-gradient-to-r from-blue-600 to-indigo-600 text-white px-4 py-2 rounded-lg hover:from-blue-700 hover:to-indigo-700 transition duration-200 font-medium flex items-center">
            <i class="fas fa-plus mr-2"></i>
            Add New Customer
        </a>
    </div>
</div>

<!-- Customer Table -->
<div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead class="bg-gray-50">
                <tr>
                    <th class="py-3 px-6 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Customer Name</th>
                    <th class="py-3 px-6 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Email</th>
                    <th class="py-3 px-6 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Phone</th>
                    <th class="py-3 px-6 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Plan</th>
                    <th class="py-3 px-6 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                    <th class="py-3 px-6 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Amount Paid</th>
                    <th class="py-3 px-6 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                @foreach($customers as $customer)
                <tr>
                    <td class="py-3 px-6">
                        <div class="flex items-center">
                            <div class="w-8 h-8 rounded-full bg-gradient-to-r from-blue-500 to-purple-500 mr-3"></div>
                            <div>
                                <p class="font-medium text-gray-800">{{ $customer->name }}</p>
                                <p class="text-sm text-gray-500">{{ $customer->business_name }}</p>
                            </div>
                        </div>
                    </td>
                    <td class="py-3 px-6">
                        <span class="text-gray-800">{{ $customer->email }}</span>
                    </td>
                    <td class="py-3 px-6">
                        <span class="text-gray-800">{{ $customer->phone ?? 'N/A' }}</span>
                    </td>
                    <td class="py-3 px-6">
                        <span class="text-gray-800">{{ $customer->plan }}</span>
                    </td>
                    <td class="py-3 px-6">
                        <span class="status-badge status-{{ $customer->status }}">{{ ucfirst($customer->status) }}</span>
                    </td>
                    <td class="py-3 px-6">
                        <span class="font-medium text-gray-800">₹{{ number_format($customer->amount_paid) }}</span>
                    </td>
                    <td class="py-3 px-6">
                        <div class="flex space-x-2">
                            <a href="{{ route('superadmin.customers.show', $customer) }}" class="p-1 text-blue-600 hover:text-blue-800" title="View Details">
                                <i class="fas fa-eye"></i>
                            </a>
                            <a href="{{ route('superadmin.customers.edit', $customer) }}" class="p-1 text-green-600 hover:text-green-800" title="Edit">
                                <i class="fas fa-edit"></i>
                            </a>
                            <form action="{{ route('superadmin.customers.destroy', $customer) }}" method="POST" class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="p-1 text-red-600 hover:text-red-800" title="Delete" onclick="return confirm('Are you sure?')">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <div class="px-6 py-4 border-t border-gray-200">
        {{ $customers->links() }}
    </div>
</div>
@endsection