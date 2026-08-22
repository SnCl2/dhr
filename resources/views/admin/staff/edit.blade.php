@extends('layouts.admin')

@section('title', 'Edit Staff - RM HR Solutions')
@section('page_title', 'Edit Staff Member Profile')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">
    <form action="{{ route('admin.staff.update', $staff) }}" method="POST" class="space-y-6">
        @csrf
        @method('PUT')

        <!-- 1. Staff Profile Details Card -->
        <div class="glass-dark p-6 sm:p-8 rounded-3xl border border-slate-200 shadow-xl space-y-6">
            <h3 class="font-outfit font-bold text-xl text-slate-850 flex items-center">
                <i class="fa-solid fa-user-pen text-purple-650 mr-2.5"></i> Staff Profile Details
            </h3>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Name -->
                <div>
                    <label for="name" class="block text-xs font-semibold uppercase tracking-wider text-slate-500">Full Name *</label>
                    <input type="text" id="name" name="name" required value="{{ old('name', $staff->name) }}"
                        class="mt-2 block w-full px-4 py-3 bg-white border border-slate-350 rounded-xl text-slate-800 focus:outline-none focus:ring-1 focus:ring-purple-500 text-sm @error('name') border-rose-500 @enderror"
                        placeholder="Rahul Sharma">
                    @error('name')
                        <span class="text-xs text-rose-500 mt-1 block">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Email -->
                <div>
                    <label for="email" class="block text-xs font-semibold uppercase tracking-wider text-slate-500">Email Address *</label>
                    <input type="email" id="email" name="email" required value="{{ old('email', $staff->email) }}"
                        class="mt-2 block w-full px-4 py-3 bg-white border border-slate-350 rounded-xl text-slate-800 focus:outline-none focus:ring-1 focus:ring-purple-500 text-sm @error('email') border-rose-500 @enderror"
                        placeholder="rahul@agency.com">
                    @error('email')
                        <span class="text-xs text-rose-500 mt-1 block">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Reset Password -->
                <div>
                    <label for="password" class="block text-xs font-semibold uppercase tracking-wider text-slate-500">Update Password (Optional)</label>
                    <input type="password" id="password" name="password"
                        class="mt-2 block w-full px-4 py-3 bg-white border border-slate-350 rounded-xl text-slate-800 focus:outline-none focus:ring-1 focus:ring-purple-500 text-sm @error('password') border-rose-500 @enderror"
                        placeholder="••••••••">
                    <span class="text-[11px] text-slate-500 mt-1.5 block leading-relaxed"><i class="fa-solid fa-circle-info mr-1"></i> Leave blank to keep current password.</span>
                    @error('password')
                        <span class="text-xs text-rose-500 mt-1 block">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Account Status -->
                <div>
                    <label for="is_active" class="block text-xs font-semibold uppercase tracking-wider text-slate-500">Account Status *</label>
                    <select id="is_active" name="is_active" required
                        class="mt-2 block w-full px-4 py-3 bg-white border border-slate-350 rounded-xl text-slate-800 focus:outline-none focus:ring-1 focus:ring-purple-500 text-sm">
                        <option value="1" {{ old('is_active', $staff->is_active ? '1' : '0') === '1' ? 'selected' : '' }}>Active</option>
                        <option value="0" {{ old('is_active', $staff->is_active ? '1' : '0') === '0' ? 'selected' : '' }}>Deactivated</option>
                    </select>
                    <span class="text-[11px] text-slate-500 mt-1.5 block leading-relaxed"><i class="fa-solid fa-circle-info mr-1"></i> Deactivated staff cannot log in.</span>
                    @error('is_active')
                        <span class="text-xs text-rose-500 mt-1 block">{{ $message }}</span>
                    @enderror
                </div>
            </div>
        </div>

        <!-- 2. System Permissions Settings Card -->
        <div class="glass-dark p-6 sm:p-8 rounded-3xl border border-slate-200 shadow-xl space-y-6">
            <div>
                <h3 class="font-outfit font-bold text-xl text-slate-850 flex items-center">
                    <i class="fa-solid fa-shield-halved text-indigo-600 mr-2.5"></i> Access Permissions Settings
                </h3>
                <p class="text-xs text-slate-500 mt-1">Configure and override the modules and functionalities this staff member is authorized to access.</p>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                @foreach($permissions as $perm)
                    <div class="flex items-start p-4 bg-slate-50 hover:bg-slate-100 border border-slate-200 hover:border-purple-250 rounded-2xl transition-all cursor-pointer select-none">
                        <div class="flex items-center h-5">
                            <input id="perm-{{ $perm->id }}" name="permissions[]" value="{{ $perm->id }}" type="checkbox"
                                {{ in_array($perm->id, old('permissions', $assignedPermissionIds)) ? 'checked' : '' }}
                                class="h-4.5 w-4.5 rounded border-slate-300 text-purple-600 focus:ring-purple-500 transition-colors">
                        </div>
                        <label for="perm-{{ $perm->id }}" class="ml-3 text-sm cursor-pointer">
                            <span class="block font-bold text-slate-800 text-xs uppercase tracking-wider">{{ str_replace('_', ' ', str_replace('manage_', '', $perm->name)) }}</span>
                            <span class="block text-xs text-slate-500 mt-0.5">{{ $perm->label }}</span>
                        </label>
                    </div>
                @endforeach
            </div>
            @error('permissions')
                <span class="text-xs text-rose-500 mt-1 block">{{ $message }}</span>
            @enderror
        </div>

        <!-- Form Actions -->
        <div class="flex items-center justify-end space-x-3 pt-4">
            <a href="{{ route('admin.staff.index') }}" class="px-5 py-3 border border-slate-200 hover:border-slate-300 hover:bg-slate-50 rounded-xl text-xs font-semibold text-slate-650 transition-all">
                Cancel & Return
            </a>
            <button type="submit" class="px-6 py-3.5 bg-gradient-to-r from-purple-600 to-indigo-600 hover:from-purple-500 hover:to-indigo-500 text-white rounded-xl text-xs font-semibold transition-all shadow-lg shadow-purple-500/10">
                <i class="fa-solid fa-circle-check mr-1.5"></i> Update Staff Profile
            </button>
        </div>
    </form>
</div>
@endsection
