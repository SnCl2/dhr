@extends('layouts.admin')

@section('title', 'Generate Payslip - Propszy')
@section('page_title', 'Monthly Payslip PDF Generator')

@section('content')
<div class="grid grid-cols-1 xl:grid-cols-12 gap-8">
    <!-- Left & Middle: Individual Payslip Form (5 cols) -->
    <div class="xl:col-span-5 glass-dark p-6 sm:p-8 rounded-3xl border border-slate-200 shadow-sm space-y-6 h-fit">
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
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                <!-- Employee -->
                <div class="sm:col-span-2">
                    <label for="employee_id" class="block text-xs font-bold uppercase tracking-wider text-slate-650">Select Staff Member</label>
                    <select id="employee_id" name="employee_id" required onchange="onEmployeeSelect(this)"
                        class="mt-2 block w-full px-3 py-3 bg-white border border-slate-300 rounded-xl text-slate-800 focus:outline-none focus:ring-1 focus:ring-purple-500 text-sm">
                        <option value="">-- Choose Employee --</option>
                        @foreach($employees->where('status', 'active') as $emp)
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
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4 pt-4 border-t border-slate-100">
                <!-- Working Days -->
                <div>
                    <label for="working_days" class="block text-xs font-bold uppercase tracking-wider text-slate-650">Working Days</label>
                    <input type="number" id="working_days" name="working_days" required value="{{ old('working_days', 31) }}" oninput="recalculate()"
                        class="mt-2 block w-full px-3 py-2.5 bg-white border border-slate-300 rounded-xl text-slate-800 focus:outline-none focus:ring-1 focus:ring-purple-500 text-xs">
                    @error('working_days') <p class="mt-1.5 text-xs text-rose-500">{{ $message }}</p> @enderror
                </div>

                <!-- Net Payable Days -->
                <div>
                    <label for="net_payable_days" class="block text-xs font-bold uppercase tracking-wider text-slate-650">Payable Days</label>
                    <input type="number" id="net_payable_days" name="net_payable_days" required value="{{ old('net_payable_days', 31) }}" oninput="recalculate()"
                        class="mt-2 block w-full px-3 py-2.5 bg-white border border-slate-300 rounded-xl text-slate-800 focus:outline-none focus:ring-1 focus:ring-purple-500 text-xs">
                    @error('net_payable_days') <p class="mt-1.5 text-xs text-rose-500">{{ $message }}</p> @enderror
                </div>

                <!-- OT Days -->
                <div>
                    <label for="ot_days" class="block text-xs font-bold uppercase tracking-wider text-slate-650">OT Days</label>
                    <input type="number" id="ot_days" name="ot_days" required value="{{ old('ot_days', 0) }}" oninput="onOtDaysChange()"
                        class="mt-2 block w-full px-3 py-2.5 bg-white border border-slate-300 rounded-xl text-slate-800 focus:outline-none focus:ring-1 focus:ring-purple-500 text-xs">
                    @error('ot_days') <p class="mt-1.5 text-xs text-rose-500">{{ $message }}</p> @enderror
                </div>

                <!-- Pay Mode -->
                <div>
                    <label for="pay_mode" class="block text-xs font-bold uppercase tracking-wider text-slate-650">Pay Mode</label>
                    <select id="pay_mode" name="pay_mode" required
                        class="mt-2 block w-full px-3 py-2.5 bg-white border border-slate-300 rounded-xl text-slate-800 focus:outline-none focus:ring-1 focus:ring-purple-500 text-xs">
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
                <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
                    <!-- Basic Salary -->
                    <div>
                        <label for="basic_salary" class="block text-xs font-bold uppercase tracking-wider text-slate-650">Basic Salary</label>
                        <input type="number" step="0.01" id="basic_salary" name="basic_salary" required value="{{ old('basic_salary', 0) }}" oninput="recalculate()"
                            class="mt-2 block w-full px-3 py-2.5 bg-white border border-slate-300 rounded-xl text-slate-800 focus:outline-none focus:ring-1 focus:ring-purple-500 text-xs">
                        @error('basic_salary') <p class="mt-1.5 text-xs text-rose-500">{{ $message }}</p> @enderror
                    </div>

                    <!-- HRA -->
                    <div>
                        <label for="hra" class="block text-xs font-bold uppercase tracking-wider text-slate-650">H.R.A.</label>
                        <input type="number" step="0.01" id="hra" name="hra" required value="{{ old('hra', 0) }}" oninput="recalculate()"
                            class="mt-2 block w-full px-3 py-2.5 bg-white border border-slate-300 rounded-xl text-slate-800 focus:outline-none focus:ring-1 focus:ring-purple-500 text-xs">
                        @error('hra') <p class="mt-1.5 text-xs text-rose-500">{{ $message }}</p> @enderror
                    </div>

                    <!-- Medical Allowance -->
                    <div>
                        <label for="medical_allowance" class="block text-xs font-bold uppercase tracking-wider text-slate-650">Medical Allowance</label>
                        <input type="number" step="0.01" id="medical_allowance" name="medical_allowance" required value="{{ old('medical_allowance', 0) }}" oninput="recalculate()"
                            class="mt-2 block w-full px-3 py-2.5 bg-white border border-slate-300 rounded-xl text-slate-800 focus:outline-none focus:ring-1 focus:ring-purple-500 text-xs">
                        @error('medical_allowance') <p class="mt-1.5 text-xs text-rose-500">{{ $message }}</p> @enderror
                    </div>

                    <!-- Special Allowance -->
                    <div>
                        <label for="special_allowance" class="block text-xs font-bold uppercase tracking-wider text-slate-650">Special Allowance</label>
                        <input type="number" step="0.01" id="special_allowance" name="special_allowance" required value="{{ old('special_allowance', 0) }}" oninput="recalculate()"
                            class="mt-2 block w-full px-3 py-2.5 bg-white border border-slate-300 rounded-xl text-slate-800 focus:outline-none focus:ring-1 focus:ring-purple-500 text-xs">
                        @error('special_allowance') <p class="mt-1.5 text-xs text-rose-500">{{ $message }}</p> @enderror
                    </div>

                    <!-- Leave Encashment -->
                    <div>
                        <label for="leave_encashment" class="block text-xs font-bold uppercase tracking-wider text-slate-650">Leave Encashment</label>
                        <input type="number" step="0.01" id="leave_encashment" name="leave_encashment" required value="{{ old('leave_encashment', 0) }}" oninput="recalculate()"
                            class="mt-2 block w-full px-3 py-2.5 bg-white border border-slate-300 rounded-xl text-slate-800 focus:outline-none focus:ring-1 focus:ring-purple-500 text-xs">
                        @error('leave_encashment') <p class="mt-1.5 text-xs text-rose-500">{{ $message }}</p> @enderror
                    </div>

                    <!-- OT Allowance -->
                    <div>
                        <label for="ot_allowance" class="block text-xs font-bold uppercase tracking-wider text-slate-650">OT Allowance</label>
                        <input type="number" step="0.01" id="ot_allowance" name="ot_allowance" required value="{{ old('ot_allowance', 0) }}" oninput="recalculate()"
                            class="mt-2 block w-full px-3 py-2.5 bg-white border border-slate-300 rounded-xl text-slate-800 focus:outline-none focus:ring-1 focus:ring-purple-500 text-xs">
                        @error('ot_allowance') <p class="mt-1.5 text-xs text-rose-500">{{ $message }}</p> @enderror
                    </div>
                </div>
            </div>

            <!-- 4. Deductions Grid -->
            <div class="pt-4 border-t border-slate-100 space-y-4">
                <h4 class="text-xs font-bold text-rose-600 uppercase tracking-wider"><i class="fa-solid fa-circle-arrow-down mr-2"></i>Deductions</h4>
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                    <!-- Professional Tax -->
                    <div>
                        <label for="professional_tax" class="block text-xs font-bold uppercase tracking-wider text-slate-650">Professional Tax</label>
                        <input type="number" step="0.01" id="professional_tax" name="professional_tax" required value="{{ old('professional_tax', 0) }}" oninput="recalculate()"
                            class="mt-2 block w-full px-3 py-2.5 bg-white border border-slate-300 rounded-xl text-slate-800 focus:outline-none focus:ring-1 focus:ring-purple-500 text-xs">
                        @error('professional_tax') <p class="mt-1.5 text-xs text-rose-500">{{ $message }}</p> @enderror
                    </div>

                    <!-- Provident Fund -->
                    <div>
                        <label for="provident_fund" class="block text-xs font-bold uppercase tracking-wider text-slate-650">Provident Fund (PF)</label>
                        <input type="number" step="0.01" id="provident_fund" name="provident_fund" required value="{{ old('provident_fund', 0) }}" oninput="recalculate()"
                            class="mt-2 block w-full px-3 py-2.5 bg-white border border-slate-300 rounded-xl text-slate-800 focus:outline-none focus:ring-1 focus:ring-purple-500 text-xs">
                        @error('provident_fund') <p class="mt-1.5 text-xs text-rose-500">{{ $message }}</p> @enderror
                    </div>

                    <!-- ESIC -->
                    <div>
                        <label for="esic" class="block text-xs font-bold uppercase tracking-wider text-slate-650">ESIC</label>
                        <input type="number" step="0.01" id="esic" name="esic" required value="{{ old('esic', 0) }}" oninput="recalculate()"
                            class="mt-2 block w-full px-3 py-2.5 bg-white border border-slate-300 rounded-xl text-slate-800 focus:outline-none focus:ring-1 focus:ring-purple-500 text-xs">
                        @error('esic') <p class="mt-1.5 text-xs text-rose-500">{{ $message }}</p> @enderror
                    </div>
                </div>
            </div>

            <!-- Live Net Payout Summary Block -->
            <div class="p-6 rounded-2xl bg-slate-900 border border-slate-850 flex flex-col items-center justify-between gap-4">
                <div class="grid grid-cols-3 gap-x-6 gap-y-2 text-left w-full">
                    <div>
                        <span class="text-[10px] font-bold text-slate-450 uppercase tracking-wider block">Gross Pay</span>
                        <span id="label_gross" class="text-sm font-bold text-emerald-400">₹0.00</span>
                    </div>
                    <div>
                        <span class="text-[10px] font-bold text-slate-450 uppercase tracking-wider block">Deductions</span>
                        <span id="label_deductions" class="text-sm font-bold text-rose-450">₹0.00</span>
                    </div>
                    <div>
                        <span class="text-[10px] font-bold text-slate-450 uppercase tracking-wider block">Net Takehome</span>
                        <span id="label_net" class="text-md font-black text-white">₹0.00</span>
                    </div>
                </div>

                <button type="submit"
                    class="w-full px-8 py-3 bg-gradient-to-r from-purple-600 to-indigo-600 hover:from-purple-500 hover:to-indigo-500 text-white rounded-xl text-sm font-semibold transition-all shadow-lg shadow-purple-500/10">
                    <i class="fa-solid fa-calculator mr-2"></i> Generate & Save Payslip
                </button>
            </div>
        </form>
    </div>

    <!-- Right: Bulk Payslip Generation (7 cols) -->
    <div class="xl:col-span-7 space-y-8">
        <!-- Step 1: Prefilled CSV Template Generator -->
        <div class="glass-dark p-6 sm:p-8 rounded-3xl border border-slate-200 shadow-sm space-y-6">
            <h3 class="font-outfit font-bold text-xl text-slate-800 mb-2 flex items-center">
                <i class="fa-solid fa-file-csv text-emerald-600 mr-3"></i> Step 1: Download Prefilled CSV Template
            </h3>
            <p class="text-xs text-slate-500 leading-relaxed mb-6">
                Filter employees below, select who you want to include, and download a template prefilled with their basic details and salary calculations.
            </p>

            <!-- Dynamic Filters -->
            <div class="p-4 rounded-2xl bg-slate-50 border border-slate-200 space-y-4">
                <h4 class="text-xs font-bold text-slate-800 uppercase tracking-wider">Employee Search & Filters</h4>
                
                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-4">
                    <!-- Search name/id -->
                    <div>
                        <label class="block text-[10px] font-semibold uppercase tracking-wider text-slate-500">Search Name/ID</label>
                        <input type="text" id="empSearch" oninput="filterEmployees()" placeholder="Search..."
                            class="mt-1.5 block w-full px-3 py-2 bg-white border border-slate-350 rounded-lg text-slate-800 focus:outline-none focus:ring-1 focus:ring-emerald-500 text-xs">
                    </div>

                    <!-- Department -->
                    <div>
                        <label class="block text-[10px] font-semibold uppercase tracking-wider text-slate-500">Department</label>
                        <select id="filterDept" onchange="filterEmployees()"
                            class="mt-1.5 block w-full px-3 py-2 bg-white border border-slate-355 rounded-lg text-slate-800 focus:outline-none focus:ring-1 focus:ring-emerald-500 text-xs">
                            <option value="">All Departments</option>
                            @foreach($departments as $dept)
                                <option value="{{ $dept->id }}">{{ $dept->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Designation -->
                    <div>
                        <label class="block text-[10px] font-semibold uppercase tracking-wider text-slate-500">Designation</label>
                        <select id="filterDesg" onchange="filterEmployees()"
                            class="mt-1.5 block w-full px-3 py-2 bg-white border border-slate-355 rounded-lg text-slate-800 focus:outline-none focus:ring-1 focus:ring-emerald-500 text-xs">
                            <option value="">All Designations</option>
                            @foreach($designations as $desg)
                                <option value="{{ $desg->id }}">{{ $desg->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Status -->
                    <div>
                        <label class="block text-[10px] font-semibold uppercase tracking-wider text-slate-500">Status</label>
                        <select id="filterStatus" onchange="filterEmployees()"
                            class="mt-1.5 block w-full px-3 py-2 bg-white border border-slate-355 rounded-lg text-slate-800 focus:outline-none focus:ring-1 focus:ring-emerald-500 text-xs">
                            <option value="active">Active Staff (Default)</option>
                            <option value="pending_review">Pending Review</option>
                            <option value="inactive">Inactive</option>
                            <option value="on_leave">On Leave</option>
                            <option value="terminated">Terminated</option>
                            <option value="">All Statuses</option>
                        </select>
                    </div>

                    <!-- Client Name -->
                    <div>
                        <label class="block text-[10px] font-semibold uppercase tracking-wider text-slate-500">Client Name</label>
                        <select id="filterClient" onchange="filterEmployees()"
                            class="mt-1.5 block w-full px-3 py-2 bg-white border border-slate-355 rounded-lg text-slate-800 focus:outline-none focus:ring-1 focus:ring-emerald-500 text-xs">
                            <option value="">All Clients</option>
                            @foreach($clientNames as $client)
                                <option value="{{ $client }}">{{ $client }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Work Location -->
                    <div>
                        <label class="block text-[10px] font-semibold uppercase tracking-wider text-slate-500">Work Location</label>
                        <select id="filterLocation" onchange="filterEmployees()"
                            class="mt-1.5 block w-full px-3 py-2 bg-white border border-slate-355 rounded-lg text-slate-800 focus:outline-none focus:ring-1 focus:ring-emerald-500 text-xs">
                            <option value="">All Locations</option>
                            @foreach($workLocations as $loc)
                                <option value="{{ $loc }}">{{ $loc }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Assigned Company -->
                    <div>
                        <label class="block text-[10px] font-semibold uppercase tracking-wider text-slate-500">Assigned Company</label>
                        <select id="filterCompany" onchange="filterEmployees()"
                            class="mt-1.5 block w-full px-3 py-2 bg-white border border-slate-355 rounded-lg text-slate-800 focus:outline-none focus:ring-1 focus:ring-emerald-500 text-xs">
                            <option value="">All Companies</option>
                            @foreach($companies as $company)
                                <option value="{{ $company->id }}">{{ $company->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>

            <!-- Pre-fill form details & selection list -->
            <form action="{{ route('admin.payslips.download-prefilled') }}" method="POST" id="downloadTemplateForm" class="space-y-6">
                @csrf

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <!-- Template Month -->
                    <div>
                        <label for="template_month" class="block text-xs font-semibold uppercase tracking-wider text-slate-550">Prefilled Month</label>
                        <input type="text" id="template_month" name="month" required value="{{ date('F Y') }}"
                            class="mt-2 block w-full px-4 py-2.5 bg-white border border-slate-300 rounded-xl text-slate-800 focus:outline-none focus:ring-1 focus:ring-emerald-500 text-sm">
                    </div>

                    <!-- Template Format -->
                    <div>
                        <label for="template_type" class="block text-xs font-semibold uppercase tracking-wider text-slate-555">Prefilled Style</label>
                        <select id="template_type" name="type" required
                            class="mt-2 block w-full px-3 py-2.5 bg-white border border-slate-300 rounded-xl text-slate-800 focus:outline-none focus:ring-1 focus:ring-emerald-500 text-sm">
                            <option value="external">External Style</option>
                            <option value="internal">Internal Style</option>
                        </select>
                    </div>
                </div>

                <!-- Employee Checkbox Selection List -->
                <div>
                    <div class="flex justify-between items-center mb-2">
                        <label class="block text-xs font-semibold uppercase tracking-wider text-slate-550">Select Employees</label>
                        <span id="selectedCount" class="text-xs text-emerald-600 font-bold">0 selected</span>
                    </div>

                    <div class="rounded-xl border border-slate-200 bg-white max-h-72 overflow-y-auto">
                        <table class="w-full text-left border-collapse text-xs">
                            <thead class="sticky top-0 bg-slate-100 border-b border-slate-200 text-slate-600 uppercase tracking-wider font-bold">
                                <tr>
                                    <th class="py-2 px-3 w-10">
                                        <input type="checkbox" id="selectAll" class="rounded border-slate-300 bg-white text-emerald-600 focus:ring-emerald-500 cursor-pointer">
                                    </th>
                                    <th class="py-2 px-3">Employee</th>
                                    <th class="py-2 px-3">Dept/Desg</th>
                                    <th class="py-2 px-3">Company</th>
                                    <th class="py-2 px-3">Client/Loc</th>
                                    <th class="py-2 px-3 text-right">Base CTC</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100 text-slate-700">
                                @forelse($employees as $emp)
                                    <tr class="employee-row hover:bg-slate-50 transition-colors"
                                        data-id="{{ $emp->id }}"
                                        data-name="{{ strtolower($emp->full_name) }}"
                                        data-empid="{{ strtolower($emp->employee_id) }}"
                                        data-dept="{{ $emp->department_id }}"
                                        data-desg="{{ $emp->designation_id }}"
                                        data-company="{{ $emp->company_id }}"
                                        data-status="{{ $emp->status }}"
                                        data-client="{{ strtolower($emp->client_name) }}"
                                        data-location="{{ strtolower($emp->work_location) }}">
                                        <td class="py-2 px-3">
                                            <input type="checkbox" name="employee_ids[]" value="{{ $emp->id }}" onchange="updateSelectedCount()"
                                                class="employee-checkbox rounded border-slate-300 bg-white text-emerald-600 focus:ring-emerald-500 cursor-pointer">
                                        </td>
                                        <td class="py-2 px-3">
                                            <span class="block font-semibold text-slate-800">{{ $emp->full_name }}</span>
                                            <span class="block text-[10px] text-slate-450">{{ $emp->employee_id }} ({{ ucfirst(str_replace('_', ' ', $emp->status)) }})</span>
                                        </td>
                                        <td class="py-2 px-3">
                                            <span class="block">{{ $emp->department->name ?? 'N/A' }}</span>
                                            <span class="block text-[10px] text-slate-450">{{ $emp->designationRelation->name ?? 'N/A' }}</span>
                                        </td>
                                        <td class="py-2 px-3">
                                            <span class="block font-semibold text-slate-750">{{ $emp->company->name ?? 'N/A' }}</span>
                                        </td>
                                        <td class="py-2 px-3">
                                            <span class="block">{{ $emp->client_name ?? 'N/A' }}</span>
                                            <span class="block text-[10px] text-slate-450">{{ $emp->work_location ?? 'N/A' }}</span>
                                        </td>
                                        <td class="py-2 px-3 text-right font-mono text-emerald-600 font-semibold">
                                            ₹{{ number_format($emp->salary ?? 0, 2) }}
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="py-4 text-center text-slate-400">No staff found on database.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="pt-4 border-t border-slate-100 flex justify-end">
                    <button type="submit"
                        class="w-full sm:w-auto px-6 py-3.5 bg-gradient-to-r from-emerald-600 to-teal-600 hover:from-emerald-500 hover:to-teal-500 text-white rounded-xl text-sm font-semibold transition-all shadow-lg shadow-emerald-500/10">
                        <i class="fa-solid fa-cloud-arrow-down mr-2"></i> Download Prefilled CSV Template
                    </button>
                </div>
            </form>
        </div>

        <!-- Step 2: Upload completed CSV & run processor -->
        <div class="glass-dark p-6 sm:p-8 rounded-3xl border border-slate-200 shadow-sm space-y-6">
            <h3 class="font-outfit font-bold text-xl text-slate-800 mb-2 flex items-center">
                <i class="fa-solid fa-file-invoice text-pink-400 mr-3"></i> Step 2: Upload Completed CSV & Generate
            </h3>
            <p class="text-xs text-slate-500 leading-relaxed mb-6">
                Upload your completed CSV spreadsheet containing the final pro-rated calculations. The system will bulk compile the PDFs and register payslips.
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

            <form action="{{ route('admin.payslips.bulk') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
                @csrf

                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-650 mb-2">Upload CSV Data Sheet</label>
                    <input type="file" id="csv_file_payslip" name="csv_file" accept=".csv" required
                        class="block w-full text-sm text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-pink-650 file:text-white hover:file:bg-pink-500 cursor-pointer">
                </div>

                <div class="pt-6 border-t border-slate-150 flex justify-end">
                    <button type="submit"
                        class="w-full px-6 py-3.5 bg-gradient-to-r from-pink-600 to-rose-600 hover:from-pink-500 hover:to-rose-500 text-white rounded-xl text-sm font-semibold transition-all shadow-lg shadow-pink-500/10">
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

    function filterEmployees() {
        const searchVal = document.getElementById('empSearch').value.toLowerCase().trim();
        const deptVal = document.getElementById('filterDept').value;
        const desgVal = document.getElementById('filterDesg').value;
        const statusVal = document.getElementById('filterStatus').value;
        const clientVal = document.getElementById('filterClient').value.toLowerCase();
        const locationVal = document.getElementById('filterLocation').value.toLowerCase();
        const companyVal = document.getElementById('filterCompany').value;

        const rows = document.querySelectorAll('.employee-row');
        rows.forEach(row => {
            const name = row.getAttribute('data-name');
            const empid = row.getAttribute('data-empid');
            const dept = row.getAttribute('data-dept');
            const desg = row.getAttribute('data-desg');
            const status = row.getAttribute('data-status');
            const client = row.getAttribute('data-client');
            const location = row.getAttribute('data-location');
            const company = row.getAttribute('data-company');

            const matchesSearch = !searchVal || name.includes(searchVal) || empid.includes(searchVal);
            const matchesDept = !deptVal || dept === deptVal;
            const matchesDesg = !desgVal || desg === desgVal;
            const matchesStatus = !statusVal || status === statusVal;
            const matchesClient = !clientVal || client.includes(clientVal);
            const matchesLocation = !locationVal || location.includes(locationVal);
            const matchesCompany = !companyVal || company === companyVal;

            if (matchesSearch && matchesDept && matchesDesg && matchesStatus && matchesClient && matchesLocation && matchesCompany) {
                row.style.display = '';
            } else {
                row.style.display = 'none';
            }
        });

        updateSelectedCount();
    }

    function updateSelectedCount() {
        const checkedBoxes = document.querySelectorAll('.employee-checkbox:checked');
        let activeCount = 0;
        
        checkedBoxes.forEach(box => {
            const row = box.closest('.employee-row');
            if (row && row.style.display !== 'none') {
                activeCount++;
            }
        });

        document.getElementById('selectedCount').textContent = `${activeCount} selected`;
    }

    // Select All Checkbox Handler (only selects visible rows)
    document.getElementById('selectAll').addEventListener('change', function() {
        const isChecked = this.checked;
        const rows = document.querySelectorAll('.employee-row');
        rows.forEach(row => {
            if (row.style.display !== 'none') {
                const checkbox = row.querySelector('.employee-checkbox');
                if (checkbox) {
                    checkbox.checked = isChecked;
                }
            }
        });
        updateSelectedCount();
    });

    // Run initial calculation and filter to restrict list to Active Staff on page load
    window.addEventListener('DOMContentLoaded', () => {
        recalculate();
        filterEmployees();
    });
</script>
@endsection
