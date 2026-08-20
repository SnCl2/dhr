@php
    $backendUser = Auth::guard('admin')->user() ?? Auth::guard('staff')->user();
    $isStaff = Auth::guard('staff')->check();
@endphp
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
<body class="bg-slate-50 text-slate-800 min-h-screen flex">

    <!-- Sidebar (Desktop) -->
    <aside class="hidden lg:flex flex-col w-72 bg-white border-r border-slate-200 shrink-0">
        <!-- Logo -->
        <div class="h-24 flex items-center px-6 border-b border-slate-200">
            <a href="{{ route('admin.dashboard') }}" class="flex items-center space-x-3">
                <div class="w-20 h-20 rounded-xl overflow-hidden flex items-center justify-center">
                    @if(file_exists(public_path('images/logo.png')))
                        <img src="{{ asset('images/logo.png') }}" class="w-full h-full object-contain bg-white" alt="RM HR SOLUTIONS Logo">
                    @else
                        <div class="w-full h-full bg-gradient-to-tr from-purple-500 to-pink-500 flex items-center justify-center text-white">
                            <i class="fa-solid fa-user-shield text-base"></i>
                        </div>
                    @endif
                </div>
                <span class="font-outfit font-extrabold text-lg tracking-wider text-slate-800">RM HR SOLUTIONS ADMIN</span>
            </a>
        </div>

        <!-- Navigation Links -->
        <nav class="flex-grow p-6 space-y-1.5 overflow-y-auto">
            <a href="{{ route('admin.dashboard') }}" class="flex items-center space-x-3 px-4 py-3 rounded-xl text-sm font-medium transition-all {{ request()->routeIs('admin.dashboard') ? 'bg-purple-600 text-white shadow-lg shadow-purple-600/10' : 'text-slate-600 hover:text-purple-650 hover:bg-slate-100' }}">
                <i class="fa-solid fa-chart-line w-5 text-center"></i>
                <span>Dashboard</span>
            </a>

            @can('view_employees')
            <a href="{{ route('admin.employees.index') }}" class="flex items-center space-x-3 px-4 py-3 rounded-xl text-sm font-medium transition-all {{ request()->routeIs('admin.employees.*') ? 'bg-purple-600 text-white shadow-lg shadow-purple-600/10' : 'text-slate-600 hover:text-purple-650 hover:bg-slate-100' }}">
                <i class="fa-solid fa-users w-5 text-center"></i>
                <span>Candidate / Staff CRUD</span>
            </a>
            @endcan

            @can('view_companies')
            <a href="{{ route('admin.companies.index') }}" class="flex items-center space-x-3 px-4 py-3 rounded-xl text-sm font-medium transition-all {{ request()->routeIs('admin.companies.*') ? 'bg-purple-600 text-white shadow-lg shadow-purple-600/10' : 'text-slate-600 hover:text-purple-650 hover:bg-slate-100' }}">
                <i class="fa-solid fa-building w-5 text-center"></i>
                <span>Manage Companies</span>
            </a>
            @endcan

            @can('view_departments')
            <a href="{{ route('admin.departments.index') }}" class="flex items-center space-x-3 px-4 py-3 rounded-xl text-sm font-medium transition-all {{ request()->routeIs('admin.departments.*') ? 'bg-purple-600 text-white shadow-lg shadow-purple-600/10' : 'text-slate-600 hover:text-purple-650 hover:bg-slate-100' }}">
                <i class="fa-solid fa-folder-tree w-5 text-center"></i>
                <span>Manage Departments</span>
            </a>
            @endcan

            @can('view_designations')
            <a href="{{ route('admin.designations.index') }}" class="flex items-center space-x-3 px-4 py-3 rounded-xl text-sm font-medium transition-all {{ request()->routeIs('admin.designations.*') ? 'bg-purple-600 text-white shadow-lg shadow-purple-600/10' : 'text-slate-600 hover:text-purple-650 hover:bg-slate-100' }}">
                <i class="fa-solid fa-id-card w-5 text-center"></i>
                <span>Manage Designations</span>
            </a>
            @endcan

            @can('view_payslips')
            <a href="{{ route('admin.payslips.generate') }}" class="flex items-center space-x-3 px-4 py-3 rounded-xl text-sm font-medium transition-all {{ request()->routeIs('admin.payslips.*') ? 'bg-purple-600 text-white shadow-lg shadow-purple-600/10' : 'text-slate-600 hover:text-purple-650 hover:bg-slate-100' }}">
                <i class="fa-solid fa-file-circle-check w-5 text-center"></i>
                <span>Generate Payslips</span>
            </a>
            @endcan

            @can('view_bulletins')
            <a href="{{ route('admin.bulletins.index') }}" class="flex items-center space-x-3 px-4 py-3 rounded-xl text-sm font-medium transition-all {{ request()->routeIs('admin.bulletins.*') ? 'bg-purple-600 text-white shadow-lg shadow-purple-600/10' : 'text-slate-600 hover:text-purple-650 hover:bg-slate-100' }}">
                <i class="fa-solid fa-bullhorn w-5 text-center"></i>
                <span>Notice Board Bulletins</span>
            </a>
            @endcan

            @can('view_inquiries')
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
            @endcan

            @can('view_cms')
            <a href="{{ route('admin.cms.index') }}" class="flex items-center space-x-3 px-4 py-3 rounded-xl text-sm font-medium transition-all {{ request()->routeIs('admin.cms.*') ? 'bg-purple-600 text-white shadow-lg shadow-purple-600/10' : 'text-slate-600 hover:text-purple-650 hover:bg-slate-100' }}">
                <i class="fa-solid fa-window-restore w-5 text-center"></i>
                <span>CMS Content Manage</span>
            </a>
            @endcan

            @can('view_staff')
            <a href="{{ route('admin.staff.index') }}" class="flex items-center space-x-3 px-4 py-3 rounded-xl text-sm font-medium transition-all {{ request()->routeIs('admin.staff.*') ? 'bg-purple-600 text-white shadow-lg shadow-purple-600/10' : 'text-slate-600 hover:text-purple-650 hover:bg-slate-100' }}">
                <i class="fa-solid fa-users-gear w-5 text-center"></i>
                <span>Manage Staff</span>
            </a>
            @endcan

            <a href="{{ route('admin.profile') }}" class="flex items-center space-x-3 px-4 py-3 rounded-xl text-sm font-medium transition-all {{ request()->routeIs('admin.profile') ? 'bg-purple-600 text-white shadow-lg shadow-purple-600/10' : 'text-slate-600 hover:text-purple-650 hover:bg-slate-100' }}">
                <i class="fa-solid fa-shield-halved w-5 text-center"></i>
                <span>{{ $isStaff ? 'Staff ID & Security' : 'Admin ID & Security' }}</span>
            </a>
        </nav>

        <!-- Admin Info & Logout -->
        <div class="p-6 border-t border-slate-200 bg-slate-50">
            <a href="{{ route('admin.profile') }}" title="Click to edit profile and password" class="flex items-center space-x-3 mb-3 p-2 -m-2 rounded-xl hover:bg-slate-200/50 transition-colors group">
                <div class="w-10 h-10 rounded-full bg-slate-200 group-hover:bg-purple-100 flex items-center justify-center text-purple-600 font-bold border border-slate-300 transition-colors">
                    {{ substr($backendUser->name ?? 'U', 0, 1) }}
                </div>
                <div class="overflow-hidden flex-grow">
                    <span class="block text-sm font-semibold text-slate-800 group-hover:text-purple-700 truncate transition-colors">{{ $backendUser->name ?? 'Backend User' }}</span>
                    <span class="block text-xs text-slate-500 truncate">{{ $backendUser->email ?? '' }}</span>
                </div>
                <i class="fa-solid fa-gear text-slate-400 group-hover:text-purple-600 text-xs"></i>
            </a>
            
            <a href="{{ route('home') }}" target="_blank" class="w-full mb-2 flex items-center justify-center space-x-2 py-2 px-4 border border-slate-200 hover:border-purple-500 rounded-lg text-xs font-semibold text-slate-650 hover:text-slate-900 transition-colors duration-200">
                <i class="fa-solid fa-globe"></i>
                <span>View Public Website</span>
            </a>

            <form action="{{ $isStaff ? route('staff.logout') : route('admin.logout') }}" method="POST">
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
                <span class="font-outfit font-extrabold text-sm text-slate-800">RM HR SOLUTIONS ADMIN</span>
            </div>

            <!-- Page Title -->
            <h2 class="hidden lg:block font-outfit font-extrabold text-lg text-slate-800">
                @yield('page_title', 'Control Administration Panel')
            </h2>

            <!-- Quick Stats/Profile -->
            <div class="flex items-center space-x-4">
                <a href="{{ route('admin.profile') }}" class="hidden sm:flex text-right flex-col justify-center hover:opacity-80 transition-opacity">
                    <span class="block text-xs font-semibold uppercase tracking-wider text-purple-600">Security Clearance</span>
                    <span class="block text-xs text-slate-500">{{ $backendUser->name ?? 'Backend User' }} ({{ $isStaff ? 'Staff Mode' : 'Admin Mode' }})</span>
                </a>
                <a href="{{ route('admin.profile') }}" title="Account Settings" class="w-9 h-9 rounded-xl bg-purple-50 hover:bg-purple-100 border border-purple-200 text-purple-600 flex items-center justify-center text-sm transition-colors">
                    <i class="fa-solid fa-user-gear"></i>
                </a>
                <div class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-ping"></div>
                <!-- Mobile logout form trigger -->
                <form action="{{ $isStaff ? route('staff.logout') : route('admin.logout') }}" method="POST" class="lg:hidden">
                    @csrf
                    <button type="submit" class="p-2.5 rounded-lg bg-slate-100 border border-slate-200 text-slate-600 hover:text-rose-600">
                        <i class="fa-solid fa-arrow-right-from-bracket text-lg"></i>
                    </button>
                </form>
            </div>
        </header>

        <!-- Panel Body Content -->
        <main class="flex-grow p-6 sm:p-8 overflow-y-auto">
            @yield('content')
        </main>
    </div>

    <!-- Global Slide-in Toast Notification Container -->
    <div id="global-toast-container" class="fixed bottom-5 right-5 z-50 flex flex-col space-y-3 max-w-sm w-full"></div>

    <script>
        function showToast(message, type = 'success') {
            const container = document.getElementById('global-toast-container');
            if (!container) return;

            const toast = document.createElement('div');
            
            // Premium layout styles
            toast.className = `transform translate-x-[120%] transition-all duration-300 ease-out flex items-center p-4 space-x-3 rounded-xl border shadow-2xl backdrop-blur-md`;
            
            if (type === 'error') {
                toast.className += ' bg-rose-950/95 border-rose-500/30 text-rose-200';
                toast.innerHTML = `<i class="fa-solid fa-circle-exclamation text-rose-400 text-lg"></i>`;
            } else if (type === 'warning') {
                toast.className += ' bg-amber-950/95 border-amber-500/30 text-amber-200';
                toast.innerHTML = `<i class="fa-solid fa-triangle-exclamation text-amber-400 text-lg"></i>`;
            } else {
                toast.className += ' bg-emerald-950/95 border-emerald-500/30 text-emerald-200';
                toast.innerHTML = `<i class="fa-solid fa-circle-check text-emerald-400 text-lg"></i>`;
            }
            
            toast.innerHTML += `
                <div class="flex-grow text-xs font-semibold pr-2">${message}</div>
                <button class="text-slate-400 hover:text-slate-200 transition-colors ml-auto" onclick="this.parentElement.remove()">
                    <i class="fa-solid fa-xmark"></i>
                </button>
            `;
            
            container.appendChild(toast);
            
            // Trigger slide-in
            setTimeout(() => {
                toast.classList.remove('translate-x-[120%]');
            }, 50);
            
            // Auto remove after 5 seconds
            setTimeout(() => {
                toast.classList.add('translate-x-[120%]');
                setTimeout(() => {
                    toast.remove();
                }, 300);
            }, 5000);
        }

        // Auto trigger toasts for session flash messages
        @if(session('success'))
            showToast("{{ session('success') }}", 'success');
        @endif
        @if(session('error'))
            showToast("{{ session('error') }}", 'error');
        @endif
        @if(session('warning'))
            showToast("{{ session('warning') }}", 'warning');
        @endif
    </script>

    @yield('scripts')
</body>
</html>
