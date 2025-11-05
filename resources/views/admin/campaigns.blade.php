@extends('components.layout')

@section('content')
      <!-- Page Content -->
                <div class="flex-1 overflow-auto">
                    <div class="p-4 lg:p-8 pb-24 lg:pb-8">
                        <div class="max-w-7xl mx-auto space-y-4 lg:space-y-6">
                            <!-- Page Header -->
                            <div class="flex flex-col lg:flex-row justify-between items-start lg:items-center gap-4">
                                <div>
                                    <h2 class="text-2xl lg:text-3xl font-bold text-slate-900">Marketing Campaigns</h2>
                                    <p class="text-slate-500 mt-2">Track performance and ROI across all campaigns</p>
                                </div>
                                <button class="inline-flex items-center justify-center gap-2 whitespace-nowrap rounded-md text-sm font-medium h-10 px-4 py-2 bg-indigo-600 hover:bg-indigo-700 shadow-lg shadow-indigo-500/30 text-white w-full lg:w-auto">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-4 h-4 mr-2">
                                        <path d="M5 12h14"></path>
                                        <path d="M12 5v14"></path>
                                    </svg>
                                    New Campaign
                                </button>
                            </div>
                            
                            <!-- Filters -->
                            <div class="flex flex-col lg:flex-row gap-3 lg:gap-4">
                                <div class="relative flex-1">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="absolute left-3 top-1/2 transform -translate-y-1/2 w-5 h-5 text-slate-400">
                                        <circle cx="11" cy="11" r="8"></circle>
                                        <path d="m21 21-4.3-4.3"></path>
                                    </svg>
                                    <input class="flex h-10 w-full rounded-md border border-slate-300 px-3 py-2 text-sm pl-10 bg-white/80 backdrop-blur-sm" placeholder="Search campaigns..." value="">
                                </div>
                                <div class="w-full lg:w-auto">
                                    <div class="h-10 items-center justify-center rounded-md p-1 bg-white/80 backdrop-blur-sm w-full lg:w-auto grid grid-cols-4 lg:flex">
                                        <button class="inline-flex items-center justify-center whitespace-nowrap rounded-sm px-3 py-1.5 text-sm font-medium bg-white shadow-sm">All</button>
                                        <button class="inline-flex items-center justify-center whitespace-nowrap rounded-sm px-3 py-1.5 text-sm font-medium">Planning</button>
                                        <button class="inline-flex items-center justify-center whitespace-nowrap rounded-sm px-3 py-1.5 text-sm font-medium">Active</button>
                                        <button class="inline-flex items-center justify-center whitespace-nowrap rounded-sm px-3 py-1.5 text-sm font-medium">Completed</button>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Campaign Cards -->
                            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 lg:gap-6">
                                <!-- Campaign Card 1 -->
                                <div class="rounded-lg border border-slate-200/60 bg-white/80 backdrop-blur-sm hover:shadow-xl transition-all duration-300 group">
                                    <div class="flex flex-col space-y-1.5 p-6 pb-3">
                                        <div class="flex items-start justify-between">
                                            <div class="flex items-start gap-3 flex-1">
                                                <div class="text-3xl">📱</div>
                                                <div class="flex-1 min-w-0">
                                                    <h3 class="font-semibold text-slate-900 text-lg truncate">TechStartup LinkedIn Lead Gen</h3>
                                                    <p class="text-sm text-slate-500 capitalize">social media</p>
                                                </div>
                                            </div>
                                            <button class="h-10 w-10 opacity-0 group-hover:opacity-100 transition-opacity">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-4 h-4">
                                                    <path d="M12 3H5a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path>
                                                    <path d="M18.375 2.625a1 1 0 0 1 3 3l-9.013 9.014a2 2 0 0 1-.853.505l-2.873.84a.5.5 0 0 1-.62-.62l.84-2.873a2 2 0 0 1 .506-.852z"></path>
                                                </svg>
                                            </button>
                                        </div>
                                    </div>
                                    <div class="p-6 pt-0 space-y-4">
                                        <div class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-semibold bg-green-100 text-green-700">active</div>
                                        <div class="space-y-3">
                                            <div>
                                                <div class="flex justify-between text-sm mb-2">
                                                    <span class="text-slate-600">Budget Used</span>
                                                    <span class="font-semibold">40%</span>
                                                </div>
                                                <div class="relative w-full overflow-hidden rounded-full bg-slate-200 h-2">
                                                    <div class="h-full w-full flex-1 bg-green-500 transition-all" style="width: 40%"></div>
                                                </div>
                                            </div>
                                            <div class="grid grid-cols-2 gap-3 pt-3 border-t border-slate-200">
                                                <div>
                                                    <p class="text-xs text-slate-500">Spent</p>
                                                    <p class="text-lg font-semibold text-slate-900">$3,200</p>
                                                </div>
                                                <div>
                                                    <p class="text-xs text-slate-500">Revenue</p>
                                                    <p class="text-lg font-semibold text-green-600">$12,000</p>
                                                </div>
                                            </div>
                                            <div class="flex items-center justify-between pt-3 border-t border-slate-200">
                                                <div class="flex items-center gap-2">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-4 h-4 text-green-600">
                                                        <polyline points="22 7 13.5 15.5 8.5 10.5 2 17"></polyline>
                                                        <polyline points="16 7 22 7 22 13"></polyline>
                                                    </svg>
                                                    <span class="text-sm font-semibold text-slate-900">ROI: 275.0%</span>
                                                </div>
                                                <div class="flex items-center gap-1 text-xs text-slate-500">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-3 h-3">
                                                        <path d="M8 2v4"></path>
                                                        <path d="M16 2v4"></path>
                                                        <rect width="18" height="18" x="3" y="4" rx="2"></rect>
                                                        <path d="M3 10h18"></path>
                                                    </svg>
                                                    Dec 1
                                                </div>
                                            </div>
                                            <div class="flex justify-around pt-3 border-t border-slate-200 text-center">
                                                <div>
                                                    <p class="text-xs text-slate-500">Clicks</p>
                                                    <p class="text-sm font-semibold">2,400</p>
                                                </div>
                                                <div>
                                                    <p class="text-xs text-slate-500">Conversions</p>
                                                    <p class="text-sm font-semibold">48</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                
                                <!-- Campaign Card 2 -->
                                <div class="rounded-lg border border-slate-200/60 bg-white/80 backdrop-blur-sm hover:shadow-xl transition-all duration-300 group">
                                    <div class="flex flex-col space-y-1.5 p-6 pb-3">
                                        <div class="flex items-start justify-between">
                                            <div class="flex items-start gap-3 flex-1">
                                                <div class="text-3xl">📱</div>
                                                <div class="flex-1 min-w-0">
                                                    <h3 class="font-semibold text-slate-900 text-lg truncate">Urban Fitness Instagram Reels</h3>
                                                    <p class="text-sm text-slate-500 capitalize">social media</p>
                                                </div>
                                            </div>
                                            <button class="h-10 w-10 opacity-0 group-hover:opacity-100 transition-opacity">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-4 h-4">
                                                    <path d="M12 3H5a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path>
                                                    <path d="M18.375 2.625a1 1 0 0 1 3 3l-9.013 9.014a2 2 0 0 1-.853.505l-2.873.84a.5.5 0 0 1-.62-.62l.84-2.873a2 2 0 0 1 .506-.852z"></path>
                                                </svg>
                                            </button>
                                        </div>
                                    </div>
                                    <div class="p-6 pt-0 space-y-4">
                                        <div class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-semibold bg-green-100 text-green-700">active</div>
                                        <div class="space-y-3">
                                            <div>
                                                <div class="flex justify-between text-sm mb-2">
                                                    <span class="text-slate-600">Budget Used</span>
                                                    <span class="font-semibold">20%</span>
                                                </div>
                                                <div class="relative w-full overflow-hidden rounded-full bg-slate-200 h-2">
                                                    <div class="h-full w-full flex-1 bg-green-500 transition-all" style="width: 20%"></div>
                                                </div>
                                            </div>
                                            <div class="grid grid-cols-2 gap-3 pt-3 border-t border-slate-200">
                                                <div>
                                                    <p class="text-xs text-slate-500">Spent</p>
                                                    <p class="text-lg font-semibold text-slate-900">$800</p>
                                                </div>
                                                <div>
                                                    <p class="text-xs text-slate-500">Revenue</p>
                                                    <p class="text-lg font-semibold text-green-600">$2,400</p>
                                                </div>
                                            </div>
                                            <div class="flex items-center justify-between pt-3 border-t border-slate-200">
                                                <div class="flex items-center gap-2">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-4 h-4 text-green-600">
                                                        <polyline points="22 7 13.5 15.5 8.5 10.5 2 17"></polyline>
                                                        <polyline points="16 7 22 7 22 13"></polyline>
                                                    </svg>
                                                    <span class="text-sm font-semibold text-slate-900">ROI: 200.0%</span>
                                                </div>
                                                <div class="flex items-center gap-1 text-xs text-slate-500">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-3 h-3">
                                                        <path d="M8 2v4"></path>
                                                        <path d="M16 2v4"></path>
                                                        <rect width="18" height="18" x="3" y="4" rx="2"></rect>
                                                        <path d="M3 10h18"></path>
                                                    </svg>
                                                    Jan 1
                                                </div>
                                            </div>
                                            <div class="flex justify-around pt-3 border-t border-slate-200 text-center">
                                                <div>
                                                    <p class="text-xs text-slate-500">Clicks</p>
                                                    <p class="text-sm font-semibold">3,200</p>
                                                </div>
                                                <div>
                                                    <p class="text-xs text-slate-500">Conversions</p>
                                                    <p class="text-sm font-semibold">24</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                
                                <!-- Campaign Card 3 -->
                                <div class="rounded-lg border border-slate-200/60 bg-white/80 backdrop-blur-sm hover:shadow-xl transition-all duration-300 group">
                                    <div class="flex flex-col space-y-1.5 p-6 pb-3">
                                        <div class="flex items-start justify-between">
                                            <div class="flex items-start gap-3 flex-1">
                                                <div class="text-3xl">💰</div>
                                                <div class="flex-1 min-w-0">
                                                    <h3 class="font-semibold text-slate-900 text-lg truncate">GreenLeaf Google Ads</h3>
                                                    <p class="text-sm text-slate-500 capitalize">ppc</p>
                                                </div>
                                            </div>
                                            <button class="h-10 w-10 opacity-0 group-hover:opacity-100 transition-opacity">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-4 h-4">
                                                    <path d="M12 3H5a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path>
                                                    <path d="M18.375 2.625a1 1 0 0 1 3 3l-9.013 9.014a2 2 0 0 1-.853.505l-2.873.84a.5.5 0 0 1-.62-.62l.84-2.873a2 2 0 0 1 .506-.852z"></path>
                                                </svg>
                                            </button>
                                        </div>
                                    </div>
                                    <div class="p-6 pt-0 space-y-4">
                                        <div class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-semibold bg-green-100 text-green-700">active</div>
                                        <div class="space-y-3">
                                            <div>
                                                <div class="flex justify-between text-sm mb-2">
                                                    <span class="text-slate-600">Budget Used</span>
                                                    <span class="font-semibold">65%</span>
                                                </div>
                                                <div class="relative w-full overflow-hidden rounded-full bg-slate-200 h-2">
                                                    <div class="h-full w-full flex-1 bg-green-500 transition-all" style="width: 65%"></div>
                                                </div>
                                            </div>
                                            <div class="grid grid-cols-2 gap-3 pt-3 border-t border-slate-200">
                                                <div>
                                                    <p class="text-xs text-slate-500">Spent</p>
                                                    <p class="text-lg font-semibold text-slate-900">$6,500</p>
                                                </div>
                                                <div>
                                                    <p class="text-xs text-slate-500">Revenue</p>
                                                    <p class="text-lg font-semibold text-green-600">$18,500</p>
                                                </div>
                                            </div>
                                            <div class="flex items-center justify-between pt-3 border-t border-slate-200">
                                                <div class="flex items-center gap-2">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-4 h-4 text-green-600">
                                                        <polyline points="22 7 13.5 15.5 8.5 10.5 2 17"></polyline>
                                                        <polyline points="16 7 22 7 22 13"></polyline>
                                                    </svg>
                                                    <span class="text-sm font-semibold text-slate-900">ROI: 184.6%</span>
                                                </div>
                                                <div class="flex items-center gap-1 text-xs text-slate-500">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-3 h-3">
                                                        <path d="M8 2v4"></path>
                                                        <path d="M16 2v4"></path>
                                                        <rect width="18" height="18" x="3" y="4" rx="2"></rect>
                                                        <path d="M3 10h18"></path>
                                                    </svg>
                                                    Nov 15
                                                </div>
                                            </div>
                                            <div class="flex justify-around pt-3 border-t border-slate-200 text-center">
                                                <div>
                                                    <p class="text-xs text-slate-500">Clicks</p>
                                                    <p class="text-sm font-semibold">8,400</p>
                                                </div>
                                                <div>
                                                    <p class="text-xs text-slate-500">Conversions</p>
                                                    <p class="text-sm font-semibold">168</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
@endsection