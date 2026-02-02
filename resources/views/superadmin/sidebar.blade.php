<!-- Top Navigation Bar -->
<nav class="glassmorphism fixed top-0 left-0 right-0 z-40 py-3 px-6 flex items-center justify-between border-b border-gray-200">
    <div class="flex items-center space-x-4">
        <div class="flex items-center space-x-2">
            <div class="gradient-bg w-8 h-8 rounded-lg flex items-center justify-center">
                <span class="text-white font-bold text-xl">C</span>
            </div>
            <span class="text-xl font-bold text-gray-800">CRM Admin</span>
        </div>
    </div>
    
    <div class="flex items-center space-x-4">
        <a href="{{ route('customers.create') }}" class="bg-gradient-to-r from-blue-600 to-indigo-600 text-white px-4 py-2 rounded-lg hover:from-blue-700 hover:to-indigo-700 transition duration-200 font-medium flex items-center">
            <i class="fas fa-plus mr-2"></i>
            Add New Customer
        </a>
        
        <div class="relative">
            <button id="profileDropdownBtn" class="flex items-center space-x-2 focus:outline-none">
                <div class="w-10 h-10 rounded-full bg-gradient-to-r from-blue-500 to-purple-500"></div>
                <div class="hidden md:block text-left">
                    <p class="text-sm font-medium">{{ Auth::user()->name ?? 'Admin User' }}</p>
                    <p class="text-xs text-gray-500">Super Admin</p>
                </div>
            </button>
        </div>
    </div>
</nav>

<!-- Sidebar Menu -->
<aside class="sidebar hidden md:block w-64 bg-white border-r border-gray-200 fixed left-0 overflow-y-auto">
    <div class="p-6">
        <ul class="space-y-1">
            <li>
                <a href="{{ route('dashboard') }}" class="{{ request()->routeIs('dashboard') ? 'active-menu flex items-center space-x-3 px-4 py-3 rounded-lg bg-blue-50 text-blue-600 font-medium' : 'menu-item flex items-center space-x-3 px-4 py-3 rounded-lg text-gray-700 hover:bg-gray-100 transition duration-200' }}">
                    <i class="fas fa-chart-line"></i>
                    <span>Dashboard</span>
                </a>
            </li>
            <li>
                <a href="{{ route('customers.index') }}" class="{{ request()->routeIs('customers.*') ? 'active-menu flex items-center space-x-3 px-4 py-3 rounded-lg bg-blue-50 text-blue-600 font-medium' : 'menu-item flex items-center space-x-3 px-4 py-3 rounded-lg text-gray-700 hover:bg-gray-100 transition duration-200' }}">
                    <i class="fas fa-users"></i>
                    <span>Customers</span>
                </a>
            </li>
            <li>
                <a href="{{ route('trials.index') }}" class="{{ request()->routeIs('trials.*') ? 'active-menu flex items-center space-x-3 px-4 py-3 rounded-lg bg-blue-50 text-blue-600 font-medium' : 'menu-item flex items-center space-x-3 px-4 py-3 rounded-lg text-gray-700 hover:bg-gray-100 transition duration-200' }}">
                    <i class="fas fa-clock"></i>
                    <span>Trials</span>
                </a>
            </li>
            <li>
                <a href="{{ route('subscriptions.index') }}" class="{{ request()->routeIs('subscriptions.*') ? 'active-menu text-xs flex items-center space-x-3 px-4 py-3 rounded-lg bg-blue-50 text-blue-600  font-medium' : 'menu-item flex items-center space-x-3 px-4 py-3 rounded-lg text-gray-700  hover:bg-gray-100 transition duration-200' }}">
                    <i class="fas fa-id-card"></i>
                    <span class="">Active Subscriptions</span>
                </a>
            </li>
            <li>
                <a href="{{ route('payments.index') }}" class="{{ request()->routeIs('payments.*') ? 'active-menu flex items-center space-x-3 px-4 py-3 rounded-lg bg-blue-50 text-blue-600 font-medium' : 'menu-item flex items-center space-x-3 px-4 py-3 rounded-lg text-gray-700 hover:bg-gray-100 transition duration-200' }}">
                    <i class="fas fa-credit-card"></i>
                    <span>Payments</span>
                </a>
            </li>
            <li>
                <a href="{{ route('invoices.index') }}" class="{{ request()->routeIs('invoices.*') ? 'active-menu flex items-center space-x-3 px-4 py-3 rounded-lg bg-blue-50 text-blue-600 font-medium' : 'menu-item flex items-center space-x-3 px-4 py-3 rounded-lg text-gray-700 hover:bg-gray-100 transition duration-200' }}">
                    <i class="fas fa-file-invoice"></i>
                    <span>Invoices</span>
                </a>
            </li>
            <li>
                <a href="{{ route('revenue.index') }}" class="{{ request()->routeIs('revenue.*') ? 'active-menu flex items-center space-x-3 px-4 py-3 rounded-lg bg-blue-50 text-blue-600 font-medium' : 'menu-item flex items-center space-x-3 px-4 py-3 rounded-lg text-gray-700 hover:bg-gray-100 transition duration-200' }}">
                    <i class="fas fa-chart-bar"></i>
                    <span>Revenue Report</span>
                </a>
            </li>
            <li>
                <a href="{{ route('settings.index') }}" class="{{ request()->routeIs('settings.*') ? 'active-menu flex items-center space-x-3 px-4 py-3 rounded-lg bg-blue-50 text-blue-600 font-medium' : 'menu-item flex items-center space-x-3 px-4 py-3 rounded-lg text-gray-700 hover:bg-gray-100 transition duration-200' }}">
                    <i class="fas fa-cog"></i>
                    <span>Settings</span>
                </a>
            </li>
        </ul>
    </div>
</aside>