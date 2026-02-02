<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Social Cults CRM - Transform Your Customer Relationships</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: '#3b82f6',
                        secondary: '#1e40af',
                        accent: '#10b981',
                    },
                    fontFamily: {
                        sans: ['Inter', 'sans-serif'],
                    },
                }
            }
        }
    </script>
    <style>
        .gradient-bg {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }
        .feature-card:hover {
            transform: translateY(-5px);
            transition: all 0.3s ease;
        }
        .pricing-card:hover {
            transform: scale(1.03);
            transition: all 0.3s ease;
        }


 
        .testimonial-shadow {
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.08);
        }
        
        .carousel-container {
            overflow: hidden;
            position: relative;
        }
        
        .carousel-track {
            display: flex;
            transition: transform 0.5s ease-in-out;
        }
        
        .carousel-slide {
            flex: 0 0 100%;
            padding: 0 15px;
        }
        
        @media (min-width: 768px) {
            .carousel-slide {
                flex: 0 0 33.333%;
            }
        }
        
        .carousel-indicators {
            display: flex;
            justify-content: center;
            margin-top: 30px;
        }
        
        .carousel-indicator {
            width: 12px;
            height: 12px;
            border-radius: 50%;
            background-color: #cbd5e1;
            margin: 0 6px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }
        
        .carousel-indicator.active {
            background-color: #4f46e5;
        }
        
        .carousel-btn {
            position: absolute;
            top: 50%;
            transform: translateY(-50%);
            background-color: white;
            border: none;
            width: 50px;
            height: 50px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            cursor: pointer;
            z-index: 10;
            transition: all 0.3s ease;
        }
        
        .carousel-btn:hover {
            background-color: #4f46e5;
            color: white;
        }
        
        .carousel-btn.prev {
            left: 10px;
        }
        
        .carousel-btn.next {
            right: 10px;
        }
        
        .hover-lift {
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }
        
        .hover-lift:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 40px rgba(0, 0, 0, 0.12);
        }

            
        .dashboard-shadow {
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.15);
        }
        
        .floating {
            animation: floating 8s ease-in-out infinite;
        }
        
        @keyframes floating {
            0% { transform: translateY(0px); }
            50% { transform: translateY(-15px); }
            100% { transform: translateY(0px); }
        }
        
        .hover-lift {
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }
        
        .hover-lift:hover {
            transform: translateY(-5px);
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
        }
        
        .gradient-text {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }
        
        .feature-card {
            transition: all 0.3s ease;
        }
        
        .feature-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
        }
        
        .feature-icon {
            width: 60px;
            height: 60px;
            border-radius: 16px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 20px;
        }


    </style>
</head>
<body class="font-sans bg-gray-50">
    <!-- Navigation -->
<nav class="bg-white/95 backdrop-blur-md shadow-lg sticky top-0 z-50 border-b border-gray-100">
    <div class="container mx-auto px-4">
        <div class="flex justify-between items-center py-3">
           <!-- Logo with Modern Design -->
<div class="flex items-center space-x-3 group cursor-pointer">
    <div class="relative">
        <!-- Logo Image Container -->
        <div class="w-24 h-12 bg-white  flex items-center justify-center group-hover:rotate-12 transition-all duration-500 overflow-hidden"
        >
            <img
                src="https://socialcults.com/images/client/logo.png"
                alt="Social Cults Logo"
                class="w-24 h-12 object-contain"
            />
        </div>

       
    </div>

    <div>
        <h1 class="text-2xl font-bold bg-gradient-to-r from-blue-600 to-purple-600 bg-clip-text text-transparent">
            Social Cults CRM
        </h1>
        <p class="text-xs text-gray-500 -mt-1 font-medium">
            Community-Driven Platform
        </p>
    </div>
</div>


            <!-- Desktop Navigation with Modern Effects -->
            <div class="hidden lg:flex items-center space-x-1">
                <a href="#features" class="nav-item group relative px-5 py-3 text-gray-700 hover:text-blue-600 font-medium transition-all duration-300">
                    <span class="relative z-10 flex items-center">
                        {{-- <i class="fas fa-star text-yellow-400 mr-2 text-sm group-hover:animate-pulse"></i> --}}
                        Features
                    </span>
                    <div class="nav-highlight"></div>
                </a>
                
                <a href="#dashboard" class="nav-item group relative px-5 py-3 text-gray-700 hover:text-blue-600 font-medium transition-all duration-300">
                    <span class="relative z-10 flex items-center">
                        Dashboard
                    </span>
                    <div class="nav-highlight"></div>
                </a>
                
                <a href="#mobile" class="nav-item group relative px-5 py-3 text-gray-700 hover:text-blue-600 font-medium transition-all duration-300">
                    <span class="relative z-10 flex items-center">
                        Mobile
                    </span>
                    <div class="nav-highlight"></div>
                </a>
                
                <a href="#pricing" class="nav-item group relative px-5 py-3 text-gray-700 hover:text-blue-600 font-medium transition-all duration-300">
                    <span class="relative z-10 flex items-center">
                        Pricing
                    </span>
                    <div class="nav-highlight"></div>
                </a>
                
                <a href="#testimonials" class="nav-item group relative px-5 py-3 text-gray-700 hover:text-blue-600 font-medium transition-all duration-300">
                    <span class="relative z-10 flex items-center">
                        Testimonials
                    </span>
                    <div class="nav-highlight"></div>
                </a>
            </div>

            <!-- CTA Buttons with Modern Design -->
            <div class="hidden lg:flex items-center space-x-4">
                <a href="{{route('login.show')}}" class="relative px-5 py-2.5 text-gray-700 font-medium group overflow-hidden rounded-lg transition-all duration-300 hover:text-blue-600">
                    <span class="relative z-10 flex items-center">
                        <i class="fas fa-sign-in-alt mr-2"></i>
                        Login
                    </span>
                    <div class="absolute inset-0 bg-gray-100 transform -translate-x-full group-hover:translate-x-0 transition-transform duration-300"></div>
                </a>
                
              
            </div>

            <!-- Modern Mobile Menu Button -->
            <div class="lg:hidden flex items-center space-x-3">
                <a href="{{route('login.show')}}" class="text-gray-600 hover:text-blue-600 p-2 rounded-lg hover:bg-gray-100 transition-colors">
                    <i class="fas fa-sign-in-alt"></i>
                </a>
                <button id="menu-toggle" class="menu-toggle-btn w-12 h-12 rounded-xl bg-gradient-to-r from-blue-50 to-purple-50 flex flex-col items-center justify-center space-y-1.5 group focus:outline-none transition-all duration-300 hover:shadow-lg">
                    <span class="menu-line w-6 h-0.5 bg-gray-700 group-hover:bg-blue-600 transition-all duration-300"></span>
                    <span class="menu-line w-6 h-0.5 bg-gray-700 group-hover:bg-purple-600 transition-all duration-300"></span>
                    <span class="menu-line w-6 h-0.5 bg-gray-700 group-hover:bg-pink-600 transition-all duration-300"></span>
                </button>
            </div>
        </div>

        <!-- Premium Mobile Menu -->
        <div id="mobile-menu" class="lg:hidden hidden absolute left-0 right-0 top-full bg-white/98 backdrop-blur-lg border-t border-gray-100 shadow-2xl transform origin-top transition-all duration-300 scale-y-0 opacity-0">
            <div class="container mx-auto px-4 py-6">
                <div class="space-y-1">
                    <a href="#features" class="mobile-nav-item group flex items-center px-4 py-4 text-gray-700 hover:text-blue-600 rounded-xl hover:bg-gradient-to-r from-blue-50 to-white transition-all duration-300 transform hover:translate-x-2">
                        <div class="w-10 h-10 bg-gradient-to-br from-yellow-50 to-yellow-100 rounded-lg flex items-center justify-center mr-4 group-hover:scale-110 transition-transform duration-300">
                            <i class="fas fa-star text-yellow-500"></i>
                        </div>
                        <div class="flex-1">
                            <span class="font-semibold text-lg">Features</span>
                            <p class="text-xs text-gray-500">Explore our platform</p>
                        </div>
                        <i class="fas fa-chevron-right text-gray-400 group-hover:text-blue-500 transition-colors"></i>
                    </a>
                    
                    <a href="#dashboard" class="mobile-nav-item group flex items-center px-4 py-4 text-gray-700 hover:text-green-600 rounded-xl hover:bg-gradient-to-r from-green-50 to-white transition-all duration-300 transform hover:translate-x-2">
                        <div class="w-10 h-10 bg-gradient-to-br from-green-50 to-green-100 rounded-lg flex items-center justify-center mr-4 group-hover:scale-110 transition-transform duration-300">
                            <i class="fas fa-chart-line text-green-500"></i>
                        </div>
                        <div class="flex-1">
                            <span class="font-semibold text-lg">Dashboard</span>
                            <p class="text-xs text-gray-500">View analytics</p>
                        </div>
                        <i class="fas fa-chevron-right text-gray-400 group-hover:text-green-500 transition-colors"></i>
                    </a>
                    
                    <a href="#mobile" class="mobile-nav-item group flex items-center px-4 py-4 text-gray-700 hover:text-purple-600 rounded-xl hover:bg-gradient-to-r from-purple-50 to-white transition-all duration-300 transform hover:translate-x-2">
                        <div class="w-10 h-10 bg-gradient-to-br from-purple-50 to-purple-100 rounded-lg flex items-center justify-center mr-4 group-hover:scale-110 transition-transform duration-300">
                            <i class="fas fa-mobile-alt text-purple-500"></i>
                        </div>
                        <div class="flex-1">
                            <span class="font-semibold text-lg">Mobile App</span>
                            <p class="text-xs text-gray-500">On-the-go access</p>
                        </div>
                        <i class="fas fa-chevron-right text-gray-400 group-hover:text-purple-500 transition-colors"></i>
                    </a>
                    
                    <a href="#pricing" class="mobile-nav-item group flex items-center px-4 py-4 text-gray-700 hover:text-orange-600 rounded-xl hover:bg-gradient-to-r from-orange-50 to-white transition-all duration-300 transform hover:translate-x-2">
                        <div class="w-10 h-10 bg-gradient-to-br from-orange-50 to-orange-100 rounded-lg flex items-center justify-center mr-4 group-hover:scale-110 transition-transform duration-300">
                            <i class="fas fa-tag text-orange-500"></i>
                        </div>
                        <div class="flex-1">
                            <span class="font-semibold text-lg">Pricing</span>
                            <p class="text-xs text-gray-500">Choose your plan</p>
                        </div>
                        <i class="fas fa-chevron-right text-gray-400 group-hover:text-orange-500 transition-colors"></i>
                    </a>
                    
                    <a href="#testimonials" class="mobile-nav-item group flex items-center px-4 py-4 text-gray-700 hover:text-pink-600 rounded-xl hover:bg-gradient-to-r from-pink-50 to-white transition-all duration-300 transform hover:translate-x-2">
                        <div class="w-10 h-10 bg-gradient-to-br from-pink-50 to-pink-100 rounded-lg flex items-center justify-center mr-4 group-hover:scale-110 transition-transform duration-300">
                            <i class="fas fa-comments text-pink-500"></i>
                        </div>
                        <div class="flex-1">
                            <span class="font-semibold text-lg">Testimonials</span>
                            <p class="text-xs text-gray-500">Client success stories</p>
                        </div>
                        <i class="fas fa-chevron-right text-gray-400 group-hover:text-pink-500 transition-colors"></i>
                    </a>
                    
                    <a href="{{route('login.show')}}" class="mobile-nav-item group flex items-center px-4 py-4 text-gray-700 hover:text-blue-600 rounded-xl hover:bg-gradient-to-r from-blue-50 to-white transition-all duration-300 transform hover:translate-x-2">
                        <div class="w-10 h-10 bg-gradient-to-br from-blue-50 to-blue-100 rounded-lg flex items-center justify-center mr-4 group-hover:scale-110 transition-transform duration-300">
                            <i class="fas fa-sign-in-alt text-blue-500"></i>
                        </div>
                        <div class="flex-1">
                            <span class="font-semibold text-lg">Login</span>
                            <p class="text-xs text-gray-500">Access your account</p>
                        </div>
                        <i class="fas fa-chevron-right text-gray-400 group-hover:text-blue-500 transition-colors"></i>
                    </a>
                </div>

                <!-- Mobile CTA -->
                <div class="mt-8 pt-6 border-t border-gray-200">
                    <a href="#pricing" class="block w-full bg-gradient-to-r from-blue-500 to-purple-600 hover:from-blue-600 hover:to-purple-700 text-white font-bold py-4 px-6 rounded-xl shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 transition-all duration-300 text-center">
                        <div class="flex items-center justify-center">
                            <i class="fas fa-rocket mr-3"></i>
                            <span>Start Now</span>
                            <i class="fas fa-arrow-right ml-3"></i>
                        </div>
                    </a>
                    
                    <!-- Social Links in Mobile Menu -->
                    <div class="mt-8">
                        <p class="text-sm text-gray-500 mb-4 text-center">Follow Social Cults</p>
                        <div class="flex justify-center space-x-4">
                            <a href="#" class="social-icon w-10 h-10 bg-blue-100 hover:bg-blue-500 text-blue-500 hover:text-white rounded-full flex items-center justify-center transition-all duration-300 transform hover:-translate-y-1">
                                <i class="fab fa-twitter"></i>
                            </a>
                            <a href="#" class="social-icon w-10 h-10 bg-purple-100 hover:bg-purple-500 text-purple-500 hover:text-white rounded-full flex items-center justify-center transition-all duration-300 transform hover:-translate-y-1">
                                <i class="fab fa-linkedin-in"></i>
                            </a>
                            <a href="#" class="social-icon w-10 h-10 bg-pink-100 hover:bg-pink-500 text-pink-500 hover:text-white rounded-full flex items-center justify-center transition-all duration-300 transform hover:-translate-y-1">
                                <i class="fab fa-instagram"></i>
                            </a>
                            <a href="#" class="social-icon w-10 h-10 bg-red-100 hover:bg-red-500 text-red-500 hover:text-white rounded-full flex items-center justify-center transition-all duration-300 transform hover:-translate-y-1">
                                <i class="fab fa-youtube"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</nav>

<!-- Optional: Notification Badge (Can be dynamic) -->
<div class="bg-gradient-to-r from-green-500 to-emerald-600 text-white py-2 text-center text-sm font-medium hidden" id="notification-bar">
    <div class="container mx-auto px-4 flex items-center justify-center">
        <i class="fas fa-bell animate-pulse mr-3"></i>
        <span>🎉 Special Offer: Get 30% off on annual plans! Limited time offer.</span>
        <a href="#pricing" class="ml-4 px-3 py-1 bg-white/20 hover:bg-white/30 rounded-lg transition-colors">Claim Now</a>
    </div>
</div>

<style>
/* Custom Animations and Styles */
.nav-highlight {
    position: absolute;
    bottom: 0;
    left: 50%;
    width: 0;
    height: 3px;
    background: linear-gradient(to right, #3b82f6, #8b5cf6);
    border-radius: 3px 3px 0 0;
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    transform: translateX(-50%);
}

.nav-item:hover .nav-highlight {
    width: 80%;
}

/* Active Navigation State */
.nav-item.active .nav-highlight {
    width: 80%;
}

.nav-item.active {
    color: #2563eb;
    font-weight: 600;
}

/* Mobile Menu Animation */
.mobile-menu-open {
    transform: scaleY(1);
    opacity: 1;
}

/* Hamburger Animation */
.menu-toggle-btn.active .menu-line:nth-child(1) {
    transform: rotate(45deg) translateY(8px);
}

.menu-toggle-btn.active .menu-line:nth-child(2) {
    opacity: 0;
}

.menu-toggle-btn.active .menu-line:nth-child(3) {
    transform: rotate(-45deg) translateY(-8px);
}

/* Gradient Text Animation */
@keyframes gradientShift {
    0%, 100% {
        background-position: 0% 50%;
    }
    50% {
        background-position: 100% 50%;
    }
}

.bg-gradient-to-r {
    background-size: 200% 200%;
    animation: gradientShift 5s ease infinite;
}

/* Floating Animation */
@keyframes float {
    0%, 100% {
        transform: translateY(0px);
    }
    50% {
        transform: translateY(-3px);
    }
}

.hover-lift:hover {
    animation: float 0.5s ease-in-out;
}

/* Social Icons Hover Effect */
.social-icon:hover {
    box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
}

/* Smooth Scrolling */
html {
    scroll-behavior: smooth;
}

/* Custom Scrollbar for Mobile Menu */
#mobile-menu {
    max-height: 85vh;
    overflow-y: auto;
}

#mobile-menu::-webkit-scrollbar {
    width: 4px;
}

#mobile-menu::-webkit-scrollbar-track {
    background: #f1f1f1;
    border-radius: 10px;
}

#mobile-menu::-webkit-scrollbar-thumb {
    background: linear-gradient(to bottom, #3b82f6, #8b5cf6);
    border-radius: 10px;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const menuToggle = document.getElementById('menu-toggle');
    const mobileMenu = document.getElementById('mobile-menu');
    const menuLines = document.querySelectorAll('.menu-line');
    
    // Mobile Menu Toggle
    menuToggle.addEventListener('click', function(e) {
        e.stopPropagation();
        
        // Toggle mobile menu visibility
        mobileMenu.classList.toggle('hidden');
        mobileMenu.classList.toggle('scale-y-0');
        mobileMenu.classList.toggle('opacity-0');
        
        // Toggle hamburger animation
        menuToggle.classList.toggle('active');
        menuLines.forEach(line => {
            if (menuToggle.classList.contains('active')) {
                line.style.transform = 'rotate(45deg)';
                line.style.backgroundColor = '#3b82f6';
            } else {
                line.style.transform = 'rotate(0)';
                line.style.backgroundColor = '';
            }
        });
    });
    
    // Close mobile menu when clicking outside
    document.addEventListener('click', function(e) {
        if (!mobileMenu.contains(e.target) && !menuToggle.contains(e.target) && !mobileMenu.classList.contains('hidden')) {
            mobileMenu.classList.add('hidden', 'scale-y-0', 'opacity-0');
            menuToggle.classList.remove('active');
            menuLines.forEach(line => {
                line.style.transform = 'rotate(0)';
                line.style.backgroundColor = '';
            });
        }
    });
    
    // Close mobile menu when clicking a link
    const mobileLinks = document.querySelectorAll('.mobile-nav-item');
    mobileLinks.forEach(link => {
        link.addEventListener('click', function() {
            mobileMenu.classList.add('hidden', 'scale-y-0', 'opacity-0');
            menuToggle.classList.remove('active');
            menuLines.forEach(line => {
                line.style.transform = 'rotate(0)';
                line.style.backgroundColor = '';
            });
        });
    });
    
    // Highlight active navigation based on scroll position
    const sections = document.querySelectorAll('section[id]');
    const navItems = document.querySelectorAll('.nav-item');
    
    function highlightNav() {
        let scrollY = window.pageYOffset;
        
        sections.forEach(section => {
            const sectionHeight = section.offsetHeight;
            const sectionTop = section.offsetTop - 100;
            const sectionId = section.getAttribute('id');
            
            if (scrollY > sectionTop && scrollY <= sectionTop + sectionHeight) {
                navItems.forEach(item => {
                    item.classList.remove('active');
                    if (item.getAttribute('href') === `#${sectionId}`) {
                        item.classList.add('active');
                    }
                });
            }
        });
    }
    
    window.addEventListener('scroll', highlightNav);
    
    // Optional: Show notification bar
    setTimeout(() => {
        const notificationBar = document.getElementById('notification-bar');
        if (notificationBar) {
            notificationBar.classList.remove('hidden');
            notificationBar.classList.add('animate-slideDown');
            
            // Auto-hide after 10 seconds
            setTimeout(() => {
                notificationBar.classList.add('animate-slideUp');
                setTimeout(() => {
                    notificationBar.classList.add('hidden');
                }, 500);
            }, 10000);
        }
    }, 2000);
    
    // Smooth scroll for all anchor links
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function(e) {
            const targetId = this.getAttribute('href');
            if (targetId === '#') return;
            
            const targetElement = document.querySelector(targetId);
            if (targetElement) {
                e.preventDefault();
                window.scrollTo({
                    top: targetElement.offsetTop - 80,
                    behavior: 'smooth'
                });
            }
        });
    });
});

// Animation for slide down/up
const style = document.createElement('style');
style.textContent = `
    @keyframes slideDown {
        from {
            transform: translateY(-100%);
            opacity: 0;
        }
        to {
            transform: translateY(0);
            opacity: 1;
        }
    }
    
    @keyframes slideUp {
        from {
            transform: translateY(0);
            opacity: 1;
        }
        to {
            transform: translateY(-100%);
            opacity: 0;
        }
    }
    
    .animate-slideDown {
        animation: slideDown 0.5s ease-out;
    }
    
    .animate-slideUp {
        animation: slideUp 0.5s ease-in;
    }
`;
document.head.appendChild(style);
</script>
    <!-- Hero Section -->

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Professional CRM Platform</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap');
        
        body {
            font-family: 'Inter', sans-serif;
        }
        
        .gradient-bg {
            background: linear-gradient(135deg, #f0f7ff 0%, #e6f0ff 100%);
        }
        
        .card-shadow {
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.08);
        }
        
        .floating-card {
            animation: float 6s ease-in-out infinite;
        }
        
        @keyframes float {
            0% { transform: translateY(0px); }
            50% { transform: translateY(-15px); }
            100% { transform: translateY(0px); }
        }
        
        .chart-container {
            background: linear-gradient(135deg, #1e3a8a 0%, #3b82f6 100%);
        }
    </style>
</head>
<body class="bg-white">
    <section class="gradient-bg py-20">
        <div class="container mx-auto px-4">
            <div class="flex flex-col lg:flex-row items-center">
                <!-- Text Content -->
                <div class="lg:w-1/2 mb-12 lg:mb-0 lg:pr-10">
                    <div class="mb-4">
                        <span class="bg-blue-100 text-blue-700 px-3 py-1 rounded-full text-sm font-medium">Enterprise-Grade CRM</span>
                    </div>
                    <h1 class="text-4xl md:text-5xl font-bold mb-6 text-gray-900 leading-tight">Transform Customer Relationships with Data-Driven Insights</h1>
                    <p class="text-xl mb-8 text-gray-600 leading-relaxed">Unify your sales, marketing, and customer service with our intelligent CRM platform designed to boost productivity and drive growth.</p>
                    
                    <div class="mb-8 flex flex-wrap gap-3">
                        <div class="flex items-center bg-white px-4 py-2 rounded-lg shadow-sm border border-gray-100">
                            <svg class="w-5 h-5 text-green-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            <span class="text-gray-700 text-sm font-medium">Increase sales by 42%</span>
                        </div>
                        <div class="flex items-center bg-white px-4 py-2 rounded-lg shadow-sm border border-gray-100">
                            <svg class="w-5 h-5 text-green-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            <span class="text-gray-700 text-sm font-medium">Reduce admin work by 60%</span>
                        </div>
                        <div class="flex items-center bg-white px-4 py-2 rounded-lg shadow-sm border border-gray-100">
                            <svg class="w-5 h-5 text-green-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            <span class="text-gray-700 text-sm font-medium">Improve customer satisfaction</span>
                        </div>
                    </div>
                    
                    <div class="flex flex-col sm:flex-row space-y-4 sm:space-y-0 sm:space-x-4">
                        <a href="#pricing" class="bg-blue-600 hover:bg-blue-700 text-white px-8 py-4 rounded-lg font-bold text-center transition duration-300 shadow-lg hover:shadow-xl flex items-center justify-center">
                            <span>Start Now</span>
                            <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"></path>
                            </svg>
                        </a>
                        <a href="#" class="border border-gray-300 hover:bg-gray-50 text-gray-700 px-8 py-4 rounded-lg font-bold text-center transition duration-300 flex items-center justify-center">
                            <svg class="w-5 h-5 mr-2 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            Watch Demo
                        </a>
                    </div>
                    
                    <div class="mt-8 flex items-center text-sm text-gray-500">
                        <svg class="w-4 h-4 mr-1 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                        </svg>
                       Join Our CRM Now — Start Managing Smarter
                    </div>
                </div>
                
                <!-- Dashboard with Charts -->
                <div class="lg:w-1/2 relative">
                    <div class="relative rounded-2xl card-shadow overflow-hidden bg-white transform hover:scale-[1.01] transition duration-500">
                        <!-- Dashboard Header -->
                        <div class="bg-gray-800 p-4 flex justify-between items-center">
                            <div class="flex space-x-2">
                                <div class="w-3 h-3 rounded-full bg-red-500"></div>
                                <div class="w-3 h-3 rounded-full bg-yellow-500"></div>
                                <div class="w-3 h-3 rounded-full bg-green-500"></div>
                            </div>
                            <div class="text-white text-sm font-medium">CRM Dashboard</div>
                            <div class="flex space-x-2">
                                <div class="w-6 h-6 rounded-full bg-gray-700 flex items-center justify-center">
                                    <i class="fas fa-bell text-xs text-gray-300"></i>
                                </div>
                                <div class="w-6 h-6 rounded-full bg-blue-500 flex items-center justify-center">
                                    <i class="fas fa-user text-xs text-white"></i>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Dashboard Content -->
                        <div class="p-6">
                            <!-- Metrics Row -->
                            <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
                                <div class="bg-blue-50 p-3 rounded-lg">
                                    <div class="text-xs text-blue-600 font-medium">Leads</div>
                                    <div class="text-xl font-bold text-gray-800">1,248</div>
                                    <div class="text-xs text-green-500 flex items-center">
                                        <i class="fas fa-arrow-up mr-1"></i> 12%
                                    </div>
                                </div>
                                <div class="bg-blue-50 p-3 rounded-lg">
                                    <div class="text-xs text-blue-600 font-medium">Conversion</div>
                                    <div class="text-xl font-bold text-gray-800">24%</div>
                                    <div class="text-xs text-green-500 flex items-center">
                                        <i class="fas fa-arrow-up mr-1"></i> 5%
                                    </div>
                                </div>
                                <div class="bg-blue-50 p-3 rounded-lg">
                                    <div class="text-xs text-blue-600 font-medium">Revenue</div>
                                    <div class="text-xl font-bold text-gray-800">₹42.8K</div>
                                    <div class="text-xs text-green-500 flex items-center">
                                        <i class="fas fa-arrow-up mr-1"></i> 8%
                                    </div>
                                </div>
                                <div class="bg-blue-50 p-3 rounded-lg">
                                    <div class="text-xs text-blue-600 font-medium">Satisfaction</div>
                                    <div class="text-xl font-bold text-gray-800">94%</div>
                                    <div class="text-xs text-green-500 flex items-center">
                                        <i class="fas fa-arrow-up mr-1"></i> 3%
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Charts Row -->
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <!-- Sales Chart -->
                                <div class="bg-white p-4 rounded-lg border border-gray-100">
                                    <div class="flex justify-between items-center mb-4">
                                        <h3 class="text-sm font-semibold text-gray-700">Sales Performance</h3>
                                        <span class="text-xs text-gray-500">Last 30 days</span>
                                    </div>
                                    <div class="h-40">
                                        <canvas id="salesChart"></canvas>
                                    </div>
                                </div>
                                
                                <!-- Leads Chart -->
                                <div class="bg-white p-4 rounded-lg border border-gray-100">
                                    <div class="flex justify-between items-center mb-4">
                                        <h3 class="text-sm font-semibold text-gray-700">Lead Sources</h3>
                                        <span class="text-xs text-gray-500">This month</span>
                                    </div>
                                    <div class="h-40">
                                        <canvas id="leadsChart"></canvas>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Activity Row -->
                            <div class="mt-6">
                                <h3 class="text-sm font-semibold text-gray-700 mb-3">Recent Activity</h3>
                                <div class="space-y-3">
                                    <div class="flex items-center">
                                        <div class="w-8 h-8 rounded-full bg-blue-100 flex items-center justify-center mr-3">
                                            <i class="fas fa-phone text-blue-600 text-xs"></i>
                                        </div>
                                        <div class="flex-1">
                                            <p class="text-xs text-gray-700">Call with Sarah Johnson</p>
                                            <p class="text-xs text-gray-500">10:30 AM • Follow-up scheduled</p>
                                        </div>
                                        <span class="text-xs text-green-500 font-medium">Completed</span>
                                    </div>
                                    <div class="flex items-center">
                                        <div class="w-8 h-8 rounded-full bg-green-100 flex items-center justify-center mr-3">
                                            <i class="fas fa-check text-green-600 text-xs"></i>
                                        </div>
                                        <div class="flex-1">
                                            <p class="text-xs text-gray-700">Deal closed: TechCorp Inc</p>
                                            <p class="text-xs text-gray-500">$24,500 • Enterprise Plan</p>
                                        </div>
                                        <span class="text-xs text-blue-500 font-medium">New</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Floating Elements -->
                    <div class="absolute -bottom-6 -left-6 bg-white rounded-xl card-shadow p-4 w-48 border border-gray-100 floating-card">
                        <div class="flex items-center mb-2">
                            <div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center mr-2">
                                <i class="fas fa-chart-line text-blue-600 text-sm"></i>
                            </div>
                            <span class="font-bold text-gray-800">Sales Analytics</span>
                        </div>
                        <p class="text-xs text-gray-600">Track performance with real-time insights</p>
                    </div>
                    
                    <div class="absolute -top-6 -right-6 bg-white rounded-xl card-shadow p-4 w-48 border border-gray-100 floating-card" style="animation-delay: 2s;">
                        <div class="flex items-center mb-2">
                            <div class="w-8 h-8 bg-green-100 rounded-full flex items-center justify-center mr-2">
                                <i class="fas fa-users text-green-600 text-sm"></i>
                            </div>
                            <span class="font-bold text-gray-800">Customer Profiles</span>
                        </div>
                        <p class="text-xs text-gray-600">Complete 360° view of all interactions</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <script>
        // Initialize charts when the page loads
        document.addEventListener('DOMContentLoaded', function() {
            // Sales Performance Chart
            const salesCtx = document.getElementById('salesChart').getContext('2d');
            const salesChart = new Chart(salesCtx, {
                type: 'line',
                data: {
                    labels: ['Week 1', 'Week 2', 'Week 3', 'Week 4'],
                    datasets: [{
                        label: 'Sales',
                        data: [12000, 19000, 15000, 25000],
                        borderColor: '#3b82f6',
                        backgroundColor: 'rgba(59, 130, 246, 0.1)',
                        borderWidth: 2,
                        tension: 0.4,
                        fill: true
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: false
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            grid: {
                                drawBorder: false
                            },
                            ticks: {
                                callback: function(value) {
                                    return '$' + (value / 1000) + 'k';
                                }
                            }
                        },
                        x: {
                            grid: {
                                display: false
                            }
                        }
                    }
                }
            });

            // Leads Chart
            const leadsCtx = document.getElementById('leadsChart').getContext('2d');
            const leadsChart = new Chart(leadsCtx, {
                type: 'doughnut',
                data: {
                    labels: ['Website', 'Referral', 'Social Media', 'Email'],
                    datasets: [{
                        data: [35, 25, 20, 20],
                        backgroundColor: [
                            '#3b82f6',
                            '#10b981',
                            '#f59e0b',
                            '#8b5cf6'
                        ],
                        borderWidth: 0
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'bottom',
                            labels: {
                                boxWidth: 12,
                                padding: 15
                            }
                        }
                    },
                    cutout: '70%'
                }
            });
        });
    </script>
</body>
</html>
    <!-- Trusted By Section -->
    <section class="bg-white py-12">
        <div class="container mx-auto px-4">
            <p class="text-center text-gray-500 mb-8">Trusted by 10,000+ companies worldwide</p>
            <div class="flex flex-wrap justify-center items-center gap-8 md:gap-16 opacity-60">
                <div class="h-8 flex items-center">
                    <i class="fab fa-microsoft text-3xl text-gray-700"></i>
                </div>
                <div class="h-8 flex items-center">
                    <i class="fab fa-google text-3xl text-gray-700"></i>
                </div>
                <div class="h-8 flex items-center">
                    <i class="fab fa-amazon text-3xl text-gray-700"></i>
                </div>
                <div class="h-8 flex items-center">
                    <i class="fab fa-slack text-3xl text-gray-700"></i>
                </div>
                <div class="h-8 flex items-center">
                    <i class="fab fa-spotify text-3xl text-gray-700"></i>
                </div>
            </div>
        </div>
    </section>

    <!-- Key Features Section -->
    <section id="features" class="py-20 bg-gradient-to-br from-white to-blue-50 relative overflow-hidden">
        <!-- Background Elements -->
        <div class="absolute top-10 left-10 w-20 h-20 bg-blue-100 rounded-full opacity-50 animate-pulse-slow"></div>
        <div class="absolute bottom-20 right-10 w-16 h-16 bg-green-100 rounded-full opacity-50 animate-float"></div>
        <div class="absolute top-1/3 right-1/4 w-12 h-12 bg-purple-100 rounded-full opacity-50 animate-pulse"></div>
        <div class="absolute top-1/2 left-1/4 w-10 h-10 bg-yellow-100 rounded-full opacity-50 animate-bounce-slow"></div>
        
        <div class="container mx-auto px-4 relative z-10">
            <div class="text-center mb-16 stagger-animation">
                <span class="inline-block px-4 py-1 bg-blue-100 text-primary rounded-full text-sm font-medium mb-4">Powerful Features</span>
                <h2 class="text-3xl md:text-5xl font-bold text-gray-800 mb-4">Everything You Need to <span class="gradient-text">Grow Your Business</span></h2>
                <p class="text-xl text-gray-600 max-w-3xl mx-auto">Our comprehensive CRM solution streamlines your workflow, enhances customer relationships, and drives revenue growth</p>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8 stagger-animation">
                <!-- Feature 1 - Sales Automation -->
                <div class="bg-white rounded-xl shadow-lg p-6 feature-card border border-gray-100">
                    <div class="icon-wrapper w-14 h-14 bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl flex items-center justify-center mb-5 mx-auto shadow-md">
                        <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-gray-800 mb-3 text-center">Intelligent Sales Automation</h3>
                    <p class="text-gray-600 mb-5 text-center">Automate repetitive tasks and focus on what matters most - closing deals</p>
                    <ul class="text-gray-600 space-y-3">
                        <li class="flex items-start group">
                            <svg class="w-5 h-5 text-green-500 mt-0.5 mr-3 group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            <span>AI-powered lead scoring & routing</span>
                        </li>
                        <li class="flex items-start group">
                            <svg class="w-5 h-5 text-green-500 mt-0.5 mr-3 group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            <span>Visual sales pipeline management</span>
                        </li>
                        <li class="flex items-start group">
                            <svg class="w-5 h-5 text-green-500 mt-0.5 mr-3 group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            <span>Personalized email sequences</span>
                        </li>
                        <li class="flex items-start group">
                            <svg class="w-5 h-5 text-green-500 mt-0.5 mr-3 group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            <span>Automated task creation & reminders</span>
                        </li>
                    </ul>
                    <div class="mt-6 pt-5 border-t border-gray-100 text-center">
                        <span class="text-xs font-medium text-primary bg-blue-50 px-2 py-1 rounded">Save 10+ hours weekly</span>
                    </div>
                </div>
                
                <!-- Feature 2 - Customer Insights -->
                <div class="bg-white rounded-xl shadow-lg p-6 feature-card border border-gray-100">
                    <div class="icon-wrapper w-14 h-14 bg-gradient-to-br from-green-500 to-green-600 rounded-xl flex items-center justify-center mb-5 mx-auto shadow-md">
                        
                        <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-7 h-7 text-white">
                                    <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                                    <circle cx="12" cy="7" r="4"></circle>
                                </svg>
                    </div>
                    <h3 class="text-xl font-bold text-gray-800 mb-3 text-center">360° Customer Insights</h3>
                    <p class="text-gray-600 mb-5 text-center">Get a complete view of every customer interaction and history</p>
                    <ul class="text-gray-600 space-y-3">
                        <li class="flex items-start group">
                            <svg class="w-5 h-5 text-green-500 mt-0.5 mr-3 group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            <span>Unified customer profiles</span>
                        </li>
                        <li class="flex items-start group">
                            <svg class="w-5 h-5 text-green-500 mt-0.5 mr-3 group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            <span>Complete interaction timeline</span>
                        </li>
                        <li class="flex items-start group">
                            <svg class="w-5 h-5 text-green-500 mt-0.5 mr-3 group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            <span>Smart segmentation & tagging</span>
                        </li>
                        <li class="flex items-start group">
                            <svg class="w-5 h-5 text-green-500 mt-0.5 mr-3 group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            <span>Customer satisfaction tracking</span>
                        </li>
                    </ul>
                    <div class="mt-6 pt-5 border-t border-gray-100 text-center">
                        <span class="text-xs font-medium text-green-600 bg-green-50 px-2 py-1 rounded">Boost retention by 35%</span>
                    </div>
                </div>
                
                <!-- Feature 3 - Analytics & Reporting -->
                <div class="bg-white rounded-xl shadow-lg p-6 feature-card border border-gray-100">
                    <div class="icon-wrapper w-14 h-14 bg-gradient-to-br from-purple-500 to-purple-600 rounded-xl flex items-center justify-center mb-5 mx-auto shadow-md">
                        <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-gray-800 mb-3 text-center">Advanced Analytics & Reporting</h3>
                    <p class="text-gray-600 mb-5 text-center">Make data-driven decisions with powerful insights and forecasts</p>
                    <ul class="text-gray-600 space-y-3">
                        <li class="flex items-start group">
                            <svg class="w-5 h-5 text-green-500 mt-0.5 mr-3 group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            <span>Real-time performance dashboards</span>
                        </li>
                        <li class="flex items-start group">
                            <svg class="w-5 h-5 text-green-500 mt-0.5 mr-3 group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            <span>Sales forecasting & trend analysis</span>
                        </li>
                        <li class="flex items-start group">
                            <svg class="w-5 h-5 text-green-500 mt-0.5 mr-3 group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            <span>Custom report builder</span>
                        </li>
                        <li class="flex items-start group">
                            <svg class="w-5 h-5 text-green-500 mt-0.5 mr-3 group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            <span>KPI tracking & goal setting</span>
                        </li>
                    </ul>
                    <div class="mt-6 pt-5 border-t border-gray-100 text-center">
                        <span class="text-xs font-medium text-purple-600 bg-purple-50 px-2 py-1 rounded">Improve decisions by 45%</span>
                    </div>
                </div>
                
                <!-- Feature 4 - Integration Hub -->
                <div class="bg-white rounded-xl shadow-lg p-6 feature-card border border-gray-100">
                    <div class="icon-wrapper w-14 h-14 bg-gradient-to-br from-yellow-500 to-yellow-600 rounded-xl flex items-center justify-center mb-5 mx-auto shadow-md">
                        <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.684 13.342C8.886 12.938 9 12.482 9 12c0-.482-.114-.938-.316-1.342m0 2.684a3 3 0 110-2.684m0 2.684l6.632 3.316m-6.632-6l6.632-3.316m0 0a3 3 0 105.367-2.684 3 3 0 00-5.367 2.684zm0 9.316a3 3 0 105.368 2.684 3 3 0 00-5.368-2.684z"></path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-gray-800 mb-3 text-center">Seamless Integration Hub</h3>
                    <p class="text-gray-600 mb-5 text-center">Connect your favorite tools and create a unified workflow</p>
                    <ul class="text-gray-600 space-y-3">
                        <li class="flex items-start group">
                            <svg class="w-5 h-5 text-green-500 mt-0.5 mr-3 group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            <span>Email & calendar synchronization</span>
                        </li>
                        <li class="flex items-start group">
                            <svg class="w-5 h-5 text-green-500 mt-0.5 mr-3 group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            <span>Communication platform integration</span>
                        </li>
                        <li class="flex items-start group">
                            <svg class="w-5 h-5 text-green-500 mt-0.5 mr-3 group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            <span>Payment & billing system connections</span>
                        </li>
                        <li class="flex items-start group">
                            <svg class="w-5 h-5 text-green-500 mt-0.5 mr-3 group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            <span>Mobile app with full functionality</span>
                        </li>
                    </ul>
                    <div class="mt-6 pt-5 border-t border-gray-100 text-center">
                        <span class="text-xs font-medium text-yellow-600 bg-yellow-50 px-2 py-1 rounded">Connect 50+ apps</span>
                    </div>
                </div>
                
                <!-- Feature 5 - Marketing Automation -->
                <div class="bg-white rounded-xl shadow-lg p-6 feature-card border border-gray-100">
                    <div class="icon-wrapper w-14 h-14 bg-gradient-to-br from-pink-500 to-pink-600 rounded-xl flex items-center justify-center mb-5 mx-auto shadow-md">
                        <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 3.055A9.001 9.001 0 1020.945 13H11V3.055z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.488 9H15V3.512A9.025 9.025 0 0120.488 9z"></path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-gray-800 mb-3 text-center">Marketing Automation</h3>
                    <p class="text-gray-600 mb-5 text-center">Create targeted campaigns that convert leads into customers</p>
                    <ul class="text-gray-600 space-y-3">
                        <li class="flex items-start group">
                            <svg class="w-5 h-5 text-green-500 mt-0.5 mr-3 group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            <span>Multi-channel campaign management</span>
                        </li>
                        <li class="flex items-start group">
                            <svg class="w-5 h-5 text-green-500 mt-0.5 mr-3 group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            <span>Behavior-based automation triggers</span>
                        </li>
                        <li class="flex items-start group">
                            <svg class="w-5 h-5 text-green-500 mt-0.5 mr-3 group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            <span>Lead nurturing workflows</span>
                        </li>
                        <li class="flex items-start group">
                            <svg class="w-5 h-5 text-green-500 mt-0.5 mr-3 group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            <span>ROI tracking & attribution</span>
                        </li>
                    </ul>
                    <div class="mt-6 pt-5 border-t border-gray-100 text-center">
                        <span class="text-xs font-medium text-pink-600 bg-pink-50 px-2 py-1 rounded">Increase conversions by 40%</span>
                    </div>
                </div>
                
                <!-- Feature 6 - Team Collaboration -->
                <div class="bg-white rounded-xl shadow-lg p-6 feature-card border border-gray-100">
                    <div class="icon-wrapper w-14 h-14 bg-gradient-to-br from-indigo-500 to-indigo-600 rounded-xl flex items-center justify-center mb-5 mx-auto shadow-md">
                        <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-gray-800 mb-3 text-center">Team Collaboration</h3>
                    <p class="text-gray-600 mb-5 text-center">Enable seamless teamwork with shared tools and workflows</p>
                    <ul class="text-gray-600 space-y-3">
                        <li class="flex items-start group">
                            <svg class="w-5 h-5 text-green-500 mt-0.5 mr-3 group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            <span>Shared calendars & scheduling</span>
                        </li>
                        <li class="flex items-start group">
                            <svg class="w-5 h-5 text-green-500 mt-0.5 mr-3 group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            <span>Team activity feeds</span>
                        </li>
                        <li class="flex items-start group">
                            <svg class="w-5 h-5 text-green-500 mt-0.5 mr-3 group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            <span>Internal chat & commenting</span>
                        </li>
                        <li class="flex items-start group">
                            <svg class="w-5 h-5 text-green-500 mt-0.5 mr-3 group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            <span>Role-based permissions</span>
                        </li>
                    </ul>
                    <div class="mt-6 pt-5 border-t border-gray-100 text-center">
                        <span class="text-xs font-medium text-indigo-600 bg-indigo-50 px-2 py-1 rounded">Improve team productivity</span>
                    </div>
                </div>
                
                <!-- Feature 7 - Security & Compliance -->
                <div class="bg-white rounded-xl shadow-lg p-6 feature-card border border-gray-100">
                    <div class="icon-wrapper w-14 h-14 bg-gradient-to-br from-red-500 to-red-600 rounded-xl flex items-center justify-center mb-5 mx-auto shadow-md">
                        <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-gray-800 mb-3 text-center">Security & Compliance</h3>
                    <p class="text-gray-600 mb-5 text-center">Protect your data with enterprise-grade security features</p>
                    <ul class="text-gray-600 space-y-3">
                        <li class="flex items-start group">
                            <svg class="w-5 h-5 text-green-500 mt-0.5 mr-3 group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            <span>End-to-end encryption</span>
                        </li>
                        <li class="flex items-start group">
                            <svg class="w-5 h-5 text-green-500 mt-0.5 mr-3 group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            <span>GDPR & CCPA compliance</span>
                        </li>
                        <li class="flex items-start group">
                            <svg class="w-5 h-5 text-green-500 mt-0.5 mr-3 group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            <span>Multi-factor authentication</span>
                        </li>
                        <li class="flex items-start group">
                            <svg class="w-5 h-5 text-green-500 mt-0.5 mr-3 group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            <span>Audit logs & access controls</span>
                        </li>
                    </ul>
                    <div class="mt-6 pt-5 border-t border-gray-100 text-center">
                        <span class="text-xs font-medium text-red-600 bg-red-50 px-2 py-1 rounded">Enterprise-grade security</span>
                    </div>
                </div>
                
                <!-- Feature 8 - Customization -->
                <div class="bg-white rounded-xl shadow-lg p-6 feature-card border border-gray-100">
                    <div class="icon-wrapper w-14 h-14 bg-gradient-to-br from-teal-500 to-teal-600 rounded-xl flex items-center justify-center mb-5 mx-auto shadow-md">
                        <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-gray-800 mb-3 text-center">Customization & Scalability</h3>
                    <p class="text-gray-600 mb-5 text-center">Tailor the platform to your unique business needs</p>
                    <ul class="text-gray-600 space-y-3">
                        <li class="flex items-start group">
                            <svg class="w-5 h-5 text-green-500 mt-0.5 mr-3 group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            <span>Custom fields & workflows</span>
                        </li>
                        <li class="flex items-start group">
                            <svg class="w-5 h-5 text-green-500 mt-0.5 mr-3 group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            <span>API access for developers</span>
                        </li>
                        <li class="flex items-start group">
                            <svg class="w-5 h-5 text-green-500 mt-0.5 mr-3 group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            <span>White-labeling options</span>
                        </li>
                        <li class="flex items-start group">
                            <svg class="w-5 h-5 text-green-500 mt-0.5 mr-3 group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            <span>Scalable for teams of any size</span>
                        </li>
                    </ul>
                    <div class="mt-6 pt-5 border-t border-gray-100 text-center">
                        <span class="text-xs font-medium text-teal-600 bg-teal-50 px-2 py-1 rounded">Grow with your business</span>
                    </div>
                </div>
            </div>
            

        </div>
    </section>

    <script>
        // Add intersection observer for scroll animations with staggered effect
        document.addEventListener('DOMContentLoaded', function() {
            const staggerElements = document.querySelectorAll('.stagger-animation > *');
            const featureCards = document.querySelectorAll('.feature-card');
            
            const observer = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        if (entry.target.classList.contains('feature-card')) {
                            entry.target.style.opacity = 1;
                            entry.target.style.transform = 'translateY(0)';
                        } else {
                            // For header elements
                            entry.target.style.opacity = 1;
                            entry.target.style.transform = 'translateY(0)';
                        }
                    }
                });
            }, { threshold: 0.1 });
            
            // Animate header elements with a slight delay
            staggerElements.forEach((element, index) => {
                element.style.opacity = 0;
                element.style.transform = 'translateY(20px)';
                element.style.transition = `opacity 0.6s ease ${index * 0.1}s, transform 0.6s ease ${index * 0.1}s`;
                observer.observe(element);
            });
            
            // Animate feature cards with staggered effect
            featureCards.forEach((card, index) => {
                card.style.opacity = 0;
                card.style.transform = 'translateY(20px)';
                card.style.transition = `opacity 0.6s ease ${index * 0.1 + 0.3}s, transform 0.6s ease ${index * 0.1 + 0.3}s`;
                observer.observe(card);
            });
        });
    </script>

<section id="dashboard" class="py-20 bg-gradient-to-b from-gray-50 to-white">
    <div class="container mx-auto px-4">
        <div class="text-center mb-16">
            <span class="inline-block px-5 py-2 bg-blue-50 text-blue-700 rounded-full text-sm font-medium mb-6">
                {{-- <i class="fas fa-tablet-alt mr-2"></i> --}}
                DASHBOARD PREVIEW
            </span>
            <h2 class="text-4xl md:text-5xl font-bold text-gray-900 mb-6">
                Experience the Power of 
                <span class="text-blue-600">Social Cults CRM</span>
            </h2>
            <p class="text-lg text-gray-600 max-w-3xl mx-auto">
                See how our intuitive dashboard transforms customer relationship management for modern businesses
            </p>
        </div>

        <!-- Tablet View Image Container -->
        <div class="mb-16">
            <div class="max-w-6xl mx-auto">
                <!-- Tablet Frame - Wider with Moderate Border -->
                <div class="relative bg-gray-900 rounded-[32px] p-4 md:p-6 shadow-2xl border-4 border-gray-800 mx-auto w-full">
                    <!-- Tablet Top Bar -->
                    <div class="absolute top-2 left-1/2 transform -translate-x-1/2 w-32 h-1 bg-gray-700 rounded-full"></div>
                    
                    <!-- Tablet Camera -->
                    <div class="absolute top-3 left-1/2 transform -translate-x-1/2 w-2 h-2 bg-gray-600 rounded-full"></div>
                    
                    <!-- Tablet Screen -->
                    <div class="bg-white rounded-[16px] overflow-hidden border border-gray-800">
                        <!-- Browser Header -->
                        <div class="px-4 md:px-6 py-3 bg-gray-800 flex items-center justify-between">
                            <div class="flex items-center space-x-3">
                                <div class="flex space-x-1">
                                    <div class="w-1.5 h-1.5 rounded-full bg-red-400"></div>
                                    <div class="w-1.5 h-1.5 rounded-full bg-yellow-400"></div>
                                    <div class="w-1.5 h-1.5 rounded-full bg-green-400"></div>
                                </div>
                                <div class="flex-1">
                                    <div class="bg-gray-700 px-3 py-1 rounded text-center max-w-xs mx-auto">
                                        <span class="text-gray-300 text-sm truncate">Social Cults CRM</span>
                                    </div>
                                </div>
                            </div>
                            <div class="text-gray-400">
                                <i class="fas fa-wifi"></i>
                            </div>
                        </div>
                        
                        <!-- Dashboard Image - Full Width -->
                        <div class="relative w-full overflow-hidden bg-gray-100">
                            <img 
                                src="{{ asset('assests/view.png') }}" 
                                alt="Social Cults CRM Dashboard - Tablet View"
                                class="w-full h-auto object-contain"
                                loading="lazy"
                                onerror="this.src='https://images.unsplash.com/photo-1551288049-bebda4e38f71?ixlib=rb-4.0.3&auto=format&fit=crop&w=1600&q=80'"
                            />
                            <!-- Loading Placeholder -->
                            <div class="absolute inset-0 bg-gradient-to-br from-blue-50 to-gray-100 flex items-center justify-center hidden" id="tabletLoading">
                                <div class="text-center">
                                    <div class="w-10 h-10 border-3 border-gray-200 border-t-blue-500 rounded-full animate-spin mx-auto mb-3"></div>
                                    <p class="text-gray-600 text-sm">Loading Preview...</p>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Tablet Bottom Controls -->
                        <div class="px-4 md:px-6 py-2 bg-gray-800 border-t border-gray-700">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center space-x-3">
                                    <div class="w-6 h-6 rounded-full bg-gray-700 flex items-center justify-center">
                                        <i class="fas fa-home text-gray-400 text-xs"></i>
                                    </div>
                                    <div class="w-6 h-6 rounded-full bg-gray-700 flex items-center justify-center">
                                        <i class="fas fa-search text-gray-400 text-xs"></i>
                                    </div>
                                </div>
                                <div class="flex items-center space-x-2">
                                    <span class="text-xs text-gray-400">10:24 AM</span>
                                    <div class="w-1.5 h-1.5 bg-green-400 rounded-full"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Tablet Home Button -->
                    <div class="absolute bottom-2 left-1/2 transform -translate-x-1/2 w-10 h-10 rounded-full bg-gray-800 border border-gray-700 flex items-center justify-center">
                        <div class="w-5 h-5 rounded-full border border-gray-600"></div>
                    </div>
                    
                    <!-- Volume Buttons -->
                    <div class="absolute left-0 top-1/4 w-0.5 h-12 bg-gray-800 rounded-r"></div>
                    <div class="absolute left-0 top-2/4 w-0.5 h-12 bg-gray-800 rounded-r"></div>
                    
                    <!-- Power Button -->
                    <div class="absolute right-0 top-1/3 w-0.5 h-6 bg-gray-800 rounded-l"></div>
                </div>
                

              
            </div>
        </div>

        <!-- Benefits Cards Grid -->
        <div class="max-w-6xl mx-auto">
            <h3 class="text-3xl font-bold text-center text-gray-900 mb-12">
                Why Businesses Choose 
                <span class="text-blue-600">Our Platform</span>
            </h3>
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <!-- Card 1 -->
                <div class="group bg-white rounded-xl p-6 shadow-lg hover:shadow-xl border border-gray-100 transition-all duration-300 hover:-translate-y-1">
                    <div class="w-14 h-14 bg-gradient-to-br from-blue-100 to-blue-200 rounded-2xl flex items-center justify-center mb-6 mx-auto group-hover:scale-110 transition-transform duration-300">
                        <i class="fas fa-bolt text-blue-600 text-xl"></i>
                    </div>
                    <h4 class="text-xl font-bold text-gray-900 text-center mb-4">Save 70% Time</h4>
                    <p class="text-gray-600 text-center text-sm leading-relaxed mb-4">
                        Automate repetitive tasks and focus on building meaningful customer relationships.
                    </p>
                    <div class="space-y-2">
                        <div class="flex items-center text-blue-600">
                            <i class="fas fa-check-circle text-xs mr-2"></i>
                            <span class="text-sm">Automated Scheduling</span>
                        </div>
                        <div class="flex items-center text-blue-600">
                            <i class="fas fa-check-circle text-xs mr-2"></i>
                            <span class="text-sm">Smart Workflows</span>
                        </div>
                        <div class="flex items-center text-blue-600">
                            <i class="fas fa-check-circle text-xs mr-2"></i>
                            <span class="text-sm">Batch Processing</span>
                        </div>
                    </div>
                </div>
                
                <!-- Card 2 -->
                <div class="group bg-white rounded-xl p-6 shadow-lg hover:shadow-xl border border-gray-100 transition-all duration-300 hover:-translate-y-1">
                    <div class="w-14 h-14 bg-gradient-to-br from-purple-100 to-purple-200 rounded-2xl flex items-center justify-center mb-6 mx-auto group-hover:scale-110 transition-transform duration-300">
                        <i class="fas fa-chart-line text-purple-600 text-xl"></i>
                    </div>
                    <h4 class="text-xl font-bold text-gray-900 text-center mb-4">47% Higher Engagement</h4>
                    <p class="text-gray-600 text-center text-sm leading-relaxed mb-4">
                        Data-driven insights help you connect with customers at the right time.
                    </p>
                    <div class="relative pt-4">
                        <div class="text-center mb-2">
                            <span class="text-2xl font-bold text-purple-600">87%</span>
                            <span class="text-gray-600 ml-2 text-sm">Success Rate</span>
                        </div>
                        <div class="w-full bg-gray-200 rounded-full h-1.5">
                            <div class="bg-gradient-to-r from-purple-500 to-pink-500 h-1.5 rounded-full" style="width: 87%"></div>
                        </div>
                    </div>
                </div>
                
                <!-- Card 3 -->
                <div class="group bg-white rounded-xl p-6 shadow-lg hover:shadow-xl border border-gray-100 transition-all duration-300 hover:-translate-y-1">
                    <div class="w-14 h-14 bg-gradient-to-br from-green-100 to-green-200 rounded-2xl flex items-center justify-center mb-6 mx-auto group-hover:scale-110 transition-transform duration-300">
                        <i class="fas fa-users text-green-600 text-xl"></i>
                    </div>
                    <h4 class="text-xl font-bold text-gray-900 text-center mb-4">Grow Your Audience</h4>
                    <p class="text-gray-600 text-center text-sm leading-relaxed mb-4">
                        Advanced analytics and targeting features to expand your reach.
                    </p>
                    <div class="grid grid-cols-2 gap-2">
                        <div class="text-center p-2 bg-gray-50 rounded-lg">
                            <i class="fas fa-user-check text-green-500 text-sm mb-1"></i>
                            <p class="text-xs text-gray-700">Audience Insights</p>
                        </div>
                        <div class="text-center p-2 bg-gray-50 rounded-lg">
                            <i class="fas fa-bullseye text-blue-500 text-sm mb-1"></i>
                            <p class="text-xs text-gray-700">Smart Targeting</p>
                        </div>
                        <div class="text-center p-2 bg-gray-50 rounded-lg">
                            <i class="fas fa-chart-bar text-purple-500 text-sm mb-1"></i>
                            <p class="text-xs text-gray-700">Analytics</p>
                        </div>
                        <div class="text-center p-2 bg-gray-50 rounded-lg">
                            <i class="fas fa-handshake text-orange-500 text-sm mb-1"></i>
                            <p class="text-xs text-gray-700">Engagement</p>
                        </div>
                    </div>
                </div>
            </div>
            
            

         
        </div>
    </div>
</section>

<style>
/* Optimized tablet styling */
.rounded-\[32px\] {
    border-radius: 32px;
}

.rounded-\[16px\] {
    border-radius: 16px;
}

/* Smooth animations */
.group {
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
}

/* Image optimization */
img {
    max-height: 500px;
    object-fit: contain;
}

/* Tablet responsive design */
@media (max-width: 768px) {
    .border-4 {
        border-width: 3px;
    }
    
    .rounded-\[32px\] {
        border-radius: 24px;
    }
    
    .rounded-\[16px\] {
        border-radius: 12px;
    }
    
    img {
        max-height: 350px;
    }
}

/* Loading animation */
.animate-spin {
    animation: spin 1s linear infinite;
}

@keyframes spin {
    from {
        transform: rotate(0deg);
    }
    to {
        transform: rotate(360deg);
    }
}

/* Better shadow for tablet */
.shadow-2xl {
    box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
}

/* Hover effects */
.hover\:shadow-xl:hover {
    box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
}
</style>






<section id="mobile" class="py-16 bg-white">
    <div class="container mx-auto px-4">
        <div class="text-center mb-12">
            <span class="inline-block px-4 py-2 bg-gradient-to-r from-purple-500 to-purple-600 text-white rounded-full text-sm font-medium mb-4 shadow-sm">
                <i class="fas fa-mobile-alt mr-2"></i>
                MOBILE PREVIEW
            </span>
            <h2 class="text-3xl md:text-4xl font-bold text-gray-800 mb-4">
                Your Social Media Dashboard
                <span class="text-purple-600">On The Go</span>
            </h2>
            <p class="text-base text-gray-600 max-w-2xl mx-auto">
                Access your complete dashboard and manage campaigns from any device, anywhere
            </p>
        </div>
        
        <div class="flex flex-col lg:flex-row items-center gap-12">
            <!-- Mobile Devices Showcase - REPLACE WITH YOUR IMAGE -->
            <div class="lg:w-1/2">
                <!-- Replace this div with your image -->
                <img 
                    src="{{ asset('assests/C3.png') }}" 
                    alt="Mobile App Dashboard Preview"
                    class="w-full max-w-md mx-auto rounded-3xl "
                >
            </div>
            
            <!-- Features Section - Compact -->
            <div class="lg:w-1/2">
                <h3 class="text-2xl font-bold text-gray-800 mb-6">
                    Complete Dashboard 
                    <span class="text-purple-600">Everywhere You Go</span>
                </h3>
                
                <!-- Compact Features Grid -->
                <div class="grid grid-cols-2 gap-4 mb-8">
                    <!-- Feature 1 -->
                    <div class="bg-white p-4 rounded-lg border border-gray-100 shadow-sm hover:shadow-md transition-shadow">
                        <div class="w-10 h-10 bg-blue-50 rounded-lg flex items-center justify-center mb-3">
                            <i class="fas fa-chart-line text-blue-600"></i>
                        </div>
                        <h4 class="text-sm font-bold text-gray-800 mb-2">Real-time Analytics</h4>
                        <p class="text-xs text-gray-600">Track performance metrics instantly from anywhere</p>
                    </div>
                    
                    <!-- Feature 2 -->
                    <div class="bg-white p-4 rounded-lg border border-gray-100 shadow-sm hover:shadow-md transition-shadow">
                        <div class="w-10 h-10 bg-purple-50 rounded-lg flex items-center justify-center mb-3">
                            <i class="fas fa-users text-purple-600"></i>
                        </div>
                        <h4 class="text-sm font-bold text-gray-800 mb-2">Client Management</h4>
                        <p class="text-xs text-gray-600">Manage all client relationships on the go</p>
                    </div>
                    
                    <!-- Feature 3 -->
                    <div class="bg-white p-4 rounded-lg border border-gray-100 shadow-sm hover:shadow-md transition-shadow">
                        <div class="w-10 h-10 bg-green-50 rounded-lg flex items-center justify-center mb-3">
                            <i class="fas fa-bullhorn text-green-600"></i>
                        </div>
                        <h4 class="text-sm font-bold text-gray-800 mb-2">Campaign Control</h4>
                        <p class="text-xs text-gray-600">Launch and monitor campaigns anywhere</p>
                    </div>
                    
                    <!-- Feature 4 -->
                    <div class="bg-white p-4 rounded-lg border border-gray-100 shadow-sm hover:shadow-md transition-shadow">
                        <div class="w-10 h-10 bg-yellow-50 rounded-lg flex items-center justify-center mb-3">
                            <i class="fas fa-bell text-yellow-600"></i>
                        </div>
                        <h4 class="text-sm font-bold text-gray-800 mb-2">Instant Notifications</h4>
                        <p class="text-xs text-gray-600">Get alerts for important updates</p>
                    </div>
                </div>
                
                <!-- Mobile Benefits -->
                <div class="space-y-4 mb-8">
                    <div class="flex items-center">
                        <div class="w-8 h-8 bg-green-100 rounded-full flex items-center justify-center mr-3">
                            <i class="fas fa-check text-green-600 text-sm"></i>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-800">Full Dashboard Experience</p>
                            <p class="text-xs text-gray-600">All features available on mobile</p>
                        </div>
                    </div>
                    <div class="flex items-center">
                        <div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center mr-3">
                            <i class="fas fa-sync-alt text-blue-600 text-sm"></i>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-800">Real-time Sync</p>
                            <p class="text-xs text-gray-600">Data updates instantly across devices</p>
                        </div>
                    </div>
                    <div class="flex items-center">
                        <div class="w-8 h-8 bg-purple-100 rounded-full flex items-center justify-center mr-3">
                            <i class="fas fa-shield-alt text-purple-600 text-sm"></i>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-800">Secure Access</p>
                            <p class="text-xs text-gray-600">Bank-level security on all devices</p>
                        </div>
                    </div>
                </div>
                
                <!-- CTA Buttons -->
                <div class="flex flex-col sm:flex-row gap-3">
                    <a href="#" class="flex-1 bg-gradient-to-r from-purple-600 to-purple-700 hover:from-purple-700 hover:to-purple-800 text-white py-3 rounded-lg font-medium text-center transition-all flex items-center justify-center">
                        <i class="fas fa-mobile-alt mr-2"></i>
                        Download App
                    </a>
                    <a href="#" class="flex-1 border border-purple-600 text-purple-600 hover:bg-purple-50 py-3 rounded-lg font-medium text-center transition-colors flex items-center justify-center">
                        <i class="fas fa-play-circle mr-2"></i>
                        Watch Demo
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>

    <style>
            .gradient-bg {
            background: linear-gradient(135deg, #f0f7ff 0%, #e6f0ff 100%);
        }
        
        .dashboard-container {
            border-radius: 16px;
            overflow: hidden;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
            transform: perspective(1000px) rotateY(-5deg) rotateX(5deg);
            transition: all 0.5s ease;
            border: 1px solid rgba(255, 255, 255, 0.2);
            position: relative;
        }
        
        .dashboard-container::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 6px;
            background: linear-gradient(90deg, #8b5cf6, #3b82f6, #10b981, #f59e0b);
            z-index: 10;
        }
        
        .dashboard-container:hover {
            transform: perspective(1000px) rotateY(0) rotateX(0);
            box-shadow: 0 30px 60px -12px rgba(0, 0, 0, 0.35);
        }
        
        .feature-icon {
            width: 64px;
            height: 64px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 16px;
            margin-bottom: 1.25rem;
            box-shadow: 0 10px 20px -5px rgba(0, 0, 0, 0.1);
            transition: all 0.3s ease;
        }
        
        .feature-card {
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }
        
        .feature-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 4px;
            height: 100%;
            /* background: linear-gradient(to bottom, #8b5cf6, #3b82f6); */
            opacity: 0;
            transition: opacity 0.3s ease;
             cursor: pointer;
            
        }
        
        .feature-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 20px 40px -10px rgba(0, 0, 0, 0.15);
        }
        
        .feature-card:hover::before {
            opacity: 1;
        }
        
        .feature-card:hover .feature-icon {
            transform: scale(1.1);
        }
        
        .highlight {
            position: relative;
            display: inline-block;
        }
        
        .highlight::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            width: 100%;
            height: 8px;
            background: linear-gradient(90deg, #8b5cf6, #3b82f6);
            opacity: 0.2;
            border-radius: 4px;
            z-index: -1;
        }
        
        .floating {
            animation: floating 8s ease-in-out infinite;
        }
        
        @keyframes floating {
            0% { transform: translateY(0px) rotateY(-5deg) rotateX(5deg); }
            50% { transform: translateY(-15px) rotateY(-3deg) rotateX(3deg); }
            100% { transform: translateY(0px) rotateY(-5deg) rotateX(5deg); }
        }
        
        .badge {
            position: relative;
            overflow: hidden;
        }
        
        .badge::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.3), transparent);
            transition: left 0.5s ease;
        }
        
        .badge:hover::before {
            left: 100%;
        }
        
        .pulse-dot {
            width: 8px;
            height: 8px;
            border-radius: 50%;
            background-color: #10b981;
            animation: pulse 2s infinite;
        }
        
        @keyframes pulse {
            0% { transform: scale(0.95); box-shadow: 0 0 0 0 rgba(16, 185, 129, 0.7); }
            70% { transform: scale(1); box-shadow: 0 0 0 10px rgba(16, 185, 129, 0); }
            100% { transform: scale(0.95); box-shadow: 0 0 0 0 rgba(16, 185, 129, 0); }
        }
    </style>

    <script>
        // Add hover effect to feature cards
        document.querySelectorAll('.feature-card').forEach(card => {
            card.addEventListener('mouseenter', function() {
                this.style.transform = 'translateY(-8px)';
            });
            
            card.addEventListener('mouseleave', function() {
                this.style.transform = 'translateY(0)';
            });
        });

        // Add click animation to CTA buttons
        document.querySelectorAll('a').forEach(link => {
            link.addEventListener('click', function(e) {
                if(this.getAttribute('href') === '#') {
                    e.preventDefault();
                    this.style.transform = 'scale(0.95)';
                    setTimeout(() => {
                        this.style.transform = 'scale(1)';
                    }, 150);
                }
            });
        });
    </script>







<section class="bg-[#fbeedc]">
    <div class="container mx-auto px-4 flex flex-col justify-center items-center text-center">

        <!-- Title -->
        <h2 class="text-4xl pt-2 mt-3 font-bold text-gray-800 mb-12 leading-tight">
            Grow with Your CRM
        </h2>

        <div class="flex items-end justify-center -space-x-2 md:-space-x-6"> <!-- Negative spacing for overlap -->

            <!-- Card 1 -->
            <div class="relative bg-[#d2c5ff] h-[250px] w-[130px] md:w-[250px] rounded-t-[150px] flex flex-col justify-center items-center text-center px-4 z-10">
                <h3 class="text-4xl font-bold text-gray-900"><span class="counter" data-target="27">0</span>%</h3>
                <p class="text-lg mt-1 font-semibold text-gray-800">Increased productivity</p>
                <p class="text-sm text-gray-700 mt-1">Do more in less time</p>
                <svg width="45" fill="#8b75ff" viewBox="0 0 24 24">
                        <path d="M13 5v6h6v2h-8V5h2zm-2 14v-6H5v-2h8v8h-2z"/>
                    </svg>

               
            </div>

            <!-- Card 2 -->
            <div class="relative bg-[#b7ecff] h-[350px] w-[130px] md:w-[250px] rounded-t-[150px] flex flex-col justify-center items-center text-center  px-4 z-10">
                <h3 class="text-4xl font-bold text-gray-900"><span class="counter" data-target="50">0</span>%</h3>
                <p class="text-lg mt-1 font-semibold text-gray-800">Faster implementation</p>
                <p class="text-sm text-gray-700 mt-1">Get started in no time</p>
                <svg width="55" fill="#88d1df" viewBox="0 0 24 24">
                        <path d="M12 2L1 21h22L12 2zm0 5l7 12H5l7-12z"/>
                    </svg>

               
            </div>

            <!-- Card 3 -->
            <div class="relative bg-[#ffcaa6] h-[450px] w-[130px] md:w-[250px] rounded-t-[150px] flex flex-col justify-start items-center text-center px-4 pt-16 z-30">
        
                <h3 class="text-4xl font-bold text-gray-900"><span class="counter" data-target="71">0</span>%</h3>
                <p class="text-lg mt-1 font-semibold text-gray-800">Saved on licensing fees</p>
                <p class="text-sm text-gray-700 mt-1">Big savings for a lifetime</p>
                  <svg width="24" height="24" fill="white" viewBox="0 0 24 24">
                            <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 15l-5-5 1.41-1.41L10 14.17l7.59-7.59L19 8l-9 9z"/>
                        </svg>
                 <img src="{{ asset('assests/crm25-removebg-preview.png') }}" alt="">
                
                
            </div>

        </div>
    </div>
</section>

<script>
// Faster animation function
function animateCounterFast(element) {
    const target = parseInt(element.getAttribute('data-target'));
    const duration = 800; // Much faster - 0.8 seconds instead of 2
    const steps = 30; // Fewer steps for faster animation
    const step = target / steps;
    let current = 0;
    let stepCount = 0;
    
    const timer = setInterval(() => {
        current += step;
        stepCount++;
        
        if (stepCount >= steps) {
            element.textContent = target;
            clearInterval(timer);
        } else {
            element.textContent = Math.floor(current);
        }
    }, duration / steps);
}

// Initialize counters immediately
document.addEventListener('DOMContentLoaded', function() {
    const counters = document.querySelectorAll('.counter');
    
    // No delays, just start immediately
    counters.forEach(counter => {
        // Start counting immediately
        animateCounterFast(counter);
    });
    
    // Make cards appear instantly (no animation)
    const cards = document.querySelectorAll('.relative');
    cards.forEach(card => {
        card.style.opacity = '1';
        card.style.transform = 'translateY(0)';
    });
});


document.addEventListener('DOMContentLoaded', function() {
    // Option 1: Instant jump with slight animation
    setTimeout(() => {
        const counters = document.querySelectorAll('.counter');
        
        counters.forEach(counter => {
            const target = parseInt(counter.getAttribute('data-target'));
            let current = 0;
            const duration = 600; // Very fast - 0.6 seconds
            const stepTime = 20; // Update every 20ms
            const steps = duration / stepTime;
            const increment = target / steps;
            
            const timer = setInterval(() => {
                current += increment;
                if (current >= target) {
                    counter.textContent = target;
                    clearInterval(timer);
                } else {
                    counter.textContent = Math.floor(current);
                }
            }, stepTime);
        });
    }, 100); // Small initial delay
});
</script>

<style>
    /* Minimal styling - no transitions */
    .counter {
        display: inline-block;
        min-width: 50px;
    }
    
    /* Optional: If you want the cards to appear instantly */
    .relative {
        opacity: 1 !important;
        transform: translateY(0) !important;
    }
</style>

<!-- Add this alternative faster script -->
<script>
// FASTEST OPTION - Count up almost instantly with visual feedback
document.addEventListener('DOMContentLoaded', function() {
    const counters = document.querySelectorAll('.counter');
    
    // Start animation immediately
    counters.forEach(counter => {
        const target = parseInt(counter.getAttribute('data-target'));
        let current = 0;
        const stepTime = 10; // Very fast updates
        const steps = 20; // Only 20 steps
        const increment = target / steps;
        
        const timer = setInterval(() => {
            current += increment;
            if (current >= target) {
                counter.textContent = target;
                clearInterval(timer);
            } else {
                counter.textContent = Math.floor(current);
            }
        }, stepTime);
    });
    
    // Cards are already visible
});
</script>

    <!-- Benefits Section -->

    <!-- Testimonials Section -->
     <section id="testimonials" class="py-20 gradient-bg">
        <div class="container mx-auto px-4">
            <div class="text-center mb-16">
                <span class="inline-block px-4 py-2 bg-indigo-100 text-indigo-700 rounded-full text-sm font-semibold tracking-wide uppercase mb-4">Testimonials</span>
                <h2 class="text-4xl md:text-5xl font-bold text-gray-900 mb-4">Loved by Social Media Professionals</h2>
                <p class="text-xl text-gray-600 max-w-3xl mx-auto">See what our customers have to say about their experience with Social Cults</p>
            </div>
            
            <!-- Carousel Container -->
            <div class="carousel-container">
                <!-- Carousel Track -->
                <div class="carousel-track" id="carouselTrack">
                    <!-- Testimonial 1 -->
                    <div class="carousel-slide">
                        <div class="bg-white rounded-2xl testimonial-shadow p-8 hover-lift h-full">
                            <div class="flex items-center mb-6">
                                <div class="w-16 h-16 rounded-full overflow-hidden mr-5 flex-shrink-0">
                                    <img src="https://images.unsplash.com/photo-1494790108755-2616b612b786?ixlib=rb-1.2.1&auto=format&fit=crop&w=500&q=80" alt="Sarah Johnson" class="w-full h-full object-cover">
                                </div>
                                <div>
                                    <h4 class="font-bold text-gray-900 text-lg">Sarah Johnson</h4>
                                    <p class="text-gray-600 text-sm">Social Media Manager, GrowthLabs</p>
                                </div>
                            </div>
                            <p class="text-gray-600 mb-6 leading-relaxed">"Social Cults has completely transformed our social media strategy. We've seen engagement rates increase by 65% and saved over 15 hours per week on content scheduling."</p>
                            <div class="flex text-yellow-400 mb-4">
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                            </div>
                            <div class="flex items-center text-sm text-gray-500">
                                <i class="fas fa-check-circle text-green-500 mr-2"></i>
                                <span>Verified Customer</span>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Testimonial 2 -->
                    <div class="carousel-slide">
                        <div class="bg-white rounded-2xl testimonial-shadow p-8 hover-lift h-full">
                            <div class="flex items-center mb-6">
                                <div class="w-16 h-16 rounded-full overflow-hidden mr-5 flex-shrink-0">
                                    <img src="https://images.unsplash.com/photo-1472099645785-5658abf4ff4e?ixlib=rb-1.2.1&auto=format&fit=crop&w=500&q=80" alt="Michael Rodriguez" class="w-full h-full object-cover">
                                </div>
                                <div>
                                    <h4 class="font-bold text-gray-900 text-lg">Michael Rodriguez</h4>
                                    <p class="text-gray-600 text-sm">Content Director, Creative Agency</p>
                                </div>
                            </div>
                            <p class="text-gray-600 mb-6 leading-relaxed">"The analytics and reporting features have given us insights we never had before. We can now prove ROI to our clients with clear, data-driven reports."</p>
                            <div class="flex text-yellow-400 mb-4">
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                            </div>
                            <div class="flex items-center text-sm text-gray-500">
                                <i class="fas fa-check-circle text-green-500 mr-2"></i>
                                <span>Verified Customer</span>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Testimonial 3 -->
                    <div class="carousel-slide">
                        <div class="bg-white rounded-2xl testimonial-shadow p-8 hover-lift h-full">
                            <div class="flex items-center mb-6">
                                <div class="w-16 h-16 rounded-full overflow-hidden mr-5 flex-shrink-0">
                                    <img src="https://images.unsplash.com/photo-1438761681033-6461ffad8d80?ixlib=rb-1.2.1&auto=format&fit=crop&w=500&q=80" alt="Jessica Lee" class="w-full h-full object-cover">
                                </div>
                                <div>
                                    <h4 class="font-bold text-gray-900 text-lg">Jessica Lee</h4>
                                    <p class="text-gray-600 text-sm">Marketing Lead, TechStart Inc</p>
                                </div>
                            </div>
                            <p class="text-gray-600 mb-6 leading-relaxed">"Our team collaboration has improved dramatically since implementing Social Cults. The approval workflows and content calendar keep everyone aligned and efficient."</p>
                            <div class="flex text-yellow-400 mb-4">
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                            </div>
                            <div class="flex items-center text-sm text-gray-500">
                                <i class="fas fa-check-circle text-green-500 mr-2"></i>
                                <span>Verified Customer</span>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Testimonial 4 -->
                    <div class="carousel-slide">
                        <div class="bg-white rounded-2xl testimonial-shadow p-8 hover-lift h-full">
                            <div class="flex items-center mb-6">
                                <div class="w-16 h-16 rounded-full overflow-hidden mr-5 flex-shrink-0">
                                    <img src="https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?ixlib=rb-1.2.1&auto=format&fit=crop&w=500&q=80" alt="David Chen" class="w-full h-full object-cover">
                                </div>
                                <div>
                                    <h4 class="font-bold text-gray-900 text-lg">David Chen</h4>
                                    <p class="text-gray-600 text-sm">Founder, E-commerce Brand</p>
                                </div>
                            </div>
                            <p class="text-gray-600 mb-6 leading-relaxed">"Social Cults helped us grow our Instagram following from 5K to 50K in just 6 months. The audience insights and content recommendations are incredibly valuable."</p>
                            <div class="flex text-yellow-400 mb-4">
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                            </div>
                            <div class="flex items-center text-sm text-gray-500">
                                <i class="fas fa-check-circle text-green-500 mr-2"></i>
                                <span>Verified Customer</span>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Testimonial 5 -->
                    <div class="carousel-slide">
                        <div class="bg-white rounded-2xl testimonial-shadow p-8 hover-lift h-full">
                            <div class="flex items-center mb-6">
                                <div class="w-16 h-16 rounded-full overflow-hidden mr-5 flex-shrink-0">
                                    <img src="https://images.unsplash.com/photo-1489424731084-a5d8b219a5bb?ixlib=rb-1.2.1&auto=format&fit=crop&w=500&q=80" alt="Amanda Wilson" class="w-full h-full object-cover">
                                </div>
                                <div>
                                    <h4 class="font-bold text-gray-900 text-lg">Amanda Wilson</h4>
                                    <p class="text-gray-600 text-sm">Digital Strategist, Media Agency</p>
                                </div>
                            </div>
                            <p class="text-gray-600 mb-6 leading-relaxed">"The competitor analysis feature alone is worth the subscription. We've been able to identify content gaps and opportunities we would have otherwise missed."</p>
                            <div class="flex text-yellow-400 mb-4">
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                            </div>
                            <div class="flex items-center text-sm text-gray-500">
                                <i class="fas fa-check-circle text-green-500 mr-2"></i>
                                <span>Verified Customer</span>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Carousel Navigation -->
                <button class="carousel-btn prev" id="prevBtn">
                    <i class="fas fa-chevron-left"></i>
                </button>
                <button class="carousel-btn next" id="nextBtn">
                    <i class="fas fa-chevron-right"></i>
                </button>
                
                <!-- Carousel Indicators -->
                <div class="carousel-indicators" id="carouselIndicators">
                    <!-- Indicators will be added by JavaScript -->
                </div>
            </div>
            
      
        </div>
    </section>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const track = document.getElementById('carouselTrack');
            const slides = document.querySelectorAll('.carousel-slide');
            const prevBtn = document.getElementById('prevBtn');
            const nextBtn = document.getElementById('nextBtn');
            const indicatorsContainer = document.getElementById('carouselIndicators');
            
            let currentIndex = 0;
            const totalSlides = slides.length;
            
            // Create indicators
            slides.forEach((_, index) => {
                const indicator = document.createElement('div');
                indicator.classList.add('carousel-indicator');
                if (index === 0) indicator.classList.add('active');
                indicator.addEventListener('click', () => goToSlide(index));
                indicatorsContainer.appendChild(indicator);
            });
            
            // Function to update carousel position
            function updateCarousel() {
                const slideWidth = slides[0].getBoundingClientRect().width;
                track.style.transform = `translateX(-${currentIndex * slideWidth}px)`;
                
                // Update indicators
                document.querySelectorAll('.carousel-indicator').forEach((indicator, index) => {
                    indicator.classList.toggle('active', index === currentIndex);
                });
            }
            
            // Function to go to specific slide
            function goToSlide(index) {
                currentIndex = index;
                updateCarousel();
            }
            
            // Next slide
            nextBtn.addEventListener('click', () => {
                currentIndex = (currentIndex + 1) % totalSlides;
                updateCarousel();
            });
            
            // Previous slide
            prevBtn.addEventListener('click', () => {
                currentIndex = (currentIndex - 1 + totalSlides) % totalSlides;
                updateCarousel();
            });
            
            // Auto-advance carousel
            let autoSlide = setInterval(() => {
                currentIndex = (currentIndex + 1) % totalSlides;
                updateCarousel();
            }, 5000);
            
            // Pause auto-slide on hover
            const carouselContainer = document.querySelector('.carousel-container');
            carouselContainer.addEventListener('mouseenter', () => {
                clearInterval(autoSlide);
            });
            
            carouselContainer.addEventListener('mouseleave', () => {
                autoSlide = setInterval(() => {
                    currentIndex = (currentIndex + 1) % totalSlides;
                    updateCarousel();
                }, 5000);
            });
            
            // Handle window resize
            window.addEventListener('resize', updateCarousel);
        });
    </script>


<section class="py-20 bg-gradient-to-br from-gray-50 to-blue-50 relative overflow-hidden">
    <!-- Background decorative elements -->
    <div class="absolute top-0 left-0 w-full h-1 bg-gradient-to-r from-blue-500 via-purple-500 to-pink-500"></div>
    <div class="absolute top-10 right-10 w-64 h-64 bg-blue-500/5 rounded-full blur-3xl"></div>
    <div class="absolute bottom-10 left-10 w-80 h-80 bg-purple-500/5 rounded-full blur-3xl"></div>
    
    <div class="container mx-auto px-4 relative z-10">
        <div class="text-center mb-16 animate-fade-in">
            <span class="inline-block px-6 py-2 bg-gradient-to-r from-blue-600 to-purple-600 text-white rounded-full text-sm font-medium mb-6 shadow-lg hover:shadow-xl transition-shadow duration-300">DASHBOARD INSIGHTS</span>
            <h2 class="text-4xl md:text-5xl lg:text-6xl font-bold text-gray-900 mb-6 leading-tight">
                Your Complete <span class="text-transparent bg-clip-text bg-gradient-to-r from-blue-600 to-purple-600">CRM Dashboard</span>
            </h2>
            <p class="text-xl text-gray-600 max-w-3xl mx-auto leading-relaxed">
                Track marketing performance, manage client relationships, and drive revenue growth from a single, intuitive dashboard
            </p>
        </div>
        
        <div class="flex flex-col lg:flex-row items-center gap-12 xl:gap-20">
            <!-- CRM Dashboard Image/Visual -->
            <div class="lg:w-1/2 relative">
                <!-- Main Dashboard Container -->
                <div class="relative group">
                    <!-- Dashboard Frame with Glow Effect -->
                    <div class="absolute -inset-4 bg-gradient-to-r from-blue-500 to-purple-500 rounded-3xl opacity-20 group-hover:opacity-30 blur-xl transition-all duration-500"></div>
                    
                    <!-- Dashboard Content -->
                    <div class="relative bg-white rounded-2xl shadow-2xl overflow-hidden transform group-hover:scale-[1.02] transition-all duration-500">
                        <!-- Dashboard Header -->
                        <div class="bg-gradient-to-r from-gray-900 to-gray-800 p-4 flex justify-between items-center">
                            <div class="flex items-center space-x-3">
                                <div class="flex space-x-2">
                                    <div class="w-3 h-3 rounded-full bg-red-400"></div>
                                    <div class="w-3 h-3 rounded-full bg-yellow-400"></div>
                                    <div class="w-3 h-3 rounded-full bg-green-400"></div>
                                </div>
                                <h3 class="text-white font-semibold text-lg">Social Cults CRM Dashboard</h3>
                            </div>
                            <div class="flex items-center space-x-4">
                                <div class="flex items-center space-x-2">
                                    <div class="w-8 h-8 rounded-full bg-gradient-to-r from-blue-500 to-blue-600 flex items-center justify-center">
                                        <i class="fas fa-user text-white text-sm"></i>
                                    </div>
                                    <div class="text-right">
                                        <p class="text-white text-sm font-medium">admin</p>
                                        <p class="text-gray-300 text-xs">admin@gmail.com</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Dashboard Body -->
                        <div class="p-6 bg-white">
                            <!-- Quick Stats -->
                            <div class="mb-8">
                                <h4 class="text-lg font-bold text-gray-900 mb-4 flex items-center">
                                    <i class="fas fa-chart-bar text-blue-500 mr-2"></i>
                                    Dashboard Overview
                                </h4>
                                <p class="text-gray-600 text-sm mb-4">Track your marketing performance and client relationships</p>
                                
                                <div class="grid grid-cols-2 gap-4">
                                    <!-- Total Revenue Card -->
                                    <div class="bg-gradient-to-br from-blue-50 to-blue-100 p-4 rounded-xl border border-blue-200">
                                        <div class="flex justify-between items-start">
                                            <div>
                                                <p class="text-blue-600 text-sm font-medium mb-1">TOTAL REVENUE</p>
                                                <p class="text-2xl font-bold text-gray-900">₹41,000</p>
                                                <div class="flex items-center mt-1">
                                                    <span class="text-green-500 text-xs font-medium flex items-center">
                                                        <i class="fas fa-arrow-up mr-1"></i> +12.5%
                                                    </span>
                                                    <span class="text-gray-500 text-xs ml-2">vs last month</span>
                                                </div>
                                            </div>
                                            <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center">
                                                <i class="fas fa-dollar-sign text-blue-600"></i>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <!-- Active Clients Card -->
                                    <div class="bg-gradient-to-br from-purple-50 to-purple-100 p-4 rounded-xl border border-purple-200">
                                        <div class="flex justify-between items-start">
                                            <div>
                                                <p class="text-purple-600 text-sm font-medium mb-1">ACTIVE CLIENTS</p>
                                                <p class="text-2xl font-bold text-gray-900">2</p>
                                                <div class="flex items-center mt-1">
                                                    <span class="text-green-500 text-xs font-medium flex items-center">
                                                        <i class="fas fa-arrow-up mr-1"></i> +8.2%
                                                    </span>
                                                    <span class="text-gray-500 text-xs ml-2">vs last month</span>
                                                </div>
                                            </div>
                                            <div class="w-10 h-10 bg-purple-100 rounded-lg flex items-center justify-center">
                                                <i class="fas fa-users text-purple-600"></i>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <!-- Active Campaigns Card -->
                                    <div class="bg-gradient-to-br from-green-50 to-green-100 p-4 rounded-xl border border-green-200">
                                        <div class="flex justify-between items-start">
                                            <div>
                                                <p class="text-green-600 text-sm font-medium mb-1">ACTIVE CAMPAIGNS</p>
                                                <p class="text-2xl font-bold text-gray-900">3</p>
                                                <div class="flex items-center mt-1">
                                                    <span class="text-green-500 text-xs font-medium flex items-center">
                                                        <i class="fas fa-arrow-up mr-1"></i> +15.3%
                                                    </span>
                                                    <span class="text-gray-500 text-xs ml-2">vs last month</span>
                                                </div>
                                            </div>
                                            <div class="w-10 h-10 bg-green-100 rounded-lg flex items-center justify-center">
                                                <i class="fas fa-bullhorn text-green-600"></i>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <!-- Average ROI Card -->
                                    <div class="bg-gradient-to-br from-orange-50 to-orange-100 p-4 rounded-xl border border-orange-200">
                                        <div class="flex justify-between items-start">
                                            <div>
                                                <p class="text-orange-600 text-sm font-medium mb-1">AVERAGE ROI</p>
                                                <p class="text-2xl font-bold text-gray-900">24.5%</p>
                                                <div class="flex items-center mt-1">
                                                    <span class="text-green-500 text-xs font-medium flex items-center">
                                                        <i class="fas fa-arrow-up mr-1"></i> +3.1%
                                                    </span>
                                                    <span class="text-gray-500 text-xs ml-2">vs last month</span>
                                                </div>
                                            </div>
                                            <div class="w-10 h-10 bg-orange-100 rounded-lg flex items-center justify-center">
                                                <i class="fas fa-chart-line text-orange-600"></i>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Charts Section -->
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <!-- Revenue Trends -->
                                <div class="bg-gray-50 rounded-xl p-4">
                                    <h5 class="font-bold text-gray-900 mb-3 flex items-center">
                                        <i class="fas fa-trending-up text-blue-500 mr-2"></i>
                                        Revenue Trends
                                    </h5>
                                    <div class="space-y-2">
                                        <div class="flex items-center justify-between p-2 hover:bg-white rounded-lg transition-colors">
                                            <span class="text-gray-700">My Attendance</span>
                                            <div class="w-20 h-2 bg-blue-200 rounded-full overflow-hidden">
                                                <div class="h-full bg-blue-500 w-3/4"></div>
                                            </div>
                                        </div>
                                        <div class="flex items-center justify-between p-2 hover:bg-white rounded-lg transition-colors">
                                            <span class="text-gray-700">Salary</span>
                                            <div class="w-20 h-2 bg-green-200 rounded-full overflow-hidden">
                                                <div class="h-full bg-green-500 w-2/3"></div>
                                            </div>
                                        </div>
                                        <div class="flex items-center justify-between p-2 hover:bg-white rounded-lg transition-colors">
                                            <span class="text-gray-700">Todo</span>
                                            <div class="w-20 h-2 bg-purple-200 rounded-full overflow-hidden">
                                                <div class="h-full bg-purple-500 w-4/5"></div>
                                            </div>
                                        </div>
                                        <div class="flex items-center justify-between p-2 hover:bg-white rounded-lg transition-colors">
                                            <span class="text-gray-700">Task</span>
                                            <div class="w-20 h-2 bg-orange-200 rounded-full overflow-hidden">
                                                <div class="h-full bg-orange-500 w-1/2"></div>
                                            </div>
                                        </div>
                                        <div class="flex items-center justify-between p-2 hover:bg-white rounded-lg transition-colors">
                                            <span class="text-gray-700">Ticket</span>
                                            <div class="w-20 h-2 bg-pink-200 rounded-full overflow-hidden">
                                                <div class="h-full bg-pink-500 w-3/5"></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                
                                <!-- Sales Pipeline -->
                                <div class="bg-gray-50 rounded-xl p-4">
                                    <h5 class="font-bold text-gray-900 mb-3 flex items-center">
                                        <i class="fas fa-filter text-purple-500 mr-2"></i>
                                        Sales Pipeline
                                    </h5>
                                    <div class="h-32 flex items-center justify-center">
                                        <div class="relative w-32 h-32">
                                            <!-- Chart Visualization Placeholder -->
                                            <div class="absolute inset-0 rounded-full border-4 border-gray-200"></div>
                                            <div class="absolute inset-4 rounded-full border-4 border-blue-500" style="clip-path: polygon(50% 50%, 50% 0%, 100% 0%, 100% 100%, 0% 100%, 0% 50%);"></div>
                                            <div class="absolute inset-8 rounded-full border-4 border-green-500" style="clip-path: polygon(50% 50%, 100% 50%, 100% 100%, 0% 100%, 0% 0%, 50% 0%);"></div>
                                            <div class="absolute inset-0 flex items-center justify-center">
                                                <div class="text-center">
                                                    <p class="text-lg font-bold text-gray-900">₹41K</p>
                                                    <p class="text-xs text-gray-600">Pipeline</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <p class="text-center text-sm text-gray-600 mt-2">Revenue & Pipeline chart visualizations</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Floating Stats -->
                <div class="absolute -bottom-6 -left-6 bg-white rounded-xl shadow-xl p-4 w-48 border border-gray-100 transform hover:-translate-y-1 transition-transform duration-300">
                    <div class="flex items-center mb-2">
                        <div class="w-8 h-8 bg-gradient-to-r from-green-100 to-green-200 rounded-full flex items-center justify-center mr-2">
                            <i class="fas fa-arrow-up text-green-600 text-sm"></i>
                        </div>
                        <div>
                            <span class="font-bold text-gray-900 text-lg">₹41,000</span>
                            <p class="text-xs text-gray-600">Total Revenue</p>
                        </div>
                    </div>
                </div>
                
                <div class="absolute -top-6 -right-6 bg-white rounded-xl shadow-xl p-4 w-48 border border-gray-100 transform hover:-translate-y-1 transition-transform duration-300">
                    <div class="flex items-center mb-2">
                        <div class="w-8 h-8 bg-gradient-to-r from-blue-100 to-blue-200 rounded-full flex items-center justify-center mr-2">
                            <i class="fas fa-chart-line text-blue-600 text-sm"></i>
                        </div>
                        <div>
                            <span class="font-bold text-gray-900 text-lg">24.5%</span>
                            <p class="text-xs text-gray-600">Average ROI</p>
                        </div>
                    </div>
                </div>
                
                <!-- Decorative Badge -->
                <div class="absolute -bottom-3 right-10 bg-gradient-to-r from-blue-600 to-purple-600 text-white px-4 py-2 rounded-full text-sm font-medium shadow-lg">
                    Real-time Analytics
                </div>
            </div>
            
            <!-- CRM Features & Benefits -->
            <div class="lg:w-1/2">
                <div class="mb-8">
                    <h3 class="text-3xl lg:text-4xl font-bold text-gray-900 mb-4 leading-tight">
                        Everything You Need to 
                        <span class="text-transparent bg-clip-text bg-gradient-to-r from-blue-600 to-purple-600">Scale Your Business</span>
                    </h3>
                    <p class="text-gray-600 text-lg leading-relaxed mb-6">
                        Social Cults CRM gives you complete visibility into your marketing performance, client relationships, 
                        and revenue growth—all in one intuitive dashboard.
                    </p>
                    
                    <div class="flex items-center space-x-4 mb-6">
                        <div class="flex items-center text-green-600">
                            <i class="fas fa-check-circle mr-2"></i>
                            <span class="font-medium">Real-time Analytics</span>
                        </div>
                        <div class="flex items-center text-blue-600">
                            <i class="fas fa-check-circle mr-2"></i>
                            <span class="font-medium">Automated Reports</span>
                        </div>
                        <div class="flex items-center text-purple-600">
                            <i class="fas fa-check-circle mr-2"></i>
                            <span class="font-medium">Custom Dashboards</span>
                        </div>
                    </div>
                </div>
                
                <!-- Key Features -->
                <div class="space-y-6 mb-8">
                    <!-- Feature 1 -->
                    <div class="flex items-start group p-4 rounded-xl hover:bg-white hover:shadow-xl transition-all duration-300">
                        <div class="w-12 h-12 bg-gradient-to-r from-blue-100 to-blue-200 rounded-xl flex items-center justify-center mr-4 flex-shrink-0 group-hover:scale-110 transition-transform duration-300">
                            <i class="fas fa-chart-pie text-blue-600 text-xl"></i>
                        </div>
                        <div>
                            <h4 class="text-xl font-bold text-gray-900 mb-2">Comprehensive Analytics</h4>
                            <p class="text-gray-600">
                                Track revenue trends, campaign performance, and client metrics with detailed visualizations and real-time updates.
                            </p>
                        </div>
                    </div>
                    
                    <!-- Feature 2 -->
                    <div class="flex items-start group p-4 rounded-xl hover:bg-white hover:shadow-xl transition-all duration-300">
                        <div class="w-12 h-12 bg-gradient-to-r from-green-100 to-green-200 rounded-xl flex items-center justify-center mr-4 flex-shrink-0 group-hover:scale-110 transition-transform duration-300">
                            <i class="fas fa-users text-green-600 text-xl"></i>
                        </div>
                        <div>
                            <h4 class="text-xl font-bold text-gray-900 mb-2">Client Management</h4>
                            <p class="text-gray-600">
                                Manage active clients, track interactions, and monitor relationship health with intuitive client profiles.
                            </p>
                        </div>
                    </div>
                    
                    <!-- Feature 3 -->
                    <div class="flex items-start group p-4 rounded-xl hover:bg-white hover:shadow-xl transition-all duration-300">
                        <div class="w-12 h-12 bg-gradient-to-r from-purple-100 to-purple-200 rounded-xl flex items-center justify-center mr-4 flex-shrink-0 group-hover:scale-110 transition-transform duration-300">
                            <i class="fas fa-bullseye text-purple-600 text-xl"></i>
                        </div>
                        <div>
                            <h4 class="text-xl font-bold text-gray-900 mb-2">Campaign Tracking</h4>
                            <p class="text-gray-600">
                                Monitor active campaigns, measure ROI, and optimize marketing strategies based on performance data.
                            </p>
                        </div>
                    </div>
                </div>
                
                <!-- Performance Highlights -->
                <div class="mb-8 bg-gradient-to-r from-gray-50 to-white rounded-2xl p-6 border border-gray-200">
                    <h4 class="text-xl font-bold text-gray-900 mb-4 text-center">Performance Highlights</h4>
                    <div class="grid grid-cols-2 gap-4">
                        <div class="text-center p-3 bg-white rounded-xl shadow-sm">
                            <div class="text-2xl font-bold text-blue-600 mb-1">+12.5%</div>
                            <div class="text-gray-700 text-sm">Revenue Growth</div>
                        </div>
                        <div class="text-center p-3 bg-white rounded-xl shadow-sm">
                            <div class="text-2xl font-bold text-green-600 mb-1">+15.3%</div>
                            <div class="text-gray-700 text-sm">Campaign Uptick</div>
                        </div>
                        <div class="text-center p-3 bg-white rounded-xl shadow-sm">
                            <div class="text-2xl font-bold text-purple-600 mb-1">+8.2%</div>
                            <div class="text-gray-700 text-sm">Client Growth</div>
                        </div>
                        <div class="text-center p-3 bg-white rounded-xl shadow-sm">
                            <div class="text-2xl font-bold text-orange-600 mb-1">+3.1%</div>
                            <div class="text-gray-700 text-sm">ROI Increase</div>
                        </div>
                    </div>
                </div>
                
                <!-- CTA Buttons -->
                <div class="flex flex-col sm:flex-row gap-4">
                    <a href="#" class="flex-1 bg-gradient-to-r from-blue-600 to-blue-700 hover:from-blue-700 hover:to-blue-800 text-white px-8 py-4 rounded-xl font-bold text-center transition-all duration-300 shadow-lg hover:shadow-xl transform hover:-translate-y-1 flex items-center justify-center">
                        <i class="fas fa-rocket mr-3"></i>
                        <span>Get Started</span>
                    </a>
                    <a href="#" class="flex-1 border-2 border-blue-600 text-blue-600 hover:bg-blue-50 px-8 py-4 rounded-xl font-bold text-center transition-all duration-300 flex items-center justify-center group">
                        <i class="fas fa-play-circle mr-3 group-hover:scale-110 transition-transform"></i>
                        <span>Watch Demo</span>
                    </a>
                </div>
                
                <!-- Trust Indicator -->
                <div class="mt-6 text-center text-gray-500 text-sm">
                    <p><i class="fas fa-shield-alt text-green-500 mr-2"></i> Trusted by 500+ businesses worldwide</p>
                </div>
            </div>
        </div>
        
        <!-- Additional Stats -->
        <div class="mt-20 grid grid-cols-1 md:grid-cols-3 gap-6">
            <div class="text-center p-6 bg-white rounded-2xl shadow-lg hover:shadow-xl transition-shadow duration-300">
                <div class="text-3xl font-bold text-blue-600 mb-2">500+</div>
                <h4 class="font-bold text-gray-900 mb-2">Happy Customers</h4>
                <p class="text-gray-600 text-sm">Businesses growing with our CRM</p>
            </div>
            <div class="text-center p-6 bg-white rounded-2xl shadow-lg hover:shadow-xl transition-shadow duration-300">
                <div class="text-3xl font-bold text-green-600 mb-2">24/7</div>
                <h4 class="font-bold text-gray-900 mb-2">Support</h4>
                <p class="text-gray-600 text-sm">Dedicated customer success team</p>
            </div>
            <div class="text-center p-6 bg-white rounded-2xl shadow-lg hover:shadow-xl transition-shadow duration-300">
                <div class="text-3xl font-bold text-purple-600 mb-2">99.9%</div>
                <h4 class="font-bold text-gray-900 mb-2">Uptime</h4>
                <p class="text-gray-600 text-sm">Reliable and secure platform</p>
            </div>
        </div>
    </div>
</section>
    <!-- Pricing Section -->
    <section id="pricing" class="py-20 bg-white">
        <div class="container mx-auto px-4">
            <div class="text-center mb-16">
                <h2 class="text-3xl md:text-4xl font-bold text-gray-800 mb-4">Simple, Transparent Pricing</h2>
                <p class="text-xl text-gray-600 max-w-3xl mx-auto">Start for free, upgrade as you grow. No hidden fees, no long-term contracts.</p>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-2 gap-8 mx-4 md:mx-96">
                <!-- Free Plan -->
                {{-- <div class="bg-white rounded-xl shadow-md border border-gray-200 p-6 pricing-card">
                    <div class="text-center mb-6">
                        <h3 class="text-xl font-bold text-gray-800 mb-2">Free</h3>
                        <div class="flex justify-center items-baseline mb-4">
                            <span class="text-3xl font-bold text-gray-800">₹0</span>
                            <span class="text-gray-600 ml-1">/month</span>
                        </div>
                        <p class="text-gray-600">Perfect for small teams getting started</p>
                    </div>
                    <ul class="text-gray-600 space-y-3 mb-6">
                        <li class="flex items-start">
                            <i class="fas fa-check text-accent mt-1 mr-2"></i>
                            <span>Up to 10 users</span>
                        </li>
                        <li class="flex items-start">
                            <i class="fas fa-check text-accent mt-1 mr-2"></i>
                            <span>Basic contact management</span>
                        </li>
                        <li class="flex items-start">
                            <i class="fas fa-check text-accent mt-1 mr-2"></i>
                            <span>Email integration</span>
                        </li>
                        <li class="flex items-start">
                            <i class="fas fa-check text-accent mt-1 mr-2"></i>
                            <span>Standard support</span>
                        </li>
                    </ul>
                    <a href="#" class="block w-full bg-gray-100 hover:bg-gray-200 text-gray-800 text-center py-3 rounded-lg font-medium transition duration-300">Get Started</a>
                </div> --}}
                
                <!-- Pro Plan -->
                <div class="bg-white rounded-xl shadow-lg border-2 border-primary p-6 pricing-card relative">
                    <div class="absolute top-0 right-0 bg-primary text-white px-4 py-1 rounded-bl-lg rounded-tr-lg text-sm font-medium">Most Popular</div>
                    <div class="text-center mb-6">
                        <h3 class="text-xl font-bold text-gray-800 mb-2">Pro</h3>
                        <div class="flex justify-center items-baseline mb-4">
                            <span class="text-3xl font-bold text-gray-800">₹4,999</span>
                            <span class="text-gray-600 ml-1">/user/month</span>
                        </div>
                        <p class="text-gray-600">Advanced features for growing teams</p>
                    </div>
                    <ul class="text-gray-600 space-y-3 mb-6">
                        <li class="flex items-start">
                            <i class="fas fa-check text-accent mt-1 mr-2"></i>
                            <span>Up to 50 users</span>
                        </li>
                        <li class="flex items-start">
                            <i class="fas fa-check text-accent mt-1 mr-2"></i>
                            <span>Advanced analytics</span>
                        </li>
                        <li class="flex items-start">
                            <i class="fas fa-check text-accent mt-1 mr-2"></i>
                            <span>Sales automation</span>
                        </li>
                        <li class="flex items-start">
                            <i class="fas fa-check text-accent mt-1 mr-2"></i>
                            <span>Priority support</span>
                        </li>
                        <li class="flex items-start">
                            <i class="fas fa-check text-accent mt-1 mr-2"></i>
                            <span>Custom reporting</span>
                        </li>
                    </ul>
                    <a href="#" class="block w-full bg-primary hover:bg-secondary text-white text-center py-3 rounded-lg font-medium transition duration-300">Start Now</a>
                </div>
                
                
                <!-- Enterprise Plan -->
                <div class="bg-white rounded-xl shadow-md border border-gray-200 p-6 pricing-card">
                    <div class="text-center mb-6">
                        <h3 class="text-xl font-bold text-gray-800 mb-2">Enterprise</h3>
                        <div class="flex justify-center items-baseline mb-4">
                            <span class="text-3xl font-bold text-gray-800">Custom</span>
                        </div>
                        <p class="text-gray-600">Tailored solutions for large organizations</p>
                    </div>
                    <ul class="text-gray-600 space-y-3 mb-6">
                        <li class="flex items-start">
                            <i class="fas fa-check text-accent mt-1 mr-2"></i>
                            <span>All Business features</span>
                        </li>
                        <li class="flex items-start">
                            <i class="fas fa-check text-accent mt-1 mr-2"></i>
                            <span>Enterprise-grade security</span>
                        </li>
                        <li class="flex items-start">
                            <i class="fas fa-check text-accent mt-1 mr-2"></i>
                            <span>Custom development</span>
                        </li>
                        <li class="flex items-start">
                            <i class="fas fa-check text-accent mt-1 mr-2"></i>
                            <span>24/7 premium support</span>
                        </li>
                        <li class="flex items-start">
                            <i class="fas fa-check text-accent mt-1 mr-2"></i>
                            <span>On-premise deployment</span>
                        </li>
                    </ul>
                    <a href="#" class="block w-full bg-gray-100 hover:bg-gray-200 text-gray-800 text-center py-3 rounded-lg font-medium transition duration-300">Contact Sales</a>
                </div>
            </div>
            
            <div class="mt-12 text-center">
                <p class="text-gray-600">All plans include a 30-day free trial. No credit card required.</p>
            </div>
        </div>
    </section>

    <!-- FAQ Section -->
    <!-- Enhanced FAQ Section -->
<section class="pb-16 bg-white">
    <div class="container mx-auto px-4">
        <div class="text-center mb-12">
            <span class="inline-block px-5 py-2 bg-purple-100 text-purple-700 rounded-full text-sm font-medium mb-4">
                FAQ
            </span>
            <h2 class="text-3xl md:text-4xl font-bold text-gray-800 mb-4">
                Frequently Asked
                <span class="text-purple-600">Questions</span>
            </h2>
            <p class="text-gray-600 max-w-2xl mx-auto">
                Get answers to common questions about Social Cults CRM
            </p>
        </div>
        
        <!-- FAQ Accordion -->
        <div class="max-w-3xl mx-auto">
            <!-- FAQ Item 1 -->
            <div class="mb-4">
                <button class="faq-question w-full text-left bg-gray-50 hover:bg-gray-100 rounded-lg p-6 flex justify-between items-center transition-colors">
                    <div class="flex items-center">
                        <div class="w-8 h-8 bg-purple-100 rounded-lg flex items-center justify-center mr-4">
                            <i class="fas fa-rocket text-purple-600 text-sm"></i>
                        </div>
                        <h3 class="text-lg font-semibold text-gray-800">How quickly can I get started?</h3>
                    </div>
                    <div class="faq-icon text-purple-600">
                        <i class="fas fa-chevron-down transition-transform duration-300"></i>
                    </div>
                </button>
                <div class="faq-answer bg-white rounded-b-lg px-6 overflow-hidden max-h-0 transition-all duration-300">
                    <div class="py-6 border-t border-gray-100">
                        <p class="text-gray-600 mb-4">Most teams are up and running in less than a day. Our intuitive interface and guided setup make onboarding quick and easy.</p>
                        <div class="bg-purple-50 rounded-lg p-4">
                            <p class="text-sm text-purple-700 font-medium">
                                <i class="fas fa-lightbulb mr-2"></i>
                                Connect your social accounts first to start seeing insights immediately.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- FAQ Item 2 -->
            <div class="mb-4">
                <button class="faq-question w-full text-left bg-gray-50 hover:bg-gray-100 rounded-lg p-6 flex justify-between items-center transition-colors">
                    <div class="flex items-center">
                        <div class="w-8 h-8 bg-blue-100 rounded-lg flex items-center justify-center mr-4">
                            <i class="fas fa-database text-blue-600 text-sm"></i>
                        </div>
                        <h3 class="text-lg font-semibold text-gray-800">Can I import existing data?</h3>
                    </div>
                    <div class="faq-icon text-blue-600">
                        <i class="fas fa-chevron-down transition-transform duration-300"></i>
                    </div>
                </button>
                <div class="faq-answer bg-white rounded-b-lg px-6 overflow-hidden max-h-0 transition-all duration-300">
                    <div class="py-6 border-t border-gray-100">
                        <p class="text-gray-600 mb-4">Yes, we provide easy import tools for data from Excel, CSV, and most popular CRM platforms. Our support team can assist with migration.</p>
                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                            <div class="text-center p-3 bg-blue-50 rounded-lg">
                                <i class="fas fa-file-excel text-blue-600 mb-2"></i>
                                <p class="text-sm font-medium text-blue-700">Excel/CSV</p>
                            </div>
                            <div class="text-center p-3 bg-blue-50 rounded-lg">
                                <i class="fas fa-cloud text-blue-600 mb-2"></i>
                                <p class="text-sm font-medium text-blue-700">API Integration</p>
                            </div>
                            <div class="text-center p-3 bg-blue-50 rounded-lg">
                                <i class="fas fa-tools text-blue-600 mb-2"></i>
                                <p class="text-sm font-medium text-blue-700">Migration Support</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- FAQ Item 3 -->
            <div class="mb-4">
                <button class="faq-question w-full text-left bg-gray-50 hover:bg-gray-100 rounded-lg p-6 flex justify-between items-center transition-colors">
                    <div class="flex items-center">
                        <div class="w-8 h-8 bg-green-100 rounded-lg flex items-center justify-center mr-4">
                            <i class="fas fa-headset text-green-600 text-sm"></i>
                        </div>
                        <h3 class="text-lg font-semibold text-gray-800">What support do you offer?</h3>
                    </div>
                    <div class="faq-icon text-green-600">
                        <i class="fas fa-chevron-down transition-transform duration-300"></i>
                    </div>
                </button>
                <div class="faq-answer bg-white rounded-b-lg px-6 overflow-hidden max-h-0 transition-all duration-300">
                    <div class="py-6 border-t border-gray-100">
                        <p class="text-gray-600 mb-4">We offer comprehensive support including documentation, video tutorials, email support, and live chat. Higher plans include phone support and dedicated account managers.</p>
                        <div class="space-y-3">
                            <div class="flex items-center">
                                <i class="fas fa-book text-green-600 mr-3"></i>
                                <span class="text-sm text-gray-700">Documentation & Guides</span>
                            </div>
                            <div class="flex items-center">
                                <i class="fas fa-video text-green-600 mr-3"></i>
                                <span class="text-sm text-gray-700">Video Tutorials</span>
                            </div>
                            <div class="flex items-center">
                                <i class="fas fa-comments text-green-600 mr-3"></i>
                                <span class="text-sm text-gray-700">Live Chat Support</span>
                            </div>
                            <div class="flex items-center">
                                <i class="fas fa-phone text-green-600 mr-3"></i>
                                <span class="text-sm text-gray-700">Phone Support (Premium)</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- FAQ Item 4 -->
            <div class="mb-4">
                <button class="faq-question w-full text-left bg-gray-50 hover:bg-gray-100 rounded-lg p-6 flex justify-between items-center transition-colors">
                    <div class="flex items-center">
                        <div class="w-8 h-8 bg-yellow-100 rounded-lg flex items-center justify-center mr-4">
                            <i class="fas fa-file-contract text-yellow-600 text-sm"></i>
                        </div>
                        <h3 class="text-lg font-semibold text-gray-800">Is there a contract?</h3>
                    </div>
                    <div class="faq-icon text-yellow-600">
                        <i class="fas fa-chevron-down transition-transform duration-300"></i>
                    </div>
                </button>
                <div class="faq-answer bg-white rounded-b-lg px-6 overflow-hidden max-h-0 transition-all duration-300">
                    <div class="py-6 border-t border-gray-100">
                        <p class="text-gray-600 mb-4">All plans are month-to-month with no long-term contracts. You can cancel anytime with no penalties.</p>
                        <div class="bg-yellow-50 rounded-lg p-4">
                            <p class="text-sm text-yellow-700 font-medium">
                                <i class="fas fa-shield-alt mr-2"></i>
                                We believe in earning your business every month - no lock-in periods.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- FAQ Item 5 -->
            <div class="mb-4">
                <button class="faq-question w-full text-left bg-gray-50 hover:bg-gray-100 rounded-lg p-6 flex justify-between items-center transition-colors">
                    <div class="flex items-center">
                        <div class="w-8 h-8 bg-indigo-100 rounded-lg flex items-center justify-center mr-4">
                            <i class="fas fa-share-alt text-indigo-600 text-sm"></i>
                        </div>
                        <h3 class="text-lg font-semibold text-gray-800">Which platforms do you support?</h3>
                    </div>
                    <div class="faq-icon text-indigo-600">
                        <i class="fas fa-chevron-down transition-transform duration-300"></i>
                    </div>
                </button>
                <div class="faq-answer bg-white rounded-b-lg px-6 overflow-hidden max-h-0 transition-all duration-300">
                    <div class="py-6 border-t border-gray-100">
                        <p class="text-gray-600 mb-4">Social Cults supports all major social media platforms including Instagram, Facebook, Twitter, LinkedIn, TikTok, and YouTube.</p>
                        <div class="flex flex-wrap gap-2">
                            <span class="px-3 py-1 bg-indigo-100 text-indigo-700 rounded-full text-sm">
                                <i class="fab fa-instagram mr-1"></i> Instagram
                            </span>
                            <span class="px-3 py-1 bg-blue-100 text-blue-700 rounded-full text-sm">
                                <i class="fab fa-facebook mr-1"></i> Facebook
                            </span>
                            <span class="px-3 py-1 bg-sky-100 text-sky-700 rounded-full text-sm">
                                <i class="fab fa-twitter mr-1"></i> Twitter
                            </span>
                            <span class="px-3 py-1 bg-blue-800 text-white rounded-full text-sm">
                                <i class="fab fa-linkedin mr-1"></i> LinkedIn
                            </span>
                            <span class="px-3 py-1 bg-black text-white rounded-full text-sm">
                                <i class="fab fa-tiktok mr-1"></i> TikTok
                            </span>
                            <span class="px-3 py-1 bg-red-100 text-red-700 rounded-full text-sm">
                                <i class="fab fa-youtube mr-1"></i> YouTube
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
    
    </div>
</section>

<style>
/* FAQ animation styles */
.faq-answer.active {
    max-height: 500px;
}

.faq-icon.active i {
    transform: rotate(180deg);
}

/* Simple transitions */
.faq-question {
    transition: all 0.3s ease;
}

.faq-answer {
    transition: max-height 0.3s ease-out;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const faqQuestions = document.querySelectorAll('.faq-question');
    
    faqQuestions.forEach(question => {
        question.addEventListener('click', function() {
            const answer = this.nextElementSibling;
            const icon = this.querySelector('.faq-icon i');
            
            // Close all other FAQ items
            document.querySelectorAll('.faq-answer').forEach(item => {
                if (item !== answer) {
                    item.classList.remove('active');
                    item.style.maxHeight = null;
                }
            });
            
            document.querySelectorAll('.faq-icon i').forEach(item => {
                if (item !== icon) {
                    item.style.transform = 'rotate(0deg)';
                }
            });
            
            // Toggle current FAQ item
            if (answer.classList.contains('active')) {
                answer.classList.remove('active');
                answer.style.maxHeight = null;
                icon.style.transform = 'rotate(0deg)';
            } else {
                answer.classList.add('active');
                answer.style.maxHeight = answer.scrollHeight + 'px';
                icon.style.transform = 'rotate(180deg)';
            }
        });
    });
    
    // Optional: Open first FAQ by default
    const firstQuestion = document.querySelector('.faq-question');
    if (firstQuestion) {
        const firstAnswer = firstQuestion.nextElementSibling;
        const firstIcon = firstQuestion.querySelector('.faq-icon i');
        
        firstAnswer.classList.add('active');
        firstAnswer.style.maxHeight = firstAnswer.scrollHeight + 'px';
        firstIcon.style.transform = 'rotate(180deg)';
    }
});
</script>
   


    <!-- Footer -->
  <footer class="bg-gradient-to-br from-gray-900 via-gray-800 to-gray-900 text-white py-12 relative overflow-hidden">
    <!-- Background decorative elements -->
    <div class="absolute top-0 left-0 w-full h-1 bg-gradient-to-r from-blue-500 via-purple-500 to-pink-500"></div>
    <div class="absolute top-10 right-10 w-32 h-32 bg-blue-500/5 rounded-full blur-3xl"></div>
    <div class="absolute bottom-10 left-10 w-40 h-40 bg-purple-500/5 rounded-full blur-3xl"></div>
    
    <div class="container mx-auto px-4 relative z-10">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 mb-12">
            <!-- Brand Column -->
            <div class="lg:col-span-3">
                <div class="flex items-center space-x-2 mb-6">
    <div
        class="w-12 h-12 bg-white rounded-xl flex items-center justify-center shadow-lg transform hover:scale-105 transition-transform duration-300 overflow-hidden"
    >
        <img
            src="https://socialcults.com/images/client/logo.png"
            alt="Social Cults Logo"
            class="w-9 h-9 object-contain"
        />
    </div>

    <div>
        <h2
            class="text-2xl font-bold bg-gradient-to-r from-blue-400 to-purple-400 bg-clip-text text-transparent"
        >
            Social Cults CRM
        </h2>
        <p class="text-blue-300 text-sm font-medium">
            Uniting Communities, Empowering Connections
        </p>
    </div>
</div>

                <p class="text-gray-300 mb-6 leading-relaxed">
                    The premier CRM platform designed exclusively for community leaders, social groups, and movement builders. 
                    Foster deeper connections and streamline your community management.
                </p>
                <div class="flex space-x-4">
                    <a href="#" class="w-10 h-10 bg-gray-800 hover:bg-blue-600 rounded-full flex items-center justify-center text-white transition-all duration-300 transform hover:-translate-y-1 shadow-lg hover:shadow-blue-500/20">
                        <i class="fab fa-twitter"></i>
                    </a>
                    <a href="#" class="w-10 h-10 bg-gray-800 hover:bg-blue-800 rounded-full flex items-center justify-center text-white transition-all duration-300 transform hover:-translate-y-1 shadow-lg hover:shadow-blue-900/20">
                        <i class="fab fa-facebook-f"></i>
                    </a>
                    <a href="#" class="w-10 h-10 bg-gray-800 hover:bg-blue-700 rounded-full flex items-center justify-center text-white transition-all duration-300 transform hover:-translate-y-1 shadow-lg hover:shadow-blue-700/20">
                        <i class="fab fa-linkedin-in"></i>
                    </a>
                    <a href="#" class="w-10 h-10 bg-gray-800 hover:bg-gradient-to-r from-purple-600 to-pink-600 rounded-full flex items-center justify-center text-white transition-all duration-300 transform hover:-translate-y-1 shadow-lg hover:shadow-purple-500/20">
                        <i class="fab fa-instagram"></i>
                    </a>
                    <a href="#" class="w-10 h-10 bg-gray-800 hover:bg-red-600 rounded-full flex items-center justify-center text-white transition-all duration-300 transform hover:-translate-y-1 shadow-lg hover:shadow-red-500/20">
                        <i class="fab fa-youtube"></i>
                    </a>
                </div>
            </div>
            
            <!-- Quick Links -->
            <div class="lg:col-span-2">
                <h3 class="text-lg font-bold mb-6 pb-2 border-b border-gray-700 flex items-center">
                    Platform
                </h3>
                <ul class="space-y-3">
                    <li>
                        <a href="#" class="text-gray-300 hover:text-blue-300 flex items-center group transition-colors duration-200">
                            {{-- <i class="fas fa-chevron-right text-xs text-blue-500 opacity-0 group-hover:opacity-100 mr-2 transition-all duration-200"></i> --}}
                            Features
                        </a>
                    </li>
                    <li>
                        <a href="#" class="text-gray-300 hover:text-blue-300 flex items-center group transition-colors duration-200">
                            <i class="fas fa-chevron-right text-xs text-blue-500 opacity-0 group-hover:opacity-100 mr-2 transition-all duration-200"></i>
                            Pricing
                        </a>
                    </li>
                    <li>
                        <a href="#" class="text-gray-300 hover:text-blue-300 flex items-center group transition-colors duration-200">
                            <i class="fas fa-chevron-right text-xs text-blue-500 opacity-0 group-hover:opacity-100 mr-2 transition-all duration-200"></i>
                            Integrations
                        </a>
                    </li>
                    <li>
                        <a href="#" class="text-gray-300 hover:text-blue-300 flex items-center group transition-colors duration-200">
                            <i class="fas fa-chevron-right text-xs text-blue-500 opacity-0 group-hover:opacity-100 mr-2 transition-all duration-200"></i>
                            API Docs
                        </a>
                    </li>
                </ul>
            </div>
            
            <!-- Resources -->
            <div class="lg:col-span-2">
                <h3 class="text-lg font-bold mb-6 pb-2 border-b border-gray-700 flex items-center">
                    Resources
                </h3>
                <ul class="space-y-3">
                    <li>
                        <a href="#" class="text-gray-300 hover:text-purple-300 flex items-center group transition-colors duration-200">
                            <i class="fas fa-chevron-right text-xs text-purple-500 opacity-0 group-hover:opacity-100 mr-2 transition-all duration-200"></i>
                            Documentation
                        </a>
                    </li>
                    <li>
                        <a href="#" class="text-gray-300 hover:text-purple-300 flex items-center group transition-colors duration-200">
                            <i class="fas fa-chevron-right text-xs text-purple-500 opacity-0 group-hover:opacity-100 mr-2 transition-all duration-200"></i>
                            Tutorials
                        </a>
                    </li>
                    <li>
                        <a href="#" class="text-gray-300 hover:text-purple-300 flex items-center group transition-colors duration-200">
                            <i class="fas fa-chevron-right text-xs text-purple-500 opacity-0 group-hover:opacity-100 mr-2 transition-all duration-200"></i>
                            Community Blog
                        </a>
                    </li>
                    <li>
                        <a href="#" class="text-gray-300 hover:text-purple-300 flex items-center group transition-colors duration-200">
                            <i class="fas fa-chevron-right text-xs text-purple-500 opacity-0 group-hover:opacity-100 mr-2 transition-all duration-200"></i>
                            Case Studies
                        </a>
                    </li>
                </ul>
            </div>
            
            <!-- Company & Newsletter -->
            <div class="lg:col-span-4">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <div>
                        <h3 class="text-lg font-bold mb-6 pb-2 border-b border-gray-700 flex items-center">
                            Company
                        </h3>
                        <ul class="space-y-3">
                            <li>
                                <a href="#" class="text-gray-300 hover:text-pink-300 flex items-center group transition-colors duration-200">
                                    <i class="fas fa-chevron-right text-xs text-pink-500 opacity-0 group-hover:opacity-100 mr-2 transition-all duration-200"></i>
                                    About Us
                                </a>
                            </li>
                            <li>
                                <a href="#" class="text-gray-300 hover:text-pink-300 flex items-center group transition-colors duration-200">
                                    <i class="fas fa-chevron-right text-xs text-pink-500 opacity-0 group-hover:opacity-100 mr-2 transition-all duration-200"></i>
                                    Careers
                                </a>
                            </li>
                            <li>
                                <a href="#" class="text-gray-300 hover:text-pink-300 flex items-center group transition-colors duration-200">
                                    <i class="fas fa-chevron-right text-xs text-pink-500 opacity-0 group-hover:opacity-100 mr-2 transition-all duration-200"></i>
                                    Contact
                                </a>
                            </li>
                            <li>
                                <a href="#" class="text-gray-300 hover:text-pink-300 flex items-center group transition-colors duration-200">
                                    <i class="fas fa-chevron-right text-xs text-pink-500 opacity-0 group-hover:opacity-100 mr-2 transition-all duration-200"></i>
                                    Privacy Policy
                                </a>
                            </li>
                        </ul>
                    </div>
                    
                    <div>
                        <h3 class="text-lg font-bold mb-6 pb-2 border-b border-gray-700 flex items-center">
                            Newsletter
                        </h3>
                        <p class="text-gray-300 text-sm mb-4">Get community building tips and updates</p>
                        <div class="flex flex-col sm:flex-row gap-2">
                            <input 
                                type="email" 
                                placeholder="Your email" 
                                class="flex-grow px-4 py-2 bg-gray-800 border border-gray-700 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent text-white"
                            >
                            <button class="px-2 py-2 bg-gradient-to-r from-blue-600 to-purple-600 text-white font-semibold rounded-lg hover:from-blue-700 hover:to-purple-700 transition-all duration-300 shadow-lg hover:shadow-blue-500/20">
                                Subscribe
                            </button>
                        </div>
                        <p class="text-gray-400 text-xs mt-3">No spam, unsubscribe anytime</p>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Bottom Bar -->
        <div class="border-t border-gray-700 pt-8">
            <div class="flex flex-col md:flex-row justify-between items-center gap-4">
                <div class="text-gray-400 text-sm">
                    <p>&copy; 2023 <span class="font-bold text-blue-300">Social Cults CRM</span>. All rights reserved.</p>
                </div>
                
                <div class="flex items-center space-x-6 text-sm">
                    <a href="#" class="text-gray-400 hover:text-white transition-colors duration-200">Terms of Service</a>
                    <a href="#" class="text-gray-400 hover:text-white transition-colors duration-200">Privacy Policy</a>
                    <a href="#" class="text-gray-400 hover:text-white transition-colors duration-200">Cookie Policy</a>
                    <a href="#" class="text-gray-400 hover:text-white transition-colors duration-200">GDPR</a>
                </div>
            </div>
            
            <!-- Trust Badges -->
            <div class="flex flex-wrap justify-center items-center gap-6 mt-8 pt-6 border-t border-gray-700">
                <div class="flex items-center space-x-2">
                    <i class="fas fa-shield-alt text-green-400"></i>
                    <span class="text-gray-300 text-sm">SOC 2 Certified</span>
                </div>
                <div class="flex items-center space-x-2">
                    <i class="fas fa-lock text-blue-400"></i>
                    <span class="text-gray-300 text-sm">GDPR Compliant</span>
                </div>
                <div class="flex items-center space-x-2">
                    <i class="fas fa-heart text-red-400"></i>
                    <span class="text-gray-300 text-sm">1000+ Communities</span>
                </div>
            </div>
        </div>
    </div>
</footer>

    <script>
        // Mobile menu toggle
        document.getElementById('menu-toggle').addEventListener('click', function() {
            const menu = document.getElementById('mobile-menu');
            menu.classList.toggle('hidden');
        });

        // Smooth scrolling for anchor links
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
                
                const targetId = this.getAttribute('href');
                if(targetId === '#') return;
                
                const targetElement = document.querySelector(targetId);
                if(targetElement) {
                    window.scrollTo({
                        top: targetElement.offsetTop - 80,
                        behavior: 'smooth'
                    });
                    
                    // Close mobile menu if open
                    const mobileMenu = document.getElementById('mobile-menu');
                    if(!mobileMenu.classList.contains('hidden')) {
                        mobileMenu.classList.add('hidden');
                    }
                }
            });
        });
    </script>
</body>
</html>