@extends('superadmin.layout.app')

@section('title', 'Edit Customer')

@section('content')
<div class="flex flex-col md:flex-row md:items-center justify-between mb-8">
    <div>
        <h1 class="text-2xl font-bold text-gray-800">Edit Customer</h1>
        <p class="text-gray-600">Update customer information</p>
    </div>
    <div class="mt-4 md:mt-0">
        <a href="{{ route('superadmin.customers.index') }}" class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition duration-200 font-medium flex items-center">
            <i class="fas fa-arrow-left mr-2"></i>
            Back to Customers
        </a>
    </div>
</div>

<div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
    <form action="{{ route('superadmin.customers.update', $customer) }}" method="POST">
        @csrf
        @method('PUT')
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Full Name *</label>
                <input type="text" name="name" value="{{ old('name', $customer->name) }}" required class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent" placeholder="Enter customer name">
                @error('name')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Email Address *</label>
                <input type="email" name="email" value="{{ old('email', $customer->email) }}" required class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent" placeholder="Enter email address">
                @error('email')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Phone Number</label>
                <input type="tel" name="phone" value="{{ old('phone', $customer->phone) }}" class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent" placeholder="Enter phone number">
                @error('phone')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Business Name</label>
                <input type="text" name="business_name" value="{{ old('business_name', $customer->business_name) }}" class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent" placeholder="Enter business name">
                @error('business_name')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>
            
            <div class="md:col-span-2">
                <label class="block text-sm font-medium text-gray-700 mb-2">Address</label>
                <textarea name="address" rows="3" class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent" placeholder="Enter full address">{{ old('address', $customer->address) }}</textarea>
                @error('address')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Current Plan</label>
                <input type="text" value="{{ $customer->plan }}" class="w-full px-4 py-3 rounded-lg border border-gray-300 bg-gray-50 text-gray-500" readonly>
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Status *</label>
                <select name="status" class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    <option value="active" {{ $customer->status == 'active' ? 'selected' : '' }}>Active</option>
                    <option value="trial" {{ $customer->status == 'trial' ? 'selected' : '' }}>Trial</option>
                    <option value="expired" {{ $customer->status == 'expired' ? 'selected' : '' }}>Expired</option>
                    <option value="pending" {{ $customer->status == 'pending' ? 'selected' : '' }}>Payment Pending</option>
                    <option value="cancelled" {{ $customer->status == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                </select>
                @error('status')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Trial Start Date</label>
                <input type="date" name="trial_start_date" value="{{ old('trial_start_date', $customer->trial_start_date) }}" class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Trial End Date</label>
                <input type="date" name="trial_end_date" value="{{ old('trial_end_date', $customer->trial_end_date) }}" class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Subscription Start</label>
                <input type="date" name="subscription_start_date" value="{{ old('subscription_start_date', $customer->subscription_start_date) }}" class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Subscription End</label>
                <input type="date" name="subscription_end_date" value="{{ old('subscription_end_date', $customer->subscription_end_date) }}" class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Amount Paid (₹)</label>
                <input type="number" name="amount_paid" value="{{ old('amount_paid', $customer->amount_paid) }}" step="0.01" class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent" placeholder="0.00">
                @error('amount_paid')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Payment Method</label>
                <select name="payment_method" class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    <option value="">Select Method</option>
                    <option value="credit_card" {{ $customer->payment_method == 'credit_card' ? 'selected' : '' }}>Credit Card</option>
                    <option value="bank_transfer" {{ $customer->payment_method == 'bank_transfer' ? 'selected' : '' }}>Bank Transfer</option>
                    <option value="upi" {{ $customer->payment_method == 'upi' ? 'selected' : '' }}>UPI</option>
                    <option value="cash" {{ $customer->payment_method == 'cash' ? 'selected' : '' }}>Cash</option>
                </select>
            </div>
        </div>
        
        <div class="mb-6">
            <label class="block text-sm font-medium text-gray-700 mb-2">License Key</label>
            <div class="flex items-center">
                <input type="text" value="{{ $customer->license_key }}" class="w-full px-4 py-3 rounded-lg border border-gray-300 bg-gray-50 text-gray-500 font-mono" readonly>
                <button type="button" onclick="copyToClipboard('{{ $customer->license_key }}')" class="ml-2 px-4 py-3 bg-gray-100 text-gray-600 rounded-lg hover:bg-gray-200 transition duration-200">
                    <i class="fas fa-copy"></i>
                </button>
            </div>
        </div>
        
        <div class="mb-6">
            <label class="block text-sm font-medium text-gray-700 mb-2">Login URL</label>
            <div class="flex items-center">
                <input type="text" value="{{ $customer->login_url }}" class="w-full px-4 py-3 rounded-lg border border-gray-300 bg-gray-50 text-blue-500 truncate" readonly>
                <a href="{{ $customer->login_url }}" target="_blank" class="ml-2 px-4 py-3 bg-blue-100 text-blue-600 rounded-lg hover:bg-blue-200 transition duration-200">
                    <i class="fas fa-external-link-alt"></i>
                </a>
            </div>
        </div>
        
        <div class="flex justify-end space-x-3 pt-6 border-t border-gray-200">
            <a href="{{ route('superadmin.customers.show', $customer) }}" class="px-6 py-3 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition duration-200 font-medium">
                Cancel
            </a>
            <button type="submit" class="px-6 py-3 bg-gradient-to-r from-blue-600 to-indigo-600 text-white rounded-lg hover:from-blue-700 hover:to-indigo-700 transition duration-200 font-medium">
                <i class="fas fa-save mr-2"></i>
                Update Customer
            </button>
        </div>
    </form>
</div>

@section('scripts')
<script>
    function copyToClipboard(text) {
        navigator.clipboard.writeText(text).then(() => {
            alert('License key copied to clipboard!');
        });
    }
</script>
@endsection