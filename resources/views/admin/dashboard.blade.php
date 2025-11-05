@extends('components.layout')

@section('content')
     <div class="flex-1 overflow-auto p-6">
                <div class="max-w-7xl mx-auto space-y-6">
                    <!-- Page Header -->
                    <div>
                        <h2 class="text-2xl lg:text-3xl font-bold text-gray-900">Dashboard Overview</h2>
                        <p class="text-gray-500 mt-2">Track your marketing performance and client relationships</p>
                    </div>

                    <!-- Stats Cards -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                        <!-- Revenue Card -->
                        <div
                            class="fade-in bg-white rounded-xl border border-gray-200 p-6 relative overflow-hidden shadow-sm hover:shadow-md transition-shadow">
                            <div
                                class="absolute top-0 right-0 w-24 h-24 transform translate-x-6 -translate-y-6 bg-green-500 rounded-full opacity-10">
                            </div>
                            <div class="flex justify-between items-start">
                                <div class="flex-1">
                                    <p class="text-sm font-medium text-gray-500 uppercase tracking-wide">Total Revenue
                                    </p>
                                    <h3 class="text-2xl font-bold mt-2 text-gray-900">$41,000</h3>
                                    <div class="flex items-center mt-3">
                                        <span class="text-sm font-semibold text-green-600">↑ 12.5%</span>
                                        <span class="text-xs text-gray-500 ml-2">vs last month</span>
                                    </div>
                                </div>
                                <div class="p-3 rounded-xl bg-green-100">
                                    <svg class="w-6 h-6 text-green-500" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1" />
                                    </svg>
                                </div>
                            </div>
                        </div>

                        <!-- Clients Card -->
                        <div
                            class="fade-in bg-white rounded-xl border border-gray-200 p-6 relative overflow-hidden shadow-sm hover:shadow-md transition-shadow">
                            <div
                                class="absolute top-0 right-0 w-24 h-24 transform translate-x-6 -translate-y-6 bg-blue-500 rounded-full opacity-10">
                            </div>
                            <div class="flex justify-between items-start">
                                <div class="flex-1">
                                    <p class="text-sm font-medium text-gray-500 uppercase tracking-wide">Active Clients
                                    </p>
                                    <h3 class="text-2xl font-bold mt-2 text-gray-900">2</h3>
                                    <div class="flex items-center mt-3">
                                        <span class="text-sm font-semibold text-green-600">↑ 8.2%</span>
                                        <span class="text-xs text-gray-500 ml-2">vs last month</span>
                                    </div>
                                </div>
                                <div class="p-3 rounded-xl bg-blue-100">
                                    <svg class="w-6 h-6 text-blue-500" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z" />
                                    </svg>
                                </div>
                            </div>
                        </div>

                        <!-- Campaigns Card -->
                        <div
                            class="fade-in bg-white rounded-xl border border-gray-200 p-6 relative overflow-hidden shadow-sm hover:shadow-md transition-shadow">
                            <div
                                class="absolute top-0 right-0 w-24 h-24 transform translate-x-6 -translate-y-6 bg-purple-500 rounded-full opacity-10">
                            </div>
                            <div class="flex justify-between items-start">
                                <div class="flex-1">
                                    <p class="text-sm font-medium text-gray-500 uppercase tracking-wide">Active
                                        Campaigns</p>
                                    <h3 class="text-2xl font-bold mt-2 text-gray-900">3</h3>
                                    <div class="flex items-center mt-3">
                                        <span class="text-sm font-semibold text-green-600">↑ 15.3%</span>
                                        <span class="text-xs text-gray-500 ml-2">vs last month</span>
                                    </div>
                                </div>
                                <div class="p-3 rounded-xl bg-purple-100">
                                    <svg class="w-6 h-6 text-purple-500" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z" />
                                    </svg>
                                </div>
                            </div>
                        </div>

                        <!-- ROI Card -->
                        <div
                            class="fade-in bg-white rounded-xl border border-gray-200 p-6 relative overflow-hidden shadow-sm hover:shadow-md transition-shadow">
                            <div
                                class="absolute top-0 right-0 w-24 h-24 transform translate-x-6 -translate-y-6 bg-orange-500 rounded-full opacity-10">
                            </div>
                            <div class="flex justify-between items-start">
                                <div class="flex-1">
                                    <p class="text-sm font-medium text-gray-500 uppercase tracking-wide">Average ROI</p>
                                    <h3 class="text-2xl font-bold mt-2 text-gray-900">24.5%</h3>
                                    <div class="flex items-center mt-3">
                                        <span class="text-sm font-semibold text-red-600">↓ 3.1%</span>
                                        <span class="text-xs text-gray-500 ml-2">vs last month</span>
                                    </div>
                                </div>
                                <div class="p-3 rounded-xl bg-orange-100">
                                    <svg class="w-6 h-6 text-orange-500" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" />
                                    </svg>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Charts Section -->
                    <div class="grid lg:grid-cols-2 gap-6">
                        <!-- Revenue Chart -->
                        <div class="bg-white rounded-xl border border-gray-200 shadow-sm">
                            <div class="p-6 border-b border-gray-200">
                                <h3 class="text-lg font-semibold text-gray-900">Revenue Trends</h3>
                            </div>
                            <div class="p-6">
                                <div class="h-64 flex items-center justify-center bg-gray-50 rounded-lg">
                                    <div class="text-center">
                                        <svg class="w-12 h-12 text-gray-400 mx-auto mb-3" fill="none"
                                            stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                                        </svg>
                                        <p class="text-gray-500 text-sm">Revenue chart visualization</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Pipeline Chart -->
                        <div class="bg-white rounded-xl border border-gray-200 shadow-sm">
                            <div class="p-6 border-b border-gray-200">
                                <h3 class="text-lg font-semibold text-gray-900">Sales Pipeline</h3>
                            </div>
                            <div class="p-6">
                                <div class="h-64 flex items-center justify-center bg-gray-50 rounded-lg">
                                    <div class="text-center">
                                        <svg class="w-12 h-12 text-gray-400 mx-auto mb-3" fill="none"
                                            stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                                        </svg>
                                        <p class="text-gray-500 text-sm">Pipeline chart visualization</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Recent Activity -->
                    <div class="bg-white rounded-xl border border-gray-200 shadow-sm">
                        <div class="p-6 border-b border-gray-200">
                            <h3 class="text-lg font-semibold text-gray-900 flex items-center gap-2">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                Recent Activity
                            </h3>
                        </div>
                        <div class="p-6">
                            <div class="space-y-4">
                                <!-- Activity Item -->
                                <div
                                    class="flex items-start gap-4 pb-4 border-b border-gray-100 last:border-0 last:pb-0">
                                    <div class="p-2 rounded-lg bg-blue-100">
                                        <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                    </div>
                                    <div class="flex-1">
                                        <p class="text-sm font-medium text-gray-900">Call back</p>
                                        <p class="text-xs text-gray-500 mt-1">Oct 17, 2025 • 11:49 AM</p>
                                    </div>
                                    <span
                                        class="text-xs font-semibold px-2 py-1 bg-gray-100 text-gray-700 rounded-full">Task</span>
                                </div>

                                <!-- Activity Item -->
                                <div
                                    class="flex items-start gap-4 pb-4 border-b border-gray-100 last:border-0 last:pb-0">
                                    <div class="p-2 rounded-lg bg-blue-100">
                                        <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                    </div>
                                    <div class="flex-1">
                                        <p class="text-sm font-medium text-gray-900">Quarterly review call with
                                            TechStartup</p>
                                        <p class="text-xs text-gray-500 mt-1">Oct 16, 2025 • 6:24 PM</p>
                                    </div>
                                    <span
                                        class="text-xs font-semibold px-2 py-1 bg-gray-100 text-gray-700 rounded-full">Task</span>
                                </div>

                                <!-- Activity Item -->
                                <div
                                    class="flex items-start gap-4 pb-4 border-b border-gray-100 last:border-0 last:pb-0">
                                    <div class="p-2 rounded-lg bg-purple-100">
                                        <svg class="w-4 h-4 text-purple-600" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z" />
                                        </svg>
                                    </div>
                                    <div class="flex-1">
                                        <p class="text-sm font-medium text-gray-900">Campaign: TechStartup LinkedIn Lead
                                            Gen</p>
                                        <p class="text-xs text-gray-500 mt-1">Oct 16, 2025 • 6:24 PM</p>
                                    </div>
                                    <span
                                        class="text-xs font-semibold px-2 py-1 bg-purple-100 text-purple-700 rounded-full">Campaign</span>
                                </div>

                                <!-- Activity Item -->
                                <div class="flex items-start gap-4">
                                    <div class="p-2 rounded-lg bg-green-100">
                                        <svg class="w-4 h-4 text-green-600" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                        </svg>
                                    </div>
                                    <div class="flex-1">
                                        <p class="text-sm font-medium text-gray-900">New lead: TechStartup Inc</p>
                                        <p class="text-xs text-gray-500 mt-1">Oct 16, 2025 • 6:24 PM</p>
                                    </div>
                                    <span
                                        class="text-xs font-semibold px-2 py-1 bg-green-100 text-green-700 rounded-full">Client</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
@endsection