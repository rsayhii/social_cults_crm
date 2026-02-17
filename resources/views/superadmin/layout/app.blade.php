<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CRM Admin Dashboard | @yield('title')</title>
    
    <!-- CSS -->
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- JavaScript -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js@3.9.1/dist/chart.min.js"></script>
    
    <style>
        * {
            font-family: 'Inter', sans-serif;
        }
        
        .glassmorphism {
            background: rgba(255, 255, 255, 0.85);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.2);
        }
        
        .sidebar {
            height: calc(100vh - 64px);
        }
        
        .content-area {
            height: calc(100vh - 64px);
            overflow-y: auto;
        }
        
        .status-badge {
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 500;
        }
        
        .status-active {
            background-color: rgba(34, 197, 94, 0.1);
            color: rgb(21, 128, 61);
        }
        
        .status-trial {
            background-color: rgba(59, 130, 246, 0.1);
            color: rgb(30, 64, 175);
        }
        
        .status-expired {
            background-color: rgba(239, 68, 68, 0.1);
            color: rgb(185, 28, 28);
        }
        
        .status-pending {
            background-color: rgba(245, 158, 11, 0.1);
            color: rgb(146, 64, 14);
        }
        
        .status-cancelled {
            background-color: rgba(107, 114, 128, 0.1);
            color: rgb(55, 65, 81);
        }
        
        .gradient-bg {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }
        
        .card-hover {
            transition: all 0.3s ease;
        }
        
        .card-hover:hover {
            transform: translateY(-5px);
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
        }
    </style>
    
    @yield('styles')
</head>
<body class="bg-gray-50 text-gray-800">
    <!-- Top Navigation Bar -->
    <nav class="glassmorphism fixed top-0 left-0 right-0 z-40 py-3 px-6 flex items-center justify-between border-b border-gray-200">
        <div class="flex items-center space-x-4">
            <div class="flex items-center space-x-2">
                {{-- <div class="gradient-bg w-8 h-8 rounded-lg flex items-center justify-center">
                    <span class="text-white font-bold text-xl">C</span>
                </div> --}}
                <span class="text-xl font-bold text-gray-800">CRM SocialCults</span>
            </div>
        </div>
        
        <div class="flex items-center space-x-4">
            {{-- <a href="{{ route('superadmin.customers.create') }}" class="bg-gradient-to-r from-blue-600 to-indigo-600 text-white px-4 py-2 rounded-lg hover:from-blue-700 hover:to-indigo-700 transition duration-200 font-medium flex items-center">
                <i class="fas fa-plus mr-2"></i>
                Add New Customer
            </a> --}}
            
            <div class="relative">
                <button id="profileDropdownBtn" class="flex items-center space-x-2 focus:outline-none">
                    {{-- <div class="w-10 h-10 rounded-full bg-gradient-to-r from-blue-500 to-purple-500"></div> --}}
                    <div class="hidden md:block text-left">
                        <p class="text-sm font-medium">Admin User</p>
                        <p class="text-xs text-gray-500">Super Admin</p>
                    </div>
                </button>
            </div>
        </div>
    </nav>

    <!-- Sidebar Menu -->
    <div class="flex pt-16">
        <aside class="sidebar hidden md:block w-64 bg-white border-r border-gray-200 fixed left-0 overflow-y-auto">
            <div class="p-6">
                <ul class="space-y-1">
                    <li>
                        <a href="{{ route('superadmin.dashboard') }}" class="{{ request()->routeIs('dashboard') ? 'active-menu flex items-center space-x-3 px-4 py-3 rounded-lg bg-blue-50 text-blue-600 font-medium' : 'menu-item flex items-center space-x-3 px-4 py-3 rounded-lg text-gray-700 hover:bg-gray-100 transition duration-200' }}">
                            <i class="fas fa-chart-line"></i>
                            <span>Dashboard</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('superadmin.customers.index') }}" class="{{ request()->routeIs('customers.*') ? 'active-menu flex items-center space-x-3 px-4 py-3 rounded-lg bg-blue-50 text-blue-600 font-medium' : 'menu-item flex items-center space-x-3 px-4 py-3 rounded-lg text-gray-700 hover:bg-gray-100 transition duration-200' }}">
                            <i class="fas fa-users"></i>
                            <span>Customers</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('superadmin.trials.index') }}" class="{{ request()->routeIs('trials.*') ? 'active-menu flex items-center space-x-3 px-4 py-3 rounded-lg bg-blue-50 text-blue-600 font-medium' : 'menu-item flex items-center space-x-3 px-4 py-3 rounded-lg text-gray-700 hover:bg-gray-100 transition duration-200' }}">
                            <i class="fas fa-clock"></i>
                            <span>Trials</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('superadmin.subscriptions.index') }}" class="{{ request()->routeIs('subscriptions.*') ? 'active-menu flex items-center space-x-3 px-4 py-3 rounded-lg bg-blue-50 text-blue-600 text-xs  font-medium' : 'menu-item flex items-center space-x-3 px-4 py-3 rounded-lg text-gray-700 hover:bg-gray-100 transition duration-200' }}">
                            <i class="fas fa-id-card"></i>
                            <span class="text-sm">Active Subscriptions</span>
                        </a>
                    </li>
                    <!-- <li>
                        <a href="{{ route('superadmin.payments.index') }}" class="{{ request()->routeIs('payments.*') ? 'active-menu flex items-center space-x-3 px-4 py-3 rounded-lg bg-blue-50 text-blue-600 font-medium' : 'menu-item flex items-center space-x-3 px-4 py-3 rounded-lg text-gray-700 hover:bg-gray-100 transition duration-200' }}">
                            <i class="fas fa-credit-card"></i>
                            <span>Payments</span>
                        </a>
                    </li> -->
                    <!-- <li>
                        <a href="{{ route('superadmin.invoices.index') }}" class="{{ request()->routeIs('invoices.*') ? 'active-menu flex items-center space-x-3 px-4 py-3 rounded-lg bg-blue-50 text-blue-600 font-medium' : 'menu-item flex items-center space-x-3 px-4 py-3 rounded-lg text-gray-700 hover:bg-gray-100 transition duration-200' }}">
                            <i class="fas fa-file-invoice"></i>
                            <span>Invoices</span>
                        </a>
                    </li> -->
                    <!-- <li>
                        <a href="{{ route('superadmin.revenue.index') }}" class="{{ request()->routeIs('revenue.*') ? 'active-menu flex items-center space-x-3 px-4 py-3 rounded-lg bg-blue-50 text-blue-600 font-medium' : 'menu-item flex items-center space-x-3 px-4 py-3 rounded-lg text-gray-700 hover:bg-gray-100 transition duration-200' }}">
                            <i class="fas fa-chart-bar"></i>
                            <span>Revenue Report</span>
                        </a>
                    </li> -->
                     <li>
                        <a href="{{ route('ticket.record.index') }}" class="{{ request()->routeIs('revenue.*') ? 'active-menu flex items-center space-x-3 px-4 py-3 rounded-lg bg-blue-50 text-blue-600 font-medium' : 'menu-item flex items-center space-x-3 px-4 py-3 rounded-lg text-gray-700 hover:bg-gray-100 transition duration-200' }}">
                             <i class="fas fa-headset w-4 h-4"></i>
                            <span>Support Desk</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('superadmin.settings.index') }}" class="{{ request()->routeIs('settings.*') ? 'active-menu flex items-center space-x-3 px-4 py-3 rounded-lg bg-blue-50 text-blue-600 font-medium' : 'menu-item flex items-center space-x-3 px-4 py-3 rounded-lg text-gray-700 hover:bg-gray-100 transition duration-200' }}">
                            <i class="fas fa-cog"></i>
                            <span>Settings</span>
                        </a>
                    </li>
                    <li>
    <form method="GET" action="{{ route('superadmin.logout') }}">
        @csrf
        <button type="submit"
            class="w-full text-left flex items-center space-x-3 px-4 py-3 rounded-lg text-gray-700 hover:bg-red-50 hover:text-red-600 transition duration-200">
            
            <i class="fas fa-sign-out-alt"></i>
            <span>Logout</span>
        </button>
    </form>
</li>
                </ul>
            </div>
        </aside>

        <!-- Main Content Area -->
        <main class="content-area md:ml-64 flex-1 p-6">
            @if(session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
                    <span class="block sm:inline">{{ session('success') }}</span>
                </div>
            @endif
            
            @if(session('error'))
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
                    <span class="block sm:inline">{{ session('error') }}</span>
                </div>
            @endif
            
            @yield('content')
        </main>
    </div>
    
    @yield('scripts')
</body>
</html>