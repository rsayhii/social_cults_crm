<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CRM Admin | @yield('title')</title>
    
    <!-- CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Inter', 'sans-serif'],
                    },
                    colors: {
                        primary: {
                            50: '#eff6ff',
                            100: '#dbeafe',
                            500: '#3b82f6',
                            600: '#2563eb',
                            700: '#1d4ed8',
                        }
                    }
                }
            }
        }
    </script>
    
    <!-- JavaScript -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js@3.9.1/dist/chart.min.js"></script>
    
    <style>
        body { font-family: 'Inter', sans-serif; }
        .glass-nav {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(12px);
            border-bottom: 1px solid rgba(243, 244, 246, 0.8);
        }
        .sidebar-item {
            position: relative;
            transition: all 0.2s ease-in-out;
        }
        .sidebar-item::before {
            content: '';
            position: absolute;
            left: 0;
            top: 50%;
            transform: translateY(-50%);
            height: 0;
            width: 3px;
            background-color: #2563eb;
            border-radius: 0 4px 4px 0;
            transition: height 0.2s ease-in-out;
            opacity: 0;
        }
        .sidebar-item.active::before {
            height: 60%;
            opacity: 1;
        }
        
        /* Custom Scrollbar */
        .custom-scrollbar::-webkit-scrollbar {
            width: 5px;
        }
        .custom-scrollbar::-webkit-scrollbar-track {
            background: transparent;
        }
        .custom-scrollbar::-webkit-scrollbar-thumb {
            background-color: #e5e7eb;
            border-radius: 20px;
        }
    </style>
    @yield('styles')
</head>
<body class="bg-slate-50 text-slate-800 antialiased selection:bg-blue-100 selection:text-blue-700">
    
    <div class="min-h-screen flex flex-col md:flex-row">
        
        <!-- Sidebar -->
        <aside class="w-full md:w-72 bg-white border-r border-slate-100 flex-shrink-0 fixed md:relative z-30 h-screen hidden md:flex flex-col shadow-[4px_0_24px_rgba(0,0,0,0.02)]">
            <!-- Logo Area -->
            <div class="h-20 flex items-center px-8 border-b border-slate-50 bg-white z-20">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-blue-600 to-indigo-600 flex items-center justify-center shadow-lg shadow-blue-200 transform hover:scale-105 transition-transform duration-300">
                        <i class="fas fa-layer-group text-white text-lg"></i>
                    </div>
                    <div class="flex flex-col">
                        <span class="text-lg font-bold bg-clip-text text-transparent bg-gradient-to-r from-slate-900 to-slate-600">
                            SocialCults
                        </span>
                        <span class="text-[10px] uppercase tracking-widest text-slate-400 font-semibold">Admin Panel</span>
                    </div>
                </div>
            </div>

            <!-- Navigation -->
            <div class="flex-1 overflow-y-auto custom-scrollbar py-6 px-4 space-y-1">
                <p class="px-4 text-xs font-bold text-slate-400 uppercase tracking-widest mb-3">Overview</p>
                
                <a href="{{ route('superadmin.dashboard') }}" 
                   class="sidebar-item flex items-center gap-3 px-4 py-3.5 text-sm font-medium rounded-xl transition-all duration-200 group {{ request()->routeIs('superadmin.dashboard') ? 'active bg-blue-50 text-blue-700' : 'text-slate-500 hover:bg-slate-50 hover:text-slate-900' }}">
                    <i class="fas fa-chart-pie w-5 {{ request()->routeIs('superadmin.dashboard') ? 'text-blue-600' : 'text-slate-400 group-hover:text-slate-600' }} transition-colors"></i>
                    Dashboard
                </a>

                <p class="px-4 text-xs font-bold text-slate-400 uppercase tracking-widest mb-3 mt-8">Management</p>

                <a href="{{ route('superadmin.customers.index') }}" 
                   class="sidebar-item flex items-center gap-3 px-4 py-3.5 text-sm font-medium rounded-xl transition-all duration-200 group {{ request()->routeIs('superadmin.customers.*') ? 'active bg-blue-50 text-blue-700' : 'text-slate-500 hover:bg-slate-50 hover:text-slate-900' }}">
                    <i class="fas fa-users w-5 {{ request()->routeIs('superadmin.customers.*') ? 'text-blue-600' : 'text-slate-400 group-hover:text-slate-600' }} transition-colors"></i>
                    Customers
                </a>

                <a href="{{ route('superadmin.trials.index') }}" 
                   class="sidebar-item flex items-center gap-3 px-4 py-3.5 text-sm font-medium rounded-xl transition-all duration-200 group {{ request()->routeIs('superadmin.trials.*') ? 'active bg-blue-50 text-blue-700' : 'text-slate-500 hover:bg-slate-50 hover:text-slate-900' }}">
                    <i class="fas fa-stopwatch w-5 {{ request()->routeIs('superadmin.trials.*') ? 'text-blue-600' : 'text-slate-400 group-hover:text-slate-600' }} transition-colors"></i>
                    Trials
                </a>

                <a href="{{ route('superadmin.subscriptions.index') }}" 
                   class="sidebar-item flex items-center gap-3 px-4 py-3.5 text-sm font-medium rounded-xl transition-all duration-200 group {{ request()->routeIs('superadmin.subscriptions.*') ? 'active bg-blue-50 text-blue-700' : 'text-slate-500 hover:bg-slate-50 hover:text-slate-900' }}">
                    <i class="fas fa-credit-card w-5 {{ request()->routeIs('superadmin.subscriptions.*') ? 'text-blue-600' : 'text-slate-400 group-hover:text-slate-600' }} transition-colors"></i>
                    Subscriptions
                </a>

                <p class="px-4 text-xs font-bold text-slate-400 uppercase tracking-widest mb-3 mt-8">System</p>

                <a href="{{ route('ticket.record.index') }}" 
                   class="sidebar-item flex items-center gap-3 px-4 py-3.5 text-sm font-medium rounded-xl transition-all duration-200 group {{ request()->routeIs('ticket.*') ? 'active bg-blue-50 text-blue-700' : 'text-slate-500 hover:bg-slate-50 hover:text-slate-900' }}">
                    <i class="fas fa-headset w-5 {{ request()->routeIs('ticket.*') ? 'text-blue-600' : 'text-slate-400 group-hover:text-slate-600' }} transition-colors"></i>
                    Support Desk
                </a>

                <a href="{{ route('superadmin.settings.index') }}" 
                   class="sidebar-item flex items-center gap-3 px-4 py-3.5 text-sm font-medium rounded-xl transition-all duration-200 group {{ request()->routeIs('superadmin.settings.*') ? 'active bg-blue-50 text-blue-700' : 'text-slate-500 hover:bg-slate-50 hover:text-slate-900' }}">
                    <i class="fas fa-cog w-5 {{ request()->routeIs('superadmin.settings.*') ? 'text-blue-600' : 'text-slate-400 group-hover:text-slate-600' }} transition-colors"></i>
                    Settings
                </a>
            </div>

            <!-- Footer User Profile (Bottom Sidebar) -->
            <div class="p-4 border-t border-slate-50">
                <form method="GET" action="{{ route('superadmin.logout') }}">
                    @csrf
                    <button type="submit" class="flex items-center gap-3 w-full px-4 py-3 text-sm font-medium text-slate-500 rounded-xl hover:bg-red-50 hover:text-red-600 transition-all duration-200 group">
                        <i class="fas fa-sign-out-alt w-5 group-hover:text-red-500 transition-colors"></i>
                        Logout
                    </button>
                </form>
            </div>
        </aside>

        <!-- Main Content -->
        <div class="flex-1 flex flex-col h-screen overflow-hidden bg-slate-50">
            <!-- Top Header -->
            <header class="h-20 glass-nav z-20 flex items-center justify-between px-6 lg:px-10">
                <!-- Mobile Menu Button (Visible on mobile) -->
                <button class="md:hidden text-slate-500 hover:text-slate-700 p-2 rounded-lg hover:bg-slate-100 transition-colors">
                    <i class="fas fa-bars text-xl"></i>
                </button>

                <!-- Page Title / Breadcrumbs -->
                <div class="hidden md:block">
                    <h1 class="text-xl font-bold text-slate-800 tracking-tight">@yield('header', 'Dashboard')</h1>
                    <p class="text-xs text-slate-400 font-medium mt-0.5">Welcome back, Super Admin</p>
                </div>

                <!-- Right Side -->
                <div class="flex items-center gap-6">
                    <!-- Search (Optional) -->
                    <div class="hidden lg:flex items-center bg-slate-100 rounded-full px-4 py-2 border border-slate-200 focus-within:border-blue-300 focus-within:ring-2 focus-within:ring-blue-100 transition-all w-64">
                        <i class="fas fa-search text-slate-400 text-sm"></i>
                        <input type="text" placeholder="Search..." class="bg-transparent border-none text-sm ml-2 w-full focus:outline-none text-slate-600 placeholder-slate-400">
                    </div>

                    <div class="h-8 w-px bg-slate-200"></div>

                    <!-- Notifications -->
                    <button class="relative p-2 text-slate-400 hover:text-blue-600 transition-colors rounded-full hover:bg-blue-50">
                        <i class="far fa-bell text-xl"></i>
                        <span class="absolute top-2 right-2.5 w-2 h-2 bg-red-500 rounded-full border-2 border-white"></span>
                    </button>

                    <!-- Profile -->
                    <div class="flex items-center gap-3 pl-2">
                        <div class="text-right hidden sm:block leading-tight">
                            <p class="text-sm font-bold text-slate-700">Admin User</p>
                            <p class="text-[10px] uppercase font-bold text-blue-600 tracking-wide">Super Admin</p>
                        </div>
                        <div class="w-10 h-10 rounded-full bg-gradient-to-tr from-blue-500 to-indigo-500 p-[2px] shadow-md shadow-blue-100 cursor-pointer hover:shadow-lg transition-shadow">
                            <div class="w-full h-full rounded-full bg-white flex items-center justify-center">
                                <span class="font-bold text-transparent bg-clip-text bg-gradient-to-tr from-blue-600 to-indigo-600 text-xs">SA</span>
                            </div>
                        </div>
                    </div>
                </div>
            </header>

            <!-- Scrollable Content -->
            <main class="flex-1 overflow-y-auto p-6 lg:p-10 scroll-smooth">
                <div class="max-w-7xl mx-auto space-y-6">
                    @if(session('success'))
                        <div class="p-4 rounded-2xl bg-emerald-50 border border-emerald-100 text-emerald-800 flex items-center gap-4 shadow-sm animate-fade-in-down" role="alert">
                            <div class="w-8 h-8 rounded-full bg-emerald-100 flex items-center justify-center flex-shrink-0">
                                <i class="fas fa-check text-emerald-600 text-sm"></i>
                            </div>
                            <span class="font-medium text-sm">{{ session('success') }}</span>
                        </div>
                    @endif
                    
                    @if(session('error'))
                        <div class="p-4 rounded-2xl bg-red-50 border border-red-100 text-red-800 flex items-center gap-4 shadow-sm animate-fade-in-down" role="alert">
                            <div class="w-8 h-8 rounded-full bg-red-100 flex items-center justify-center flex-shrink-0">
                                <i class="fas fa-exclamation text-red-600 text-sm"></i>
                            </div>
                            <span class="font-medium text-sm">{{ session('error') }}</span>
                        </div>
                    @endif

                    @yield('content')
                </div>
            </main>
        </div>
    </div>
    
    <style>
        @keyframes fadeInDown {
            from {
                opacity: 0;
                transform: translateY(-10px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        .animate-fade-in-down {
            animation: fadeInDown 0.3s ease-out forwards;
        }
    </style>
    {{-- @yield('scripts') --}}
</body>
</html>