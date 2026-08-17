@extends('layouts.employee')

@section('title', 'Employee Dashboard - RMHRSolutions')
@section('page_title', 'My Profile & Self-Service')

@section('content')
<div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
    <!-- Left Column: User Profile & Update Form -->
    <div class="lg:col-span-8 space-y-8">
        <!-- Profile Info Card -->
        <div class="glass-dark p-6 sm:p-8 rounded-3xl border border-slate-850">
            <h3 class="font-outfit font-bold text-xl text-white mb-6 flex items-center">
                <span class="w-2.5 h-2.5 rounded-full bg-purple-500 mr-2.5"></span> Employment Record Details
            </h3>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6 text-sm">
                <div class="space-y-4">
                    <div>
                        <span class="block text-xs text-slate-500 uppercase tracking-wider">Employee Name</span>
                        <span class="block font-semibold text-white text-base mt-0.5">{{ $employee->full_name }}</span>
                    </div>
                    <div>
                        <span class="block text-xs text-slate-500 uppercase tracking-wider">Email Address</span>
                        <span class="block text-slate-200 mt-0.5">{{ $employee->email }}</span>
                    </div>
                    <div>
                        <span class="block text-xs text-slate-500 uppercase tracking-wider">Phone Number</span>
                        <span class="block text-slate-200 mt-0.5">{{ $employee->phone ?? 'N/A' }}</span>
                    </div>
                </div>

                <div class="space-y-4">
                    <div>
                        <span class="block text-xs text-slate-500 uppercase tracking-wider">Department</span>
                        <span class="block text-slate-200 mt-0.5">{{ $employee->department ? $employee->department->name : 'Unassigned' }}</span>
                    </div>
                    <div>
                        <span class="block text-xs text-slate-500 uppercase tracking-wider">Designation</span>
                        <span class="block text-slate-200 mt-0.5">{{ $employee->designation ? $employee->designation->name : 'Unassigned' }}</span>
                    </div>
                    <div>
                        <span class="block text-xs text-slate-500 uppercase tracking-wider">Date of Onboarding</span>
                        <span class="block text-slate-200 mt-0.5">{{ $employee->joining_date ? $employee->joining_date->format('d-M-Y') : 'N/A' }}</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Request Update Form -->
        <div class="glass-dark p-6 sm:p-8 rounded-3xl border border-slate-850">
            <h3 class="font-outfit font-bold text-xl text-white mb-2 flex items-center">
                <i class="fa-solid fa-user-pen text-purple-400 mr-2.5 text-sm"></i> Request Profile Update
            </h3>
            <p class="text-xs text-slate-400 leading-relaxed mb-6">
                Need to change your contact details, address, or other personal info? Submit a request to the HR Admin team.
            </p>

            <form action="{{ route('employee.profile.update') }}" method="POST" class="space-y-6">
                @csrf

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                    <!-- Phone -->
                    <div class="sm:col-span-2">
                        <label for="phone" class="block text-xs font-semibold uppercase tracking-wider text-slate-400">Current / New Phone Number</label>
                        <input type="text" id="phone" name="phone" required value="{{ old('phone', $employee->phone) }}"
                            class="mt-2 block w-full px-4 py-3 bg-slate-950/60 border border-slate-800 rounded-xl text-slate-200 focus:outline-none focus:ring-1 focus:ring-purple-500 text-sm">
                    </div>

                    <!-- Request Description -->
                    <div class="sm:col-span-2">
                        <label for="request_notes" class="block text-xs font-semibold uppercase tracking-wider text-slate-400 mb-2">Request Details / Notes</label>
                        <textarea id="request_notes" name="request_notes" rows="4" required
                            class="block w-full px-4 py-3 bg-slate-950/60 border border-slate-800 rounded-xl text-slate-200 focus:outline-none focus:ring-1 focus:ring-purple-500 text-sm"
                            placeholder="Explain what changes are needed (e.g. correct spelling of last name, update address, etc.)..."></textarea>
                    </div>
                </div>

                <div class="flex justify-end pt-4 border-t border-slate-850">
                    <button type="submit"
                        class="w-full sm:w-auto px-6 py-3.5 bg-gradient-to-r from-purple-600 to-indigo-600 hover:from-purple-500 hover:to-indigo-500 text-white rounded-xl text-sm font-semibold transition-all shadow-lg shadow-purple-500/10">
                        <i class="fa-solid fa-paper-plane mr-2"></i> Submit Request
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Right Column: Document Counts & Quick bulletins -->
    <div class="lg:col-span-4 space-y-8">
        <!-- Quick Stats -->
        <div class="glass-dark p-6 rounded-3xl border border-slate-850">
            <h4 class="font-outfit font-bold text-sm text-slate-350 uppercase tracking-wider mb-4">My Documents Summary</h4>
            <div class="grid grid-cols-2 gap-4">
                <div class="bg-slate-950/50 p-4 rounded-2xl border border-slate-900 text-center">
                    <span class="block font-outfit font-extrabold text-2xl text-purple-400">{{ $stats['letters_count'] }}</span>
                    <span class="block text-[10px] text-slate-500 uppercase tracking-widest mt-1">Offer Letters</span>
                </div>
                <div class="bg-slate-950/50 p-4 rounded-2xl border border-slate-900 text-center">
                    <span class="block font-outfit font-extrabold text-2xl text-pink-400">{{ $stats['payslips_count'] }}</span>
                    <span class="block text-[10px] text-slate-500 uppercase tracking-widest mt-1">Payslips</span>
                </div>
            </div>
            <a href="{{ route('employee.documents') }}" class="w-full mt-4 flex items-center justify-center space-x-2 py-2 px-4 bg-purple-600/10 hover:bg-purple-600/20 text-purple-300 hover:text-white rounded-xl text-xs font-semibold transition-all">
                <span>Access Document Center</span>
                <i class="fa-solid fa-arrow-right"></i>
            </a>
        </div>

        <!-- Notices Board Bulletin -->
        <div class="glass-dark p-6 rounded-3xl border border-slate-850">
            <div class="flex items-center justify-between mb-4">
                <h4 class="font-outfit font-bold text-sm text-slate-350 uppercase tracking-wider">Recent Announcements</h4>
                <a href="{{ route('employee.bulletins') }}" class="text-[10px] font-semibold text-purple-400">View All</a>
            </div>

            <div class="space-y-4">
                @forelse($bulletins as $bull)
                <div class="p-3.5 rounded-xl bg-slate-950/40 border border-slate-900 text-xs">
                    <div class="flex items-center justify-between mb-1.5">
                        <span class="font-bold text-white truncate max-w-[150px]">{{ $bull->title }}</span>
                        <span class="text-[9px] text-slate-500">{{ $bull->created_at->diffForHumans() }}</span>
                    </div>
                    <p class="text-slate-400 leading-relaxed truncate">{{ $bull->content }}</p>
                </div>
                @empty
                <p class="text-center py-4 text-xs text-slate-500">No announcements posted.</p>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection
