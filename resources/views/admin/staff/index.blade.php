@extends('layouts.admin')

@section('title', 'Manage Management Staff - RM HR Solutions')
@section('page_title', 'Management Staff Members')

@section('content')
<div class="space-y-6">
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <p class="text-xs text-slate-500">Configure administrative system staff profiles and customize their system access permissions.</p>
        </div>
        <a href="{{ route('admin.staff.create') }}" class="inline-flex items-center justify-center space-x-2 py-3 px-5 bg-gradient-to-r from-purple-600 to-indigo-600 hover:from-purple-500 hover:to-indigo-500 text-white rounded-xl text-xs font-semibold shadow-lg shadow-purple-500/10 transition-all">
            <i class="fa-solid fa-user-plus text-sm"></i>
            <span>Register New Staff</span>
        </a>
    </div>

    <!-- Staff List Card -->
    <div class="glass-dark rounded-3xl border border-slate-200 shadow-xl overflow-hidden">
        <div class="p-6 border-b border-slate-100 bg-white flex items-center justify-between">
            <h3 class="font-outfit font-bold text-slate-800 flex items-center">
                <i class="fa-solid fa-users-gear text-purple-650 mr-2.5"></i> Active Staff Accounts
            </h3>
            <span class="px-2.5 py-1 bg-purple-50 border border-purple-200 text-purple-700 text-xs font-bold rounded-lg">{{ $staffMembers->count() }} Profiles</span>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="border-b border-slate-200 bg-slate-50">
                        <th class="p-5 text-xs font-bold uppercase tracking-wider text-slate-500">Name & Email</th>
                        <th class="p-5 text-xs font-bold uppercase tracking-wider text-slate-500">Assigned Permissions</th>
                        <th class="p-5 text-xs font-bold uppercase tracking-wider text-slate-500">Password Changed</th>
                        <th class="p-5 text-xs font-bold uppercase tracking-wider text-slate-500">Date Registered</th>
                        <th class="p-5 text-xs font-bold uppercase tracking-wider text-slate-500 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($staffMembers as $staff)
                        <tr class="hover:bg-slate-50/50 transition-colors">
                            <td class="p-5">
                                <div class="flex items-center space-x-3">
                                    <div class="w-10 h-10 rounded-xl bg-purple-50 border border-purple-100 flex items-center justify-center text-purple-600 font-extrabold text-sm shrink-0">
                                        {{ substr($staff->name, 0, 1) }}
                                    </div>
                                    <div>
                                        <span class="block font-semibold text-slate-800 text-sm">{{ $staff->name }}</span>
                                        <span class="block text-xs text-slate-500">{{ $staff->email }}</span>
                                    </div>
                                </div>
                            </td>
                            <td class="p-5 max-w-xs sm:max-w-sm lg:max-w-md">
                                <div class="flex flex-wrap gap-1.5">
                                    @forelse($staff->permissions as $perm)
                                        <span class="px-2 py-0.5 bg-indigo-50 border border-indigo-150 text-[10px] font-semibold text-indigo-600 rounded-md uppercase tracking-wider" title="{{ $perm->label }}">
                                            {{ str_replace('manage_', '', $perm->name) }}
                                        </span>
                                    @empty
                                        <span class="text-xs text-slate-400 italic"><i class="fa-solid fa-lock text-[10px] mr-1"></i> No Permissions (Read Only)</span>
                                    @endforelse
                                </div>
                            </td>
                            <td class="p-5">
                                @if($staff->is_password_changed)
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-lg text-xs font-semibold bg-emerald-50 border border-emerald-200 text-emerald-700">
                                        <i class="fa-solid fa-circle-check text-[10px] mr-1.5"></i> Yes
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-lg text-xs font-semibold bg-amber-50 border border-amber-200 text-amber-700 animate-pulse">
                                        <i class="fa-solid fa-user-clock text-[10px] mr-1.5"></i> Pending Setup
                                    </span>
                                @endif
                            </td>
                            <td class="p-5 text-xs text-slate-500">
                                {{ $staff->created_at->format('d-M-Y h:i A') }}
                            </td>
                            <td class="p-5 text-right space-x-2">
                                <a href="{{ route('admin.staff.edit', $staff) }}" class="inline-flex items-center justify-center p-2 bg-slate-100 hover:bg-purple-50 text-slate-500 hover:text-purple-650 border border-slate-200 hover:border-purple-200 rounded-xl transition-all" title="Edit Profile & Permissions">
                                    <i class="fa-solid fa-pen-to-square text-sm"></i>
                                </a>
                                
                                <form action="{{ route('admin.staff.destroy', $staff) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure you want to delete this staff member? This will immediately revoke their access.');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="inline-flex items-center justify-center p-2 bg-slate-100 hover:bg-rose-50 text-slate-500 hover:text-rose-600 border border-slate-200 hover:border-rose-200 rounded-xl transition-all" title="Delete Profile">
                                        <i class="fa-solid fa-trash-can text-sm"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="p-10 text-center text-slate-400 italic">
                                <div class="flex flex-col items-center justify-center space-y-2">
                                    <i class="fa-solid fa-users-slash text-4xl text-slate-300"></i>
                                    <span>No staff profiles registered. Click "Register New Staff" to create one.</span>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
