@extends('components.layout')

@section('content')

    <meta name="csrf-token" content="{{ csrf_token() }}">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>
        .chat-bubble-left {
            border-radius: 18px 18px 18px 4px;
        }

        .chat-bubble-right {
            border-radius: 18px 18px 4px 18px;
        }

        .typing-indicator {
            display: inline-block;
            position: relative;
            width: 60px;
            height: 20px;
        }

        .typing-dot {
            display: inline-block;
            width: 8px;
            height: 8px;
            border-radius: 50%;
            margin-right: 3px;
            background: #9ca3af;
            animation: typing 1.3s linear infinite;
        }

        .typing-dot:nth-child(2) {
            animation-delay: -1.1s;
        }

        .typing-dot:nth-child(3) {
            animation-delay: -0.9s;
        }

        @keyframes typing {

            0%,
            60%,
            100% {
                transform: translateY(0);
            }

            30% {
                transform: translateY(-10px);
            }
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

        .fade-in {
            animation: fadeIn 0.3s ease-in-out;
        }

        .sidebar-transition {
            transition: all 0.3s ease;
        }

        .message-transition {
            transition: all 0.2s ease;
        }

        .tab-active {
            border-bottom: 2px solid #4f46e5;
            color: #4f46e5;
        }

        .tab-inactive {
            border-bottom: 2px solid transparent;
            color: #6b7280;
        }

        .group-badge {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }

        .client-badge {
            background: linear-gradient(135deg, #f59e0b 0%, #f4a64eff 100%);
            color: white;
            font-size: 0.65rem;
            padding: 2px 6px;
            border-radius: 8px;
            font-weight: 600;
        }

        .admin-badge {
            background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
            color: white;
            font-size: 0.65rem;
            padding: 2px 6px;
            border-radius: 8px;
            font-weight: 600;
        }

        /* Active subgroup highlighting */
        .subgroup-active {
            background: linear-gradient(90deg, #e0e7ff 0%, #f5f3ff 100%);
            border-left: 3px solid #4f46e5 !important;
        }

        .subgroup-active span.text-gray-700 {
            color: #4338ca;
            font-weight: 600;
        }

        /* Date separator styling */
        .date-separator {
            display: flex;
            align-items: center;
            text-align: center;
            margin: 16px 0;
        }

        .date-separator::before,
        .date-separator::after {
            content: '';
            flex: 1;
            border-bottom: 1px solid #e5e7eb;
        }

        .date-separator span {
            padding: 0 12px;
            font-size: 0.75rem;
            color: #6b7280;
            background: #f3f4f6;
            border-radius: 12px;
            padding: 4px 12px;
        }

        /* Mobile hamburger menu */
        .mobile-menu-btn {
            display: none;
        }

        @media (max-width: 768px) {
            .mobile-menu-btn {
                display: flex;
            }

            .sidebar-mobile-hidden {
                display: none !important;
            }

            .sidebar-mobile-visible {
                display: flex !important;
                position: fixed;
                top: 0;
                left: 0;
                z-index: 50;
                width: 85%;
                max-width: 320px;
                height: 100vh;
                box-shadow: 4px 0 15px rgba(0, 0, 0, 0.1);
            }

            .mobile-overlay {
                position: fixed;
                inset: 0;
                background: rgba(0, 0, 0, 0.3);
                z-index: 40;
            }
        }

        /* Custom Apple-style Dropdown */
        .custom-dropdown-trigger {
            background-color: #ffffff;
            border: 1px solid #e5e7eb;
            border-radius: 12px;
            /* Smooth rounded corners */
            padding: 10px 14px;
            font-size: 0.9rem;
            color: #374151;
            box-shadow: 0 1px 2px rgba(0, 0, 0, 0.05);
            transition: all 0.2s ease;
            display: flex;
            justify-content: space-between;
            align-items: center;
            width: 100%;
            cursor: pointer;
        }

        .custom-dropdown-trigger:hover {
            border-color: #d1d5db;
        }

        .custom-dropdown-trigger:focus {
            outline: none;
            border-color: #6366f1;
            /* Indigo */
            box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.1);
        }

        .custom-dropdown-options {
            position: absolute;
            top: 100%;
            left: 0;
            width: 100%;
            margin-top: 6px;
            background-color: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border: 1px solid #e5e7eb;
            border-radius: 12px;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
            z-index: 50;
            max-height: 250px;
            overflow-y: auto;
            display: none;
            animation: fadeIn 0.15s ease-out;
        }

        .custom-dropdown-options.show {
            display: block;
        }

        .custom-option {
            padding: 10px 14px;
            font-size: 0.9rem;
            color: #374151;
            cursor: pointer;
            transition: background-color 0.1s;
            border-bottom: 1px solid #f3f4f6;
        }

        .custom-option:last-child {
            border-bottom: none;
        }

        .custom-option:hover {
            background-color: #3b82f6;
            /* Apple Blue */
            color: white;
        }

        .custom-option.selected {
            background-color: #eff6ff;
            color: #1d4ed8;
            font-weight: 500;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(-5px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
    </style>

    <div class="flex h-screen bg-gray-100 font-sans">

        <div id="chatInterface" class="w-full flex flex-col md:flex-row">

            <!-- Mobile overlay -->
            <div id="mobileOverlay" class="mobile-overlay hidden" onclick="toggleMobileSidebar()"></div>

            <!-- Sidebar -->
            <div id="chatSidebar"
                class="w-full md:w-1/4 bg-white border-r border-gray-200 flex flex-col h-full sidebar-transition sidebar-mobile-hidden">

                <div class="p-4 border-b border-gray-200 flex justify-between items-center">
                    <div>
                        <h2 class="font-bold text-lg" id="sidebarTitle">Chats</h2>
                        <p class="text-xs text-gray-500">{{ auth()->user()->getRoleNames()->first() }}</p>
                    </div>
                    <div class="flex space-x-2">
                        <button class="p-2 rounded-full hover:bg-gray-100" id="searchToggle">
                            <i class="fas fa-search text-gray-500"></i>
                        </button>
                    </div>
                </div>

                <!-- Tabs for Direct/Group Chats -->
                <div class="flex border-b border-gray-200">
                    <button id="directTab" class="flex-1 py-3 px-4 text-sm font-medium tab-active"
                        onclick="switchTab('direct')">
                        <i class="fas fa-user mr-2"></i>Direct
                    </button>
                    <button id="groupTab" class="flex-1 py-3 px-4 text-sm font-medium tab-inactive"
                        onclick="switchTab('group')">
                        <i class="fas fa-users mr-2"></i>Groups
                    </button>
                </div>

                <div class="p-4 border-b border-gray-200 hidden" id="searchBar">
                    <div class="relative">
                        <input type="text" placeholder="Search chats..." id="chatSearch"
                            class="w-full pl-10 pr-4 py-2 rounded-lg border border-gray-300 focus:outline-none">
                        <i class="fas fa-search absolute left-3 top-3 text-gray-400"></i>
                    </div>
                </div>

                <!-- Direct Chat: User Selection -->
                <div class="p-4 border-b border-gray-200" id="userSelectionContainer">
                    <label class="text-xs font-bold text-gray-500 mb-1 block">Start New Chat</label>
                    <div class="relative custom-dropdown-container" id="customDropdown">
                        <!-- Hidden native select to maintain logic compatibility -->
                        <select class="hidden" id="clientSelectSidebar">
                            <option value="">-- Select User --</option>
                        </select>

                        <!-- Custom Trigger -->
                        <button type="button" class="custom-dropdown-trigger" id="customTrigger">
                            <span class="truncate" id="customTriggerText">-- Select User --</span>
                            <i class="fas fa-chevron-down text-xs text-gray-400"></i>
                        </button>

                        <!-- Custom Options List -->
                        <div class="custom-dropdown-options" id="customOptions">
                            <!-- Populated via JS -->
                        </div>
                    </div>
                </div>

                <button id="startChatBtn"
                    class="bg-indigo-600 text-white px-3 py-2 rounded-lg hidden mt-2 disabled:bg-indigo-300 disabled:cursor-not-allowed mx-4 mb-2">
                    Start Chat
                </button>

                <!-- Group Chat: Admin Controls -->
                <div class="p-4 border-b border-gray-200 hidden" id="groupChatContainer">
                    @if(auth()->user()->hasRole('admin'))
                        <button id="createGroupBtn"
                            class="w-full bg-indigo-600 text-white px-4 py-2 rounded-lg hover:bg-indigo-700 transition flex items-center justify-center">
                            <i class="fas fa-plus mr-2"></i> Create New Group
                        </button>
                    @else
                        <p class="text-sm text-gray-500 text-center">
                            <i class="fas fa-users text-gray-400 mr-1"></i>
                            Groups you are a member of will appear below
                        </p>
                    @endif
                </div>

                <div class="flex-1 overflow-y-auto" id="chatList">
                    <div class="p-8 text-center text-gray-500">
                        <i class="fas fa-comments text-3xl mb-4 text-gray-300"></i>
                        <p>Loading chats...</p>
                    </div>
                </div>

                <div class="p-4 border-t border-gray-200 text-center">
                    <p class="text-xs text-gray-500">Social Cults CRM © {{ date('Y') }}</p>
                </div>
            </div>

            <!-- Chat Area -->
            <div class="w-full md:w-3/4 flex flex-col h-full bg-gray-50">

                <div class="p-4 border-b border-gray-200 bg-white flex justify-between items-center">
                    <div class="flex items-center">
                        <!-- Mobile menu toggle -->
                        <button id="mobileMenuBtn" class="mobile-menu-btn p-2 mr-2 rounded-lg hover:bg-gray-100"
                            onclick="toggleMobileSidebar()">
                            <i class="fas fa-bars text-gray-600"></i>
                        </button>
                        <div class="w-10 h-10 rounded-full bg-indigo-100 flex items-center justify-center mr-3"
                            id="chatAvatar">
                            <i class="fas fa-user text-indigo-600"></i>
                        </div>
                        <div>
                            <h3 class="font-bold" id="chatWithName">Select a chat</h3>
                            <p class="text-xs text-gray-500" id="chatTeamInfo">Team info</p>
                        </div>
                    </div>
                    <div id="participantCount" class="hidden">
                        <button onclick="showGroupMembers()"
                            class="text-sm text-gray-500 hover:text-indigo-600 transition cursor-pointer">
                            <i class="fas fa-users mr-1"></i><span id="participantNumber">0</span> members
                            <i class="fas fa-chevron-right text-xs ml-1"></i>
                        </button>
                    </div>
                </div>

                <!-- Members Modal -->
                <div id="membersModal"
                    class="fixed inset-0 bg-black bg-opacity-50 hidden z-50 flex items-center justify-center">
                    <div class="bg-white rounded-xl shadow-xl max-w-md w-full mx-4 max-h-[80vh] overflow-hidden">
                        <div
                            class="p-4 border-b border-gray-200 flex justify-between items-center bg-gradient-to-r from-indigo-500 to-purple-600 text-white">
                            <h3 class="font-bold" id="membersModalTitle">Group Members</h3>
                            <button onclick="closeMembersModal()" class="text-white hover:text-gray-200">
                                <i class="fas fa-times"></i>
                            </button>
                        </div>
                        <div class="p-4 overflow-y-auto max-h-96" id="membersList">
                            <p class="text-gray-500 text-center">Loading...</p>
                        </div>
                    </div>
                </div>

                <!-- Create Group Modal (Admin Only) -->
                @if(auth()->user()->hasRole('admin'))
                    <div id="createGroupModal"
                        class="fixed inset-0 bg-black bg-opacity-50 hidden z-50 flex items-center justify-center">
                        <div class="bg-white rounded-xl shadow-xl max-w-lg w-full mx-4 max-h-[90vh] overflow-hidden">
                            <!-- Modal Header -->
                            <div
                                class="p-4 border-b border-gray-200 flex justify-between items-center bg-gradient-to-r from-indigo-600 to-purple-600 text-white">
                                <h3 class="font-bold text-lg"><i class="fas fa-users mr-2"></i>Create New Group</h3>
                                <button onclick="closeCreateGroupModal()" class="text-white hover:text-gray-200">
                                    <i class="fas fa-times"></i>
                                </button>
                            </div>

                            <!-- Modal Body -->
                            <div class="p-4 overflow-y-auto max-h-[60vh]">
                                <!-- Group Name -->
                                <div class="mb-4">
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Group Name *</label>
                                    <input type="text" id="newGroupName"
                                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"
                                        placeholder="Enter group name...">
                                </div>

                                <!-- Add Clients Section -->
                                <div class="mb-4">
                                    <label class="block text-sm font-medium text-gray-700 mb-1">
                                        <i class="fas fa-briefcase text-sky-500 mr-1"></i> Add Clients
                                    </label>
                                    <div id="clientsCheckboxList"
                                        class="max-h-32 overflow-y-auto border border-gray-200 rounded-lg p-2 bg-gray-50">
                                        <p class="text-sm text-gray-400">Loading clients...</p>
                                    </div>
                                </div>

                                <!-- Add Team Members by Role -->
                                <div class="mb-4">
                                    <label class="block text-sm font-medium text-gray-700 mb-1">
                                        <i class="fas fa-user-tag text-indigo-500 mr-1"></i> Add Team Members by Role
                                    </label>
                                    <select id="roleSelector" class="w-full px-3 py-2 border border-gray-300 rounded-lg mb-2">
                                        <option value="">-- Select a Role --</option>
                                    </select>
                                    <div id="roleUsersCheckboxList"
                                        class="max-h-40 overflow-y-auto border border-gray-200 rounded-lg p-2 bg-gray-50 hidden">
                                        <p class="text-sm text-gray-400">Select a role to see users</p>
                                    </div>
                                </div>

                                <!-- Selected Members Preview -->
                                <div class="mb-2">
                                    <label class="block text-sm font-medium text-gray-700 mb-1">
                                        <i class="fas fa-check-circle text-green-500 mr-1"></i> Selected Members (<span
                                            id="selectedMemberCount">0</span>)
                                    </label>
                                    <div id="selectedMembersList"
                                        class="flex flex-wrap gap-2 min-h-[40px] border border-gray-200 rounded-lg p-2 bg-green-50">
                                        <p class="text-sm text-gray-400" id="noMembersText">No members selected yet</p>
                                    </div>
                                </div>
                            </div>

                            <!-- Modal Footer -->
                            <div class="p-4 border-t border-gray-200 flex justify-end gap-2">
                                <button onclick="closeCreateGroupModal()"
                                    class="px-4 py-2 text-gray-600 hover:bg-gray-100 rounded-lg transition">
                                    Cancel
                                </button>
                                <button onclick="submitCreateGroup()"
                                    class="px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition">
                                    <i class="fas fa-plus mr-1"></i> Create Group
                                </button>
                            </div>
                        </div>
                    </div>
                @endif

                <div class="flex-1 overflow-y-auto p-4" id="messagesArea">
                    <div class="flex flex-col items-center justify-center h-full text-gray-500" id="emptyChatState">
                        <i class="fas fa-comments text-4xl mb-4 text-gray-300"></i>
                        <p class="text-lg">Select a chat to start messaging</p>
                    </div>
                </div>

                <!-- Teams Panel (deprecated - now using sidebar subgroups) -->
                <div id="teamsPanel" class="hidden"></div>

                <div class="px-4 py-2 hidden" id="typingIndicator">
                    <div class="flex items-center">
                        <div class="bg-white p-3 rounded-xl shadow-sm">
                            <div class="typing-indicator">
                                <span class="typing-dot"></span>
                                <span class="typing-dot"></span>
                                <span class="typing-dot"></span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="p-4 border-t border-gray-200 bg-white">
                    <!-- Selected Team Indicator -->
                    <div id="selectedTeamIndicator"
                        class="hidden mb-2 px-3 py-1 bg-indigo-50 rounded-lg border border-indigo-200">
                        <span class="text-xs text-indigo-700">
                            <i class="fas fa-arrow-right mr-1"></i>
                            Sending to: <strong id="selectedTeamName">All</strong>
                        </span>
                    </div>
                    <div class="flex items-center">
                        <div class="flex-1 mx-2">
                            <input type="text" placeholder="Type a message..." id="messageInput"
                                class="w-full px-4 py-2 rounded-full border border-gray-300 focus:outline-none">
                        </div>
                        <button class="p-2 rounded-full bg-indigo-600 text-white ml-1 hover:bg-indigo-700"
                            id="sendMessageBtn">
                            <i class="fas fa-paper-plane"></i>
                        </button>
                    </div>
                </div>

            </div>

        </div>

        {{-- JAVASCRIPT LOGIC --}}
        {{-- ================== --}}

        <script>
            // Globals
            let activeChat = null;
            let activeChatType = null; // 'direct' or 'group'
            let currentTab = 'direct';
            let lastChats = [];
            let lastMessageCounts = new Map();
            let selectedTargetRole = null; // For client team targeting
            let groupTeams = []; // Teams in current group
            const csrfToken = document.querySelector('meta[name="csrf-token"]').content;
            const userRole = "{{ auth()->user()->getRoleNames()->first() ?? '' }}";
            const userId = {{ auth()->id() ?? 'null' }};
            const isAdmin = {{ auth()->user()->hasRole('admin') ? 'true' : 'false' }};
            const isClient = userRole.toLowerCase() === 'client';

            // DOM refs
            const chatListEl = document.getElementById('chatList');
            const messagesArea = document.getElementById('messagesArea');
            const emptyChatState = document.getElementById('emptyChatState');
            const chatWithName = document.getElementById('chatWithName');
            const chatTeamInfo = document.getElementById('chatTeamInfo');
            const messageInput = document.getElementById('messageInput');
            const typingIndicator = document.getElementById('typingIndicator');
            const directTab = document.getElementById('directTab');
            const groupTab = document.getElementById('groupTab');
            const userSelectionContainer = document.getElementById('userSelectionContainer');
            const groupChatContainer = document.getElementById('groupChatContainer');
            const participantCount = document.getElementById('participantCount');
            const participantNumber = document.getElementById('participantNumber');
            const chatAvatar = document.getElementById('chatAvatar');

            // Simple debounce function
            function debounce(fn, delay) {
                let timer;
                return (...args) => {
                    clearTimeout(timer);
                    timer = setTimeout(() => fn.apply(this, args), delay);
                };
            }

            // Mobile sidebar toggle
            function toggleMobileSidebar() {
                const sidebar = document.getElementById('chatSidebar');
                const overlay = document.getElementById('mobileOverlay');
                const isVisible = sidebar.classList.contains('sidebar-mobile-visible');

                if (isVisible) {
                    sidebar.classList.remove('sidebar-mobile-visible');
                    sidebar.classList.add('sidebar-mobile-hidden');
                    overlay.classList.add('hidden');
                } else {
                    sidebar.classList.remove('sidebar-mobile-hidden');
                    sidebar.classList.add('sidebar-mobile-visible');
                    overlay.classList.remove('hidden');
                }
            }

            // Format date for message grouping
            function formatMessageDate(dateStr) {
                const date = new Date(dateStr);
                const today = new Date();
                const yesterday = new Date(today);
                yesterday.setDate(yesterday.getDate() - 1);

                const isToday = date.toDateString() === today.toDateString();
                const isYesterday = date.toDateString() === yesterday.toDateString();

                if (isToday) return 'Today';
                if (isYesterday) return 'Yesterday';
                return date.toLocaleDateString('en-US', { weekday: 'short', month: 'short', day: 'numeric' });
            }

            // Clear active subgroup highlights
            function clearSubgroupHighlights() {
                document.querySelectorAll('.subgroup-active').forEach(el => {
                    el.classList.remove('subgroup-active');
                });
            }

            // Tab switching
            function switchTab(tab) {
                currentTab = tab;
                if (tab === 'direct') {
                    directTab.classList.remove('tab-inactive');
                    directTab.classList.add('tab-active');
                    groupTab.classList.remove('tab-active');
                    groupTab.classList.add('tab-inactive');
                    userSelectionContainer.classList.remove('hidden');
                    groupChatContainer.classList.add('hidden');
                } else {
                    groupTab.classList.remove('tab-inactive');
                    groupTab.classList.add('tab-active');
                    directTab.classList.remove('tab-active');
                    directTab.classList.add('tab-inactive');
                    userSelectionContainer.classList.add('hidden');
                    groupChatContainer.classList.remove('hidden');
                }
                loadChatList();
            }

            // Load initial UI config
            document.addEventListener('DOMContentLoaded', () => {
                fetchSameRoleUsers();
                loadChatList();
                if (isAdmin) {
                    setupCreateGroupModal();
                }
                setInterval(loadChatList, 10000);
                setInterval(() => {
                    if (activeChat) refreshMessages();
                }, 3000);
            });

            // ============================================
            // ADMIN CREATE GROUP MODAL FUNCTIONS
            // ============================================

            // Track selected members
            let selectedMembers = new Map(); // id -> {name, type: 'client'|'team'}

            // Setup Create Group Modal (Admin only)
            function setupCreateGroupModal() {
                const createBtn = document.getElementById('createGroupBtn');
                if (createBtn) {
                    createBtn.addEventListener('click', openCreateGroupModal);
                }

                const roleSelector = document.getElementById('roleSelector');
                if (roleSelector) {
                    roleSelector.addEventListener('change', loadRoleUsers);
                }
            }

            // Open Create Group Modal
            function openCreateGroupModal() {
                selectedMembers.clear();
                document.getElementById('newGroupName').value = '';
                updateSelectedMembersList();
                document.getElementById('createGroupModal').classList.remove('hidden');
                loadClientsForModal();
                loadRolesForModal();
            }

            // Close Create Group Modal
            function closeCreateGroupModal() {
                document.getElementById('createGroupModal').classList.add('hidden');
            }

            // Load clients for checkbox list
            function loadClientsForModal() {
                const container = document.getElementById('clientsCheckboxList');
                container.innerHTML = '<p class="text-sm text-gray-400">Loading...</p>';

                fetch('/chat/clients-list')
                    .then(res => res.json())
                    .then(clients => {
                        if (!clients || clients.length === 0) {
                            container.innerHTML = '<p class="text-sm text-gray-400">No clients found</p>';
                            return;
                        }

                        container.innerHTML = '';
                        clients.forEach(client => {
                            const label = document.createElement('label');
                            label.className = 'flex items-center p-1 hover:bg-gray-100 rounded cursor-pointer';
                            label.innerHTML = `
                                                                                                                                            <input type="checkbox" class="mr-2 client-checkbox" data-id="${client.id}" data-name="${escapeHtml(client.name)}">
                                                                                                                                            <span class="text-sm">${escapeHtml(client.name)}</span>
                                                                                                                                            <span class="text-xs text-gray-400 ml-1">(${escapeHtml(client.email)})</span>
                                                                                                                                                                   `;
                            label.querySelector('input').addEventListener('change', (e) => {
                                if (e.target.checked) {
                                    selectedMembers.set(client.id, { name: client.name, type: 'client' });
                                } else {
                                    selectedMembers.delete(client.id);
                                }
                                updateSelectedMembersList();
                            });
                            container.appendChild(label);
                        });
                    })
                    .catch(err => {
                        console.error('Error loading clients:', err);
                        container.innerHTML = '<p class="text-sm text-red-400">Error loading clients</p>';
                    });
            }

            // Load roles for dropdown
            function loadRolesForModal() {
                const selector = document.getElementById('roleSelector');
                selector.innerHTML = '<option value="">-- Select a Role --</option>';

                fetch('/chat/roles-list')
                    .then(res => res.json())
                    .then(roles => {
                        roles.forEach(role => {
                            const opt = document.createElement('option');
                            opt.value = role.id;
                            opt.textContent = `${role.name} (${role.user_count} users)`;
                            selector.appendChild(opt);
                        });
                    })
                    .catch(err => console.error('Error loading roles:', err));
            }

            // Load users for selected role
            function loadRoleUsers() {
                const roleId = document.getElementById('roleSelector').value;
                const container = document.getElementById('roleUsersCheckboxList');

                if (!roleId) {
                    container.classList.add('hidden');
                    return;
                }

                container.classList.remove('hidden');
                container.innerHTML = '<p class="text-sm text-gray-400">Loading users...</p>';

                fetch(`/chat/users-by-role/${roleId}`)
                    .then(res => res.json())
                    .then(data => {
                        if (!data.users || data.users.length === 0) {
                            container.innerHTML = '<p class="text-sm text-gray-400">No users in this role</p>';
                            return;
                        }

                        container.innerHTML = '';

                        // Select All checkbox
                        const selectAllLabel = document.createElement('label');
                        selectAllLabel.className = 'flex items-center p-1 hover:bg-indigo-100 rounded cursor-pointer font-medium border-b mb-1 pb-1';
                        selectAllLabel.innerHTML = `
                                                                                                                                        <input type="checkbox" class="mr-2" id="selectAllRoleUsers">
                                                                                                                                        <span class="text-sm text-indigo-600">Select All (${data.users.length})</span>
                                                                                                                                    `;
                        selectAllLabel.querySelector('input').addEventListener('change', (e) => {
                            const checkboxes = container.querySelectorAll('.role-user-checkbox');
                            checkboxes.forEach(cb => {
                                cb.checked = e.target.checked;
                                const id = parseInt(cb.dataset.id);
                                const name = cb.dataset.name;
                                if (e.target.checked) {
                                    selectedMembers.set(id, { name, type: 'team' });
                                } else {
                                    selectedMembers.delete(id);
                                }
                            });
                            updateSelectedMembersList();
                        });
                        container.appendChild(selectAllLabel);

                        // Individual user checkboxes
                        data.users.forEach(user => {
                            const label = document.createElement('label');
                            label.className = 'flex items-center p-1 hover:bg-gray-100 rounded cursor-pointer';
                            const isChecked = selectedMembers.has(user.id);
                            label.innerHTML = `
                                                                                                                                            <input type="checkbox" class="mr-2 role-user-checkbox" data-id="${user.id}" data-name="${escapeHtml(user.name)}" ${isChecked ? 'checked' : ''}>
                                                                                                                                            <span class="text-sm">${escapeHtml(user.name)}</span>
                                                                                                                                        `;
                            label.querySelector('input').addEventListener('change', (e) => {
                                if (e.target.checked) {
                                    selectedMembers.set(user.id, { name: user.name, type: 'team' });
                                } else {
                                    selectedMembers.delete(user.id);
                                }
                                updateSelectedMembersList();
                            });
                            container.appendChild(label);
                        });
                    })
                    .catch(err => {
                        console.error('Error loading role users:', err);
                        container.innerHTML = '<p class="text-sm text-red-400">Error loading users</p>';
                    });
            }

            // Update selected members preview list
            function updateSelectedMembersList() {
                const container = document.getElementById('selectedMembersList');
                const countSpan = document.getElementById('selectedMemberCount');
                const noMembersText = document.getElementById('noMembersText');

                countSpan.textContent = selectedMembers.size;

                if (selectedMembers.size === 0) {
                    container.innerHTML = '<p class="text-sm text-gray-400" id="noMembersText">No members selected yet</p>';
                    return;
                }

                container.innerHTML = '';
                selectedMembers.forEach((data, id) => {
                    const badge = document.createElement('span');
                    badge.className = `inline-flex items-center px-2 py-1 rounded-full text-xs ${data.type === 'client' ? 'bg-sky-100 text-sky-700' : 'bg-indigo-100 text-indigo-700'}`;
                    badge.innerHTML = `
                                                                                                                                    ${escapeHtml(data.name)}
                                                                                                                                    <button class="ml-1 hover:text-red-500" onclick="removeMemberFromSelection(${id})">
                                                                                                                                        <i class="fas fa-times"></i>
                                                                                                                                    </button>
                                                                                                                                `;
                    container.appendChild(badge);
                });
            }

            // Remove member from selection
            function removeMemberFromSelection(id) {
                selectedMembers.delete(id);

                // Uncheck the corresponding checkbox
                const checkbox = document.querySelector(`input[data-id="${id}"]`);
                if (checkbox) checkbox.checked = false;

                updateSelectedMembersList();
            }

            // Submit Create Group
            function submitCreateGroup() {
                const groupName = document.getElementById('newGroupName').value.trim();

                if (!groupName) {
                    alert('Please enter a group name');
                    return;
                }

                const memberIds = Array.from(selectedMembers.keys());

                fetch('/chat/create-group', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken
                    },
                    body: JSON.stringify({
                        name: groupName,
                        member_ids: memberIds
                    })
                })
                    .then(res => res.json())
                    .then(data => {
                        if (data.error) {
                            alert(data.error);
                            return;
                        }

                        closeCreateGroupModal();
                        loadChatList();
                        switchTab('group');

                        // Open the new group
                        setTimeout(() => {
                            openChat(data.group.id, data.group.name, 'group', data.group.participant_count);
                        }, 500);
                    })
                    .catch(err => {
                        console.error('Error creating group:', err);
                        alert('Failed to create group');
                    });
            }

            // ============================================
            // CLIENT TEAM SELECTION FUNCTIONS
            // ============================================

            // Load teams in a group (for clients)
            function loadGroupTeams(conversationId) {
                const teamsPanel = document.getElementById('teamsPanel');
                const teamsList = document.getElementById('teamsList');

                fetch(`/chat/group-teams/${conversationId}`)
                    .then(res => res.json())
                    .then(teams => {
                        groupTeams = teams;

                        if (!teams || teams.length === 0) {
                            teamsPanel.classList.add('hidden');
                            return;
                        }

                        teamsPanel.classList.remove('hidden');
                        teamsList.innerHTML = '';

                        // Add "Send to All" button
                        const allBtn = document.createElement('button');
                        allBtn.className = 'px-3 py-1.5 rounded-full text-xs font-medium transition bg-gray-200 text-gray-700 hover:bg-gray-300';
                        allBtn.innerHTML = '<i class="fas fa-users mr-1"></i> All Teams';
                        allBtn.dataset.roleId = '';
                        allBtn.onclick = () => selectTeam(null, 'All Teams');
                        teamsList.appendChild(allBtn);

                        // Add team buttons
                        teams.forEach(team => {
                            const btn = document.createElement('button');
                            btn.className = 'px-3 py-1.5 rounded-full text-xs font-medium transition bg-indigo-100 text-indigo-700 hover:bg-indigo-200 border border-indigo-200';
                            btn.innerHTML = `<i class="fas fa-user-tie mr-1"></i> ${escapeHtml(team.name)} (${team.member_count})`;
                            btn.dataset.roleId = team.id;
                            btn.onclick = () => selectTeam(team.id, team.name);
                            teamsList.appendChild(btn);
                        });
                    })
                    .catch(err => {
                        console.error('Error loading teams:', err);
                        teamsPanel.classList.add('hidden');
                    });
            }

            // Select a team to message
            function selectTeam(roleId, roleName) {
                selectedTargetRole = roleId;

                const indicator = document.getElementById('selectedTeamIndicator');
                const teamNameEl = document.getElementById('selectedTeamName');
                const clearBtn = document.getElementById('clearTeamBtn');

                if (roleId) {
                    teamNameEl.textContent = `@${roleName}`;
                    indicator.classList.remove('hidden');
                    clearBtn.style.display = 'inline';

                    // Update button styles
                    document.querySelectorAll('#teamsList button').forEach(btn => {
                        if (btn.dataset.roleId == roleId) {
                            btn.className = 'px-3 py-1.5 rounded-full text-xs font-medium transition bg-indigo-600 text-white ring-2 ring-indigo-300';
                        } else {
                            btn.className = 'px-3 py-1.5 rounded-full text-xs font-medium transition bg-indigo-100 text-indigo-700 hover:bg-indigo-200 border border-indigo-200';
                            if (btn.dataset.roleId === '') {
                                btn.className = 'px-3 py-1.5 rounded-full text-xs font-medium transition bg-gray-200 text-gray-700 hover:bg-gray-300';
                            }
                        }
                    });
                } else {
                    teamNameEl.textContent = 'All Teams';
                    indicator.classList.add('hidden');
                    clearBtn.style.display = 'none';

                    // Reset all button styles
                    document.querySelectorAll('#teamsList button').forEach(btn => {
                        if (btn.dataset.roleId === '') {
                            btn.className = 'px-3 py-1.5 rounded-full text-xs font-medium transition bg-gray-300 text-gray-800 ring-2 ring-gray-400';
                        } else {
                            btn.className = 'px-3 py-1.5 rounded-full text-xs font-medium transition bg-indigo-100 text-indigo-700 hover:bg-indigo-200 border border-indigo-200';
                        }
                    });
                }
            }

            // Clear team selection
            function clearTeamSelection() {
                selectTeam(null, 'All Teams');
            }

            // Show group members modal
            function showGroupMembers() {
                if (!activeChat || activeChatType !== 'group') return;

                const modal = document.getElementById('membersModal');
                const membersList = document.getElementById('membersList');
                const modalTitle = document.getElementById('membersModalTitle');

                modal.classList.remove('hidden');
                membersList.innerHTML = '<p class="text-gray-500 text-center">Loading...</p>';

                fetch(`/chat/group-members/${activeChat}`)
                    .then(res => res.json())
                    .then(data => {
                        if (data.error) {
                            membersList.innerHTML = `<p class="text-red-500 text-center">${data.error}</p>`;
                            return;
                        }

                        modalTitle.textContent = `${data.group_name} - ${data.member_count} Members`;

                        if (!data.members || data.members.length === 0) {
                            membersList.innerHTML = '<p class="text-gray-500 text-center">No members found</p>';
                            return;
                        }

                        membersList.innerHTML = '';
                        data.members.forEach(member => {
                            const div = document.createElement('div');
                            div.className = 'flex items-center justify-between p-3 bg-gray-50 rounded-lg mb-2';

                            const clientBadge = member.is_client
                                ? '<span class="client-badge ml-2">🏢 Client</span>'
                                : '';

                            div.innerHTML = `
                                                                                                                                                                        <div class="flex items-center">
                                                                                                                                                                            <div class="w-10 h-10 rounded-full ${member.is_client ? 'bg-amber-100' : 'bg-indigo-100'} flex items-center justify-center mr-3">
                                                                                                                                                                                <i class="fas ${member.is_client ? 'fa-building text-amber-600' : 'fa-user text-indigo-600'}"></i>
                                                                                                                                                                            </div>
                                                                                                                                                                            <div>
                                                                                                                                                                                <p class="font-medium text-gray-800">${escapeHtml(member.name)}${clientBadge}</p>
                                                                                                                                                                                <p class="text-xs text-gray-500">${escapeHtml(member.email)}</p>
                                                                                                                                                                            </div>
                                                                                                                                                                        </div>
                                                                                                                                                                    `;
                            membersList.appendChild(div);
                        });
                    })
                    .catch(err => {
                        console.error('Error loading members:', err);
                        membersList.innerHTML = '<p class="text-red-500 text-center">Failed to load members</p>';
                    });
            }

            // Close members modal
            function closeMembersModal() {
                document.getElementById('membersModal').classList.add('hidden');
            }

            // Close modal on outside click
            document.getElementById('membersModal')?.addEventListener('click', function (e) {
                if (e.target === this) {
                    closeMembersModal();
                }
            });

            // Fetch chat list based on current tab
            function loadChatList() {
                fetch('/chat/list')
                    .then(r => r.json())
                    .then(data => {
                        renderChatList(data);
                    })
                    .catch(err => {
                        console.error('Error fetching chats', err);
                    });
            }

            // Render chat list filtered by current tab
            function renderChatList(chats) {
                if (!Array.isArray(chats)) return;

                const filteredChats = chats.filter(c => {
                    if (currentTab === 'direct') return c.type === 'direct' || !c.type;
                    return c.type === 'group';
                });

                if (JSON.stringify(filteredChats) === JSON.stringify(lastChats)) return;
                lastChats = filteredChats;

                if (filteredChats.length === 0) {
                    const emptyMsg = currentTab === 'direct'
                        ? 'No direct chats yet. Select a user above to start chatting.'
                        : 'No group chats yet. Click "Join My Role Group" to get started.';
                    chatListEl.innerHTML = `
                            <div class="p-8 text-center text-gray-500">
                                <i class="fas fa-${currentTab === 'direct' ? 'user' : 'users'} text-3xl mb-4 text-gray-300"></i>
                                <p>${emptyMsg}</p>
                            </div>
                        `;
                    return;
                }

                chatListEl.innerHTML = '';
                filteredChats.forEach(chat => {
                    const isGroup = chat.type === 'group';
                    const name = chat.name || 'Chat';
                    const last = chat.last_message || 'No messages yet';
                    // User requested ONLY hr and min
                    const ts = chat.last_message_at ? new Date(chat.last_message_at).toLocaleTimeString([], { hour: '2-digit', minute: '2-digit', hour12: false }) : '';

                    // Unread badge logic
                    const unreadBadge = (chat.unread_count > 0)
                        ? `<span class="ml-2 bg-red-500 text-white text-xs font-bold px-2 py-0.5 rounded-full shadow-sm min-w-[20px] text-center">${chat.unread_count}</span>`
                        : '';

                    if (isGroup) {
                        const showSubgroups = isAdmin || isClient;

                        if (showSubgroups) {
                            // Admin/Client: Expandable
                            const groupContainer = document.createElement('div');
                            groupContainer.className = 'group-container border-b border-gray-100';
                            groupContainer.dataset.groupId = chat.id;

                            const header = document.createElement('div');
                            header.className = 'p-3 hover:bg-gray-50 cursor-pointer flex items-center transition-colors';
                            header.innerHTML = `
                                    <i class="fas fa-chevron-right text-gray-400 mr-2 transition-transform duration-200 expand-icon text-xs"></i>
                                    <div class="w-12 h-12 rounded-full bg-gradient-to-br from-indigo-500 to-purple-600 flex items-center justify-center mr-3 relative flex-shrink-0">
                                        <i class="fas fa-users text-white text-lg"></i>
                                    </div>
                                    <div class="flex-1 min-w-0 grid grid-cols-[1fr_auto] gap-x-2">
                                        <div class="flex items-center min-w-0">
                                            <h4 class="font-semibold text-gray-900 truncate">${escapeHtml(name)}</h4>
                                            ${unreadBadge}
                                        </div>
                                        <span class="text-xs text-gray-400 whitespace-nowrap">${ts}</span>

                                        <div class="col-span-2 flex items-center mt-0.5">
                                            <span class="text-[10px] bg-purple-100 text-purple-700 px-1.5 py-0.5 rounded-full mr-2 flex-shrink-0">
                                                ${chat.participant_count || 0} members
                                            </span>
                                        </div>
                                    </div>
                                `;

                            // Subgroups container
                            const subgroupsContainer = document.createElement('div');
                            subgroupsContainer.className = 'subgroups-container hidden pl-8 bg-slate-50';
                            subgroupsContainer.dataset.loaded = 'false';

                            header.addEventListener('click', () => {
                                const icon = header.querySelector('.expand-icon');
                                const isExpanded = !subgroupsContainer.classList.contains('hidden');
                                if (isExpanded) {
                                    subgroupsContainer.classList.add('hidden');
                                    icon.style.transform = 'rotate(0deg)';
                                } else {
                                    subgroupsContainer.classList.remove('hidden');
                                    icon.style.transform = 'rotate(90deg)';
                                    if (subgroupsContainer.dataset.loaded === 'false') {
                                        loadGroupSubgroups(chat.id, subgroupsContainer, name);
                                    }
                                }
                            });

                            groupContainer.appendChild(header);
                            groupContainer.appendChild(subgroupsContainer);
                            chatListEl.appendChild(groupContainer);
                        } else {
                            // Team Member Group
                            const el = document.createElement('div');
                            el.className = 'p-3 border-b border-gray-100 hover:bg-gray-50 cursor-pointer transition-colors';
                            el.dataset.chatId = chat.id;
                            el.dataset.chatType = 'group';

                            el.innerHTML = `
                                    <div class="flex items-center">
                                        <div class="w-12 h-12 rounded-full bg-gradient-to-br from-indigo-500 to-purple-600 flex items-center justify-center mr-3 flex-shrink-0">
                                            <i class="fas fa-users text-white text-lg"></i>
                                        </div>
                                        <div class="flex-1 min-w-0 grid grid-cols-[1fr_auto] gap-x-2">
                                            <div class="flex items-center min-w-0">
                                                <h4 class="font-semibold text-gray-900 truncate">${escapeHtml(name)}</h4>
                                                ${unreadBadge}
                                            </div>
                                            <span class="text-xs text-gray-400 whitespace-nowrap">${ts}</span>

                                            <div class="col-span-2 flex items-center mt-0.5 min-w-0">
                                                 <span class="text-[10px] bg-purple-100 text-purple-700 px-1.5 py-0.5 rounded-full mr-2 flex-shrink-0">
                                                    ${chat.participant_count || 0} members
                                                </span>
                                                <p class="text-sm text-gray-500 truncate flex-1">${escapeHtml(last)}</p>
                                            </div>
                                        </div>
                                    </div>
                                `;
                            el.addEventListener('click', () => {
                                openChat(chat.id, name, 'group', chat.participant_count || 0);
                            });
                            chatListEl.appendChild(el);
                        }
                    } else {
                        // Direct Chat
                        const el = document.createElement('div');
                        el.className = 'p-3 border-b border-gray-100 hover:bg-gray-50 cursor-pointer transition-colors';
                        el.dataset.chatId = chat.id;
                        el.dataset.chatType = 'direct';

                        el.innerHTML = `
                                <div class="flex items-center">
                                    <div class="w-12 h-12 rounded-full bg-indigo-100 flex items-center justify-center mr-3 flex-shrink-0">
                                        <span class="text-indigo-700 font-bold text-lg">${(name[0] || 'U').toUpperCase()}</span>
                                    </div>
                                    <div class="flex-1 min-w-0 grid grid-cols-[1fr_auto] gap-x-2">
                                        <div class="flex items-center min-w-0">
                                            <h4 class="font-semibold text-gray-900 truncate">${escapeHtml(name)}</h4>
                                            ${unreadBadge}
                                        </div>
                                        <span class="text-xs text-gray-400 whitespace-nowrap">${ts}</span>

                                        <div class="col-span-2 mt-0.5 min-w-0">
                                            <p class="text-sm text-gray-500 truncate">${escapeHtml(last)}</p>
                                        </div>
                                    </div>
                                </div>
                            `;
                        el.addEventListener('click', () => {
                            openChat(chat.id, name, 'direct', 0);
                        });
                        chatListEl.appendChild(el);
                    }
                });
            }

            // Load subgroups (role channels) for a group
            function loadGroupSubgroups(groupId, container, groupName) {
                container.innerHTML = '<div class="py-2 text-xs text-gray-400 text-center">Loading channels...</div>';

                fetch(`/chat/group-teams/${groupId}`)
                    .then(res => res.json())
                    .then(teams => {
                        container.innerHTML = '';
                        container.dataset.loaded = 'true';

                        // Calculate total members across all teams
                        const totalMembers = teams.reduce((sum, t) => sum + (t.member_count || 0), 0);

                        // "All Teams" option (messages visible to everyone)
                        const allTeamsBtn = document.createElement('div');
                        allTeamsBtn.className = 'p-3 hover:bg-indigo-50 cursor-pointer flex items-center border-l-2 border-transparent hover:border-indigo-400 subgroup-item';
                        allTeamsBtn.dataset.targetRoleId = '';
                        allTeamsBtn.innerHTML = `
                                                                                        <i class="fas fa-users text-gray-500 mr-3"></i>
                                                                                        <span class="text-sm font-medium text-gray-700">All Teams</span>
                                                                                        <span class="ml-auto text-xs bg-gray-100 text-gray-600 px-2 py-0.5 rounded-full">${totalMembers}</span>
                                                                                    `;
                        allTeamsBtn.addEventListener('click', (e) => {
                            e.stopPropagation();
                            openChat(groupId, `${groupName} - All Teams`, 'group', totalMembers, null);
                        });
                        container.appendChild(allTeamsBtn);

                        // Role subgroups
                        if (teams && teams.length > 0) {
                            teams.forEach(team => {
                                const teamBtn = document.createElement('div');
                                teamBtn.className = 'p-3 hover:bg-indigo-50 cursor-pointer flex items-center border-l-2 border-transparent hover:border-indigo-400 subgroup-item';
                                teamBtn.dataset.targetRoleId = team.id;
                                teamBtn.innerHTML = `
                                                            <i class="fas fa-user-tie text-indigo-500 mr-3"></i>
                                                            <span class="text-sm font-medium text-gray-700">${escapeHtml(team.name)}</span>
                                                            <span class="ml-auto text-xs bg-indigo-100 text-indigo-600 px-2 py-0.5 rounded-full">${team.member_count}</span>
                                                        `;
                                teamBtn.addEventListener('click', (e) => {
                                    e.stopPropagation();
                                    // Clear previous highlights and add new
                                    clearSubgroupHighlights();
                                    teamBtn.classList.add('subgroup-active');
                                    // Close mobile sidebar
                                    const sidebar = document.getElementById('chatSidebar');
                                    if (sidebar.classList.contains('sidebar-mobile-visible')) {
                                        toggleMobileSidebar();
                                    }
                                    openChat(groupId, `${groupName} - @${team.name}`, 'group', team.member_count || 0, team.id);
                                });
                                container.appendChild(teamBtn);
                            });
                        }
                    })
                    .catch(err => {
                        console.error('Error loading subgroups:', err);
                        container.innerHTML = '<div class="py-2 text-xs text-red-400 text-center">Failed to load</div>';
                    });
            }

            // Open chat (with optional targetRoleId for subgroup context)
            function openChat(id, name, type, participantCnt = 0, targetRoleId = null) {
                activeChat = id;
                activeChatType = type;
                chatWithName.textContent = name;
                emptyChatState.classList.add('hidden');

                // Set the active subgroup context (used for message filtering in backend)
                selectedTargetRole = targetRoleId;
                groupTeams = [];

                // Hide old teams panel (no longer used)
                document.getElementById('teamsPanel').classList.add('hidden');

                // Show subgroup indicator if targeting a specific team
                const indicator = document.getElementById('selectedTeamIndicator');
                if (targetRoleId) {
                    indicator.classList.remove('hidden');
                    document.getElementById('selectedTeamName').textContent = name.includes('@') ? name.split('@')[1] : 'Specific Team';
                } else if (type === 'group') {
                    indicator.classList.remove('hidden');
                    document.getElementById('selectedTeamName').textContent = 'All Teams';
                } else {
                    indicator.classList.add('hidden');
                }

                // Update header based on chat type
                if (type === 'group') {
                    chatAvatar.innerHTML = '<i class="fas fa-users text-white"></i>';
                    chatAvatar.className = 'w-10 h-10 rounded-full bg-gradient-to-br from-indigo-500 to-purple-600 flex items-center justify-center mr-3';
                    chatTeamInfo.textContent = targetRoleId ? 'Team Channel' : 'Group Chat';
                    participantCount.classList.remove('hidden');

                    // Fetch actual participant count from API
                    fetch('/chat/role-groups')
                        .then(res => res.json())
                        .then(groups => {
                            const thisGroup = groups.find(g => g.id === id);
                            if (thisGroup) {
                                participantNumber.textContent = thisGroup.participant_count || 0;
                            } else {
                                participantNumber.textContent = participantCnt;
                            }
                        })
                        .catch(() => {
                            participantNumber.textContent = participantCnt;
                        });
                } else {
                    chatAvatar.innerHTML = '<i class="fas fa-user text-indigo-600"></i>';
                    chatAvatar.className = 'w-10 h-10 rounded-full bg-indigo-100 flex items-center justify-center mr-3';
                    chatTeamInfo.textContent = '';
                    participantCount.classList.add('hidden');
                }

                // Build context key for message count tracking (includes subgroup)
                const contextKey = targetRoleId !== null && targetRoleId !== undefined ? `${id}_${targetRoleId}` : `${id}`;
                lastMessageCounts.set(contextKey, 0);  // Reset for fresh load

                // Clear messages immediately and show loading
                messagesArea.innerHTML = `<div class="p-6 text-center text-gray-400">
                                                        <i class="fas fa-spinner fa-spin text-2xl mb-2"></i>
                                                        <p>Loading messages...</p>
                                                    </div>`;

                // Build URL with optional target_role_id for subgroup filtering
                let messagesUrl = `/chat/messages/${id}`;
                if (targetRoleId !== undefined && targetRoleId !== null) {
                    messagesUrl += `?target_role_id=${targetRoleId}`;
                }

                console.log('[DEBUG] Fetching messages URL:', messagesUrl, 'targetRoleId:', targetRoleId);

                // Capture context at fetch time to prevent stale renders
                const fetchedChatId = id;
                const fetchedTargetRole = targetRoleId;

                fetch(messagesUrl)
                    .then(r => {
                        if (!r.ok) throw r;
                        return r.json();
                    })
                    .then(data => {
                        // GUARD: Only render if this is still the active chat context
                        // (prevents stale fetch results from overwriting newer ones)
                        if (activeChat !== fetchedChatId || selectedTargetRole !== fetchedTargetRole) {
                            console.log('[DEBUG] Ignoring stale fetch result. Current:', activeChat, selectedTargetRole, 'Fetched:', fetchedChatId, fetchedTargetRole);
                            return;
                        }

                        console.log('[DEBUG] Messages received:', data.length, 'messages');
                        data.sort((a, b) => new Date(a.created_at) - new Date(b.created_at));
                        renderMessagesFromDB(data);
                    })
                    .catch(err => {
                        console.error('Error loading messages', err);
                        // Only show error if still relevant
                        if (activeChat === fetchedChatId && selectedTargetRole === fetchedTargetRole) {
                            messagesArea.innerHTML = `<div class="p-6 text-center text-red-500">Unable to load messages</div>`;
                        }
                    });
            }

            // Render messages
            function renderMessagesFromDB(messages) {
                if (!Array.isArray(messages)) return;

                const chatId = activeChat;
                const chatContext = selectedTargetRole;
                const contextKey = (chatContext !== null && chatContext !== undefined) ? `${chatId}_${chatContext}` : `${chatId}`;

                console.log('[DEBUG] Render - contextKey:', contextKey, 'selectedTargetRole:', selectedTargetRole, 'messages:', messages.length);

                // ALWAYS clear and render fresh (simplified to fix the bug)
                messagesArea.innerHTML = '';

                if (messages.length === 0) {
                    messagesArea.innerHTML = `<div class="p-6 text-center text-gray-400">
                                                            <i class="fas fa-comments text-3xl mb-2"></i>
                                                            <p>No messages yet in this channel</p>
                                                        </div>`;
                } else {
                    let lastDate = null;
                    messages.forEach(msg => {
                        // Check for date change
                        const msgDate = new Date(msg.created_at).toDateString();
                        if (msgDate !== lastDate) {
                            const separator = document.createElement('div');
                            separator.className = 'date-separator';
                            separator.innerHTML = `<span>${formatMessageDate(msg.created_at)}</span>`;
                            messagesArea.appendChild(separator);
                            lastDate = msgDate;
                        }

                        appendMessage(msg, '');
                    });
                }

                lastMessageCounts.set(contextKey, messages.length);

                setTimeout(() => {
                    messagesArea.scrollTop = messagesArea.scrollHeight;
                }, 10);
            }

            // Append message
            function appendMessage(msg, extraClass = '') {
                const isSent = (msg.sender_id == userId);
                const wrapper = document.createElement('div');
                wrapper.className = `flex ${isSent ? 'justify-end' : 'justify-start'} mb-4 message-transition ${extraClass}`;

                // Show badge for group chats when sender is a client or admin
                let roleBadge = '';
                if (activeChatType === 'group' && !isSent) {
                    if (msg.is_admin) {
                        roleBadge = '<span class="ml-2 px-2 py-0.5 text-xs rounded-full bg-indigo-100 text-indigo-700">Admin</span>';
                    } else if (msg.is_client) {
                        roleBadge = '<span class="ml-2 px-2 py-0.5 text-xs rounded-full bg-sky-100 text-sky-700">Client</span>';
                    }
                }

                // Team target indicator (shows which team the message was sent to)
                let targetBadge = '';
                if (msg.target_role_name && activeChatType === 'group') {
                    targetBadge = `<span class="ml-2 px-2 py-0.5 text-xs rounded-full bg-purple-100 text-purple-700">
                                                                                                        <i class="fas fa-arrow-right mr-1"></i>@${escapeHtml(msg.target_role_name)}
                                                                                                    </span>`;
                }

                const senderName = activeChatType === 'group' && !isSent
                    ? `<div class="text-xs text-gray-500 mb-1 ml-10">${escapeHtml(msg.sender_name || 'User')}${roleBadge}${targetBadge}</div>`
                    : '';

                if (isSent) {
                    // Show target badge on sent messages if targeting a team
                    const sentTargetBadge = msg.target_role_name
                        ? `<div class="text-xs text-purple-600 mb-1 text-right"><i class="fas fa-arrow-right mr-1"></i>@${escapeHtml(msg.target_role_name)}</div>`
                        : '';

                    wrapper.innerHTML = `
                                                                                                                                                            <div class="max-w-xs lg:max-w-md">
                                                                                                                                                                ${sentTargetBadge}
                                                                                                                                                                <div class="bg-indigo-600 text-white p-3 rounded-xl chat-bubble-right shadow-sm">
                                                                                                                                                                    <p>${escapeHtml(msg.message)}</p>
                                                                                                                                                                </div>
                                                                                                                                                                <div class="text-xs text-gray-500 mt-1 text-right">${new Date(msg.created_at).toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' })} <i class="fas fa-check-double text-indigo-500 ml-1"></i></div>
                                                                                                                                                            </div>
                                                                                                                                                        `;
                } else {
                    // Special styling for client and admin messages
                    let messageBoxClass = 'bg-white p-3 rounded-xl chat-bubble-left shadow-sm';
                    let avatarClass = 'bg-gray-200';
                    let avatarIcon = 'fa-user text-gray-500';
                    let textClass = 'text-gray-800';

                    if (msg.is_admin) {
                        messageBoxClass =
                            'bg-indigo-50 p-3 rounded-xl shadow-sm';
                        avatarClass =
                            'bg-indigo-100 ring-2 ring-indigo-500';
                        avatarIcon =
                            'fa-user-shield text-indigo-600';
                        textClass =
                            'text-slate-900';
                        headerClass =
                            'text-indigo-700';
                    }

                    else if (msg.is_client) {
                        messageBoxClass =
                            'bg-sky-50 p-3 rounded-xl shadow-sm';
                        avatarClass =
                            'bg-sky-100 ring-2 ring-sky-500';
                        avatarIcon =
                            'fa-briefcase text-sky-600';
                        textClass =
                            'text-slate-800';
                        headerClass =
                            'text-sky-700';
                    }



                    wrapper.innerHTML = `
                                                                                                                                                            <div class="max-w-xs lg:max-w-md">
                                                                                                                                                                ${senderName}
                                                                                                                                                                <div class="flex items-end">
                                                                                                                                                                    <div class="w-8 h-8 rounded-full ${avatarClass} flex items-center justify-center mr-2 flex-shrink-0">
                                                                                                                                                                        <i class="fas ${avatarIcon} text-xs"></i>
                                                                                                                                                                    </div>
                                                                                                                                                                    <div class="${messageBoxClass}">
                                                                                                                                                                        <p class="${textClass}">${escapeHtml(msg.message)}</p>
                                                                                                                                                                    </div>
                                                                                                                                                                </div>
                                                                                                                                                                <div class="text-xs text-gray-500 mt-1 ml-10">${new Date(msg.created_at).toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' })}</div>
                                                                                                                                                            </div>
                                                                                                                                                        `;
                }
                messagesArea.appendChild(wrapper);
            }

            // Send message
            document.getElementById('sendMessageBtn').addEventListener('click', sendMessage);
            messageInput.addEventListener('keypress', function (e) {
                if (e.key === 'Enter') sendMessage();
            });

            function sendMessage() {
                const text = messageInput.value.trim();
                if (!text || !activeChat) return;

                const now = new Date().toISOString();

                // Get target role info for optimistic update
                const targetRoleId = selectedTargetRole;
                const targetRoleName = targetRoleId ? groupTeams.find(t => t.id == targetRoleId)?.name : null;

                const optimisticMsg = {
                    id: Date.now(),
                    message: text,
                    sender_id: userId,
                    sender_name: '{{ auth()->user()->name ?? auth()->user()->email }}',
                    target_role_id: targetRoleId,
                    target_role_name: targetRoleName,
                    created_at: now
                };
                appendMessage(optimisticMsg);

                const chatId = activeChat;
                const currentCount = lastMessageCounts.get(chatId) || 0;
                lastMessageCounts.set(chatId, currentCount + 1);

                messageInput.value = '';
                messagesArea.scrollTop = messagesArea.scrollHeight;

                // Build request body with target_role_id
                const requestBody = {
                    conversation_id: activeChat,
                    message: text
                };
                if (targetRoleId) {
                    requestBody.target_role_id = targetRoleId;
                }

                fetch('/chat/send', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken
                    },
                    body: JSON.stringify(requestBody)
                })
                    .then(r => {
                        if (!r.ok) throw r;
                        return r.json();
                    })
                    .then(data => {
                        refreshMessages();
                    })
                    .catch(err => {
                        console.error('Send error', err);
                        alert('Unable to send message');
                        const lastChild = messagesArea.lastElementChild;
                        if (lastChild) lastChild.remove();
                        lastMessageCounts.set(chatId, currentCount);
                    });
            }

            // Refresh messages
            const debouncedRefreshMessages = debounce(() => {
                if (!activeChat) return;

                // Build URL with target_role_id if viewing a subgroup
                let refreshUrl = `/chat/messages/${activeChat}`;
                if (selectedTargetRole !== null && selectedTargetRole !== undefined) {
                    refreshUrl += `?target_role_id=${selectedTargetRole}`;
                }

                fetch(refreshUrl)
                    .then(r => r.json())
                    .then(data => {
                        data.sort((a, b) => new Date(a.created_at) - new Date(b.created_at));
                        renderMessagesFromDB(data);
                    })
                    .catch(err => console.error('Refresh error', err));
            }, 300);

            function refreshMessages() {
                debouncedRefreshMessages();
            }

            // Escape html
            function escapeHtml(unsafe) {
                if (unsafe === null || unsafe === undefined) return '';
                return String(unsafe)
                    .replaceAll('&', '&amp;')
                    .replaceAll('<', '&lt;')
                    .replaceAll('>', '&gt;')
                    .replaceAll('"', '&quot;')
                    .replaceAll("'", '&#039;');
            }

            // Fetch same-role users for direct chat
            const clientSelect = document.getElementById('clientSelectSidebar');
            const startChatBtn = document.getElementById('startChatBtn');

            function fetchSameRoleUsers() {
                clientSelect.innerHTML = '<option value="">Loading users...</option>';
                startChatBtn.style.display = 'none';

                fetch('/chat/same-role-users')
                    .then(res => res.json())
                    .then(users => {
                        clientSelect.innerHTML = '<option value="">-- Select User --</option>';
                        if (users.length === 0) {
                            clientSelect.innerHTML += '<option value="" disabled>No users with same role</option>';
                            return;
                        }
                        users.forEach(u => {
                            clientSelect.innerHTML += `<option value="${u.id}">${u.name || u.email}</option>`;
                        });
                    })
                    .catch(err => {
                        console.error("Error fetching users:", err);
                        clientSelect.innerHTML = '<option value="">Error loading users</option>';
                    });
            }

            if (clientSelect) {
                clientSelect.addEventListener('change', function () {
                    if (this.value) {
                        startChatBtn.style.display = 'block';
                        startChatBtn.classList.remove('hidden');
                    } else {
                        startChatBtn.style.display = 'none';
                    }
                });
            }

            startChatBtn.addEventListener('click', function () {
                const selectedUserId = clientSelect.value;
                if (!selectedUserId) {
                    alert("Please select a user.");
                    return;
                }

                fetch('/chat/create-conversation', {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json",
                        "X-CSRF-TOKEN": csrfToken
                    },
                    body: JSON.stringify({
                        team_id: userId,
                        client_id: selectedUserId
                    })
                })
                    .then(res => res.json())
                    .then(convo => {
                        if (!convo.id) {
                            console.error(convo);
                            alert(convo.message || "Conversation could not be created.");
                            return;
                        }

                        const userName = clientSelect.options[clientSelect.selectedIndex].text;
                        openChat(convo.id, userName, 'direct', 0);
                        loadChatList();
                    })
                    .catch(err => {
                        console.error("Error creating conversation", err);
                        alert("Failed to create conversation.");
                    });
            });

            // Join or create role group
            function joinOrCreateRoleGroup() {
                const btn = document.getElementById('joinRoleGroupBtn');
                if (btn) {
                    btn.disabled = true;
                    btn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Joining...';
                }

                fetch('/chat/get-or-create-role-group', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken
                    }
                })
                    .then(res => res.json())
                    .then(data => {
                        if (data.error) {
                            alert(data.error);
                            return;
                        }

                        // Switch to group tab and open the group
                        switchTab('group');
                        loadChatList();

                        // Disable button since user has joined
                        if (btn) {
                            btn.disabled = true;
                            btn.classList.remove('from-indigo-500', 'to-purple-600', 'hover:from-indigo-600', 'hover:to-purple-700');
                            btn.classList.add('bg-green-500', 'cursor-not-allowed');
                            btn.innerHTML = '<i class="fas fa-check-circle mr-2"></i>Already in Role Group';
                            const infoText = btn.nextElementSibling;
                            if (infoText) {
                                infoText.textContent = 'You have joined the ' + userRole + ' group';
                            }
                        }

                        setTimeout(() => {
                            openChat(data.id, data.name, 'group', 0);
                        }, 500);
                    })
                    .catch(err => {
                        console.error('Error joining group:', err);
                        alert('Failed to join group');
                        // Re-enable button on error
                        if (btn) {
                            btn.disabled = false;
                            btn.innerHTML = '<i class="fas fa-users mr-2"></i>Join My Role Group';
                        }
                    });
            }

            // Search toggle
            document.getElementById('searchToggle').addEventListener('click', function () {
                const searchBar = document.getElementById('searchBar');
                searchBar.classList.toggle('hidden');
            });

            // Search filter
            document.getElementById('chatSearch').addEventListener('input', function (e) {
                const query = e.target.value.toLowerCase();
                const items = chatListEl.querySelectorAll('[data-chat-id]');
                items.forEach(item => {
                    const name = item.querySelector('h4').textContent.toLowerCase();
                    if (name.includes(query)) {
                        item.style.display = '';
                    } else {
                        item.style.display = 'none';
                    }
                });
            });

            // Custom Dropdown Logic
            const nativeSelect = document.getElementById('clientSelectSidebar');
            const customTrigger = document.getElementById('customTrigger');
            const customTriggerText = document.getElementById('customTriggerText');
            const customOptions = document.getElementById('customOptions');
            const customDropdown = document.getElementById('customDropdown');

            if (nativeSelect && customTrigger && customOptions) {
                // Toggle
                customTrigger.addEventListener('click', (e) => {
                    e.stopPropagation();
                    const isShown = customOptions.classList.contains('show');
                    // Close others if needed
                    document.querySelectorAll('.custom-dropdown-options.show').forEach(el => el.classList.remove('show'));

                    if (!isShown) {
                        customOptions.classList.add('show');
                        customTrigger.style.borderColor = '#6366f1'; // Highlight border
                    } else {
                        customOptions.classList.remove('show');
                        customTrigger.style.borderColor = '';
                    }
                });

                // Close on outside click
                document.addEventListener('click', (e) => {
                    if (!customDropdown.contains(e.target)) {
                        customOptions.classList.remove('show');
                        customTrigger.style.borderColor = '';
                    }
                });

                // Sync logic: rebuild custom options when native select changes
                function syncCustomDropdown() {
                    customOptions.innerHTML = '';
                    Array.from(nativeSelect.options).forEach(opt => {
                        // Skip placeholder if we want... but let's keep it to allow resetting?
                        // Usually dropdowns hide value="" option in list if it's "select user"
                        // But let's show all

                        const div = document.createElement('div');
                        div.className = 'custom-option' + (opt.selected ? ' selected' : '');
                        div.textContent = opt.text;
                        div.dataset.value = opt.value;

                        div.onclick = () => {
                            nativeSelect.value = opt.value;
                            customTriggerText.textContent = opt.text;
                            customOptions.classList.remove('show');

                            // Reset border
                            customTrigger.style.borderColor = '';

                            // Update selected styling
                            document.querySelectorAll('.custom-option').forEach(el => el.classList.remove('selected'));
                            div.classList.add('selected');

                            // Dispatch change event so existing listeners fire
                            nativeSelect.dispatchEvent(new Event('change'));
                        };
                        customOptions.appendChild(div);

                        // Set initial text if selected
                        if (opt.selected) {
                            customTriggerText.textContent = opt.text;
                        }
                    });
                }

                // Observe changes to native select options
                const observer = new MutationObserver(syncCustomDropdown);
                observer.observe(nativeSelect, { childList: true, subtree: true, attributes: true });

                // Initial sync
                syncCustomDropdown();
            }

        </script>

    </div>

@endsection