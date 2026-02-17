@extends('components.layout')

@section('content')

    <main class="max-w-2xl mx-auto py-6 sm:px-6 lg:px-8">
        <div class="bg-white rounded-lg shadow-sm p-6">
            <!-- Success Message -->
            @if(session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-6">
                    {{ session('success') }}
                </div>
            @endif

            <div class="flex items-center gap-4 mb-6">
                <div class="w-16 h-16 bg-gradient-to-br from-indigo-400 to-purple-400 rounded-full flex items-center justify-center">
                    <span class="text-white font-semibold text-xl">
                        {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                    </span>
                </div>
                <div>
                    <h2 class="text-2xl font-bold text-gray-900">Edit Your Profile</h2>
                    <p class="text-gray-600">Update your personal information</p>
                </div>
            </div>

            <form method="POST" action="{{ route('profile.update') }}">
                @csrf
                
                <div class="space-y-6">
                    <!-- Admin Name Field -->
                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700 mb-2">
                            Admin Name
                        </label>
                        <input 
                            type="text" 
                            id="name" 
                            name="name" 
                            value="{{ old('name', Auth::user()->name) }}"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('name') border-red-500 @enderror"
                            required
                        >
                        @error('name')
                            <span class="text-red-500 text-sm mt-1">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Email Field -->
                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700 mb-2">
                            Email Address
                        </label>
                        <input 
                            type="email" 
                            id="email" 
                            name="email" 
                            value="{{ old('email', Auth::user()->email) }}"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('email') border-red-500 @enderror"
                            required
                        >
                        @error('email')
                            <span class="text-red-500 text-sm mt-1">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Company Name Field -->
                    @if(Auth::user()->company)
                    <div>
                        <label for="company_name" class="block text-sm font-medium text-gray-700 mb-2">
                            Company Name
                        </label>
                        <input 
                            type="text" 
                            id="company_name" 
                            name="company_name" 
                            value="{{ old('company_name', Auth::user()->company->name) }}"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('company_name') border-red-500 @enderror"
                        >
                        @error('company_name')
                            <span class="text-red-500 text-sm mt-1">{{ $message }}</span>
                        @enderror
                    </div>
                    @endif

                    <!-- Status Field (Read-only) -->
                    <div>
                        <label for="status" class="block text-sm font-medium text-gray-700 mb-2">
                            Account Status
                        </label>
                        <input 
                            type="text" 
                            id="status" 
                            value="{{ ucfirst(Auth::user()->company->status ?? 'Active') }}"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md bg-gray-50 cursor-not-allowed"
                            disabled
                        >
                        <p class="text-sm text-gray-500 mt-1">Account status cannot be changed</p>
                    </div>

                    <!-- Member Since (Read-only) -->
                    <div>
                        <label for="created_at" class="block text-sm font-medium text-gray-700 mb-2">
                            Member Since
                        </label>
                        <input 
                            type="text" 
                            id="created_at" 
                            value="{{ Auth::user()->created_at->format('F d, Y') }}"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md bg-gray-50 cursor-not-allowed"
                            disabled
                        >
                    </div>

                    <!-- Password Change Section -->
                    <div class="border-t border-gray-200 pt-6 mt-6">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Change Password</h3>
                        
                        <div class="space-y-4">
                            <!-- Current Password -->
                            <div>
                                <label for="current_password" class="block text-sm font-medium text-gray-700 mb-2">
                                    Current Password
                                </label>
                                <div class="relative">
                                    <input 
                                        type="password" 
                                        id="current_password" 
                                        name="current_password" 
                                        class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 pr-10 @error('current_password') border-red-500 @enderror"
                                    >
                                    <button type="button" class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-500 hover:text-gray-700 focus:outline-none toggle-password" data-target="current_password">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                </div>
                                @error('current_password')
                                    <span class="text-red-500 text-sm mt-1">{{ $message }}</span>
                                @enderror
                            </div>

                            <!-- New Password -->
                            <div>
                                <label for="new_password" class="block text-sm font-medium text-gray-700 mb-2">
                                    New Password
                                </label>
                                <div class="relative">
                                    <input 
                                        type="password" 
                                        id="new_password" 
                                        name="new_password" 
                                        class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 pr-10 @error('new_password') border-red-500 @enderror"
                                    >
                                    <button type="button" class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-500 hover:text-gray-700 focus:outline-none toggle-password" data-target="new_password">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                </div>
                                @error('new_password')
                                    <span class="text-red-500 text-sm mt-1">{{ $message }}</span>
                                @enderror
                            </div>

                            <!-- Confirm Password -->
                            <div>
                                <label for="new_password_confirmation" class="block text-sm font-medium text-gray-700 mb-2">
                                    Confirm New Password
                                </label>
                                <div class="relative">
                                    <input 
                                        type="password" 
                                        id="new_password_confirmation" 
                                        name="new_password_confirmation" 
                                        class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 pr-10"
                                    >
                                    <button type="button" class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-500 hover:text-gray-700 focus:outline-none toggle-password" data-target="new_password_confirmation">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Form Actions -->
                    <div class="flex items-center justify-between pt-6 border-t border-gray-200">
                        <a href="/profile" class="px-4 py-2 text-gray-600 border border-gray-300 rounded-md hover:bg-gray-50 transition-colors">
                            Cancel
                        </a>
                        <button 
                            type="submit" 
                            class="px-6 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-colors"
                        >
                            Update Profile
                        </button>
                    </div>
                </div>
            </form>

            <!-- Additional Information -->
            <div class="mt-8 p-4 bg-blue-50 rounded-lg">
                <h3 class="text-sm font-semibold text-blue-900 mb-2">Profile Information</h3>
                <p class="text-sm text-blue-700">
                    Your profile information is used to personalize your experience and help other users recognize you.
                </p>
            </div>
        </div>
    </main>

    <script>
        // Simple form validation
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.querySelector('form');
            const nameInput = document.getElementById('name');
            const emailInput = document.getElementById('email');

            form.addEventListener('submit', function(e) {
                let valid = true;

                // Name validation
                if (nameInput.value.trim().length < 3) {
                    valid = false;
                    if (!nameInput.nextElementSibling?.classList.contains('text-red-500')) {
                        const error = document.createElement('span');
                        error.className = 'text-red-500 text-sm mt-1';
                        error.textContent = 'Name must be at least 3 characters long';
                        nameInput.parentNode.appendChild(error);
                    }
                }

                // Email validation
                const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                if (!emailRegex.test(emailInput.value.trim())) {
                    valid = false;
                    if (!emailInput.nextElementSibling?.classList.contains('text-red-500')) {
                        const error = document.createElement('span');
                        error.className = 'text-red-500 text-sm mt-1';
                        error.textContent = 'Please enter a valid email address';
                        emailInput.parentNode.appendChild(error);
                    }
                }

                if (!valid) {
                    e.preventDefault();
                }
            });

            // Clear errors on input
            nameInput.addEventListener('input', function() {
                const error = this.nextElementSibling;
                if (error && error.classList.contains('text-red-500')) {
                    error.remove();
                }
            });

            emailInput.addEventListener('input', function() {
                const error = this.nextElementSibling;
                if (error && error.classList.contains('text-red-500')) {
                    error.remove();
                }
            });

            // Password visibility toggle
            document.querySelectorAll('.toggle-password').forEach(button => {
                button.addEventListener('click', function() {
                    const targetId = this.getAttribute('data-target');
                    const input = document.getElementById(targetId);
                    const icon = this.querySelector('i');
                    
                    if (input.type === 'password') {
                        input.type = 'text';
                        icon.classList.remove('fa-eye');
                        icon.classList.add('fa-eye-slash');
                    } else {
                        input.type = 'password';
                        icon.classList.remove('fa-eye-slash');
                        icon.classList.add('fa-eye');
                    }
                });
            });
        });
    </script>

     @endsection
