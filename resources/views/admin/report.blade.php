{{-- resources/views/reports/index.blade.php --}}
@extends('components.layout')
@section('content')

<!-- Page Content -->
<div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <!-- Report List Page -->
    <div id="report-list" class="animate-fade-in">
        <!-- Header -->
        <div class="mb-8">
            <div class="gradient-primary rounded-2xl p-8 text-white shadow-card">
                <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                    <div>
                        <h1 class="text-3xl font-bold mb-2">My Daily Reports</h1>
                        <p class="opacity-90 max-w-2xl">Track and manage your daily work reports. Monitor progress and maintain productivity.</p>
                    </div>
                    <button id="createReportBtn" class="mt-2 md:mt-0 bg-white text-indigo-600 px-6 py-3 rounded-full font-semibold flex items-center space-x-2 hover-lift smooth-transition shadow-soft">
                        <i class="fas fa-plus"></i>
                        <span>New Report</span>
                    </button>
                </div>
            </div>
        </div>
        <!-- Stats Cards -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            <div class="gradient-card rounded-2xl p-6 shadow-card">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-500">Total Reports</p>
                        <p id="totalReportsCount" class="text-2xl font-bold text-gray-800 mt-1">0</p>
                        <div class="progress-bar mt-2">
                            <div class="progress-fill" style="width: 85%"></div>
                        </div>
                    </div>
                    <div class="w-12 h-12 rounded-xl bg-indigo-100 flex items-center justify-center">
                        <i class="fas fa-file-alt text-indigo-600 text-lg"></i>
                    </div>
                </div>
            </div>
            <div class="gradient-card rounded-2xl p-6 shadow-card">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-500">This Week</p>
                        <p id="weekReportsCount" class="text-2xl font-bold text-gray-800 mt-1">0</p>
                        <div class="progress-bar mt-2">
                            <div class="progress-fill" style="width: 65%"></div>
                        </div>
                    </div>
                    <div class="w-12 h-12 rounded-xl bg-green-100 flex items-center justify-center">
                        <i class="fas fa-calendar-week text-green-600 text-lg"></i>
                    </div>
                </div>
            </div>
            <div class="gradient-card rounded-2xl p-6 shadow-card">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-500">Avg. Tasks/Day</p>
                        <p id="avgTasksCount" class="text-2xl font-bold text-gray-800 mt-1">0</p>
                        <div class="progress-bar mt-2">
                            <div class="progress-fill" style="width: 70%"></div>
                        </div>
                    </div>
                    <div class="w-12 h-12 rounded-xl bg-purple-100 flex items-center justify-center">
                        <i class="fas fa-tasks text-purple-600 text-lg"></i>
                    </div>
                </div>
            </div>
            <div class="gradient-card rounded-2xl p-6 shadow-card">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-500">Productivity</p>
                        <p id="productivityPercent" class="text-2xl font-bold text-gray-800 mt-1">0%</p>
                        <div class="progress-bar mt-2">
                            <div class="progress-fill" style="width: 0%"></div>
                        </div>
                    </div>
                    <div class="w-12 h-12 rounded-xl bg-blue-100 flex items-center justify-center">
                        <i class="fas fa-chart-line text-blue-600 text-lg"></i>
                    </div>
                </div>
            </div>
        </div>
        <!-- Reports Table -->
        <div class="bg-white rounded-2xl shadow-card overflow-hidden">
            <div class="px-6 py-5 border-b border-gray-100">
                <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
                    <!-- Left: Title + Active Filters -->
                    <div>
                        <h2 class="text-lg font-semibold text-gray-900">Recent Reports</h2>
                        <!-- Active Filters Bar -->
                        <div id="activeFiltersBar" class="mt-2 flex flex-wrap items-center gap-2 hidden">
                            <span class="text-xs font-medium text-gray-400 uppercase tracking-wide">Active Filters:</span>
                            <div id="activeFilterChips" class="flex flex-wrap gap-2"></div>
                        </div>
                    </div>
                    <!-- Right: Inline Filters -->
                    <div class="flex flex-col lg:flex-row lg:items-center gap-3 w-full lg:w-auto">
                        <!-- Search -->
                        <div class="relative w-full lg:w-56">
                            <input type="text" id="searchInput" placeholder="Search reports..." class="pill-input input-focus w-full bg-white">
                            <i class="fas fa-search absolute left-3 top-2.5 text-gray-400 text-sm"></i>
                        </div>
                        <!-- Status + Date Range + Buttons -->
                        <div class="flex flex-wrap items-center gap-2">
                            <select id="statusFilter" class="px-3 py-2 rounded-full border border-gray-200 text-sm input-focus bg-white">
                                <option value="all">All Status</option>
                                <option value="completed">Completed</option>
                                <option value="inprogress">In Progress</option>
                                <option value="pending">Pending</option>
                            </select>
                            <div class="flex items-center gap-1">
                                <input type="date" id="startDate" class="px-3 py-2 rounded-full border border-gray-200 text-xs input-focus bg-white">
                                <span class="text-xs text-gray-400">to</span>
                                <input type="date" id="endDate" class="px-3 py-2 rounded-full border border-gray-200 text-xs input-focus bg-white">
                            </div>
                            <button id="applyFilter" class="px-3 py-2 text-xs bg-indigo-600 text-white rounded-full hover:bg-indigo-700 smooth-transition font-medium">
                                Apply
                            </button>
                            <button id="resetFilter" class="px-3 py-2 text-xs bg-gray-100 text-gray-700 rounded-full border border-gray-200 hover:bg-gray-200 smooth-transition font-medium">
                                Clear
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tasks</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Time Spent</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Summary</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody id="reportsTableBody" class="bg-white divide-y divide-gray-200">
                        <!-- Reports will be populated here -->
                    </tbody>
                </table>
            </div>
            <div class="px-6 py-4 bg-gray-50 border-t border-gray-200 flex flex-col sm:flex-row sm:items-center sm:justify-between">
                <div class="text-sm text-gray-500 mb-2 sm:mb-0">
                    Showing <span class="font-medium" id="showingCount">0</span> of <span class="font-medium" id="totalCount">0</span> reports
                </div>
                <div class="flex space-x-2">
                    <button id="prevPage" class="px-3 py-2 text-sm bg-white border border-gray-300 rounded-full hover:bg-gray-50 font-medium smooth-transition">Previous</button>
                    <button id="nextPage" class="px-3 py-2 text-sm bg-white border border-gray-300 rounded-full hover:bg-gray-50 font-medium smooth-transition">Next</button>
                </div>
            </div>
        </div>
    </div>
    <!-- Create Report Page -->
    <div id="create-report" class="hidden animate-fade-in">
        <!-- Header -->
        <div class="mb-8">
            <div class="gradient-primary rounded-2xl p-8 text-white shadow-card">
                <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                    <div>
                        <h1 class="text-3xl font-bold mb-2">Create Daily Report</h1>
                        <p class="opacity-90 max-w-2xl">
                            Log your daily tasks, time spent and a concise summary in a clean, professional layout.
                        </p>
                    </div>
                    <div class="flex items-center space-x-4">
                        <button id="backToListFromCreate" class="text-white/80 hover:text-white smooth-transition font-medium flex items-center">
                            <i class="fas fa-arrow-left mr-2"></i> Back to Reports
                        </button>
                    </div>
                </div>
            </div>
        </div>
        <!-- Modern Form Layout -->
        <div class="bg-white/95 rounded-2xl shadow-card px-6 sm:px-10 py-8">
            <form id="createReportForm" class="space-y-8">
                <!-- Top: Date + Status + Preview -->
                <div class="grid gap-6 md:grid-cols-3">
                    <div class="md:col-span-1">
                        <div class="card-section p-4">
                            <label class="block text-sm font-semibold text-gray-800 mb-2">
                                Date <span class="text-red-500">*</span>
                            </label>
                            <input
                                type="date"
                                id="createDate"
                                class="w-full px-4 py-3 border border-gray-200 rounded-xl input-focus text-sm bg-white"
                            >
                            <p class="mt-1 text-xs text-gray-400">
                                Select the working day for this report.
                            </p>
                        </div>
                    </div>
                    <div class="md:col-span-1">
                        <div class="card-section p-4">
                            <label class="block text-sm font-semibold text-gray-800 mb-2">
                                Status <span class="text-red-500">*</span>
                            </label>
                            <select
                                id="createStatus"
                                class="w-full px-4 py-3 border border-gray-200 rounded-xl input-focus text-sm bg-white"
                            >
                                <option value="completed">Completed</option>
                                <option value="inprogress">In Progress</option>
                                <option value="pending">Pending</option>
                            </select>
                            <p class="mt-1 text-xs text-gray-400">
                                Overall completion status of the day.
                            </p>
                        </div>
                    </div>
                    <!-- Preview Card -->
                    <div class="md:col-span-1">
                        <div class="card-section p-4 bg-gradient-to-br from-gray-50 to-white">
                            <div class="flex items-center justify-between mb-3">
                                <h3 class="text-sm font-semibold text-gray-800">Quick Preview</h3>
                                <span class="text-[11px] uppercase tracking-wide text-gray-400">Live</span>
                            </div>
                            <div class="space-y-1.5 text-sm text-gray-700">
                                <div class="flex justify-between">
                                    <span class="text-gray-500">Date</span>
                                    <span id="previewDate" class="font-medium text-gray-900">-</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-500">Total Tasks</span>
                                    <span id="previewTasks" class="font-medium text-gray-900">0</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-500">Total Time</span>
                                    <span id="previewTime" class="font-medium text-gray-900">0h</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-500">Status</span>
                                    <span id="previewStatus" class="font-medium text-gray-900">-</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Middle: Tasks -->
                <div class="card-section p-5">
                    <div class="flex items-center justify-between mb-3">
                        <div>
                            <label class="block text-sm font-semibold text-gray-800">
                                Tasks <span class="text-red-500">*</span>
                            </label>
                            <p class="text-xs text-gray-400">
                                Add the key tasks you worked on and approximate time spent on each.
                            </p>
                        </div>
                        <button
                            type="button"
                            id="addTaskBtn"
                            class="text-sm text-indigo-600 hover:text-indigo-800 font-semibold flex items-center space-x-2 smooth-transition"
                        >
                            <i class="fas fa-plus-circle text-sm"></i>
                            <span>Add Task</span>
                        </button>
                    </div>
                    <div id="tasksContainer" class="space-y-4 border-t border-gray-100 pt-3">
                        <!-- Task fields added dynamically -->
                    </div>
                    <div id="tasksError" class="text-red-500 text-sm mt-2 hidden">
                        Please add at least one task
                    </div>
                </div>
                <!-- Bottom: Summary + Actions -->
                <div class="grid gap-6 md:grid-cols-3 items-start">
                    <div class="md:col-span-2">
                        <div class="card-section p-5">
                            <label class="block text-sm font-semibold text-gray-800 mb-2">
                                Summary <span class="text-red-500">*</span>
                            </label>
                            <textarea
                                rows="6"
                                id="createSummary"
                                class="w-full px-4 py-3 border border-gray-200 rounded-xl input-focus text-sm resize-none bg-white"
                                placeholder="Briefly summarize your main accomplishments, blockers, and important notes for tomorrow..."
                            ></textarea>
                            <p class="mt-1 text-xs text-gray-400">
                                Keep it concise and outcome-focused so your manager can quickly understand your day.
                            </p>
                        </div>
                    </div>
                    <div class="md:col-span-1">
                        <div class="h-full flex flex-col justify-between gap-4">
                            <div class="card-section p-5">
                                <h4 class="text-sm font-semibold text-gray-800 mb-2">
                                    Helpful tips
                                </h4>
                                <ul class="text-xs text-gray-500 space-y-1.5 list-disc list-inside">
                                    <li>Mention results, not just activities.</li>
                                    <li>Call out any blockers or dependencies.</li>
                                    <li>Add 1–2 focus items for tomorrow.</li>
                                </ul>
                            </div>
                            <div class="flex justify-end gap-3 pt-1">
                                <button
                                    type="button"
                                    id="cancelCreateBtn"
                                    class="px-6 py-3 border border-gray-300 rounded-full text-gray-700 hover:bg-gray-50 text-sm font-medium smooth-transition"
                                >
                                    Cancel
                                </button>
                                <button
                                    type="submit"
                                    class="px-8 py-3 rounded-full text-sm font-semibold gradient-primary text-white hover-lift smooth-transition shadow-soft flex items-center space-x-2"
                                >
                                    <i class="fas fa-paper-plane text-sm"></i>
                                    <span>Submit Report</span>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <!-- Edit Report Page -->
    <div id="edit-report" class="hidden animate-fade-in">
        <!-- Header -->
        <div class="mb-8">
            <div class="gradient-secondary rounded-2xl p-8 text-white shadow-card">
                <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                    <div>
                        <h1 class="text-3xl font-bold mb-2">Edit Daily Report</h1>
                        <p class="opacity-90 max-w-2xl">
                            Update your report for <span id="editReportDate">October 15, 2023</span>. Modify tasks, time spent, or summary as needed.
                        </p>
                    </div>
                    <div class="flex items-center space-x-4">
                        <button id="backToListFromEdit" class="text-white/80 hover:text-white smooth-transition font-medium flex items-center">
                            <i class="fas fa-arrow-left mr-2"></i> Back to Reports
                        </button>
                    </div>
                </div>
            </div>
        </div>
        <!-- Form -->
        <div class="bg-white rounded-2xl shadow-card p-8">
            <form id="editReportForm">
                <input type="hidden" id="editReportId">
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                    <div class="lg:col-span-2 space-y-6">
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                Date <span class="text-red-500">*</span>
                            </label>
                            <input
                                type="date"
                                id="editDate"
                                class="w-full px-4 py-3 border border-gray-200 rounded-xl input-focus smooth-transition"
                                required
                            >
                        </div>
                        <div>
                            <div class="flex justify-between items-center mb-4">
                                <label class="block text-sm font-semibold text-gray-700">
                                    Tasks <span class="text-red-500">*</span>
                                </label>
                                <button
                                    type="button"
                                    id="addTaskBtnEdit"
                                    class="text-sm text-indigo-600 hover:text-indigo-800 font-semibold flex items-center space-x-2 smooth-transition"
                                >
                                    <i class="fas fa-plus-circle"></i>
                                    <span>Add Task</span>
                                </button>
                            </div>
                          
                            <div id="tasksContainerEdit" class="space-y-4 border-t border-gray-100 pt-3">
                                <!-- Pre-filled task fields -->
                            </div>
                            <div id="tasksErrorEdit" class="text-red-500 text-sm mt-2 hidden">
                                Please add at least one task
                            </div>
                        </div>
                    </div>
                    <div class="space-y-6">
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                Summary <span class="text-red-500">*</span>
                            </label>
                            <textarea
                                rows="10"
                                id="editSummary"
                                class="w-full px-4 py-3 border border-gray-200 rounded-xl input-focus smooth-transition resize-none"
                                required
                            ></textarea>
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                Status <span class="text-red-500">*</span>
                            </label>
                            <select
                                id="editStatus"
                                class="w-full px-4 py-3 border border-gray-200 rounded-xl input-focus smooth-transition"
                                required
                            >
                                <option value="completed">Completed</option>
                                <option value="inprogress">In Progress</option>
                                <option value="pending">Pending</option>
                            </select>
                        </div>
                        <div class="rounded-xl border border-gray-200 bg-white px-4 py-3 text-sm text-gray-700">
                            <h4 class="font-semibold text-gray-800 mb-2">Report Preview</h4>
                            <div class="space-y-1">
                                <div class="flex justify-between">
                                    <span>Date:</span>
                                    <span id="previewDateEdit" class="font-medium">-</span>
                                </div>
                                <div class="flex justify-between">
                                    <span>Total Tasks:</span>
                                    <span id="previewTasksEdit" class="font-medium">0</span>
                                </div>
                                <div class="flex justify-between">
                                    <span>Total Time:</span>
                                    <span id="previewTimeEdit" class="font-medium">0h</span>
                                </div>
                                <div class="flex justify-between">
                                    <span>Status:</span>
                                    <span id="previewStatusEdit" class="font-medium">-</span>
                                </div>
                            </div>
                        </div>
                        <div class="pt-4">
                            <div class="flex justify-end space-x-4">
                                <button
                                    type="button"
                                    id="cancelEditBtn"
                                    class="px-6 py-3 border border-gray-300 rounded-full text-gray-700 hover:bg-gray-50 font-medium smooth-transition"
                                >
                                    Cancel
                                </button>
                                <button
                                    type="submit"
                                    class="gradient-success text-white text-xs px-8 py-3 rounded-full font-semibold flex items-center space-x-2 hover-lift smooth-transition shadow-soft"
                                >
                                    <i class="fas fa-save"></i>
                                    <span>Update Report</span>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <!-- Delete Confirmation Modal -->
    <div id="deleteConfirmModal" class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50 hidden">
        <div class="delete-modal-content fade-in">
            <div class="delete-modal-header">
                <div class="flex justify-between items-center">
                    <h3 class="text-xl font-bold">Delete Report</h3>
                    <button id="closeDeleteModal" class="text-white hover:text-gray-200 cursor-pointer">
                        <i class="fas fa-times text-xl"></i>
                    </button>
                </div>
            </div>
            <div class="delete-modal-body">
                <div class="w-20 h-20 mx-auto mb-6 rounded-full bg-red-100 flex items-center justify-center text-red-600">
                    <i class="fas fa-trash-alt text-3xl"></i>
                </div>
                <h4 class="text-lg font-semibold text-gray-800 mb-2">Are you sure?</h4>
                <p class="text-gray-600 mb-8">This action cannot be undone. This report will be permanently deleted.</p>
                <div class="flex space-x-4">
                    <button id="cancelDeleteBtn" class="flex-1 bg-gray-200 text-gray-800 py-3 rounded-lg font-medium hover:bg-gray-300 transition-all duration-300 cursor-pointer">
                        <i class="fas fa-times mr-2"></i> Cancel
                    </button>
                    <button id="confirmDeleteBtn" class="flex-1 bg-gradient-to-r from-red-600 to-red-700 text-white py-3 rounded-lg font-medium hover:from-red-700 hover:to-red-800 transition-all duration-300 shadow-sm hover:shadow-md cursor-pointer">
                        <i class="fas fa-trash mr-2"></i> Delete Report
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Notification Container -->
<div id="notificationContainer" class="fixed top-4 right-4 z-50 space-y-3"></div>

<!-- Styles -->
<style>
    * {
        font-family: 'Inter', sans-serif;
    }

    /* 100% RESPONSIVE - NO HORIZONTAL SCROLL ON ANY DEVICE */
    html, body {
        overflow-x: hidden !important;
        width: 100% !important;
        margin: 0 !important;
        padding: 0 !important;
    }
    * {
        box-sizing: border-box !important;
        max-width: 100% !important;
    }

    .gradient-primary {
        background: linear-gradient(135deg, #6366F1 0%, #8B5CF6 100%);
    }

    .gradient-secondary {
        background: linear-gradient(135deg, #EC4899 0%, #8B5CF6 100%);
    }

    .gradient-success {
        background: linear-gradient(135deg, #10B981 0%, #059669 100%);
    }

    .gradient-card {
        background: linear-gradient(135deg, #ffffff 0%, #f8fafc 100%);
    }

    .shadow-soft {
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05), 0 2px 4px -1px rgba(0, 0, 0, 0.03);
    }

    .shadow-card {
        box-shadow: 0 18px 45px -18px rgba(15, 23, 42, 0.18);
    }

    .animate-fade-in {
        animation: fadeIn 0.5s ease-out;
    }

    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(10px); }
        to { opacity: 1; transform: translateY(0); }
    }

    .task-item {
        transition: all 0.3s ease;
    }

    .status-badge {
        padding: 6px 12px;
        border-radius: 20px;
        font-size: 0.75rem;
        font-weight: 600;
    }

    .status-completed {
        background-color: #D1FAE5;
        color: #065F46;
    }

    .status-pending {
        background-color: #FEF3C7;
        color: #92400E;
    }

    .status-inprogress {
        background-color: #DBEAFE;
        color: #1E40AF;
    }

    .smooth-transition {
        transition: all 0.3s ease;
    }

    .hover-lift:hover {
        transform: translateY(-2px);
        box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1);
    }

    .input-focus:focus {
        box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.16);
        border-color: #6366F1;
        outline: none;
    }

    .progress-bar {
        height: 6px;
        border-radius: 3px;
        background: #E5E7EB;
        overflow: hidden;
    }

    .progress-fill {
        height: 100%;
        border-radius: 3px;
        background: linear-gradient(90deg, #6366F1, #8B5CF6);
        transition: width 0.5s ease;
    }

    .fade-out {
        animation: fadeOut 0.3s ease-out forwards;
    }

    @keyframes fadeOut {
        from { opacity: 1; transform: translateY(0); }
        to { opacity: 0; transform: translateY(-10px); }
    }
    .filter-chip {
        display: inline-flex;
        align-items: center;
        padding: 4px 10px;
        border-radius: 999px;
        font-size: 0.75rem;
        font-weight: 500;
        background: #EEF2FF;
        color: #3730A3;
        border: 1px solid #E0E7FF;
    }
    .filter-chip button {
        margin-left: 6px;
    }
    .filter-chip button i {
        font-size: 0.6rem;
    }
    .filter-chip:hover {
        background: #E0E7FF;
    }
    .pill-input {
        border-radius: 999px;
        border: 1px solid #E5E7EB;
        padding-left: 2.5rem;
        padding-right: 1rem;
        padding-top: 0.5rem;
        padding-bottom: 0.5rem;
        font-size: 0.875rem;
    }
    .pill-button {
        border-radius: 999px;
        padding: 0.55rem 1.2rem;
        border: 1px solid #E5E7EB;
        font-size: 0.875rem;
        font-weight: 500;
    }
    .card-section {
        border-radius: 1.25rem;
        border: 1px solid #E5E7EB;
        background: rgba(255,255,255,0.96);
    }
    .loading {
        opacity: 0.6;
        pointer-events: none;
    }
    /* Delete Modal Styles */
    .delete-modal-content {
        background: white;
        border-radius: 1rem;
        box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
        max-width: 28rem;
        width: 90%;
    }
    .delete-modal-header {
        background: linear-gradient(135deg, #dc2626 0%, #b91c1c 100%);
        padding: 1.5rem 2rem;
        border-top-left-radius: 1rem;
        border-top-right-radius: 1rem;
        color: white;
    }
    .delete-modal-body {
        padding: 2rem;
        text-align: center;
    }

    /* MOBILE RESPONSIVE FIXES */
    @media (max-width: 767px) {
        .stats-grid-mobile {
            grid-template-columns: 1fr 1fr !important;
        }
        .filter-bar-mobile {
            flex-direction: column;
            align-items: stretch;
        }
        .filter-group-mobile {
            width: 100%;
        }
        .filter-select-mobile {
            width: 100% !important;
        }
        .button-full-mobile {
            width: 100% !important;
            justify-content: center;
        }
    }
</style>

<script src="https://cdn.tailwindcss.com"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

<script>
    // Global variables
    let currentReports = [];
    let totalReports = 0;
    let totalPages = 1;
    let currentPage = 1;
    const reportsPerPage = 5;
    let currentFilters = {
        status: 'all',
        startDate: '',
        endDate: '',
        search: ''
    };
    let searchTimeout;
    // CSRF Token for AJAX requests
    const reportCsrfToken = document.querySelector('meta[name="csrf-token"]') ? document.querySelector('meta[name="csrf-token"]').getAttribute('content') : '';
    // Delete modal variables
    let reportIdToDelete = null;
    // Initialize the application
    document.addEventListener('DOMContentLoaded', function() {
        initializeApp();
    });
    function initializeApp() {
        setupEventListeners();
        loadReports();
        updateStatsCards();
        renderActiveFilters();
      
        const today = new Date().toISOString().split('T')[0];
        document.getElementById('createDate').value = today;
        updateCreatePreview();
      
        addTask('tasksContainer');
    }
    function setupEventListeners() {
        // Navigation
        document.getElementById('createReportBtn').addEventListener('click', () => showPage('create-report'));
        document.getElementById('backToListFromCreate').addEventListener('click', () => showPage('report-list'));
        document.getElementById('backToListFromEdit').addEventListener('click', () => showPage('report-list'));
        document.getElementById('cancelCreateBtn').addEventListener('click', () => showPage('report-list'));
        document.getElementById('cancelEditBtn').addEventListener('click', () => showPage('report-list'));
      
        // Add task buttons
        document.getElementById('addTaskBtn').addEventListener('click', () => addTask('tasksContainer'));
        document.getElementById('addTaskBtnEdit').addEventListener('click', () => addTask('tasksContainerEdit'));
      
        // Remove task functionality
        document.addEventListener('click', function(e) {
            if (e.target.classList.contains('remove-task') || e.target.closest('.remove-task')) {
                const taskItem = e.target.closest('.task-item');
                if (taskItem) {
                    taskItem.classList.add('fade-out');
                    setTimeout(() => {
                        taskItem.remove();
                        updatePreview();
                    }, 300);
                }
            }
        });
        // Filter chip remove
        document.addEventListener('click', function(e) {
            const chipBtn = e.target.closest('.filter-chip-remove');
            if (chipBtn) {
                const type = chipBtn.dataset.filter;
                clearFilter(type);
            }
        });
      
        // Form submissions
        document.getElementById('createReportForm').addEventListener('submit', handleCreateSubmit);
        document.getElementById('editReportForm').addEventListener('submit', handleEditSubmit);
      
        // Pagination
        document.getElementById('prevPage').addEventListener('click', goToPrevPage);
        document.getElementById('nextPage').addEventListener('click', goToNextPage);
      
        // Search with debounce
        document.getElementById('searchInput').addEventListener('input', handleSearch);
      
        // Filter controls (inline)
        document.getElementById('applyFilter').addEventListener('click', applyFilters);
        document.getElementById('resetFilter').addEventListener('click', resetFilters);
        document.getElementById('statusFilter').addEventListener('change', applyFilters);
        document.getElementById('startDate').addEventListener('change', applyFilters);
        document.getElementById('endDate').addEventListener('change', applyFilters);
      
        // Preview updates
        document.getElementById('createDate').addEventListener('change', updateCreatePreview);
        document.getElementById('editDate').addEventListener('change', updateEditPreview);
        document.getElementById('createStatus').addEventListener('change', updateCreatePreview);
        document.getElementById('editStatus').addEventListener('change', updateEditPreview);
      
        // Task input changes
        document.addEventListener('input', function(e) {
            if (e.target.matches('.task-title, .task-time, .task-unit')) {
                updatePreview();
            }
        });
        // Delete modal events
        document.getElementById('closeDeleteModal').addEventListener('click', closeDeleteModal);
        document.getElementById('cancelDeleteBtn').addEventListener('click', closeDeleteModal);
        document.getElementById('deleteConfirmModal').addEventListener('click', (e) => {
            if (e.target === document.getElementById('deleteConfirmModal')) closeDeleteModal();
        });
        document.getElementById('confirmDeleteBtn').addEventListener('click', confirmDeleteReport);
    }
    // Delete Modal Functions
    function openDeleteModal(reportId) {
        reportIdToDelete = reportId;
        document.getElementById('deleteConfirmModal').classList.remove('hidden');
        document.getElementById('deleteConfirmModal').classList.add('flex');
        document.body.style.overflow = 'hidden';
    }
    function closeDeleteModal() {
        document.getElementById('deleteConfirmModal').classList.add('hidden');
        document.getElementById('deleteConfirmModal').classList.remove('flex');
        document.body.style.overflow = 'auto';
        reportIdToDelete = null;
    }
    async function confirmDeleteReport() {
        if (!reportIdToDelete) return;
        try {
            await deleteReport(reportIdToDelete);
            showNotification('Report deleted successfully!', 'success');
            loadReports();
            updateStatsCards();
        } catch (error) {
            showNotification(error.message || 'Failed to delete report', 'error');
        } finally {
            closeDeleteModal();
        }
    }
    // Modified delete handler in table
    function deleteReportHandler(reportId) {
        openDeleteModal(reportId);
    }
    // API Functions
    async function fetchReports() {
        try {
            const params = new URLSearchParams();
            if (currentFilters.status !== 'all') params.append('status', currentFilters.status);
            if (currentFilters.startDate) params.append('start_date', currentFilters.startDate);
            if (currentFilters.endDate) params.append('end_date', currentFilters.endDate);
            if (currentFilters.search) params.append('search', currentFilters.search);
            params.append('per_page', reportsPerPage);
            params.append('page', currentPage);
            const response = await fetch(`/reports?${params}`, {
                headers: {
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                }
            });
            if (!response.ok) throw new Error('Failed to fetch reports');
          
            const data = await response.json();
            return {
                reports: data.reports || { data: [] },
                total: data.reports?.total || 0,
                lastPage: data.reports?.last_page || 1
            };
        } catch (error) {
            console.error('Error fetching reports:', error);
            showNotification('Failed to load reports', 'error');
            return { reports: { data: [] }, total: 0, lastPage: 1 };
        }
    }
    async function fetchStats() {
        try {
            const response = await fetch('/reports/stats', {
                headers: {
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                }
            });
            if (!response.ok) throw new Error('Failed to fetch stats');
          
            return await response.json();
        } catch (error) {
            console.error('Error fetching stats:', error);
            return {};
        }
    }
    async function createReport(reportData) {
        try {
            const response = await fetch('/reports', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': reportCsrfToken,
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: JSON.stringify(reportData)
            });
            const data = await response.json();
          
            if (!response.ok) {
                throw new Error(data.message || 'Failed to create report');
            }
          
            return data;
        } catch (error) {
            console.error('Error creating report:', error);
            throw error;
        }
    }
    async function updateReport(reportId, reportData) {
        try {
            const response = await fetch(`/reports/${reportId}`, {
                method: 'PUT',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': reportCsrfToken,
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: JSON.stringify(reportData)
            });
            const data = await response.json();
          
            if (!response.ok) {
                throw new Error(data.message || 'Failed to update report');
            }
          
            return data;
        } catch (error) {
            console.error('Error updating report:', error);
            throw error;
        }
    }
    async function deleteReport(reportId) {
        try {
            const response = await fetch(`/reports/${reportId}`, {
                method: 'DELETE',
                headers: {
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': reportCsrfToken,
                    'X-Requested-With': 'XMLHttpRequest'
                }
            });
            const data = await response.json();
          
            if (!response.ok) {
                throw new Error(data.message || 'Failed to delete report');
            }
          
            return data;
        } catch (error) {
            console.error('Error deleting report:', error);
            throw error;
        }
    }
    async function loadReports() {
        const responseData = await fetchReports();
        currentReports = responseData.reports.data || [];
        totalReports = responseData.total;
        totalPages = responseData.lastPage;
        renderReportsTable();
        updatePagination();
    }
    async function updateStatsCards() {
        const stats = await fetchStats();
      
        document.getElementById('totalReportsCount').textContent = stats.total_reports || 0;
        document.getElementById('weekReportsCount').textContent = stats.week_reports || 0;
        document.getElementById('avgTasksCount').textContent = stats.avg_tasks || 0;
        document.getElementById('productivityPercent').textContent = `${stats.productivity || 0}%`;
      
        const progressFill = document.querySelector('#productivityPercent').closest('.gradient-card').querySelector('.progress-fill');
        progressFill.style.width = `${stats.productivity || 0}%`;
    }
    function applyFilters() {
        const statusFilter = document.getElementById('statusFilter').value;
        const startDate = document.getElementById('startDate').value;
        const endDate = document.getElementById('endDate').value;
      
        currentFilters = {
            status: statusFilter,
            startDate: startDate,
            endDate: endDate,
            search: document.getElementById('searchInput').value
        };
      
        filterReports();
    }
    function resetFilters() {
        document.getElementById('statusFilter').value = 'all';
        document.getElementById('startDate').value = '';
        document.getElementById('endDate').value = '';
        document.getElementById('searchInput').value = '';
      
        currentFilters = {
            status: 'all',
            startDate: '',
            endDate: '',
            search: ''
        };
      
        filterReports();
        showNotification('All filters cleared successfully!', 'success');
    }
    function clearFilter(type) {
        if (type === 'status') {
            currentFilters.status = 'all';
            document.getElementById('statusFilter').value = 'all';
        } else if (type === 'date') {
            currentFilters.startDate = '';
            currentFilters.endDate = '';
            document.getElementById('startDate').value = '';
            document.getElementById('endDate').value = '';
        } else if (type === 'search') {
            currentFilters.search = '';
            document.getElementById('searchInput').value = '';
        }
        filterReports();
    }
    async function filterReports() {
        currentPage = 1;
        await loadReports();
        await updateStatsCards();
        renderActiveFilters();
    }
    function renderActiveFilters() {
        const bar = document.getElementById('activeFiltersBar');
        const chipsContainer = document.getElementById('activeFilterChips');
        chipsContainer.innerHTML = '';
        const chips = [];
        if (currentFilters.status !== 'all') {
            const statusText = getStatusText(currentFilters.status);
            chips.push({ type: 'status', label: `Status: ${statusText}` });
        }
        if (currentFilters.startDate || currentFilters.endDate) {
            let label = 'Date: ';
            if (currentFilters.startDate && currentFilters.endDate) {
                label += `${currentFilters.startDate} → ${currentFilters.endDate}`;
            } else if (currentFilters.startDate) {
                label += `From ${currentFilters.startDate}`;
            } else if (currentFilters.endDate) {
                label += `Until ${currentFilters.endDate}`;
            }
            chips.push({ type: 'date', label });
        }
        if (currentFilters.search) {
            chips.push({ type: 'search', label: `Search: ${currentFilters.search}` });
        }
        if (chips.length === 0) {
            bar.classList.add('hidden');
            return;
        }
        chips.forEach(chip => {
            const div = document.createElement('div');
            div.className = 'filter-chip';
            div.innerHTML = `
                <span>${chip.label}</span>
                <button type="button" class="filter-chip-remove" data-filter="${chip.type}">
                    <i class="fas fa-times"></i>
                </button>
            `;
            chipsContainer.appendChild(div);
        });
        bar.classList.remove('hidden');
    }
    function renderReportsTable() {
        const tableBody = document.getElementById('reportsTableBody');
        tableBody.innerHTML = '';
      
        if (currentReports.length === 0) {
            tableBody.innerHTML = `
                <tr>
                    <td colspan="6" class="px-6 py-8 text-center text-gray-500">
                        <i class="fas fa-inbox text-3xl mb-2 opacity-50"></i>
                        <p>No reports found</p>
                    </td>
                </tr>
            `;
        } else {
            currentReports.forEach(report => {
                const totalTime = report.tasks.reduce((sum, task) => {
                    return sum + (task.unit === 'minutes' ? parseFloat(task.time) / 60 : parseFloat(task.time));
                }, 0);
              
                const statusClass = report.status === 'completed' ? 'status-completed' :
                                  report.status === 'inprogress' ? 'status-inprogress' : 'status-pending';
                const statusText = getStatusText(report.status);
              
                const row = document.createElement('tr');
                row.className = 'hover:bg-gray-50 smooth-transition';
                row.innerHTML = `
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="text-sm font-semibold text-gray-900">${formatDate(report.date)}</div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="text-sm font-medium text-gray-900">${report.tasks.length} tasks</span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="text-sm text-gray-700">${totalTime.toFixed(1)}h</div>
                    </td>
                    <td class="px-6 py-4">
                        <div class="text-sm text-gray-700 max-w-xs truncate">${report.summary}</div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="status-badge ${statusClass}">${statusText}</span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                        <button onclick="editReport(${report.id})" class="text-indigo-600 hover:text-indigo-900 smooth-transition font-medium mr-4">Edit</button>
                        <button type="button" onclick="deleteReportHandler(${report.id})" class="text-red-600 hover:text-red-900 smooth-transition">
                            <i class="fas fa-trash"></i>
                        </button>
                    </td>
                `;
                tableBody.appendChild(row);
            });
        }
    }
    async function handleCreateSubmit(e) {
        e.preventDefault();
      
        const tasks = getTasksFromContainer('tasksContainer');
        if (tasks.length === 0) {
            document.getElementById('tasksError').classList.remove('hidden');
            return;
        }
      
        const reportData = {
            date: document.getElementById('createDate').value,
            tasks: tasks,
            summary: document.getElementById('createSummary').value,
            status: document.getElementById('createStatus').value
        };
      
        try {
            const submitBtn = e.target.querySelector('button[type="submit"]');
            submitBtn.classList.add('loading');
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i><span>Creating...</span>';
          
            await createReport(reportData);
            showNotification('Report created successfully!', 'success');
          
            setTimeout(() => {
                showPage('report-list');
                document.getElementById('createReportForm').reset();
                document.getElementById('tasksContainer').innerHTML = '';
                document.getElementById('tasksError').classList.add('hidden');
                addTask('tasksContainer');
              
                const today = new Date().toISOString().split('T')[0];
                document.getElementById('createDate').value = today;
                document.getElementById('createStatus').value = 'completed';
                updateCreatePreview();
              
                loadReports();
                updateStatsCards();
            }, 1200);
          
        } catch (error) {
            showNotification(error.message || 'Failed to create report', 'error');
        } finally {
            const submitBtn = e.target.querySelector('button[type="submit"]');
            submitBtn.classList.remove('loading');
            submitBtn.innerHTML = '<i class="fas fa-paper-plane text-sm"></i><span>Submit Report</span>';
        }
    }
    async function handleEditSubmit(e) {
        e.preventDefault();
      
        const tasks = getTasksFromContainer('tasksContainerEdit');
        if (tasks.length === 0) {
            document.getElementById('tasksErrorEdit').classList.remove('hidden');
            return;
        }
      
        const reportId = parseInt(document.getElementById('editReportId').value);
        const reportData = {
            date: document.getElementById('editDate').value,
            tasks: tasks,
            summary: document.getElementById('editSummary').value,
            status: document.getElementById('editStatus').value
        };
      
        try {
            const submitBtn = e.target.querySelector('button[type="submit"]');
            submitBtn.classList.add('loading');
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i><span>Updating...</span>';
          
            await updateReport(reportId, reportData);
            showNotification('Report updated successfully!', 'success');
          
            setTimeout(() => {
                showPage('report-list');
                loadReports();
                updateStatsCards();
            }, 1200);
          
        } catch (error) {
            showNotification(error.message || 'Failed to update report', 'error');
        } finally {
            const submitBtn = e.target.querySelector('button[type="submit"]');
            submitBtn.classList.remove('loading');
            submitBtn.innerHTML = '<i class="fas fa-save"></i><span>Update Report</span>';
        }
    }
    async function editReport(id) {
        try {
            const response = await fetch(`/reports/${id}`, {
                headers: {
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                }
            });
          
            if (!response.ok) throw new Error('Failed to fetch report');
          
            const data = await response.json();
            if (data.success) {
                loadEditForm(data.report);
                showPage('edit-report');
            }
        } catch (error) {
            console.error('Error fetching report:', error);
            showNotification('Failed to load report for editing', 'error');
        }
    }
    function showPage(pageId) {
        document.getElementById('report-list').classList.add('hidden');
        document.getElementById('create-report').classList.add('hidden');
        document.getElementById('edit-report').classList.add('hidden');
      
        document.getElementById(pageId).classList.remove('hidden');
        window.scrollTo({ top: 0, behavior: 'smooth' });
      
        if (pageId === 'report-list') {
            loadReports();
            updateStatsCards();
            renderActiveFilters();
        }
    }
    function addTask(containerId) {
        const container = document.getElementById(containerId);
      
        const taskHtml = `
            <div class="task-item animate-fade-in pt-2 border-t border-gray-100 first:pt-0 first:border-t-0">
                <div class="flex items-start gap-3">
                    <div class="flex-1 grid md:grid-cols-2 gap-3">
                        <input type="text" class="task-title w-full px-4 py-2.5 border border-gray-200 rounded-lg input-focus text-sm" placeholder="Task title" required>
                        <div class="flex gap-3">
                            <input type="number" step="0.5" min="0.5" class="task-time w-full px-4 py-2.5 border border-gray-200 rounded-lg input-focus text-sm" placeholder="Time spent" required>
                            <select class="task-unit w-32 px-3 py-2.5 border border-gray-200 rounded-lg input-focus text-sm">
                                <option value="hours">hours</option>
                                <option value="minutes">minutes</option>
                            </select>
                        </div>
                    </div>
                    <button type="button" class="remove-task text-gray-400 hover:text-red-500 smooth-transition mt-2">
                        <i class="fas fa-times text-sm"></i>
                    </button>
                </div>
            </div>
        `;
      
        container.insertAdjacentHTML('beforeend', taskHtml);
        updatePreview();
    }
    function getTasksFromContainer(containerId) {
        const tasks = [];
        const taskItems = document.querySelectorAll(`#${containerId} .task-item`);
      
        taskItems.forEach(item => {
            const titleInput = item.querySelector('.task-title');
            const timeInput = item.querySelector('.task-time');
            const unitSelect = item.querySelector('.task-unit');
          
            if (titleInput && titleInput.value && timeInput && timeInput.value) {
                tasks.push({
                    title: titleInput.value,
                    time: timeInput.value,
                    unit: unitSelect ? unitSelect.value : 'hours'
                });
            }
        });
      
        return tasks;
    }
    function loadEditForm(report) {
        document.getElementById('editReportId').value = report.id;
        document.getElementById('editDate').value = report.date;
        document.getElementById('editSummary').value = report.summary;
        document.getElementById('editStatus').value = report.status;
        document.getElementById('editReportDate').textContent = formatDate(report.date);
      
        const container = document.getElementById('tasksContainerEdit');
        container.innerHTML = '';
      
        report.tasks.forEach(task => {
            const taskHtml = `
                <div class="task-item animate-fade-in pt-2 border-t border-gray-100 first:pt-0 first:border-t-0">
                    <div class="flex items-start gap-3">
                        <div class="flex-1 grid md:grid-cols-2 gap-3">
                            <input type="text" class="task-title w-full px-4 py-2.5 border border-gray-200 rounded-lg input-focus text-sm" value="${task.title}" placeholder="Task title" required>
                            <div class="flex gap-3">
                                <input type="number" step="0.5" min="0.5" class="task-time w-full px-4 py-2.5 border border-gray-200 rounded-lg input-focus text-sm" value="${task.time}" placeholder="Time spent" required>
                                <select class="task-unit w-32 px-3 py-2.5 border border-gray-200 rounded-lg input-focus text-sm">
                                    <option value="hours" ${task.unit === 'hours' ? 'selected' : ''}>hours</option>
                                    <option value="minutes" ${task.unit === 'minutes' ? 'selected' : ''}>minutes</option>
                                </select>
                            </div>
                        </div>
                        <button type="button" class="remove-task text-gray-400 hover:text-red-500 smooth-transition mt-2">
                            <i class="fas fa-times text-sm"></i>
                        </button>
                    </div>
                </div>
            `;
            container.insertAdjacentHTML('beforeend', taskHtml);
        });
      
        updateEditPreview();
    }
    function updatePreview() {
        updateCreatePreview();
        updateEditPreview();
    }
    function updateCreatePreview() {
        const dateInput = document.getElementById('createDate');
        const statusInput = document.getElementById('createStatus');
        const tasks = document.querySelectorAll('#tasksContainer .task-item');
        let totalTime = 0;
      
        tasks.forEach(task => {
            const timeInput = task.querySelector('.task-time');
            const unitSelect = task.querySelector('.task-unit');
            if (timeInput && timeInput.value) {
                const time = parseFloat(timeInput.value) || 0;
                const unit = unitSelect ? unitSelect.value : 'hours';
                totalTime += unit === 'minutes' ? time / 60 : time;
            }
        });
      
        document.getElementById('previewDate').textContent = formatDate(dateInput.value);
        document.getElementById('previewTasks').textContent = tasks.length;
        document.getElementById('previewTime').textContent = totalTime.toFixed(1) + 'h';
        document.getElementById('previewStatus').textContent = getStatusText(statusInput.value);
    }
    function updateEditPreview() {
        const dateInput = document.getElementById('editDate');
        const statusInput = document.getElementById('editStatus');
        const tasks = document.querySelectorAll('#tasksContainerEdit .task-item');
        let totalTime = 0;
      
        tasks.forEach(task => {
            const timeInput = task.querySelector('.task-time');
            const unitSelect = task.querySelector('.task-unit');
            if (timeInput && timeInput.value) {
                const time = parseFloat(timeInput.value) || 0;
                const unit = unitSelect ? unitSelect.value : 'hours';
                totalTime += unit === 'minutes' ? time / 60 : time;
            }
        });
      
        document.getElementById('previewDateEdit').textContent = formatDate(dateInput.value);
        document.getElementById('previewTasksEdit').textContent = tasks.length;
        document.getElementById('previewTimeEdit').textContent = totalTime.toFixed(1) + 'h';
        document.getElementById('previewStatusEdit').textContent = getStatusText(statusInput.value);
    }
    function handleSearch(e) {
        clearTimeout(searchTimeout);
        searchTimeout = setTimeout(() => {
            currentFilters.search = e.target.value;
            filterReports();
        }, 300);
    }
    function goToPrevPage() {
        if (currentPage > 1) {
            currentPage--;
            loadReports();
        }
    }
    function goToNextPage() {
        if (currentPage < totalPages) {
            currentPage++;
            loadReports();
        }
    }
    function updatePagination() {
        const startItem = totalReports === 0 ? 0 : (currentPage - 1) * reportsPerPage + 1;
        const endItem = Math.min(currentPage * reportsPerPage, totalReports);
      
        document.getElementById('showingCount').textContent = totalReports === 0 ? '0' : `${startItem}-${endItem}`;
        document.getElementById('totalCount').textContent = totalReports;
      
        document.getElementById('prevPage').disabled = currentPage === 1;
        document.getElementById('nextPage').disabled = currentPage === totalPages || totalPages === 0;
    }
    function formatDate(dateString) {
        if (!dateString) return '-';
        const date = new Date(dateString);
        return date.toLocaleDateString('en-US', {
            weekday: 'short',
            year: 'numeric',
            month: 'short',
            day: 'numeric'
        });
    }
    function getStatusText(status) {
        switch(status) {
            case 'completed': return 'Completed';
            case 'inprogress': return 'In Progress';
            case 'pending': return 'Pending';
            default: return status;
        }
    }
    function showNotification(message, type) {
        const container = document.getElementById('notificationContainer');
        const id = Date.now();
      
        const bgColor = type === 'success' ? 'bg-green-500' :
                       type === 'warning' ? 'bg-yellow-500' : 'bg-red-500';
      
        const notification = document.createElement('div');
        notification.id = `notification-${id}`;
        notification.className = `${bgColor} text-white px-6 py-4 rounded-xl shadow-card max-w-sm animate-fade-in`;
        notification.innerHTML = `
            <div class="flex items-center justify-between">
                <div class="flex items-center space-x-3">
                    <i class="fas fa-${type === 'success' ? 'check' : type === 'warning' ? 'exclamation-triangle' : 'exclamation-circle'}"></i>
                    <span class="font-medium">${message}</span>
                </div>
                <button onclick="closeNotification(${id})" class="text-white/70 hover:text-white">
                    <i class="fas fa-times"></i>
                </button>
            </div>
        `;
      
        container.appendChild(notification);
      
        setTimeout(() => {
            closeNotification(id);
        }, 5000);
    }
    function closeNotification(id) {
        const notification = document.getElementById(`notification-${id}`);
        if (notification) {
            notification.style.opacity = '0';
            notification.style.transform = 'translateX(100%)';
            setTimeout(() => {
                notification.remove();
            }, 300);
        }
    }
</script>
@endsection