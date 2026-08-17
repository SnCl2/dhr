@extends('layouts.admin')

@section('title', 'Create Template - Propszy')
@section('page_title', 'Create Offer Letter Template')

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="mb-6">
        <a href="{{ route('admin.templates.index') }}" class="text-xs font-semibold text-slate-400 hover:text-white flex items-center">
            <i class="fa-solid fa-arrow-left mr-1.5"></i> Back to Templates
        </a>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
        <!-- Left: Form -->
        <div class="lg:col-span-8 glass-dark p-8 rounded-3xl border border-slate-850 shadow-2xl">
            <form action="{{ route('admin.templates.store') }}" method="POST" class="space-y-6">
                @csrf

                <!-- Name -->
                <div>
                    <label for="name" class="block text-xs font-semibold uppercase tracking-wider text-slate-400">Template Name</label>
                    <input type="text" id="name" name="name" required value="{{ old('name') }}"
                        class="mt-2 block w-full px-4 py-3 bg-slate-950/60 border border-slate-800 rounded-xl text-slate-200 focus:outline-none focus:ring-1 focus:ring-purple-500 text-sm"
                        placeholder="e.g. Standard Developer Offer Letter">
                    @error('name')
                        <p class="mt-1.5 text-xs text-rose-400">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Subject -->
                <div>
                    <label for="subject" class="block text-xs font-semibold uppercase tracking-wider text-slate-400">Email/Document Subject Line</label>
                    <input type="text" id="subject" name="subject" required value="{{ old('subject') }}"
                        class="mt-2 block w-full px-4 py-3 bg-slate-950/60 border border-slate-800 rounded-xl text-slate-200 focus:outline-none focus:ring-1 focus:ring-purple-500 text-sm"
                        placeholder="e.g. Offer of Appointment - Propszy">
                    @error('subject')
                        <p class="mt-1.5 text-xs text-rose-400">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Type -->
                <div>
                    <label for="type" class="block text-xs font-semibold uppercase tracking-wider text-slate-400">Template Format Style</label>
                    <select id="type" name="type" required
                        class="mt-2 block w-full px-3 py-3 bg-slate-950/60 border border-slate-800 rounded-xl text-slate-200 focus:outline-none focus:ring-1 focus:ring-purple-500 text-sm">
                        <option value="internal" {{ old('type') === 'internal' ? 'selected' : '' }}>Internal Staff Layout (Dense corporate header)</option>
                        <option value="external" {{ old('type') === 'external' ? 'selected' : '' }}>External Staff Layout (Premium border frame + elegant margins)</option>
                    </select>
                    @error('type')
                        <p class="mt-1.5 text-xs text-rose-400">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Content -->
                <div>
                    <label for="content" class="block text-xs font-semibold uppercase tracking-wider text-slate-400 mb-2">Offer Letter Body Content</label>
                    <textarea id="content" name="content" rows="12" required
                        class="block w-full px-4 py-3 bg-slate-950/60 border border-slate-800 rounded-xl text-slate-200 focus:outline-none focus:ring-1 focus:ring-purple-500 text-sm font-mono leading-relaxed"
                        placeholder="Write the letter body..."></textarea>
                    @error('content')
                        <p class="mt-1.5 text-xs text-rose-400">{{ $message }}</p>
                    @enderror
                </div>

                <div class="pt-6 border-t border-slate-850 flex justify-end space-x-3">
                    <a href="{{ route('admin.templates.index') }}"
                        class="px-6 py-3 bg-slate-850 hover:bg-slate-800 text-slate-400 hover:text-white rounded-xl text-sm font-semibold transition-all">
                        Cancel
                    </a>
                    <button type="submit"
                        class="px-6 py-3 bg-gradient-to-r from-purple-600 to-indigo-600 hover:from-purple-500 hover:to-indigo-500 text-white rounded-xl text-sm font-semibold transition-all shadow-lg shadow-purple-500/10">
                        <i class="fa-solid fa-save mr-2"></i> Save Template
                    </button>
                </div>
            </form>
        </div>

        <!-- Right: Guide of Placeholders -->
        <div class="lg:col-span-4 space-y-6">
            <div class="glass-dark p-6 rounded-3xl border border-slate-850">
                <h3 class="font-outfit font-bold text-base text-white mb-4 flex items-center">
                    <i class="fa-solid fa-code text-purple-400 mr-2 text-sm"></i> Dynamic Placeholders
                </h3>
                <p class="text-xs text-slate-400 leading-relaxed mb-4">
                    Insert these placeholders in your letter content. They will be dynamically replaced when generating FPDF files:
                </p>

                <div class="space-y-3 font-mono text-xs">
                    <div class="p-2.5 rounded bg-slate-950/50 border border-slate-900 flex justify-between">
                        <span class="text-purple-400 select-all font-semibold">{first_name}</span>
                        <span class="text-slate-500 text-[10px]">Candidate first name</span>
                    </div>
                    <div class="p-2.5 rounded bg-slate-950/50 border border-slate-900 flex justify-between">
                        <span class="text-purple-400 select-all font-semibold">{last_name}</span>
                        <span class="text-slate-500 text-[10px]">Candidate last name</span>
                    </div>
                    <div class="p-2.5 rounded bg-slate-950/50 border border-slate-900 flex justify-between">
                        <span class="text-purple-400 select-all font-semibold">{full_name}</span>
                        <span class="text-slate-500 text-[10px]">Candidate full name</span>
                    </div>
                    <div class="p-2.5 rounded bg-slate-950/50 border border-slate-900 flex justify-between">
                        <span class="text-purple-400 select-all font-semibold">{employee_id}</span>
                        <span class="text-slate-500 text-[10px]">Generated ID</span>
                    </div>
                    <div class="p-2.5 rounded bg-slate-950/50 border border-slate-900 flex justify-between">
                        <span class="text-purple-400 select-all font-semibold">{department}</span>
                        <span class="text-slate-500 text-[10px]">Assigned department</span>
                    </div>
                    <div class="p-2.5 rounded bg-slate-950/50 border border-slate-900 flex justify-between">
                        <span class="text-purple-400 select-all font-semibold">{designation}</span>
                        <span class="text-slate-500 text-[10px]">Assigned designation</span>
                    </div>
                    <div class="p-2.5 rounded bg-slate-950/50 border border-slate-900 flex justify-between">
                        <span class="text-purple-400 select-all font-semibold">{salary}</span>
                        <span class="text-slate-500 text-[10px]">Payout amount</span>
                    </div>
                    <div class="p-2.5 rounded bg-slate-950/50 border border-slate-900 flex justify-between">
                        <span class="text-purple-400 select-all font-semibold">{joining_date}</span>
                        <span class="text-slate-500 text-[10px]">Onboarding date</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
