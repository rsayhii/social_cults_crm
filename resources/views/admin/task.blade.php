@extends('components.layout')

@section('content')
        <!-- Content -->
            <div class="flex-1 overflow-auto p-4 lg:p-8 pb-24 lg:pb-8">
                <div class="max-w-5xl mx-auto space-y-4 lg:space-y-6">
                    <!-- Page Header -->
                    <div class="flex flex-col lg:flex-row justify-between items-start lg:items-center gap-4">
                        <div>
                            <h2 class="text-2xl lg:text-3xl font-bold text-slate-900">Tasks & Follow-ups</h2>
                            <p class="text-slate-500 mt-2">Stay on top of your client interactions</p>
                        </div>
                        <button class="inline-flex items-center gap-2 bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-md shadow-lg shadow-indigo-500/30 w-full lg:w-auto">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path d="M5 12h14"></path>
                                <path d="M12 5v14"></path>
                            </svg>
                            New Task
                        </button>
                    </div>
                    
                    <!-- Tabs -->
                    <div class="bg-white/80 backdrop-blur-sm rounded-md w-full lg:w-auto grid grid-cols-4 lg:flex">
                        <button class="px-3 py-1.5 text-sm font-medium rounded-sm bg-white shadow-sm">Pending</button>
                        <button class="px-3 py-1.5 text-sm font-medium rounded-sm">In Progress</button>
                        <button class="px-3 py-1.5 text-sm font-medium rounded-sm">Completed</button>
                        <button class="px-3 py-1.5 text-sm font-medium rounded-sm">All Tasks</button>
                    </div>
                    
                    <!-- Task List -->
                    <div class="space-y-3">
                        <!-- Task 1 -->
                        <div class="rounded-lg border border-slate-200/60 bg-white/80 backdrop-blur-sm hover:shadow-lg transition-all duration-300">
                            <div class="p-4">
                                <div class="flex items-start gap-4">
                                    <input type="checkbox" class="mt-1 h-4 w-4 rounded border border-primary">
                                    <div class="flex-1 min-w-0">
                                        <div class="flex items-start justify-between gap-3">
                                            <div class="flex-1">
                                                <h4 class="font-semibold text-slate-900">call back</h4>
                                            </div>
                                            <button class="h-10 w-10 flex-shrink-0">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path d="M12 3H5a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path>
                                                    <path d="M18.375 2.625a1 1 0 0 1 3 3l-9.013 9.014a2 2 0 0 1-.853.505l-2.873.84a.5.5 0 0 1-.62-.62l.84-2.873a2 2 0 0 1 .506-.852z"></path>
                                                </svg>
                                            </button>
                                        </div>
                                        <div class="flex flex-wrap items-center gap-2 mt-3">
                                            <span class="rounded-full border px-2.5 py-0.5 text-xs font-semibold flex items-center gap-1">
                                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"></path>
                                                </svg>
                                                follow up
                                            </span>
                                            <span class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-semibold bg-slate-100 text-slate-600">low</span>
                                            <span class="rounded-full border border-red-500 text-red-700 px-2.5 py-0.5 text-xs font-semibold flex items-center gap-1">
                                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path d="M8 2v4"></path>
                                                    <path d="M16 2v4"></path>
                                                    <rect width="18" height="18" x="3" y="4" rx="2"></rect>
                                                    <path d="M3 10h18"></path>
                                                </svg>
                                                Oct 16, 7:52 PM
                                            </span>
                                            <span class="rounded-full border px-2.5 py-0.5 text-xs font-semibold flex items-center gap-1">
                                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path d="M19 21v-2a4 4 0 0 0-4-4H9a4 4 0 0 0-4 4v2"></path>
                                                    <circle cx="12" cy="7" r="4"></circle>
                                                </svg>
                                                socialcults@gmail.com
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Task 2 -->
                        <div class="rounded-lg border border-slate-200/60 bg-white/80 backdrop-blur-sm hover:shadow-lg transition-all duration-300">
                            <div class="p-4">
                                <div class="flex items-start gap-4">
                                    <input type="checkbox" class="mt-1 h-4 w-4 rounded border border-primary">
                                    <div class="flex-1 min-w-0">
                                        <div class="flex items-start justify-between gap-3">
                                            <div class="flex-1">
                                                <h4 class="font-semibold text-slate-900">Send proposal to Urban Fitness</h4>
                                                <p class="text-sm text-slate-600 mt-1">Instagram & TikTok content package proposal</p>
                                            </div>
                                            <button class="h-10 w-10 flex-shrink-0">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path d="M12 3H5a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path>
                                                    <path d="M18.375 2.625a1 1 0 0 1 3 3l-9.013 9.014a2 2 0 0 1-.853.505l-2.873.84a.5.5 0 0 1-.62-.62l.84-2.873a2 2 0 0 1 .506-.852z"></path>
                                                </svg>
                                            </button>
                                        </div>
                                        <div class="flex flex-wrap items-center gap-2 mt-3">
                                            <span class="rounded-full border px-2.5 py-0.5 text-xs font-semibold flex items-center gap-1">
                                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path d="M15 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V7Z"></path>
                                                    <path d="M14 2v4a2 2 0 0 0 2 2h4"></path>
                                                    <path d="M10 9H8"></path>
                                                    <path d="M16 13H8"></path>
                                                    <path d="M16 17H8"></path>
                                                </svg>
                                                proposal
                                            </span>
                                            <span class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-semibold bg-red-100 text-red-700">urgent</span>
                                            <span class="rounded-full border border-red-500 text-red-700 px-2.5 py-0.5 text-xs font-semibold flex items-center gap-1">
                                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path d="M8 2v4"></path>
                                                    <path d="M16 2v4"></path>
                                                    <rect width="18" height="18" x="3" y="4" rx="2"></rect>
                                                    <path d="M3 10h18"></path>
                                                </svg>
                                                Jan 7, 5:00 PM
                                            </span>
                                            <span class="rounded-full border px-2.5 py-0.5 text-xs font-semibold flex items-center gap-1">
                                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path d="M19 21v-2a4 4 0 0 0-4-4H9a4 4 0 0 0-4 4v2"></path>
                                                    <circle cx="12" cy="7" r="4"></circle>
                                                </svg>
                                                sales@marketpro.com
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
@endsection