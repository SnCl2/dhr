@extends('layouts.admin')

@section('title', 'Edit Staff Member - Propszy')
@section('page_title', 'Modify Staff Member Profile')

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="mb-6">
        <a href="{{ route('admin.employees.index') }}" class="text-xs font-semibold text-slate-400 hover:text-white flex items-center">
            <i class="fa-solid fa-arrow-left mr-1.5"></i> Back to Database List
        </a>
    </div>

    <div class="glass-dark p-8 sm:p-10 rounded-3xl border border-slate-850 shadow-2xl">
        <form action="{{ route('admin.employees.update', $employee) }}" method="POST" class="space-y-6">
            @csrf
            @method('PUT')

            <div class="p-4 rounded-xl bg-slate-900 border border-slate-800 flex items-center justify-between mb-6">
                <div>
                    <span class="block text-xs text-slate-500 uppercase tracking-wider">System Assigned ID</span>
                    <span class="block font-outfit font-extrabold text-xl text-purple-400">{{ $employee->employee_id }}</span>
                </div>
                <div class="text-right">
                    <span class="block text-xs text-slate-500 uppercase tracking-wider">Password Status</span>
                    @if($employee->is_password_changed)
                        <span class="text-xs font-bold text-emerald-400"><i class="fa-solid fa-shield-halved mr-1"></i> Customized by User</span>
                    @else
                        <span class="text-xs font-bold text-amber-400"><i class="fa-solid fa-triangle-exclamation mr-1"></i> Temporary Default Password</span>
                    @endif
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                <!-- First Name -->
                <div>
                    <label for="first_name" class="block text-xs font-semibold uppercase tracking-wider text-slate-400">First Name</label>
                    <input type="text" id="first_name" name="first_name" required value="{{ old('first_name', $employee->first_name) }}"
                        class="mt-2 block w-full px-4 py-3 bg-slate-950/60 border border-slate-800 rounded-xl text-slate-200 focus:outline-none focus:ring-1 focus:ring-purple-500 text-sm">
                    @error('first_name')
                        <p class="mt-1.5 text-xs text-rose-400">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Last Name -->
                <div>
                    <label for="last_name" class="block text-xs font-semibold uppercase tracking-wider text-slate-400">Last Name</label>
                    <input type="text" id="last_name" name="last_name" required value="{{ old('last_name', $employee->last_name) }}"
                        class="mt-2 block w-full px-4 py-3 bg-slate-950/60 border border-slate-800 rounded-xl text-slate-200 focus:outline-none focus:ring-1 focus:ring-purple-500 text-sm">
                    @error('last_name')
                        <p class="mt-1.5 text-xs text-rose-400">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                <!-- Email -->
                <div>
                    <label for="email" class="block text-xs font-semibold uppercase tracking-wider text-slate-400">Email Address</label>
                    <input type="email" id="email" name="email" required value="{{ old('email', $employee->email) }}"
                        class="mt-2 block w-full px-4 py-3 bg-slate-950/60 border border-slate-800 rounded-xl text-slate-200 focus:outline-none focus:ring-1 focus:ring-purple-500 text-sm">
                    @error('email')
                        <p class="mt-1.5 text-xs text-rose-400">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Phone -->
                <div>
                    <label for="phone" class="block text-xs font-semibold uppercase tracking-wider text-slate-400">Phone Number</label>
                    <input type="tel" id="phone" name="phone" value="{{ old('phone', $employee->phone) }}"
                        class="mt-2 block w-full px-4 py-3 bg-slate-950/60 border border-slate-800 rounded-xl text-slate-200 focus:outline-none focus:ring-1 focus:ring-purple-500 text-sm">
                    @error('phone')
                        <p class="mt-1.5 text-xs text-rose-400">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                <!-- Status -->
                <div>
                    <label for="status" class="block text-xs font-semibold uppercase tracking-wider text-slate-400">Employment Status</label>
                    <select id="status" name="status" required
                        class="mt-2 block w-full px-3 py-3 bg-slate-950/60 border border-slate-800 rounded-xl text-slate-200 focus:outline-none focus:ring-1 focus:ring-purple-500 text-sm">
                        <option value="pending_review" {{ old('status', $employee->status) === 'pending_review' ? 'selected' : '' }}>Pending Review</option>
                        <option value="active" {{ old('status', $employee->status) === 'active' ? 'selected' : '' }}>Active</option>
                        <option value="inactive" {{ old('status', $employee->status) === 'inactive' ? 'selected' : '' }}>Inactive</option>
                        <option value="on_leave" {{ old('status', $employee->status) === 'on_leave' ? 'selected' : '' }}>On Leave</option>
                        <option value="terminated" {{ old('status', $employee->status) === 'terminated' ? 'selected' : '' }}>Terminated</option>
                    </select>
                    @error('status')
                        <p class="mt-1.5 text-xs text-rose-400">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Company -->
                <div>
                    <label for="company_id" class="block text-xs font-semibold uppercase tracking-wider text-slate-400">Assigned Company</label>
                    <select id="company_id" name="company_id"
                        class="mt-2 block w-full px-3 py-3 bg-slate-950/60 border border-slate-800 rounded-xl text-slate-200 focus:outline-none focus:ring-1 focus:ring-purple-500 text-sm">
                        <option value="">Unassigned</option>
                        @foreach($companies as $company)
                            <option value="{{ $company->id }}" {{ old('company_id', $employee->company_id) == $company->id ? 'selected' : '' }}>{{ $company->name }}</option>
                        @endforeach
                    </select>
                    @error('company_id')
                        <p class="mt-1.5 text-xs text-rose-400">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Department -->
                <div>
                    <label for="department_id" class="block text-xs font-semibold uppercase tracking-wider text-slate-400">Department</label>
                    <select id="department_id" name="department_id"
                        class="mt-2 block w-full px-3 py-3 bg-slate-950/60 border border-slate-800 rounded-xl text-slate-200 focus:outline-none focus:ring-1 focus:ring-purple-500 text-sm">
                        <option value="">Unassigned</option>
                        @foreach($departments as $dept)
                            <option value="{{ $dept->id }}" {{ old('department_id', $employee->department_id) == $dept->id ? 'selected' : '' }}>{{ $dept->name }}</option>
                        @endforeach
                    </select>
                    @error('department_id')
                        <p class="mt-1.5 text-xs text-rose-400">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Designation -->
                <div>
                    <label for="designation_id" class="block text-xs font-semibold uppercase tracking-wider text-slate-400">Designation</label>
                    <select id="designation_id" name="designation_id"
                        class="mt-2 block w-full px-3 py-3 bg-slate-950/60 border border-slate-800 rounded-xl text-slate-200 focus:outline-none focus:ring-1 focus:ring-purple-500 text-sm">
                        <option value="">Unassigned</option>
                        @foreach($designations as $desig)
                            <option value="{{ $desig->id }}" {{ old('designation_id', $employee->designation_id) == $desig->id ? 'selected' : '' }}>{{ $desig->name }}</option>
                        @endforeach
                    </select>
                    @error('designation_id')
                        <p class="mt-1.5 text-xs text-rose-400">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                <!-- Salary -->
                <div>
                    <label for="salary" class="block text-xs font-semibold uppercase tracking-wider text-slate-400">Salary (Monthly Payout)</label>
                    <input type="number" step="0.01" id="salary" name="salary" value="{{ old('salary', $employee->salary) }}"
                        class="mt-2 block w-full px-4 py-3 bg-slate-950/60 border border-slate-800 rounded-xl text-slate-200 focus:outline-none focus:ring-1 focus:ring-purple-500 text-sm">
                    @error('salary')
                        <p class="mt-1.5 text-xs text-rose-400">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Joining Date -->
                <div>
                    <label for="joining_date" class="block text-xs font-semibold uppercase tracking-wider text-slate-400">Joining Date</label>
                    <input type="date" id="joining_date" name="joining_date" value="{{ old('joining_date', $employee->joining_date ? $employee->joining_date->format('Y-m-d') : '') }}"
                        class="mt-2 block w-full px-4 py-3 bg-slate-950/60 border border-slate-800 rounded-xl text-slate-200 focus:outline-none focus:ring-1 focus:ring-purple-500 text-sm">
                    @error('joining_date')
                        <p class="mt-1.5 text-xs text-rose-400">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="pt-6 border-t border-slate-850 flex justify-end space-x-3">
                <a href="{{ route('admin.employees.index') }}"
                    class="px-6 py-3 bg-slate-850 hover:bg-slate-800 text-slate-400 hover:text-white rounded-xl text-sm font-semibold transition-all">
                    Cancel
                </a>
                <button type="submit"
                    class="px-6 py-3 bg-gradient-to-r from-purple-600 to-indigo-600 hover:from-purple-500 hover:to-indigo-500 text-white rounded-xl text-sm font-semibold transition-all shadow-lg shadow-purple-500/10">
                    <i class="fa-solid fa-save mr-2"></i> Save Changes
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
