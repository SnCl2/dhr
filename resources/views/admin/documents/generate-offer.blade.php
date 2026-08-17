@extends('layouts.admin')

@section('title', 'Generate Offer Letter - Propszy')
@section('page_title', 'Offer Letter PDF Generator')

@section('content')
<div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
    <!-- Left: Individual Generation Form -->
    <div class="glass-dark p-6 sm:p-8 rounded-3xl border border-slate-850 shadow-2xl">
        <h3 class="font-outfit font-bold text-xl text-white mb-2 flex items-center">
            <i class="fa-solid fa-file-signature text-purple-400 mr-3"></i> Generate Individual Offer Letter
        </h3>
        <p class="text-xs text-slate-400 leading-relaxed mb-6">
            Select a candidate or staff member and map them to a letter template. Values will be substituted automatically.
        </p>

        <form action="{{ route('admin.offer-letters.generate.submit') }}" method="POST" class="space-y-6">
            @csrf

            <!-- Employee -->
            <div>
                <label for="employee_id" class="block text-xs font-semibold uppercase tracking-wider text-slate-400">Select Candidate / Staff</label>
                <select id="employee_id" name="employee_id" required
                    class="mt-2 block w-full px-3 py-3 bg-slate-950/60 border border-slate-800 rounded-xl text-slate-200 focus:outline-none focus:ring-1 focus:ring-purple-500 text-sm">
                    <option value="">-- Choose Candidate --</option>
                    @foreach($employees as $emp)
                        <option value="{{ $emp->id }}" {{ old('employee_id') == $emp->id ? 'selected' : '' }}>
                            {{ $emp->full_name }} ({{ $emp->employee_id }})
                        </option>
                    @endforeach
                </select>
                @error('employee_id')
                    <p class="mt-1.5 text-xs text-rose-400">{{ $message }}</p>
                @enderror
            </div>

            <!-- Offer Letter Type -->
            <div>
                <label for="type" class="block text-xs font-semibold uppercase tracking-wider text-slate-400">Select Letter Type</label>
                <select id="type" name="type" required
                    class="mt-2 block w-full px-3 py-3 bg-slate-950/60 border border-slate-800 rounded-xl text-slate-200 focus:outline-none focus:ring-1 focus:ring-purple-500 text-sm">
                    <option value="">-- Choose Type --</option>
                    <option value="internal" {{ old('type') == 'internal' ? 'selected' : '' }}>Internal Staff Layout</option>
                    <option value="external" {{ old('type') == 'external' ? 'selected' : '' }}>External/Contractor Layout</option>
                </select>
                @error('type')
                    <p class="mt-1.5 text-xs text-rose-400">{{ $message }}</p>
                @enderror
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                <!-- Salary Override -->
                <div>
                    <label for="salary" class="block text-xs font-semibold uppercase tracking-wider text-slate-400">Salary Override (Optional)</label>
                    <input type="number" step="0.01" id="salary" name="salary" value="{{ old('salary') }}"
                        class="mt-2 block w-full px-4 py-3 bg-slate-950/60 border border-slate-800 rounded-xl text-slate-200 focus:outline-none focus:ring-1 focus:ring-purple-500 text-sm"
                        placeholder="Leave blank to use profile default">
                    @error('salary')
                        <p class="mt-1.5 text-xs text-rose-400">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Joining Date Override -->
                <div>
                    <label for="joining_date" class="block text-xs font-semibold uppercase tracking-wider text-slate-400">Joining Date Override (Optional)</label>
                    <input type="date" id="joining_date" name="joining_date" value="{{ old('joining_date') }}"
                        class="mt-2 block w-full px-4 py-3 bg-slate-950/60 border border-slate-800 rounded-xl text-slate-200 focus:outline-none focus:ring-1 focus:ring-purple-500 text-sm">
                    @error('joining_date')
                        <p class="mt-1.5 text-xs text-rose-400">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="pt-6 border-t border-slate-850 flex justify-end">
                <button type="submit"
                    class="w-full sm:w-auto px-6 py-3.5 bg-gradient-to-r from-purple-600 to-indigo-600 hover:from-purple-500 hover:to-indigo-500 text-white rounded-xl text-sm font-semibold transition-all shadow-lg shadow-purple-500/10">
                    <i class="fa-solid fa-file-pdf mr-2"></i> Generate & Store PDF
                </button>
            </div>
        </form>
    </div>

    <!-- Right: Bulk Generation Form -->
    <div class="glass-dark p-6 sm:p-8 rounded-3xl border border-slate-850 shadow-2xl flex flex-col justify-between">
        <div>
            <h3 class="font-outfit font-bold text-xl text-white mb-2 flex items-center">
                <i class="fa-solid fa-file-invoice text-pink-400 mr-3"></i> Generate Bulk Offer Letters
            </h3>
            <p class="text-xs text-slate-400 leading-relaxed mb-6">
                Upload a CSV file containing employee details to generate offer letters in bulk.
            </p>

            <div class="p-4 rounded-xl bg-slate-950/40 border border-slate-800 mb-6 text-xs text-slate-400">
                <span class="block font-semibold text-white mb-1">CSV Format Requirements:</span>
                File must contain headers exactly: <code class="text-purple-400 font-mono select-all">employee_id,salary,joining_date</code>
            </div>

            <form action="{{ route('admin.offer-letters.bulk') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                @csrf

                <!-- Offer Letter Type -->
                <div>
                    <label for="bulk_type" class="block text-xs font-semibold uppercase tracking-wider text-slate-400">Select Letter Type</label>
                    <select id="bulk_type" name="type" required
                        class="mt-2 block w-full px-3 py-3 bg-slate-950/60 border border-slate-800 rounded-xl text-slate-200 focus:outline-none focus:ring-1 focus:ring-purple-500 text-sm">
                        <option value="">-- Choose Type --</option>
                        <option value="internal">Internal Staff Layout</option>
                        <option value="external">External/Contractor Layout</option>
                    </select>
                    @error('type')
                        <p class="mt-1.5 text-xs text-rose-400">{{ $message }}</p>
                    @enderror
                </div>

                <!-- CSV File -->
                <div>
                    <label for="csv_file" class="block text-xs font-semibold uppercase tracking-wider text-slate-400 mb-2">Select CSV Data File</label>
                    <input type="file" id="csv_file" name="csv_file" accept=".csv" required
                        class="block w-full text-sm text-slate-450 file:mr-4 file:py-2.5 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-pink-650 file:text-white hover:file:bg-pink-500 cursor-pointer">
                </div>

                <div class="pt-6 border-t border-slate-850 flex justify-end">
                    <button type="submit"
                        class="w-full sm:w-auto px-6 py-3.5 bg-gradient-to-r from-pink-600 to-rose-600 hover:from-pink-500 hover:to-rose-500 text-white rounded-xl text-sm font-semibold transition-all shadow-lg shadow-pink-500/10">
                        <i class="fa-solid fa-gears mr-2"></i> Run Bulk PDF Generator
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
