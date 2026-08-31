<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Employee Portal - Propszy')</title>
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Outfit:wght@400;500;600;700;850&display=swap" rel="stylesheet">
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
        .glass-dark {
            background: #ffffff;
            border: 1px solid #e2e8f0;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05), 0 2px 4px -1px rgba(0, 0, 0, 0.025);
        }
        body {
            font-family: 'Inter', sans-serif;
            color: #1e293b;
        }
        /* Candidate light theme overrides for inputs and standard labels */
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
        /* Navigation Links Overrides */
        nav a {
            color: #475569 !important;
        }
        nav a:hover {
            color: #2563eb !important;
            background-color: #f1f5f9 !important;
        }
        nav a.bg-purple-600 {
            background-color: #2563eb !important;
            color: #ffffff !important;
        }
        /* Table Light Styling overrides */
        table {
            color: #334155 !important;
        }
        thead tr {
            background-color: #f8fafc !important;
            border-bottom: 1px solid #e2e8f0 !important;
        }
        tbody tr:hover {
            background-color: #f1f5f9/40 !important;
        }
        th {
            color: #475569 !important;
        }
        td {
            color: #334155 !important;
        }
    </style>
</head>
<body class="bg-slate-50 text-slate-800 min-h-screen flex flex-col">

    @if(Auth::guard('admin')->check())
        <div class="bg-gradient-to-r from-blue-600 to-indigo-600 text-white text-xs font-semibold py-2 px-6 flex items-center justify-between shadow-md z-50 shrink-0">
            <span><i class="fa-solid fa-user-shield mr-2"></i> You are logged in as admin and viewing candidate portal.</span>
            <a href="{{ route('admin.dashboard') }}" class="bg-white/20 hover:bg-white/30 text-white px-3 py-1 rounded-lg font-bold transition-all">
                <i class="fa-solid fa-arrow-left-long mr-1"></i> Switch Back to Admin
            </a>
        </div>
    @endif

    <div class="flex-grow flex min-h-0">

    <!-- Sidebar (Desktop) -->
    <aside class="hidden lg:flex flex-col w-72 bg-white border-r border-slate-200 shrink-0">
        <!-- Logo -->
        <div class="h-24 flex items-center px-6 border-b border-slate-200">
            <a href="{{ route('employee.dashboard') }}" class="flex items-center space-x-3">
                <div class="w-20 h-20 rounded-xl overflow-hidden flex items-center justify-center">
                    @if(file_exists(public_path('images/logo.png')))
                        <img src="{{ asset('images/logo.png') }}" class="w-full h-full object-contain bg-white" alt="RM HR SOLUTIONS Logo">
                    @else
                        <div class="w-full h-full bg-gradient-to-tr from-purple-500 to-pink-500 flex items-center justify-center text-white">
                            <i class="fa-solid fa-id-card-clip text-base"></i>
                        </div>
                    @endif
                </div>
                <span class="font-outfit font-extrabold text-lg tracking-wider text-slate-800">RM HR SOLUTIONS PORTAL</span>
            </a>
        </div>

        <!-- Navigation Links -->
        <nav class="flex-grow p-6 space-y-1.5 overflow-y-auto">
            <a href="{{ route('employee.dashboard') }}" class="flex items-center space-x-3 px-4 py-3 rounded-xl text-sm font-medium transition-all {{ request()->routeIs('employee.dashboard') ? 'bg-purple-600 text-white shadow-lg shadow-purple-600/10' : 'text-slate-600 hover:text-purple-650 hover:bg-slate-100' }}">
                <i class="fa-solid fa-gauge-high w-5 text-center"></i>
                <span>My Dashboard</span>
            </a>

            <a href="{{ route('employee.documents') }}" class="flex items-center space-x-3 px-4 py-3 rounded-xl text-sm font-medium transition-all {{ request()->routeIs('employee.documents') ? 'bg-purple-600 text-white shadow-lg shadow-purple-600/10' : 'text-slate-600 hover:text-purple-650 hover:bg-slate-100' }}">
                <i class="fa-solid fa-folder-open w-5 text-center"></i>
                <span>Documents Center</span>
            </a>

            <a href="{{ route('employee.bulletins') }}" class="flex items-center space-x-3 px-4 py-3 rounded-xl text-sm font-medium transition-all {{ request()->routeIs('employee.bulletins') ? 'bg-purple-600 text-white shadow-lg shadow-purple-600/10' : 'text-slate-600 hover:text-purple-650 hover:bg-slate-100' }}">
                <i class="fa-solid fa-bullhorn w-5 text-center"></i>
                <span>Company Bulletin</span>
            </a>
        </nav>

        <!-- Employee Info & Logout -->
        <div class="p-6 border-t border-slate-200 bg-slate-50">
            <div class="flex items-center space-x-3 mb-4">
                @if(Auth::guard('employee')->user()->profile_image)
                    <img src="{{ asset(Auth::guard('employee')->user()->profile_image) }}" alt="{{ Auth::guard('employee')->user()->full_name }}" class="w-10 h-10 rounded-full object-cover border border-slate-300 shadow-2xs">
                @else
                    <div class="w-10 h-10 rounded-full bg-slate-200 flex items-center justify-center text-purple-600 font-bold border border-slate-300">
                        {{ substr(Auth::guard('employee')->user()->first_name, 0, 1) }}
                    </div>
                @endif
                <div class="overflow-hidden">
                    <span class="block text-sm font-semibold text-slate-800 truncate">{{ Auth::guard('employee')->user()->full_name }}</span>
                    <span class="block text-xs text-slate-500 truncate">{{ Auth::guard('employee')->user()->employee_id }}</span>
                </div>
            </div>
            
            <a href="{{ route('home') }}" target="_blank" class="w-full mb-2 flex items-center justify-center space-x-2 py-2 px-4 border border-slate-200 hover:border-purple-500 rounded-lg text-xs font-semibold text-slate-650 hover:text-slate-900 transition-colors duration-200">
                <i class="fa-solid fa-globe"></i>
                <span>View Public Website</span>
            </a>

            <form action="{{ route('employee.logout') }}" method="POST">
                @csrf
                <button type="submit" class="w-full flex items-center justify-center space-x-2 py-2 px-4 bg-slate-100 hover:bg-rose-50 hover:text-rose-600 border border-slate-200 hover:border-rose-200 rounded-lg text-xs font-semibold text-slate-650 transition-colors duration-200">
                    <i class="fa-solid fa-arrow-right-from-bracket"></i>
                    <span>Log Out Account</span>
                </button>
            </form>
        </div>
    </aside>

    <!-- Main Panel Section -->
    <div class="flex-grow flex flex-col min-w-0">
        <!-- Top Bar Header -->
        <header class="h-20 border-b border-slate-200 bg-white px-6 sm:px-8 flex items-center justify-between z-20">
            <div class="flex items-center lg:hidden">
                <div class="w-8 h-8 rounded-lg bg-gradient-to-tr from-purple-500 to-pink-500 flex items-center justify-center text-white mr-3">
                    <i class="fa-solid fa-id-card-clip text-sm"></i>
                </div>
                <span class="font-outfit font-extrabold text-sm text-slate-800">RM HR SOLUTIONS PORTAL</span>
            </div>

            <!-- Page Title -->
            <h2 class="hidden lg:block font-outfit font-extrabold text-lg text-slate-800">
                @yield('page_title', 'Employee Self-Service')
            </h2>

            <!-- Quick Status/Profile -->
            <div class="flex items-center space-x-4">
                <div class="hidden sm:flex text-right flex-col justify-center">
                    <span class="block text-xs font-semibold uppercase tracking-wider text-purple-650">Authenticated Staff</span>
                    <span class="block text-xs text-slate-500">{{ Auth::guard('employee')->user()->employee_id }}</span>
                </div>
                <div class="w-1.5 h-1.5 rounded-full bg-purple-500 animate-ping"></div>
                <!-- Mobile logout form trigger -->
                <form action="{{ route('employee.logout') }}" method="POST" class="lg:hidden">
                    @csrf
                    <button type="submit" class="p-2.5 rounded-lg bg-slate-100 border border-slate-200 text-slate-650 hover:text-rose-600">
                        <i class="fa-solid fa-arrow-right-from-bracket text-lg"></i>
                    </button>
                </form>
            </div>
        </header>

        <!-- Panel Body Content -->
        <main class="flex-grow p-6 sm:p-8 overflow-y-auto">
            @if(session('success'))
                <div class="mb-6 p-4 rounded-xl bg-emerald-500/10 border border-emerald-500/20 text-emerald-300 flex items-center space-x-3 shadow-lg">
                    <i class="fa-solid fa-circle-check text-xl text-emerald-400"></i>
                    <span class="text-sm font-medium">{{ session('success') }}</span>
                </div>
            @endif

            @if(session('error'))
                <div class="mb-6 p-4 rounded-xl bg-rose-500/10 border border-rose-500/20 text-rose-300 flex items-center space-x-3 shadow-lg">
                    <i class="fa-solid fa-circle-exclamation text-xl text-rose-400"></i>
                    <span class="text-sm font-medium">{{ session('error') }}</span>
                </div>
            @endif

            @yield('content')
        </main>
    </div>

    </div>

    @yield('scripts')
</body>
</html>
