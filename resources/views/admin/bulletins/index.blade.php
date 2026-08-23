@extends('layouts.admin')

@section('title', 'Bulletins Management - Propszy')
@section('page_title', 'Notice Board Bulletins')

@section('content')
<div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
    <!-- Left: Publish Announcement Form -->
    <div class="lg:col-span-4 glass-dark p-6 sm:p-8 rounded-3xl border border-slate-200 shadow-xl h-fit">
        <h3 class="font-outfit font-bold text-lg text-slate-800 mb-6 flex items-center">
            <i class="fa-solid fa-bullhorn text-purple-600 mr-2 text-sm"></i> Publish Announcement
        </h3>

        <form action="{{ route('admin.bulletins.store') }}" method="POST" class="space-y-6">
            @csrf

            <!-- Title -->
            <div>
                <label for="title" class="block text-xs font-semibold uppercase tracking-wider text-slate-600">Bulletin Title</label>
                <input type="text" id="title" name="title" required value="{{ old('title') }}"
                    class="mt-2 block w-full px-4 py-3 bg-white border border-slate-300 rounded-xl text-slate-800 focus:outline-none focus:ring-1 focus:ring-purple-500 text-sm"
                    placeholder="e.g. Office Holiday Notice">
                @error('title')
                    <p class="mt-1.5 text-xs text-rose-500">{{ $message }}</p>
                @enderror
            </div>

            <!-- Content -->
            <div>
                <label for="content" class="block text-xs font-semibold uppercase tracking-wider text-slate-600 mb-2">Bulletin Content</label>
                <textarea id="content" name="content" rows="6" required
                    class="block w-full px-4 py-3 bg-white border border-slate-300 rounded-xl text-slate-800 focus:outline-none focus:ring-1 focus:ring-purple-500 text-sm"
                    placeholder="Write notice details here..."></textarea>
                @error('content')
                    <p class="mt-1.5 text-xs text-rose-500">{{ $message }}</p>
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
        <h3 class="font-outfit font-bold text-lg text-slate-800 mb-4">Active Notice Board</h3>
        
        @forelse($bulletins as $bull)
        <div class="glass-dark p-6 rounded-3xl border border-slate-200 flex flex-col justify-between hover:border-slate-350 transition-all duration-200 shadow-lg">
            <div>
                <div class="flex items-center justify-between mb-3">
                    <span class="text-xs text-slate-500">C: {{ $bull->created_at ? $bull->created_at->format('d-M-Y h:i A') : 'N/A' }} | U: {{ $bull->updated_at ? $bull->updated_at->format('d-M-Y h:i A') : 'N/A' }}</span>
                    <span class="px-2 py-0.5 rounded bg-emerald-500/10 text-emerald-600 border border-emerald-500/20 text-[10px] font-bold uppercase tracking-wider">Active</span>
                </div>
                <h4 class="font-outfit font-bold text-lg text-slate-800 mb-2">{{ $bull->title }}</h4>
                <p class="text-sm text-slate-600 leading-relaxed mb-6">{{ $bull->content }}</p>
            </div>
            
            <div class="flex justify-end pt-4 border-t border-slate-200">
                <form action="{{ route('admin.bulletins.destroy', $bull) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this notice?')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="inline-flex items-center px-3 py-1.5 rounded-lg bg-slate-100 hover:bg-rose-50 border border-slate-200 hover:border-rose-200 text-xs font-semibold text-slate-650 hover:text-rose-600 transition-colors">
                        <i class="fa-solid fa-trash-can mr-1.5"></i> Delete Announcement
                    </button>
                </form>
            </div>
        </div>
        @empty
        <div class="glass-dark p-12 rounded-3xl text-center text-slate-500">
            <i class="fa-solid fa-bullethorn text-4xl mb-4 block"></i>
            Notice board is empty. Complete form to publish announcement.
        </div>
        @endforelse
    </div>
</div>
@endsection
