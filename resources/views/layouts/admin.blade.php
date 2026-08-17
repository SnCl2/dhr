<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin Dashboard - Propszy')</title>
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
                            50: '#f5f3ff',
                            100: '#ede9fe',
                            500: '#8b5cf6',
                            600: '#7c3aed',
                            700: '#6d28d9',
                            800: '#5b21b6',
                            900: '#4c1d95',
                        },
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
        /* Admin light theme overrides for inputs and standard labels */
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
        /* Sidebar Link Overrides */
        nav a {
            color: #475569 !important;
        }
        nav a:hover {
            color: #6d28d9 !important;
            background-color: #f1f5f9 !important;
        }
        nav a.bg-purple-600 {
            background-color: #7c3aed !important;
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
<body class="bg-slate-50 text-slate-800 min-h-screen flex">

    <!-- Sidebar (Desktop) -->
    <aside class="hidden lg:flex flex-col w-72 bg-white border-r border-slate-200 shrink-0">
        <!-- Logo -->
        <div class="h-20 flex items-center px-6 border-b border-slate-200">
            <a href="{{ route('admin.dashboard') }}" class="flex items-center space-x-3">
                <div class="w-9 h-9 rounded-xl bg-gradient-to-tr from-purple-500 to-pink-500 flex items-center justify-center text-white">
                    <i class="fa-solid fa-user-shield text-base"></i>
                </div>
                <span class="font-outfit font-extrabold text-lg tracking-wider text-slate-800">RMHRSOLUTIONS ADMIN</span>
            </a>
        </div>

        <!-- Navigation Links -->
        <nav class="flex-grow p-6 space-y-1.5 overflow-y-auto">
            <a href="{{ route('admin.dashboard') }}" class="flex items-center space-x-3 px-4 py-3 rounded-xl text-sm font-medium transition-all {{ request()->routeIs('admin.dashboard') ? 'bg-purple-600 text-white shadow-lg shadow-purple-600/10' : 'text-slate-600 hover:text-purple-650 hover:bg-slate-100' }}">
                <i class="fa-solid fa-chart-line w-5 text-center"></i>
                <span>Dashboard</span>
            </a>

            <a href="{{ route('admin.employees.index') }}" class="flex items-center space-x-3 px-4 py-3 rounded-xl text-sm font-medium transition-all {{ request()->routeIs('admin.employees.*') ? 'bg-purple-600 text-white shadow-lg shadow-purple-600/10' : 'text-slate-600 hover:text-purple-650 hover:bg-slate-100' }}">
                <i class="fa-solid fa-users w-5 text-center"></i>
                <span>Candidate / Staff CRUD</span>
            </a>

            <a href="{{ route('admin.companies.index') }}" class="flex items-center space-x-3 px-4 py-3 rounded-xl text-sm font-medium transition-all {{ request()->routeIs('admin.companies.*') ? 'bg-purple-600 text-white shadow-lg shadow-purple-600/10' : 'text-slate-600 hover:text-purple-650 hover:bg-slate-100' }}">
                <i class="fa-solid fa-building w-5 text-center"></i>
                <span>Manage Companies</span>
            </a>

            <a href="{{ route('admin.templates.index') }}" class="flex items-center space-x-3 px-4 py-3 rounded-xl text-sm font-medium transition-all {{ request()->routeIs('admin.templates.*') ? 'bg-purple-600 text-white shadow-lg shadow-purple-600/10' : 'text-slate-600 hover:text-purple-650 hover:bg-slate-100' }}">
                <i class="fa-solid fa-file-invoice w-5 text-center"></i>
                <span>Offer Templates</span>
            </a>

            <a href="{{ route('admin.offer-letters.generate') }}" class="flex items-center space-x-3 px-4 py-3 rounded-xl text-sm font-medium transition-all {{ request()->routeIs('admin.offer-letters.*') ? 'bg-purple-600 text-white shadow-lg shadow-purple-600/10' : 'text-slate-600 hover:text-purple-650 hover:bg-slate-100' }}">
                <i class="fa-solid fa-file-pdf w-5 text-center"></i>
                <span>Generate Offer Letters</span>
            </a>

            <a href="{{ route('admin.payslips.generate') }}" class="flex items-center space-x-3 px-4 py-3 rounded-xl text-sm font-medium transition-all {{ request()->routeIs('admin.payslips.*') ? 'bg-purple-600 text-white shadow-lg shadow-purple-600/10' : 'text-slate-600 hover:text-purple-650 hover:bg-slate-100' }}">
                <i class="fa-solid fa-file-circle-check w-5 text-center"></i>
                <span>Generate Payslips</span>
            </a>

            <a href="{{ route('admin.bulletins.index') }}" class="flex items-center space-x-3 px-4 py-3 rounded-xl text-sm font-medium transition-all {{ request()->routeIs('admin.bulletins.*') ? 'bg-purple-600 text-white shadow-lg shadow-purple-600/10' : 'text-slate-600 hover:text-purple-650 hover:bg-slate-100' }}">
                <i class="fa-solid fa-bullhorn w-5 text-center"></i>
                <span>Notice Board Bulletins</span>
            </a>

            <a href="{{ route('admin.inquiries.index') }}" class="flex items-center space-x-3 px-4 py-3 rounded-xl text-sm font-medium transition-all {{ request()->routeIs('admin.inquiries.*') ? 'bg-purple-600 text-white shadow-lg shadow-purple-600/10' : 'text-slate-600 hover:text-purple-650 hover:bg-slate-100' }}">
                <i class="fa-solid fa-envelope-open-text w-5 text-center"></i>
                <span>Contact Inquiries</span>
                @php
                    $unread = \App\Models\Inquiry::where('status', 'unread')->count();
                @endphp
                @if($unread > 0)
                    <span class="ml-auto inline-flex items-center justify-center px-2 py-0.5 rounded-full text-xs font-bold bg-pink-500 text-white animate-pulse">{{ $unread }}</span>
                @endif
            </a>

            <a href="{{ route('admin.cms.index') }}" class="flex items-center space-x-3 px-4 py-3 rounded-xl text-sm font-medium transition-all {{ request()->routeIs('admin.cms.*') ? 'bg-purple-600 text-white shadow-lg shadow-purple-600/10' : 'text-slate-600 hover:text-purple-650 hover:bg-slate-100' }}">
                <i class="fa-solid fa-window-restore w-5 text-center"></i>
                <span>CMS Content Manage</span>
            </a>
        </nav>

        <!-- Admin Info & Logout -->
        <div class="p-6 border-t border-slate-200 bg-slate-50">
            <div class="flex items-center space-x-3 mb-4">
                <div class="w-10 h-10 rounded-full bg-slate-200 flex items-center justify-center text-purple-600 font-bold border border-slate-300">
                    {{ substr(Auth::guard('admin')->user()->name, 0, 1) }}
                </div>
                <div class="overflow-hidden">
                    <span class="block text-sm font-semibold text-slate-800 truncate">{{ Auth::guard('admin')->user()->name }}</span>
                    <span class="block text-xs text-slate-500 truncate">{{ Auth::guard('admin')->user()->email }}</span>
                </div>
            </div>
            
            <a href="{{ route('home') }}" target="_blank" class="w-full mb-2 flex items-center justify-center space-x-2 py-2 px-4 border border-slate-200 hover:border-purple-500 rounded-lg text-xs font-semibold text-slate-650 hover:text-slate-900 transition-colors duration-200">
                <i class="fa-solid fa-globe"></i>
                <span>View Public Website</span>
            </a>

            <form action="{{ route('admin.logout') }}" method="POST">
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
                    <i class="fa-solid fa-user-shield text-sm"></i>
                </div>
                <span class="font-outfit font-extrabold text-sm text-slate-800">RMHRSOLUTIONS ADMIN</span>
            </div>

            <!-- Page Title -->
            <h2 class="hidden lg:block font-outfit font-extrabold text-lg text-slate-800">
                @yield('page_title', 'Control Administration Panel')
            </h2>

            <!-- Quick Stats/Profile -->
            <div class="flex items-center space-x-4">
                <div class="hidden sm:flex text-right flex-col justify-center">
                    <span class="block text-xs font-semibold uppercase tracking-wider text-purple-600">Security Clearance</span>
                    <span class="block text-xs text-slate-500">System Admin Mode</span>
                </div>
                <div class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-ping"></div>
                <!-- Mobile logout form trigger -->
                <form action="{{ route('admin.logout') }}" method="POST" class="lg:hidden">
                    @csrf
                    <button type="submit" class="p-2.5 rounded-lg bg-slate-100 border border-slate-200 text-slate-600 hover:text-rose-600">
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

    @yield('scripts')
</body>
</html>
