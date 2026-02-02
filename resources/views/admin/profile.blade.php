@extends('components.layout')

@section('content')

<main class="min-h-screen bg-gradient-to-br from-gray-50 to-blue-50 py-8 px-4">
    <div class="max-w-5xl mx-auto">
        
        <!-- Success Message -->
        @if(session('success'))
            <div class="mb-8 animate-fade-in">
                <div class="bg-green-50 border border-green-200 rounded-xl p-4 shadow-sm">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-green-400" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                            </svg>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm font-medium text-green-800">{{ session('success') }}</p>
                        </div>
                    </div>
                </div>
            </div>
        @endif

        <!-- Main Profile Card -->
        <div class="bg-white rounded-2xl shadow-lg overflow-hidden border border-gray-100">
            <!-- Profile Header with Gradient -->
            <div class="relative">
                <div class="h-32 bg-gradient-to-r from-blue-100 to-indigo-100"></div>
                
                <!-- Profile Picture and Info -->
                <div class="relative px-8 pb-8">
                    <div class="flex flex-col sm:flex-row items-start sm:items-end gap-6 -mt-16">
                        <!-- Avatar -->
                        <div class="relative">
                            <div class="w-32 h-32 bg-gradient-to-br from-blue-400 to-indigo-400 rounded-2xl shadow-xl flex items-center justify-center border-4 border-white">
                                <span class="text-white font-bold text-4xl">
                                    {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                                </span>
                            </div>
                            <!-- Status Badge -->
                            <div class="absolute -bottom-2 -right-2 bg-white px-3 py-1 rounded-full shadow-md border hidden">
                                <span class="text-sm font-semibold text-blue-600 capitalize">{{ Auth::user()->status }}</span>
                            </div>
                        </div>

                        <!-- User Info and Actions -->
                        <div class="flex-1">
                            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                                <div>
                                    <h1 class="text-3xl font-bold text-gray-900">{{ Auth::user()->name }}</h1>
                                    <p class="text-gray-600 mt-1">{{ Auth::user()->email }}</p>
                                    <div class="mt-3 flex items-center gap-2 text-sm text-gray-500">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                        </svg>
                                        <span>Joined {{ Auth::user()->created_at->format('F Y') }}</span>
                                    </div>
                                </div>
                                
                                <!-- Action Buttons -->
                                <div class="flex items-center gap-3">
                                    <a href="/profile/edit" 
                                       class="inline-flex items-center gap-2 px-5 py-2.5 bg-gradient-to-r from-blue-500 to-indigo-500 text-white font-medium rounded-xl hover:from-blue-600 hover:to-indigo-600 transition-all shadow-md hover:shadow-lg">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                        </svg>
                                        Edit Profile
                                    </a>
                                    
                                    <form method="GET" action="{{ route('logout') }}">
                                        @csrf
                                        <button type="submit"
                                            class="inline-flex items-center gap-2 px-4 py-2.5 border border-gray-300 text-gray-700 font-medium rounded-xl hover:bg-gray-50 transition-colors">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                                            </svg>
                                            Logout
                                        </button>
                                    </form>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Profile Details Grid -->
            <div class="px-8 pb-8">
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                    
                    <!-- Personal Information Card -->
                    <div class="bg-gradient-to-br from-gray-50 to-white rounded-xl border border-gray-200 p-6 hover:border-blue-200 transition-colors">
                        <div class="flex items-center gap-3 mb-6">
                            <div class="p-3 bg-blue-100 rounded-lg">
                                <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                </svg>
                            </div>
                            <h3 class="text-xl font-semibold text-gray-900">Personal Information</h3>
                        </div>
                        
                        <div class="space-y-5">
                            <div class="pb-4 border-b border-gray-100">
                                <div class="flex justify-between items-start">
                                    <div>
                                        <p class="text-sm font-medium text-gray-500 uppercase tracking-wider">Username</p>
                                        <p class="text-gray-900 font-semibold text-lg mt-1">{{ Auth::user()->name }}</p>
                                    </div>
                                    <svg class="w-5 h-5 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                    </svg>
                                </div>
                            </div>
                            
                            <div class="pb-4 border-b border-gray-100">
                                <div class="flex justify-between items-start">
                                    <div>
                                        <p class="text-sm font-medium text-gray-500 uppercase tracking-wider">Email Address</p>
                                        <p class="text-gray-900 font-semibold text-lg mt-1">{{ Auth::user()->email }}</p>
                                    </div>
                                    <svg class="w-5 h-5 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                                    </svg>
                                </div>
                            </div>
                            
                            <div>
                                <div class="flex justify-between items-start">
                                    <div>
                                        <p class="text-sm font-medium text-gray-500 uppercase tracking-wider">Account Status</p>
                                        <div class="mt-1">
                                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium 
                                                {{ Auth::user()->status === 'active' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                                                {{ Auth::user()->status }}
                                            </span>
                                        </div>
                                    </div>
                                    <svg class="w-5 h-5 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Account Information Card -->
                    <div class="bg-gradient-to-br from-gray-50 to-white rounded-xl border border-gray-200 p-6 hover:border-indigo-200 transition-colors">
                        <div class="flex items-center gap-3 mb-6">
                            <div class="p-3 bg-indigo-100 rounded-lg">
                                <svg class="w-6 h-6 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                                </svg>
                            </div>
                            <h3 class="text-xl font-semibold text-gray-900">Account Information</h3>
                        </div>
                        
                        <div class="space-y-5">
                            <div class="pb-4 border-b border-gray-100">
                                <div class="flex justify-between items-start">
                                    <div>
                                        <p class="text-sm font-medium text-gray-500 uppercase tracking-wider">Member Since</p>
                                        <p class="text-gray-900 font-semibold text-lg mt-1">
                                            {{ Auth::user()->created_at->format('F d, Y') }}
                                        </p>
                                    </div>
                                    <svg class="w-5 h-5 text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                    </svg>
                                </div>
                            </div>
                            
                            <div class="pb-4 border-b border-gray-100">
                                <div class="flex justify-between items-start">
                                    <div>
                                        <p class="text-sm font-medium text-gray-500 uppercase tracking-wider">Last Updated</p>
                                        <p class="text-gray-900 font-semibold text-lg mt-1">
                                            {{ Auth::user()->updated_at->format('F d, Y') }}
                                        </p>
                                    </div>
                                    <svg class="w-5 h-5 text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                </div>
                            </div>
                            
                            <div>
                                <div class="flex justify-between items-start">
                                    <div>
                                        <p class="text-sm font-medium text-gray-500 uppercase tracking-wider">User ID</p>
                                        <p class="text-gray-900 font-semibold text-lg mt-1">#{{ Auth::user()->id }}</p>
                                    </div>
                                    <svg class="w-5 h-5 text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2h-5m-4 0V5a2 2 0 114 0v1m-4 0a2 2 0 104 0m-5 8a2 2 0 100-4 2 2 0 000 4zm0 0c1.306 0 2.417.835 2.83 2M9 14a3.001 3.001 0 00-2.83 2M15 11h3m-3 4h2"/>
                                    </svg>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Quick Stats -->
                <div class="mt-8 grid grid-cols-2 md:grid-cols-4 gap-4">
                    <div class="bg-gradient-to-br from-blue-50 to-white rounded-xl border border-blue-100 p-4 text-center">
                        <div class="text-2xl font-bold text-blue-600">
                            {{ (int) Auth::user()->created_at->diffInDays(now()) }}
                        </div>
                        <div class="text-sm text-gray-600 mt-1">Days Active</div>
                    </div>

                    <div class="bg-gradient-to-br from-indigo-50 to-white rounded-xl border border-indigo-100 p-4 text-center">
                        <div class="text-2xl font-bold text-indigo-600">{{ Auth::user()->created_at->format('Y') }}</div>
                        <div class="text-sm text-gray-600 mt-1">Joined Year</div>
                    </div>
                    <div class="bg-gradient-to-br from-blue-50 to-white rounded-xl border border-blue-100 p-4 text-center">
                        <div class="text-2xl font-bold text-blue-600">{{ Auth::user()->created_at->format('m') }}</div>
                        <div class="text-sm text-gray-600 mt-1">Joined Month</div>
                    </div>
                    <div class="bg-gradient-to-br from-indigo-50 to-white rounded-xl border border-indigo-100 p-4 text-center">
                        <div class="text-2xl font-bold text-indigo-600">{{ Auth::user()->id }}</div>
                        <div class="text-sm text-gray-600 mt-1">Account ID</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>

<style>
    .animate-fade-in {
        animation: fadeIn 0.3s ease-out;
    }
    
    @keyframes fadeIn {
        from {
            opacity: 0;
            transform: translateY(-5px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
</style>

@endsection