@extends('layouts.app')

@section('title', 'Change Password - RMHRSolutions')

@section('content')
<div class="min-h-[75vh] flex flex-col justify-center items-center py-12 px-4 sm:px-6 lg:px-8">
    <div class="sm:mx-auto sm:w-full sm:max-w-md text-center">
        <div class="inline-flex w-16 h-16 rounded-2xl bg-gradient-to-tr from-amber-500 to-orange-500 items-center justify-center text-white shadow-lg mb-4">
            <i class="fa-solid fa-key text-2xl"></i>
        </div>
        <h2 class="font-outfit font-extrabold text-3xl tracking-tight text-white">
            Secure Your Account
        </h2>
        <p class="mt-2 text-sm text-slate-400">
            This is your first login. You must customize your password to continue.
        </p>
    </div>

    <div class="mt-8 sm:mx-auto sm:w-full sm:max-w-md">
        <div class="glass-dark p-8 rounded-3xl shadow-2xl border border-slate-800">
            <form action="{{ route('employee.password.change.update') }}" method="POST" class="space-y-6">
                @csrf

                <!-- New Password -->
                <div>
                    <label for="password" class="block text-xs font-semibold uppercase tracking-wider text-slate-300">
                        New Password
                    </label>
                    <div class="mt-2 relative rounded-xl shadow-sm">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-500">
                            <i class="fa-solid fa-lock"></i>
                        </div>
                        <input id="password" name="password" type="password" required
                            class="block w-full pl-10 pr-4 py-3 bg-slate-900/60 border border-slate-700 rounded-xl text-slate-200 placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent text-sm transition-all"
                            placeholder="Min. 8 characters">
                    </div>
                    @error('password')
                        <p class="mt-2 text-xs text-rose-400 font-medium">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Confirm Password -->
                <div>
                    <label for="password_confirmation" class="block text-xs font-semibold uppercase tracking-wider text-slate-300">
                        Confirm New Password
                    </label>
                    <div class="mt-2 relative rounded-xl shadow-sm">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-500">
                            <i class="fa-solid fa-circle-check"></i>
                        </div>
                        <input id="password_confirmation" name="password_confirmation" type="password" required
                            class="block w-full pl-10 pr-4 py-3 bg-slate-900/60 border border-slate-700 rounded-xl text-slate-200 placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent text-sm transition-all"
                            placeholder="Confirm password">
                    </div>
                </div>

                <!-- Submit Button -->
                <div>
                    <button type="submit"
                        class="w-full flex justify-center py-3 px-4 border border-transparent rounded-xl shadow-md text-sm font-semibold text-white bg-gradient-to-r from-amber-600 to-orange-600 hover:from-amber-500 hover:to-orange-500 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-amber-500 shadow-amber-500/20 hover:shadow-amber-500/30 transition-all duration-300">
                        <i class="fa-solid fa-save mr-2 mt-0.5"></i> Update Password & Proceed
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
