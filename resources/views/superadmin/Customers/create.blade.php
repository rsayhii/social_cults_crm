@extends('superadmin.layout.app')

@section('title', 'Add New Customer')

@section('content')
<div class="flex flex-col md:flex-row md:items-center justify-between mb-8">
    <div>
        <h1 class="text-2xl font-bold text-gray-800">Add New Customer</h1>
        <p class="text-gray-600">Create a new customer account</p>
    </div>
    <div class="mt-4 md:mt-0">
        <a href="{{ route('superadmin.customers.index') }}" class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition duration-200 font-medium flex items-center">
            <i class="fas fa-arrow-left mr-2"></i>
            Back to Customers
        </a>
    </div>
</div>

<div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
    <form action="{{ route('superadmin.customers.store') }}" method="POST">
        @csrf
        
        <h3 class="text-lg font-bold text-gray-800 mb-4 border-b pb-2">Company Details</h3>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Company Name *</label>
                <input type="text" name="company_name" required class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent" placeholder="Enter company name">
                @error('company_name')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Company Email *</label>
                <input type="email" name="email" required class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent" placeholder="Enter company email">
                @error('email')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Phone Number</label>
                <input type="tel" name="phone" class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent" placeholder="Enter phone number">
                @error('phone')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Address</label>
                <textarea name="address" rows="1" class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent" placeholder="Enter full address"></textarea>
                @error('address')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>
        </div>

        <h3 class="text-lg font-bold text-gray-800 mb-4 border-b pb-2">Admin Account Details</h3>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Admin Name *</label>
                <input type="text" name="admin_name" required class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent" placeholder="Enter admin name">
                @error('admin_name')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Admin Email *</label>
                <input type="email" name="admin_email" required class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent" placeholder="Enter admin login email">
                @error('admin_email')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Password *</label>
                <input type="password" name="password" required class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent" placeholder="Enter password (min 8 chars)">
                @error('password')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>
        </div>
        
        <h3 class="text-lg font-bold text-gray-800 mb-4 border-b pb-2">Subscription & Details</h3>
        <div class="mb-6">
            <label class="block text-sm font-medium text-gray-700 mb-3">Subscription Type *</label>
            <div class="flex space-x-6">
                <label class="flex items-center">
                    <input type="radio" name="subscription_type" value="trial" checked class="mr-3 h-5 w-5 text-blue-600 focus:ring-blue-500 border-gray-300">
                    <span class="text-gray-700">
                        <span class="font-medium">30-Day Trial</span>
                        <span class="text-gray-500 text-sm block">Free trial for 30 days</span>
                    </span>
                </label>
                <label class="flex items-center">
                    <input type="radio" name="subscription_type" value="paid" class="mr-3 h-5 w-5 text-blue-600 focus:ring-blue-500 border-gray-300">
                    <span class="text-gray-700">
                        <span class="font-medium">Paid Subscription</span>
                        <span class="text-gray-500 text-sm block">₹5,000/year (Billed annually)</span>
                    </span>
                </label>
            </div>
            @error('subscription_type')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>
        
        <div class="mb-6">
            <label class="block text-sm font-medium text-gray-700 mb-2">Initial Status</label>
            <select name="status" class="w-full md:w-1/2 px-4 py-3 rounded-lg border border-gray-300 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                <option value="trial">Trial</option>
                <option value="active">Active</option>
                <option value="pending">Payment Pending</option>
            </select>
            @error('status')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>
        
        <div class="flex justify-end space-x-3 pt-6 border-t border-gray-200">
            <a href="{{ route('superadmin.customers.index') }}" class="px-6 py-3 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition duration-200 font-medium">
                Cancel
            </a>
            <button type="submit" class="px-6 py-3 bg-gradient-to-r from-blue-600 to-indigo-600 text-white rounded-lg hover:from-blue-700 hover:to-indigo-700 transition duration-200 font-medium">
                <i class="fas fa-plus mr-2"></i>
                Create Customer
            </button>
        </div>
    </form>
</div>

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const subscriptionTypeRadios = document.querySelectorAll('input[name="subscription_type"]');
        
        function updatePlanStatus() {
            const selectedValue = document.querySelector('input[name="subscription_type"]:checked').value;
            const statusSelect = document.querySelector('select[name="status"]');
            
            if (selectedValue === 'trial') {
                statusSelect.value = 'trial';
            } else {
                statusSelect.value = 'active';
            }
        }
        
        subscriptionTypeRadios.forEach(radio => {
            radio.addEventListener('change', updatePlanStatus);
        });
        
        // Initial call
        updatePlanStatus();
    });
</script>
@endsection