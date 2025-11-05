<!-- resources/views/auth/login-register.blade.php -->

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login & Register</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 min-h-screen flex items-center justify-center">
    <div class="bg-white p-8 rounded-lg shadow-md w-full max-w-md">
        <!-- Tabs -->
        <div class="flex mb-6 border-b">
            <button 
                id="login-tab" 
                class="flex-1 py-2 text-center font-medium {{ session('form') !== 'register' ? 'text-blue-600 border-b-2 border-blue-600' : 'text-gray-500' }}"
                onclick="showLogin()"
            >
                Login
            </button>
            {{-- <button 
                id="register-tab" 
                class="flex-1 py-2 text-center font-medium {{ session('form') === 'register' ? 'text-blue-600 border-b-2 border-blue-600' : 'text-gray-500' }}"
                onclick="showRegister()"
            >
                Register
            </button> --}}
        </div>

        <!-- Success Message -->
        @if(session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                {{ session('success') }}
            </div>
        @endif

        <!-- Login Form -->
        <div id="login-form" class="{{ session('form') === 'register' ? 'hidden' : '' }}">
            <h2 class="text-2xl font-bold mb-6 text-center">Login</h2>
            
            <form method="POST" action="{{ route('login') }}">
                @csrf
                
                <div class="mb-4">
                    <label for="login-username" class="block text-gray-700 text-sm font-medium mb-2">
                        Username
                    </label>
                    <input 
                        type="text" 
                        id="login-username" 
                        name="name" 
                        value="{{ old('name') }}"
                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                        required
                    >
                    @error('name')
                        <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror
                </div>

                <div class="mb-6">
                    <label for="login-password" class="block text-gray-700 text-sm font-medium mb-2">
                        Password
                    </label>
                    <input 
                        type="password" 
                        id="login-password" 
                        name="password" 
                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                        required
                    >
                </div>

                <div class="mb-4">
                    <label class="flex items-center">
                        <input type="checkbox" name="remember" class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                        <span class="ml-2 text-sm text-gray-600">Remember me</span>
                    </label>
                </div>

                <button 
                    type="submit" 
                    class="w-full bg-blue-600 text-white py-2 px-4 rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2"
                >
                    Login
                </button>
            </form>
        </div>

        <!-- Register Form -->
        <div id="register-form" class="{{ session('form') === 'register' ? '' : 'hidden' }}">
            <h2 class="text-2xl font-bold mb-6 text-center">Register</h2>
            
            <form method="POST" action="{{ route('register') }}">
                @csrf
                
                <div class="mb-4">
                    <label for="register-username" class="block text-gray-700 text-sm font-medium mb-2">
                        Username
                    </label>
                    <input 
                        type="text" 
                        id="register-username" 
                        name="username" 
                        value="{{ old('username') }}"
                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                        required
                    >
                    @error('username')
                        <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror
                </div>

                <div class="mb-4">
                    <label for="register-email" class="block text-gray-700 text-sm font-medium mb-2">
                        Email
                    </label>
                    <input 
                        type="email" 
                        id="register-email" 
                        name="email" 
                        value="{{ old('email') }}"
                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                        required
                    >
                    @error('email')
                        <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror
                </div>

                <div class="mb-4">
                    <label for="register-password" class="block text-gray-700 text-sm font-medium mb-2">
                        Password
                    </label>
                    <input 
                        type="password" 
                        id="register-password" 
                        name="password" 
                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                        required
                    >
                    @error('password')
                        <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror
                </div>

                <div class="mb-6">
                    <label for="register-password-confirmation" class="block text-gray-700 text-sm font-medium mb-2">
                        Confirm Password
                    </label>
                    <input 
                        type="password" 
                        id="register-password-confirmation" 
                        name="password_confirmation" 
                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                        required
                    >
                </div>

                <button 
                    type="submit" 
                    class="w-full bg-green-600 text-white py-2 px-4 rounded-md hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2"
                >
                    Register
                </button>
            </form>
        </div>
    </div>

    <script>
        function showLogin() {
            document.getElementById('login-form').classList.remove('hidden');
            document.getElementById('register-form').classList.add('hidden');
            document.getElementById('login-tab').classList.add('text-blue-600', 'border-b-2', 'border-blue-600');
            document.getElementById('login-tab').classList.remove('text-gray-500');
            document.getElementById('register-tab').classList.remove('text-blue-600', 'border-b-2', 'border-blue-600');
            document.getElementById('register-tab').classList.add('text-gray-500');
        }

        function showRegister() {
            document.getElementById('register-form').classList.remove('hidden');
            document.getElementById('login-form').classList.add('hidden');
            document.getElementById('register-tab').classList.add('text-blue-600', 'border-b-2', 'border-blue-600');
            document.getElementById('register-tab').classList.remove('text-gray-500');
            document.getElementById('login-tab').classList.remove('text-blue-600', 'border-b-2', 'border-blue-600');
            document.getElementById('login-tab').classList.add('text-gray-500');
        }

        // Show appropriate form based on validation errors
        @if($errors->has('username') || $errors->has('password') || session('form') === 'login')
            showLogin();
        @elseif($errors->has('email') || $errors->has('password_confirmation') || session('form') === 'register')
            showRegister();
        @endif
    </script>
</body>
</html>