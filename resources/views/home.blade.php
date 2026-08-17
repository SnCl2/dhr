@extends('layouts.app')

@section('title', 'RM HR Solutions - Premium Staffing & HR Consulting Partner')

@section('content')
<!-- Include Alpine.js via CDN for interactive components -->
<script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>

<!-- Section 1: Hero Slider Section -->
<div class="relative overflow-hidden bg-slate-900 text-white" x-data="{ activeSlide: 1, timer: null }" x-init="timer = setInterval(() => { activeSlide = activeSlide === 6 ? 1 : activeSlide + 1 }, 5000)" @mouseenter="clearInterval(timer)" @mouseleave="timer = setInterval(() => { activeSlide = activeSlide === 6 ? 1 : activeSlide + 1 }, 5000)">
    <!-- Slide 1 -->
    <div x-show="activeSlide === 1" x-transition.opacity.duration.800ms class="relative min-h-[500px] sm:min-h-[600px] flex items-center bg-cover bg-center" style="background-image: linear-gradient(rgba(0, 0, 0, 0.6), rgba(0, 0, 0, 0.6)), url('{{ asset('images/slider_1.png') }}');">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 w-full relative z-10 py-20">
            <div class="max-w-3xl space-y-6">
                <span class="inline-flex items-center px-3.5 py-1.5 rounded-full text-xs font-bold bg-cyan-500/20 text-cyan-300 border border-cyan-500/30 uppercase tracking-widest">
                    Welcome to RM HR Solutions
                </span>
                <h1 class="font-outfit font-black text-4xl sm:text-5xl lg:text-6xl tracking-tight text-white leading-tight">
                    Connecting Companies <br><span class="text-cyan-400">To the Right Work Force</span>
                </h1>
                <p class="text-base sm:text-lg text-slate-300 leading-relaxed font-sans max-w-xl">
                    We match multinationals and leading business houses with the best-suited workforce in India.
                </p>
                <div class="pt-4">
                    <a href="{{ route('about') }}" class="inline-flex items-center px-8 py-4 bg-cyan-500 hover:bg-cyan-400 text-slate-900 rounded-full text-sm font-black uppercase tracking-wider transition-all duration-300 transform hover:scale-105 shadow-lg shadow-cyan-500/20">
                        Learn More <i class="fa-solid fa-arrow-right-long ml-2"></i>
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Slide 2 -->
    <div x-show="activeSlide === 2" x-transition.opacity.duration.800ms class="relative min-h-[500px] sm:min-h-[600px] flex items-center bg-cover bg-center" style="background-image: linear-gradient(rgba(0, 0, 0, 0.6), rgba(0, 0, 0, 0.6)), url('{{ asset('images/slider_2.png') }}');">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 w-full relative z-10 py-20">
            <div class="max-w-3xl space-y-6">
                <span class="inline-flex items-center px-3.5 py-1.5 rounded-full text-xs font-bold bg-cyan-500/20 text-cyan-300 border border-cyan-500/30 uppercase tracking-widest">
                    Your HR Partner
                </span>
                <h1 class="font-outfit font-black text-4xl sm:text-5xl lg:text-6xl tracking-tight text-white leading-tight">
                    Your Staffing <br><span class="text-cyan-400">Solution Partner</span>
                </h1>
                <p class="text-base sm:text-lg text-slate-300 leading-relaxed font-sans max-w-xl">
                    We take away the HR headaches so you can focus on serving clients and growing your business.
                </p>
                <div class="pt-4">
                    <a href="{{ route('about') }}" class="inline-flex items-center px-8 py-4 bg-cyan-500 hover:bg-cyan-400 text-slate-900 rounded-full text-sm font-black uppercase tracking-wider transition-all duration-300 transform hover:scale-105 shadow-lg shadow-cyan-500/20">
                        Learn More <i class="fa-solid fa-arrow-right-long ml-2"></i>
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Slide 3 -->
    <div x-show="activeSlide === 3" x-transition.opacity.duration.800ms class="relative min-h-[500px] sm:min-h-[600px] flex items-center bg-cover bg-center" style="background-image: linear-gradient(rgba(0, 0, 0, 0.6), rgba(0, 0, 0, 0.6)), url('{{ asset('images/slider_3.png') }}');">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 w-full relative z-10 py-20">
            <div class="max-w-3xl space-y-6">
                <span class="inline-flex items-center px-3.5 py-1.5 rounded-full text-xs font-bold bg-cyan-500/20 text-cyan-300 border border-cyan-500/30 uppercase tracking-widest">
                    Build Your Career
                </span>
                <h1 class="font-outfit font-black text-4xl sm:text-5xl lg:text-6xl tracking-tight text-white leading-tight">
                    Find Your Next <br><span class="text-cyan-400">Perfect Place to Work</span>
                </h1>
                <p class="text-base sm:text-lg text-slate-300 leading-relaxed font-sans max-w-xl">
                    Building and Sustaining High Performance Culture. Register and view joining documents instantly.
                </p>
                <div class="pt-4">
                    <a href="{{ route('about') }}" class="inline-flex items-center px-8 py-4 bg-cyan-500 hover:bg-cyan-400 text-slate-900 rounded-full text-sm font-black uppercase tracking-wider transition-all duration-300 transform hover:scale-105 shadow-lg shadow-cyan-500/20">
                        Learn More <i class="fa-solid fa-arrow-right-long ml-2"></i>
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Slide 4 -->
    <div x-show="activeSlide === 4" x-transition.opacity.duration.800ms class="relative min-h-[500px] sm:min-h-[600px] flex items-center bg-cover bg-center" style="background-image: linear-gradient(rgba(0, 0, 0, 0.6), rgba(0, 0, 0, 0.6)), url('{{ asset('images/slider_4.png') }}');">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 w-full relative z-10 py-20">
            <div class="max-w-3xl space-y-6">
                <span class="inline-flex items-center px-3.5 py-1.5 rounded-full text-xs font-bold bg-cyan-500/20 text-cyan-300 border border-cyan-500/30 uppercase tracking-widest">
                    Statutory Integrity
                </span>
                <h1 class="font-outfit font-black text-4xl sm:text-5xl lg:text-6xl tracking-tight text-white leading-tight">
                    Statutory Compliance <br><span class="text-cyan-400">And HR Outsourcing</span>
                </h1>
                <p class="text-base sm:text-lg text-slate-300 leading-relaxed font-sans max-w-xl">
                    100% compliant calculations of PF, ESIC, and Professional Tax with absolute transparency.
                </p>
                <div class="pt-4">
                    <a href="{{ route('about') }}" class="inline-flex items-center px-8 py-4 bg-cyan-500 hover:bg-cyan-400 text-slate-900 rounded-full text-sm font-black uppercase tracking-wider transition-all duration-300 transform hover:scale-105 shadow-lg shadow-cyan-500/20">
                        Learn More <i class="fa-solid fa-arrow-right-long ml-2"></i>
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Slide 5 -->
    <div x-show="activeSlide === 5" x-transition.opacity.duration.800ms class="relative min-h-[500px] sm:min-h-[600px] flex items-center bg-cover bg-center" style="background-image: linear-gradient(rgba(0, 0, 0, 0.6), rgba(0, 0, 0, 0.6)), url('{{ asset('images/slider_5.png') }}');">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 w-full relative z-10 py-20">
            <div class="max-w-3xl space-y-6">
                <span class="inline-flex items-center px-3.5 py-1.5 rounded-full text-xs font-bold bg-cyan-500/20 text-cyan-300 border border-cyan-500/30 uppercase tracking-widest">
                    Flexi Manpower
                </span>
                <h1 class="font-outfit font-black text-4xl sm:text-5xl lg:text-6xl tracking-tight text-white leading-tight">
                    Contractual & Flexi <br><span class="text-cyan-400">Personnel Deployments</span>
                </h1>
                <p class="text-base sm:text-lg text-slate-300 leading-relaxed font-sans max-w-xl">
                    Scale your operating teams dynamically based on seasonal demands with flexible contractual staff.
                </p>
                <div class="pt-4">
                    <a href="{{ route('about') }}" class="inline-flex items-center px-8 py-4 bg-cyan-500 hover:bg-cyan-400 text-slate-900 rounded-full text-sm font-black uppercase tracking-wider transition-all duration-300 transform hover:scale-105 shadow-lg shadow-cyan-500/20">
                        Learn More <i class="fa-solid fa-arrow-right-long ml-2"></i>
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Slide 6 -->
    <div x-show="activeSlide === 6" x-transition.opacity.duration.800ms class="relative min-h-[500px] sm:min-h-[600px] flex items-center bg-cover bg-center" style="background-image: linear-gradient(rgba(0, 0, 0, 0.6), rgba(0, 0, 0, 0.6)), url('{{ asset('images/slider_6.png') }}');">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 w-full relative z-10 py-20">
            <div class="max-w-3xl space-y-6">
                <span class="inline-flex items-center px-3.5 py-1.5 rounded-full text-xs font-bold bg-cyan-500/20 text-cyan-300 border border-cyan-500/30 uppercase tracking-widest">
                    HR Advisory
                </span>
                <h1 class="font-outfit font-black text-4xl sm:text-5xl lg:text-6xl tracking-tight text-white leading-tight">
                    Tailored Multi-Sector <br><span class="text-cyan-400">HR Advisory Consulting</span>
                </h1>
                <p class="text-base sm:text-lg text-slate-300 leading-relaxed font-sans max-w-xl">
                    Strategic consultation on policy design, job description mapping, and operational recruitment audits.
                </p>
                <div class="pt-4">
                    <a href="{{ route('about') }}" class="inline-flex items-center px-8 py-4 bg-cyan-500 hover:bg-cyan-400 text-slate-900 rounded-full text-sm font-black uppercase tracking-wider transition-all duration-300 transform hover:scale-105 shadow-lg shadow-cyan-500/20">
                        Learn More <i class="fa-solid fa-arrow-right-long ml-2"></i>
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Slider Dots -->
    <div class="absolute bottom-6 left-0 right-0 flex justify-center space-x-3 z-20">
        <button @click="activeSlide = 1" :class="activeSlide === 1 ? 'bg-cyan-400 w-8' : 'bg-white/50 w-2.5'" class="h-2.5 rounded-full transition-all duration-300"></button>
        <button @click="activeSlide = 2" :class="activeSlide === 2 ? 'bg-cyan-400 w-8' : 'bg-white/50 w-2.5'" class="h-2.5 rounded-full transition-all duration-300"></button>
        <button @click="activeSlide = 3" :class="activeSlide === 3 ? 'bg-cyan-400 w-8' : 'bg-white/50 w-2.5'" class="h-2.5 rounded-full transition-all duration-300"></button>
        <button @click="activeSlide = 4" :class="activeSlide === 4 ? 'bg-cyan-400 w-8' : 'bg-white/50 w-2.5'" class="h-2.5 rounded-full transition-all duration-300"></button>
        <button @click="activeSlide = 5" :class="activeSlide === 5 ? 'bg-cyan-400 w-8' : 'bg-white/50 w-2.5'" class="h-2.5 rounded-full transition-all duration-300"></button>
        <button @click="activeSlide = 6" :class="activeSlide === 6 ? 'bg-cyan-400 w-8' : 'bg-white/50 w-2.5'" class="h-2.5 rounded-full transition-all duration-300"></button>
    </div>
</div>

<!-- Section 2: Group Companies & Brand Badges -->
<div class="bg-white py-6 border-b border-indigo-50 shadow-sm relative z-20">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex flex-col sm:flex-row items-center justify-center gap-6 sm:gap-12">
            <span class="text-xxs font-bold text-slate-400 uppercase tracking-widest">Partner Group Brands:</span>
            <div class="flex items-center gap-8">
                <a href="https://swagatham.co.in/" target="_blank" class="opacity-60 hover:opacity-100 transition-all duration-300 transform hover:scale-105">
                    <span class="font-outfit font-extrabold text-slate-800 text-sm sm:text-base tracking-wider"><i class="fa-solid fa-hotel mr-2 text-indigo-500"></i>SWAGATHAM</span>
                </a>
                <a href="https://flickjobs.in/" target="_blank" class="opacity-60 hover:opacity-100 transition-all duration-300 transform hover:scale-105">
                    <span class="font-outfit font-extrabold text-slate-800 text-sm sm:text-base tracking-wider"><i class="fa-solid fa-briefcase mr-2 text-indigo-500"></i>FLICKJOBS</span>
                </a>
            </div>
        </div>
    </div>
</div>

<!-- Section 3: About Section ("ABOUT RM HR SOLUTIONS") -->
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-20 border-b border-slate-100">
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 items-center">
        <!-- Content Column -->
        <div class="lg:col-span-7 space-y-6">
            <span class="inline-flex items-center px-3 py-1 rounded-full text-[10px] font-bold bg-indigo-50 text-indigo-600 uppercase tracking-wider">
                About RM HR Solutions
            </span>
            <h2 class="font-outfit font-black text-3xl sm:text-4xl text-slate-850 leading-tight">
                Empowering Business Through Integrated <br>And Tailored Staffing Solutions!
            </h2>
            <div class="space-y-4 text-slate-600 text-sm leading-relaxed">
                <p>
                    Established in 2010, RM HR Solutions is a pioneer in multi-industry HR consulting. With over a decade of experience, we have earned the trust of multinationals and leading business houses as their preferred talent acquisition and consulting partner in India.
                </p>
                <p>
                    We are dedicated to the empowerment of businesses through our commitment to cater to customized staffing and HR solutions backed by the latest technology and a passionate team. We take pride in upholding our core values of integrity, respect, passion, and transparency.
                </p>
            </div>
            <div class="pt-2">
                <a href="{{ route('about') }}" class="inline-flex items-center px-6 py-3.5 bg-slate-900 hover:bg-slate-800 text-white rounded-full text-xs font-bold uppercase tracking-wider transition-all duration-300">
                    Get to Know Us <i class="fa-solid fa-arrow-right-long ml-2"></i>
                </a>
            </div>
        </div>

        <!-- Image Column -->
        <div class="lg:col-span-5 relative">
            <div class="absolute -top-4 -left-4 w-72 h-72 rounded-full bg-indigo-400/5 blur-3xl"></div>
            <img src="https://images.unsplash.com/photo-1524178232363-1fb2b075b655?auto=format&fit=crop&w=700&q=80"
                alt="RM HR operations"
                class="rounded-3xl border border-slate-200 shadow-md object-cover w-full h-80 relative z-10">
        </div>
    </div>
</div>

<!-- Section 4: Services Carousel Section ("Services We Provide" & "See What we do ...") -->
<div class="bg-slate-50 border-b border-slate-100 py-20">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-12">
            <!-- Left: Carousel of 8 Services (7 cols) -->
            <div class="lg:col-span-7 bg-indigo-900 rounded-3xl p-8 text-white relative overflow-hidden shadow-xl" x-data="{ serviceIndex: 0 }">
                <div class="absolute -right-16 -top-16 w-48 h-48 rounded-full bg-white/5 blur-2xl"></div>
                
                <h3 class="font-outfit font-black text-2xl mb-6 flex items-center">
                    <i class="fa-solid fa-circle-notch text-cyan-400 mr-3 animate-spin-slow"></i> Services We Provide
                </h3>

                <!-- Service Slider -->
                <div class="relative h-64 flex items-center">
                    <!-- Service 1 -->
                    <div x-show="serviceIndex === 0" x-transition class="space-y-4">
                        <span class="text-xs font-bold text-cyan-400 uppercase tracking-widest">01 / 08</span>
                        <h4 class="font-outfit font-bold text-xl">Permanent Staffing</h4>
                        <p class="text-slate-300 text-sm leading-relaxed max-w-lg">
                            Acquire high-caliber professionals custom-profiled for your permanent organizational requirements to support long-term stability and business growth.
                        </p>
                    </div>
                    <!-- Service 2 -->
                    <div x-show="serviceIndex === 1" x-transition class="space-y-4">
                        <span class="text-xs font-bold text-cyan-400 uppercase tracking-widest">02 / 08</span>
                        <h4 class="font-outfit font-bold text-xl">Contractual Staffing</h4>
                        <p class="text-slate-300 text-sm leading-relaxed max-w-lg">
                            Scale your operating teams dynamically based on seasonal demands with flexible contractual personnel deployments.
                        </p>
                    </div>
                    <!-- Service 3 -->
                    <div x-show="serviceIndex === 2" x-transition class="space-y-4">
                        <span class="text-xs font-bold text-cyan-400 uppercase tracking-widest">03 / 08</span>
                        <h4 class="font-outfit font-bold text-xl">Payroll Management</h4>
                        <p class="text-slate-300 text-sm leading-relaxed max-w-lg">
                            Streamline monthly calculations, payouts, tax deductions, and detailed Payslip Statements with zero operational compliance errors.
                        </p>
                    </div>
                    <!-- Service 4 -->
                    <div x-show="serviceIndex === 3" x-transition class="space-y-4">
                        <span class="text-xs font-bold text-cyan-400 uppercase tracking-widest">04 / 08</span>
                        <h4 class="font-outfit font-bold text-xl">Compliance Outsourcing</h4>
                        <p class="text-slate-300 text-sm leading-relaxed max-w-lg">
                            Manage statutory compliance audits, including Provident Fund (PF), ESIC, and regional labor code registries seamlessly.
                        </p>
                    </div>
                    <!-- Service 5 -->
                    <div x-show="serviceIndex === 4" x-transition class="space-y-4">
                        <span class="text-xs font-bold text-cyan-400 uppercase tracking-widest">05 / 08</span>
                        <h4 class="font-outfit font-bold text-xl">Migrant Labour Management</h4>
                        <p class="text-slate-300 text-sm leading-relaxed max-w-lg">
                            Onboard, transport, and house regional workers safely with structured operational support and transparent compliance records.
                        </p>
                    </div>
                    <!-- Service 6 -->
                    <div x-show="serviceIndex === 5" x-transition class="space-y-4">
                        <span class="text-xs font-bold text-cyan-400 uppercase tracking-widest">06 / 08</span>
                        <h4 class="font-outfit font-bold text-xl">Expat Hiring</h4>
                        <p class="text-slate-300 text-sm leading-relaxed max-w-lg">
                            Identify and place premium cross-border executive candidates for senior corporate management and board-level responsibilities.
                        </p>
                    </div>
                    <!-- Service 7 -->
                    <div x-show="serviceIndex === 7" x-transition class="space-y-4">
                        <span class="text-xs font-bold text-cyan-400 uppercase tracking-widest">07 / 08</span>
                        <h4 class="font-outfit font-bold text-xl">HR Consulting</h4>
                        <p class="text-slate-300 text-sm leading-relaxed max-w-lg">
                            Receive strategic organizational advisory, job description metrics, policy design, and operational audits.
                        </p>
                    </div>
                    <!-- Service 8 -->
                    <div x-show="serviceIndex === 6" x-transition class="space-y-4">
                        <span class="text-xs font-bold text-cyan-400 uppercase tracking-widest">08 / 08</span>
                        <h4 class="font-outfit font-bold text-xl">NAPS Promotion</h4>
                        <p class="text-slate-300 text-sm leading-relaxed max-w-lg">
                            Implement statutory training workflows under the National Apprenticeship Promotion Scheme coordinates.
                        </p>
                    </div>
                </div>

                <!-- Carousel Controls -->
                <div class="mt-6 flex items-center justify-between">
                    <div class="flex space-x-2">
                        <template x-for="i in 8">
                            <button @click="serviceIndex = i - 1" :class="serviceIndex === i - 1 ? 'bg-cyan-400 w-6' : 'bg-white/30 w-2'" class="h-2 rounded-full transition-all duration-300"></button>
                        </template>
                    </div>
                    <div class="flex space-x-4">
                        <button @click="serviceIndex = serviceIndex === 0 ? 7 : serviceIndex - 1" class="w-8 h-8 rounded-full bg-white/10 hover:bg-white/20 flex items-center justify-center text-white transition-all">
                            <i class="fa-solid fa-arrow-left text-sm"></i>
                        </button>
                        <button @click="serviceIndex = serviceIndex === 7 ? 0 : serviceIndex + 1" class="w-8 h-8 rounded-full bg-white/10 hover:bg-white/20 flex items-center justify-center text-white transition-all">
                            <i class="fa-solid fa-arrow-right text-sm"></i>
                        </button>
                    </div>
                </div>
            </div>

            <!-- Right: "See What we do ..." Content (5 cols) -->
            <div class="lg:col-span-5 flex flex-col justify-center space-y-6">
                <h3 class="font-outfit font-black text-2xl text-slate-850">See What we do ...</h3>
                <p class="text-slate-600 text-sm leading-relaxed">
                    Manpower is the key to success for any economic or production activity. At RM HR Solutions we help businesses grow by recruiting the right people for their specialized and customized staffing and HR needs. 
                </p>
                <p class="text-slate-500 text-xs leading-relaxed">
                    Providing a timely and trustworthy solution for all staffing needs since 2010. We cater to the requirements of both job seekers and employers through a wide network of offices across India.
                </p>
                <div class="pt-2">
                    <a href="{{ route('services') }}" class="inline-flex items-center px-6 py-3.5 bg-slate-900 hover:bg-slate-800 text-white rounded-full text-xs font-bold uppercase tracking-wider transition-all">
                        Explore Our Services <i class="fa-solid fa-arrow-right-long ml-2"></i>
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Section 5: Metrics Section -->
<div class="bg-white py-16 border-b border-slate-100">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-2 md:grid-cols-5 gap-8 text-center">
            <div>
                <span class="block font-outfit font-black text-4xl text-cyan-500">170+</span>
                <span class="block text-slate-700 text-xs font-bold uppercase tracking-wider mt-2">Core Team Professionals</span>
            </div>
            <div>
                <span class="block font-outfit font-black text-4xl text-cyan-500">250+</span>
                <span class="block text-slate-700 text-xs font-bold uppercase tracking-wider mt-2">Valued Clients</span>
            </div>
            <div>
                <span class="block font-outfit font-black text-4xl text-cyan-500">12+</span>
                <span class="block text-slate-700 text-xs font-bold uppercase tracking-wider mt-2">Branch Offices</span>
            </div>
            <div>
                <span class="block font-outfit font-black text-4xl text-cyan-500">25+</span>
                <span class="block text-slate-700 text-xs font-bold uppercase tracking-wider mt-2">Presence in States</span>
            </div>
            <div class="col-span-2 md:col-span-1">
                <span class="block font-outfit font-black text-4xl text-cyan-500">350K+</span>
                <span class="block text-slate-700 text-xs font-bold uppercase tracking-wider mt-2">Placements Executed</span>
            </div>
        </div>
    </div>
</div>

<!-- Section 6: Tabbed "Discover RM HR" Section -->
<div class="bg-indigo-900 text-white py-20" x-data="{ tab: 'vision' }">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-12">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 items-center">
            <!-- Left: Tab details (7 cols) -->
            <div class="lg:col-span-7 space-y-6">
                <span class="inline-flex items-center px-3 py-1.5 rounded-full text-xs font-bold bg-white/10 text-cyan-300 border border-white/15 uppercase tracking-widest">
                    Discover RM HR Solutions
                </span>
                <h2 class="font-outfit font-black text-3xl sm:text-4xl">One stop solution for all your HR needs</h2>

                <!-- Tabs navigation -->
                <div class="flex space-x-6 border-b border-white/10 pb-2">
                    <button @click="tab = 'vision'" :class="tab === 'vision' ? 'border-b-2 border-cyan-400 text-cyan-300 font-bold' : 'text-slate-400 hover:text-white'" class="pb-2 text-sm uppercase tracking-wider transition-all">Our Vision</button>
                    <button @click="tab = 'mission'" :class="tab === 'mission' ? 'border-b-2 border-cyan-400 text-cyan-300 font-bold' : 'text-slate-400 hover:text-white'" class="pb-2 text-sm uppercase tracking-wider transition-all">Our Mission</button>
                    <button @click="tab = 'values'" :class="tab === 'values' ? 'border-b-2 border-cyan-400 text-cyan-300 font-bold' : 'text-slate-400 hover:text-white'" class="pb-2 text-sm uppercase tracking-wider transition-all">Our Values</button>
                </div>

                <!-- Tab Panels -->
                <div class="min-h-[120px] flex items-center">
                    <div x-show="tab === 'vision'" x-transition class="space-y-4">
                        <p class="text-slate-300 text-sm leading-relaxed">
                            RM HR Solutions aims to become the most trusted consulting services firm renowned for ethics and excellence. We aim to provide comprehensive services through innovative and integrated HR solutions, adhering to the latest industry standards across our diverse project portfolio.
                        </p>
                    </div>
                    <div x-show="tab === 'mission'" x-transition class="space-y-4">
                        <p class="text-slate-300 text-sm leading-relaxed">
                            Our mission is to establish sustainable partnerships with businesses, leverage advanced HR technologies, and dispatch fully compliant payroll structures to enhance client profitability and candidates career safety.
                        </p>
                    </div>
                    <div x-show="tab === 'values'" x-transition class="space-y-4">
                        <p class="text-slate-300 text-sm leading-relaxed">
                            Core values of integrity, respect, passion, and transparency form the base of every recruitment audit, compliance filing, and employee transaction we execute.
                        </p>
                    </div>
                </div>

                <div class="pt-4">
                    <a href="{{ route('about') }}" class="inline-flex items-center px-6 py-3.5 bg-cyan-500 hover:bg-cyan-400 text-slate-900 rounded-full text-xs font-bold uppercase tracking-wider transition-all">
                        Meet the Team <i class="fa-solid fa-arrow-right-long ml-2"></i>
                    </a>
                </div>
            </div>

            <!-- Right: Image (5 cols) -->
            <div class="lg:col-span-5 relative">
                <div class="absolute -bottom-6 -right-6 w-48 h-48 rounded-full bg-white/5 blur-3xl"></div>
                <img src="https://images.unsplash.com/photo-1573497019940-1c28c88b4f3e?auto=format&fit=crop&w=700&q=80"
                    alt="Corporate Meeting"
                    class="rounded-3xl border border-white/10 shadow-lg object-cover w-full h-80 relative z-10">
            </div>
        </div>

        <!-- 3 Feature Cards underneath -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 pt-10 border-t border-white/10">
            <div class="bg-white p-6 rounded-2xl border border-slate-100 shadow-sm text-slate-800 space-y-3">
                <span class="text-xl font-black text-cyan-500 font-outfit">01</span>
                <h4 class="font-outfit font-bold text-base">Functional Expertise</h4>
                <p class="text-slate-500 text-xs leading-relaxed">
                    Our seasoned professionals bring unparalleled industry expertise to tackle your unique recruitment and statutory payroll challenges.
                </p>
            </div>
            <div class="bg-white p-6 rounded-2xl border border-slate-100 shadow-sm text-slate-800 space-y-3">
                <span class="text-xl font-black text-cyan-500 font-outfit">02</span>
                <h4 class="font-outfit font-bold text-base">Integrity & Innovations</h4>
                <p class="text-slate-500 text-xs leading-relaxed">
                    We blend unwavering statutory integrity with innovative cloud-based employee databases to drive your success.
                </p>
            </div>
            <div class="bg-white p-6 rounded-2xl border border-slate-100 shadow-sm text-slate-800 space-y-3">
                <span class="text-xl font-black text-cyan-500 font-outfit">03</span>
                <h4 class="font-outfit font-bold text-base">Performance Driven</h4>
                <p class="text-slate-500 text-xs leading-relaxed">
                    We are committed to delivering results that exceed client expectations, ensuring 100% compliant audits and payouts.
                </p>
            </div>
        </div>
    </div>
</div>

<!-- Section 7: Process Section ("OUR PROCESS") -->
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-20 border-b border-slate-100" x-data="{}">
    <div class="text-center max-w-2xl mx-auto mb-16 space-y-3">
        <span class="inline-flex items-center px-3 py-1.5 rounded-full text-[10px] font-bold bg-indigo-50 text-indigo-600 uppercase tracking-widest">
            Our Process
        </span>
        <h2 class="font-outfit font-black text-3xl text-slate-850">
            Get productive instantly with a few simple steps
        </h2>
        <p class="text-slate-500 text-xs">
            We can help you with picking out the best people for your company.
        </p>
    </div>

    <!-- Steps Flow -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-8 relative items-start">
        <!-- Card 1 -->
        <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm relative space-y-4 text-center">
            <div class="absolute -top-3 left-1/2 -translate-x-1/2 w-6 h-6 rounded-full bg-cyan-400 text-slate-900 text-xxs font-black flex items-center justify-center font-outfit">01</div>
            <div class="w-12 h-12 rounded-2xl bg-indigo-50 text-indigo-600 flex items-center justify-center mx-auto">
                <i class="fa-solid fa-list-check text-lg"></i>
            </div>
            <h4 class="font-outfit font-bold text-sm text-slate-850">Identify Requirements</h4>
            <p class="text-slate-500 text-xxs leading-relaxed">
                Analyzing specific company headcount parameters, salary slabs, and designation profiles.
            </p>
        </div>

        <!-- Card 2 -->
        <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm relative space-y-4 text-center">
            <div class="absolute -top-3 left-1/2 -translate-x-1/2 w-6 h-6 rounded-full bg-cyan-400 text-slate-900 text-xxs font-black flex items-center justify-center font-outfit">02</div>
            <div class="w-12 h-12 rounded-2xl bg-blue-50 text-blue-600 flex items-center justify-center mx-auto">
                <i class="fa-solid fa-network-wired text-lg"></i>
            </div>
            <h4 class="font-outfit font-bold text-sm text-slate-850">Identify Source of Supply</h4>
            <p class="text-slate-500 text-xxs leading-relaxed">
                Scanning regional partner channels, active candidate pipelines, and digital job databases.
            </p>
        </div>

        <!-- Card 3 -->
        <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm relative space-y-4 text-center">
            <div class="absolute -top-3 left-1/2 -translate-x-1/2 w-6 h-6 rounded-full bg-cyan-400 text-slate-900 text-xxs font-black flex items-center justify-center font-outfit">03</div>
            <div class="w-12 h-12 rounded-2xl bg-emerald-50 text-emerald-600 flex items-center justify-center mx-auto">
                <i class="fa-solid fa-comments text-lg"></i>
            </div>
            <h4 class="font-outfit font-bold text-sm text-slate-850">Communicating Information</h4>
            <p class="text-slate-500 text-xxs leading-relaxed">
                Dispatching job offer details, performing background screening checks, and mapping values.
            </p>
        </div>

        <!-- Card 4 -->
        <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm relative space-y-4 text-center">
            <div class="absolute -top-3 left-1/2 -translate-x-1/2 w-6 h-6 rounded-full bg-cyan-400 text-slate-900 text-xxs font-black flex items-center justify-center font-outfit">04</div>
            <div class="w-12 h-12 rounded-2xl bg-purple-50 text-purple-600 flex items-center justify-center mx-auto">
                <i class="fa-solid fa-user-check text-lg"></i>
            </div>
            <h4 class="font-outfit font-bold text-sm text-slate-850">Identify Suitable Talent</h4>
            <p class="text-slate-500 text-xxs leading-relaxed">
                Matching best-suited profiles, final validation, issuing offer letters, and team deployment.
            </p>
        </div>
    </div>

    <div class="mt-12 text-center">
        <a href="{{ route('contact') }}" class="inline-flex items-center px-8 py-4 bg-slate-900 hover:bg-slate-800 text-white rounded-full text-xs font-bold uppercase tracking-wider transition-all duration-300">
            Get Started Now <i class="fa-solid fa-arrow-right-long ml-2"></i>
        </a>
    </div>
</div>

<!-- Section 8: Customer Reviews Section -->
<div class="bg-slate-50 border-b border-slate-100 py-20" x-data="{ reviewActive: 0 }">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 items-center">
            <!-- Left: Image (5 cols) -->
            <div class="lg:col-span-5 relative">
                <img src="https://images.unsplash.com/photo-1497366216548-37526070297c?auto=format&fit=crop&w=700&q=80"
                    alt="Review Desk"
                    class="rounded-3xl border border-slate-200 shadow-md object-cover w-full h-80 relative z-10">
            </div>

            <!-- Right: Review Block (7 cols) -->
            <div class="lg:col-span-7 bg-cyan-500 p-8 sm:p-10 rounded-3xl text-slate-900 relative overflow-hidden shadow-lg space-y-6">
                <span class="inline-flex items-center px-3 py-1 rounded-full text-[10px] font-bold bg-slate-900 text-white uppercase tracking-widest">
                    Customer Reviews
                </span>

                <div class="relative h-44 flex items-center">
                    <div x-show="reviewActive === 0" x-transition class="space-y-4">
                        <i class="fa-solid fa-quote-left text-3xl text-slate-900/20 block"></i>
                        <p class="text-sm font-medium leading-relaxed italic">
                            "Aparoksha Financial Services (Loan2wheels) has been associated with RM HR Solutions. We found their recruitment team exceptionally professional and compliance audits standard."
                        </p>
                        <div>
                            <h5 class="font-bold text-xs font-outfit uppercase">Aparoksha HR Operations</h5>
                            <span class="text-[10px] text-slate-800">Financial Services Partner</span>
                        </div>
                    </div>
                    <div x-show="reviewActive === 1" x-transition class="space-y-4">
                        <i class="fa-solid fa-quote-left text-3xl text-slate-900/20 block"></i>
                        <p class="text-sm font-medium leading-relaxed italic">
                            "RM HR Solutions has consistently delivered quality contractual manpower for our packaging and logistics hubs. Their ESIC and PF statutory reporting is 100% compliant."
                        </p>
                        <div>
                            <h5 class="font-bold text-xs font-outfit uppercase">Logistics Operations Manager</h5>
                            <span class="text-[10px] text-slate-800">Ecommerce Fulfillment Partner</span>
                        </div>
                    </div>
                </div>

                <div class="flex space-x-3 pt-2">
                    <button @click="reviewActive = 0" :class="reviewActive === 0 ? 'bg-slate-900 w-8' : 'bg-slate-900/30 w-2.5'" class="h-2.5 rounded-full transition-all duration-300"></button>
                    <button @click="reviewActive = 1" :class="reviewActive === 1 ? 'bg-slate-900 w-8' : 'bg-slate-900/30 w-2.5'" class="h-2.5 rounded-full transition-all duration-300"></button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Section 9: Industries Section ("OUR INDUSTRIES" / "Why choose RM HR Solutions") -->
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-20 border-b border-slate-100">
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 items-center">
        <!-- Content Column -->
        <div class="lg:col-span-7 space-y-6">
            <span class="inline-flex items-center px-3 py-1.5 rounded-full text-[10px] font-bold bg-indigo-50 text-indigo-600 uppercase tracking-widest">
                Our Industries
            </span>
            <h2 class="font-outfit font-black text-3xl text-slate-850">
                Why choose RM HR Solutions
            </h2>
            <p class="text-slate-650 text-sm leading-relaxed">
                RM HR Solutions is committed to organizational empowerment by supplying customized staffing services across multiple domains. We maintain a database of specialists tailored for:
            </p>
            
            <div class="grid grid-cols-2 sm:grid-cols-3 gap-6 pt-4">
                <div class="flex items-center space-x-3">
                    <div class="w-8 h-8 rounded-full bg-cyan-100 text-cyan-600 flex items-center justify-center shrink-0">
                        <i class="fa-solid fa-cart-shopping text-xs"></i>
                    </div>
                    <span class="text-xs font-bold text-slate-800">E-Commerce</span>
                </div>
                <div class="flex items-center space-x-3">
                    <div class="w-8 h-8 rounded-full bg-cyan-100 text-cyan-600 flex items-center justify-center shrink-0">
                        <i class="fa-solid fa-truck text-xs"></i>
                    </div>
                    <span class="text-xs font-bold text-slate-800">Logistics</span>
                </div>
                <div class="flex items-center space-x-3">
                    <div class="w-8 h-8 rounded-full bg-cyan-100 text-cyan-600 flex items-center justify-center shrink-0">
                        <i class="fa-solid fa-bowl-food text-xs"></i>
                    </div>
                    <span class="text-xs font-bold text-slate-800">FMCG</span>
                </div>
                <div class="flex items-center space-x-3">
                    <div class="w-8 h-8 rounded-full bg-cyan-100 text-cyan-600 flex items-center justify-center shrink-0">
                        <i class="fa-solid fa-road text-xs"></i>
                    </div>
                    <span class="text-xs font-bold text-slate-800">Infrastructure</span>
                </div>
                <div class="flex items-center space-x-3">
                    <div class="w-8 h-8 rounded-full bg-cyan-100 text-cyan-600 flex items-center justify-center shrink-0">
                        <i class="fa-solid fa-trowel-bricks text-xs"></i>
                    </div>
                    <span class="text-xs font-bold text-slate-800">Construction</span>
                </div>
                <div class="flex items-center space-x-3">
                    <div class="w-8 h-8 rounded-full bg-cyan-100 text-cyan-600 flex items-center justify-center shrink-0">
                        <i class="fa-solid fa-store text-xs"></i>
                    </div>
                    <span class="text-xs font-bold text-slate-800">Retail</span>
                </div>
                <div class="flex items-center space-x-3">
                    <div class="w-8 h-8 rounded-full bg-cyan-100 text-cyan-600 flex items-center justify-center shrink-0">
                        <i class="fa-solid fa-credit-card text-xs"></i>
                    </div>
                    <span class="text-xs font-bold text-slate-800">Fintech</span>
                </div>
                <div class="flex items-center space-x-3">
                    <div class="w-8 h-8 rounded-full bg-cyan-100 text-cyan-600 flex items-center justify-center shrink-0">
                        <i class="fa-solid fa-industry text-xs"></i>
                    </div>
                    <span class="text-xs font-bold text-slate-800">Manufacturing</span>
                </div>
                <div class="flex items-center space-x-3">
                    <div class="w-8 h-8 rounded-full bg-cyan-100 text-cyan-600 flex items-center justify-center shrink-0">
                        <i class="fa-solid fa-prescription-bottle-medical text-xs"></i>
                    </div>
                    <span class="text-xs font-bold text-slate-800">Pharma</span>
                </div>
            </div>
        </div>

        <!-- Image Column -->
        <div class="lg:col-span-5 relative">
            <img src="https://images.unsplash.com/photo-1522071820081-009f0129c71c?auto=format&fit=crop&w=700&q=80"
                alt="Industries Team"
                class="rounded-3xl border border-slate-200 shadow-md object-cover w-full h-80 relative z-10">
        </div>
    </div>
</div>

<!-- Section 10: Valuable Clients Section -->
<div class="bg-white py-16 border-b border-slate-100 overflow-hidden">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-10">
        <div class="text-center space-y-2">
            <span class="inline-flex items-center px-3 py-1 rounded-full text-[10px] font-bold bg-indigo-50 text-indigo-600 uppercase tracking-widest">
                Valuable Clients
            </span>
            <h3 class="font-outfit font-black text-2xl text-slate-850">Honoring Our Valued Partners</h3>
        </div>

        <!-- Logos Marquee -->
        <div class="flex flex-wrap items-center justify-center gap-8 sm:gap-16 opacity-60">
            <span class="font-outfit font-extrabold text-slate-700 text-lg tracking-wider">WRITER CORPORATION</span>
            <span class="font-outfit font-extrabold text-slate-700 text-lg tracking-wider">XPRESSBEES</span>
            <span class="font-outfit font-extrabold text-slate-700 text-lg tracking-wider">LOAN2WHEELS</span>
            <span class="font-outfit font-extrabold text-slate-700 text-lg tracking-wider">CHQBOOK</span>
        </div>
    </div>
</div>

<!-- Section 11: Recent Post / Blog Section -->
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-20 border-b border-slate-100">
    <div class="text-center max-w-2xl mx-auto mb-16 space-y-3">
        <span class="inline-flex items-center px-3 py-1.5 rounded-full text-[10px] font-bold bg-indigo-50 text-indigo-600 uppercase tracking-widest">
            Recent Post
        </span>
        <h2 class="font-outfit font-black text-3xl text-slate-850">
            Read the latest from our account experts
        </h2>
        <p class="text-slate-500 text-xs">
            Let's explore some of the latest trends and strategies.
        </p>
    </div>

    <!-- Masonry Grid -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <!-- Post 1 -->
        <div class="bg-white rounded-2xl border border-slate-200 overflow-hidden shadow-sm flex flex-col justify-between hover:shadow-md transition-all">
            <div>
                <img src="https://images.unsplash.com/photo-1522071820081-009f0129c71c?auto=format&fit=crop&w=600&q=80" alt="Blog 1" class="w-full h-40 object-cover">
                <div class="p-6 space-y-2">
                    <span class="text-[10px] font-bold text-cyan-600 uppercase tracking-widest block">ADHAAN INSIGHTS</span>
                    <h4 class="font-outfit font-bold text-base text-slate-850 leading-snug">
                        RM HR Solutions Pvt. Ltd. – Shaping The Future of Enterprises!
                    </h4>
                    <p class="text-[10px] text-slate-400">Published on: September 29, 2026</p>
                </div>
            </div>
            <div class="p-6 pt-0">
                <a href="{{ route('services') }}" class="text-xxs font-bold uppercase text-indigo-600 hover:underline">Read Article <i class="fa-solid fa-arrow-right ml-1"></i></a>
            </div>
        </div>

        <!-- Post 2 (Center highlight card) -->
        <div class="bg-white rounded-2xl border border-slate-200 overflow-hidden shadow-sm flex flex-col justify-between hover:shadow-md transition-all md:scale-105 border-indigo-200">
            <div>
                <img src="https://images.unsplash.com/photo-1524178232363-1fb2b075b655?auto=format&fit=crop&w=600&q=80" alt="Blog 2" class="w-full h-44 object-cover">
                <div class="p-6 space-y-3">
                    <span class="text-[10px] font-bold text-indigo-600 uppercase tracking-widest block">LEADERSHIP SPOTLIGHT</span>
                    <h4 class="font-outfit font-black text-lg text-slate-850 leading-snug">
                        Bhavna Udernani: Changing The Dynamics Of HR Solutions
                    </h4>
                    <p class="text-[10px] text-slate-400">Published on: March 8, 2026</p>
                    <p class="text-slate-600 text-xs leading-relaxed">
                        An in-depth review on re-defining the staffing landscape, managing employee payroll, and statutory reporting frameworks.
                    </p>
                </div>
            </div>
            <div class="p-6 pt-0">
                <a href="{{ route('services') }}" class="text-xxs font-bold uppercase text-indigo-600 hover:underline">Read Feature <i class="fa-solid fa-arrow-right ml-1"></i></a>
            </div>
        </div>

        <!-- Post 3 -->
        <div class="bg-white rounded-2xl border border-slate-200 overflow-hidden shadow-sm flex flex-col justify-between hover:shadow-md transition-all">
            <div>
                <img src="https://images.unsplash.com/photo-1573497019940-1c28c88b4f3e?auto=format&fit=crop&w=600&q=80" alt="Blog 3" class="w-full h-40 object-cover">
                <div class="p-6 space-y-2">
                    <span class="text-[10px] font-bold text-cyan-600 uppercase tracking-widest block">HR MANAGEMENT</span>
                    <h4 class="font-outfit font-bold text-base text-slate-850 leading-snug">
                        Managing Director, RM HR Solutions: Operational Sourcing Benchmarks
                    </h4>
                    <p class="text-[10px] text-slate-400">Published on: January 17, 2026</p>
                </div>
            </div>
            <div class="p-6 pt-0">
                <a href="{{ route('services') }}" class="text-xxs font-bold uppercase text-indigo-600 hover:underline">Read Article <i class="fa-solid fa-arrow-right ml-1"></i></a>
            </div>
        </div>
    </div>

    <!-- Bottom Bar (2 Cards style) -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-8">
        <!-- Post 4 -->
        <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm flex items-center justify-between">
            <div class="space-y-1">
                <span class="text-[9px] font-bold text-cyan-600 uppercase tracking-wider block">COMPLIANCE DOCUMENTS</span>
                <h5 class="font-outfit font-bold text-sm text-slate-850">Statutory Compliance & Audits Setup</h5>
                <p class="text-[10px] text-slate-400">November 30, 2025</p>
            </div>
            <a href="{{ route('services') }}" class="w-10 h-10 rounded-full bg-slate-100 hover:bg-slate-200 flex items-center justify-center text-slate-700 transition-all shrink-0">
                <i class="fa-solid fa-chevron-right text-xs"></i>
            </a>
        </div>

        <!-- Post 5 (CTA card) -->
        <div class="bg-indigo-900 p-6 rounded-2xl text-white shadow-sm flex items-center justify-between">
            <div class="space-y-1">
                <h5 class="font-outfit font-bold text-sm">Want to explore all articles?</h5>
                <p class="text-slate-300 text-xs">Read the latest trends from our account experts.</p>
            </div>
            <a href="{{ route('services') }}" class="px-5 py-2.5 bg-cyan-500 hover:bg-cyan-400 text-slate-900 rounded-full text-xs font-black uppercase tracking-wider transition-all shrink-0">
                View All Blogs
            </a>
        </div>
    </div>
</div>

<!-- Section 12: Call to Action Banner -->
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-20 text-center">
    <div class="bg-indigo-950 rounded-3xl p-8 sm:p-12 text-white shadow-xl relative overflow-hidden">
        <div class="absolute -top-12 -left-12 w-64 h-64 rounded-full bg-white/5 blur-2xl"></div>
        <div class="absolute -bottom-12 -right-12 w-64 h-64 rounded-full bg-white/5 blur-2xl"></div>

        <h2 class="font-outfit font-black text-3xl sm:text-4xl mb-4 relative z-10">
            Start making great hires today
        </h2>
        <p class="text-slate-300 max-w-xl mx-auto mb-8 text-sm leading-relaxed relative z-10">
            Connect with our recruitment consulting representatives to organize, scale, and manage statutory employee structures.
        </p>
        <a href="{{ route('contact') }}" class="inline-flex items-center px-8 py-4 bg-cyan-500 hover:bg-cyan-400 text-slate-900 rounded-full text-sm font-black uppercase tracking-wider shadow-lg shadow-cyan-500/20 hover:scale-[1.02] transition-all duration-300 relative z-10">
            Book a Consulting <i class="fa-solid fa-angle-right ml-2 text-slate-900"></i>
        </a>
    </div>
</div>
@endsection
