@extends('layouts.app')

@section('title', 'Staff Portal Login - RM HR Solutions')

@section('content')
<div class="min-h-[80vh] flex flex-col justify-center items-center py-12 sm:px-6 lg:px-8">
    <div class="sm:mx-auto sm:w-full sm:max-w-md text-center space-y-3">
        <div class="inline-flex w-16 h-16 rounded-2xl bg-gradient-to-tr from-purple-600 to-indigo-600 items-center justify-center text-white shadow-lg shadow-purple-600/25 mb-2">
            <i class="fa-solid fa-briefcase text-2xl"></i>
        </div>
        <h2 class="font-outfit font-black text-3xl tracking-tight text-slate-850">
            Management Staff Login
        </h2>
        <p class="text-xs text-slate-500 max-w-xs mx-auto">
            Authorized management staff members only.
        </p>
    </div>

    <div class="mt-8 sm:mx-auto sm:w-full sm:max-w-md px-4">
        <div class="bg-white p-8 rounded-3xl border border-slate-200 shadow-sm space-y-6">
            @if(session('success'))
                <div class="p-3.5 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-700 text-xs font-semibold">
                    {{ session('success') }}
                </div>
            @endif

            @if(session('warning'))
                <div class="p-3.5 rounded-xl bg-amber-50 border border-amber-200 text-amber-700 text-xs font-semibold">
                    {{ session('warning') }}
                </div>
            @endif

            <form action="{{ route('staff.login.submit') }}" method="POST" class="space-y-6">
                @csrf

                <!-- Email -->
                <div class="space-y-2">
                    <label for="email" class="block text-xs font-bold uppercase tracking-wider text-slate-600">
                        Email Address
                    </label>
                    <div class="relative rounded-xl shadow-sm">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400">
                            <i class="fa-solid fa-envelope text-sm"></i>
                        </div>
                        <input id="email" name="email" type="email" autocomplete="email" required value="{{ old('email') }}"
                            class="block w-full pl-10 pr-4 py-3 bg-white border border-slate-300 rounded-xl text-slate-800 placeholder-slate-400 focus:outline-none focus:ring-1 focus:ring-indigo-500 text-sm transition-all"
                            placeholder="staff@agency.com">
                    </div>
                    @error('email')
                        <p class="mt-2 text-xs text-rose-500 font-medium">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Password -->
                <div class="space-y-2">
                    <label for="password" class="block text-xs font-bold uppercase tracking-wider text-slate-600">
                        Password
                    </label>
                    <div class="relative rounded-xl shadow-sm">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400">
                            <i class="fa-solid fa-lock text-sm"></i>
                        </div>
                        <input id="password" name="password" type="password" autocomplete="current-password" required
                            class="block w-full pl-10 pr-4 py-3 bg-white border border-slate-300 rounded-xl text-slate-800 placeholder-slate-400 focus:outline-none focus:ring-1 focus:ring-indigo-500 text-sm transition-all"
                            placeholder="••••••••">
                    </div>
                    @error('password')
                        <p class="mt-2 text-xs text-rose-500 font-medium">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Remember me -->
                <div class="flex items-center justify-between">
                    <div class="flex items-center">
                        <input id="remember" name="remember" type="checkbox"
                            class="h-4 w-4 rounded border-slate-300 text-indigo-600 focus:ring-indigo-500">
                        <label for="remember" class="ml-2 block text-xs font-semibold text-slate-600">
                            Remember me
                        </label>
                    </div>
                </div>

                <!-- Submit Button -->
                <div>
                    <button type="submit"
                        class="w-full flex justify-center py-3.5 px-4 border border-transparent rounded-xl shadow-md text-sm font-semibold text-white bg-gradient-to-r from-purple-600 to-indigo-600 hover:from-purple-500 hover:to-indigo-500 focus:outline-none focus:ring-1 focus:ring-indigo-500 shadow-purple-500/20 transition-all duration-300">
                        <i class="fa-solid fa-arrow-right-to-bracket mr-2 mt-0.5"></i> Sign In to Staff Panel
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
