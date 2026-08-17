@extends('layouts.app')

@section('title', 'Contact Us - Propszy Manpower Solutions')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16 sm:py-24">
    <!-- Header -->
    <div class="text-center max-w-3xl mx-auto mb-16">
        <span class="text-xs font-semibold uppercase tracking-wider text-purple-400 bg-purple-500/10 px-3 py-1 rounded-full border border-purple-500/20">Get In Touch</span>
        <h1 class="font-outfit font-extrabold text-4xl sm:text-5xl text-white mt-4">
            Connect With Our Team
        </h1>
        <p class="mt-4 text-slate-400">
            Have questions about candidate placement or corporate staffing? Fill out the form or reach us via phone or email.
        </p>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 items-start">
        <!-- Left: Contact Details -->
        <div class="lg:col-span-5 space-y-8">
            <div class="glass-dark p-8 rounded-3xl border border-slate-800 space-y-6">
                <h3 class="font-outfit font-bold text-xl text-white">Contact Information</h3>
                
                <!-- Address -->
                <div class="flex items-start space-x-4">
                    <div class="w-10 h-10 rounded-xl bg-purple-500/10 text-purple-400 flex items-center justify-center shrink-0">
                        <i class="fa-solid fa-location-dot"></i>
                    </div>
                    <div>
                        <span class="block text-xs font-semibold text-slate-400 uppercase tracking-wider">Office Location</span>
                        <span class="block text-slate-200 mt-1 text-sm leading-relaxed">{{ $cms['address'] }}</span>
                    </div>
                </div>

                <!-- Phone -->
                <div class="flex items-start space-x-4">
                    <div class="w-10 h-10 rounded-xl bg-indigo-500/10 text-indigo-400 flex items-center justify-center shrink-0">
                        <i class="fa-solid fa-phone"></i>
                    </div>
                    <div>
                        <span class="block text-xs font-semibold text-slate-400 uppercase tracking-wider">Call Us</span>
                        <span class="block text-slate-200 mt-1 text-sm">{{ $cms['phone'] }}</span>
                    </div>
                </div>

                <!-- Email -->
                <div class="flex items-start space-x-4">
                    <div class="w-10 h-10 rounded-xl bg-pink-500/10 text-pink-400 flex items-center justify-center shrink-0">
                        <i class="fa-solid fa-envelope"></i>
                    </div>
                    <div>
                        <span class="block text-xs font-semibold text-slate-400 uppercase tracking-wider">Email Address</span>
                        <span class="block text-slate-200 mt-1 text-sm">{{ $cms['email'] }}</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Right: Inquiry Form -->
        <div class="lg:col-span-7">
            <div class="glass-dark p-8 sm:p-10 rounded-3xl border border-slate-800 shadow-2xl">
                <h3 class="font-outfit font-bold text-xl text-white mb-6">Send an Inquiry</h3>
                
                <form action="{{ route('contact.submit') }}" method="POST" class="space-y-6">
                    @csrf

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                        <!-- Name -->
                        <div>
                            <label for="name" class="block text-xs font-semibold uppercase tracking-wider text-slate-300">Your Name</label>
                            <input type="text" id="name" name="name" required value="{{ old('name') }}"
                                class="mt-2 block w-full px-4 py-3 bg-slate-900/60 border border-slate-700 rounded-xl text-slate-200 placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent text-sm transition-all"
                                placeholder="John Doe">
                            @error('name')
                                <p class="mt-2 text-xs text-rose-400 font-medium">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Email -->
                        <div>
                            <label for="email" class="block text-xs font-semibold uppercase tracking-wider text-slate-300">Email Address</label>
                            <input type="email" id="email" name="email" required value="{{ old('email') }}"
                                class="mt-2 block w-full px-4 py-3 bg-slate-900/60 border border-slate-700 rounded-xl text-slate-200 placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent text-sm transition-all"
                                placeholder="john@example.com">
                            @error('email')
                                <p class="mt-2 text-xs text-rose-400 font-medium">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                        <!-- Phone -->
                        <div>
                            <label for="phone" class="block text-xs font-semibold uppercase tracking-wider text-slate-300">Phone (Optional)</label>
                            <input type="tel" id="phone" name="phone" value="{{ old('phone') }}"
                                class="mt-2 block w-full px-4 py-3 bg-slate-900/60 border border-slate-700 rounded-xl text-slate-200 placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent text-sm transition-all"
                                placeholder="+91 98765 43210">
                            @error('phone')
                                <p class="mt-2 text-xs text-rose-400 font-medium">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Subject -->
                        <div>
                            <label for="subject" class="block text-xs font-semibold uppercase tracking-wider text-slate-300">Subject</label>
                            <input type="text" id="subject" name="subject" required value="{{ old('subject') }}"
                                class="mt-2 block w-full px-4 py-3 bg-slate-900/60 border border-slate-700 rounded-xl text-slate-200 placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent text-sm transition-all"
                                placeholder="Hiring Requirement / Inquiry">
                            @error('subject')
                                <p class="mt-2 text-xs text-rose-400 font-medium">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Message -->
                    <div>
                        <label for="message" class="block text-xs font-semibold uppercase tracking-wider text-slate-300">Your Message</label>
                        <textarea id="message" name="message" rows="5" required
                            class="mt-2 block w-full px-4 py-3 bg-slate-900/60 border border-slate-700 rounded-xl text-slate-200 placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent text-sm transition-all"
                            placeholder="Write your message here..."></textarea>
                        @error('message')
                            <p class="mt-2 text-xs text-rose-400 font-medium">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Submit -->
                    <div>
                        <button type="submit"
                            class="w-full flex justify-center py-3.5 px-4 border border-transparent rounded-xl shadow-md text-sm font-semibold text-white bg-gradient-to-r from-purple-600 to-indigo-600 hover:from-purple-500 hover:to-indigo-500 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-purple-500 shadow-purple-500/20 hover:shadow-purple-500/30 transition-all duration-300">
                            <i class="fa-solid fa-paper-plane mr-2 mt-0.5"></i> Submit Inquiry
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
