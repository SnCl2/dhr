@extends('layouts.admin')

@section('title', 'Inquiries Inbox - Propszy')
@section('page_title', 'Contact Us Inquiries')

@section('content')
<div class="glass-dark rounded-3xl overflow-hidden shadow-xl mb-8">
    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="border-b border-slate-850 bg-slate-900/40 text-xs font-semibold text-slate-400 uppercase tracking-wider">
                    <th class="p-6">Sender Details</th>
                    <th class="p-6">Subject & Message</th>
                    <th class="p-6">Status</th>
                    <th class="p-6 text-right">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-850 text-sm">
                @forelse($inquiries as $inq)
                <tr class="text-slate-300 hover:bg-slate-900/20">
                    <td class="p-6">
                        <span class="block font-semibold text-white">{{ $inq->name }}</span>
                        <span class="block text-xs text-purple-400 mt-0.5">{{ $inq->email }}</span>
                        @if($inq->phone)
                            <span class="block text-xs text-slate-500 mt-0.5">{{ $inq->phone }}</span>
                        @endif
                        <span class="block text-[10px] text-slate-500 mt-1">Received: {{ $inq->created_at->format('d-M-Y h:i A') }}</span>
                    </td>
                    <td class="p-6 max-w-md">
                        <span class="block font-semibold text-slate-200 mb-1.5">{{ $inq->subject }}</span>
                        <p class="text-xs text-slate-400 leading-relaxed whitespace-pre-line">{{ $inq->message }}</p>
                    </td>
                    <td class="p-6">
                        <span class="px-2 py-0.5 rounded-lg text-xs font-semibold uppercase tracking-wider 
                            @if($inq->status === 'unread') bg-pink-500/10 text-pink-400 border border-pink-500/20
                            @elseif($inq->status === 'replied') bg-emerald-500/10 text-emerald-400 border border-emerald-500/20
                            @else bg-slate-800 text-slate-400 border border-slate-750
                            @endif">
                            {{ $inq->status }}
                        </span>
                    </td>
                    <td class="p-6 text-right">
                        @if($inq->status === 'unread')
                        <form action="{{ route('admin.inquiries.reply', $inq) }}" method="POST" class="inline">
                            @csrf
                            <button type="submit" class="px-4 py-2 bg-slate-900 border border-slate-800 hover:border-slate-700 text-xs font-semibold text-purple-400 hover:text-white rounded-xl transition-all">
                                <i class="fa-solid fa-check mr-1.5"></i> Mark Replied
                            </button>
                        </form>
                        @else
                            <span class="text-xs text-slate-500"><i class="fa-solid fa-circle-check text-emerald-500 mr-1.5"></i> Resolved</span>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" class="p-12 text-center text-slate-500 text-base">
                        <i class="fa-solid fa-envelope-open text-3xl mb-4 block"></i>
                        No contact inquiries found in database.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    @if($inquiries->hasPages())
    <div class="px-6 py-4 border-t border-slate-850">
        {{ $inquiries->links() }}
    </div>
    @endif
</div>
@endsection
