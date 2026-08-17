@extends('layouts.admin')

@section('title', 'Edit Company - Adhikshita Plotters')
@section('page_title', 'Update Company Profile')

@section('content')
<div class="max-w-2xl mx-auto">
    <div class="glass-dark p-6 sm:p-8 rounded-3xl border border-slate-200 shadow-xl">
        <h3 class="font-outfit font-bold text-xl text-slate-850 mb-6 flex items-center">
            <i class="fa-solid fa-building text-purple-650 mr-2.5"></i> Update Details for: {{ $company->name }}
        </h3>

        <form action="{{ route('admin.companies.update', $company) }}" method="POST" class="space-y-6">
            @csrf
            @method('PUT')

            <!-- Company Name -->
            <div>
                <label for="name" class="block text-xs font-semibold uppercase tracking-wider text-slate-500">Company Name *</label>
                <input type="text" id="name" name="name" required value="{{ old('name', $company->name) }}"
                    class="mt-2 block w-full px-4 py-3 bg-white border border-slate-350 rounded-xl text-slate-800 focus:outline-none focus:ring-1 focus:ring-purple-500 text-sm @error('name') border-rose-500 @enderror">
                @error('name')
                    <span class="text-xs text-rose-500 mt-1 block">{{ $message }}</span>
                @enderror
            </div>

            <!-- Company Address -->
            <div>
                <label for="address" class="block text-xs font-semibold uppercase tracking-wider text-slate-500">Company Address</label>
                <textarea id="address" name="address" rows="3"
                    class="mt-2 block w-full px-4 py-3 bg-white border border-slate-350 rounded-xl text-slate-800 focus:outline-none focus:ring-1 focus:ring-purple-500 text-sm @error('address') border-rose-500 @enderror"
                    placeholder="Full postal address of the company office...">{{ old('address', $company->address) }}</textarea>
                @error('address')
                    <span class="text-xs text-rose-500 mt-1 block">{{ $message }}</span>
                @enderror
            </div>

            <!-- Submit buttons -->
            <div class="flex items-center justify-end space-x-3 pt-6 border-t border-slate-200">
                <a href="{{ route('admin.companies.index') }}" class="px-5 py-3 border border-slate-200 hover:border-slate-300 hover:bg-slate-50 rounded-xl text-xs font-semibold text-slate-650 transition-all">
                    Cancel & Return
                </a>
                <button type="submit" class="px-6 py-3 bg-gradient-to-r from-purple-600 to-indigo-600 hover:from-purple-500 hover:to-indigo-500 text-white rounded-xl text-xs font-semibold transition-all shadow-lg shadow-purple-500/10">
                    <i class="fa-solid fa-circle-check mr-1.5"></i> Update Profile
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
