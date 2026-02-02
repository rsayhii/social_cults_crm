@extends('components.layout')

@section('content')
<div class="py-4 md:py-6">
    <div class="max-w-7xl mx-auto px-3 sm:px-4 lg:px-8">
        <!-- Header -->
        <div class="bg-white overflow-hidden shadow-sm rounded-lg sm:rounded-lg mb-4 md:mb-6">
            <div class="p-4 md:p-6 bg-white shadow text-black">
                <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center gap-3">
                    <div class="mb-3 sm:mb-0">
                        <h2 class="text-xl sm:text-2xl font-bold">Salary Management</h2>
                        <p class="text-blue-900 mt-1 text-sm sm:text-base">Generate and manage employee salaries</p>
                    </div>
                    <div class="flex flex-wrap gap-2 sm:gap-3">
                        <a href="{{ route('salary.config') }}" 
                           class="hidden inline-flex items-center justify-center px-3 sm:px-4 py-2 bg-white/40 hover:bg-white/30 rounded-lg transition-colors text-sm sm:text-base min-w-[120px]">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 sm:h-5 sm:w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                            <span class="truncate">Settings</span>
                        </a>
                        <a href="{{ route('salary.list') }}" 
                           class="inline-flex items-center justify-center px-3 sm:px-4 py-2 bg-white/40 hover:bg-white/30 rounded-lg transition-colors text-sm sm:text-base min-w-[120px]">
                           <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="h-5 w-5">
                            <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8l-6-6zM16 18H8v-2h8v2zm0-4H8v-2h8v2zm-3-8V3.5L18.5 9H13a1 1 0 0 1-1-1z"/>
                            </svg>
                            <span class="truncate">Salary Records</span>
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-4 md:gap-6">
            <!-- Salary Generation Form -->
            <div class="lg:col-span-2">
                <div class="bg-white overflow-hidden shadow-sm rounded-lg sm:rounded-lg">
                    <div class="p-4 md:p-6 border-b border-gray-200">
                        <h3 class="text-lg font-semibold text-gray-800">Generate Salary</h3>
                        <p class="text-gray-600 text-sm mt-1">Select employee and month to generate salary slip</p>
                    </div>
                    
                    <form id="salaryForm" class="p-4 md:p-6 space-y-4 md:space-y-6">
                        @csrf
                        
                        <!-- Employee Selection -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                <span class="flex items-center">
                                    Select Employee
                                    <span class="text-red-500 ml-1">*</span>
                                </span>
                            </label>
                            <div class="relative">
                                <select id="employee_id" 
                                        name="employee_id"
                                        required
                                        class="w-full px-3 sm:px-4 py-2 sm:py-3 text-sm sm:text-base border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition appearance-none cursor-pointer">
                                    <option value="" disabled selected>Choose an employee...</option>
                                    @foreach($employees as $employee)
                                        <option value="{{ $employee->id }}" 
                                                data-salary="{{ $employee->salary }}"
                                                class="py-2 truncate">
                                            <span class="truncate">{{ $employee->name }}</span> 
                                            <span class="text-gray-500 text-xs sm:text-sm">(₹{{ number_format($employee->salary) }})</span>
                                        </option>
                                    @endforeach
                                </select>
                                <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-gray-700">
                                    <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                        <path d="M9.293 12.95l.707.707L15.657 8l-1.414-1.414L10 10.828 5.757 6.586 4.343 8z"/>
                                    </svg>
                                </div>
                            </div>
                        </div>

                        <!-- Salary Period -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 md:gap-6">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    <span class="flex items-center">
                                        Month
                                        <span class="text-red-500 ml-1">*</span>
                                    </span>
                                </label>
                                <div class="relative">
                                    <select id="month" 
                                            name="month"
                                            required
                                            class="w-full px-3 sm:px-4 py-2 sm:py-3 text-sm sm:text-base border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition appearance-none cursor-pointer">
                                        @for($i = 1; $i <= 12; $i++)
                                            <option value="{{ $i }}" {{ $i == date('m') ? 'selected' : '' }}>
                                                {{ date('F', mktime(0, 0, 0, $i, 1)) }}
                                            </option>
                                        @endfor
                                    </select>
                                    <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-gray-700">
                                        <svg class="w-4 h-4 sm:w-5 sm:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                        </svg>
                                    </div>
                                </div>
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    <span class="flex items-center">
                                        Year
                                        <span class="text-red-500 ml-1">*</span>
                                    </span>
                                </label>
                                <div class="relative">
                                    <input type="number" 
                                           id="year" 
                                           name="year"
                                           value="{{ date('Y') }}"
                                           min="2000"
                                           max="2100"
                                           required
                                           class="w-full px-3 sm:px-4 py-2 sm:py-3 pl-8 sm:pl-10 text-sm sm:text-base border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition">
                                    <div class="absolute inset-y-0 left-0 pl-2 sm:pl-3 flex items-center pointer-events-none">
                                        <svg class="w-4 h-4 sm:w-5 sm:h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                        </svg>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Action Buttons -->
                        <div class="flex flex-col sm:flex-row gap-3 pt-4">
                            <button type="submit"
                                    id="generateBtn"
                                    class="w-full sm:flex-1 bg-gradient-to-r from-blue-600 to-blue-700 hover:from-blue-700 hover:to-blue-800 text-white font-medium py-2.5 sm:py-3 px-4 sm:px-6 rounded-lg transition-all duration-300 transform hover:-translate-y-0.5 shadow-lg hover:shadow-xl flex items-center justify-center text-sm sm:text-base">
                                <svg class="w-4 h-4 sm:w-5 sm:h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                <span class="truncate">Generate Salary</span>
                            </button>
                            
                            <button type="button"
                                    id="bulkGenerateBtn"
                                    class="w-full sm:flex-1 bg-gradient-to-r from-green-600 to-emerald-600 hover:from-green-700 hover:to-emerald-700 text-white font-medium py-2.5 sm:py-3 px-4 sm:px-6 rounded-lg transition-all duration-300 transform hover:-translate-y-0.5 shadow-lg hover:shadow-xl flex items-center justify-center text-sm sm:text-base">
                                <svg class="w-4 h-4 sm:w-5 sm:h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                                </svg>
                                <span class="truncate">Bulk Generate</span>
                            </button>
                        </div>
                    </form>
                </div>

                <!-- Result Container -->
                <div id="result" class="mt-4 md:mt-6"></div>
            </div>

            <!-- Sidebar - Info & Quick Stats -->
            <div class="space-y-4 md:space-y-6">
                <!-- Quick Stats -->
                <div class="bg-white overflow-hidden shadow-sm rounded-lg sm:rounded-lg">
                    <div class="p-4 md:p-6 border-b border-gray-200">
                        <h3 class="text-lg font-semibold text-gray-800">Quick Stats</h3>
                    </div>
                    <div class="p-4 md:p-6 space-y-3 md:space-y-4">
                        <div class="flex items-center justify-between p-3 bg-blue-50 rounded-lg">
                            <div class="flex items-center">
                                <div class="p-2 bg-blue-100 rounded-lg">
                                    <svg class="w-5 h-5 sm:w-6 sm:h-6 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                                    </svg>
                                </div>
                                <div class="ml-3">
                                    <p class="text-xs sm:text-sm text-gray-600">Total Employees</p>
                                    <p class="text-lg sm:text-xl font-bold text-gray-800">{{ $employees->count() }}</p>
                                </div>
                            </div>
                        </div>
                        
                        <div class="flex items-center justify-between p-3 bg-green-50 rounded-lg">
                            <div class="flex items-center">
                                <div class="p-2 bg-green-100 rounded-lg">
                                    <svg class="w-5 h-5 sm:w-6 sm:h-6 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                </div>
                                <div class="ml-3">
                                    <p class="text-xs sm:text-sm text-gray-600">Avg. Salary</p>
                                    <p class="text-lg sm:text-xl font-bold text-gray-800">
                                        ₹{{ number_format($employees->avg('salary') ?? 0) }}
                                    </p>
                                </div>
                            </div>
                        </div>

                        <div class="flex items-center justify-between p-3 bg-purple-50 rounded-lg">
                            <div class="flex items-center">
                                <div class="p-2 bg-purple-100 rounded-lg">
                                    <svg class="w-5 h-5 sm:w-6 sm:h-6 text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                                    </svg>
                                </div>
                                <div class="ml-3">
                                    <p class="text-xs sm:text-sm text-gray-600">This Month</p>
                                    <p class="text-lg sm:text-xl font-bold text-gray-800">
                                        {{ date('F Y') }}
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Recent Salary Slips -->
                <div class="bg-white overflow-hidden shadow-sm rounded-lg sm:rounded-lg">
                    <div class="p-4 md:p-6 border-b border-gray-200">
                        <div class="flex justify-between items-center">
                            <h3 class="text-lg font-semibold text-gray-800">Recent Slips</h3>
                            <a href="{{ route('salary.list') }}" class="text-xs sm:text-sm text-blue-600 hover:text-blue-800">View all</a>
                        </div>
                    </div>
                    <div class="p-4 md:p-6">
                        @if($recentSalaries->count() > 0)
                            <div class="space-y-2 md:space-y-3">
                                @foreach($recentSalaries as $salary)
                                <div class="flex items-center justify-between p-3 hover:bg-gray-50 rounded-lg transition-colors">
                                    <div class="min-w-0 flex-1 mr-3">
                                        <p class="font-medium text-gray-800 truncate text-sm sm:text-base">{{ $salary->employee->name }}</p>
                                        <p class="text-xs sm:text-sm text-gray-600">
                                            {{ \Carbon\Carbon::parse($salary->salary_month)->format('M Y') }}
                                        </p>
                                    </div>
                                    <div class="text-right flex-shrink-0">
                                        <p class="font-bold text-gray-800 text-sm sm:text-base">₹{{ number_format($salary->net_salary) }}</p>
                                        <span class="inline-block px-2 py-0.5 text-xs rounded-full 
                                            {{ $salary->status == 'paid' ? 'bg-green-100 text-green-800' : 
                                               ($salary->status == 'approved' ? 'bg-yellow-100 text-yellow-800' : 
                                               'bg-gray-100 text-gray-800') }}">
                                            {{ ucfirst($salary->status) }}
                                        </span>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        @else
                            <div class="text-center py-6 md:py-8">
                                <svg class="w-10 h-10 sm:w-12 sm:h-12 text-gray-400 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                </svg>
                                <p class="mt-3 text-gray-500 text-sm sm:text-base">No salary slips generated yet</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Export Modal -->
<div id="exportModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 p-4 hidden">
    <div class="bg-white rounded-xl p-4 md:p-6 w-full max-w-md mx-auto max-h-[90vh] overflow-y-auto">
        <div class="flex justify-between items-center mb-4">
            <h3 class="text-base md:text-lg font-semibold text-gray-800">Export Salary Report</h3>
            <button onclick="hideExportModal()" class="text-gray-400 hover:text-gray-600">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>
        
        <div class="space-y-4">
            <!-- File Name -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    File Name
                </label>
                <input type="text" 
                       id="exportFileName" 
                       class="w-full px-3 py-2 text-sm sm:text-base border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                       placeholder="Salary_Report_January_2024">
            </div>
            
            <!-- Export Format -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    Export Format
                </label>
                <div class="grid grid-cols-2 gap-2 sm:gap-3">
                    <label class="flex items-center p-2 sm:p-3 border border-green-200 rounded-lg hover:bg-green-50 transition-colors cursor-pointer text-sm sm:text-base">
                        <input type="radio" name="exportFormat" value="excel" checked class="mr-2">
                        <div>
                            <div class="font-medium text-gray-800">Excel</div>
                            <div class="text-xs text-gray-600">.xlsx format</div>
                        </div>
                    </label>
                    <label class="flex items-center p-2 sm:p-3 border border-red-200 rounded-lg hover:bg-red-50 transition-colors cursor-pointer text-sm sm:text-base">
                        <input type="radio" name="exportFormat" value="pdf" class="mr-2">
                        <div>
                            <div class="font-medium text-gray-800">PDF</div>
                            <div class="text-xs text-gray-600">.pdf format</div>
                        </div>
                    </label>
                </div>
            </div>
            
            <!-- Options -->
            <div>
                <label class="flex items-center text-sm sm:text-base">
                    <input type="checkbox" 
                           id="includeAll" 
                           class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                    <span class="ml-2 text-gray-700">Include all employees</span>
                </label>
            </div>
            
            <!-- Export Summary -->
            <div class="bg-blue-50 p-3 rounded-lg">
                <div class="flex justify-between text-xs sm:text-sm">
                    <span class="text-gray-600">Selected Period:</span>
                    <span class="font-medium" id="exportPeriodDisplay"></span>
                </div>
                <div class="flex justify-between text-xs sm:text-sm mt-1">
                    <span class="text-gray-600">Format:</span>
                    <span class="font-medium">Excel (.xlsx)</span>
                </div>
            </div>
            
            <!-- Action Buttons -->
            <div class="flex flex-col sm:flex-row sm:justify-end gap-2 sm:space-x-3 pt-4">
                <button onclick="hideExportModal()"
                        class="w-full sm:w-auto px-4 py-2 text-gray-700 hover:bg-gray-100 rounded-lg transition-colors text-sm sm:text-base">
                    Cancel
                </button>
                <button onclick="processExport()"
                        class="w-full sm:w-auto px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors flex items-center justify-center text-sm sm:text-base">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                    Export
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Loading Spinner Modal -->
<div id="loadingModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 p-4 hidden">
    <div class="bg-white rounded-xl p-6 sm:p-8 w-full max-w-sm mx-auto">
        <div class="flex flex-col items-center">
            <div class="animate-spin rounded-full h-10 w-10 sm:h-12 sm:w-12 border-b-2 border-blue-600 mb-4"></div>
            <p class="text-base sm:text-lg font-semibold text-gray-800">Generating Salary</p>
            <p class="text-gray-600 text-center mt-2 text-sm sm:text-base">Please wait while we process your request...</p>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const form = document.getElementById('salaryForm');
        const generateBtn = document.getElementById('generateBtn');
        const bulkGenerateBtn = document.getElementById('bulkGenerateBtn');
        const loadingModal = document.getElementById('loadingModal');
        const resultDiv = document.getElementById('result');

        // Update showSuccess function for mobile responsiveness
        window.showSuccess = function(data) {
            const salary = data.salary;
            const resultDiv = document.getElementById('result');
            
            if (!resultDiv) return;
            
            const salaryMonth = new Date(salary.salary_month).toLocaleDateString('en-US', { 
                month: 'long', 
                year: 'numeric' 
            });
            
            // Mobile-optimized result template
            resultDiv.innerHTML = `
                <div class="bg-white overflow-hidden shadow-sm rounded-lg border border-green-200">
                    <div class="p-4 md:p-6 bg-gradient-to-r from-green-50 to-emerald-50">
                        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                            <div class="flex items-center">
                                <div class="p-2 sm:p-3 bg-green-100 rounded-full">
                                    <svg class="w-6 h-6 sm:w-8 sm:h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                </div>
                                <div class="ml-3">
                                    <h3 class="text-lg sm:text-xl font-bold text-gray-800">Salary Calculated!</h3>
                                    <p class="text-gray-600 text-xs sm:text-sm">Salary slip has been created</p>
                                </div>
                            </div>
                            <div class="text-left sm:text-right">
                                <p class="text-xl sm:text-2xl font-bold text-green-600">₹${salary.net_salary.toLocaleString('en-IN', {minimumFractionDigits: 2})}</p>
                                <p class="text-gray-600 text-xs sm:text-sm">Net Salary</p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="p-4 md:p-6">
                        <!-- Employee and Period Info - Stack on mobile -->
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-3 md:gap-6 mb-4 md:mb-6">
                            <div class="bg-blue-50 p-3 md:p-4 rounded-lg">
                                <p class="text-xs md:text-sm text-gray-600">Employee</p>
                                <p class="font-semibold text-sm md:text-lg truncate">${salary.employee.name}</p>
                                <p class="text-xs text-gray-500 truncate">${salary.employee.email}</p>
                            </div>
                            <div class="bg-purple-50 p-3 md:p-4 rounded-lg">
                                <p class="text-xs md:text-sm text-gray-600">Period</p>
                                <p class="font-semibold text-sm md:text-lg">${salaryMonth}</p>
                                <p class="text-xs text-gray-500">Working Days: 22</p>
                            </div>
                            <div class="bg-green-50 p-3 md:p-4 rounded-lg">
                                <p class="text-xs md:text-sm text-gray-600">Status</p>
                                <span class="inline-flex items-center px-2 py-0.5 sm:px-3 sm:py-1 rounded-full text-xs sm:text-sm font-semibold bg-green-100 text-green-800">
                                    ${salary.status.charAt(0).toUpperCase() + salary.status.slice(1)}
                                </span>
                                <p class="text-xs text-gray-500 mt-1">Per Day: ₹${salary.per_day_salary.toFixed(2)}</p>
                            </div>
                        </div>
                        
                        <!-- Attendance Breakdown - Grid adjustments for mobile -->
                        <div class="mb-6 md:mb-8">
                            <h4 class="font-semibold text-gray-700 mb-3 text-sm md:text-base">📊 Attendance Summary</h4>
                            <div class="grid grid-cols-2 md:grid-cols-4 gap-2 md:gap-4">
                                <div class="bg-green-50 p-2 md:p-4 rounded-lg text-center">
                                    <p class="text-xl md:text-3xl font-bold text-green-600">${salary.total_present_days}</p>
                                    <p class="text-xs md:text-sm text-gray-600">Present Days</p>
                                    <p class="text-xs text-gray-500">₹${(salary.total_present_days * salary.per_day_salary).toFixed(2)}</p>
                                </div>
                                <div class="bg-yellow-50 p-2 md:p-4 rounded-lg text-center">
                                    <p class="text-xl md:text-3xl font-bold text-yellow-600">${salary.total_late_days}</p>
                                    <p class="text-xs md:text-sm text-gray-600">Late Days</p>
                                    <p class="text-xs text-gray-500">₹${(salary.total_late_days * salary.per_day_salary).toFixed(2)}</p>
                                </div>
                                <div class="bg-orange-50 p-2 md:p-4 rounded-lg text-center">
                                    <p class="text-xl md:text-3xl font-bold text-orange-600">${salary.total_half_days}</p>
                                    <p class="text-xs md:text-sm text-gray-600">Half Days</p>
                                    <p class="text-xs text-gray-500">₹${(salary.total_half_days * (salary.per_day_salary * 0.5)).toFixed(2)}</p>
                                </div>
                                <div class="bg-red-50 p-2 md:p-4 rounded-lg text-center">
                                    <p class="text-xl md:text-3xl font-bold text-red-600">${salary.total_absent_days}</p>
                                    <p class="text-xs md:text-sm text-gray-600">Absent Days</p>
                                    <p class="text-xs text-gray-500">-₹${(salary.total_absent_days * salary.per_day_salary).toFixed(2)}</p>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Financial Breakdown - Stack on mobile -->
                        <div class="mb-6 md:mb-8">
                            <h4 class="font-semibold text-gray-700 mb-3 text-sm md:text-base">💰 Financial Breakdown</h4>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-3 md:gap-6">
                                <div class="bg-gray-50 p-3 md:p-4 rounded-lg">
                                    <h5 class="font-medium text-gray-700 mb-2 text-sm md:text-base">Income</h5>
                                    <div class="space-y-1 md:space-y-2">
                                        <div class="flex justify-between text-xs md:text-sm">
                                            <span class="text-gray-600">Basic Salary:</span>
                                            <span class="font-medium">₹${salary.basic_salary.toLocaleString('en-IN', {minimumFractionDigits: 2})}</span>
                                        </div>
                                        <div class="flex justify-between text-xs md:text-sm">
                                            <span class="text-gray-600">Allowances:</span>
                                            <span class="font-medium text-green-600">+₹${salary.total_allowances.toLocaleString('en-IN', {minimumFractionDigits: 2})}</span>
                                        </div>
                                        <div class="flex justify-between text-xs md:text-sm">
                                            <span class="text-gray-600">Overtime:</span>
                                            <span class="font-medium text-green-600">+₹${salary.overtime_amount.toLocaleString('en-IN', {minimumFractionDigits: 2})}</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="bg-gray-50 p-3 md:p-4 rounded-lg">
                                    <h5 class="font-medium text-gray-700 mb-2 text-sm md:text-base">Deductions</h5>
                                    <div class="space-y-1 md:space-y-2">
                                        <div class="flex justify-between text-xs md:text-sm">
                                            <span class="text-gray-600">Absent Days:</span>
                                            <span class="font-medium text-red-600">-₹${(salary.total_absent_days * salary.per_day_salary).toLocaleString('en-IN', {minimumFractionDigits: 2})}</span>
                                        </div>
                                        <div class="flex justify-between text-xs md:text-sm">
                                            <span class="text-gray-600">Other Deductions:</span>
                                            <span class="font-medium text-red-600">-₹${salary.total_deductions.toLocaleString('en-IN', {minimumFractionDigits: 2})}</span>
                                        </div>
                                        <div class="flex justify-between text-xs md:text-sm pt-1 md:pt-2 border-t border-gray-200">
                                            <span class="text-gray-600 font-semibold">Total Deductions:</span>
                                            <span class="font-bold text-red-600">-₹${(salary.total_deductions + (salary.total_absent_days * salary.per_day_salary)).toLocaleString('en-IN', {minimumFractionDigits: 2})}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Net Salary Highlight -->
                        <div class="bg-gradient-to-r from-blue-50 to-indigo-50 p-4 md:p-6 rounded-lg mb-4 md:mb-6">
                            <div class="flex flex-col md:flex-row justify-between items-center gap-3">
                                <div class="text-center md:text-left">
                                    <h4 class="text-base md:text-lg font-semibold text-gray-800">Final Net Salary</h4>
                                    <p class="text-gray-600 text-xs md:text-sm">After all calculations</p>
                                </div>
                                <div class="mt-2 md:mt-0">
                                    <div class="text-2xl md:text-3xl font-bold text-blue-700">
                                        ₹${salary.net_salary.toLocaleString('en-IN', {minimumFractionDigits: 2})}
                                    </div>
                                    <div class="text-xs md:text-sm text-gray-600 text-center md:text-right mt-1">
                                        Basic: ₹${salary.basic_salary.toLocaleString('en-IN')}
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Action Buttons - Stack on mobile -->
                        <div class="flex flex-col gap-3">
                            <a href="/salary/slip/${salary.id}" 
                               target="_blank"
                               class="w-full bg-gradient-to-r from-blue-600 to-blue-700 hover:from-blue-700 hover:to-blue-800 text-white font-medium py-2.5 md:py-3 px-4 md:px-6 rounded-lg transition-all flex items-center justify-center text-sm md:text-base">
                                <svg class="w-4 h-4 md:w-5 md:h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                </svg>
                                View Detailed Salary Slip
                            </a>
                            
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                                <a href="/salary/slip/${salary.id}?download=1" 
                                   class="bg-gradient-to-r from-green-600 to-emerald-600 hover:from-green-700 hover:to-emerald-700 text-white font-medium py-2.5 md:py-3 px-4 md:px-6 rounded-lg transition-all flex items-center justify-center text-sm md:text-base">
                                    <svg class="w-4 h-4 md:w-5 md:h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                    </svg>
                                    Download PDF
                                </a>
                                
                                <button onclick="printSalarySlip(${salary.id})"
                                        class="bg-gradient-to-r from-purple-600 to-purple-700 hover:from-purple-700 hover:to-purple-800 text-white font-medium py-2.5 md:py-3 px-4 md:px-6 rounded-lg transition-all flex items-center justify-center text-sm md:text-base">
                                    <svg class="w-4 h-4 md:w-5 md:h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/>
                                    </svg>
                                    Print
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            `;
            
            // Scroll to result
            resultDiv.scrollIntoView({ behavior: 'smooth', block: 'start' });
        };

        // Single employee salary generation
        form.addEventListener('submit', async function(e) {
            e.preventDefault();
            
            if (!validateForm()) {
                return;
            }

            showLoading();
            
            const formData = {
                employee_id: document.getElementById('employee_id').value,
                month: document.getElementById('month').value,
                year: document.getElementById('year').value,
                _token: '{{ csrf_token() }}'
            };
            
            try {
                const response = await fetch('{{ route("salary.generate") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify(formData)
                });
                
                const data = await response.json();
                hideLoading();
                
                if (data.success) {
                    const salary = data.salary || {
                        id: data.id,
                        employee: { name: data.employee_name },
                        net_salary: data.net_salary || data.amount,
                        status: data.status || 'approved'
                    };
                    
                    showSuccess({
                        success: true,
                        salary: salary
                    });
                } else {
                    showError(data.error || data.message || 'Something went wrong');
                }
            } catch (error) {
                hideLoading();
                showError('Network error: ' + error.message);
            }
        });

        // Bulk salary generation
        bulkGenerateBtn.addEventListener('click', async function() {
            if (!confirm('Generate salaries for ALL employees for selected month? This may take a moment.')) {
                return;
            }

            showLoading();
            
            const formData = {
                month: document.getElementById('month').value,
                year: document.getElementById('year').value,
                _token: '{{ csrf_token() }}'
            };
            
            try {
                const response = await fetch('{{ route("salary.bulk-generate") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify(formData)
                });
                
                const data = await response.json();
                hideLoading();
                
                if (data.success) {
                    showBulkSuccess(data);
                } else {
                    showError(data.error || 'Bulk generation failed');
                }
            } catch (error) {
                hideLoading();
                showError('Network error. Please try again.');
            }
        });

        function validateForm() {
            const employeeId = document.getElementById('employee_id').value;
            if (!employeeId) {
                showError('Please select an employee');
                return false;
            }
            return true;
        }

        // Function to print salary slip
        window.printSalarySlip = function(salaryId) {
            window.open(`/salary/slip/${salaryId}?print=1`, '_blank');
        };

        // Update export period when month/year changes
        document.getElementById('month').addEventListener('change', updateExportPeriodDisplay);
        document.getElementById('year').addEventListener('change', updateExportPeriodDisplay);
        
        // Initial update
        updateExportPeriodDisplay();
    });

    function updateExportPeriodDisplay() {
        const month = document.getElementById('month').value;
        const year = document.getElementById('year').value;
        const monthName = new Date(year, month - 1).toLocaleString('default', { month: 'long' });
        if (document.getElementById('exportPeriodDisplay')) {
            document.getElementById('exportPeriodDisplay').textContent = `${monthName} ${year}`;
        }
    }

    function showExportModal() {
        updateExportPeriodDisplay();
        document.getElementById('exportModal').classList.remove('hidden');
    }

    function hideExportModal() {
        document.getElementById('exportModal').classList.add('hidden');
    }

    async function processExport() {
        const format = document.querySelector('input[name="exportFormat"]:checked')?.value || 'excel';
        const month = document.getElementById('month').value;
        const year = document.getElementById('year').value;
        const includeAll = document.getElementById('includeAll').checked;
        const fileName = document.getElementById('exportFileName').value || `Salary_Report_${month}_${year}`;
        
        hideExportModal();
        showLoading();
        
        try {
            let exportUrl = `/salary/export?format=${format}&month=${month}&year=${year}`;
            
            if (includeAll) {
                exportUrl += '&include_all=1';
            }
            
            const link = document.createElement('a');
            link.href = exportUrl;
            link.download = `${fileName}.${format === 'excel' ? 'xlsx' : 'pdf'}`;
            document.body.appendChild(link);
            link.click();
            document.body.removeChild(link);
            
            hideLoading();
        } catch (error) {
            hideLoading();
            showError('Export failed: ' + error.message);
        }
    }

    function showLoading() {
        loadingModal.classList.remove('hidden');
        generateBtn.disabled = true;
        bulkGenerateBtn.disabled = true;
    }

    function hideLoading() {
        loadingModal.classList.add('hidden');
        generateBtn.disabled = false;
        bulkGenerateBtn.disabled = false;
    }

    function showError(message) {
        const resultDiv = document.getElementById('result');
        if (!resultDiv) return;
        
        resultDiv.innerHTML = `
            <div class="bg-white border border-red-200 rounded-lg p-4">
                <div class="flex items-center">
                    <div class="p-2 bg-red-100 rounded-full mr-3">
                        <svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <div>
                        <p class="text-gray-800 font-medium">Error</p>
                        <p class="text-gray-600 text-sm">${message}</p>
                    </div>
                </div>
            </div>
        `;
        
        resultDiv.scrollIntoView({ behavior: 'smooth', block: 'start' });
    }
</script>
@endsection