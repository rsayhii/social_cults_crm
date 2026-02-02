@extends('components.layout')

@section('content')
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Help & Support System - Digital Marketing CRM</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <style>
        .shadow-custom {
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
        }
        .page {
            display: none;
        }
        .active-page {
            display: block;
        }
        .chat-bubble {
            max-width: 70%;
            border-radius: 18px;
            padding: 12px 16px;
            margin-bottom: 16px;
            position: relative;
        }
        .client-message {
            background-color: #e5e7eb;
            margin-right: auto;
            border-bottom-left-radius: 4px;
        }
        .support-message {
            background-color: #3b82f6;
            color: white;
            margin-left: auto;
            border-bottom-right-radius: 4px;
        }
        .internal-note {
            background-color: #fef3c7;
            border-left: 4px solid #f59e0b;
            margin: 0 auto;
            max-width: 90%;
        }
        .sla-critical {
            color: #ef4444;
            font-weight: bold;
            animation: pulse 2s infinite;
        }
        @keyframes pulse {
            0% { opacity: 1; }
            50% { opacity: 0.7; }
            100% { opacity: 1; }
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
        .status-open {
            background-color: #dbeafe;
            color: #1e40af;
        }
        .status-in-progress {
            background-color: #fef3c7;
            color: #92400e;
        }
        .status-completed {
            background-color: #d1fae5;
            color: #065f46;
        }
        .status-closed {
            background-color: #f3f4f6;
            color: #374151;
        }
        .loading {
            opacity: 0.6;
            pointer-events: none;
        }
    </style>
</head>
<body class="bg-gray-50">
    <!-- Navigation -->
    <nav class="bg-white shadow-custom">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <div class="flex items-center">
                    <div class="flex-shrink-0 flex items-center">
                        <i class="fas fa-headset text-blue-600 text-2xl mr-2"></i>
                        <span class="text-xl font-bold text-gray-800">Digital Marketing CRM</span>
                    </div>
                    <div class="hidden md:ml-6 md:flex md:space-x-4">
                        <a href="#" class="nav-link page-link active text-blue-600 border-b-2 border-blue-600" data-page="dashboard">Dashboard</a>
                        <a href="#" class="nav-link page-link text-gray-600 hover:text-blue-600" data-page="submit-ticket">Submit Ticket</a>
                        <a href="#" class="nav-link page-link text-gray-600 hover:text-blue-600" data-page="ticket-list">Tickets</a>
                        <a href="#" class="nav-link page-link text-gray-600 hover:text-blue-600" data-page="knowledge-base">Knowledge Base</a>
                    </div>
                </div>
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="relative">
                            <button class="flex items-center text-sm rounded-full focus:outline-none" id="user-menu-button">
                                <img class="h-8 w-8 rounded-full" src="https://ui-avatars.com/api/?name={{ urlencode(auth()->user()->name) }}&background=0D8ABC&color=fff" alt="User avatar">
                                <span class="ml-2 text-gray-700">{{ auth()->user()->name }}</span>
                                <i class="fas fa-chevron-down ml-1 text-gray-500"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </nav>

    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
        <!-- Dashboard -->
        <div id="dashboard" class="page active-page mb-12">
            <h2 class="text-2xl font-bold text-gray-800 mb-6">Help & Support Dashboard</h2>
            
            <!-- Stats Cards -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                <div class="bg-white rounded-xl shadow-custom p-6">
                    <div class="flex items-center">
                        <div class="rounded-full bg-blue-100 p-3 mr-4">
                            <i class="fas fa-ticket-alt text-blue-600 text-xl"></i>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-500">Total Tickets</p>
                            <h3 class="text-2xl font-bold text-gray-800" id="total-tickets">{{ $stats['total_tickets'] ?? 0 }}</h3>
                        </div>
                    </div>
                </div>
                
                <div class="bg-white rounded-xl shadow-custom p-6">
                    <div class="flex items-center">
                        <div class="rounded-full bg-yellow-100 p-3 mr-4">
                            <i class="fas fa-clock text-yellow-600 text-xl"></i>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-500">Open Tickets</p>
                            <h3 class="text-2xl font-bold text-gray-800" id="open-tickets">{{ $stats['open_tickets'] ?? 0 }}</h3>
                        </div>
                    </div>
                </div>
                
                <div class="bg-white rounded-xl shadow-custom p-6">
                    <div class="flex items-center">
                        <div class="rounded-full bg-green-100 p-3 mr-4">
                            <i class="fas fa-check-circle text-green-600 text-xl"></i>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-500">Resolved</p>
                            <h3 class="text-2xl font-bold text-gray-800" id="resolved-tickets">{{ $stats['resolved_tickets'] ?? 0 }}</h3>
                        </div>
                    </div>
                </div>
                
                <div class="bg-white rounded-xl shadow-custom p-6">
                    <div class="flex items-center">
                        <div class="rounded-full bg-red-100 p-3 mr-4">
                            <i class="fas fa-exclamation-triangle text-red-600 text-xl"></i>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-500">High Priority</p>
                            <h3 class="text-2xl font-bold text-gray-800" id="high-priority">{{ $stats['high_priority_tickets'] ?? 0 }}</h3>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- SLA Alerts and Recent Tickets -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- SLA Alerts -->
                <div class="bg-white rounded-xl shadow-custom p-6 lg:col-span-1">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">SLA Alerts</h3>
                    <div class="space-y-4" id="sla-alerts-container">
                        @forelse($slaAlerts as $alert)
                        <div class="flex items-center justify-between p-3 bg-red-50 rounded-lg">
                            <div class="flex items-center">
                                <i class="fas fa-exclamation-circle text-red-500 mr-2"></i>
                                <span class="text-sm font-medium">Ticket #{{ $alert->ticket_id }}</span>
                            </div>
                            <span class="text-xs font-bold text-red-600">{{ $alert->sla_due_at->diffForHumans() }}</span>
                        </div>
                        @empty
                        <div class="text-center text-gray-500 py-4">
                            <i class="fas fa-check-circle text-green-500 text-2xl mb-2"></i>
                            <p>No SLA alerts</p>
                        </div>
                        @endforelse
                    </div>
                </div>
                
                <!-- Recent Tickets -->
                <div class="bg-white rounded-xl shadow-custom p-6 lg:col-span-2">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-lg font-semibold text-gray-800">Recent Tickets</h3>
                        <a href="#" class="text-blue-600 hover:text-blue-800 text-sm font-medium page-link" data-page="ticket-list">View All</a>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead>
                                <tr>
                                    <th class="px-4 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Ticket ID</th>
                                    <th class="px-4 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Subject</th>
                                    <th class="px-4 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Priority</th>
                                    <th class="px-4 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                    <th class="px-4 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">SLA</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200" id="recent-tickets-table">
                                @forelse($recentTickets as $ticket)
                                <tr class="hover:bg-gray-50 cursor-pointer" onclick="viewTicket({{ $ticket->id }})">
                                    <td class="px-4 py-3 whitespace-nowrap text-sm font-medium text-gray-900">#{{ $ticket->ticket_id }}</td>
                                    <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-700 truncate max-w-xs">{{ $ticket->title }}</td>
                                    <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-700">
                                        <span class="px-2 py-1 rounded-full text-xs font-medium {{ $ticket->priority === 'urgent' ? 'bg-red-100 text-red-800' : ($ticket->priority === 'high' ? 'bg-orange-100 text-orange-800' : ($ticket->priority === 'medium' ? 'bg-yellow-100 text-yellow-800' : 'bg-green-100 text-green-800')) }}">
                                            {{ ucfirst($ticket->priority) }}
                                        </span>
                                    </td>
                                    <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-700">
                                        <span class="px-2 py-1 rounded-full text-xs font-medium status-{{ str_replace(' ', '-', $ticket->status) }}">
                                            {{ ucfirst(str_replace('-', ' ', $ticket->status)) }}
                                        </span>
                                    </td>
                                    <td class="px-4 py-3 whitespace-nowrap text-sm {{ $ticket->sla_due_at && $ticket->sla_due_at->isPast() ? 'sla-critical' : 'text-gray-700' }}">
                                        {{ $ticket->sla_due_at ? $ticket->sla_due_at->diffForHumans() : 'N/A' }}
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="5" class="px-4 py-3 text-center text-gray-500">No tickets found</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            
            <!-- Team Performance -->
            <div class="mt-6 bg-white rounded-xl shadow-custom p-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">Team Performance</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                    @foreach($teamPerformance as $team)
                    <div class="bg-gray-50 p-4 rounded-lg">
                        <div class="flex items-center justify-between mb-2">
                            <span class="text-sm font-medium text-gray-700">{{ $team->name }}</span>
                            <span class="text-xs bg-green-100 text-green-800 px-2 py-1 rounded-full">{{ $team->ticket_count }} tickets</span>
                        </div>
                        <div class="w-full bg-gray-200 rounded-full h-2">
                            <div class="bg-green-600 h-2 rounded-full" style="width: {{ min(100, ($team->ticket_count / max(1, $stats['total_tickets'])) * 100) }}%"></div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
        
        <!-- Submit Ticket -->
        <div id="submit-ticket" class="page mb-12">
            <div class="flex justify-between items-center mb-6">
                <h2 class="text-2xl font-bold text-gray-800">Submit Support Ticket</h2>
                <button class="bg-gray-200 hover:bg-gray-300 text-gray-700 font-medium py-2 px-4 rounded-lg page-link" data-page="dashboard">
                    Back to Dashboard
                </button>
            </div>
            
            <div class="bg-white rounded-xl shadow-custom p-6">
                <form id="ticket-form" enctype="multipart/form-data">
                    @csrf
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Category</label>
                            <select class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500" id="ticket-category" name="category" required>
                                <option value="">Select Category</option>
                                <option value="website">Website Issue</option>
                                <option value="social-media">Social Media Issue</option>
                                <option value="ads-manager">Ads Manager Issue</option>
                                <option value="email">Email Issue</option>
                                <option value="billing">Billing</option>
                                <option value="others">Others</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Priority</label>
                            <select class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500" id="ticket-priority" name="priority" required>
                                <option value="">Select Priority</option>
                                <option value="low">Low</option>
                                <option value="medium">Medium</option>
                                <option value="high">High</option>
                                <option value="urgent">Urgent</option>
                            </select>
                        </div>
                    </div>
                    
                    <div class="mb-6">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Title</label>
                        <input type="text" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500" id="ticket-title" name="title" placeholder="Brief description of your issue" required>
                    </div>
                    
                    <div class="mb-6">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Description</label>
                        <textarea class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500" id="ticket-description" name="description" rows="5" placeholder="Please provide detailed information about your issue..." required></textarea>
                    </div>
                    
                    <div class="mb-6">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Attachments</label>
                        <div class="border-2 border-dashed border-gray-300 rounded-lg p-6 text-center">
                            <i class="fas fa-cloud-upload-alt text-gray-400 text-3xl mb-2"></i>
                            <p class="text-gray-500 mb-2">Drag & drop files here or click to browse</p>
                            <p class="text-xs text-gray-400">Supports: images, documents, videos (Max: 10MB each)</p>
                            <input type="file" class="hidden" id="file-upload" name="attachments[]" multiple>
                            <button type="button" class="mt-2 bg-blue-500 hover:bg-blue-600 text-white font-medium py-2 px-4 rounded-lg" onclick="document.getElementById('file-upload').click()">
                                Browse Files
                            </button>
                        </div>
                        <div id="file-preview" class="mt-4 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 hidden">
                            <!-- File previews will be added here -->
                        </div>
                    </div>
                    
                    <div class="flex justify-end">
                        <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white font-medium py-2 px-6 rounded-lg flex items-center" id="submit-ticket-btn">
                            <i class="fas fa-paper-plane mr-2"></i> Submit Ticket
                        </button>
                    </div>
                </form>
            </div>
        </div>
        
        <!-- Ticket List -->
        <div id="ticket-list" class="page mb-12">
            <div class="flex justify-between items-center mb-6">
                <h2 class="text-2xl font-bold text-gray-800">Support Tickets</h2>
                <div class="flex space-x-2">
                    <button class="bg-gray-200 hover:bg-gray-300 text-gray-700 font-medium py-2 px-4 rounded-lg page-link" data-page="dashboard">
                        Back to Dashboard
                    </button>
                    <button class="bg-blue-500 hover:bg-blue-600 text-white font-medium py-2 px-4 rounded-lg page-link" data-page="submit-ticket">
                        <i class="fas fa-plus mr-2"></i> New Ticket
                    </button>
                </div>
            </div>
            
            <!-- Filters -->
            <div class="bg-white rounded-xl shadow-custom p-6 mb-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">Filters</h3>
                <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                        <select class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500" id="filter-status">
                            <option value="all">All Status</option>
                            <option value="open">Open</option>
                            <option value="in-progress">In Progress</option>
                            <option value="completed">Completed</option>
                            <option value="closed">Closed</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Priority</label>
                        <select class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500" id="filter-priority">
                            <option value="all">All Priority</option>
                            <option value="low">Low</option>
                            <option value="medium">Medium</option>
                            <option value="high">High</option>
                            <option value="urgent">Urgent</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Category</label>
                        <select class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500" id="filter-category">
                            <option value="all">All Categories</option>
                            <option value="website">Website Issue</option>
                            <option value="social-media">Social Media Issue</option>
                            <option value="ads-manager">Ads Manager Issue</option>
                            <option value="email">Email Issue</option>
                            <option value="billing">Billing</option>
                            <option value="others">Others</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Assigned To</label>
                        <select class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500" id="filter-assigned">
                            <option value="all">All Agents</option>
                            <option value="me">Assigned to Me</option>
                        </select>
                    </div>
                </div>
                <div class="mt-4 flex justify-between">
                    <div class="flex items-center">
                        <input type="text" class="border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500" id="search-tickets" placeholder="Search tickets...">
                        <button class="ml-2 bg-blue-500 hover:bg-blue-600 text-white font-medium py-2 px-4 rounded-lg" onclick="loadTickets()">
                            <i class="fas fa-search"></i>
                        </button>
                    </div>
                    <div class="flex space-x-2">
                        <button id="export-tickets" class="bg-green-500 hover:bg-green-600 text-white font-medium py-2 px-4 rounded-lg flex items-center">
                            <i class="fas fa-file-export mr-2"></i> Export
                        </button>
                        <button id="print-tickets" class="bg-gray-500 hover:bg-gray-600 text-white font-medium py-2 px-4 rounded-lg flex items-center">
                            <i class="fas fa-print mr-2"></i> Print
                        </button>
                    </div>
                </div>
            </div>
            
            <!-- Tickets Table -->
            <div class="bg-white rounded-xl shadow-custom p-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">All Tickets</h3>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead>
                            <tr>
                                <th class="px-4 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Ticket ID</th>
                                <th class="px-4 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Client</th>
                                <th class="px-4 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Category</th>
                                <th class="px-4 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Priority</th>
                                <th class="px-4 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                <th class="px-4 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Assigned To</th>
                                <th class="px-4 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date Created</th>
                                <th class="px-4 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">SLA</th>
                                <th class="px-4 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200" id="tickets-table-body">
                            <!-- Tickets will be populated by JavaScript -->
                        </tbody>
                    </table>
                </div>
                <div class="mt-4 flex justify-between items-center">
                    <div>
                        <span class="text-sm text-gray-600" id="tickets-pagination-info"></span>
                    </div>
                    <div class="flex space-x-2" id="tickets-pagination">
                        <!-- Pagination will be populated by JavaScript -->
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Ticket Details -->
        <div id="ticket-details" class="page mb-12">
            <div class="flex justify-between items-center mb-6">
                <h2 class="text-2xl font-bold text-gray-800">Ticket Details</h2>
                <div class="flex space-x-2">
                    <button class="bg-gray-200 hover:bg-gray-300 text-gray-700 font-medium py-2 px-4 rounded-lg page-link" data-page="ticket-list">
                        Back to Tickets
                    </button>
                    <button class="bg-blue-500 hover:bg-blue-600 text-white font-medium py-2 px-4 rounded-lg" id="export-ticket-details">
                        <i class="fas fa-file-export mr-2"></i> Export
                    </button>
                </div>
            </div>
            
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- Ticket Info and Chat -->
                <div class="lg:col-span-2">
                    <!-- Ticket Header -->
                    <div class="bg-white rounded-xl shadow-custom p-6 mb-6">
                        <div class="flex justify-between items-start mb-4">
                            <div>
                                <h3 class="text-xl font-bold text-gray-800" id="detail-ticket-title">Loading...</h3>
                                <p class="text-gray-600">Ticket #<span id="detail-ticket-id">-</span> • Created by <span id="detail-client-name">-</span> on <span id="detail-created-date">-</span></p>
                            </div>
                            <div class="flex space-x-2">
                                <span class="px-3 py-1 rounded-full text-sm font-medium" id="detail-priority-badge">-</span>
                                <span class="px-3 py-1 rounded-full text-sm font-medium" id="detail-status-badge">-</span>
                            </div>
                        </div>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                            <div>
                                <p class="text-sm font-medium text-gray-700">Category</p>
                                <p class="text-gray-900" id="detail-category">-</p>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-700">SLA Status</p>
                                <p class="text-gray-900" id="detail-sla">-</p>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-700">Assigned To</p>
                                <p class="text-gray-900" id="detail-assigned-to">-</p>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-700">Last Updated</p>
                                <p class="text-gray-900" id="detail-last-updated">-</p>
                            </div>
                        </div>
                        
                        <div class="mb-4">
                            <p class="text-sm font-medium text-gray-700">Description</p>
                            <p class="text-gray-900 mt-1" id="detail-description">-</p>
                        </div>
                        
                        <div>
                            <p class="text-sm font-medium text-gray-700">Attachments</p>
                            <div class="flex flex-wrap gap-2 mt-2" id="detail-attachments">
                                <!-- Attachments will be populated by JavaScript -->
                            </div>
                        </div>
                    </div>
                    
                    <!-- Chat/Conversation -->
                    <div class="bg-white rounded-xl shadow-custom p-6">
                        <h3 class="text-lg font-semibold text-gray-800 mb-4">Conversation</h3>
                        <div class="h-96 overflow-y-auto p-4 border border-gray-200 rounded-lg mb-4" id="chat-messages">
                            <!-- Chat messages will be populated by JavaScript -->
                        </div>
                        
                        <div class="flex items-start">
                            <div class="flex-grow mr-2">
                                <textarea class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500" id="reply-message" rows="2" placeholder="Type your reply..."></textarea>
                                <div class="flex justify-between mt-2">
                                    <div class="flex space-x-2">
                                        <button class="text-gray-500 hover:text-gray-700">
                                            <i class="fas fa-smile"></i>
                                        </button>
                                        <button class="text-gray-500 hover:text-gray-700" onclick="document.getElementById('file-attachment').click()">
                                            <i class="fas fa-paperclip"></i>
                                        </button>
                                        <input type="file" class="hidden" id="file-attachment" multiple>
                                    </div>
                                    <div>
                                        <label class="inline-flex items-center mr-4">
                                            <input type="checkbox" class="rounded border-gray-300 text-blue-600 focus:ring-blue-500" id="internal-note-check">
                                            <span class="ml-2 text-sm text-gray-600">Internal Note</span>
                                        </label>
                                        <button class="bg-blue-500 hover:bg-blue-600 text-white font-medium py-2 px-4 rounded-lg" id="send-reply">
                                            Send
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Sidebar - Actions and Timeline -->
                <div class="lg:col-span-1">
                    <!-- Quick Actions -->
                    <div class="bg-white rounded-xl shadow-custom p-6 mb-6">
                        <h3 class="text-lg font-semibold text-gray-800 mb-4">Quick Actions</h3>
                        <div class="space-y-3">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Assign to Team</label>
                                <select class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500" id="assign-team">
                                    <option value="">Select Team</option>
                                    <option value="SEO Team">SEO Team</option>
                                    <option value="SMM Team">SMM Team</option>
                                    <option value="Ads Team">Ads Team</option>
                                    <option value="Web Team">Web Team</option>
                                    <option value="Content Team">Content Team</option>
                                    <option value="Design Team">Design Team</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Update Priority</label>
                                <select class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500" id="update-priority">
                                    <option value="low">Low</option>
                                    <option value="medium">Medium</option>
                                    <option value="high">High</option>
                                    <option value="urgent">Urgent</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Update Status</label>
                                <select class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500" id="update-status">
                                    <option value="open">Open</option>
                                    <option value="in-progress">In Progress</option>
                                    <option value="completed">Completed</option>
                                    <option value="closed">Closed</option>
                                </select>
                            </div>
                            <button class="w-full bg-blue-500 hover:bg-blue-600 text-white font-medium py-2 px-4 rounded-lg mt-2" onclick="updateTicket()">
                                Apply Changes
                            </button>
                        </div>
                    </div>
                    
                    <!-- Timeline -->
                    <div class="bg-white rounded-xl shadow-custom p-6">
                        <h3 class="text-lg font-semibold text-gray-800 mb-4">Ticket Timeline</h3>
                        <div class="space-y-4" id="ticket-timeline">
                            <!-- Timeline events will be populated by JavaScript -->
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Knowledge Base -->
        <div id="knowledge-base" class="page mb-12">
            <div class="flex justify-between items-center mb-6">
                <h2 class="text-2xl font-bold text-gray-800">Knowledge Base</h2>
                <button class="bg-gray-200 hover:bg-gray-300 text-gray-700 font-medium py-2 px-4 rounded-lg page-link" data-page="dashboard">
                    Back to Dashboard
                </button>
            </div>
            
            <!-- Search Bar -->
            <div class="bg-white rounded-xl shadow-custom p-6 mb-6">
                <div class="flex">
                    <input type="text" class="flex-grow border border-gray-300 rounded-l-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500" id="kb-search" placeholder="Search for articles, guides, or solutions...">
                    <button class="bg-blue-500 hover:bg-blue-600 text-white font-medium py-2 px-6 rounded-r-lg" onclick="searchKnowledgeBase()">
                        <i class="fas fa-search"></i>
                    </button>
                </div>
                <div class="mt-4 flex flex-wrap gap-2">
                    <span class="text-sm text-gray-600">Popular searches:</span>
                    <a href="#" class="text-sm text-blue-600 hover:text-blue-800" onclick="setSearch('website not loading')">website not loading</a>
                    <span class="text-gray-400">•</span>
                    <a href="#" class="text-sm text-blue-600 hover:text-blue-800" onclick="setSearch('Facebook ads not showing')">Facebook ads not showing</a>
                    <span class="text-gray-400">•</span>
                    <a href="#" class="text-sm text-blue-600 hover:text-blue-800" onclick="setSearch('email campaign issues')">email campaign issues</a>
                    <span class="text-gray-400">•</span>
                    <a href="#" class="text-sm text-blue-600 hover:text-blue-800" onclick="setSearch('SEO ranking dropped')">SEO ranking dropped</a>
                </div>
            </div>
            
            <div class="grid grid-cols-1 lg:grid-cols-4 gap-6" id="knowledge-base-content">
                <!-- Content will be loaded dynamically -->
            </div>
        </div>
    </div>

    <script>
        // Global variables
        let currentTicketId = null;
        let currentPage = 1;

        // Initialize the application
        document.addEventListener('DOMContentLoaded', function() {
            // Set up event listeners
            document.querySelectorAll('.page-link').forEach(link => {
                link.addEventListener('click', function(e) {
                    e.preventDefault();
                    const pageId = this.getAttribute('data-page');
                    showPage(pageId);
                    
                    // Update active nav link
                    document.querySelectorAll('.nav-link').forEach(navLink => {
                        navLink.classList.remove('text-blue-600', 'border-b-2', 'border-blue-600');
                        navLink.classList.add('text-gray-600', 'hover:text-blue-600');
                    });
                    
                    if (this.classList.contains('nav-link')) {
                        this.classList.remove('text-gray-600', 'hover:text-blue-600');
                        this.classList.add('text-blue-600', 'border-b-2', 'border-blue-600');
                    }
                });
            });
            
            // Load initial data
            loadTickets();
            loadKnowledgeBase();
            
            // Set up form submissions
            document.getElementById('ticket-form').addEventListener('submit', handleTicketSubmit);
            document.getElementById('file-upload').addEventListener('change', handleFileUpload);
            document.getElementById('send-reply').addEventListener('click', handleSendReply);
            document.getElementById('export-tickets').addEventListener('click', exportTickets);
            document.getElementById('print-tickets').addEventListener('click', printTickets);
            document.getElementById('export-ticket-details').addEventListener('click', exportTicketDetails);
            
            // Set up filter changes
            document.getElementById('filter-status').addEventListener('change', loadTickets);
            document.getElementById('filter-priority').addEventListener('change', loadTickets);
            document.getElementById('filter-category').addEventListener('change', loadTickets);
            document.getElementById('filter-assigned').addEventListener('change', loadTickets);
            document.getElementById('search-tickets').addEventListener('keypress', function(e) {
                if (e.key === 'Enter') loadTickets();
            });
        });

        // Show specific page
        function showPage(pageId) {
            document.querySelectorAll('.page').forEach(page => {
                page.classList.remove('active-page');
            });
            document.getElementById(pageId).classList.add('active-page');
        }

        // Load tickets with filters
        function loadTickets(page = 1) {
            currentPage = page;
            const status = document.getElementById('filter-status').value;
            const priority = document.getElementById('filter-priority').value;
            const category = document.getElementById('filter-category').value;
            const assigned = document.getElementById('filter-assigned').value;
            const search = document.getElementById('search-tickets').value;

            showLoading('tickets-table-body');

            axios.get('/helpandsupport/tickets', {
                params: {
                    status: status !== 'all' ? status : null,
                    priority: priority !== 'all' ? priority : null,
                    category: category !== 'all' ? category : null,
                    assigned_to: assigned !== 'all' ? assigned : null,
                    search: search,
                    page: page
                }
            })
            .then(response => {
                populateTicketsTable(response.data);
            })
            .catch(error => {
                console.error('Error loading tickets:', error);
                showError('tickets-table-body', 'Failed to load tickets');
            });
        }

        // Populate tickets table
        function populateTicketsTable(data) {
            const tbody = document.getElementById('tickets-table-body');
            tbody.innerHTML = '';

            if (data.data.length === 0) {
                tbody.innerHTML = '<tr><td colspan="9" class="px-4 py-3 text-center text-gray-500">No tickets found</td></tr>';
                return;
            }

            data.data.forEach(ticket => {
                const row = document.createElement('tr');
                row.className = 'hover:bg-gray-50 cursor-pointer';
                row.addEventListener('click', () => viewTicket(ticket.id));
                
                const createdDate = new Date(ticket.created_at).toLocaleDateString();
                const slaDue = ticket.sla_due_at ? new Date(ticket.sla_due_at) : null;
                const isSlaCritical = slaDue && slaDue < new Date();
                
                row.innerHTML = `
                    <td class="px-4 py-3 whitespace-nowrap text-sm font-medium text-gray-900">#${ticket.ticket_id}</td>
                    <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-700">${ticket.client ? ticket.client.name : 'N/A'}</td>
                    <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-700">${ticket.category.replace('-', ' ')}</td>
                    <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-700">
                        <span class="px-2 py-1 rounded-full text-xs font-medium ${getPriorityClass(ticket.priority)}">
                            ${ticket.priority.charAt(0).toUpperCase() + ticket.priority.slice(1)}
                        </span>
                    </td>
                    <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-700">
                        <span class="px-2 py-1 rounded-full text-xs font-medium ${getStatusClass(ticket.status)}">
                            ${ticket.status.replace('-', ' ')}
                        </span>
                    </td>
                    <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-700">${ticket.assigned_agent ? ticket.assigned_agent.name : 'Unassigned'}</td>
                    <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-700">${createdDate}</td>
                    <td class="px-4 py-3 whitespace-nowrap text-sm ${isSlaCritical ? 'sla-critical' : 'text-gray-700'}">
                        ${slaDue ? slaDue.toLocaleDateString() : 'N/A'}
                    </td>
                    <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-700">
                        <button class="text-blue-600 hover:text-blue-900 mr-2" onclick="event.stopPropagation(); viewTicket(${ticket.id})">
                            <i class="fas fa-eye"></i>
                        </button>
                    </td>
                `;
                tbody.appendChild(row);
            });

            // Update pagination
            updatePagination(data);
        }

        // View ticket details
        function viewTicket(ticketId) {
            currentTicketId = ticketId;
            showPage('ticket-details');
            loadTicketDetails(ticketId);
        }

        // Load ticket details
        function loadTicketDetails(ticketId) {
            showLoading('chat-messages');
            
            axios.get(`/helpandsupport/tickets/${ticketId}`)
                .then(response => {
                    const ticket = response.data;
                    populateTicketDetails(ticket);
                })
                .catch(error => {
                    console.error('Error loading ticket details:', error);
                    showError('chat-messages', 'Failed to load ticket details');
                });
        }

        // Populate ticket details
        function populateTicketDetails(ticket) {
            // Update basic info
            document.getElementById('detail-ticket-id').textContent = ticket.ticket_id;
            document.getElementById('detail-ticket-title').textContent = ticket.title;
            document.getElementById('detail-client-name').textContent = ticket.client ? ticket.client.name : 'Unknown';
            document.getElementById('detail-created-date').textContent = new Date(ticket.created_at).toLocaleDateString();
            document.getElementById('detail-category').textContent = ticket.category.replace('-', ' ');
            document.getElementById('detail-sla').textContent = ticket.sla_due_at ? 
                `Due: ${new Date(ticket.sla_due_at).toLocaleString()}` : 'No SLA set';
            document.getElementById('detail-assigned-to').textContent = ticket.assigned_agent ? 
                ticket.assigned_agent.name : 'Unassigned';
            document.getElementById('detail-last-updated').textContent = new Date(ticket.updated_at).toLocaleDateString();
            document.getElementById('detail-description').textContent = ticket.description;

            // Update priority and status badges
            document.getElementById('detail-priority-badge').textContent = ticket.priority.charAt(0).toUpperCase() + ticket.priority.slice(1);
            document.getElementById('detail-priority-badge').className = `px-3 py-1 rounded-full text-sm font-medium ${getPriorityClass(ticket.priority)}`;
            
            document.getElementById('detail-status-badge').textContent = ticket.status.replace('-', ' ');
            document.getElementById('detail-status-badge').className = `px-3 py-1 rounded-full text-sm font-medium ${getStatusClass(ticket.status)}`;

            // Update form values
            document.getElementById('assign-team').value = ticket.assigned_team || '';
            document.getElementById('update-priority').value = ticket.priority;
            document.getElementById('update-status').value = ticket.status;

            // Populate attachments
            const attachmentsContainer = document.getElementById('detail-attachments');
            attachmentsContainer.innerHTML = '';
            
            if (ticket.attachments && ticket.attachments.length > 0) {
                ticket.attachments.forEach(attachment => {
                    const attachmentElement = document.createElement('div');
                    attachmentElement.className = 'flex items-center bg-gray-50 px-3 py-2 rounded-lg';
                    attachmentElement.innerHTML = `
                        <i class="fas fa-file text-gray-400 mr-2"></i>
                        <span class="text-sm text-gray-700">${attachment.file_name}</span>
                        <a href="/storage/${attachment.file_path}" download class="ml-2 text-blue-600 hover:text-blue-800">
                            <i class="fas fa-download"></i>
                        </a>
                    `;
                    attachmentsContainer.appendChild(attachmentElement);
                });
            } else {
                attachmentsContainer.innerHTML = '<p class="text-sm text-gray-500">No attachments</p>';
            }

            // Populate conversation
            populateConversation(ticket.conversations);

            // Populate timeline
            populateTimeline(ticket.timelines);
        }

        // Handle ticket form submission
        function handleTicketSubmit(e) {
            e.preventDefault();
            
            const formData = new FormData(e.target);
            const submitBtn = document.getElementById('submit-ticket-btn');
            
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i> Submitting...';
            
            axios.post('/helpandsupport/tickets', formData, {
                headers: {
                    'Content-Type': 'multipart/form-data'
                }
            })
            .then(response => {
                alert('Ticket submitted successfully! Ticket ID: ' + response.data.ticket.ticket_id);
                e.target.reset();
                document.getElementById('file-preview').innerHTML = '';
                document.getElementById('file-preview').classList.add('hidden');
                showPage('ticket-list');
                loadTickets();
            })
            .catch(error => {
                console.error('Error submitting ticket:', error);
                alert('Failed to submit ticket: ' + (error.response?.data?.message || 'Unknown error'));
            })
            .finally(() => {
                submitBtn.disabled = false;
                submitBtn.innerHTML = '<i class="fas fa-paper-plane mr-2"></i> Submit Ticket';
            });
        }

        // Handle file upload
        function handleFileUpload(e) {
            const files = e.target.files;
            if (files.length > 0) {
                const preview = document.getElementById('file-preview');
                preview.innerHTML = '';
                preview.classList.remove('hidden');
                
                for (let i = 0; i < files.length; i++) {
                    const file = files[i];
                    const fileElement = document.createElement('div');
                    fileElement.className = 'bg-gray-50 p-3 rounded-lg flex items-center';
                    fileElement.innerHTML = `
                        <i class="fas fa-file text-gray-400 mr-2"></i>
                        <span class="text-sm text-gray-700 truncate">${file.name}</span>
                        <button type="button" class="ml-auto text-red-500 hover:text-red-700" onclick="this.parentElement.remove()">
                            <i class="fas fa-times"></i>
                        </button>
                    `;
                    preview.appendChild(fileElement);
                }
            } else {
                document.getElementById('file-preview').classList.add('hidden');
            }
        }

        // Populate conversation
        function populateConversation(conversations) {
            const container = document.getElementById('chat-messages');
            container.innerHTML = '';
            
            if (!conversations || conversations.length === 0) {
                container.innerHTML = '<div class="text-center text-gray-500 py-8">No messages yet</div>';
                return;
            }
            
            conversations.forEach(message => {
                const messageElement = document.createElement('div');
                
                if (message.is_internal) {
                    messageElement.className = 'internal-note chat-bubble';
                    messageElement.innerHTML = `
                        <div class="flex justify-between items-center mb-1">
                            <span class="text-xs font-medium text-yellow-700">Internal Note • ${message.user.name}</span>
                            <span class="text-xs text-yellow-600">${new Date(message.created_at).toLocaleString()}</span>
                        </div>
                        <p class="text-sm">${message.message}</p>
                    `;
                } else if (message.user_id === {{ auth()->id() }}) {
                    messageElement.className = 'support-message chat-bubble';
                    messageElement.innerHTML = `
                        <div class="flex justify-between items-center mb-1">
                            <span class="text-xs font-medium text-blue-100">${message.user.name}</span>
                            <span class="text-xs text-blue-100">${new Date(message.created_at).toLocaleString()}</span>
                        </div>
                        <p class="text-sm">${message.message}</p>
                    `;
                } else {
                    messageElement.className = 'client-message chat-bubble';
                    messageElement.innerHTML = `
                        <div class="flex justify-between items-center mb-1">
                            <span class="text-xs font-medium text-gray-700">${message.user.name}</span>
                            <span class="text-xs text-gray-500">${new Date(message.created_at).toLocaleString()}</span>
                        </div>
                        <p class="text-sm">${message.message}</p>
                    `;
                }
                
                container.appendChild(messageElement);
            });
            
            // Scroll to bottom
            container.scrollTop = container.scrollHeight;
        }

        // Populate timeline
        function populateTimeline(timelines) {
            const container = document.getElementById('ticket-timeline');
            container.innerHTML = '';
            
            if (!timelines || timelines.length === 0) {
                container.innerHTML = '<div class="text-center text-gray-500 py-4">No timeline events</div>';
                return;
            }
            
            timelines.forEach(event => {
                const eventElement = document.createElement('div');
                eventElement.className = 'flex items-start';
                eventElement.innerHTML = `
                    <div class="flex-shrink-0 w-2 h-2 mt-2 bg-blue-500 rounded-full"></div>
                    <div class="ml-3">
                        <p class="text-sm font-medium text-gray-900">${event.event}</p>
                        <p class="text-xs text-gray-500">${new Date(event.created_at).toLocaleString()} • ${event.user.name}</p>
                        ${event.description ? `<p class="text-xs text-gray-600 mt-1">${event.description}</p>` : ''}
                    </div>
                `;
                container.appendChild(eventElement);
            });
        }

        // Handle sending a reply
        function handleSendReply() {
            const message = document.getElementById('reply-message').value.trim();
            if (!message) return;
            
            const isInternal = document.getElementById('internal-note-check').checked;
            const fileInput = document.getElementById('file-attachment');
            const formData = new FormData();
            
            formData.append('message', message);
            formData.append('is_internal', isInternal);
            
            if (fileInput.files.length > 0) {
                for (let i = 0; i < fileInput.files.length; i++) {
                    formData.append('attachments[]', fileInput.files[i]);
                }
            }
            
            axios.post(`/helpandsupport/tickets/${currentTicketId}/conversation`, formData, {
                headers: {
                    'Content-Type': 'multipart/form-data'
                }
            })
            .then(response => {
                document.getElementById('reply-message').value = '';
                document.getElementById('internal-note-check').checked = false;
                fileInput.value = '';
                loadTicketDetails(currentTicketId);
            })
            .catch(error => {
                console.error('Error sending reply:', error);
                alert('Failed to send message: ' + (error.response?.data?.message || 'Unknown error'));
            });
        }

        // Update ticket
        function updateTicket() {
            const assignedTeam = document.getElementById('assign-team').value;
            const priority = document.getElementById('update-priority').value;
            const status = document.getElementById('update-status').value;
            
            axios.put(`/helpandsupport/tickets/${currentTicketId}`, {
                assigned_team: assignedTeam,
                priority: priority,
                status: status
            })
            .then(response => {
                alert('Ticket updated successfully');
                loadTicketDetails(currentTicketId);
            })
            .catch(error => {
                console.error('Error updating ticket:', error);
                alert('Failed to update ticket: ' + (error.response?.data?.message || 'Unknown error'));
            });
        }

        // Load knowledge base
        function loadKnowledgeBase() {
            axios.get('/helpandsupport/knowledge-base')
                .then(response => {
                    populateKnowledgeBase(response.data);
                })
                .catch(error => {
                    console.error('Error loading knowledge base:', error);
                });
        }

        // Populate knowledge base
        function populateKnowledgeBase(data) {
            const container = document.getElementById('knowledge-base-content');
            
            let html = `
                <div class="lg:col-span-1">
                    <div class="bg-white rounded-xl shadow-custom p-6">
                        <h3 class="text-lg font-semibold text-gray-800 mb-4">Categories</h3>
                        <ul class="space-y-2">
            `;
            
            data.categories.forEach(category => {
                html += `
                    <li>
                        <a href="#" class="text-gray-700 hover:text-blue-600 flex items-center py-2" onclick="filterByCategory(${category.id})">
                            <i class="fas ${category.icon || 'fa-folder'} mr-2 text-blue-500"></i>
                            ${category.name} (${category.articles.length})
                        </a>
                    </li>
                `;
            });
            
            html += `
                        </ul>
                    </div>
                    
                    <div class="bg-white rounded-xl shadow-custom p-6 mt-6">
                        <h3 class="text-lg font-semibold text-gray-800 mb-4">Popular Articles</h3>
                        <ul class="space-y-3">
            `;
            
            data.popularArticles.forEach(article => {
                html += `
                    <li>
                        <a href="#" class="text-sm text-gray-700 hover:text-blue-600" onclick="viewArticle(${article.id})">
                            ${article.title}
                        </a>
                    </li>
                `;
            });
            
            html += `
                        </ul>
                    </div>
                </div>
                
                <div class="lg:col-span-3">
                    <div class="bg-white rounded-xl shadow-custom p-6 mb-6">
                        <h3 class="text-lg font-semibold text-gray-800 mb-4">Recent Articles</h3>
                        <div class="space-y-4">
            `;
            
            data.recentArticles.forEach(article => {
                html += `
                    <div class="border-b border-gray-200 pb-4">
                        <h4 class="font-semibold text-gray-800 mb-1">${article.title}</h4>
                        <p class="text-sm text-gray-600 mb-2">${article.content.substring(0, 150)}...</p>
                        <div class="flex items-center text-xs text-gray-500">
                            <span>${article.category.name}</span>
                            <span class="mx-2">•</span>
                            <span>Views: ${article.views}</span>
                            <span class="mx-2">•</span>
                            <span>${new Date(article.published_at).toLocaleDateString()}</span>
                        </div>
                    </div>
                `;
            });
            
            html += `
                        </div>
                    </div>
                </div>
            `;
            
            container.innerHTML = html;
        }

        // Search knowledge base
        function searchKnowledgeBase() {
            const query = document.getElementById('kb-search').value;
            if (!query.trim()) return;
            
            axios.get('/helpandsupport/knowledge-base/search', {
                params: { query: query }
            })
            .then(response => {
                // Implement search results display
                console.log('Search results:', response.data);
                alert('Search functionality would display results here');
            })
            .catch(error => {
                console.error('Error searching knowledge base:', error);
            });
        }

        // Utility functions
        function getPriorityClass(priority) {
            const classes = {
                'urgent': 'bg-red-100 text-red-800',
                'high': 'bg-orange-100 text-orange-800',
                'medium': 'bg-yellow-100 text-yellow-800',
                'low': 'bg-green-100 text-green-800'
            };
            return classes[priority] || 'bg-gray-100 text-gray-800';
        }

        function getStatusClass(status) {
            const classes = {
                'open': 'status-open',
                'in-progress': 'status-in-progress',
                'completed': 'status-completed',
                'closed': 'status-closed'
            };
            return classes[status] || 'status-closed';
        }

        function showLoading(containerId) {
            const container = document.getElementById(containerId);
            container.innerHTML = '<div class="text-center py-8"><i class="fas fa-spinner fa-spin text-blue-500 text-2xl"></i><p class="mt-2 text-gray-500">Loading...</p></div>';
        }

        function showError(containerId, message) {
            const container = document.getElementById(containerId);
            container.innerHTML = `<div class="text-center py-8 text-red-500"><i class="fas fa-exclamation-triangle"></i><p class="mt-2">${message}</p></div>`;
        }

        function updatePagination(data) {
            const info = document.getElementById('tickets-pagination-info');
            const pagination = document.getElementById('tickets-pagination');
            
            if (!data.meta) {
                info.textContent = `Showing ${data.data.length} tickets`;
                pagination.innerHTML = '';
                return;
            }
            
            const from = (data.meta.current_page - 1) * data.meta.per_page + 1;
            const to = Math.min(data.meta.current_page * data.meta.per_page, data.meta.total);
            
            info.textContent = `Showing ${from}-${to} of ${data.meta.total} tickets`;
            
            let paginationHtml = '';
            
            // Previous button
            if (data.meta.current_page > 1) {
                paginationHtml += `<button class="bg-gray-200 hover:bg-gray-300 text-gray-700 font-medium py-1 px-3 rounded" onclick="loadTickets(${data.meta.current_page - 1})">
                    <i class="fas fa-chevron-left"></i>
                </button>`;
            }
            
            // Page numbers
            for (let i = 1; i <= data.meta.last_page; i++) {
                if (i === data.meta.current_page) {
                    paginationHtml += `<button class="bg-blue-500 hover:bg-blue-600 text-white font-medium py-1 px-3 rounded">${i}</button>`;
                } else {
                    paginationHtml += `<button class="bg-gray-200 hover:bg-gray-300 text-gray-700 font-medium py-1 px-3 rounded" onclick="loadTickets(${i})">${i}</button>`;
                }
            }
            
            // Next button
            if (data.meta.current_page < data.meta.last_page) {
                paginationHtml += `<button class="bg-gray-200 hover:bg-gray-300 text-gray-700 font-medium py-1 px-3 rounded" onclick="loadTickets(${data.meta.current_page + 1})">
                    <i class="fas fa-chevron-right"></i>
                </button>`;
            }
            
            pagination.innerHTML = paginationHtml;
        }

        function setSearch(query) {
            document.getElementById('kb-search').value = query;
            searchKnowledgeBase();
        }

        function filterByCategory(categoryId) {
            // Implement category filtering
            alert('Category filtering would be implemented here');
        }

        function viewArticle(articleId) {
            // Implement article viewing
            alert('Article viewing would be implemented here');
        }

        function exportTickets() {
            axios.post('/helpandsupport/export-tickets')
                .then(response => {
                    alert('Export functionality would download a file');
                })
                .catch(error => {
                    console.error('Error exporting tickets:', error);
                    alert('Failed to export tickets');
                });
        }

        function printTickets() {
            window.print();
        }

        function exportTicketDetails() {
            alert('Export ticket details functionality would be implemented here');
        }
    </script>
</body>
</html>
@endsection