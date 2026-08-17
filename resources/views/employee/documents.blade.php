@extends('layouts.employee')

@section('title', 'My Documents - RMHRSolutions')
@section('page_title', 'Document Download Center')

@section('content')
<div class="space-y-8">
    <!-- Offer / Appointment Letters -->
    <div class="bg-white p-6 sm:p-8 rounded-3xl border border-slate-200 shadow-sm transition-all duration-200">
        <h3 class="font-outfit font-bold text-lg text-slate-800 mb-6 flex items-center">
            <i class="fa-solid fa-file-contract text-blue-600 mr-3"></i> My Appointment & Joining Letters
        </h3>

        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="border-b border-slate-200 text-xs font-semibold text-slate-400 uppercase tracking-wider">
                        <th class="pb-3">Document Title</th>
                        <th class="pb-3">Subject Reference</th>
                        <th class="pb-3">Status</th>
                        <th class="pb-3">Date Generated</th>
                        <th class="pb-3 text-right">Download</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-sm">
                    @forelse($offerLetters as $ol)
                    <tr class="text-slate-650 hover:bg-slate-50/80 transition-colors duration-150">
                        <td class="py-4 font-semibold text-slate-800">Employment Contract & Appointment Letter</td>
                        <td class="py-4 text-slate-500">OFFER CUM APPOINTMENT LETTER</td>
                        <td class="py-4">
                            <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold uppercase tracking-wider bg-blue-100 text-blue-700">
                                Active Contract
                            </span>
                        </td>
                        <td class="py-4 text-slate-500">{{ $ol->created_at->format('d-M-Y') }}</td>
                        <td class="py-4 text-right">
                            <a href="{{ route('employee.download.offer-letter', $ol) }}" class="inline-flex items-center px-4 py-2.5 bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 text-white text-xs font-bold rounded-xl transition-all shadow-md shadow-blue-500/10">
                                <i class="fa-solid fa-cloud-arrow-down mr-1.5"></i> Download PDF
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="py-8 text-center text-slate-400">No appointment letters issued yet.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Monthly Payslips -->
    <div class="bg-white p-6 sm:p-8 rounded-3xl border border-slate-200 shadow-sm transition-all duration-200">
        <h3 class="font-outfit font-bold text-lg text-slate-800 mb-6 flex items-center">
            <i class="fa-solid fa-file-invoice-dollar text-blue-600 mr-3"></i> My Monthly Salary Payslips
        </h3>

        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="border-b border-slate-200 text-xs font-semibold text-slate-400 uppercase tracking-wider">
                        <th class="pb-3">Salary Month</th>
                        <th class="pb-3">Basic Salary</th>
                        <th class="pb-3">Allowances</th>
                        <th class="pb-3">Deductions</th>
                        <th class="pb-3 font-semibold text-slate-800">Net Take-Home</th>
                        <th class="pb-3 text-right">Download</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-sm">
                    @forelse($payslips as $ps)
                    <tr class="text-slate-650 hover:bg-slate-50/80 transition-colors duration-150">
                        <td class="py-4 font-semibold text-slate-800">{{ $ps->month }}</td>
                        <td class="py-4">Rs. {{ number_format($ps->basic_salary, 2) }}</td>
                        <td class="py-4 text-emerald-600 font-medium">+ Rs. {{ number_format($ps->allowances, 2) }}</td>
                        <td class="py-4 text-rose-600 font-medium">- Rs. {{ number_format($ps->deductions, 2) }}</td>
                        <td class="py-4 font-bold text-blue-700">Rs. {{ number_format($ps->net_salary, 2) }}</td>
                        <td class="py-4 text-right">
                            <a href="{{ route('employee.download.payslip', $ps) }}" class="inline-flex items-center px-4 py-2.5 bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 text-white text-xs font-bold rounded-xl transition-all shadow-md shadow-blue-500/10">
                                <i class="fa-solid fa-cloud-arrow-down mr-1.5"></i> Download PDF
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="py-8 text-center text-slate-400">No payslips generated yet.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
