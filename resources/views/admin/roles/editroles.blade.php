@extends('components.layout')

@section('content')
<div class="flex-1 overflow-auto p-4 sm:p-6">
    <div class="bg-white shadow-md rounded-lg p-4 sm:p-6 max-w-3xl mx-auto">
        <!-- Header -->
        <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center mb-6 gap-3">
            <div>
                <h2 class="text-xl sm:text-2xl font-semibold text-gray-900">Update Role</h2>
                <p class="text-sm text-gray-600 mt-1">Edit role details and permissions</p>
            </div>
            <div class="flex flex-col sm:flex-row gap-2">
                <a href="{{ route('roles') }}" 
                   class="inline-flex items-center justify-center px-4 py-2 text-sm font-medium text-gray-700 bg-gray-100 hover:bg-gray-200 rounded-md shadow-sm transition-colors">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    Back to Roles
                </a>
                
                <button type="button" 
                        onclick="openDeleteModal('{{ $role->id }}', '{{ addslashes($role->name) }}', '{{ route('roles.destroy', $role->id) }}')"
                        class="inline-flex items-center justify-center px-4 py-2 text-sm font-medium text-white bg-red-600 hover:bg-red-700 rounded-md shadow-sm transition-colors">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                    </svg>
                    Delete Role
                </button>
            </div>
        </div>

        <!-- Success/Error Messages -->
        @if (session('success'))
            <div id="alert-success" 
                 class="flex items-center justify-between bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-lg mb-6 shadow-sm animate-fade-in">
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

        @if (session('error'))
            <div id="alert-error" 
                 class="flex items-center justify-between bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded-lg mb-6 shadow-sm animate-fade-in">
                <div class="flex items-center space-x-2">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-5 h-5 text-red-600">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <span class="text-sm sm:text-base">{{ session('error') }}</span>
                </div>
                <button onclick="document.getElementById('alert-error').style.display='none'"
                        class="text-red-700 hover:text-red-900 focus:outline-none text-sm font-medium ml-4">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
        @endif

        <!-- Form -->
        <form action="{{ route('roles.update', $role->id) }}" method="POST">
            @csrf
            @method("PUT")
            
            <!-- Role Name -->
            <div class="mb-5">
                <label for="name" class="block text-sm font-medium text-gray-700 mb-2">
                    Role Name *
                </label>
                <input type="text" id="name" name="name"
                       class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                       placeholder="e.g., Administrator, Editor, Viewer" 
                       value="{{ old('name', $role->name) }}" 
                       required>
                @error('name')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Permissions -->
            <div class="mb-8">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-medium text-gray-900">Assign Permissions</h3>
                    <span class="text-sm text-gray-500 bg-gray-100 px-3 py-1 rounded-full">
                        {{ $permissions->count() }} permissions available
                    </span>
                </div>
                
                <!-- Permissions Grid -->
                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-3">
                    @foreach ($permissions as $permission)
                        <label class="flex items-center space-x-3 border border-gray-200 rounded-lg p-3 hover:bg-gray-50 transition-colors cursor-pointer">
                            <input type="checkbox" 
                                   name="permissions[]" 
                                   value="{{ $permission->name }}" 
                                   {{ $role->hasPermissionTo($permission->name) ? 'checked' : '' }}
                                   class="h-4 w-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500 transition-colors">
                            <div class="flex-1">
                                <span class="text-sm font-medium text-gray-900">{{ ucfirst($permission->name) }}</span>
                                @if($permission->description)
                                    <p class="text-xs text-gray-500 mt-0.5">{{ $permission->description }}</p>
                                @endif
                            </div>
                        </label>
                    @endforeach
                </div>
                
                @error('permissions')
                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Form Actions -->
            <div class="flex flex-col sm:flex-row justify-end gap-3 pt-6 border-t border-gray-200">
                <a href="{{ route('roles') }}" 
                   class="inline-flex items-center justify-center px-5 py-2.5 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 shadow-sm transition-colors">
                    Cancel
                </a>
                <button type="submit"
                        class="inline-flex items-center justify-center px-5 py-2.5 text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 rounded-lg shadow-sm transition-colors focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                    Update Role
                </button>
            </div>
        </form>
    </div>
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
                
                <p class="text-sm text-gray-600 mb-2">You are about to delete the role:</p>
                <div class="bg-red-50 border border-red-200 rounded-lg p-3 mb-4">
                    <div class="flex items-center">
                        <div class="w-8 h-8 rounded-full bg-gradient-to-br from-red-500 to-pink-600 flex items-center justify-center text-white font-semibold text-xs">
                            {{ strtoupper(substr($role->name, 0, 2)) }}
                        </div>
                        <div class="ml-3">
                            <p id="roleToDeleteName" class="text-sm font-semibold text-gray-900"></p>
                            <p class="text-xs text-gray-500">ID: #{{ $role->id }}</p>
                        </div>
                    </div>
                </div>
                <p class="text-xs text-gray-500">This role will be permanently removed from the system along with all assigned permissions.</p>
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
                    <button type="submit"
                        class="w-full px-4 py-2.5 text-sm font-medium text-white bg-red-600 hover:bg-red-700 rounded-lg focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 transition-colors">
                        Delete Role
                    </button>
                </form>
            </div>
        </div>
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

    /* Responsive adjustments */
    @media (max-width: 640px) {
        .permissions-grid {
            grid-template-columns: 1fr;
        }
        
        .form-container {
            padding: 1rem;
        }
        
        .header-actions {
            flex-direction: column;
            gap: 0.75rem;
        }
    }

    /* Improve touch targets on mobile */
    @media (max-width: 640px) {
        button, 
        a[role="button"], 
        .button-like {
            min-height: 44px;
            min-width: 44px;
        }
        
        .checkbox-label {
            padding: 0.75rem;
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
        // Auto-hide success/error messages after 5 seconds
        const successAlert = document.getElementById('alert-success');
        const errorAlert = document.getElementById('alert-error');
        
        if (successAlert) {
            setTimeout(() => {
                successAlert.style.display = 'none';
            }, 5000);
        }
        
        if (errorAlert) {
            setTimeout(() => {
                errorAlert.style.display = 'none';
            }, 5000);
        }
        
        // Prevent horizontal scrolling on mobile
        document.body.style.overflowX = 'hidden';
        
        // Handle form validation
        const form = document.querySelector('form');
        if (form) {
            form.addEventListener('submit', function(e) {
                // Basic validation - check if at least one permission is selected
                const checkboxes = form.querySelectorAll('input[name="permissions[]"]:checked');
                if (checkboxes.length === 0) {
                    e.preventDefault();
                    alert('Please select at least one permission for this role.');
                    return false;
                }
            });
        }
        
        // Select all permissions checkbox (optional feature)
        // You can add a "Select All" button if needed
        const selectAllBtn = document.createElement('button');
        selectAllBtn.type = 'button';
        selectAllBtn.className = 'text-sm text-blue-600 hover:text-blue-800 mb-3';
        selectAllBtn.textContent = 'Select All';
        selectAllBtn.onclick = function() {
            const checkboxes = document.querySelectorAll('input[name="permissions[]"]');
            const allChecked = Array.from(checkboxes).every(cb => cb.checked);
            checkboxes.forEach(cb => cb.checked = !allChecked);
            this.textContent = allChecked ? 'Select All' : 'Deselect All';
        };
        
        // Uncomment to add Select All functionality
        // const permissionsHeader = document.querySelector('.permissions-header');
        // if (permissionsHeader) {
        //     permissionsHeader.appendChild(selectAllBtn);
        // }
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

    // Handle delete form submission
    document.getElementById('deleteForm')?.addEventListener('submit', function(e) {
        // Optional: Add loading state or additional validation
        const submitBtn = this.querySelector('button[type="submit"]');
        if (submitBtn) {
            submitBtn.innerHTML = '<span class="flex items-center justify-center"><svg class="animate-spin h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg> Deleting...</span>';
            submitBtn.disabled = true;
        }
    });
</script>
@endsection