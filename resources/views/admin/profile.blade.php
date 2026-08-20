@php
    $isStaff = Auth::guard('staff')->check();
@endphp
@extends('layouts.admin')

@section('title', ($isStaff ? 'Staff' : 'Admin') . ' Profile & Security - RM HR Solutions')
@section('page_title', ($isStaff ? 'Staff' : 'Admin') . ' Account & Security Settings')

@section('content')
<div class="max-w-5xl mx-auto space-y-8">
    
    <!-- Profile Header Hero -->
    <div class="p-6 sm:p-8 rounded-3xl bg-gradient-to-r from-slate-900 via-purple-950 to-indigo-950 text-white shadow-2xl relative overflow-hidden">
        <div class="absolute -right-10 -bottom-10 w-64 h-64 bg-purple-500/10 rounded-full blur-3xl pointer-events-none"></div>
        <div class="relative z-10 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-6">
            <div class="flex items-center space-x-4">
                <div class="w-16 h-16 rounded-2xl bg-gradient-to-tr from-purple-500 to-indigo-500 flex items-center justify-center text-2xl font-bold shadow-lg shadow-purple-500/30">
                    <i class="fa-solid fa-shield-halved"></i>
                </div>
                <div>
                    <div class="flex items-center space-x-2">
                        <h2 class="font-outfit font-extrabold text-2xl tracking-wide">{{ $admin->name }}</h2>
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-emerald-500/20 text-emerald-300 border border-emerald-500/30">
                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-400 mr-1.5 animate-pulse"></span> {{ $isStaff ? 'Staff Member' : 'Super Admin' }}
                        </span>
                    </div>
                    <p class="text-xs text-slate-300 mt-1 flex items-center space-x-2">
                        <i class="fa-solid fa-envelope text-slate-400"></i>
                        <span>{{ $admin->email }}</span>
                    </p>
                </div>
            </div>
            <div class="bg-white/10 backdrop-blur-md px-4 py-2.5 rounded-2xl border border-white/10 text-xs">
                <span class="text-slate-300 block">System Guard:</span>
                <span class="font-semibold text-white font-mono">{{ $isStaff ? 'staff' : 'admin' }} (Session Protected)</span>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        
        <!-- 1. Change Admin ID / Name & Email -->
        <div class="glass-dark p-6 sm:p-8 rounded-3xl border border-slate-200 shadow-xl space-y-6 flex flex-col justify-between">
            <div>
                <div class="flex items-center space-x-3 border-b border-slate-100 pb-4 mb-6">
                    <div class="w-10 h-10 rounded-xl bg-purple-50 text-purple-600 flex items-center justify-center text-lg shadow-xs">
                        <i class="fa-solid fa-user-pen"></i>
                    </div>
                    <div>
                        <h3 class="font-outfit font-bold text-lg text-slate-900">{{ $isStaff ? 'Staff' : 'Admin' }} Identity & Login ID</h3>
                        <p class="text-xs text-slate-500">Update your official identity profile name and login email ID.</p>
                    </div>
                </div>

                <form action="{{ route('admin.profile.update') }}" method="POST" class="space-y-5">
                    @csrf
                    @method('PUT')

                    <!-- Full Name -->
                    <div>
                        <label for="name" class="block text-xs font-semibold uppercase tracking-wider text-slate-600">{{ $isStaff ? 'Staff' : 'Administrator' }} Full Name *</label>
                        <div class="mt-1.5 relative rounded-xl shadow-xs">
                            <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                                <i class="fa-solid fa-user text-sm"></i>
                            </div>
                            <input type="text" id="name" name="name" required value="{{ old('name', $admin->name) }}"
                                class="block w-full pl-10 pr-4 py-3 bg-white border border-slate-300 rounded-xl text-slate-900 focus:outline-none focus:ring-1 focus:ring-purple-500 text-sm font-medium @error('name') border-rose-500 @enderror"
                                placeholder="{{ $isStaff ? 'e.g. John Doe' : 'e.g. Master Administrator' }}">
                        </div>
                        @error('name')
                            <p class="mt-1.5 text-xs text-rose-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Login Email -->
                    <div>
                        <label for="email" class="block text-xs font-semibold uppercase tracking-wider text-slate-600">{{ $isStaff ? 'Staff' : 'Admin' }} Login Email (ID) *</label>
                        <div class="mt-1.5 relative rounded-xl shadow-xs">
                            <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                                <i class="fa-solid fa-at text-sm"></i>
                            </div>
                            <input type="email" id="email" name="email" required value="{{ old('email', $admin->email) }}"
                                class="block w-full pl-10 pr-4 py-3 bg-white border border-slate-300 rounded-xl text-slate-900 focus:outline-none focus:ring-1 focus:ring-purple-500 text-sm font-medium @error('email') border-rose-500 @enderror"
                                placeholder="email@example.com">
                        </div>
                        @error('email')
                            <p class="mt-1.5 text-xs text-rose-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="pt-4 border-t border-slate-100 flex justify-end">
                        <button type="submit"
                            class="px-6 py-3 bg-gradient-to-r from-purple-600 to-indigo-600 hover:from-purple-500 hover:to-indigo-500 text-white rounded-xl text-xs font-semibold transition-all shadow-lg shadow-purple-500/10">
                            <i class="fa-solid fa-floppy-disk mr-1.5"></i> Update {{ $isStaff ? 'Staff' : 'Admin' }} Identity
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- 2. Change Admin Password -->
        <div class="glass-dark p-6 sm:p-8 rounded-3xl border border-slate-200 shadow-xl space-y-6 flex flex-col justify-between">
            <div>
                <div class="flex items-center space-x-3 border-b border-slate-100 pb-4 mb-6">
                    <div class="w-10 h-10 rounded-xl bg-rose-50 text-rose-600 flex items-center justify-center text-lg shadow-xs">
                        <i class="fa-solid fa-key"></i>
                    </div>
                    <div>
                        <h3 class="font-outfit font-bold text-lg text-slate-900">Change {{ $isStaff ? 'Staff' : 'Admin' }} Password</h3>
                        <p class="text-xs text-slate-500">Provide your current password to set a new secure password.</p>
                    </div>
                </div>

                <form action="{{ route('admin.profile.password') }}" method="POST" class="space-y-5">
                    @csrf
                    @method('PUT')

                    <!-- Current Password -->
                    <div>
                        <label for="current_password" class="block text-xs font-semibold uppercase tracking-wider text-slate-600">Current Password *</label>
                        <div class="mt-1.5 relative rounded-xl shadow-xs">
                            <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                                <i class="fa-solid fa-lock text-sm"></i>
                            </div>
                            <input type="password" id="current_password" name="current_password" required
                                class="block w-full pl-10 pr-10 py-3 bg-white border border-slate-300 rounded-xl text-slate-900 focus:outline-none focus:ring-1 focus:ring-purple-500 text-sm font-medium @error('current_password') border-rose-500 @enderror"
                                placeholder="Enter existing password">
                            <button type="button" onclick="togglePasswordVisibility('current_password', 'eye-curr')" class="absolute inset-y-0 right-0 pr-3 flex items-center text-slate-400 hover:text-slate-600">
                                <i id="eye-curr" class="fa-solid fa-eye text-xs"></i>
                            </button>
                        </div>
                        @error('current_password')
                            <p class="mt-1.5 text-xs text-rose-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- New Password -->
                    <div>
                        <label for="new_password" class="block text-xs font-semibold uppercase tracking-wider text-slate-600">New Password (Min. 8 Chars) *</label>
                        <div class="mt-1.5 relative rounded-xl shadow-xs">
                            <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                                <i class="fa-solid fa-shield text-sm"></i>
                            </div>
                            <input type="password" id="new_password" name="new_password" required minlength="8"
                                class="block w-full pl-10 pr-10 py-3 bg-white border border-slate-300 rounded-xl text-slate-900 focus:outline-none focus:ring-1 focus:ring-purple-500 text-sm font-medium @error('new_password') border-rose-500 @enderror"
                                placeholder="Enter strong new password">
                            <button type="button" onclick="togglePasswordVisibility('new_password', 'eye-new')" class="absolute inset-y-0 right-0 pr-3 flex items-center text-slate-400 hover:text-slate-600">
                                <i id="eye-new" class="fa-solid fa-eye text-xs"></i>
                            </button>
                        </div>
                        @error('new_password')
                            <p class="mt-1.5 text-xs text-rose-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Confirm New Password -->
                    <div>
                        <label for="new_password_confirmation" class="block text-xs font-semibold uppercase tracking-wider text-slate-600">Confirm New Password *</label>
                        <div class="mt-1.5 relative rounded-xl shadow-xs">
                            <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                                <i class="fa-solid fa-circle-check text-sm"></i>
                            </div>
                            <input type="password" id="new_password_confirmation" name="new_password_confirmation" required minlength="8"
                                class="block w-full pl-10 pr-10 py-3 bg-white border border-slate-300 rounded-xl text-slate-900 focus:outline-none focus:ring-1 focus:ring-purple-500 text-sm font-medium"
                                placeholder="Re-type new password">
                            <button type="button" onclick="togglePasswordVisibility('new_password_confirmation', 'eye-conf')" class="absolute inset-y-0 right-0 pr-3 flex items-center text-slate-400 hover:text-slate-600">
                                <i id="eye-conf" class="fa-solid fa-eye text-xs"></i>
                            </button>
                        </div>
                    </div>

                    <div class="pt-4 border-t border-slate-100 flex justify-end">
                        <button type="submit"
                            class="px-6 py-3 bg-gradient-to-r from-rose-600 to-pink-600 hover:from-rose-500 hover:to-pink-500 text-white rounded-xl text-xs font-semibold transition-all shadow-lg shadow-rose-500/10">
                            <i class="fa-solid fa-lock mr-1.5"></i> Update Security Password
                        </button>
                    </div>
                </form>
            </div>
        </div>

    </div>
</div>
@endsection

@section('scripts')
<script>
    function togglePasswordVisibility(inputId, iconId) {
        const input = document.getElementById(inputId);
        const icon = document.getElementById(iconId);
        if (input.type === 'password') {
            input.type = 'text';
            icon.classList.remove('fa-eye');
            icon.classList.add('fa-eye-slash');
        } else {
            input.type = 'password';
            icon.classList.remove('fa-eye-slash');
            icon.classList.add('fa-eye');
        }
    }
</script>
@endsection
