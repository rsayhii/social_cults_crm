<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Responsive Notification Header</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @keyframes slideInRight {
            from { transform: translateX(100%); opacity: 0; }
            to { transform: translateX(0); opacity: 1; }
        }
        @keyframes slideOutRight {
            from { transform: translateX(0); opacity: 1; }
            to { transform: translateX(100%); opacity: 0; }
        }
    </style>
</head>
<body class="bg-gray-100">

<header class="bg-white border-b border-gray-200 px-4 sm:px-6 py-4 sticky top-0 z-50">
    <div class="flex justify-between items-center">

        <!-- Title -->
        <h1 class="text-lg sm:text-xl font-bold text-gray-900">
            Dashboard
        </h1>

        <!-- Right Section -->
        <div class="flex items-center gap-3 relative">

            <!-- Notification Button -->
            <button id="notificationBtn"
                class="relative p-2 text-gray-600 hover:bg-gray-100 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                </svg>

                <span
                    class="absolute -top-1 -right-1 w-5 h-5 bg-orange-500 text-white text-xs rounded-full flex items-center justify-center" id="notify-count">
                     {{ auth()->user()->unreadNotifications->count() }}
                </span>
            </button>

            <!-- Notification Dropdown -->
            <div id="notificationDropdown"
                class="hidden absolute top-12 right-0 
                       w-[90vw] sm:w-80 
                       max-w-sm sm:max-w-none
                       bg-white border border-gray-200 
                       rounded-lg shadow-lg overflow-hidden z-50">

                <div class="px-4 py-3 border-b">
                    <h3 class="text-sm font-semibold text-gray-800">
                        Notifications
                    </h3>
                </div>

                {{-- <ul class="max-h-64 overflow-y-auto divide-y">
                    <li class="px-4 py-3 hover:bg-gray-50">
                        <p class="text-sm text-gray-700 font-medium">
                            New invoice created
                        </p>
                        <span class="text-xs text-gray-500">
                            2 minutes ago
                        </span>
                    </li>
                </ul> --}}

                @foreach(auth()->user()->unreadNotifications as $notification)
<a href="{{ $notification->data['url'] }}"
   class="block px-4 py-2 hover:bg-gray-100">
    <strong>{{ $notification->data['title'] }}</strong>
    <p class="text-xs text-gray-500">
        {{ $notification->data['message'] }}
    </p>
</a>
@endforeach


                {{-- <div class="px-4 py-2 text-center bg-gray-50">
                    <a href="#" class="text-sm text-blue-600 hover:underline">
                        View all
                    </a>
                </div> --}}
            </div>

            <!-- Search Button -->
            {{-- <button class="p-2 text-gray-600 hover:bg-gray-100 rounded-lg focus:outline-none">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 0 0114 0z" />
                </svg>
            </button> --}}

        </div>
    </div>
</header>

<!-- JS -->
<script>
    const btn = document.getElementById('notificationBtn');
    const dropdown = document.getElementById('notificationDropdown');
    const countEl = document.getElementById('notify-count');
    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
    const userId = {{ auth()->id() }};
    const storageKey = 'notifyLastSeenCount_' + userId;
    const currentCount = parseInt((countEl?.textContent || '0').trim()) || 0;
    let autoHideTimer = null;
    function markNotificationsRead() {
        if (!csrfToken) return;
        fetch('/notifications/read', {
            method: 'POST',
            headers: { 'X-CSRF-TOKEN': csrfToken, 'Accept': 'application/json' }
        }).then(() => {
            if (countEl) {
                countEl.textContent = '0';
                countEl.classList.add('hidden');
            }
            localStorage.setItem(storageKey, String(0));
        }).catch(() => {});
    }
    document.addEventListener('DOMContentLoaded', () => {
        const lastCount = localStorage.getItem(storageKey);
        const shouldAutoSlide = currentCount > 0 && lastCount !== String(currentCount);
        if (shouldAutoSlide) {
            dropdown.classList.remove('hidden');
            dropdown.style.animation = 'slideInRight 0.3s ease-out';
            if (autoHideTimer) clearTimeout(autoHideTimer);
            autoHideTimer = setTimeout(() => {
                dropdown.style.animation = 'slideOutRight 0.3s ease-in';
                setTimeout(() => dropdown.classList.add('hidden'), 300);
            }, 2000);
            localStorage.setItem(storageKey, String(currentCount));
        }
        if (currentCount === 0 && countEl) countEl.classList.add('hidden');
    });

    btn.addEventListener('click', (e) => {
        e.stopPropagation();
        if (!dropdown.classList.contains('hidden')) {
            return;
        }
        dropdown.classList.remove('hidden');
        dropdown.style.animation = 'slideInRight 0.2s ease-out';
        if (autoHideTimer) { clearTimeout(autoHideTimer); autoHideTimer = null; }
    });


    dropdown.addEventListener('click', (e) => {
        const a = e.target.closest('a[href]');
        if (a) {
            e.preventDefault();
            e.stopPropagation();
            fetch('/notifications/read', {
                method: 'POST',
                headers: { 'X-CSRF-TOKEN': csrfToken, 'Accept': 'application/json' }
            }).finally(() => {
                window.location.href = a.href;
            });
        } else {
            e.stopPropagation();
        }
    });

    document.addEventListener('click', () => {
        if (!dropdown.classList.contains('hidden')) {
            dropdown.style.animation = 'slideOutRight 0.3s ease-in';
            setTimeout(() => dropdown.classList.add('hidden'), 300);
        }
    });
</script>

</body>
</html>
