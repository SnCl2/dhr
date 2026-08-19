@extends('layouts.employee')

@section('title', 'Company Bulletins - RM HR Solutions')
@section('page_title', 'Notices & Announcements')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">
    <p class="text-sm text-slate-555">Keep updated with the latest notices, policies, and schedules published by the Administration.</p>
    
    @forelse($bulletins as $bull)
    <div class="bg-white p-6 sm:p-8 rounded-3xl border border-slate-200 shadow-sm hover:border-blue-300 transition-all duration-200">
        <div class="flex items-center justify-between mb-4">
            <span class="text-xs text-slate-450"><i class="fa-solid fa-clock mr-1.5"></i>Published: {{ $bull->created_at->format('d-M-Y h:i A') }}</span>
            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full bg-blue-50 text-blue-700 border border-blue-100 text-[10px] font-bold uppercase tracking-wider">Announcement</span>
        </div>
        <h3 class="font-outfit font-bold text-xl text-slate-800 mb-3">{{ $bull->title }}</h3>
        <p class="text-sm text-slate-650 leading-relaxed whitespace-pre-line">{{ $bull->content }}</p>
    </div>
    @empty
    <div class="bg-white p-12 rounded-3xl text-center text-slate-400 border border-slate-200 shadow-sm">
        <i class="fa-solid fa-bullhorn text-4xl mb-4 block text-slate-300"></i>
        Notice board is empty. There are no announcements.
    </div>
    @endforelse
</div>
@endsection
