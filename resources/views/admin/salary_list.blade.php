@extends('components.layout')

@section('content')
<div class="py-4 md:py-6">
    <div class="max-w-7xl mx-auto px-3 sm:px-4 lg:px-8">
        <!-- Header -->
        <div class="bg-white overflow-hidden shadow-sm rounded-lg sm:rounded-lg mb-4 md:mb-6">
            <div class="p-4 md:p-6 bg-gradient-to-r from-indigo-600 to-purple-700 text-white">
                <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center gap-3">
                    <div class="mb-3 sm:mb-0">
                        <h2 class="text-xl sm:text-2xl font-bold">Salary Records</h2>
                        <p class="text-indigo-100 mt-1 text-sm sm:text-base">View and manage all salary records</p>
                    </div>
                </div>
                
                <!-- Stats Cards - Responsive Grid -->
                <div class="grid grid-cols-2 md:grid-cols-4 gap-3 md:gap-4 mt-4 md:mt-6">
                    <div class="bg-white/10 backdrop-blur-sm rounded-lg p-3 md:p-4">
                        <div class="flex items-center">
                            <div class="p-1.5 md:p-2 bg-white/20 rounded-lg">
                                <svg class="w-4 h-4 md:w-6 md:h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                                </svg>
                            </div>
                            <div class="ml-2 md:ml-3">
                                <p class="text-xs md:text-sm opacity-80">Total Records</p>
                                <p class="text-lg md:text-xl font-bold">{{ $salaries->total() }}</p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="bg-white/10 backdrop-blur-sm rounded-lg p-3 md:p-4">
                        <div class="flex items-center">
                            <div class="p-1.5 md:p-2 bg-white/20 rounded-lg">
                                <svg class="w-4 h-4 md:w-6 md:h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                            </div>
                            <div class="ml-2 md:ml-3">
                                <p class="text-xs md:text-sm opacity-80">Paid</p>
                                <p class="text-lg md:text-xl font-bold">{{ $statusCounts['paid'] ?? 0 }}</p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="bg-white/10 backdrop-blur-sm rounded-lg p-3 md:p-4">
                        <div class="flex items-center">
                            <div class="p-1.5 md:p-2 bg-white/20 rounded-lg">
                                <svg class="w-4 h-4 md:w-6 md:h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                            </div>
                            <div class="ml-2 md:ml-3">
                                <p class="text-xs md:text-sm opacity-80">Pending</p>
                                <p class="text-lg md:text-xl font-bold">{{ $statusCounts['pending'] ?? 0 }}</p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="bg-white/10 backdrop-blur-sm rounded-lg p-3 md:p-4">
                        <div class="flex items-center">
                            <div class="p-1.5 md:p-2 bg-white/20 rounded-lg">
                                <svg class="w-4 h-4 md:w-6 md:h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2z"/>
                                </svg>
                            </div>
                            <div class="ml-2 md:ml-3">
                                <p class="text-xs md:text-sm opacity-80">Total Paid</p>
                                <p class="text-lg md:text-xl font-bold">₹{{ number_format($totalPaidAmount) }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Filters and Search -->
        <div class="bg-white overflow-hidden shadow-sm rounded-lg sm:rounded-lg mb-4 md:mb-6">
            <div class="p-4 md:p-6">
                <form method="GET" action="{{ route('salary.list') }}" class="space-y-4">
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-3 md:gap-4">
                        <!-- Employee Filter -->
                        <div>
                            <label class="block text-xs md:text-sm font-medium text-gray-700 mb-1">Employee</label>
                            <select name="employee_id" 
                                    class="w-full px-3 py-2 text-sm md:text-base border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                <option value="">All Employees</option>
                                @foreach($employees as $emp)
                                    <option value="{{ $emp->id }}" {{ request('employee_id') == $emp->id ? 'selected' : '' }}>
                                        {{ $emp->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        
                        <!-- Month Filter -->
                        <div>
                            <label class="block text-xs md:text-sm font-medium text-gray-700 mb-1">Month</label>
                            <select name="month" 
                                    class="w-full px-3 py-2 text-sm md:text-base border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                <option value="">All Months</option>
                                @for($i = 1; $i <= 12; $i++)
                                    <option value="{{ $i }}" {{ request('month') == $i ? 'selected' : '' }}>
                                        {{ date('F', mktime(0, 0, 0, $i, 1)) }}
                                    </option>
                                @endfor
                            </select>
                        </div>
                        
                        <!-- Year Filter -->
                        <div>
                            <label class="block text-xs md:text-sm font-medium text-gray-700 mb-1">Year</label>
                            <input type="number" 
                                   name="year" 
                                   value="{{ request('year') }}" 
                                   min="2000" 
                                   max="2100"
                                   class="w-full px-3 py-2 text-sm md:text-base border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                   placeholder="Year">
                        </div>
                        
                        <!-- Status Filter -->
                        <div>
                            <label class="block text-xs md:text-sm font-medium text-gray-700 mb-1">Status</label>
                            <select name="status" 
                                    class="w-full px-3 py-2 text-sm md:text-base border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                <option value="">All Status</option>
                                <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                                <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>Approved</option>
                                <option value="paid" {{ request('status') == 'paid' ? 'selected' : '' }}>Paid</option>
                            </select>
                        </div>
                    </div>
                    
                    <div class="flex flex-col md:flex-row md:justify-between md:items-center gap-3">
                        <!-- Search -->
                        <div class="w-full md:flex-1 md:max-w-md">
                            <div class="relative">
                                <input type="text" 
                                       name="search" 
                                       value="{{ request('search') }}"
                                       placeholder="Search by employee name..."
                                       class="w-full pl-10 pr-4 py-2 text-sm md:text-base border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                <svg class="absolute left-3 top-2.5 w-4 h-4 md:w-5 md:h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                                </svg>
                            </div>
                        </div>
                        
                        <!-- Action Buttons -->
                        <div class="flex flex-col sm:flex-row gap-2 w-full md:w-auto">
                            <button type="submit" 
                                    class="w-full sm:w-auto px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors flex items-center justify-center text-sm md:text-base">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"/>
                                </svg>
                                Filter
                            </button>
                            <a href="{{ route('salary.list') }}" 
                               class="w-full sm:w-auto px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition-colors flex items-center justify-center text-sm md:text-base">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                                </svg>
                                Reset
                            </a>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <!-- Salary Records Table - Mobile Cards, Desktop Table -->
        <div class="bg-white overflow-hidden shadow-sm rounded-lg sm:rounded-lg">
            <div class="px-4 md:px-6 py-3 md:py-4 border-b border-gray-200">
                <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center gap-2">
                    <h3 class="text-base md:text-lg font-semibold text-gray-800">Salary Records</h3>
                    <div class="text-xs md:text-sm text-gray-600">
                        Showing {{ $salaries->firstItem() ?? 0 }}-{{ $salaries->lastItem() ?? 0 }} of {{ $salaries->total() }} records
                    </div>
                </div>
            </div>
            
            <!-- Mobile View - Cards -->
            <div class="md:hidden">
                @forelse($salaries as $salary)
                <div class="border-b border-gray-200 p-4">
                    <!-- Card Header -->
                    <div class="flex items-center justify-between mb-3">
                        <div class="flex items-center">
                            <div class="flex-shrink-0 h-8 w-8 bg-gradient-to-r from-blue-500 to-purple-500 rounded-full flex items-center justify-center text-white font-bold text-sm">
                                {{ substr($salary->employee->name, 0, 1) }}
                            </div>
                            <div class="ml-3">
                                <div class="text-sm font-medium text-gray-900 truncate max-w-[150px]">
                                    {{ $salary->employee->name }}
                                </div>
                                <div class="text-xs text-gray-500">
                                    {{ \Carbon\Carbon::parse($salary->salary_month)->format('M Y') }}
                                </div>
                            </div>
                        </div>
                        <div>
                            <span class="px-2 py-0.5 text-xs font-semibold rounded-full 
                                {{ $salary->status == 'paid' ? 'bg-green-100 text-green-800' : 
                                   ($salary->status == 'approved' ? 'bg-yellow-100 text-yellow-800' : 
                                   'bg-gray-100 text-gray-800') }}">
                                {{ ucfirst($salary->status) }}
                            </span>
                        </div>
                    </div>
                    
                    <!-- Salary Details -->
                    <div class="grid grid-cols-2 gap-3 mb-3">
                        <div class="bg-gray-50 p-2 rounded">
                            <div class="text-xs text-gray-500">Basic Salary</div>
                            <div class="text-sm font-semibold">₹{{ number_format($salary->basic_salary) }}</div>
                        </div>
                        <div class="bg-gray-50 p-2 rounded">
                            <div class="text-xs text-gray-500">Net Salary</div>
                            <div class="text-sm font-bold text-green-600">₹{{ number_format($salary->net_salary) }}</div>
                        </div>
                    </div>
                    
                    <!-- Attendance Summary -->
                    <div class="mb-3">
                        <div class="text-xs text-gray-500 mb-1">Attendance</div>
                        <div class="flex items-center space-x-4">
                            <div class="flex flex-col items-center">
                                <span class="text-xs font-semibold text-green-600">{{ $salary->total_present_days }}</span>
                                <span class="text-[10px] text-gray-500">Present</span>
                            </div>
                            <div class="h-4 w-px bg-gray-200"></div>
                            <div class="flex flex-col items-center">
                                <span class="text-xs font-semibold text-red-600">{{ $salary->total_absent_days }}</span>
                                <span class="text-[10px] text-gray-500">Absent</span>
                            </div>
                            <div class="h-4 w-px bg-gray-200"></div>
                            <div class="flex flex-col items-center">
                                <span class="text-xs font-semibold text-yellow-600">{{ $salary->total_half_days }}</span>
                                <span class="text-[10px] text-gray-500">Half</span>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Action Buttons -->
                    <div class="flex justify-between items-center pt-3 border-t border-gray-100">
                        <div class="flex space-x-2">
                            <!-- View Button -->
                            <a href="{{ route('salary.slip', $salary->id) }}" 
                               target="_blank"
                               class="text-blue-600 hover:text-blue-900 p-1.5 hover:bg-blue-50 rounded-lg transition-colors"
                               title="View">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                </svg>
                            </a>
                            
                            <!-- Download Button -->
                            <a href="{{ route('salary.slip', ['id' => $salary->id, 'download' => 1]) }}" 
                               class="text-green-600 hover:text-green-900 p-1.5 hover:bg-green-50 rounded-lg transition-colors"
                               title="Download">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                </svg>
                            </a>
                            
                            @if($salary->status != 'paid')
                            <!-- Mark as Paid Button -->
                            <button onclick="markAsPaid({{ $salary->id }})"
                                    class="text-purple-600 hover:text-purple-900 p-1.5 hover:bg-purple-50 rounded-lg transition-colors"
                                    title="Mark Paid">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                            </button>
                            @endif
                        </div>
                        
                        <!-- More Actions Dropdown -->
                        <div class="relative">
                            <button onclick="toggleMobileDropdown({{ $salary->id }})"
                                    class="text-gray-600 hover:text-gray-900 p-1.5 hover:bg-gray-100 rounded-lg transition-colors">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 5v.01M12 12v.0M12 19v.0M12 6a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2z"/>
                                </svg>
                            </button>
                            
                            <!-- Mobile Dropdown Menu -->
                            <div id="mobile-dropdown-{{ $salary->id }}" 
                                 class="absolute right-0 mt-1 w-48 bg-white rounded-lg shadow-lg border border-gray-200 z-50 hidden">
                                <div class="py-1">
                                    <a href="{{ route('salary.edit', $salary->id) }}"
                                       class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 flex items-center">
                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                        </svg>
                                        Edit Salary
                                    </a>
                                    <button onclick="sendEmail({{ $salary->id }})"
                                            class="w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 flex items-center">
                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                                        </svg>
                                        Send via Email
                                    </button>
                                    <button onclick="regenerate({{ $salary->id }})"
                                            class="w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 flex items-center">
                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                                        </svg>
                                        Regenerate
                                    </button>
                                    <button onclick="deleteSalary({{ $salary->id }})"
                                            class="w-full text-left px-4 py-2 text-sm text-red-700 hover:bg-red-50 flex items-center">
                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                        </svg>
                                        Delete
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                @empty
                <div class="p-8 text-center">
                    <svg class="w-12 h-12 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                    <h3 class="text-base font-medium text-gray-900 mb-2">No salary records found</h3>
                    <p class="text-gray-500 text-sm mb-4">Get started by generating a salary slip</p>
                    <a href="{{ route('salary.generate') }}" 
                       class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors inline-flex items-center text-sm">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                        </svg>
                        Generate Salary
                    </a>
                </div>
                @endforelse
            </div>
            
            <!-- Desktop View - Table -->
            <div class="hidden md:block overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-4 lg:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                <div class="flex items-center">
                                    Employee
                                </div>
                            </th>
                            <th class="px-4 lg:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Period
                            </th>
                            <th class="px-4 lg:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Attendance
                            </th>
                            <th class="px-4 lg:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Basic Salary
                            </th>
                            <th class="px-4 lg:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Net Salary
                            </th>
                            <th class="px-4 lg:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Status
                            </th>
                            <th class="px-4 lg:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Actions
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($salaries as $salary)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <!-- Employee Column -->
                            <td class="px-4 lg:px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0 h-8 w-8 lg:h-10 lg:w-10 bg-gradient-to-r from-blue-500 to-purple-500 rounded-full flex items-center justify-center text-white font-bold text-sm lg:text-base">
                                        {{ substr($salary->employee->name, 0, 1) }}
                                    </div>
                                    <div class="ml-3">
                                        <div class="text-sm font-medium text-gray-900 truncate max-w-[120px] lg:max-w-none">
                                            {{ $salary->employee->name }}
                                        </div>
                                        <div class="text-xs text-gray-500 truncate max-w-[120px] lg:max-w-none">
                                            {{ $salary->employee->email }}
                                        </div>
                                    </div>
                                </div>
                            </td>
                            
                            <!-- Period Column -->
                            <td class="px-4 lg:px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900">
                                    {{ \Carbon\Carbon::parse($salary->salary_month)->format('F Y') }}
                                </div>
                                <div class="text-xs text-gray-500">
                                    {{ $salary->created_at->format('d M, Y') }}
                                </div>
                            </td>
                            
                            <!-- Attendance Column -->
                            <td class="px-4 lg:px-6 py-4">
                                <div class="flex items-center space-x-2">
                                    <div class="flex flex-col items-center">
                                        <span class="text-xs text-gray-500">Present</span>
                                        <span class="font-semibold text-green-600 text-sm">{{ $salary->total_present_days }}</span>
                                    </div>
                                    <div class="h-6 w-px bg-gray-200"></div>
                                    <div class="flex flex-col items-center">
                                        <span class="text-xs text-gray-500">Absent</span>
                                        <span class="font-semibold text-red-600 text-sm">{{ $salary->total_absent_days }}</span>
                                    </div>
                                    <div class="h-6 w-px bg-gray-200"></div>
                                    <div class="flex flex-col items-center">
                                        <span class="text-xs text-gray-500">Half</span>
                                        <span class="font-semibold text-yellow-600 text-sm">{{ $salary->total_half_days }}</span>
                                    </div>
                                </div>
                            </td>
                            
                            <!-- Basic Salary Column -->
                            <td class="px-4 lg:px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-semibold text-gray-900">
                                    ₹{{ number_format($salary->basic_salary) }}
                                </div>
                                <div class="text-xs text-gray-500">
                                    ₹{{ number_format($salary->per_day_salary, 2) }}/day
                                </div>
                            </td>
                            
                            <!-- Net Salary Column -->
                            <td class="px-4 lg:px-6 py-4 whitespace-nowrap">
                                <div class="text-base lg:text-lg font-bold text-gray-900">
                                    ₹{{ number_format($salary->net_salary) }}
                                </div>
                                <div class="text-xs text-gray-500">
                                    +₹{{ number_format($salary->total_allowances) }} | -₹{{ number_format($salary->total_deductions) }}
                                </div>
                            </td>
                            
                            <!-- Status Column -->
                            <td class="px-4 lg:px-6 py-4 whitespace-nowrap">
                                <span class="px-2 py-1 inline-flex text-xs leading-4 font-semibold rounded-full 
                                    {{ $salary->status == 'paid' ? 'bg-green-100 text-green-800' : 
                                       ($salary->status == 'approved' ? 'bg-yellow-100 text-yellow-800' : 
                                       'bg-gray-100 text-gray-800') }}">
                                    {{ ucfirst($salary->status) }}
                                </span>
                                @if($salary->payment_date)
                                <div class="text-xs text-gray-500 mt-1">
                                    {{ \Carbon\Carbon::parse($salary->payment_date)->format('d M, Y') }}
                                </div>
                                @endif
                            </td>
                            
                            <!-- Actions Column -->
                            <td class="px-4 lg:px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <div class="flex space-x-1 lg:space-x-2">
                                    <!-- View Button -->
                                    <a href="{{ route('salary.slip', $salary->id) }}" 
                                       target="_blank"
                                       class="text-blue-600 hover:text-blue-900 p-1.5 hover:bg-blue-50 rounded-lg transition-colors"
                                       title="View Salary Slip">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                        </svg>
                                    </a>
                                    
                                    <!-- Download Button -->
                                    <a href="{{ route('salary.slip', ['id' => $salary->id, 'download' => 1]) }}" 
                                       class="text-green-600 hover:text-green-900 p-1.5 hover:bg-green-50 rounded-lg transition-colors"
                                       title="Download PDF">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                        </svg>
                                    </a>
                                    
                                    <!-- Mark as Paid Button -->
                                    @if($salary->status != 'paid')
                                    <button onclick="markAsPaid({{ $salary->id }})"
                                            class="text-purple-600 hover:text-purple-900 p-1.5 hover:bg-purple-50 rounded-lg transition-colors"
                                            title="Mark as Paid">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                        </svg>
                                    </button>
                                    @endif
                                    
                                    <!-- More Actions Dropdown -->
                                    <div class="relative inline-block">
                                        <button id="dropdown-{{ $salary->id }}"
                                                onclick="toggleDropdown({{ $salary->id }})"
                                                class="text-gray-600 hover:text-gray-900 p-1.5 hover:bg-gray-100 rounded-lg transition-colors relative z-20"
                                                title="More actions">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 5v.01M12 12v.0M12 19v.0M12 6a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2z"/>
                                            </svg>
                                        </button>
                                        
                                        <!-- Dropdown Menu -->
                                        <div id="dropdown-menu-{{ $salary->id }}" 
                                             class="absolute right-0 mt-1 w-48 bg-white rounded-lg shadow-lg border border-gray-200 z-50 hidden">
                                            <div class="py-1">
                                                <a href="{{ route('salary.edit', $salary->id) }}"
                                                   class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 flex items-center">
                                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                                    </svg>
                                                    Edit Salary
                                                </a>
                                               
                                                <button onclick="sendEmail({{ $salary->id }})"
                                                        class="w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 flex items-center">
                                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                                                    </svg>
                                                    Send via Email
                                                </button>
                                                <button onclick="regenerate({{ $salary->id }})"
                                                        class="w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 flex items-center">
                                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                                                    </svg>
                                                    Regenerate
                                                </button>
                                                <button onclick="deleteSalary({{ $salary->id }})"
                                                        class="w-full text-left px-4 py-2 text-sm text-red-700 hover:bg-red-50 flex items-center">
                                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                                    </svg>
                                                    Delete
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="px-6 py-12 text-center">
                                <div class="flex flex-col items-center justify-center">
                                    <svg class="w-12 h-12 text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                    </svg>
                                    <h3 class="text-base font-medium text-gray-900 mb-2">No salary records found</h3>
                                    <p class="text-gray-500 text-sm mb-4">Get started by generating a salary slip</p>
                                    <a href="{{ route('salary.generate') }}" 
                                       class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors inline-flex items-center text-sm">
                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                                        </svg>
                                        Generate Salary
                                    </a>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            <!-- Pagination -->
            @if($salaries->hasPages())
            <div class="px-4 md:px-6 py-3 md:py-4 border-t border-gray-200">
                <div class="flex flex-col sm:flex-row items-center justify-between gap-3">
                    <div class="text-xs md:text-sm text-gray-700">
                        Showing {{ $salaries->firstItem() }} to {{ $salaries->lastItem() }} of {{ $salaries->total() }} results
                    </div>
                    <div class="flex space-x-1">
                        {{ $salaries->links() }}
                    </div>
                </div>
            </div>
            @endif
        </div>
    </div>
</div>

<!-- Export Options Modal -->
<div id="exportModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 p-4 hidden">
    <div class="bg-white rounded-xl p-4 md:p-6 w-full max-w-md mx-auto">
        <h3 class="text-base md:text-lg font-semibold text-gray-800 mb-4">Export Salary Records</h3>
        <div class="space-y-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Export Format</label>
                <div class="grid grid-cols-2 gap-2 md:gap-3">
                    <button onclick="exportFormat('excel')" 
                            class="p-3 md:p-4 border border-green-200 rounded-lg hover:bg-green-50 transition-colors text-center">
                        <svg class="w-6 h-6 md:w-8 md:h-8 text-green-600 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                        <span class="font-medium text-sm md:text-base">Excel</span>
                    </button>
                    <button onclick="exportFormat('pdf')" 
                            class="p-3 md:p-4 border border-red-200 rounded-lg hover:bg-red-50 transition-colors text-center">
                        <svg class="w-6 h-6 md:w-8 md:h-8 text-red-600 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                        </svg>
                        <span class="font-medium text-sm md:text-base">PDF</span>
                    </button>
                </div>
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Date Range</label>
                <div class="grid grid-cols-2 gap-2 md:gap-3">
                    <input type="month" 
                           id="exportFrom" 
                           class="px-3 py-2 text-sm md:text-base border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    <input type="month" 
                           id="exportTo" 
                           class="px-3 py-2 text-sm md:text-base border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                </div>
            </div>
            
            <div class="flex flex-col sm:flex-row sm:justify-end gap-2 sm:space-x-3 pt-4">
                <button onclick="hideExportModal()"
                        class="w-full sm:w-auto px-4 py-2 text-gray-700 hover:bg-gray-100 rounded-lg transition-colors text-sm md:text-base">
                    Cancel
                </button>
                <button onclick="processExport()"
                        class="w-full sm:w-auto px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors text-sm md:text-base">
                    Export
                </button>
            </div>
        </div>
    </div>
</div>

<script>
// Mobile dropdown toggle
function toggleMobileDropdown(id) {
    const menu = document.getElementById(`mobile-dropdown-${id}`);
    if (menu.classList.contains('hidden')) {
        // Close all other dropdowns
        document.querySelectorAll('[id^="mobile-dropdown-"]').forEach(m => {
            if (m.id !== `mobile-dropdown-${id}`) {
                m.classList.add('hidden');
            }
        });
        menu.classList.remove('hidden');
    } else {
        menu.classList.add('hidden');
    }
}

// Desktop dropdown toggle
function toggleDropdown(id) {
    const menu = document.getElementById(`dropdown-menu-${id}`);
    const button = document.getElementById(`dropdown-${id}`);
    
    document.querySelectorAll('[id^="dropdown-menu-"]').forEach(m => {
        if (m.id !== `dropdown-menu-${id}`) {
            m.classList.add('hidden');
        }
    });
    
    if (menu.classList.contains('hidden')) {
        const buttonRect = button.getBoundingClientRect();
        menu.style.position = 'fixed';
        menu.style.top = `${buttonRect.bottom + window.scrollY + 5}px`;
        menu.style.left = `${Math.min(buttonRect.right - menu.offsetWidth, window.innerWidth - menu.offsetWidth - 10)}px`;
        menu.classList.remove('hidden');
    } else {
        menu.classList.add('hidden');
    }
}

// Close dropdowns when clicking outside
document.addEventListener('click', function(event) {
    // Mobile dropdowns
    const isMobileButton = event.target.closest('[onclick^="toggleMobileDropdown"]');
    const isMobileMenu = event.target.closest('[id^="mobile-dropdown-"]');
    
    if (!isMobileButton && !isMobileMenu) {
        document.querySelectorAll('[id^="mobile-dropdown-"]').forEach(menu => {
            menu.classList.add('hidden');
        });
    }
    
    // Desktop dropdowns
    const isDesktopButton = event.target.closest('[id^="dropdown-"]');
    const isDesktopMenu = event.target.closest('[id^="dropdown-menu-"]');
    
    if (!isDesktopButton && !isDesktopMenu) {
        document.querySelectorAll('[id^="dropdown-menu-"]').forEach(menu => {
            menu.classList.add('hidden');
        });
    }
});

// Close dropdowns on escape key
document.addEventListener('keydown', function(event) {
    if (event.key === 'Escape') {
        document.querySelectorAll('[id^="mobile-dropdown-"], [id^="dropdown-menu-"]').forEach(menu => {
            menu.classList.add('hidden');
        });
    }
});

// Mark as paid function
async function markAsPaid(salaryId) {
    if (!confirm('Mark this salary as paid?')) return;
    
    try {
        const response = await fetch(`/salary/mark-paid/${salaryId}`, {
            method: 'PUT',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Content-Type': 'application/json'
            }
        });
        
        const data = await response.json();
        
        if (data.success) {
            showToast('Salary marked as paid successfully!', 'success');
            setTimeout(() => location.reload(), 1500);
        } else {
            showToast(data.error || 'Failed to mark as paid', 'error');
        }
    } catch (error) {
        showToast('Network error. Please try again.', 'error');
    }
}

// Delete salary record
async function deleteSalary(salaryId) {
    if (!confirm('Are you sure you want to delete this salary record? This action cannot be undone.')) return;
    
    try {
        const response = await fetch(`{{ route('salary.delete', '') }}/${salaryId}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Content-Type': 'application/json',
                'Accept': 'application/json'
            }
        });
        
        const data = await response.json();
        
        if (!response.ok) {
            throw new Error(data.error || 'Failed to delete record');
        }
        
        if (data.success) {
            showToast('Salary record deleted successfully!', 'success');
            setTimeout(() => location.reload(), 1500);
        } else {
            showToast(data.error || 'Failed to delete record', 'error');
        }
    } catch (error) {
        showToast(error.message || 'Network error. Please try again.', 'error');
    }
}

// Send email function
async function sendEmail(salaryId) {
    if (!confirm('Send salary slip via email to employee?')) return;
    
    try {
        const response = await fetch(`/salary/send-email/${salaryId}`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Content-Type': 'application/json'
            }
        });
        
        const data = await response.json();
        
        if (data.success) {
            showToast('Email sent successfully!', 'success');
        } else {
            showToast(data.error || 'Failed to send email', 'error');
        }
    } catch (error) {
        showToast('Network error. Please try again.', 'error');
    }
}

// Regenerate salary
async function regenerate(salaryId) {
    if (!confirm('Regenerate this salary slip? This will recalculate based on current attendance data.')) return;
    
    try {
        const response = await fetch(`/salary/regenerate/${salaryId}`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Content-Type': 'application/json'
            }
        });
        
        const data = await response.json();
        
        if (data.success) {
            showToast('Salary regenerated successfully!', 'success');
            setTimeout(() => location.reload(), 1500);
        } else {
            showToast(data.error || 'Failed to regenerate', 'error');
        }
    } catch (error) {
        showToast('Network error. Please try again.', 'error');
    }
}

// Export functions
function exportToExcel() {
    document.getElementById('exportModal').classList.remove('hidden');
}

function hideExportModal() {
    document.getElementById('exportModal').classList.add('hidden');
}

function exportFormat(format) {
    console.log(`Exporting in ${format} format`);
}

function processExport() {
    const from = document.getElementById('exportFrom').value;
    const to = document.getElementById('exportTo').value;
    
    let exportUrl = `/salary/export?format=excel`;
    
    const params = new URLSearchParams(window.location.search);
    params.forEach((value, key) => {
        exportUrl += `&${key}=${value}`;
    });
    
    if (from) exportUrl += `&from=${from}`;
    if (to) exportUrl += `&to=${to}`;
    
    window.open(exportUrl, '_blank');
    hideExportModal();
}

// Toast notification function
function showToast(message, type = 'success') {
    const toast = document.createElement('div');
    toast.className = `fixed top-4 right-4 px-4 py-3 md:px-6 md:py-3 rounded-lg shadow-lg z-50 transform transition-all duration-300 translate-x-full text-sm md:text-base`;
    toast.innerHTML = `
        <div class="flex items-center">
            <svg class="w-4 h-4 md:w-5 md:h-5 mr-2 ${type === 'success' ? 'text-green-500' : 'text-red-500'}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                ${type === 'success' ? 
                    '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>' :
                    '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>'
                }
            </svg>
            <span>${message}</span>
        </div>
    `;
    
    document.body.appendChild(toast);
    
    setTimeout(() => {
        toast.classList.remove('translate-x-full');
        toast.classList.add('translate-x-0');
    }, 10);
    
    setTimeout(() => {
        toast.classList.remove('translate-x-0');
        toast.classList.add('translate-x-full');
        setTimeout(() => toast.remove(), 300);
    }, 3000);
}

// Responsive table adjustments
function handleResize() {
    const tableContainer = document.querySelector('.overflow-x-auto');
    if (tableContainer) {
        const shouldScroll = tableContainer.scrollWidth > tableContainer.clientWidth;
        if (shouldScroll) {
            tableContainer.classList.add('pb-2'); // Add padding for scrollbar
        }
    }
}

window.addEventListener('resize', handleResize);
window.addEventListener('load', handleResize);
</script>

<style>
/* Custom scrollbar */
.overflow-x-auto {
    scrollbar-width: thin;
    scrollbar-color: #cbd5e0 #f7fafc;
}

.overflow-x-auto::-webkit-scrollbar {
    height: 6px;
}

.overflow-x-auto::-webkit-scrollbar-track {
    background: #f7fafc;
}

.overflow-x-auto::-webkit-scrollbar-thumb {
    background-color: #cbd5e0;
    border-radius: 3px;
}

/* Responsive table styles */
@media (max-width: 767px) {
    .table-cell {
        padding: 8px 4px;
    }
    
    .text-truncate {
        max-width: 120px;
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
    }
}

/* Print styles */
@media print {
    .no-print {
        display: none !important;
    }
    
    .bg-gradient-to-r {
        background: #4f46e5 !important;
    }
}

/* Smooth transitions */
.transition-colors {
    transition: background-color 0.2s ease, color 0.2s ease;
}

/* Mobile dropdown positioning */
@media (max-width: 640px) {
    [id^="mobile-dropdown-"] {
        position: fixed !important;
        top: auto !important;
        bottom: 0 !important;
        left: 0 !important;
        right: 0 !important;
        width: 100% !important;
        margin: 0 !important;
        border-radius: 0.75rem 0.75rem 0 0 !important;
    }
}
</style>
@endsection