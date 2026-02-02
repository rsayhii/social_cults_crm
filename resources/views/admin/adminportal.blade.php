@extends('components.layout')

@section('content')

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=5.0">
    <title>Admin Portal | Leave Management</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap');
        
        * {
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Inter', sans-serif;
            overflow-x: hidden;
        }
        
        .leave-card {
            transition: all 0.3s ease;
            height: 100%;
            display: flex;
            flex-direction: column;
        }
        
        .leave-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1);
        }
        
        .modal-overlay {
            background-color: rgba(0, 0, 0, 0.5);
            backdrop-filter: blur(4px);
        }
        
        .notification {
            transition: all 0.3s ease;
        }
        
        .status-pending { background-color: #fef3c7; color: #d97706; }
        .status-approved { background-color: #d1fae5; color: #059669; }
        .status-rejected { background-color: #fee2e2; color: #dc2626; }
        
        .leave-badge { 
            padding: 0.25rem 0.75rem; 
            border-radius: 9999px; 
            font-size: 0.75rem; 
            font-weight: 500;
            display: inline-block;
            white-space: nowrap;
        }
        
        .leave-casual { background-color: #fef3c7; color: #d97706; }
        .leave-sick { background-color: #fee2e2; color: #dc2626; }
        .leave-paid { background-color: #d1fae5; color: #059669; }
        .leave-emergency { background-color: #fce7f3; color: #be185d; }
        .leave-halfday { background-color: #e0e7ff; color: #3730a3; }
        .leave-wfh { background-color: #f5f3ff; color: #7c3aed; }
        
        .email-editor { 
            min-height: 150px; 
            border: 1px solid #e5e7eb; 
            border-radius: 0.5rem; 
            padding: 1rem;
            overflow-wrap: break-word;
            word-wrap: break-word;
        }
        
        .email-editor:focus { 
            outline: none; 
            border-color: #3b82f6; 
        }
        
        .timeline-item { 
            position: relative; 
            padding-left: 2rem; 
            margin-bottom: 1.5rem; 
            word-wrap: break-word;
            overflow-wrap: break-word;
        }
        
        .timeline-item:before { 
            content: ''; 
            position: absolute; 
            left: 0; 
            top: 0.25rem; 
            width: 12px; 
            height: 12px; 
            border-radius: 50%; 
            background: #3b82f6; 
        }
        
        .timeline-item:after { 
            content: ''; 
            position: absolute; 
            left: 5px; 
            top: 1.25rem; 
            width: 2px; 
            height: calc(100% + 1rem); 
            background: #e5e7eb; 
        }
        
        .timeline-item:last-child:after { 
            display: none; 
        }
        
        .sticky-header { 
            position: sticky; 
            top: 0; 
            z-index: 10; 
            background-color: white; 
            box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.05);
        }
        
        .smooth-transition { 
            transition: all 0.3s ease; 
        }
        
        .page-container { 
            display: none; 
            width: 100%;
            overflow-x: hidden;
        }
        
        .page-active { 
            display: block; 
        }
        
        .avatar-initials {
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
            color: white;
        }
        
        /* Mobile optimizations */
        @media (max-width: 640px) {
            .mobile-hidden {
                display: none !important;
            }
            
            .mobile-flex-col {
                flex-direction: column;
            }
            
            .mobile-w-full {
                width: 100% !important;
            }
            
            .mobile-text-center {
                text-align: center;
            }
            
            .mobile-p-4 {
                padding: 1rem;
            }
            
            .mobile-text-sm {
                font-size: 0.875rem;
            }
            
            .mobile-text-xs {
                font-size: 0.75rem;
            }
            
            .timeline-item {
                padding-left: 1.5rem;
            }
            
            .timeline-item:before {
                width: 10px;
                height: 10px;
                top: 0.35rem;
            }
            
            .timeline-item:after {
                left: 4px;
            }
        }
        
        /* Tablet optimizations */
        @media (min-width: 641px) and (max-width: 1024px) {
            .tablet-w-full {
                width: 100%;
            }
            
            .tablet-flex-col {
                flex-direction: column;
            }
            
            .tablet-text-center {
                text-align: center;
            }
        }
        
        /* Prevent text overflow */
        .truncate-2-lines {
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }
        
        .truncate-1-line {
            display: -webkit-box;
            -webkit-line-clamp: 1;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }
        
        /* Better touch targets for mobile */
        @media (max-width: 768px) {
            button, 
            a,
            input[type="submit"],
            .clickable {
                min-height: 44px;
                min-width: 44px;
            }
            
            input,
            select,
            textarea {
                font-size: 16px !important; /* Prevents iOS zoom on focus */
            }
        }
        
        /* Hide scrollbar for better mobile experience */
        .hide-scrollbar {
            -ms-overflow-style: none;
            scrollbar-width: none;
        }
        
        .hide-scrollbar::-webkit-scrollbar {
            display: none;
        }

        /* Popup Notification Styles */
.popup-notification {
    position: fixed;
    top: 20px;
    right: 20px;
    z-index: 9999;
    min-width: 300px;
    max-width: 400px;
    border-radius: 8px;
    padding: 16px;
    display: flex;
    align-items: flex-start;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
    transform: translateX(150%);
    transition: transform 0.3s ease;
    backdrop-filter: blur(10px);
}

.popup-notification.show {
    transform: translateX(0);
}

.popup-notification.hide {
    transform: translateX(150%);
}

.popup-notification.success {
    background: linear-gradient(135deg, #10b981 0%, #059669 100%);
    border-left: 4px solid #047857;
}

.popup-notification.error {
    background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
    border-left: 4px solid #b91c1c;
}

.popup-notification.warning {
    background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
    border-left: 4px solid #b45309;
}

.popup-notification.info {
    background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);
    border-left: 4px solid #1d4ed8;
}

.popup-notification-icon {
    margin-right: 12px;
    margin-top: 2px;
    flex-shrink: 0;
}

.popup-notification-content {
    flex: 1;
}

.popup-notification-title {
    font-weight: 600;
    font-size: 16px;
    color: white;
    margin-bottom: 4px;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.popup-notification-message {
    font-size: 14px;
    color: rgba(255, 255, 255, 0.9);
    line-height: 1.4;
}

.popup-notification-close {
    background: none;
    border: none;
    color: rgba(255, 255, 255, 0.8);
    cursor: pointer;
    font-size: 14px;
    margin-left: 8px;
    padding: 2px 6px;
    border-radius: 4px;
    transition: background-color 0.2s;
}

.popup-notification-close:hover {
    background-color: rgba(255, 255, 255, 0.1);
}

.popup-notification-progress {
    position: absolute;
    bottom: 0;
    left: 0;
    right: 0;
    height: 3px;
    background: rgba(255, 255, 255, 0.3);
    border-radius: 0 0 8px 8px;
    overflow: hidden;
}

/* Dynamic animation duration for progress bar */
.popup-notification-progress-bar {
    height: 100%;
    background: white;
    width: 100%;
    transform: scaleX(1);
    transform-origin: left;
    animation: progress linear forwards;
}

@keyframes progress {
    from {
        transform: scaleX(1);
    }
    to {
        transform: scaleX(0);
    }
}
/* Mobile responsiveness for popups */
@media (max-width: 640px) {
    .popup-notification {
        left: 20px;
        right: 20px;
        max-width: none;
        transform: translateY(-150%);
        top: 20px;
    }
    
    .popup-notification.show {
        transform: translateY(0);
    }
    
    .popup-notification.hide {
        transform: translateY(-150%);
    }
}


/* Confirmation Popup Styles */
.popup-notification[style*="position: fixed"] {
    backdrop-filter: blur(5px);
    background: rgba(255, 255, 255, 0.95);
    border: 1px solid #e5e7eb;
    box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
}

.popup-notification[style*="position: fixed"] .popup-notification-message {
    color: #374151;
    font-size: 15px;
    line-height: 1.5;
}

.popup-notification[style*="position: fixed"] .popup-notification-title {
    color: #1f2937;
}

/* Ensure confirmation popups are on top */
#delete-confirmation-popup,
#approve-confirmation-popup {
    z-index: 10000 !important;
}

/* Backdrop overlay for confirmation popups */
.popup-backdrop {
    position: fixed;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: rgba(0, 0, 0, 0.5);
    backdrop-filter: blur(2px);
    z-index: 9999;
}
    </style>
</head>
<body class="bg-gray-50">
    <!-- Header Section - Responsive -->
    <header class="bg-white shadow-sm border-b border-gray-200 sticky-header hidden">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between px-4 sm:px-6 py-3 sm:py-4">
            <!-- Mobile menu button and logo -->
            <div class="flex items-center justify-between w-full sm:w-auto mb-3 sm:mb-0">
                <div class="flex items-center space-x-3">
                    <button id="mobileMenuBtn" class="sm:hidden p-2 text-gray-500 hover:text-gray-700 rounded-lg hover:bg-gray-100">
                       
                    </button>
                    
                    <div class="text-sm text-gray-500 truncate">
                        {{-- <span class="text-gray-400 mobile-hidden">/</span> 
                        <span class="mobile-hidden sm:inline">Admin</span>
                        <span class="text-gray-400 mobile-hidden">/</span>  --}}
                        <span class="text-indigo-600 font-medium" id="currentPage"></span>
                    </div>
                </div>
                
                <!-- Mobile notification and profile -->
                <div class="flex items-center space-x-2 sm:hidden">
                    <div class="relative">
                        <button class="p-2 text-gray-500 hover:text-gray-700 rounded-full hover:bg-gray-100">
                            <i class="fas fa-bell text-lg"></i>
                        </button>
                        <span class="absolute top-0 right-0 h-2 w-2 bg-red-500 rounded-full"></span>
                    </div>
                    <div class="h-8 w-8 bg-green-100 rounded-full flex items-center justify-center">
                        <span class="text-green-600 font-medium text-sm">AD</span>
                    </div>
                </div>
            </div>

            <!-- Popup Notification Container -->
<div id="popupNotificationContainer"></div>
            
            <!-- Search bar (hidden on mobile, shown on tablet/desktop) -->
            <div class="w-full sm:w-auto mb-3 sm:mb-0">
                <div class="relative mobile-hidden sm:block">
                    <input type="text" placeholder="Search leaves..." class="pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-200 focus:border-indigo-500 w-full sm:w-64">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <i class="fas fa-search text-gray-400"></i>
                    </div>
                </div>
                
                <!-- Mobile search button -->
                <button id="mobileSearchBtn" class="sm:hidden w-full flex items-center justify-center p-2 border border-gray-300 rounded-lg text-gray-500 hover:text-gray-700">
                    <i class="fas fa-search mr-2"></i>
                    <span>Search leaves...</span>
                </button>
            </div>
            
            <!-- Desktop notification and profile -->
            <div class="flex items-center space-x-4 mobile-hidden sm:flex">
                <div class="relative">
                    <button class="p-2 text-gray-500 hover:text-gray-700 rounded-full hover:bg-gray-100">
                        <i class="fas fa-bell text-lg"></i>
                    </button>
                    <span class="absolute top-0 right-0 h-2 w-2 bg-red-500 rounded-full"></span>
                </div>
                <button class="p-2 text-gray-500 hover:text-gray-700 rounded-full hover:bg-gray-100">
                    <i class="fas fa-cog text-lg"></i>
                </button>
                <div class="relative group">
                    <button class="flex items-center space-x-2 text-gray-700 hover:text-indigo-600">
                        <div class="h-8 w-8 bg-green-100 rounded-full flex items-center justify-center">
                            <span class="text-green-600 font-medium">AD</span>
                        </div>
                        <span class="font-medium mobile-hidden md:inline">Administrator</span>
                        <i class="fas fa-chevron-down text-xs mobile-hidden md:inline"></i>
                    </button>
                </div>
            </div>
        </div>
        
        <!-- Mobile search overlay -->
        <div id="mobileSearchOverlay" class="fixed inset-0 bg-white z-50 p-4 hidden">
            <div class="flex items-center mb-4">
                <button id="closeMobileSearch" class="p-2 mr-3 text-gray-500 hover:text-gray-700">
                    <i class="fas fa-arrow-left"></i>
                </button>
                <div class="relative flex-1">
                    <input type="text" placeholder="Search leaves by name, position, or email..." class="w-full pl-10 pr-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-200 focus:border-indigo-500" id="mobileSearchInput">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <i class="fas fa-search text-gray-400"></i>
                    </div>
                </div>
            </div>
            <div class="text-sm text-gray-500 p-3 border-t border-gray-200">
                Search by: Employee name, Position, Email, or Leave type
            </div>
        </div>
        
        <!-- Mobile menu overlay -->
        <div id="mobileMenuOverlay" class="fixed inset-0 bg-white z-50 p-4 hidden">
            <div class="flex justify-between items-center mb-8">
                <div class="h-10 w-10 bg-indigo-100 rounded-lg flex items-center justify-center">
                    <span class="text-indigo-600 font-bold">LM</span>
                </div>
                <button id="closeMobileMenu" class="p-2 text-gray-500 hover:text-gray-700">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>
            
            <div class="space-y-1 mb-8">
                <div class="flex items-center p-3 bg-indigo-50 rounded-lg">
                    <div class="h-10 w-10 bg-green-100 rounded-full flex items-center justify-center mr-3">
                        <span class="text-green-600 font-medium">AD</span>
                    </div>
                    <div>
                        <h4 class="font-medium text-gray-800">Administrator</h4>
                        <p class="text-sm text-gray-500">Admin Account</p>
                    </div>
                </div>
            </div>
            
            <nav class="space-y-1">
                <a href="#" class="flex items-center p-3 text-gray-700 hover:bg-gray-100 rounded-lg">
                    <i class="fas fa-tachometer-alt mr-3 text-gray-400 w-5"></i>
                    <span>Dashboard</span>
                </a>
                <a href="#" class="flex items-center p-3 text-gray-700 hover:bg-gray-100 rounded-lg">
                    <i class="fas fa-users mr-3 text-gray-400 w-5"></i>
                    <span>Employees</span>
                </a>
                <a href="#" class="flex items-center p-3 text-indigo-600 bg-indigo-50 rounded-lg">
                    <i class="fas fa-calendar-alt mr-3 text-indigo-500 w-5"></i>
                    <span>Leave Management</span>
                </a>
                <a href="#" class="flex items-center p-3 text-gray-700 hover:bg-gray-100 rounded-lg">
                    <i class="fas fa-chart-bar mr-3 text-gray-400 w-5"></i>
                    <span>Reports</span>
                </a>
                <a href="#" class="flex items-center p-3 text-gray-700 hover:bg-gray-100 rounded-lg">
                    <i class="fas fa-cog mr-3 text-gray-400 w-5"></i>
                    <span>Settings</span>
                </a>
            </nav>
            
            <div class="absolute bottom-0 left-0 right-0 p-4 border-t border-gray-200">
                <button class="flex items-center justify-center w-full p-3 text-gray-700 hover:bg-gray-100 rounded-lg">
                    <i class="fas fa-sign-out-alt mr-3"></i>
                    <span>Logout</span>
                </button>
            </div>
        </div>
    </header>

    <!-- Dashboard Page -->
    <div id="dashboardPage" class="page-container page-active">
        <!-- Main Content -->
        <div class="container mx-auto px-3 sm:px-4 py-4 sm:py-6">
            <!-- Page Title and Actions -->
            <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-6">
                <div class="mb-4 md:mb-0">
                    <h1 class="text-xl sm:text-2xl font-bold text-gray-800">Admin Leave Portal</h1>
                    {{-- <div class="flex items-center mt-1 text-sm text-gray-500">
                        <span class="text-gray-400 mobile-hidden">/</span>
                        <span class="mx-1 mobile-hidden">Admin</span>
                        <span class="text-gray-400 mobile-hidden">/</span>
                        <span class="mx-1 text-indigo-600 font-medium">Leave Management</span>
                    </div> --}}
                </div>
                <div class="flex items-center space-x-3">
                    <!-- Mobile filter buttons -->
                    <div class="flex sm:hidden space-x-2">
                        <button id="mobileFilterBtn" class="p-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50">
                            <i class="fas fa-filter"></i>
                        </button>
                        <button id="mobileExportBtn" class="p-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 hidden">
                            <i class="fas fa-download"></i>
                        </button>
                    </div>
                    
                    <!-- Desktop export dropdown -->
                    <div class="relative group mobile-hidden sm:block">
                        <button id="exportBtn" class="hidden flex items-center space-x-2 bg-white border border-gray-300 text-gray-700 py-2 px-4 rounded-lg hover:bg-gray-50 transition-all duration-300">
                            <i class="fas fa-download"></i>
                            <span class="mobile-hidden md:inline">Export</span>
                            <i class="fas fa-chevron-down text-xs"></i>
                        </button>
                        <div id="exportMenu" class="absolute right-0 mt-2 w-40 bg-white rounded-lg shadow-lg py-2 z-10 hidden">
                            <a href="{{ route('adminportal.export', ['format' => 'csv']) }}"
                               class="w-full block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 flex items-center">
                                <i class="fas fa-file-csv mr-2 text-green-500"></i> CSV
                            </a>
                            <a href="{{ route('adminportal.export', ['format' => 'excel']) }}"
                               class="w-full block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 flex items-center">
                                <i class="fas fa-file-excel mr-2 text-green-600"></i> Excel
                            </a>
                        </div>
                    </div>
                    
                    <button id="refreshBtn" class="bg-indigo-600 text-white py-2 px-4 rounded-lg font-medium hover:bg-indigo-700 transition-all duration-300 flex items-center">
                        <i class="fas fa-sync-alt mr-2"></i>
                        <span class="mobile-hidden md:inline">Refresh</span>
                    </button>
                </div>
            </div>

            <!-- Mobile Filter Modal -->
            <div id="mobileFilterModal" class="fixed inset-0 bg-white z-50 p-4 hidden">
                <div class="flex justify-between items-center mb-6">
                    <h2 class="text-xl font-bold text-gray-800">Filters</h2>
                    <button id="closeMobileFilter" class="p-2 text-gray-500 hover:text-gray-700">
                        <i class="fas fa-times text-xl"></i>
                    </button>
                </div>
                
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                        <select id="mobileFilterStatus" class="w-full border border-gray-300 rounded-lg py-3 px-4 text-base">
                            <option value="">All Status</option>
                            <option value="pending">Pending</option>
                            <option value="approved">Approved</option>
                            <option value="rejected">Rejected</option>
                        </select>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Leave Type</label>
                        <select id="mobileFilterType" class="w-full border border-gray-300 rounded-lg py-3 px-4 text-base">
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
                        <label class="block text-sm font-medium text-gray-700 mb-1">Search</label>
                        <input type="text" id="mobileFilterSearch" placeholder="Search..." class="w-full border border-gray-300 rounded-lg py-3 px-4 text-base">
                    </div>
                </div>
                
                <div class="absolute bottom-0 left-0 right-0 p-4 border-t border-gray-200 bg-white">
                    <div class="flex space-x-3">
                        <button id="resetMobileFilters" class="flex-1 py-3 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50">
                            Reset
                        </button>
                        <button id="applyMobileFilters" class="flex-1 py-3 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700">
                            Apply Filters
                        </button>
                    </div>
                </div>
            </div>

            <!-- Admin Stats - Responsive Grid -->
            <div class="grid grid-cols-2 sm:grid-cols-2 md:grid-cols-4 gap-3 sm:gap-4 md:gap-6 mb-6">
                <div class="bg-white rounded-xl shadow-sm p-4 sm:p-6">
                    <div class="flex items-center">
                        <div class="bg-blue-100 text-blue-600 p-2 sm:p-3 rounded-lg mr-3 sm:mr-4">
                            <i class="fas fa-clock text-lg sm:text-xl"></i>
                        </div>
                        <div>
                            <h3 class="text-xl sm:text-2xl font-bold text-gray-800" id="pendingCount">0</h3>
                            <p class="text-gray-600 text-sm sm:text-base">Pending</p>
                        </div>
                    </div>
                </div>
                <div class="bg-white rounded-xl shadow-sm p-4 sm:p-6">
                    <div class="flex items-center">
                        <div class="bg-green-100 text-green-600 p-2 sm:p-3 rounded-lg mr-3 sm:mr-4">
                            <i class="fas fa-check-circle text-lg sm:text-xl"></i>
                        </div>
                        <div>
                            <h3 class="text-xl sm:text-2xl font-bold text-gray-800" id="approvedCount">0</h3>
                            <p class="text-gray-600 text-sm sm:text-base">Approved</p>
                        </div>
                    </div>
                </div>
                <div class="bg-white rounded-xl shadow-sm p-4 sm:p-6">
                    <div class="flex items-center">
                        <div class="bg-red-100 text-red-600 p-2 sm:p-3 rounded-lg mr-3 sm:mr-4">
                            <i class="fas fa-times-circle text-lg sm:text-xl"></i>
                        </div>
                        <div>
                            <h3 class="text-xl sm:text-2xl font-bold text-gray-800" id="rejectedCount">0</h3>
                            <p class="text-gray-600 text-sm sm:text-base">Rejected</p>
                        </div>
                    </div>
                </div>
                <div class="bg-white rounded-xl shadow-sm p-4 sm:p-6">
                    <div class="flex items-center">
                        <div class="bg-purple-100 text-purple-600 p-2 sm:p-3 rounded-lg mr-3 sm:mr-4">
                            <i class="fas fa-users text-lg sm:text-xl"></i>
                        </div>
                        <div>
                            <h3 class="text-xl sm:text-2xl font-bold text-gray-800" id="totalEmployees">0</h3>
                            <p class="text-gray-600 text-sm sm:text-base">Employees</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Desktop Filters -->
            <div class="mobile-hidden md:flex md:items-center md:justify-between mb-6">
                <div class="text-sm text-gray-500">
                    Showing <span id="leavesCount" class="font-medium text-gray-700">0</span> leave applications
                </div>
                <div>
                    <div class="flex items-center space-x-4">
                        <div class="flex items-center space-x-2">
                            <span class="text-sm text-gray-500">Search:</span>
                            <input type="text" id="searchLeave" placeholder="Search by name, position, or email..." class="border border-gray-300 rounded-lg px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 w-48 lg:w-64">
                        </div>
                        <div class="flex items-center space-x-2">
                            <span class="text-sm text-gray-500">Status:</span>
                            <select id="filterStatus" class="border border-gray-300 rounded-lg py-2 px-3 text-sm focus:ring-2 focus:ring-indigo-200 focus:border-indigo-500">
                                <option value="">All Status</option>
                                <option value="pending">Pending</option>
                                <option value="approved">Approved</option>
                                <option value="rejected">Rejected</option>
                            </select>
                        </div>
                        <div class="flex items-center space-x-2">
                            <span class="text-sm text-gray-500">Type:</span>
                            <select id="filterType" class="border border-gray-300 rounded-lg py-2 px-3 text-sm focus:ring-2 focus:ring-indigo-200 focus:border-indigo-500">
                                <option value="">All Types</option>
                                <option value="casual">Casual</option>
                                <option value="sick">Sick</option>
                                <option value="paid">Paid</option>
                                <option value="emergency">Emergency</option>
                                <option value="halfday">Half Day</option>
                                <option value="wfh">WFH</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Mobile Filters Summary -->
            <div class="md:hidden mb-4">
                <div class="flex items-center justify-between">
                    <div class="text-sm text-gray-500">
                        <span id="mobileLeavesCount" class="font-medium text-gray-700">0</span> applications
                    </div>
                    <div class="text-sm text-gray-500" id="mobileFilterSummary">
                        No filters applied
                    </div>
                </div>
            </div>

            <!-- Leaves Grid - Responsive -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 sm:gap-6" id="leavesGrid">
                <!-- Loading State -->
                <div class="col-span-full text-center py-12">
                    <div class="animate-pulse">
                        <i class="fas fa-calendar-check text-gray-300 text-4xl sm:text-5xl mb-4"></i>
                        <h3 class="text-base sm:text-lg font-medium text-gray-700 mb-2">Loading leave applications...</h3>
                        <p class="text-gray-500 text-sm sm:text-base">Please wait while we load leave records</p>
                    </div>
                </div>
            </div>
            
            <!-- Mobile Export Options -->
            <div id="mobileExportOptions" class="fixed bottom-0 left-0 right-0 bg-white border-t border-gray-200 p-4 hidden z-40">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="font-medium text-gray-800">Export Format</h3>
                    <button id="closeMobileExport" class="p-2 text-gray-500">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
                <div class="grid grid-cols-2 gap-3">
                    <a href="{{ route('adminportal.export', ['format' => 'csv']) }}"
                       class="flex items-center justify-center p-3 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50">
                        <i class="fas fa-file-csv mr-2 text-green-500"></i> CSV
                    </a>
                    <a href="{{ route('adminportal.export', ['format' => 'excel']) }}"
                       class="flex items-center justify-center p-3 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50">
                        <i class="fas fa-file-excel mr-2 text-green-600"></i> Excel
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Leave Details Page -->
    <div id="leaveDetailsPage" class="page-container">
        <div class="container mx-auto px-3 sm:px-4 py-4 sm:py-6">
            <!-- Back button and title for mobile -->
            <div class="md:hidden mb-4">
                <button id="backToDashboardMobile" class="flex items-center text-gray-600 hover:text-gray-800 mb-3">
                    <i class="fas fa-arrow-left mr-2"></i>
                    <span>Back</span>
                </button>
            </div>
            
            <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-6">
                <div class="mb-4 md:mb-0">
                    <h1 class="text-xl sm:text-2xl font-bold text-gray-800">Leave Request Details</h1>
                    <div class="flex items-center mt-1 text-sm text-gray-500">
                        <span class="text-gray-400 mobile-hidden">/</span>
                        <span class="mx-1 mobile-hidden">Admin</span>
                        <span class="text-gray-400 mobile-hidden">/</span>
                        <span class="mx-1 mobile-hidden">Leave Management</span>
                        <span class="text-gray-400 mobile-hidden">/</span>
                        <span class="mx-1 text-indigo-600 font-medium">Details</span>
                    </div>
                </div>
                <button id="backToDashboard" class="mobile-hidden md:flex items-center space-x-2 text-gray-600 hover:text-gray-800">
                    <i class="fas fa-arrow-left"></i>
                    <span>Back to Dashboard</span>
                </button>
            </div>

            <div class="bg-white rounded-xl shadow-sm p-4 sm:p-6">
                <div id="leaveDetailsContent">
                    <!-- Leave details will be populated by JS -->
                </div>
            </div>
        </div>
    </div>

    <!-- Reject Leave Modal - Responsive -->
    <div id="rejectLeaveModal" class="fixed inset-0 z-50 flex items-center justify-center hidden">
        <div class="modal-overlay absolute inset-0 bg-black opacity-30"></div>
        <div class="bg-white rounded-xl shadow-lg w-full max-w-md mx-4 z-10 max-h-[90vh] overflow-y-auto">
            <div class="p-4 sm:p-6 border-b border-gray-200">
                <h2 class="text-lg sm:text-xl font-bold text-gray-800">Reject Leave Request</h2>
            </div>
            <div class="p-4 sm:p-6">
                <form id="rejectLeaveForm">
                    @csrf
                    <input type="hidden" id="rejectLeaveId" name="id">
                    
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Rejection Reason *</label>
                        <textarea id="rejectionReason" name="admin_remarks" rows="4" class="w-full px-4 py-3 text-base border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-200 focus:border-indigo-500" placeholder="Please provide a reason for rejecting this leave request..." required></textarea>
                        <div id="rejectionReasonError" class="text-red-500 text-xs mt-1 hidden"></div>
                    </div>
                    
                    <div class="flex flex-col sm:flex-row sm:justify-end space-y-3 sm:space-y-0 sm:space-x-3">
                        <button type="button" id="cancelRejectLeave" class="px-4 py-3 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-all duration-300 order-2 sm:order-1">
                            Cancel
                        </button>
                        <button type="submit" class="px-4 py-3 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-all duration-300 flex items-center justify-center order-1 sm:order-2">
                            <i class="" id="rejectLeaveSpinner"></i>
                            Reject Leave
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

   

    <script>
        // DOM Elements
        const leavesGrid = document.getElementById('leavesGrid');
        const dashboardPage = document.getElementById('dashboardPage');
        const leaveDetailsPage = document.getElementById('leaveDetailsPage');
        const rejectLeaveModal = document.getElementById('rejectLeaveModal');
        const backToDashboard = document.getElementById('backToDashboard');
        const backToDashboardMobile = document.getElementById('backToDashboardMobile');
        const cancelRejectLeave = document.getElementById('cancelRejectLeave');
        const rejectLeaveForm = document.getElementById('rejectLeaveForm');
        const filterType = document.getElementById('filterType');
        const filterStatus = document.getElementById('filterStatus');
        const searchLeave = document.getElementById('searchLeave');
        const refreshBtn = document.getElementById('refreshBtn');
        const exportBtn = document.getElementById('exportBtn');
        const exportMenu = document.getElementById('exportMenu');
        const currentPageElement = document.getElementById('currentPage');
        
        // Mobile elements
        const mobileMenuBtn = document.getElementById('mobileMenuBtn');
        const mobileMenuOverlay = document.getElementById('mobileMenuOverlay');
        const closeMobileMenu = document.getElementById('closeMobileMenu');
        const mobileSearchBtn = document.getElementById('mobileSearchBtn');
        const mobileSearchOverlay = document.getElementById('mobileSearchOverlay');
        const closeMobileSearch = document.getElementById('closeMobileSearch');
        const mobileSearchInput = document.getElementById('mobileSearchInput');
        const mobileFilterBtn = document.getElementById('mobileFilterBtn');
        const mobileFilterModal = document.getElementById('mobileFilterModal');
        const closeMobileFilter = document.getElementById('closeMobileFilter');
        const mobileFilterStatus = document.getElementById('mobileFilterStatus');
        const mobileFilterType = document.getElementById('mobileFilterType');
        const mobileFilterSearch = document.getElementById('mobileFilterSearch');
        const resetMobileFilters = document.getElementById('resetMobileFilters');
        const applyMobileFilters = document.getElementById('applyMobileFilters');
        const mobileExportBtn = document.getElementById('mobileExportBtn');
        const mobileExportOptions = document.getElementById('mobileExportOptions');
        const closeMobileExport = document.getElementById('closeMobileExport');
        
        // Statistics elements
        const pendingCountElement = document.getElementById('pendingCount');
        const approvedCountElement = document.getElementById('approvedCount');
        const rejectedCountElement = document.getElementById('rejectedCount');
        const totalEmployeesElement = document.getElementById('totalEmployees');
        const leavesCountElement = document.getElementById('leavesCount');
        const mobileLeavesCount = document.getElementById('mobileLeavesCount');
        const mobileFilterSummary = document.getElementById('mobileFilterSummary');

        // Initialize the application
        function initApp() {
            loadLeaves();
            
            // Set up event listeners
            refreshBtn.addEventListener('click', loadLeaves);
            backToDashboard.addEventListener('click', showDashboardPage);
            if (backToDashboardMobile) {
                backToDashboardMobile.addEventListener('click', showDashboardPage);
            }
            cancelRejectLeave.addEventListener('click', closeRejectLeaveModal);
            rejectLeaveForm.addEventListener('submit', rejectLeave);
            
            // Desktop filter listeners
            if (filterType) filterType.addEventListener('change', filterLeaves);
            if (filterStatus) filterStatus.addEventListener('change', filterLeaves);
            if (searchLeave) searchLeave.addEventListener('input', filterLeaves);
            
            // Export button functionality
            if (exportBtn) {
                exportBtn.addEventListener('click', (e) => {
                    e.stopPropagation();
                    exportMenu.classList.toggle('hidden');
                });
            }

            // Mobile menu functionality
            mobileMenuBtn.addEventListener('click', openMobileMenu);
            closeMobileMenu.addEventListener('click', closeMobileMenuOverlay);
            
            // Mobile search functionality
            mobileSearchBtn.addEventListener('click', openMobileSearch);
            closeMobileSearch.addEventListener('click', closeMobileSearchOverlay);
            mobileSearchInput.addEventListener('input', function() {
                // Update desktop search input for consistency
                if (searchLeave) {
                    searchLeave.value = this.value;
                }
                filterLeaves();
            });
            
            // Mobile filter functionality
            mobileFilterBtn.addEventListener('click', openMobileFilter);
            closeMobileFilter.addEventListener('click', closeMobileFilterModal);
            applyMobileFilters.addEventListener('click', applyMobileFiltersHandler);
            resetMobileFilters.addEventListener('click', resetMobileFiltersHandler);
            
            // Mobile export functionality
            mobileExportBtn.addEventListener('click', openMobileExport);
            closeMobileExport.addEventListener('click', closeMobileExportOptions);
            
            // Close modals when clicking outside
            window.addEventListener('click', function(e) {
                if (e.target === rejectLeaveModal) closeRejectLeaveModal();
                if (exportBtn && !exportBtn.contains(e.target) && exportMenu && !exportMenu.contains(e.target)) {
                    exportMenu.classList.add('hidden');
                }

                // Close confirmation popups when clicking outside
                const deletePopup = document.getElementById('delete-confirmation-popup');
                const approvePopup = document.getElementById('approve-confirmation-popup');
                
                if (deletePopup && !deletePopup.contains(e.target) && !e.target.closest('.delete-leave')) {
                    closeDeleteConfirmation();
                }
                
                if (approvePopup && !approvePopup.contains(e.target) && !e.target.closest('.approve-leave')) {
                    closeApproveConfirmation();
                }
            });
            
            // Close mobile overlays with escape key
            document.addEventListener('keydown', function(e) {
                if (e.key === 'Escape') {
                    closeDeleteConfirmation();
                    closeApproveConfirmation();
                    closeAllNotifications();
                    if (!mobileMenuOverlay.classList.contains('hidden')) closeMobileMenuOverlay();
                    if (!mobileSearchOverlay.classList.contains('hidden')) closeMobileSearchOverlay();
                    if (!mobileFilterModal.classList.contains('hidden')) closeMobileFilterModal();
                    if (!mobileExportOptions.classList.contains('hidden')) closeMobileExportOptions();
                    if (!rejectLeaveModal.classList.contains('hidden')) closeRejectLeaveModal();
                }
            });
            
            // Notification toast
            // document.getElementById('closeToast').addEventListener('click', () => {
            //     hideNotificationToast();
            // });
            
            // Prevent body scroll when modals are open
            setupModalScrollPrevention();
            
            // Initialize mobile filter summary
            updateMobileFilterSummary();
        }

        // Page Navigation Functions
        function showDashboardPage() {
            dashboardPage.classList.add('page-active');
            leaveDetailsPage.classList.remove('page-active');
            currentPageElement.textContent = 'Leave Management';
            loadLeaves();
        }
        
        function showLeaveDetailsPage(leaveId) {
            // Close any open mobile overlays
            closeMobileMenuOverlay();
            closeMobileSearchOverlay();
            closeMobileFilterModal();
            closeMobileExportOptions();
            
            fetch(`{{ route('adminportal.leave.show', '') }}/${leaveId}`, {
                headers: {
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error('Failed to fetch leave: ' + response.status);
                }
                return response.json();
            })
            .then(data => {
                if (data.success) {
                    const leave = data.leave;
                    
                    dashboardPage.classList.remove('page-active');
                    leaveDetailsPage.classList.add('page-active');
                    currentPageElement.textContent = 'Leave Details';
                    
                    const statusClass = getStatusClass(leave.status);
                    const statusText = leave.status.charAt(0).toUpperCase() + leave.status.slice(1);
                    const typeClass = getLeaveTypeClass(leave.leave_type);
                    const typeName = getLeaveTypeName(leave.leave_type);
                    const avatarColor = stringToColor(leave.employee_name);
                    const initials = getInitials(leave.employee_name);
                    
                    // Responsive timeline items
                    const timelineHTML = generateTimelineHTML(leave.timeline);
                    
                    let html = `
                        <div class="flex flex-col sm:flex-row sm:items-start mb-6 sm:mb-8">
                            <div class="flex items-center mb-4 sm:mb-0 sm:mr-6">
                                <div class="w-16 h-16 rounded-full border-2 border-white shadow mr-4 flex items-center justify-center text-white text-xl font-bold flex-shrink-0" style="background-color: ${avatarColor};">
                                    ${initials}
                                </div>
                                <div class="min-w-0">
                                    <h4 class="text-lg font-bold text-gray-800 truncate">${leave.employee_name}</h4>
                                    <p class="text-gray-600 text-sm truncate">${leave.employee_position}</p>
                                    <p class="text-gray-500 text-sm truncate">${leave.employee_email}</p>
                                    <p class="text-gray-500 text-sm">${leave.employee_mobile}</p>
                                </div>
                            </div>
                        </div>
                        
                        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
                            <div>
                                <h4 class="font-semibold text-gray-700 mb-4">Application Information</h4>
                                <div class="space-y-3">
                                    <div class="flex flex-col sm:flex-row sm:justify-between">
                                        <span class="text-gray-600 text-sm sm:text-base">Sent To:</span>
                                        <span class="font-medium text-sm sm:text-base truncate">${leave.sent_to}</span>
                                    </div>
                                    <div class="flex flex-col sm:flex-row sm:justify-between">
                                        <span class="text-gray-600 text-sm sm:text-base">Subject:</span>
                                        <span class="font-medium text-sm sm:text-base truncate">${leave.subject}</span>
                                    </div>
                                    <div class="flex flex-col sm:flex-row sm:justify-between">
                                        <span class="text-gray-600 text-sm sm:text-base">Applied On:</span>
                                        <span class="font-medium text-sm sm:text-base">${formatDate(leave.applied_date)}</span>
                                    </div>
                                </div>
                                
                                <h4 class="font-semibold text-gray-700 mb-4 mt-6">Leave Information</h4>
                                <div class="space-y-3">
                                    <div class="flex flex-col sm:flex-row sm:justify-between">
                                        <span class="text-gray-600 text-sm sm:text-base">Leave Type:</span>
                                        <span class="font-medium text-sm sm:text-base">
                                            <span class="leave-badge ${typeClass}">${typeName}</span>
                                        </span>
                                    </div>
                                    <div class="flex flex-col sm:flex-row sm:justify-between">
                                        <span class="text-gray-600 text-sm sm:text-base">From - To:</span>
                                        <span class="font-medium text-sm sm:text-base">${formatDate(leave.from_date)} - ${formatDate(leave.to_date)}</span>
                                    </div>
                                    <div class="flex flex-col sm:flex-row sm:justify-between">
                                        <span class="text-gray-600 text-sm sm:text-base">Total Days:</span>
                                        <span class="font-medium text-sm sm:text-base">${leave.total_days} day${leave.total_days > 1 ? 's' : ''}</span>
                                    </div>
                                    <div class="flex flex-col sm:flex-row sm:justify-between">
                                        <span class="text-gray-600 text-sm sm:text-base">Status:</span>
                                        <span class="${statusClass} px-3 py-1 rounded-full text-sm font-medium inline-block mt-1 sm:mt-0">${statusText}</span>
                                    </div>
                                </div>
                            </div>
                            <div>
                                <h4 class="font-semibold text-gray-700 mb-4">Leave Reason</h4>
                                <div class="email-editor bg-gray-50 rounded-lg p-4 min-h-[200px] lg:min-h-[300px]">
                                    ${leave.reason}
                                </div>
                            </div>
                        </div>
                        
                        <h4 class="font-semibold text-gray-700 mb-4">Timeline</h4>
                        <div class="pl-4 mb-8">
                            ${timelineHTML}
                        </div>
                    `;
                    
                    // Add admin remarks if available
                    if (leave.admin_remarks) {
                        html += `
                            <div class="mb-6">
                                <h4 class="font-semibold text-gray-700 mb-2">Admin Remarks</h4>
                                <p class="text-gray-800 bg-blue-50 p-4 rounded-lg border border-blue-100 break-words">${leave.admin_remarks}</p>
                            </div>
                        `;
                    }
                    
                    // Add action buttons for pending leaves
                    if (leave.status === 'pending') {
                        html += `
                            <div id="adminActionButtons" class="flex flex-col sm:flex-row flex-wrap gap-3">
                                <button onclick="approveLeave(${leave.id})" class="bg-green-600 hover:bg-green-700 text-white font-medium py-3 px-6 rounded-lg flex items-center justify-center">
                                    <i class="fas fa-check mr-2"></i> Approve Leave
                                </button>
                                <button onclick="openRejectLeaveModal(${leave.id})" class="bg-red-600 hover:bg-red-700 text-white font-medium py-3 px-6 rounded-lg flex items-center justify-center">
                                   Reject Leave
                                </button>
                            </div>
                        `;
                    } else {
                        html += `
                            <div class="flex flex-col sm:flex-row gap-3">
                                <button onclick="approveLeave(${leave.id})" class="bg-green-600 hover:bg-green-700 text-white font-medium py-3 px-6 rounded-lg flex items-center justify-center ${leave.status === 'approved' ? 'opacity-50 cursor-not-allowed' : ''}" ${leave.status === 'approved' ? 'disabled' : ''}>
                                    <i class="fas fa-check mr-2"></i> Approve Again
                                </button>
                                <button onclick="openRejectLeaveModal(${leave.id})" class="bg-red-600 hover:bg-red-700 text-white font-medium py-3 px-6 rounded-lg flex items-center justify-center ${leave.status === 'rejected' ? 'opacity-50 cursor-not-allowed' : ''}" ${leave.status === 'rejected' ? 'disabled' : ''}>
                                    <i class="fas fa-times mr-2"></i> Reject Again
                                </button>
                            </div>
                        `;
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

        // Mobile Functions
        function openMobileMenu() {
            mobileMenuOverlay.classList.remove('hidden');
            document.body.style.overflow = 'hidden';
        }
        
        function closeMobileMenuOverlay() {
            mobileMenuOverlay.classList.add('hidden');
            document.body.style.overflow = 'auto';
        }
        
        function openMobileSearch() {
            mobileSearchOverlay.classList.remove('hidden');
            setTimeout(() => {
                mobileSearchInput.focus();
            }, 100);
            document.body.style.overflow = 'hidden';
        }
        
        function closeMobileSearchOverlay() {
            mobileSearchOverlay.classList.add('hidden');
            document.body.style.overflow = 'auto';
        }
        
        function openMobileFilter() {
            mobileFilterModal.classList.remove('hidden');
            document.body.style.overflow = 'hidden';
        }
        
        function closeMobileFilterModal() {
            mobileFilterModal.classList.add('hidden');
            document.body.style.overflow = 'auto';
        }
        
        function openMobileExport() {
            mobileExportOptions.classList.remove('hidden');
        }
        
        function closeMobileExportOptions() {
            mobileExportOptions.classList.add('hidden');
        }
        
        function applyMobileFiltersHandler() {
            // Update desktop filters to match mobile filters
            if (filterStatus) filterStatus.value = mobileFilterStatus.value;
            if (filterType) filterType.value = mobileFilterType.value;
            if (searchLeave) searchLeave.value = mobileFilterSearch.value;
            
            // Apply filters
            filterLeaves();
            closeMobileFilterModal();
            updateMobileFilterSummary();
        }
        
        function resetMobileFiltersHandler() {
            // Reset mobile filters
            mobileFilterStatus.value = '';
            mobileFilterType.value = '';
            mobileFilterSearch.value = '';
            
            // Reset desktop filters
            if (filterStatus) filterStatus.value = '';
            if (filterType) filterType.value = '';
            if (searchLeave) searchLeave.value = '';
            
            // Apply reset
            filterLeaves();
            updateMobileFilterSummary();
        }
        
        function updateMobileFilterSummary() {
            let summary = 'All';
            let filters = [];
            
            if (filterStatus && filterStatus.value) filters.push(filterStatus.value);
            if (filterType && filterType.value) filters.push(filterType.value);
            if (searchLeave && searchLeave.value) filters.push('Search');
            
            if (filters.length > 0) {
                summary = filters.join(', ');
            }
            
            mobileFilterSummary.textContent = summary;
        }

        // Modal Functions
        function openRejectLeaveModal(leaveId) {
            document.getElementById('rejectLeaveId').value = leaveId;
            document.getElementById('rejectionReason').value = '';
            document.getElementById('rejectionReasonError').classList.add('hidden');
            rejectLeaveModal.classList.remove('hidden');
            document.body.style.overflow = 'hidden';
        }

        function closeRejectLeaveModal() {
            rejectLeaveModal.classList.add('hidden');
            document.body.style.overflow = 'auto';
        }

        // Load leaves from server
        function loadLeaves() {
            showLoadingState();
            
            const type = filterType ? filterType.value : '';
            const status = filterStatus ? filterStatus.value : '';
            const search = searchLeave ? searchLeave.value : '';
            
            let url = '{{ route("adminportal.leaves") }}';
            const params = new URLSearchParams();
            if (type) params.append('leave_type', type);
            if (status) params.append('status', status);
            if (search) params.append('search', search);
            
            if (params.toString()) {
                url += '?' + params.toString();
            }
            
            fetch(url, {
                headers: {
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error('Network response was not ok: ' + response.status);
                }
                return response.json();
            })
            .then(data => {
                if (data.success) {
                    renderLeaves(data.leaves);
                    updateStatistics(data.stats);
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

        // Show loading state
        function showLoadingState() {
            leavesGrid.innerHTML = `
                <div class="col-span-full text-center py-12">
                    <div class="animate-pulse">
                        <i class="fas fa-calendar-check text-gray-300 text-4xl sm:text-5xl mb-4"></i>
                        <h3 class="text-base sm:text-lg font-medium text-gray-700 mb-2">Loading leave applications...</h3>
                        <p class="text-gray-500 text-sm sm:text-base">Please wait while we load leave records</p>
                    </div>
                </div>
            `;
        }

        // Show error state
        function showErrorState(message) {
            leavesGrid.innerHTML = `
                <div class="col-span-full text-center py-12">
                    <i class="fas fa-exclamation-triangle text-red-400 text-4xl sm:text-5xl mb-4"></i>
                    <h3 class="text-base sm:text-lg font-medium text-gray-700 mb-2">Unable to load leaves</h3>
                    <p class="text-gray-500 text-sm sm:text-base mb-4">${message}</p>
                    <button onclick="loadLeaves()" class="bg-indigo-600 text-white py-2 px-4 rounded-lg font-medium hover:bg-indigo-700 transition-all duration-300">
                        <i class="fas fa-redo mr-2"></i> Try Again
                    </button>
                </div>
            `;
        }

        // Update leaves count
        function updateLeavesCount(count) {
            if (leavesCountElement) leavesCountElement.textContent = count;
            if (mobileLeavesCount) mobileLeavesCount.textContent = count;
        }

        // Update statistics
        function updateStatistics(stats) {
            if (pendingCountElement) pendingCountElement.textContent = stats.pending || 0;
            if (approvedCountElement) approvedCountElement.textContent = stats.approved || 0;
            if (rejectedCountElement) rejectedCountElement.textContent = stats.rejected || 0;
            if (totalEmployeesElement) totalEmployeesElement.textContent = stats.total_employees || 0;
        }

        // Render leaves to the grid
        function renderLeaves(leaves) {
            leavesGrid.innerHTML = '';
            
            if (!leaves || leaves.length === 0) {
                leavesGrid.innerHTML = `
                    <div class="col-span-full text-center py-12">
                        <i class="fas fa-calendar-check text-gray-300 text-4xl sm:text-5xl mb-4"></i>
                        <h3 class="text-base sm:text-lg font-medium text-gray-700 mb-2">No leave applications found</h3>
                        <p class="text-gray-500 text-sm sm:text-base mb-4">There are no leave applications matching your criteria.</p>
                        <button onclick="resetMobileFiltersHandler()" class="bg-indigo-600 text-white py-2 px-4 rounded-lg font-medium hover:bg-indigo-700 transition-all duration-300">
                            Clear Filters
                        </button>
                    </div>
                `;
                return;
            }
            
            leaves.forEach(leave => {
                const leaveCard = document.createElement('div');
                leaveCard.className = 'leave-card bg-white rounded-xl border border-gray-200 p-4 sm:p-6 relative';
                
                const statusClass = getStatusClass(leave.status);
                const statusText = leave.status.charAt(0).toUpperCase() + leave.status.slice(1);
                const typeClass = getLeaveTypeClass(leave.leave_type);
                const typeName = getLeaveTypeName(leave.leave_type);
                const avatarColor = stringToColor(leave.employee_name);
                const initials = getInitials(leave.employee_name);
                
                leaveCard.innerHTML = `
                    <div class="flex justify-between items-start mb-4">
                        <div class="flex items-center min-w-0">
                            <div class="w-10 h-10 rounded-full mr-3 flex items-center justify-center text-white font-bold flex-shrink-0" style="background-color: ${avatarColor};">
                                ${initials}
                            </div>
                            <div class="min-w-0">
                                <div class="font-medium text-gray-900 truncate">${leave.employee_name}</div>
                                <div class="text-gray-500 text-sm truncate">${leave.employee_position}</div>
                            </div>
                        </div>
                        
                        <div class="relative">
                            <button class="menu-btn text-gray-400 hover:text-gray-600 transition-colors p-2">
                                <svg xmlns="http://www.w3.org/2000/svg" 
                                     class="w-5 h-5" 
                                     fill="none" 
                                     viewBox="0 0 24 24" 
                                     stroke="currentColor" 
                                     stroke-width="2">
                                    <path stroke-linecap="round" 
                                          stroke-linejoin="round" 
                                          d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                </svg>
                            </button>
                            <div class="menu-dropdown absolute right-0 mt-2 w-40 bg-white rounded-lg shadow-lg py-2 z-10 hidden">
                                <button class="view-leave w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 flex items-center" data-id="${leave.id}">
                                    <i class="fas fa-eye mr-2 text-blue-500"></i> View Details
                                </button>
                                ${leave.status === 'pending' ? `
                                    <button class="approve-leave w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 flex items-center" data-id="${leave.id}">
                                        <i class="fas fa-check mr-2 text-green-500"></i> Approve
                                    </button>
                                    <button class="reject-leave w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 flex items-center" data-id="${leave.id}">
                                        <i class="fas fa-times mr-2 text-red-500"></i> Reject
                                    </button>
                                ` : ''}
                                <button class="delete-leave w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 flex items-center" data-id="${leave.id}">
                                    <i class="fas fa-trash mr-2 text-red-500"></i> Delete
                                </button>
                            </div>
                        </div>
                    </div>
                    
                    <div class="mb-4 flex-grow">
                        <div class="flex flex-wrap gap-2 mb-3">
                            <span class="leave-badge ${typeClass}">${typeName}</span>
                            <span class="${statusClass} px-3 py-1 rounded-full text-xs font-medium">${statusText}</span>
                        </div>
                        
                        <h4 class="font-bold text-gray-800 text-base sm:text-lg mb-2 truncate-1-line">${leave.subject}</h4>
                        
                        <div class="space-y-2 mb-4">
                            <div class="flex items-center text-sm text-gray-600">
                                <i class="fas fa-calendar-alt text-gray-400 mr-2 w-5 flex-shrink-0"></i>
                                <span class="truncate">${formatDate(leave.from_date)} - ${formatDate(leave.to_date)}</span>
                            </div>
                            <div class="flex items-center text-sm text-gray-600">
                                <i class="fas fa-clock text-gray-400 mr-2 w-5 flex-shrink-0"></i>
                                <span>${leave.total_days} day${leave.total_days > 1 ? 's' : ''}</span>
                            </div>
                            <div class="flex items-center text-sm text-gray-600">
                                <i class="fas fa-envelope text-gray-400 mr-2 w-5 flex-shrink-0"></i>
                                <span class="truncate">${leave.employee_email}</span>
                            </div>
                        </div>
                    </div>
                    
                    <div class="text-sm text-gray-500 mb-4">
                        <p class="truncate">Sent to: ${leave.sent_to}</p>
                        <p class="mt-1">Applied on: ${formatDate(leave.applied_date)}</p>
                    </div>
                    
                    ${leave.admin_remarks ? `
                        <div class="mt-4 pt-4 border-t border-gray-100">
                            <p class="text-sm text-gray-600 truncate-2-lines"><strong>Admin Remarks:</strong> ${leave.admin_remarks}</p>
                        </div>
                    ` : ''}
                    
                    <div class="mt-4 pt-4 border-t border-gray-100">
                        <button class="view-leave-btn w-full bg-indigo-50 text-indigo-600 hover:bg-indigo-100 py-2 px-4 rounded-lg text-sm font-medium flex items-center justify-center" data-id="${leave.id}">
                            <i class="fas fa-eye mr-2"></i> View Details
                        </button>
                    </div>
                `;
                leavesGrid.appendChild(leaveCard);
            });

            // Event Listeners for cards
            document.querySelectorAll('.view-leave').forEach(button => {
                button.addEventListener('click', function() {
                    const leaveId = parseInt(this.getAttribute('data-id'));
                    showLeaveDetailsPage(leaveId);
                });
            });

            document.querySelectorAll('.view-leave-btn').forEach(button => {
                button.addEventListener('click', function() {
                    const leaveId = parseInt(this.getAttribute('data-id'));
                    showLeaveDetailsPage(leaveId);
                });
            });

            document.querySelectorAll('.approve-leave').forEach(button => {
                button.addEventListener('click', function() {
                    const leaveId = parseInt(this.getAttribute('data-id'));
                    approveLeave(leaveId);
                });
            });

            document.querySelectorAll('.reject-leave').forEach(button => {
                button.addEventListener('click', function() {
                    const leaveId = parseInt(this.getAttribute('data-id'));
                    openRejectLeaveModal(leaveId);
                });
            });

            document.querySelectorAll('.delete-leave').forEach(button => {
                button.addEventListener('click', function() {
                    const leaveId = parseInt(this.getAttribute('data-id'));
                    deleteLeave(leaveId);
                });
            });

            // Dropdown logic
            document.querySelectorAll('.menu-btn').forEach(btn => {
                btn.addEventListener('click', function (e) {
                    e.stopPropagation();
                    document.querySelectorAll('.menu-dropdown').forEach(d => {
                        if (d !== this.nextElementSibling) d.classList.add('hidden');
                    });
                    this.nextElementSibling.classList.toggle('hidden');
                });
            });

            document.querySelectorAll('.menu-dropdown').forEach(menu => {
                menu.addEventListener('click', (e) => e.stopPropagation());
            });

            document.addEventListener('click', () => {
                document.querySelectorAll('.menu-dropdown').forEach(d => d.classList.add('hidden'));
            });
        }

        // Admin Actions
        function approveLeave(leaveId) {
    // Get leave details to show in confirmation
    const leaveToApprove = getLeaveById(leaveId);
    const leaveName = leaveToApprove ? leaveToApprove.employee_name : 'this leave application';
    
    // Create custom confirmation popup
    const confirmationPopup = document.createElement('div');
    confirmationPopup.className = 'popup-notification success';
    confirmationPopup.id = 'approve-confirmation-popup';
    confirmationPopup.style.position = 'fixed';
    confirmationPopup.style.top = '50%';
    confirmationPopup.style.left = '50%';
    confirmationPopup.style.transform = 'translate(-50%, -50%)';
    confirmationPopup.style.zIndex = '10000';
    confirmationPopup.style.maxWidth = '400px';
    confirmationPopup.style.width = '90%';
    
    confirmationPopup.innerHTML = `
        <div class="popup-notification-icon">
            <i class="fas fa-check-circle text-white text-xl"></i>
        </div>
        <div class="popup-notification-content">
            <div class="popup-notification-title">
                <span>Confirm Approval</span>
                <button class="popup-notification-close" onclick="closeApproveConfirmation()">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <div class="popup-notification-message">
                Are you sure you want to approve ${leaveName}'s leave request?
            </div>
            <div class="flex space-x-3 mt-4">
                <button onclick="closeApproveConfirmation()" class="flex-1 px-4 py-2 bg-gray-300 text-gray-800 rounded-lg hover:bg-gray-400 transition-all duration-300">
                    Cancel
                </button>
                <button onclick="confirmApproveLeave(${leaveId})" class="flex-1 px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-all duration-300 flex items-center justify-center">
                    <i class="fas fa-check mr-2"></i> Approve
                </button>
            </div>
        </div>
    `;
    
    document.body.appendChild(confirmationPopup);
    setTimeout(() => {
        confirmationPopup.classList.add('show');
    }, 10);
}

// Close approve confirmation
function closeApproveConfirmation() {
    const popup = document.getElementById('approve-confirmation-popup');
    if (popup) {
        popup.classList.remove('show');
        popup.classList.add('hide');
        setTimeout(() => {
            if (popup.parentNode) {
                popup.parentNode.removeChild(popup);
            }
        }, 10000);
    }
}

// Confirm approve action
// Confirm approve action - UPDATED
function confirmApproveLeave(leaveId) {
    // Close confirmation popup INSTANTLY
    const confirmationPopup = document.getElementById('approve-confirmation-popup');
    if (confirmationPopup) {
        confirmationPopup.style.display = 'none';
        if (confirmationPopup.parentNode) {
            confirmationPopup.parentNode.removeChild(confirmationPopup);
        }
    }
    
    // Also call the regular close function for cleanup
    closeApproveConfirmation();
    
    // Show loading notification
    showNotification('info', 'Processing...', 'Approving leave request...');
    
    const url = `{{ route('adminportal.leave.approve', ':id') }}`.replace(':id', leaveId);
    fetch(url, {
        method: 'POST',
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
            showNotification('success', 'Leave Approved', data.message, 5000); // Auto-close in 1 second
            loadLeaves();
            
            // If we're on the details page, update it
            if (leaveDetailsPage.classList.contains('page-active')) {
                showLeaveDetailsPage(leaveId);
            }
        } else {
            throw new Error(data.message || 'Failed to approve leave');
        }
    })
    .catch(error => {
        console.error('Error approving leave:', error);
        showNotification('error', 'Approval Failed', error.message);
    });
}
        
        function rejectLeave(e) {
            e.preventDefault();
            
            const leaveId = document.getElementById('rejectLeaveId').value;
            const submitButton = e.target.querySelector('button[type="submit"]');
            const spinner = document.getElementById('rejectLeaveSpinner');
            
            // Show loading state
            submitButton.disabled = true;
            spinner.classList.remove('hidden');
            
            // Get form data
            const formData = new FormData(rejectLeaveForm);
            
            // Add CSRF token
            formData.append('_token', '{{ csrf_token() }}');

            const url = `{{ route('adminportal.leave.reject', ':id') }}`.replace(':id', leaveId);
            
            fetch(url, {
                method: 'POST',
                headers: {
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    closeRejectLeaveModal();
                    showNotification('success', 'Leave Rejected', data.message);
                    loadLeaves();
                    
                    // If we're on the details page, update it
                    if (leaveDetailsPage.classList.contains('page-active')) {
                        showLeaveDetailsPage(leaveId);
                    }
                } else {
                    if (data.errors) {
                        document.getElementById('rejectionReasonError').textContent = data.errors.admin_remarks ? data.errors.admin_remarks[0] : '';
                        document.getElementById('rejectionReasonError').classList.remove('hidden');
                    } else {
                        throw new Error(data.message || 'Failed to reject leave');
                    }
                }
            })
            .catch(error => {
                console.error('Error rejecting leave:', error);
                showNotification('error', 'Rejection Failed', error.message);
            })
            .finally(() => {
                // Restore button state
                submitButton.disabled = false;
                spinner.classList.add('hidden');
            });
        }

       function deleteLeave(leaveId) {
    // Get leave details to show in confirmation
    const leaveToDelete = getLeaveById(leaveId);
    const leaveName = leaveToDelete ? leaveToDelete.employee_name : 'this leave application';
    
    // Create custom confirmation popup
    const confirmationPopup = document.createElement('div');
    confirmationPopup.className = 'popup-notification warning';
    confirmationPopup.id = 'delete-confirmation-popup';
    confirmationPopup.style.position = 'fixed';
    confirmationPopup.style.top = '50%';
    confirmationPopup.style.left = '50%';
    confirmationPopup.style.transform = 'translate(-50%, -50%)';
    confirmationPopup.style.zIndex = '10000';
    confirmationPopup.style.maxWidth = '400px';
    confirmationPopup.style.width = '90%';
    
    confirmationPopup.innerHTML = `
        <div class="popup-notification-icon">
            <i class="fas fa-exclamation-triangle text-white text-xl"></i>
        </div>
        <div class="popup-notification-content">
            <div class="popup-notification-title">
                <span>Confirm Delete</span>
                <button class="popup-notification-close" onclick="closeDeleteConfirmation()">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <div class="popup-notification-message">
                Are you sure you want to delete ${leaveName}'s leave application?<br>
                <strong>This action cannot be undone.</strong>
            </div>
            <div class="flex space-x-3 mt-4">
                <button onclick="closeDeleteConfirmation()" class="flex-1 px-4 py-2 bg-gray-300 text-gray-800 rounded-lg hover:bg-gray-400 transition-all duration-300">
                    Cancel
                </button>
                <button onclick="confirmDeleteLeave(${leaveId})" class="flex-1 px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-all duration-300 flex items-center justify-center">
                    <i class="fas fa-trash mr-2"></i> Delete
                </button>
            </div>
        </div>
    `;
    
    document.body.appendChild(confirmationPopup);
    setTimeout(() => {
        confirmationPopup.classList.add('show');
    }, 10);
}

// Helper function to get leave by ID
function getLeaveById(leaveId) {
    // This function should return the leave object by ID
    // You might need to implement this based on your data structure
    const leaveCards = document.querySelectorAll('.leave-card');
    for (const card of leaveCards) {
        const viewBtn = card.querySelector('.view-leave-btn');
        if (viewBtn && parseInt(viewBtn.getAttribute('data-id')) === leaveId) {
            const employeeName = card.querySelector('.font-medium.text-gray-900')?.textContent || '';
            return { employee_name: employeeName };
        }
    }
    return null;
}

// Close delete confirmation
function closeDeleteConfirmation() {
    const popup = document.getElementById('delete-confirmation-popup');
    if (popup) {
        popup.classList.remove('show');
        popup.classList.add('hide');
        setTimeout(() => {
            if (popup.parentNode) {
                popup.parentNode.removeChild(popup);
            }
        }, 300);
    }
}

// Confirm delete action - UPDATED
function confirmDeleteLeave(leaveId) {
    // Close confirmation popup INSTANTLY
    const confirmationPopup = document.getElementById('delete-confirmation-popup');
    if (confirmationPopup) {
        confirmationPopup.style.display = 'none';
        if (confirmationPopup.parentNode) {
            confirmationPopup.parentNode.removeChild(confirmationPopup);
        }
    }
    
    // Also call the regular close function for cleanup
    closeDeleteConfirmation();
    
    // Show loading notification
    showNotification('info', 'Processing...', 'Deleting leave application...');
    
    fetch(`{{ route('adminportal.leave.destroy', '') }}/${leaveId}`, {
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
            showNotification('success', 'Deleted Successfully', data.message);
            loadLeaves();
            
            // If we're on the details page, go back to dashboard
            if (leaveDetailsPage.classList.contains('page-active')) {
                showDashboardPage();
            }
        } else {
            throw new Error(data.message || 'Failed to delete leave');
        }
    })
    .catch(error => {
        console.error('Error deleting leave:', error);
        showNotification('error', 'Delete Failed', error.message);
    });
}


        // Filter Leaves
        function filterLeaves() {
            loadLeaves();
            updateMobileFilterSummary();
        }

        // Helper Functions
        function getStatusClass(status) {
            if (status === 'approved') return 'status-approved';
            if (status === 'rejected') return 'status-rejected';
            return 'status-pending';
        }

        function getLeaveTypeClass(type) {
            const classes = {
                casual: "leave-casual",
                sick: "leave-sick",
                paid: "leave-paid",
                emergency: "leave-emergency",
                halfday: "leave-halfday",
                wfh: "leave-wfh"
            };
            return classes[type] || "";
        }

        function getLeaveTypeName(type) {
            const types = {
                casual: "Casual Leave",
                sick: "Sick Leave",
                paid: "Paid Leave",
                emergency: "Emergency Leave",
                halfday: "Half Day",
                wfh: "Work From Home"
            };
            return types[type] || type;
        }

        function formatDate(dateString) {
            try {
                const date = new Date(dateString);
                if (isNaN(date.getTime())) {
                    return dateString; // Return original if invalid date
                }
                return date.toLocaleDateString('en-US', { 
                    month: 'short', 
                    day: 'numeric',
                    year: 'numeric'
                });
            } catch (e) {
                return dateString;
            }
        }

        function formatDateTime(dateTimeString) {
            try {
                const date = new Date(dateTimeString);
                if (isNaN(date.getTime())) {
                    return dateTimeString;
                }
                return date.toLocaleString('en-US', {
                    month: 'short',
                    day: 'numeric',
                    year: 'numeric',
                    hour: '2-digit',
                    minute: '2-digit'
                });
            } catch (e) {
                return dateTimeString;
            }
        }

        function generateTimelineHTML(timeline) {
            if (!timeline || timeline.length === 0) {
                return '<p class="text-gray-500 text-sm">No timeline entries found</p>';
            }
            
            return timeline.map(item => `
                <div class="timeline-item">
                    <div class="flex items-center mb-1">
                        <i class="${item.icon || 'fas fa-circle'} text-blue-500 mr-2 text-sm"></i>
                        <span class="font-medium text-gray-800 text-sm">${item.action || 'Action'}</span>
                    </div>
                    <p class="text-gray-500 text-xs pl-6">${formatDateTime(item.date)}</p>
                </div>
            `).join('');
        }

        function stringToColor(str) {
            let hash = 0;
            for (let i = 0; i < str.length; i++) {
                hash = str.charCodeAt(i) + ((hash << 5) - hash);
            }
            const colors = ['#3b82f6', '#10b981', '#8b5cf6', '#f59e0b', '#ef4444', '#ec4899', '#14b8a6'];
            return colors[Math.abs(hash) % colors.length];
        }

        function getInitials(name) {
            if (!name) return 'NA';
            return name.split(' ').map(part => part[0]).join('').toUpperCase().substring(0, 2);
        }

        // Show notification
// Show popup notification with optional duration
function showNotification(type, title, message, duration = 5000) {
    const container = document.getElementById('popupNotificationContainer');
    
    // Create notification element
    const notification = document.createElement('div');
    notification.className = `popup-notification ${type}`;
    
    // Set icon based on type
    let icon = 'fa-info-circle';
    if (type === 'success') icon = 'fa-check-circle';
    if (type === 'error') icon = 'fa-exclamation-circle';
    if (type === 'warning') icon = 'fa-exclamation-triangle';
    
    // Generate unique ID for this notification
    const notificationId = 'notification-' + Date.now();
    notification.id = notificationId;
    
    notification.innerHTML = `
        <div class="popup-notification-icon">
            <i class="fas ${icon} text-white text-xl"></i>
        </div>
        <div class="popup-notification-content">
            <div class="popup-notification-title">
                <span>${title}</span>
                <button class="popup-notification-close" onclick="closeNotification('${notificationId}')">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <div class="popup-notification-message">
                ${message}
            </div>
            <div class="popup-notification-progress">
                <div class="popup-notification-progress-bar" style="animation-duration: ${duration}ms"></div>
            </div>
        </div>
    `;
    
    // Add notification to container
    container.appendChild(notification);
    
    // Trigger animation
    setTimeout(() => {
        notification.classList.add('show');
    }, 10);
    
    // Auto-remove after specified duration
    const autoRemoveTimer = setTimeout(() => {
        closeNotification(notificationId);
    }, duration);
    
    // Store timer ID for cleanup
    notification.dataset.timerId = autoRemoveTimer;
    
    // Pause progress on hover
    const progressBar = notification.querySelector('.popup-notification-progress-bar');
    notification.addEventListener('mouseenter', () => {
        progressBar.style.animationPlayState = 'paused';
        clearTimeout(autoRemoveTimer);
    });
    
    notification.addEventListener('mouseleave', () => {
        progressBar.style.animationPlayState = 'running';
        const newTimer = setTimeout(() => {
            closeNotification(notificationId);
        }, 2000);
        notification.dataset.timerId = newTimer;
    });
}
// Close specific notification
function closeNotification(notificationId) {
    const notification = document.getElementById(notificationId);
    if (!notification) return;
    
    // Clear any pending timer
    if (notification.dataset.timerId) {
        clearTimeout(parseInt(notification.dataset.timerId));
    }
    
    // Hide animation
    notification.classList.remove('show');
    notification.classList.add('hide');
    
    // Remove from DOM after animation
    setTimeout(() => {
        if (notification.parentNode) {
            notification.parentNode.removeChild(notification);
        }
    }, 300);
}

// Close all notifications
function closeAllNotifications() {
    const notifications = document.querySelectorAll('.popup-notification');
    notifications.forEach(notification => {
        closeNotification(notification.id);
    });
}
        
        function hideNotificationToast() {
            const notificationToast = document.getElementById('notificationToast');
            notificationToast.classList.add('translate-y-full');
            setTimeout(() => {
                notificationToast.classList.add('hidden');
            }, 300);
        }

        // Prevent body scroll when modals are open
        function setupModalScrollPrevention() {
            const modals = [mobileMenuOverlay, mobileSearchOverlay, mobileFilterModal, rejectLeaveModal];
            
            modals.forEach(modal => {
                if (modal) {
                    modal.addEventListener('scroll', function(e) {
                        e.stopPropagation();
                    });
                }
            });
        }

        // Initialize the app
        document.addEventListener('DOMContentLoaded', initApp);
        
        // Handle window resize
        window.addEventListener('resize', function() {
            // Close dropdowns on resize
            document.querySelectorAll('.menu-dropdown').forEach(d => d.classList.add('hidden'));
            if (exportMenu) exportMenu.classList.add('hidden');
            
            // Update UI based on screen size
            if (window.innerWidth >= 768) {
                // Close mobile overlays when switching to desktop
                closeMobileMenuOverlay();
                closeMobileSearchOverlay();
                closeMobileFilterModal();
                closeMobileExportOptions();
            }
        });
    </script>
</body>
</html>

@endsection