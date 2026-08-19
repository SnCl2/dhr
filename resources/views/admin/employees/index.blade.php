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
<div id="csv-import-modal" class="hidden fixed inset-0 z-50 bg-slate-900/60 backdrop-blur-xs flex items-center justify-center p-4">
    <div class="bg-white p-6 sm:p-8 rounded-3xl max-w-xl w-full border border-slate-200 shadow-2xl space-y-6">
        <div class="flex items-center justify-between border-b border-slate-100 pb-4">
            <div class="flex items-center space-x-3">
                <div class="w-10 h-10 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center text-lg">
                    <i class="fa-solid fa-file-csv"></i>
                </div>
                <div>
                    <h3 class="font-outfit font-bold text-base text-slate-800">Bulk Import Candidates / Employees</h3>
                    <p class="text-xs text-slate-500">Upload CSV matching the master KYC spreadsheet template</p>
                </div>
            </div>
            <button onclick="document.getElementById('csv-import-modal').classList.add('hidden')" class="text-slate-400 hover:text-slate-700 p-2 rounded-xl hover:bg-slate-100 transition-colors">
                <i class="fa-solid fa-xmark text-lg"></i>
            </button>
        </div>

        <!-- Download Template Form -->
        <div class="p-5 rounded-2xl bg-gradient-to-r from-slate-50 to-blue-50/40 border border-slate-200/80 space-y-3">
            <div class="flex items-center justify-between">
                <h4 class="text-xs font-bold text-slate-800 uppercase tracking-wider flex items-center">
                    <i class="fa-solid fa-cloud-arrow-down text-blue-600 mr-2"></i> 1. Download Master CSV Template
                </h4>
                <span class="text-xxs font-semibold bg-blue-100 text-blue-700 px-2.5 py-0.5 rounded-full">30 Columns</span>
            </div>
            <p class="text-xs text-slate-500">Pre-select Client and Designation to download an Excel/CSV ready template:</p>
            
            <form action="{{ route('admin.employees.download-template') }}" method="GET" class="space-y-3">
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                    <div>
                        <label for="template_company" class="block text-xxs font-bold text-slate-600 uppercase tracking-wider mb-1">Assigned Client / Company</label>
                        <select id="template_company" name="company" class="w-full px-3 py-2 bg-white border border-slate-300 rounded-xl text-slate-800 focus:outline-none focus:ring-2 focus:ring-blue-500 text-xs shadow-2xs">
                            <option value="">Default (Acme Corp)</option>
                            @foreach($companies as $company)
                                <option value="{{ $company->name }}">{{ $company->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label for="template_designation" class="block text-xxs font-bold text-slate-600 uppercase tracking-wider mb-1">Default Designation</label>
                        <select id="template_designation" name="designation" class="w-full px-3 py-2 bg-white border border-slate-300 rounded-xl text-slate-800 focus:outline-none focus:ring-2 focus:ring-blue-500 text-xs shadow-2xs">
                            <option value="">Default (Office Assistant)</option>
                            @foreach($designations as $desig)
                                <option value="{{ $desig->name }}">{{ $desig->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <button type="submit" class="w-full py-2.5 bg-white hover:bg-blue-50 text-blue-600 hover:text-blue-700 rounded-xl text-xs font-bold border border-blue-200 hover:border-blue-300 transition-all shadow-2xs flex items-center justify-center">
                    <i class="fa-solid fa-download mr-2"></i> Download Master CSV Template (.csv)
                </button>
            </form>
        </div>

        <!-- Upload CSV Form -->
        <form action="{{ route('admin.employees.import') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
            @csrf

            <div>
                <h4 class="text-xs font-bold text-slate-800 uppercase tracking-wider mb-2 flex items-center">
                    <i class="fa-solid fa-file-arrow-up text-blue-600 mr-2"></i> 2. Upload Filled CSV Spreadsheet
                </h4>
                
                <label for="csv_file" class="flex flex-col items-center justify-center p-6 border-2 border-dashed border-slate-300 hover:border-blue-500 bg-slate-50/60 hover:bg-blue-50/20 rounded-2xl cursor-pointer transition-all text-center group">
                    <div class="w-12 h-12 rounded-xl bg-blue-100 text-blue-600 flex items-center justify-center text-xl mb-2 group-hover:scale-105 transition-all">
                        <i class="fa-solid fa-file-csv"></i>
                    </div>
                    <span class="text-xs font-bold text-slate-800 group-hover:text-blue-600">Choose completed CSV file</span>
                    <span class="text-xxs text-slate-500 mt-0.5">Click to browse or drag & drop</span>
                    
                    <div id="modal_csv_file_info" class="hidden mt-3 p-2 bg-emerald-50 border border-emerald-200 text-emerald-800 rounded-lg text-xxs font-medium flex items-center space-x-1.5">
                        <i class="fa-solid fa-circle-check text-emerald-600"></i>
                        <span id="modal_csv_file_name">Selected file</span>
                    </div>
                </label>
                <input type="file" id="csv_file" name="csv_file" accept=".csv" required class="hidden" onchange="const f = this.files[0]; if(f){ const b = document.getElementById('modal_csv_file_info'); b.classList.remove('hidden'); document.getElementById('modal_csv_file_name').textContent = f.name; }">
            </div>

            <div class="flex items-center justify-end space-x-3 pt-4 border-t border-slate-100">
                <button type="button" onclick="document.getElementById('csv-import-modal').classList.add('hidden')"
                    class="px-5 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-600 rounded-xl text-xs font-semibold transition-all">
                    Cancel
                </button>
                <button type="submit"
                    class="px-6 py-2.5 bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 text-white rounded-xl text-xs font-semibold shadow-md shadow-blue-500/20 transition-all flex items-center">
                    <i class="fa-solid fa-file-import mr-2"></i> Start Bulk Import
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
