@extends('layouts.app')

@section('title', 'RM HR Solutions - Premium Staffing & HR Consulting Partner')

@section('content')
<!-- Section 1: Hero Banner (Main Banner) -->
<div class="relative overflow-hidden gradient-bg py-20 sm:py-28 border-b border-indigo-100">
    <!-- Background accents -->
    <div class="absolute top-0 right-0 -mt-12 -mr-12 w-96 h-96 rounded-full bg-blue-500/10 blur-3xl"></div>
    <div class="absolute bottom-0 left-0 -mb-12 -ml-12 w-96 h-96 rounded-full bg-indigo-500/10 blur-3xl"></div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
        <div class="lg:grid lg:grid-cols-12 lg:gap-12 items-center">
            <!-- Left: Hero Text -->
            <div class="sm:text-center md:max-w-2xl md:mx-auto lg:col-span-7 lg:text-left space-y-6">
                <span class="inline-flex items-center px-3.5 py-1.5 rounded-full text-xs font-bold bg-indigo-100 text-indigo-700 border border-indigo-200 uppercase tracking-wider">
                    <i class="fa-solid fa-sparkles mr-1.5 animate-pulse"></i> Multi-Industry HR Leader Since 2010
                </span>
                <h1 class="font-outfit font-black text-4xl sm:text-5xl lg:text-6xl tracking-tight text-slate-900 leading-tight">
                    Empowering Business Through <span class="text-indigo-600">Integrated & Tailored</span> Staffing Solutions
                </h1>
                <p class="text-md sm:text-lg text-slate-600 leading-relaxed font-sans">
                    RM HR Solutions is a pioneer in multi-industry HR consulting and talent acquisition. We redfined the recruitment landscape by matching multinats and leading business houses with the best-suited workforce in India.
                </p>
                <div class="pt-4 flex flex-col sm:flex-row sm:justify-center lg:justify-start gap-4">
                    <a href="{{ route('about') }}" class="flex items-center justify-center px-8 py-4 bg-indigo-650 hover:bg-indigo-600 text-white rounded-2xl text-base font-bold shadow-lg shadow-indigo-600/20 hover:scale-[1.02] transition-all duration-300">
                        <i class="fa-solid fa-circle-info mr-2"></i> Get to Know Us
                    </a>
                    <a href="{{ route('contact') }}" class="flex items-center justify-center px-8 py-4 border border-slate-300 hover:border-indigo-400 bg-white text-slate-700 hover:text-indigo-600 rounded-2xl text-base font-bold hover:scale-[1.02] transition-all duration-300">
                        <i class="fa-solid fa-paper-plane mr-2 text-indigo-500"></i> Contact Us
                    </a>
                </div>
            </div>

            <!-- Right: Dynamic Stats Grid -->
            <div class="mt-12 sm:mt-16 lg:mt-0 lg:col-span-5">
                <div class="glass p-8 rounded-3xl relative shadow-xl border border-white/60">
                    <div class="absolute -top-6 -left-6 w-12 h-12 rounded-2xl bg-indigo-600 flex items-center justify-center text-white shadow-lg">
                        <i class="fa-solid fa-shield-halved text-lg"></i>
                    </div>
                    <h3 class="font-outfit font-bold text-xl text-slate-800 mb-6 flex items-center">
                        <span class="w-2.5 h-2.5 rounded-full bg-indigo-600 mr-2.5 animate-ping"></span>
                        RM HR Operations Database
                    </h3>
                    <div class="grid grid-cols-2 gap-4">
                        <div class="bg-white/80 p-5 rounded-2xl border border-indigo-50">
                            <span class="block font-outfit font-black text-3xl text-indigo-700">{{ $stats['total_employees'] }}+</span>
                            <span class="block text-slate-500 text-[10px] font-bold uppercase tracking-wider mt-1">Hired Staff</span>
                        </div>
                        <div class="bg-white/80 p-5 rounded-2xl border border-indigo-50">
                            <span class="block font-outfit font-black text-3xl text-blue-700">{{ $stats['active_placements'] }}</span>
                            <span class="block text-slate-500 text-[10px] font-bold uppercase tracking-wider mt-1">Active Placements</span>
                        </div>
                        <div class="bg-white/80 p-5 rounded-2xl border border-indigo-50 col-span-2 flex items-center justify-between">
                            <div>
                                <span class="block font-outfit font-bold text-base text-slate-800">Secure Self-Service</span>
                                <span class="block text-slate-500 text-[10px] font-bold uppercase tracking-wider mt-1">For Candidates & Employees</span>
                            </div>
                            <a href="{{ route('login') }}" class="px-4 py-2 bg-indigo-50 text-indigo-650 hover:bg-indigo-100 rounded-xl text-xs font-bold transition-all">
                                Login <i class="fa-solid fa-arrow-right ml-1"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Section 2: Services We Provide -->
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-20 border-b border-indigo-50">
    <div class="text-center max-w-3xl mx-auto mb-16">
        <h2 class="font-outfit font-extrabold text-3xl sm:text-4xl text-slate-900">
            Services We Provide
        </h2>
        <div class="w-16 h-1 bg-indigo-600 mx-auto mt-4 rounded-full"></div>
        <p class="mt-4 text-slate-650 text-sm sm:text-base">
            Providing reliable statutory compliance, flexi-staffing, and premium multi-sector talent sourcing models.
        </p>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
        <!-- Permanent Staffing -->
        <div class="bg-white p-6 rounded-2xl border border-slate-150 hover:border-indigo-400 hover:shadow-md transition-all duration-300 hover:-translate-y-1 flex flex-col justify-between">
            <div>
                <div class="w-10 h-10 rounded-xl bg-indigo-50 text-indigo-600 flex items-center justify-center mb-4">
                    <i class="fa-solid fa-user-check text-md"></i>
                </div>
                <h3 class="font-outfit font-bold text-base text-slate-800 mb-2">Permanent Staffing</h3>
                <p class="text-slate-500 text-xs leading-relaxed">
                    Source top-tier talent and operations personnel tailored for long-term strategic growth.
                </p>
            </div>
            <a href="{{ route('services') }}" class="text-xxs font-bold uppercase text-indigo-600 mt-4 inline-block hover:underline">Learn More <i class="fa-solid fa-arrow-right ml-1"></i></a>
        </div>

        <!-- Contractual Staffing -->
        <div class="bg-white p-6 rounded-2xl border border-slate-150 hover:border-indigo-400 hover:shadow-md transition-all duration-300 hover:-translate-y-1 flex flex-col justify-between">
            <div>
                <div class="w-10 h-10 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center mb-4">
                    <i class="fa-solid fa-business-time text-md"></i>
                </div>
                <h3 class="font-outfit font-bold text-base text-slate-800 mb-2">Contractual Staffing</h3>
                <p class="text-slate-500 text-xs leading-relaxed">
                    Flexible manpower scaling solutions to manage temporary and project-based workloads.
                </p>
            </div>
            <a href="{{ route('services') }}" class="text-xxs font-bold uppercase text-indigo-600 mt-4 inline-block hover:underline">Learn More <i class="fa-solid fa-arrow-right ml-1"></i></a>
        </div>

        <!-- Payroll Management -->
        <div class="bg-white p-6 rounded-2xl border border-slate-150 hover:border-indigo-400 hover:shadow-md transition-all duration-300 hover:-translate-y-1 flex flex-col justify-between">
            <div>
                <div class="w-10 h-10 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center mb-4">
                    <i class="fa-solid fa-money-check-dollar text-md"></i>
                </div>
                <h3 class="font-outfit font-bold text-base text-slate-800 mb-2">Payroll Management</h3>
                <p class="text-slate-500 text-xs leading-relaxed">
                    Seamless end-to-end payroll computation, monthly payouts, slips generation, and taxes.
                </p>
            </div>
            <a href="{{ route('services') }}" class="text-xxs font-bold uppercase text-indigo-600 mt-4 inline-block hover:underline">Learn More <i class="fa-solid fa-arrow-right ml-1"></i></a>
        </div>

        <!-- Compliance Outsourcing -->
        <div class="bg-white p-6 rounded-2xl border border-slate-150 hover:border-indigo-400 hover:shadow-md transition-all duration-300 hover:-translate-y-1 flex flex-col justify-between">
            <div>
                <div class="w-10 h-10 rounded-xl bg-purple-50 text-purple-600 flex items-center justify-center mb-4">
                    <i class="fa-solid fa-gavel text-md"></i>
                </div>
                <h3 class="font-outfit font-bold text-base text-slate-800 mb-2">Compliance Outsourcing</h3>
                <p class="text-slate-500 text-xs leading-relaxed">
                    100% compliant statutory auditing and maintenance (PF, ESIC, PTax, and Labor Codes).
                </p>
            </div>
            <a href="{{ route('services') }}" class="text-xxs font-bold uppercase text-indigo-600 mt-4 inline-block hover:underline">Learn More <i class="fa-solid fa-arrow-right ml-1"></i></a>
        </div>

        <!-- Migrant Labour Management -->
        <div class="bg-white p-6 rounded-2xl border border-slate-150 hover:border-indigo-400 hover:shadow-md transition-all duration-300 hover:-translate-y-1 flex flex-col justify-between">
            <div>
                <div class="w-10 h-10 rounded-xl bg-pink-50 text-pink-650 flex items-center justify-center mb-4">
                    <i class="fa-solid fa-people-group text-md"></i>
                </div>
                <h3 class="font-outfit font-bold text-base text-slate-800 mb-2">Migrant Labour</h3>
                <p class="text-slate-500 text-xs leading-relaxed">
                    Structured migration operations, transit support, camp layout, and local labor integrations.
                </p>
            </div>
            <a href="{{ route('services') }}" class="text-xxs font-bold uppercase text-indigo-600 mt-4 inline-block hover:underline">Learn More <i class="fa-solid fa-arrow-right ml-1"></i></a>
        </div>

        <!-- Expat Hiring -->
        <div class="bg-white p-6 rounded-2xl border border-slate-150 hover:border-indigo-400 hover:shadow-md transition-all duration-300 hover:-translate-y-1 flex flex-col justify-between">
            <div>
                <div class="w-10 h-10 rounded-xl bg-rose-50 text-rose-600 flex items-center justify-center mb-4">
                    <i class="fa-solid fa-earth-americas text-md"></i>
                </div>
                <h3 class="font-outfit font-bold text-base text-slate-800 mb-2">Expat Hiring</h3>
                <p class="text-slate-500 text-xs leading-relaxed">
                    Identify and acquire premium global leadership profiles for critical cross-border positions.
                </p>
            </div>
            <a href="{{ route('services') }}" class="text-xxs font-bold uppercase text-indigo-600 mt-4 inline-block hover:underline">Learn More <i class="fa-solid fa-arrow-right ml-1"></i></a>
        </div>

        <!-- HR Consulting -->
        <div class="bg-white p-6 rounded-2xl border border-slate-150 hover:border-indigo-400 hover:shadow-md transition-all duration-300 hover:-translate-y-1 flex flex-col justify-between">
            <div>
                <div class="w-10 h-10 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center mb-4">
                    <i class="fa-solid fa-handshake-angle text-md"></i>
                </div>
                <h3 class="font-outfit font-bold text-base text-slate-800 mb-2">HR Consulting</h3>
                <p class="text-slate-500 text-xs leading-relaxed">
                    Advisory service on workplace architecture, policies creation, and operational optimization.
                </p>
            </div>
            <a href="{{ route('services') }}" class="text-xxs font-bold uppercase text-indigo-600 mt-4 inline-block hover:underline">Learn More <i class="fa-solid fa-arrow-right ml-1"></i></a>
        </div>

        <!-- NAPS -->
        <div class="bg-white p-6 rounded-2xl border border-slate-150 hover:border-indigo-400 hover:shadow-md transition-all duration-300 hover:-translate-y-1 flex flex-col justify-between">
            <div>
                <div class="w-10 h-10 rounded-xl bg-teal-50 text-teal-600 flex items-center justify-center mb-4">
                    <i class="fa-solid fa-graduation-cap text-md"></i>
                </div>
                <h3 class="font-outfit font-bold text-base text-slate-800 mb-2">NAPS Program</h3>
                <p class="text-slate-500 text-xs leading-relaxed">
                    Strategic consultation on the National Apprenticeship Promotion Scheme guidelines.
                </p>
            </div>
            <a href="{{ route('services') }}" class="text-xxs font-bold uppercase text-indigo-600 mt-4 inline-block hover:underline">Learn More <i class="fa-solid fa-arrow-right ml-1"></i></a>
        </div>
    </div>

    <div class="mt-12 text-center">
        <a href="{{ route('services') }}" class="inline-flex items-center px-6 py-3 bg-indigo-50 hover:bg-indigo-100 text-indigo-650 rounded-xl text-sm font-bold transition-all">
            Explore All Services <i class="fa-solid fa-arrow-right-long ml-2"></i>
        </a>
    </div>
</div>

<!-- Section 3: "See What We Do" Split Banner -->
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-20 border-b border-indigo-50">
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
        <!-- Visual Column -->
        <div class="relative">
            <div class="absolute -top-4 -left-4 w-72 h-72 rounded-full bg-blue-400/10 blur-3xl"></div>
            <img src="https://images.unsplash.com/photo-1586528116311-ad8dd3c8310d?auto=format&fit=crop&w=800&q=80"
                alt="RM HR operations"
                class="rounded-3xl border border-slate-200 shadow-lg object-cover w-full h-80 relative z-10">
        </div>

        <!-- Content Column -->
        <div class="space-y-6">
            <h2 class="font-outfit font-extrabold text-3xl text-slate-900">
                See What We Do...
            </h2>
            <p class="text-slate-600 text-sm leading-relaxed">
                Manpower is the primary engine of success for any economic or industrial activity. At RM HR Solutions, we help businesses scale efficiently by placing the right candidates in specialized statutory and flexi roles.
            </p>
            <p class="text-slate-650 text-xs leading-relaxed">
                Providing timely and trustworthy HR operations across India. We coordinate everything from initial candidate interviews to statutory compliance validation.
            </p>
            <a href="{{ route('services') }}" class="inline-flex items-center px-6 py-3 bg-indigo-650 hover:bg-indigo-600 text-white rounded-xl text-xs font-bold transition-all">
                Explore Our Services <i class="fa-solid fa-angles-right ml-1.5"></i>
            </a>
        </div>
    </div>
</div>

<!-- Section 4: Vision, Mission & Values Block -->
<div class="bg-slate-50 border-y border-indigo-50 py-20">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center max-w-2xl mx-auto mb-16">
            <h2 class="font-outfit font-extrabold text-3xl text-slate-900">
                One-Stop Solution For All Your HR Needs
            </h2>
            <div class="w-12 h-1 bg-indigo-600 mx-auto mt-4 rounded-full"></div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <!-- Vision -->
            <div class="bg-white p-8 rounded-2xl border border-slate-150 shadow-sm flex flex-col justify-between">
                <div>
                    <span class="text-[10px] font-extrabold text-indigo-600 uppercase tracking-widest block mb-2">Our Goal</span>
                    <h3 class="font-outfit font-bold text-xl text-slate-800 mb-4 flex items-center">
                        <i class="fa-solid fa-eye text-indigo-500 mr-2.5"></i> Our Vision
                    </h3>
                    <p class="text-slate-600 text-xs leading-relaxed">
                        To become India's most trusted recruitment consulting and manpower outsourcing agency, globally recognized for statutory transparency, ethics, and standard execution.
                    </p>
                </div>
                <a href="{{ route('about') }}" class="text-xxs font-bold text-indigo-600 hover:underline uppercase mt-6 inline-block">Meet the Team <i class="fa-solid fa-chevron-right ml-1"></i></a>
            </div>

            <!-- Mission -->
            <div class="bg-white p-8 rounded-2xl border border-slate-150 shadow-sm flex flex-col justify-between">
                <div>
                    <span class="text-[10px] font-extrabold text-blue-600 uppercase tracking-widest block mb-2">Our Action</span>
                    <h3 class="font-outfit font-bold text-xl text-slate-800 mb-4 flex items-center">
                        <i class="fa-solid fa-rocket text-blue-500 mr-2.5"></i> Our Mission
                    </h3>
                    <p class="text-slate-600 text-xs leading-relaxed">
                        To bridge the gap between job seekers and industry leaders through integrated, compliance-backed HR models that leverage advanced tech infrastructure.
                    </p>
                </div>
                <a href="{{ route('about') }}" class="text-xxs font-bold text-indigo-600 hover:underline uppercase mt-6 inline-block">Meet the Team <i class="fa-solid fa-chevron-right ml-1"></i></a>
            </div>

            <!-- Values -->
            <div class="bg-white p-8 rounded-2xl border border-slate-150 shadow-sm flex flex-col justify-between">
                <div>
                    <span class="text-[10px] font-extrabold text-emerald-600 uppercase tracking-widest block mb-2">Our Core</span>
                    <h3 class="font-outfit font-bold text-xl text-slate-800 mb-4 flex items-center">
                        <i class="fa-solid fa-heart text-emerald-500 mr-2.5"></i> Our Values
                    </h3>
                    <p class="text-slate-600 text-xs leading-relaxed">
                        Our entire client engagement model is built on four columns of code: Integrity, Mutual Respect, Passionate execution, and complete compliance Transparency.
                    </p>
                </div>
                <a href="{{ route('about') }}" class="text-xxs font-bold text-indigo-600 hover:underline uppercase mt-6 inline-block">Meet the Team <i class="fa-solid fa-chevron-right ml-1"></i></a>
            </div>
        </div>
    </div>
</div>

<!-- Section 5: Process Flow -->
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-20 border-b border-indigo-50">
    <div class="text-center max-w-2xl mx-auto mb-16">
        <h2 class="font-outfit font-extrabold text-3xl text-slate-900">
            Get Productive Instantly With 4 Simple Steps
        </h2>
        <div class="w-12 h-1 bg-indigo-600 mx-auto mt-4 rounded-full"></div>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-8 relative">
        <!-- Card 1 -->
        <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm relative space-y-4">
            <span class="absolute top-4 right-4 text-3xl font-black text-slate-100 font-outfit">01</span>
            <div class="w-10 h-10 rounded-xl bg-indigo-50 text-indigo-600 flex items-center justify-center">
                <i class="fa-solid fa-list-check"></i>
            </div>
            <h4 class="font-outfit font-bold text-base text-slate-800">Identify Requirements</h4>
            <p class="text-slate-500 text-xs leading-relaxed">
                Analyzing company headcount demands, salary limits, locations, and designations.
            </p>
        </div>

        <!-- Card 2 -->
        <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm relative space-y-4">
            <span class="absolute top-4 right-4 text-3xl font-black text-slate-100 font-outfit">02</span>
            <div class="w-10 h-10 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center">
                <i class="fa-solid fa-magnifying-glass"></i>
            </div>
            <h4 class="font-outfit font-bold text-base text-slate-800">Sourcing Talent Supply</h4>
            <p class="text-slate-500 text-xs leading-relaxed">
                Scanning multi-channel resume banks, local listings, and regional partner hubs.
            </p>
        </div>

        <!-- Card 3 -->
        <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm relative space-y-4">
            <span class="absolute top-4 right-4 text-3xl font-black text-slate-100 font-outfit">03</span>
            <div class="w-10 h-10 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center">
                <i class="fa-solid fa-comments"></i>
            </div>
            <h4 class="font-outfit font-bold text-base text-slate-800">Communication & Setup</h4>
            <p class="text-slate-500 text-xs leading-relaxed">
                Coordinating online tests, onboarding profiles, and statutory checks.
            </p>
        </div>

        <!-- Card 4 -->
        <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm relative space-y-4">
            <span class="absolute top-4 right-4 text-3xl font-black text-slate-100 font-outfit">04</span>
            <div class="w-10 h-10 rounded-xl bg-purple-50 text-purple-600 flex items-center justify-center">
                <i class="fa-solid fa-user-check"></i>
            </div>
            <h4 class="font-outfit font-bold text-base text-slate-800">Identify Suitable Talent</h4>
            <p class="text-slate-500 text-xs leading-relaxed">
                Selecting candidates, issuing joining letters, and dispatching team placements.
            </p>
        </div>
    </div>

    <div class="mt-12 text-center">
        <a href="{{ route('contact') }}" class="inline-flex items-center px-8 py-4 bg-indigo-650 hover:bg-indigo-600 text-white rounded-2xl text-sm font-bold transition-all shadow-md">
            Get Started Now <i class="fa-solid fa-arrow-right ml-2"></i>
        </a>
    </div>
</div>

<!-- Section 6: Why Choose RM HR Solutions -->
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-20 border-b border-indigo-50">
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
        <!-- Content Column -->
        <div class="space-y-6">
            <h2 class="font-outfit font-extrabold text-3xl text-slate-900">
                Why Choose RM HR Solutions
            </h2>
            <div class="w-16 h-1 bg-indigo-600 rounded-full"></div>
            <p class="text-slate-650 text-sm leading-relaxed">
                RM HR Solutions is committed to the organizational and financial empowerment of corporate operations. We build customized manpower deployments supported by 100% compliant statutory processes.
            </p>
            <div class="space-y-4">
                <div class="flex items-start">
                    <span class="w-6 h-6 rounded-full bg-indigo-50 text-indigo-600 flex items-center justify-center mr-3 mt-0.5 shrink-0"><i class="fa-solid fa-check text-xs"></i></span>
                    <p class="text-slate-700 text-xs leading-relaxed"><strong>Static & Flexi Models:</strong> Options to scale headcounts dynamically based on active orders.</p>
                </div>
                <div class="flex items-start">
                    <span class="w-6 h-6 rounded-full bg-indigo-50 text-indigo-600 flex items-center justify-center mr-3 mt-0.5 shrink-0"><i class="fa-solid fa-check text-xs"></i></span>
                    <p class="text-slate-700 text-xs leading-relaxed"><strong>statutory Security:</strong> Automatic handling of PF, ESIC, PTax calculations with 0.0% variance.</p>
                </div>
                <div class="flex items-start">
                    <span class="w-6 h-6 rounded-full bg-indigo-50 text-indigo-600 flex items-center justify-center mr-3 mt-0.5 shrink-0"><i class="fa-solid fa-check text-xs"></i></span>
                    <p class="text-slate-700 text-xs leading-relaxed"><strong>Transparent Portal:</strong> Real-time payslip downloads and self-service parameters for onboarding.</p>
                </div>
            </div>
        </div>

        <!-- Illustration Column -->
        <div class="relative">
            <div class="absolute -bottom-4 -right-4 w-72 h-72 rounded-full bg-indigo-400/10 blur-3xl"></div>
            <img src="https://images.unsplash.com/photo-1497366216548-37526070297c?auto=format&fit=crop&w=800&q=80"
                alt="Corporate Office"
                class="rounded-3xl border border-slate-200 shadow-lg object-cover w-full h-80 relative z-10">
        </div>
    </div>
</div>

<!-- Section 7: Read the Latest News -->
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-20 border-b border-indigo-50">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-12">
        <div>
            <h2 class="font-outfit font-extrabold text-3xl text-slate-900">
                Read the Latest From Our Experts
            </h2>
            <div class="w-16 h-1 bg-indigo-600 mt-3 rounded-full"></div>
        </div>
        <a href="{{ route('services') }}" class="text-xs font-bold text-indigo-600 hover:underline uppercase mt-4 sm:mt-0">
            View All Blogs <i class="fa-solid fa-chevron-right ml-1"></i>
        </a>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
        <!-- Blog 1 -->
        <div class="bg-white rounded-2xl border border-slate-200 overflow-hidden shadow-sm hover:shadow-md transition-all duration-300 flex flex-col justify-between">
            <div>
                <img src="https://images.unsplash.com/photo-1522071820081-009f0129c71c?auto=format&fit=crop&w=600&q=80" alt="Blog 1" class="w-full h-44 object-cover">
                <div class="p-6 space-y-2">
                    <span class="text-[10px] font-bold text-indigo-600 uppercase tracking-wider">Staffing Trends</span>
                    <h4 class="font-outfit font-bold text-base text-slate-800 leading-snug">
                        RM HR Solutions Pvt. Ltd. – Shaping The Future of Enterprises!
                    </h4>
                    <p class="text-slate-500 text-xxs">Published on: September 29, 2026</p>
                </div>
            </div>
            <div class="px-6 pb-6 pt-2">
                <a href="{{ route('services') }}" class="text-xxs font-bold text-indigo-600 hover:underline">Read Article <i class="fa-solid fa-arrow-right ml-1"></i></a>
            </div>
        </div>

        <!-- Blog 2 -->
        <div class="bg-white rounded-2xl border border-slate-200 overflow-hidden shadow-sm hover:shadow-md transition-all duration-300 flex flex-col justify-between">
            <div>
                <img src="https://images.unsplash.com/photo-1524178232363-1fb2b075b655?auto=format&fit=crop&w=600&q=80" alt="Blog 2" class="w-full h-44 object-cover">
                <div class="p-6 space-y-2">
                    <span class="text-[10px] font-bold text-indigo-600 uppercase tracking-wider">Compliance</span>
                    <h4 class="font-outfit font-bold text-base text-slate-800 leading-snug">
                        Changing the Dynamics of HR Compliance and Statutory Audits
                    </h4>
                    <p class="text-slate-500 text-xxs">Published on: March 8, 2026</p>
                </div>
            </div>
            <div class="px-6 pb-6 pt-2">
                <a href="{{ route('services') }}" class="text-xxs font-bold text-indigo-600 hover:underline">Read Article <i class="fa-solid fa-arrow-right ml-1"></i></a>
            </div>
        </div>

        <!-- Blog 3 -->
        <div class="bg-white rounded-2xl border border-slate-200 overflow-hidden shadow-sm hover:shadow-md transition-all duration-300 flex flex-col justify-between">
            <div>
                <img src="https://images.unsplash.com/photo-1573497019940-1c28c88b4f3e?auto=format&fit=crop&w=600&q=80" alt="Blog 3" class="w-full h-44 object-cover">
                <div class="p-6 space-y-2">
                    <span class="text-[10px] font-bold text-indigo-600 uppercase tracking-wider">Management</span>
                    <h4 class="font-outfit font-bold text-base text-slate-800 leading-snug">
                        Bhavna Udernani, Managing Director: Redefining Logistics Staffing
                    </h4>
                    <p class="text-slate-500 text-xxs">Published on: January 17, 2026</p>
                </div>
            </div>
            <div class="px-6 pb-6 pt-2">
                <a href="{{ route('services') }}" class="text-xxs font-bold text-indigo-600 hover:underline">Read Article <i class="fa-solid fa-arrow-right ml-1"></i></a>
            </div>
        </div>
    </div>
</div>

<!-- Section 8: Final CTA -->
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-20 text-center">
    <div class="bg-gradient-to-r from-indigo-600 to-blue-600 rounded-3xl p-8 sm:p-12 text-white shadow-xl relative overflow-hidden">
        <div class="absolute -top-12 -left-12 w-64 h-64 rounded-full bg-white/5 blur-2xl"></div>
        <div class="absolute -bottom-12 -right-12 w-64 h-64 rounded-full bg-white/5 blur-2xl"></div>

        <h2 class="font-outfit font-black text-3xl sm:text-4xl mb-4 relative z-10">
            Start Making Great Hires Today
        </h2>
        <p class="text-slate-100 max-w-xl mx-auto mb-8 text-sm leading-relaxed relative z-10">
            Align your organizational headcount with top statutory talent. Schedule a consulting session with our recruitment representatives.
        </p>
        <a href="{{ route('contact') }}" class="inline-flex items-center px-8 py-4 bg-white hover:bg-slate-100 text-indigo-600 rounded-2xl text-base font-bold shadow-lg hover:scale-[1.02] transition-all duration-300 relative z-10">
            Book a Consultation <i class="fa-solid fa-angle-right ml-2 text-indigo-600"></i>
        </a>
    </div>
</div>
@endsection
