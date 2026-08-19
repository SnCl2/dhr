@extends('layouts.admin')

@section('title', 'Candidate & Staff Management - Propszy')
@section('page_title', 'Candidate & Staff Database')

@section('content')
<!-- Search, Filter & Bulk actions toolbar -->
<div class="grid grid-cols-1 lg:grid-cols-12 gap-6 mb-8 items-end">
    <!-- Filters form -->
    <div class="lg:col-span-8">
        <form action="{{ route('admin.employees.index') }}" method="GET" class="grid grid-cols-1 sm:grid-cols-4 gap-4">
            <!-- Search -->
            <div>
                <label for="search" class="block text-xs font-semibold text-slate-400 uppercase tracking-wider mb-2">Search Records</label>
                <div class="relative">
                    <input type="text" id="search" name="search" value="{{ request('search') }}"
                        class="block w-full pl-3 pr-10 py-2.5 bg-slate-900 border border-slate-800 rounded-xl text-slate-200 placeholder-slate-500 focus:outline-none focus:ring-1 focus:ring-purple-500 text-sm"
                        placeholder="ID, Name, Email...">
                    @if(request('search'))
                        <a href="{{ route('admin.employees.index', request()->except('search')) }}" class="absolute inset-y-0 right-0 pr-3 flex items-center text-slate-500 hover:text-slate-300">
                            <i class="fa-solid fa-circle-xmark"></i>
                        </a>
                    @endif
                </div>
            </div>

            <!-- Status Filter -->
            <div>
                <label for="status" class="block text-xs font-semibold text-slate-400 uppercase tracking-wider mb-2">Filter Status</label>
                <select id="status" name="status" onchange="this.form.submit()"
                    class="block w-full px-3 py-2.5 bg-slate-900 border border-slate-800 rounded-xl text-slate-200 focus:outline-none focus:ring-1 focus:ring-purple-500 text-sm">
                    <option value="">All Statuses</option>
                    <option value="pending_review" {{ request('status') === 'pending_review' ? 'selected' : '' }}>Pending Review</option>
                    <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Active Staff</option>
                    <option value="inactive" {{ request('status') === 'inactive' ? 'selected' : '' }}>Inactive Staff</option>
                    <option value="on_leave" {{ request('status') === 'on_leave' ? 'selected' : '' }}>On Leave</option>
                    <option value="terminated" {{ request('status') === 'terminated' ? 'selected' : '' }}>Terminated</option>
                </select>
            </div>

            <!-- Offer Letter Status Filter -->
            <div>
                <label for="offer_letter_status" class="block text-xs font-semibold text-slate-400 uppercase tracking-wider mb-2">Offer Letter</label>
                <select id="offer_letter_status" name="offer_letter_status" onchange="this.form.submit()"
                    class="block w-full px-3 py-2.5 bg-slate-900 border border-slate-800 rounded-xl text-slate-200 focus:outline-none focus:ring-1 focus:ring-purple-500 text-sm">
                    <option value="">All Candidates</option>
                    <option value="generated" {{ request('offer_letter_status') === 'generated' ? 'selected' : '' }}>Offer Letter Generated</option>
                    <option value="not_generated" {{ request('offer_letter_status') === 'not_generated' ? 'selected' : '' }}>Offer Letter Not Generated</option>
                </select>
            </div>

            <!-- Filter submit button -->
            <div class="flex items-end">
                <button type="submit" class="w-full px-6 py-2.5 bg-slate-800 hover:bg-slate-700 text-white rounded-xl text-sm font-semibold border border-slate-750 transition-colors">
                    <i class="fa-solid fa-filter mr-2"></i>Apply Filters
                </button>
            </div>
        </form>
    </div>

    <!-- Right Side Toolbar: Add New & CSV Import triggers -->
    <div class="lg:col-span-4 flex justify-end space-x-3">
        <button onclick="document.getElementById('csv-import-modal').classList.remove('hidden')" class="px-4 py-2.5 bg-slate-850 hover:bg-slate-800 text-purple-300 hover:text-white border border-purple-500/20 hover:border-purple-500/40 rounded-xl text-sm font-semibold transition-all">
            <i class="fa-solid fa-file-csv mr-2"></i>Import CSV
        </button>
        <a href="{{ route('admin.employees.create') }}" class="px-5 py-2.5 bg-gradient-to-r from-purple-600 to-indigo-600 hover:from-purple-500 hover:to-indigo-500 text-white rounded-xl text-sm font-semibold shadow-lg shadow-purple-500/10 transition-all duration-300">
            <i class="fa-solid fa-user-plus mr-2"></i>Add Staff Member
        </a>
    </div>
</div>

<!-- Bulk Actions Form -->
<form id="bulk-generate-form" action="{{ route('admin.offer-letters.bulk-generate-selected') }}" method="POST">
    @csrf

    <!-- Bulk Actions Header Panel (hidden by default) -->
    <div id="bulk-actions-panel" class="hidden mb-6 p-4 bg-slate-900 border border-slate-800 rounded-2xl flex flex-col sm:flex-row items-center justify-between text-sm shadow-xl">
        <div class="flex items-center space-x-3 text-slate-300 mb-3 sm:mb-0">
            <i class="fa-solid fa-square-check text-purple-400 text-lg"></i>
            <span>Selected <strong id="selected-count" class="text-white">0</strong> candidates</span>
        </div>
        <div class="flex items-center space-x-4">
            <div class="flex items-center space-x-2">
                <label for="bulk_type" class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Type:</label>
                <select id="bulk_type" name="type" required
                    class="px-3 py-1.5 bg-slate-950 border border-slate-800 rounded-xl text-slate-200 focus:outline-none focus:ring-1 focus:ring-purple-500 text-xs">
                    <option value="external">External/Contractor Layout</option>
                    <option value="internal">Internal Staff Layout</option>
                </select>
            </div>
            <button type="submit" class="px-5 py-2 bg-gradient-to-r from-purple-600 to-indigo-600 hover:from-purple-500 hover:to-indigo-500 text-white rounded-xl text-xs font-bold transition-all shadow-lg shadow-purple-500/10">
                <i class="fa-solid fa-bolt mr-1.5"></i> Bulk Generate
            </button>
        </div>
    </div>

    <!-- Employees Database Table -->
    <div class="glass-dark rounded-3xl overflow-hidden shadow-xl mb-8">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="border-b border-slate-850 bg-slate-900/40 text-xs font-semibold text-slate-400 uppercase tracking-wider">
                        <th class="p-6 w-12">
                            <input type="checkbox" id="select-all-checkbox" class="rounded bg-slate-950 border border-slate-800 text-purple-600 focus:ring-0 cursor-pointer">
                        </th>
                        <th class="p-6">Employee ID</th>
                        <th class="p-6">Name</th>
                        <th class="p-6">Contact Info</th>
                        <th class="p-6">Department & Designation</th>
                        <th class="p-6">Status</th>
                        <th class="p-6 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-850 text-sm">
                    @forelse($employees as $emp)
                    <tr class="text-slate-300 hover:bg-slate-900/20">
                        <td class="p-6">
                            <input type="checkbox" name="employee_ids[]" value="{{ $emp->id }}" class="employee-checkbox rounded bg-slate-950 border border-slate-800 text-purple-600 focus:ring-0 cursor-pointer">
                        </td>
                        <td class="p-6 font-semibold text-purple-400 select-all">{{ $emp->employee_id }}</td>
                        <td class="p-6">
                            <div class="flex items-center space-x-3">
                                @if($emp->profile_image)
                                    <img src="{{ asset($emp->profile_image) }}" alt="{{ $emp->full_name }}" class="w-10 h-10 rounded-full object-cover border border-purple-500/30">
                                @else
                                    <div class="w-10 h-10 rounded-full bg-slate-800 border border-slate-750 flex items-center justify-center text-xs font-bold text-purple-300">
                                        {{ strtoupper(substr($emp->full_name, 0, 1)) }}
                                    </div>
                                @endif
                                <div>
                                    <span class="block font-semibold text-white">{{ $emp->full_name }}</span>
                                    <span class="block text-xs text-slate-500 mt-0.5">Joined: {{ $emp->joining_date ? $emp->joining_date->format('d-M-Y') : 'N/A' }}</span>
                                </div>
                            </div>
                        </td>
                        <td class="p-6">
                            <span class="block">{{ $emp->email }}</span>
                            @if($emp->phone)
                                <span class="block text-xs text-slate-500 mt-0.5">{{ $emp->phone }}</span>
                            @endif
                        </td>
                        <td class="p-6">
                            @if($emp->company)
                                <span class="block font-bold text-purple-750 text-xs mb-1 select-all"><i class="fa-solid fa-building mr-1.5"></i>{{ $emp->company->name }}</span>
                            @endif
                            @if($emp->department || $emp->designationRelation)
                                <span class="block text-slate-700">{{ $emp->department ? $emp->department->name : 'N/A' }}</span>
                                <span class="block text-xs text-slate-500 mt-0.5">{{ $emp->designationRelation ? $emp->designationRelation->name : 'N/A' }}</span>
                            @else
                                <span class="text-slate-500">Unassigned</span>
                            @endif
                        </td>
                        <td class="p-6">
                            <span class="px-2.5 py-1 rounded-lg text-xs font-semibold uppercase tracking-wider 
                                @if($emp->status === 'active') bg-emerald-500/10 text-emerald-400 border border-emerald-500/20
                                @elseif($emp->status === 'pending_review') bg-amber-500/10 text-amber-400 border border-amber-500/20
                                @elseif($emp->status === 'on_leave') bg-indigo-500/10 text-indigo-400 border border-indigo-500/20
                                @else bg-rose-500/10 text-rose-400 border border-rose-500/20
                                @endif">
                                {{ str_replace('_', ' ', $emp->status) }}
                            </span>
                        </td>
                        <td class="p-6 text-right space-x-2">
                            <!-- Generate Offer Letter (Only if they do not have one already) -->
                            @if($emp->offerLetters->isEmpty() && $emp->status !== 'terminated')
                                <a href="{{ route('admin.offer-letters.generate', ['employee_id' => $emp->id]) }}" title="Generate Offer Letter" class="inline-flex p-2 rounded-lg bg-emerald-500/10 border border-emerald-500/20 text-emerald-500 hover:bg-emerald-500 hover:text-white transition-colors">
                                    <i class="fa-solid fa-file-signature"></i>
                                </a>
                            @endif

                            <!-- Secret Login / Switch Account -->
                            <form action="{{ route('admin.employees.login-as', $emp) }}" method="POST" class="inline" target="_blank">
                                @csrf
                                <button type="submit" title="Login As Employee" class="inline-flex p-2 rounded-lg bg-slate-100 border border-slate-200 text-slate-600 hover:text-purple-600 hover:border-purple-300 transition-colors">
                                    <i class="fa-solid fa-arrow-right-to-bracket"></i>
                                </button>
                            </form>

                            <a href="{{ route('admin.employees.edit', $emp) }}" title="Edit Candidate" class="inline-flex p-2 rounded-lg bg-slate-100 border border-slate-200 text-slate-600 hover:text-slate-900 hover:border-slate-350 transition-colors">
                                <i class="fa-solid fa-pen-to-square"></i>
                            </a>

                            <form action="{{ route('admin.employees.destroy', $emp) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure you want to delete this staff record?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" title="Delete Candidate" class="inline-flex p-2 rounded-lg bg-slate-100 border border-slate-200 text-slate-600 hover:text-rose-600 hover:border-rose-350 transition-colors">
                                    <i class="fa-solid fa-trash-can"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="p-12 text-center text-slate-550 text-base">
                            <i class="fa-solid fa-folder-open text-3xl mb-4 block"></i>
                            No candidates or employees found in this view.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if($employees->hasPages())
        <div class="px-6 py-4 border-t border-slate-850">
            {{ $employees->links() }}
        </div>
        @endif
    </div>
</form>

<!-- CSV Bulk Import Modal (Popup) -->
<div id="csv-import-modal" class="hidden fixed inset-0 z-50 bg-slate-950/80 flex items-center justify-center p-4">
    <div class="glass-dark p-6 sm:p-8 rounded-3xl max-w-lg w-full border border-slate-850 shadow-2xl">
        <div class="flex items-center justify-between mb-6">
            <h3 class="font-outfit font-bold text-lg text-white">Import Candidates / Employees via CSV</h3>
            <button onclick="document.getElementById('csv-import-modal').classList.add('hidden')" class="text-slate-400 hover:text-white p-1 rounded hover:bg-slate-800">
                <i class="fa-solid fa-xmark text-lg"></i>
            </button>
        </div>

        <p class="text-xs text-slate-400 leading-relaxed mb-6">
            Upload a CSV file containing employee credentials. CSV headers must exactly match:
            <code class="block mt-2 p-2 bg-slate-900 rounded border border-slate-800 text-purple-400 select-all overflow-x-auto whitespace-nowrap">first_name,last_name,email,phone,salary,joining_date,status,department,designation</code>
        </p>

        <!-- Download Template Form -->
        <div class="border-b border-slate-850 pb-5 mb-5">
            <h4 class="text-xs font-semibold text-slate-350 uppercase tracking-wider mb-2">Prefilled CSV Template Downloader</h4>
            <p class="text-[11px] text-slate-500 mb-3">Select options to pre-populate the CSV template cells, making it easier to fill in details without looking up names/IDs.</p>
            <form action="{{ route('admin.employees.download-template') }}" method="GET" class="space-y-3">
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                    <div>
                        <label for="template_company" class="block text-[10px] font-semibold text-slate-450 uppercase tracking-wider mb-1">Company</label>
                        <select id="template_company" name="company" class="w-full px-2 py-2 bg-slate-950 border border-slate-800 rounded-xl text-slate-300 focus:outline-none focus:ring-1 focus:ring-purple-500 text-xs">
                            <option value="">None (Empty)</option>
                            @foreach($companies as $company)
                                <option value="{{ $company->name }}">{{ $company->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label for="template_department" class="block text-[10px] font-semibold text-slate-450 uppercase tracking-wider mb-1">Department</label>
                        <select id="template_department" name="department" class="w-full px-2 py-2 bg-slate-950 border border-slate-800 rounded-xl text-slate-300 focus:outline-none focus:ring-1 focus:ring-purple-500 text-xs">
                            <option value="">None (Empty)</option>
                            @foreach($departments as $dept)
                                <option value="{{ $dept->name }}">{{ $dept->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label for="template_designation" class="block text-[10px] font-semibold text-slate-450 uppercase tracking-wider mb-1">Designation</label>
                        <select id="template_designation" name="designation" class="w-full px-2 py-2 bg-slate-950 border border-slate-800 rounded-xl text-slate-300 focus:outline-none focus:ring-1 focus:ring-purple-500 text-xs">
                            <option value="">None (Empty)</option>
                            @foreach($designations as $desig)
                                <option value="{{ $desig->name }}">{{ $desig->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <button type="submit" class="w-full py-2.5 bg-slate-900 hover:bg-slate-850 text-purple-400 hover:text-white rounded-xl text-xs font-bold border border-slate-800 transition-colors">
                    <i class="fa-solid fa-download mr-1.5"></i> Download CSV Template
                </button>
            </form>
        </div>

        <form action="{{ route('admin.employees.import') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
            @csrf

            <div>
                <label for="csv_file" class="block text-xs font-semibold text-slate-400 uppercase tracking-wider mb-2">Select CSV File</label>
                <input type="file" id="csv_file" name="csv_file" accept=".csv" required
                    class="block w-full text-sm text-slate-400 file:mr-4 file:py-2.5 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-purple-600 file:text-white hover:file:bg-purple-500 cursor-pointer">
            </div>

            <div class="flex justify-end space-x-3 pt-4 border-t border-slate-850">
                <button type="button" onclick="document.getElementById('csv-import-modal').classList.add('hidden')"
                    class="px-5 py-2.5 bg-slate-850 hover:bg-slate-800 text-slate-400 hover:text-white rounded-xl text-sm font-semibold transition-all">
                    Cancel
                </button>
                <button type="submit"
                    class="px-5 py-2.5 bg-gradient-to-r from-purple-600 to-indigo-600 hover:from-purple-500 hover:to-indigo-500 text-white rounded-xl text-sm font-semibold transition-all shadow-lg shadow-purple-500/10">
                    <i class="fa-solid fa-file-import mr-2"></i> Onboard Employees
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const selectAll = document.getElementById('select-all-checkbox');
        const checkboxes = document.querySelectorAll('.employee-checkbox');
        const bulkPanel = document.getElementById('bulk-actions-panel');
        const selectedCount = document.getElementById('selected-count');

        function updateBulkPanel() {
            const checked = document.querySelectorAll('.employee-checkbox:checked');
            selectedCount.textContent = checked.length;
            if (checked.length > 0) {
                bulkPanel.classList.remove('hidden');
            } else {
                bulkPanel.classList.add('hidden');
            }
        }

        if (selectAll) {
            selectAll.addEventListener('change', function () {
                checkboxes.forEach(cb => {
                    cb.checked = selectAll.checked;
                });
                updateBulkPanel();
            });
        }

        checkboxes.forEach(cb => {
            cb.addEventListener('change', function () {
                const checkedCount = document.querySelectorAll('.employee-checkbox:checked').length;
                if (selectAll) {
                    selectAll.checked = (checkedCount === checkboxes.length);
                }
                updateBulkPanel();
            });
        });
    });
</script>
@endsection
