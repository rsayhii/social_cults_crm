@extends('components.layout')

@section('content')
<div class="min-h-screen bg-gray-50 py-4 sm:py-6 lg:py-8">
    <div class="max-w-7xl mx-auto px-3 sm:px-4 lg:px-6 xl:px-8">
        <!-- Header -->
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-6 sm:mb-8 gap-4">
            <div>
                <h1 class="text-2xl sm:text-3xl font-bold text-gray-900">User Management</h1>
                <p class="mt-1 text-sm text-gray-600">Manage all system users and their roles.</p>
            </div>
            <a href="{{ route('users.create') }}"
               class="inline-flex items-center justify-center px-4 py-2.5 sm:px-5 sm:py-2.5 bg-indigo-600 text-white font-medium text-sm rounded-lg shadow-sm hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-colors w-full sm:w-auto">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                </svg>
                <span class="sm:hidden">Add User</span>
                <span class="hidden sm:inline">Add New User</span>
            </a>
        </div>

        <!-- Success Message -->
        @if(session('success'))
            <div class="mb-4 sm:mb-6 p-3 sm:p-4 bg-green-50 border border-green-200 text-green-800 rounded-lg flex items-center animate-fade-in">
                <svg class="w-4 h-4 sm:w-5 sm:h-5 mr-2 text-green-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                </svg>
                <span class="text-sm sm:text-base">{{ session('success') }}</span>
            </div>
        @endif


        <!-- Success Message -->
        @if(Session::has('error'))
            <div class="mb-4 sm:mb-6 p-3 sm:p-4 bg-green-50 border border-green-200 text-green-800 rounded-lg flex items-center animate-fade-in">
                <svg class="w-4 h-4 sm:w-5 sm:h-5 mr-2 text-green-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                </svg>
                <span class="text-sm sm:text-base">{{ Session::get('error') }}</span>
            </div>
        @endif

        <!-- Users Table Container -->
        <div class="bg-white shadow-sm rounded-xl border border-gray-200 overflow-hidden">
            <!-- Desktop Table (hidden on mobile) -->
            <div class="hidden sm:block overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-4 lg:px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">ID</th>
                            <th class="px-4 lg:px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Name</th>
                            <th class="px-4 lg:px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Email</th>
                            <th class="px-4 lg:px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Roles</th>
                            <th class="px-4 lg:px-6 py-3 text-right text-xs font-semibold text-gray-700 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($users as $user)
                            <tr class="hover:bg-gray-50 transition-colors">
                                <td class="px-4 lg:px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $loop->iteration }}</td>
                                <td class="px-4 lg:px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div class="w-8 h-8 lg:w-9 lg:h-9 bg-gradient-to-br from-indigo-500 to-purple-600 rounded-full flex items-center justify-center text-white font-semibold text-xs lg:text-sm flex-shrink-0">
                                            {{ strtoupper(substr($user->name, 0, 2)) }}
                                        </div>
                                        <div class="ml-2 lg:ml-3 min-w-0">
                                            <p class="text-sm font-medium text-gray-900 truncate">{{ $user->name }}</p>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-4 lg:px-6 py-4 whitespace-nowrap text-sm text-gray-600">{{ $user->email }}</td>
                                <td class="px-4 lg:px-6 py-4 whitespace-nowrap">
                                    <div class="flex flex-wrap gap-1">
                                        @foreach ($user->getRoleNames() as $role)
                                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium
                                                @if($role === 'admin') bg-red-100 text-red-800
                                                @elseif($role === 'editor') bg-amber-100 text-amber-800
                                                @elseif($role === 'user') bg-gray-100 text-gray-800
                                                @else bg-indigo-100 text-indigo-800 @endif">
                                                {{ ucfirst($role) }}
                                            </span>
                                        @endforeach
                                    </div>
                                </td>
                                <td class="px-4 lg:px-6 py-4 whitespace-nowrap text-right text-sm font-medium space-x-1">
                                    <a href="{{ route('users.show', $user->id) }}"
                                       class="inline-flex items-center px-2.5 py-1.5 bg-gray-100 text-gray-700 rounded-md hover:bg-gray-200 transition-colors text-xs">
                                        <svg class="w-3.5 h-3.5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                        </svg>
                                        View
                                    </a>

                                    <a href="{{ route('users.edit', $user->id) }}"
                                       class="inline-flex items-center px-2.5 py-1.5 bg-amber-100 text-amber-700 rounded-md hover:bg-amber-200 transition-colors text-xs">
                                        <svg class="w-3.5 h-3.5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l-.586-.585z"></path>
                                        </svg>
                                        Edit
                                    </a>

                                    <form action="{{ route('users.destroy', $user->id) }}" method="POST" class="inline"
                                          onsubmit="return confirm('Are you sure you want to delete this user?')">
                                        @csrf @method('DELETE')
                                        <button type="button" onclick="openDeleteModal('{{ $user->id }}', '{{ addslashes($user->name) }}')"
        class="inline-flex items-center px-2.5 py-1.5 bg-red-100 text-red-700 rounded-md hover:bg-red-200 transition-colors text-xs">
    <svg class="w-3.5 h-3.5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
    </svg>
    Delete
</button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-12 text-center text-gray-500">
                                    <svg class="mx-auto h-10 w-10 sm:h-12 sm:w-12 text-gray-400 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                    </svg>
                                    <p class="text-base sm:text-lg font-medium">No users found</p>
                                    <p class="text-xs sm:text-sm mt-1">Get started by adding your first user.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Mobile Cards (visible only on mobile) -->
            <div class="sm:hidden divide-y divide-gray-200">
                @forelse($users as $user)
                    <div class="p-4 hover:bg-gray-50 transition-colors">
                        <!-- Card Header -->
                        <div class="flex items-center justify-between mb-3">
                            <div class="flex items-center">
                                <div class="w-10 h-10 bg-gradient-to-br from-indigo-500 to-purple-600 rounded-full flex items-center justify-center text-white font-semibold text-sm">
                                    {{ strtoupper(substr($user->name, 0, 2)) }}
                                </div>
                                <div class="ml-3">
                                    <p class="text-sm font-medium text-gray-900">{{ $user->name }}</p>
                                    <p class="text-xs text-gray-500 mt-0.5">ID: {{ $loop->iteration }}</p>
                                </div>
                            </div>
                        </div>

                        <!-- User Info -->
                        <div class="mb-3">
                            <div class="flex items-center mb-2">
                                <svg class="w-4 h-4 text-gray-400 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                                </svg>
                                <span class="text-sm text-gray-600 truncate">{{ $user->email }}</span>
                            </div>

                            <!-- Roles -->
                            <div class="flex flex-wrap gap-1 mt-2">
                                @foreach ($user->getRoleNames() as $role)
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium
                                        @if($role === 'admin') bg-red-100 text-red-800
                                        @elseif($role === 'editor') bg-amber-100 text-amber-800
                                        @elseif($role === 'user') bg-gray-100 text-gray-800
                                        @else bg-indigo-100 text-indigo-800 @endif">
                                        {{ ucfirst($role) }}
                                    </span>
                                @endforeach
                            </div>
                        </div>

                        <!-- Action Buttons -->
                        <div class="flex justify-end space-x-2 pt-3 border-t border-gray-100">
                            <a href="{{ route('users.show', $user->id) }}"
                               class="inline-flex items-center px-3 py-1.5 bg-gray-100 text-gray-700 rounded-md hover:bg-gray-200 transition-colors text-xs flex-1 justify-center">
                                <svg class="w-3.5 h-3.5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                </svg>
                                View
                            </a>

                            <a href="{{ route('users.edit', $user->id) }}"
                               class="inline-flex items-center px-3 py-1.5 bg-amber-100 text-amber-700 rounded-md hover:bg-amber-200 transition-colors text-xs flex-1 justify-center">
                                <svg class="w-3.5 h-3.5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l-.586-.585z"></path>
                                </svg>
                                Edit
                            </a>

                            <form action="{{ route('users.destroy', $user->id) }}" method="POST" class="flex-1"
                                  onsubmit="return confirm('Are you sure you want to delete this user?')">
                                @csrf @method('DELETE')
                                <button type="button" onclick="openDeleteModal('{{ $user->id }}', '{{ addslashes($user->name) }}')"
        class="inline-flex items-center px-2.5 py-1.5 bg-red-100 text-red-700 rounded-md hover:bg-red-200 transition-colors text-xs">
    <svg class="w-3.5 h-3.5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
    </svg>
    Delete
</button>
                            </form>
                        </div>
                    </div>
                @empty
                    <div class="p-6 text-center text-gray-500">
                        <svg class="mx-auto h-12 w-12 text-gray-400 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                        </svg>
                        <p class="text-lg font-medium">No users found</p>
                        <p class="text-sm mt-1">Get started by adding your first user.</p>
                    </div>
                @endforelse
            </div>
        </div>

        <!-- Pagination -->
        @if(method_exists($users, 'links'))
            <div class="mt-4 sm:mt-6">
                <div class="flex justify-center">
                    {{ $users->links('pagination::tailwind') }}
                </div>
            </div>
        @endif
    </div>
</div>

<style>
    /* Modal animations */
#deleteModal {
    transition: opacity 0.3s ease;
}

#deleteModal > div {
    animation: modalSlideIn 0.3s ease-out;
}

@keyframes modalSlideIn {
    from {
        opacity: 0;
        transform: translateY(-20px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

/* Prevent background scrolling when modal is open */
body.overflow-hidden {
    overflow: hidden;
}
</style>



<!-- Delete Confirmation Modal -->
<div id="deleteModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50 hidden">
    <div class="relative top-20 mx-auto p-4 w-full max-w-md">
        <div class="relative bg-white rounded-lg shadow-xl">
            <!-- Modal Header -->
            <div class="flex items-start justify-between p-5 border-b rounded-t">
                <h3 class="text-lg sm:text-xl font-semibold text-gray-900">
                    Confirm Delete
                </h3>
                <button type="button" onclick="closeDeleteModal()"
                    class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                    </svg>
                </button>
            </div>
            
            <!-- Modal Body -->
            <div class="p-6">
                <div class="flex items-center mb-4">
                    <div class="flex-shrink-0 w-12 h-12 bg-red-100 rounded-full flex items-center justify-center">
                        <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.998-.833-2.732 0L4.732 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm sm:text-base font-medium text-gray-900">Delete User</p>
                        <p class="text-sm text-gray-500 mt-1">This action cannot be undone.</p>
                    </div>
                </div>
                
                <p class="text-sm text-gray-600 mb-2">You are about to delete user: <span id="userToDeleteName" class="font-semibold text-gray-900"></span></p>
                <p class="text-xs text-gray-500">All user data will be permanently removed from the system.</p>
            </div>
            
            <!-- Modal Footer -->
            <div class="flex flex-col sm:flex-row sm:items-center justify-end p-6 border-t border-gray-200 space-y-3 sm:space-y-0 sm:space-x-3">
                <button type="button" onclick="closeDeleteModal()"
                    class="w-full sm:w-auto px-4 py-2.5 text-sm font-medium text-gray-700 bg-gray-100 hover:bg-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 transition-colors">
                    Cancel
                </button>
                <form id="deleteForm" method="POST" class="w-full sm:w-auto">
                    @csrf @method('DELETE')
                    <button type="submit"
                        class="w-full px-4 py-2.5 text-sm font-medium text-white bg-red-600 hover:bg-red-700 rounded-lg focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 transition-colors">
                        Delete User
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>




<!-- Responsive Styles -->
<style>
    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(-4px); }
        to { opacity: 1; transform: translateY(0); }
    }
    .animate-fade-in {
        animation: fadeIn 0.3s ease-out;
    }

    /* Tablet-specific adjustments */
    @media (min-width: 640px) and (max-width: 768px) {
        .table-container {
            font-size: 0.875rem;
        }
        
        .table-cell {
            padding-left: 0.75rem;
            padding-right: 0.75rem;
        }
        
        .action-buttons {
            display: flex;
            flex-wrap: wrap;
            gap: 0.25rem;
        }
        
        .action-button {
            padding: 0.375rem 0.5rem;
            font-size: 0.75rem;
        }
    }

    /* Small mobile devices */
    @media (max-width: 360px) {
        .mobile-user-card {
            padding: 0.75rem;
        }
        
        .mobile-user-avatar {
            width: 2rem;
            height: 2rem;
            font-size: 0.75rem;
        }
        
        .mobile-action-buttons {
            flex-direction: column;
            gap: 0.5rem;
        }
        
        .mobile-action-button {
            width: 100%;
            justify-content: center;
        }
    }

    /* Ensure proper overflow handling on small screens */
    @media (max-width: 640px) {
        .overflow-x-auto {
            -webkit-overflow-scrolling: touch;
        }
        
        .table-responsive {
            margin: 0 -0.75rem;
            padding: 0 0.75rem;
        }
    }

    /* Improve button touch targets on mobile */
    @media (max-width: 640px) {
        button, 
        a[role="button"], 
        .button-like {
            min-height: 44px;
            min-width: 44px;
        }
        
        .touch-target {
            padding: 0.75rem;
        }
    }

    /* Optimize spacing for different screen sizes */
    @media (min-width: 1536px) {
        .xl\:px-8 {
            padding-left: 2rem;
            padding-right: 2rem;
        }
        
        .xl\:py-10 {
            padding-top: 2.5rem;
            padding-bottom: 2.5rem;
        }
    }
</style>

<!-- Optional: Add JavaScript for better mobile experience -->
<!-- Optional: Add JavaScript for better mobile experience -->
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Handle responsive behavior
        function handleResponsive() {
            const width = window.innerWidth;
            
            // Adjust table overflow on tablet
            if (width >= 640 && width <= 1024) {
                const tables = document.querySelectorAll('table');
                tables.forEach(table => {
                    table.parentElement.classList.add('overflow-x-auto');
                });
            }
            
            // Improve touch targets on mobile
            if (width <= 640) {
                const buttons = document.querySelectorAll('button, a[href], .cursor-pointer');
                buttons.forEach(btn => {
                    if (!btn.classList.contains('touch-target')) {
                        btn.classList.add('touch-target');
                    }
                });
            }
        }
        
        // Initial call
        handleResponsive();
        
        // Handle window resize
        let resizeTimer;
        window.addEventListener('resize', function() {
            clearTimeout(resizeTimer);
            resizeTimer = setTimeout(handleResponsive, 250);
        });
        
        // Prevent horizontal scrolling on mobile
        document.body.style.overflowX = 'hidden';
    });

    // Delete Modal Functions
    function openDeleteModal(userId, userName) {
        const modal = document.getElementById('deleteModal');
        const form = document.getElementById('deleteForm');
        const userNameSpan = document.getElementById('userToDeleteName');
        
        // Set the user name in the modal
        userNameSpan.textContent = userName;
        
        // Update the form action with the correct user ID
        form.action = `/users/${userId}`;
        
        // Show the modal
        modal.classList.remove('hidden');
        modal.classList.add('flex');
        document.body.classList.add('overflow-hidden');
    }

    function closeDeleteModal() {
        const modal = document.getElementById('deleteModal');
        
        // Hide the modal
        modal.classList.add('hidden');
        modal.classList.remove('flex');
        document.body.classList.remove('overflow-hidden');
    }

    // Close modal when clicking outside
    document.getElementById('deleteModal')?.addEventListener('click', function(e) {
        if (e.target.id === 'deleteModal') {
            closeDeleteModal();
        }
    });

    // Close modal with Escape key
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            closeDeleteModal();
        }
    });
</script>
@endsection