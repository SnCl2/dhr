@extends('layouts.app')

@section('title', 'Our Services - RM HR Solutions')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16 sm:py-24">
    <!-- Header -->
    <div class="text-center max-w-3xl mx-auto mb-16 space-y-3">
        <span class="text-xs font-bold uppercase tracking-widest text-indigo-600 bg-indigo-50 px-3 py-1.5 rounded-full border border-indigo-100">Solutions</span>
        <h1 class="font-outfit font-black text-4xl sm:text-5xl text-slate-850">
            Manpower & Staffing Services
        </h1>
        <p class="text-sm text-slate-500 max-w-xl mx-auto">
            We provide a wide array of staffing resources configured to align with your organization structure.
        </p>
    </div>

    <!-- Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-16">
        <!-- Card 1 -->
        <div class="bg-white p-8 rounded-3xl border border-slate-200 shadow-sm flex items-start space-x-6">
            <div class="w-12 h-12 rounded-xl bg-indigo-50 text-indigo-600 flex items-center justify-center shrink-0">
                <i class="fa-solid fa-people-group text-xl"></i>
            </div>
            <div class="space-y-2">
                <h3 class="font-outfit font-bold text-lg text-slate-800">Temporary Contract Staffing</h3>
                <p class="text-slate-600 text-xs leading-relaxed">
                    Provide workforce for seasonal surges, event management, assembly line bottlenecks, or project-specific requirements with standard internal and external onboarding controls.
                </p>
            </div>
        </div>

        <!-- Card 2 -->
        <div class="bg-white p-8 rounded-3xl border border-slate-200 shadow-sm flex items-start space-x-6">
            <div class="w-12 h-12 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center shrink-0">
                <i class="fa-solid fa-user-check text-xl"></i>
            </div>
            <div class="space-y-2">
                <h3 class="font-outfit font-bold text-lg text-slate-800">Permanent Recruitment</h3>
                <p class="text-slate-600 text-xs leading-relaxed">
                    Connecting your enterprise with certified technical experts, operational staff, and management specialists for long-term placements.
                </p>
            </div>
        </div>

        <!-- Card 3 -->
        <div class="bg-white p-8 rounded-3xl border border-slate-200 shadow-sm flex items-start space-x-6">
            <div class="w-12 h-12 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center shrink-0">
                <i class="fa-solid fa-file-invoice-dollar text-xl"></i>
            </div>
            <div class="space-y-2">
                <h3 class="font-outfit font-bold text-lg text-slate-800">Payroll & Document Management</h3>
                <p class="text-slate-600 text-xs leading-relaxed">
                    Comprehensive payroll support including automatic generation of PDF payslips and contract offer letters using clean FPDF templates.
                </p>
            </div>
        </div>

        <!-- Card 4 -->
        <div class="bg-white p-8 rounded-3xl border border-slate-200 shadow-sm flex items-start space-x-6">
            <div class="w-12 h-12 rounded-xl bg-purple-50 text-purple-600 flex items-center justify-center shrink-0">
                <i class="fa-solid fa-clock-rotate-left text-xl"></i>
            </div>
            <div class="space-y-2">
                <h3 class="font-outfit font-bold text-lg text-slate-800">On-demand Labor Force</h3>
                <p class="text-slate-600 text-xs leading-relaxed">
                    Instant access to warehouse packing staff, loaders, supervisors, and logistics handlers with transparent profile management.
                </p>
            </div>
        </div>
    </div>
</div>
@endsection
