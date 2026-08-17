@extends('layouts.admin')

@section('title', 'Bulletins Management - Propszy')
@section('page_title', 'Notice Board Bulletins')

@section('content')
<div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
    <!-- Left: Publish Announcement Form -->
    <div class="lg:col-span-4 glass-dark p-6 sm:p-8 rounded-3xl border border-slate-850 shadow-2xl h-fit">
        <h3 class="font-outfit font-bold text-lg text-white mb-6 flex items-center">
            <i class="fa-solid fa-bullhorn text-purple-400 mr-2 text-sm"></i> Publish Announcement
        </h3>

        <form action="{{ route('admin.bulletins.store') }}" method="POST" class="space-y-6">
            @csrf

            <!-- Title -->
            <div>
                <label for="title" class="block text-xs font-semibold uppercase tracking-wider text-slate-400">Bulletin Title</label>
                <input type="text" id="title" name="title" required value="{{ old('title') }}"
                    class="mt-2 block w-full px-4 py-3 bg-slate-950/60 border border-slate-800 rounded-xl text-slate-200 focus:outline-none focus:ring-1 focus:ring-purple-500 text-sm"
                    placeholder="e.g. Office Holiday Notice">
                @error('title')
                    <p class="mt-1.5 text-xs text-rose-400">{{ $message }}</p>
                @enderror
            </div>

            <!-- Content -->
            <div>
                <label for="content" class="block text-xs font-semibold uppercase tracking-wider text-slate-400 mb-2">Bulletin Content</label>
                <textarea id="content" name="content" rows="6" required
                    class="block w-full px-4 py-3 bg-slate-950/60 border border-slate-800 rounded-xl text-slate-200 focus:outline-none focus:ring-1 focus:ring-purple-500 text-sm"
                    placeholder="Write notice details here..."></textarea>
                @error('content')
                    <p class="mt-1.5 text-xs text-rose-400">{{ $message }}</p>
                @enderror
            </div>

            <button type="submit"
                class="w-full flex justify-center py-3.5 px-4 border border-transparent rounded-xl shadow-md text-sm font-semibold text-white bg-gradient-to-r from-purple-600 to-indigo-600 hover:from-purple-500 hover:to-indigo-500 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-purple-500 shadow-purple-500/20 transition-all duration-300">
                <i class="fa-solid fa-paper-plane mr-2 mt-0.5"></i> Publish Bulletin
            </button>
        </form>
    </div>

    <!-- Right: Active Notice list -->
    <div class="lg:col-span-8 space-y-6">
        <h3 class="font-outfit font-bold text-lg text-white mb-4">Active Notice Board</h3>
        
        @forelse($bulletins as $bull)
        <div class="glass-dark p-6 rounded-3xl border border-slate-850 flex flex-col justify-between hover:border-slate-800 transition-all duration-200">
            <div>
                <div class="flex items-center justify-between mb-3">
                    <span class="text-xs text-slate-500">Published: {{ $bull->created_at->format('d-M-Y h:i A') }}</span>
                    <span class="px-2 py-0.5 rounded bg-emerald-500/10 text-emerald-400 border border-emerald-500/20 text-[10px] font-bold uppercase tracking-wider">Active</span>
                </div>
                <h4 class="font-outfit font-bold text-lg text-white mb-2">{{ $bull->title }}</h4>
                <p class="text-sm text-slate-400 leading-relaxed mb-6">{{ $bull->content }}</p>
            </div>
            
            <div class="flex justify-end pt-4 border-t border-slate-850">
                <form action="{{ route('admin.bulletins.destroy', $bull) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this notice?')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="inline-flex items-center px-3 py-1.5 rounded-lg bg-slate-900 border border-slate-800 text-xs font-semibold text-slate-400 hover:text-rose-450 hover:border-rose-950 transition-colors">
                        <i class="fa-solid fa-trash-can mr-1.5"></i> Delete Announcement
                    </button>
                </form>
            </div>
        </div>
        @empty
        <div class="glass-dark p-12 rounded-3xl text-center text-slate-500">
            <i class="fa-solid fa-bullhorn text-4xl mb-4 block"></i>
            Notice board is empty. Complete form to publish announcement.
        </div>
        @endforelse
    </div>
</div>
@endsection
