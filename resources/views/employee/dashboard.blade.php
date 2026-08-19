@extends('layouts.employee')

@section('title', 'Employee Dashboard - RM HR Solutions')
@section('page_title', 'My Profile & Self-Service')

@section('content')
<div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
    <!-- Left Column: User Profile & Update Form -->
    <div class="lg:col-span-8 space-y-8">
        <!-- Profile Info Card -->
        <div class="bg-white p-6 sm:p-8 rounded-3xl border border-slate-200 shadow-sm transition-all duration-200">
            <h3 class="font-outfit font-bold text-xl text-slate-800 mb-6 flex items-center">
                <span class="w-3.5 h-3.5 rounded-full bg-blue-600 mr-3 flex items-center justify-center">
                    <span class="w-1.5 h-1.5 rounded-full bg-white"></span>
                </span> 
                Employment Record Details
            </h3>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6 text-sm">
                <div class="space-y-5">
                    <div>
                        <span class="block text-xs font-semibold text-slate-400 uppercase tracking-wider">Employee Name</span>
                        <span class="block font-bold text-slate-800 text-base mt-1">{{ $employee->full_name }}</span>
                    </div>
                    <div>
                        <span class="block text-xs font-semibold text-slate-400 uppercase tracking-wider">Email Address</span>
                        <span class="block font-medium text-slate-700 mt-1">{{ $employee->email }}</span>
                    </div>
                    <div>
                        <span class="block text-xs font-semibold text-slate-400 uppercase tracking-wider">Phone Number</span>
                        <span class="block font-medium text-slate-700 mt-1">{{ $employee->phone ?? 'N/A' }}</span>
                    </div>
                </div>

                <div class="space-y-5">
                    <div>
                        <span class="block text-xs font-semibold text-slate-400 uppercase tracking-wider">Department</span>
                        <span class="block font-medium text-slate-700 mt-1">{{ $employee->department ? $employee->department->name : 'Unassigned' }}</span>
                    </div>
                    <div>
                        <span class="block text-xs font-semibold text-slate-400 uppercase tracking-wider">Designation</span>
                        <span class="block font-medium text-slate-700 mt-1">{{ $employee->designationRelation ? $employee->designationRelation->name : 'Unassigned' }}</span>
                    </div>
                    <div>
                        <span class="block text-xs font-semibold text-slate-400 uppercase tracking-wider">Date of Onboarding</span>
                        <span class="block font-medium text-slate-700 mt-1">{{ $employee->joining_date ? $employee->joining_date->format('d-M-Y') : 'N/A' }}</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Request Update Form -->
        <div class="bg-white p-6 sm:p-8 rounded-3xl border border-slate-200 shadow-sm transition-all duration-200">
            <h3 class="font-outfit font-bold text-xl text-slate-800 mb-2 flex items-center">
                <i class="fa-solid fa-user-pen text-blue-600 mr-3 text-lg"></i> Request Profile Update
            </h3>
            <p class="text-xs text-slate-500 leading-relaxed mb-6">
                Need to change your contact details, address, or other personal info? Submit a request to the HR Admin team.
            </p>

            <form action="{{ route('employee.profile.update') }}" method="POST" class="space-y-6">
                @csrf

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                    <!-- Phone -->
                    <div class="sm:col-span-2">
                        <label for="phone" class="block text-xs font-bold uppercase tracking-wider text-slate-550">Current / New Phone Number</label>
                        <input type="text" id="phone" name="phone" required value="{{ old('phone', $employee->phone) }}"
                            class="mt-2 block w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-slate-800 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm transition-all duration-200">
                    </div>

                    <!-- Request Description -->
                    <div class="sm:col-span-2">
                        <label for="request_notes" class="block text-xs font-bold uppercase tracking-wider text-slate-550 mb-2">Request Details / Notes</label>
                        <textarea id="request_notes" name="request_notes" rows="4" required
                            class="block w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-slate-800 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm transition-all duration-200"
                            placeholder="Explain what changes are needed (e.g. correct spelling of last name, update address, etc.)..."></textarea>
                    </div>
                </div>

                <div class="flex justify-end pt-4 border-t border-slate-100">
                    <button type="submit"
                        class="w-full sm:w-auto px-6 py-3.5 bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 text-white rounded-xl text-sm font-semibold transition-all shadow-md shadow-blue-500/10">
                        <i class="fa-solid fa-paper-plane mr-2"></i> Submit Request
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Right Column: Document Counts & Quick bulletins -->
    <div class="lg:col-span-4 space-y-8">
        <!-- Quick Stats -->
        <div class="bg-white p-6 rounded-3xl border border-slate-200 shadow-sm">
            <h4 class="font-outfit font-bold text-xs text-slate-450 uppercase tracking-wider mb-4">My Documents Summary</h4>
            <div class="grid grid-cols-2 gap-4">
                <div class="bg-blue-50/60 p-5 rounded-2xl border border-blue-100/50 text-center shadow-sm">
                    <span class="block font-outfit font-extrabold text-3xl text-blue-600">{{ $stats['letters_count'] }}</span>
                    <span class="block text-[10px] text-slate-500 uppercase tracking-widest mt-1.5 font-semibold">Offer Letters</span>
                </div>
                <div class="bg-indigo-50/60 p-5 rounded-2xl border border-indigo-100/50 text-center shadow-sm">
                    <span class="block font-outfit font-extrabold text-3xl text-indigo-600">{{ $stats['payslips_count'] }}</span>
                    <span class="block text-[10px] text-slate-500 uppercase tracking-widest mt-1.5 font-semibold">Payslips</span>
                </div>
            </div>
            <a href="{{ route('employee.documents') }}" class="w-full mt-4 flex items-center justify-center space-x-2 py-3 px-4 bg-blue-600 hover:bg-blue-700 text-white rounded-xl text-xs font-semibold transition-all shadow-md shadow-blue-500/5">
                <span>Access Document Center</span>
                <i class="fa-solid fa-arrow-right text-xs"></i>
            </a>
        </div>

        <!-- Notices Board Bulletin -->
        <div class="bg-white p-6 rounded-3xl border border-slate-200 shadow-sm">
            <div class="flex items-center justify-between mb-4">
                <h4 class="font-outfit font-bold text-xs text-slate-450 uppercase tracking-wider">Recent Announcements</h4>
                <a href="{{ route('employee.bulletins') }}" class="text-[10px] font-semibold text-blue-600 hover:underline">View All</a>
            </div>

            <div class="space-y-4">
                @forelse($bulletins as $bull)
                <div class="p-4 rounded-2xl bg-slate-50 hover:bg-slate-100/60 border border-slate-100 text-xs transition-all duration-150">
                    <div class="flex items-center justify-between mb-2">
                        <span class="font-bold text-slate-800 truncate max-w-[150px]">{{ $bull->title }}</span>
                        <span class="text-[9px] text-slate-400 font-medium">{{ $bull->created_at->diffForHumans() }}</span>
                    </div>
                    <p class="text-slate-600 leading-relaxed truncate">{{ $bull->content }}</p>
                </div>
                @empty
                <p class="text-center py-6 text-xs text-slate-400">No announcements posted yet.</p>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection
