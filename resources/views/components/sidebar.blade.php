@php
    $company = auth()->user()->company;
    $crmAccessible = $company && (
        $company->is_paid ||
        now()->lt($company->trial_ends_at)
    );
@endphp



<!-- Desktop Sidebar -->
<div class="hidden lg:flex fixed inset-y-0 z-10 h-svh w-64 transition-all duration-200 border-r border-slate-200/60 bg-white/80 backdrop-blur-xl">
    <div class="flex h-full w-full flex-col">
        <!-- Sidebar Header -->
    <div class="flex flex-col gap-2 border-b border-slate-200/60 p-6">
    <div class="items-center gap-3">
       <div class="text-3xl font-extrabold text-slate-800 tracking-tight">
            {{ auth()->user()->company->name ?? 'Company' }}
        </div>

        @php
            $company = auth()->user()->company;
        @endphp

        @if($company)
            @if(!$company->is_paid && now()->lt($company->trial_ends_at))
                <span class="px-2 py-0.5 text-xs font-medium rounded-full bg-yellow-100 text-yellow-700">
                    Trial
                </span>
            @elseif($company->is_paid)
                <span class="px-2 py-0.5 text-xs font-medium rounded-full bg-green-100 text-green-700">
                    Active
                </span>
            @else
                <span class="px-2 py-0.5 text-xs font-medium rounded-full bg-red-100 text-red-700">
                    Expired
                </span>
            @endif
        @endif
    </div>




  {{-- Days + Hours only --}}
@if($company && !$company->is_paid && now()->lt($company->trial_ends_at))
    @php
        $diff = now()->diff($company->trial_ends_at);
    @endphp
    <div class="flex flex-col gap-3 mt-2">
        <p class="text-[14px] text-slate-500 font-medium whitespace-nowrap text-center">
            Trial ends in {{ $diff->days }} days {{ $diff->h }} hours
        </p>
        <a href="{{ route('upgrade.index') }}"
            class="w-full justify-center group relative inline-flex items-center gap-x-1.5 overflow-hidden rounded-full bg-sky-500 px-3 py-2 text-xs font-bold text-white shadow-md transition-all hover:bg-sky-600 hover:scale-105 hover:shadow-lg">
            UPGRADE
        </a>
    </div>
@endif
</div>

 <!-- Sidebar Header -->


        <!-- Sidebar Content -->
        <div class="flex min-h-0 flex-1 flex-col gap-2 overflow-auto p-3">
            <!-- Navigation Section -->
            <div class="relative flex w-full min-w-0 flex-col p-2">
                <div class="text-xs font-semibold text-slate-500 uppercase tracking-wider px-3 py-2">Navigation</div>
                <div class="w-full text-[8px]">



                  

                    <ul class="flex w-full min-w-0 flex-col gap-1">
                        <!-- Dashboard -->
                        @can('dashboard')
                          @if($crmAccessible)
                        <li class="relative">
                            <a href="{{ route('dashboard') }}"
                               class="flex w-full items-center gap-2 overflow-hidden p-2 text-left h-8 text-sm rounded-xl mb-1 flex items-center gap-3 px-4 py-3 
                               {{ request()->routeIs('dashboard') 
                                    ? 'bg-gradient-to-r from-indigo-600 to-purple-600 text-white shadow-lg shadow-indigo-500/30' 
                                    : 'hover:bg-indigo-50 hover:text-indigo-700 transition-all duration-200 text-slate-700' }}">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                     fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                     stroke-linejoin="round" class="w-4 h-4">
                                    <rect width="7" height="9" x="3" y="3" rx="1"></rect>
                                    <rect width="7" height="5" x="14" y="3" rx="1"></rect>
                                    <rect width="7" height="9" x="14" y="12" rx="1"></rect>
                                    <rect width="7" height="5" x="3" y="16" rx="1"></rect>
                                </svg>
                                <span class="font-medium">Dashboard</span>
                            </a>
                        </li>
                        @endcan

                        <!-- Users -->
                        @can('users')
                        <li class="relative">
                            <a href="{{ route('users') }}"
                               class="flex w-full items-center gap-2 overflow-hidden p-2 text-left h-8 text-sm rounded-xl mb-1 flex items-center gap-3 px-4 py-3 
                               {{ request()->routeIs('users') 
                                    ? 'bg-gradient-to-r from-indigo-600 to-purple-600 text-white shadow-lg shadow-indigo-500/30' 
                                    : 'hover:bg-indigo-50 hover:text-indigo-700 transition-all duration-200 text-slate-700' }}">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                     viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                     stroke-linecap="round" stroke-linejoin="round" class="w-4 h-4">
                                    <rect width="8" height="4" x="8" y="2" rx="1" ry="1"></rect>
                                    <path d="M16 4h2a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2h2"></path>
                                    <path d="m9 14 2 2 4-4"></path>
                                </svg>
                                <span class="font-medium">Users</span>
                            </a>
                        </li>
                        @endcan

                        <!-- Roles -->
                        @can('roles')
                        <li class="relative">
                            <a href="{{ route('roles') }}"
                               class="flex w-full items-center gap-2 overflow-hidden p-2 text-left h-8 text-sm rounded-xl mb-1 flex items-center gap-3 px-4 py-3 
                               {{ request()->routeIs('roles') 
                                    ? 'bg-gradient-to-r from-indigo-600 to-purple-600 text-white shadow-lg shadow-indigo-500/30' 
                                    : 'hover:bg-indigo-50 hover:text-indigo-700 transition-all duration-200 text-slate-700' }}">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-4 h-4">
                                    <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                                    <circle cx="12" cy="7" r="4"></circle>
                                </svg>
                                <span class="font-medium">Roles</span>
                            </a>
                        </li>
                        @endcan

                        <!-- Besdex -->
                        @can('besdex')
                        <li class="relative">
                            <a href="{{ route('clients.index') }}"
                               class="flex w-full items-center gap-2 overflow-hidden p-2 text-left h-8 text-sm rounded-xl mb-1 flex items-center gap-3 px-4 py-3 
                               {{ request()->routeIs('clients.index') 
                                    ? 'bg-gradient-to-r from-indigo-600 to-purple-600 text-white shadow-lg shadow-indigo-500/30' 
                                    : 'hover:bg-indigo-50 hover:text-indigo-700 transition-all duration-200 text-slate-700' }}">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                     viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                     stroke-linecap="round" stroke-linejoin="round" class="w-4 h-4">
                                    <path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"></path>
                                    <circle cx="9" cy="7" r="4"></circle>
                                    <path d="M22 21v-2a4 4 0 0 0-3-3.87"></path>
                                    <path d="M16 3.13a4 4 0 0 1 0 7.75"></path>
                                </svg>
                                <span class="font-medium">Besdex</span>
                            </a>
                        </li>
                        @endcan

                        <!-- Attendance Records -->
                        @can('attendance records')
                        <li class="relative">
                            <a href="{{ route('attendance-record.index') }}"
                               class="flex w-full items-center gap-2 overflow-hidden p-2 text-left h-8 text-sm rounded-xl mb-1 flex items-center gap-3 px-4 py-3 
                               {{ request()->routeIs('attendance-record.index') 
                                    ? 'bg-gradient-to-r from-indigo-600 to-purple-600 text-white shadow-lg shadow-indigo-500/30' 
                                    : 'hover:bg-indigo-50 hover:text-indigo-700 transition-all duration-200 text-slate-700' }}">
                                <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24"
                     fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                     stroke-linejoin="round" class="w-4 h-4">
                    <rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect>
                    <line x1="16" y1="2" x2="16" y2="6"></line>
                    <line x1="8" y1="2" x2="8" y2="6"></line>
                </svg>
                                <span class="font-medium">Attendance Records</span>
                            </a>
                        </li>
                        @endcan

                        <!-- My Leads -->
                        @can('my leads')
                        <li class="relative">
                            <a href="{{ route('myleads') }}"
                               class="flex w-full items-center gap-2 overflow-hidden p-2 text-left h-8 text-sm rounded-xl mb-1 flex items-center gap-3 px-4 py-3 
                               {{ request()->routeIs('myleads') 
                                    ? 'bg-gradient-to-r from-indigo-600 to-purple-600 text-white shadow-lg shadow-indigo-500/30' 
                                    : 'hover:bg-indigo-50 hover:text-indigo-700 transition-all duration-200 text-slate-700' }}">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                     fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                     stroke-linejoin="round" class="w-4 h-4">
                    <path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"></path>
                </svg>
                                <span class="font-medium">My Leads</span>
                            </a>
                        </li>
                        @endcan

                       <!-- Proposal -->
                        {{-- @can('proposal')
                        <li class="relative">
                            <a href="{{ route('proposal') }}"
                               class="flex w-full items-center gap-2 overflow-hidden p-2 text-left h-8 text-sm rounded-xl mb-1 flex items-center gap-3 px-4 py-3 
                               {{ request()->routeIs('proposal') 
                                    ? 'bg-gradient-to-r from-indigo-600 to-purple-600 text-white shadow-lg shadow-indigo-500/30' 
                                    : 'hover:bg-indigo-50 hover:text-indigo-700 transition-all duration-200 text-slate-700' }}">
                               <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                     fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                     stroke-linejoin="round" class="w-4 h-4">
                    <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                    <polyline points="14 2 14 8 20 8"></polyline>
                </svg>
                                <span class="font-medium">Proposal</span>
                            </a>
                        </li>
                        @endcan --}}

                        <!-- My Attendance -->
                        @can('my attendance')
                        <li class="relative">
                            <a href="{{ route('my-attendance.index') }}"
                               class="flex w-full items-center gap-2 overflow-hidden p-2 text-left h-8 text-sm rounded-xl mb-1 flex items-center gap-3 px-4 py-3 
                               {{ request()->routeIs('my-attendance.index') 
                                    ? 'bg-gradient-to-r from-indigo-600 to-purple-600 text-white shadow-lg shadow-indigo-500/30' 
                                    : 'hover:bg-indigo-50 hover:text-indigo-700 transition-all duration-200 text-slate-700' }}">
                                 <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                     fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                     stroke-linejoin="round" class="w-4 h-4">
                    <rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect>
                    <line x1="16" y1="2" x2="16" y2="6"></line>
                    <line x1="8" y1="2" x2="8" y2="6"></line>
                    <circle cx="12" cy="12" r="3"></circle>
                </svg>
                                <span class="font-medium">My Attendance</span>
                            </a>
                        </li>
                        @endcan





                          <!-- salary -->
                        @can('salary')
                        <li class="relative">
                            <a href="{{ route('salary.index') }}"
                               class="flex w-full items-center gap-2 overflow-hidden p-2 text-left h-8 text-sm rounded-xl mb-1 flex items-center gap-3 px-4 py-3 
                               {{ request()->routeIs('salary.index') 
                                    ? 'bg-gradient-to-r from-indigo-600 to-purple-600 text-white shadow-lg shadow-indigo-500/30' 
                                    : 'hover:bg-indigo-50 hover:text-indigo-700 transition-all duration-200 text-slate-700' }}">
                                 <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                     fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                     stroke-linejoin="round" class="w-4 h-4">
                    <rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect>
                    <line x1="16" y1="2" x2="16" y2="6"></line>
                    <line x1="8" y1="2" x2="8" y2="6"></line>
                    <circle cx="12" cy="12" r="3"></circle>
                </svg>
                                <span class="font-medium">Salary</span>
                            </a>
                        </li>
                        @endcan







                        <!-- Todo -->
                        @can('todo')
                        <li class="relative">
                            <a href="{{ route('todo.index') }}"
                               class="flex w-full items-center gap-2 overflow-hidden p-2 text-left h-8 text-sm rounded-xl mb-1 flex items-center gap-3 px-4 py-3 
                               {{ request()->routeIs('todo.index') 
                                    ? 'bg-gradient-to-r from-indigo-600 to-purple-600 text-white shadow-lg shadow-indigo-500/30' 
                                    : 'hover:bg-indigo-50 hover:text-indigo-700 transition-all duration-200 text-slate-700' }}">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                     fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                     stroke-linejoin="round" class="w-4 h-4">
                    <rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect>
                    <path d="m9 12 2 2 4-4"></path>
                </svg>
                                <span class="font-medium">Todo</span>
                            </a>
                        </li>
                        @endcan

                        <!-- Task -->
                        @can('task')
                        <li class="relative">
                            <a href="{{ route('tasks.index') }}"
                               class="flex w-full items-center gap-2 overflow-hidden p-2 text-left h-8 text-sm rounded-xl mb-1 flex items-center gap-3 px-4 py-3 
                               {{ request()->routeIs('tasks.index') 
                                    ? 'bg-gradient-to-r from-indigo-600 to-purple-600 text-white shadow-lg shadow-indigo-500/30' 
                                    : 'hover:bg-indigo-50 hover:text-indigo-700 transition-all duration-200 text-slate-700' }}">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                     fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                     stroke-linejoin="round" class="w-4 h-4">
                    <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                    <path d="M14 2v6h6"></path>
                    <path d="M16 13H8"></path>
                    <path d="M16 17H8"></path>
                </svg>
                                <span class="font-medium">Task</span>
                            </a>
                        </li>
                        @endcan

                        <!-- Calendar -->
                        @can('calender')
                        <li class="relative">
                            <a href="{{ route('calender.index') }}"
                               class="flex w-full items-center gap-2 overflow-hidden p-2 text-left h-8 text-sm rounded-xl mb-1 flex items-center gap-3 px-4 py-3 
                               {{ request()->routeIs('calender.index') 
                                    ? 'bg-gradient-to-r from-indigo-600 to-purple-600 text-white shadow-lg shadow-indigo-500/30' 
                                    : 'hover:bg-indigo-50 hover:text-indigo-700 transition-all duration-200 text-slate-700' }}">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                     fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                     stroke-linejoin="round" class="w-4 h-4">
                    <rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect>
                    <line x1="16" y1="2" x2="16" y2="6"></line>
                    <line x1="8" y1="2" x2="8" y2="6"></line>
                    <line x1="3" y1="10" x2="21" y2="10"></line>
                </svg>
                                <span class="font-medium">Calendar</span>
                            </a>
                        </li>
                        @endcan

                        <!-- Links and Remark -->
                        @can('links and remark')
                        <li class="relative">
                            <a href="{{ route('linksandremark.index') }}"
                               class="flex w-full items-center gap-2 overflow-hidden p-2 text-left h-8 text-sm rounded-xl mb-1 flex items-center gap-3 px-4 py-3 
                               {{ request()->routeIs('linksandremark.index') 
                                    ? 'bg-gradient-to-r from-indigo-600 to-purple-600 text-white shadow-lg shadow-indigo-500/30' 
                                    : 'hover:bg-indigo-50 hover:text-indigo-700 transition-all duration-200 text-slate-700' }}">
                                 <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                     fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                     stroke-linejoin="round" class="w-4 h-4">
                    <path d="M10 13a5 5 0 0 0 7.54.54l3-3a5 5 0 0 0-7.07-7.07l-1.72 1.71"></path>
                    <path d="M14 11a5 5 0 0 0-7.54-.54l-3 3a5 5 0 0 0 7.07 7.07l1.71-1.71"></path>
                </svg>
                                <span class="font-medium">Links and Remark</span>
                            </a>
                        </li>
                        @endcan

                        <!-- Interaction -->
                        @can('client serive interation')
                        <li class="relative">
                            <a href="{{ route('chat.index') }}"
                               class="flex w-full items-center gap-2 overflow-hidden p-2 text-left h-8 text-sm rounded-xl mb-1 flex items-center gap-3 px-4 py-3 
                               {{ request()->routeIs('chat.index') 
                                    ? 'bg-gradient-to-r from-indigo-600 to-purple-600 text-white shadow-lg shadow-indigo-500/30' 
                                    : 'hover:bg-indigo-50 hover:text-indigo-700 transition-all duration-200 text-slate-700' }}">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                     fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                     stroke-linejoin="round" class="w-4 h-4">
                    <path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"></path>
                </svg>
                                <span class="font-medium">Interaction</span>
                            </a>
                        </li>
                        @endcan

                        <!-- Invoice -->
                        @can('invoice')
                        <li class="relative">
                            <a href="{{ route('invoices.index') }}"
                               class="flex w-full items-center gap-2 overflow-hidden p-2 text-left h-8 text-sm rounded-xl mb-1 flex items-center gap-3 px-4 py-3 
                               {{ request()->routeIs('invoices.index') 
                                    ? 'bg-gradient-to-r from-indigo-600 to-purple-600 text-white shadow-lg shadow-indigo-500/30' 
                                    : 'hover:bg-indigo-50 hover:text-indigo-700 transition-all duration-200 text-slate-700' }}">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                     fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                     stroke-linejoin="round" class="w-4 h-4">
                    <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                    <polyline points="14 2 14 8 20 8"></polyline>
                    <line x1="16" y1="13" x2="8" y2="13"></line>
                    <line x1="16" y1="17" x2="8" y2="17"></line>
                </svg>
                                <span class="font-medium">Invoice</span>
                            </a>
                        </li>
                        @endcan

                        

                    <!--help and support-->
                         {{-- @can('help and support')
                        <li class="relative">
                            <a href="{{ route('helpandsupportuser.index') }}"
                               class="flex w-full items-center gap-2 overflow-hidden p-2 text-left h-8 text-sm rounded-xl mb-1 flex items-center gap-3 px-4 py-3 
                               {{ request()->routeIs('helpandsupportuser.index') 
                                    ? 'bg-gradient-to-r from-indigo-600 to-purple-600 text-white shadow-lg shadow-indigo-500/30' 
                                    : 'hover:bg-indigo-50 hover:text-indigo-700 transition-all duration-200 text-slate-700' }}">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                     fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                     stroke-linejoin="round" class="w-4 h-4">
                    <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                    <polyline points="14 2 14 8 20 8"></polyline>
                    <line x1="16" y1="13" x2="8" y2="13"></line>
                    <line x1="16" y1="17" x2="8" y2="17"></line>
                </svg>
                                <span class="font-medium">Help and Support</span>
                            </a>
                        </li>
                        @endcan --}}



                        <!--help and support admin-->
                           {{-- @can('helpandsupportadmin')
                        <li class="relative">
                            <a href="{{ route('helpandsupportadmin.index') }}"
                               class="flex w-full items-center gap-2 overflow-hidden p-2 text-left h-8 text-sm rounded-xl mb-1 flex items-center gap-3 px-4 py-3 
                               {{ request()->routeIs('helpandsupportadmin.index') 
                                    ? 'bg-gradient-to-r from-indigo-600 to-purple-600 text-white shadow-lg shadow-indigo-500/30' 
                                    : 'hover:bg-indigo-50 hover:text-indigo-700 transition-all duration-200 text-slate-700' }}">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                     fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                     stroke-linejoin="round" class="w-4 h-4">
                    <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                    <polyline points="14 2 14 8 20 8"></polyline>
                    <line x1="16" y1="13" x2="8" y2="13"></line>
                    <line x1="16" y1="17" x2="8" y2="17"></line>
                </svg>
                                <span class="font-medium">Help and Support Admin</span>
                            </a>
                        </li>
                        @endcan --}}


                          <!-- admin leave protal -->
                        @can('admin portal')
                        <li class="relative">
                            <a href="{{ route('adminportal.index') }}"
                               class="flex w-full items-center gap-2 overflow-hidden p-2 text-left h-8 text-sm rounded-xl mb-1 flex items-center gap-3 px-4 py-3 
                               {{ request()->routeIs('adminportal.index') 
                                    ? 'bg-gradient-to-r from-indigo-600 to-purple-600 text-white shadow-lg shadow-indigo-500/30' 
                                    : 'hover:bg-indigo-50 hover:text-indigo-700 transition-all duration-200 text-slate-700' }}">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                     fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                     stroke-linejoin="round" class="w-4 h-4">
                    <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                    <polyline points="14 2 14 8 20 8"></polyline>
                    <line x1="16" y1="13" x2="8" y2="13"></line>
                    <line x1="16" y1="17" x2="8" y2="17"></line>
                </svg>
                                <span class="font-medium">Leave Record</span>
                            </a>
                        </li>
                        @endcan




                         <!-- employee leave protal -->
                        @can('employeeportal')
                        <li class="relative">
                            <a href="{{ route('employeeportal.index') }}"
                               class="flex w-full items-center gap-2 overflow-hidden p-2 text-left h-8 text-sm rounded-xl mb-1 flex items-center gap-3 px-4 py-3 
                               {{ request()->routeIs('employeeportal.index') 
                                    ? 'bg-gradient-to-r from-indigo-600 to-purple-600 text-white shadow-lg shadow-indigo-500/30' 
                                    : 'hover:bg-indigo-50 hover:text-indigo-700 transition-all duration-200 text-slate-700' }}">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                     fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                     stroke-linejoin="round" class="w-4 h-4">
                    <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                    <polyline points="14 2 14 8 20 8"></polyline>
                    <line x1="16" y1="13" x2="8" y2="13"></line>
                    <line x1="16" y1="17" x2="8" y2="17"></line>
                </svg>
                                <span class="font-medium">Leave Apply</span>
                            </a>
                        </li>
                        @endcan































                        <!-- Contact -->
                        @can('contact')
                        <li class="relative">
                            <a href="{{ route('contacts.index') }}"
                               class="flex w-full items-center gap-2 overflow-hidden p-2 text-left h-8 text-sm rounded-xl mb-1 flex items-center gap-3 px-4 py-3 
                               {{ request()->routeIs('contacts.index') 
                                    ? 'bg-gradient-to-r from-indigo-600 to-purple-600 text-white shadow-lg shadow-indigo-500/30' 
                                    : 'hover:bg-indigo-50 hover:text-indigo-700 transition-all duration-200 text-slate-700' }}">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                     fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                     stroke-linejoin="round" class="w-4 h-4">
                    <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
                    <circle cx="9" cy="7" r="4"></circle>
                    <path d="M23 21v-2a4 4 0 0 0-3-3.87"></path>
                    <path d="M16 3.13a4 4 0 0 1 0 7.75"></path>
                </svg>
                                <span class="font-medium">Contact</span>
                            </a>
                        </li>
                        @endcan



                          <!-- project management -->
                        @can('project management')
                        <li class="relative">
                            <a href="{{ route('projectmanagement.index') }}"
                               class="flex w-full items-center gap-2 overflow-hidden p-2 text-left h-8 text-sm rounded-xl mb-1 flex items-center gap-3 px-4 py-3 
                               {{ request()->routeIs('projectmanagement.index') 
                                    ? 'bg-gradient-to-r from-indigo-600 to-purple-600 text-white shadow-lg shadow-indigo-500/30' 
                                    : 'hover:bg-indigo-50 hover:text-indigo-700 transition-all duration-200 text-slate-700' }}">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                     fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                     stroke-linejoin="round" class="w-4 h-4">
                    <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
                    <circle cx="9" cy="7" r="4"></circle>
                    <path d="M23 21v-2a4 4 0 0 0-3-3.87"></path>
                    <path d="M16 3.13a4 4 0 0 1 0 7.75"></path>
                </svg>
                                <span class="font-medium">project management</span>
                            </a>
                        </li>
                        @endcan

                        <!-- Report -->
                        @can('report')
                        <li class="relative">
                            <a href="{{ route('rr.index') }}"
                               class="flex w-full items-center gap-2 overflow-hidden p-2 text-left h-8 text-sm rounded-xl mb-1 flex items-center gap-3 px-4 py-3 
                               {{ request()->routeIs('rr.index') 
                                    ? 'bg-gradient-to-r from-indigo-600 to-purple-600 text-white shadow-lg shadow-indigo-500/30' 
                                    : 'hover:bg-indigo-50 hover:text-indigo-700 transition-all duration-200 text-slate-700' }}">
                               <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                    fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                    stroke-linejoin="round" class="w-4 h-4">
                                    <path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"></path>
                                    <line x1="9" y1="10" x2="15" y2="10"></line>
                                    <line x1="12" y1="7" x2="12" y2="13"></line>
                                </svg>
                                <span class="font-medium">Report</span>
                            </a>
                        </li>
                        @endcan



                        
                        <!-- Project -->
                        {{-- <li class="relative">
                            <a href="{{ route('project.index') }}"
                               class="flex w-full items-center gap-2 overflow-hidden p-2 text-left h-8 text-sm rounded-xl mb-1 flex items-center gap-3 px-4 py-3 
                               {{ request()->routeIs('project.index') 
                                    ? 'bg-gradient-to-r from-indigo-600 to-purple-600 text-white shadow-lg shadow-indigo-500/30' 
                                    : 'hover:bg-indigo-50 hover:text-indigo-700 transition-all duration-200 text-slate-700' }}">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-4 h-4">
  <rect x="2" y="7" width="20" height="14" rx="2" ry="2"/>
  <path d="M16 21V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v16"/>
</svg>
                                <span class="font-medium">Project</span>
                            </a>
                        </li> --}}


                        <!-- Notepad -->
                        @can('notepad')
                        <li class="relative">
                            <a href="{{ route('notepad.index') }}"
                               class="flex w-full items-center gap-2 overflow-hidden p-2 text-left h-8 text-sm rounded-xl mb-1 flex items-center gap-3 px-4 py-3 
                               {{ request()->routeIs('notepad.index') 
                                    ? 'bg-gradient-to-r from-indigo-600 to-purple-600 text-white shadow-lg shadow-indigo-500/30' 
                                    : 'hover:bg-indigo-50 hover:text-indigo-700 transition-all duration-200 text-slate-700' }}">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-4 h-4">
                                    <path d="M8 3h8a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2z"/>
                                    <path d="M10 7h4"/>
                                    <path d="M10 11h4"/>
                                    <path d="M10 15h2"/>
                                    <path d="M16 3v4"/>
                                    <path d="M8 3v2"/>
                                </svg>
                                <span class="font-medium">Notepad</span>
                            </a>
                        </li>
                        @endcan

                        {{-- yahan --}}



                           @can('ticket records')
                             <li class="relative">
                            <a href="{{ route('ticket.record.index') }}" class="flex w-full items-center gap-2 overflow-hidden p-2 text-left h-8 text-sm rounded-xl mb-1 flex items-center gap-3 px-4 py-3 
                                                                        {{ request()->routeIs('ticket.record.*')
    ? 'bg-gradient-to-r from-indigo-600 to-purple-600 text-white shadow-lg shadow-indigo-500/30'
    : 'hover:bg-indigo-50 hover:text-indigo-700 transition-all duration-200 text-slate-700' }}">
                                <i class="fas fa-headset w-4 h-4"></i>
                                <span class="font-medium">Support Desk</span>
                            </a>
                        </li>
                         @endcan


                             @can('ticket raise')
                          <li class="relative">
                            <a href="{{ route('user.support.ticket.index') }}" class="flex w-full items-center gap-2 overflow-hidden p-2 text-left h-8 text-sm rounded-xl mb-1 flex items-center gap-3 px-4 py-3 
                                                                        {{ request()->routeIs('user.support.ticket.*')
    ? 'bg-gradient-to-r from-indigo-600 to-purple-600 text-white shadow-lg shadow-indigo-500/30'
    : 'hover:bg-indigo-50 hover:text-indigo-700 transition-all duration-200 text-slate-700' }}">
                                <i class="fas fa-ticket-alt w-4 h-4"></i>
                                <span class="font-medium">My Tickets</span>
                            </a>
                        </li>
                          @endcan


                   


                        <!-- Help and Support -->
                        {{-- <li class="relative">
                            <a href="{{ route('helpandsupport.index') }}"
                               class="flex w-full items-center gap-2 overflow-hidden p-2 text-left h-8 text-sm rounded-xl mb-1 flex items-center gap-3 px-4 py-3 
                               {{ request()->routeIs('helpandsupport.index') 
                                    ? 'bg-gradient-to-r from-indigo-600 to-purple-600 text-white shadow-lg shadow-indigo-500/30' 
                                    : 'hover:bg-indigo-50 hover:text-indigo-700 transition-all duration-200 text-slate-700' }}">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                    fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                    stroke-linejoin="round" class="w-4 h-4">
                                     <circle cx="12" cy="12" r="10"></circle>
                                    <path d="M9.09 9a3 3 0 0 1 5.83 1c0 2-3 3-3 3"></path>
                                    <line x1="12" y1="17" x2="12.01" y2="17"></line>
                                </svg>
                                <span class="font-medium">Help and Support</span>
                            </a>
                        </li> --}}

          @endif
                        <!-- Upgrade Plan -->
<li class="relative">
    <a href="{{ route('upgrade.index') }}" class="flex w-full items-center gap-2 overflow-hidden p-2 text-left h-8 text-sm rounded-xl mb-1 flex items-center gap-3 px-4 py-3 
       {{ request()->routeIs('upgrade.index')
        ? 'bg-gradient-to-r from-indigo-600 to-purple-600 text-white shadow-lg shadow-indigo-500/30'
        : 'hover:bg-indigo-50 hover:text-indigo-700 transition-all duration-200 text-slate-700' }}">
        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
            fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
            stroke-linejoin="round" class="w-4 h-4">
            <polygon
                points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2">
            </polygon>
        </svg>
        <span class="font-medium">Upgrade Plan</span>
    </a>
</li>



                    </ul>

            


                  
                </div>
            </div>
        </div>

        <!-- Sidebar Footer -->
        <div class="flex flex-col gap-2 border-t border-slate-200/60 p-4">
            <div class="group relative overflow-hidden rounded-xl">
                <div class="flex items-center gap-3 p-3 bg-slate-50 hover:bg-slate-100 transition-colors cursor-pointer">
                    <div class="w-10 h-10 bg-gradient-to-br from-indigo-400 to-purple-400 rounded-full flex items-center justify-center">
                        <span class="text-white font-semibold text-sm">
                           {{ strtoupper(substr(Auth::user()->username ?? Auth::user()->name, 0, 2)) }}
                        </span>
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="font-semibold text-slate-900 text-sm truncate">
                            {{ Auth::user()->name }}
                        </p>
                        <p class="text-xs text-slate-500 truncate">
                            {{ Auth::user()->email }}
                        </p>
                    </div>
                </div>

                <!-- Slide-up hover layer -->
                <div class="absolute bottom-0 left-0 w-full bg-white/95 flex justify-center gap-3 py-3 translate-y-full group-hover:translate-y-0 transition-all duration-300">
                    <a href="{{ route('profile.view') }}" class="bg-indigo-600 text-white px-4 py-2 rounded-md text-sm hover:bg-indigo-700 transition">
                        Visit Profile
                    </a>
                    <form method="GET" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="bg-red-600 text-white px-4 py-2 rounded-md text-sm hover:bg-red-700 transition">
                            Logout
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Mobile Bottom Navigation -->
<!-- Mobile Bottom Navigation -->

@php
$navItems = [];
$index = 0;

if ($crmAccessible) {

    if(auth()->user()->can('dashboard')) {
        $navItems[] = [
            'name' => 'Dashboard',
            'route' => route('dashboard'),
            'icon' => '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                     fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                     stroke-linejoin="round" class="w-4 h-4">
                                    <rect width="7" height="9" x="3" y="3" rx="1"></rect>
                                    <rect width="7" height="5" x="14" y="3" rx="1"></rect>
                                    <rect width="7" height="9" x="14" y="12" rx="1"></rect>
                                    <rect width="7" height="5" x="3" y="16" rx="1"></rect>
                                </svg>',
            'active' => request()->routeIs('dashboard'),
            'index' => $index++
        ];
    }

    if(auth()->user()->can('users')) {
        $navItems[] = [
            'name' => 'Users',
            'route' => route('users'),
            'icon' => '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                     viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                     stroke-linecap="round" stroke-linejoin="round" class="w-4 h-4">
                                    <rect width="8" height="4" x="8" y="2" rx="1" ry="1"></rect>
                                    <path d="M16 4h2a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2h2"></path>
                                    <path d="m9 14 2 2 4-4"></path>
                                </svg>',
            'active' => request()->routeIs('users'),
            'index' => $index++
        ];
    }

    if(auth()->user()->can('roles')) {
        $navItems[] = [
            'name' => 'Roles',
            'route' => route('roles'),
            'icon' => ' <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-4 h-4">
                                    <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                                    <circle cx="12" cy="7" r="4"></circle>
                                </svg>',
            'active' => request()->routeIs('roles'),
            'index' => $index++
        ];
    }

    if(auth()->user()->can('besdex')) {
        $navItems[] = [
            'name' => 'Besdex',
            'route' => route('clients.index'),
            'icon' => ' <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                     viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                     stroke-linecap="round" stroke-linejoin="round" class="w-4 h-4">
                                    <path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"></path>
                                    <circle cx="9" cy="7" r="4"></circle>
                                    <path d="M22 21v-2a4 4 0 0 0-3-3.87"></path>
                                    <path d="M16 3.13a4 4 0 0 1 0 7.75"></path>
                                </svg>',
            'active' => request()->routeIs('clients.index'),
            'index' => $index++
        ];
    }

     if(auth()->user()->can('employeeportal')) {
        $navItems[] = [
            'name' => 'Leave Apply',
            'route' => route('employeeportal.index'),
            'icon' => ' <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                     fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                     stroke-linejoin="round" class="w-4 h-4">
                    <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                    <polyline points="14 2 14 8 20 8"></polyline>
                    <line x1="16" y1="13" x2="8" y2="13"></line>
                    <line x1="16" y1="17" x2="8" y2="17"></line>
                </svg>',
            'active' => request()->routeIs('employeeportal.index'),
            'index' => $index++
        ];
    }

    
     if(auth()->user()->can('admin portal')) {
        $navItems[] = [
            'name' => 'Leave Record',
            'route' => route('adminportal.index'),
            'icon' => ' <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                     fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                     stroke-linejoin="round" class="w-4 h-4">
                    <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                    <polyline points="14 2 14 8 20 8"></polyline>
                    <line x1="16" y1="13" x2="8" y2="13"></line>
                    <line x1="16" y1="17" x2="8" y2="17"></line>
                </svg>',
            'active' => request()->routeIs('adminportal.index'),
            'index' => $index++
        ];
    }

    
    if(auth()->user()->can('attendance records')) {
        $navItems[] = [
            'name' => 'Attendance',
            'route' => route('attendance-record.index'),
            'icon' => ' <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24"
                     fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                     stroke-linejoin="round" class="w-4 h-4">
                    <rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect>
                    <line x1="16" y1="2" x2="16" y2="6"></line>
                    <line x1="8" y1="2" x2="8" y2="6"></line>
                </svg>',
            'active' => request()->routeIs('attendance-record.index'),
            'index' => $index++
        ];
    }

    if(auth()->user()->can('my leads')) {
        $navItems[] = [
            'name' => 'Leads',
            'route' => route('myleads'),
            'icon' => ' <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                     fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                     stroke-linejoin="round" class="w-4 h-4">
                    <path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"></path>
                </svg>',
            'active' => request()->routeIs('myleads'),
            'index' => $index++
        ];
    }
   
     if(auth()->user()->can('salary')) {
        $navItems[] = [
            'name' => 'Salary',
            'route' => route('salary.index'),
            'icon' => ' <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                     fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                     stroke-linejoin="round" class="w-4 h-4">
                    <rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect>
                    <line x1="16" y1="2" x2="16" y2="6"></line>
                    <line x1="8" y1="2" x2="8" y2="6"></line>
                    <circle cx="12" cy="12" r="3"></circle>
                </svg>',
            'active' => request()->routeIs('salary.index'),
            'index' => $index++
        ];
    }
   
    //   if(auth()->user()->can('proposal')) {
    //     $navItems[] = [
    //         'name' => 'Proposal',
    //         'route' => route('proposal'),
    //         'icon' => ' <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
    //                  fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
    //                  stroke-linejoin="round" class="w-4 h-4">
    //                 <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
    //                 <polyline points="14 2 14 8 20 8"></polyline>
    //             </svg>',
    //         'active' => request()->routeIs('proposal'),
    //         'index' => $index++
    //     ];
    // }
   
      if(auth()->user()->can('my attendance')) {
        $navItems[] = [
            'name' => 'My Attendence',
            'route' => route('my-attendance.index'),
            'icon' => '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                     fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                     stroke-linejoin="round" class="w-4 h-4">
                    <rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect>
                    <line x1="16" y1="2" x2="16" y2="6"></line>
                    <line x1="8" y1="2" x2="8" y2="6"></line>
                    <circle cx="12" cy="12" r="3"></circle>
                </svg>',
            'active' => request()->routeIs('my-attendance.index'),
            'index' => $index++
        ];
    } 
    
      if(auth()->user()->can('todo')) {
        $navItems[] = [
            'name' => 'Todo',
            'route' => route('todo.index'),
            'icon' => '                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                     fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                     stroke-linejoin="round" class="w-4 h-4">
                    <rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect>
                    <path d="m9 12 2 2 4-4"></path>
                </svg>',
            'active' => request()->routeIs('todo.index'),
            'index' => $index++
        ];
    } 
    
     
      if(auth()->user()->can('task')) {
        $navItems[] = [
            'name' => 'Task',
            'route' => route('tasks.index'),
            'icon' => '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                     fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                     stroke-linejoin="round" class="w-4 h-4">
                    <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                    <path d="M14 2v6h6"></path>
                    <path d="M16 13H8"></path>
                    <path d="M16 17H8"></path>
                </svg>',
            'active' => request()->routeIs('tasks.index'),
            'index' => $index++
        ];
    } 
    

      if(auth()->user()->can('calender')) {
        $navItems[] = [
            'name' => 'Calendar',
            'route' => route('calender.index'),
            'icon' => ' <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                     fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                     stroke-linejoin="round" class="w-4 h-4">
                    <rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect>
                    <line x1="16" y1="2" x2="16" y2="6"></line>
                    <line x1="8" y1="2" x2="8" y2="6"></line>
                    <line x1="3" y1="10" x2="21" y2="10"></line>
                </svg>',
            'active' => request()->routeIs('calender.index'),
            'index' => $index++
        ];
    } 
      

    if(auth()->user()->can('links and remark')) {
        $navItems[] = [
            'name' => 'Links',
            'route' => route('linksandremark.index'),
            'icon' => '  <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                     fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                     stroke-linejoin="round" class="w-4 h-4">
                    <path d="M10 13a5 5 0 0 0 7.54.54l3-3a5 5 0 0 0-7.07-7.07l-1.72 1.71"></path>
                    <path d="M14 11a5 5 0 0 0-7.54-.54l-3 3a5 5 0 0 0 7.07 7.07l1.71-1.71"></path>
                </svg>',
            'active' => request()->routeIs('linksandremark.index'),
            'index' => $index++
        ];
    } 
 
     if(auth()->user()->can('client serive interation')) {
        $navItems[] = [
            'name' => 'Interaction',
            'route' => route('chat.index'),
            'icon' => '  <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                     fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                     stroke-linejoin="round" class="w-4 h-4">
                    <path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"></path>
                </svg>',
            'active' => request()->routeIs('chat.index'),
            'index' => $index++
        ];
    } 

     if(auth()->user()->can('invoice')) {
        $navItems[] = [
            'name' => 'Invoice',
            'route' => route('invoices.index'),
            'icon' => ' <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                     fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                     stroke-linejoin="round" class="w-4 h-4">
                    <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                    <polyline points="14 2 14 8 20 8"></polyline>
                    <line x1="16" y1="13" x2="8" y2="13"></line>
                    <line x1="16" y1="17" x2="8" y2="17"></line>
                </svg>',
            'active' => request()->routeIs('invoices.index'),
            'index' => $index++
        ];
    } 

    
      if(auth()->user()->can('contact')) {
        $navItems[] = [
            'name' => 'Contact',
            'route' => route('contacts.index'),
            'icon' => ' <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                     fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                     stroke-linejoin="round" class="w-4 h-4">
                    <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
                    <circle cx="9" cy="7" r="4"></circle>
                    <path d="M23 21v-2a4 4 0 0 0-3-3.87"></path>
                    <path d="M16 3.13a4 4 0 0 1 0 7.75"></path>
                </svg>',
            'active' => request()->routeIs('contacts.index'),
            'index' => $index++
        ];
    } 


     if(auth()->user()->can('report')) {
        $navItems[] = [
            'name' => 'Report',
            'route' => route('rr.index'),
            'icon' => ' <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                         fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                         stroke-linejoin="round" class="w-4 h-4">
                         <path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"></path>
                         <line x1="9" y1="10" x2="15" y2="10"></line>
                         <line x1="12" y1="7" x2="12" y2="13"></line>
                        </svg>',
            'active' => request()->routeIs('rr.index'),
            'index' => $index++
        ];
    } 
    

      if(auth()->user()->can('project management')) {
        $navItems[] = [
            'name' => 'Project Management',
            'route' => route('projectmanagement.index'),
            'icon' => '  <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                     fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                     stroke-linejoin="round" class="w-4 h-4">
                    <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
                    <circle cx="9" cy="7" r="4"></circle>
                    <path d="M23 21v-2a4 4 0 0 0-3-3.87"></path>
                    <path d="M16 3.13a4 4 0 0 1 0 7.75"></path>
                </svg>',
            'active' => request()->routeIs('projectmanagement.index'),
            'index' => $index++
        ];
    } 


    
      if(auth()->user()->can('notepad')) {
        $navItems[] = [
            'name' => 'Notepad',
            'route' => route('notepad.index'),
            'icon' => ' <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-4 h-4">
        <path d="M8 3h8a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2z"/>
                  <path d="M10 7h4"/>
                  <path d="M10 11h4"/>
                  <path d="M10 15h2"/>
                  <path d="M16 3v4"/>
                    <path d="M8 3v2"/>
                   </svg>',
            'active' => request()->routeIs('notepad.index'),
            'index' => $index++
        ];
    } 


     if(auth()->user()->can('ticket records')) {
        $navItems[] = [
            'name' => 'Support Desk',
            'route' => route('ticket.record.index'),
            'icon' => ' <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-4 h-4">
        <path d="M8 3h8a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2z"/>
                  <path d="M10 7h4"/>
                  <path d="M10 11h4"/>
                  <path d="M10 15h2"/>
                  <path d="M16 3v4"/>
                    <path d="M8 3v2"/>
                   </svg>',
            'active' => request()->routeIs('ticket.record.index'),
            'index' => $index++
        ];
    } 


       if(auth()->user()->can('ticket raise')) {
        $navItems[] = [
            'name' => 'My Tickets',
            'route' => route('user.support.ticket.index'),
            'icon' => ' <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-4 h-4">
        <path d="M8 3h8a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2z"/>
                  <path d="M10 7h4"/>
                  <path d="M10 11h4"/>
                  <path d="M10 15h2"/>
                  <path d="M16 3v4"/>
                    <path d="M8 3v2"/>
                   </svg>',
            'active' => request()->routeIs('user.support.ticket.index'),
            'index' => $index++
        ];
    } 
}


    $navItems[] = [
    'name' => 'Upgrade',
    'route' => route('upgrade.index'),
    'icon' => '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-4 h-4">
                                    <polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"></polygon>
                                </svg>',
    'active' => request()->routeIs('upgrade.index'),
    'index' => $index++
];





    
    //   if(auth()->user()->can('help and support')) {
    //     $navItems[] = [
    //         'name' => 'Help & Support',
    //         'route' => route('helpandsupportuser.index'),
    //         'icon' => '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
    //                  fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
    //                  stroke-linejoin="round" class="w-4 h-4">
    //                 <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
    //                 <polyline points="14 2 14 8 20 8"></polyline>
    //                 <line x1="16" y1="13" x2="8" y2="13"></line>
    //                 <line x1="16" y1="17" x2="8" y2="17"></line>
    //             </svg>',
    //         'active' => request()->routeIs('helpandsupportuser.index'),
    //         'index' => $index++
    //     ];
    // } 


    //  if(auth()->user()->can('helpandsupportadmin')) {
    //     $navItems[] = [
    //         'name' => 'Admin Help & Support ',
    //         'route' => route('helpandsupportadmin.index'),
    //         'icon' => ' <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
    //                  fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
    //                  stroke-linejoin="round" class="w-4 h-4">
    //                 <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
    //                 <polyline points="14 2 14 8 20 8"></polyline>
    //                 <line x1="16" y1="13" x2="8" y2="13"></line>
    //                 <line x1="16" y1="17" x2="8" y2="17"></line>
    //             </svg>',
    //         'active' => request()->routeIs('helpandsupportadmin.index'),
    //         'index' => $index++
    //     ];
    // } 



    $navItems[] = [
        'name' => 'Profile',
        'route' => route('profile.view'),
        'icon' => '<div class="w-5 h-2 bg-gradient-to-br from-indigo-400 to-purple-400 rounded-full flex items-center justify-center"><span class="text-white font-semibold text-[8px]">' . strtoupper(substr(Auth::user()->username, 0, 2)) . '</span></div>',
        'active' => request()->routeIs('profile.view'),
        'index' => $index++
    ];
@endphp

<div class="lg:hidden fixed bottom-0 left-0 right-0 z-40">
    <!-- Bottom Navigation Container -->
    <div class="bottom-nav bg-white/95 backdrop-blur-xl border-t border-gray-200/60 shadow-2xl">
        <!-- Navigation Glow Effect -->
        <div id="navGlow" class="nav-glow absolute -bottom-2 left-0 w-14 h-4 rounded-full transition-all duration-300"></div>
        
        <div class="flex overflow-x-auto px-3 py-2 scrollbar-hide" id="navScrollContainer">
            <div class="flex items-center space-x-4 min-w-max" id="navItemsContainer">
                @foreach ($navItems as $item)
                    <div class="nav-item-wrapper flex-shrink-0" data-index="{{ $item['index'] }}">
                        <div class="nav-item {{ $item['active'] ? 'active' : '' }}" 
                             data-route="{{ $item['route'] }}"
                             data-name="{{ $item['name'] }}">
                            <a href="{{ $item['route'] }}" class="nav-link flex flex-col items-center justify-center w-full h-full rounded-xl transition-all duration-300 text-slate-500 {{ $item['active'] ? 'text-white' : 'hover:bg-[linear-gradient(135deg,_#667eea_0%,_#764ba2_100%)]' }}">
                                <div class="icon mb-0.5 transition-transform duration-300 flex items-center justify-center">
                                    {!! $item['icon'] !!}
                                </div>
                                <span class="label text-[8px] font-medium truncate max-w-[60px] transition-all duration-300 {{ $item['active'] ? 'opacity-900 translate-y-0 text-white' : 'opacity-900 translate-y-1' }}">
                                    {{ $item['name'] }}
                                </span>
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</div>

<style>
    /* Reset and base styles */
    .nav-item-wrapper {
        position: relative;
        width: 60px;
        height: 60px;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    
    .nav-item {
        width: 52px;
        height: 52px;
        border-radius: 16px;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: all 0.35s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        position: relative;
        cursor: pointer;
    }
    
    .nav-item:not(.active) {
        background: transparent;
    }
    
    .nav-item.active {
        position: absolute;
        width: 60px;
        height: 60px;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        box-shadow: 0 8px 20px rgba(102, 126, 234, 0.4);
        z-index: 30;
        /* transform: translateY(-25px);
        animation: floatActive 3s ease-in-out infinite; */
    }
    
    /* @keyframes floatActive {
        0%, 100% { 
            transform: translateY(-25px) scale(1.08); 
            box-shadow: 0 12px 24px rgba(102, 126, 234, 0.45);
        }
        50% { 
            transform: translateY(-28px) scale(1.12); 
            box-shadow: 0 15px 30px rgba(102, 126, 234, 0.5);
        }
    }
     */
    .nav-link {
        width: 100%;
        height: 100%;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        /* padding: 10px; */
    }
    
    /* .nav-item.active .nav-link {
        color: white !important;
    } */
    
    .nav-item.active .nav-link .icon svg {
        stroke: white !important;
    }
    
    .nav-item:not(.active) .nav-link .icon svg {
        stroke: #64748b;
    }
    
    .nav-glow {
        background: linear-gradient(135deg, rgba(102, 126, 234, 0.35), rgba(118, 75, 162, 0.35));
        filter: blur(10px);
        transition: all 0.35s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        opacity: 0.7;
    }
    
    .scrollbar-hide {
        -ms-overflow-style: none;
        scrollbar-width: none;
    }
    
    .scrollbar-hide::-webkit-scrollbar {
        display: none;
    }
    
    /* Reduced height bottom navigation */
    .bottom-nav {
        border-radius: 20px 20px 0 0;
        height: 80px; /* Reduced from 90px */
        display: flex;
        align-items: flex-end;
        position: relative;
        overflow: visible;
    }
    
    .icon svg {
        width: 18px; /* Slightly smaller */
        height: 18px;
        display: block;
    }
    
    /* Profile icon specific */
    .nav-link .icon .bg-gradient-to-br {
        width: 18px;
        height: 18px;
    }
    
    /* Active state for profile icon */
    .nav-item.active .nav-link .icon .bg-gradient-to-br {
        background: white !important;
    }
    
    .nav-item.active .nav-link .icon .bg-gradient-to-br span {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
        font-size: 10px ; /* Slightly smaller */
    }
    
    /* Label styling */
    .label {
        font-size: 10px !important; /* Smaller font */
        line-height: 1.1;
    }
</style>

<script>
    class EnhancedMobileNavigation {
        constructor() {
            this.navItems = document.querySelectorAll('.nav-item');
            this.navWrappers = document.querySelectorAll('.nav-item-wrapper');
            this.navLinks = document.querySelectorAll('.nav-link');
            this.navGlow = document.getElementById('navGlow');
            this.navScrollContainer = document.getElementById('navScrollContainer');

            this.currentIndex = 0;
            this.totalItems = this.navItems.length;

            // Find active item index
            this.navItems.forEach((item, index) => {
                if (item.classList.contains('active')) {
                    this.currentIndex = index;
                }
            });

            this.config = {
                transitionDuration: 350,
                itemSpacing: 16
            };

            this.init();
        }

        init() {
            // Add click events to wrappers
            this.navWrappers.forEach((wrapper) => {
                wrapper.addEventListener('click', (e) => {
                    e.preventDefault();
                    const index = parseInt(wrapper.dataset.index);
                    this.navigateTo(index);
                });
            });

            // Initial setup
            this.updateActiveItem();
            setTimeout(() => {
                this.centerActiveItem();
                this.updateGlowPosition();
            }, 100);

            // Handle window resize
            let resizeTimeout;
            window.addEventListener('resize', () => {
                clearTimeout(resizeTimeout);
                resizeTimeout = setTimeout(() => {
                    this.centerActiveItem();
                    this.updateGlowPosition();
                }, 250);
            });
        }

        navigateTo(index) {
            if (index < 0 || index >= this.totalItems || index === this.currentIndex) return;

            this.currentIndex = index;

            this.updateActiveItem();
            this.centerActiveItem();
            this.updateGlowPosition();

            const activeItem = this.navItems[this.currentIndex];
            const link = activeItem.querySelector('.nav-link');

            setTimeout(() => {
                window.location.href = link.href;
            }, 300);

            if (navigator.vibrate) {
                navigator.vibrate(20);
            }
        }

        updateActiveItem() {
            this.navItems.forEach((item, i) => {
                const isActive = i === this.currentIndex;
                item.classList.toggle('active', isActive);

                // Label: always visible, just change color
                const label = item.querySelector('.label');
                if (label) {
                    if (isActive) {
                        label.classList.add('text-white');
                        label.classList.remove('text-gray-400');
                    } else {
                        label.classList.add('text-gray-400');
                        label.classList.remove('text-white');
                    }
                    // Ensure opacity is always full
                    label.classList.add('opacity-100');
                    label.classList.remove('opacity-0', 'translate-y-1', 'translate-y-0');
                }

                // Icon/link text color
                const link = item.querySelector('.nav-link');
                if (link) {
                    link.classList.toggle('text-white', isActive);
                    link.classList.toggle('text-slate-500', !isActive);
                }

                // SVG stroke
                const svg = link?.querySelector('svg');
                if (svg) {
                    svg.style.stroke = isActive ? 'white' : '#64748b';
                }

                // Profile icon gradient handling
                const profileIcon = link?.querySelector('.bg-gradient-to-br');
                if (profileIcon) {
                    const span = profileIcon.querySelector('span');
                    if (isActive) {
                        profileIcon.style.background = 'white';
                        if (span) {
                            span.style.color = 'transparent';
                            span.style.webkitTextFillColor = 'transparent';
                            span.style.background = 'linear-gradient(135deg, #667eea 0%, #764ba2 100%)';
                            span.style.webkitBackgroundClip = 'text';
                            span.style.backgroundClip = 'text';
                        }
                    } else {
                        profileIcon.style.background = 'linear-gradient(135deg, #667eea 0%, #764ba2 100%)';
                        if (span) {
                            span.style.color = 'white';
                            span.style.webkitTextFillColor = 'white';
                            span.style.background = 'none';
                        }
                    }
                }
            });
        }

        updateGlowPosition() {
            if (!this.navGlow) return;

            const activeWrapper = this.navWrappers[this.currentIndex];
            if (!activeWrapper) return;

            const rect = activeWrapper.getBoundingClientRect();
            const containerRect = this.navScrollContainer.getBoundingClientRect();

            const relativeLeft = rect.left - containerRect.left + this.navScrollContainer.scrollLeft;
            const itemCenter = relativeLeft + (rect.width / 2);
            const glowCenter = itemCenter - 28;

            this.navGlow.style.transform = `translateX(${glowCenter}px)`;
            this.navGlow.style.opacity = '0.7';
        }

        centerActiveItem() {
            const activeWrapper = this.navWrappers[this.currentIndex];
            if (!activeWrapper || !this.navScrollContainer) return;

            const containerWidth = this.navScrollContainer.offsetWidth;
            const itemWidth = activeWrapper.offsetWidth;
            const itemOffset = activeWrapper.offsetLeft;

            const targetScroll = itemOffset - (containerWidth / 2) + (itemWidth / 2);

            this.navScrollContainer.scrollTo({
                left: targetScroll,
                behavior: 'smooth'
            });
        }
    }

    document.addEventListener('DOMContentLoaded', () => {
        new EnhancedMobileNavigation();
    });
</script>



<style>
.scrollbar-hide {
    -ms-overflow-style: none;
    scrollbar-width: none;
}
.scrollbar-hide::-webkit-scrollbar {
    display: none;
}
</style>