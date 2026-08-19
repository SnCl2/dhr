@extends('layouts.admin')

@section('title', 'Department Management - RM HR Solutions')
@section('page_title', 'Manage Departments')

@section('content')
<div class="flex justify-between items-center mb-8">
    <p class="text-sm text-slate-500">Manage organization departments for employee sorting and payroll structures.</p>
    <a href="{{ route('admin.departments.create') }}" class="px-5 py-2.5 bg-gradient-to-r from-purple-600 to-indigo-600 hover:from-purple-500 hover:to-indigo-500 text-white rounded-xl text-sm font-semibold shadow-lg shadow-purple-500/10 transition-all duration-300">
        <i class="fa-solid fa-plus mr-2"></i>Create Department
    </a>
</div>

<div class="glass-dark rounded-3xl overflow-hidden shadow-xl mb-8">
    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="border-b border-slate-200 bg-slate-50 text-xs font-semibold text-slate-650 uppercase tracking-wider">
                    <th class="p-6">ID</th>
                    <th class="p-6">Department Name</th>
                    <th class="p-6">Staff Count</th>
                    <th class="p-6 text-right">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-200 text-sm">
                @forelse($departments as $dept)
                <tr class="hover:bg-slate-100/40 text-slate-700">
                    <td class="p-6 font-semibold text-slate-500">{{ $dept->id }}</td>
                    <td class="p-6 font-bold text-slate-900">{{ $dept->name }}</td>
                    <td class="p-6">
                        <span class="inline-flex items-center justify-center px-2.5 py-1 rounded-full text-xs font-bold bg-purple-100 text-purple-700">
                            {{ $dept->employees_count ?? $dept->employees()->count() }} Staff Members
                        </span>
                    </td>
                    <td class="p-6 text-right space-x-2">
                        <a href="{{ route('admin.departments.edit', $dept) }}" title="Edit Department" class="inline-flex p-2 rounded-lg bg-slate-100 border border-slate-200 text-slate-600 hover:text-slate-900 hover:border-slate-350 transition-colors">
                            <i class="fa-solid fa-pen-to-square"></i>
                        </a>
                        <form action="{{ route('admin.departments.destroy', $dept) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure you want to delete this department? Associated staff members will be set to Unassigned.')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" title="Delete Department" class="inline-flex p-2 rounded-lg bg-slate-100 border border-slate-200 text-slate-600 hover:text-rose-600 hover:border-rose-350 transition-colors">
                                <i class="fa-solid fa-trash-can"></i>
                            </button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" class="p-12 text-center text-slate-500 text-base">
                        <i class="fa-solid fa-folder-tree text-3xl mb-4 block text-slate-400"></i>
                        No departments registered yet. Get started by creating your first department.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
