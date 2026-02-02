@extends('components.layout')
@section('content')
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=5.0">
    <title>Employee Portal | Leave Management</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap');
      
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }
      
        body {
            font-family: 'Inter', sans-serif;
            -webkit-font-smoothing: antialiased;
            -moz-osx-font-smoothing: grayscale;
            overflow-x: hidden;
        }
      
        /* Mobile-First CSS */
        @media (max-width: 767px) {
            .mobile-only { display: block !important; }
            .desktop-only { display: none !important; }
            .container-mobile { width: 100%; max-width: 100%; padding-left: 16px; padding-right: 16px; margin: 0 auto; }
            .text-mobile-sm { font-size: 12px !important; }
            .text-mobile-base { font-size: 14px !important; }
            .text-mobile-lg { font-size: 16px !important; }
            .text-mobile-xl { font-size: 18px !important; }
            .text-mobile-2xl { font-size: 20px !important; }
            .space-mobile-y-2 > * + * { margin-top: 8px !important; }
            .space-mobile-y-3 > * + * { margin-top: 12px !important; }
            .space-mobile-y-4 > * + * { margin-top: 16px !important; }
            .btn-mobile { min-height: 44px; min-width: 44px; padding: 12px 16px; font-size: 16px; }
            .truncate-mobile { white-space: nowrap; overflow: hidden; text-overflow: ellipsis; max-width: 100%; }
            .card-mobile { width: 100%; padding: 16px; margin-bottom: 16px; border-radius: 12px; border: 1px solid #e5e7eb; background: white; }
        }
      
        @media (min-width: 768px) {
            .mobile-only { display: none !important; }
            .desktop-only { display: block !important; }
            .container-desktop { max-width: 1280px; margin: 0 auto; padding-left: 24px; padding-right: 24px; }
        }
      
        @media (min-width: 1024px) {
            .container-desktop { padding-left: 32px; padding-right: 32px; }
        }
      
        .modal-overlay { background-color: rgba(0, 0, 0, 0.5); backdrop-filter: blur(4px); }
        .notification { transition: all 0.3s ease; }
        .status-pending { background-color: #fef3c7; color: #d97706; }
        .status-approved { background-color: #d1fae5; color: #059669; }
        .status-rejected { background-color: #fee2e2; color: #dc2626; }
        .leave-badge { padding: 4px 12px; border-radius: 9999px; font-size: 12px; font-weight: 500; display: inline-block; white-space: nowrap; }
        .leave-casual { background-color: #fef3c7; color: #d97706; }
        .leave-sick { background-color: #fee2e2; color: #dc2626; }
        .leave-paid { background-color: #d1fae5; color: #059669; }
        .leave-emergency { background-color: #fce7f3; color: #be185d; }
        .leave-halfday { background-color: #e0e7ff; color: #3730a3; }
        .leave-wfh { background-color: #f5f3ff; color: #7c3aed; }
        .email-editor { min-height: 150px; border: 1px solid #e5e7eb; border-radius: 8px; padding: 16px; overflow-wrap: break-word; word-wrap: break-word; }
        .email-editor:focus { outline: none; border-color: #3b82f6; }
        .timeline-item { position: relative; padding-left: 32px; margin-bottom: 24px; word-wrap: break-word; overflow-wrap: break-word; }
        .timeline-item:before { content: ''; position: absolute; left: 0; top: 4px; width: 12px; height: 12px; border-radius: 50%; background: #3b82f6; }
        .timeline-item:after { content: ''; position: absolute; left: 5px; top: 20px; width: 2px; height: calc(100% + 16px); background: #e5e7eb; }
        .timeline-item:last-child:after { display: none; }
        .sticky-header { position: sticky; top: 0; z-index: 50; background-color: white; box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.05); width: 100%; }
        .smooth-transition { transition: all 0.3s ease; }
        .page-container { display: none; width: 100%; }
        .page-active { display: block; animation: fadeIn 0.3s ease; }
        @keyframes fadeIn { from { opacity: 0; transform: translateY(10px); } to { opacity: 1; transform: translateY(0); } }
        @media (max-width: 767px) {
            .timeline-item { padding-left: 24px; margin-bottom: 16px; }
            .timeline-item:before { width: 10px; height: 10px; top: 6px; }
            .timeline-item:after { left: 4px; top: 16px; }
        }
        @supports (padding: max(0px)) {
            .safe-area-top { padding-top: max(16px, env(safe-area-inset-top)); }
            .safe-area-bottom { padding-bottom: max(16px, env(safe-area-inset-bottom)); }
            .safe-area-left { padding-left: max(16px, env(safe-area-inset-left)); }
            .safe-area-right { padding-right: max(16px, env(safe-area-inset-right)); }
        }
        .table-container { overflow-x: auto; border-radius: 8px; border: 1px solid #e5e7eb; background: white; -webkit-overflow-scrolling: touch; }
        input[type="text"], input[type="email"], input[type="tel"], input[type="date"], select, textarea { font-size: 16px !important; }
        @media (max-width: 767px) {
            .form-grid-mobile { display: flex; flex-direction: column; gap: 16px; }
            .form-input-mobile { width: 100%; padding: 12px 16px; font-size: 16px; border-radius: 8px; border: 1px solid #e5e7eb; }
            .btn-group-mobile { display: flex; flex-direction: column; gap: 12px; width: 100%; }
            .btn-group-mobile button { width: 100%; }
        }
        .hide-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }
        .hide-scrollbar::-webkit-scrollbar { display: none; }
        .skeleton { background: linear-gradient(90deg, #f0f0f0 25%, #e0e0e0 50%, #f0f0f0 75%); background-size: 200% 100%; animation: loading 1.5s infinite; }
        @keyframes loading { 0% { background-position: 200% 0; } 100% { background-position: -200% 0; } }
        #deletePopup { opacity: 0; transform: scale(0.95); transition: all 0.3s ease; }
        #deletePopup .modal-overlay { backdrop-filter: blur(4px); }
        @media (max-width: 767px) {
            #deletePopup { padding: 16px; align-items: flex-end; }
            #deletePopup > div { margin-bottom: 20px; max-height: 90vh; overflow-y: auto; }
        }
        [contenteditable][placeholder]:empty:before { content: attr(placeholder); color: #9ca3af; pointer-events: none; display: block; }
        .email-editor:empty:focus:before { content: attr(placeholder); color: #9ca3af; }
    </style>
</head>
<body class="bg-gray-50">
    <!-- Mobile Menu Overlay -->
    <div id="mobileMenuOverlay" class="fixed inset-0 bg-white z-50 hidden overflow-y-auto">
        <div class="container-mobile safe-area-top safe-area-bottom">
            <div class="flex items-center justify-between py-4 border-b border-gray-200">
                <div class="h-10 w-10 bg-indigo-100 rounded-lg flex items-center justify-center">
                    <span class="text-indigo-600 font-bold">LM</span>
                </div>
                <button id="closeMobileMenu" class="p-2 text-gray-600 hover:text-gray-800">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>
          
            <div class="py-6">
                <div class="flex items-center p-4 bg-gray-50 rounded-lg mb-6">
                    <div class="h-12 w-12 bg-indigo-100 rounded-full flex items-center justify-center mr-4">
                        <span class="text-indigo-600 font-medium text-lg">JD</span>
                    </div>
                    <div>
                        <h4 class="font-semibold text-gray-800">John Doe</h4>
                        <p class="text-gray-500 text-sm">Employee Account</p>
                    </div>
                </div>
              
                <nav class="space-y-2">
                    <a href="#" class="flex items-center p-3 text-gray-700 hover:bg-gray-100 rounded-lg">
                        <i class="fas fa-tachometer-alt mr-3 text-gray-500 w-5"></i>
                        <span>Dashboard</span>
                    </a>
                    <a href="#" class="flex items-center p-3 text-indigo-600 bg-indigo-50 rounded-lg">
                        <i class="fas fa-calendar-alt mr-3 text-indigo-500 w-5"></i>
                        <span>Leave Management</span>
                    </a>
                    <a href="#" class="flex items-center p-3 text-gray-700 hover:bg-gray-100 rounded-lg">
                        <i class="fas fa-chart-bar mr-3 text-gray-500 w-5"></i>
                        <span>Reports</span>
                    </a>
                    <a href="#" class="flex items-center p-3 text-gray-700 hover:bg-gray-100 rounded-lg">
                        <i class="fas fa-cog mr-3 text-gray-500 w-5"></i>
                        <span>Settings</span>
                    </a>
                </nav>
            </div>
          
            <div class="pt-6 border-t border-gray-200">
                <button class="flex items-center justify-center w-full p-3 text-gray-700 hover:bg-gray-100 rounded-lg">
                    <i class="fas fa-sign-out-alt mr-3"></i>
                    <span>Logout</span>
                </button>
            </div>
        </div>
    </div>
    <!-- Dashboard Page -->
    <div id="dashboardPage" class="page-container page-active">
        <div class="container-mobile container-desktop">
            <div class="py-4 safe-area-top">
                <!-- Page Header -->
                <div class="mb-6">
                    <div class="mobile-only mb-4">
                        <button id="mobileFilterBtn" class="w-full flex items-center justify-center p-3 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 mb-3 hidden md:block">
                            <i class="fas fa-filter mr-2"></i>
                            <span>Filter & Sort</span>
                        </button>
                    </div>
                  
                    <div class="flex flex-col md:flex-row md:items-center md:justify-between">
                        <div class="mb-4 md:mb-0">
                            <h1 class="text-mobile-2xl text-2xl font-bold text-gray-800 mb-1">Employee Leave Portal</h1>
                            <p class="text-mobile-base text-gray-600">Manage your leave applications</p>
                        </div>
                      
                        <div class="flex items-center space-x-3">
                            <div class="relative desktop-only">
                                <button id="exportBtn" class="hidden flex items-center space-x-2 bg-white border border-gray-300 text-gray-700 py-2 px-4 rounded-lg hover:bg-gray-50">
                                    <i class="fas fa-download"></i>
                                    <span>Export</span>
                                </button>
                                <div id="exportMenu" class="absolute right-0 mt-2 w-40 bg-white rounded-lg shadow-lg py-2 z-10 hidden">
                                    <a href="#" onclick="exportData('csv')" class="w-full block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 flex items-center">
                                        <i class="fas fa-file-csv mr-2 text-green-500"></i> CSV
                                    </a>
                                    <a href="#" onclick="exportData('excel')" class="w-full block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 flex items-center">
                                        <i class="fas fa-file-excel mr-2 text-green-600"></i> Excel
                                    </a>
                                </div>
                            </div>
                          
                            <button id="addLeaveBtn" class="bg-orange-500 text-white py-3 px-4 rounded-lg font-medium hover:bg-orange-600 transition-all duration-300 flex items-center btn-mobile">
                                <i class="fas fa-plus mr-2"></i>
                                <span class="mobile-only">Apply</span>
                                <span class="desktop-only">Apply Leave</span>
                            </button>
                        </div>
                    </div>
                </div>
                <!-- Mobile Filter Modal -->
                <div id="mobileFilterModal" class="fixed inset-0 bg-white z-50 hidden overflow-y-auto">
                    <div class="container-mobile safe-area-top safe-area-bottom">
                        <div class="flex items-center justify-between py-4 border-b border-gray-200">
                            <h2 class="text-lg font-bold text-gray-800">Filters</h2>
                            <button id="closeMobileFilter" class="p-2 text-gray-600 hover:text-gray-800">
                                <i class="fas fa-times text-xl"></i>
                            </button>
                        </div>
                      
                        <div class="py-6">
                            <div class="space-y-6">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-3">Leave Type</label>
                                    <select id="mobileFilterType" class="w-full px-4 py-3 border border-gray-300 rounded-lg text-base">
                                        <option value="">All Types</option>
                                        <option value="casual">Casual Leave</option>
                                        <option value="sick">Sick Leave</option>
                                        <option value="paid">Paid Leave</option>
                                        <option value="emergency">Emergency Leave</option>
                                        <option value="halfday">Half Day</option>
                                        <option value="wfh">Work From Home</option>
                                    </select>
                                </div>
                              
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-3">Status</label>
                                    <select id="mobileFilterStatus" class="w-full px-4 py-3 border border-gray-300 rounded-lg text-base">
                                        <option value="">All Status</option>
                                        <option value="pending">Pending</option>
                                        <option value="approved">Approved</option>
                                        <option value="rejected">Rejected</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                      
                        <div class="pt-6 border-t border-gray-200">
                            <div class="btn-group-mobile">
                                <button id="resetMobileFilters" class="py-3 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50">
                                    Reset All Filters
                                </button>
                                <button id="applyMobileFilters" class="py-3 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700">
                                    Apply Filters
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Statistics Cards -->
                <div class="grid grid-cols-2 md:grid-cols-4 gap-3 mb-6">
                    <div class="bg-white rounded-lg border border-gray-200 p-4">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-mobile-sm text-sm text-gray-500 mb-1">Total</p>
                                <p class="text-mobile-xl text-xl font-bold text-gray-800" id="totalApps">0</p>
                            </div>
                            <div class="h-10 w-10 bg-blue-100 rounded-full flex items-center justify-center">
                                <i class="fas fa-file-alt text-blue-600"></i>
                            </div>
                        </div>
                    </div>
                    <div class="bg-white rounded-lg border border-gray-200 p-4">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-mobile-sm text-sm text-gray-500 mb-1">Pending</p>
                                <p class="text-mobile-xl text-xl font-bold text-yellow-600" id="pendingApps">0</p>
                            </div>
                            <div class="h-10 w-10 bg-yellow-100 rounded-full flex items-center justify-center">
                                <i class="fas fa-clock text-yellow-600"></i>
                            </div>
                        </div>
                    </div>
                    <div class="bg-white rounded-lg border border-gray-200 p-4">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-mobile-sm text-sm text-gray-500 mb-1">Approved</p>
                                <p class="text-mobile-xl text-xl font-bold text-green-600" id="approvedApps">0</p>
                            </div>
                            <div class="h-10 w-10 bg-green-100 rounded-full flex items-center justify-center">
                                <i class="fas fa-check-circle text-green-600"></i>
                            </div>
                        </div>
                    </div>
                    <div class="bg-white rounded-lg border border-gray-200 p-4">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-mobile-sm text-sm text-gray-500 mb-1">Rejected</p>
                                <p class="text-mobile-xl text-xl font-bold text-red-600" id="rejectedApps">0</p>
                            </div>
                            <div class="h-10 w-10 bg-red-100 rounded-full flex items-center justify-center">
                                <i class="fas fa-times-circle text-red-600"></i>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Desktop Filters -->
                <div class="desktop-only mb-6">
                    <div class="flex items-center justify-between">
                        <div class="text-sm text-gray-500">
                            Showing <span id="leavesCount" class="font-medium text-gray-700">0</span> applications
                        </div>
                        <div class="flex items-center space-x-4">
                            <div class="flex items-center space-x-2">
                                <span class="text-sm text-gray-500">Type:</span>
                                <select id="filterType" class="border border-gray-300 rounded-lg py-2 px-3 text-sm">
                                    <option value="">All Types</option>
                                    <option value="casual">Casual</option>
                                    <option value="sick">Sick</option>
                                    <option value="paid">Paid</option>
                                    <option value="emergency">Emergency</option>
                                    <option value="halfday">Half Day</option>
                                    <option value="wfh">WFH</option>
                                </select>
                            </div>
                            <div class="flex items-center space-x-2">
                                <span class="text-sm text-gray-500">Status:</span>
                                <select id="filterStatus" class="border border-gray-300 rounded-lg py-2 px-3 text-sm">
                                    <option value="">All Status</option>
                                    <option value="pending">Pending</option>
                                    <option value="approved">Approved</option>
                                    <option value="rejected">Rejected</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Mobile Summary -->
                <div class="mobile-only mb-4">
                    <div class="flex items-center justify-between text-mobile-base">
                        <span class="font-medium text-gray-700">
                            <span id="mobileLeavesCount">0</span> applications
                        </span>
                        <span class="text-gray-500" id="mobileFilterSummary">All</span>
                    </div>
                </div>
                <!-- Leaves List -->
                <div id="leavesList">
                    <div class="text-center py-12">
                        <div class="animate-pulse">
                            <i class="fas fa-calendar-alt text-gray-300 text-4xl mb-4"></i>
                            <h3 class="text-lg font-medium text-gray-700 mb-2">Loading leave applications...</h3>
                            <p class="text-gray-500 text-sm">Please wait while we load your leave records</p>
                        </div>
                    </div>
                </div>
                <!-- Desktop Table -->
                <div id="desktopTable" class="desktop-only table-container">
                    <table class="min-w-full">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="py-3 px-4 text-left text-sm font-medium text-gray-700">Employee</th>
                                <th class="py-3 px-4 text-left text-sm font-medium text-gray-700">Position</th>
                                <th class="py-3 px-4 text-left text-sm font-medium text-gray-700">Leave Dates</th>
                                <th class="py-3 px-4 text-left text-sm font-medium text-gray-700">Days</th>
                                <th class="py-3 px-4 text-left text-sm font-medium text-gray-700">Status</th>
                                <th class="py-3 px-4 text-left text-sm font-medium text-gray-700">Applied Date</th>
                                <th class="py-3 px-4 text-left text-sm font-medium text-gray-700">Actions</th>
                            </tr>
                        </thead>
                        <tbody id="leavesTableBody" class="mb-8"></tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <!-- Apply Leave Page -->
    <div id="applyLeavePage" class="page-container">
        <div class="container-mobile container-desktop">
            <div class="py-4 safe-area-top">
                <div class="mobile-only mb-4">
                    <button id="backToDashboardMobile" class="flex items-center text-gray-600 hover:text-gray-800 py-2">
                        <i class="fas fa-arrow-left mr-2"></i>
                        <span>Back</span>
                    </button>
                </div>
              
                <div class="mb-6">
                    <div class="flex flex-col md:flex-row md:items-center md:justify-between">
                        <div class="mb-4 md:mb-0">
                            <h1 class="text-mobile-2xl text-2xl font-bold text-gray-800 mb-1">Apply for Leave</h1>
                            <p class="text-mobile-base text-gray-600">Fill in your leave application details</p>
                        </div>
                        <button id="backToDashboard" class="desktop-only flex items-center text-gray-600 hover:text-gray-800 py-2">
                            <i class="fas fa-arrow-left mr-2"></i>
                            <span>Back to Dashboard</span>
                        </button>
                    </div>
                </div>
                <div class="bg-white rounded-xl shadow-sm p-4 md:p-6 mb-8">
                    <form id="leaveForm" class="space-y-6">
                        @csrf
                        <div class="form-grid-mobile md:grid md:grid-cols-2 md:gap-6">
                            <div class="space-y-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Full Name *</label>
                                    <input type="text" id="employeeName" name="employee_name" required
                                           class="form-input-mobile w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                                           placeholder="Enter your full name">
                                    <div id="employeeNameError" class="text-red-500 text-xs mt-1 hidden"></div>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Email Address *</label>
                                    <input type="email" id="employeeEmail" name="employee_email" required
                                           class="form-input-mobile w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                                           placeholder="Enter your email">
                                    <div id="employeeEmailError" class="text-red-500 text-xs mt-1 hidden"></div>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">From Date *</label>
                                    <input type="date" id="fromDate" name="from_date" required
                                           class="form-input-mobile w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                                    <div id="fromDateError" class="text-red-500 text-xs mt-1 hidden"></div>
                                </div>
                            </div>
                            <div class="space-y-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Position/Role *</label>
                                    <select id="employeePosition" name="employee_position" required
                                            class="form-input-mobile w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                                        <option value="">Select a role</option>
                                    </select>
                                    <div id="employeePositionError" class="text-red-500 text-xs mt-1 hidden"></div>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Mobile Number *</label>
                                    <input type="tel" id="employeeMobile" name="employee_mobile" required
                                           class="form-input-mobile w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                                           placeholder="Enter your mobile number">
                                    <div id="employeeMobileError" class="text-red-500 text-xs mt-1 hidden"></div>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">To Date *</label>
                                    <input type="date" id="toDate" name="to_date" required
                                           class="form-input-mobile w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                                    <div id="toDateError" class="text-red-500 text-xs mt-1 hidden"></div>
                                </div>
                            </div>
                        </div>
                      
                        <div class="space-y-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">To (Recipient) *</label>
                                <input type="text" id="sentTo" name="sent_to"
                                       class="form-input-mobile w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                                       placeholder="e.g., HR Manager, Department Head" required>
                                <div id="sentToError" class="text-red-500 text-xs mt-1 hidden"></div>
                            </div>
                          
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Subject *</label>
                                <input type="text" id="subject" name="subject"
                                       class="form-input-mobile w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                                       placeholder="e.g., Leave Application for Family Event" required>
                                <div id="subjectError" class="text-red-500 text-xs mt-1 hidden"></div>
                            </div>
                          
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Total Days</label>
                                <input type="text" id="totalDays" name="total_days" value="0"
                                       class="form-input-mobile w-full px-4 py-3 border border-gray-300 rounded-lg bg-gray-50" readonly>
                            </div>
                          
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Leave Reason *</label>
                                <div class="email-editor" id="reason" contenteditable="true" placeholder="Start writing your leave application here...&#10;&#10;You can format your message like an email. Include details about why you need leave, how your work will be managed, and when you'll return.">
                                </div>
                                <textarea id="reasonText" name="reason" class="hidden"></textarea>
                                <div id="reasonError" class="text-red-500 text-xs mt-1 hidden"></div>
                            </div>
                          
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Attachments (Optional)</label>
                                <div class="border-2 border-dashed border-gray-300 rounded-lg p-4 md:p-6 text-center">
                                    <i class="fas fa-cloud-upload-alt text-2xl md:text-3xl text-gray-400 mb-3"></i>
                                    <p class="text-gray-600 mb-3 text-sm md:text-base">Drag and drop files here or click to upload</p>
                                    <input type="file" id="attachments" name="attachments[]" class="hidden" multiple>
                                    <label for="attachments" class="inline-block bg-gray-100 hover:bg-gray-200 text-gray-800 font-medium py-2 px-4 rounded-lg cursor-pointer">
                                        <i class="fas fa-paperclip mr-2"></i>Choose Files
                                    </label>
                                    <p class="text-gray-500 text-xs md:text-sm mt-3">Max file size: 5MB. Supported formats: PDF, JPG, PNG, DOC</p>
                                </div>
                                <div id="filePreview" class="mt-3 space-y-2 hidden"></div>
                            </div>
                        </div>
                      
                        <div class="btn-group-mobile md:flex md:justify-end md:space-x-4 pt-6 border-t border-gray-200">
                            <button type="button" id="cancelLeaveForm"
                                    class="w-full md:w-auto py-3 px-6 border border-gray-300 text-gray-700 font-medium rounded-lg hover:bg-gray-50 mb-3 md:mb-0">
                                Cancel
                            </button>
                            <button type="submit"
                                    class="w-full md:w-auto py-3 px-6 bg-blue-600 text-white font-medium rounded-lg hover:bg-blue-700 flex items-center justify-center">
                                <i class="fa-spin hidden mr-2" id="submitSpinner"></i>
                                Submit Leave Request
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- View Leave Details Page -->
    <div id="viewLeavePage" class="page-container">
        <div class="container-mobile container-desktop">
            <div class="py-4 safe-area-top">
                <div class="mobile-only mb-4">
                    <button id="backFromViewMobile" class="flex items-center text-gray-600 hover:text-gray-800 py-2">
                        <i class="fas fa-arrow-left mr-2"></i>
                        <span>Back</span>
                    </button>
                </div>
              
                <div class="mb-6">
                    <div class="flex flex-col md:flex-row md:items-center md:justify-between">
                        <div class="mb-4 md:mb-0">
                            <h1 class="text-mobile-2xl text-2xl font-bold text-gray-800 mb-1">Leave Application Details</h1>
                            <p class="text-mobile-base text-gray-600">View complete leave application information</p>
                        </div>
                        <button id="backFromView" class="desktop-only flex items-center text-gray-600 hover:text-gray-800 py-2">
                            <i class="fas fa-arrow-left mr-2"></i>
                            <span>Back to Dashboard</span>
                        </button>
                    </div>
                </div>
                <div class="bg-white rounded-xl shadow-sm p-4 md:p-6">
                    <div id="leaveDetailsContent"></div>
                </div>
            </div>
        </div>
    </div>
    <!-- Edit Leave Status Page -->
    <div id="editLeavePage" class="page-container">
        <div class="container-mobile container-desktop">
            <div class="py-4 safe-area-top">
                <div class="mobile-only mb-4">
                    <button id="backFromEditMobile" class="flex items-center text-gray-600 hover:text-gray-800 py-2">
                        <i class="fas fa-arrow-left mr-2"></i>
                        <span>Back</span>
                    </button>
                </div>
              
                <div class="mb-6">
                    <div class="flex flex-col md:flex-row md:items-center md:justify-between">
                        <div class="mb-4 md:mb-0">
                            <h1 class="text-mobile-2xl text-2xl font-bold text-gray-800 mb-1">Edit Leave Application</h1>
                            <p class="text-mobile-base text-gray-600">Update leave application details, status and remarks</p>
                        </div>
                        <button id="backFromEdit" class="desktop-only flex items-center text-gray-600 hover:text-gray-800 py-2">
                            <i class="fas fa-arrow-left mr-2"></i>
                            <span>Back to Dashboard</span>
                        </button>
                    </div>
                </div>
                <div class="bg-white rounded-xl shadow-sm p-4 md:p-6">
                    <div id="editLeaveContent" class="mb-6 md:mb-8"></div>
                  
                    <div class="pt-6 border-t border-gray-200">
                        <h3 class="text-lg font-semibold text-gray-800 mb-4">Update Status & Remarks</h3>
                        <form id="updateStatusForm">
                            @csrf
                            @method('PUT')
                            <input type="hidden" id="editLeaveId" name="id">
                          
                            <div class="mb-4">
                                <label class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                                <select id="editStatus" name="status"
                                        class="form-input-mobile w-full px-4 py-3 border border-gray-300 rounded-lg" disabled>
                                    <option value="pending">Pending</option>
                                    <option value="approved">Approved</option>
                                    <option value="rejected">Rejected</option>
                                </select>
                            </div>
                          
                            <div class="mb-6">
                                <label class="block text-sm font-medium text-gray-700 mb-2">Admin Remarks</label>
                                <textarea id="editAdminRemarks" name="admin_remarks" rows="3"
                                          class="form-input-mobile w-full px-4 py-3 border border-gray-300 rounded-lg"
                                          placeholder="Add remarks or comments..." disabled></textarea>
                            </div>
                          
                            <div class="btn-group-mobile md:flex md:justify-end md:space-x-3">
                                <button type="button" id="cancelEdit"
                                        class="w-full md:w-auto py-3 px-4 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 mb-3 md:mb-0">
                                    Cancel
                                </button>
                                <button type="submit"
                                        class="w-full md:w-auto py-3 px-4 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 flex items-center justify-center">
                                    <i class="fa-spin hidden mr-2" id="editSpinner"></i>
                                    Save Changes
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Notification Toast -->
    <div id="notificationToast" class="fixed bottom-4 right-4 left-4 md:left-auto bg-white rounded-xl shadow-lg border border-gray-200 p-4 max-w-full md:max-w-md transform translate-y-full transition-transform duration-300 z-50 hidden">
        <div class="flex items-start">
            <div id="toastIcon" class="mr-3 mt-1 flex-shrink-0"></div>
            <div class="flex-1 min-w-0">
                <h5 id="toastTitle" class="font-bold text-gray-800 truncate"></h5>
                <p id="toastMessage" class="text-gray-600 text-sm mt-1 break-words"></p>
            </div>
            <button id="closeToast" class="ml-4 text-gray-500 hover:text-gray-700 flex-shrink-0">
                <i class="fas fa-times"></i>
            </button>
        </div>
    </div>
    <script>
        // DOM Elements
        const leavesList = document.getElementById('leavesList');
        const leavesTableBody = document.getElementById('leavesTableBody');
        const leaveForm = document.getElementById('leaveForm');
        const updateStatusForm = document.getElementById('updateStatusForm');
        const filterType = document.getElementById('filterType');
        const filterStatus = document.getElementById('filterStatus');
        const mobileFilterType = document.getElementById('mobileFilterType');
        const mobileFilterStatus = document.getElementById('mobileFilterStatus');
        const leavesCount = document.getElementById('leavesCount');
        const mobileLeavesCount = document.getElementById('mobileLeavesCount');
        const mobileFilterSummary = document.getElementById('mobileFilterSummary');
      
        // Statistics elements
        const totalAppsElement = document.getElementById('totalApps');
        const pendingAppsElement = document.getElementById('pendingApps');
        const approvedAppsElement = document.getElementById('approvedApps');
        const rejectedAppsElement = document.getElementById('rejectedApps');
        // Page elements
        const dashboardPage = document.getElementById('dashboardPage');
        const applyLeavePage = document.getElementById('applyLeavePage');
        const viewLeavePage = document.getElementById('viewLeavePage');
        const editLeavePage = document.getElementById('editLeavePage');
        // Mobile elements
        const mobileMenuBtn = document.getElementById('mobileMenuBtn');
        const mobileMenuOverlay = document.getElementById('mobileMenuOverlay');
        const closeMobileMenu = document.getElementById('closeMobileMenu');
        const mobileFilterBtn = document.getElementById('mobileFilterBtn');
        const mobileFilterModal = document.getElementById('mobileFilterModal');
        const closeMobileFilter = document.getElementById('closeMobileFilter');
        const resetMobileFilters = document.getElementById('resetMobileFilters');
        const applyMobileFilters = document.getElementById('applyMobileFilters');
        // Initialize the application
        function initApp() {
            loadLeaves();
          
            const today = new Date().toISOString().split('T')[0];
            document.getElementById('fromDate').value = today;
            document.getElementById('toDate').value = today;
            calculateTotalDays();
          
            initializeReasonEditor();
          
            document.getElementById('addLeaveBtn').addEventListener('click', showApplyLeavePage);
          
            document.getElementById('backToDashboard').addEventListener('click', showDashboardPage);
            document.getElementById('backToDashboardMobile').addEventListener('click', showDashboardPage);
            document.getElementById('backFromView').addEventListener('click', showDashboardPage);
            document.getElementById('backFromViewMobile').addEventListener('click', showDashboardPage);
            document.getElementById('backFromEdit').addEventListener('click', showDashboardPage);
            document.getElementById('backFromEditMobile').addEventListener('click', showDashboardPage);
            document.getElementById('cancelLeaveForm').addEventListener('click', showDashboardPage);
            document.getElementById('cancelEdit').addEventListener('click', showDashboardPage);
          
            leaveForm.addEventListener('submit', saveNewLeave);
            updateStatusForm.addEventListener('submit', updateLeaveStatus);
          
            if (filterType) filterType.addEventListener('change', filterLeaves);
            if (filterStatus) filterStatus.addEventListener('change', filterLeaves);
          
            document.getElementById('fromDate').addEventListener('change', calculateTotalDays);
            document.getElementById('toDate').addEventListener('change', calculateTotalDays);
          
            document.getElementById('attachments').addEventListener('change', handleFileUpload);
          
            mobileMenuBtn.addEventListener('click', openMobileMenu);
            closeMobileMenu.addEventListener('click', closeMobileMenuOverlay);
          
            mobileFilterBtn.addEventListener('click', openMobileFilterModal);
            closeMobileFilter.addEventListener('click', closeMobileFilterModal);
            applyMobileFilters.addEventListener('click', applyMobileFiltersHandler);
            resetMobileFilters.addEventListener('click', resetMobileFiltersHandler);
          
            document.getElementById('closeToast').addEventListener('click', hideNotificationToast);
          
            updateMobileFilterSummary();
          
            window.addEventListener('resize', handleResize);
          
            setupModalScrollPrevention();
        }
        function initializeReasonEditor() {
            const reasonEditor = document.getElementById('reason');
            reasonEditor.innerHTML = '';
            reasonEditor.addEventListener('focus', function() {
                if (this.innerHTML === '' || this.innerHTML === this.getAttribute('placeholder')) {
                    this.innerHTML = '';
                }
            });
            reasonEditor.addEventListener('blur', function() {
                if (this.innerHTML === '') {
                    this.innerHTML = '';
                }
            });
            reasonEditor.addEventListener('paste', function(e) {
                e.preventDefault();
                const text = e.clipboardData.getData('text/plain');
                document.execCommand('insertText', false, text);
            });
        }
        function openMobileMenu() {
            mobileMenuOverlay.classList.remove('hidden');
            document.body.style.overflow = 'hidden';
        }
        function closeMobileMenuOverlay() {
            mobileMenuOverlay.classList.add('hidden');
            document.body.style.overflow = 'auto';
        }
        function openMobileFilterModal() {
            mobileFilterModal.classList.remove('hidden');
            document.body.style.overflow = 'hidden';
        }
        function closeMobileFilterModal() {
            mobileFilterModal.classList.add('hidden');
            document.body.style.overflow = 'auto';
        }
        function applyMobileFiltersHandler() {
            if (filterType) filterType.value = mobileFilterType.value;
            if (filterStatus) filterStatus.value = mobileFilterStatus.value;
            filterLeaves();
            closeMobileFilterModal();
            updateMobileFilterSummary();
        }
        function resetMobileFiltersHandler() {
            mobileFilterType.value = '';
            mobileFilterStatus.value = '';
            if (filterType) filterType.value = '';
            if (filterStatus) filterStatus.value = '';
            filterLeaves();
            updateMobileFilterSummary();
        }
        function updateMobileFilterSummary() {
            let summary = 'All';
            let filters = [];
            if (filterType && filterType.value) {
                const typeText = getLeaveTypeName(filterType.value);
                filters.push(typeText);
            }
            if (filterStatus && filterStatus.value) {
                filters.push(filterStatus.value.charAt(0).toUpperCase() + filterStatus.value.slice(1));
            }
            if (filters.length > 0) {
                summary = filters.join(', ');
            }
            if (mobileFilterSummary) {
                mobileFilterSummary.textContent = summary;
            }
        }
        function showDashboardPage() {
            closeMobileMenuOverlay();
            closeMobileFilterModal();
            dashboardPage.classList.add('page-active');
            applyLeavePage.classList.remove('page-active');
            viewLeavePage.classList.remove('page-active');
            editLeavePage.classList.remove('page-active');
            loadLeaves();
        }
        function showApplyLeavePage() {
            closeMobileMenuOverlay();
            closeMobileFilterModal();
            dashboardPage.classList.remove('page-active');
            applyLeavePage.classList.add('page-active');
            viewLeavePage.classList.remove('page-active');
            editLeavePage.classList.remove('page-active');
          
            const today = new Date().toISOString().split('T')[0];
            document.getElementById('fromDate').value = today;
            document.getElementById('toDate').value = today;
            calculateTotalDays();
          
            document.getElementById('employeeName').value = '';
            document.getElementById('employeeEmail').value = '';
            document.getElementById('employeeMobile').value = '';
            document.getElementById('sentTo').value = '';
            document.getElementById('subject').value = '';
          
            const reasonEditor = document.getElementById('reason');
            reasonEditor.innerHTML = '';
          
            document.getElementById('filePreview').classList.add('hidden');
            document.getElementById('attachments').value = '';
          
            clearFormErrors();
          
            loadRoles();
          
            setTimeout(() => {
                document.getElementById('employeeName').focus();
            }, 100);
        }
        function loadRoles() {
            const positionSelect = document.getElementById('employeePosition');
            positionSelect.innerHTML = '<option value="">Loading roles...</option>';
          
            fetch('{{ route("employeeportal.roles") }}', {
                headers: {
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    positionSelect.innerHTML = '<option value="">Select a role</option>';
                    data.roles.forEach(role => {
                        const option = document.createElement('option');
                        option.value = role.name;
                        option.textContent = role.name.charAt(0).toUpperCase() + role.name.slice(1);
                        positionSelect.appendChild(option);
                    });
                } else {
                    positionSelect.innerHTML = '<option value="">Failed to load roles</option>';
                }
            })
            .catch(error => {
                console.error('Error loading roles:', error);
                positionSelect.innerHTML = '<option value="">Error loading roles</option>';
            });
        }
        function showViewLeavePage(leaveId) {
            closeMobileMenuOverlay();
            closeMobileFilterModal();
          
            fetch(`{{ route('employeeportal.leave.show', '') }}/${leaveId}`, {
                headers: {
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    const leave = data.leave;
                    dashboardPage.classList.remove('page-active');
                    applyLeavePage.classList.remove('page-active');
                    viewLeavePage.classList.add('page-active');
                    editLeavePage.classList.remove('page-active');
                  
                    const statusClass = getStatusClass(leave.status);
                    const statusText = leave.status.charAt(0).toUpperCase() + leave.status.slice(1);
                  
                    let html = `
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                            <div>
                                <h4 class="font-semibold text-gray-700 mb-3">Employee Details</h4>
                                <div class="space-y-3">
                                    <div class="flex justify-between"><span class="text-gray-600">Name:</span><span class="font-medium">${leave.employee_name}</span></div>
                                    <div class="flex justify-between"><span class="text-gray-600">Position:</span><span class="font-medium">${leave.employee_position}</span></div>
                                    <div class="flex justify-between"><span class="text-gray-600">Email:</span><span class="font-medium">${leave.employee_email}</span></div>
                                    <div class="flex justify-between"><span class="text-gray-600">Phone:</span><span class="font-medium">${leave.employee_mobile}</span></div>
                                </div>
                            </div>
                            <div>
                                <h4 class="font-semibold text-gray-700 mb-3">Leave Details</h4>
                                <div class="space-y-3">
                                    <div class="flex justify-between"><span class="text-gray-600">From - To:</span><span class="font-medium">${formatDate(leave.from_date)} - ${formatDate(leave.to_date)}</span></div>
                                    <div class="flex justify-between"><span class="text-gray-600">Total Days:</span><span class="font-medium">${leave.total_days} day${leave.total_days > 1 ? 's' : ''}</span></div>
                                    <div class="flex justify-between"><span class="text-gray-600">Status:</span><span class="${statusClass} px-3 py-1 rounded-full text-sm font-medium">${statusText}</span></div>
                                </div>
                            </div>
                        </div>
                        <div class="mb-6"><h4 class="font-semibold text-gray-700 mb-2">Subject</h4><p class="text-gray-800 bg-gray-50 p-3 rounded-lg">${leave.subject}</p></div>
                        <div class="mb-6"><h4 class="font-semibold text-gray-700 mb-2">Leave Reason</h4><div class="text-gray-800 bg-gray-50 p-4 rounded-lg">${leave.reason}</div></div>
                    `;
                    if (leave.admin_remarks) {
                        html += `<div class="mb-6"><h4 class="font-semibold text-gray-700 mb-2">Admin Remarks</h4><p class="text-gray-800 bg-blue-50 p-4 rounded-lg border border-blue-100">${leave.admin_remarks}</p></div>`;
                    }
                    if (leave.timeline && leave.timeline.length > 0) {
                        html += `<h4 class="font-semibold text-gray-700 mb-4">Timeline</h4><div class="pl-4">${generateTimelineHTML(leave.timeline)}</div>`;
                    }
                    document.getElementById('leaveDetailsContent').innerHTML = html;
                } else {
                    throw new Error(data.message || 'Failed to load leave');
                }
            })
            .catch(error => {
                console.error('Error loading leave:', error);
                showNotification('error', 'Load Failed', error.message);
                showDashboardPage();
            });
        }
        function showEditLeavePage(leaveId) {
            closeMobileMenuOverlay();
            closeMobileFilterModal();
          
            fetch(`{{ route('employeeportal.leave.show', '') }}/${leaveId}`, {
                headers: {
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    const leave = data.leave;
                    dashboardPage.classList.remove('page-active');
                    applyLeavePage.classList.remove('page-active');
                    viewLeavePage.classList.remove('page-active');
                    editLeavePage.classList.add('page-active');
                  
                    document.getElementById('editLeaveId').value = leave.id;
                    document.getElementById('editStatus').value = leave.status;
                    document.getElementById('editAdminRemarks').value = leave.admin_remarks || '';
                  
                    const statusClass = getStatusClass(leave.status);
                    const statusText = leave.status.charAt(0).toUpperCase() + leave.status.slice(1);
                  
                    let html = `
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                            <div>
                                <h4 class="font-semibold text-gray-700 mb-3">Employee Details</h4>
                                <div class="space-y-2">
                                    <div class="flex justify-between"><span class="text-gray-600">Name:</span><span class="font-medium">${leave.employee_name}</span></div>
                                    <div class="flex justify-between"><span class="text-gray-600">Position:</span><span class="font-medium">${leave.employee_position}</span></div>
                                    <div class="flex justify-between"><span class="text-gray-600">Email:</span><span class="font-medium">${leave.employee_email}</span></div>
                                    <div class="flex justify-between"><span class="text-gray-600">Phone:</span><span class="font-medium">${leave.employee_mobile}</span></div>
                                </div>
                            </div>
                            <div>
                                <h4 class="font-semibold text-gray-700 mb-3">Leave Details</h4>
                                <div class="space-y-2">
                                    <div class="flex justify-between"><span class="text-gray-600">Type:</span><select id="editLeaveType" class="border border-gray-300 rounded-lg py-1 px-3 text-sm">
                                        <option value="casual">Casual Leave</option>
                                        <option value="sick">Sick Leave</option>
                                        <option value="paid">Paid Leave</option>
                                        <option value="emergency">Emergency Leave</option>
                                        <option value="halfday">Half Day</option>
                                        <option value="wfh">Work From Home</option>
                                    </select></div>
                                    <div class="flex justify-between"><span class="text-gray-600">Dates:</span><span class="font-medium">${formatDate(leave.from_date)} - ${formatDate(leave.to_date)}</span></div>
                                    <div class="flex justify-between"><span class="text-gray-600">Days:</span><span class="font-medium">${leave.total_days} day${leave.total_days > 1 ? 's' : ''}</span></div>
                                    <div class="flex justify-between"><span class="text-gray-600">Current Status:</span><span class="${statusClass} px-3 py-1 rounded-full text-sm font-medium">${statusText}</span></div>
                                </div>
                            </div>
                        </div>
                        <div class="mb-4"><h4 class="font-semibold text-gray-700 mb-2">Subject (Editable)</h4><input type="text" id="editSubject" class="form-input-mobile w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"><div id="editSubjectError" class="text-red-500 text-xs mt-1 hidden"></div></div>
                        <div class="mb-4"><h4 class="font-semibold text-gray-700 mb-2">Sent To (Editable)</h4><input type="text" id="editSentTo" class="form-input-mobile w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"><div id="editSentToError" class="text-red-500 text-xs mt-1 hidden"></div></div>
                        <div class="mb-6"><h4 class="font-semibold text-gray-700 mb-2">Leave Reason (Editable)</h4><div class="email-editor" id="editReason" contenteditable="true" placeholder="Click here to edit the leave reason..."></div><div id="editReasonError" class="text-red-500 text-xs mt-1 hidden"></div></div>
                    `;
                    document.getElementById('editLeaveContent').innerHTML = html;
                    document.getElementById('editSubject').value = leave.subject || '';
                    document.getElementById('editSentTo').value = leave.sent_to || '';
                    document.getElementById('editLeaveType').value = leave.leave_type || 'casual';
                    const editReason = document.getElementById('editReason');
                    if (editReason) {
                        editReason.innerHTML = leave.reason || '';
                    }
                    document.querySelectorAll('#editSubjectError, #editSentToError, #editReasonError').forEach(el => el.classList.add('hidden'));
                    if (editReason) {
                        editReason.addEventListener('paste', function(e) {
                            e.preventDefault();
                            const text = e.clipboardData.getData('text/plain');
                            document.execCommand('insertText', false, text);
                        });
                    }
                } else {
                    throw new Error(data.message || 'Failed to load leave');
                }
            })
            .catch(error => {
                console.error('Error loading leave:', error);
                showNotification('error', 'Load Failed', error.message);
                showDashboardPage();
            });
        }
        function loadLeaves() {
            showLoadingState();
            const type = filterType ? filterType.value : '';
            const status = filterStatus ? filterStatus.value : '';
            let url = '{{ route("employeeportal.leaves") }}';
            const params = new URLSearchParams();
            if (type) params.append('leave_type', type);
            if (status) params.append('status', status);
            if (params.toString()) url += '?' + params.toString();
            fetch(url, {
                headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    renderLeaves(data.leaves);
                    updateStatistics(data);
                    updateLeavesCount(data.leaves.length);
                    updateMobileFilterSummary();
                } else {
                    throw new Error(data.message || 'Failed to load leaves');
                }
            })
            .catch(error => {
                console.error('Error loading leaves:', error);
                showErrorState('Failed to load leaves. Please try again.');
            });
        }
        function showLoadingState() {
            leavesList.innerHTML = `<div class="text-center py-12"><div class="animate-pulse"><i class="fas fa-calendar-alt text-gray-300 text-4xl mb-4"></i><h3 class="text-lg font-medium text-gray-700 mb-2">Loading leave applications...</h3><p class="text-gray-500 text-sm">Please wait while we load your leave records</p></div></div>`;
            leavesTableBody.innerHTML = '';
        }
        function showErrorState(message) {
            leavesList.innerHTML = `<div class="text-center py-12"><i class="fas fa-exclamation-triangle text-red-400 text-4xl mb-4"></i><h3 class="text-lg font-medium text-gray-700 mb-2">Unable to load leaves</h3><p class="text-gray-500 text-sm mb-6">${message}</p><button onclick="loadLeaves()" class="bg-indigo-600 text-white py-3 px-6 rounded-lg font-medium hover:bg-indigo-700"><i class="fas fa-redo mr-2"></i> Try Again</button></div>`;
        }
        function showNoDataState() {
            leavesList.innerHTML = `<div class="text-center py-12"><i class="fas fa-calendar-alt text-gray-300 text-4xl mb-4"></i><h3 class="text-lg font-medium text-gray-700 mb-2">No leave applications found</h3><p class="text-gray-500 text-sm mb-6">Get started by applying for your first leave.</p><button onclick="showApplyLeavePage()" class="bg-orange-500 text-white py-3 px-6 rounded-lg font-medium hover:bg-orange-600"><i class="fas fa-plus mr-2"></i> Apply for Leave</button></div>`;
            leavesTableBody.innerHTML = '';
        }
        function updateLeavesCount(count) {
            if (leavesCount) leavesCount.textContent = count;
            if (mobileLeavesCount) mobileLeavesCount.textContent = count;
        }
        function updateStatistics(data) {
            if (totalAppsElement) totalAppsElement.textContent = data.total || 0;
            if (pendingAppsElement) pendingAppsElement.textContent = data.pending_count || 0;
            if (approvedAppsElement) approvedAppsElement.textContent = data.approved_count || 0;
            if (rejectedAppsElement) rejectedAppsElement.textContent = data.rejected_count || 0;
        }
        function renderLeaves(leaves) {
            if (!leaves || leaves.length === 0) {
                showNoDataState();
                return;
            }
            leavesList.innerHTML = '';
            leavesTableBody.innerHTML = '';
            renderMobileLeavesCards(leaves);
            renderDesktopLeavesTable(leaves);
        }
        function renderMobileLeavesCards(leaves) {
            const isMobile = window.innerWidth < 768;
            if (!isMobile) return;
            let cardsHTML = '';
            leaves.forEach(leave => {
                const statusClass = getStatusClass(leave.status);
                const statusText = leave.status.charAt(0).toUpperCase() + leave.status.slice(1);
                const appliedDate = formatDate(leave.applied_date || leave.created_at);
                const leaveDates = `${formatDate(leave.from_date)} - ${formatDate(leave.to_date)}`;
                cardsHTML += `
                    <div class="card-mobile">
                        <div class="flex items-start justify-between mb-3">
                            <div class="flex-1 min-w-0">
                                <div class="flex items-center mb-2">
                                    <div class="h-10 w-10 rounded-full bg-orange-100 flex items-center justify-center mr-3 flex-shrink-0">
                                        <span class="text-orange-600 font-medium ">${getInitials(leave.employee_name)}</span>
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <h3 class="font-semibold text-gray-800 truncate-mobile">${leave.employee_name}</h3>
                                        <p class="text-gray-500 text-sm truncate-mobile">${leave.employee_position}</p>
                                    </div>
                                </div>
                                <div class="grid grid-cols-2 gap-2 mb-3">
                                    <div><p class="text-gray-500 text-xs mb-1">Leave Dates</p><p class="font-medium text-sm">${leaveDates}</p></div>
                                    <div><p class="text-gray-500 text-xs mb-1">Days</p><p class="font-medium text-sm">${leave.total_days} day${leave.total_days > 1 ? 's' : ''}</p></div>
                                    <div><p class="text-gray-500 text-xs mb-1">Status</p><span class="${statusClass} px-2 py-1 rounded-full text-xs font-medium">${statusText}</span></div>
                                    <div><p class="text-gray-500 text-xs mb-1">Applied</p><p class="font-medium text-sm">${appliedDate}</p></div>
                                </div>
                            </div>
                        </div>
                        <div class="flex items-center justify-between pt-3 mb-[45px] border-t border-gray-100">
                            <div class="flex items-center space-x-2">
                                <button onclick="showViewLeavePage(${leave.id})" class="p-2 text-blue-600 hover:text-blue-800 rounded-lg hover:bg-blue-50"><i class="fas fa-eye"></i></button>
                                <button onclick="showEditLeavePage(${leave.id})" class="p-2 text-green-600 hover:text-green-800 rounded-lg hover:bg-green-50"><i class="fas fa-edit"></i></button>
                                <button onclick="deleteLeave(${leave.id})" class="p-2 text-red-600 hover:text-red-800 rounded-lg hover:bg-red-50"><i class="fas fa-trash"></i></button>
                            </div>
                            <span class="text-gray-500 text-sm">${leave.employee_email}</span>
                        </div>
                    </div>
                `;
            });
            leavesList.innerHTML = cardsHTML;
        }
        function renderDesktopLeavesTable(leaves) {
            const isDesktop = window.innerWidth >= 768;
            if (!isDesktop) return;
            let tableHTML = '';
            leaves.forEach(leave => {
                const statusClass = getStatusClass(leave.status);
                const statusText = leave.status.charAt(0).toUpperCase() + leave.status.slice(1);
                const appliedDate = formatDate(leave.applied_date || leave.created_at);
                const leaveDates = `${formatDate(leave.from_date)} - ${formatDate(leave.to_date)}`;
                tableHTML += `
                    <tr class="hover:bg-gray-50">
                        <td class="py-3 px-4">
                            <div class="flex items-center">
                                <div class="h-8 w-8 rounded-full bg-orange-100 flex items-center justify-center mr-3">
                                    <span class="text-orange-600 font-medium text-sm">${getInitials(leave.employee_name)}</span>
                                </div>
                                <div>
                                    <p class="font-medium text-gray-800">${leave.employee_name}</p>
                                    <p class="text-gray-500 text-sm">${leave.employee_email}</p>
                                </div>
                            </div>
                        </td>
                        <td class="py-3 px-4"><span class="text-gray-800">${leave.employee_position}</span></td>
                        <td class="py-3 px-4"><span class="text-gray-800">${leaveDates}</span></td>
                        <td class="py-3 px-4"><span class="font-medium text-gray-800">${leave.total_days} day${leave.total_days > 1 ? 's' : ''}</span></td>
                        <td class="py-3 px-4"><span class="${statusClass} px-3 py-1 rounded-full text-xs font-medium">${statusText}</span></td>
                        <td class="py-3 px-4"><span class="text-gray-600 text-sm">${appliedDate}</span></td>
                        <td class="py-3 px-4">
                            <div class="flex items-center space-x-2">
                                <button onclick="showViewLeavePage(${leave.id})" class="p-1 text-blue-600 hover:text-blue-800" title="View"><i class="fas fa-eye"></i></button>
                                <button onclick="showEditLeavePage(${leave.id})" class="p-1 text-green-600 hover:text-green-800" title="Edit"><i class="fas fa-edit"></i></button>
                                <button onclick="deleteLeave(${leave.id})" class="p-1 text-red-600 hover:text-red-800" title="Delete"><i class="fas fa-trash"></i></button>
                            </div>
                        </td>
                    </tr>
                `;
            });
            leavesTableBody.innerHTML = tableHTML;
        }
        function clearFormErrors() {
            const errorElements = document.querySelectorAll(`[id$="Error"]`);
            errorElements.forEach(element => {
                element.classList.add('hidden');
                element.textContent = '';
            });
        }
        function calculateTotalDays() {
            const fromDate = document.getElementById('fromDate');
            const toDate = document.getElementById('toDate');
            const totalDays = document.getElementById('totalDays');
            if (!fromDate.value || !toDate.value) {
                totalDays.value = 1;
                return;
            }
            const from = new Date(fromDate.value);
            const to = new Date(toDate.value);
            if (from > to) {
                toDate.value = fromDate.value;
                calculateTotalDays();
                return;
            }
            const diffTime = Math.abs(to - from);
            const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24)) + 1;
            totalDays.value = diffDays;
        }
        function handleFileUpload(event) {
            const files = event.target.files;
            const filePreview = document.getElementById('filePreview');
            if (files.length > 0) {
                filePreview.innerHTML = '';
                filePreview.classList.remove('hidden');
                for (let i = 0; i < files.length; i++) {
                    const file = files[i];
                    const fileSize = (file.size / 1024).toFixed(1);
                    const fileItem = document.createElement('div');
                    fileItem.className = 'flex items-center justify-between bg-blue-50 border border-blue-100 rounded-lg p-3';
                    fileItem.innerHTML = `
                        <div class="flex items-center min-w-0">
                            <i class="fas fa-file-pdf text-red-500 text-lg mr-3 flex-shrink-0"></i>
                            <div class="min-w-0">
                                <p class="font-medium text-gray-800 truncate text-sm">${file.name}</p>
                                <p class="text-gray-600 text-xs">${fileSize} KB</p>
                            </div>
                        </div>
                        <button type="button" class="text-red-500 hover:text-red-700 remove-file ml-3 flex-shrink-0" data-index="${i}">
                            <i class="fas fa-times"></i>
                        </button>
                    `;
                    filePreview.appendChild(fileItem);
                }
                document.querySelectorAll('.remove-file').forEach(btn => {
                    btn.addEventListener('click', function() {
                        const dataTransfer = new DataTransfer();
                        const fileInput = document.getElementById('attachments');
                        for (let i = 0; i < fileInput.files.length; i++) {
                            if (i != this.getAttribute('data-index')) {
                                dataTransfer.items.add(fileInput.files[i]);
                            }
                        }
                        fileInput.files = dataTransfer.files;
                        this.parentElement.remove();
                        if (filePreview.children.length === 0) {
                            filePreview.classList.add('hidden');
                        }
                    });
                });
            }
        }
        function saveNewLeave(e) {
            e.preventDefault();
            const submitButton = e.target.querySelector('button[type="submit"]');
            const spinner = document.getElementById('submitSpinner');
            submitButton.disabled = true;
            spinner.classList.remove('hidden');
            const formData = new FormData(leaveForm);
            const reasonDiv = document.getElementById('reason');
            const reasonContent = reasonDiv.innerHTML.trim();
            if (reasonContent === '' || reasonContent === '<br>') {
                showNotification('error', 'Validation Error', 'Please enter a leave reason.');
                submitButton.disabled = false;
                spinner.classList.add('hidden');
                return;
            }
            formData.set('reason', reasonContent);
            formData.set('total_days', document.getElementById('totalDays').value);
            formData.append('_token', '{{ csrf_token() }}');
            fetch('{{ route("employeeportal.leave.store") }}', {
                method: 'POST',
                headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' },
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showDashboardPage();
                    showNotification('success', 'Leave Application Submitted', 'Leave application submitted successfully!');
                } else {
                    if (data.errors) {
                        showFormErrors(data.errors);
                    } else {
                        throw new Error(data.message || 'Failed to submit leave application');
                    }
                }
            })
            .catch(error => {
                console.error('Error submitting leave:', error);
                showNotification('error', 'Submission Failed', error.message);
            })
            .finally(() => {
                submitButton.disabled = false;
                spinner.classList.add('hidden');
            });
        }
        function updateLeaveStatus(e) {
            e.preventDefault();
            const leaveId = document.getElementById('editLeaveId').value;
            const submitButton = e.target.querySelector('button[type="submit"]');
            const spinner = document.getElementById('editSpinner');
            submitButton.disabled = true;
            spinner.classList.remove('hidden');
            const formData = new FormData(updateStatusForm);
            formData.append('_token', '{{ csrf_token() }}');
            formData.append('_method', 'PUT');
            let hasError = false;
            const editSubject = document.getElementById('editSubject');
            if (editSubject) {
                const subjectVal = editSubject.value.trim();
                if (subjectVal === '') {
                    const err = document.getElementById('editSubjectError');
                    if (err) { err.textContent = 'Subject is required.'; err.classList.remove('hidden'); }
                    hasError = true;
                } else {
                    formData.append('subject', subjectVal);
                }
            }
            const editSentTo = document.getElementById('editSentTo');
            if (editSentTo) {
                const sentToVal = editSentTo.value.trim();
                if (sentToVal === '') {
                    const err = document.getElementById('editSentToError');
                    if (err) { err.textContent = 'Recipient is required.'; err.classList.remove('hidden'); }
                    hasError = true;
                } else {
                    formData.append('sent_to', sentToVal);
                }
            }
            const editReasonDiv = document.getElementById('editReason');
            if (editReasonDiv) {
                let reasonContent = editReasonDiv.innerHTML.trim();
                if (reasonContent === '' || reasonContent === '<br>' || reasonContent === '&nbsp;') {
                    const err = document.getElementById('editReasonError');
                    if (err) { err.textContent = 'Leave reason is required.'; err.classList.remove('hidden'); }
                    hasError = true;
                } else {
                    formData.append('reason', reasonContent);
                }
            }
            const editLeaveType = document.getElementById('editLeaveType');
            if (editLeaveType) {
                formData.append('leave_type', editLeaveType.value);
            }
            if (hasError) {
                submitButton.disabled = false;
                spinner.classList.add('hidden');
                return;
            }
            fetch(`{{ route('employeeportal.leave.update', '') }}/${leaveId}`, {
                method: 'POST',
                headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' },
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showDashboardPage();
                    showNotification('success', 'Changes Saved', 'Leave application updated successfully!');
                } else {
                    if (data.errors) {
                        showFormErrors(data.errors);
                    } else {
                        throw new Error(data.message || 'Failed to update leave');
                    }
                }
            })
            .catch(error => {
                console.error('Error updating leave:', error);
                showNotification('error', 'Update Failed', error.message);
            })
            .finally(() => {
                submitButton.disabled = false;
                spinner.classList.add('hidden');
            });
        }
        function deleteLeave(leaveId) {
            fetch(`{{ route('employeeportal.leave.show', '') }}/${leaveId}`, {
                headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    const leave = data.leave;
                    const popupHTML = `
                        <div id="deletePopup" class="fixed inset-0 bg-black/50 flex items-center justify-center z-[100] p-4 modal-overlay">
                            <div class="bg-white rounded-xl w-full max-w-md">
                                <div class="p-6">
                                    <div class="flex items-center mb-4">
                                        <div class="h-10 w-10 rounded-full bg-red-100 flex items-center justify-center mr-3">
                                            <i class="fas fa-exclamation-triangle text-red-600"></i>
                                        </div>
                                        <h3 class="text-lg font-bold text-gray-800">Delete Leave Application</h3>
                                    </div>
                                    <p class="text-gray-600 mb-6">Are you sure you want to delete this leave application? This action cannot be undone.</p>
                                    <div class="bg-red-50 border border-red-100 rounded-lg p-4 mb-6">
                                        <h4 class="font-medium text-gray-800 mb-2">Application Details:</h4>
                                        <div class="space-y-1 text-sm">
                                            <p><span class="text-gray-500">Employee:</span> <span class="font-medium">${leave.employee_name}</span></p>
                                            <p><span class="text-gray-500">Dates:</span> <span class="font-medium">${formatDate(leave.from_date)} - ${formatDate(leave.to_date)}</span></p>
                                            <p><span class="text-gray-500">Type:</span> <span class="leave-badge ${getLeaveTypeClass(leave.leave_type)}">${getLeaveTypeName(leave.leave_type)}</span></p>
                                            <p><span class="text-gray-500">Status:</span> <span class="${getStatusClass(leave.status)} px-2 py-1 rounded-full text-xs">${leave.status.charAt(0).toUpperCase() + leave.status.slice(1)}</span></p>
                                        </div>
                                    </div>
                                    <div class="flex flex-col sm:flex-row gap-3">
                                        <button id="cancelDeleteBtn" class="flex-1 py-3 px-4 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 font-medium">Cancel</button>
                                        <button id="confirmDeleteBtn" class="flex-1 py-3 px-4 bg-red-600 text-white rounded-lg hover:bg-red-700 font-medium flex items-center justify-center"><i class="fas fa-trash mr-2"></i> Delete Permanently</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    `;
                    document.body.insertAdjacentHTML('beforeend', popupHTML);
                    const deletePopup = document.getElementById('deletePopup');
                    setTimeout(() => { deletePopup.style.opacity = '1'; deletePopup.style.transform = 'scale(1)'; }, 10);
                    document.getElementById('cancelDeleteBtn').addEventListener('click', closeDeletePopup);
                    document.getElementById('confirmDeleteBtn').addEventListener('click', () => performDelete(leaveId));
                    deletePopup.addEventListener('click', (e) => { if (e.target === deletePopup) closeDeletePopup(); });
                    document.addEventListener('keydown', function handleEscape(e) {
                        if (e.key === 'Escape') {
                            closeDeletePopup();
                            document.removeEventListener('keydown', handleEscape);
                        }
                    });
                } else {
                    throw new Error(data.message || 'Failed to load leave details');
                }
            })
            .catch(error => {
                console.error('Error loading leave details:', error);
                showNotification('error', 'Error', 'Failed to load leave details.');
            });
        }
        function closeDeletePopup() {
            const deletePopup = document.getElementById('deletePopup');
            if (deletePopup) {
                deletePopup.style.opacity = '0';
                deletePopup.style.transform = 'scale(0.95)';
                setTimeout(() => { deletePopup.remove(); }, 300);
            }
        }
        function performDelete(leaveId) {
            const deleteBtn = document.getElementById('confirmDeleteBtn');
            const originalText = deleteBtn.innerHTML;
            deleteBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i> Deleting...';
            deleteBtn.disabled = true;
            fetch(`{{ route('employeeportal.leave.destroy', '') }}/${leaveId}`, {
                method: 'DELETE',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    closeDeletePopup();
                    loadLeaves();
                    showNotification('success', 'Deleted Successfully', 'Leave application deleted successfully!');
                } else {
                    throw new Error(data.message || 'Failed to delete leave');
                }
            })
            .catch(error => {
                console.error('Error deleting leave:', error);
                showNotification('error', 'Delete Failed', error.message);
            })
            .finally(() => {
                deleteBtn.innerHTML = originalText;
                deleteBtn.disabled = false;
            });
        }
        function filterLeaves() {
            loadLeaves();
            updateMobileFilterSummary();
        }
        function exportData(format) {
            const type = filterType ? filterType.value : '';
            const status = filterStatus ? filterStatus.value : '';
            let url = `/employeeportal/export/${format}`;
            const params = new URLSearchParams();
            if (type) params.append('leave_type', type);
            if (status) params.append('status', status);
            if (params.toString()) url += '?' + params.toString();
            window.open(url, '_blank');
            showNotification('success', 'Export Started', `Your ${format.toUpperCase()} file download has started.`);
        }
        function getStatusClass(status) {
            if (status === 'approved') return 'status-approved';
            if (status === 'rejected') return 'status-rejected';
            return 'status-pending';
        }
        function getLeaveTypeClass(type) {
            const classes = { casual: "leave-casual", sick: "leave-sick", paid: "leave-paid", emergency: "leave-emergency", halfday: "leave-halfday", wfh: "leave-wfh" };
            return classes[type] || "";
        }
        function getLeaveTypeName(type) {
            const types = { casual: "Casual Leave", sick: "Sick Leave", paid: "Paid Leave", emergency: "Emergency Leave", halfday: "Half Day", wfh: "Work From Home" };
            return types[type] || type.charAt(0).toUpperCase() + type.slice(1);
        }
        function getInitials(name) {
            if (!name) return 'NA';
            return name.split(' ').map(part => part[0]).join('').toUpperCase().substring(0, 2);
        }
        function formatDate(dateString) {
            try {
                const date = new Date(dateString);
                return date.toLocaleDateString('en-US', { month: 'short', day: 'numeric', year: 'numeric' });
            } catch (error) {
                return dateString;
            }
        }
        function generateTimelineHTML(timeline) {
            if (!timeline || !Array.isArray(timeline)) return '';
            return timeline.map(item => `
                <div class="timeline-item">
                    <div class="flex items-center mb-1">
                        <i class="${item.icon || 'fas fa-clock'} text-blue-500 mr-2"></i>
                        <span class="font-medium text-gray-800">${item.action || 'Update'}</span>
                    </div>
                    <p class="text-gray-500 text-sm pl-6">${formatDateTime(item.date || new Date())}</p>
                </div>
            `).join('');
        }
        function formatDateTime(dateTimeString) {
            try {
                const date = new Date(dateTimeString);
                return date.toLocaleString('en-US', { month: 'short', day: 'numeric', year: 'numeric', hour: '2-digit', minute: '2-digit' });
            } catch (error) {
                return dateTimeString;
            }
        }
        function showNotification(type, title, message) {
            const toastIcon = document.getElementById('toastIcon');
            const toastTitle = document.getElementById('toastTitle');
            const toastMessage = document.getElementById('toastMessage');
            if (type === 'success') toastIcon.innerHTML = '<i class="fas fa-check-circle text-green-500 text-xl"></i>';
            else if (type === 'error') toastIcon.innerHTML = '<i class="fas fa-exclamation-circle text-red-500 text-xl"></i>';
            else if (type === 'warning') toastIcon.innerHTML = '<i class="fas fa-exclamation-triangle text-yellow-500 text-xl"></i>';
            else toastIcon.innerHTML = '<i class="fas fa-info-circle text-blue-500 text-xl"></i>';
            toastTitle.textContent = title;
            toastMessage.textContent = message;
            const notificationToast = document.getElementById('notificationToast');
            notificationToast.classList.remove('hidden');
            setTimeout(() => { notificationToast.classList.remove('translate-y-full'); }, 10);
            setTimeout(() => { hideNotificationToast(); }, 5000);
        }
        function hideNotificationToast() {
            const notificationToast = document.getElementById('notificationToast');
            notificationToast.classList.add('translate-y-full');
            setTimeout(() => { notificationToast.classList.add('hidden'); }, 300);
        }
        function handleResize() {
            loadLeaves();
            updateMobileFilterSummary();
            if (window.innerWidth >= 768) {
                closeMobileMenuOverlay();
                closeMobileFilterModal();
            }
        }
        function setupModalScrollPrevention() {
            const modals = [mobileMenuOverlay, mobileFilterModal];
            modals.forEach(modal => {
                if (modal) {
                    modal.addEventListener('scroll', function(e) { e.stopPropagation(); });
                }
            });
        }
        document.addEventListener('DOMContentLoaded', initApp);
    </script>
</body>
</html>
@endsection