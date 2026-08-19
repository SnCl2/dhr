@extends('layouts.app')

@section('title', 'Directors Desk - RM HR Solutions')

@section('content')
<!-- Hero Banner -->
<div class="relative bg-gradient-to-r from-slate-900 via-purple-950 to-slate-900 py-20 sm:py-28 overflow-hidden">
    <!-- Faded image overlay for that organic layout feel (reference screenshot uses a light group overlay, we use a subtle mesh of gradients for premium feel) -->
    <div class="absolute inset-0 opacity-10 bg-[radial-gradient(circle_at_top_right,_var(--tw-gradient-stops))] from-purple-500 via-indigo-500 to-transparent"></div>
    <div class="absolute top-0 right-0 w-96 h-96 bg-purple-500/10 rounded-full blur-3xl -mr-20 -mt-20"></div>
    <div class="absolute bottom-0 left-0 w-96 h-96 bg-indigo-500/10 rounded-full blur-3xl -ml-20 -mb-20"></div>
    
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10 text-center space-y-4">
        <h1 class="font-outfit font-black text-4xl sm:text-6xl text-white tracking-tight">
            Directors Desk
        </h1>
        <p class="mt-4 text-slate-300 text-sm sm:text-base max-w-xl mx-auto font-medium">
            Vision, leadership, and our commitment to connecting talent with opportunity.
        </p>
    </div>
</div>

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16 sm:py-24 relative">
    <!-- Floating background decorative elements from the reference screenshot -->
    <div class="hidden lg:block absolute right-16 top-24 w-4 h-4 rounded-full bg-cyan-300/40 blur-[1px]"></div>
    <div class="hidden lg:block absolute right-1/4 top-48 w-8 h-8 rounded-full bg-cyan-400/80"></div>
    <div class="hidden lg:block absolute right-8 top-72 w-56 h-56 rounded-full bg-cyan-100/50 -z-10"></div>
    
    <!-- Top Section (Main Dashboard Message) -->
    <div class="max-w-3xl mb-16 sm:mb-20 space-y-4">
        <span class="inline-block text-[11px] font-bold uppercase tracking-widest text-sky-600 bg-sky-50 px-3.5 py-1.5 rounded-full border border-sky-100">
            Our Main Dashboard
        </span>
        <h2 class="font-outfit font-black text-3xl sm:text-5xl text-slate-800 leading-tight">
            We help our clients find talented people to build their teams
        </h2>
        <p class="text-sm text-slate-600 leading-relaxed max-w-2xl font-medium">
            We help talented people find their dream jobs. But, just as important are those people we call our colleagues. Our leadership team has a broad range of experience but one common passion – helping candidates and organisations fulfil their unique potential.
        </p>
    </div>

    <!-- Main Grid Content -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 sm:gap-16 items-start">
        
        <!-- Left Column: Director Image & Quick Card -->
        <div class="lg:col-span-5 space-y-6 lg:sticky lg:top-28">
            <div class="relative group">
                <!-- Outer shadow/glow -->
                <div class="absolute inset-0 bg-gradient-to-tr from-sky-300 to-purple-400 rounded-3xl blur-lg opacity-25 group-hover:opacity-40 transition-opacity duration-300"></div>
                
                <!-- Image Wrapper -->
                <div class="relative bg-white p-3 rounded-3xl border border-slate-200 shadow-sm overflow-hidden">
                    <img src="{{ asset('images/director.png') }}" alt="Akib Ahmed Molla" class="w-full h-[400px] object-cover rounded-2xl grayscale-[20%] group-hover:grayscale-0 transition-all duration-500">
                </div>
            </div>
            
            <!-- Quick Profile info -->
            <div class="space-y-4 px-2">
                <div>
                    <span class="text-[10px] font-extrabold uppercase tracking-widest text-slate-400">Managing Director</span>
                    <h3 class="font-outfit font-extrabold text-2xl text-slate-800 mt-1">Akib Ahmed Molla</h3>
                </div>
                
                <hr class="border-slate-200">
                
                <!-- Contact info (Retrieved from CMS) -->
                <div class="space-y-3 text-xs text-slate-600">
                    @if(!empty($contactPhone))
                        <div class="flex items-center space-x-3">
                            <span class="w-8 h-8 rounded-lg bg-slate-100 flex items-center justify-center text-slate-500">
                                <i class="fa-solid fa-phone"></i>
                            </span>
                            <a href="tel:{{ $contactPhone }}" class="hover:text-purple-600 transition-colors font-medium">{{ $contactPhone }}</a>
                        </div>
                    @endif

                    @if(!empty($contactEmail))
                        <div class="flex items-center space-x-3">
                            <span class="w-8 h-8 rounded-lg bg-slate-100 flex items-center justify-center text-slate-500">
                                <i class="fa-solid fa-envelope"></i>
                            </span>
                            <a href="mailto:{{ $contactEmail }}" class="hover:text-purple-600 transition-colors font-medium">{{ $contactEmail }}</a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
        
        <!-- Right Column: Biography & Intro -->
        <div class="lg:col-span-7 space-y-8">
            <div class="space-y-4">
                <span class="inline-block text-[11px] font-bold uppercase tracking-widest text-purple-600 bg-purple-50 px-3.5 py-1.5 rounded-full border border-purple-100">
                    Introduction
                </span>
                <h3 class="font-outfit font-black text-2xl sm:text-3xl text-slate-800">
                    Akib Ahmed Molla
                </h3>
            </div>
            
            <div class="text-sm text-slate-650 leading-relaxed space-y-6 font-medium">
                <p>
                    <strong class="text-slate-800 font-extrabold">Akib Ahmed Molla</strong>, the Managing Director of RM HR Solutions, has had a remarkable journey as an entrepreneur dedicated to making a difference in Human Resource management. Despite humble beginnings, he always envisioned owning a business of his own.
                </p>
                
                <p>
                    His dream became a reality through <strong class="text-slate-800 font-bold">RM HR Solutions</strong>, a startup built on a solid foundation. His strong leadership and business acumen were crucial in safeguarding RM HR Solutions' stability and positioning it for continued success during global market transitions. His exceptional business development skills and ethics supported RM HR Solutions in collaborating with some of the most prestigious clients and MNCs.
                </p>
                
                <p>
                    RM HR Solutions has undergone an incredible transformation, developing and diversifying itself to keep pace with the ever-changing market. What once began as a small team has now grown into a strong workforce of employees spread across multiple regions. Akib acknowledged the importance of digital innovations, so new technologies, advanced software, and data management systems were incorporated. His unwavering commitment to aligning with the latest ideas and business strategies has secured immense fame and repute for the company.
                </p>
                
                <div class="p-6 rounded-2xl bg-gradient-to-r from-sky-50 to-indigo-50 border border-sky-100 my-8">
                    <p class="text-slate-800 font-bold italic leading-relaxed text-sm">
                        "Akib strongly believes in equal opportunity and inclusivity. He is passionate about empowering youth, creating sustainable career pathways, and building diverse work environments."
                    </p>
                </div>
                
                <p>
                    With RM HR Solutions, Akib has created a platform that believes in sharing opportunities and growing together by making the workplace accepted and valued. The diversity-led solutions have led to doubling the productivity and profitability of the organization as a whole.
                </p>
                
                <p>
                    His remarkable personal and professional contributions have garnered widespread recognition in human resources and staffing. Yet he remains grounded and focused on his impactful purpose of directing and mentoring. Akib's ability to navigate complex recruitment landscapes makes him a trusted leader and visionary.
                </p>
            </div>
        </div>

    </div>
</div>
@endsection
