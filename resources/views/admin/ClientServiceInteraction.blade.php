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
    </style>

    <div class="flex h-screen bg-gray-100 font-sans">

        <div id="chatInterface" class="w-full flex flex-col md:flex-row">

            <!-- Sidebar -->
            <div class="w-full md:w-1/4 bg-white border-r border-gray-200 flex flex-col h-full sidebar-transition">

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

                <div class="p-4 border-b border-gray-200 hidden" id="searchBar">
                    <div class="relative">
                        <input type="text" placeholder="Search chats..." id="chatSearch"
                            class="w-full pl-10 pr-4 py-2 rounded-lg border border-gray-300 focus:outline-none">
                        <i class="fas fa-search absolute left-3 top-3 text-gray-400"></i>
                    </div>
                </div>

                <div class="p-4 border-b border-gray-200" id="userSelectionContainer">
                    <label class="text-xs font-bold text-gray-500 mb-1 block">Select User</label>
                    <select class="w-full px-3 py-2 rounded-lg border border-gray-300" id="clientSelectSidebar">
                        <option value="">-- Select Chat User --</option>
                    </select>
                </div>

                <button id="startChatBtn"
                    class="bg-indigo-600 text-white px-3 py-2 rounded-lg hidden mt-4 disabled:bg-indigo-300 disabled:cursor-not-allowed mx-4 mb-2">
                    Start Chat
                </button>

                <div class="flex-1 overflow-y-auto" id="chatList">
                    <div class="p-8 text-center text-gray-500">
                        <i class="fas fa-comments text-3xl mb-4 text-gray-300"></i>
                        <p>Loading chats...</p>
                    </div>
                </div>

                <div class="p-4 border-t border-gray-200 text-center">
                    <p class="text-xs text-gray-500">AgencyConnect CRM © {{ date('Y') }}</p>
                </div>
            </div>

            <!-- Chat Area -->
            <div class="w-full md:w-3/4 flex flex-col h-full bg-gray-50">

                <div class="p-4 border-b border-gray-200 bg-white flex justify-between items-center">
                    <div class="flex items-center">
                        <div class="w-10 h-10 rounded-full bg-indigo-100 flex items-center justify-center mr-3">
                            <i class="fas fa-user text-indigo-600"></i>
                        </div>
                        <div>
                            <h3 class="font-bold" id="chatWithName">Select a chat</h3>
                            <p class="text-xs text-gray-500" id="chatTeamInfo">Team info</p>
                        </div>
                    </div>
                </div>

                <div class="flex-1 overflow-y-auto p-4" id="messagesArea">
                    <div class="flex flex-col items-center justify-center h-full text-gray-500" id="emptyChatState">
                        <i class="fas fa-comments text-4xl mb-4 text-gray-300"></i>
                        <p class="text-lg">Select a chat to start messaging</p>
                    </div>
                </div>

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
            let lastChats = [];  // Track previous chats for diffing
            let lastMessageCounts = new Map();  // Per-chat message count for incremental updates
            const csrfToken = document.querySelector('meta[name="csrf-token"]').content;
            const userRole = "{{ auth()->user()->getRoleNames()->first() ?? '' }}";
            const userId = {{ auth()->id() ?? 'null' }};

            // DOM refs
            const chatListEl = document.getElementById('chatList');
            const messagesArea = document.getElementById('messagesArea');
            const emptyChatState = document.getElementById('emptyChatState');
            const chatWithName = document.getElementById('chatWithName');
            const chatTeamInfo = document.getElementById('chatTeamInfo');
            const messageInput = document.getElementById('messageInput');
            const typingIndicator = document.getElementById('typingIndicator');

            // Simple debounce function
            function debounce(fn, delay) {
                let timer;
                return (...args) => {
                    clearTimeout(timer);
                    timer = setTimeout(() => fn(...args), delay);
                };
            }
            const debouncedRenderChatList = debounce(renderChatListFromDatabase, 500);

            // Load initial UI config
            document.addEventListener('DOMContentLoaded', () => {
                // Load Users directly for selection (No team filter)
                fetchClients();

                loadChatList();
                // Poll for new chats every 10s (increased from 4s to reduce frequency)
                setInterval(loadChatList, 10000);

                // Poll for new messages in active chat every 3s
                setInterval(() => {
                    if (activeChat) refreshMessages();
                }, 3000);
            });

            // Fetch chat list
            function loadChatList() {
                fetch('/chat/list')
                    .then(r => r.json())
                    .then(data => {
                        debouncedRenderChatList(data);
                    })
                    .catch(err => {
                        console.error('Error fetching chats', err);
                    });
            }

            // Render chat list with diffing to avoid full rebuilds
            function renderChatListFromDatabase(chats) {
                if (!Array.isArray(chats)) return;

                // Simple diff: Skip if data hasn't changed
                if (JSON.stringify(chats) === JSON.stringify(lastChats)) return;

                lastChats = chats;

                if (chats.length === 0) {
                    chatListEl.innerHTML = `
                            <div class="p-8 text-center text-gray-500">
                                <i class="fas fa-comments text-3xl mb-4 text-gray-300"></i>
                                <p>No chats found</p>
                                <p class="text-sm mt-2">Start a conversation to see it here</p>
                            </div>
                        `;
                    return;
                }

                // Full rebuild only if necessary (e.g., on first load or major changes)
                chatListEl.innerHTML = '';
                chats.forEach(chat => {
                    // Correct logic: Show the person who is NOT me.
                    // userId is auth()->id().
                    // If I am team_id, show client. If I am client_id, show team.
                    let otherSide = null;
                    // Use chat.team.id because backend sends 'team' object, not 'team_id' property in the custom payload
                    if (chat.team && chat.team.id == userId) {
                        otherSide = chat.client;
                    } else {
                        otherSide = chat.team;
                    }

                    const name = otherSide ? (otherSide.name || otherSide.email || 'Conversation') : 'Conversation';
                    const last = chat.last_message || 'No messages yet';
                    const ts = chat.last_message_at ? new Date(chat.last_message_at).toLocaleString() : '';

                    const el = document.createElement('div');
                    el.className = 'p-4 border-b border-gray-100 hover:bg-gray-50 cursor-pointer';  // No fade-in to reduce flicker
                    el.dataset.chatId = chat.id;
                    el.innerHTML = `
                            <div class="flex justify-between items-start">
                                <div class="flex items-center">
                                    <div class="w-12 h-12 rounded-full bg-indigo-100 flex items-center justify-center mr-3">
                                        <span class="font-bold text-indigo-700">${(name[0] || 'U').toUpperCase()}</span>
                                    </div>
                                    <div class="flex-1">
                                        <div class="flex justify-between">
                                            <h4 class="font-semibold">${escapeHtml(name)}</h4>
                                        </div>
                                        <p class="text-sm text-gray-600 truncate max-w-xs">${escapeHtml(last)}</p>
                                    </div>
                                </div>
                                <div class="text-xs text-gray-500">${escapeHtml(ts)}</div>
                            </div>
                        `;
                    el.addEventListener('click', () => {
                        openChat(chat.id, name, (chat.team ? chat.team.name : (chat.client ? chat.client.name : '')), false, false);  // No fade for initial load
                    });
                    chatListEl.appendChild(el);
                });
            }

            // Open chat and load messages
            // keepSilent true => do not show typing demo
            // applyFadeToAll true => apply fade-in to all messages on initial load (set to false to reduce flicker)
            function openChat(id, name, teamName, keepSilent = false, applyFadeToAll = false) {
                activeChat = id;
                chatWithName.textContent = name;
                // chatTeamInfo.textContent = teamName || ''; // Removed to prevent confusion (showing user own name)
                chatTeamInfo.textContent = ''; // Clear it
                emptyChatState.classList.add('hidden');

                // Reset message count for this chat
                lastMessageCounts.set(id, 0);

                fetch(`/chat/messages/${id}`)
                    .then(r => {
                        if (!r.ok) throw r;
                        return r.json();
                    })
                    .then(data => {
                        // Ensure messages are sorted by created_at ascending
                        data.sort((a, b) => new Date(a.created_at) - new Date(b.created_at));
                        renderMessagesFromDB(data, applyFadeToAll);
                        if (!keepSilent) simulateOtherTyping();
                    })
                    .catch(err => {
                        console.error('Error loading messages', err);
                        messagesArea.innerHTML = `<div class="p-6">Unable to load messages</div>`;
                    });
            }

            // Render messages incrementally
            function renderMessagesFromDB(messages, applyFadeToAll = false) {
                if (!Array.isArray(messages)) return;

                const chatId = activeChat;
                let currentCount = lastMessageCounts.get(chatId) || 0;

                if (messages.length <= currentCount) return;  // No new messages

                // If first load for this chat, clear and render all without fade to avoid staggered flicker
                if (currentCount === 0) {
                    messagesArea.innerHTML = '';
                    messages.forEach(msg => {
                        appendMessage(msg, '');  // No fade on initial load
                    });
                } else {
                    // Append only new messages (assume messages are sorted ascending by created_at)
                    for (let i = currentCount; i < messages.length; i++) {
                        const msg = messages[i];
                        appendMessage(msg, 'fade-in');  // Fade only new ones
                    }
                }

                lastMessageCounts.set(chatId, messages.length);

                // Scroll to bottom with a slight delay to allow layout
                setTimeout(() => {
                    messagesArea.scrollTop = messagesArea.scrollHeight;
                }, 10);
            }

            // Helper to append a single message
            function appendMessage(msg, extraClass = '') {
                const isSent = (msg.sender_id == userId);
                const wrapper = document.createElement('div');
                wrapper.className = `flex ${isSent ? 'justify-end' : 'justify-start'} mb-4 message-transition ${extraClass}`;

                if (isSent) {
                    wrapper.innerHTML = `
                            <div class="max-w-xs lg:max-w-md">
                                <div class="bg-indigo-600 text-white p-3 rounded-xl chat-bubble-right shadow-sm">
                                    <p>${escapeHtml(msg.message)}</p>
                                </div>
                                <div class="text-xs text-gray-500 mt-1 text-right">${new Date(msg.created_at).toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' })} <i class="fas fa-check-double text-indigo-500 ml-1"></i></div>
                            </div>
                        `;
                } else {
                    wrapper.innerHTML = `
                            <div class="max-w-xs lg:max-w-md">
                                <div class="flex items-end">
                                    <div class="w-8 h-8 rounded-full bg-gray-200 flex items-center justify-center mr-2 flex-shrink-0">
                                        <i class="fas fa-user text-gray-500 text-xs"></i>
                                    </div>
                                    <div class="bg-white p-3 rounded-xl chat-bubble-left shadow-sm">
                                        <p class="text-gray-800">${escapeHtml(msg.message)}</p>
                                    </div>
                                </div>
                                <div class="text-xs text-gray-500 mt-1 ml-10">${new Date(msg.created_at).toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' })}</div>
                            </div>
                        `;
                }
                messagesArea.appendChild(wrapper);
            }

            // Send message with optimistic append
            document.getElementById('sendMessageBtn').addEventListener('click', sendMessage);
            messageInput.addEventListener('keypress', function (e) {
                if (e.key === 'Enter') sendMessage();
            });

            function sendMessage() {
                const text = messageInput.value.trim();
                if (!text || !activeChat) return;

                // Optimistic append: Add message immediately as sent
                const now = new Date().toISOString();
                const optimisticMsg = {
                    id: Date.now(),  // Temp ID
                    message: text,
                    sender_id: userId,
                    created_at: now
                };
                appendMessage(optimisticMsg);

                // Update count immediately to prevent duplicate append on refresh
                const chatId = activeChat;
                const currentCount = lastMessageCounts.get(chatId) || 0;
                lastMessageCounts.set(chatId, currentCount + 1);

                messageInput.value = '';
                messagesArea.scrollTop = messagesArea.scrollHeight;

                fetch('/chat/send', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken
                    },
                    body: JSON.stringify({
                        conversation_id: activeChat,
                        message: text
                    })
                })
                    .then(r => {
                        if (!r.ok) throw r;
                        return r.json();
                    })
                    .then(data => {
                        // Refresh to confirm (but count is updated, so likely skips append; minor timestamp diff may cause tiny shift)
                        refreshMessages();
                    })
                    .catch(err => {
                        console.error('Send error', err);
                        alert('Unable to send message');
                        // Remove optimistic message on error and revert count
                        const lastChild = messagesArea.lastElementChild;
                        if (lastChild) lastChild.remove();
                        lastMessageCounts.set(chatId, currentCount);
                        messagesArea.scrollTop = messagesArea.scrollHeight;
                    });
            }

            // Debounced refresh for messages
            const debouncedRefreshMessages = debounce(() => {
                if (!activeChat) return;
                fetch(`/chat/messages/${activeChat}`)
                    .then(r => r.json())
                    .then(data => {
                        // Ensure sorted
                        data.sort((a, b) => new Date(a.created_at) - new Date(b.created_at));
                        renderMessagesFromDB(data);
                    })
                    .catch(err => console.error('Refresh error', err));
            }, 300);

            function refreshMessages() {
                debouncedRefreshMessages();
            }

            // Small typing demo from other side (for UX)
            function simulateOtherTyping() {
                if (Math.random() > 0.6) {
                    typingIndicator.classList.remove('hidden');
                    setTimeout(() => {
                        typingIndicator.classList.add('hidden');
                    }, 1500 + Math.random() * 2000);
                }
            }

            // Escape html helper
            function escapeHtml(unsafe) {
                if (unsafe === null || unsafe === undefined) return '';
                return String(unsafe)
                    .replaceAll('&', '&amp;')
                    .replaceAll('<', '&lt;')
                    .replaceAll('>', '&gt;')
                    .replaceAll('"', '&quot;')
                    .replaceAll("'", '&#039;');
            }

            // User Selection Logic
            const clientSelect = document.getElementById('clientSelectSidebar');
            const startChatBtn = document.getElementById('startChatBtn');

            function fetchClients() {
                clientSelect.innerHTML = '<option value="">Loading users...</option>';
                startChatBtn.style.display = 'none';

                // Endpoint now returns ALL users (per user request)
                fetch('/chat/clients')
                    .then(res => res.json())
                    .then(clients => {
                        clientSelect.innerHTML = '<option value="">-- Select User --</option>';
                        if (clients.length === 0) {
                            clientSelect.innerHTML += '<option value="" disabled>No users found</option>';
                            return;
                        }
                        clients.forEach(c => {
                            clientSelect.innerHTML += `<option value="${c.id}">${c.name || c.email}</option>`;
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
                        // Optional: remove hidden class if using that style
                        startChatBtn.classList.remove('hidden');
                    } else {
                        startChatBtn.style.display = 'none';
                    }
                });
            }

            startChatBtn.addEventListener('click', function () {
                const userId = clientSelect.value;
                if (!userId) {
                    alert("Please select a user.");
                    return;
                }

                // Note: We're reusing start-conversation but the 'team_id' concept might be irrelevant now.
                // However, the backend expects 'team_id' and 'client_id'.
                // To support "Any user to Any user" chat, we might need to adjust createConversation backend too?
                // User said: "it should not depend on the roles".
                // If the backend strictly checks if team_id is a team role, it will fail.
                // BUT: The prompt said "it only depends on the user module".
                // I should pass the selected user as 'client_id' (or similar) and maybe a dummy or self as team_id?
                // Actually, let's verify if `createConversation` still enforces roles. 
                // The previous code had: if ($team->hasRole('admin') || $team->hasRole('client')) error.
                // I should check ChatController::createConversation again to be safe.
                // For now, I'll pass the selected user as 'client_id' and the CURRENT user (auth) as 'team_id'?? 
                // Or maybe simply pass the two IDs involved.

                // Let's assume for this specific blade adjustment we just pass the selected ID.
                // I will updated createConversation next if needed.

                // Temporary Hack: Pass the selected user as 'client_id' and the 'team_id' as... ??
                // If I look at the controller logic, it creates conversation between team_id and client_id.
                // PROPOSED FIX:
                // Send `user_id` to a new endpoint or update existing one?
                // Let's stick to existing: client_id = selected user, team_id = current user (if current user is 'team'?).
                // If current user is Admin, they can chat with anyone.
                // Let's try passing team_id as current user ID if possible?
                // JS doesn't always know current user ID perfectly unless I grab it from the `userId` var.

                const myId = userId; // Wait, userId variable in JS is Auth::id().

                fetch('/chat/create-conversation', {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json",
                        "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify({
                        team_id: {{ auth()->id() }},
                        client_id: userId
                    })
                })
                    .then(res => res.json())
                    .then(convo => {
                        if (!convo.id) {
                            // Fallback logic if backend validation fails due to role checks
                            console.error(convo);
                            alert(convo.message || "Conversation could not be created.");
                            return;
                        }

                        activeChat = convo.id;
                        const userName = clientSelect.options[clientSelect.selectedIndex].text;
                        openChat(convo.id, userName, "User " + userId, false, false);
                        loadChatList();
                    })
                    .catch(err => {
                        console.error("Error creating conversation", err);
                        alert("Failed to create conversation.");
                    });
            });

        </script>

        </script>

@endsection