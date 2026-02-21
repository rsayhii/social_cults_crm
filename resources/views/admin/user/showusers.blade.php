{{-- resources/views/users/show.blade.php --}}
@extends('components.layout')

@section('content')
<div class="min-h-screen bg-gray-50 py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="sm:flex sm:items-center sm:justify-between mb-8">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">User Details</h1>
                <p class="mt-1 text-sm text-gray-600">View detailed information for {{ $user->name }}.</p>
            </div>
            <div class="flex space-x-3">
                <a href="{{ route('users.edit', $user) }}"
                   class="inline-flex items-center px-5 py-2.5 bg-amber-600 text-white font-medium text-sm rounded-lg shadow-sm hover:bg-amber-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-amber-500 transition-colors">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l-.586-.585z"></path>
                    </svg>
                    Edit User
                </a>
                <a href="{{ route('users') }}"
                   class="inline-flex items-center px-5 py-2.5 bg-gray-600 text-white font-medium text-sm rounded-lg shadow-sm hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 transition-colors">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    Back to Users
                </a>
            </div>
        </div>

        <!-- User Details Card -->
        <div class="bg-white shadow-sm rounded-xl border border-gray-200 overflow-hidden">
            <div class="px-6 py-8">
                <div class="flex items-start space-x-6">
                    <div class="flex-shrink-0">
                        <div class="w-20 h-20 bg-gradient-to-br from-indigo-500 to-purple-600 rounded-full flex items-center justify-center text-white font-semibold text-xl">
                            {{ strtoupper(substr($user->name, 0, 2)) }}
                        </div>
                    </div>
                    <div class="flex-1 min-w-0">
                        <h2 class="text-2xl font-bold text-gray-900">{{ $user->name }}</h2>
                        <p class="text-sm text-gray-500 mt-1">{{ $user->email }}</p>
                    </div>
                </div>

                <div class="mt-8 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">ID</label>
                        <p class="text-lg font-semibold text-gray-900">{{ $user->id }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Email</label>
                        <p class="text-lg text-gray-900 break-all">{{ $user->email }}</p>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Salary</label>
                        <p class="text-lg font-semibold text-gray-900">₹{{ number_format($user->salary, 2) }}</p>
                    </div>
                    
                    <div class="md:col-span-2 lg:col-span-3">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Roles</label>
                        <div class="flex flex-wrap gap-2">
                            @forelse ($user->getRoleNames() as $role)
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium
                                    @if($role === 'admin') bg-red-100 text-red-800
                                    @elseif($role === 'editor') bg-amber-100 text-amber-800
                                    @elseif($role === 'user') bg-gray-100 text-gray-800
                                    @else bg-indigo-100 text-indigo-800 @endif">
                                    {{ ucfirst($role) }}
                                </span>
                            @empty
                                <p class="text-sm text-gray-500">No roles assigned</p>
                            @endforelse
                        </div>
                    </div>
                    <div class="md:col-span-2 lg:col-span-3">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Created At</label>
                        <p class="text-sm text-gray-900">{{ $user->created_at->format('F d, Y \a\t g:i A') }}</p>
                    </div>
                    @if($user->updated_at !== $user->created_at)
                        <div class="md:col-span-2 lg:col-span-3">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Updated At</label>
                            <p class="text-sm text-gray-900">{{ $user->updated_at->format('F d, Y \a\t g:i A') }}</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection