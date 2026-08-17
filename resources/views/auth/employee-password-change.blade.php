@extends('layouts.app')

@section('title', 'Change Password - RM HR Solutions')

@section('content')
<div class="min-h-[75vh] flex flex-col justify-center items-center py-12 px-4 sm:px-6 lg:px-8">
    <div class="sm:mx-auto sm:w-full sm:max-w-md text-center space-y-3">
        <div class="inline-flex w-16 h-16 rounded-2xl bg-gradient-to-tr from-amber-500 to-orange-500 items-center justify-center text-white shadow-lg shadow-amber-500/25 mb-2">
            <i class="fa-solid fa-key text-2xl"></i>
        </div>
        <h2 class="font-outfit font-black text-3xl tracking-tight text-slate-850">
            Secure Your Account
        </h2>
        <p class="text-xs text-slate-500 max-w-xs mx-auto">
            This is your first login. You must customize your password to continue.
        </p>
    </div>

    <div class="mt-8 sm:mx-auto sm:w-full sm:max-w-md">
        <div class="bg-white p-8 rounded-3xl border border-slate-200 shadow-sm space-y-6">
            <form action="{{ route('employee.password.change.update') }}" method="POST" class="space-y-6">
                @csrf

                <!-- New Password -->
                <div class="space-y-2">
                    <label for="password" class="block text-xs font-bold uppercase tracking-wider text-slate-600">
                        New Password
                    </label>
                    <div class="relative rounded-xl shadow-sm">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400">
                            <i class="fa-solid fa-lock text-sm"></i>
                        </div>
                        <input id="password" name="password" type="password" required
                            class="block w-full pl-10 pr-4 py-3 bg-white border border-slate-300 rounded-xl text-slate-800 placeholder-slate-400 focus:outline-none focus:ring-1 focus:ring-indigo-500 text-sm transition-all"
                            placeholder="Min. 8 characters">
                    </div>
                    @error('password')
                        <p class="mt-2 text-xs text-rose-500 font-medium">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Confirm Password -->
                <div class="space-y-2">
                    <label for="password_confirmation" class="block text-xs font-bold uppercase tracking-wider text-slate-600">
                        Confirm New Password
                    </label>
                    <div class="relative rounded-xl shadow-sm">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400">
                            <i class="fa-solid fa-circle-check text-sm"></i>
                        </div>
                        <input id="password_confirmation" name="password_confirmation" type="password" required
                            class="block w-full pl-10 pr-4 py-3 bg-white border border-slate-300 rounded-xl text-slate-800 placeholder-slate-400 focus:outline-none focus:ring-1 focus:ring-indigo-500 text-sm transition-all"
                            placeholder="Confirm password">
                    </div>
                </div>

                <!-- Submit Button -->
                <div>
                    <button type="submit"
                        class="w-full flex justify-center py-3.5 px-4 border border-transparent rounded-xl shadow-md text-sm font-semibold text-white bg-gradient-to-r from-amber-600 to-orange-600 hover:from-amber-500 hover:to-orange-500 focus:outline-none focus:ring-1 focus:ring-amber-500 shadow-amber-500/25 transition-all duration-300">
                        <i class="fa-solid fa-save mr-2 mt-0.5"></i> Update Password & Proceed
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
