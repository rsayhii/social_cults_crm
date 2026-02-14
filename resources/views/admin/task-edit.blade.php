@extends('components.layout')
@section('content')
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
        }

        .existing-attachment {
            @apply bg-blue-50 border border-blue-200 rounded p-3 mb-2 flex justify-between items-center;
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
            border-color: #4f46e5;
            background-color: #f5f3ff;
            box-shadow: 0 0 0 1px rgba(79, 70, 229, 0.2);
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

        /* Custom category styles */
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

        /* Task Assignment Modal Styles */
        .task-modal-container {
            max-width: 48rem;
            margin: 2rem auto;
            background: white;
            border-radius: 1rem;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
            overflow: hidden;
        }

        .task-modal-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            padding: 1.5rem 2rem;
            color: white;
        }

        .task-modal-body {
            padding: 2rem;
        }

        /* Assigned By Card */
        .assigner-card {
            background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
            border: 1px solid #e2e8f0;
            border-radius: 0.75rem;
            padding: 1.5rem;
            margin-bottom: 2rem;
        }
    </style>

    <!-- Task Edit Modal (styled like Task Assignment Modal) -->
    <div class="min-h-screen bg-gray-50 p-4">
        <div class="task-modal-container fade-in">
            <!-- Header -->
            <div class="task-modal-header">
                <div class="flex justify-between items-center">
                    <div>
                        <h1 class="text-2xl font-bold">Edit Task</h1>
                        <p class="text-white/90 text-sm mt-1">Update task details below</p>
                    </div>
                    <a href="{{ route('tasks.index') }}" class="text-white hover:text-white/80 transition-colors">
                        <i class="fas fa-times text-xl"></i>
                    </a>
                </div>
            </div>

            <!-- Assigned By Info -->
            <div class="task-modal-body">
                <div class="assigner-card">
                    <h3 class="text-sm font-medium text-gray-700 mb-3">Assigned By</h3>
                    @if($task->assigner)
                        <div class="flex items-center">
                            <div
                                class="w-12 h-12 rounded-full bg-gradient-to-r from-blue-100 to-indigo-100 flex items-center justify-center text-indigo-600 font-semibold text-lg mr-4">
                                {{ strtoupper(substr($task->assigner->name, 0, 1)) }}
                            </div>
                            <div>
                                <p class="text-lg font-medium text-gray-900">{{ $task->assigner->name }}</p>
                                @if($task->assigner->roles->isNotEmpty())
                                    <div class="flex items-center space-x-2 mt-1">
                                        @foreach($task->assigner->roles as $role)
                                            <span class="role-badge">{{ $role->name }}</span>
                                        @endforeach
                                    </div>
                                @endif
                            </div>
                        </div>
                    @else
                        <p class="text-gray-500 italic">No assigner recorded</p>
                    @endif
                </div>

                <form id="taskForm" enctype="multipart/form-data" method="POST"
                    action="{{ route('tasks.update', $task->id) }}">
                    @csrf
                    <input type="hidden" name="_method" value="PUT">
                    <input type="hidden" name="task_id" value="{{ $task->id }}">

                    <div class="form-section">
                        <h3 class="form-section-title">Basic Information</h3>
                        <div class="form-grid form-grid-1 gap-6">
                            <div>
                                <label for="taskTitle" class="form-label required-field">Task Title</label>
                                <div class="input-with-icon">
                                    <i class="fas fa-heading input-icon"></i>
                                    <input type="text" id="taskTitle" name="title" value="{{ $task->title }}"
                                        class="form-input" placeholder="Enter task title" required>
                                </div>
                            </div>

                            <div class="col-span-2">
                                <label for="taskCategory" class="form-label">Task Category</label>
                                <div class="space-y-3">
                                    <div class="flex space-x-3 items-center">
                                        <div class="input-with-icon flex-1">
                                            <i class="fas fa-tag input-icon"></i>
                                            <select id="taskCategory" name="category"
                                                class="form-input form-select cursor-pointer">
                                                <option value="">Select Category</option>
                                                <option value="SEO" {{ $task->category == 'SEO' ? 'selected' : '' }}>SEO
                                                </option>
                                                <option value="Ads" {{ $task->category == 'Ads' ? 'selected' : '' }}>Ads
                                                </option>
                                                <option value="Content" {{ $task->category == 'Content' ? 'selected' : '' }}>
                                                    Content</option>
                                                <option value="Social Media" {{ $task->category == 'Social Media' ? 'selected' : '' }}>Social Media</option>
                                                <option value="Designing" {{ $task->category == 'Designing' ? 'selected' : '' }}>Designing</option>
                                                <option value="Video Editing" {{ $task->category == 'Video Editing' ? 'selected' : '' }}>Video Editing</option>
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
                                                    class="px-4 py-3 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 flex items-center justify-center flex-1">
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
                                placeholder="Describe the task in detail...">{{ $task->description }}</textarea>
                        </div>
                    </div>

                    <div class="form-section">
                        <h3 class="form-section-title">Assignment Type</h3>
                        <div class="assignment-toggle">
                            <button type="button" class="toggle-btn {{ !$task->assigned_to_team ? 'active' : '' }}"
                                data-type="individual">
                                <i class="fas fa-user"></i>
                                Individual Users
                            </button>
                            <button type="button" class="toggle-btn {{ $task->assigned_to_team ? 'active' : '' }}"
                                data-type="team">
                                <i class="fas fa-users"></i>
                                Whole Team
                            </button>
                        </div>
                        <input type="hidden" name="assigned_type"
                            value="{{ $task->assigned_to_team ? 'team' : 'individual' }}">

                        <div id="individualAssignment" class="mt-4"
                            style="{{ $task->assigned_to_team ? 'display: none;' : '' }}">
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
                            <div id="selectedUsersContainer" class="mb-4 {{ $task->users->isEmpty() ? 'hidden' : '' }}">
                                <div class="selected-users-container">
                                    <div class="flex flex-wrap gap-2" id="selectedUsersBadges">
                                        @foreach($task->users as $user)
                                            <div class="selected-users-badge" data-user-id="{{ $user->id }}">
                                                <div
                                                    class="w-6 h-6 rounded-full bg-gradient-to-r from-blue-100 to-indigo-100 flex items-center justify-center text-indigo-600 text-xs font-semibold mr-2">
                                                    {{ strtoupper(substr($user->name, 0, 1)) }}
                                                </div>
                                                <span class="font-medium">{{ $user->name }}</span>
                                                <button type="button"
                                                    class="ml-2 text-indigo-400 hover:text-indigo-600 remove-selected-user"
                                                    data-user-id="{{ $user->id }}">
                                                    <i class="fas fa-times text-xs"></i>
                                                </button>
                                            </div>
                                        @endforeach
                                    </div>
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
                                        <div
                                            class="user-checkbox-card {{ $task->users->contains($user->id) ? 'selected' : '' }}">
                                            <input type="checkbox" name="assigned_users[]" value="{{ $user->id }}"
                                                class="user-checkbox hidden" id="user-{{ $user->id }}" {{ $task->users->contains($user->id) ? 'checked' : '' }}>
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
                                                    class="check-indicator w-5 h-5 border-2 border-gray-300 rounded flex items-center justify-center transition-all duration-200 {{ $task->users->contains($user->id) ? 'bg-indigo-500 border-indigo-500' : '' }}">
                                                    <i
                                                        class="fas fa-check text-white text-xs {{ $task->users->contains($user->id) ? '' : 'opacity-0' }}"></i>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>

                        <div id="teamAssignment" class="mt-4"
                            style="{{ !$task->assigned_to_team ? 'display: none;' : '' }}">
                            <div class="form-grid form-grid-2 gap-6">
                                <div>
                                    <label for="assignedRole" class="form-label required-field">Select Team Role</label>
                                    <div class="input-with-icon">
                                        <i class="fas fa-user-tag input-icon"></i>
                                        <select id="assignedRole" name="assigned_role"
                                            class="form-input form-select cursor-pointer">
                                            <option value="">Select Role</option>
                                            @foreach($roles as $role)
                                                <option value="{{ $role->id }}" {{ ($task->role && $task->role->id == $role->id) ? 'selected' : '' }}>{{ $role->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div>
                                    <label class="form-label">&nbsp;</label>
                                    <div class="mt-6">
                                        <!-- <button type="button" id="addRoleBtn"
                                            class="text-sm text-indigo-600 hover:text-indigo-800 flex items-center font-medium">
                                            <i class="fas fa-plus-circle mr-1.5"></i> Add New Role
                                        </button> -->
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
                                    <div class="priority-card {{ $task->priority == 'Low' ? 'active' : '' }}"
                                        data-priority="low" id="priority-low">
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
                                    <div class="priority-card {{ $task->priority == 'Medium' ? 'active' : '' }}"
                                        data-priority="medium" id="priority-medium">
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
                                    <div class="priority-card {{ $task->priority == 'High' ? 'active' : '' }}"
                                        data-priority="high" id="priority-high">
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
                                <input type="hidden" id="priority" name="priority"
                                    value="{{ $task->priority ?? 'Medium' }}">
                            </div>

                            <div>
                                <label for="dueDate" class="form-label required-field">Due Date</label>
                                <div class="input-with-icon">
                                    <i class="fas fa-calendar-alt input-icon"></i>
                                    <input type="date" id="dueDate" name="due_date"
                                        value="{{ $task->due_date ? $task->due_date->format('Y-m-d') : '' }}"
                                        class="form-input cursor-pointer">
                                </div>
                            </div>

                            <div>
                                <label for="taskStatus" class="form-label">Status</label>
                                <div class="input-with-icon">
                                    <i class="fas fa-flag input-icon"></i>
                                    <select id="taskStatus" name="status" class="form-input form-select cursor-pointer">
                                        <option value="Pending" {{ $task->status == 'Pending' ? 'selected' : '' }}>Pending
                                        </option>
                                        <option value="In Progress" {{ $task->status == 'In Progress' ? 'selected' : '' }}>In
                                            Progress</option>
                                        <option value="Completed" {{ $task->status == 'Completed' ? 'selected' : '' }}>
                                            Completed</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Existing Attachments -->
                    @if($task->attachments && count($task->attachments) > 0)
                        <div class="form-section">
                            <h3 class="form-section-title">Existing Attachments</h3>
                            <div id="existingAttachments" class="space-y-3">
                                @foreach($task->attachments as $index => $path)
                                    <div class="existing-attachment">
                                        <div class="flex items-center flex-1 min-w-0">
                                            <div
                                                class="w-12 h-12 rounded-lg bg-gradient-to-r from-indigo-100 to-purple-100 flex items-center justify-center text-indigo-600 mr-4">
                                                <i class="fas fa-file"></i>
                                            </div>
                                            <div class="min-w-0">
                                                <div class="font-semibold text-gray-800 truncate">{{ basename($path) }}</div>
                                                <div class="text-sm text-gray-500">
                                                    @if(Storage::disk('public')->exists($path))
                                                        {{ number_format(filesize(storage_path('app/public/' . $path)) / 1024, 1) }} KB
                                                    @else
                                                        File not found
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                        <div class="flex items-center space-x-4">
                                            @if(Storage::disk('public')->exists($path))
                                                <a href="{{ Storage::url($path) }}" target="_blank"
                                                    class="text-indigo-600 hover:text-indigo-800" title="View">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                <a href="{{ Storage::url($path) }}" download class="text-green-600 hover:text-green-800"
                                                    title="Download">
                                                    <i class="fas fa-download"></i>
                                                </a>
                                            @endif
                                            <label class="flex items-center cursor-pointer">
                                                <input type="checkbox" name="remove_attachments[]" value="{{ $path }}"
                                                    class="mr-2 h-4 w-4 text-red-600 focus:ring-red-500 border-gray-300 rounded">
                                                <span class="text-sm text-red-600">Remove</span>
                                            </label>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    <div class="form-section">
                        <h3 class="form-section-title">Add New Attachments</h3>
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
                        <a href="{{ route('tasks.index') }}" class="btn-secondary flex-1">
                            <i class="fas fa-times mr-2"></i>
                            Cancel
                        </a>
                        <button type="submit" class="btn-primary flex-1">
                            <i class="fas fa-save mr-2"></i>
                            Update Task
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        // csrfToken is already declared in layout component
        let selectedFiles = [];
        let selectedUsers = new Set();

        // Initialize selected users from existing task data
        @foreach($task->users as $user)
            selectedUsers.add('{{ $user->id }}');
        @endforeach

                    // Category functionality
                    const categorySelect = document.getElementById('taskCategory');
        const customContainer = document.getElementById('customCategoryContainer');
        const customInput = document.getElementById('customCategoryInput');

        categorySelect.addEventListener('change', function () {
            if (this.value === 'Custom') {
                this.disabled = true;
                customContainer.classList.remove('hidden');
                customInput.focus();
            }
        });

        document.getElementById('saveCustomCategory').addEventListener('click', function () {
            const customValue = customInput.value.trim();
            if (customValue) {
                const newOption = document.createElement('option');
                newOption.value = customValue;
                newOption.textContent = customValue;
                categorySelect.appendChild(newOption);
                categorySelect.value = customValue;
                customContainer.classList.add('hidden');
                categorySelect.disabled = false;
                showToast('Custom category added!');
            }
        });

        document.getElementById('cancelCustomCategory').addEventListener('click', function () {
            customContainer.classList.add('hidden');
            categorySelect.disabled = false;
            categorySelect.value = '';
            customInput.value = '';
        });

        // Check if current category is custom
        @if(!in_array($task->category, ['SEO', 'Ads', 'Content', 'Social Media', 'Designing', 'Video Editing']) && $task->category)
            setTimeout(() => {
                const customValue = "{{ $task->category }}";
                const exists = Array.from(categorySelect.options).some(opt => opt.value === customValue);
                if (!exists) {
                    const newOption = document.createElement('option');
                    newOption.value = customValue;
                    newOption.textContent = customValue;
                    categorySelect.appendChild(newOption);
                }
                categorySelect.value = customValue;
            }, 100);
        @endif

        // Assignment type toggle
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
            const allUserCheckboxes = document.querySelectorAll('.user-checkbox');

            // Helper function to update checkmark UI
            function updateCheckmarkUI(card, isSelected) {
                const checkIndicator = card.querySelector('.check-indicator');
                const checkIcon = card.querySelector('.check-indicator i');

                if (isSelected) {
                    checkIndicator.classList.add('bg-indigo-500', 'border-indigo-500');
                    checkIndicator.classList.remove('border-gray-300');
                    checkIcon.classList.remove('opacity-0');
                } else {
                    checkIndicator.classList.remove('bg-indigo-500', 'border-indigo-500');
                    checkIndicator.classList.add('border-gray-300');
                    checkIcon.classList.add('opacity-0');
                }
            }

            // Initialize checkboxes based on selectedUsers Set
            allUserCheckboxes.forEach(checkbox => {
                if (selectedUsers.has(checkbox.value)) {
                    checkbox.checked = true;
                    const card = checkbox.closest('.user-checkbox-card');
                    card.classList.add('selected');
                    updateCheckmarkUI(card, true);
                }
            });

            // User checkbox card click handler
            document.querySelectorAll('.user-checkbox-card').forEach(card => {
                card.addEventListener('click', function (e) {
                    const checkbox = this.querySelector('.user-checkbox');
                    const userId = checkbox.value;

                    if (checkbox.checked) {
                        checkbox.checked = false;
                        selectedUsers.delete(userId);
                        this.classList.remove('selected');
                        updateCheckmarkUI(this, false);
                    } else {
                        checkbox.checked = true;
                        selectedUsers.add(userId);
                        this.classList.add('selected');
                        updateCheckmarkUI(this, true);
                    }
                    updateSelectedUsersDisplay();
                    updateSelectedCount();
                    updateSelectAllButton();
                });
            });

            // Remove selected user handler
            document.querySelectorAll('.remove-selected-user').forEach(btn => {
                btn.addEventListener('click', function (e) {
                    e.stopPropagation();
                    const userId = this.getAttribute('data-user-id');
                    selectedUsers.delete(userId);

                    const userCheckbox = document.querySelector(`.user-checkbox[value="${userId}"]`);
                    if (userCheckbox) {
                        userCheckbox.checked = false;
                        const card = userCheckbox.closest('.user-checkbox-card');
                        card.classList.remove('selected');
                        updateCheckmarkUI(card, false);
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
                        updateCheckmarkUI(card, false);
                    } else {
                        checkbox.checked = true;
                        selectedUsers.add(checkbox.value);
                        card.classList.add('selected');
                        updateCheckmarkUI(card, true);
                    }
                });
                updateSelectedUsersDisplay();
                updateSelectedCount();
                updateSelectAllButton();
            });

            document.getElementById('clearSelectionBtn').addEventListener('click', function () {
                allUserCheckboxes.forEach(checkbox => {
                    checkbox.checked = false;
                    const card = checkbox.closest('.user-checkbox-card');
                    card.classList.remove('selected');
                    updateCheckmarkUI(card, false);
                });
                selectedUsers.clear();
                updateSelectedUsersDisplay();
                updateSelectedCount();
                updateSelectAllButton();
            });

            document.getElementById('userSearch').addEventListener('input', filterUsers);

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

            const allUserCheckboxes = document.querySelectorAll('.user-checkbox');
            allUserCheckboxes.forEach(checkbox => {
                if (checkbox.checked) {
                    const card = checkbox.closest('.user-checkbox-card');
                    const userName = card.querySelector('.font-medium').textContent;
                    const userInitial = card.querySelector('.rounded-full').textContent.trim();

                    const badge = document.createElement('div');
                    badge.className = 'selected-users-badge';
                    badge.innerHTML = `
                                    <div     class="w-6 h-6 rounded-full bg-gradient-to-r from-blue-100 to-indigo-100 flex items-center justify-center text-indigo-600 text-xs font-semibold mr-2">
                                        ${userInitial}
                                    </div>
                                    <span class="font-medium">${userName}</span>
                                    <button type="button" class="ml-2 text-indigo-400 hover:text-indigo-600 remove-selected-user" data-user-id="${checkbox.value}">
                                        <i class="fas fa-times text-xs"></i>
                                    </button>
                                `;

                    badge.querySelector('.remove-selected-user').addEventListener('click', function (e) {
                        e.stopPropagation();
                        const userId = this.getAttribute('data-user-id');
                        selectedUsers.delete(userId);
                        const userCheckbox = document.querySelector(`.user-checkbox[value="${userId}"]`);
                        if (userCheckbox) {
                            userCheckbox.checked = false;
                            const card = userCheckbox.closest('.user-checkbox-card');
                            card.classList.remove('selected');
                            updateCheckmarkUI(card, false);
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
            const count = selectedUsers.size;
            document.getElementById('selectedCount').textContent = `${count} user${count !== 1 ? 's' : ''} selected`;
        }

        function updateSelectAllButton() {
            const button = document.getElementById('selectAllBtn');
            const clearButton = document.getElementById('clearSelectionBtn');
            const allUserCheckboxes = document.querySelectorAll('.user-checkbox');
            const allSelected = selectedUsers.size === allUserCheckboxes.length;
            button.textContent = allSelected ? 'Deselect All' : 'Select All';
            clearButton.style.display = allSelected ? 'none' : 'block';
        }

        // Priority selection
        const priorityCards = document.querySelectorAll('.priority-card');
        const priorityInput = document.getElementById('priority');

        console.log('Priority cards found:', priorityCards.length);
        console.log('Priority input:', priorityInput);

        function setPrioritySelection(priority) {
            console.log('setPrioritySelection called with:', priority);
            priorityCards.forEach(card => card.classList.remove('active'));
            const selectedCard = document.getElementById(`priority-${priority}`);
            if (selectedCard) {
                selectedCard.classList.add('active');
                console.log('Added active class to:', selectedCard);
            }
            priorityInput.value = priority.charAt(0).toUpperCase() + priority.slice(1);
            console.log('Priority input value set to:', priorityInput.value);
        }

        priorityCards.forEach(card => {
            console.log('Attaching click handler to card:', card.dataset.priority);
            card.addEventListener('click', function () {
                console.log('Priority card clicked:', this.dataset.priority);
                setPrioritySelection(this.dataset.priority);
            });
        });

        // File upload functionality
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
                                <div     class="flex items-center justify-between">
                                    <div     class="flex items-center flex-1 min-w-0">
                                        <div     class="w-12 h-12 rounded-lg bg-gradient-to-r from-indigo-100 to-purple-100 flex items-center justify-center text-indigo-600 mr-4">
                                            <i c    lass="fas fa-file"></i>
                                        </di    v>
                                        <div     class="min-w-0">
                                            <div     class="font-semibold text-gray-800 truncate">${file.name}</div>
                                            <div     class="text-sm text-gray-500">${Math.round(file.size / 1024)} KB</div>
                                        </di    v>
                                    </di    v>
                                    <but    ton type="button" class="text-red-500 hover:text-red-700 ml-4 remove-file" data-index="${index}" title="Remove file">
                                        <i c    lass="fas fa-times text-lg"></i>
                                    </bu    tton>
                                </di    v>
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

        // Role management
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
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json'
                },
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

        // Form submission
        const taskForm = document.getElementById('taskForm');
        taskForm.addEventListener('submit', function (e) {
            e.preventDefault();

            const formData = new FormData(taskForm);
            const assignedType = document.querySelector('input[name="assigned_type"]').value;

            // Validation
            const validationErrors = [];
            if (!document.getElementById('taskTitle').value.trim()) validationErrors.push('Task title is required');
            if (!priorityInput.value) validationErrors.push('Please select a priority level');
            if (assignedType === 'individual' && selectedUsers.size === 0) validationErrors.push('Please select at least one team member');
            if (assignedType === 'team' && !document.getElementById('assignedRole').value) validationErrors.push('Please select a team role');
            if (!document.getElementById('dueDate').value) validationErrors.push('Please select a due date');

            if (validationErrors.length > 0) {
                showToast(validationErrors.join('\n'), 'error');
                return;
            }

            // Add selected users to form data
            if (assignedType === 'individual') {
                formData.delete('assigned_users');
                selectedUsers.forEach(userId => {
                    formData.append('assigned_users[]', userId);
                });
            }

            const submitBtn = taskForm.querySelector('button[type="submit"]');
            const original = submitBtn.innerHTML;
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i> Updating...';
            submitBtn.disabled = true;

            fetch('{{ route("tasks.update", $task->id) }}', {
                method: 'POST',
                body: formData,
                headers: { 'X-CSRF-TOKEN': csrfToken, 'Accept': 'application/json' }
            })
                .then(res => res.ok ? res.json() : res.json().then(err => { throw err; }))
                .then(data => {
                    if (data.success) {
                        showToast('Task updated successfully!');
                        setTimeout(() => {
                            window.location.href = '{{ route("tasks.index") }}';
                        }, 1500);
                    } else {
                        throw new Error(data.message || 'Failed to update task');
                    }
                })
                .catch(err => {
                    console.error('Update Error:', err);
                    showToast(err.message || 'Failed to update task.', 'error');
                })
                .finally(() => {
                    submitBtn.innerHTML = original;
                    submitBtn.disabled = false;
                });
        });

        // Toast function
        function showToast(message, type = 'success') {
            const toast = document.createElement('div');
            toast.className = `fixed bottom-4 right-4 px-6 py-3 rounded-lg shadow-xl z-50 fade-in flex items-center text-white max-w-md ${type === 'error' ? 'bg-gradient-to-r from-red-500 to-rose-600' : type === 'warning' ? 'bg-gradient-to-r from-yellow-500 to-amber-600' : 'bg-gradient-to-r from-green-500 to-emerald-600'}`;
            toast.innerHTML = `
                            <div     class="w-8 h-8 rounded-full bg-white/20 flex items-center justify-center mr-3">
                                <i c    lass="${type === 'error' ? 'fas fa-exclamation-circle' : type === 'warning' ? 'fas fa-exclamation-triangle' : 'fas fa-check-circle'}"></i>
                            </di    v>
                            <div     class="flex-1"><span class="font-medium">${message}</span></div>
                            <but    ton class="ml-4 text-white/80 hover:text-white" onclick="this.parentElement.remove()"> <i class="fas fa-times"></i> </button>
                        `;
            document.body.appendChild(toast);
            setTimeout(() => toast.remove(), 5000);
        }

        // Initialize on page load
        document.addEventListener('DOMContentLoaded', function () {
            initializeUserSelection();
            updateSelectedUsersDisplay();

            // Set current priority
            const currentPriority = "{{ strtolower($task->priority) }}";
            if (currentPriority) {
                setPrioritySelection(currentPriority);
            }
        });
    </script>
    </body>

    </html>
@endsection