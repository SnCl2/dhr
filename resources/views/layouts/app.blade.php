<!DOCTYPE html>
<html lang="en" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Manpower Hiring & Employee Management System')</title>
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Outfit:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Inter', 'sans-serif'],
                        outfit: ['Outfit', 'sans-serif'],
                    },
                    colors: {
                        primary: {
                            50: '#eff6ff',
                            100: '#dbeafe',
                            500: '#3b82f6',
                            600: '#2563eb',
                            700: '#1d4ed8',
                            800: '#1e40af',
                            900: '#1e3a8a',
                        },
                        purple: {
                            50: '#eff6ff',
                            100: '#dbeafe',
                            200: '#bfdbfe',
                            300: '#93c5fd',
                            400: '#60a5fa',
                            500: '#3b82f6',
                            600: '#2563eb',
                            650: '#1d4ed8',
                            700: '#1d4ed8',
                            800: '#1e40af',
                            900: '#1e3a8a',
                            950: '#172554',
                        },
                        indigo: {
                            50: '#f0f9ff',
                            100: '#e0f2fe',
                            200: '#bae6fd',
                            300: '#7dd3fc',
                            400: '#38bdf8',
                            500: '#0ea5e9',
                            600: '#0284c7',
                            700: '#0369a1',
                            800: '#075985',
                            900: '#0c4a6e',
                            950: '#082f49',
                        },
                        pink: {
                            50: '#f8fafc',
                            100: '#f1f5f9',
                            200: '#e2e8f0',
                            300: '#cbd5e1',
                            400: '#94a3b8',
                            500: '#3b82f6',
                            600: '#2563eb',
                            700: '#1d4ed8',
                            800: '#1e40af',
                            900: '#1e3a8a',
                        }
                    }
                }
            }
        }
    </script>
    <!-- FontAwesome for icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        .glass {
            background: rgba(255, 255, 255, 0.45);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            border: 1px solid rgba(255, 255, 255, 0.25);
        }
        .glass-dark {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            border: 1px solid rgba(229, 231, 235, 0.7);
        }
        .gradient-bg {
            background: radial-gradient(circle at 10% 20%, rgb(243, 244, 246) 0%, rgb(224, 231, 255) 90%);
        }
        .gradient-text {
            background: linear-gradient(135deg, #2563eb 0%, #38bdf8 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }
        body {
            font-family: 'Inter', sans-serif;
        }
        /* Light theme overrides for form inputs */
        input, select, textarea {
            background-color: #ffffff !important;
            border-color: #cbd5e1 !important;
            color: #1e293b !important;
        }
        input::placeholder, textarea::placeholder {
            color: #94a3b8 !important;
        }
        label {
            color: #475569 !important;
        }
    </style>
</head>
<body class="min-h-screen bg-slate-50 text-slate-800 flex flex-col selection:bg-purple-500 selection:text-white">

    <!-- Header Navigation -->
    <header class="sticky top-0 z-50 glass-dark border-b border-slate-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-20">
                <!-- Logo -->
                <div class="flex items-center">
                    <a href="{{ route('home') }}" class="flex items-center space-x-3 group">
                        <div class="w-10 h-10 rounded-xl bg-gradient-to-tr from-purple-500 to-pink-500 flex items-center justify-center text-white shadow-lg group-hover:scale-105 transition-transform duration-300">
                            <i class="fa-solid fa-people-carry-box text-xl animate-pulse"></i>
                        </div>
                        <span class="font-outfit font-extrabold text-2xl tracking-wider bg-gradient-to-r from-purple-800 via-purple-600 to-indigo-500 bg-clip-text text-transparent group-hover:text-purple-600 transition-colors duration-300">RMHRSOLUTIONS</span>
                    </a>
                </div>

                <!-- Navigation Links (Desktop) -->
                <nav class="hidden md:flex items-center space-x-1">
                    <a href="{{ route('home') }}" class="px-4 py-2 rounded-lg text-sm font-medium transition-all duration-200 {{ request()->routeIs('home') ? 'bg-purple-500/10 text-purple-700 border border-purple-500/20' : 'text-slate-650 hover:text-purple-750 hover:bg-slate-100/80' }}">Home</a>
                    <a href="{{ route('about') }}" class="px-4 py-2 rounded-lg text-sm font-medium transition-all duration-200 {{ request()->routeIs('about') ? 'bg-purple-500/10 text-purple-700 border border-purple-500/20' : 'text-slate-650 hover:text-purple-750 hover:bg-slate-100/80' }}">About Us</a>
                    <a href="{{ route('services') }}" class="px-4 py-2 rounded-lg text-sm font-medium transition-all duration-200 {{ request()->routeIs('services') ? 'bg-purple-500/10 text-purple-700 border border-purple-500/20' : 'text-slate-650 hover:text-purple-750 hover:bg-slate-100/80' }}">Services</a>
                    <a href="{{ route('gallery') }}" class="px-4 py-2 rounded-lg text-sm font-medium transition-all duration-200 {{ request()->routeIs('gallery') ? 'bg-purple-500/10 text-purple-700 border border-purple-500/20' : 'text-slate-650 hover:text-purple-750 hover:bg-slate-100/80' }}">Gallery</a>
                    <a href="{{ route('contact') }}" class="px-4 py-2 rounded-lg text-sm font-medium transition-all duration-200 {{ request()->routeIs('contact') ? 'bg-purple-500/10 text-purple-700 border border-purple-500/20' : 'text-slate-650 hover:text-purple-750 hover:bg-slate-100/80' }}">Contact Us</a>
                </nav>

                <div class="hidden md:flex items-center space-x-3">
                    @auth('employee')
                        <a href="{{ route('employee.dashboard') }}" class="px-4 py-2 bg-gradient-to-r from-purple-600 to-indigo-600 hover:from-purple-500 hover:to-indigo-500 text-white rounded-xl text-sm font-semibold shadow-lg hover:shadow-purple-500/20 transition-all duration-300">
                            <i class="fa-solid fa-id-card mr-2"></i>Dashboard
                        </a>
                    @else
                        <a href="{{ route('login') }}" class="px-4 py-2 bg-gradient-to-r from-purple-600 to-indigo-600 hover:from-purple-500 hover:to-indigo-500 text-white rounded-xl text-sm font-semibold shadow-lg hover:shadow-purple-500/20 transition-all duration-300">
                            <i class="fa-solid fa-arrow-right-to-bracket mr-2"></i>Employee Login
                        </a>
                    @endauth
                </div>

                <!-- Mobile Menu Button -->
                <div class="md:hidden">
                    <button id="mobile-menu-btn" class="text-slate-400 hover:text-white focus:outline-none p-2 rounded-lg hover:bg-slate-800/50">
                        <i class="fa-solid fa-bars text-2xl" id="menu-icon"></i>
                    </button>
                </div>
            </div>
        </div>

        <!-- Mobile Navigation Menu -->
        <div id="mobile-menu" class="hidden md:hidden border-t border-slate-800 bg-slate-950/95 px-4 py-4 space-y-2">
            <a href="{{ route('home') }}" class="block px-4 py-2 rounded-lg text-base font-medium text-slate-300 hover:text-white hover:bg-slate-800">Home</a>
            <a href="{{ route('about') }}" class="block px-4 py-2 rounded-lg text-base font-medium text-slate-300 hover:text-white hover:bg-slate-800">About Us</a>
            <a href="{{ route('services') }}" class="block px-4 py-2 rounded-lg text-base font-medium text-slate-300 hover:text-white hover:bg-slate-800">Services</a>
            <a href="{{ route('gallery') }}" class="block px-4 py-2 rounded-lg text-base font-medium text-slate-300 hover:text-white hover:bg-slate-800">Gallery</a>
            <a href="{{ route('contact') }}" class="block px-4 py-2 rounded-lg text-base font-medium text-slate-300 hover:text-white hover:bg-slate-800">Contact Us</a>
            <div class="pt-4 border-t border-slate-800 flex flex-col space-y-2">
                @auth('employee')
                    <a href="{{ route('employee.dashboard') }}" class="w-full text-center px-4 py-2 bg-purple-600 text-white rounded-lg text-base font-semibold">Dashboard</a>
                @else
                    <a href="{{ route('login') }}" class="w-full text-center px-4 py-2 bg-purple-600 text-white rounded-lg text-base font-semibold">Employee Login</a>
                @endauth
            </div>
        </div>
    </header>

    <!-- Main Content Area -->
    <main class="flex-grow">
        @if(session('success'))
            <div class="max-w-7xl mx-auto px-4 mt-6">
                <div class="p-4 rounded-xl bg-emerald-500/10 border border-emerald-500/20 text-emerald-300 flex items-center space-x-3 shadow-lg">
                    <i class="fa-solid fa-circle-check text-xl text-emerald-400"></i>
                    <span class="text-sm font-medium">{{ session('success') }}</span>
                </div>
            </div>
        @endif

        @if(session('error'))
            <div class="max-w-7xl mx-auto px-4 mt-6">
                <div class="p-4 rounded-xl bg-rose-500/10 border border-rose-500/20 text-rose-300 flex items-center space-x-3 shadow-lg">
                    <i class="fa-solid fa-circle-exclamation text-xl text-rose-400"></i>
                    <span class="text-sm font-medium">{{ session('error') }}</span>
                </div>
            </div>
        @endif

        @if(session('warning'))
            <div class="max-w-7xl mx-auto px-4 mt-6">
                <div class="p-4 rounded-xl bg-amber-500/10 border border-amber-500/20 text-amber-300 flex items-center space-x-3 shadow-lg">
                    <i class="fa-solid fa-triangle-exclamation text-xl text-amber-400"></i>
                    <span class="text-sm font-medium">{{ session('warning') }}</span>
                </div>
            </div>
        @endif

        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="bg-slate-950 border-t border-slate-900 py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
                <!-- Info -->
                <div class="space-y-4">
                    <div class="flex items-center space-x-3">
                        <div class="w-8 h-8 rounded-lg bg-gradient-to-tr from-purple-500 to-pink-500 flex items-center justify-center text-white">
                            <i class="fa-solid fa-people-carry-box"></i>
                        </div>
                        <span class="font-outfit font-extrabold text-xl tracking-wider text-white">RMHRSOLUTIONS PLOTTERS</span>
                    </div>
                    <p class="text-slate-400 text-sm leading-relaxed">
                        Leading manpower supplier, recruitment consulting firm, and corporate staffing partner. Connecting organizations with professional talent.
                    </p>
                </div>

                <!-- Quick Links -->
                <div>
                    <h3 class="font-outfit font-bold text-sm text-slate-200 uppercase tracking-widest mb-4">Quick Links</h3>
                    <ul class="space-y-2 text-sm">
                        <li><a href="{{ route('home') }}" class="text-slate-400 hover:text-purple-400 transition-colors">Home</a></li>
                        <li><a href="{{ route('about') }}" class="text-slate-400 hover:text-purple-400 transition-colors">About Us</a></li>
                        <li><a href="{{ route('services') }}" class="text-slate-400 hover:text-purple-400 transition-colors">Our Services</a></li>
                        <li><a href="{{ route('gallery') }}" class="text-slate-400 hover:text-purple-400 transition-colors">Gallery</a></li>
                        <li><a href="{{ route('contact') }}" class="text-slate-400 hover:text-purple-400 transition-colors">Contact Us</a></li>
                    </ul>
                </div>

                <!-- Portals -->
                <div>
                    <h3 class="font-outfit font-bold text-sm text-slate-200 uppercase tracking-widest mb-4">Portals</h3>
                    <ul class="space-y-2 text-sm">
                        <li><a href="{{ route('login') }}" class="text-slate-400 hover:text-purple-400 transition-colors"><i class="fa-solid fa-arrow-right-to-bracket mr-1 text-xs"></i> Employee Login</a></li>
                    </ul>
                </div>

                <!-- Contact details -->
                <div>
                    <h3 class="font-outfit font-bold text-sm text-slate-200 uppercase tracking-widest mb-4">Contact Info</h3>
                    <ul class="space-y-2 text-sm text-slate-400">
                        <li class="flex items-start space-x-2">
                            <i class="fa-solid fa-location-dot mt-1 text-purple-400"></i>
                            <span>Amtala, DH Road, South 24 Parganas, West Bengal, 743503</span>
                        </li>
                        <li class="flex items-center space-x-2">
                            <i class="fa-solid fa-phone text-purple-400"></i>
                            <span>+91 94323 13430</span>
                        </li>
                        <li class="flex items-center space-x-2">
                            <i class="fa-solid fa-envelope text-purple-400"></i>
                            <span>info@propszy.com</span>
                        </li>
                    </ul>
                </div>
            </div>

            <div class="mt-12 pt-8 border-t border-slate-900 flex flex-col md:flex-row items-center justify-between text-slate-500 text-xs">
                <p>&copy; {{ date('Y') }} Propszy Infotech. All rights reserved.</p>
                <div class="flex space-x-4 mt-4 md:mt-0">
                    <a href="#" class="hover:text-purple-400">Privacy Policy</a>
                    <a href="#" class="hover:text-purple-400">Terms of Service</a>
                </div>
            </div>
        </div>
    </footer>

    <!-- Mobile Menu script -->
    <script>
        const mobileMenuBtn = document.getElementById('mobile-menu-btn');
        const mobileMenu = document.getElementById('mobile-menu');
        const menuIcon = document.getElementById('menu-icon');

        mobileMenuBtn.addEventListener('click', () => {
            const isHidden = mobileMenu.classList.contains('hidden');
            if (isHidden) {
                mobileMenu.classList.remove('hidden');
                menuIcon.classList.remove('fa-bars');
                menuIcon.classList.add('fa-xmark');
            } else {
                mobileMenu.classList.add('hidden');
                menuIcon.classList.remove('fa-xmark');
                menuIcon.classList.add('fa-bars');
            }
        });
    </script>
    @yield('scripts')
</body>
</html>
