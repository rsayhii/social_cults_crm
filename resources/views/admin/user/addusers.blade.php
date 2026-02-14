@extends('components.layout')

@section('content')
<div class="min-h-screen bg-gray-50 py-8">
    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-900">Create New User</h1>
            <p class="mt-1 text-sm text-gray-600">Fill in the details below to add a new user to the system.</p>
        </div>

        <!-- Form Card -->
        <div class="bg-white shadow-sm rounded-xl border border-gray-200 p-6 sm:p-8">
            <form action="{{ route('users.store') }}" method="POST" novalidate>
                @csrf

                <!-- Name Field -->
                <div class="mb-6">
                    <label for="name" class="block text-sm font-medium text-gray-700 mb-2">
                        <svg class="inline w-4 h-4 mr-1 -mt-0.5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                        </svg>
                        Full Name
                    </label>
                    <input type="text" 
                           name="name" 
                           id="name"
                           value="{{ old('name') }}"
                           class="w-full px-4 py-2.5 border {{ $errors->has('name') ? 'border-red-500 focus:ring-red-500' : 'border-gray-300 focus:ring-indigo-500' }} rounded-lg focus:outline-none focus:ring-2 focus:ring-offset-1 transition-colors"
                           placeholder="John Doe"
                           required>
                    @error('name')
                        <p class="mt-1.5 text-sm text-red-600 flex items-center">
                            <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path></svg>
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                <!-- Email Field -->
                <div class="mb-6">
                    <label for="email" class="block text-sm font-medium text-gray-700 mb-2">
                        <svg class="inline w-4 h-4 mr-1 -mt-0.5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                        </svg>
                        Email Address
                    </label>
                    <input type="email" 
                           name="email" 
                           id="email"
                           value="{{ old('email') }}"
                           class="w-full px-4 py-2.5 border {{ $errors->has('email') ? 'border-red-500 focus:ring-red-500' : 'border-gray-300 focus:ring-indigo-500' }} rounded-lg focus:outline-none focus:ring-2 focus:ring-offset-1 transition-colors"
                           placeholder="john@example.com"
                           required>
                    @error('email')
                        <p class="mt-1.5 text-sm text-red-600 flex items-center">
                            <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path></svg>
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                <!-- Password Field -->
                <div class="mb-6">
                    <label for="password" class="block text-sm font-medium text-gray-700 mb-2">
                        <svg class="inline w-4 h-4 mr-1 -mt-0.5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                        </svg>
                        Password
                    </label>
                    <input type="password" 
                           name="password" 
                           id="password"
                           class="w-full px-4 py-2.5 border {{ $errors->has('password') ? 'border-red-500 focus:ring-red-500' : 'border-gray-300 focus:ring-indigo-500' }} rounded-lg focus:outline-none focus:ring-2 focus:ring-offset-1 transition-colors"
                           placeholder="••••••••"
                           required>
                    @error('password')
                        <p class="mt-1.5 text-sm text-red-600 flex items-center">
                            <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path></svg>
                            {{ $message }}
                        </p>
                    @enderror
                </div>


                <div class="mb-8">
                    <label class="block text-sm font-medium text-gray-700 mb-3">
                        <svg class="inline w-4 h-4 mr-1 -mt-0.5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                        </svg>
                        Assign Role
                    </label>
                    <div class="space-y-3">
                        @foreach ($roles as $role)
                            <label for="role_{{ $role->id }}" class="flex items-center p-3 rounded-lg border border-gray-200 hover:bg-gray-50 cursor-pointer transition-colors">
                                <input type="radio"
                                       id="role_{{ $role->id }}"
                                       name="roles[]"
                                       value="{{ $role->id }}"
                                       {{ in_array($role->id, old('roles', [])) ? 'checked' : '' }}
                                       class="w-4 h-4 text-indigo-600 border-gray-300 focus:ring-indigo-500 role-radio"
                                       data-role-name="{{ strtolower($role->name) }}">
                                <span class="ml-3 text-sm font-medium text-gray-700 capitalize">{{ $role->name }}</span>
                                <span class="ml-auto text-xs text-gray-500">
                                    @if(strtolower($role->name) === 'admin') Super user
                                    @elseif($role->name === 'editor') Can edit content
                                    @elseif($role->name === 'user') Standard access
                                    @else {{ ucfirst($role->name) }} @endif
                                </span>
                            </label>
                        @endforeach
                    </div>
                    @error('roles')
                        <p class="mt-2 text-sm text-red-600 flex items-center">
                            <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path></svg>
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                <!-- Salary Field -->
                <div class="mb-6" id="salary-field">
                    <label for="salary" class="block text-sm font-medium text-gray-700 mb-2">
                        <svg class="inline w-4 h-4 mr-1 -mt-0.5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 8.25L9 8.25M15 11.25H9M12 17.25L9 14.25H10.5C12.1569 14.25 13.5 12.9069 13.5 11.25C13.5 9.59315 12.1569 8.25 10.5 8.25M21 12C21 16.9706 16.9706 21 12 21C7.02944 21 3 16.9706 3 12C3 7.02944 7.02944 3 12 3C16.9706 3 21 7.02944 21 12Z"></path>
                        </svg>
                        salary
                    </label>
                    <input type="text" 
                           name="salary" 
                           id="salary"
                           class="w-full px-4 py-2.5 border {{ $errors->has('salary') ? 'border-red-500 focus:ring-red-500' : 'border-gray-300 focus:ring-indigo-500' }} rounded-lg focus:outline-none focus:ring-2 focus:ring-offset-1 transition-colors"
                           placeholder="₹30000"
                          >
                    @error('salary')
                        <p class="mt-1.5 text-sm text-red-600 flex items-center">
                            <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path></svg>
                            {{ $message }}
                        </p>
                    @enderror
                </div>

<script>
        document.addEventListener('DOMContentLoaded', function() {
            const roleRadios = document.querySelectorAll('.role-radio');
            const salaryField = document.getElementById('salary-field');
            const salaryInput = document.getElementById('salary');

            function toggleSalaryField() {
                const selectedRole = document.querySelector('.role-radio:checked');
                
                if (selectedRole && selectedRole.dataset.roleName === 'client') {
                    salaryField.style.display = 'none';
                    salaryInput.removeAttribute('required');
                    salaryInput.value = '';
                } else {
                    salaryField.style.display = 'block';
                    salaryInput.setAttribute('required', 'required');
                }
            }

            toggleSalaryField();

            roleRadios.forEach(radio => {
                radio.addEventListener('change', toggleSalaryField);
            });
        });
</script>

                <!-- Actions -->
                <div class="flex items-center justify-between pt-4 border-t border-gray-200">
                    <a href="{{ route('users') }}" 
                       class="text-sm font-medium text-gray-600 hover:text-gray-900 transition-colors">
                        Cancel
                    </a>
                    <button type="submit"
                            class="inline-flex items-center px-6 py-2.5 bg-indigo-600 text-white font-medium text-sm rounded-lg shadow-sm hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-colors">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                        Create User
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
