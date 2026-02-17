{{-- resources/views/users/edit.blade.php - Fixed roles checkboxes to use role names instead of IDs --}}
@extends('components.layout')

@section('content')
<div class="min-h-screen bg-gray-50 py-8">
    <div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="sm:flex sm:items-center sm:justify-between mb-8">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">Edit User</h1>
                <p class="mt-1 text-sm text-gray-600">Update {{ $user->name }}'s information.</p>
            </div>
            <a href="{{ route('users') }}"
               class="inline-flex items-center px-5 py-2.5 bg-gray-600 text-white font-medium text-sm rounded-lg shadow-sm hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 transition-colors">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                Back to Users
            </a>
        </div>

        <!-- Success/Error Messages -->
        @if ($errors->any())
            <div class="mb-6 p-4 bg-red-50 border border-red-200 text-red-800 rounded-lg">
                <ul class="mb-0 space-y-1">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        @if(session('success'))
            <div class="mb-6 p-4 bg-green-50 border border-green-200 text-green-800 rounded-lg flex items-center">
                <svg class="w-5 h-5 mr-2 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                </svg>
                {{ session('success') }}
            </div>
        @endif

        <!-- Edit Form -->
        <div class="bg-white shadow-sm rounded-xl border border-gray-200 overflow-hidden">
            <div class="px-6 py-8">
                <form action="{{ route('users.update', $user) }}" method="POST">
                    @csrf @method('PUT')

                    <div class="space-y-6">
                        <!-- Name -->
                        <div>
                            <label for="name" class="block text-sm font-medium text-gray-700 mb-2">Name</label>
                            <input type="text" name="name" id="name" value="{{ old('name', $user->name) }}" required
                                   class="block w-full rounded-lg border border-gray-300 px-3 py-2 text-gray-900 placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent @error('name') border-red-500 @enderror">
                            @error('name')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Email -->
                        <div>
                            <label for="email" class="block text-sm font-medium text-gray-700 mb-2">Email</label>
                            <input type="email" name="email" id="email" value="{{ old('email', $user->email) }}" required
                                   class="block w-full rounded-lg border border-gray-300 px-3 py-2 text-gray-900 placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent @error('email') border-red-500 @enderror">
                            @error('email')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Password (Optional) -->
                        <div>
                            <label for="password" class="block text-sm font-medium text-gray-700 mb-2">Password (leave blank to keep current)</label>
                            <input type="password" name="password" id="password" placeholder="Enter new password if changing"
                                   class="block w-full rounded-lg border border-gray-300 px-3 py-2 text-gray-900 placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent @error('password') border-red-500 @enderror">
                            @error('password')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Salary -->
                        <div id="salary-field">
                            <label for="salary" class="block text-sm font-medium text-gray-700 mb-2">Salary</label>
                            <input type="number" name="salary" id="salary" value="{{ old('salary', $user->salary) }}" step="0.01" min="0"
                                   class="block w-full rounded-lg border border-gray-300 px-3 py-2 text-gray-900 placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent @error('salary') border-red-500 @enderror">
                            @error('salary')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Role -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Role</label>
                            <div class="space-y-3">
                                @foreach($roles as $role)
                                    <div class="flex items-center">
                                        <input type="radio" name="roles[]" id="role_{{ $role->id }}" value="{{ $role->id }}"
                                               {{ $user->roles->contains('id', $role->id) ? 'checked' : '' }}
                                               data-role-name="{{ strtolower($role->name) }}"
                                               class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 role-radio">
                                        <label for="role_{{ $role->id }}" class="ml-3 block text-sm font-medium text-gray-700">
                                            {{ ucfirst($role->name) }}
                                        </label>
                                    </div>
                                @endforeach
                            </div>
                            @error('roles')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="mt-8 flex justify-end space-x-3">
                        <a href="{{ route('users') }}"
                           class="inline-flex items-center px-5 py-2.5 bg-gray-600 text-white font-medium text-sm rounded-lg shadow-sm hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 transition-colors">
                            Cancel
                        </a>
                        <button type="submit"
                                class="inline-flex items-center px-5 py-2.5 bg-indigo-600 text-white font-medium text-sm rounded-lg shadow-sm hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-colors">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            Update User
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
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
                salaryInput.value = ''; // Clear value if client is selected
            } else {
                salaryField.style.display = 'block';
            }
        }

        // Initial check on page load
        toggleSalaryField();

        // Listen for changes
        roleRadios.forEach(radio => {
            radio.addEventListener('change', toggleSalaryField);
        });
    });
</script>
@endsection
