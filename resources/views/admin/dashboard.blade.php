@extends('layouts.admin')

@section('title', 'Admin Dashboard - Propszy')
@section('page_title', 'Dashboard Overview')

@section('content')
<!-- Dashboard Stats Cards -->
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
    <!-- Card 1 -->
    <div class="glass-dark p-6 rounded-2xl flex items-center justify-between">
        <div>
            <span class="block text-xs font-semibold text-slate-400 uppercase tracking-wider">Total Candidates / Employees</span>
            <span class="block font-outfit font-extrabold text-3xl text-white mt-1">{{ $stats['total_employees'] }}</span>
        </div>
        <div class="w-12 h-12 rounded-xl bg-purple-500/10 text-purple-400 flex items-center justify-center text-xl shrink-0">
            <i class="fa-solid fa-users"></i>
        </div>
    </div>

    <!-- Card 2 -->
    <div class="glass-dark p-6 rounded-2xl flex items-center justify-between">
        <div>
            <span class="block text-xs font-semibold text-slate-400 uppercase tracking-wider">Active Staff Placements</span>
            <span class="block font-outfit font-extrabold text-3xl text-emerald-400 mt-1">{{ $stats['active_employees'] }}</span>
        </div>
        <div class="w-12 h-12 rounded-xl bg-emerald-500/10 text-emerald-400 flex items-center justify-center text-xl shrink-0">
            <i class="fa-solid fa-user-check"></i>
        </div>
    </div>

    <!-- Card 3 -->
    <div class="glass-dark p-6 rounded-2xl flex items-center justify-between">
        <div>
            <span class="block text-xs font-semibold text-slate-400 uppercase tracking-wider">Pending Review Approvals</span>
            <span class="block font-outfit font-extrabold text-3xl text-amber-400 mt-1">{{ $stats['pending_reviews'] }}</span>
        </div>
        <div class="w-12 h-12 rounded-xl bg-amber-500/10 text-amber-400 flex items-center justify-center text-xl shrink-0">
            <i class="fa-solid fa-user-clock"></i>
        </div>
    </div>

    <!-- Card 4 -->
    <div class="glass-dark p-6 rounded-2xl flex items-center justify-between">
        <div>
            <span class="block text-xs font-semibold text-slate-400 uppercase tracking-wider">Unread Inbox Inquiries</span>
            <span class="block font-outfit font-extrabold text-3xl text-pink-400 mt-1">{{ $stats['unread_inquiries'] }}</span>
        </div>
        <div class="w-12 h-12 rounded-xl bg-pink-500/10 text-pink-400 flex items-center justify-center text-xl shrink-0">
            <i class="fa-solid fa-envelope-open-text"></i>
        </div>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
    <!-- Recent Employees Onboarding -->
    <div class="lg:col-span-7 glass-dark p-6 sm:p-8 rounded-3xl">
        <div class="flex items-center justify-between mb-6">
            <h3 class="font-outfit font-bold text-lg text-white">Recent Candidates / Employees</h3>
            <a href="{{ route('admin.employees.index') }}" class="text-xs font-semibold text-purple-400 hover:text-purple-300">View All <i class="fa-solid fa-arrow-right ml-1"></i></a>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="border-b border-slate-800 text-xs font-semibold text-slate-400 uppercase tracking-wider">
                        <th class="pb-3">Employee ID</th>
                        <th class="pb-3">Name</th>
                        <th class="pb-3">Status</th>
                        <th class="pb-3">Joined Date</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-800 text-sm">
                    @forelse($recentEmployees as $emp)
                    <tr class="text-slate-300 hover:bg-slate-900/40">
                        <td class="py-3 font-semibold text-purple-400">{{ $emp->employee_id }}</td>
                        <td class="py-3">{{ $emp->full_name }}</td>
                        <td class="py-3">
                            <span class="px-2 py-0.5 rounded-md text-xs font-semibold uppercase 
                                @if($emp->status === 'active') bg-emerald-500/10 text-emerald-400 border border-emerald-500/20
                                @elseif($emp->status === 'pending_review') bg-amber-500/10 text-amber-400 border border-amber-500/20
                                @else bg-slate-800 text-slate-400
                                @endif">
                                {{ str_replace('_', ' ', $emp->status) }}
                            </span>
                        </td>
                        <td class="py-3 text-slate-400">{{ $emp->joining_date ? $emp->joining_date->format('d-M-Y') : 'N/A' }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="py-4 text-center text-slate-500">No candidates found in database.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Recent Inquiries Inbox -->
    <div class="lg:col-span-5 glass-dark p-6 sm:p-8 rounded-3xl">
        <div class="flex items-center justify-between mb-6">
            <h3 class="font-outfit font-bold text-lg text-white">Recent Website Inquiries</h3>
            <a href="{{ route('admin.inquiries.index') }}" class="text-xs font-semibold text-purple-400 hover:text-purple-300">Open Inbox <i class="fa-solid fa-arrow-right ml-1"></i></a>
        </div>

        <div class="space-y-4">
            @forelse($recentInquiries as $inq)
            <div class="p-4 rounded-xl bg-slate-950/40 border border-slate-905/10 flex items-start space-x-3">
                <div class="w-8 h-8 rounded-lg bg-pink-500/10 text-pink-400 flex items-center justify-center shrink-0 mt-0.5">
                    <i class="fa-solid fa-envelope"></i>
                </div>
                <div class="min-w-0 flex-grow">
                    <div class="flex items-center justify-between">
                        <span class="block text-sm font-semibold text-white truncate">{{ $inq->name }}</span>
                        <span class="text-xs text-slate-500 shrink-0">{{ $inq->created_at->diffForHumans() }}</span>
                    </div>
                    <span class="block text-xs text-purple-300 font-semibold truncate">{{ $inq->subject }}</span>
                    <p class="text-xs text-slate-400 mt-1 truncate">{{ $inq->message }}</p>
                </div>
            </div>
            @empty
            <p class="text-center text-slate-500 text-sm py-4">No contact messages received.</p>
            @endforelse
        </div>
    </div>
</div>
@endsection
