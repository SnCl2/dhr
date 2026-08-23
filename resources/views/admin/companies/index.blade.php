@extends('layouts.admin')

@section('title', 'Company Management - RM HR Solutions')
@section('page_title', 'Associated Companies')

@section('content')
<div class="flex justify-between items-center mb-8">
    <p class="text-sm text-slate-500">Manage companies for employee payroll assignments, offer letter templates, and department organization.</p>
    <a href="{{ route('admin.companies.create') }}" class="px-5 py-2.5 bg-gradient-to-r from-purple-600 to-indigo-600 hover:from-purple-500 hover:to-indigo-500 text-white rounded-xl text-sm font-semibold shadow-lg shadow-purple-500/10 transition-all duration-300">
        <i class="fa-solid fa-plus mr-2"></i>Create Company
    </a>
</div>

<div class="glass-dark rounded-3xl overflow-hidden shadow-xl mb-8">
    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="border-b border-slate-200 bg-slate-50 text-xs font-semibold text-slate-650 uppercase tracking-wider">
                    <th class="p-6">ID</th>
                    <th class="p-6">Company Name</th>
                    <th class="p-6">Address</th>
                    <th class="p-6">Annexure CTC / Net</th>
                    <th class="p-6">Staff Count</th>
                    <th class="p-6">Created / Updated</th>
                    <th class="p-6 text-right">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-200 text-sm">
                @forelse($companies as $company)
                <tr class="hover:bg-slate-100/40 text-slate-700">
                    <td class="p-6 font-semibold text-slate-500">{{ $company->id }}</td>
                    <td class="p-6 font-bold text-slate-900">{{ $company->name }}</td>
                    <td class="p-6 text-slate-500">{{ $company->address ?? 'No address provided' }}</td>
                    <td class="p-6">
                        @if($company->ctc > 0)
                            <div class="space-y-1 text-xs">
                                <span class="font-bold text-blue-700 block">CTC: ₹{{ number_format($company->ctc, 2) }}</span>
                                <span class="text-purple-700 font-semibold block">Net: ₹{{ number_format($company->net_salary, 2) }}</span>
                            </div>
                        @else
                            <span class="text-xs text-slate-400 italic">Not configured</span>
                        @endif
                    </td>
                    <td class="p-6">
                        <span class="inline-flex items-center justify-center px-2.5 py-1 rounded-full text-xs font-bold bg-purple-100 text-purple-700">
                            {{ $company->employees_count ?? $company->employees()->count() }} Active Staff
                        </span>
                    </td>
                    <td class="p-6 text-xs text-slate-500">
                        <div class="flex flex-col">
                            <span>C: {{ $company->created_at ? $company->created_at->format('d-M-Y h:i A') : 'N/A' }}</span>
                            <span class="text-[10px] text-slate-400 mt-0.5">U: {{ $company->updated_at ? $company->updated_at->format('d-M-Y h:i A') : 'N/A' }}</span>
                        </div>
                    </td>
                    <td class="p-6 text-right space-x-2">
                        <a href="{{ route('admin.companies.edit', $company) }}" title="Edit Company" class="inline-flex p-2 rounded-lg bg-slate-100 border border-slate-200 text-slate-600 hover:text-slate-900 hover:border-slate-350 transition-colors">
                            <i class="fa-solid fa-pen-to-square"></i>
                        </a>
                        <form action="{{ route('admin.companies.destroy', $company) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure you want to delete this company? All associated employees will be set as unassigned.')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" title="Delete Company" class="inline-flex p-2 rounded-lg bg-slate-100 border border-slate-200 text-slate-600 hover:text-rose-600 hover:border-rose-350 transition-colors">
                                <i class="fa-solid fa-trash-can"></i>
                            </button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="p-12 text-center text-slate-500 text-base">
                        <i class="fa-solid fa-building text-3xl mb-4 block"></i>
                        No companies registered yet. Get started by creating your first company.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
