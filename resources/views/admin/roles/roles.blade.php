@extends('components.layout')

@section('content')
<div class="min-h-screen bg-gray-50 p-3 sm:p-4 lg:p-6">
    <div class="max-w-7xl mx-auto">
        <!-- Header Section -->
        <div class="bg-white shadow-sm rounded-xl border border-gray-200 p-4 sm:p-6 mb-4 sm:mb-6">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 sm:gap-4">
                <div>
                    <h1 class="text-xl sm:text-2xl lg:text-3xl font-bold text-gray-900">Roles Management</h1>
                    <p class="mt-1 text-sm text-gray-600">Manage system roles and permissions.</p>
                </div>
                <a href="{{ route('roles.create') }}" 
                   class="inline-flex items-center justify-center w-full sm:w-auto px-4 py-2.5 bg-green-500 hover:bg-green-600 text-white text-sm font-medium rounded-lg shadow-sm transition-colors focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                    </svg>
                    <span class="sm:hidden">Create Role</span>
                    <span class="hidden sm:inline">Create New Role</span>
                </a>
            </div>
        </div>

        <!-- Success Alert -->
        @if (session('success'))
            <div id="alert-success" 
                 class="flex items-center justify-between bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-lg mb-4 sm:mb-6 shadow-sm animate-fade-in">
                <div class="flex items-center space-x-2">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-5 h-5 text-green-600">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2l4-4m-7 8h8a2 2 0 002-2V8a2 2 0 00-2-2H7a2 2 0 00-2 2v8a2 2 0 002 2z" />
                    </svg>
                    <span class="text-sm sm:text-base">{{ session('success') }}</span>
                </div>
                <button onclick="document.getElementById('alert-success').style.display='none'"
                        class="text-green-700 hover:text-green-900 focus:outline-none text-sm font-medium ml-4">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
        @endif


          @if (Session::has('error'))
            <div id="alert-success" 
                 class="flex items-center justify-between bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-lg mb-4 sm:mb-6 shadow-sm animate-fade-in">
                <div class="flex items-center space-x-2">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-5 h-5 text-green-600">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2l4-4m-7 8h8a2 2 0 002-2V8a2 2 0 00-2-2H7a2 2 0 00-2 2v8a2 2 0 002 2z" />
                    </svg>
                    <span class="text-sm sm:text-base">{{ Session::get('error') }}</span>
                </div>
                <button onclick="document.getElementById('alert-success').style.display='none'"
                        class="text-green-700 hover:text-green-900 focus:outline-none text-sm font-medium ml-4">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
        @endif

        <!-- Desktop Table (hidden on mobile) -->
        <div class="bg-white shadow-sm rounded-xl border border-gray-200 overflow-hidden mb-4 sm:mb-6 hidden sm:block">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th scope="col" class="px-4 lg:px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">ID</th>
                            <th scope="col" class="px-4 lg:px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Name</th>
                            <th scope="col" class="px-4 lg:px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Permissions</th>
                            <th scope="col" class="px-4 lg:px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Users</th>
                            <th scope="col" class="px-4 lg:px-6 py-3 text-right text-xs font-semibold text-gray-700 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse ($roles as $role)
                            <tr class="hover:bg-gray-50 transition-colors">
                                <td class="px-4 lg:px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">#{{ $loop->iteration }}</td>
                                <td class="px-4 lg:px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div class="w-8 h-8 rounded-full bg-gradient-to-br from-blue-500 to-indigo-600 flex items-center justify-center text-white font-semibold text-xs">
                                            {{ strtoupper(substr($role->name, 0, 2)) }}
                                        </div>
                                        <div class="ml-3">
                                            <p class="text-sm font-medium text-gray-900">{{ ucfirst($role->name) }}</p>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-4 lg:px-6 py-4 whitespace-nowrap">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                        {{ $role->permissions_count ?? 0 }} permissions
                                    </span>
                                </td>
                                <td class="px-4 lg:px-6 py-4 whitespace-nowrap">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                        {{ $role->users_count ?? 0 }} users
                                    </span>
                                </td>
                                <td class="px-4 lg:px-6 py-4 whitespace-nowrap text-right text-sm font-medium space-x-1">
                                    <a href="{{ route('roles.show', $role->id) }}"
                                       class="inline-flex items-center px-3 py-1.5 bg-blue-50 text-blue-700 rounded-md hover:bg-blue-100 transition-colors text-xs">
                                        <svg class="w-3.5 h-3.5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                        </svg>
                                        View
                                    </a>
                                    
                                    <a href="{{ route('roles.edit', $role->id) }}"
                                       class="inline-flex items-center px-3 py-1.5 bg-green-50 text-green-700 rounded-md hover:bg-green-100 transition-colors text-xs">
                                        <svg class="w-3.5 h-3.5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l-.586-.585z"></path>
                                        </svg>
                                        Edit
                                    </a>
                                    
                                    <button type="button" 
                                            onclick="openDeleteModal('{{ $role->id }}', '{{ addslashes($role->name) }}', '{{ route('roles.destroy', $role->id) }}')"
                                            class="inline-flex items-center px-3 py-1.5 bg-red-50 text-red-700 rounded-md hover:bg-red-100 transition-colors text-xs">
                                        <svg class="w-3.5 h-3.5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                        </svg>
                                        Delete
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-12 text-center text-gray-500">
                                    <svg class="mx-auto h-12 w-12 text-gray-400 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                                    </svg>
                                    <p class="text-lg font-medium">No roles found</p>
                                    <p class="text-sm mt-1">Get started by creating your first role.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Mobile Cards (visible only on mobile) -->
        <div class="sm:hidden space-y-3">
            @forelse ($roles as $role)
                <div class="bg-white shadow-sm rounded-xl border border-gray-200 p-4">
                    <!-- Card Header -->
                    <div class="flex items-center justify-between mb-3">
                        <div class="flex items-center">
                            <div class="w-10 h-10 rounded-full bg-gradient-to-br from-blue-500 to-indigo-600 flex items-center justify-center text-white font-semibold text-sm">
                                {{ strtoupper(substr($role->name, 0, 2)) }}
                            </div>
                            <div class="ml-3">
                                <p class="text-sm font-medium text-gray-900">{{ ucfirst($role->name) }}</p>
                                <p class="text-xs text-gray-500 mt-0.5">ID: #{{ $role->id }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Role Info -->
                    <div class="grid grid-cols-2 gap-3 mb-4">
                        <div class="bg-blue-50 rounded-lg p-3 text-center">
                            <p class="text-xs text-blue-600 font-medium mb-1">Permissions</p>
                            <p class="text-sm font-bold text-blue-700">{{ $role->permissions_count ?? 0 }}</p>
                        </div>
                       <div class="bg-gray-50 rounded-lg p-3 text-center">
                            <p class="text-xs text-gray-600 font-medium mb-1">Users</p>
                            <p class="text-sm font-bold text-gray-700">
                                {{ $role->users_count }}
                            </p>
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="flex flex-col sm:flex-row gap-2 pt-4 border-t border-gray-100">
                        <a href="{{ route('roles.show', $role->id) }}"
                           class="inline-flex items-center justify-center px-3 py-2.5 bg-blue-50 text-blue-700 rounded-lg hover:bg-blue-100 transition-colors text-sm font-medium">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                            </svg>
                            View Details
                        </a>
                        
                        <div class="flex gap-2">
                            <a href="{{ route('roles.edit', $role->id) }}"
                               class="flex-1 inline-flex items-center justify-center px-3 py-2.5 bg-green-50 text-green-700 rounded-lg hover:bg-green-100 transition-colors text-sm font-medium">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l-.586-.585z"></path>
                                </svg>
                                Edit
                            </a>
                            
                            <button type="button" 
                                    onclick="openDeleteModal('{{ $role->id }}', '{{ addslashes($role->name) }}', '{{ route('roles.destroy', $role->id) }}')"
                                    class="flex-1 w-full inline-flex items-center justify-center px-3 py-2.5 bg-red-50 text-red-700 rounded-lg hover:bg-red-100 transition-colors text-sm font-medium">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                </svg>
                                Delete
                            </button>
                        </div>
                    </div>
                </div>
            @empty
                <div class="bg-white shadow-sm rounded-xl border border-gray-200 p-6 text-center">
                    <svg class="mx-auto h-12 w-12 text-gray-400 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                    </svg>
                    <p class="text-lg font-medium text-gray-900">No roles found</p>
                    <p class="text-sm text-gray-600 mt-1">Get started by creating your first role.</p>
                </div>
            @endforelse
        </div>

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
                                <p class="text-sm sm:text-base font-medium text-gray-900">Delete Role</p>
                                <p class="text-sm text-gray-500 mt-1">This action cannot be undone.</p>
                            </div>
                        </div>
                        
                        <p class="text-sm text-gray-600 mb-2">You are about to delete role: <span id="roleToDeleteName" class="font-semibold text-gray-900"></span></p>
                        <p class="text-xs text-gray-500">This role will be permanently removed from the system.</p>
                    </div>
                    
                    <!-- Modal Footer -->
                    <div class="flex flex-col sm:flex-row sm:items-center justify-end p-6 border-t border-gray-200 space-y-3 sm:space-y-0 sm:space-x-3">
                        <button type="button" onclick="closeDeleteModal()"
                            class="w-full sm:w-auto px-4 py-2.5 text-sm font-medium text-gray-700 bg-gray-100 hover:bg-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 transition-colors">
                            Cancel
                        </button>
                        <form id="deleteForm" method="POST" class="w-full sm:w-auto">
                            @csrf 
                            @method('DELETE')
                            <input type="hidden" name="role_id" id="deleteRoleId">
                            <button type="submit"
                                class="w-full px-4 py-2.5 text-sm font-medium text-white bg-red-600 hover:bg-red-700 rounded-lg focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 transition-colors">
                                Delete Role
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Pagination -->
        @if(method_exists($roles, 'links'))
            <div class="mt-4 sm:mt-6">
                <div class="flex justify-center">
                    {{ $roles->links('pagination::tailwind') }}
                </div>
            </div>
        @endif
    </div>
</div>

<style>
    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(-4px); }
        to { opacity: 1; transform: translateY(0); }
    }
    
    .animate-fade-in {
        animation: fadeIn 0.3s ease-out;
    }

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
        position: fixed;
        width: 100%;
    }

    /* Tablet-specific adjustments */
    @media (min-width: 640px) and (max-width: 768px) {
        .table-responsive {
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
        .mobile-role-card {
            padding: 0.75rem;
        }
        
        .mobile-role-avatar {
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
            padding: 0.75rem;
        }
        
        .mobile-stats-grid {
            grid-template-columns: 1fr;
        }
    }

    /* Medium mobile devices */
    @media (min-width: 361px) and (max-width: 420px) {
        .mobile-role-info {
            font-size: 0.875rem;
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
            padding: 0.75rem 1rem;
        }
    }

    /* Large desktop screens */
    @media (min-width: 1536px) {
        .container-wide {
            max-width: 90rem;
        }
        
        .text-4xl {
            font-size: 2.5rem;
        }
    }

    /* High contrast mode support */
    @media (prefers-contrast: high) {
        .bg-white {
            background-color: #ffffff;
        }
        
        .text-gray-900 {
            color: #000000;
        }
        
        .border-gray-200 {
            border-color: #000000;
        }
    }

    /* Reduced motion support */
    @media (prefers-reduced-motion: reduce) {
        .transition-colors,
        .animate-fade-in,
        .hover\:bg-gray-50 {
            transition: none;
            animation: none;
        }
    }
</style>

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
        
        // Auto-hide success message after 5 seconds
        const successAlert = document.getElementById('alert-success');
        if (successAlert) {
            setTimeout(() => {
                successAlert.style.display = 'none';
            }, 5000);
        }
    });

    // Delete Modal Functions
    function openDeleteModal(roleId, roleName, deleteUrl) {
        const modal = document.getElementById('deleteModal');
        const form = document.getElementById('deleteForm');
        const roleNameSpan = document.getElementById('roleToDeleteName');
        
        // Set the role name in the modal
        roleNameSpan.textContent = roleName;
        
        // Update the form action with the correct URL
        form.action = deleteUrl;
        
        // Show the modal
        modal.classList.remove('hidden');
        document.body.classList.add('overflow-hidden');
    }

    function closeDeleteModal() {
        const modal = document.getElementById('deleteModal');
        
        // Hide the modal
        modal.classList.add('hidden');
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

    // Handle form submission
    document.getElementById('deleteForm')?.addEventListener('submit', function(e) {
        // You can add additional validation here if needed
        // The form will submit to the dynamically set action URL
    });
</script>
@endsection