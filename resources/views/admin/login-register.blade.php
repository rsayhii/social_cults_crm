<!-- resources/views/auth/login.blade.php -->

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login | Social Cults CRM</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body {
            background-color: #f9fafb;
            min-height: 100vh;
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
        }
        
        .card-shadow {
            box-shadow: 0 10px 35px rgba(0, 0, 0, 0.08);
            border: 1px solid #e5e7eb;
        }
        
        .input-focus {
            transition: all 0.3s ease;
        }
        
        .input-focus:focus {
            box-shadow: 0 0 0 3px rgba(79, 70, 229, 0.1);
            border-color: #4f46e5;
        }
        
        .btn-primary {
            background: linear-gradient(135deg, #4f46e5 0%, #4338ca 100%);
            transition: all 0.3s ease;
        }
        
        .btn-primary:hover {
            transform: translateY(-1px);
            box-shadow: 0 8px 20px rgba(79, 70, 229, 0.2);
        }
        
        .btn-primary:active {
            transform: translateY(0);
        }
        
        .logo-container {
            margin: 0 auto 1.5rem;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        
        .company-logo {
            height: 50px;
            width: auto;
        }
        
        .brand-text {
            background: linear-gradient(135deg, #4f46e5 0%, #4338ca 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }
    </style>
</head>
<body class="flex items-center justify-center p-4">
    <div class="bg-white rounded-xl card-shadow w-full max-w-md overflow-hidden">
        <!-- Header Section -->
        <div class="p-8 pb-6">
            <!-- Company Logo -->
            <div class="logo-container">
                <img src="https://socialcults.com/images/client/logo.png" 
                     alt="Social Cults CRM" 
                     class="company-logo">
            </div>
            
            <h1 class="text-2xl font-bold text-center text-gray-800 mb-2">Social Cults CRM</h1>
            <p class="text-gray-600 text-center mb-8">Sign in to access your dashboard</p>
            
            <!-- Success Message -->
            @if(session('success'))
                <div class="mb-6 p-4 bg-green-50 border border-green-200 rounded-lg">
                    <div class="flex items-center">
                        <i class="fas fa-check-circle text-green-500 mr-3"></i>
                        <p class="text-green-700 text-sm">{{ session('success') }}</p>
                    </div>
                </div>
            @endif
            
            <!-- Error Messages -->
            @if($errors->any())
                <div class="mb-6 p-4 bg-red-50 border border-red-200 rounded-lg">
                    <div class="flex items-center">
                        <i class="fas fa-exclamation-circle text-red-500 mr-3"></i>
                        <div>
                            <p class="text-red-700 font-medium text-sm mb-1">Login failed</p>
                            <p class="text-red-600 text-sm">{{ $errors->first() }}</p>
                        </div>
                    </div>
                </div>
            @endif
            
            <!-- Login Form -->
            <form method="POST" action="{{ route('login') }}">
                @csrf
                
                <!-- Username Field -->
                <div class="mb-6">
                    <label for="email" class="block text-gray-700 text-sm font-medium mb-2">
                        Email
                    </label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i class="fas fa-user text-gray-400"></i>
                        </div>
                        <input 
                            type="text" 
                            id="email" 
                            name="email" 
                            value="{{ old('email') }}"
                            class="w-full pl-10 pr-4 py-3 border border-gray-300 rounded-lg input-focus focus:outline-none"
                            placeholder="Enter your email"
                            required
                            autofocus
                        >
                    </div>
                    @error('email')
                        <p class="mt-2 text-sm text-red-600 flex items-center">
                            <i class="fas fa-exclamation-circle mr-2"></i> {{ $message }}
                        </p>
                    @enderror
                </div>

                <!-- Password Field -->
                <div class="mb-6">
                    <div class="flex justify-between items-center mb-2">
                        <label for="password" class="block text-gray-700 text-sm font-medium">
                            Password
                        </label>
                        @if (Route::has('password.request'))
                        <a href="{{ route('password.request') }}" class="text-sm text-indigo-600 hover:text-indigo-800 font-medium transition-colors">
                            Forgot password?
                        </a>
                        @endif
                    </div>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i class="fas fa-lock text-gray-400"></i>
                        </div>
                        <input 
                            type="password" 
                            id="password" 
                            name="password" 
                            class="w-full pl-10 pr-10 py-3 border border-gray-300 rounded-lg input-focus focus:outline-none"
                            placeholder="Enter your password"
                            required
                        >
                        <div class="absolute inset-y-0 right-0 pr-3 flex items-center">
                            <button type="button" onclick="togglePassword()" class="text-gray-400 hover:text-gray-600 focus:outline-none">
                                <i class="fas fa-eye" id="toggle-icon"></i>
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Remember Me -->
                <div class="mb-8">
                    <label class="flex items-center cursor-pointer">
                        <div class="relative">
                            <input type="checkbox" name="remember" class="sr-only" id="remember">
                            <div class="w-5 h-5 border border-gray-300 rounded flex items-center justify-center checkbox-bg">
                                <i class="fas fa-check text-white text-xs hidden"></i>
                            </div>
                        </div>
                        <span class="ml-3 text-gray-700 text-sm">Remember me</span>
                    </label>
                </div>

                <!-- Submit Button -->
                <button type="submit" class="w-full btn-primary text-white font-medium py-3 rounded-lg mb-6">
                    <i class="fas fa-sign-in-alt mr-2"></i>Sign In
                </button>
            </form>

            
        </div>

        <!-- Footer -->
        <div class="bg-gray-50 px-8 py-5 border-t border-gray-200">
            <div class="text-center">
                <p class="text-gray-600 text-sm">
                    © {{ date('Y') }} Social Cults CRM. All rights reserved.
                </p>
                <p class="text-gray-500 text-xs mt-2">
                    <a href="https://socialcults.com" class="text-indigo-600 hover:text-indigo-800 font-medium" target="_blank">
                        Visit our website
                    </a>
                    <span class="mx-2">•</span>
                    <a href="mailto:support@socialcults.com" class="text-indigo-600 hover:text-indigo-800 font-medium">
                        Contact Support
                    </a>
                </p>
                <div class="mt-3 pt-3 border-t border-gray-200">
                    <p class="text-gray-400 text-xs">
                        v1.0.0 • Secure Access
                    </p>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Toggle password visibility
        function togglePassword() {
            const passwordInput = document.getElementById('password');
            const toggleIcon = document.getElementById('toggle-icon');
            
            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                toggleIcon.classList.remove('fa-eye');
                toggleIcon.classList.add('fa-eye-slash');
            } else {
                passwordInput.type = 'password';
                toggleIcon.classList.remove('fa-eye-slash');
                toggleIcon.classList.add('fa-eye');
            }
        }
        
        // Custom checkbox styling
        document.addEventListener('DOMContentLoaded', function() {
            const rememberCheckbox = document.getElementById('remember');
            const checkboxBg = document.querySelector('.checkbox-bg');
            const checkIcon = checkboxBg.querySelector('i');
            
            // Update checkbox UI
            function updateCheckbox() {
                if (rememberCheckbox.checked) {
                    checkboxBg.style.backgroundColor = '#4f46e5';
                    checkboxBg.style.borderColor = '#4f46e5';
                    checkIcon.classList.remove('hidden');
                } else {
                    checkboxBg.style.backgroundColor = 'transparent';
                    checkboxBg.style.borderColor = '#d1d5db';
                    checkIcon.classList.add('hidden');
                }
            }
            
            // Initialize checkbox state
            updateCheckbox();
            
            // Toggle on click
            checkboxBg.parentElement.addEventListener('click', function(e) {
                e.preventDefault();
                rememberCheckbox.checked = !rememberCheckbox.checked;
                updateCheckbox();
            });
            
            // Add animation to form inputs on focus
            const inputs = document.querySelectorAll('input[type="text"], input[type="password"]');
            inputs.forEach(input => {
                input.addEventListener('focus', function() {
                    this.parentElement.classList.add('ring-1', 'ring-indigo-300');
                });
                
                input.addEventListener('blur', function() {
                    this.parentElement.classList.remove('ring-1', 'ring-indigo-300');
                });
            });
            
            // Add subtle animation to the card on load
            const card = document.querySelector('.card-shadow');
            card.style.opacity = '0';
            card.style.transform = 'translateY(8px)';
            
            setTimeout(() => {
                card.style.transition = 'opacity 0.4s ease, transform 0.4s ease';
                card.style.opacity = '1';
                card.style.transform = 'translateY(0)';
            }, 50);
        });
        
        // Handle Enter key submission
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Enter' && !e.target.matches('button')) {
                const submitBtn = document.querySelector('button[type="submit"]');
                if (submitBtn) {
                    submitBtn.click();
                }
            }
        });
    </script>
</body>
</html>