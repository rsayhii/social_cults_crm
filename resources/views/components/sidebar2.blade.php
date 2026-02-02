<!-- Desktop Sidebar -->
<div class="hidden lg:flex fixed inset-y-0 z-10 h-svh w-64 transition-all duration-200 border-r border-slate-200/60 bg-white/80 backdrop-blur-xl">
    <div class="flex h-full w-full flex-col">
        <!-- Sidebar Header -->
        <div class="flex flex-col gap-2 border-b border-slate-200/60 p-6">
            <div class="flex items-center gap-3">
                <img src="https://socialcults.com/images/client/logo.png" alt="Social Cults" class="h-10 w-auto">
            </div>
        </div>

        <!-- Sidebar Content -->
        <div class="flex min-h-0 flex-1 flex-col gap-2 overflow-auto p-3">
            <!-- Navigation Section -->
            <div class="relative flex w-full min-w-0 flex-col p-2">
                <div class="text-xs font-semibold text-slate-500 uppercase tracking-wider px-3 py-2">Navigation</div>
                <div class="w-full text-sm">
                    <ul class="flex w-full min-w-0 flex-col gap-1">
                        <!-- Dashboard -->
                        @can('dashboard')
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

                        <!-- Proposal -->
                        @can('proposal')
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
                        @endcan

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

                        <!-- Report -->
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



                        <!-- Notepad -->
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

                        <!-- Help and Support -->
                        <li class="relative">
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
                            {{ strtoupper(substr(Auth::user()->username, 0, 2)) }}
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
<div class="lg:hidden fixed bottom-0 left-0 right-0 bg-white/90 backdrop-blur-xl border-t border-slate-200/60 shadow-lg z-20">
    <div class="flex overflow-x-auto px-2 py-3 scrollbar-hide">
        <div class="flex items-center space-x-4 min-w-max">
            <!-- Dashboard -->
            @can('dashboard')
            <a href="{{ route('dashboard') }}" 
               class="flex flex-col items-center gap-1 p-2 min-w-[70px] {{ request()->routeIs('dashboard') ? 'text-indigo-600' : 'text-slate-500' }}">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                     fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                     stroke-linejoin="round" class="w-5 h-5">
                    <rect width="7" height="9" x="3" y="3" rx="1"></rect>
                    <rect width="7" height="5" x="14" y="3" rx="1"></rect>
                    <rect width="7" height="9" x="14" y="12" rx="1"></rect>
                    <rect width="7" height="5" x="3" y="16" rx="1"></rect>
                </svg>
                <span class="text-xs font-medium truncate max-w-[65px]">Dashboard</span>
            </a>
            @endcan

            <!-- Users -->
            @can('users')
            <a href="{{ route('users') }}" 
               class="flex flex-col items-center gap-1 p-2 min-w-[70px] {{ request()->routeIs('users') ? 'text-indigo-600' : 'text-slate-500' }}">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                     fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                     stroke-linejoin="round" class="w-5 h-5">
                    <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                    <circle cx="12" cy="7" r="4"></circle>
                </svg>
                <span class="text-xs font-medium truncate max-w-[65px]">Users</span>
            </a>
            @endcan

            <!-- Roles -->
            @can('roles')
            <a href="{{ route('roles') }}" 
               class="flex flex-col items-center gap-1 p-2 min-w-[70px] {{ request()->routeIs('roles') ? 'text-indigo-600' : 'text-slate-500' }}">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                     fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                     stroke-linejoin="round" class="w-5 h-5">
                    <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                    <circle cx="12" cy="7" r="4"></circle>
                </svg>
                <span class="text-xs font-medium truncate max-w-[65px]">Roles</span>
            </a>
            @endcan

            <!-- Besdex -->
            @can('besdex')
            <a href="{{ route('clients.index') }}" 
               class="flex flex-col items-center gap-1 p-2 min-w-[70px] {{ request()->routeIs('clients.index') ? 'text-indigo-600' : 'text-slate-500' }}">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                     fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                     stroke-linejoin="round" class="w-5 h-5">
                    <path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"></path>
                    <circle cx="9" cy="7" r="4"></circle>
                    <path d="M22 21v-2a4 4 0 0 0-3-3.87"></path>
                </svg>
                <span class="text-xs font-medium truncate max-w-[65px]">Besdex</span>
            </a>
            @endcan


            <!-- salary -->
            @can('salary')
            <a href="{{ route('salary.index') }}" 
               class="flex flex-col items-center gap-1 p-2 min-w-[70px] {{ request()->routeIs('salary.index') ? 'text-indigo-600' : 'text-slate-500' }}">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                    fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                    stroke-linejoin="round" class="w-4 h-4">
                                    <rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect>
                                    <line x1="16" y1="2" x2="16" y2="6"></line>
                                    <line x1="8" y1="2" x2="8" y2="6"></line>
                                    <circle cx="12" cy="12" r="3"></circle>
                                </svg>
                <span class="text-xs font-medium truncate max-w-[65px]">Salary</span>
            </a>
            @endcan



             


            <!-- Leave Records -->
            @can('admin portal')
            <a href="{{ route('adminportal.index') }}" 
               class="flex flex-col items-center gap-1 p-2 min-w-[70px] {{ request()->routeIs('adminportal.index') ? 'text-indigo-600' : 'text-slate-500' }}">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                     fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                     stroke-linejoin="round" class="w-5 h-5">
                    <rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect>
                    <line x1="16" y1="2" x2="16" y2="6"></line>
                    <line x1="8" y1="2" x2="8" y2="6"></line>
                </svg>
                <span class="text-xs font-medium truncate max-w-[65px]">Leave Record</span>
            </a>
            @endcan


             


            <!-- Leave Apply -->
            @can('employeeportal')
            <a href="{{ route('employeeportal.index') }}" 
               class="flex flex-col items-center gap-1 p-2 min-w-[70px] {{ request()->routeIs('employeeportal.index') ? 'text-indigo-600' : 'text-slate-500' }}">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                     fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                     stroke-linejoin="round" class="w-5 h-5">
                    <rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect>
                    <line x1="16" y1="2" x2="16" y2="6"></line>
                    <line x1="8" y1="2" x2="8" y2="6"></line>
                </svg>
                <span class="text-xs font-medium truncate max-w-[65px]">Leave Apply</span>
            </a>
            @endcan

            <!-- Attendance Records -->
            @can('attendance records')
            <a href="{{ route('attendance-record.index') }}" 
               class="flex flex-col items-center gap-1 p-2 min-w-[70px] {{ request()->routeIs('attendance-record.index') ? 'text-indigo-600' : 'text-slate-500' }}">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                     fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                     stroke-linejoin="round" class="w-5 h-5">
                    <rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect>
                    <line x1="16" y1="2" x2="16" y2="6"></line>
                    <line x1="8" y1="2" x2="8" y2="6"></line>
                </svg>
                <span class="text-xs font-medium truncate max-w-[65px]">Attendance</span>
            </a>
            @endcan

            <!-- My Leads -->
            @can('my leads')
            <a href="{{ route('myleads') }}" 
               class="flex flex-col items-center gap-1 p-2 min-w-[70px] {{ request()->routeIs('myleads') ? 'text-indigo-600' : 'text-slate-500' }}">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                     fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                     stroke-linejoin="round" class="w-5 h-5">
                    <path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"></path>
                </svg>
                <span class="text-xs font-medium truncate max-w-[65px]">Leads</span>
            </a>
            @endcan

            <!-- Proposal -->
            @can('proposal')
            <a href="{{ route('proposal') }}" 
               class="flex flex-col items-center gap-1 p-2 min-w-[70px] {{ request()->routeIs('proposal') ? 'text-indigo-600' : 'text-slate-500' }}">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                     fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                     stroke-linejoin="round" class="w-5 h-5">
                    <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                    <polyline points="14 2 14 8 20 8"></polyline>
                </svg>
                <span class="text-xs font-medium truncate max-w-[65px]">Proposal</span>
            </a>
            @endcan

            <!-- My Attendance -->
            @can('my attendance')
            <a href="{{ route('my-attendance.index') }}" 
               class="flex flex-col items-center gap-1 p-2 min-w-[70px] {{ request()->routeIs('my-attendance.index') ? 'text-indigo-600' : 'text-slate-500' }}">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                     fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                     stroke-linejoin="round" class="w-5 h-5">
                    <rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect>
                    <line x1="16" y1="2" x2="16" y2="6"></line>
                    <line x1="8" y1="2" x2="8" y2="6"></line>
                    <circle cx="12" cy="12" r="3"></circle>
                </svg>
                <span class="text-xs font-medium truncate max-w-[65px]">My Attend</span>
            </a>
            @endcan





            <!-- My Attendance -->
            @can('project management')
            <a href="{{ route('projectmanagement.index') }}" 
               class="flex flex-col items-center gap-1 p-2 min-w-[70px] {{ request()->routeIs('projectmanagement.index') ? 'text-indigo-600' : 'text-slate-500' }}">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                     fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                     stroke-linejoin="round" class="w-5 h-5">
                    <rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect>
                    <line x1="16" y1="2" x2="16" y2="6"></line>
                    <line x1="8" y1="2" x2="8" y2="6"></line>
                    <circle cx="12" cy="12" r="3"></circle>
                </svg>
                <span class="text-xs font-medium truncate max-w-[65px]">Project Management</span>
            </a>
            @endcan

            <!-- Todo -->
            @can('todo')
            <a href="{{ route('todo.index') }}" 
               class="flex flex-col items-center gap-1 p-2 min-w-[70px] {{ request()->routeIs('todo.index') ? 'text-indigo-600' : 'text-slate-500' }}">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                     fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                     stroke-linejoin="round" class="w-5 h-5">
                    <rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect>
                    <path d="m9 12 2 2 4-4"></path>
                </svg>
                <span class="text-xs font-medium truncate max-w-[65px]">Todo</span>
            </a>
            @endcan

            <!-- Task -->
            @can('task')
            <a href="{{ route('tasks.index') }}" 
               class="flex flex-col items-center gap-1 p-2 min-w-[70px] {{ request()->routeIs('tasks.index') ? 'text-indigo-600' : 'text-slate-500' }}">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                     fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                     stroke-linejoin="round" class="w-5 h-5">
                    <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                    <path d="M14 2v6h6"></path>
                    <path d="M16 13H8"></path>
                    <path d="M16 17H8"></path>
                </svg>
                <span class="text-xs font-medium truncate max-w-[65px]">Task</span>
            </a>
            @endcan

            <!-- Calendar -->
            @can('calender')
            <a href="{{ route('calender.index') }}" 
               class="flex flex-col items-center gap-1 p-2 min-w-[70px] {{ request()->routeIs('calender.index') ? 'text-indigo-600' : 'text-slate-500' }}">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                     fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                     stroke-linejoin="round" class="w-5 h-5">
                    <rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect>
                    <line x1="16" y1="2" x2="16" y2="6"></line>
                    <line x1="8" y1="2" x2="8" y2="6"></line>
                    <line x1="3" y1="10" x2="21" y2="10"></line>
                </svg>
                <span class="text-xs font-medium truncate max-w-[65px]">Calendar</span>
            </a>
            @endcan

            <!-- Links and Remark -->
            @can('links and remark')
            <a href="{{ route('linksandremark.index') }}" 
               class="flex flex-col items-center gap-1 p-2 min-w-[70px] {{ request()->routeIs('linksandremark.index') ? 'text-indigo-600' : 'text-slate-500' }}">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                     fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                     stroke-linejoin="round" class="w-5 h-5">
                    <path d="M10 13a5 5 0 0 0 7.54.54l3-3a5 5 0 0 0-7.07-7.07l-1.72 1.71"></path>
                    <path d="M14 11a5 5 0 0 0-7.54-.54l-3 3a5 5 0 0 0 7.07 7.07l1.71-1.71"></path>
                </svg>
                <span class="text-xs font-medium truncate max-w-[65px]">Links</span>
            </a>
            @endcan

            <!-- Interaction -->
            @can('client serive interation')
            <a href="{{ route('chat.index') }}" 
               class="flex flex-col items-center gap-1 p-2 min-w-[70px] {{ request()->routeIs('chat.index') ? 'text-indigo-600' : 'text-slate-500' }}">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                     fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                     stroke-linejoin="round" class="w-5 h-5">
                    <path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"></path>
                </svg>
                <span class="text-xs font-medium truncate max-w-[65px]">Interaction</span>
            </a>
            @endcan

            <!-- Invoice -->
            @can('invoice')
            <a href="{{ route('invoices.index') }}" 
               class="flex flex-col items-center gap-1 p-2 min-w-[70px] {{ request()->routeIs('invoices.index') ? 'text-indigo-600' : 'text-slate-500' }}">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                     fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                     stroke-linejoin="round" class="w-5 h-5">
                    <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                    <polyline points="14 2 14 8 20 8"></polyline>
                    <line x1="16" y1="13" x2="8" y2="13"></line>
                    <line x1="16" y1="17" x2="8" y2="17"></line>
                </svg>
                <span class="text-xs font-medium truncate max-w-[65px]">Invoice</span>
            </a>
            @endcan

            <!-- Contact -->
            @can('contact')
            <a href="{{ route('contacts.index') }}" 
               class="flex flex-col items-center gap-1 p-2 min-w-[70px] {{ request()->routeIs('contacts.index') ? 'text-indigo-600' : 'text-slate-500' }}">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                     fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                     stroke-linejoin="round" class="w-5 h-5">
                    <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
                    <circle cx="9" cy="7" r="4"></circle>
                    <path d="M23 21v-2a4 4 0 0 0-3-3.87"></path>
                    <path d="M16 3.13a4 4 0 0 1 0 7.75"></path>
                </svg>
                <span class="text-xs font-medium truncate max-w-[65px]">Contact</span>
            </a>
            @endcan

            <!-- Report -->
            <a href="{{ route('rr.index') }}" 
               class="flex flex-col items-center gap-1 p-2 min-w-[70px] {{ request()->routeIs('rr.index') ? 'text-indigo-600' : 'text-slate-500' }}">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                     fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                     stroke-linejoin="round" class="w-5 h-5">
                    <path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"></path>
                    <line x1="9" y1="10" x2="15" y2="10"></line>
                    <line x1="12" y1="7" x2="12" y2="13"></line>
                </svg>
                <span class="text-xs font-medium truncate max-w-[65px]">Report</span>
            </a>

            <!-- Help and Support -->
            <a href="{{ route('helpandsupport.index') }}" 
               class="flex flex-col items-center gap-1 p-2 min-w-[70px] {{ request()->routeIs('helpandsupport.index') ? 'text-indigo-600' : 'text-slate-500' }}">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                     fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                     stroke-linejoin="round" class="w-5 h-5">
                    <circle cx="12" cy="12" r="10"></circle>
                    <path d="M9.09 9a3 3 0 0 1 5.83 1c0 2-3 3-3 3"></path>
                    <line x1="12" y1="17" x2="12.01" y2="17"></line>
                </svg>
                <span class="text-xs font-medium truncate max-w-[65px]">Help</span>
            </a>

            <!-- Profile -->
            <div class="relative group">
                <a href="{{ route('profile.view') }}" 
                   class="flex flex-col items-center gap-1 p-2 min-w-[70px] {{ request()->routeIs('profile.view') ? 'text-indigo-600' : 'text-slate-500' }}">
                    <div class="w-5 h-5 bg-gradient-to-br from-indigo-400 to-purple-400 rounded-full flex items-center justify-center">
                        <span class="text-white font-semibold text-xs">
                            {{ strtoupper(substr(Auth::user()->username, 0, 2)) }}
                        </span>
                    </div>
                    <span class="text-xs font-medium truncate max-w-[65px]">Profile</span>
                </a>
                
                <!-- Profile Dropdown for Mobile -->
                <div class="absolute bottom-full left-1/2 transform -translate-x-1/2 mb-2 hidden group-hover:block">
                    <div class="bg-white rounded-lg shadow-lg border border-slate-200 min-w-[140px]">
                        <div class="p-3 border-b border-slate-100">
                            <p class="font-semibold text-sm text-slate-900 truncate">{{ Auth::user()->name }}</p>
                            <p class="text-xs text-slate-500 truncate">{{ Auth::user()->email }}</p>
                        </div>
                        <div class="p-2">
                            <a href="{{ route('profile.view') }}" class="block w-full text-left px-3 py-2 text-sm hover:bg-slate-50 rounded">
                                Visit Profile
                            </a>
                            <form method="GET" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="block w-full text-left px-3 py-2 text-sm text-red-600 hover:bg-red-50 rounded">
                                    Logout
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.scrollbar-hide {
    -ms-overflow-style: none;
    scrollbar-width: none;
}
.scrollbar-hide::-webkit-scrollbar {
    display: none;
}
</style>