@extends('superadmin.layout.app')

@section('title', 'Settings')

@section('content')
<div class="flex flex-col md:flex-row md:items-center justify-between mb-8">
    <div>
        <h1 class="text-2xl font-bold text-gray-800">Settings</h1>
        <p class="text-gray-600">Configure your CRM admin settings</p>
    </div>
</div>

<!-- Settings Content -->
<div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
    <h3 class="text-lg font-bold text-gray-800 mb-6">General Settings</h3>
    
    <form action="{{ route('superadmin.settings.update') }}" method="POST">
        @csrf
        
        <div class="space-y-6">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">CRM Name</label>
                <input type="text" name="crm_name" value="CRM Admin Pro" class="w-full md:w-1/2 px-4 py-2 rounded-lg border border-gray-300 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Default Currency</label>
                <select name="currency" class="w-full md:w-1/2 px-4 py-2 rounded-lg border border-gray-300 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    <option value="INR" selected>Indian Rupee (₹)</option>
                    <option value="USD">US Dollar ($)</option>
                    <option value="EUR">Euro (€)</option>
                </select>
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Trial Period (Days)</label>
                <input type="number" name="trial_days" value="30" class="w-full md:w-1/2 px-4 py-2 rounded-lg border border-gray-300 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Subscription Price (Yearly)</label>
                <div class="flex items-center w-full md:w-1/2">
                    <span class="mr-2">₹</span>
                    <input type="number" name="yearly_price" value="5000" class="flex-1 px-4 py-2 rounded-lg border border-gray-300 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                </div>
            </div>
            
            <div class="pt-4 border-t border-gray-200">
                <button type="submit" class="px-6 py-2 bg-gradient-to-r from-blue-600 to-indigo-600 text-white rounded-lg hover:from-blue-700 hover:to-indigo-700 transition duration-200 font-medium">
                    Save Changes
                </button>
            </div>
        </div>
    </form>
</div>
@endsection