{{-- resources/views/todo/index.blade.php --}}
@extends('components.layout')
@section('content')
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Todo Management | HRMS/CRM</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: '#4f46e5',
                        secondary: '#64748b',
                        dark: '#0f172a',
                        orange: {
                            500: '#f97316',
                            600: '#ea580c'
                        }
                    }
                }
            }
        }
    </script>
    <style>
        .task-item {
            transition: all 0.2s ease;
            position: relative;
        }
        .task-item:hover {
            transform: translateY(-1px);
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
        }
        .priority-high {
            border-left: 4px solid #ef4444;
        }
        .priority-medium {
            border-left: 4px solid #f59e0b;
        }
        .priority-low {
            border-left: 4px solid #10b981;
        }
        .dragging {
            opacity: 0.7;
            transform: rotate(3deg);
        }
        .fade-in {
            animation: fadeIn 0.3s ease-in-out;
        }
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .category-projects { background-color: #e0f2fe; color: #0369a1; }
        .category-internal { background-color: #f3e8ff; color: #7c3aed; }
        .category-reminder { background-color: #fef3c7; color: #d97706; }
        .category-social { background-color: #dcfce7; color: #16a34a; }
        .status-onhold { background-color: #fef3c7; color: #d97706; }
        .status-inprogress { background-color: #dbeafe; color: #1d4ed8; }
        .status-completed { background-color: #dcfce7; color: #16a34a; }
        .status-pending { background-color: #fef3c7; color: #d97706; }
       
        .priority-filter.active {
            background-color: #4f46e5 !important;
            color: white !important;
        }
       
        .task-item.hidden {
            display: none !important;
        }
       
        .section-content {
            transition: opacity 0.3s ease;
        }
       
        @keyframes pulse {
            0%, 100% { opacity: 1; }
            50% { opacity: 0.5; }
        }
       
        .loading {
            animation: pulse 1.5s infinite;
        }
       
        .transition-all {
            transition-property: all;
            transition-timing-function: cubic-bezier(0.4, 0, 0.2, 1);
            transition-duration: 300ms;
        }
       
        .task-menu-dropdown {
            animation: fadeIn 0.15s ease-out;
        }
       
        input[type="checkbox"]:checked {
            background-color: #4f46e5;
            border-color: #4f46e5;
        }
       
        .task-menu.active {
            background-color: #e5e7eb;
            color: #4b5563;
        }
        /* Delete Confirmation Modal Styles */
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

        /* Fully responsive - removed all horizontal scrolling on mobile */
        .priority-filters-container {
            display: flex;
            flex-wrap: wrap;
            gap: 0.5rem;
        }
        
        @media (max-width: 767px) {
            .priority-filters-container > * {
                flex: 1 1 45%;
                min-width: 140px;
            }
            .task-item-mobile {
                flex-direction: column;
                align-items: flex-start !important;
                gap: 0.75rem;
            }
            .task-item-mobile > div:first-child,
            .task-item-mobile > div:nth-child(2),
            .task-item-mobile > div:nth-child(3) {
                order: -1;
            }
            .task-item-mobile .flex-1 {
                width: 100%;
            }
            .task-item-mobile .mr-4 {
                margin-right: 0 !important;
            }
            .task-item-actions {
                width: 100%;
                justify-content: space-between !important;
            }
            .drag-handle {
                display: none;
            }
        }
    </style>
</head>
<body class="bg-gray-50 min-h-screen">
    <div class="container mx-auto px-4 sm:px-6 py-6 max-w-7xl">
        <!-- Total Todo Panel -->
        <div class="bg-white rounded-xl shadow-sm p-4 sm:p-6 mb-6">
            <div class="flex flex-col md:flex-row md:items-center justify-between mb-4 gap-4">
                <div class="flex flex-col sm:flex-row sm:items-center sm:gap-6">
                    <div class="flex items-center gap-3">
                        <h2 class="text-xl sm:text-2xl font-bold text-gray-800">Todo Management</h2>
                        {{-- <button id="addTaskBtn" class="w-10 h-10 rounded-full bg-indigo-100 text-indigo-600 flex items-center justify-center hover:bg-indigo-200 transition-all duration-300 flex-shrink-0">
                            <i class="fas fa-plus text-base"></i>
                        </button> --}}
                    </div>
                    <div class="text-gray-600 text-sm sm:text-base mt-2 sm:mt-0">
                        <span class="font-medium">Total: <strong id="total-count">{{ $high->count() + $medium->count() + $low->count() }}</strong></span> |
                        <span class="text-orange-500 font-medium">Pending: <strong class="text-orange-600" id="pending-count">
                            {{
                                $high->where('completed', false)->count() +
                                $medium->where('completed', false)->count() +
                                $low->where('completed', false)->count()
                            }}
                        </strong></span> |
                        <span class="text-green-500 font-medium">Completed: <strong class="text-green-600" id="completed-count">
                            {{
                                $high->where('completed', true)->count() +
                                $medium->where('completed', true)->count() +
                                $low->where('completed', true)->count()
                            }}
                        </strong></span>
                    </div>
                </div>
                <div class="flex justify-center md:justify-end">
                    <button
                        id="newTaskBtn"
                        class="bg-indigo-600 text-white py-2.5 px-5 rounded-lg font-medium hover:bg-indigo-700 transition-all duration-300 flex items-center justify-center shadow-sm text-sm sm:text-base w-full"
                        >
                        <i class="fas fa-plus mr-2"></i>
                        New Todo
                        </button>
                </div>
            </div>

            <style>
                /* Phone screens only */
            @media (max-width: 640px) {

            /* Each filter row */
            .filters-row {
                display: flex;
                align-items: center;
                justify-content: space-between;
                width: 100%;
                gap: 8px;
            }

            /* Label (left side) */
            .filters-row span {
                font-size: 14px;
                color: #4b5563; /* gray-600 */
                white-space: nowrap;
            }

            /* Select (right side) */
            .filters-row select {
                width: 160px;
                padding: 8px 12px;
                font-size: 14px;
                border: 1px solid #d1d5db;
                border-radius: 8px;
                background-color: #ffffff;
            }

            }

            </style>
           
            <!-- Filters -->
            <div class="flex flex-wrap items-center gap-3 pt-4 border-t border-gray-200 flex-col sm:flex-row">
                <div class="flex flex-wrap items-center gap-3 w-full sm:w-auto">
                    <div class="flex items-center gap-2 justify-between filters-row">
                        <span class="text-sm text-gray-600 whitespace-nowrap">Due Date:</span>
                        <select id="dueDateFilter" class="text-sm border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-indigo-200 focus:border-indigo-500 bg-white cursor-pointer min-w-[120px]">
                            <option value="all">All Dates</option>
                            <option value="today">Today</option>
                            <option value="week">This Week</option>
                            <option value="month">This Month</option>
                        </select>
                    </div>
                   
                    <div class="flex items-center gap-2 filters-row">
                        <span class="text-sm text-gray-600 whitespace-nowrap">Category:</span>
                        <select id="tagFilter" class="text-sm border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-indigo-200 focus:border-indigo-500 bg-white cursor-pointer min-w-[120px]">
                            <option value="all">All Categories</option>
                            <option value="projects">Projects</option>
                            <option value="internal">Internal</option>
                            <option value="reminder">Reminder</option>
                            <option value="social">Social</option>
                        </select>
                    </div>
                   
                    <div class="flex items-center gap-2 filters-row">
                        <span class="text-sm text-gray-600 whitespace-nowrap">Status:</span>
                        <select id="statusFilter" class="text-sm border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-indigo-200 focus:border-indigo-500 bg-white cursor-pointer min-w-[120px]">
                            <option value="all">All Status</option>
                            <option value="pending">Pending</option>
                            <option value="inprogress">In Progress</option>
                            <option value="completed">Completed</option>
                            <option value="onhold">On Hold</option>
                        </select>
                    </div>
                   
                    <div class="flex items-center gap-2 filters-row">
                        <span class="text-sm text-gray-600 whitespace-nowrap">Sort By:</span>
                        <select id="sortFilter" class="text-sm border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-indigo-200 focus:border-indigo-500 bg-white cursor-pointer min-w-[120px]">
                            <option value="created">Created Date</option>
                            <option value="due">Due Date</option>
                            <option value="priority">Priority</option>
                            <option value="title">Title</option>
                        </select>
                    </div>
                </div>
               
                <button id="clearFilters" class="text-sm text-gray-600 hover:text-gray-800 bg-gray-200 hover:bg-gray-100 px-4 py-2 rounded-lg transition-all duration-300 flex items-center cursor-pointer whitespace-nowrap">
                    <i class="fas fa-times mr-1"></i> Clear Filters
                </button>
            </div>
        </div>

        <!-- Priority Filter Bar - Now wraps instead of scrolling -->
        <div class="priority-filters-container mb-6">
            <button id="filter-all" class="priority-filter active px-4 py-2 rounded-lg font-medium bg-indigo-600 text-white transition-all duration-300 whitespace-nowrap flex-shrink-0 cursor-pointer hover:bg-indigo-700">
                <i class="fas fa-layer-group mr-2"></i>All Tasks
            </button>
            <button id="filter-high" class="priority-filter px-4 py-2 rounded-lg font-medium bg-white text-gray-700 border border-gray-300 hover:bg-gray-50 transition-all duration-300 whitespace-nowrap flex-shrink-0 cursor-pointer hover:border-red-300 hover:text-red-700">
                <i class="fas fa-exclamation-circle mr-2 text-red-500"></i>High Priority
            </button>
            <button id="filter-medium" class="priority-filter px-4 py-2 rounded-lg font-medium bg-white text-gray-700 border border-gray-300 hover:bg-gray-50 transition-all duration-300 whitespace-nowrap flex-shrink-0 cursor-pointer hover:border-yellow-300 hover:text-yellow-700">
                <i class="fas fa-exclamation-triangle mr-2 text-yellow-500"></i>Medium Priority
            </button>
            <button id="filter-low" class="priority-filter px-4 py-2 rounded-lg font-medium bg-white text-gray-700 border border-gray-300 hover:bg-gray-50 transition-all duration-300 whitespace-nowrap flex-shrink-0 cursor-pointer hover:border-green-300 hover:text-green-700">
                <i class="fas fa-info-circle mr-2 text-green-500"></i>Low Priority
            </button>
        </div>

        <!-- Todo Groups -->
        <div class="space-y-6" id="todoGroups">
            <!-- High Priority Group -->
            <div class="bg-white rounded-xl shadow-sm overflow-hidden high-priority-section">
                <div class="flex items-center justify-between p-4 border-b border-gray-200 bg-red-50">
                    <div class="flex items-center">
                        <button class="toggle-section mr-3 text-gray-500 hover:text-gray-700 transition-all duration-300 cursor-pointer">
                            <i class="fas fa-chevron-down"></i>
                        </button>
                        <h3 class="text-lg font-semibold text-gray-800">High Priority</h3>
                        <span class="ml-2 bg-red-100 text-red-800 text-xs font-medium px-2.5 py-0.5 rounded-full" id="high-count">{{ $high->count() }}</span>
                    </div>
                    <div class="flex items-center">
                        <button class="add-task-section text-sm text-indigo-600 hover:text-indigo-800 font-medium flex items-center cursor-pointer" data-priority="high">
                            <i class="fas fa-plus mr-1"></i> Add New
                        </button>
                    </div>
                </div>
               
                <div class="section-content" id="high-priority-tasks">
                    @if($high->count() > 0)
                        @foreach($high as $task)
                        <div class="task-item priority-high p-4 border-b border-gray-100 bg-white flex items-center hover:bg-gray-50 task-item-mobile"
                             data-id="{{ $task->id }}"
                             data-priority="high"
                             data-category="{{ $task->category }}"
                             data-status="{{ $task->status }}"
                             data-due-date="{{ $task->due_date ?? '' }}"
                             data-completed="{{ $task->completed ? 'true' : 'false' }}"
                             data-created="{{ $task->created_at }}">
                            <div class="drag-handle mr-3 text-gray-400 cursor-move hover:text-gray-600 hidden sm:block">
                                <i class="fas fa-grip-vertical"></i>
                            </div>
                            <div class="flex items-center mr-3">
                                <input type="checkbox" class="task-checkbox h-5 w-5 text-indigo-600 border-gray-300 rounded focus:ring-indigo-500 cursor-pointer" {{ $task->completed ? 'checked' : '' }}>
                            </div>
                            <div class="flex items-center mr-3 text-yellow-400 cursor-pointer hover:text-yellow-500">
                                <i class="{{ $task->starred ? 'fas' : 'far' }} fa-star"></i>
                            </div>
                            <div class="flex-1">
                                <div class="font-medium text-gray-800 {{ $task->completed ? 'line-through text-gray-400' : '' }}">{{ $task->title }}</div>
                                @if($task->description)
                                <div class="text-sm text-gray-500 mt-1">{{ Str::limit($task->description, 60) }}</div>
                                @endif
                                <div class="flex flex-wrap items-center mt-2 gap-3 text-xs sm:text-sm">
                                    <div class="flex items-center text-gray-500">
                                        <i class="fas fa-calendar-alt mr-1.5"></i>
                                        <span class="due-date-text">{{ $task->due_date ? 'Due: ' . $task->due_date : 'No due date' }}</span>
                                    </div>
                                    <span class="font-medium px-2.5 py-1 rounded {{ 'category-'.$task->category }}">
                                        {{ ucfirst($task->category) }}
                                    </span>
                                    <span class="font-medium px-2.5 py-1 rounded {{ 'status-'.$task->status }}">
                                        {{ ucfirst($task->status) }}
                                    </span>
                                </div>
                            </div>
                            <div class="flex items-center mr-4 hidden sm:flex">
                                <div class="flex -space-x-2">
                                    <div class="w-8 h-8 rounded-full bg-indigo-100 border-2 border-white flex items-center justify-center text-indigo-600 text-xs font-bold shadow-sm">ME</div>
                                </div>
                            </div>
                            <div class="flex items-center task-item-actions">
                                <form id="delete-form-{{ $task->id }}" action="{{ route('todo.destroy', $task->id) }}" method="POST" class="hidden">
                                    @csrf
                                    @method('DELETE')
                                </form>
                                <button type="button" class="delete-task-btn text-red-500 hover:text-red-700 transition-colors duration-200 cursor-pointer">
                                    <i class="fas fa-trash"></i>
                                </button>
                                <button class="task-menu p-2 text-gray-400 hover:text-gray-600 rounded-lg hover:bg-gray-100 transition-all duration-200 cursor-pointer">
                                    <i class="fas fa-ellipsis-v"></i>
                                </button>
                            </div>
                        </div>
                        @endforeach
                    @else
                        <div class="p-8 text-center text-gray-500" id="high-empty-state">
                            <i class="fas fa-clipboard-list text-3xl mb-3 opacity-20"></i>
                            <p class="text-lg font-medium">No high priority tasks</p>
                            <p class="text-sm mt-1">Click "Add New" to create a high priority task</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Medium Priority Group -->
            <div class="bg-white rounded-xl shadow-sm overflow-hidden medium-priority-section">
                <div class="flex items-center justify-between p-4 border-b border-gray-200 bg-amber-50">
                    <div class="flex items-center">
                        <button class="toggle-section mr-3 text-gray-500 hover:text-gray-700 transition-all duration-300 cursor-pointer">
                            <i class="fas fa-chevron-down"></i>
                        </button>
                        <h3 class="text-lg font-semibold text-gray-800">Medium Priority</h3>
                        <span class="ml-2 bg-amber-100 text-amber-800 text-xs font-medium px-2.5 py-0.5 rounded-full" id="medium-count">{{ $medium->count() }}</span>
                    </div>
                    <div class="flex items-center">
                        <button class="add-task-section text-sm text-indigo-600 hover:text-indigo-800 font-medium flex items-center cursor-pointer" data-priority="medium">
                            <i class="fas fa-plus mr-1"></i> Add New
                        </button>
                    </div>
                </div>
               
                <div class="section-content" id="medium-priority-tasks">
                    @if($medium->count() > 0)
                        @foreach($medium as $task)
                        <div class="task-item priority-medium p-4 border-b border-gray-100 bg-white flex items-center hover:bg-gray-50 task-item-mobile"
                             data-id="{{ $task->id }}"
                             data-priority="medium"
                             data-category="{{ $task->category }}"
                             data-status="{{ $task->status }}"
                             data-due-date="{{ $task->due_date ?? '' }}"
                             data-completed="{{ $task->completed ? 'true' : 'false' }}"
                             data-created="{{ $task->created_at }}">
                            <div class="drag-handle mr-3 text-gray-400 cursor-move hover:text-gray-600 hidden sm:block">
                                <i class="fas fa-grip-vertical"></i>
                            </div>
                            <div class="flex items-center mr-3">
                                <input type="checkbox" class="task-checkbox h-5 w-5 text-indigo-600 border-gray-300 rounded focus:ring-indigo-500 cursor-pointer" {{ $task->completed ? 'checked' : '' }}>
                            </div>
                            <div class="flex items-center mr-3 text-yellow-400 cursor-pointer hover:text-yellow-500">
                                <i class="{{ $task->starred ? 'fas' : 'far' }} fa-star"></i>
                            </div>
                            <div class="flex-1">
                                <div class="font-medium text-gray-800 {{ $task->completed ? 'line-through text-gray-400' : '' }}">{{ $task->title }}</div>
                                @if($task->description)
                                <div class="text-sm text-gray-500 mt-1">{{ Str::limit($task->description, 60) }}</div>
                                @endif
                                <div class="flex flex-wrap items-center mt-2 gap-3 text-xs sm:text-sm">
                                    <div class="flex items-center text-gray-500">
                                        <i class="fas fa-calendar-alt mr-1.5"></i>
                                        <span class="due-date-text">{{ $task->due_date ? 'Due: ' . $task->due_date : 'No due date' }}</span>
                                    </div>
                                    <span class="font-medium px-2.5 py-1 rounded {{ 'category-'.$task->category }}">
                                        {{ ucfirst($task->category) }}
                                    </span>
                                    <span class="font-medium px-2.5 py-1 rounded {{ 'status-'.$task->status }}">
                                        {{ ucfirst($task->status) }}
                                    </span>
                                </div>
                            </div>
                            <div class="flex items-center mr-4 hidden sm:flex">
                                <div class="flex -space-x-2">
                                    <div class="w-8 h-8 rounded-full bg-indigo-100 border-2 border-white flex items-center justify-center text-indigo-600 text-xs font-bold shadow-sm">ME</div>
                                </div>
                            </div>
                            <div class="flex items-center task-item-actions">
                                <form id="delete-form-{{ $task->id }}" action="{{ route('todo.destroy', $task->id) }}" method="POST" class="hidden">
                                    @csrf
                                    @method('DELETE')
                                </form>
                                <button type="button" class="delete-task-btn text-red-500 hover:text-red-700 transition-colors duration-200 cursor-pointer">
                                    <!-- <i class="fas fa-trash"></i>    -->
                                </button>
                                <button class="task-menu p-2 text-gray-400 hover:text-gray-600 rounded-lg hover:bg-gray-100 transition-all duration-200 cursor-pointer">
                                    <i class="fas fa-ellipsis-v"></i>
                                </button>
                            </div>
                        </div>
                        @endforeach
                    @else
                        <div class="p-8 text-center text-gray-500" id="medium-empty-state">
                            <i class="fas fa-clipboard-list text-3xl mb-3 opacity-20"></i>
                            <p class="text-lg font-medium">No medium priority tasks</p>
                            <p class="text-sm mt-1">Click "Add New" to create a medium priority task</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Low Priority Group -->
            <div class="bg-white rounded-xl shadow-sm overflow-hidden low-priority-section">
                <div class="flex items-center justify-between p-4 border-b border-gray-200 bg-emerald-50">
                    <div class="flex items-center">
                        <button class="toggle-section mr-3 text-gray-500 hover:text-gray-700 transition-all duration-300 cursor-pointer">
                            <i class="fas fa-chevron-down"></i>
                        </button>
                        <h3 class="text-lg font-semibold text-gray-800">Low Priority</h3>
                        <span class="ml-2 bg-emerald-100 text-emerald-800 text-xs font-medium px-2.5 py-0.5 rounded-full" id="low-count">{{ $low->count() }}</span>
                    </div>
                    <div class="flex items-center">
                        <button class="add-task-section text-sm text-indigo-600 hover:text-indigo-800 font-medium flex items-center cursor-pointer" data-priority="low">
                            <i class="fas fa-plus mr-1"></i> Add New
                        </button>
                    </div>
                </div>
               
                <div class="section-content" id="low-priority-tasks">
                    @if($low->count() > 0)
                        @foreach($low as $task)
                        <div class="task-item priority-low p-4 border-b border-gray-100 bg-white flex items-center hover:bg-gray-50 task-item-mobile"
                             data-id="{{ $task->id }}"
                             data-priority="low"
                             data-category="{{ $task->category }}"
                             data-status="{{ $task->status }}"
                             data-due-date="{{ $task->due_date ?? '' }}"
                             data-completed="{{ $task->completed ? 'true' : 'false' }}"
                             data-created="{{ $task->created_at }}">
                            <div class="drag-handle mr-3 text-gray-400 cursor-move hover:text-gray-600 hidden sm:block">
                                <i class="fas fa-grip-vertical"></i>
                            </div>
                            <div class="flex items-center mr-3">
                                <input type="checkbox" class="task-checkbox h-5 w-5 text-indigo-600 border-gray-300 rounded focus:ring-indigo-500 cursor-pointer" {{ $task->completed ? 'checked' : '' }}>
                            </div>
                            <div class="flex items-center mr-3 text-yellow-400 cursor-pointer hover:text-yellow-500">
                                <i class="{{ $task->starred ? 'fas' : 'far' }} fa-star"></i>
                            </div>
                            <div class="flex-1">
                                <div class="font-medium text-gray-800 {{ $task->completed ? 'line-through text-gray-400' : '' }}">{{ $task->title }}</div>
                                @if($task->description)
                                <div class="text-sm text-gray-500 mt-1">{{ Str::limit($task->description, 60) }}</div>
                                @endif
                                <div class="flex flex-wrap items-center mt-2 gap-3 text-xs sm:text-sm">
                                    <div class="flex items-center text-gray-500">
                                        <i class="fas fa-calendar-alt mr-1.5"></i>
                                        <span class="due-date-text">{{ $task->due_date ? 'Due: ' . $task->due_date : 'No due date' }}</span>
                                    </div>
                                    <span class="font-medium px-2.5 py-1 rounded {{ 'category-'.$task->category }}">
                                        {{ ucfirst($task->category) }}
                                    </span>
                                    <span class="font-medium px-2.5 py-1 rounded {{ 'status-'.$task->status }}">
                                        {{ ucfirst($task->status) }}
                                    </span>
                                </div>
                            </div>
                            <div class="flex items-center mr-4 hidden sm:flex">
                                <div class="flex -space-x-2">
                                    <div class="w-8 h-8 rounded-full bg-indigo-100 border-2 border-white flex items-center justify-center text-indigo-600 text-xs font-bold shadow-sm">ME</div>
                                </div>
                            </div>
                            <div class="flex items-center task-item-actions">
                                <form id="delete-form-{{ $task->id }}" action="{{ route('todo.destroy', $task->id) }}" method="POST" class="hidden">
                                    @csrf
                                    @method('DELETE')
                                </form>
                                <button type="button" class="delete-task-btn text-red-500 hover:text-red-700 transition-colors duration-200 cursor-pointer">
                                    <i class="fas fa-trash"></i>
                                </button>
                                <button class="task-menu p-2 text-gray-400 hover:text-gray-600 rounded-lg hover:bg-gray-100 transition-all duration-200 cursor-pointer">
                                    <i class="fas fa-ellipsis-v"></i>
                                </button>
                            </div>
                        </div>
                        @endforeach
                    @else
                        <div class="p-8 text-center text-gray-500" id="low-empty-state">
                            <i class="fas fa-clipboard-list text-3xl mb-3 opacity-20"></i>
                            <p class="text-lg font-medium">No low priority tasks</p>
                            <p class="text-sm mt-1">Click "Add New" to create a low priority task</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Load More Button -->
        <div class="flex justify-center mt-8">
            <button id="loadMore" class="bg-indigo-600 text-white py-3 px-6 rounded-lg font-medium hover:bg-indigo-700 transition-all duration-300 shadow-sm hover:shadow-md flex items-center cursor-pointer">
                <i class="fas fa-plus-circle mr-2"></i>
                Load More Tasks
            </button>
        </div>
    </div>

    <!-- Add/Edit Task Modal -->
    <div id="taskModal" class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50 hidden">
        <div class="bg-white rounded-xl shadow-lg max-w-md w-full mx-4 fade-in max-h-[90vh] overflow-y-auto">
            <div class="p-6 border-b border-gray-200 flex justify-between items-center sticky top-0 bg-white z-10">
                <h2 class="text-xl font-bold text-gray-800" id="modalTitle">Add New Todo</h2>
                <button id="closeModal" class="text-gray-500 hover:text-gray-700 transition-colors duration-200 cursor-pointer">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>
           
            <form action="{{ route('todo.store') }}" method="POST" class="p-6 space-y-5" id="taskForm">
                @csrf
                <input type="hidden" id="taskId" name="id" value="">
                <input type="hidden" id="formAction" name="_method" value="">
               
                <div>
                    <label for="taskTitle" class="block text-sm font-medium text-gray-700 mb-1">Task Title *</label>
                    <input type="text" id="taskTitle" name="title" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-200 focus:border-indigo-500" placeholder="Enter task title" required>
                </div>
               
                <div>
                    <label for="taskDescription" class="block text-sm font-medium text-gray-700 mb-1">Description</label>
                    <textarea id="taskDescription" name="description" rows="4" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-200 focus:border-indigo-500 resize-none" placeholder="Enter task description (optional)"></textarea>
                </div>
               
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label for="taskPriority" class="block text-sm font-medium text-gray-700 mb-1">Priority *</label>
                        <select id="taskPriority" name="priority" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-200 focus:border-indigo-500 cursor-pointer" required>
                            <option value="high">High</option>
                            <option value="medium" selected>Medium</option>
                            <option value="low">Low</option>
                        </select>
                    </div>
                    <div>
                        <label for="taskDueDate" class="block text-sm font-medium text-gray-700 mb-1">Due Date</label>
                        <input type="date" id="taskDueDate" name="due_date" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-200 focus:border-indigo-500 cursor-pointer">
                    </div>
                </div>
               
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label for="taskCategory" class="block text-sm font-medium text-gray-700 mb-1">Category</label>
                        <select id="taskCategory" name="category" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-200 focus:border-indigo-500 cursor-pointer">
                            <option value="projects">Projects</option>
                            <option value="internal">Internal</option>
                            <option value="reminder">Reminder</option>
                            <option value="social">Social</option>
                        </select>
                    </div>
                    <div>
                        <label for="taskStatus" class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                        <select id="taskStatus" name="status" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-200 focus:border-indigo-500 cursor-pointer">
                            <option value="pending">Pending</option>
                            <option value="inprogress">In Progress</option>
                            <option value="completed">Completed</option>
                            <option value="onhold">On Hold</option>
                        </select>
                    </div>
                </div>
               
                <div class="flex flex-col sm:flex-row gap-4 pt-6">
                    <button type="button" id="cancelTask" class="flex-1 bg-gray-200 text-gray-800 py-3 rounded-lg font-medium hover:bg-gray-300 transition-all duration-300 cursor-pointer order-2 sm:order-1">
                        Cancel
                    </button>
                    <button type="submit" class="flex-1 bg-indigo-600 text-white py-3 rounded-lg font-medium hover:bg-indigo-700 transition-all duration-300 shadow-sm hover:shadow-md flex items-center justify-center cursor-pointer order-1 sm:order-2">
                        <i class="fas fa-save mr-2"></i>
                        Save Task
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Delete Confirmation Modal -->
    <div id="deleteConfirmModal" class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50 hidden">
        <div class="delete-modal-content fade-in">
            <div class="delete-modal-header">
                <div class="flex justify-between items-center">
                    <h3 class="text-xl font-bold">Delete Task</h3>
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
                <p class="text-gray-600 mb-8">This action cannot be undone. This task will be permanently deleted.</p>
                <div class="flex flex-col sm:flex-row gap-4">
                    <button id="cancelDeleteBtn" class="flex-1 bg-gray-200 text-gray-800 py-3 rounded-lg font-medium hover:bg-gray-300 transition-all duration-300 cursor-pointer">
                        <i class="fas fa-times mr-2"></i> Cancel
                    </button>
                    <button id="confirmDeleteBtn" class="flex-1 bg-gradient-to-r from-red-600 to-red-700 text-white py-3 rounded-lg font-medium hover:from-red-700 hover:to-red-800 transition-all duration-300 shadow-sm hover:shadow-md cursor-pointer">
                        <i class="fas fa-trash mr-2"></i> Delete Task
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Success Toast -->
    <div id="successToast" class="fixed bottom-4 right-4 bg-green-500 text-white px-6 py-3 rounded-lg shadow-lg hidden fade-in z-50 flex items-center max-w-sm">
        <i class="fas fa-check-circle mr-2"></i>
        <span id="toastMessage" class="flex-1">Operation completed successfully!</span>
        <button class="ml-4 text-green-100 hover:text-white cursor-pointer" onclick="hideToast()">
            <i class="fas fa-times"></i>
        </button>
    </div>

<script>
    const taskModal = document.getElementById('taskModal');
    const closeModal = document.getElementById('closeModal');
    const cancelTask = document.getElementById('cancelTask');
    const loadMoreBtn = document.getElementById('loadMore');
    const priorityFilters = document.querySelectorAll('.priority-filter');
    const toggleSections = document.querySelectorAll('.toggle-section');
    const newTaskBtn = document.getElementById('newTaskBtn');
    const addTaskBtn = document.getElementById('addTaskBtn');
    const addTaskSections = document.querySelectorAll('.add-task-section');
    const successToast = document.getElementById('successToast');
    const dueDateFilter = document.getElementById('dueDateFilter');
    const tagFilter = document.getElementById('tagFilter');
    const statusFilter = document.getElementById('statusFilter');
    const sortFilter = document.getElementById('sortFilter');
    const clearFilters = document.getElementById('clearFilters');
    const taskForm = document.getElementById('taskForm');
   
    const deleteConfirmModal = document.getElementById('deleteConfirmModal');
    const closeDeleteModal = document.getElementById('closeDeleteModal');
    const cancelDeleteBtn = document.getElementById('cancelDeleteBtn');
    const confirmDeleteBtn = document.getElementById('confirmDeleteBtn');
    let taskIdToDelete = null;
    let activeFilter = 'all';
    let currentFilters = {
        priority: 'all',
        dueDate: 'all',
        tag: 'all',
        status: 'all',
        sort: 'created'
    };
   
    let activeTaskMenu = null;
    let currentTaskMenuDropdown = null;
    let isMenuOpen = false;

    function initApp() {
        closeModal.addEventListener('click', closeTaskModal);
        cancelTask.addEventListener('click', closeTaskModal);
        loadMoreBtn.addEventListener('click', handleLoadMore);
       
        if (newTaskBtn) newTaskBtn.addEventListener('click', openTaskModal);
        if (addTaskBtn) addTaskBtn.addEventListener('click', openTaskModal);
       
        addTaskSections.forEach(button => {
            button.addEventListener('click', (e) => {
                const priority = e.currentTarget.dataset.priority;
                openTaskModal(priority);
            });
        });
       
        priorityFilters.forEach(filter => {
            filter.addEventListener('click', handlePriorityFilter);
        });
       
        toggleSections.forEach(toggle => {
            toggle.addEventListener('click', handleSectionToggle);
        });
       
        dueDateFilter.addEventListener('change', applyFilters);
        tagFilter.addEventListener('change', applyFilters);
        statusFilter.addEventListener('change', applyFilters);
        sortFilter.addEventListener('change', applyFilters);
        clearFilters.addEventListener('click', clearAllFilters);
       
        document.addEventListener('click', (e) => {
            if (e.target.closest('.task-menu')) {
                e.preventDefault();
                e.stopPropagation();
                handleTaskMenu(e);
            } else if (e.target.classList.contains('fa-star')) {
                handleStarClick(e);
            } else if (e.target.closest('.delete-task-btn')) {
                e.preventDefault();
                const taskId = e.target.closest('.delete-task-btn').dataset.taskId;
                openDeleteModal(taskId);
            }
        });
       
        document.addEventListener('click', (e) => {
            if (!e.target.closest('.task-menu') && !e.target.closest('.task-menu-dropdown') && isMenuOpen) {
                closeTaskMenu();
            }
        });
       
        document.addEventListener('change', (e) => {
            if (e.target.classList.contains('task-checkbox')) {
                handleCheckboxChange(e);
            }
        });
       
        closeDeleteModal.addEventListener('click', closeDeleteModalFunc);
        cancelDeleteBtn.addEventListener('click', closeDeleteModalFunc);
        deleteConfirmModal.addEventListener('click', (e) => {
            if (e.target === deleteConfirmModal) closeDeleteModalFunc();
        });
       
        confirmDeleteBtn.addEventListener('click', () => {
            if (!taskIdToDelete) return;
            const form = document.getElementById(`delete-form-${taskIdToDelete}`);
            if (form) form.submit();
            closeDeleteModalFunc();
            showToast('Task deleted successfully!');
        });
        setActiveFilter('all');
        updateTaskCounts();
    }

    function openDeleteModal(taskId) {
        taskIdToDelete = taskId;
        deleteConfirmModal.classList.remove('hidden');
        deleteConfirmModal.classList.add('flex');
        document.body.style.overflow = 'hidden';
    }

    function closeDeleteModalFunc() {
        deleteConfirmModal.classList.add('hidden');
        deleteConfirmModal.classList.remove('flex');
        document.body.style.overflow = 'auto';
        taskIdToDelete = null;
    }

    function handlePriorityFilter(e) {
        const filterButton = e.target.closest('.priority-filter');
        if (!filterButton) return;
        const filter = filterButton.id.replace('filter-', '');
        setActiveFilter(filter);
    }

    function setActiveFilter(filter) {
        activeFilter = filter;
        currentFilters.priority = filter;
       
        priorityFilters.forEach(btn => {
            const btnFilter = btn.id.replace('filter-', '');
            if (btnFilter === filter) {
                btn.classList.remove('bg-white', 'text-gray-700', 'border-gray-300');
                btn.classList.add('bg-indigo-600', 'text-white');
            } else {
                btn.classList.remove('bg-indigo-600', 'text-white');
                btn.classList.add('bg-white', 'text-gray-700', 'border-gray-300');
            }
        });
       
        applyFilters();
    }

    function applyFilters() {
        currentFilters.dueDate = dueDateFilter.value;
        currentFilters.tag = tagFilter.value;
        currentFilters.status = statusFilter.value;
        currentFilters.sort = sortFilter.value;
       
        const allTasks = document.querySelectorAll('.task-item');
       
        allTasks.forEach(task => task.style.display = 'flex');
       
        if (currentFilters.priority !== 'all') {
            allTasks.forEach(task => {
                if (task.dataset.priority !== currentFilters.priority) {
                    task.style.display = 'none';
                }
            });
        }
       
        if (currentFilters.dueDate !== 'all') {
            const today = new Date();
            today.setHours(0,0,0,0);
           
            allTasks.forEach(task => {
                if (task.style.display === 'none') return;
                const dueDate = task.dataset.dueDate;
                if (!dueDate) {
                    task.style.display = 'none';
                    return;
                }
                const taskDate = new Date(dueDate);
                taskDate.setHours(0,0,0,0);
                let show = false;
                if (currentFilters.dueDate === 'today') show = taskDate.getTime() === today.getTime();
                else if (currentFilters.dueDate === 'week') {
                    const weekLater = new Date(today);
                    weekLater.setDate(today.getDate() + 7);
                    show = taskDate >= today && taskDate <= weekLater;
                } else if (currentFilters.dueDate === 'month') {
                    const monthLater = new Date(today);
                    monthLater.setMonth(today.getMonth() + 1);
                    show = taskDate >= today && taskDate <= monthLater;
                }
                if (!show) task.style.display = 'none';
            });
        }
       
        if (currentFilters.tag !== 'all') {
            allTasks.forEach(task => {
                if (task.style.display === 'none') return;
                if (task.dataset.category !== currentFilters.tag) task.style.display = 'none';
            });
        }
       
        if (currentFilters.status !== 'all') {
            allTasks.forEach(task => {
                if (task.style.display === 'none') return;
                if (task.dataset.status !== currentFilters.status) task.style.display = 'none';
            });
        }
       
        sortTasks(currentFilters.sort);
        updateEmptyStates();
        updateTaskCounts();
    }

    function sortTasks(sortBy) {
        ['high', 'medium', 'low'].forEach(priority => {
            const section = document.getElementById(`${priority}-priority-tasks`);
            if (!section) return;
            const tasks = Array.from(section.querySelectorAll('.task-item'));
           
            tasks.sort((a, b) => {
                if (sortBy === 'due') {
                    const dateA = a.dataset.dueDate ? new Date(a.dataset.dueDate) : new Date('9999-12-31');
                    const dateB = b.dataset.dueDate ? new Date(b.dataset.dueDate) : new Date('9999-12-31');
                    return dateA - dateB;
                } else if (sortBy === 'title') {
                    return a.querySelector('.font-medium').textContent.localeCompare(b.querySelector('.font-medium').textContent);
                }
                return 0;
            });
           
            tasks.forEach(task => section.appendChild(task));
        });
    }

    function clearAllFilters() {
        setActiveFilter('all');
        dueDateFilter.value = 'all';
        tagFilter.value = 'all';
        statusFilter.value = 'all';
        sortFilter.value = 'created';
        applyFilters();
        showToast('All filters cleared');
    }

    function updateEmptyStates() {
        ['high', 'medium', 'low'].forEach(priority => {
            const section = document.getElementById(`${priority}-priority-tasks`);
            if (!section) return;
            const visible = section.querySelectorAll('.task-item[style*="display: flex"]').length;
            const empty = section.querySelector(`#${priority}-empty-state`);
            const container = section.closest(`.${priority}-priority-section`);
            if (visible === 0) {
                if (empty) empty.style.display = 'block';
                if (currentFilters.priority !== 'all' && currentFilters.priority !== priority) {
                    container.style.display = 'none';
                }
            } else {
                if (empty) empty.style.display = 'none';
                container.style.display = 'block';
            }
        });
    }

    function handleSectionToggle(e) {
        const toggle = e.target.closest('.toggle-section');
        if (!toggle) return;
        const content = toggle.closest('.bg-white').querySelector('.section-content');
        const icon = toggle.querySelector('i');
        if (content.style.display === 'none' || !content.style.display) {
            content.style.display = 'block';
            icon.className = 'fas fa-chevron-down';
        } else {
            content.style.display = 'none';
            icon.className = 'fas fa-chevron-right';
        }
    }

    function handleTaskMenu(e) {
        e.preventDefault();
        e.stopPropagation();
        const menuButton = e.target.closest('.task-menu');
        const taskItem = menuButton.closest('.task-item');
        if (activeTaskMenu === menuButton && isMenuOpen) {
            closeTaskMenu();
            return;
        }
        closeTaskMenu();
        menuButton.classList.add('active');
        activeTaskMenu = menuButton;
        isMenuOpen = true;
        const menu = document.createElement('div');
        menu.className = 'absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-lg border border-gray-200 task-menu-dropdown z-50';
        menu.innerHTML = `
            <div class="py-1">
                <button class="edit-task w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 flex items-center">
                    <i class="fas fa-edit mr-3 text-indigo-600"></i> Edit Task
                </button>
                <button class="hidden duplicate-task w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 flex items-center">
                    <i class="fas fa-copy mr-3 text-green-600"></i> Duplicate Task
                </button>
                <div class="border-t border-gray-200 my-1"></div>
                <button class="delete-task w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-red-50 flex items-center">
                    <i class="fas fa-trash mr-3"></i> Delete Task
                </button>
            </div>
        `;
        taskItem.appendChild(menu);
        currentTaskMenuDropdown = menu;
        menu.querySelector('.edit-task').addEventListener('click', (ev) => {
            ev.stopPropagation();
            openEditModal(taskItem);
            closeTaskMenu();
        });
        menu.querySelector('.duplicate-task').addEventListener('click', (ev) => {
            ev.stopPropagation();
            duplicateTask(taskItem.dataset.id);
            closeTaskMenu();
        });
        menu.querySelector('.delete-task').addEventListener('click', (ev) => {
            ev.stopPropagation();
            openDeleteModal(taskItem.dataset.id);
            closeTaskMenu();
        });
        menu.addEventListener('click', ev => ev.stopPropagation());
    }

    function closeTaskMenu() {
        if (currentTaskMenuDropdown) {
            currentTaskMenuDropdown.remove();
            currentTaskMenuDropdown = null;
        }
        if (activeTaskMenu) {
            activeTaskMenu.classList.remove('active');
            activeTaskMenu = null;
        }
        isMenuOpen = false;
    }

    function openEditModal(taskItem) {
        const title = taskItem.querySelector('.font-medium').textContent.trim();
        const descEl = taskItem.querySelector('.text-sm.text-gray-500');
        const description = descEl ? descEl.textContent.trim() : '';
        const dueText = taskItem.querySelector('.due-date-text').textContent;
        const dueDate = dueText.includes('Due:') ? dueText.replace('Due: ', '').trim() : '';
        const catSpan = taskItem.querySelector('[class*="category-"]');
        const category = catSpan ? catSpan.textContent.trim().toLowerCase() : 'projects';
        const statusSpan = taskItem.querySelector('[class*="status-"]');
        const statusClass = [...statusSpan.classList].find(c => c.startsWith('status-'));
        const status = statusClass ? statusClass.replace('status-', '') : 'pending';
        document.getElementById('modalTitle').textContent = 'Edit Task';
        document.getElementById('taskId').value = taskItem.dataset.id;
        document.getElementById('taskTitle').value = title;
        document.getElementById('taskDescription').value = description;
        document.getElementById('taskPriority').value = taskItem.dataset.priority;
        document.getElementById('taskDueDate').value = dueDate;
        document.getElementById('taskCategory').value = category;
        document.getElementById('taskStatus').value = status;
        taskForm.action = `/todo/${taskItem.dataset.id}`;
        document.getElementById('formAction').value = 'PUT';
        openTaskModal();
    }

    function handleCheckboxChange(e) {
        const taskItem = e.target.closest('.task-item');
        const title = taskItem.querySelector('.font-medium');
        if (e.target.checked) {
            title.classList.add('line-through', 'text-gray-400');
            taskItem.dataset.completed = 'true';
        } else {
            title.classList.remove('line-through', 'text-gray-400');
            taskItem.dataset.completed = 'false';
        }
        updateTaskCounts();
        showToast(`Task marked as ${e.target.checked ? 'completed' : 'pending'}`);
    }

    function handleStarClick(e) {
        const star = e.target;
        if (star.classList.contains('far')) {
            star.classList.replace('far', 'fas');
            star.classList.add('text-yellow-500');
            showToast('Task starred');
        } else {
            star.classList.replace('fas', 'far');
            star.classList.remove('text-yellow-500');
            showToast('Star removed');
        }
    }

    function handleLoadMore() {
        showToast('Load more functionality can be implemented with AJAX');
    }

    function duplicateTask(taskId) {
        const original = document.querySelector(`.task-item[data-id="${taskId}"]`);
        if (!original) return;
        const clone = original.cloneNode(true);
        clone.dataset.id = 'duplicate-' + Date.now();
        original.closest('.section-content').appendChild(clone);
        applyFilters();
        showToast('Task duplicated');
    }

    function openTaskModal(priority = null) {
        if (priority) document.getElementById('taskPriority').value = priority;
        taskModal.classList.remove('hidden');
        taskModal.classList.add('flex');
        document.body.style.overflow = 'hidden';
    }

    function closeTaskModal() {
        taskModal.classList.add('hidden');
        taskModal.classList.remove('flex');
        document.body.style.overflow = 'auto';
        taskForm.reset();
        document.getElementById('modalTitle').textContent = 'Add New Task';
        document.getElementById('taskId').value = '';
        document.getElementById('formAction').value = '';
        taskForm.action = '{{ route('todo.store') }}';
    }

    function updateTaskCounts() {
        const visible = document.querySelectorAll('.task-item[style*="display: flex"]');
        const total = visible.length;
        const completed = Array.from(visible).filter(t => t.dataset.completed === 'true').length;
       
        document.getElementById('total-count').textContent = total;
        document.getElementById('pending-count').textContent = total - completed;
        document.getElementById('completed-count').textContent = completed;
       
        ['high', 'medium', 'low'].forEach(p => {
            const el = document.getElementById(`${p}-count`);
            if (el) el.textContent = document.querySelectorAll(`.task-item.priority-${p}[style*="display: flex"]`).length;
        });
    }

    function showToast(message) {
        document.getElementById('toastMessage').textContent = message;
        successToast.classList.remove('hidden');
        setTimeout(() => successToast.classList.add('hidden'), 4000);
    }

    function hideToast() {
        successToast.classList.add('hidden');
    }

    document.addEventListener('DOMContentLoaded', initApp);
    document.addEventListener('keydown', (e) => {
        if (e.key === 'Escape') {
            if (!taskModal.classList.contains('hidden')) closeTaskModal();
            if (!deleteConfirmModal.classList.contains('hidden')) closeDeleteModalFunc();
            if (isMenuOpen) closeTaskMenu();
        }
    });
</script>
</body>
</html>
@endsection