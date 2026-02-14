@extends('components.layout')

@section('content')
<div class="flex-1 overflow-auto p-6">
    <div class="bg-white shadow-md rounded-lg p-6 max-w-3xl mx-auto">

        <!-- Header -->
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-2xl font-semibold text-gray-800">Role Details</h2>
            <a href="{{ route('roles') }}" 
               class="text-sm text-gray-600 hover:text-gray-800 underline">
                ← Back to Roles
            </a>
        </div>

        <!-- Role Info -->
        <div class="mb-5">
            <h3 class="text-lg font-medium text-gray-700 mb-1">Role Name:</h3>
            <p class="text-gray-900 text-base font-semibold border border-gray-200 rounded-md p-2 bg-gray-50">
                {{ $role->name }}
            </p>
        </div>

        <!-- Permissions -->
        <div>
            <h3 class="text-lg font-medium text-gray-700 mb-3">Assigned Permissions:</h3>

            @if ($role->permissions->count() > 0)
                <div class="flex flex-wrap gap-2">
                    @foreach ($role->permissions as $permission)
                        <span class="inline-block bg-blue-100 text-blue-700 text-sm font-medium px-3 py-1 rounded-full">
                            {{ ucfirst($permission->name) }}
                        </span>
                    @endforeach
                </div>
            @else
                <p class="text-gray-600 text-sm italic">No permissions assigned to this role.</p>
            @endif
        </div>

        <!-- Footer Buttons -->
        <div class="flex justify-end mt-6 space-x-3">
            <a href="{{ route('roles.edit', $role->id) }}" 
               class="bg-blue-600 hover:bg-blue-700 text-white text-sm px-4 py-2 rounded-md shadow-sm">
                Edit Role
            </a>

            @php
    $roleName = strtolower(trim($role->name));
@endphp

            @php
    $roleName = strtolower(trim($role->name));
@endphp

@if(!in_array($roleName, ['admin', 'client']))
    <form action="{{ route('roles.destroy', $role->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this role?');">
        @csrf
        @method('DELETE')
        <button type="submit" 
                class="bg-red-600 hover:bg-red-700 text-white text-sm px-4 py-2 rounded-md shadow-sm">
            Delete
        </button>
    </form>
@endif
        </div>
    </div>
</div>
@endsection
