@extends('layouts.app')

@section('title', 'Visual Showcase - Propszy Manpower Solutions')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16 sm:py-24">
    <!-- Header -->
    <div class="text-center max-w-3xl mx-auto mb-16">
        <span class="text-xs font-semibold uppercase tracking-wider text-purple-400 bg-purple-500/10 px-3 py-1 rounded-full border border-purple-500/20">Portfolio</span>
        <h1 class="font-outfit font-extrabold text-4xl sm:text-5xl text-white mt-4">
            Our Operations Gallery
        </h1>
        <p class="mt-4 text-slate-400">
            A visual representation of our manpower recruitment training operations, corporate team, and workspace placements.
        </p>
    </div>

    <!-- Gallery Grid -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8">
        @foreach($images as $img)
        <div class="glass-dark rounded-3xl overflow-hidden border border-slate-800 group hover:border-purple-500/30 transition-all duration-300">
            <div class="h-64 overflow-hidden relative">
                <!-- Overlay -->
                <div class="absolute inset-0 bg-slate-950/40 opacity-0 group-hover:opacity-100 transition-opacity duration-300 z-10 flex items-center justify-center">
                    <span class="px-4 py-2 rounded-xl bg-purple-600 text-white text-xs font-bold shadow-lg">View Workspace</span>
                </div>
                <img src="{{ $img['url'] }}" alt="{{ $img['title'] }}" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">
            </div>
            <div class="p-6">
                <span class="px-2.5 py-1 rounded-lg bg-slate-900 text-purple-400 text-xs font-semibold uppercase tracking-wider">
                    {{ $img['category'] }}
                </span>
                <h3 class="font-outfit font-bold text-lg text-white mt-3">{{ $img['title'] }}</h3>
            </div>
        </div>
        @endforeach
    </div>
</div>
@endsection
