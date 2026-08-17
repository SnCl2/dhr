@extends('layouts.app')

@section('title', 'About Us - Propszy Manpower Solutions')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16 sm:py-24">
    <!-- Header -->
    <div class="text-center max-w-3xl mx-auto mb-16">
        <span class="text-xs font-semibold uppercase tracking-wider text-purple-400 bg-purple-500/10 px-3 py-1 rounded-full border border-purple-500/20">Our Story</span>
        <h1 class="font-outfit font-extrabold text-4xl sm:text-5xl text-white mt-4">
            About Our Organization
        </h1>
        <p class="mt-4 text-slate-400">
            Learn more about our mission, vision, and core values that drive our manpower services.
        </p>
    </div>

    <!-- Core Content -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center mb-20">
        <div>
            <h2 class="font-outfit font-bold text-2xl text-white mb-6 flex items-center">
                <span class="w-1 h-8 bg-gradient-to-b from-purple-500 to-pink-500 rounded mr-3"></span>
                Company Background & Mission
            </h2>
            <p class="text-slate-300 leading-relaxed mb-6">
                {{ $cms['about_text'] }}
            </p>
            <p class="text-slate-400 text-sm leading-relaxed">
                We believe in bridging the gap between top-tier hiring requirements and candidate career paths. Through rigorous screening, background verification, and continuous upskilling, we ensure that every employee matches the high standards demanded by our enterprise partners.
            </p>
        </div>
        <div class="relative">
            <div class="absolute -top-6 -left-6 w-72 h-72 rounded-full bg-purple-500/10 blur-3xl"></div>
            <div class="glass p-8 rounded-3xl border border-slate-700 relative z-10 text-slate-800">
                <h3 class="font-outfit font-extrabold text-2xl text-purple-900 mb-6">Our Commitments</h3>
                <ul class="space-y-4">
                    <li class="flex items-start">
                        <i class="fa-solid fa-circle-check text-purple-600 mt-1 mr-3"></i>
                        <span class="text-slate-700 font-medium">Compliance-first background verification</span>
                    </li>
                    <li class="flex items-start">
                        <i class="fa-solid fa-circle-check text-purple-600 mt-1 mr-3"></i>
                        <span class="text-slate-700 font-medium">Auto-generated credential distribution</span>
                    </li>
                    <li class="flex items-start">
                        <i class="fa-solid fa-circle-check text-purple-600 mt-1 mr-3"></i>
                        <span class="text-slate-700 font-medium">Transparent monthly payslip downloads</span>
                    </li>
                    <li class="flex items-start">
                        <i class="fa-solid fa-circle-check text-purple-600 mt-1 mr-3"></i>
                        <span class="text-slate-700 font-medium">High quality FPDF generated documents</span>
                    </li>
                </ul>
            </div>
        </div>
    </div>

    <!-- Vision & Mission Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
        <div class="glass-dark p-8 rounded-3xl border border-slate-800">
            <div class="w-12 h-12 rounded-xl bg-purple-500/10 text-purple-400 flex items-center justify-center mb-6">
                <i class="fa-solid fa-eye text-xl"></i>
            </div>
            <h3 class="font-outfit font-bold text-xl text-white mb-4">Our Vision</h3>
            <p class="text-slate-400 text-sm leading-relaxed">
                To be the most trusted global partner in manpower supply and employee management services, recognized for our commitment to quality, speed, and employee welfare.
            </p>
        </div>

        <div class="glass-dark p-8 rounded-3xl border border-slate-800">
            <div class="w-12 h-12 rounded-xl bg-indigo-500/10 text-indigo-400 flex items-center justify-center mb-6">
                <i class="fa-solid fa-bullseye text-xl"></i>
            </div>
            <h3 class="font-outfit font-bold text-xl text-white mb-4">Our Mission</h3>
            <p class="text-slate-400 text-sm leading-relaxed">
                To empower organizations with top-quality candidates, while providing employees with clear career growth, timely document generation, transparent financial payouts, and a supportive digital portal.
            </p>
        </div>
    </div>
</div>
@endsection
