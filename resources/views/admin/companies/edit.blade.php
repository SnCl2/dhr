@extends('layouts.admin')

@section('title', 'Edit Company - RM HR Solutions')
@section('page_title', 'Update Company Profile')

@section('content')
<div class="max-w-5xl mx-auto space-y-6">
    <form action="{{ route('admin.companies.update', $company) }}" method="POST" id="company-form" class="space-y-6">
        @csrf
        @method('PUT')

        <!-- 1. Company Profile Card -->
        <div class="glass-dark p-6 sm:p-8 rounded-3xl border border-slate-200 shadow-xl space-y-6">
            <h3 class="font-outfit font-bold text-xl text-slate-850 flex items-center">
                <i class="fa-solid fa-building text-purple-650 mr-2.5"></i> Update Details for: {{ $company->name }}
            </h3>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Company Name -->
                <div>
                    <label for="name" class="block text-xs font-semibold uppercase tracking-wider text-slate-500">Company / Client Name *</label>
                    <input type="text" id="name" name="name" required value="{{ old('name', $company->name) }}"
                        class="mt-2 block w-full px-4 py-3 bg-white border border-slate-350 rounded-xl text-slate-800 focus:outline-none focus:ring-1 focus:ring-purple-500 text-sm @error('name') border-rose-500 @enderror">
                    @error('name')
                        <span class="text-xs text-rose-500 mt-1 block">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Company Address -->
                <div>
                    <label for="address" class="block text-xs font-semibold uppercase tracking-wider text-slate-500">Official / Posting Address</label>
                    <textarea id="address" name="address" rows="1"
                        class="mt-2 block w-full px-4 py-3 bg-white border border-slate-350 rounded-xl text-slate-800 focus:outline-none focus:ring-1 focus:ring-purple-500 text-sm @error('address') border-rose-500 @enderror">{{ old('address', $company->address) }}</textarea>
                    @error('address')
                        <span class="text-xs text-rose-500 mt-1 block">{{ $message }}</span>
                    @enderror
                </div>
            </div>
        </div>

        <!-- 2. Offer Letter Annexure (Salary Structure Breakdown) -->
        <div class="glass-dark p-6 sm:p-8 rounded-3xl border border-slate-200 shadow-xl space-y-6">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2 border-b border-slate-100 pb-4">
                <div>
                    <h3 class="font-outfit font-bold text-xl text-slate-850 flex items-center">
                        <i class="fa-solid fa-calculator text-indigo-600 mr-2.5"></i> Offer Letter Annexure (Salary Structure)
                    </h3>
                    <p class="text-xs text-slate-500 mt-1">Configure default salary components for employees assigned to this company. Summation totals are calculated automatically.</p>
                </div>
                <div class="flex items-center space-x-2 bg-purple-50 border border-purple-200 px-3 py-1.5 rounded-xl">
                    <span class="text-xs font-semibold text-purple-700">Auto-Calculated Totals Active</span>
                </div>
            </div>

            <!-- 3 Column Layout for Salary Components -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                
                <!-- Card 1: Earnings -->
                <div class="p-5 rounded-2xl bg-slate-50 border border-slate-200 space-y-4">
                    <div class="flex items-center justify-between border-b border-slate-200 pb-2">
                        <h4 class="text-xs font-bold uppercase tracking-wider text-slate-800 flex items-center">
                            <i class="fa-solid fa-coins text-emerald-600 mr-1.5"></i> Earnings
                        </h4>
                        <span class="text-[11px] font-semibold text-emerald-700 bg-emerald-100/70 px-2 py-0.5 rounded-full">Part A</span>
                    </div>

                    <!-- Basic -->
                    <div>
                        <label for="basic" class="block text-[11px] font-semibold uppercase text-slate-600">Basic (₹)</label>
                        <input type="number" step="0.01" min="0" id="basic" name="basic" value="{{ old('basic', $company->basic ?? '0.00') }}"
                            class="sal-input mt-1 block w-full px-3 py-2 bg-white border border-slate-300 rounded-xl text-slate-900 text-sm font-medium focus:ring-1 focus:ring-purple-500">
                    </div>

                    <!-- HRA -->
                    <div>
                        <label for="hra" class="block text-[11px] font-semibold uppercase text-slate-600">HRA (₹)</label>
                        <input type="number" step="0.01" min="0" id="hra" name="hra" value="{{ old('hra', $company->hra ?? '0.00') }}"
                            class="sal-input mt-1 block w-full px-3 py-2 bg-white border border-slate-300 rounded-xl text-slate-900 text-sm font-medium focus:ring-1 focus:ring-purple-500">
                    </div>

                    <!-- Conveyance -->
                    <div>
                        <label for="conveyance" class="block text-[11px] font-semibold uppercase text-slate-600">Conveyance (₹)</label>
                        <input type="number" step="0.01" min="0" id="conveyance" name="conveyance" value="{{ old('conveyance', $company->conveyance ?? '0.00') }}"
                            class="sal-input mt-1 block w-full px-3 py-2 bg-white border border-slate-300 rounded-xl text-slate-900 text-sm font-medium focus:ring-1 focus:ring-purple-500">
                    </div>

                    <!-- Medical Allowance -->
                    <div>
                        <label for="medical_allowance" class="block text-[11px] font-semibold uppercase text-slate-600">Medical Allowance (₹)</label>
                        <input type="number" step="0.01" min="0" id="medical_allowance" name="medical_allowance" value="{{ old('medical_allowance', $company->medical_allowance ?? '0.00') }}"
                            class="sal-input mt-1 block w-full px-3 py-2 bg-white border border-slate-300 rounded-xl text-slate-900 text-sm font-medium focus:ring-1 focus:ring-purple-500">
                    </div>

                    <!-- SP Allowance -->
                    <div>
                        <label for="sp_allowance" class="block text-[11px] font-semibold uppercase text-slate-600">SP Allowance (₹)</label>
                        <input type="number" step="0.01" min="0" id="sp_allowance" name="sp_allowance" value="{{ old('sp_allowance', $company->sp_allowance ?? '0.00') }}"
                            class="sal-input mt-1 block w-full px-3 py-2 bg-white border border-slate-300 rounded-xl text-slate-900 text-sm font-medium focus:ring-1 focus:ring-purple-500">
                    </div>

                    <!-- Gross Earning (A) Display -->
                    <div class="pt-3 border-t border-slate-200">
                        <div class="flex justify-between items-center bg-emerald-50 border border-emerald-200 p-3 rounded-xl">
                            <span class="text-xs font-bold text-emerald-900">GROSS EARNING (A)</span>
                            <span id="disp-gross" class="text-sm font-extrabold text-emerald-700">₹ 0.00</span>
                        </div>
                    </div>
                </div>

                <!-- Card 2: Employer Contributions & CTC -->
                <div class="p-5 rounded-2xl bg-slate-50 border border-slate-200 space-y-4">
                    <div class="flex items-center justify-between border-b border-slate-200 pb-2">
                        <h4 class="text-xs font-bold uppercase tracking-wider text-slate-800 flex items-center">
                            <i class="fa-solid fa-briefcase text-blue-600 mr-1.5"></i> Employer & CTC
                        </h4>
                        <span class="text-[11px] font-semibold text-blue-700 bg-blue-100/70 px-2 py-0.5 rounded-full">Part C & Cost</span>
                    </div>

                    <!-- Bonus (C) -->
                    <div>
                        <label for="bonus" class="block text-[11px] font-semibold uppercase text-slate-600">Bonus (C) (₹)</label>
                        <input type="number" step="0.01" min="0" id="bonus" name="bonus" value="{{ old('bonus', $company->bonus ?? '0.00') }}"
                            class="sal-input mt-1 block w-full px-3 py-2 bg-white border border-slate-300 rounded-xl text-slate-900 text-sm font-medium focus:ring-1 focus:ring-purple-500">
                    </div>

                    <!-- Employer PF Contribution -->
                    <div>
                        <label for="employer_pf" class="block text-[11px] font-semibold uppercase text-slate-600">Employer PF Contribution (₹)</label>
                        <input type="number" step="0.01" min="0" id="employer_pf" name="employer_pf" value="{{ old('employer_pf', $company->employer_pf ?? '0.00') }}"
                            class="sal-input mt-1 block w-full px-3 py-2 bg-white border border-slate-300 rounded-xl text-slate-900 text-sm font-medium focus:ring-1 focus:ring-purple-500">
                    </div>

                    <!-- Employer ESIC Contribution -->
                    <div>
                        <label for="employer_esic" class="block text-[11px] font-semibold uppercase text-slate-600">Employer ESIC Contribution (₹)</label>
                        <input type="number" step="0.01" min="0" id="employer_esic" name="employer_esic" value="{{ old('employer_esic', $company->employer_esic ?? '0.00') }}"
                            class="sal-input mt-1 block w-full px-3 py-2 bg-white border border-slate-300 rounded-xl text-slate-900 text-sm font-medium focus:ring-1 focus:ring-purple-500">
                    </div>

                    <!-- LWF (Employer) -->
                    <div>
                        <label for="employer_lwf" class="block text-[11px] font-semibold uppercase text-slate-600">Employer LWF (₹)</label>
                        <input type="number" step="0.01" min="0" id="employer_lwf" name="employer_lwf" value="{{ old('employer_lwf', $company->employer_lwf ?? '0.00') }}"
                            class="sal-input mt-1 block w-full px-3 py-2 bg-white border border-slate-300 rounded-xl text-slate-900 text-sm font-medium focus:ring-1 focus:ring-purple-500">
                    </div>

                    <!-- CTC Display -->
                    <div class="pt-9 border-t border-slate-200">
                        <div class="flex justify-between items-center bg-blue-50 border border-blue-200 p-3 rounded-xl">
                            <span class="text-xs font-bold text-blue-900">CTC (COST TO CO.)</span>
                            <span id="disp-ctc" class="text-sm font-extrabold text-blue-700">₹ 0.00</span>
                        </div>
                    </div>
                </div>

                <!-- Card 3: Deductions & Net Salary -->
                <div class="p-5 rounded-2xl bg-slate-50 border border-slate-200 space-y-4">
                    <div class="flex items-center justify-between border-b border-slate-200 pb-2">
                        <h4 class="text-xs font-bold uppercase tracking-wider text-slate-800 flex items-center">
                            <i class="fa-solid fa-scissors text-rose-600 mr-1.5"></i> Deductions & Net
                        </h4>
                        <span class="text-[11px] font-semibold text-rose-700 bg-rose-100/70 px-2 py-0.5 rounded-full">Part B & Net</span>
                    </div>

                    <!-- Employee PF Contribution -->
                    <div>
                        <label for="employee_pf" class="block text-[11px] font-semibold uppercase text-slate-600">Employee PF Contribution (₹)</label>
                        <input type="number" step="0.01" min="0" id="employee_pf" name="employee_pf" value="{{ old('employee_pf', $company->employee_pf ?? '0.00') }}"
                            class="sal-input mt-1 block w-full px-3 py-2 bg-white border border-slate-300 rounded-xl text-slate-900 text-sm font-medium focus:ring-1 focus:ring-purple-500">
                    </div>

                    <!-- Employee ESIC Contribution -->
                    <div>
                        <label for="employee_esic" class="block text-[11px] font-semibold uppercase text-slate-600">Employee ESIC Contribution (₹)</label>
                        <input type="number" step="0.01" min="0" id="employee_esic" name="employee_esic" value="{{ old('employee_esic', $company->employee_esic ?? '0.00') }}"
                            class="sal-input mt-1 block w-full px-3 py-2 bg-white border border-slate-300 rounded-xl text-slate-900 text-sm font-medium focus:ring-1 focus:ring-purple-500">
                    </div>

                    <!-- Employee LWF -->
                    <div>
                        <label for="employee_lwf" class="block text-[11px] font-semibold uppercase text-slate-600">Employee LWF (₹)</label>
                        <input type="number" step="0.01" min="0" id="employee_lwf" name="employee_lwf" value="{{ old('employee_lwf', $company->employee_lwf ?? '0.00') }}"
                            class="sal-input mt-1 block w-full px-3 py-2 bg-white border border-slate-300 rounded-xl text-slate-900 text-sm font-medium focus:ring-1 focus:ring-purple-500">
                    </div>

                    <!-- Professional Tax -->
                    <div>
                        <label for="professional_tax" class="block text-[11px] font-semibold uppercase text-slate-600">Professional Tax (₹)</label>
                        <input type="number" step="0.01" min="0" id="professional_tax" name="professional_tax" value="{{ old('professional_tax', $company->professional_tax ?? '0.00') }}"
                            class="sal-input mt-1 block w-full px-3 py-2 bg-white border border-slate-300 rounded-xl text-slate-900 text-sm font-medium focus:ring-1 focus:ring-purple-500">
                    </div>

                    <!-- Total Deductions & Net Salary Display -->
                    <div class="pt-2 border-t border-slate-200 space-y-2">
                        <div class="flex justify-between items-center bg-rose-50 border border-rose-200 p-2.5 rounded-xl">
                            <span class="text-[11px] font-bold text-rose-900">TOTAL DEDUCTIONS (B)</span>
                            <span id="disp-deductions" class="text-xs font-extrabold text-rose-700">₹ 0.00</span>
                        </div>
                        <div class="flex justify-between items-center bg-purple-100/80 border border-purple-300 p-3 rounded-xl">
                            <span class="text-xs font-bold text-purple-950">NET SALARY (A - B + C)</span>
                            <span id="disp-net" class="text-sm font-extrabold text-purple-900">₹ 0.00</span>
                        </div>
                    </div>
                </div>

            </div>
        </div>

        <!-- Form Actions -->
        <div class="flex items-center justify-end space-x-3 pt-4">
            <a href="{{ route('admin.companies.index') }}" class="px-5 py-3 border border-slate-200 hover:border-slate-300 hover:bg-slate-50 rounded-xl text-xs font-semibold text-slate-650 transition-all">
                Cancel & Return
            </a>
            <button type="submit" class="px-6 py-3.5 bg-gradient-to-r from-purple-600 to-indigo-600 hover:from-purple-500 hover:to-indigo-500 text-white rounded-xl text-xs font-semibold transition-all shadow-lg shadow-purple-500/10">
                <i class="fa-solid fa-circle-check mr-1.5"></i> Update Profile & Save Annexure
            </button>
        </div>
    </form>
</div>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const getVal = (id) => parseFloat(document.getElementById(id)?.value) || 0;
        const formatMoney = (val) => '₹ ' + val.toLocaleString('en-IN', { minimumFractionDigits: 2, maximumFractionDigits: 2 });

        function recalculateSalary() {
            // Earnings
            const basic = getVal('basic');
            const hra = getVal('hra');
            const conveyance = getVal('conveyance');
            const medical = getVal('medical_allowance');
            const sp = getVal('sp_allowance');
            const gross = basic + hra + conveyance + medical + sp;
            document.getElementById('disp-gross').innerText = formatMoney(gross);

            // Employer & CTC
            const bonus = getVal('bonus');
            const emprPf = getVal('employer_pf');
            const emprEsic = getVal('employer_esic');
            const emprLwf = getVal('employer_lwf');
            const ctc = gross + bonus + emprPf + emprEsic + emprLwf;
            document.getElementById('disp-ctc').innerText = formatMoney(ctc);

            // Deductions & Net
            const empePf = getVal('employee_pf');
            const empeEsic = getVal('employee_esic');
            const empeLwf = getVal('employee_lwf');
            const ptax = getVal('professional_tax');
            const totalDeductions = empePf + empeEsic + empeLwf + ptax;
            document.getElementById('disp-deductions').innerText = formatMoney(totalDeductions);

            const netSalary = gross - totalDeductions + bonus;
            document.getElementById('disp-net').innerText = formatMoney(netSalary);
        }

        document.querySelectorAll('.sal-input').forEach(input => {
            input.addEventListener('input', recalculateSalary);
            input.addEventListener('change', recalculateSalary);
        });

        recalculateSalary();
    });
</script>
@endsection
