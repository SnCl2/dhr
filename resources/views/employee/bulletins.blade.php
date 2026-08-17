@extends('layouts.employee')

@section('title', 'Company Bulletins - Adhikshita Plotters')
@section('page_title', 'Notices & Announcements')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">
    <p class="text-sm text-slate-400">Keep updated with the latest notices, policies, and schedules published by the Administration.</p>
    
    @forelse($bulletins as $bull)
    <div class="glass-dark p-6 sm:p-8 rounded-3xl border border-slate-850 hover:border-slate-800 transition-all duration-200">
        <div class="flex items-center justify-between mb-4">
            <span class="text-xs text-slate-500"><i class="fa-solid fa-clock mr-1.5"></i>Published: {{ $bull->created_at->format('d-M-Y h:i A') }}</span>
            <span class="inline-flex items-center px-2 py-0.5 rounded bg-purple-500/10 text-purple-400 border border-purple-500/20 text-[10px] font-bold uppercase tracking-wider">Announcement</span>
        </div>
        <h3 class="font-outfit font-bold text-xl text-white mb-3">{{ $bull->title }}</h3>
        <p class="text-sm text-slate-300 leading-relaxed whitespace-pre-line">{{ $bull->content }}</p>
    </div>
    @empty
    <div class="glass-dark p-12 rounded-3xl text-center text-slate-500">
        <i class="fa-solid fa-bullhorn text-4xl mb-4 block"></i>
        Notice board is empty. There are no announcements.
    </div>
    @endforelse
</div>
@endsection
