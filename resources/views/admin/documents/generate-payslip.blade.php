@extends('layouts.admin')

@section('title', 'Generate Payslip - Propszy')
@section('page_title', 'Monthly Payslip PDF Generator')

@section('content')
<div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
    <!-- Left: Individual Payslip Form -->
    <div class="glass-dark p-6 sm:p-8 rounded-3xl border border-slate-850 shadow-2xl">
        <h3 class="font-outfit font-bold text-xl text-white mb-2 flex items-center">
            <i class="fa-solid fa-file-invoice-dollar text-purple-400 mr-3"></i> Generate Individual Payslip
        </h3>
        <p class="text-xs text-slate-400 leading-relaxed mb-6">
            Enter monthly earnings, allowances, and deductions. Net salary will be calculated automatically.
        </p>

        <form action="{{ route('admin.payslips.generate.submit') }}" method="POST" class="space-y-6">
            @csrf

            <!-- Employee -->
            <div>
                <label for="employee_id" class="block text-xs font-semibold uppercase tracking-wider text-slate-400">Select Active Staff</label>
                <select id="employee_id" name="employee_id" required onchange="updateSalaryField(this)"
                    class="mt-2 block w-full px-3 py-3 bg-slate-950/60 border border-slate-800 rounded-xl text-slate-200 focus:outline-none focus:ring-1 focus:ring-purple-500 text-sm">
                    <option value="">-- Choose Employee --</option>
                    @foreach($employees as $emp)
                        <option value="{{ $emp->id }}" data-salary="{{ $emp->salary }}" {{ old('employee_id') == $emp->id ? 'selected' : '' }}>
                            {{ $emp->full_name }} ({{ $emp->employee_id }})
                        </option>
                    @endforeach
                </select>
                @error('employee_id')
                    <p class="mt-1.5 text-xs text-rose-400">{{ $message }}</p>
                @enderror
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                <!-- Month -->
                <div>
                    <label for="month" class="block text-xs font-semibold uppercase tracking-wider text-slate-400">Salary Month</label>
                    <input type="text" id="month" name="month" required value="{{ old('month', date('F Y')) }}"
                        class="mt-2 block w-full px-4 py-3 bg-slate-950/60 border border-slate-800 rounded-xl text-slate-200 focus:outline-none focus:ring-1 focus:ring-purple-500 text-sm"
                        placeholder="e.g. August 2026">
                    @error('month')
                        <p class="mt-1.5 text-xs text-rose-400">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Payslip Format Type -->
                <div>
                    <label for="type" class="block text-xs font-semibold uppercase tracking-wider text-slate-400">Payslip Format Type</label>
                    <select id="type" name="type" required
                        class="mt-2 block w-full px-3 py-3 bg-slate-950/60 border border-slate-800 rounded-xl text-slate-200 focus:outline-none focus:ring-1 focus:ring-purple-500 text-sm">
                        <option value="external" {{ old('type') === 'external' ? 'selected' : '' }}>External Style (Clean billing statement)</option>
                        <option value="internal" {{ old('type') === 'internal' ? 'selected' : '' }}>Internal Style (Detailed payroll block)</option>
                    </select>
                    @error('type')
                        <p class="mt-1.5 text-xs text-rose-400">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-3 gap-6">
                <!-- Basic Salary -->
                <div>
                    <label for="basic_salary" class="block text-xs font-semibold uppercase tracking-wider text-slate-400">Basic Salary</label>
                    <input type="number" step="0.01" id="basic_salary" name="basic_salary" required value="{{ old('basic_salary') }}"
                        class="mt-2 block w-full px-4 py-3 bg-slate-950/60 border border-slate-800 rounded-xl text-slate-200 focus:outline-none focus:ring-1 focus:ring-purple-500 text-sm"
                        placeholder="0.00">
                    @error('basic_salary')
                        <p class="mt-1.5 text-xs text-rose-400">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Allowances -->
                <div>
                    <label for="allowances" class="block text-xs font-semibold uppercase tracking-wider text-slate-400">Allowances</label>
                    <input type="number" step="0.01" id="allowances" name="allowances" value="{{ old('allowances', 0) }}"
                        class="mt-2 block w-full px-4 py-3 bg-slate-950/60 border border-slate-800 rounded-xl text-slate-200 focus:outline-none focus:ring-1 focus:ring-purple-500 text-sm">
                    @error('allowances')
                        <p class="mt-1.5 text-xs text-rose-400">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Deductions -->
                <div>
                    <label for="deductions" class="block text-xs font-semibold uppercase tracking-wider text-slate-400">Deductions</label>
                    <input type="number" step="0.01" id="deductions" name="deductions" value="{{ old('deductions', 0) }}"
                        class="mt-2 block w-full px-4 py-3 bg-slate-950/60 border border-slate-800 rounded-xl text-slate-200 focus:outline-none focus:ring-1 focus:ring-purple-500 text-sm">
                    @error('deductions')
                        <p class="mt-1.5 text-xs text-rose-400">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="pt-6 border-t border-slate-850 flex justify-end">
                <button type="submit"
                    class="w-full sm:w-auto px-6 py-3.5 bg-gradient-to-r from-purple-600 to-indigo-600 hover:from-purple-500 hover:to-indigo-500 text-white rounded-xl text-sm font-semibold transition-all shadow-lg shadow-purple-500/10">
                    <i class="fa-solid fa-calculator mr-2"></i> Compute & Save Payslip
                </button>
            </div>
        </form>
    </div>

    <!-- Right: Bulk Payslip Generation via CSV -->
    <div class="glass-dark p-6 sm:p-8 rounded-3xl border border-slate-850 shadow-2xl flex flex-col justify-between">
        <div>
            <h3 class="font-outfit font-bold text-xl text-white mb-2 flex items-center">
                <i class="fa-solid fa-file-invoice text-pink-400 mr-3"></i> Generate Bulk Payslips
            </h3>
            <p class="text-xs text-slate-400 leading-relaxed mb-6">
                Upload a CSV spreadsheet containing monthly basic salaries, allowances, and deductions to perform bulk processing.
            </p>

            <div class="p-4 rounded-xl bg-slate-950/40 border border-slate-800 mb-6 text-xs text-slate-400">
                <span class="block font-semibold text-white mb-1">CSV Format Requirements:</span>
                File must contain headers exactly: <code class="text-purple-400 font-mono select-all">employee_id,month,basic_salary,allowances,deductions,type</code>
            </div>

            <form action="{{ route('admin.payslips.bulk') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                @csrf

                <!-- CSV File -->
                <div>
                    <label for="csv_file_payslip" class="block text-xs font-semibold uppercase tracking-wider text-slate-400 mb-2">Select CSV Data File</label>
                    <input type="file" id="csv_file_payslip" name="csv_file" accept=".csv" required
                        class="block w-full text-sm text-slate-450 file:mr-4 file:py-2.5 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-pink-650 file:text-white hover:file:bg-pink-500 cursor-pointer">
                </div>

                <div class="pt-6 border-t border-slate-850 flex justify-end">
                    <button type="submit"
                        class="w-full sm:w-auto px-6 py-3.5 bg-gradient-to-r from-pink-600 to-rose-600 hover:from-pink-500 hover:to-rose-500 text-white rounded-xl text-sm font-semibold transition-all shadow-lg shadow-pink-500/10">
                        <i class="fa-solid fa-gears mr-2"></i> Run Bulk Payslips Engine
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    function updateSalaryField(selectElement) {
        const selectedOption = selectElement.options[selectElement.selectedIndex];
        const salary = selectedOption.getAttribute('data-salary');
        const basicSalaryInput = document.getElementById('basic_salary');
        if (salary) {
            basicSalaryInput.value = parseFloat(salary).toFixed(2);
        } else {
            basicSalaryInput.value = '';
        }
    }
</script>
@endsection
