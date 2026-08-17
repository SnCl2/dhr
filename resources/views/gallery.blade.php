@extends('layouts.app')

@section('title', 'Visual Showcase - RM HR Solutions')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16 sm:py-24">
    <!-- Header -->
    <div class="text-center max-w-3xl mx-auto mb-16 space-y-3">
        <span class="text-xs font-bold uppercase tracking-widest text-indigo-600 bg-indigo-50 px-3 py-1.5 rounded-full border border-indigo-100">Portfolio</span>
        <h1 class="font-outfit font-black text-4xl sm:text-5xl text-slate-850">
            Our Operations Gallery
        </h1>
        <p class="text-sm text-slate-500 max-w-xl mx-auto">
            A visual representation of our manpower recruitment training operations, corporate team, and workspace placements.
        </p>
    </div>

    <!-- Gallery Grid -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8">
        @foreach($images as $img)
        <div class="bg-white rounded-3xl overflow-hidden border border-slate-200 shadow-sm group hover:border-indigo-400/50 transition-all duration-300">
            <div class="h-64 overflow-hidden relative">
                <!-- Overlay -->
                <div class="absolute inset-0 bg-slate-900/40 opacity-0 group-hover:opacity-100 transition-opacity duration-300 z-10 flex items-center justify-center">
                    <span class="px-4 py-2 rounded-xl bg-indigo-600 text-white text-xs font-bold shadow-lg">View Workspace</span>
                </div>
                <img src="{{ $img['url'] }}" alt="{{ $img['title'] }}" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">
            </div>
            <div class="p-6 space-y-3">
                <span class="px-2.5 py-1 rounded-lg bg-indigo-50 text-indigo-700 text-xxs font-bold uppercase tracking-wider">
                    {{ $img['category'] }}
                </span>
                <h3 class="font-outfit font-bold text-base text-slate-850 mt-1">{{ $img['title'] }}</h3>
            </div>
        </div>
        @endforeach
    </div>
</div>
@endsection
