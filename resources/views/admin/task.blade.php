@extends('components.layout')
@section('content')
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        .sidebar-transition {
            transition: all 0.3s ease;
        }

        .task-card {
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }

        .task-card:hover {
            transform: translateY(-2px);
        }

        .gradient-bg {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }

        .priority-badge {
            padding: 0.25rem 0.75rem;
            border-radius: 9999px;
            font-size: 0.75rem;
            font-weight: 600;
        }

        .priority-low {
            background-color: #d1fae5;
            color: #065f46;
        }

        .priority-medium {
            background-color: #fef3c7;
            color: #92400e;
        }

        .priority-high {
            background-color: #fee2e2;
            color: #991b1b;
        }

        .status-pending {
            background-color: #fef3c7;
            color: #92400e;
        }

        .status-in-progress {
            background-color: #dbeafe;
            color: #1e40af;
        }

        .status-completed {
            background-color: #d1fae5;
            color: #065f46;
        }

        .drag-drop-area {
            border: 2px dashed #cbd5e1;
            transition: all 0.3s ease;
        }

        .drag-drop-area.dragover {
            border-color: #4f46e5;
            background-color: #f8fafc;
        }

        .fade-in {
            animation: fadeIn 0.3s ease-in-out;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(10px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .modal-overlay {
            background-color: rgba(0, 0, 0, 0.5);
            backdrop-filter: blur(4px);
        }

        .sortable-header {
            cursor: pointer;
            user-select: none;
        }

        .sortable-header:hover {
            background-color: #f3f4f6;
        }

        .sort-icon {
            margin-left: 0.5rem;
            opacity: 0.5;
        }

        .sort-icon.asc::after {
            content: " ↑";
            opacity: 1;
        }

        .sort-icon.desc::after {
            content: " ↓";
            opacity: 1;
        }

        /* Professional Form Styles */
        .form-label {
            display: block;
            font-size: 0.875rem;
            font-weight: 600;
            color: #374151;
            margin-bottom: 0.5rem;
        }

        .form-input {
            width: 100%;
            padding: 0.75rem 1rem;
            border: 1px solid #d1d5db;
            border-radius: 0.5rem;
            background-color: #fff;
            font-size: 0.875rem;
            transition: all 0.2s ease;
            box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.05);
        }

        .form-input:focus {
            border-color: #4f46e5;
            box-shadow: 0 0 0 3px rgba(79, 70, 229, 0.1);
            outline: none;
        }

        .form-input:hover:not(:focus) {
            border-color: #9ca3af;
        }

        .form-select {
            appearance: none;
            background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 20 20'%3e%3cpath stroke='%236b7280' stroke-linecap='round' stroke-linejoin='round' stroke-width='1.5' d='M6 8l4 4 4-4'/%3e%3c/svg%3e");
            background-position: right 0.75rem center;
            background-repeat: no-repeat;
            background-size: 1.5em 1.5em;
            cursor: pointer;
        }

        .required-field::after {
            content: "*";
            color: #ef4444;
            margin-left: 2px;
        }

        .priority-card {
            border: 1px solid #e5e7eb;
            border-radius: 0.5rem;
            transition: all 0.2s ease;
            cursor: pointer;
            padding: 0.75rem;
        }

        .priority-card:hover:not(.active) {
            transform: translateY(-2px);
            border-color: #c7d2fe;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
        }

        .priority-card.active {
            border-color: #4f46e5;
            background-color: #f5f3ff;
            box-shadow: 0 0 0 2px rgba(79, 70, 229, 0.1);
        }

        .user-avatar {
            width: 2rem;
            height: 2rem;
            border-radius: 9999px;
            background-color: #e5e7eb;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 0.875rem;
            font-weight: 600;
            color: #4b5563;
        }

        .form-section {
            margin-bottom: 1.5rem;
            padding-bottom: 1.25rem;
            border-bottom: 1px solid #f3f4f6;
        }

        .form-section:last-child {
            border-bottom: none;
        }

        .form-section-title {
            font-size: 1rem;
            font-weight: 700;
            color: #111827;
            margin-bottom: 1rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .form-section-title::before {
            content: "";
            display: block;
            width: 4px;
            height: 16px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border-radius: 2px;
        }

        /* User selection styles - Professional */
        .user-checkbox-card {
            border: 1px solid #e5e7eb;
            border-radius: 0.75rem;
            transition: all 0.2s ease;
            cursor: pointer;
            padding: 0.75rem;
            background: white;
            position: relative;
        }

        .user-checkbox-card:hover {
            border-color: #c7d2fe;
            background-color: #f8fafc;
            transform: translateY(-1px);
        }

        .user-checkbox-card.selected {
            border-color: #2e2c5a;
            background-color: #f5f3ff;
            box-shadow: 0 0 0 1px rgba(79, 70, 229, 0.2);
            color: #4f46e5;
        }


        .user-checkbox-card input[type="checkbox"] {
            position: absolute;
            opacity: 0;
            cursor: pointer;

        }

        .role-badge {
            font-size: 0.7rem;
            padding: 0.2rem 0.6rem;
            border-radius: 9999px;
            background: linear-gradient(135deg, #e0e7ff 0%, #ede9fe 100%);
            color: #4f46e5;
            font-weight: 500;
            border: 1px solid #ddd6fe;
        }

        .search-input {
            padding: 0.75rem 1rem 0.75rem 2.75rem;
            background-color: #f9fafb;
            border: 1px solid #e5e7eb;
        }

        .selected-users-badge {
            display: inline-flex;
            align-items: center;
            background: linear-gradient(135deg, #e0e7ff 0%, #ede9fe 100%);
            color: #4f46e5;
            padding: 0.375rem 0.875rem;
            border-radius: 9999px;
            font-size: 0.875rem;
            font-weight: 500;
            margin: 0.125rem;
            border: 1px solid #ddd6fe;
        }

        .selected-users-container {
            min-height: 3rem;
            border: 1px solid #e5e7eb;
            border-radius: 0.75rem;
            padding: 0.75rem;
            background-color: #f9fafb;
        }

        .user-list-container {
            max-height: 14rem;
            overflow-y: auto;
            border: 1px solid #e5e7eb;
            border-radius: 0.75rem;
            background: white;
        }

        .user-list-container::-webkit-scrollbar {
            width: 6px;
        }

        .user-list-container::-webkit-scrollbar-track {
            background: #f1f5f9;
            border-radius: 3px;
        }

        .user-list-container::-webkit-scrollbar-thumb {
            background: #cbd5e1;
            border-radius: 3px;
        }

        .user-list-container::-webkit-scrollbar-thumb:hover {
            background: #94a3b8;
        }

        .select-all-btn {
            border: 1px solid #d1d5db;
            border-radius: 0.5rem;
            padding: 0.5rem 1rem;
            font-size: 0.875rem;
            font-weight: 500;
            background: white;
            transition: all 0.2s ease;
        }

        .select-all-btn:hover {
            background-color: #f3f4f6;
            border-color: #9ca3af;
        }

        .user-counter {
            font-size: 0.875rem;
            color: #6b7280;
            font-weight: 500;
        }

        /* File upload styles */
        .file-item {
            background: #f9fafb;
            border: 1px solid #e5e7eb;
            border-radius: 0.75rem;
            padding: 1rem;
            transition: all 0.2s ease;
        }

        .file-item:hover {
            background: #f3f4f6;
            border-color: #d1d5db;
        }

        /* Assignment type toggle */
        .assignment-toggle {
            display: flex;
            gap: 0.5rem;
            padding: 0.5rem;
            background: #f9fafb;
            border-radius: 0.75rem;
            margin-bottom: 1.25rem;
        }

        .toggle-btn {
            flex: 1;
            padding: 0.75rem 1rem;
            border: 2px solid transparent;
            border-radius: 0.5rem;
            background: white;
            font-weight: 500;
            color: #6b7280;
            cursor: pointer;
            transition: all 0.2s ease;
            text-align: center;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
        }

        .toggle-btn:hover {
            background: #f3f4f6;
            color: #4b5563;
        }

        .toggle-btn.active {
            background: linear-gradient(135deg, #f5f3ff 0%, #e0e7ff 100%);
            border-color: #4f46e5;
            color: #4f46e5;
            box-shadow: 0 2px 4px rgba(79, 70, 229, 0.1);
        }

        /* Radio card styles */
        .radio-card {
            border: 1px solid #e5e7eb;
            border-radius: 0.75rem;
            padding: 1rem;
            cursor: pointer;
            transition: all 0.2s ease;
            background: white;
        }

        .radio-card:hover {
            border-color: #c7d2fe;
            background-color: #f8fafc;
        }

        .radio-card.selected {
            border-color: #4f46e5;
            background-color: #f5f3ff;
            box-shadow: 0 0 0 1px rgba(79, 70, 229, 0.2);
        }

        .radio-card input[type="radio"] {
            display: none;
        }

        .radio-indicator {
            width: 20px;
            height: 20px;
            border: 2px solid #d1d5db;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.2s ease;
            margin-right: 0.75rem;
            flex-shrink: 0;
        }

        .radio-card.selected .radio-indicator {
            border-color: #4f46e5;
            background-color: #4f46e5;
        }

        .radio-card.selected .radio-indicator::after {
            content: "";
            width: 8px;
            height: 8px;
            background: white;
            border-radius: 50%;
        }

        /* Improved modal styling */
        .modal-content {
            background: white;
            border-radius: 1rem;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
            overflow: hidden;
            max-width: 48rem;
        }

        .modal-header {
            background: white;
            padding: 1.5rem 2rem;
            color: black;
            border: 1rem;
            border-color: black;
        }

        .modal-body {
            padding: 2rem;
            max-height: calc(90vh - 140px);
            overflow-y: auto;
        }

        /* Add to your existing CSS */
        #customCategoryContainer {
            transition: all 0.3s ease;
            animation: fadeIn 0.3s ease;
            width: 100%;
        }

        #customCategoryContainer .space-y-3>* {
            width: 100%;
        }

        #customCategoryInput {
            width: 100%;
            box-sizing: border-box;
        }

        #customCategoryContainer .flex {
            width: 100%;
        }

        #customCategoryContainer button {
            flex: 1;
            min-width: 0;
            /* Prevents flex items from overflowing */
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(-10px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* Form grid improvements */
        .form-grid {
            display: grid;
            grid-template-columns: 1fr;
            gap: 1.5rem;
        }

        @media (min-width: 768px) {
            .form-grid-2 {
                grid-template-columns: repeat(2, 1fr);
            }

            .form-grid-3 {
                grid-template-columns: repeat(3, 1fr);
            }
        }

        /* Button styles */
        .btn-primary {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            font-weight: 600;
            padding: 0.875rem 1.5rem;
            border-radius: 0.75rem;
            transition: all 0.2s ease;
            border: none;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            box-shadow: 0 4px 6px -1px rgba(102, 126, 234, 0.2), 0 2px 4px -1px rgba(102, 126, 234, 0.1);
        }

        .btn-primary:hover {
            transform: translateY(-1px);
            box-shadow: 0 10px 15px -3px rgba(102, 126, 234, 0.2), 0 4px 6px -2px rgba(102, 126, 234, 0.1);
        }

        .btn-primary:active {
            transform: translateY(0);
        }

        .btn-secondary {
            background: white;
            color: #4b5563;
            font-weight: 600;
            padding: 0.875rem 1.5rem;
            border-radius: 0.75rem;
            transition: all 0.2s ease;
            border: 1px solid #d1d5db;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
        }

        .btn-secondary:hover {
            background: #f9fafb;
            border-color: #9ca3af;
        }

        /* Icon wrapper */
        .icon-wrapper {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 2rem;
            height: 2rem;
            border-radius: 0.5rem;
            background: #f5f3ff;
            color: #4f46e5;
        }

        .icon-wrapper-sm {
            width: 1.5rem;
            height: 1.5rem;
        }

        /* Input with icon */
        .input-with-icon {
            position: relative;
        }

        .input-icon {
            position: absolute;
            left: 1rem;
            top: 50%;
            transform: translateY(-50%);
            color: #9ca3af;
            pointer-events: none;
        }

        .input-with-icon input,
        .input-with-icon textarea,
        .input-with-icon select {
            padding-left: 3rem;
        }

        .input-with-icon textarea {
            padding-top: 0.875rem;
        }

        /* Status indicator */
        .status-indicator {
            width: 0.5rem;
            height: 0.5rem;
            border-radius: 50%;
            display: inline-block;
            margin-right: 0.5rem;
        }

        .status-indicator.pending {
            background-color: #f59e0b;
        }

        .status-indicator.in-progress {
            background-color: #3b82f6;
        }

        .status-indicator.completed {
            background-color: #10b981;
        }

        /* Validation highlight styles */
        @keyframes pulse-border {

            0%,
            100% {
                border-color: #f59e0b;
                box-shadow: 0 0 0 0 rgba(245, 158, 11, 0.4);
            }

            50% {
                border-color: #d97706;
                box-shadow: 0 0 0 4px rgba(245, 158, 11, 0.2);
            }
        }

        .animate-pulse {
            animation: pulse-border 1s ease-in-out infinite;
        }

        /* Validation modal specific styles */
        .validation-error-item {
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }

        .validation-error-item::before {
            content: '';
            position: absolute;
            left: 0;
            top: 0;
            height: 100%;
            width: 4px;
            background: linear-gradient(to bottom, #f59e0b, #d97706);
            border-radius: 2px;
        }

        .validation-error-item:hover {
            transform: translateX(4px);
            box-shadow: 0 4px 12px rgba(245, 158, 11, 0.15);
        }

        /* Date picker highlight */
        input[type="date"]:focus {
            border-color: #f59e0b;
            box-shadow: 0 0 0 3px rgba(245, 158, 11, 0.1);
        }

        /* Required field indicator */
        .form-input.required-highlight {
            border-color: #f59e0b;
            background-color: #fffbeb;
        }

        /* Assignment type toggle highlight */
        .toggle-btn.required-highlight {
            border-color: #f59e0b;
            background: linear-gradient(135deg, #fffbeb 0%, #fef3c7 100%);
        }

        /* Priority card highlight */
        .priority-card.required-highlight {
            border-color: #f59e0b;
            background: linear-gradient(135deg, #fffbeb 0%, #fef3c7 100%);
        }

        /* User list highlight */
        .user-list-container.required-highlight {
            border-color: #f59e0b;
            background: linear-gradient(135deg, #fffbeb 0%, #fef3c7 100%);
        }

        /* Select highlight */
        .form-select.required-highlight {
            border-color: #f59e0b;
            background-color: #fffbeb;
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

        /* Responsive improvements for mobile/tablet/desktop */
        @media (max-width: 767px) {
            .tasks-table thead {
                display: none;
            }

            .tasks-table tr {
                display: block;
                margin-bottom: 1rem;
                background: white;
                border: 1px solid #e5e7eb;
                border-radius: 0.75rem;
                padding: 1rem;
                box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
            }

            .tasks-table td {
                display: block;
                text-align: right;
                padding: 0.5rem 0;
                border: none;
            }

            .tasks-table td::before {
                content: attr(data-label);
                float: left;
                font-weight: 600;
                color: #374151;
                text-transform: capitalize;
            }

            .tasks-table td:last-child {
                border-bottom: 0;
            }

            .actions-mobile {
                display: flex !important;
                justify-content: flex-end;
                gap: 0.5rem;
            }
        }
    </style>
    </style>

    <div class="flex flex-col min-h-screen">
        <div class="flex flex-col min-h-screen">
            <main class="flex-1 p-4 sm:p-6 md:p-8 max-w-7xl mx-auto w-full">
                <div class="flex flex-col md:flex-row md:items-center justify-between mb-8 gap-4">
                    <div>
                        <h2 class="text-2xl font-bold text-gray-800">Task Management</h2>
                        <p class="text-gray-600 mt-1">Manage and track all your team tasks in one place</p>
                    </div>
                    <button id="addTaskBtn" class="btn-primary w-full md:w-auto">
                        <i class="fas fa-plus mr-2"></i>
                        Add New Task
                    </button>
                </div>
                <div class="grid grid-cols-2 sm:grid-cols-2 lg:grid-cols-4 gap-2 md:gap-6 mb-8">
                    <div class="bg-white rounded-xl shadow-sm p-2 md:p-6 border border-gray-200">
                        <div class="flex items-center">
                            <div
                                class="w-12 h-12 rounded-lg bg-gradient-to-r from-indigo-100 to-purple-100 flex items-center justify-center text-indigo-600 mr-4">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                    xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4">
                                    </path>
                                </svg>
                            </div>
                            <div>
                                <p class="text-sm text-gray-500">Total Tasks</p>
                                <p class="text-2xl font-bold text-gray-800">{{ $totalTasks }}</p>
                            </div>
                        </div>
                    </div>
                    <div class="bg-white rounded-xl shadow-sm p-2 md:p-6 border border-gray-200">
                        <div class="flex items-center">
                            <div
                                class="w-12 h-12 rounded-lg bg-gradient-to-r from-yellow-100 to-amber-100 flex items-center justify-center text-yellow-600 mr-4">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                    xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                            <div>
                                <p class="text-sm text-gray-500">Pending</p>
                                <p class="text-2xl font-bold text-gray-800">{{ $pending }}</p>
                            </div>
                        </div>
                    </div>
                    <div class="bg-white rounded-xl shadow-sm p-2 md:p-6 border border-gray-200">
                        <div class="flex items-center">
                            <div
                                class="w-12 h-12 rounded-lg bg-gradient-to-r from-blue-100 to-cyan-100 flex items-center justify-center text-blue-600 mr-4">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                    xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15">
                                    </path>
                                </svg>
                            </div>
                            <div>
                                <p class="text-sm text-gray-500">In Progress</p>
                                <p class="text-2xl font-bold text-gray-800">{{ $inProgress }}</p>
                            </div>
                        </div>
                    </div>
                    <div class="bg-white rounded-xl shadow-sm p-2 md:p-6 border border-gray-200">
                        <div class="flex items-center">
                            <div
                                class="w-12 h-12 rounded-lg bg-gradient-to-r from-green-100 to-emerald-100 flex items-center justify-center text-green-600 mr-4">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                    xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                            <div>
                                <p class="text-sm text-gray-500">Completed</p>
                                <p class="text-2xl font-bold text-gray-800">{{ $completed }}</p>
                            </div>
                        </div>
                    </div>
                </div>
                <section class="bg-white rounded-xl shadow-sm p-4 sm:p-6 border border-gray-200">
                    <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4 mb-6">

                        <!-- Title -->
                        <h2 class="text-xl sm:text-2xl font-bold text-gray-800 text-center lg:text-left">
                            Recent Tasks
                        </h2>

                        <!-- Filters -->
                        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-3 w-full lg:w-auto">

                            <select id="statusFilter" class="w-full px-4 py-2 border border-gray-300 rounded-lg 
                                       focus:ring-2 focus:ring-indigo-200 focus:border-indigo-500 
                                       text-sm bg-white">
                                <option>All Status</option>
                                <option>Pending</option>
                                <option>In Progress</option>
                                <option>Completed</option>
                            </select>

                            <select id="priorityFilter" class="w-full px-4 py-2 border border-gray-300 rounded-lg 
                                       focus:ring-2 focus:ring-indigo-200 focus:border-indigo-500 
                                       text-sm bg-white">
                                <option>All Priority</option>
                                <option>Low</option>
                                <option>Medium</option>
                                <option>High</option>
                            </select>

                            <select id="assigneeFilter" class="w-full px-4 py-2 border border-gray-300 rounded-lg 
                                       focus:ring-2 focus:ring-indigo-200 focus:border-indigo-500 
                                       text-sm bg-white">
                                <option value="">All Assignees</option>

                                <optgroup label="Users">
                                    @foreach($users as $user)
                                        <option value="user-{{ $user->id }}">{{ $user->name }}</option>
                                    @endforeach
                                </optgroup>

                                <optgroup label="Teams">
                                    @foreach($roles as $role)
                                        <option value="role-{{ $role->id }}">Team: {{ $role->name }}</option>
                                    @endforeach
                                </optgroup>
                            </select>

                        </div>
                    </div>


                    <div class="overflow-x-auto">
                        <table class="w-full tasks-table">
                            <thead class="bg-gray-50 hidden md:table-header-group">
                                <tr class="border-b border-gray-200">
                                    <th class="sortable-header py-3 px-4 text-left text-sm font-semibold text-gray-700"
                                        data-sort="title">
                                        Task Title <span class="sort-icon"></span>
                                    </th>
                                    <th class="py-3 px-4 text-left text-sm font-semibold text-gray-700">Assigned To</th>
                                    <th class="sortable-header py-3 px-4 text-left text-sm font-semibold text-gray-700"
                                        data-sort="priority">
                                        Priority <span class="sort-icon"></span>
                                    </th>
                                    <th class="sortable-header py-3 px-4 text-left text-sm font-semibold text-gray-700"
                                        data-sort="status">
                                        Status <span class="sort-icon"></span>
                                    </th>
                                    <th class="sortable-header py-3 px-4 text-left text-sm font-semibold text-gray-700"
                                        data-sort="due-date">
                                        Due Date <span class="sort-icon"></span>
                                    </th>
                                    <th class="py-3 px-4 text-left text-sm font-semibold text-gray-700">Assigned By</th>
                                    <th class="py-3 px-4 text-left text-sm font-semibold text-gray-700">Actions</th>
                                </tr>
                            </thead>
                            <tbody id="taskList">
                                @forelse($tasks as $task)
                                    @php
                                        $assigneeType = ($task->assigned_to_team && $task->role) ? 'team' : ($task->users->isNotEmpty() ? 'individual' : 'none');
                                        $assigneeId = ($task->assigned_to_team && $task->role) ? $task->role->id : ($task->users->isNotEmpty() ? $task->users->pluck('id')->implode(',') : '');
                                    @endphp
                                    <tr class="border-b border-gray-100 hover:bg-gray-50 task-card md:table-row"
                                        data-task-id="{{ $task->id }}" data-assignee-type="{{ $assigneeType }}"
                                        data-assignee-id="{{ $assigneeId }}">
                                        <td class="py-4 px-4" data-label="Task Title">
                                            <div class="font-medium text-gray-800">{{ $task->title }}</div>

                                            @if($task->category)
                                                <span class="text-xs text-gray-500 mt-1 block">{{ $task->category }}</span>
                                            @endif
                                        </td>

                                        <td class="py-4 px-4" data-label="Assigned To">
                                            <div class="flex items-center flex-wrap gap-2">
                                                @if($task->assigned_to_team && $task->role)
                                                    <span
                                                        class="bg-gradient-to-r from-blue-50 to-indigo-50 text-blue-700 px-3 py-1 rounded-full text-xs font-medium border border-blue-100">
                                                        <i class="fas fa-users mr-1"></i>
                                                        {{ $task->role->name }}
                                                    </span>
                                                @else
                                                    @foreach($task->users->take(3) as $user)
                                                        <div
                                                            class="w-8 h-8 rounded-full bg-gradient-to-r from-indigo-100 to-purple-100 flex items-center justify-center text-indigo-600 text-sm font-semibold">
                                                            {{ strtoupper(substr($user->name, 0, 1)) }}
                                                        </div>
                                                    @endforeach
                                                    @if($task->users->count() > 3)
                                                        <span
                                                            class="text-gray-500 text-sm font-medium">+{{ $task->users->count() - 3 }}</span>
                                                    @endif
                                                @endif
                                            </div>
                                        </td>

                                        <td class="py-4 px-4" data-label="Priority">
                                            <span class="priority-badge priority-{{ strtolower($task->priority) }}">
                                                <i class="fas fa-flag mr-1"></i>
                                                {{ $task->priority }}
                                            </span>
                                        </td>
                                        <td class="py-4 px-4" data-label="Status">
                                            <span
                                                class="priority-badge status-{{ strtolower(str_replace(' ', '-', $task->status)) }}">
                                                <span
                                                    class="status-indicator {{ strtolower(str_replace(' ', '-', $task->status)) }}"></span>
                                                {{ $task->status }}
                                            </span>
                                        </td>
                                        <td class="py-4 px-4" data-label="Due Date">
                                            <div class="flex items-center text-gray-700">
                                                <i class="fas fa-calendar-alt mr-2 text-gray-400 text-sm"></i>
                                                {{ $task->formatted_due_date ?? $task->due_date }}
                                            </div>
                                        </td>
                                        <td class="py-4 px-4" data-label="Assigned By">
                                            @if($task->assigner)
                                                <div class="flex items-center">
                                                    <div
                                                        class="w-8 h-8 rounded-full bg-gradient-to-r from-gray-100 to-gray-200 flex items-center justify-center text-gray-600 text-sm font-semibold mr-3">
                                                        {{ strtoupper(substr($task->assigner->name, 0, 1)) }}
                                                    </div>
                                                    <div>
                                                        <span
                                                            class="text-sm font-medium text-gray-700">{{ $task->assigner->name }}</span>
                                                        @if($task->assigner->roles->isNotEmpty())
                                                            <div class="text-xs text-gray-500">
                                                                {{ $task->assigner->roles->pluck('name')->implode(', ') }}
                                                            </div>
                                                        @endif
                                                    </div>
                                                </div>
                                            @else
                                                <span class="text-sm text-gray-500 italic">Unassigned</span>
                                            @endif
                                        </td>
                                        <td class="py-4 px-4" data-label="Actions">
                                            <div class="flex items-center space-x-2 actions-mobile hidden md:flex">
                                                <button
                                                    class="edit-task text-indigo-600 hover:text-indigo-800 p-2 hover:bg-indigo-50 rounded-lg"
                                                    data-task-id="{{ $task->id }}" title="Edit">
                                                    <i class="fas fa-edit"></i>
                                                </button>
                                                <button
                                                    class="view-task text-gray-500 hover:text-gray-700 p-2 hover:bg-gray-100 rounded-lg"
                                                    data-task-id="{{ $task->id }}" title="View">
                                                    <i class="fas fa-eye"></i>
                                                </button>
                                                <button
                                                    class="delete-task text-red-500 hover:text-red-700 p-2 hover:bg-red-50 rounded-lg"
                                                    data-task-id="{{ $task->id }}" title="Delete">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="py-16 text-center">
                                            <div
                                                class="w-24 h-24 mx-auto mb-6 rounded-full bg-gradient-to-r from-gray-100 to-gray-200 flex items-center justify-center text-gray-400">
                                                <i class="fas fa-tasks text-3xl"></i>
                                            </div>
                                            <h3 class="text-lg font-semibold text-gray-600">No tasks assigned yet</h3>
                                            <p class="text-gray-400 mt-2">Get started by creating your first task</p>
                                            <button id="addTaskBtnEmpty" class="mt-4 btn-primary">
                                                <i class="fas fa-plus mr-2"></i>
                                                Create First Task
                                            </button>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </section>
            </main>
        </div>


        <style>
            @media (max-width: 767px) {

                table thead {
                    display: none;
                }

                table,
                tbody,
                tr {
                    display: block;
                    width: 100%;
                }

                tr.task-card {
                    background: #ffffff;
                    border-radius: 14px;
                    padding: 16px;
                    margin-bottom: 16px;
                    box-shadow: 0 6px 20px rgba(0, 0, 0, 0.06);
                }

                td {
                    display: grid;
                    grid-template-columns: 120px 1fr;
                    gap: 12px;
                    padding: 8px 0;
                    border: none;
                    align-items: center;
                }

                td::before {
                    content: attr(data-label);
                    font-size: 12px;
                    font-weight: 600;
                    color: #64748b;
                    /* slate-500 */
                    text-transform: uppercase;
                    letter-spacing: 0.04em;
                }

                /* =======================
                           Task Title
                        ======================= */
                td[data-label="Task Title"] {
                    grid-template-columns: 1fr;
                    padding-bottom: 12px;
                    border-bottom: 1px solid #e5e7eb;
                }

                td[data-label="Task Title"]::before {
                    display: none;
                }

                td[data-label="Task Title"] .font-medium {
                    font-size: 16px;
                    font-weight: 700;
                    color: #0f172a;
                }

                /* =======================
                           Right column alignment (GLOBAL)
                        ======================= */
                td>div {
                    justify-self: end;
                    text-align: right;
                }

                /* =======================
                           Assigned To
                        ======================= */
                td[data-label="Assigned To"]>div {
                    display: flex;
                    justify-content: flex-end;
                }

                /* =======================
                           Priority & Status badges
                        ======================= */
                .priority-badge {
                    justify-self: end;
                    white-space: nowrap;
                }

                /* =======================
                           Due Date FIX (ICON + TEXT)
                        ======================= */
                td[data-label="Due Date"]>div {
                    display: inline-flex;
                    align-items: center;
                    justify-content: flex-end;
                    gap: 6px;
                    white-space: nowrap;
                }

                td[data-label="Due Date"] i {
                    font-size: 14px;
                    min-width: 14px;
                }

                /* =======================
                           Assigned By
                        ======================= */
                td[data-label="Assigned By"]>div {
                    display: flex;
                    justify-content: flex-end;
                    align-items: center;
                    text-align: right;
                    gap: 8px;
                }

                /* =======================
                           Actions
                        ======================= */
                td[data-label="Actions"] {
                    grid-template-columns: 1fr;
                    padding-top: 12px;
                }

                td[data-label="Actions"]::before {
                    display: none;
                }

                .actions-mobile {
                    display: flex !important;
                    justify-content: flex-end;
                    gap: 12px;
                }
            }
        </style>
        <!-- Task Assignment Modal -->
        <div id="taskModal" class="fixed inset-0 z-50 flex items-center justify-center modal-overlay hidden">
            <div class="modal-content max-w-5xl w-full mx-4 max-h-[90vh] overflow-hidden fade-in">
                <div class="modal-header">
                    <div class="flex justify-between items-center">
                        <div>
                            <h2 class="text-2xl font-bold text-black">Create New Task</h2>
                            <p class="text-black-100 text-sm mt-1">Fill in the details below to create and assign a new task
                            </p>
                        </div>
                        <button id="closeModal" class="text-red hover:text-indigo-600 transition-colors duration-600">
                            <i class="fas fa-times text-2xl"></i>
                        </button>
                    </div>
                </div>

                <form id="taskForm" class="modal-body" enctype="multipart/form-data">
                    @csrf
                    <div class="form-section">
                        <h3 class="form-section-title">Basic Information</h3>
                        <div class="form-grid form-grid-2 gap-6">
                            <div>
                                <label for="taskTitle" class="form-label required-field">Task Title</label>
                                <div class="input-with-icon">
                                    <i class="fas fa-heading input-icon"></i>
                                    <input type="text" id="taskTitle" name="title" class="form-input"
                                        placeholder="Enter task title" required>
                                </div>
                            </div>

                            <div>
                                <label for="taskCategory" class="form-label">Task Category</label>
                                <div class="space-y-3">
                                    <div class="flex space-x-3 items-center">
                                        <div class="input-with-icon flex-1">
                                            <i class="fas fa-tag input-icon"></i>
                                            <select id="taskCategory" name="category"
                                                class="form-input form-select cursor-pointer">
                                                <option value="">Select Category</option>
                                                <option value="SEO">SEO</option>
                                                <option value="Ads">Ads</option>
                                                <option value="Content">Content</option>
                                                <option value="Social Media">Social Media</option>
                                                <option value="Designing">Designing</option>
                                                <option value="Video Editing">Video Editing</option>
                                                <option value="Custom">(Enter your own)</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div id="customCategoryContainer" class="hidden">
                                        <div class="space-y-3">
                                            <div class="input-with-icon w-full">
                                                <i class="fas fa-plus input-icon"></i>
                                                <input type="text" id="customCategoryInput" class="form-input w-full"
                                                    placeholder="Enter custom category name..." maxlength="50">
                                            </div>
                                            <div class="flex space-x-3">
                                                <button type="button" id="saveCustomCategory"
                                                    class="px-4 py-3 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 flex items-center text-sm justify-center flex-1">
                                                    <i class="fas fa-check mr-2"></i>
                                                    Save Category
                                                </button>
                                                <button type="button" id="cancelCustomCategory"
                                                    class="px-4 py-3 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 flex items-center justify-center flex-1">
                                                    <i class="fas fa-times mr-2"></i>
                                                    Cancel
                                                </button>
                                            </div>
                                            <p class="text-xs text-gray-500 mt-2">This custom category will be available for
                                                future tasks</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="form-section">
                        <label for="taskDescription" class="form-label">Task Description</label>
                        <div class="input-with-icon">
                            <i class="fas fa-align-left input-icon" style="top: 1.125rem;"></i>
                            <textarea id="taskDescription" name="description" rows="4" class="form-input"
                                placeholder="Describe the task in detail..."></textarea>
                        </div>
                    </div>

                    <div class="form-section">
                        <h3 class="form-section-title">Assignment Type</h3>
                        <div class="assignment-toggle">
                            <button type="button" class="toggle-btn active" data-type="individual">
                                <i class="fas fa-user"></i>
                                Individual Users
                            </button>
                            <button type="button" class="toggle-btn" data-type="team">
                                <i class="fas fa-users"></i>
                                Whole Team
                            </button>
                        </div>
                        <input type="hidden" name="assigned_type" value="individual">

                        <div id="individualAssignment" class="mt-4">
                            <div class="flex justify-between items-center mb-4">
                                <label class="form-label required-field">Select Team Members</label>
                                <span id="selectedCount" class="user-counter">0 users selected</span>
                            </div>
                            <div class="mb-4">
                                <div class="input-with-icon">
                                    <i class="fas fa-search input-icon"></i>
                                    <input type="text" id="userSearch" class="form-input search-input"
                                        placeholder="Search users by name or role...">
                                </div>
                            </div>
                            <div id="selectedUsersContainer" class="mb-4 hidden">
                                <div class="selected-users-container">
                                    <div class="flex flex-wrap gap-2" id="selectedUsersBadges"></div>
                                </div>
                            </div>
                            <div class="user-list-container">
                                <div class="p-3 border-b border-gray-200 bg-gray-50 flex justify-between items-center">
                                    <span class="font-semibold text-gray-700 text-sm">Team Members</span>
                                    <div class="flex items-center space-x-2">
                                        <button type="button" id="clearSelectionBtn"
                                            class="text-xs text-gray-600 hover:text-gray-800 px-2 py-1 rounded border border-gray-300">
                                            Clear All
                                        </button>
                                        <button type="button" id="selectAllBtn"
                                            class="text-xs text-indigo-600 hover:text-indigo-800 px-2 py-1 rounded border border-gray-300">
                                            Select All
                                        </button>
                                    </div>
                                </div>
                                <div class="p-3 grid grid-cols-1 md:grid-cols-2 gap-2" id="userList">
                                    @foreach($users as $user)
                                        <div class="user-checkbox-card">
                                            <input type="checkbox" name="assigned_users[]" value="{{ $user->id }}"
                                                class="user-checkbox hidden" id="user-{{ $user->id }}">
                                            <div class="flex items-center justify-between">
                                                <div class="flex items-center space-x-3">
                                                    <div
                                                        class="w-8 h-8 rounded-full bg-gradient-to-r from-blue-100 to-indigo-100 flex items-center justify-center text-indigo-600 font-semibold text-sm">
                                                        {{ strtoupper(substr($user->name, 0, 1)) }}
                                                    </div>
                                                    <div>
                                                        <div class="font-medium text-gray-800 text-sm">{{ $user->name }}</div>
                                                        <div class="flex items-center space-x-1 mt-0.5">
                                                            @foreach($user->roles as $role)
                                                                <span class="role-badge">{{ $role->name }}</span>
                                                            @endforeach
                                                        </div>
                                                    </div>
                                                </div>
                                                <div
                                                    class="check-indicator w-5 h-5 border-2 border-gray-300 rounded flex items-center justify-center transition-all duration-200">
                                                    <i class="fas fa-check text-white text-xs"></i>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>

                        <div id="teamAssignment" style="display: none;" class="mt-4">
                            <div class="form-grid form-grid-2 gap-6">
                                <div>
                                    <label for="assignedRole" class="form-label required-field">Select Team Role</label>
                                    <div class="input-with-icon">
                                        <i class="fas fa-user-tag input-icon"></i>
                                        <select id="assignedRole" name="assigned_role"
                                            class="form-input form-select cursor-pointer">
                                            <option value="">Select Role</option>
                                            @foreach($roles as $role)
                                                <option value="{{ $role->id }}">{{ $role->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div>
                                    <label class="form-label">&nbsp;</label>
                                    <div class="mt-6">
                                        <button type="button" id="addRoleBtn"
                                            class="text-sm text-indigo-600 hover:text-indigo-800 flex items-center font-medium">
                                            <i class="fas fa-plus-circle mr-1.5"></i> Add New Role
                                        </button>
                                    </div>
                                </div>
                            </div>
                            <div id="newRoleInput" class="mt-3 hidden">
                                <div class="flex space-x-2">
                                    <input type="text" id="roleName" class="flex-1 form-input"
                                        placeholder="Enter new role name">
                                    <button type="button" id="saveNewRole" class="btn-primary px-4 py-2">
                                        Add
                                    </button>
                                    <button type="button" id="cancelNewRole" class="btn-secondary px-4 py-2">
                                        Cancel
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="form-section">
                        <h3 class="form-section-title">Task Settings</h3>
                        <div class="form-grid form-grid-3 gap-6">
                            <div>
                                <label class="form-label required-field">Priority Level</label>
                                <div class="space-y-2">
                                    <div class="priority-card" data-priority="low" id="priority-low">
                                        <div class="flex items-center">
                                            <div
                                                class="w-8 h-8 rounded-full bg-green-100 flex items-center justify-center text-green-600 mr-3">
                                                <i class="fas fa-arrow-down"></i>
                                            </div>
                                            <div>
                                                <div class="font-semibold text-gray-800 text-sm">Low</div>
                                                <div class="text-xs text-gray-500">Not urgent</div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="priority-card active" data-priority="medium" id="priority-medium">
                                        <div class="flex items-center">
                                            <div
                                                class="w-8 h-8 rounded-full bg-yellow-100 flex items-center justify-center text-yellow-600 mr-3">
                                                <i class="fas fa-equals"></i>
                                            </div>
                                            <div>
                                                <div class="font-semibold text-gray-800 text-sm">Medium</div>
                                                <div class="text-xs text-gray-500">Normal priority</div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="priority-card" data-priority="high" id="priority-high">
                                        <div class="flex items-center">
                                            <div
                                                class="w-8 h-8 rounded-full bg-red-100 flex items-center justify-center text-red-600 mr-3">
                                                <i class="fas fa-exclamation"></i>
                                            </div>
                                            <div>
                                                <div class="font-semibold text-gray-800 text-sm">High</div>
                                                <div class="text-xs text-gray-500">Urgent</div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <input type="hidden" id="priority" name="priority" value="Medium">
                            </div>

                            <div>
                                <label for="dueDate" class="form-label required-field">Due Date</label>
                                <div class="input-with-icon">
                                    <i class="fas fa-calendar-alt input-icon"></i>
                                    <input type="date" id="dueDate" name="due_date" class="form-input cursor-pointer">
                                </div>
                            </div>

                            <div>
                                <label for="taskStatus" class="form-label">Initial Status</label>
                                <div class="input-with-icon">
                                    <i class="fas fa-flag input-icon"></i>
                                    <select id="taskStatus" name="status" class="form-input form-select cursor-pointer">
                                        <option value="Pending">Pending</option>
                                        <option value="In Progress">In Progress</option>
                                        <option value="Completed">Completed</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="form-section">
                        <h3 class="form-section-title">Attachments</h3>
                        <div>
                            <div id="dragDropArea"
                                class="drag-drop-area rounded-xl p-8 text-center cursor-pointer transition-all duration-300 hover:border-indigo-400 hover:bg-indigo-50">
                                <div
                                    class="w-16 h-16 mx-auto mb-4 rounded-full bg-gradient-to-r from-indigo-100 to-purple-100 flex items-center justify-center text-indigo-600">
                                    <i class="fas fa-cloud-upload-alt text-2xl"></i>
                                </div>
                                <p class="text-lg font-semibold text-gray-700 mb-2">Drag & drop files here</p>
                                <p class="text-gray-500">or <span
                                        class="text-indigo-600 font-semibold cursor-pointer">browse</span> to upload</p>
                                <p class="text-xs text-gray-400 mt-3">Supports: PDF, JPG, PNG, DOC (Max 10MB each)</p>
                                <input type="file" id="fileInput" name="attachments[]" class="hidden" multiple>
                            </div>
                            <div id="fileList" class="mt-4 space-y-3"></div>
                        </div>
                    </div>

                    <div class="flex space-x-4 pt-8 border-t border-gray-200">
                        <button type="button" id="cancelTask" class="btn-secondary flex-1">
                            <i class="fas fa-times mr-2"></i>
                            Cancel
                        </button>
                        <button type="submit" class="btn-primary flex-1">
                            <i class="fas fa-paper-plane mr-2"></i>
                            Create Task
                        </button>
                    </div>
                </form>
            </div>
        </div>
        <!-- Delete Confirmation Popup Modal -->
        <div id="deleteConfirmModal" class="fixed inset-0 z-50 flex items-center justify-center modal-overlay hidden">
            <div class="delete-modal-content fade-in">
                <div class="delete-modal-header">
                    <div class="flex justify-between items-center">
                        <h3 class="text-xl font-bold">Delete Task</h3>
                        <button id="closeDeleteModal" class="text-white hover:text-gray-200">
                            <i class="fas fa-times text-xl"></i>
                        </button>
                    </div>
                </div>
                <div class="delete-modal-body">
                    <div
                        class="w-20 h-20 mx-auto mb-6 rounded-full bg-red-100 flex items-center justify-center text-red-600">
                        <i class="fas fa-trash-alt text-3xl"></i>
                    </div>
                    <h4 class="text-lg font-semibold text-gray-800 mb-2">Are you sure?</h4>
                    <p class="text-gray-600 mb-8">This action cannot be undone. This task will be permanently deleted.</p>
                    <div class="flex space-x-4">
                        <button id="cancelDeleteBtn" class="flex-1 btn-secondary py-3">
                            <i class="fas fa-times mr-2"></i> Cancel
                        </button>
                        <button id="confirmDeleteBtn" class="flex-1 btn-primary py-3"
                            style="background: linear-gradient(135deg, #dc2626 0%, #b91c1c 100%);">
                            <i class="fas fa-trash mr-2"></i> Delete Task
                        </button>
                    </div>
                </div>
            </div>
        </div>
        <script>
            const taskCsrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
            let selectedFiles = [];
            let selectedUsers = new Set();
            let allUserCheckboxes = [];
            let taskIdToDelete = null;
            const addTaskBtn = document.getElementById('addTaskBtn');
            const addTaskBtnEmpty = document.getElementById('addTaskBtnEmpty');
            const taskModal = document.getElementById('taskModal');
            const closeModal = document.getElementById('closeModal');
            const cancelTask = document.getElementById('cancelTask');
            const deleteConfirmModal = document.getElementById('deleteConfirmModal');
            const closeDeleteModal = document.getElementById('closeDeleteModal');
            const cancelDeleteBtn = document.getElementById('cancelDeleteBtn');
            const confirmDeleteBtn = document.getElementById('confirmDeleteBtn');
            function openModal() {
                taskModal.classList.remove('hidden');
                document.body.style.overflow = 'hidden';
                const today = new Date().toISOString().split('T')[0];
                document.getElementById('dueDate').value = today;
                document.getElementById('dueDate').min = today;
                initializeUserSelection();
                initializeCategoryFunctionality();
                setTimeout(() => {
                    document.getElementById('taskTitle').focus();
                    addRequiredFieldIndicators();
                }, 300);
            }
            function closeModalFunc() {
                taskModal.classList.add('hidden');
                document.body.style.overflow = 'auto';
                document.getElementById('taskForm').reset();
                selectedFiles = [];
                selectedUsers.clear();
                updateFileList();
                updateSelectedUsersDisplay();
                setPrioritySelection('medium');
                document.querySelector('[data-type="individual"]').classList.add('active');
                document.querySelector('[data-type="team"]').classList.remove('active');
                document.getElementById('individualAssignment').style.display = 'block';
                document.getElementById('teamAssignment').style.display = 'none';
                document.querySelector('input[name="assigned_type"]').value = 'individual';
                document.getElementById('newRoleInput').classList.add('hidden');
                document.getElementById('roleName').value = '';
                document.getElementById('userSearch').value = '';
                filterUsers();
                updateFileInput();
                resetCategorySelection();
                document.querySelectorAll('.required-highlight, .animate-pulse').forEach(el => {
                    el.classList.remove('required-highlight', 'animate-pulse', 'border-2', 'border-amber-400');
                });
            }
            function resetCategorySelection() {
                const categorySelect = document.getElementById('taskCategory');
                const customContainer = document.getElementById('customCategoryContainer');
                const customInput = document.getElementById('customCategoryInput');
                categorySelect.value = '';
                categorySelect.disabled = false;
                customContainer.classList.add('hidden');
                customInput.value = '';
            }
            addTaskBtn.addEventListener('click', openModal);
            if (addTaskBtnEmpty) addTaskBtnEmpty.addEventListener('click', openModal);
            closeModal.addEventListener('click', closeModalFunc);
            cancelTask.addEventListener('click', closeModalFunc);
            taskModal.addEventListener('click', e => { if (e.target === taskModal) closeModalFunc(); });
            // Delete Modal Functions
            function openDeleteModal(taskId) {
                taskIdToDelete = taskId;
                deleteConfirmModal.classList.remove('hidden');
                document.body.style.overflow = 'hidden';
            }
            function closeDeleteModalFunc() {
                deleteConfirmModal.classList.add('hidden');
                document.body.style.overflow = 'auto';
                taskIdToDelete = null;
            }
            closeDeleteModal.addEventListener('click', closeDeleteModalFunc);
            cancelDeleteBtn.addEventListener('click', closeDeleteModalFunc);
            deleteConfirmModal.addEventListener('click', e => { if (e.target === deleteConfirmModal) closeDeleteModalFunc(); });
            confirmDeleteBtn.addEventListener('click', function () {
                if (!taskIdToDelete) return;
                fetch(`/tasks/${taskIdToDelete}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': taskCsrfToken,
                        'Accept': 'application/json'
                    }
                })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            const row = document.querySelector(`tr[data-task-id="${taskIdToDelete}"]`);
                            if (row) row.remove();
                            showToast(data.message || 'Task deleted successfully!');
                            setTimeout(() => location.reload(), 500);
                        } else {
                            showToast('Failed to delete task.', 'error');
                        }
                    })
                    .catch(error => {
                        console.error('Delete Error:', error);
                        showToast('Error deleting task.', 'error');
                    })
                    .finally(() => closeDeleteModalFunc());
            });
            document.querySelectorAll('.toggle-btn').forEach(btn => {
                btn.addEventListener('click', function () {
                    const type = this.dataset.type;
                    document.querySelectorAll('.toggle-btn').forEach(b => b.classList.remove('active'));
                    this.classList.add('active');
                    document.getElementById('individualAssignment').style.display = type === 'individual' ? 'block' : 'none';
                    document.getElementById('teamAssignment').style.display = type === 'team' ? 'block' : 'none';
                    document.querySelector('input[name="assigned_type"]').value = type;
                    document.getElementById('newRoleInput').classList.add('hidden');
                    document.getElementById('roleName').value = '';
                });
            });
            function initializeUserSelection() {
                allUserCheckboxes = document.querySelectorAll('.user-checkbox');
                document.querySelectorAll('.user-checkbox-card').forEach(card => {
                    card.addEventListener('click', function (e) {
                        const checkbox = this.querySelector('.user-checkbox');
                        const userId = checkbox.value;
                        if (checkbox.checked) {
                            checkbox.checked = false;
                            selectedUsers.delete(userId);
                            this.classList.remove('selected');
                        } else {
                            checkbox.checked = true;
                            selectedUsers.add(userId);
                            this.classList.add('selected');
                        }
                        updateSelectedUsersDisplay();
                        updateSelectedCount();
                        updateSelectAllButton();
                    });
                });
                document.getElementById('selectAllBtn').addEventListener('click', function () {
                    const allSelected = selectedUsers.size === allUserCheckboxes.length;
                    allUserCheckboxes.forEach(checkbox => {
                        const card = checkbox.closest('.user-checkbox-card');
                        if (allSelected) {
                            checkbox.checked = false;
                            selectedUsers.delete(checkbox.value);
                            card.classList.remove('selected');
                        } else {
                            checkbox.checked = true;
                            selectedUsers.add(checkbox.value);
                            card.classList.add('selected');
                        }
                    });
                    updateSelectedUsersDisplay();
                    updateSelectedCount();
                    updateSelectAllButton();
                });
                document.getElementById('clearSelectionBtn').addEventListener('click', function () {
                    allUserCheckboxes.forEach(checkbox => {
                        checkbox.checked = false;
                        checkbox.closest('.user-checkbox-card').classList.remove('selected');
                    });
                    selectedUsers.clear();
                    updateSelectedUsersDisplay();
                    updateSelectedCount();
                    updateSelectAllButton();
                });
                document.getElementById('userSearch').addEventListener('input', filterUsers);
                allUserCheckboxes.forEach(checkbox => {
                    if (selectedUsers.has(checkbox.value)) {
                        checkbox.checked = true;
                        checkbox.closest('.user-checkbox-card').classList.add('selected');
                    }
                });
                updateSelectedCount();
                updateSelectAllButton();
            }
            function filterUsers() {
                const searchTerm = document.getElementById('userSearch').value.toLowerCase();
                document.querySelectorAll('.user-checkbox-card').forEach(card => {
                    const userName = card.querySelector('.font-medium').textContent.toLowerCase();
                    const roleText = Array.from(card.querySelectorAll('.role-badge')).map(r => r.textContent.toLowerCase()).join(' ');
                    card.style.display = (userName.includes(searchTerm) || roleText.includes(searchTerm) || searchTerm === '') ? 'block' : 'none';
                });
            }
            function updateSelectedUsersDisplay() {
                const container = document.getElementById('selectedUsersContainer');
                const badgesContainer = document.getElementById('selectedUsersBadges');
                badgesContainer.innerHTML = '';
                if (selectedUsers.size === 0) {
                    container.classList.add('hidden');
                    return;
                }
                container.classList.remove('hidden');
                allUserCheckboxes.forEach(checkbox => {
                    if (checkbox.checked) {
                        const card = checkbox.closest('.user-checkbox-card');
                        const userName = card.querySelector('.font-medium').textContent;
                        const userInitial = card.querySelector('.rounded-full').textContent.trim();
                        const badge = document.createElement('div');
                        badge.className = 'selected-users-badge';
                        badge.innerHTML = `
                                        <div class="w-6 h-6 rounded-full bg-gradient-to-r from-blue-100 to-indigo-100 flex items-center justify-center text-indigo-600 text-xs font-semibold mr-2">
                                            ${userInitial}
                                        </div>
                                        <span class="font-medium">${userName}</span>
                                        <button type="button" class="ml-2 text-indigo-400 hover:text-indigo-600" data-user-id="${checkbox.value}">
                                            <i class="fas fa-times text-xs"></i>
                                        </button>
                                    `;
                        badge.querySelector('button').addEventListener('click', e => {
                            e.stopPropagation();
                            const userId = badge.querySelector('button').getAttribute('data-user-id');
                            selectedUsers.delete(userId);
                            const userCheckbox = document.querySelector(`.user-checkbox[value="${userId}"]`);
                            if (userCheckbox) {
                                userCheckbox.checked = false;
                                userCheckbox.closest('.user-checkbox-card').classList.remove('selected');
                            }
                            updateSelectedUsersDisplay();
                            updateSelectedCount();
                            updateSelectAllButton();
                        });
                        badgesContainer.appendChild(badge);
                    }
                });
            }
            function updateSelectedCount() {
                document.getElementById('selectedCount').textContent = `${selectedUsers.size} user${selectedUsers.size !== 1 ? 's' : ''} selected`;
            }
            function updateSelectAllButton() {
                const button = document.getElementById('selectAllBtn');
                const clearButton = document.getElementById('clearSelectionBtn');
                const allSelected = selectedUsers.size === allUserCheckboxes.length;
                button.textContent = allSelected ? 'Deselect All' : 'Select All';
                clearButton.style.display = selectedUsers.size > 0 ? 'block' : 'none';
            }
            const priorityCards = document.querySelectorAll('.priority-card');
            const priorityInput = document.getElementById('priority');
            function setPrioritySelection(priority) {
                priorityCards.forEach(card => card.classList.remove('active'));
                const selectedCard = document.getElementById(`priority-${priority}`);
                if (selectedCard) selectedCard.classList.add('active');
                priorityInput.value = priority.charAt(0).toUpperCase() + priority.slice(1);
            }
            priorityCards.forEach(card => {
                card.addEventListener('click', () => setPrioritySelection(card.dataset.priority));
            });
            setPrioritySelection('medium');
            const dragDropArea = document.getElementById('dragDropArea');
            const fileInput = document.getElementById('fileInput');
            const fileList = document.getElementById('fileList');
            dragDropArea.addEventListener('click', () => fileInput.click());
            dragDropArea.addEventListener('dragover', e => { e.preventDefault(); dragDropArea.classList.add('dragover'); });
            dragDropArea.addEventListener('dragleave', () => dragDropArea.classList.remove('dragover'));
            dragDropArea.addEventListener('drop', e => { e.preventDefault(); dragDropArea.classList.remove('dragover'); handleFiles(e.dataTransfer.files); });
            fileInput.addEventListener('change', e => handleFiles(e.target.files));
            function handleFiles(newFiles) {
                for (let file of newFiles) {
                    if (file.size > 10 * 1024 * 1024) {
                        showToast(`File ${file.name} is too large (>10MB). Skipping.`, 'error');
                        continue;
                    }
                    const isDuplicate = selectedFiles.some(f => f.name === file.name && f.size === file.size);
                    if (isDuplicate) {
                        showToast(`File ${file.name} is already selected. Skipping.`, 'warning');
                        continue;
                    }
                    selectedFiles.push(file);
                }
                updateFileList();
                updateFileInput();
            }
            function updateFileList() {
                fileList.innerHTML = '';
                selectedFiles.forEach((file, index) => {
                    const fileItem = document.createElement('div');
                    fileItem.className = 'file-item';
                    fileItem.innerHTML = `
                                    <div class="flex items-center justify-between">
                                        <div class="flex items-center flex-1 min-w-0">
                                            <div class="w-12 h-12 rounded-lg bg-gradient-to-r from-indigo-100 to-purple-100 flex items-center justify-center text-indigo-600 mr-4">
                                                <i class="fas fa-file"></i>
                                            </div>
                                            <div class="min-w-0">
                                                <div class="font-semibold text-gray-800 truncate">${file.name}</div>
                                                <div class="text-sm text-gray-500">${Math.round(file.size / 1024)} KB</div>
                                            </div>
                                        </div>
                                        <button type="button" class="text-red-500 hover:text-red-700 ml-4 remove-file" data-index="${index}" title="Remove file">
                                            <i class="fas fa-times text-lg"></i>
                                        </button>
                                    </div>
                                `;
                    fileItem.querySelector('.remove-file').addEventListener('click', () => {
                        selectedFiles.splice(index, 1);
                        updateFileList();
                        updateFileInput();
                    });
                    fileList.appendChild(fileItem);
                });
            }
            function updateFileInput() {
                const dt = new DataTransfer();
                selectedFiles.forEach(file => dt.items.add(file));
                fileInput.files = dt.files;
            }
            document.getElementById('addRoleBtn')?.addEventListener('click', () => {
                document.getElementById('newRoleInput').classList.remove('hidden');
                document.getElementById('roleName').focus();
            });
            document.getElementById('cancelNewRole')?.addEventListener('click', () => {
                document.getElementById('newRoleInput').classList.add('hidden');
                document.getElementById('roleName').value = '';
            });
            document.getElementById('saveNewRole')?.addEventListener('click', () => {
                const roleName = document.getElementById('roleName').value.trim();
                if (!roleName) return showToast('Please enter a role name', 'error');
                const exists = Array.from(document.getElementById('assignedRole').options).some(o => o.text.toLowerCase() === roleName.toLowerCase());
                if (exists) return showToast('This role already exists!', 'warning');
                fetch('{{ route("roles.store") }}', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': taskCsrfToken, 'Accept': 'application/json' },
                    body: JSON.stringify({ name: roleName })
                })
                    .then(res => res.ok ? res.json() : res.json().then(err => { throw err; }))
                    .then(data => {
                        const select = document.getElementById('assignedRole');
                        const opt = document.createElement('option');
                        opt.value = data.role.id;
                        opt.textContent = data.role.name;
                        select.appendChild(opt);
                        select.value = data.role.id;
                        document.getElementById('newRoleInput').classList.add('hidden');
                        document.getElementById('roleName').value = '';
                        showToast('New role created successfully!');
                    })
                    .catch(err => {
                        showToast(err.message || 'Failed to create role.', 'error');
                    });
            });
            const taskForm = document.getElementById('taskForm');
            taskForm.addEventListener('submit', e => {
                e.preventDefault();
                const formData = new FormData(taskForm);
                formData.append('assigned_type', document.querySelector('input[name="assigned_type"]').value);
                const validationErrors = [];
                if (!document.getElementById('taskTitle').value.trim()) validationErrors.push({ field: 'taskTitle', message: 'Task title is required' });
                if (!priorityInput.value) validationErrors.push({ field: 'priority', message: 'Please select a priority level' });
                if (document.querySelector('input[name="assigned_type"]').value === 'individual' && selectedUsers.size === 0) validationErrors.push({ field: 'userList', message: 'Please select at least one team member' });
                if (document.querySelector('input[name="assigned_type"]').value === 'team' && !document.getElementById('assignedRole').value) validationErrors.push({ field: 'assignedRole', message: 'Please select a team role' });
                if (!document.getElementById('dueDate').value) validationErrors.push({ field: 'dueDate', message: 'Please select a due date' });
                if (validationErrors.length > 0) {
                    return;
                }
                const submitBtn = taskForm.querySelector('button[type="submit"]');
                const original = submitBtn.innerHTML;
                submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i> Creating...';
                submitBtn.disabled = true;
                fetch('{{ route("tasks.store") }}', {
                    method: 'POST',
                    body: formData,
                    headers: { 'X-CSRF-TOKEN': taskCsrfToken, 'Accept': 'application/json' }
                })
                    .then(res => res.ok ? res.json() : res.json().then(err => { throw err; }))
                    .then(data => {
                        if (data.success) {
                            closeModalFunc();
                            showToast('Task created successfully!');
                            setTimeout(() => location.reload(), 1000);
                        }
                    })
                    .catch(err => showToast(err.message || 'Failed to create task.', 'error'))
                    .finally(() => {
                        submitBtn.innerHTML = original;
                        submitBtn.disabled = false;
                    });
            });
            function showToast(message, type = 'success') {
                const toast = document.createElement('div');
                toast.className = `fixed bottom-4 right-4 px-6 py-3 rounded-lg shadow-xl z-50 fade-in flex items-center text-white max-w-md ${type === 'error' ? 'bg-gradient-to-r from-red-500 to-rose-600' : type === 'warning' ? 'bg-gradient-to-r from-yellow-500 to-amber-600' : 'bg-gradient-to-r from-green-500 to-emerald-600'}`;
                toast.innerHTML = `
                                <div class="w-8 h-8 rounded-full bg-white/20 flex items-center justify-center mr-3">
                                    <i class="${type === 'error' ? 'fas fa-exclamation-circle' : type === 'warning' ? 'fas fa-exclamation-triangle' : 'fas fa-check-circle'}"></i>
                                </div>
                                <div class="flex-1"><span class="font-medium">${message}</span></div>
                                <button class="ml-4 text-white/80 hover:text-white" onclick="this.parentElement.remove()"> <i class="fas fa-times"></i> </button>
                            `;
                document.body.appendChild(toast);
                setTimeout(() => toast.remove(), 5000);
            }
            document.addEventListener('click', e => {
                if (e.target.closest('.edit-task')) {
                    const taskId = e.target.closest('.edit-task').dataset.taskId;
                    window.location.href = `/tasks/${taskId}/edit`;
                }
                if (e.target.closest('.view-task')) {
                    const taskId = e.target.closest('.view-task').dataset.taskId;
                    window.location.href = `/tasks/${taskId}`;
                }
                if (e.target.closest('.delete-task')) {
                    const taskId = e.target.closest('.delete-task').dataset.taskId;
                    openDeleteModal(taskId);
                }
            });
            const statusFilter = document.getElementById('statusFilter');
            const priorityFilter = document.getElementById('priorityFilter');
            const assigneeFilter = document.getElementById('assigneeFilter');
            const tbody = document.getElementById('taskList');
            const dataRows = document.querySelectorAll('#taskList tr[data-task-id]');
            let currentSort = { column: null, ascending: true };
            function filterTable() {
                const selectedStatus = statusFilter.value;
                const selectedPriority = priorityFilter.value;
                const selectedAssignee = assigneeFilter.value;
                dataRows.forEach(row => {
                    const statusBadge = row.cells[3].querySelector('.priority-badge');
                    const priorityBadge = row.cells[2].querySelector('.priority-badge');
                    const rowStatus = statusBadge ? statusBadge.textContent.trim() : '';
                    const rowPriority = priorityBadge ? priorityBadge.textContent.trim() : '';
                    let show = true;
                    if (selectedStatus && selectedStatus !== 'All Status' && rowStatus !== selectedStatus) show = false;
                    if (selectedPriority && selectedPriority !== 'All Priority' && rowPriority !== selectedPriority) show = false;
                    if (selectedAssignee && selectedAssignee !== '') {
                        const [selType, selId] = selectedAssignee.split('-');
                        const rowType = row.dataset.assigneeType;
                        const rowIds = row.dataset.assigneeId ? row.dataset.assigneeId.split(',') : [];
                        let assigneeMatch = false;
                        if (selType === 'user' && rowType === 'individual') assigneeMatch = rowIds.includes(selId);
                        else if (selType === 'role' && rowType === 'team') assigneeMatch = rowIds.length > 0 && rowIds[0] === selId;
                        if (!assigneeMatch) show = false;
                    }
                    row.style.display = show ? '' : 'none';
                });
                const visibleRows = Array.from(dataRows).filter(row => row.style.display !== 'none');
                let emptyRow = tbody.querySelector('tr td[colspan="7"]');
                if (visibleRows.length === 0) {
                    if (!emptyRow) {
                        emptyRow = document.createElement('tr');
                        emptyRow.innerHTML = `
                                        <td colspan="7" class="py-16 text-center">
                                            <div class="w-24 h-24 mx-auto mb-6 rounded-full bg-gradient-to-r from-gray-100 to-gray-200 flex items-center justify-center text-gray-400">
                                                <i class="fas fa-tasks text-3xl"></i>
                                            </div>
                                            <h3 class="text-lg font-semibold text-gray-600">No tasks match the selected filters</h3>
                                            <p class="text-gray-400 mt-2">Adjust your filters to see tasks</p>
                                            <button id="clearFiltersBtn" class="mt-4 btn-secondary">
                                                <i class="fas fa-filter mr-2"></i>
                                                Clear All Filters
                                            </button>
                                        </td>
                                    `;
                        tbody.appendChild(emptyRow);
                        document.getElementById('clearFiltersBtn').addEventListener('click', function () {
                            statusFilter.value = 'All Status';
                            priorityFilter.value = 'All Priority';
                            assigneeFilter.value = '';
                            filterTable();
                        });
                    }
                } else {
                    if (emptyRow) emptyRow.remove();
                }
            }
            function sortTable(column, ascending) {
                const rowsArray = Array.from(dataRows).filter(row => row.style.display !== 'none');
                rowsArray.sort((a, b) => {
                    let aVal, bVal;
                    if (column === 'title') {
                        aVal = a.cells[0].textContent.trim().toLowerCase();
                        bVal = b.cells[0].textContent.trim().toLowerCase();
                    } else if (column === 'priority') {
                        const prioMap = { 'Low': 1, 'Medium': 2, 'High': 3 };
                        aVal = prioMap[a.cells[2].textContent.trim()] || 0;
                        bVal = prioMap[b.cells[2].textContent.trim()] || 0;
                    } else if (column === 'status') {
                        const statMap = { 'Pending': 1, 'In Progress': 2, 'Completed': 3 };
                        aVal = statMap[a.cells[3].textContent.trim()] || 0;
                        bVal = statMap[b.cells[3].textContent.trim()] || 0;
                    } else if (column === 'due-date') {
                        aVal = new Date(a.cells[4].textContent.trim());
                        bVal = new Date(b.cells[4].textContent.trim());
                        if (isNaN(aVal.getTime())) aVal = new Date(0);
                        if (isNaN(bVal.getTime())) bVal = new Date(0);
                    }
                    if (aVal < bVal) return ascending ? -1 : 1;
                    if (aVal > bVal) return ascending ? 1 : -1;
                    return 0;
                });
                rowsArray.forEach(row => tbody.appendChild(tbody.removeChild(row)));
            }
            statusFilter.addEventListener('change', filterTable);
            priorityFilter.addEventListener('change', filterTable);
            assigneeFilter.addEventListener('change', filterTable);
            document.querySelectorAll('.sortable-header').forEach(header => {
                header.addEventListener('click', function () {
                    const column = this.dataset.sort;
                    const ascending = currentSort.column === column ? !currentSort.ascending : true;
                    currentSort = { column, ascending };
                    document.querySelectorAll('.sort-icon').forEach(icon => icon.className = 'sort-icon');
                    this.querySelector('.sort-icon').className = `sort-icon ${ascending ? 'asc' : 'desc'}`;
                    sortTable(column, ascending);
                });
            });
            filterTable();

            function initializeCategoryFunctionality() {
                const categorySelect = document.getElementById('taskCategory');
                if (!categorySelect) return;

                categorySelect.addEventListener('change', function () {
                    if (this.value === 'Custom') {
                        document.getElementById('customCategoryContainer').classList.remove('hidden');
                    } else {
                        document.getElementById('customCategoryContainer').classList.add('hidden');
                    }
                });

                document.getElementById('saveCustomCategory')?.addEventListener('click', function () {
                    const customValue = document.getElementById('customCategoryInput').value.trim();
                    if (customValue) {
                        document.getElementById('taskCategory').value = customValue;
                        document.getElementById('customCategoryContainer').classList.add('hidden');
                    }
                });

                document.getElementById('cancelCustomCategory')?.addEventListener('click', function () {
                    document.getElementById('taskCategory').value = '';
                    document.getElementById('customCategoryContainer').classList.add('hidden');
                    document.getElementById('customCategoryInput').value = '';
                });
            }

            function addRequiredFieldIndicators() {
                const requiredInputs = document.querySelectorAll('input[required], select[required], textarea[required]');
                requiredInputs.forEach(input => {
                    const label = document.querySelector(`label[for="${input.id}"]`);
                    if (label && !label.classList.contains('required-field')) {
                        label.classList.add('required-field');
                    }
                });
            }
        </script>

@endsection