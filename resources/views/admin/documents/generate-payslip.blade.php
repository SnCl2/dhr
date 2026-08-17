@extends('layouts.admin')

@section('title', 'Generate Payslip - Propszy')
@section('page_title', 'Monthly Payslip PDF Generator')

@section('content')
<div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
    <!-- Left & Middle: Individual Payslip Form (2 cols) -->
    <div class="lg:col-span-2 glass-dark p-6 sm:p-8 rounded-3xl border border-slate-200 shadow-sm space-y-6">
        <div>
            <h3 class="font-outfit font-bold text-xl text-slate-800 mb-2 flex items-center">
                <i class="fa-solid fa-file-invoice-dollar text-purple-600 mr-3"></i> Generate Individual Payslip
            </h3>
            <p class="text-xs text-slate-500 leading-relaxed">
                Provide salary parameters. The system will auto-compute standard PF, ESIC, HRA and Professional Tax values based on working days and CTC, which you can adjust manually.
            </p>
        </div>

        <form action="{{ route('admin.payslips.generate.submit') }}" method="POST" class="space-y-6">
            @csrf

            <!-- 1. Selection & Period -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <!-- Employee -->
                <div>
                    <label for="employee_id" class="block text-xs font-bold uppercase tracking-wider text-slate-650">Select Staff Member</label>
                    <select id="employee_id" name="employee_id" required onchange="onEmployeeSelect(this)"
                        class="mt-2 block w-full px-3 py-3 bg-white border border-slate-300 rounded-xl text-slate-800 focus:outline-none focus:ring-1 focus:ring-purple-500 text-sm">
                        <option value="">-- Choose Employee --</option>
                        @foreach($employees as $emp)
                            <option value="{{ $emp->id }}" data-salary="{{ $emp->salary }}" data-uan="{{ $emp->old_uan_number }}" data-esic="{{ $emp->old_esic_number }}" {{ old('employee_id') == $emp->id ? 'selected' : '' }}>
                                {{ $emp->full_name }} ({{ $emp->employee_id }})
                            </option>
                        @endforeach
                    </select>
                    @error('employee_id')
                        <p class="mt-1.5 text-xs text-rose-500">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Month -->
                <div>
                    <label for="month" class="block text-xs font-bold uppercase tracking-wider text-slate-650">Salary Month</label>
                    <input type="text" id="month" name="month" required value="{{ old('month', date('F Y')) }}"
                        class="mt-2 block w-full px-4 py-3 bg-white border border-slate-300 rounded-xl text-slate-800 focus:outline-none focus:ring-1 focus:ring-purple-500 text-sm"
                        placeholder="e.g. August 2026">
                    @error('month')
                        <p class="mt-1.5 text-xs text-rose-500">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Format Style -->
                <div>
                    <label for="type" class="block text-xs font-bold uppercase tracking-wider text-slate-650">Layout Format Style</label>
                    <select id="type" name="type" required
                        class="mt-2 block w-full px-3 py-3 bg-white border border-slate-300 rounded-xl text-slate-800 focus:outline-none focus:ring-1 focus:ring-purple-500 text-sm">
                        <option value="external" {{ old('type') === 'external' ? 'selected' : '' }}>External Style (Clean statement)</option>
                        <option value="internal" {{ old('type') === 'internal' ? 'selected' : '' }}>Internal Style (Detailed blocks)</option>
                    </select>
                    @error('type')
                        <p class="mt-1.5 text-xs text-rose-500">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- 2. Attendance & Payment Details -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6 pt-4 border-t border-slate-100">
                <!-- Working Days -->
                <div>
                    <label for="working_days" class="block text-xs font-bold uppercase tracking-wider text-slate-650">Working Days</label>
                    <input type="number" id="working_days" name="working_days" required value="{{ old('working_days', 31) }}" oninput="recalculate()"
                        class="mt-2 block w-full px-4 py-3 bg-white border border-slate-300 rounded-xl text-slate-800 focus:outline-none focus:ring-1 focus:ring-purple-500 text-sm">
                    @error('working_days') <p class="mt-1.5 text-xs text-rose-500">{{ $message }}</p> @enderror
                </div>

                <!-- Net Payable Days -->
                <div>
                    <label for="net_payable_days" class="block text-xs font-bold uppercase tracking-wider text-slate-650">Payable Days</label>
                    <input type="number" id="net_payable_days" name="net_payable_days" required value="{{ old('net_payable_days', 31) }}" oninput="recalculate()"
                        class="mt-2 block w-full px-4 py-3 bg-white border border-slate-300 rounded-xl text-slate-800 focus:outline-none focus:ring-1 focus:ring-purple-500 text-sm">
                    @error('net_payable_days') <p class="mt-1.5 text-xs text-rose-500">{{ $message }}</p> @enderror
                </div>

                <!-- OT Days -->
                <div>
                    <label for="ot_days" class="block text-xs font-bold uppercase tracking-wider text-slate-650">OT Days</label>
                    <input type="number" id="ot_days" name="ot_days" required value="{{ old('ot_days', 0) }}" oninput="onOtDaysChange()"
                        class="mt-2 block w-full px-4 py-3 bg-white border border-slate-300 rounded-xl text-slate-800 focus:outline-none focus:ring-1 focus:ring-purple-500 text-sm">
                    @error('ot_days') <p class="mt-1.5 text-xs text-rose-500">{{ $message }}</p> @enderror
                </div>

                <!-- Pay Mode -->
                <div>
                    <label for="pay_mode" class="block text-xs font-bold uppercase tracking-wider text-slate-650">Pay Mode</label>
                    <select id="pay_mode" name="pay_mode" required
                        class="mt-2 block w-full px-3 py-3 bg-white border border-slate-300 rounded-xl text-slate-800 focus:outline-none focus:ring-1 focus:ring-purple-500 text-sm">
                        <option value="Bank Transfer">Bank Transfer</option>
                        <option value="Cheque">Cheque</option>
                        <option value="Cash">Cash</option>
                    </select>
                    @error('pay_mode') <p class="mt-1.5 text-xs text-rose-500">{{ $message }}</p> @enderror
                </div>
            </div>

            <!-- 3. Earnings & Allowances Grid -->
            <div class="pt-4 border-t border-slate-100 space-y-4">
                <h4 class="text-xs font-bold text-emerald-600 uppercase tracking-wider"><i class="fa-solid fa-circle-arrow-up mr-2"></i>Earnings & Allowances</h4>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <!-- Basic Salary -->
                    <div>
                        <label for="basic_salary" class="block text-xs font-bold uppercase tracking-wider text-slate-650">Basic Salary</label>
                        <input type="number" step="0.01" id="basic_salary" name="basic_salary" required value="{{ old('basic_salary', 0) }}" oninput="recalculate()"
                            class="mt-2 block w-full px-4 py-3 bg-white border border-slate-300 rounded-xl text-slate-800 focus:outline-none focus:ring-1 focus:ring-purple-500 text-sm">
                        @error('basic_salary') <p class="mt-1.5 text-xs text-rose-500">{{ $message }}</p> @enderror
                    </div>

                    <!-- HRA -->
                    <div>
                        <label for="hra" class="block text-xs font-bold uppercase tracking-wider text-slate-650">H.R.A.</label>
                        <input type="number" step="0.01" id="hra" name="hra" required value="{{ old('hra', 0) }}" oninput="recalculate()"
                            class="mt-2 block w-full px-4 py-3 bg-white border border-slate-300 rounded-xl text-slate-800 focus:outline-none focus:ring-1 focus:ring-purple-500 text-sm">
                        @error('hra') <p class="mt-1.5 text-xs text-rose-500">{{ $message }}</p> @enderror
                    </div>

                    <!-- Medical Allowance -->
                    <div>
                        <label for="medical_allowance" class="block text-xs font-bold uppercase tracking-wider text-slate-650">Medical Allowance</label>
                        <input type="number" step="0.01" id="medical_allowance" name="medical_allowance" required value="{{ old('medical_allowance', 0) }}" oninput="recalculate()"
                            class="mt-2 block w-full px-4 py-3 bg-white border border-slate-300 rounded-xl text-slate-800 focus:outline-none focus:ring-1 focus:ring-purple-500 text-sm">
                        @error('medical_allowance') <p class="mt-1.5 text-xs text-rose-500">{{ $message }}</p> @enderror
                    </div>

                    <!-- Special Allowance -->
                    <div>
                        <label for="special_allowance" class="block text-xs font-bold uppercase tracking-wider text-slate-650">Special Allowance</label>
                        <input type="number" step="0.01" id="special_allowance" name="special_allowance" required value="{{ old('special_allowance', 0) }}" oninput="recalculate()"
                            class="mt-2 block w-full px-4 py-3 bg-white border border-slate-300 rounded-xl text-slate-800 focus:outline-none focus:ring-1 focus:ring-purple-500 text-sm">
                        @error('special_allowance') <p class="mt-1.5 text-xs text-rose-500">{{ $message }}</p> @enderror
                    </div>

                    <!-- Leave Encashment -->
                    <div>
                        <label for="leave_encashment" class="block text-xs font-bold uppercase tracking-wider text-slate-650">Leave Encashment</label>
                        <input type="number" step="0.01" id="leave_encashment" name="leave_encashment" required value="{{ old('leave_encashment', 0) }}" oninput="recalculate()"
                            class="mt-2 block w-full px-4 py-3 bg-white border border-slate-300 rounded-xl text-slate-800 focus:outline-none focus:ring-1 focus:ring-purple-500 text-sm">
                        @error('leave_encashment') <p class="mt-1.5 text-xs text-rose-500">{{ $message }}</p> @enderror
                    </div>

                    <!-- OT Allowance -->
                    <div>
                        <label for="ot_allowance" class="block text-xs font-bold uppercase tracking-wider text-slate-650">OT Allowance</label>
                        <input type="number" step="0.01" id="ot_allowance" name="ot_allowance" required value="{{ old('ot_allowance', 0) }}" oninput="recalculate()"
                            class="mt-2 block w-full px-4 py-3 bg-white border border-slate-300 rounded-xl text-slate-800 focus:outline-none focus:ring-1 focus:ring-purple-500 text-sm">
                        @error('ot_allowance') <p class="mt-1.5 text-xs text-rose-500">{{ $message }}</p> @enderror
                    </div>
                </div>
            </div>

            <!-- 4. Deductions Grid -->
            <div class="pt-4 border-t border-slate-100 space-y-4">
                <h4 class="text-xs font-bold text-rose-600 uppercase tracking-wider"><i class="fa-solid fa-circle-arrow-down mr-2"></i>Deductions</h4>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <!-- Professional Tax -->
                    <div>
                        <label for="professional_tax" class="block text-xs font-bold uppercase tracking-wider text-slate-650">Professional Tax</label>
                        <input type="number" step="0.01" id="professional_tax" name="professional_tax" required value="{{ old('professional_tax', 0) }}" oninput="recalculate()"
                            class="mt-2 block w-full px-4 py-3 bg-white border border-slate-300 rounded-xl text-slate-800 focus:outline-none focus:ring-1 focus:ring-purple-500 text-sm">
                        @error('professional_tax') <p class="mt-1.5 text-xs text-rose-500">{{ $message }}</p> @enderror
                    </div>

                    <!-- Provident Fund -->
                    <div>
                        <label for="provident_fund" class="block text-xs font-bold uppercase tracking-wider text-slate-650">Provident Fund (PF)</label>
                        <input type="number" step="0.01" id="provident_fund" name="provident_fund" required value="{{ old('provident_fund', 0) }}" oninput="recalculate()"
                            class="mt-2 block w-full px-4 py-3 bg-white border border-slate-300 rounded-xl text-slate-800 focus:outline-none focus:ring-1 focus:ring-purple-500 text-sm">
                        @error('provident_fund') <p class="mt-1.5 text-xs text-rose-500">{{ $message }}</p> @enderror
                    </div>

                    <!-- ESIC -->
                    <div>
                        <label for="esic" class="block text-xs font-bold uppercase tracking-wider text-slate-650">ESIC</label>
                        <input type="number" step="0.01" id="esic" name="esic" required value="{{ old('esic', 0) }}" oninput="recalculate()"
                            class="mt-2 block w-full px-4 py-3 bg-white border border-slate-300 rounded-xl text-slate-800 focus:outline-none focus:ring-1 focus:ring-purple-500 text-sm">
                        @error('esic') <p class="mt-1.5 text-xs text-rose-500">{{ $message }}</p> @enderror
                    </div>
                </div>
            </div>

            <!-- Live Net Payout Summary Block -->
            <div class="p-6 rounded-2xl bg-slate-900 border border-slate-850 flex flex-col md:flex-row items-center justify-between gap-4">
                <div class="grid grid-cols-2 md:grid-cols-3 gap-x-8 gap-y-2 text-center md:text-left">
                    <div>
                        <span class="text-xxs font-bold text-slate-400 uppercase tracking-widest block">Gross Earnings</span>
                        <span id="label_gross" class="text-md font-bold text-emerald-400">₹0.00</span>
                    </div>
                    <div>
                        <span class="text-xxs font-bold text-slate-400 uppercase tracking-widest block">Total Deductions</span>
                        <span id="label_deductions" class="text-md font-bold text-rose-450">₹0.00</span>
                    </div>
                    <div class="col-span-2 md:col-span-1">
                        <span class="text-xxs font-bold text-slate-400 uppercase tracking-widest block">Net Payout</span>
                        <span id="label_net" class="text-lg font-black text-white">₹0.00</span>
                    </div>
                </div>

                <button type="submit"
                    class="w-full md:w-auto px-8 py-3.5 bg-gradient-to-r from-purple-600 to-indigo-600 hover:from-purple-500 hover:to-indigo-500 text-white rounded-xl text-sm font-semibold transition-all shadow-lg shadow-purple-500/10">
                    <i class="fa-solid fa-calculator mr-2"></i> Generate & Save Payslip
                </button>
            </div>
        </form>
    </div>

    <!-- Right Column: Bulk Engine & CSV Template Download -->
    <div class="glass-dark p-6 sm:p-8 rounded-3xl border border-slate-200 shadow-sm flex flex-col justify-between h-fit space-y-6">
        <div>
            <h3 class="font-outfit font-bold text-xl text-slate-800 mb-2 flex items-center">
                <i class="fa-solid fa-file-invoice text-pink-600 mr-3"></i> Generate Bulk Payslips
            </h3>
            <p class="text-xs text-slate-500 leading-relaxed mb-6">
                Upload a structured CSV spreadsheet containing detailed salary components to run bulk generation in one click.
            </p>

            <div class="p-4 rounded-xl bg-slate-50 border border-slate-200 space-y-3">
                <span class="block font-semibold text-slate-700 text-xs"><i class="fa-solid fa-circle-info text-purple-600 mr-1.5"></i>CSV File Structure</span>
                <p class="text-[11px] text-slate-500 leading-relaxed">
                    The CSV table must contain the columns corresponding to all details:
                </p>
                <code class="block p-2 rounded bg-slate-100 text-[10px] text-purple-700 font-mono select-all break-all leading-normal">
                    employee_id,month,type,working_days,net_payable_days,ot_days,pay_mode,basic_salary,hra,medical_allowance,special_allowance,leave_encashment,ot_allowance,professional_tax,provident_fund,esic
                </code>
            </div>
        </div>

        <div class="space-y-4">
            <a href="{{ route('admin.payslips.template') }}"
                class="block w-full text-center px-4 py-3 bg-slate-100 hover:bg-slate-200 border border-slate-350 text-slate-700 rounded-xl text-xs font-bold transition-all">
                <i class="fa-solid fa-download mr-2"></i> Download Sample CSV Template
            </a>

            <form action="{{ route('admin.payslips.bulk') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
                @csrf

                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-650 mb-2">Upload CSV Data Sheet</label>
                    <input type="file" id="csv_file_payslip" name="csv_file" accept=".csv" required
                        class="block w-full text-sm text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-pink-600 file:text-white hover:file:bg-pink-500 cursor-pointer">
                </div>

                <button type="submit"
                    class="w-full px-6 py-3.5 bg-gradient-to-r from-pink-600 to-rose-600 hover:from-pink-500 hover:to-rose-500 text-white rounded-xl text-sm font-semibold transition-all shadow-lg shadow-pink-500/10">
                    <i class="fa-solid fa-gears mr-2"></i> Run Bulk Payslips Engine
                </button>
            </form>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    let activeCTC = 0;

    function onEmployeeSelect(select) {
        const option = select.options[select.selectedIndex];
        if (!option || !option.value) {
            activeCTC = 0;
            clearForm();
            return;
        }

        activeCTC = parseFloat(option.getAttribute('data-salary')) || 0;
        recalculateDefaultSalary();
    }

    function clearForm() {
        document.getElementById('basic_salary').value = '0.00';
        document.getElementById('hra').value = '0.00';
        document.getElementById('medical_allowance').value = '0.00';
        document.getElementById('special_allowance').value = '0.00';
        document.getElementById('leave_encashment').value = '0.00';
        document.getElementById('ot_allowance').value = '0.00';
        document.getElementById('professional_tax').value = '0.00';
        document.getElementById('provident_fund').value = '0.00';
        document.getElementById('esic').value = '0.00';
        recalculate();
    }

    function recalculateDefaultSalary() {
        if (!activeCTC) return;

        const workingDays = parseInt(document.getElementById('working_days').value) || 31;
        const netPayableDays = parseInt(document.getElementById('net_payable_days').value) || 31;

        // Pro-rate CTC based on payable days
        const proRatedCTC = activeCTC * (netPayableDays / workingDays);

        // Standard Reverse Calculation Formulas (based on offer letter structure)
        // CTC = Basic + HRA + PF + ESIC + Professional Tax
        // Basic = (ProRatedCTC - 500) / 1.215721
        const basic = Math.max(0, (proRatedCTC - 500) / 1.215721);
        const hra = basic * 0.05;
        const gross = basic + hra;

        // Calculate PF (12% of basic)
        const pf = basic * 0.12;

        // Calculate ESIC (0.75% of gross)
        const esic = gross * 0.0075;

        // West Bengal Professional Tax Slab Calculation
        let ptax = 0;
        if (gross > 40000) {
            ptax = 200;
        } else if (gross > 25000) {
            ptax = 150;
        } else if (gross > 15000) {
            ptax = 130;
        } else if (gross > 10000) {
            ptax = 110;
        }

        document.getElementById('basic_salary').value = basic.toFixed(2);
        document.getElementById('hra').value = hra.toFixed(2);
        document.getElementById('provident_fund').value = pf.toFixed(2);
        document.getElementById('esic').value = esic.toFixed(2);
        document.getElementById('professional_tax').value = ptax.toFixed(2);

        // Defaults
        document.getElementById('medical_allowance').value = '0.00';
        document.getElementById('special_allowance').value = '0.00';
        document.getElementById('leave_encashment').value = '0.00';

        onOtDaysChange(); // Updates OT allowance if OT days > 0
    }

    function onOtDaysChange() {
        const otDays = parseInt(document.getElementById('ot_days').value) || 0;
        const basic = parseFloat(document.getElementById('basic_salary').value) || 0;
        const hra = parseFloat(document.getElementById('hra').value) || 0;
        const netPayableDays = parseInt(document.getElementById('net_payable_days').value) || 31;

        if (otDays > 0 && netPayableDays > 0) {
            const grossPerDay = (basic + hra) / netPayableDays;
            const otAllowance = grossPerDay * otDays;
            document.getElementById('ot_allowance').value = otAllowance.toFixed(2);
        } else {
            document.getElementById('ot_allowance').value = '0.00';
        }

        recalculate();
    }

    function recalculate() {
        const basic = parseFloat(document.getElementById('basic_salary').value) || 0;
        const hra = parseFloat(document.getElementById('hra').value) || 0;
        const medical = parseFloat(document.getElementById('medical_allowance').value) || 0;
        const special = parseFloat(document.getElementById('special_allowance').value) || 0;
        const leave = parseFloat(document.getElementById('leave_encashment').value) || 0;
        const otAllow = parseFloat(document.getElementById('ot_allowance').value) || 0;

        const ptax = parseFloat(document.getElementById('professional_tax').value) || 0;
        const pf = parseFloat(document.getElementById('provident_fund').value) || 0;
        const esic = parseFloat(document.getElementById('esic').value) || 0;

        const totalEarnings = basic + hra + medical + special + leave + otAllow;
        const totalDeductions = ptax + pf + esic;
        const net = totalEarnings - totalDeductions;

        document.getElementById('label_gross').textContent = '₹' + totalEarnings.toFixed(2);
        document.getElementById('label_deductions').textContent = '₹' + totalDeductions.toFixed(2);
        document.getElementById('label_net').textContent = '₹' + net.toFixed(2);
    }

    // Run initial calculation
    recalculate();
</script>
@endsection
