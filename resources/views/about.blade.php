@extends('layouts.app')

@section('title', 'About Us - RM HR Solutions')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16 sm:py-24">
    <!-- Header -->
    <div class="text-center max-w-3xl mx-auto mb-16 space-y-3">
        <span class="text-xs font-bold uppercase tracking-widest text-indigo-600 bg-indigo-50 px-3 py-1.5 rounded-full border border-indigo-100">Our Story</span>
        <h1 class="font-outfit font-black text-4xl sm:text-5xl text-slate-850">
            About Our Organization
        </h1>
        <p class="text-sm text-slate-500 max-w-xl mx-auto">
            Learn more about our mission, vision, and core values that drive our manpower services.
        </p>
    </div>

    <!-- Core Content -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center mb-20">
        <div class="space-y-6">
            <h2 class="font-outfit font-bold text-2xl text-slate-800 flex items-center">
                <span class="w-1 h-8 bg-gradient-to-b from-indigo-500 to-cyan-500 rounded mr-3"></span>
                Company Background & Mission
            </h2>
            <div class="text-slate-600 text-sm leading-relaxed space-y-4">
                <p>
                    {{ $cms['about_text'] }}
                </p>
                <p>
                    We believe in bridging the gap between top-tier hiring requirements and candidate career paths. Through rigorous screening, background verification, and continuous upskilling, we ensure that every employee matches the high standards demanded by our enterprise partners.
                </p>
            </div>
        </div>
        <div class="relative">
            <div class="absolute -top-6 -left-6 w-72 h-72 rounded-full bg-indigo-400/5 blur-3xl"></div>
            <div class="bg-white p-8 rounded-3xl border border-slate-200 relative z-10 text-slate-800 shadow-sm">
                <h3 class="font-outfit font-extrabold text-2xl text-slate-850 mb-6 flex items-center">
                    <i class="fa-solid fa-star text-amber-500 mr-2"></i> Our Commitments
                </h3>
                <ul class="space-y-4">
                    <li class="flex items-start">
                        <i class="fa-solid fa-circle-check text-indigo-600 mt-1 mr-3"></i>
                        <span class="text-slate-650 text-xs leading-normal">Compliance-first background verification and candidate KYC checks</span>
                    </li>
                    <li class="flex items-start">
                        <i class="fa-solid fa-circle-check text-indigo-600 mt-1 mr-3"></i>
                        <span class="text-slate-650 text-xs leading-normal">Auto-generated contract and joining letter distribution</span>
                    </li>
                    <li class="flex items-start">
                        <i class="fa-solid fa-circle-check text-indigo-600 mt-1 mr-3"></i>
                        <span class="text-slate-650 text-xs leading-normal">Transparent monthly payslip downloads via employee portal</span>
                    </li>
                    <li class="flex items-start">
                        <i class="fa-solid fa-circle-check text-indigo-600 mt-1 mr-3"></i>
                        <span class="text-slate-650 text-xs leading-normal">High-quality FPDF generated legal documents with zero errors</span>
                    </li>
                </ul>
            </div>
        </div>
    </div>

    <!-- Vision & Mission Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
        <div class="bg-white p-8 rounded-3xl border border-slate-200 shadow-sm space-y-4">
            <div class="w-12 h-12 rounded-xl bg-indigo-50 text-indigo-600 flex items-center justify-center">
                <i class="fa-solid fa-eye text-xl"></i>
            </div>
            <h3 class="font-outfit font-bold text-xl text-slate-800">Our Vision</h3>
            <p class="text-slate-650 text-xs leading-relaxed">
                To be the most trusted global partner in manpower supply and employee management services, recognized for our commitment to quality, speed, and employee welfare.
            </p>
        </div>

        <div class="bg-white p-8 rounded-3xl border border-slate-200 shadow-sm space-y-4">
            <div class="w-12 h-12 rounded-xl bg-cyan-50 text-cyan-600 flex items-center justify-center">
                <i class="fa-solid fa-bullseye text-xl"></i>
            </div>
            <h3 class="font-outfit font-bold text-xl text-slate-800">Our Mission</h3>
            <p class="text-slate-650 text-xs leading-relaxed">
                To empower organizations with top-quality candidates, while providing employees with clear career growth, timely document generation, transparent financial payouts, and a supportive digital portal.
            </p>
        </div>
    </div>
</div>
@endsection
