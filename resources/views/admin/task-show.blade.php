{{-- resources/views/admin/task-show.blade.php --}}
@extends('components.layout')

@section('content')
    <!DOCTYPE html>
    <html lang="en">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>View Task | Digital Marketing CRM</title>
        <script src="https://cdn.tailwindcss.com"></script>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
        <style>
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
        </style>
    </head>

    <body class="bg-gray-50">
        <!-- Top Header / Navbar -->
        <header class="bg-white shadow-sm hidden">
            <div class="flex items-center justify-between px-4 py-3">
                <button onclick="history.back()" class="text-gray-500 hover:text-gray-700">
                    <i class="fas fa-arrow-left text-xl"></i>
                </button>
                <div class="flex items-center hidden">
                    <div
                        class="w-10 h-10 rounded-lg gradient-bg flex items-center justify-center text-white font-bold mr-3">
                        DM</div>
                    <h1 class="text-xl font-bold text-gray-800">View Task</h1>
                </div>
                <div class="flex items-center space-x-4 hidden">
                    <a href="{{ route('tasks.index') }}" class="text-indigo-600 hover:text-indigo-800">
                        <i class="fas fa-list text-xl"></i>
                    </a>
                    <div class="flex items-center space-x-2 cursor-pointer">
                        <div class="w-9 h-9 rounded-full bg-indigo-100 flex items-center justify-center text-indigo-600">
                            <i class="fas fa-user"></i>
                        </div>
                        <span class="hidden md:block text-gray-700 font-medium">Sarah Johnson</span>
                    </div>
                </div>
            </div>
        </header>

        <main class="p-6 md:p-8 max-w-4xl mx-auto">
            <div class="bg-white rounded-xl shadow-sm overflow-hidden fade-in">
                <!-- Task Header -->
                <div class="p-6 border-b border-gray-200">
                    <div class="flex flex-col md:flex-row md:items-start justify-between">
                        <div class="flex-1">
                            <h2 class="text-2xl font-bold text-gray-800">{{ $task->title }}</h2>
                            <div class="flex items-center space-x-4 mt-2">
                                <span
                                    class="priority-badge priority-{{ strtolower($task->priority) }}">{{ $task->priority }}</span>
                                <span
                                    class="priority-badge status-{{ strtolower(str_replace(' ', '-', $task->status)) }}">{{ $task->status }}</span>
                                @if($task->category)
                                    <span
                                        class="bg-gray-100 text-gray-800 px-2 py-1 rounded-full text-sm">{{ $task->category }}</span>
                                @endif
                            </div>
                        </div>
                        <div class="mt-4 md:mt-0 text-right">
                            <p class="text-sm text-gray-500">Created: {{ $task->created_at->format('M d, Y') }}</p>
                            @if($task->due_date)
                                <p class="text-sm text-gray-500">Due: {{ $task->formatted_due_date }}</p>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Assigned By -->
                @if($task->assigner)
                    <div class="p-6 border-b border-gray-200 bg-gray-50">
                        <h3 class="text-lg font-semibold text-gray-800 mb-3">Assigned By</h3>
                        <div class="flex items-center">
                            <div
                                class="w-10 h-10 rounded-full bg-indigo-100 flex items-center justify-center text-indigo-600 font-medium mr-3">
                                {{ strtoupper(substr($task->assigner->name, 0, 1)) }}
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-900">{{ $task->assigner->name }}</p>
                                @if($task->assigner->roles->isNotEmpty())
                                    <p class="text-xs text-gray-500">Role:
                                        {{ $task->assigner->roles->pluck('name')->implode(', ') }}</p>
                                @endif
                            </div>
                        </div>
                    </div>
                @endif

                <!-- Description -->
                @if($task->description)
                    <div class="p-6 border-b border-gray-200">
                        <h3 class="text-lg font-semibold text-gray-800 mb-2">Description</h3>
                        <p class="text-gray-600 whitespace-pre-wrap">{{ $task->description }}</p>
                    </div>
                @endif

                <!-- Assignees -->
                <div class="p-6 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-800 mb-3">Assigned To</h3>
                    @if($task->assigned_to_team && $task->role)
                        <div class="flex items-center">
                            <div class="w-8 h-8 rounded-full bg-blue-100 flex items-center justify-center text-blue-600 mr-3">
                                <i class="fas fa-users"></i>
                            </div>
                            <span class="text-gray-800 font-medium">Team: {{ $task->role->name }}</span>
                        </div>
                    @else
                        <div class="space-y-2">
                            @forelse($task->users as $user)
                                <div class="flex items-center gap-2">
                                    <div
                                        class="w-8 h-8 rounded-full bg-indigo-100 flex items-center justify-center text-indigo-600 font-medium text-sm flex-shrink-0">
                                        {{ strtoupper(substr($user->name, 0, 1)) }}
                                    </div>
                                    <span class="text-sm font-medium text-gray-800">{{ $user->name }}</span>
                                </div>
                            @empty
                                <span class="text-gray-500 italic">No users assigned</span>
                            @endforelse
                        </div>
                    @endif
                </div>

                <!-- Attachments -->
                @if($task->attachments && count($task->attachments) > 0)
                    <div class="p-6">
                        <h3 class="text-lg font-semibold text-gray-800 mb-3">Attachments</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            @foreach($task->attachments as $attachment)
                                <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                                    <div class="flex items-center">
                                        <i class="fas fa-file text-gray-400 mr-3"></i>
                                        <span class="text-sm text-gray-700 truncate max-w-xs">{{ basename($attachment) }}</span>
                                    </div>
                                    <a href="{{ Storage::url($attachment) }}" target="_blank"
                                        class="text-indigo-600 hover:text-indigo-800">
                                        <i class="fas fa-download"></i>
                                    </a>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @else
                    <div class="p-6 border-b border-gray-200">
                        <p class="text-gray-500 italic">No attachments</p>
                    </div>
                @endif

                <!-- Actions -->
                <div class="p-6 bg-gray-50">
                    <div class="flex space-x-4">
                       
                        <a href="{{ route('tasks.index') }}"
                            class="bg-gray-300 text-gray-800 px-6 py-2 rounded-lg font-medium hover:bg-gray-400 transition">
                            Back to List
                        </a>
                    </div>
                </div>
            </div>
        </main>
    </body>

    </html>
@endsection