@extends('components.layout')

@section('content')

    <main class="max-w-4xl mx-auto py-6 sm:px-6 lg:px-8">
        <div class="bg-white rounded-lg shadow-sm p-6">
            <!-- Success Message -->
            @if(session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-6">
                    {{ session('success') }}
                </div>
            @endif

            <div class="flex items-center gap-6 mb-8">
                <div class="w-20 h-20 bg-gradient-to-br from-indigo-400 to-purple-400 rounded-full flex items-center justify-center">
                    <span class="text-white font-semibold text-2xl">
                        {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                    </span>
                </div>
                <div>
                    <h2 class="text-2xl font-bold text-gray-900">{{ Auth::user()->name }}</h2>
                    <p class="text-gray-600">{{ Auth::user()->email }}</p>
                    <span class="inline-block mt-1 px-3 py-1 bg-blue-100 text-blue-800 text-sm rounded-full capitalize">
                        {{ Auth::user()->status }}
                    </span>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="space-y-6">
                    <div>
                        <h3 class="text-lg font-semibold mb-4 text-gray-800">Personal Information</h3>
                        <div class="space-y-3">
                            <div>
                                <label class="text-sm font-medium text-gray-500">Username</label>
                                <p class="text-gray-900">{{ Auth::user()->name }}</p>
                            </div>
                            <div>
                                <label class="text-sm font-medium text-gray-500">Email Address</label>
                                <p class="text-gray-900">{{ Auth::user()->email }}</p>
                            </div>
                            <div>
                                <label class="text-sm font-medium text-gray-500">Account Status</label>
                                <p class="text-gray-900 capitalize">{{ Auth::user()->status }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="space-y-6">
                    <div>
                        <h3 class="text-lg font-semibold mb-4 text-gray-800">Account Information</h3>
                        <div class="space-y-3">
                            <div>
                                <label class="text-sm font-medium text-gray-500">Member Since</label>
                                <p class="text-gray-900">{{ Auth::user()->created_at->format('F d, Y') }}</p>
                            </div>
                            <div>
                                <label class="text-sm font-medium text-gray-500">Last Updated</label>
                                <p class="text-gray-900">{{ Auth::user()->updated_at->format('F d, Y') }}</p>
                            </div>
                            <div>
                                <label class="text-sm font-medium text-gray-500">User ID</label>
                                <p class="text-gray-900">{{ Auth::user()->id }}</p>
                            </div>
                        </div>
                    </div>

                    <div class="pt-4">
                        <a href="/profile/edit" class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition-colors">
                            Edit Profile
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </main>
 @endsection
