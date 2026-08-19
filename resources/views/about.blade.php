@extends('layouts.app')

@section('title', 'About Us - RM HR Solutions')

@section('content')
<!-- Hero Banner -->
<div class="relative bg-gradient-to-r from-slate-900 via-purple-950 to-slate-900 py-20 sm:py-28 overflow-hidden">
    <div class="absolute inset-0 opacity-10 bg-[radial-gradient(circle_at_top_right,_var(--tw-gradient-stops))] from-purple-500 via-indigo-500 to-transparent"></div>
    <div class="absolute top-0 right-0 w-96 h-96 bg-purple-500/10 rounded-full blur-3xl -mr-20 -mt-20"></div>
    <div class="absolute bottom-0 left-0 w-96 h-96 bg-indigo-500/10 rounded-full blur-3xl -ml-20 -mb-20"></div>
    
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10 text-center space-y-4">
        <h1 class="font-outfit font-black text-4xl sm:text-6xl text-white tracking-tight">
            About Our Company
        </h1>
        <p class="mt-4 text-slate-300 text-sm sm:text-base max-w-xl mx-auto font-medium">
            Bridging talent and compliance with tailor-made staffing solutions.
        </p>
    </div>
</div>

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16 sm:py-24">
    <!-- Introduction Section -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 sm:gap-16 items-start mb-24">
        <div class="lg:col-span-5 space-y-4">
            <span class="inline-block text-[11px] font-bold uppercase tracking-widest text-purple-600 bg-purple-50 px-3.5 py-1.5 rounded-full border border-purple-100">
                Who We Are
            </span>
            <h2 class="font-outfit font-black text-3xl sm:text-5xl text-slate-800 leading-tight">
                Empowering business through tailored staffing solutions
            </h2>
        </div>
        <div class="lg:col-span-7 space-y-6 text-slate-650 text-sm leading-relaxed font-medium">
            <p class="text-slate-850 font-extrabold text-base leading-relaxed">
                {{ $cms['about_text'] }}
            </p>
            <p>
                Founded on the pillars of flexibility, cost-effectiveness, and compliance, RM HR Solutions has consistently pushed the envelope in human resource management. We harness modern database matching, detailed applicant profiling, and an in-house training platform to connect local candidate talent pools with enterprise requirements.
            </p>
            <p>
                Our core solutions span Contractual Staffing, Permanent Recruitment, Payroll Management, and Compliance Outsourcing. By ensuring a 100% KYC and statutory compliance guarantee, we help our partners optimize workforce productivity while eliminating operational risks.
            </p>
        </div>
    </div>

    <!-- Stats Section -->
    <div class="bg-gradient-to-r from-sky-50 to-indigo-50 border border-sky-100 rounded-3xl p-8 sm:p-12 mb-24 relative overflow-hidden">
        <div class="absolute -top-10 -right-10 w-40 h-40 bg-purple-200/30 rounded-full blur-2xl"></div>
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-8 text-center relative z-10">
            <div class="space-y-2">
                <span class="block font-outfit font-black text-4xl sm:text-5xl text-purple-700">50+</span>
                <span class="block text-xs font-bold uppercase tracking-wider text-slate-500">Enterprise Clients</span>
            </div>
            <div class="space-y-2">
                <span class="block font-outfit font-black text-4xl sm:text-5xl text-purple-700">5,000+</span>
                <span class="block text-xs font-bold uppercase tracking-wider text-slate-500">Talent Placements</span>
            </div>
            <div class="space-y-2">
                <span class="block font-outfit font-black text-4xl sm:text-5xl text-purple-700">3+</span>
                <span class="block text-xs font-bold uppercase tracking-wider text-slate-500">Branch Locations</span>
            </div>
            <div class="space-y-2">
                <span class="block font-outfit font-black text-4xl sm:text-5xl text-purple-700">100%</span>
                <span class="block text-xs font-bold uppercase tracking-wider text-slate-500">KYC Compliant</span>
            </div>
        </div>
    </div>

    <!-- Vision, Mission & Core Values Grid -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-8 mb-24">
        <!-- Vision -->
        <div class="bg-white p-8 rounded-3xl border border-slate-200 shadow-sm space-y-4 hover:shadow-md transition-shadow duration-300">
            <div class="w-12 h-12 rounded-xl bg-purple-50 text-purple-600 flex items-center justify-center">
                <i class="fa-solid fa-eye text-xl"></i>
            </div>
            <h3 class="font-outfit font-bold text-xl text-slate-800">Our Vision</h3>
            <p class="text-slate-650 text-xs leading-relaxed font-medium">
                To be the region's most trusted manpower management and consulting partner, recognized for exceptional compliance standards, service ethics, and operational excellence.
            </p>
        </div>

        <!-- Mission -->
        <div class="bg-white p-8 rounded-3xl border border-slate-200 shadow-sm space-y-4 hover:shadow-md transition-shadow duration-300">
            <div class="w-12 h-12 rounded-xl bg-sky-50 text-sky-600 flex items-center justify-center">
                <i class="fa-solid fa-bullseye text-xl"></i>
            </div>
            <h3 class="font-outfit font-bold text-xl text-slate-800">Our Mission</h3>
            <p class="text-slate-650 text-xs leading-relaxed font-medium">
                To bridge recruitment bottlenecks for our corporate clients with compliant, responsive staffing resources while providing placed candidates with robust career opportunities.
            </p>
        </div>

        <!-- Core Values -->
        <div class="bg-white p-8 rounded-3xl border border-slate-200 shadow-sm space-y-4 hover:shadow-md transition-shadow duration-300">
            <div class="w-12 h-12 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center">
                <i class="fa-solid fa-handshake text-xl"></i>
            </div>
            <h3 class="font-outfit font-bold text-xl text-slate-800">Core Values</h3>
            <p class="text-slate-650 text-xs leading-relaxed font-medium">
                We strongly uphold our core values of <strong class="text-slate-800">Transparency, Integrity, Respect, and Passion</strong>. This builds a strong foundation for creating extraordinary client and candidate relationships.
            </p>
        </div>
    </div>

    <!-- Timeline / Story Section -->
    <div class="mb-24">
        <div class="text-center max-w-3xl mx-auto mb-16 space-y-3">
            <span class="text-xs font-bold uppercase tracking-widest text-purple-600 bg-purple-50 px-3 py-1.5 rounded-full border border-purple-100">Journey So Far</span>
            <h2 class="font-outfit font-black text-3xl sm:text-5xl text-slate-850">Our Story & Milestones</h2>
            <p class="text-sm text-slate-500 max-w-xl mx-auto font-medium">
                Sailing through challenges and redefining the recruitment landscape year by year.
            </p>
        </div>

        <div class="relative border-l border-slate-200 ml-4 md:ml-32 space-y-12">
            <!-- 2016 -->
            <div class="relative pl-8 md:pl-12">
                <div class="absolute -left-[9px] top-1.5 w-4 h-4 rounded-full bg-purple-600 border-4 border-white shadow-sm"></div>
                <div class="absolute -left-20 top-0 hidden md:block w-16 text-right">
                    <span class="font-outfit font-black text-lg text-purple-600">2016</span>
                </div>
                <div class="space-y-1">
                    <span class="font-outfit font-black text-lg text-purple-600 md:hidden">2016</span>
                    <h3 class="font-outfit font-extrabold text-lg text-slate-850">Genesis in West Bengal</h3>
                    <p class="text-xs text-slate-600 leading-relaxed font-medium max-w-2xl">
                        RM HR Solutions was established in West Bengal with a primary mission of providing high-quality compliance-driven staffing to regional industries.
                    </p>
                </div>
            </div>

            <!-- 2018 -->
            <div class="relative pl-8 md:pl-12">
                <div class="absolute -left-[9px] top-1.5 w-4 h-4 rounded-full bg-purple-600 border-4 border-white shadow-sm"></div>
                <div class="absolute -left-20 top-0 hidden md:block w-16 text-right">
                    <span class="font-outfit font-black text-lg text-purple-600">2018</span>
                </div>
                <div class="space-y-1">
                    <span class="font-outfit font-black text-lg text-purple-600 md:hidden">2018</span>
                    <h3 class="font-outfit font-extrabold text-lg text-slate-850">Sector Diversification</h3>
                    <p class="text-xs text-slate-600 leading-relaxed font-medium max-w-2xl">
                        Successfully diversified operations, venturing into telecommunication and engineering sectors with tailored recruitment packages.
                    </p>
                </div>
            </div>

            <!-- 2020 -->
            <div class="relative pl-8 md:pl-12">
                <div class="absolute -left-[9px] top-1.5 w-4 h-4 rounded-full bg-purple-600 border-4 border-white shadow-sm"></div>
                <div class="absolute -left-20 top-0 hidden md:block w-16 text-right">
                    <span class="font-outfit font-black text-lg text-purple-600">2020</span>
                </div>
                <div class="space-y-1">
                    <span class="font-outfit font-black text-lg text-purple-600 md:hidden">2020</span>
                    <h3 class="font-outfit font-extrabold text-lg text-slate-850">Enterprise Scaling</h3>
                    <p class="text-xs text-slate-600 leading-relaxed font-medium max-w-2xl">
                        Secured major contracts with logistics and e-commerce giants, scaling our monthly payroll footprint to support operations during high-demand shifts.
                    </p>
                </div>
            </div>

            <!-- 2022 -->
            <div class="relative pl-8 md:pl-12">
                <div class="absolute -left-[9px] top-1.5 w-4 h-4 rounded-full bg-purple-600 border-4 border-white shadow-sm"></div>
                <div class="absolute -left-20 top-0 hidden md:block w-16 text-right">
                    <span class="font-outfit font-black text-lg text-purple-600">2022</span>
                </div>
                <div class="space-y-1">
                    <span class="font-outfit font-black text-lg text-purple-600 md:hidden">2022</span>
                    <h3 class="font-outfit font-extrabold text-lg text-slate-850">100% KYC & Govt EPF Compliance</h3>
                    <p class="text-xs text-slate-600 leading-relaxed font-medium max-w-2xl">
                        Implemented rigorous digitized validation audits. Achieved a verified 100% KYC enrollment milestone across all active client locations.
                    </p>
                </div>
            </div>

            <!-- 2024 -->
            <div class="relative pl-8 md:pl-12">
                <div class="absolute -left-[9px] top-1.5 w-4 h-4 rounded-full bg-purple-600 border-4 border-white shadow-sm"></div>
                <div class="absolute -left-20 top-0 hidden md:block w-16 text-right">
                    <span class="font-outfit font-black text-lg text-purple-600">2024</span>
                </div>
                <div class="space-y-1">
                    <span class="font-outfit font-black text-lg text-purple-600 md:hidden">2024</span>
                    <h3 class="font-outfit font-extrabold text-lg text-slate-850">Self-Service Portals & Automation</h3>
                    <p class="text-xs text-slate-600 leading-relaxed font-medium max-w-2xl">
                        Launched our integrated custom employee and admin portal, automating payslip distribution and legal offer letters with FPDF generation templates.
                    </p>
                </div>
            </div>
        </div>
    </div>

    <!-- Testimonials Section -->
    <div class="mb-24">
        <div class="text-center max-w-3xl mx-auto mb-16 space-y-3">
            <span class="text-xs font-bold uppercase tracking-widest text-sky-600 bg-sky-50 px-3 py-1.5 rounded-full border border-sky-100">Testimonials</span>
            <h2 class="font-outfit font-black text-3xl sm:text-5xl text-slate-850">What Our Partners Say</h2>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
            <div class="bg-white p-8 rounded-3xl border border-slate-200 shadow-sm space-y-4 hover:shadow-md transition-shadow duration-300">
                <p class="text-slate-600 text-xs italic font-medium leading-relaxed">
                    "RM HR Solutions has been a phenomenal staffing partner. Their client service is extremely fast and they adhere strictly to all statutory and compliance parameters."
                </p>
                <div class="flex items-center space-x-3 pt-2">
                    <div class="w-10 h-10 rounded-full bg-slate-100 flex items-center justify-center font-bold text-sky-600">B</div>
                    <div>
                        <h4 class="text-xs font-bold text-slate-800">Big Basket Client Operations</h4>
                        <span class="text-[10px] text-slate-400">Logistics & Supply Partner</span>
                    </div>
                </div>
            </div>

            <div class="bg-white p-8 rounded-3xl border border-slate-200 shadow-sm space-y-4 hover:shadow-md transition-shadow duration-300">
                <p class="text-slate-600 text-xs italic font-medium leading-relaxed">
                    "We have worked with RM HR Solutions for years. Their automated onboarding process and document compliance is prompt and highly responsive to corporate growth needs."
                </p>
                <div class="flex items-center space-x-3 pt-2">
                    <div class="w-10 h-10 rounded-full bg-slate-100 flex items-center justify-center font-bold text-purple-600">A</div>
                    <div>
                        <h4 class="text-xs font-bold text-slate-800">Aparoksha Financial Services</h4>
                        <span class="text-[10px] text-slate-400">Enterprise Payout Client</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Awards & Recognition Section -->
    <div>
        <div class="text-center max-w-3xl mx-auto mb-16 space-y-3">
            <span class="text-xs font-bold uppercase tracking-widest text-emerald-600 bg-emerald-50 px-3 py-1.5 rounded-full border border-emerald-100">Recognition</span>
            <h2 class="font-outfit font-black text-3xl sm:text-5xl text-slate-850">Awards & Certifications</h2>
        </div>

        <div class="grid grid-cols-2 sm:grid-cols-4 gap-6">
            <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm text-center space-y-2">
                <i class="fa-solid fa-trophy text-amber-500 text-2xl"></i>
                <h4 class="font-bold text-xs text-slate-800">Quality Brand Award</h4>
                <span class="block text-[10px] text-slate-400">HR Services Excellence</span>
            </div>
            <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm text-center space-y-2">
                <i class="fa-solid fa-ranking-star text-purple-500 text-2xl"></i>
                <h4 class="font-bold text-xs text-slate-800">Fastest Growing Firm</h4>
                <span class="block text-[10px] text-slate-400">Regional Expansion</span>
            </div>
            <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm text-center space-y-2">
                <i class="fa-solid fa-shield-halved text-sky-500 text-2xl"></i>
                <h4 class="font-bold text-xs text-slate-800">100% KYC Certification</h4>
                <span class="block text-[10px] text-slate-400">Statutory Department EPF</span>
            </div>
            <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm text-center space-y-2">
                <i class="fa-solid fa-ribbon text-emerald-500 text-2xl"></i>
                <h4 class="font-bold text-xs text-slate-800">Small Business Honors</h4>
                <span class="block text-[10px] text-slate-400">Recruitment Excellence</span>
            </div>
        </div>
    </div>

</div>
@endsection
