@extends('layouts.admin')

@section('title', 'CMS Content Management - Propszy')
@section('page_title', 'CMS Site Content Management')

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="glass-dark p-8 sm:p-10 rounded-3xl border border-slate-200 shadow-xl">
        <form action="{{ route('admin.cms.update') }}" method="POST" class="space-y-6">
            @csrf

            <h3 class="font-outfit font-bold text-lg text-slate-800 mb-6 flex items-center">
                <i class="fa-solid fa-window-restore text-purple-600 mr-2 text-sm"></i> Configure Homepage Marketing Contents
            </h3>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                <!-- Banner Title -->
                <div class="sm:col-span-2">
                    <label for="home_banner_title" class="block text-xs font-semibold uppercase tracking-wider text-slate-600">Homepage Banner Title</label>
                    <input type="text" id="home_banner_title" name="home_banner_title" required 
                        value="{{ old('home_banner_title', $content['home_banner_title'] ?? '') }}"
                        class="mt-2 block w-full px-4 py-3 bg-white border border-slate-300 rounded-xl text-slate-800 focus:outline-none focus:ring-1 focus:ring-purple-500 text-sm">
                </div>

                <!-- Banner Subtitle -->
                <div class="sm:col-span-2">
                    <label for="home_banner_subtitle" class="block text-xs font-semibold uppercase tracking-wider text-slate-600">Homepage Banner Subtitle</label>
                    <textarea id="home_banner_subtitle" name="home_banner_subtitle" rows="3" required
                        class="mt-2 block w-full px-4 py-3 bg-white border border-slate-300 rounded-xl text-slate-800 focus:outline-none focus:ring-1 focus:ring-purple-500 text-sm">{{ old('home_banner_subtitle', $content['home_banner_subtitle'] ?? '') }}</textarea>
                </div>
            </div>

            <!-- About Us Paragraph -->
            <div>
                <label for="about_us_text" class="block text-xs font-semibold uppercase tracking-wider text-slate-600">About Us Main Description</label>
                <textarea id="about_us_text" name="about_us_text" rows="5" required
                    class="mt-2 block w-full px-4 py-3 bg-white border border-slate-300 rounded-xl text-slate-800 focus:outline-none focus:ring-1 focus:ring-purple-500 text-sm">{{ old('about_us_text', $content['about_us_text'] ?? '') }}</textarea>
            </div>

            <h3 class="font-outfit font-bold text-lg text-slate-800 pt-6 border-t border-slate-200 mb-6 flex items-center">
                <i class="fa-solid fa-address-book text-pink-650 mr-2 text-sm"></i> Official Agency Contact Info
            </h3>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                <!-- Phone -->
                <div>
                    <label for="contact_phone" class="block text-xs font-semibold uppercase tracking-wider text-slate-600">Official Phone Number</label>
                    <input type="text" id="contact_phone" name="contact_phone" required 
                        value="{{ old('contact_phone', $content['contact_phone'] ?? '') }}"
                        class="mt-2 block w-full px-4 py-3 bg-white border border-slate-300 rounded-xl text-slate-800 focus:outline-none focus:ring-1 focus:ring-purple-500 text-sm">
                </div>

                <!-- Email -->
                <div>
                    <label for="contact_email" class="block text-xs font-semibold uppercase tracking-wider text-slate-600">Official Inquiry Email</label>
                    <input type="email" id="contact_email" name="contact_email" required 
                        value="{{ old('contact_email', $content['contact_email'] ?? '') }}"
                        class="mt-2 block w-full px-4 py-3 bg-white border border-slate-300 rounded-xl text-slate-800 focus:outline-none focus:ring-1 focus:ring-purple-500 text-sm">
                </div>

                <!-- Address -->
                <div class="sm:col-span-2">
                    <label for="contact_address" class="block text-xs font-semibold uppercase tracking-wider text-slate-600">Office Physical Address</label>
                    <input type="text" id="contact_address" name="contact_address" required 
                        value="{{ old('contact_address', $content['contact_address'] ?? '') }}"
                        class="mt-2 block w-full px-4 py-3 bg-white border border-slate-300 rounded-xl text-slate-800 focus:outline-none focus:ring-1 focus:ring-purple-500 text-sm">
                </div>
            </div>

            <div class="pt-6 border-t border-slate-200 flex justify-end">
                <button type="submit"
                    class="w-full sm:w-auto px-6 py-3.5 bg-gradient-to-r from-purple-600 to-indigo-600 hover:from-purple-500 hover:to-indigo-500 text-white rounded-xl text-sm font-semibold transition-all shadow-lg shadow-purple-500/10">
                    <i class="fa-solid fa-save mr-2"></i> Update CMS Content Settings
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
