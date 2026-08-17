@extends('layouts.app')

@section('title', 'Contact Us - RM HR Solutions')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16 sm:py-24">
    <!-- Header -->
    <div class="text-center max-w-3xl mx-auto mb-16 space-y-3">
        <span class="text-xs font-bold uppercase tracking-widest text-indigo-600 bg-indigo-50 px-3 py-1.5 rounded-full border border-indigo-100">Get In Touch</span>
        <h1 class="font-outfit font-black text-4xl sm:text-5xl text-slate-850">
            Connect With Our Team
        </h1>
        <p class="text-sm text-slate-500 max-w-xl mx-auto">
            Have questions about candidate placement or corporate staffing? Fill out the form or reach us via phone or email.
        </p>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 items-start">
        <!-- Left: Contact Details -->
        <div class="lg:col-span-5 space-y-8">
            <div class="bg-white p-8 rounded-3xl border border-slate-200 shadow-sm space-y-6">
                <h3 class="font-outfit font-bold text-xl text-slate-800">Contact Information</h3>
                
                <!-- Address -->
                <div class="flex items-start space-x-4">
                    <div class="w-10 h-10 rounded-xl bg-indigo-50 text-indigo-600 flex items-center justify-center shrink-0">
                        <i class="fa-solid fa-location-dot"></i>
                    </div>
                    <div class="space-y-1">
                        <span class="block text-xxs font-bold text-slate-400 uppercase tracking-wider">Office Location</span>
                        <span class="block text-slate-700 text-xs leading-relaxed">{{ $cms['address'] }}</span>
                    </div>
                </div>

                <!-- Phone -->
                <div class="flex items-start space-x-4">
                    <div class="w-10 h-10 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center shrink-0">
                        <i class="fa-solid fa-phone"></i>
                    </div>
                    <div class="space-y-1">
                        <span class="block text-xxs font-bold text-slate-400 uppercase tracking-wider">Call Us</span>
                        <span class="block text-slate-700 text-xs">{{ $cms['phone'] }}</span>
                    </div>
                </div>

                <!-- Email -->
                <div class="flex items-start space-x-4">
                    <div class="w-10 h-10 rounded-xl bg-pink-50 text-pink-650 flex items-center justify-center shrink-0">
                        <i class="fa-solid fa-envelope"></i>
                    </div>
                    <div class="space-y-1">
                        <span class="block text-xxs font-bold text-slate-400 uppercase tracking-wider">Email Address</span>
                        <span class="block text-slate-700 text-xs">{{ $cms['email'] }}</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Right: Inquiry Form -->
        <div class="lg:col-span-7">
            <div class="bg-white p-8 sm:p-10 rounded-3xl border border-slate-200 shadow-sm space-y-6">
                <h3 class="font-outfit font-bold text-xl text-slate-800">Send an Inquiry</h3>
                
                <form action="{{ route('contact.submit') }}" method="POST" class="space-y-6">
                    @csrf

                    <!-- Success Alert -->
                    @if(session('success'))
                        <div class="p-4 rounded-xl bg-emerald-50 border border-emerald-200 text-xs text-emerald-700 font-medium">
                            {{ session('success') }}
                        </div>
                    @endif

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                        <!-- Name -->
                        <div>
                            <label for="name" class="block text-xs font-bold uppercase tracking-wider text-slate-655">Your Name</label>
                            <input type="text" id="name" name="name" required value="{{ old('name') }}"
                                class="mt-2 block w-full px-4 py-3 bg-white border border-slate-300 rounded-xl text-slate-800 placeholder-slate-400 focus:outline-none focus:ring-1 focus:ring-indigo-500 text-sm transition-all"
                                placeholder="John Doe">
                            @error('name')
                                <p class="mt-2 text-xs text-rose-500 font-medium">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Email -->
                        <div>
                            <label for="email" class="block text-xs font-bold uppercase tracking-wider text-slate-655">Email Address</label>
                            <input type="email" id="email" name="email" required value="{{ old('email') }}"
                                class="mt-2 block w-full px-4 py-3 bg-white border border-slate-300 rounded-xl text-slate-800 placeholder-slate-400 focus:outline-none focus:ring-1 focus:ring-indigo-500 text-sm transition-all"
                                placeholder="john@example.com">
                            @error('email')
                                <p class="mt-2 text-xs text-rose-500 font-medium">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                        <!-- Phone -->
                        <div>
                            <label for="phone" class="block text-xs font-bold uppercase tracking-wider text-slate-655">Phone (Optional)</label>
                            <input type="tel" id="phone" name="phone" value="{{ old('phone') }}"
                                class="mt-2 block w-full px-4 py-3 bg-white border border-slate-300 rounded-xl text-slate-800 placeholder-slate-400 focus:outline-none focus:ring-1 focus:ring-indigo-500 text-sm transition-all"
                                placeholder="+91 98765 43210">
                            @error('phone')
                                <p class="mt-2 text-xs text-rose-500 font-medium">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Subject -->
                        <div>
                            <label for="subject" class="block text-xs font-bold uppercase tracking-wider text-slate-655">Subject</label>
                            <input type="text" id="subject" name="subject" required value="{{ old('subject') }}"
                                class="mt-2 block w-full px-4 py-3 bg-white border border-slate-300 rounded-xl text-slate-800 placeholder-slate-400 focus:outline-none focus:ring-1 focus:ring-indigo-500 text-sm transition-all"
                                placeholder="Hiring Requirement / Inquiry">
                            @error('subject')
                                <p class="mt-2 text-xs text-rose-500 font-medium">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Message -->
                    <div>
                        <label for="message" class="block text-xs font-bold uppercase tracking-wider text-slate-655">Your Message</label>
                        <textarea id="message" name="message" rows="5" required
                            class="mt-2 block w-full px-4 py-3 bg-white border border-slate-300 rounded-xl text-slate-800 placeholder-slate-400 focus:outline-none focus:ring-1 focus:ring-indigo-500 text-sm transition-all"
                            placeholder="Write your message here..."></textarea>
                        @error('message')
                            <p class="mt-2 text-xs text-rose-500 font-medium">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Submit -->
                    <div>
                        <button type="submit"
                            class="w-full flex justify-center py-3.5 px-4 border border-transparent rounded-xl shadow-md text-sm font-semibold text-white bg-gradient-to-r from-indigo-650 to-blue-650 hover:from-indigo-600 hover:to-blue-600 focus:outline-none focus:ring-1 focus:ring-indigo-500 shadow-indigo-600/10 transition-all duration-300">
                            <i class="fa-solid fa-paper-plane mr-2 mt-0.5"></i> Submit Inquiry
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
