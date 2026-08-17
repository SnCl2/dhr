@extends('layouts.app')

@section('title', 'Our Services - Propszy Manpower Solutions')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16 sm:py-24">
    <!-- Header -->
    <div class="text-center max-w-3xl mx-auto mb-16">
        <span class="text-xs font-semibold uppercase tracking-wider text-purple-400 bg-purple-500/10 px-3 py-1 rounded-full border border-purple-500/20">Solutions</span>
        <h1 class="font-outfit font-extrabold text-4xl sm:text-5xl text-white mt-4">
            Manpower & Staffing Services
        </h1>
        <p class="mt-4 text-slate-400">
            We provide a wide array of staffing resources configured to align with your organization structure.
        </p>
    </div>

    <!-- Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-16">
        <!-- Card 1 -->
        <div class="glass-dark p-8 rounded-3xl border border-slate-800 flex items-start space-x-6">
            <div class="w-12 h-12 rounded-xl bg-purple-500/10 text-purple-400 flex items-center justify-center shrink-0">
                <i class="fa-solid fa-people-group text-xl"></i>
            </div>
            <div>
                <h3 class="font-outfit font-bold text-lg text-white mb-2">Temporary Contract Staffing</h3>
                <p class="text-slate-400 text-sm leading-relaxed">
                    Provide workforce for seasonal surges, event management, assembly line bottlenecks, or project-specific requirements with standard internal and external onboarding controls.
                </p>
            </div>
        </div>

        <!-- Card 2 -->
        <div class="glass-dark p-8 rounded-3xl border border-slate-800 flex items-start space-x-6">
            <div class="w-12 h-12 rounded-xl bg-indigo-500/10 text-indigo-400 flex items-center justify-center shrink-0">
                <i class="fa-solid fa-user-check text-xl"></i>
            </div>
            <div>
                <h3 class="font-outfit font-bold text-lg text-white mb-2">Permanent Recruitment</h3>
                <p class="text-slate-400 text-sm leading-relaxed">
                    Connecting your enterprise with certified technical experts and management specialists for long-term placement.
                </p>
            </div>
        </div>

        <!-- Card 3 -->
        <div class="glass-dark p-8 rounded-3xl border border-slate-800 flex items-start space-x-6">
            <div class="w-12 h-12 rounded-xl bg-pink-500/10 text-pink-400 flex items-center justify-center shrink-0">
                <i class="fa-solid fa-file-invoice-dollar text-xl"></i>
            </div>
            <div>
                <h3 class="font-outfit font-bold text-lg text-white mb-2">Payroll & Document Management</h3>
                <p class="text-slate-400 text-sm leading-relaxed">
                    Comprehensive payroll support including automatic generation of PDF payslips and contract offer letters using clean FPDF templates.
                </p>
            </div>
        </div>

        <!-- Card 4 -->
        <div class="glass-dark p-8 rounded-3xl border border-slate-800 flex items-start space-x-6">
            <div class="w-12 h-12 rounded-xl bg-amber-500/10 text-amber-400 flex items-center justify-center shrink-0">
                <i class="fa-solid fa-clock-rotate-left text-xl"></i>
            </div>
            <div>
                <h3 class="font-outfit font-bold text-lg text-white mb-2">On-demand Labor Force</h3>
                <p class="text-slate-400 text-sm leading-relaxed">
                    Instant access to warehouse packing staff, loaders, supervisors, and logistics handlers with transparent profile management.
                </p>
            </div>
        </div>
    </div>
</div>
@endsection
