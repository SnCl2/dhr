@extends('layouts.admin')

@section('title', 'Offer Templates - Propszy')
@section('page_title', 'Offer Letter Templates')

@section('content')
<div class="flex justify-between items-center mb-8">
    <p class="text-sm text-slate-400">Configure templates for both internal and external staff hiring.</p>
    <a href="{{ route('admin.templates.create') }}" class="px-5 py-2.5 bg-gradient-to-r from-purple-600 to-indigo-600 hover:from-purple-500 hover:to-indigo-500 text-white rounded-xl text-sm font-semibold shadow-lg shadow-purple-500/10 transition-all duration-300">
        <i class="fa-solid fa-plus mr-2"></i>Create New Template
    </a>
</div>

<div class="grid grid-cols-1 md:grid-cols-2 gap-8">
    @forelse($templates as $tmpl)
    <div class="glass-dark p-6 sm:p-8 rounded-3xl border border-slate-850 flex flex-col justify-between hover:border-purple-500/30 transition-all duration-300">
        <div>
            <div class="flex items-center justify-between mb-4">
                <span class="px-2.5 py-1 rounded-lg text-xs font-semibold uppercase tracking-wider
                    @if($tmpl->type === 'internal') bg-purple-500/10 text-purple-400 border border-purple-500/20
                    @else bg-pink-500/10 text-pink-400 border border-pink-500/20
                    @endif">
                    {{ strtoupper($tmpl->type) }}
                </span>
                <span class="text-xs text-slate-500">Template ID: #{{ $tmpl->id }}</span>
            </div>

            <h3 class="font-outfit font-bold text-xl text-white mb-2">{{ $tmpl->name }}</h3>
            <span class="block text-xs font-semibold text-purple-400 mb-4 truncate">Subject: {{ $tmpl->subject }}</span>
            
            <div class="bg-slate-950/40 p-4 rounded-xl border border-slate-900 text-xs text-slate-400 leading-relaxed font-mono max-h-32 overflow-y-auto whitespace-pre-line mb-6">
                {{ $tmpl->content }}
            </div>
        </div>

        <div class="flex items-center justify-between pt-4 border-t border-slate-850">
            <span class="text-xs text-slate-500">Last updated: {{ $tmpl->updated_at->diffForHumans() }}</span>
            <div class="space-x-2">
                <a href="{{ route('admin.templates.edit', $tmpl) }}" class="inline-flex items-center px-3 py-1.5 rounded-lg bg-slate-900 border border-slate-800 text-xs font-semibold text-slate-300 hover:text-white hover:border-slate-700 transition-colors">
                    <i class="fa-solid fa-pen mr-1 text-xs"></i> Edit
                </a>
                <form action="{{ route('admin.templates.destroy', $tmpl) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure you want to delete this template?')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="inline-flex items-center px-3 py-1.5 rounded-lg bg-slate-900 border border-slate-800 text-xs font-semibold text-slate-400 hover:text-rose-400 hover:border-rose-950 transition-colors">
                        <i class="fa-solid fa-trash-can mr-1 text-xs"></i> Delete
                    </button>
                </form>
            </div>
        </div>
    </div>
    @empty
    <div class="col-span-2 glass-dark p-12 rounded-3xl text-center text-slate-500">
        <i class="fa-solid fa-folder-open text-4xl mb-4 block"></i>
        No templates configured yet. Click "Create New Template" above.
    </div>
    @endforelse
</div>
@endsection
