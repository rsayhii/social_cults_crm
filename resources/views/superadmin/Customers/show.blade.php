@extends('superadmin.layout.app')

@section('title', 'Customer Details')

@section('content')
<div class="flex items-center mb-8">
    <a href="{{ route('superadmin.customers.index') }}" class="mr-4 w-10 h-10 rounded-full bg-gray-100 flex items-center justify-center text-gray-600 hover:bg-gray-200 transition duration-200">
        <i class="fas fa-arrow-left"></i>
    </a>
    <div class="flex-1">
        <h1 class="text-2xl font-bold text-gray-800">Customer Details</h1>
        <p class="text-gray-600">View and manage customer subscription information</p>
    </div>
    <div class="flex space-x-2">
        <a href="{{ route('superadmin.customers.edit', $customer) }}" class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition duration-200 font-medium flex items-center">
            <i class="fas fa-edit mr-2"></i>
            Edit
        </a>
        <form action="{{ route('superadmin.customers.destroy', $customer) }}" method="POST" class="inline">
            @csrf
            @method('DELETE')
            <button type="submit" onclick="return confirm('Are you sure you want to delete this customer?')" class="px-4 py-2 border border-red-300 text-red-600 rounded-lg hover:bg-red-50 transition duration-200 font-medium flex items-center">
                <i class="fas fa-trash mr-2"></i>
                Delete
            </button>
        </form>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <!-- Customer Info Card -->
    <div class="lg:col-span-2">
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 mb-6">
            <div class="flex items-center justify-between mb-6">
                <h2 class="text-xl font-bold text-gray-800">Customer Information</h2>
                <span class="status-badge status-{{ $customer->status }}">{{ ucfirst($customer->status) }}</span>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <p class="text-gray-500 text-sm">Company Name</p>
                    <p class="font-medium text-gray-800 text-lg">{{ $customer->name }}</p>
                </div>
                <div>
                    <p class="text-gray-500 text-sm">Company Email</p>
                    <p class="font-medium text-gray-800">{{ $customer->email }}</p>
                </div>
                <div>
                    <p class="text-gray-500 text-sm">Phone Number</p>
                    <p class="font-medium text-gray-800">{{ $customer->phone ?? 'N/A' }}</p>
                </div>
                <div>
                    <p class="text-gray-500 text-sm">Admin Name</p>
                    <p class="font-medium text-gray-800">{{ $customer->user->name ?? 'N/A' }}</p>
                </div>
                <div>
                    <p class="text-gray-500 text-sm">Admin Email</p>
                    <p class="font-medium text-gray-800">{{ $customer->user->email ?? 'N/A' }}</p>
                </div>
                <div class="md:col-span-2">
                    <p class="text-gray-500 text-sm">Address</p>
                    <p class="font-medium text-gray-800">{{ $customer->address ?? 'N/A' }}</p>
                </div>
            </div>
        </div>
        
        <!-- Subscription Timeline -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 mb-6">
            <h2 class="text-xl font-bold text-gray-800 mb-6">Subscription Details</h2>
            
            <div class="space-y-6">
                @if($customer->trial_ends_at)
                <div class="flex items-start">
                    <div class="w-10 h-10 rounded-full bg-blue-100 flex items-center justify-center mr-4 flex-shrink-0">
                        <i class="fas fa-clock text-blue-600"></i>
                    </div>
                    <div class="flex-1">
                        <div class="flex justify-between items-center mb-1">
                            <h3 class="font-medium text-gray-800">Trial Period</h3>
                            @if($customer->trial_ends_at->isFuture())
                            <span class="status-badge status-trial">Active</span>
                            @else
                            <span class="text-gray-500 text-sm">Expired</span>
                            @endif
                        </div>
                        <p class="text-gray-600 text-sm">
                            Ends: {{ $customer->trial_ends_at->format('M d, Y') }}
                        </p>
                        @php
                            $trialDaysRemaining = $customer->trial_ends_at->diffInDays(now());
                        @endphp
                        @if($customer->trial_ends_at->isFuture())
                        <div class="mt-2">
                            <div class="w-full bg-gray-200 rounded-full h-2">
                                <div class="bg-blue-600 h-2 rounded-full" style="width: {{ min(100, (30 - $trialDaysRemaining) / 30 * 100) }}%"></div>
                            </div>
                            <p class="text-gray-500 text-sm mt-1">{{ 30 - $trialDaysRemaining }} days used ({{ $customer->trial_ends_at->diffForHumans() }})</p>
                        </div>
                        @else
                         <p class="text-red-500 text-sm mt-1">Trial Expired</p>
                        @endif
                    </div>
                </div>
                @endif
                
                <div class="flex items-start">
                    <div class="w-10 h-10 rounded-full bg-green-100 flex items-center justify-center mr-4 flex-shrink-0">
                        <i class="fas fa-id-card text-green-600"></i>
                    </div>
                    <div class="flex-1">
                        <div class="flex justify-between items-center mb-1">
                            <h3 class="font-medium text-gray-800">Plan Status</h3>
                            @if($customer->is_paid)
                            <span class="status-badge status-active">Paid</span>
                            @else
                            <span class="text-gray-500 text-sm">Free/Trial</span>
                            @endif
                        </div>
                    </div>
                </div>

            </div>
        </div>
        
        <!-- Recent Activity -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
            <h2 class="text-xl font-bold text-gray-800 mb-6">Recent Activity</h2>
            
            <div class="space-y-4">
                <div class="flex items-start">
                    <div class="w-8 h-8 rounded-full bg-blue-100 flex items-center justify-center mr-3 flex-shrink-0">
                        <i class="fas fa-user-plus text-blue-600 text-sm"></i>
                    </div>
                    <div>
                        <p class="font-medium text-gray-800">Company Created</p>
                        <p class="text-sm text-gray-600">Account was created on {{ $customer->created_at->format('M d, Y') }}</p>
                        <p class="text-xs text-gray-500 mt-1">{{ $customer->created_at->diffForHumans() }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Subscription Info Card -->
    <div>
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 mb-6">
            <h2 class="text-xl font-bold text-gray-800 mb-6">Company Information</h2>
            
            <div class="space-y-4">
                <div>
                    <p class="text-gray-500 text-sm">GSTIN</p>
                    <p class="font-medium text-gray-800">{{ $customer->gstin ?? 'N/A' }}</p>
                </div>

                <div>
                    <p class="text-gray-500 text-sm">Slug</p>
                    <p class="font-mono text-gray-600">{{ $customer->slug }}</p>
                </div>
                
                <div>
                    <p class="text-gray-500 text-sm">Status</p>
                    <p class="font-medium {{ $customer->status == 'active' ? 'text-green-600' : ($customer->status == 'trial' ? 'text-blue-600' : 'text-red-600') }}">
                        {{ ucfirst($customer->status) }}
                    </p>
                </div>
                
                <!-- <div>
                    <p class="text-gray-500 text-sm">Total Paid</p>
                    <p class="font-medium text-gray-800">₹{{ number_format($customer->payments->sum('amount')) }}</p>
                </div> -->
            </div>
            
            <!-- <div class="mt-8 pt-6 border-t border-gray-200">
                <div class="grid grid-cols-2 gap-2">
                    <button class="px-3 py-2 border border-gray-300 rounded text-sm font-medium hover:bg-gray-50">Extend Trial</button>
                </div>
            </div> -->
        </div>
        
        <!-- CRM Access Card -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
            <h2 class="text-xl font-bold text-gray-800 mb-6">CRM Access</h2>
            
            <div class="space-y-4">
                <div>
                    <p class="text-gray-500 text-sm">Login URL (Autogenerated)</p>
                     <p class="text-sm text-gray-500">Subdomain/Slug based login usually</p>
                </div>
                
                <div>
                    <p class="text-gray-500 text-sm">Customer Since</p>
                    <p class="font-medium text-gray-800">{{ $customer->created_at->format('M d, Y') }}</p>
                </div>
                
                <div>
                    <p class="text-gray-500 text-sm">Last Updated</p>
                    <p class="font-medium text-gray-800">{{ $customer->updated_at->format('M d, Y') }}</p>
                </div>
            </div>
            
            <div class="mt-8 pt-6 border-t border-gray-200">
                <h3 class="font-medium text-gray-800 mb-3">Danger Zone</h3>
                <form action="{{ route('superadmin.customers.destroy', $customer->id) }}" method="POST" class="inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" onclick="return confirm('Are you absolutely sure? This will permanently delete the customer and all their data.')" class="w-full px-4 py-2 border border-red-300 text-red-600 rounded text-sm font-medium hover:bg-red-50 flex items-center justify-center">
                        <i class="fas fa-trash mr-2"></i>
                        Delete Customer Account
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

@section('scripts')
<script>
    function copyToClipboard(text) {
        navigator.clipboard.writeText(text).then(() => {
            alert('Copied to clipboard!');
        });
    }
</script>
@endsection