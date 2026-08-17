@extends('layouts.app')

@section('title', 'Candidate Online Registration - RMHRSolutions')

@section('content')
<div class="min-h-[85vh] flex flex-col justify-center items-center py-12 px-4 sm:px-6 lg:px-8">
    <div class="sm:mx-auto sm:w-full sm:max-w-lg text-center">
        <div class="inline-flex w-16 h-16 rounded-2xl bg-gradient-to-tr from-purple-500 to-pink-500 items-center justify-center text-white shadow-lg mb-4">
            <i class="fa-solid fa-user-plus text-2xl animate-pulse"></i>
        </div>
        <h2 class="font-outfit font-extrabold text-3xl tracking-tight text-white">
            Candidate Online Registration
        </h2>
        <p class="mt-2 text-sm text-slate-400">
            Submit your profile to register in our hiring database. You will receive a unique Employee ID upon submission.
        </p>
    </div>

    <div class="mt-8 sm:mx-auto sm:w-full sm:max-w-lg">
        <div class="glass-dark p-8 sm:p-10 rounded-3xl shadow-2xl border border-slate-800">
            <form action="{{ route('employee.register.submit') }}" method="POST" class="space-y-6">
                @csrf

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                    <!-- First Name -->
                    <div>
                        <label for="first_name" class="block text-xs font-semibold uppercase tracking-wider text-slate-300">
                            First Name
                        </label>
                        <div class="mt-2 relative rounded-xl shadow-sm">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-500">
                                <i class="fa-solid fa-user"></i>
                            </div>
                            <input id="first_name" name="first_name" type="text" required value="{{ old('first_name') }}"
                                class="block w-full pl-10 pr-4 py-3 bg-slate-900/60 border border-slate-700 rounded-xl text-slate-200 placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent text-sm transition-all"
                                placeholder="John">
                        </div>
                        @error('first_name')
                            <p class="mt-2 text-xs text-rose-400 font-medium">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Last Name -->
                    <div>
                        <label for="last_name" class="block text-xs font-semibold uppercase tracking-wider text-slate-300">
                            Last Name
                        </label>
                        <div class="mt-2 relative rounded-xl shadow-sm">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-500">
                                <i class="fa-solid fa-user"></i>
                            </div>
                            <input id="last_name" name="last_name" type="text" required value="{{ old('last_name') }}"
                                class="block w-full pl-10 pr-4 py-3 bg-slate-900/60 border border-slate-700 rounded-xl text-slate-200 placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent text-sm transition-all"
                                placeholder="Doe">
                        </div>
                        @error('last_name')
                            <p class="mt-2 text-xs text-rose-400 font-medium">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

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
                            placeholder="john.doe@example.com">
                    </div>
                    @error('email')
                        <p class="mt-2 text-xs text-rose-400 font-medium">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Phone -->
                <div>
                    <label for="phone" class="block text-xs font-semibold uppercase tracking-wider text-slate-300">
                        Phone Number
                    </label>
                    <div class="mt-2 relative rounded-xl shadow-sm">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-500">
                            <i class="fa-solid fa-phone"></i>
                        </div>
                        <input id="phone" name="phone" type="tel" value="{{ old('phone') }}"
                            class="block w-full pl-10 pr-4 py-3 bg-slate-900/60 border border-slate-700 rounded-xl text-slate-200 placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent text-sm transition-all"
                            placeholder="+91 98765 43210">
                    </div>
                    @error('phone')
                        <p class="mt-2 text-xs text-rose-400 font-medium">{{ $message }}</p>
                    @enderror
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                    <!-- Password -->
                    <div>
                        <label for="password" class="block text-xs font-semibold uppercase tracking-wider text-slate-300">
                            Password
                        </label>
                        <div class="mt-2 relative rounded-xl shadow-sm">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-500">
                                <i class="fa-solid fa-lock"></i>
                            </div>
                            <input id="password" name="password" type="password" required
                                class="block w-full pl-10 pr-4 py-3 bg-slate-900/60 border border-slate-700 rounded-xl text-slate-200 placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent text-sm transition-all"
                                placeholder="••••••••">
                        </div>
                        @error('password')
                            <p class="mt-2 text-xs text-rose-400 font-medium">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Confirm Password -->
                    <div>
                        <label for="password_confirmation" class="block text-xs font-semibold uppercase tracking-wider text-slate-300">
                            Confirm Password
                        </label>
                        <div class="mt-2 relative rounded-xl shadow-sm">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-500">
                                <i class="fa-solid fa-check-double"></i>
                            </div>
                            <input id="password_confirmation" name="password_confirmation" type="password" required
                                class="block w-full pl-10 pr-4 py-3 bg-slate-900/60 border border-slate-700 rounded-xl text-slate-200 placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent text-sm transition-all"
                                placeholder="••••••••">
                        </div>
                    </div>
                </div>

                <!-- Submit Button -->
                <div>
                    <button type="submit"
                        class="w-full flex justify-center py-3.5 px-4 border border-transparent rounded-xl shadow-md text-sm font-semibold text-white bg-gradient-to-r from-purple-600 to-indigo-600 hover:from-purple-500 hover:to-indigo-500 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-purple-500 shadow-purple-500/20 hover:shadow-purple-500/30 transition-all duration-300">
                        <i class="fa-solid fa-user-plus mr-2 mt-0.5"></i> Submit & Generate ID
                    </button>
                </div>
            </form>

            <div class="mt-6 pt-6 border-t border-slate-800 text-center text-sm text-slate-400">
                Already registered? 
                <a href="{{ route('login') }}" class="text-purple-400 hover:text-purple-300 font-semibold transition-colors">
                    Sign In
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
