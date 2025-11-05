     <!-- Desktop Sidebar -->
            <div class="hidden lg:flex fixed inset-y-0 z-10 h-svh w-64 transition-all duration-200 border-r border-slate-200/60 bg-white/80 backdrop-blur-xl border border-red-800">
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

            <!-- Clients -->
            @can('besdex')
            <li class="relative">
                <a href="{{ route('clients') }}"
                   class="flex w-full items-center gap-2 overflow-hidden p-2 text-left h-8 text-sm rounded-xl mb-1 flex items-center gap-3 px-4 py-3 
                   {{ request()->routeIs('clients') 
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

            <!-- Campaigns -->
            @can('campaigns')
            <li class="relative">
                <a href="{{ route('campaigns') }}"
                   class="flex w-full items-center gap-2 overflow-hidden p-2 text-left h-8 text-sm rounded-xl mb-1 flex items-center gap-3 px-4 py-3 
                   {{ request()->routeIs('campaigns') 
                        ? 'bg-gradient-to-r from-indigo-600 to-purple-600 text-white shadow-lg shadow-indigo-500/30' 
                        : 'hover:bg-indigo-50 hover:text-indigo-700 transition-all duration-200 text-slate-700' }}">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                         viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                         stroke-linecap="round" stroke-linejoin="round" class="w-4 h-4">
                        <path d="m3 11 18-5v12L3 14v-3z"></path>
                        <path d="M11.6 16.8a3 3 0 1 1-5.8-1.6"></path>
                    </svg>
                    <span class="font-medium">Campaigns</span>
                </a>
            </li>
             @endcan

            <!-- Tasks -->
            @can('tasks')
            <li class="relative">
                <a href="{{ route('task') }}"
                   class="flex w-full items-center gap-2 overflow-hidden p-2 text-left h-8 text-sm rounded-xl mb-1 flex items-center gap-3 px-4 py-3 
                   {{ request()->routeIs('task') 
                        ? 'bg-gradient-to-r from-indigo-600 to-purple-600 text-white shadow-lg shadow-indigo-500/30' 
                        : 'hover:bg-indigo-50 hover:text-indigo-700 transition-all duration-200 text-slate-700' }}">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                         viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                         stroke-linecap="round" stroke-linejoin="round" class="w-4 h-4">
                        <path d="M21 10.5V19a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h12.5"></path>
                        <path d="m9 11 3 3L22 4"></path>
                    </svg>
                    <span class="font-medium">Task</span>
                </a>
            </li>
             @endcan

            <!-- Attendance -->
            @can('attendance records')
            <li class="relative">
                <a href="{{ route('attendance') }}"
                   class="flex w-full items-center gap-2 overflow-hidden p-2 text-left h-8 text-sm rounded-xl mb-1 flex items-center gap-3 px-4 py-3 
                   {{ request()->routeIs('attendance') 
                        ? 'bg-gradient-to-r from-indigo-600 to-purple-600 text-white shadow-lg shadow-indigo-500/30' 
                        : 'hover:bg-indigo-50 hover:text-indigo-700 transition-all duration-200 text-slate-700' }}">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                         viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                         stroke-linecap="round" stroke-linejoin="round" class="w-4 h-4">
                        <rect width="8" height="4" x="8" y="2" rx="1" ry="1"></rect>
                        <path d="M16 4h2a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2h2"></path>
                        <path d="m9 14 2 2 4-4"></path>
                    </svg>
                    <span class="font-medium">Attendance Records</span>
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


              <!-- roles -->
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
               {{-- @can('besdex')
            <li class="relative">
                <a href="{{ route('besdex') }}"
                   class="flex w-full items-center gap-2 overflow-hidden p-2 text-left h-8 text-sm rounded-xl mb-1 flex items-center gap-3 px-4 py-3 
                   {{ request()->routeIs('besdex') 
                        ? 'bg-gradient-to-r from-indigo-600 to-purple-600 text-white shadow-lg shadow-indigo-500/30' 
                        : 'hover:bg-indigo-50 hover:text-indigo-700 transition-all duration-200 text-slate-700' }}">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                         viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                         stroke-linecap="round" stroke-linejoin="round" class="w-4 h-4">
                        <rect width="8" height="4" x="8" y="2" rx="1" ry="1"></rect>
                        <path d="M16 4h2a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2h2"></path>
                        <path d="m9 14 2 2 4-4"></path>
                    </svg>
                    <span class="font-medium">Besdex</span>
                </a>
            </li>
             @endcan --}}




                <!-- My Leads -->
               @can('my leads')
            <li class="relative">
                <a href="{{ route('myleads') }}"
                   class="flex w-full items-center gap-2 overflow-hidden p-2 text-left h-8 text-sm rounded-xl mb-1 flex items-center gap-3 px-4 py-3 
                   {{ request()->routeIs('myleads') 
                        ? 'bg-gradient-to-r from-indigo-600 to-purple-600 text-white shadow-lg shadow-indigo-500/30' 
                        : 'hover:bg-indigo-50 hover:text-indigo-700 transition-all duration-200 text-slate-700' }}">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                         viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                         stroke-linecap="round" stroke-linejoin="round" class="w-4 h-4">
                        <rect width="8" height="4" x="8" y="2" rx="1" ry="1"></rect>
                        <path d="M16 4h2a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2h2"></path>
                        <path d="m9 14 2 2 4-4"></path>
                    </svg>
                    <span class="font-medium">My Leads</span>
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
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                         viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                         stroke-linecap="round" stroke-linejoin="round" class="w-4 h-4">
                        <rect width="8" height="4" x="8" y="2" rx="1" ry="1"></rect>
                        <path d="M16 4h2a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2h2"></path>
                        <path d="m9 14 2 2 4-4"></path>
                    </svg>
                    <span class="font-medium">Proposal</span>
                </a>
            </li>
             @endcan



             <!-- My Attendance -->
               @can('my attendance')
            <li class="relative">
                <a href="{{ route('myattendance') }}"
                   class="flex w-full items-center gap-2 overflow-hidden p-2 text-left h-8 text-sm rounded-xl mb-1 flex items-center gap-3 px-4 py-3 
                   {{ request()->routeIs('myattendance') 
                        ? 'bg-gradient-to-r from-indigo-600 to-purple-600 text-white shadow-lg shadow-indigo-500/30' 
                        : 'hover:bg-indigo-50 hover:text-indigo-700 transition-all duration-200 text-slate-700' }}">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                         viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                         stroke-linecap="round" stroke-linejoin="round" class="w-4 h-4">
                        <rect width="8" height="4" x="8" y="2" rx="1" ry="1"></rect>
                        <path d="M16 4h2a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2h2"></path>
                        <path d="m9 14 2 2 4-4"></path>
                    </svg>
                    <span class="font-medium">My Attendance</span>
                </a>
            </li>
             @endcan







        </ul>
    </div>
</div>

                        <!-- Navigation Section -->
                        

                        <!-- Quick Stats Section -->
                        <div class="relative flex w-full min-w-0 flex-col p-2 mt-6">
                            <div class="text-xs font-semibold text-slate-500 uppercase tracking-wider px-3 py-2">Quick Stats</div>
                            <div class="w-full text-sm">
                                <div class="px-4 py-3 space-y-3">
                                    <div class="flex items-center justify-between p-3 bg-gradient-to-r from-orange-50 to-orange-100/50 rounded-lg">
                                        <span class="text-sm font-medium text-slate-700">New Leads</span>
                                        <div class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-semibold bg-orange-500 hover:bg-orange-600 text-white">1</div>
                                    </div>
                                    <div class="flex items-center justify-between p-3 bg-gradient-to-r from-blue-50 to-blue-100/50 rounded-lg">
                                        <span class="text-sm font-medium text-slate-700">Pending Tasks</span>
                                        <div class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-semibold bg-blue-500 hover:bg-blue-600 text-white">2</div>
                                    </div>
                                </div>
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