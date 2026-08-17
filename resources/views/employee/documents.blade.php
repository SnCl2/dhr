@extends('layouts.employee')

@section('title', 'My Documents - RMHRSolutions')
@section('page_title', 'Document Download Center')

@section('content')
<div class="space-y-8">
    <!-- Offer / Appointment Letters -->
    <div class="glass-dark p-6 sm:p-8 rounded-3xl border border-slate-850 shadow-xl">
        <h3 class="font-outfit font-bold text-lg text-white mb-6 flex items-center">
            <i class="fa-solid fa-file-contract text-purple-400 mr-3"></i> My Appointment & Joining Letters
        </h3>

        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="border-b border-slate-850 text-xs font-semibold text-slate-400 uppercase tracking-wider">
                        <th class="pb-3">Document Title</th>
                        <th class="pb-3">Subject</th>
                        <th class="pb-3">Template Format</th>
                        <th class="pb-3">Date Generated</th>
                        <th class="pb-3 text-right">Download</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-850 text-sm">
                    @forelse($offerLetters as $ol)
                    <tr class="text-slate-300 hover:bg-slate-900/20">
                        <td class="py-4 font-semibold text-white">{{ $ol->template->name }}</td>
                        <td class="py-4 text-slate-400">{{ $ol->template->subject }}</td>
                        <td class="py-4">
                            <span class="px-2 py-0.5 rounded text-[10px] font-bold uppercase tracking-wider
                                @if($ol->template->type === 'internal') bg-purple-500/10 text-purple-400
                                @else bg-pink-500/10 text-pink-400
                                @endif">
                                {{ $ol->template->type }}
                            </span>
                        </td>
                        <td class="py-4 text-slate-400">{{ $ol->created_at->format('d-M-Y') }}</td>
                        <td class="py-4 text-right">
                            <a href="{{ route('employee.download.offer-letter', $ol) }}" class="inline-flex items-center px-4 py-2 bg-purple-600 hover:bg-purple-500 text-white text-xs font-bold rounded-xl transition-all shadow-md shadow-purple-500/10">
                                <i class="fa-solid fa-cloud-arrow-down mr-1.5"></i> Download PDF
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="py-8 text-center text-slate-500">No appointment letters issued yet.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Monthly Payslips -->
    <div class="glass-dark p-6 sm:p-8 rounded-3xl border border-slate-850 shadow-xl">
        <h3 class="font-outfit font-bold text-lg text-white mb-6 flex items-center">
            <i class="fa-solid fa-file-invoice-dollar text-pink-400 mr-3"></i> My Monthly Salary Payslips
        </h3>

        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="border-b border-slate-850 text-xs font-semibold text-slate-400 uppercase tracking-wider">
                        <th class="pb-3">Salary Month</th>
                        <th class="pb-3">Basic Salary</th>
                        <th class="pb-3">Allowances</th>
                        <th class="pb-3">Deductions</th>
                        <th class="pb-3 font-semibold text-white">Net Take-Home</th>
                        <th class="pb-3 text-right">Download</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-850 text-sm">
                    @forelse($payslips as $ps)
                    <tr class="text-slate-300 hover:bg-slate-900/20">
                        <td class="py-4 font-semibold text-white">{{ $ps->month }}</td>
                        <td class="py-4">Rs. {{ number_format($ps->basic_salary, 2) }}</td>
                        <td class="py-4 text-emerald-400">+ Rs. {{ number_format($ps->allowances, 2) }}</td>
                        <td class="py-4 text-rose-400">- Rs. {{ number_format($ps->deductions, 2) }}</td>
                        <td class="py-4 font-bold text-purple-400">Rs. {{ number_format($ps->net_salary, 2) }}</td>
                        <td class="py-4 text-right">
                            <a href="{{ route('employee.download.payslip', $ps) }}" class="inline-flex items-center px-4 py-2 bg-pink-650 hover:bg-pink-500 text-white text-xs font-bold rounded-xl transition-all shadow-md shadow-pink-500/10">
                                <i class="fa-solid fa-cloud-arrow-down mr-1.5"></i> Download PDF
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="py-8 text-center text-slate-500">No payslips generated yet.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
