@extends('layouts.app')

@section('title', 'Admin Portal Login - Adhikshita Plotters')

@section('content')
<div class="min-h-[80vh] flex flex-col justify-center items-center py-12 sm:px-6 lg:px-8">
    <div class="sm:mx-auto sm:w-full sm:max-w-md text-center">
        <div class="inline-flex w-16 h-16 rounded-2xl bg-gradient-to-tr from-purple-500 to-pink-500 items-center justify-center text-white shadow-lg mb-4">
            <i class="fa-solid fa-user-gear text-2xl animate-spin-slow"></i>
        </div>
        <h2 class="font-outfit font-extrabold text-3xl tracking-tight text-white">
            Admin Portal Login
        </h2>
        <p class="mt-2 text-sm text-slate-400">
            For authorized system administrators only.
        </p>
    </div>

    <div class="mt-8 sm:mx-auto sm:w-full sm:max-w-md px-4">
        <div class="glass-dark p-8 rounded-3xl shadow-2xl border border-slate-800">
            <form action="{{ route('admin.login.submit') }}" method="POST" class="space-y-6">
                @csrf

                <!-- Email -->
                <div>
                    <label for="email" class="block text-xs font-semibold uppercase tracking-wider text-slate-300">
                        Email Address
                    </label>
                    <div class="mt-2 relative rounded-xl shadow-sm">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-500">
                            <i class="fa-solid fa-envelope"></i>
                        </div>
                        <input id="email" name="email" type="email" autocomplete="email" required value="{{ old('email') }}"
                            class="block w-full pl-10 pr-4 py-3 bg-slate-900/60 border border-slate-700 rounded-xl text-slate-200 placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent text-sm transition-all"
                            placeholder="admin@agency.com">
                    </div>
                    @error('email')
                        <p class="mt-2 text-xs text-rose-400 font-medium">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Password -->
                <div>
                    <label for="password" class="block text-xs font-semibold uppercase tracking-wider text-slate-300">
                        Password
                    </label>
                    <div class="mt-2 relative rounded-xl shadow-sm">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-500">
                            <i class="fa-solid fa-lock"></i>
                        </div>
                        <input id="password" name="password" type="password" autocomplete="current-password" required
                            class="block w-full pl-10 pr-4 py-3 bg-slate-900/60 border border-slate-700 rounded-xl text-slate-200 placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent text-sm transition-all"
                            placeholder="••••••••">
                    </div>
                    @error('password')
                        <p class="mt-2 text-xs text-rose-400 font-medium">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Remember me -->
                <div class="flex items-center justify-between">
                    <div class="flex items-center">
                        <input id="remember" name="remember" type="checkbox"
                            class="h-4 w-4 rounded bg-slate-900 border-slate-700 text-purple-600 focus:ring-purple-500 focus:ring-offset-slate-900">
                        <label for="remember" class="ml-2 block text-sm text-slate-300">
                            Remember me
                        </label>
                    </div>
                </div>

                <!-- Submit Button -->
                <div>
                    <button type="submit"
                        class="w-full flex justify-center py-3 px-4 border border-transparent rounded-xl shadow-md text-sm font-semibold text-white bg-gradient-to-r from-purple-600 to-indigo-600 hover:from-purple-500 hover:to-indigo-500 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-purple-500 shadow-purple-500/20 hover:shadow-purple-500/30 transition-all duration-300">
                        <i class="fa-solid fa-arrow-right-to-bracket mr-2 mt-0.5"></i> Sign In to Dashboard
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
