<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Start 30-Day Free Trial</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        .slider-container {
            width: 200%;
            display: flex;
            transition: transform 0.5s cubic-bezier(0.4, 0, 0.2, 1);
        }
        .step {
            width: 50%;
            padding: 0 12px;
        }
        .form-slide {
            overflow: hidden;
        }
        .fade-in {
            animation: fadeIn 0.5s ease-out;
        }
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .form-input {
            transition: all 0.2s ease;
        }
        .form-input:focus {
            box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.1);
            border-color: #6366f1;
        }
        .step-indicator {
            transition: all 0.3s ease;
        }
        .input-error {
            border-color: #f87171 !important;
        }
        .error-message {
            color: #ef4444;
            font-size: 0.875rem;
            margin-top: 0.25rem;
            display: none;
        }
    </style>
</head>

<body class="bg-gradient-to-br from-gray-50 to-gray-100 min-h-screen flex items-center justify-center p-4">
    <div class="w-full max-w-2xl fade-in">
        <div class="text-center mb-8">
            <h1 class="text-3xl font-bold text-gray-800 mb-2">Start Your 30-Day Free Trial</h1>
            <p class="text-gray-500 mb-6">No credit card required • Get started in seconds</p>
            
            <!-- Step Indicator -->
            <div class="flex justify-center items-center space-x-4 mb-8">
                <div class="flex items-center step-indicator" id="step1-indicator">
                    <div class="w-10 h-10 rounded-full bg-indigo-600 text-white flex items-center justify-center font-semibold border-2 border-indigo-600">1</div>
                    <div class="ml-3 text-sm font-semibold text-gray-800">Company Details</div>
                </div>
                
                <div class="w-20 h-1 bg-gray-300 rounded-full mx-2">
                    <div class="h-full bg-indigo-600 rounded-full transition-all duration-500" id="progress-bar" style="width: 0%"></div>
                </div>
                
                <div class="flex items-center step-indicator" id="step2-indicator">
                    <div class="w-10 h-10 rounded-full bg-gray-200 text-gray-600 flex items-center justify-center font-semibold border-2 border-gray-300">2</div>
                    <div class="ml-3 text-sm font-medium text-gray-500">Admin Account</div>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-2xl shadow-xl overflow-hidden">
            <div class="p-8">
                {{-- Errors --}}
                @if ($errors->any())
                    <div class="mb-6 bg-red-50 border-l-4 border-red-500 p-4 rounded-r-lg">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <svg class="h-5 w-5 text-red-400" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                                </svg>
                            </div>
                            <div class="ml-3">
                                <h3 class="text-sm font-medium text-red-800">Please correct the following errors:</h3>
                                <div class="mt-2 text-sm text-red-700">
                                    <ul class="list-disc pl-5 space-y-1">
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif

                <form method="POST" action="{{ route('trial.store') }}" id="trialForm">
                    @csrf
                    
                    <div class="form-slide">
                        <div class="slider-container" id="slider">
                            {{-- STEP 1: COMPANY DETAILS --}}
                            <div class="step">
                                <div class="mb-8">
                                    <h3 class="text-2xl font-bold text-gray-800 mb-2">Company Information</h3>
                                    <p class="text-gray-500">Tell us about your company to get started</p>
                                </div>

                                <div class="space-y-6">
                                    <div>
                                        <label class="block text-sm font-semibold text-gray-700 mb-2">Company Name *</label>
                                        <input type="text" name="company_name" value="{{ old('company_name') }}"
                                               class="w-full border border-gray-300 rounded-xl px-4 py-3.5 form-input focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent text-gray-700"
                                               placeholder="Enter your company name" required id="company_name">
                                        <div class="error-message" id="company_name_error">Company name is required</div>
                                    </div>

                                    <div>
                                        <label class="block text-sm font-semibold text-gray-700 mb-2">Company Address *</label>
                                        <textarea name="address" rows="3"
                                                  class="w-full border border-gray-300 rounded-xl px-4 py-3.5 form-input focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent text-gray-700"
                                                  placeholder="Enter company address" required id="address">{{ old('address') }}</textarea>
                                        <div class="error-message" id="address_error">Company address is required</div>
                                    </div>

                                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                                        <div>
                                            <label class="block text-sm font-semibold text-gray-700 mb-2">Company Email *</label>
                                            <input type="email" name="company_email" value="{{ old('company_email') }}"
                                                   class="w-full border border-gray-300 rounded-xl px-4 py-3.5 form-input focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent text-gray-700"
                                                   placeholder="company@example.com" required id="company_email">
                                            <div class="error-message" id="company_email_error">Please enter a valid email address</div>
                                        </div>

                                        <div>
                                            <label class="block text-sm font-semibold text-gray-700 mb-2">Phone Number *</label>
                                            <input type="text" name="phone" value="{{ old('phone') }}"
                                                   class="w-full border border-gray-300 rounded-xl px-4 py-3.5 form-input focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent text-gray-700"
                                                   placeholder="+1 (555) 000-0000" required id="phone">
                                            <div class="error-message" id="phone_error">Phone number is required</div>
                                        </div>
                                    </div>

                                    <div>
                                        <label class="block text-sm font-semibold text-gray-700 mb-2">GSTIN (Optional)</label>
                                        <input type="text" name="gstin" value="{{ old('gstin') }}"
                                               class="w-full border border-gray-300 rounded-xl px-4 py-3.5 form-input focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent text-gray-700"
                                               placeholder="Enter GSTIN number">
                                        <p class="text-xs text-gray-500 mt-2">Only required if you have a GST number</p>
                                    </div>
                                </div>

                                <button type="button"
                                        onclick="validateStep1()"
                                        class="w-full mt-10 bg-gradient-to-r from-indigo-600 to-indigo-700 hover:from-indigo-700 hover:to-indigo-800 text-white py-4 rounded-xl font-bold text-lg shadow-lg hover:shadow-xl transition-all duration-300 flex items-center justify-center group">
                                    Continue to Admin Setup
                                    <svg class="w-6 h-6 ml-3 group-hover:translate-x-2 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                    </svg>
                                </button>
                            </div>

                            {{-- STEP 2: ADMIN DETAILS --}}
                            <div class="step">
                                <div class="mb-8">
                                    <h3 class="text-2xl font-bold text-gray-800 mb-2">Admin Account Setup</h3>
                                    <p class="text-gray-500">Create your administrator credentials</p>
                                </div>

                                <div class="space-y-6">
                                    <div>
                                        <label class="block text-sm font-semibold text-gray-700 mb-2">Your Full Name *</label>
                                        <input type="text" name="name" value="{{ old('name') }}"
                                               class="w-full border border-gray-300 rounded-xl px-4 py-3.5 form-input focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent text-gray-700"
                                               placeholder="Enter your full name" required id="admin_name">
                                        <div class="error-message" id="admin_name_error">Full name is required</div>
                                    </div>

                                    <div>
                                        <label class="block text-sm font-semibold text-gray-700 mb-2">Admin Email *</label>
                                        <input type="email" name="admin_email" value="{{ old('admin_email') }}"
                                               class="w-full border border-gray-300 rounded-xl px-4 py-3.5 form-input focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent text-gray-700"
                                               placeholder="admin@company.com" required id="admin_email">
                                        <div class="error-message" id="admin_email_error">Valid email is required</div>
                                        <p class="text-xs text-gray-500 mt-2">This will be your login email</p>
                                    </div>

                                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                                        <div>
                                            <label class="block text-sm font-semibold text-gray-700 mb-2">Password *</label>
                                            <input type="password" name="password"
                                                   class="w-full border border-gray-300 rounded-xl px-4 py-3.5 form-input focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent text-gray-700"
                                                   placeholder="Create a strong password" required id="password">
                                            <div class="error-message" id="password_error">Password is required</div>
                                        </div>

                                        <div>
                                            <label class="block text-sm font-semibold text-gray-700 mb-2">Confirm Password *</label>
                                            <input type="password" name="password_confirmation"
                                                   class="w-full border border-gray-300 rounded-xl px-4 py-3.5 form-input focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent text-gray-700"
                                                   placeholder="Re-enter your password" required id="password_confirmation">
                                            <div class="error-message" id="password_confirmation_error">Passwords do not match</div>
                                        </div>
                                    </div>
                                    
                                    <div class="bg-blue-50 border-l-4 border-blue-500 p-4 rounded-r-lg mt-4">
                                        <div class="flex">
                                            <svg class="h-5 w-5 text-blue-500 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                            </svg>
                                            <div>
                                                <p class="text-sm text-blue-700 font-medium">Your trial includes:</p>
                                                <ul class="text-xs text-blue-600 mt-1 list-disc pl-5">
                                                    <li>Full access to all features</li>
                                                    <li>30 days free, no credit card required</li>
                                                    <li>Support during trial period</li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="flex gap-4 mt-10">
                                    <button type="button"
                                            onclick="prevStep()"
                                            class="w-1/2 border-2 border-gray-300 hover:border-gray-400 hover:bg-gray-50 text-gray-700 py-4 rounded-xl font-bold text-lg transition-all duration-300 flex items-center justify-center group">
                                        <svg class="w-6 h-6 mr-3 group-hover:-translate-x-2 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                                        </svg>
                                        Go Back
                                    </button>

                                    <button type="submit"
                                            class="w-1/2 bg-gradient-to-r from-green-600 to-green-700 hover:from-green-700 hover:to-green-800 text-white py-4 rounded-xl font-bold text-lg shadow-lg hover:shadow-xl transition-all duration-300 flex items-center justify-center group">
                                        Start Free Trial
                                        <svg class="w-6 h-6 ml-3 group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                                        </svg>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>

                <div class="mt-8 pt-6 border-t border-gray-200">
                    <p class="text-center text-sm text-gray-600">
                        Already have an account?
                        <a href="{{ route('login') }}" class="text-indigo-600 hover:text-indigo-800 font-bold hover:underline ml-1 transition-colors">
                            Sign in here
                        </a>
                    </p>
                </div>
            </div>
        </div>

        <div class="text-center mt-6">
            <p class="text-xs text-gray-500">
                By clicking "Start Free Trial", you agree to our 
                <a href="#" class="text-indigo-600 hover:underline font-medium">Terms of Service</a> and 
                <a href="#" class="text-indigo-600 hover:underline font-medium">Privacy Policy</a>
            </p>
        </div>
    </div>

<script>
    function validateStep1() {
        let isValid = true;
        
        // Get input values
        const companyName = document.getElementById('company_name').value.trim();
        const address = document.getElementById('address').value.trim();
        const companyEmail = document.getElementById('company_email').value.trim();
        const phone = document.getElementById('phone').value.trim();
        
        // Reset all error states
        document.querySelectorAll('.error-message').forEach(el => el.style.display = 'none');
        document.querySelectorAll('.input-error').forEach(el => el.classList.remove('input-error'));
        
        // Validate company name (required)
        if (!companyName) {
            document.getElementById('company_name').classList.add('input-error');
            document.getElementById('company_name_error').textContent = 'Company name is required';
            document.getElementById('company_name_error').style.display = 'block';
            isValid = false;
        }
        
        // Validate company address (required)
        if (!address) {
            document.getElementById('address').classList.add('input-error');
            document.getElementById('address_error').textContent = 'Company address is required';
            document.getElementById('address_error').style.display = 'block';
            isValid = false;
        }
        
        // Validate company email (required)
        if (!companyEmail) {
            document.getElementById('company_email').classList.add('input-error');
            document.getElementById('company_email_error').textContent = 'Company email is required';
            document.getElementById('company_email_error').style.display = 'block';
            isValid = false;
        } else if (!isValidEmail(companyEmail)) {
            document.getElementById('company_email').classList.add('input-error');
            document.getElementById('company_email_error').textContent = 'Please enter a valid email address';
            document.getElementById('company_email_error').style.display = 'block';
            isValid = false;
        }
        
        // Validate phone number (required)
        if (!phone) {
            document.getElementById('phone').classList.add('input-error');
            document.getElementById('phone_error').textContent = 'Phone number is required';
            document.getElementById('phone_error').style.display = 'block';
            isValid = false;
        } else if (!isValidPhone(phone)) {
            document.getElementById('phone').classList.add('input-error');
            document.getElementById('phone_error').textContent = 'Please enter a valid phone number';
            document.getElementById('phone_error').style.display = 'block';
            isValid = false;
        }
        
        // If validation passes, proceed to next step
        if (isValid) {
            nextStep();
        } else {
            // Scroll to first error
            const firstError = document.querySelector('.input-error');
            if (firstError) {
                firstError.scrollIntoView({ behavior: 'smooth', block: 'center' });
                firstError.focus();
            }
        }
        
        return isValid;
    }
    
    function isValidEmail(email) {
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        return emailRegex.test(email);
    }
    
    function isValidPhone(phone) {
        // Basic phone validation - accepts international formats
        const phoneRegex = /^[\d\s\-\+\(\)]{10,}$/;
        return phoneRegex.test(phone);
    }
    
    function nextStep() {
        document.getElementById('slider').style.transform = 'translateX(-50%)';
        document.getElementById('step1-indicator').classList.remove('text-gray-800');
        document.getElementById('step1-indicator').classList.add('text-gray-600');
        document.getElementById('step2-indicator').classList.remove('text-gray-500');
        document.getElementById('step2-indicator').classList.add('text-gray-800');
        
        document.querySelector('#step1-indicator .w-10').classList.remove('bg-indigo-600', 'border-indigo-600');
        document.querySelector('#step1-indicator .w-10').classList.add('bg-gray-200', 'border-gray-300');
        
        document.querySelector('#step2-indicator .w-10').classList.remove('bg-gray-200', 'border-gray-300');
        document.querySelector('#step2-indicator .w-10').classList.add('bg-indigo-600', 'border-indigo-600', 'text-white');
        
        document.getElementById('progress-bar').style.width = '100%';
    }

    function prevStep() {
        document.getElementById('slider').style.transform = 'translateX(0%)';
        document.getElementById('step1-indicator').classList.remove('text-gray-600');
        document.getElementById('step1-indicator').classList.add('text-gray-800');
        document.getElementById('step2-indicator').classList.remove('text-gray-800');
        document.getElementById('step2-indicator').classList.add('text-gray-500');
        
        document.querySelector('#step1-indicator .w-10').classList.remove('bg-gray-200', 'border-gray-300');
        document.querySelector('#step1-indicator .w-10').classList.add('bg-indigo-600', 'border-indigo-600', 'text-white');
        
        document.querySelector('#step2-indicator .w-10').classList.remove('bg-indigo-600', 'border-indigo-600', 'text-white');
        document.querySelector('#step2-indicator .w-10').classList.add('bg-gray-200', 'border-gray-300');
        
        document.getElementById('progress-bar').style.width = '0%';
    }
    
    // Add real-time validation for step 1 fields
    document.getElementById('company_name').addEventListener('blur', function() {
        const name = this.value.trim();
        if (!name) {
            this.classList.add('input-error');
            document.getElementById('company_name_error').textContent = 'Company name is required';
            document.getElementById('company_name_error').style.display = 'block';
        } else {
            this.classList.remove('input-error');
            document.getElementById('company_name_error').style.display = 'none';
        }
    });
    
    document.getElementById('address').addEventListener('blur', function() {
        const address = this.value.trim();
        if (!address) {
            this.classList.add('input-error');
            document.getElementById('address_error').textContent = 'Company address is required';
            document.getElementById('address_error').style.display = 'block';
        } else {
            this.classList.remove('input-error');
            document.getElementById('address_error').style.display = 'none';
        }
    });
    
    document.getElementById('company_email').addEventListener('blur', function() {
        const email = this.value.trim();
        if (!email) {
            this.classList.add('input-error');
            document.getElementById('company_email_error').textContent = 'Company email is required';
            document.getElementById('company_email_error').style.display = 'block';
        } else if (!isValidEmail(email)) {
            this.classList.add('input-error');
            document.getElementById('company_email_error').textContent = 'Please enter a valid email address';
            document.getElementById('company_email_error').style.display = 'block';
        } else {
            this.classList.remove('input-error');
            document.getElementById('company_email_error').style.display = 'none';
        }
    });
    
    document.getElementById('phone').addEventListener('blur', function() {
        const phone = this.value.trim();
        if (!phone) {
            this.classList.add('input-error');
            document.getElementById('phone_error').textContent = 'Phone number is required';
            document.getElementById('phone_error').style.display = 'block';
        } else if (!isValidPhone(phone)) {
            this.classList.add('input-error');
            document.getElementById('phone_error').textContent = 'Please enter a valid phone number';
            document.getElementById('phone_error').style.display = 'block';
        } else {
            this.classList.remove('input-error');
            document.getElementById('phone_error').style.display = 'none';
        }
    });
    
    // Add form submission validation for step 2
    document.querySelector('form').addEventListener('submit', function(e) {
        // Validate step 2 fields
        const adminName = document.getElementById('admin_name').value.trim();
        const adminEmail = document.getElementById('admin_email').value.trim();
        const password = document.getElementById('password').value;
        const passwordConfirm = document.getElementById('password_confirmation').value;
        
        let isValid = true;
        
        // Reset all error states
        document.querySelectorAll('.error-message').forEach(el => el.style.display = 'none');
        document.querySelectorAll('.input-error').forEach(el => el.classList.remove('input-error'));
        
        // Validate admin name
        if (!adminName) {
            document.getElementById('admin_name').classList.add('input-error');
            document.getElementById('admin_name_error').style.display = 'block';
            isValid = false;
        }
        
        // Validate admin email
        if (!adminEmail || !isValidEmail(adminEmail)) {
            document.getElementById('admin_email').classList.add('input-error');
            document.getElementById('admin_email_error').style.display = 'block';
            isValid = false;
        }
        
        // Validate password
        if (!password) {
            document.getElementById('password').classList.add('input-error');
            document.getElementById('password_error').style.display = 'block';
            isValid = false;
        } else if (password.length < 8) {
            document.getElementById('password').classList.add('input-error');
            document.getElementById('password_error').textContent = 'Password must be at least 8 characters long';
            document.getElementById('password_error').style.display = 'block';
            isValid = false;
        }
        
        // Validate password confirmation
        if (password !== passwordConfirm) {
            document.getElementById('password_confirmation').classList.add('input-error');
            document.getElementById('password_confirmation_error').style.display = 'block';
            isValid = false;
        }
        
        // Also validate step 1 fields again (in case user went back and changed them)
        if (!validateStep1()) {
            // If step 1 validation fails, go back to step 1
            e.preventDefault();
            prevStep();
            return false;
        }
        
        if (!isValid) {
            e.preventDefault();
            // Scroll to first error in step 2
            const firstError = document.querySelector('.input-error');
            if (firstError) {
                firstError.scrollIntoView({ behavior: 'smooth', block: 'center' });
                firstError.focus();
            }
            return false;
        }
    });
</script>

</body>
</html>