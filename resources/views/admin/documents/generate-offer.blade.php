@extends('layouts.admin')

@section('title', 'Generate Offer Letter - RM HR Solutions')
@section('page_title', 'Offer Letter PDF Generator')

@section('content')
<div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
    <!-- Left: Individual Generation Form -->
    <div class="lg:col-span-7 glass-dark p-6 sm:p-8 rounded-3xl border border-slate-200 shadow-xl space-y-6">
        <div>
            <h3 class="font-outfit font-bold text-xl text-slate-850 flex items-center">
                <i class="fa-solid fa-file-signature text-purple-650 mr-2.5"></i> Generate Individual Offer Letter
            </h3>
            <p class="text-xs text-slate-500 mt-1">
                Select a candidate. Their assigned Company's Annexure salary structure will be loaded automatically.
            </p>
        </div>

        <form action="{{ route('admin.offer-letters.generate.submit') }}" method="POST" id="offer-letter-form" class="space-y-6">
            @csrf

            <!-- Employee Selection -->
            <div>
                <label for="employee_id" class="block text-xs font-semibold uppercase tracking-wider text-slate-500">Select Candidate / Staff *</label>
                <select id="employee_id" name="employee_id" required
                    class="mt-2 block w-full px-3 py-3 bg-white border border-slate-350 rounded-xl text-slate-800 focus:outline-none focus:ring-1 focus:ring-purple-500 text-sm">
                    <option value="">-- Choose Candidate --</option>
                    @foreach($employees as $emp)
                        <option value="{{ $emp->id }}" 
                            data-company-id="{{ $emp->company_id }}"
                            data-company-name="{{ $emp->company ? $emp->company->name : 'No Company Assigned' }}"
                            data-salary="{{ $emp->salary }}"
                            data-joining="{{ $emp->joining_date ? $emp->joining_date->format('Y-m-d') : '' }}"
                            {{ old('employee_id', $selectedEmployeeId ?? '') == $emp->id ? 'selected' : '' }}>
                            {{ $emp->full_name }} ({{ $emp->employee_id }}) &mdash; {{ $emp->company ? $emp->company->name : 'Unassigned' }}
                        </option>
                    @endforeach
                </select>
                @error('employee_id')
                    <p class="mt-1.5 text-xs text-rose-500">{{ $message }}</p>
                @enderror
            </div>

            <!-- Selected Company Info Banner -->
            <div id="company-info-banner" class="hidden p-4 rounded-2xl bg-purple-50 border border-purple-200">
                <div class="flex items-center justify-between">
                    <div class="flex items-center space-x-3">
                        <div class="w-9 h-9 rounded-xl bg-purple-600 text-white flex items-center justify-center text-sm shadow-md">
                            <i class="fa-solid fa-building"></i>
                        </div>
                        <div>
                            <span class="text-xs text-purple-600 font-semibold block uppercase">Assigned Client / Company</span>
                            <span id="banner-comp-name" class="text-sm font-bold text-slate-900">Acme Corp</span>
                        </div>
                    </div>
                    <div class="text-right">
                        <span class="text-xs text-slate-500 block">Annexure CTC</span>
                        <span id="banner-comp-ctc" class="text-sm font-extrabold text-purple-700">₹ 0.00</span>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                <!-- Offer Letter Type -->
                <div>
                    <label for="type" class="block text-xs font-semibold uppercase tracking-wider text-slate-500">Letter Type *</label>
                    <select id="type" name="type" required
                        class="mt-2 block w-full px-3 py-3 bg-white border border-slate-350 rounded-xl text-slate-800 focus:outline-none focus:ring-1 focus:ring-purple-500 text-sm">
                        <option value="internal" {{ old('type') == 'internal' ? 'selected' : '' }}>Internal Staff Layout</option>
                        <option value="external" {{ old('type') == 'external' ? 'selected' : '' }}>External/Contractor Layout</option>
                    </select>
                    @error('type')
                        <p class="mt-1.5 text-xs text-rose-500">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Joining Date Override -->
                <div>
                    <label for="joining_date" class="block text-xs font-semibold uppercase tracking-wider text-slate-500">Joining Date (Optional)</label>
                    <input type="date" id="joining_date" name="joining_date" value="{{ old('joining_date') }}"
                        class="mt-2 block w-full px-4 py-3 bg-white border border-slate-350 rounded-xl text-slate-800 focus:outline-none focus:ring-1 focus:ring-purple-500 text-sm">
                    @error('joining_date')
                        <p class="mt-1.5 text-xs text-rose-500">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Annexure Salary Breakdown Accordion -->
            <div class="border border-slate-200 rounded-2xl overflow-hidden bg-slate-50">
                <button type="button" id="toggle-annexure-btn" class="w-full px-4 py-3 bg-slate-100 flex items-center justify-between text-left hover:bg-slate-200/60 transition-colors">
                    <span class="text-xs font-bold text-slate-800 flex items-center">
                        <i class="fa-solid fa-calculator text-indigo-600 mr-2"></i> Review / Override Annexure Salary Structure
                    </span>
                    <i id="toggle-chevron" class="fa-solid fa-chevron-down text-slate-500 text-xs transition-transform"></i>
                </button>

                <div id="annexure-fields-panel" class="p-4 space-y-4">
                    <p class="text-[11px] text-slate-500">Values are loaded automatically from the company. You may adjust them below for this offer letter if needed.</p>

                    <!-- Earnings Grid -->
                    <div class="space-y-3">
                        <span class="text-[11px] font-bold uppercase text-emerald-700 block border-b border-slate-200 pb-1">Earnings</span>
                        <div class="grid grid-cols-2 sm:grid-cols-3 gap-3 text-xs">
                            <div>
                                <label class="block text-[10px] text-slate-500 font-semibold uppercase">Basic (₹)</label>
                                <input type="number" step="0.01" min="0" id="basic" name="basic" class="ann-input mt-1 w-full px-2.5 py-1.5 bg-white border border-slate-300 rounded-lg text-slate-900 text-xs font-medium">
                            </div>
                            <div>
                                <label class="block text-[10px] text-slate-500 font-semibold uppercase">HRA (₹)</label>
                                <input type="number" step="0.01" min="0" id="hra" name="hra" class="ann-input mt-1 w-full px-2.5 py-1.5 bg-white border border-slate-300 rounded-lg text-slate-900 text-xs font-medium">
                            </div>
                            <div>
                                <label class="block text-[10px] text-slate-500 font-semibold uppercase">Conveyance (₹)</label>
                                <input type="number" step="0.01" min="0" id="conveyance" name="conveyance" class="ann-input mt-1 w-full px-2.5 py-1.5 bg-white border border-slate-300 rounded-lg text-slate-900 text-xs font-medium">
                            </div>
                            <div>
                                <label class="block text-[10px] text-slate-500 font-semibold uppercase">Medical Allow (₹)</label>
                                <input type="number" step="0.01" min="0" id="medical_allowance" name="medical_allowance" class="ann-input mt-1 w-full px-2.5 py-1.5 bg-white border border-slate-300 rounded-lg text-slate-900 text-xs font-medium">
                            </div>
                            <div>
                                <label class="block text-[10px] text-slate-500 font-semibold uppercase">SP Allow (₹)</label>
                                <input type="number" step="0.01" min="0" id="sp_allowance" name="sp_allowance" class="ann-input mt-1 w-full px-2.5 py-1.5 bg-white border border-slate-300 rounded-lg text-slate-900 text-xs font-medium">
                            </div>
                            <div class="flex flex-col justify-end">
                                <div class="bg-emerald-50 border border-emerald-200 p-2 rounded-lg text-right">
                                    <span class="text-[10px] text-emerald-800 font-bold block">Gross (A)</span>
                                    <span id="disp-gross" class="text-xs font-extrabold text-emerald-700">₹ 0.00</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Employer & Deductions Grid -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 pt-2 border-t border-slate-200">
                        <!-- Employer & CTC -->
                        <div class="space-y-2">
                            <span class="text-[11px] font-bold uppercase text-blue-700 block border-b border-slate-200 pb-1">Employer & CTC</span>
                            <div class="grid grid-cols-2 gap-2 text-xs">
                                <div>
                                    <label class="block text-[10px] text-slate-500 font-semibold uppercase">Bonus (C)</label>
                                    <input type="number" step="0.01" min="0" id="bonus" name="bonus" class="ann-input mt-1 w-full px-2 py-1.5 bg-white border border-slate-300 rounded-lg text-slate-900 text-xs font-medium">
                                </div>
                                <div>
                                    <label class="block text-[10px] text-slate-500 font-semibold uppercase">Employer PF</label>
                                    <input type="number" step="0.01" min="0" id="employer_pf" name="employer_pf" class="ann-input mt-1 w-full px-2 py-1.5 bg-white border border-slate-300 rounded-lg text-slate-900 text-xs font-medium">
                                </div>
                                <div>
                                    <label class="block text-[10px] text-slate-500 font-semibold uppercase">Employer ESIC</label>
                                    <input type="number" step="0.01" min="0" id="employer_esic" name="employer_esic" class="ann-input mt-1 w-full px-2 py-1.5 bg-white border border-slate-300 rounded-lg text-slate-900 text-xs font-medium">
                                </div>
                                <div>
                                    <label class="block text-[10px] text-slate-500 font-semibold uppercase">Employer LWF</label>
                                    <input type="number" step="0.01" min="0" id="employer_lwf" name="employer_lwf" class="ann-input mt-1 w-full px-2 py-1.5 bg-white border border-slate-300 rounded-lg text-slate-900 text-xs font-medium">
                                </div>
                            </div>
                            <div class="bg-blue-50 border border-blue-200 p-2 rounded-lg flex justify-between items-center">
                                <span class="text-[10px] font-bold text-blue-900">CTC (Cost to Co.)</span>
                                <span id="disp-ctc" class="text-xs font-extrabold text-blue-700">₹ 0.00</span>
                            </div>
                        </div>

                        <!-- Deductions -->
                        <div class="space-y-2">
                            <span class="text-[11px] font-bold uppercase text-rose-700 block border-b border-slate-200 pb-1">Deductions</span>
                            <div class="grid grid-cols-2 gap-2 text-xs">
                                <div>
                                    <label class="block text-[10px] text-slate-500 font-semibold uppercase">Employee PF</label>
                                    <input type="number" step="0.01" min="0" id="employee_pf" name="employee_pf" class="ann-input mt-1 w-full px-2 py-1.5 bg-white border border-slate-300 rounded-lg text-slate-900 text-xs font-medium">
                                </div>
                                <div>
                                    <label class="block text-[10px] text-slate-500 font-semibold uppercase">Employee ESIC</label>
                                    <input type="number" step="0.01" min="0" id="employee_esic" name="employee_esic" class="ann-input mt-1 w-full px-2 py-1.5 bg-white border border-slate-300 rounded-lg text-slate-900 text-xs font-medium">
                                </div>
                                <div>
                                    <label class="block text-[10px] text-slate-500 font-semibold uppercase">Employee LWF</label>
                                    <input type="number" step="0.01" min="0" id="employee_lwf" name="employee_lwf" class="ann-input mt-1 w-full px-2 py-1.5 bg-white border border-slate-300 rounded-lg text-slate-900 text-xs font-medium">
                                </div>
                                <div>
                                    <label class="block text-[10px] text-slate-500 font-semibold uppercase">Prof. Tax</label>
                                    <input type="number" step="0.01" min="0" id="professional_tax" name="professional_tax" class="ann-input mt-1 w-full px-2 py-1.5 bg-white border border-slate-300 rounded-lg text-slate-900 text-xs font-medium">
                                </div>
                            </div>
                            <div class="bg-rose-50 border border-rose-200 p-2 rounded-lg flex justify-between items-center">
                                <span class="text-[10px] font-bold text-rose-900">Total Deductions (B)</span>
                                <span id="disp-deductions" class="text-xs font-extrabold text-rose-700">₹ 0.00</span>
                            </div>
                        </div>
                    </div>

                    <!-- Net Salary Summary Banner -->
                    <div class="p-3 bg-purple-100/80 border border-purple-300 rounded-xl flex items-center justify-between">
                        <span class="text-xs font-bold text-purple-950">NET SALARY (A - B + C)</span>
                        <span id="disp-net" class="text-sm font-extrabold text-purple-900">₹ 0.00</span>
                    </div>
                </div>
            </div>

            <div class="pt-4 border-t border-slate-200 flex justify-end">
                <button type="submit"
                    class="w-full sm:w-auto px-6 py-3.5 bg-gradient-to-r from-purple-600 to-indigo-600 hover:from-purple-500 hover:to-indigo-500 text-white rounded-xl text-sm font-semibold transition-all shadow-lg shadow-purple-500/10">
                    <i class="fa-solid fa-file-pdf mr-2"></i> Generate & Store Offer Letter
                </button>
            </div>
        </form>
    </div>

    <!-- Right: Bulk Generation Form -->
    <div class="lg:col-span-5 glass-dark p-6 sm:p-8 rounded-3xl border border-slate-200 shadow-xl flex flex-col justify-between">
        <div>
            <h3 class="font-outfit font-bold text-xl text-slate-850 flex items-center">
                <i class="fa-solid fa-file-invoice text-pink-600 mr-2.5"></i> Generate Bulk Offer Letters
            </h3>
            <p class="text-xs text-slate-500 leading-relaxed mt-1 mb-6">
                Upload a CSV file with candidate IDs. Each candidate's offer letter will automatically apply the Annexure salary structure of their respective company.
            </p>

            <div class="p-4 rounded-2xl bg-pink-50/70 border border-pink-200 mb-6 text-xs text-slate-700">
                <span class="block font-bold text-pink-900 mb-1">CSV Format Requirements:</span>
                Required column: <code class="text-pink-700 font-mono bg-pink-100 px-1.5 py-0.5 rounded font-bold">employee_id</code> (optional: <code class="text-pink-700 font-mono bg-pink-100 px-1.5 py-0.5 rounded font-bold">salary,joining_date</code>).
            </div>

            <form action="{{ route('admin.offer-letters.bulk') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                @csrf

                <!-- Offer Letter Type -->
                <div>
                    <label for="bulk_type" class="block text-xs font-semibold uppercase tracking-wider text-slate-500">Letter Type *</label>
                    <select id="bulk_type" name="type" required
                        class="mt-2 block w-full px-3 py-3 bg-white border border-slate-350 rounded-xl text-slate-800 focus:outline-none focus:ring-1 focus:ring-purple-500 text-sm">
                        <option value="internal">Internal Staff Layout</option>
                        <option value="external">External/Contractor Layout</option>
                    </select>
                    @error('type')
                        <p class="mt-1.5 text-xs text-rose-500">{{ $message }}</p>
                    @enderror
                </div>

                <!-- CSV File -->
                <div>
                    <label for="csv_file" class="block text-xs font-semibold uppercase tracking-wider text-slate-500 mb-2">Select CSV Data File</label>
                    <input type="file" id="csv_file" name="csv_file" accept=".csv" required
                        class="block w-full text-sm text-slate-500 file:mr-4 file:py-2.5 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-pink-600 file:text-white hover:file:bg-pink-500 cursor-pointer">
                </div>

                <div class="pt-6 border-t border-slate-200 flex justify-end">
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

@section('scripts')
<script>
    const companiesData = @json($companies->keyBy('id'));

    document.addEventListener('DOMContentLoaded', function () {
        const empSelect = document.getElementById('employee_id');
        const companyBanner = document.getElementById('company-info-banner');
        const bannerCompName = document.getElementById('banner-comp-name');
        const bannerCompCtc = document.getElementById('banner-comp-ctc');

        const fields = [
            'basic', 'hra', 'conveyance', 'medical_allowance', 'sp_allowance',
            'bonus', 'employer_pf', 'employer_esic', 'employer_lwf',
            'employee_pf', 'employee_esic', 'employee_lwf', 'professional_tax'
        ];

        const getVal = (id) => parseFloat(document.getElementById(id)?.value) || 0;
        const formatMoney = (val) => '₹ ' + val.toLocaleString('en-IN', { minimumFractionDigits: 2, maximumFractionDigits: 2 });

        function recalculate() {
            const basic = getVal('basic');
            const hra = getVal('hra');
            const conveyance = getVal('conveyance');
            const medical = getVal('medical_allowance');
            const sp = getVal('sp_allowance');
            const gross = basic + hra + conveyance + medical + sp;
            document.getElementById('disp-gross').innerText = formatMoney(gross);

            const bonus = getVal('bonus');
            const emprPf = getVal('employer_pf');
            const emprEsic = getVal('employer_esic');
            const emprLwf = getVal('employer_lwf');
            const ctc = gross + bonus + emprPf + emprEsic + emprLwf;
            document.getElementById('disp-ctc').innerText = formatMoney(ctc);

            const empePf = getVal('employee_pf');
            const empeEsic = getVal('employee_esic');
            const empeLwf = getVal('employee_lwf');
            const ptax = getVal('professional_tax');
            const totalDeductions = empePf + empeEsic + empeLwf + ptax;
            document.getElementById('disp-deductions').innerText = formatMoney(totalDeductions);

            const netSalary = gross - totalDeductions + bonus;
            document.getElementById('disp-net').innerText = formatMoney(netSalary);
        }

        function loadEmployeeCompany() {
            const opt = empSelect.options[empSelect.selectedIndex];
            if (!opt || !opt.value) {
                companyBanner.classList.add('hidden');
                return;
            }

            const compId = opt.getAttribute('data-company-id');
            const joining = opt.getAttribute('data-joining');
            if (joining) {
                document.getElementById('joining_date').value = joining;
            }

            if (compId && companiesData[compId]) {
                const comp = companiesData[compId];
                companyBanner.classList.remove('hidden');
                bannerCompName.innerText = comp.name;
                bannerCompCtc.innerText = formatMoney(parseFloat(comp.ctc) || 0);

                fields.forEach(f => {
                    const input = document.getElementById(f);
                    if (input) {
                        input.value = parseFloat(comp[f]) || 0;
                    }
                });
            } else {
                companyBanner.classList.add('hidden');
            }
            recalculate();
        }

        empSelect.addEventListener('change', loadEmployeeCompany);

        document.querySelectorAll('.ann-input').forEach(inp => {
            inp.addEventListener('input', recalculate);
            inp.addEventListener('change', recalculate);
        });

        // Initial trigger
        if (empSelect.value) {
            loadEmployeeCompany();
        }
    });
</script>
@endsection
