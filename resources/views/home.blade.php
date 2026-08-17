@extends('layouts.app')

@section('title', 'Manpower Hiring & Employee Management System - Home')

@section('content')
<!-- Hero Section -->
<div class="relative overflow-hidden gradient-bg py-24 sm:py-32">
    <!-- Background accents -->
    <div class="absolute top-0 right-0 -mt-12 -mr-12 w-96 h-96 rounded-full bg-purple-500/20 blur-3xl"></div>
    <div class="absolute bottom-0 left-0 -mb-12 -ml-12 w-96 h-96 rounded-full bg-pink-500/10 blur-3xl"></div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
        <div class="lg:grid lg:grid-cols-12 lg:gap-8 items-center">
            <!-- Left: Hero Text -->
            <div class="sm:text-center md:max-w-2xl md:mx-auto lg:col-span-6 lg:text-left">
                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-purple-500/10 text-purple-300 border border-purple-500/20 mb-6 uppercase tracking-wider">
                    <i class="fa-solid fa-sparkles mr-1.5 animate-spin-slow"></i> Premium Recruitment Agency
                </span>
                <h1 class="font-outfit font-extrabold text-4xl sm:text-5xl lg:text-6xl tracking-tight text-white leading-tight">
                    {{ $cms['banner_title'] }}
                </h1>
                <p class="mt-6 text-lg text-slate-300 leading-relaxed">
                    {{ $cms['banner_subtitle'] }}
                </p>
                <div class="mt-10 sm:flex sm:justify-center lg:justify-start gap-4">
                    <a href="{{ route('login') }}" class="flex items-center justify-center px-8 py-4 bg-gradient-to-r from-purple-600 to-indigo-600 hover:from-purple-500 hover:to-indigo-500 text-white rounded-2xl text-base font-bold shadow-xl shadow-purple-500/20 hover:scale-[1.02] transition-all duration-300">
                        <i class="fa-solid fa-arrow-right-to-bracket mr-2"></i> Employee Login
                    </a>
                    <a href="{{ route('contact') }}" class="mt-4 sm:mt-0 flex items-center justify-center px-8 py-4 border border-slate-700 hover:border-purple-500 bg-slate-900/40 text-slate-200 hover:text-white rounded-2xl text-base font-bold hover:scale-[1.02] transition-all duration-300">
                        <i class="fa-solid fa-paper-plane mr-2 text-purple-400"></i> Contact Us
                    </a>
                </div>
            </div>

            <!-- Right: Glassmorphic Stats Grid / Preview -->
            <div class="mt-16 sm:mt-24 lg:mt-0 lg:col-span-6">
                <div class="glass p-8 rounded-3xl relative">
                    <div class="absolute -top-6 -left-6 w-12 h-12 rounded-2xl bg-pink-500 flex items-center justify-center text-white shadow-lg">
                        <i class="fa-solid fa-shield-halved text-lg"></i>
                    </div>
                    <h3 class="font-outfit font-bold text-xl text-slate-900 mb-6 flex items-center">
                        <span class="w-2.5 h-2.5 rounded-full bg-purple-600 mr-2.5"></span>
                        Quick Placements & Plannings
                    </h3>
                    <div class="grid grid-cols-2 gap-4">
                        <div class="bg-slate-900/5 p-5 rounded-2xl border border-slate-900/10">
                            <span class="block font-outfit font-extrabold text-3xl text-purple-800">{{ $stats['total_employees'] }}+</span>
                            <span class="block text-slate-700 text-xs font-semibold uppercase tracking-wider mt-1">Hired Employees</span>
                        </div>
                        <div class="bg-slate-900/5 p-5 rounded-2xl border border-slate-900/10">
                            <span class="block font-outfit font-extrabold text-3xl text-indigo-800">{{ $stats['active_placements'] }}</span>
                            <span class="block text-slate-700 text-xs font-semibold uppercase tracking-wider mt-1">Placements</span>
                        </div>
                        <div class="bg-slate-900/5 p-5 rounded-2xl border border-slate-900/10 col-span-2 flex items-center justify-between">
                            <div>
                                <span class="block font-outfit font-extrabold text-2xl text-pink-800">Secure DB</span>
                                <span class="block text-slate-700 text-xs font-semibold uppercase tracking-wider mt-1">Custom Auth Guards</span>
                            </div>
                            <i class="fa-solid fa-lock text-3xl text-slate-400"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Services Highlights -->
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-24">
    <div class="text-center max-w-3xl mx-auto mb-16">
        <h2 class="font-outfit font-extrabold text-3xl sm:text-4xl text-white">
            Specialized Manpower Solutions
        </h2>
        <p class="mt-4 text-slate-400">
            We provide skilled, semi-skilled, and professional manpower across various industries to meet project demands.
        </p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
        <!-- Service Card 1 -->
        <div class="glass-dark p-8 rounded-3xl hover:border-purple-500/50 transition-all duration-300 hover:scale-[1.03] group">
            <div class="w-12 h-12 rounded-xl bg-purple-500/10 text-purple-400 flex items-center justify-center mb-6 group-hover:bg-purple-600 group-hover:text-white transition-all duration-300">
                <i class="fa-solid fa-truck-ramp-box text-xl"></i>
            </div>
            <h3 class="font-outfit font-bold text-lg text-white mb-3">Logistics & Operations</h3>
            <p class="text-slate-400 text-sm leading-relaxed">
                Reliable warehouse operators, delivery executives, packers, and supervisors for rapid supply chain execution.
            </p>
        </div>

        <!-- Service Card 2 -->
        <div class="glass-dark p-8 rounded-3xl hover:border-purple-500/50 transition-all duration-300 hover:scale-[1.03] group">
            <div class="w-12 h-12 rounded-xl bg-indigo-500/10 text-indigo-400 flex items-center justify-center mb-6 group-hover:bg-indigo-600 group-hover:text-white transition-all duration-300">
                <i class="fa-solid fa-laptop-code text-xl"></i>
            </div>
            <h3 class="font-outfit font-bold text-lg text-white mb-3">Technical & IT Staffing</h3>
            <p class="text-slate-400 text-sm leading-relaxed">
                Skilled engineers, IT support specialists, project leaders, and developers selected via deep profiling metrics.
            </p>
        </div>

        <!-- Service Card 3 -->
        <div class="glass-dark p-8 rounded-3xl hover:border-purple-500/50 transition-all duration-300 hover:scale-[1.03] group">
            <div class="w-12 h-12 rounded-xl bg-pink-500/10 text-pink-400 flex items-center justify-center mb-6 group-hover:bg-pink-600 group-hover:text-white transition-all duration-300">
                <i class="fa-solid fa-user-tie text-xl"></i>
            </div>
            <h3 class="font-outfit font-bold text-lg text-white mb-3">Corporate Management</h3>
            <p class="text-slate-400 text-sm leading-relaxed">
                Office assistants, accountants, HR executives, and managers for comprehensive corporate operations.
            </p>
        </div>
    </div>
</div>
@endsection
