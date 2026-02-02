@extends('components.layout')
@section('content')
<div class="container mx-auto px-3 sm:px-4 py-4 sm:py-8">
    <div class="bg-white shadow-xl rounded-lg overflow-hidden border border-gray-200">
        <!-- Header matching reference image style - Responsive -->
        <div class="bg-gradient-to-r from-purple-600 to-indigo-600 text-white px-4 sm:px-6 py-4 sm:py-5 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-3 sm:gap-0">
            <h3 class="text-xl sm:text-2xl font-bold text-center sm:text-left w-full sm:w-auto">Monthly Attendance Summary ({{ $currentMonthYear }})</h3>
            <a href="{{ route('attendance-record.index') }}" class="text-xs sm:text-sm font-medium hover:text-white/90 transition-colors underline w-full sm:w-auto text-center sm:text-right">Refresh Data</a>
        </div>
        
        <div class="p-4 sm:p-6">
            {{-- Summary Cards - Responsive grid with mobile stacking --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-6 mb-6 sm:mb-8">
                {{-- Total Users (Employees) --}}
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-4 sm:p-6 hover:shadow-md transition-shadow">
                    <div class="flex items-center justify-between">
                        <div class="flex-grow">
                            <p class="text-xs sm:text-sm font-medium text-gray-600">TOTAL USERS</p>
                            <p class="text-2xl sm:text-3xl font-bold text-gray-900 mt-1">{{ $totalSummary['total_employees'] ?? 0 }}</p>
                            <p class="text-xs text-green-600 mt-2 hidden sm:block">↑ 12.5% vs last month</p>
                        </div>
                        <div class="w-10 h-10 sm:w-12 sm:h-12 rounded-full bg-green-100 flex items-center justify-center flex-shrink-0 ml-3">
                            <i class="fas fa-user text-green-600 text-lg sm:text-xl"></i>
                        </div>
                    </div>
                    <p class="text-xs text-green-600 mt-2 sm:hidden">↑ 12.5% vs last month</p>
                </div>
                
                {{-- Present Today --}}
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-4 sm:p-6 hover:shadow-md transition-shadow">
                    <div class="flex items-center justify-between">
                        <div class="flex-grow">
                            <p class="text-xs sm:text-sm font-medium text-gray-600">PRESENT TODAY</p>
                            <p class="text-2xl sm:text-3xl font-bold text-gray-900 mt-1">{{ $totalSummary['present_today'] ?? 0 }}</p>
                            <p class="text-xs text-green-600 mt-2 hidden sm:block">↑ 8.2% vs yesterday</p>
                        </div>
                        <div class="w-10 h-10 sm:w-12 sm:h-12 rounded-full bg-blue-100 flex items-center justify-center flex-shrink-0 ml-3">
                            <i class="fas fa-check-circle text-blue-600 text-lg sm:text-xl"></i>
                        </div>
                    </div>
                    <p class="text-xs text-green-600 mt-2 sm:hidden">↑ 8.2% vs yesterday</p>
                </div>
                
                {{-- Total Clients --}}
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-4 sm:p-6 hover:shadow-md transition-shadow">
                    <div class="flex items-center justify-between">
                        <div class="flex-grow">
                            <p class="text-xs sm:text-sm font-medium text-gray-600">TOTAL CLIENTS</p>
                            <p class="text-2xl sm:text-3xl font-bold text-gray-900 mt-1">2</p>
                            <p class="text-xs text-green-600 mt-2 hidden sm:block">↑ 15.3% vs last month</p>
                        </div>
                        <div class="w-10 h-10 sm:w-12 sm:h-12 rounded-full bg-purple-100 flex items-center justify-center flex-shrink-0 ml-3">
                            <i class="fas fa-briefcase text-purple-600 text-lg sm:text-xl"></i>
                        </div>
                    </div>
                    <p class="text-xs text-green-600 mt-2 sm:hidden">↑ 15.3% vs last month</p>
                </div>

                {{-- Total Contacts --}}
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-4 sm:p-6 hover:shadow-md transition-shadow">
                    <div class="flex items-center justify-between">
                        <div class="flex-grow">
                            <p class="text-xs sm:text-sm font-medium text-gray-600">TOTAL CONTACTS</p>
                            <p class="text-2xl sm:text-3xl font-bold text-gray-900 mt-1">0</p>
                            <p class="text-xs text-red-600 mt-2 hidden sm:block">↓ 3.1% vs last month</p>
                        </div>
                        <div class="w-10 h-10 sm:w-12 sm:h-12 rounded-full bg-orange-100 flex items-center justify-center flex-shrink-0 ml-3">
                            <i class="fas fa-phone text-orange-600 text-lg sm:text-xl"></i>
                        </div>
                    </div>
                    <p class="text-xs text-red-600 mt-2 sm:hidden">↓ 3.1% vs last month</p>
                </div>
            </div>

            {{-- Main Filter - Responsive layout --}}
            <div class="mb-6 sm:mb-8 p-4 sm:p-5 bg-gray-50 rounded-xl border border-gray-200">
                <div class="grid grid-cols-1 md:grid-cols-4 gap-3 sm:gap-4">
                    <div class="md:col-span-3">
                        <label for="employeeFilter" class="block text-xs sm:text-sm font-semibold text-gray-700 mb-1 sm:mb-2">Filter by Employee</label>
                        <div class="flex flex-col sm:flex-row gap-3 sm:gap-4">
                            <select id="employeeFilter" class="flex-grow border border-gray-300 rounded-lg px-3 sm:px-4 py-2.5 sm:py-3 text-xs sm:text-sm focus:outline-none focus:ring-2 focus:ring-purple-500 bg-white">
                                <option value="">All Employees</option>
                                @foreach($employees as $employee)
                                    <option value="{{ $employee['name'] }}" data-id="{{ $employee['id'] }}">{{ $employee['name'] }}</option>
                                @endforeach
                            </select>
                            <button type="button" id="clearFilters" class="bg-gray-700 hover:bg-gray-900 text-white px-4 sm:px-6 py-2.5 sm:py-3 rounded-lg text-xs sm:text-sm font-medium transition-colors shadow-sm w-full sm:w-auto">
                                Clear Filter
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            {{-- DataTable - Monthly Employee Summary - ALL COLUMNS VISIBLE ON MOBILE --}}
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                <div id="attendanceTable_wrapper" class="dataTables_wrapper p-4 sm:p-6">
                    <div class="w-full">
                        <table id="attendanceTable" class="w-full table-auto text-xs sm:text-sm">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-3 sm:px-6 py-3 text-left font-bold text-gray-700 uppercase tracking-wider">Employee</th>
                                    <th class="px-3 sm:px-6 py-3 text-left font-bold text-gray-700 uppercase tracking-wider">Total Present</th>
                                    <th class="px-3 sm:px-6 py-3 text-left font-bold text-gray-700 uppercase tracking-wider">Total Absent</th>
                                    <th class="px-3 sm:px-6 py-3 text-left font-bold text-gray-700 uppercase tracking-wider">Total Half Day</th>
                                    <th class="px-3 sm:px-6 py-3 text-left font-bold text-gray-700 uppercase tracking-wider">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100">
                                @foreach($employeeSummaries as $employeeSummary)
                                <tr class="hover:bg-gray-50 transition-colors">
                                    <td class="px-3 sm:px-6 py-4 font-medium text-gray-900">{{ $employeeSummary['employee_name'] ?? 'N/A' }}</td>
                                    <td class="px-3 sm:px-6 py-4 text-gray-900 text-center">{{ $employeeSummary['present_count'] }}</td>
                                    <td class="px-3 sm:px-6 py-4 text-gray-900 text-center">{{ $employeeSummary['absent_count'] }}</td>
                                    <td class="px-3 sm:px-6 py-4 text-gray-900 text-center">{{ $employeeSummary['half_day_count'] }}</td>
                                    <td class="px-3 sm:px-6 py-4 text-center">
                                        <button class="bg-gradient-to-r from-purple-600 to-indigo-600 hover:from-purple-700 hover:to-indigo-700 text-white px-3 sm:px-5 py-2 sm:py-2.5 rounded-lg text-xs sm:text-sm font-medium transition-all shadow-sm hover:shadow viewMonthly w-full sm:w-auto"
                                            data-employee-id="{{ $employeeSummary['employee_id'] }}"
                                            data-employee-name="{{ $employeeSummary['employee_name'] ?? 'N/A' }}"
                                            data-attendance-date="{{ $currentMonthYear }}-01">
                                            View Details
                                        </button>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Monthly Attendance Modal - Responsive --}}
<div id="monthlyModal" class="fixed inset-0 bg-black bg-opacity-50 overflow-y-auto h-full w-full hidden z-50 flex items-center justify-center p-2 sm:p-4">
    <div class="relative mx-auto p-4 sm:p-6 md:p-8 w-full max-w-6xl max-h-[90vh] sm:max-h-[85vh] overflow-y-auto bg-white rounded-xl sm:rounded-2xl shadow-2xl" id="modalContent">
        <div class="flex justify-between items-center pb-4 border-b border-gray-200 mb-4 sm:mb-6">
            <h5 class="text-lg sm:text-xl md:text-2xl font-bold text-gray-900 truncate">
                Attendance for <span class="text-purple-600" id="monthlyEmployeeName"></span>
            </h5>
            <button type="button" class="text-gray-400 hover:text-gray-600 touch-target" onclick="closeModal()">
                <i class="fas fa-times text-xl sm:text-2xl"></i>
            </button>
        </div>
        
        {{-- Modal Filters - Responsive --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-3 sm:gap-4 mb-4 sm:mb-6 p-3 sm:p-5 bg-gray-50 rounded-xl border border-gray-200">
            <div class="sm:col-span-2 lg:col-span-1">
                <label for="modalEmployeeFilter" class="block text-xs font-semibold text-gray-700 mb-1">Employee</label>
                <input type="text" id="modalEmployeeFilter" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-xs sm:text-sm bg-gray-100" disabled>
            </div>
            <div>
                <label for="modalStatusFilter" class="block text-xs font-semibold text-gray-700 mb-1">Status</label>
                <select id="modalStatusFilter" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-xs sm:text-sm focus:outline-none focus:ring-2 focus:ring-purple-500">
                    <option value="">All Status</option>
                    <option value="Present">Present</option>
                    <option value="Late">Late</option>
                    <option value="Half Day">Half Day</option>
                    <option value="Absent">Absent</option>
                </select>
            </div>
            <div>
                <label for="modalDateFrom" class="block text-xs font-semibold text-gray-700 mb-1">Date From</label>
                <input type="date" id="modalDateFrom" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-xs sm:text-sm focus:outline-none focus:ring-2 focus:ring-purple-500">
            </div>
            <div>
                <label for="modalDateTo" class="block text-xs font-semibold text-gray-700 mb-1">Date To</label>
                <input type="date" id="modalDateTo" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-xs sm:text-sm focus:outline-none focus:ring-2 focus:ring-purple-500">
            </div>
            <div class="flex items-end">
                <button type="button" id="modalClearFilters" class="w-full bg-gray-700 hover:bg-gray-900 text-white px-3 sm:px-4 py-2 rounded-lg text-xs sm:text-sm font-medium transition-colors shadow-sm">
                    Clear Filters
                </button>
            </div>
        </div>

        <div id="monthlyTableContainer" class="mt-4 sm:mt-6">
            <!-- Table loads here -->
        </div>
    </div>
</div>

{{-- Responsive CSS --}}
<style>
/* Mobile optimizations */
@media (max-width: 640px) {
    .touch-target {
        min-height: 44px;
        min-width: 44px;
    }
    
    .dataTables_wrapper .dataTables_filter input {
        margin-top: 10px !important;
        margin-bottom: 10px !important;
    }
    
    .dataTables_wrapper .dataTables_info {
        padding-left: 0.5rem !important;
        padding-right: 0.5rem !important;
    }
    
    .dataTables_wrapper .dataTables_paginate {
        padding: 0.5rem !important;
    }
    
    /* Make table cells wrap on mobile */
    #attendanceTable td, #attendanceTable th {
        white-space: normal;
        word-wrap: break-word;
        padding: 0.5rem 0.25rem;
    }
    
    #attendanceTable th {
        font-size: 0.65rem;
        padding: 0.5rem 0.25rem;
        line-height: 1.2;
    }
    
    #attendanceTable td {
        font-size: 0.7rem;
        padding: 0.5rem 0.25rem;
        line-height: 1.2;
    }
    
    /* Reduce button padding on mobile */
    #attendanceTable button {
        padding: 0.25rem 0.5rem;
        font-size: 0.65rem;
        white-space: nowrap;
        line-height: 1.2;
    }
    
    /* Remove any overflow */
    #attendanceTable_wrapper {
        overflow-x: hidden !important;
        overflow-y: visible !important;
    }
    
    #attendanceTable {
        width: 100% !important;
        max-width: 100% !important;
        table-layout: fixed !important;
    }
}

@media (max-width: 768px) {
    /* Adjust modal for smaller screens */
    #modalContent {
        width: 95% !important;
        padding: 1rem !important;
    }
    
    /* Make modal table wrap on mobile */
    #monthlyDataTable td, #monthlyDataTable th {
        white-space: normal;
        word-wrap: break-word;
        font-size: 0.7rem;
        padding: 0.4rem 0.25rem;
        line-height: 1.2;
    }
    
    #monthlyDataTable th {
        font-size: 0.65rem;
        padding: 0.5rem 0.25rem;
        line-height: 1.2;
    }
    
    #monthlyDataTable button, #monthlyDataTable .status-badge {
        font-size: 0.65rem;
        line-height: 1.2;
    }
}

/* Touch-friendly scrolling (only vertical) */
.scroll-touch {
    -webkit-overflow-scrolling: touch;
}

/* Modal responsiveness */
@media (max-width: 1024px) {
    #monthlyModal .modal-content {
        width: 95%;
        margin: 1rem auto;
    }
}

/* Status badges for mobile */
.status-badge {
    display: inline-block;
    padding: 2px 6px;
    border-radius: 10px;
    font-size: 0.65rem;
    font-weight: 600;
    line-height: 1.2;
}

.status-present { background-color: #d1fae5; color: #065f46; }
.status-late { background-color: #fef3c7; color: #92400e; }
.status-half-day { background-color: #e0e7ff; color: #3730a3; }
.status-absent { background-color: #fee2e2; color: #991b1b; }

/* Ensure all table columns are visible on mobile WITHOUT SCROLLING */
#attendanceTable {
    width: 100%;
    table-layout: fixed;
}

/* Fixed column widths for mobile */
@media (max-width: 640px) {
    #attendanceTable {
        table-layout: fixed;
        width: 100%;
    }
    
    #attendanceTable th:nth-child(1), 
    #attendanceTable td:nth-child(1) {
        width: 35% !important;
        max-width: 35% !important;
        overflow: hidden;
        text-overflow: ellipsis;
    }
    
    #attendanceTable th:nth-child(2), 
    #attendanceTable td:nth-child(2) {
        width: 15% !important;
        max-width: 15% !important;
    }
    
    #attendanceTable th:nth-child(3), 
    #attendanceTable td:nth-child(3) {
        width: 15% !important;
        max-width: 15% !important;
    }
    
    #attendanceTable th:nth-child(4), 
    #attendanceTable td:nth-child(4) {
        width: 15% !important;
        max-width: 15% !important;
    }
    
    #attendanceTable th:nth-child(5), 
    #attendanceTable td:nth-child(5) {
        width: 20% !important;
        max-width: 20% !important;
    }
}

/* For modal table on mobile */
@media (max-width: 640px) {
    #monthlyDataTable {
        table-layout: fixed;
        width: 100%;
    }
    
    #monthlyDataTable th:nth-child(1), 
    #monthlyDataTable td:nth-child(1) {
        width: 20% !important;
        max-width: 20% !important;
    }
    
    #monthlyDataTable th:nth-child(2), 
    #monthlyDataTable td:nth-child(2) {
        width: 15% !important;
        max-width: 15% !important;
    }
    
    #monthlyDataTable th:nth-child(3), 
    #monthlyDataTable td:nth-child(3) {
        width: 15% !important;
        max-width: 15% !important;
    }
    
    #monthlyDataTable th:nth-child(4), 
    #monthlyDataTable td:nth-child(4) {
        width: 15% !important;
        max-width: 15% !important;
    }
    
    #monthlyDataTable th:nth-child(5), 
    #monthlyDataTable td:nth-child(5) {
        width: 20% !important;
        max-width: 20% !important;
    }
    
    #monthlyDataTable th:nth-child(6), 
    #monthlyDataTable td:nth-child(6) {
        width: 15% !important;
        max-width: 15% !important;
    }
}

/* Desktop view remains exactly the same */
@media (min-width: 641px) {
    #attendanceTable {
        table-layout: auto;
    }
    
    #attendanceTable td, #attendanceTable th {
        white-space: normal;
    }
}
</style>

{{-- JS/jQuery with responsive enhancements --}}
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/jquery.dataTables.min.css">

<script>
let monthlyDataTable;
let summaryTable;

function openModal() {
    $('#monthlyModal').removeClass('hidden').addClass('flex');
    document.body.style.overflow = 'hidden';
}

function closeModal() {
    $('#monthlyModal').removeClass('flex').addClass('hidden');
    document.body.style.overflow = 'auto';
    if (monthlyDataTable) {
        monthlyDataTable.destroy();
        monthlyDataTable = null;
    }
    $('#monthlyTableContainer').html('');
    $('#modalStatusFilter, #modalDateFrom, #modalDateTo').val('');
}

$(document).ready(function() {
    // Main Table - ALL COLUMNS VISIBLE on mobile WITHOUT SCROLLING
    summaryTable = $('#attendanceTable').DataTable({
        pageLength: 10,
        responsive: false, // Turn off DataTables responsive to keep all columns
        ordering: true,
        searching: true,
        lengthChange: false,
        scrollX: false, // Disable horizontal scrolling
        autoWidth: false, // Disable auto width calculation
        columnDefs: [
            { orderable: false, targets: -1 },
            { className: 'text-center', targets: [1, 2, 3] }
        ],
        dom: '<"flex flex-col sm:flex-row sm:items-center justify-between mb-4 gap-2"<"flex-grow"l><"mt-2 sm:mt-0"f>>rt<"flex flex-col sm:flex-row sm:items-center justify-between mt-4 gap-2"ip>',
        language: {
            search: "",
            searchPlaceholder: "Search employees...",
            paginate: {
                previous: "‹",
                next: "›",
                first: "«",
                last: "»"
            },
            info: "Showing _START_-_END_ of _TOTAL_",
            lengthMenu: "Show _MENU_ entries"
        },
        initComplete: function() {
            // Make search box responsive
            $('.dataTables_filter input').addClass('border border-gray-300 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-purple-500 w-full sm:w-64');
            
            // Adjust table for mobile - NO SCROLLING
            adjustTableForMobile();
            
            // Force table to fit without scrolling
            forceTableFit();
        },
        drawCallback: function() {
            // Update mobile view
            updateMobileView();
            forceTableFit();
        }
    });

    // Function to force table to fit without scrolling
    function forceTableFit() {
        if ($(window).width() < 768) {
            // Remove any DataTables scrolling wrapper
            $('#attendanceTable_wrapper .dataTables_scroll').remove();
            $('#attendanceTable_wrapper .dataTables_scrollHead').remove();
            $('#attendanceTable_wrapper .dataTables_scrollBody').remove();
            $('#attendanceTable_wrapper .dataTables_scrollFoot').remove();
            
            // Make table wrapper non-scrollable
            $('#attendanceTable_wrapper').css({
                'overflow-x': 'hidden',
                'overflow-y': 'visible'
            });
            
            // Ensure table takes full width
            $('#attendanceTable').css({
                'width': '100%',
                'max-width': '100%',
                'table-layout': 'fixed'
            });
        }
    }

    // Function to adjust table for mobile view
    function adjustTableForMobile() {
        if ($(window).width() < 768) {
            // Reduce font size for mobile
            $('#attendanceTable').addClass('text-xs');
            $('.dataTables_length select').addClass('text-xs py-1.5');
            
            // Ensure table fits screen WITHOUT SCROLLING
            $('#attendanceTable').css('width', '100%');
            $('#attendanceTable_wrapper').css('overflow-x', 'hidden');
            
            // Truncate long employee names
            $('#attendanceTable td:first-child').each(function() {
                let text = $(this).text();
                if (text.length > 20) {
                    $(this).text(text.substring(0, 17) + '...');
                    $(this).attr('title', text);
                }
            });
        } else {
            $('#attendanceTable').removeClass('text-xs');
            $('#attendanceTable').css('width', '');
            $('#attendanceTable_wrapper').css('overflow-x', '');
        }
    }

    // Function to update mobile-specific display
    function updateMobileView() {
        if ($(window).width() < 768) {
            $('.dataTables_length select').addClass('text-xs py-1.5');
        }
    }

    // Main Filter
    $('#employeeFilter').on('change', function() {
        summaryTable.columns(0).search($(this).val()).draw();
    });

    $('#clearFilters').on('click', function() {
        $('#employeeFilter').val('');
        summaryTable.search('').columns().search('').draw();
    });

    // View Details Modal
    $(document).on('click', '.viewMonthly', function() {
        let employeeId = $(this).data('employee-id');
        let employeeName = $(this).data('employee-name');
        let selectedMonth = $(this).data('attendance-date').substring(0, 7);

        $('#monthlyEmployeeName').text(employeeName + ' (' + selectedMonth + ')');
        $('#modalEmployeeFilter').val(employeeName);
        $('#monthlyTableContainer').html(`
            <div class="text-center py-8 sm:py-12">
                <div class="inline-block animate-spin rounded-full h-8 w-8 border-t-2 border-b-2 border-purple-600 mb-4"></div>
                <p class="text-gray-500 text-sm">Loading attendance details...</p>
            </div>
        `);
        
        openModal();

        // Adjust modal for mobile
        if ($(window).width() < 640) {
            $('#modalContent').addClass('p-3');
        }

        $.ajax({
            url: '{{ route('attendance-record.monthly') }}',
            method: 'GET',
            data: { employee_id: employeeId, month: selectedMonth },
            success: function(response) {
                let monthlyData = response;
                if (monthlyData.length === 0) {
                    $('#monthlyTableContainer').html(`
                        <div class="text-center py-8 sm:py-12">
                            <i class="fas fa-clipboard-list text-gray-400 text-3xl mb-4"></i>
                            <p class="text-gray-600">No attendance records found for this month.</p>
                        </div>
                    `);
                    return;
                }

                // Create table structure - ALL COLUMNS VISIBLE WITHOUT SCROLLING
                let html = `
                    <div class="w-full overflow-x-hidden">
                        <table id="monthlyDataTable" class="w-full text-xs sm:text-sm">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-3 sm:px-6 py-3 text-left font-bold text-gray-700">Date</th>
                                    <th class="px-3 sm:px-6 py-3 text-left font-bold text-gray-700">Punch In</th>
                                    <th class="px-3 sm:px-6 py-3 text-left font-bold text-gray-700">Punch Out</th>
                                    <th class="px-3 sm:px-6 py-3 text-left font-bold text-gray-700">Hours</th>
                                    <th class="px-3 sm:px-6 py-3 text-left font-bold text-gray-700">Status</th>
                                    <th class="px-3 sm:px-6 py-3 text-left font-bold text-gray-700">Overtime</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100">`;
                
                monthlyData.forEach(function(item) {
                    let formattedDate = item.date.substring(0, 10);
                    let punchIn = item.punch_in ? new Date(item.punch_in).toLocaleTimeString([], {hour: '2-digit', minute: '2-digit'}) : '—';
                    let punchOut = item.punch_out ? new Date(item.punch_out).toLocaleTimeString([], {hour: '2-digit', minute: '2-digit'}) : '—';
                    let workHours = item.work_hours ? item.work_hours.split(':').slice(0, 2).join(':') : '—';
                    let overtime = item.overtime_seconds ? new Date(item.overtime_seconds * 1000).toISOString().substr(11, 8) : '00:00:00';
                    
                    // Get status class
                    let statusClass = 'status-badge ';
                    switch(item.status) {
                        case 'Present': statusClass += 'status-present'; break;
                        case 'Late': statusClass += 'status-late'; break;
                        case 'Half Day': statusClass += 'status-half-day'; break;
                        case 'Absent': statusClass += 'status-absent'; break;
                        default: statusClass += 'bg-gray-100 text-gray-800';
                    }

                    html += `<tr class="hover:bg-gray-50">
                        <td class="px-3 sm:px-6 py-4 font-medium">${formattedDate}</td>
                        <td class="px-3 sm:px-6 py-4">${punchIn}</td>
                        <td class="px-3 sm:px-6 py-4">${punchOut}</td>
                        <td class="px-3 sm:px-6 py-4">${workHours}</td>
                        <td class="px-3 sm:px-6 py-4">
                            <span class="${statusClass}">${item.status}</span>
                        </td>
                        <td class="px-3 sm:px-6 py-4">
                            <span class="text-xs font-medium text-purple-700">${overtime}</span>
                        </td>
                    </tr>`;
                });
                
                html += '</tbody></table></div>';
                $('#monthlyTableContainer').html(html);

                // Initialize DataTable for modal - ALL COLUMNS VISIBLE WITHOUT SCROLLING
                monthlyDataTable = $('#monthlyDataTable').DataTable({
                    paging: true,
                    pageLength: 8,
                    searching: true,
                    info: true,
                    order: [[0, 'asc']],
                    responsive: false, // Turn off responsive to keep all columns
                    scrollX: false, // Disable horizontal scrolling
                    autoWidth: false, // Disable auto width
                    lengthChange: false,
                    columnDefs: [],
                    dom: '<"flex flex-col sm:flex-row sm:items-center justify-between mb-4 gap-2"fl>rt<"flex flex-col sm:flex-row sm:items-center justify-between mt-4 gap-2"ip>',
                    language: {
                        search: "",
                        searchPlaceholder: "Search records...",
                        paginate: {
                            previous: "‹",
                            next: "›",
                            first: "«",
                            last: "»"
                        },
                        info: "Showing _START_-_END_ of _TOTAL_"
                    },
                    initComplete: function() {
                        // Adjust modal table for mobile
                        if ($(window).width() < 768) {
                            $('#monthlyDataTable').addClass('text-xs');
                            $('.dataTables_filter input', '#monthlyTableContainer').addClass('w-full');
                            
                            // Force modal table to fit
                            $('#monthlyDataTable').css({
                                'width': '100%',
                                'max-width': '100%',
                                'table-layout': 'fixed'
                            });
                            
                            // Remove any scrolling wrappers
                            $('#monthlyTableContainer .dataTables_scroll').remove();
                        }
                    }
                });

                // Style search box
                $('.dataTables_filter input', '#monthlyTableContainer').addClass('border border-gray-300 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-purple-500 w-full sm:w-64');

                // Custom filtering function
                $.fn.dataTable.ext.search.push(function(settings, data, dataIndex) {
                    if (settings.nTable.id !== 'monthlyDataTable') return true;
                    
                    let status = $('#modalStatusFilter').val();
                    let from = $('#modalDateFrom').val();
                    let to = $('#modalDateTo').val();
                    
                    let date = data[0];
                    let rowStatus = $(monthlyDataTable.row(dataIndex).node()).find('td:nth-child(5) span').text().trim();

                    // Status filter
                    if (status && rowStatus !== status) return false;
                    
                    // Date range filter
                    if (from && date < from) return false;
                    if (to && date > to) return false;
                    
                    return true;
                });

                // Bind filter events
                $('#modalStatusFilter, #modalDateFrom, #modalDateTo').on('change', function() {
                    monthlyDataTable.draw();
                });

                $('#modalClearFilters').on('click', function() {
                    $('#modalStatusFilter, #modalDateFrom, #modalDateTo').val('');
                    monthlyDataTable.draw();
                });

            },
            error: function(xhr, status, error) {
                console.error('Error loading attendance data:', error);
                $('#monthlyTableContainer').html(`
                    <div class="text-center py-8 sm:py-12">
                        <i class="fas fa-exclamation-triangle text-red-400 text-3xl mb-4"></i>
                        <p class="text-red-600 mb-2">Failed to load attendance data.</p>
                        <p class="text-gray-500 text-sm">Please try again later.</p>
                    </div>
                `);
            }
        });
    });

    // Close modal when clicking outside
    $('#monthlyModal').on('click', function(e) {
        if (e.target === this) closeModal();
    });

    // Close modal with Escape key
    $(document).on('keydown', function(e) {
        if (e.key === 'Escape') closeModal();
    });

    // Handle window resize for table responsiveness
    $(window).on('resize', function() {
        adjustTableForMobile();
        updateMobileView();
        forceTableFit();
    });

    // Initial adjustments
    adjustTableForMobile();
    updateMobileView();
    forceTableFit();
});
</script>
@endsection