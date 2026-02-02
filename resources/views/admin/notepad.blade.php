@extends('components.layout')

@section('content')
<div class="min-h-screen bg-gray-50">
    <!-- Main Container -->
    <div class="mx-auto px-4 sm:px-6 lg:px-8 py-4">
        <!DOCTYPE html>
        <html lang="en">
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=5.0, user-scalable=yes">
            <title>Marketing CRM | Notes Management System</title>
            <script src="https://cdn.tailwindcss.com"></script>
            <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
            <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
            <meta name="csrf-token" content="{{ csrf_token() }}">
            <style>
                body {
                    font-family: 'Inter', sans-serif;
                    background-color: #f8fafc;
                    overflow-x: hidden;
                }
                
                .note-card {
                    transition: all 0.2s ease;
                }
                
                .note-card:hover {
                    transform: translateY(-2px);
                    box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
                }
                
                .category-badge {
                    padding: 0.25rem 0.75rem;
                    border-radius: 9999px;
                    font-size: 0.75rem;
                    font-weight: 500;
                    display: inline-block;
                }
                
                .tag-badge {
                    padding: 0.2rem 0.5rem;
                    border-radius: 0.375rem;
                    font-size: 0.7rem;
                    font-weight: 500;
                    display: inline-block;
                }
                
                .modal-transition {
                    transition: opacity 0.2s ease;
                }
                
                /* Rich text editor simulation */
                .editor-toolbar button {
                    transition: all 0.2s ease;
                }
                
                .editor-toolbar button:hover {
                    background-color: #e2e8f0;
                }
                
                /* Custom scrollbar */
                ::-webkit-scrollbar {
                    width: 6px;
                }
                
                ::-webkit-scrollbar-track {
                    background: #f1f1f1;
                    border-radius: 4px;
                }
                
                ::-webkit-scrollbar-thumb {
                    background: #c1c1c1;
                    border-radius: 4px;
                }
                
                ::-webkit-scrollbar-thumb:hover {
                    background: #a1a1a1;
                }
                
                /* Animation for note actions */
                @keyframes fadeIn {
                    from { opacity: 0; transform: translateY(10px); }
                    to { opacity: 1; transform: translateY(0); }
                }
                
                .fade-in {
                    animation: fadeIn 0.2s ease forwards;
                }
                
                /* Page transition */
                .page-transition {
                    transition: all 0.3s ease;
                }
                
                .loading-spinner {
                    border: 3px solid #f3f3f3;
                    border-top: 3px solid #3b82f6;
                    border-radius: 50%;
                    width: 30px;
                    height: 30px;
                    animation: spin 1s linear infinite;
                }
                
                @keyframes spin {
                    0% { transform: rotate(0deg); }
                    100% { transform: rotate(360deg); }
                }

                /* Rich Text Editor Styles */
                #note-content-editor {
                    min-height: 150px;
                    outline: none;
                    padding: 1rem;
                    overflow-y: auto;
                }
                
                #note-content-editor:empty:before {
                    content: attr(placeholder);
                    color: #9ca3af;
                }
                
                #note-content-editor:focus {
                    outline: none;
                }
                
                .editor-toolbar button.active {
                    background-color: #d1d5db;
                }

                /* List styles for rich text editor */
                #note-content-editor ul,
                #note-content-editor ol {
                    padding-left: 1.5rem;
                    margin: 0.5rem 0;
                }
                
                #note-content-editor li {
                    margin: 0.25rem 0;
                }
                
                #note-content-editor ul {
                    list-style-type: disc;
                }
                
                #note-content-editor ol {
                    list-style-type: decimal;
                }

                /* Responsive fixes */
                @media (max-width: 640px) {
                    .mobile-flex-col {
                        flex-direction: column;
                    }
                    
                    .mobile-full-width {
                        width: 100%;
                    }
                    
                    .mobile-stack {
                        display: flex;
                        flex-direction: column;
                        gap: 1rem;
                    }
                    
                    .mobile-text-center {
                        text-align: center;
                    }
                    
                    .mobile-p-4 {
                        padding: 1rem;
                    }
                    
                    .mobile-p-2 {
                        padding: 0.5rem;
                    }
                    
                    .mobile-mb-2 {
                        margin-bottom: 0.5rem;
                    }
                    
                    .mobile-mt-2 {
                        margin-top: 0.5rem;
                    }
                }

                /* Ensure proper scrolling on mobile */
                @media (max-width: 768px) {
                    body {
                        -webkit-overflow-scrolling: touch;
                    }
                    
                    .mobile-overflow-auto {
                        overflow: auto;
                    }
                }
            </style>
        </head>
        <body class="bg-gray-50">
            <!-- Top Header -->
            <header class="sticky top-0 z-10 bg-white shadow-sm">
                <div class="px-4 py-4 sm:px-6">
                    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                        <div class="flex items-center">
                            <h1 class="text-xl font-bold text-gray-800" id="page-title">Notes Dashboard</h1>
                        </div>
                        
                        <div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-4">
                            <button id="add-note-btn" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 font-medium flex items-center justify-center sm:justify-start order-2 sm:order-1">
                                <i class="fas fa-plus mr-2"></i>
                                New Note
                            </button>
                            <div class="relative order-1 sm:order-2">
                                <input type="text" id="global-search" placeholder="Search notes..." class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                <div class="absolute left-3 top-2.5 text-gray-400">
                                    <i class="fas fa-search"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </header>
            
            <!-- Main Content Area -->
            <main class="p-4 sm:p-6">
                <!-- Dashboard View -->
                <div id="dashboard-view">
                    <!-- Stats Cards -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-5 gap-4 mb-8" id="stats-container">
                        <div class="bg-white rounded-xl shadow p-5">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-gray-500 text-sm">Total Notes</p>
                                    <h3 class="text-2xl font-bold text-gray-800 mt-1" id="total-notes">0</h3>
                                </div>
                                <div class="w-10 h-10 sm:w-12 sm:h-12 bg-blue-50 rounded-lg flex items-center justify-center">
                                    <i class="fas fa-sticky-note text-blue-600 text-lg sm:text-xl"></i>
                                </div>
                            </div>
                        </div>
                        
                        <div class="bg-white rounded-xl shadow p-5">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-gray-500 text-sm">My Notes</p>
                                    <h3 class="text-2xl font-bold text-gray-800 mt-1" id="my-notes-count">0</h3>
                                </div>
                                <div class="w-10 h-10 sm:w-12 sm:h-12 bg-green-50 rounded-lg flex items-center justify-center">
                                    <i class="fas fa-user text-green-600 text-lg sm:text-xl"></i>
                                </div>
                            </div>
                        </div>
                        
                        <div class="bg-white rounded-xl shadow p-5">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-gray-500 text-sm">Team Notes</p>
                                    <h3 class="text-2xl font-bold text-gray-800 mt-1" id="team-notes-count">0</h3>
                                </div>
                                <div class="w-10 h-10 sm:w-12 sm:h-12 bg-purple-50 rounded-lg flex items-center justify-center">
                                    <i class="fas fa-users text-purple-600 text-lg sm:text-xl"></i>
                                </div>
                            </div>
                        </div>
                        
                        <div class="bg-white rounded-xl shadow p-5">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-gray-500 text-sm">Pinned</p>
                                    <h3 class="text-2xl font-bold text-gray-800 mt-1" id="pinned-notes-count">0</h3>
                                </div>
                                <div class="w-10 h-10 sm:w-12 sm:h-12 bg-yellow-50 rounded-lg flex items-center justify-center">
                                    <i class="fas fa-thumbtack text-yellow-600 text-lg sm:text-xl"></i>
                                </div>
                            </div>
                        </div>
                        
                        <div class="bg-white rounded-xl shadow p-5">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-gray-500 text-sm">This Week</p>
                                    <h3 class="text-2xl font-bold text-gray-800 mt-1" id="recent-notes-count">0</h3>
                                </div>
                                <div class="w-10 h-10 sm:w-12 sm:h-12 bg-red-50 rounded-lg flex items-center justify-center">
                                    <i class="fas fa-history text-red-600 text-lg sm:text-xl"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Filters Bar -->
                    <div class="bg-white rounded-xl shadow mb-6 p-4">
                        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
                            <div class="flex flex-wrap items-center gap-3">
                                <div class="mobile-full-width sm:w-auto">
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Category</label>
                                    <select id="filter-category" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                                        <option value="all">All Categories</option>
                                        <option value="client">Client</option>
                                        <option value="project">Project</option>
                                        <option value="task">Task</option>
                                        <option value="meeting">Meeting</option>
                                        <option value="idea">Idea</option>
                                        <option value="campaign">Campaign</option>
                                        <option value="personal">Personal</option>
                                    </select>
                                </div>
                                
                                <div class="mobile-full-width sm:w-auto">
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Visibility</label>
                                    <select id="filter-visibility" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                                        <option value="all">All Visibility</option>
                                        <option value="private">Private</option>
                                        <option value="team">Team</option>
                                        <option value="public">Public</option>
                                    </select>
                                </div>
                                
                                <div class="mobile-full-width sm:w-auto">
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Date</label>
                                    <select id="filter-date" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                                        <option value="all">All Time</option>
                                        <option value="week">This Week</option>
                                        <option value="month">This Month</option>
                                    </select>
                                </div>
                                
                                <div class="mobile-full-width sm:w-auto">
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Sort By</label>
                                    <select id="filter-sort" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                                        <option value="created_at">Newest First</option>
                                        <option value="oldest">Oldest First</option>
                                        <option value="updated_at">Recently Updated</option>
                                        <option value="title">Title A-Z</option>
                                    </select>
                                </div>
                            </div>
                            
                            <div class="flex items-center gap-3">
                                <button id="clear-filters" class="px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 font-medium whitespace-nowrap">
                                    <i class="fas fa-times mr-2"></i>
                                    Clear
                                </button>
                                <div class="flex border border-gray-300 rounded-lg overflow-hidden">
                                    <button id="view-grid" class="p-2 bg-blue-100 text-blue-700 border-r border-gray-300">
                                        <i class="fas fa-th-large"></i>
                                    </button>
                                    <button id="view-list" class="p-2 text-gray-700 hover:bg-gray-100">
                                        <i class="fas fa-list"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Notes Section -->
                    <div class="mobile-overflow-auto">
                        <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center gap-2 mb-4">
                            <h2 class="text-lg font-semibold text-gray-800" id="section-title">All Notes</h2>
                            <p class="text-gray-500 text-sm" id="notes-count-text">Loading notes...</p>
                        </div>
                        
                        <!-- Notes Grid/List View -->
                        <div id="notes-container" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 sm:gap-6">
                            <!-- Notes will be dynamically loaded here -->
                        </div>
                        
                        <!-- Loading State -->
                        <div id="loading-state" class="text-center py-12">
                            <div class="loading-spinner mx-auto mb-4"></div>
                            <p class="text-gray-600">Loading notes...</p>
                        </div>
                        
                        <!-- Empty State -->
                        <div id="empty-state" class="hidden text-center py-12">
                            <div class="w-20 h-20 sm:w-24 sm:h-24 mx-auto mb-6 rounded-full bg-gray-100 flex items-center justify-center">
                                <i class="fas fa-sticky-note text-gray-400 text-2xl sm:text-3xl"></i>
                            </div>
                            <h3 class="text-xl font-semibold text-gray-700 mb-2">No Notes Found</h3>
                            <p class="text-gray-500 mb-6">Create your first note to get started</p>
                            <button id="create-first-note" class="px-5 py-2.5 bg-blue-600 text-white rounded-lg hover:bg-blue-700 font-medium">
                                <i class="fas fa-plus mr-2"></i> Create Note
                            </button>
                        </div>
                    </div>
                </div>
                
                <!-- Note Detail View -->
                <div id="note-detail-view" class="hidden page-transition mobile-overflow-auto">
                    <!-- Back button -->
                    <div class="mb-6">
                        <button id="back-to-notes" class="flex items-center text-blue-600 hover:text-blue-800 font-medium">
                            <i class="fas fa-arrow-left mr-2"></i>
                            Back to Notes
                        </button>
                    </div>
                    
                    <!-- Note Detail Content -->
                    <div id="note-detail-content">
                        <!-- Content will be dynamically loaded here -->
                    </div>
                </div>
                
                <!-- Add/Edit Note View -->
                <div id="add-edit-note-view" class="hidden page-transition mobile-overflow-auto">
                    <!-- Back button -->
                    <div class="mb-6">
                        <button id="back-from-add-edit" class="flex items-center text-blue-600 hover:text-blue-800 font-medium">
                            <i class="fas fa-arrow-left mr-2"></i>
                            Back to Notes
                        </button>
                    </div>
                    
                    <!-- Add/Edit Note Content -->
                    <div class="bg-white rounded-xl shadow p-4 sm:p-6">
                        <h2 class="text-xl sm:text-2xl font-bold text-gray-800 mb-6" id="add-edit-title">Create New Note</h2>
                        
                        <form id="note-form">
                            <input type="hidden" id="note-id" value="">
                            
                            <div class="space-y-6">
                                <!-- Note Title -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Note Title *</label>
                                    <input type="text" id="note-title" class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Enter note title" required>
                                    <div id="title-error" class="text-red-500 text-sm mt-1 hidden"></div>
                                </div>
                                
                                <!-- Note Content -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Note Content *</label>
                                    <div class="border border-gray-300 rounded-lg overflow-hidden">
                                        <!-- Editor Toolbar -->
                                        <div class="editor-toolbar flex flex-wrap items-center border-b border-gray-300 bg-gray-50 p-2 gap-1">
                                            <button type="button" class="format-btn p-2 rounded hover:bg-gray-200" data-command="bold" title="Bold">
                                                <i class="fas fa-bold"></i>
                                            </button>
                                            <button type="button" class="format-btn p-2 rounded hover:bg-gray-200" data-command="italic" title="Italic">
                                                <i class="fas fa-italic"></i>
                                            </button>
                                            <button type="button" class="format-btn p-2 rounded hover:bg-gray-200" data-command="underline" title="Underline">
                                                <i class="fas fa-underline"></i>
                                            </button>
                                            <div class="border-r border-gray-300 h-6 mx-1"></div>
                                            <button type="button" class="format-btn p-2 rounded hover:bg-gray-200" data-command="insertUnorderedList" title="Bulleted List">
                                                <i class="fas fa-list-ul"></i>
                                            </button>
                                            <button type="button" class="format-btn p-2 rounded hover:bg-gray-200" data-command="insertOrderedList" title="Numbered List">
                                                <i class="fas fa-list-ol"></i>
                                            </button>
                                        </div>
                                        
                                        <!-- ContentEditable Div for Rich Text -->
                                        <div id="note-content-editor" contenteditable="true" class="w-full min-h-[150px] max-h-[300px] overflow-y-auto p-3 sm:p-4" placeholder="Start typing your note here..." required></div>
                                        
                                        <!-- Hidden textarea for form submission -->
                                        <textarea id="note-content" name="content" class="hidden"></textarea>
                                    </div>
                                    <div id="content-error" class="text-red-500 text-sm mt-1 hidden"></div>
                                </div>
                                
                                <!-- Category & Tags -->
                                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Category *</label>
                                        <select id="note-category" class="w-full border border-gray-300 rounded-lg px-4 py-2.5 focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                                            <option value="">Select Category</option>
                                            <option value="client">Client</option>
                                            <option value="project">Project</option>
                                            <option value="task">Task</option>
                                            <option value="meeting">Meeting</option>
                                            <option value="idea">Idea</option>
                                            <option value="campaign">Campaign</option>
                                            <option value="personal">Personal</option>
                                        </select>
                                        <div id="category-error" class="text-red-500 text-sm mt-1 hidden"></div>
                                    </div>
                                    
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Tags</label>
                                        <div class="flex flex-wrap gap-2 mb-2" id="selected-tags">
                                            <!-- Selected tags will appear here -->
                                        </div>
                                        <div class="relative">
                                            <input type="text" id="tag-input" class="w-full border border-gray-300 rounded-lg px-4 py-2.5 focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Type and press Enter to add tags">
                                            <div class="absolute right-3 top-2.5 text-gray-400">
                                                <i class="fas fa-tag"></i>
                                            </div>
                                        </div>
                                        <div class="mt-2">
                                            <p class="text-xs text-gray-500 mb-1">Popular tags:</p>
                                            <div class="flex flex-wrap gap-1">
                                                <button type="button" class="tag-option text-xs px-2 py-1 bg-gray-100 text-gray-700 rounded hover:bg-gray-200" data-tag="SEO">SEO</button>
                                                <button type="button" class="tag-option text-xs px-2 py-1 bg-gray-100 text-gray-700 rounded hover:bg-gray-200" data-tag="Ads">Ads</button>
                                                <button type="button" class="tag-option text-xs px-2 py-1 bg-gray-100 text-gray-700 rounded hover:bg-gray-200" data-tag="Website">Website</button>
                                                <button type="button" class="tag-option text-xs px-2 py-1 bg-gray-100 text-gray-700 rounded hover:bg-gray-200" data-tag="SMM">SMM</button>
                                                <button type="button" class="tag-option text-xs px-2 py-1 bg-gray-100 text-gray-700 rounded hover:bg-gray-200" data-tag="Branding">Branding</button>
                                                <button type="button" class="tag-option text-xs px-2 py-1 bg-gray-100 text-gray-700 rounded hover:bg-gray-200" data-tag="Urgent">Urgent</button>
                                                <button type="button" class="tag-option text-xs px-2 py-1 bg-gray-100 text-gray-700 rounded hover:bg-gray-200" data-tag="Follow-up">Follow-up</button>
                                                <button type="button" class="tag-option text-xs px-2 py-1 bg-gray-100 text-gray-700 rounded hover:bg-gray-200" data-tag="Strategy">Strategy</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                
                                <!-- Related Items -->
                                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Related Client</label>
                                        <select id="related-client" class="w-full border border-gray-300 rounded-lg px-4 py-2.5 focus:outline-none focus:ring-2 focus:ring-blue-500">
                                            <option value="">Select Client (Optional)</option>
                                            <option value="Tech Innovations">Tech Innovations</option>
                                            <option value="Global Retail">Global Retail</option>
                                            <option value="HealthPlus Clinic">HealthPlus Clinic</option>
                                            <option value="EcoStyle Fashion">EcoStyle Fashion</option>
                                            <option value="Foodies Paradise">Foodies Paradise</option>
                                        </select>
                                    </div>
                                    
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Related Project</label>
                                        <select id="related-project" class="w-full border border-gray-300 rounded-lg px-4 py-2.5 focus:outline-none focus:ring-2 focus:ring-blue-500">
                                            <option value="">Select Project (Optional)</option>
                                            <option value="Website Redesign">Website Redesign</option>
                                            <option value="SEO Campaign">SEO Campaign</option>
                                            <option value="Social Media Ads">Social Media Ads</option>
                                            <option value="Email Marketing">Email Marketing</option>
                                            <option value="Brand Awareness">Brand Awareness</option>
                                        </select>
                                    </div>
                                    
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Related Task</label>
                                        <select id="related-task" class="w-full border border-gray-300 rounded-lg px-4 py-2.5 focus:outline-none focus:ring-2 focus:ring-blue-500">
                                            <option value="">Select Task (Optional)</option>
                                            <option value="Content Creation">Content Creation</option>
                                            <option value="Keyword Research">Keyword Research</option>
                                            <option value="Ad Copywriting">Ad Copywriting</option>
                                            <option value="Analytics Report">Analytics Report</option>
                                            <option value="Client Meeting">Client Meeting</option>
                                        </select>
                                    </div>
                                </div>
                                
                                <!-- Visibility & Team -->
                                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Visibility *</label>
                                        <div class="space-y-2">
                                            <div class="flex items-center">
                                                <input type="radio" id="visibility-private" name="visibility" value="private" class="h-4 w-4 text-blue-600 focus:ring-blue-500" checked>
                                                <label for="visibility-private" class="ml-2 block text-sm text-gray-700">
                                                    <span class="font-medium">Private</span> - Only visible to you
                                                </label>
                                            </div>
                                            <div class="flex items-center">
                                                <input type="radio" id="visibility-team" name="visibility" value="team" class="h-4 w-4 text-blue-600 focus:ring-blue-500">
                                                <label for="visibility-team" class="ml-2 block text-sm text-gray-700">
                                                    <span class="font-medium">Team</span> - Visible to selected teams
                                                </label>
                                            </div>
                                            <div class="flex items-center">
                                                <input type="radio" id="visibility-public" name="visibility" value="public" class="h-4 w-4 text-blue-600 focus:ring-blue-500">
                                                <label for="visibility-public" class="ml-2 block text-sm text-gray-700">
                                                    <span class="font-medium">Public</span> - Visible to all employees
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div id="team-selection-container" class="hidden">
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Select Teams</label>
                                        <div class="space-y-2">
                                            <div class="flex items-center">
                                                <input type="checkbox" id="team-seo" value="seo" class="h-4 w-4 text-blue-600 rounded focus:ring-blue-500">
                                                <label for="team-seo" class="ml-2 block text-sm text-gray-700">SEO Team</label>
                                            </div>
                                            <div class="flex items-center">
                                                <input type="checkbox" id="team-web" value="web" class="h-4 w-4 text-blue-600 rounded focus:ring-blue-500">
                                                <label for="team-web" class="ml-2 block text-sm text-gray-700">Web Dev Team</label>
                                            </div>
                                            <div class="flex items-center">
                                                <input type="checkbox" id="team-smm" value="smm" class="h-4 w-4 text-blue-600 rounded focus:ring-blue-500">
                                                <label for="team-smm" class="ml-2 block text-sm text-gray-700">Social Media Team</label>
                                            </div>
                                            <div class="flex items-center">
                                                <input type="checkbox" id="team-ads" value="ads" class="h-4 w-4 text-blue-600 rounded focus:ring-blue-500">
                                                <label for="team-ads" class="ml-2 block text-sm text-gray-700">Ads Team</label>
                                            </div>
                                            <div class="flex items-center">
                                                <input type="checkbox" id="team-content" value="content" class="h-4 w-4 text-blue-600 rounded focus:ring-blue-500">
                                                <label for="team-content" class="ml-2 block text-sm text-gray-700">Content Team</label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                
                                <!-- Pinned -->
                                <div>
                                    <div class="flex items-center">
                                        <input type="checkbox" id="note-pinned" class="h-4 w-4 text-blue-600 rounded focus:ring-blue-500">
                                        <label for="note-pinned" class="ml-2 block text-sm text-gray-700">
                                            <span class="font-medium">Pin this note</span> - Keep it at the top
                                        </label>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="flex flex-col sm:flex-row justify-end gap-3 mt-8 pt-4 border-t">
                                <button type="button" id="cancel-note" class="px-5 py-2.5 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 font-medium order-2 sm:order-1">
                                    Cancel
                                </button>
                                <button type="submit" id="submit-note" class="px-5 py-2.5 bg-blue-600 text-white rounded-lg hover:bg-blue-700 font-medium flex items-center justify-center order-1 sm:order-2">
                                    <span id="submit-text">Save Note</span>
                                    <div id="submit-spinner" class="loading-spinner ml-2 hidden" style="width: 20px; height: 20px;"></div>
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </main>
        </div>
        
        <!-- Delete Confirmation Modal -->
        <div id="delete-modal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50 modal-transition flex items-center justify-center p-4">
            <div class="relative mx-auto border w-full max-w-md shadow-lg rounded-xl bg-white">
                <div class="p-5 sm:p-6 text-center">
                    <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-red-100 mb-4">
                        <i class="fas fa-exclamation-triangle text-red-600 text-xl"></i>
                    </div>
                    <h3 class="text-lg font-medium text-gray-900 mb-2" id="delete-modal-title">Delete Note</h3>
                    <p class="text-gray-500 mb-6" id="delete-modal-message">Are you sure you want to delete this note? This action cannot be undone.</p>
                    <div class="flex flex-col sm:flex-row justify-center gap-3">
                        <button id="cancel-delete" class="px-5 py-2.5 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 font-medium">
                            Cancel
                        </button>
                        <button id="confirm-delete" class="px-5 py-2.5 bg-red-600 text-white rounded-lg hover:bg-red-700 font-medium">
                            Delete Note
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- JavaScript -->
        <script>
            // Notes Management System with Database Integration
            let currentView = "grid"; // grid or list
            let currentFilter = "all"; // all, my, team, pinned, recent
            let selectedTags = [];
            let noteToDelete = null;
            let noteDetailId = null;
            let csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
            
            // Initialize the application
            document.addEventListener('DOMContentLoaded', function() {
                // Load initial data
                loadStats();
                loadNotes();
                
                // Set up rich text editor buttons
                setupRichTextEditor();
                
                // Back to notes button
                document.getElementById('back-to-notes').addEventListener('click', showNotesList);
                document.getElementById('back-from-add-edit').addEventListener('click', showNotesList);
                
                // Add note button
                document.getElementById('add-note-btn').addEventListener('click', openAddNotePage);
                document.getElementById('create-first-note').addEventListener('click', openAddNotePage);
                
                // Cancel note button
                document.getElementById('cancel-note').addEventListener('click', showNotesList);
                
                // View toggle buttons
                document.getElementById('view-grid').addEventListener('click', () => {
                    currentView = 'grid';
                    document.getElementById('view-grid').classList.add('bg-blue-100', 'text-blue-700');
                    document.getElementById('view-list').classList.remove('bg-blue-100', 'text-blue-700');
                    document.getElementById('notes-container').className = 'grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 sm:gap-6';
                    loadNotes();
                });
                
                document.getElementById('view-list').addEventListener('click', () => {
                    currentView = 'list';
                    document.getElementById('view-list').classList.add('bg-blue-100', 'text-blue-700');
                    document.getElementById('view-grid').classList.remove('bg-blue-100', 'text-blue-700');
                    document.getElementById('notes-container').className = 'grid grid-cols-1 gap-4 sm:gap-6';
                    loadNotes();
                });
                
                // Filter event listeners
                document.getElementById('filter-category').addEventListener('change', loadNotes);
                document.getElementById('filter-visibility').addEventListener('change', loadNotes);
                document.getElementById('filter-date').addEventListener('change', loadNotes);
                document.getElementById('filter-sort').addEventListener('change', loadNotes);
                document.getElementById('global-search').addEventListener('input', debounce(loadNotes, 500));
                document.getElementById('clear-filters').addEventListener('click', clearFilters);
                
                // Note form submission
                document.getElementById('note-form').addEventListener('submit', handleNoteSubmit);
                
                // Tag input handling
                document.getElementById('tag-input').addEventListener('keydown', (e) => {
                    if (e.key === 'Enter') {
                        e.preventDefault();
                        const tag = e.target.value.trim();
                        if (tag && !selectedTags.includes(tag)) {
                            selectedTags.push(tag);
                            renderSelectedTags();
                            e.target.value = '';
                        }
                    }
                });
                
                // Tag option buttons
                document.querySelectorAll('.tag-option').forEach(button => {
                    button.addEventListener('click', () => {
                        const tag = button.dataset.tag;
                        if (!selectedTags.includes(tag)) {
                            selectedTags.push(tag);
                            renderSelectedTags();
                        }
                    });
                });
                
                // Remove tag event delegation
                document.getElementById('selected-tags').addEventListener('click', (e) => {
                    if (e.target.closest('.remove-tag')) {
                        const tag = e.target.closest('.remove-tag').dataset.tag;
                        selectedTags = selectedTags.filter(t => t !== tag);
                        renderSelectedTags();
                    }
                });
                
                // Visibility radio buttons
                document.querySelectorAll('input[name="visibility"]').forEach(radio => {
                    radio.addEventListener('change', (e) => {
                        const teamContainer = document.getElementById('team-selection-container');
                        if (e.target.value === 'team') {
                            teamContainer.classList.remove('hidden');
                        } else {
                            teamContainer.classList.add('hidden');
                        }
                    });
                });
                
                // Delete modal
                document.getElementById('cancel-delete').addEventListener('click', closeDeleteModal);
                document.getElementById('confirm-delete').addEventListener('click', confirmDeleteNote);
                
                // Close delete modal when clicking outside
                window.addEventListener('click', (e) => {
                    if (e.target === document.getElementById('delete-modal')) {
                        closeDeleteModal();
                    }
                });
                
                // Handle window resize for better mobile experience
                window.addEventListener('resize', handleResize);
                
                // Initial resize handling
                handleResize();
            });
            
            // Handle responsive layout adjustments
            function handleResize() {
                const width = window.innerWidth;
                
                // Adjust note container layout for mobile
                if (width < 640) {
                    // Ensure proper stacking on mobile
                    const noteContainer = document.getElementById('notes-container');
                    if (noteContainer && currentView === 'grid') {
                        noteContainer.className = 'grid grid-cols-1 gap-4';
                    }
                }
            }
            
            // Set up rich text editor functionality with improved list handling
            function setupRichTextEditor() {
                const editor = document.getElementById('note-content-editor');
                const formatButtons = document.querySelectorAll('.format-btn');
                
                formatButtons.forEach(button => {
                    button.addEventListener('click', function(e) {
                        e.preventDefault();
                        const command = this.dataset.command;
                        
                        // Focus the editor first
                        editor.focus();
                        
                        // Execute the formatting command
                        if (command === 'insertUnorderedList' || command === 'insertOrderedList') {
                            // For lists, we need to ensure proper HTML structure
                            try {
                                document.execCommand(command, false, null);
                            } catch (err) {
                                // Fallback for list creation
                                if (command === 'insertUnorderedList') {
                                    insertList('ul');
                                } else {
                                    insertList('ol');
                                }
                            }
                        } else {
                            document.execCommand(command, false, null);
                        }
                        
                        // Update button active state
                        formatButtons.forEach(btn => btn.classList.remove('active'));
                        this.classList.add('active');
                        
                        // Remove active state after a short delay
                        setTimeout(() => {
                            this.classList.remove('active');
                        }, 300);
                        
                        // Update hidden textarea
                        updateHiddenTextarea();
                    });
                });
                
                // Update hidden textarea whenever editor content changes
                editor.addEventListener('input', updateHiddenTextarea);
                editor.addEventListener('blur', updateHiddenTextarea);
                
                // Ensure proper list styling
                editor.addEventListener('keydown', function(e) {
                    if (e.key === 'Enter') {
                        setTimeout(() => {
                            updateHiddenTextarea();
                        }, 10);
                    }
                });
            }
            
            // Helper function to insert list with proper HTML
            function insertList(type) {
                const editor = document.getElementById('note-content-editor');
                const selection = window.getSelection();
                const range = selection.getRangeAt(0);
                
                // Create list element
                const list = document.createElement(type);
                const listItem = document.createElement('li');
                listItem.innerHTML = '&nbsp;'; // Non-breaking space for empty item
                list.appendChild(listItem);
                
                // Insert list at cursor position
                range.deleteContents();
                range.insertNode(list);
                
                // Move cursor inside the list item
                const newRange = document.createRange();
                newRange.setStart(listItem, 0);
                newRange.setEnd(listItem, 0);
                selection.removeAllRanges();
                selection.addRange(newRange);
                
                updateHiddenTextarea();
            }
            
            // Update hidden textarea with editor content
            function updateHiddenTextarea() {
                const editor = document.getElementById('note-content-editor');
                const textarea = document.getElementById('note-content');
                textarea.value = editor.innerHTML;
            }
            
            // Load statistics from database
            function loadStats() {
                fetch('/api/notes/stats')
                    .then(response => {
                        if (!response.ok) {
                            throw new Error('Network response was not ok');
                        }
                        return response.json();
                    })
                    .then(data => {
                        document.getElementById('total-notes').textContent = data.total || 0;
                        document.getElementById('my-notes-count').textContent = data.my_notes || 0;
                        document.getElementById('team-notes-count').textContent = data.team_notes || 0;
                        document.getElementById('pinned-notes-count').textContent = data.pinned || 0;
                        document.getElementById('recent-notes-count').textContent = data.recent || 0;
                    })
                    .catch(error => {
                        console.error('Error loading stats:', error);
                        // Set default values if API fails
                        document.getElementById('total-notes').textContent = 0;
                        document.getElementById('my-notes-count').textContent = 0;
                        document.getElementById('team-notes-count').textContent = 0;
                        document.getElementById('pinned-notes-count').textContent = 0;
                        document.getElementById('recent-notes-count').textContent = 0;
                    });
            }
            
            // Load notes from database with filters
            function loadNotes() {
                // Show loading state
                document.getElementById('loading-state').classList.remove('hidden');
                document.getElementById('empty-state').classList.add('hidden');
                document.getElementById('notes-count-text').textContent = 'Loading notes...';
                
                // Build query parameters
                const params = new URLSearchParams({
                    filter: currentFilter,
                    category: document.getElementById('filter-category').value,
                    visibility: document.getElementById('filter-visibility').value,
                    date: document.getElementById('filter-date').value,
                    sort_by: document.getElementById('filter-sort').value === 'oldest' ? 'created_at' : 
                             document.getElementById('filter-sort').value === 'title' ? 'title' :
                             document.getElementById('filter-sort').value,
                    sort_order: document.getElementById('filter-sort').value === 'oldest' ? 'asc' : 'desc',
                    search: document.getElementById('global-search').value
                });
                
                fetch(`/api/notes?${params}`)
                    .then(response => {
                        if (!response.ok) {
                            throw new Error('Network response was not ok');
                        }
                        return response.json();
                    })
                    .then(notes => {
                        renderNotes(notes);
                        loadStats(); // Refresh stats
                    })
                    .catch(error => {
                        console.error('Error loading notes:', error);
                        document.getElementById('notes-count-text').textContent = 'Error loading notes';
                        renderNotes([]); // Show empty state
                    })
                    .finally(() => {
                        document.getElementById('loading-state').classList.add('hidden');
                    });
            }
            
            // Render notes to the DOM
            function renderNotes(notes) {
                const notesContainer = document.getElementById('notes-container');
                
                // Update notes count text
                document.getElementById('notes-count-text').textContent = `Showing ${notes.length} note${notes.length !== 1 ? 's' : ''}`;
                
                // Show empty state if no notes
                if (notes.length === 0) {
                    notesContainer.innerHTML = '';
                    document.getElementById('empty-state').classList.remove('hidden');
                    return;
                }
                
                document.getElementById('empty-state').classList.add('hidden');
                
                // Clear container
                notesContainer.innerHTML = '';
                
                // Render each note
                notes.forEach(note => {
                    const noteElement = createNoteElement(note);
                    notesContainer.appendChild(noteElement);
                });
            }
            
            // Create note element for grid/list view
            function createNoteElement(note) {
                const noteDiv = document.createElement('div');
                noteDiv.className = `note-card bg-white rounded-xl shadow p-4 sm:p-5 fade-in ${currentView === 'list' ? 'md:col-span-3' : ''}`;
                noteDiv.dataset.id = note.id;
                
                const categoryColor = getCategoryColor(note.category);
                const visibilityInfo = getVisibilityInfo(note.visibility);
                
                // Parse tags from JSON if needed
                let tags = note.tags;
                if (typeof tags === 'string') {
                    try {
                        tags = JSON.parse(tags);
                    } catch (e) {
                        tags = [];
                    }
                }
                
                // Parse teams from JSON if needed
                let teams = note.teams;
                if (typeof teams === 'string') {
                    try {
                        teams = JSON.parse(teams);
                    } catch (e) {
                        teams = [];
                    }
                }
                
                // Truncate content for preview (strip HTML tags)
                let contentPreview = note.content || '';
                contentPreview = contentPreview.replace(/<[^>]*>/g, ''); // Remove HTML tags
                contentPreview = contentPreview.length > 120 
                    ? contentPreview.substring(0, 120) + '...' 
                    : contentPreview;
                
                noteDiv.innerHTML = `
                    <div class="${currentView === 'list' ? 'flex-1' : ''}">
                        <!-- Note Header -->
                        <div class="flex justify-between items-start mb-3">
                            <div class="flex-1">
                                <h3 class="font-bold text-gray-800 ${currentView === 'list' ? 'text-lg' : ''}">${note.title}</h3>
                                <div class="flex flex-wrap items-center mt-1 gap-1">
                                    <span class="${categoryColor} category-badge">${getCategoryName(note.category)}</span>
                                    <span class="${visibilityInfo.color} tag-badge">
                                        <i class="fas ${visibilityInfo.icon} mr-1"></i>${note.visibility}
                                    </span>
                                    ${note.pinned ? '<span class="bg-yellow-100 text-yellow-800 tag-badge"><i class="fas fa-thumbtack mr-1"></i>Pinned</span>' : ''}
                                </div>
                            </div>
                            <div class="flex gap-1 ml-2">
                                <button class="note-pin-btn p-1.5 text-gray-400 hover:text-yellow-500 rounded-lg hover:bg-yellow-50" title="${note.pinned ? 'Unpin' : 'Pin'}">
                                    <i class="fas fa-thumbtack ${note.pinned ? 'text-yellow-500' : ''}"></i>
                                </button>
                                <button class="note-edit-btn p-1.5 text-gray-400 hover:text-blue-600 rounded-lg hover:bg-blue-50" title="Edit">
                                    <i class="fas fa-edit"></i>
                                </button>
                            </div>
                        </div>
                        
                        <!-- Note Content Preview -->
                        <p class="text-gray-600 mb-4 whitespace-pre-line ${currentView === 'list' ? '' : 'h-16 sm:h-20 overflow-hidden'}">${contentPreview}</p>
                        
                        <!-- Tags -->
                        <div class="mb-4">
                            <div class="flex flex-wrap gap-1">
                                ${tags && tags.length > 0 ? tags.map(tag => `
                                    <span class="bg-gray-100 text-gray-700 tag-badge">${tag}</span>
                                `).join('') : ''}
                            </div>
                        </div>
                        
                        <!-- Note Footer -->
                        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-2 pt-3 border-t border-gray-100">
                            <div class="flex items-center">
                                <div class="w-6 h-6 bg-blue-100 rounded-full flex items-center justify-center mr-2">
                                    <span class="text-blue-700 text-xs font-medium">${(note.user?.name?.charAt(0) || note.created_by?.charAt(0) || 'U')}</span>
                                </div>
                                <span class="text-sm text-gray-700">${note.user?.name || note.created_by || 'User'}</span>
                                <span class="text-xs text-gray-500 mx-2">•</span>
                                <span class="text-xs text-gray-500">${formatDate(note.created_at)}</span>
                            </div>
                            <button class="note-view-btn px-3 py-1 bg-blue-600 text-white text-sm rounded-lg hover:bg-blue-700">
                                View
                            </button>
                        </div>
                    </div>
                `;
                
                // Add event listeners
                const viewBtn = noteDiv.querySelector('.note-view-btn');
                viewBtn.addEventListener('click', () => showNoteDetailPage(note.id));
                
                const editBtn = noteDiv.querySelector('.note-edit-btn');
                if (editBtn) {
                    editBtn.addEventListener('click', () => openEditNotePage(note.id));
                }
                
                const pinBtn = noteDiv.querySelector('.note-pin-btn');
                pinBtn.addEventListener('click', (e) => {
                    e.stopPropagation();
                    togglePinNote(note.id);
                });
                
                // Make entire card clickable for viewing (except action buttons)
                noteDiv.addEventListener('click', (e) => {
                    if (!e.target.closest('button')) {
                        showNoteDetailPage(note.id);
                    }
                });
                
                return noteDiv;
            }
            
            // Show note detail page
            function showNoteDetailPage(noteId) {
                noteDetailId = noteId;
                
                // Update page title
                document.getElementById('page-title').textContent = 'Note Details';
                
                // Hide dashboard and add/edit view, show note detail view
                document.getElementById('dashboard-view').classList.add('hidden');
                document.getElementById('add-edit-note-view').classList.add('hidden');
                document.getElementById('note-detail-view').classList.remove('hidden');
                
                // Load note details from database
                fetch(`/api/notes/${noteId}`)
                    .then(response => {
                        if (!response.ok) {
                            throw new Error('Note not found');
                        }
                        return response.json();
                    })
                    .then(note => {
                        renderNoteDetail(note);
                    })
                    .catch(error => {
                        console.error('Error loading note:', error);
                        alert('Error loading note details');
                        showNotesList();
                    });
            }
            
            // Render note detail content
            function renderNoteDetail(note) {
                const detailContent = document.getElementById('note-detail-content');
                const categoryColor = getCategoryColor(note.category);
                const visibilityInfo = getVisibilityInfo(note.visibility);
                
                // Parse tags from JSON if needed
                let tags = note.tags;
                if (typeof tags === 'string') {
                    try {
                        tags = JSON.parse(tags);
                    } catch (e) {
                        tags = [];
                    }
                }
                
                // Parse teams from JSON if needed
                let teams = note.teams;
                if (typeof teams === 'string') {
                    try {
                        teams = JSON.parse(teams);
                    } catch (e) {
                        teams = [];
                    }
                }
                
                detailContent.innerHTML = `
                    <!-- Note Header -->
                    <div class="bg-white rounded-xl shadow p-4 sm:p-6 mb-6">
                        <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-4">
                            <div>
                                <h2 class="text-xl sm:text-2xl font-bold text-gray-800 mb-2">${note.title}</h2>
                                <div class="flex flex-wrap items-center gap-2">
                                    <span class="${categoryColor} category-badge">${getCategoryName(note.category)}</span>
                                    <span class="${visibilityInfo.color} tag-badge">
                                        <i class="fas ${visibilityInfo.icon} mr-1"></i>${note.visibility}
                                    </span>
                                    ${note.pinned ? '<span class="bg-yellow-100 text-yellow-800 tag-badge"><i class="fas fa-thumbtack mr-1"></i>Pinned</span>' : ''}
                                    <span class="text-gray-600 text-sm sm:text-base"><i class="far fa-clock mr-1"></i> ${formatDate(note.created_at)}</span>
                                </div>
                            </div>
                            <div class="mt-4 lg:mt-0 flex flex-wrap gap-2">
                                <button id="detail-pin-btn" class="px-3 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 font-medium flex-1 sm:flex-none">
                                    <i class="fas fa-thumbtack mr-2"></i> <span id="pin-text">${note.pinned ? 'Unpin' : 'Pin'}</span>
                                </button>
                                <button id="detail-edit-btn" class="px-3 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 font-medium flex-1 sm:flex-none">
                                    <i class="fas fa-edit mr-2"></i> Edit
                                </button>
                                <button id="detail-delete-btn" class="px-3 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 font-medium flex-1 sm:flex-none">
                                    <i class="fas fa-trash-alt mr-2"></i> Delete
                                </button>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Main Content Grid -->
                    <div class="grid grid-cols-1 lg:grid-cols-3 gap-4 sm:gap-6">
                        <!-- Left Column -->
                        <div class="lg:col-span-2 space-y-4 sm:space-y-6">
                            <!-- Note Content -->
                            <div class="bg-white rounded-xl shadow p-4 sm:p-6">
                                <h3 class="text-lg font-bold text-gray-800 mb-4">Note Content</h3>
                                <div class="prose max-w-none">
                                    ${note.content || 'No content available'}
                                </div>
                            </div>
                            
                            <!-- Tags -->
                            ${tags && tags.length > 0 ? `
                                <div class="bg-white rounded-xl shadow p-4 sm:p-6">
                                    <h3 class="text-lg font-bold text-gray-800 mb-4">Tags</h3>
                                    <div class="flex flex-wrap gap-2">
                                        ${tags.map(tag => `
                                            <span class="bg-gray-100 text-gray-700 tag-badge">${tag}</span>
                                        `).join('')}
                                    </div>
                                </div>
                            ` : ''}
                        </div>
                        
                        <!-- Right Column -->
                        <div class="space-y-4 sm:space-y-6">
                            <!-- Note Metadata -->
                            <div class="bg-white rounded-xl shadow p-4 sm:p-6">
                                <h3 class="text-lg font-bold text-gray-800 mb-4">Note Details</h3>
                                <div class="space-y-4">
                                    <div>
                                        <p class="text-gray-500 text-sm">Created By</p>
                                        <div class="flex items-center mt-1">
                                            <div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center mr-3">
                                                <span class="text-blue-700 text-sm font-medium">${(note.user?.name?.charAt(0) || note.created_by?.charAt(0) || 'U')}</span>
                                            </div>
                                            <p class="font-medium">${note.user?.name || note.created_by || 'User'}</p>
                                        </div>
                                    </div>
                                    <div>
                                        <p class="text-gray-500 text-sm">Created</p>
                                        <p class="font-medium">${formatDate(note.created_at)}</p>
                                    </div>
                                    <div>
                                        <p class="text-gray-500 text-sm">Last Updated</p>
                                        <p class="font-medium">${formatDate(note.updated_at)}</p>
                                    </div>
                                    <div>
                                        <p class="text-gray-500 text-sm">Visibility</p>
                                        <p class="font-medium"><span class="${visibilityInfo.color} tag-badge"><i class="fas ${visibilityInfo.icon} mr-1"></i>${note.visibility.charAt(0).toUpperCase() + note.visibility.slice(1)}</span></p>
                                    </div>
                                    ${teams && teams.length > 0 ? `
                                        <div>
                                            <p class="text-gray-500 text-sm">Shared with Teams</p>
                                            <div class="flex flex-wrap gap-1 mt-1">
                                                ${teams.map(team => `
                                                    <span class="bg-blue-100 text-blue-800 tag-badge">${team.charAt(0).toUpperCase() + team.slice(1)}</span>
                                                `).join('')}
                                            </div>
                                        </div>
                                    ` : ''}
                                </div>
                            </div>
                            
                            <!-- Related Items -->
                            ${note.related_client || note.related_project || note.related_task ? `
                                <div class="bg-white rounded-xl shadow p-4 sm:p-6">
                                    <h3 class="text-lg font-bold text-gray-800 mb-4">Related Items</h3>
                                    <div class="space-y-3">
                                        ${note.related_client ? `
                                            <div>
                                                <p class="text-gray-500 text-sm">Client</p>
                                                <p class="font-medium">${note.related_client}</p>
                                            </div>
                                        ` : ''}
                                        ${note.related_project ? `
                                            <div>
                                                <p class="text-gray-500 text-sm">Project</p>
                                                <p class="font-medium">${note.related_project}</p>
                                            </div>
                                        ` : ''}
                                        ${note.related_task ? `
                                            <div>
                                                <p class="text-gray-500 text-sm">Task</p>
                                                <p class="font-medium">${note.related_task}</p>
                                            </div>
                                        ` : ''}
                                    </div>
                                </div>
                            ` : ''}
                        </div>
                    </div>
                `;
                
                // Add event listeners to detail page buttons
                const pinBtn = document.getElementById('detail-pin-btn');
                if (pinBtn) {
                    pinBtn.addEventListener('click', () => {
                        togglePinNote(note.id);
                    });
                }
                
                const editBtn = document.getElementById('detail-edit-btn');
                if (editBtn) {
                    editBtn.addEventListener('click', () => {
                        openEditNotePage(note.id);
                    });
                }
                
                const deleteBtn = document.getElementById('detail-delete-btn');
                if (deleteBtn) {
                    deleteBtn.addEventListener('click', () => {
                        openDeleteModal(note.id);
                    });
                }
            }
            
            // Open add note page
            function openAddNotePage() {
                // Update page title
                document.getElementById('page-title').textContent = 'Create New Note';
                document.getElementById('add-edit-title').textContent = 'Create New Note';
                document.getElementById('note-id').value = '';
                document.getElementById('submit-text').textContent = 'Save Note';
                
                // Reset form
                document.getElementById('note-form').reset();
                document.getElementById('note-content-editor').innerHTML = '';
                document.getElementById('selected-tags').innerHTML = '';
                selectedTags = [];
                
                // Clear errors
                clearFormErrors();
                
                // Set default category
                document.getElementById('note-category').value = 'client';
                
                // Set default visibility
                document.getElementById('visibility-private').checked = true;
                
                // Hide team selection
                document.getElementById('team-selection-container').classList.add('hidden');
                
                // Uncheck all team checkboxes
                document.querySelectorAll('#team-selection-container input[type="checkbox"]').forEach(cb => {
                    cb.checked = false;
                });
                
                // Hide dashboard and detail view, show add/edit view
                document.getElementById('dashboard-view').classList.add('hidden');
                document.getElementById('note-detail-view').classList.add('hidden');
                document.getElementById('add-edit-note-view').classList.remove('hidden');
                
                // Focus on title input
                setTimeout(() => {
                    document.getElementById('note-title').focus();
                }, 100);
            }
            
            // Open edit note page
            function openEditNotePage(noteId) {
                fetch(`/api/notes/${noteId}`)
                    .then(response => {
                        if (!response.ok) {
                            throw new Error('Note not found');
                        }
                        return response.json();
                    })
                    .then(note => {
                        // Update page title
                        document.getElementById('page-title').textContent = 'Edit Note';
                        document.getElementById('add-edit-title').textContent = 'Edit Note';
                        document.getElementById('note-id').value = noteId;
                        document.getElementById('submit-text').textContent = 'Update Note';
                        
                        // Fill form with note data
                        document.getElementById('note-title').value = note.title || '';
                        document.getElementById('note-content-editor').innerHTML = note.content || '';
                        document.getElementById('note-category').value = note.category || 'client';
                        document.getElementById('note-pinned').checked = note.pinned || false;
                        
                        // Update hidden textarea
                        updateHiddenTextarea();
                        
                        // Set tags (parse from JSON if needed)
                        if (typeof note.tags === 'string') {
                            try {
                                selectedTags = JSON.parse(note.tags);
                            } catch (e) {
                                selectedTags = [];
                            }
                        } else {
                            selectedTags = note.tags || [];
                        }
                        renderSelectedTags();
                        
                        // Set related items
                        document.getElementById('related-client').value = note.related_client || '';
                        document.getElementById('related-project').value = note.related_project || '';
                        document.getElementById('related-task').value = note.related_task || '';
                        
                        // Set visibility
                        document.querySelector(`input[name="visibility"][value="${note.visibility || 'private'}"]`).checked = true;
                        
                        // Show/hide team selection based on visibility
                        const teamContainer = document.getElementById('team-selection-container');
                        if (note.visibility === 'team') {
                            teamContainer.classList.remove('hidden');
                            // Check teams (parse from JSON if needed)
                            let teams = note.teams;
                            if (typeof teams === 'string') {
                                try {
                                    teams = JSON.parse(teams);
                                } catch (e) {
                                    teams = [];
                                }
                            }
                            if (teams) {
                                teams.forEach(team => {
                                    const checkbox = document.getElementById(`team-${team}`);
                                    if (checkbox) checkbox.checked = true;
                                });
                            }
                        } else {
                            teamContainer.classList.add('hidden');
                        }
                        
                        // Clear errors
                        clearFormErrors();
                        
                        // Hide dashboard and detail view, show add/edit view
                        document.getElementById('dashboard-view').classList.add('hidden');
                        document.getElementById('note-detail-view').classList.add('hidden');
                        document.getElementById('add-edit-note-view').classList.remove('hidden');
                        
                        // Focus on title input
                        setTimeout(() => {
                            document.getElementById('note-title').focus();
                        }, 100);
                    })
                    .catch(error => {
                        console.error('Error loading note for edit:', error);
                        alert('Error loading note for editing');
                        showNotesList();
                    });
            }
            
            // Go back to notes list
            function showNotesList() {
                // Update page title
                document.getElementById('page-title').textContent = 'Notes Dashboard';
                
                // Show dashboard, hide note detail and add/edit views
                document.getElementById('dashboard-view').classList.remove('hidden');
                document.getElementById('note-detail-view').classList.add('hidden');
                document.getElementById('add-edit-note-view').classList.add('hidden');
                
                // Refresh notes list
                loadNotes();
            }
            
            // Toggle pin status of a note
            function togglePinNote(noteId) {
                fetch(`/api/notes/${noteId}/toggle-pin`, {
                    method: 'PATCH',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': csrfToken
                    }
                })
                .then(response => {
                    if (!response.ok) throw new Error('Network response was not ok');
                    return response.json();
                })
                .then(data => {
                    if (data.success) {
                        // Update UI
                        loadNotes();
                        
                        // If we're in detail view, update the pin button text
                        if (noteDetailId === noteId) {
                            const pinText = document.getElementById('pin-text');
                            if (pinText) {
                                pinText.textContent = data.pinned ? 'Unpin' : 'Pin';
                            }
                        }
                    }
                })
                .catch(error => console.error('Error toggling pin:', error));
            }
            
            // Render selected tags in the form
            function renderSelectedTags() {
                const container = document.getElementById('selected-tags');
                container.innerHTML = '';
                
                selectedTags.forEach(tag => {
                    const tagElement = document.createElement('span');
                    tagElement.className = 'inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800';
                    tagElement.innerHTML = `
                        ${tag}
                        <button type="button" class="ml-1 text-blue-600 hover:text-blue-800 remove-tag" data-tag="${tag}">
                            <i class="fas fa-times"></i>
                        </button>
                    `;
                    container.appendChild(tagElement);
                });
            }
            
            // Handle note form submission
            function handleNoteSubmit(e) {
                e.preventDefault();
                
                // Show loading state
                const submitBtn = document.getElementById('submit-note');
                const submitText = document.getElementById('submit-text');
                const submitSpinner = document.getElementById('submit-spinner');
                
                submitText.textContent = 'Saving...';
                submitSpinner.classList.remove('hidden');
                submitBtn.disabled = true;
                
                // Clear previous errors
                clearFormErrors();
                
                // Get form data
                const noteId = document.getElementById('note-id').value;
                const title = document.getElementById('note-title').value.trim();
                const content = document.getElementById('note-content').value.trim();
                const category = document.getElementById('note-category').value;
                const visibility = document.querySelector('input[name="visibility"]:checked').value;
                const pinned = document.getElementById('note-pinned').checked;
                const relatedClient = document.getElementById('related-client').value;
                const relatedProject = document.getElementById('related-project').value;
                const relatedTask = document.getElementById('related-task').value;
                
                // Get selected teams if visibility is team
                let selectedTeams = [];
                if (visibility === 'team') {
                    document.querySelectorAll('#team-selection-container input[type="checkbox"]:checked').forEach(checkbox => {
                        selectedTeams.push(checkbox.value);
                    });
                }
                
                // Validate required fields
                let hasError = false;
                
                if (!title) {
                    document.getElementById('title-error').textContent = 'Title is required';
                    document.getElementById('title-error').classList.remove('hidden');
                    hasError = true;
                }
                
                if (!content) {
                    document.getElementById('content-error').textContent = 'Content is required';
                    document.getElementById('content-error').classList.remove('hidden');
                    hasError = true;
                }
                
                if (!category) {
                    document.getElementById('category-error').textContent = 'Category is required';
                    document.getElementById('category-error').classList.remove('hidden');
                    hasError = true;
                }
                
                if (hasError) {
                    submitText.textContent = noteId ? 'Update Note' : 'Save Note';
                    submitSpinner.classList.add('hidden');
                    submitBtn.disabled = false;
                    return;
                }
                
                // Prepare data
                const formData = {
                    title,
                    content,
                    category,
                    visibility,
                    tags: selectedTags,
                    teams: selectedTeams,
                    related_client: relatedClient || null,
                    related_project: relatedProject || null,
                    related_task: relatedTask || null,
                    pinned
                };
                
                // Determine URL and method
                const url = noteId ? `/api/notes/${noteId}` : '/api/notes';
                const method = noteId ? 'PUT' : 'POST';
                
                // Send request to database
                fetch(url, {
                    method: method,
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': csrfToken
                    },
                    body: JSON.stringify(formData)
                })
                .then(response => {
                    if (!response.ok) {
                        return response.json().then(err => {
                            throw err;
                        });
                    }
                    return response.json();
                })
                .then(data => {
                    if (data.success) {
                        // Show success message
                        // alert(data.message);
                        alert("Note saved successfully");
                        
                        // Go to detail view of the note
                        if (noteId) {
                            showNoteDetailPage(noteId);
                        } else {
                            showNoteDetailPage(data.note.id);
                        }
                    }
                })
                .catch(error => {
                    if (error.errors) {
                        // Display validation errors
                        Object.keys(error.errors).forEach(field => {
                            const errorElement = document.getElementById(`${field}-error`);
                            if (errorElement) {
                                errorElement.textContent = error.errors[field][0];
                                errorElement.classList.remove('hidden');
                            }
                        });
                    } else {
                        alert('Error saving note: ' + error.message);
                    }
                })
                .finally(() => {
                    // Reset button state
                    submitText.textContent = noteId ? 'Update Note' : 'Save Note';
                    submitSpinner.classList.add('hidden');
                    submitBtn.disabled = false;
                });
            }
            
            // Open delete confirmation modal
            function openDeleteModal(noteId) {
                noteToDelete = noteId;
                
                // Fetch note title for modal
                fetch(`/api/notes/${noteId}`)
                    .then(response => response.json())
                    .then(note => {
                        document.getElementById('delete-modal-title').textContent = `Delete "${note.title}"`;
                    })
                    .catch(() => {
                        document.getElementById('delete-modal-title').textContent = 'Delete Note';
                    });
                
                document.getElementById('delete-modal').classList.remove('hidden');
            }
            
            // Close delete modal
            function closeDeleteModal() {
                document.getElementById('delete-modal').classList.add('hidden');
                noteToDelete = null;
            }
            
            // Confirm and delete note
            function confirmDeleteNote() {
                if (!noteToDelete) return;
                
                fetch(`/api/notes/${noteToDelete}`, {
                    method: 'DELETE',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': csrfToken
                    }
                })
                .then(response => {
                    if (!response.ok) throw new Error('Network response was not ok');
                    return response.json();
                })
                .then(data => {
                    if (data.success) {
                        alert(data.message);
                        closeDeleteModal();
                        showNotesList();
                    }
                })
                .catch(error => {
                    console.error('Error deleting note:', error);
                    alert('Error deleting note');
                    closeDeleteModal();
                });
            }
            
            // Clear all filters
            function clearFilters() {
                document.getElementById('filter-category').value = 'all';
                document.getElementById('filter-visibility').value = 'all';
                document.getElementById('filter-date').value = 'all';
                document.getElementById('filter-sort').value = 'created_at';
                document.getElementById('global-search').value = '';
                selectedTags = [];
                currentFilter = 'all';
                document.getElementById('section-title').textContent = 'All Notes';
                
                loadNotes();
            }
            
            // Clear form errors
            function clearFormErrors() {
                document.querySelectorAll('[id$="-error"]').forEach(element => {
                    element.textContent = '';
                    element.classList.add('hidden');
                });
            }
            
            // Utility functions
            function getCategoryColor(category) {
                const colors = {
                    client: 'bg-purple-100 text-purple-800',
                    project: 'bg-blue-100 text-blue-800',
                    task: 'bg-green-100 text-green-800',
                    meeting: 'bg-yellow-100 text-yellow-800',
                    idea: 'bg-pink-100 text-pink-800',
                    campaign: 'bg-red-100 text-red-800',
                    personal: 'bg-gray-100 text-gray-800'
                };
                return colors[category] || 'bg-gray-100 text-gray-800';
            }
            
            function getCategoryName(category) {
                const names = {
                    client: 'Client',
                    project: 'Project',
                    task: 'Task',
                    meeting: 'Meeting',
                    idea: 'Idea',
                    campaign: 'Campaign',
                    personal: 'Personal'
                };
                return names[category] || category;
            }
            
            function getVisibilityInfo(visibility) {
                const info = {
                    private: { icon: 'fa-lock', color: 'bg-gray-100 text-gray-800' },
                    team: { icon: 'fa-users', color: 'bg-blue-100 text-blue-800' },
                    public: { icon: 'fa-globe', color: 'bg-green-100 text-green-800' }
                };
                return info[visibility] || info.private;
            }
            
            function formatDate(dateString) {
                if (!dateString) return 'Recently';
                
                const date = new Date(dateString);
                const now = new Date();
                const diffMs = now - date;
                const diffMins = Math.floor(diffMs / 60000);
                const diffHours = Math.floor(diffMs / 3600000);
                const diffDays = Math.floor(diffMs / 86400000);
                
                if (diffMins < 1) return "Just now";
                if (diffMins < 60) return `${diffMins}m ago`;
                if (diffHours < 24) return `${diffHours}h ago`;
                if (diffDays < 7) return `${diffDays}d ago`;
                
                return date.toLocaleDateString('en-US', { 
                    month: 'short', 
                    day: 'numeric',
                    year: date.getFullYear() !== now.getFullYear() ? 'numeric' : undefined
                });
            }
            
            function debounce(func, wait) {
                let timeout;
                return function executedFunction(...args) {
                    const later = () => {
                        clearTimeout(timeout);
                        func(...args);
                    };
                    clearTimeout(timeout);
                    timeout = setTimeout(later, wait);
                };
            }
        </script>
        </body>
        </html>
    </div>
</div>
@endsection