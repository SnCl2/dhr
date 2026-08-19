@extends('layouts.app')

@section('title', 'Our Services - RM HR Solutions')

@section('content')
<!-- Hero Banner -->
<div class="relative bg-gradient-to-r from-slate-900 via-purple-950 to-slate-900 py-20 sm:py-28 overflow-hidden">
    <div class="absolute inset-0 opacity-10 bg-[radial-gradient(circle_at_top_right,_var(--tw-gradient-stops))] from-purple-500 via-indigo-500 to-transparent"></div>
    <div class="absolute top-0 right-0 w-96 h-96 bg-purple-500/10 rounded-full blur-3xl -mr-20 -mt-20"></div>
    <div class="absolute bottom-0 left-0 w-96 h-96 bg-indigo-500/10 rounded-full blur-3xl -ml-20 -mb-20"></div>
    
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10 text-center space-y-4">
        <h1 class="font-outfit font-black text-4xl sm:text-6xl text-white tracking-tight">
            Our Staffing & HR Services
        </h1>
        <p class="mt-4 text-slate-300 text-sm sm:text-base max-w-xl mx-auto font-medium">
            Empowering enterprise growth with compliant, flexible, and integrated talent resources.
        </p>
    </div>
</div>

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16 sm:py-24">
    <!-- Header/Introduction -->
    <div class="text-center max-w-3xl mx-auto mb-16 sm:mb-20 space-y-3">
        <span class="text-xs font-bold uppercase tracking-widest text-purple-600 bg-purple-50 px-3.5 py-1.5 rounded-full border border-purple-100">
            What We Do
        </span>
        <h2 class="font-outfit font-black text-3xl sm:text-5xl text-slate-850">
            Comprehensive HR Solutions
        </h2>
        <p class="text-sm text-slate-500 max-w-xl mx-auto font-medium">
            We provide a wide array of staffing resources configured to align with your organization's operational structure and statutory requirements.
        </p>
    </div>

    <!-- 8 Services Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8 mb-20">
        <!-- Service 1: Permanent Staffing -->
        <div class="bg-white p-8 rounded-3xl border border-slate-200 shadow-sm space-y-5 hover:shadow-md transition-shadow duration-300 flex flex-col justify-between">
            <div class="space-y-4">
                <div class="w-12 h-12 rounded-xl bg-purple-50 text-purple-600 flex items-center justify-center shrink-0">
                    <i class="fa-solid fa-user-tie text-xl"></i>
                </div>
                <h3 class="font-outfit font-bold text-lg text-slate-800">Permanent Staffing</h3>
                <p class="text-slate-650 text-xs leading-relaxed font-medium">
                    Acquire high-caliber professionals custom-profiled for your permanent organizational requirements to support long-term stability and business growth.
                </p>
            </div>
            <div class="pt-2">
                <span class="text-[10px] font-bold text-purple-600 bg-purple-50 px-2.5 py-1 rounded-md">Talent Acquisition</span>
            </div>
        </div>

        <!-- Service 2: Contractual Staffing -->
        <div class="bg-white p-8 rounded-3xl border border-slate-200 shadow-sm space-y-5 hover:shadow-md transition-shadow duration-300 flex flex-col justify-between">
            <div class="space-y-4">
                <div class="w-12 h-12 rounded-xl bg-sky-50 text-sky-600 flex items-center justify-center shrink-0">
                    <i class="fa-solid fa-file-contract text-xl"></i>
                </div>
                <h3 class="font-outfit font-bold text-lg text-slate-800">Contractual Staffing</h3>
                <p class="text-slate-650 text-xs leading-relaxed font-medium">
                    Scale your operating teams dynamically based on seasonal demands with flexible contractual personnel deployments.
                </p>
            </div>
            <div class="pt-2">
                <span class="text-[10px] font-bold text-sky-600 bg-sky-50 px-2.5 py-1 rounded-md">Flexible Staffing</span>
            </div>
        </div>

        <!-- Service 3: Payroll Management -->
        <div class="bg-white p-8 rounded-3xl border border-slate-200 shadow-sm space-y-5 hover:shadow-md transition-shadow duration-300 flex flex-col justify-between">
            <div class="space-y-4">
                <div class="w-12 h-12 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center shrink-0">
                    <i class="fa-solid fa-file-invoice-dollar text-xl"></i>
                </div>
                <h3 class="font-outfit font-bold text-lg text-slate-800">Payroll Management</h3>
                <p class="text-slate-650 text-xs leading-relaxed font-medium">
                    Streamline monthly calculations, payouts, tax deductions, and detailed Payslip Statements with zero operational compliance errors.
                </p>
            </div>
            <div class="pt-2">
                <span class="text-[10px] font-bold text-emerald-600 bg-emerald-50 px-2.5 py-1 rounded-md">Automated Payroll</span>
            </div>
        </div>

        <!-- Service 4: Compliance Outsourcing -->
        <div class="bg-white p-8 rounded-3xl border border-slate-200 shadow-sm space-y-5 hover:shadow-md transition-shadow duration-300 flex flex-col justify-between">
            <div class="space-y-4">
                <div class="w-12 h-12 rounded-xl bg-indigo-50 text-indigo-600 flex items-center justify-center shrink-0">
                    <i class="fa-solid fa-shield-halved text-xl"></i>
                </div>
                <h3 class="font-outfit font-bold text-lg text-slate-800">Compliance Outsourcing</h3>
                <p class="text-slate-650 text-xs leading-relaxed font-medium">
                    Manage statutory compliance audits, including Provident Fund (PF), ESIC, and regional labor code registries seamlessly.
                </p>
            </div>
            <div class="pt-2">
                <span class="text-[10px] font-bold text-indigo-600 bg-indigo-50 px-2.5 py-1 rounded-md">Statutory Audit</span>
            </div>
        </div>

        <!-- Service 5: Migrant Labour Management -->
        <div class="bg-white p-8 rounded-3xl border border-slate-200 shadow-sm space-y-5 hover:shadow-md transition-shadow duration-300 flex flex-col justify-between">
            <div class="space-y-4">
                <div class="w-12 h-12 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center shrink-0">
                    <i class="fa-solid fa-bus-simple text-xl"></i>
                </div>
                <h3 class="font-outfit font-bold text-lg text-slate-800">Migrant Labour Management</h3>
                <p class="text-slate-650 text-xs leading-relaxed font-medium">
                    Onboard, transport, and house regional workers safely with structured operational support and transparent compliance records.
                </p>
            </div>
            <div class="pt-2">
                <span class="text-[10px] font-bold text-amber-600 bg-amber-50 px-2.5 py-1 rounded-md">Logistics & Welfare</span>
            </div>
        </div>

        <!-- Service 6: Expat Hiring -->
        <div class="bg-white p-8 rounded-3xl border border-slate-200 shadow-sm space-y-5 hover:shadow-md transition-shadow duration-300 flex flex-col justify-between">
            <div class="space-y-4">
                <div class="w-12 h-12 rounded-xl bg-pink-50 text-pink-600 flex items-center justify-center shrink-0">
                    <i class="fa-solid fa-earth-americas text-xl"></i>
                </div>
                <h3 class="font-outfit font-bold text-lg text-slate-800">Expat Hiring</h3>
                <p class="text-slate-650 text-xs leading-relaxed font-medium">
                    Identify and place premium cross-border executive candidates for senior corporate management and board-level responsibilities.
                </p>
            </div>
            <div class="pt-2">
                <span class="text-[10px] font-bold text-pink-600 bg-pink-50 px-2.5 py-1 rounded-md">Global Executive</span>
            </div>
        </div>

        <!-- Service 7: HR Consulting -->
        <div class="bg-white p-8 rounded-3xl border border-slate-200 shadow-sm space-y-5 hover:shadow-md transition-shadow duration-300 flex flex-col justify-between">
            <div class="space-y-4">
                <div class="w-12 h-12 rounded-xl bg-rose-50 text-rose-600 flex items-center justify-center shrink-0">
                    <i class="fa-solid fa-user-gear text-xl"></i>
                </div>
                <h3 class="font-outfit font-bold text-lg text-slate-800">HR Consulting</h3>
                <p class="text-slate-650 text-xs leading-relaxed font-medium">
                    Receive strategic organizational advisory, job description metrics, policy design, and operational audits.
                </p>
            </div>
            <div class="pt-2">
                <span class="text-[10px] font-bold text-rose-600 bg-rose-50 px-2.5 py-1 rounded-md">Advisory & Policy</span>
            </div>
        </div>

        <!-- Service 8: NAPS Promotion -->
        <div class="bg-white p-8 rounded-3xl border border-slate-200 shadow-sm space-y-5 hover:shadow-md transition-shadow duration-300 flex flex-col justify-between">
            <div class="space-y-4">
                <div class="w-12 h-12 rounded-xl bg-teal-50 text-teal-600 flex items-center justify-center shrink-0">
                    <i class="fa-solid fa-graduation-cap text-xl"></i>
                </div>
                <h3 class="font-outfit font-bold text-lg text-slate-800">NAPS Promotion</h3>
                <p class="text-slate-650 text-xs leading-relaxed font-medium">
                    Implement statutory training workflows under the National Apprenticeship Promotion Scheme coordinates.
                </p>
            </div>
            <div class="pt-2">
                <span class="text-[10px] font-bold text-teal-600 bg-teal-50 px-2.5 py-1 rounded-md">Apprenticeship</span>
            </div>
        </div>
    </div>

    <!-- Call to Action Banner -->
    <div class="text-center">
        <div class="bg-indigo-950 rounded-3xl p-8 sm:p-12 text-white shadow-xl relative overflow-hidden">
            <div class="absolute -top-12 -left-12 w-64 h-64 rounded-full bg-white/5 blur-2xl"></div>
            <div class="absolute -bottom-12 -right-12 w-64 h-64 rounded-full bg-white/5 blur-2xl"></div>

            <h2 class="font-outfit font-black text-2xl sm:text-4xl mb-4 relative z-10">
                Ready to optimize your workforce?
            </h2>
            <p class="text-slate-300 max-w-xl mx-auto mb-8 text-sm leading-relaxed relative z-10">
                Connect with our recruitment consulting representatives to organize, scale, and manage statutory employee structures.
            </p>
            <a href="{{ route('contact') }}" class="inline-flex items-center px-8 py-4 bg-cyan-500 hover:bg-cyan-400 text-slate-900 rounded-full text-sm font-black uppercase tracking-wider shadow-lg shadow-cyan-500/20 hover:scale-[1.02] transition-all duration-300 relative z-10">
                Book a Consulting <i class="fa-solid fa-angle-right ml-2 text-slate-900"></i>
            </a>
        </div>
    </div>

</div>
@endsection
