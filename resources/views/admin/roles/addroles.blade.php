@extends('components.layout')

@section('content')
<div class="flex-1 overflow-auto p-6">
    <div class="bg-white shadow-md rounded-lg p-6 max-w-3xl mx-auto">
        <!-- Header -->
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-2xl font-semibold">Create Role</h2>
            <a href="{{ route('roles') }}" 
               class="text-sm text-gray-600 hover:text-gray-800 underline">
                ← Back to Roles
            </a>
        </div>

        <!-- Form -->
        <form action="{{ route('roles.store') }}" method="POST">
            @csrf

            <!-- Role Name -->
            <div class="mb-5">
                <label for="name" class="block text-sm font-medium text-gray-700 mb-2">
                    Role Name
                </label>
                <input type="text" id="name" name="name"
                       class="w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 text-sm p-2"
                       placeholder="Enter role name" required>
                         @error('name')
                           <span style="color: red;">{{ $message }}</span>
                       @enderror
            </div>

            <!-- Permissions -->
            <div class="mb-6">
                <h3 class="text-lg font-medium text-gray-800 mb-3">Assign Permissions</h3>
                <div class="grid grid-cols-2 sm:grid-cols-3 gap-3">
                    @foreach ($permissions as $permission)
                        <label class="flex items-center space-x-2 border rounded-md p-2 hover:bg-gray-50">
                            <input type="checkbox" name="permissions[]" value="{{ $permission->name }}"
                                   class="text-blue-600 border-gray-300 rounded focus:ring-blue-500">
                            <span class="text-sm text-gray-700">{{ ucfirst($permission->name) }}</span>
                        </label>
                    @endforeach
                </div>
            </div>

            <!-- Submit Button -->
            <div class="flex justify-end">
                <button type="submit"
                        class="bg-blue-600 hover:bg-blue-700 text-white text-sm px-5 py-2 rounded-md shadow-sm transition">
                    Save Role
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
