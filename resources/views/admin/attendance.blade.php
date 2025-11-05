@extends('components.layout')


@section('content')
         <!-- Content -->
            <div class="flex-1 overflow-auto p-4 lg:p-8 pb-24 lg:pb-8">
                <div class="max-w-6xl mx-auto space-y-6">
                    <!-- Page Header -->
                    <div>
                        <h2 class="text-2xl lg:text-3xl font-bold text-slate-900">Attendance Tracker</h2>
                        <p class="text-slate-500 mt-2">Track your daily work hours</p>
                    </div>
                    
                    <!-- Time Display -->
                    <div class="rounded-lg border border-slate-200/60 bg-gradient-to-br from-indigo-50 via-white to-purple-50 shadow-lg">
                        <div class="p-6 lg:p-8">
                            <div class="text-center space-y-4">
                                <div class="flex items-center justify-center gap-2">
                                    <svg class="w-8 h-8 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <circle cx="12" cy="12" r="10"></circle>
                                        <polyline points="12 6 12 12 16 14"></polyline>
                                    </svg>
                                    <div>
                                        <div class="text-4xl lg:text-5xl font-bold text-slate-900">12:03:03</div>
                                        <div class="text-sm text-slate-500 mt-1">Saturday, October 18, 2025</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Check In/Out Cards -->
                    <div class="grid lg:grid-cols-2 gap-6">
                        <!-- Check In Card -->
                        <div class="rounded-lg border border-slate-200/60 bg-white/80 backdrop-blur-sm shadow-lg">
                            <div class="p-6">
                                <h3 class="text-2xl font-semibold flex items-center gap-2">
                                    <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path d="M15 3h4a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2h-4"></path>
                                        <polyline points="10 17 15 12 10 7"></polyline>
                                        <line x1="15" x2="3" y1="12" y2="12"></line>
                                    </svg>
                                    Check In
                                </h3>
                            </div>
                            <div class="p-6 pt-0 space-y-4">
                                <div class="space-y-2">
                                    <label class="text-sm font-medium text-slate-700">Location</label>
                                    <input class="w-full h-10 rounded-md border border-slate-300 bg-white px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500" placeholder="e.g., Office, Home, Client Site" value="Office">
                                </div>
                                <div class="space-y-2">
                                    <label class="text-sm font-medium text-slate-700">Notes (Optional)</label>
                                    <textarea class="w-full min-h-[80px] rounded-md border border-slate-300 bg-white px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500" placeholder="Add any notes for today..." rows="3"></textarea>
                                </div>
                                <button class="w-full h-10 bg-green-600 hover:bg-green-700 text-white rounded-md font-medium">
                                    Check In Now
                                </button>
                            </div>
                        </div>
                        
                        <!-- Check Out Card -->
                        <div class="rounded-lg border border-slate-200/60 bg-white/80 backdrop-blur-sm shadow-lg">
                            <div class="p-6">
                                <h3 class="text-2xl font-semibold flex items-center gap-2">
                                    <svg class="w-5 h-5 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"></path>
                                        <polyline points="16 17 21 12 16 7"></polyline>
                                        <line x1="21" x2="9" y1="12" y2="12"></line>
                                    </svg>
                                    Check Out
                                </h3>
                            </div>
                            <div class="p-6 pt-0">
                                <div class="text-center py-8 text-slate-500">Please check in first</div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Stats Cards -->
                    <div class="grid md:grid-cols-3 gap-4">
                        <!-- Days Present -->
                        <div class="rounded-lg border border-slate-200/60 bg-white/80 backdrop-blur-sm">
                            <div class="p-6">
                                <div class="flex items-center gap-3">
                                    <div class="p-3 bg-blue-100 rounded-xl">
                                        <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path d="M8 2v4"></path>
                                            <path d="M16 2v4"></path>
                                            <rect width="18" height="18" x="3" y="4" rx="2"></rect>
                                            <path d="M3 10h18"></path>
                                        </svg>
                                    </div>
                                    <div>
                                        <p class="text-sm text-slate-500">Days Present</p>
                                        <p class="text-2xl font-bold text-slate-900">1</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Average Hours -->
                        <div class="rounded-lg border border-slate-200/60 bg-white/80 backdrop-blur-sm">
                            <div class="p-6">
                                <div class="flex items-center gap-3">
                                    <div class="p-3 bg-purple-100 rounded-xl">
                                        <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <circle cx="12" cy="12" r="10"></circle>
                                            <polyline points="12 6 12 12 16 14"></polyline>
                                        </svg>
                                    </div>
                                    <div>
                                        <p class="text-sm text-slate-500">Avg Hours/Day</p>
                                        <p class="text-2xl font-bold text-slate-900">1.0h</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Total Hours -->
                        <div class="rounded-lg border border-slate-200/60 bg-white/80 backdrop-blur-sm">
                            <div class="p-6">
                                <div class="flex items-center gap-3">
                                    <div class="p-3 bg-green-100 rounded-xl">
                                        <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <polyline points="22 7 13.5 15.5 8.5 10.5 2 17"></polyline>
                                            <polyline points="16 7 22 7 22 13"></polyline>
                                        </svg>
                                    </div>
                                    <div>
                                        <p class="text-sm text-slate-500">Total Hours</p>
                                        <p class="text-2xl font-bold text-slate-900">1h</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Recent Attendance -->
                    <div class="rounded-lg border border-slate-200/60 bg-white/80 backdrop-blur-sm shadow-lg">
                        <div class="p-6">
                            <h3 class="text-2xl font-semibold">Recent Attendance</h3>
                        </div>
                        <div class="p-6 pt-0">
                            <div class="space-y-3">
                                <div class="flex items-center justify-between p-4 bg-slate-50 rounded-lg">
                                    <div class="flex items-center gap-3">
                                        <svg class="w-5 h-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path d="M8 2v4"></path>
                                            <path d="M16 2v4"></path>
                                            <rect width="18" height="18" x="3" y="4" rx="2"></rect>
                                            <path d="M3 10h18"></path>
                                        </svg>
                                        <div>
                                            <p class="font-semibold text-slate-900">Friday, Oct 17</p>
                                            <div class="flex items-center gap-2 text-sm text-slate-500 mt-1">
                                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path d="M20 10c0 4.993-5.539 10.193-7.399 11.799a1 1 0 0 1-1.202 0C9.539 20.193 4 14.993 4 10a8 8 0 0 1 16 0"></path>
                                                    <circle cx="12" cy="10" r="3"></circle>
                                                </svg>
                                                Office
                                            </div>
                                        </div>
                                    </div>
                                    <div class="text-right">
                                        <span class="inline-block bg-green-100 text-green-700 text-xs px-2.5 py-0.5 rounded-full mb-1">1h</span>
                                        <div class="text-xs text-slate-500">12:11 AM - 01:17 AM</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
@endsection