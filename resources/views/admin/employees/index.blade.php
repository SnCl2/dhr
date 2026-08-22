@extends('layouts.admin')

@section('title', 'Candidate & Staff Management - RM HR Solutions')
@section('page_title', 'Candidate & Staff Database')

@section('content')
@php
    $selectedCompanies = (array) request('company_id', []);
    $selectedDesignations = (array) request('designation_id', []);
    $selectedDepartments = (array) request('department_id', []);
    $selectedStatuses = (array) request('status', []);
@endphp

<div class="space-y-6">

    <!-- Top Action Bar -->
    <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
        <div>
            <h2 class="text-lg font-bold text-slate-800 flex items-center">
                <i class="fa-solid fa-users text-blue-600 mr-2.5"></i> Employee Records
                <span class="ml-3 px-3 py-1 rounded-full text-xs font-bold bg-blue-50 text-blue-700 border border-blue-200">
                    {{ $employees->total() }} Total
                </span>
            </h2>
            <p class="text-xs text-slate-500 mt-0.5">Manage onboarded candidates, export data, and generate official documentation.</p>
        </div>

        <div class="flex flex-wrap items-center gap-3">
            <!-- Export Filtered CSV -->
            <a href="{{ route('admin.employees.export', request()->query()) }}" 
               title="Export filtered records matching current search/filter to CSV"
               class="inline-flex items-center px-4 py-2.5 bg-white hover:bg-slate-50 border border-slate-300 hover:border-slate-400 text-slate-700 hover:text-blue-600 rounded-xl text-xs font-semibold shadow-2xs transition-all">
                <i class="fa-solid fa-file-export mr-2 text-emerald-600 text-sm"></i> Export CSV
            </a>

            <!-- Import CSV Modal Trigger -->
            <button onclick="document.getElementById('csv-import-modal').classList.remove('hidden')" 
                    class="inline-flex items-center px-4 py-2.5 bg-white hover:bg-slate-50 border border-slate-300 hover:border-slate-400 text-slate-700 hover:text-blue-600 rounded-xl text-xs font-semibold shadow-2xs transition-all">
                <i class="fa-solid fa-file-import mr-2 text-blue-600 text-sm"></i> Import CSV
            </button>

            <!-- Add Candidate -->
            <a href="{{ route('admin.employees.create') }}" 
               class="inline-flex items-center px-5 py-2.5 bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 text-white rounded-xl text-xs font-bold shadow-md shadow-blue-500/20 transition-all">
                <i class="fa-solid fa-user-plus mr-2"></i> Onboard Candidate
            </a>
        </div>
    </div>

    <!-- Comprehensive Search & Multi-Filters Card -->
    <div class="bg-white p-6 rounded-3xl border border-slate-200 shadow-sm space-y-5">
        <div class="flex items-center justify-between border-b border-slate-100 pb-3">
            <h3 class="text-xs font-bold text-slate-700 uppercase tracking-wider flex items-center">
                <i class="fa-solid fa-filter text-blue-600 mr-2"></i> Comprehensive Search & Multi-Filters
            </h3>
            @if(request()->hasAny(['search', 'company_id', 'designation_id', 'department_id', 'status', 'work_location', 'from_date', 'to_date', 'offer_letter_status']))
                <a href="{{ route('admin.employees.index') }}" class="text-xs font-semibold text-rose-600 hover:underline flex items-center">
                    <i class="fa-solid fa-rotate-left mr-1.5"></i> Reset All Filters
                </a>
            @endif
        </div>

        <form action="{{ route('admin.employees.index') }}" method="GET" class="space-y-4" id="filters-form">
            <!-- Row 1: Keyword Search Bar -->
            <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-slate-400">
                    <i class="fa-solid fa-magnifying-glass"></i>
                </div>
                <input type="text" id="search" name="search" value="{{ request('search') }}"
                    class="block w-full pl-11 pr-10 py-3 bg-slate-50/50 border border-slate-300 rounded-2xl text-slate-800 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm shadow-2xs transition-all"
                    placeholder="Search by Full Name, Aadhaar Number, PAN, Email, Phone, Employee ID, UAN, Location, City...">
                @if(request('search'))
                    <a href="{{ route('admin.employees.index', request()->except('search')) }}" class="absolute inset-y-0 right-0 pr-4 flex items-center text-slate-400 hover:text-slate-600">
                        <i class="fa-solid fa-circle-xmark"></i>
                    </a>
                @endif
            </div>

            <!-- Row 2: Multi-Select Filter Row (4 Balanced Columns) -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                
                <!-- 1. Company / Client Multi-Select -->
                <div class="relative custom-multiselect">
                    <label class="block text-xs font-bold text-slate-600 uppercase tracking-wider mb-1.5">Company / Client</label>
                    <button type="button" class="multiselect-toggle w-full px-3.5 py-2.5 bg-white border border-slate-300 hover:border-slate-400 rounded-xl text-slate-800 focus:outline-none focus:ring-2 focus:ring-blue-500 text-xs shadow-2xs flex items-center justify-between text-left transition-all">
                        <span class="truncate multiselect-label font-medium">
                            @if(count($selectedCompanies) > 0)
                                {{ count($selectedCompanies) }} Selected
                            @else
                                All Companies
                            @endif
                        </span>
                        <i class="fa-solid fa-chevron-down text-xxs text-slate-400 ml-2 shrink-0"></i>
                    </button>
                    
                    <!-- Dropdown Popup -->
                    <div class="multiselect-menu hidden absolute left-0 z-50 mt-1.5 w-72 bg-white border border-slate-200 rounded-2xl shadow-2xl p-3.5 max-h-64 overflow-y-auto space-y-1 ring-1 ring-black/5">
                        <div class="flex items-center justify-between pb-2 mb-1.5 border-b border-slate-100 text-xs">
                            <span class="font-bold text-slate-700">Select Companies</span>
                            <button type="button" class="font-semibold text-blue-600 hover:text-blue-800 clear-multiselect">Clear</button>
                        </div>
                        @foreach($companies as $comp)
                            <label class="flex items-center space-x-2.5 px-2 py-1.5 rounded-xl hover:bg-blue-50/50 cursor-pointer text-xs text-slate-700 transition-colors">
                                <input type="checkbox" name="company_id[]" value="{{ $comp->id }}" 
                                    {{ in_array($comp->id, $selectedCompanies) ? 'checked' : '' }}
                                    class="rounded border-slate-300 text-blue-600 focus:ring-0 w-4 h-4 cursor-pointer">
                                <span class="truncate font-medium">{{ $comp->name }}</span>
                            </label>
                        @endforeach
                    </div>
                </div>

                <!-- 2. Designation Multi-Select -->
                <div class="relative custom-multiselect">
                    <label class="block text-xs font-bold text-slate-600 uppercase tracking-wider mb-1.5">Designation</label>
                    <button type="button" class="multiselect-toggle w-full px-3.5 py-2.5 bg-white border border-slate-300 hover:border-slate-400 rounded-xl text-slate-800 focus:outline-none focus:ring-2 focus:ring-blue-500 text-xs shadow-2xs flex items-center justify-between text-left transition-all">
                        <span class="truncate multiselect-label font-medium">
                            @if(count($selectedDesignations) > 0)
                                {{ count($selectedDesignations) }} Selected
                            @else
                                All Designations
                            @endif
                        </span>
                        <i class="fa-solid fa-chevron-down text-xxs text-slate-400 ml-2 shrink-0"></i>
                    </button>
                    
                    <!-- Dropdown Popup -->
                    <div class="multiselect-menu hidden absolute left-0 z-50 mt-1.5 w-72 bg-white border border-slate-200 rounded-2xl shadow-2xl p-3.5 max-h-64 overflow-y-auto space-y-1 ring-1 ring-black/5">
                        <div class="flex items-center justify-between pb-2 mb-1.5 border-b border-slate-100 text-xs">
                            <span class="font-bold text-slate-700">Select Designations</span>
                            <button type="button" class="font-semibold text-blue-600 hover:text-blue-800 clear-multiselect">Clear</button>
                        </div>
                        @foreach($designations as $desig)
                            <label class="flex items-center space-x-2.5 px-2 py-1.5 rounded-xl hover:bg-blue-50/50 cursor-pointer text-xs text-slate-700 transition-colors">
                                <input type="checkbox" name="designation_id[]" value="{{ $desig->id }}" 
                                    {{ in_array($desig->id, $selectedDesignations) ? 'checked' : '' }}
                                    class="rounded border-slate-300 text-blue-600 focus:ring-0 w-4 h-4 cursor-pointer">
                                <span class="truncate font-medium">{{ $desig->name }}</span>
                            </label>
                        @endforeach
                    </div>
                </div>

                <!-- 3. Department Multi-Select -->
                <div class="relative custom-multiselect">
                    <label class="block text-xs font-bold text-slate-600 uppercase tracking-wider mb-1.5">Department</label>
                    <button type="button" class="multiselect-toggle w-full px-3.5 py-2.5 bg-white border border-slate-300 hover:border-slate-400 rounded-xl text-slate-800 focus:outline-none focus:ring-2 focus:ring-blue-500 text-xs shadow-2xs flex items-center justify-between text-left transition-all">
                        <span class="truncate multiselect-label font-medium">
                            @if(count($selectedDepartments) > 0)
                                {{ count($selectedDepartments) }} Selected
                            @else
                                All Departments
                            @endif
                        </span>
                        <i class="fa-solid fa-chevron-down text-xxs text-slate-400 ml-2 shrink-0"></i>
                    </button>
                    
                    <!-- Dropdown Popup -->
                    <div class="multiselect-menu hidden absolute left-0 z-50 mt-1.5 w-72 bg-white border border-slate-200 rounded-2xl shadow-2xl p-3.5 max-h-64 overflow-y-auto space-y-1 ring-1 ring-black/5">
                        <div class="flex items-center justify-between pb-2 mb-1.5 border-b border-slate-100 text-xs">
                            <span class="font-bold text-slate-700">Select Departments</span>
                            <button type="button" class="font-semibold text-blue-600 hover:text-blue-800 clear-multiselect">Clear</button>
                        </div>
                        @foreach($departments as $dept)
                            <label class="flex items-center space-x-2.5 px-2 py-1.5 rounded-xl hover:bg-blue-50/50 cursor-pointer text-xs text-slate-700 transition-colors">
                                <input type="checkbox" name="department_id[]" value="{{ $dept->id }}" 
                                    {{ in_array($dept->id, $selectedDepartments) ? 'checked' : '' }}
                                    class="rounded border-slate-300 text-blue-600 focus:ring-0 w-4 h-4 cursor-pointer">
                                <span class="truncate font-medium">{{ $dept->name }}</span>
                            </label>
                        @endforeach
                    </div>
                </div>

                <!-- 4. Status Multi-Select -->
                <div class="relative custom-multiselect">
                    <label class="block text-xs font-bold text-slate-600 uppercase tracking-wider mb-1.5">Status</label>
                    <button type="button" class="multiselect-toggle w-full px-3.5 py-2.5 bg-white border border-slate-300 hover:border-slate-400 rounded-xl text-slate-800 focus:outline-none focus:ring-2 focus:ring-blue-500 text-xs shadow-2xs flex items-center justify-between text-left transition-all">
                        <span class="truncate multiselect-label font-medium">
                            @if(count($selectedStatuses) > 0)
                                {{ count($selectedStatuses) }} Selected
                            @else
                                All Statuses
                            @endif
                        </span>
                        <i class="fa-solid fa-chevron-down text-xxs text-slate-400 ml-2 shrink-0"></i>
                    </button>
                    
                    <!-- Dropdown Popup -->
                    <div class="multiselect-menu hidden absolute right-0 sm:left-0 z-50 mt-1.5 w-72 bg-white border border-slate-200 rounded-2xl shadow-2xl p-3.5 max-h-64 overflow-y-auto space-y-1 ring-1 ring-black/5">
                        <div class="flex items-center justify-between pb-2 mb-1.5 border-b border-slate-100 text-xs">
                            <span class="font-bold text-slate-700">Select Statuses</span>
                            <button type="button" class="font-semibold text-blue-600 hover:text-blue-800 clear-multiselect">Clear</button>
                        </div>
                        @php
                            $statusOptions = [
                                'pending_review' => 'Pending Review',
                                'active' => 'Active',
                                'inactive' => 'Inactive',
                                'on_leave' => 'On Leave',
                                'terminated' => 'Terminated',
                            ];
                        @endphp
                        @foreach($statusOptions as $val => $label)
                            <label class="flex items-center space-x-2.5 px-2 py-1.5 rounded-xl hover:bg-blue-50/50 cursor-pointer text-xs text-slate-700 transition-colors">
                                <input type="checkbox" name="status[]" value="{{ $val }}" 
                                    {{ in_array($val, $selectedStatuses) ? 'checked' : '' }}
                                    class="rounded border-slate-300 text-blue-600 focus:ring-0 w-4 h-4 cursor-pointer">
                                <span class="truncate font-medium">{{ $label }}</span>
                            </label>
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- Row 3: Secondary Filters & Action Buttons -->
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-4 items-end pt-1">
                
                <!-- Work Location -->
                <div>
                    <label for="work_location" class="block text-xs font-bold text-slate-600 uppercase tracking-wider mb-1.5">Work Location</label>
                    <input type="text" id="work_location" name="work_location" value="{{ request('work_location') }}"
                        class="w-full px-3.5 py-2.5 bg-white border border-slate-300 rounded-xl text-slate-800 focus:outline-none focus:ring-2 focus:ring-blue-500 text-xs shadow-2xs transition-all"
                        placeholder="e.g. Kolkata">
                </div>

                <!-- Date Range: From -->
                <div>
                    <label for="from_date" class="block text-xs font-bold text-slate-600 uppercase tracking-wider mb-1.5">Joined From</label>
                    <input type="date" id="from_date" name="from_date" value="{{ request('from_date') }}"
                        class="w-full px-3.5 py-2.5 bg-white border border-slate-300 rounded-xl text-slate-800 focus:outline-none focus:ring-2 focus:ring-blue-500 text-xs shadow-2xs transition-all">
                </div>

                <!-- Date Range: To -->
                <div>
                    <label for="to_date" class="block text-xs font-bold text-slate-600 uppercase tracking-wider mb-1.5">Joined To</label>
                    <input type="date" id="to_date" name="to_date" value="{{ request('to_date') }}"
                        class="w-full px-3.5 py-2.5 bg-white border border-slate-300 rounded-xl text-slate-800 focus:outline-none focus:ring-2 focus:ring-blue-500 text-xs shadow-2xs transition-all">
                </div>

                <!-- Offer Letter Status -->
                <div>
                    <label for="offer_letter_status" class="block text-xs font-bold text-slate-600 uppercase tracking-wider mb-1.5">Offer Letter</label>
                    <select id="offer_letter_status" name="offer_letter_status" class="w-full px-3.5 py-2.5 bg-white border border-slate-300 rounded-xl text-slate-800 focus:outline-none focus:ring-2 focus:ring-blue-500 text-xs shadow-2xs transition-all">
                        <option value="">Any</option>
                        <option value="generated" {{ request('offer_letter_status') === 'generated' ? 'selected' : '' }}>Generated</option>
                        <option value="not_generated" {{ request('offer_letter_status') === 'not_generated' ? 'selected' : '' }}>Not Generated</option>
                    </select>
                </div>

                <!-- Submit & Clear Buttons (spanning 2 columns on lg) -->
                <div class="lg:col-span-2 flex items-center space-x-3">
                    <button type="submit" class="flex-1 py-2.5 px-4 bg-blue-600 hover:bg-blue-700 text-white rounded-xl text-xs font-bold shadow-md shadow-blue-500/20 transition-all flex items-center justify-center">
                        <i class="fa-solid fa-magnifying-glass mr-2"></i> Apply Filters
                    </button>
                    @if(request()->hasAny(['search', 'company_id', 'designation_id', 'department_id', 'status', 'work_location', 'from_date', 'to_date', 'offer_letter_status']))
                        <a href="{{ route('admin.employees.index') }}" class="py-2.5 px-4 bg-slate-100 hover:bg-slate-200 text-slate-600 rounded-xl text-xs font-semibold transition-all">
                            Clear
                        </a>
                    @endif
                </div>
            </div>
        </form>
    </div>

    <!-- Bulk Actions Form -->
    <form id="bulk-generate-form" action="{{ route('admin.employees.bulk-action') }}" method="POST" onsubmit="return handleBulkGenerateSubmit(event)">
        @csrf

        <!-- Bulk Actions Header Panel (hidden by default) -->
        <div id="bulk-actions-panel" class="hidden mb-6 p-4 bg-slate-900 border border-slate-800 rounded-2xl flex flex-col lg:flex-row items-center justify-between text-sm shadow-xl transition-all">
            <div class="flex items-center space-x-3 text-slate-300 mb-3 lg:mb-0">
                <i class="fa-solid fa-square-check text-blue-400 text-lg"></i>
                <span>Selected <strong id="selected-count" class="text-white">0</strong> candidates</span>
            </div>
            <div class="flex flex-wrap items-center gap-4">
                <!-- Action selector -->
                <div class="flex items-center space-x-2">
                    <label for="bulk_action" class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Action:</label>
                    <select id="bulk_action" name="action" required onchange="handleBulkActionChange()"
                        class="px-3 py-1.5 bg-slate-950 border border-slate-850 rounded-xl text-slate-200 focus:outline-none focus:ring-1 focus:ring-blue-500 text-xs">
                        <option value="offer_letter">Generate Offer Letters</option>
                        <option value="status_change">Change Status</option>
                        <option value="delete">Delete Selected</option>
                    </select>
                </div>

                <!-- Parameters for Offer Letter -->
                <div id="bulk_offer_letter_params" class="flex items-center space-x-2">
                    <label for="bulk_type" class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Type:</label>
                    <select id="bulk_type" name="type"
                        class="px-3 py-1.5 bg-slate-950 border border-slate-850 rounded-xl text-slate-200 focus:outline-none focus:ring-1 focus:ring-blue-500 text-xs">
                        <option value="external">External/Contractor Layout</option>
                        <option value="internal">Internal Staff Layout</option>
                    </select>
                </div>

                <!-- Parameters for Status Change -->
                <div id="bulk_status_change_params" class="hidden flex items-center space-x-2">
                    <label for="bulk_status" class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Status:</label>
                    <select id="bulk_status" name="status"
                        class="px-3 py-1.5 bg-slate-950 border border-slate-850 rounded-xl text-slate-200 focus:outline-none focus:ring-1 focus:ring-blue-500 text-xs">
                        <option value="pending_review">Pending Review</option>
                        <option value="active">Active</option>
                        <option value="inactive">Inactive</option>
                        <option value="on_leave">On Leave</option>
                        <option value="terminated">Terminated</option>
                    </select>
                </div>

                <div id="bulk-hidden-inputs"></div>
                <button type="submit" class="px-5 py-2 bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-500 hover:to-indigo-500 text-white rounded-xl text-xs font-bold transition-all shadow-lg shadow-blue-500/10">
                    <i class="fa-solid fa-bolt mr-1.5"></i> Apply Action
                </button>
            </div>
        </div>
    </form>

    <!-- Employees Database Table -->
    <div class="bg-white rounded-3xl overflow-hidden border border-slate-200 shadow-sm mb-8">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="border-b border-slate-200 bg-slate-50/80 text-xs font-bold text-slate-600 uppercase tracking-wider">
                            <th class="p-5 w-12 text-center">
                                <input type="checkbox" id="select-all-checkbox" class="rounded bg-white border border-slate-300 text-blue-600 focus:ring-0 cursor-pointer">
                            </th>
                            <th class="p-5">Employee ID</th>
                            <th class="p-5">Name & Personal</th>
                            <th class="p-5">Contact Details</th>
                            <th class="p-5">Company & Designation</th>
                            <th class="p-5">Status</th>
                            <th class="p-5 text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 text-sm">
                        @forelse($employees as $emp)
                        <tr class="text-slate-700 hover:bg-blue-50/20 transition-colors">
                            <td class="p-5 text-center">
                                <input type="checkbox" name="employee_ids[]" value="{{ $emp->id }}" class="employee-checkbox rounded bg-white border border-slate-300 text-blue-600 focus:ring-0 cursor-pointer">
                            </td>
                            <td class="p-5 font-bold text-blue-600 font-mono select-all">{{ $emp->employee_id }}</td>
                            <td class="p-5">
                                <div class="flex items-center space-x-3">
                                    @if($emp->profile_image)
                                        <img src="{{ asset($emp->profile_image) }}" alt="{{ $emp->full_name }}" class="w-10 h-10 rounded-full object-cover border border-blue-200 shadow-2xs">
                                    @else
                                        <div class="w-10 h-10 rounded-full bg-blue-50 border border-blue-200 flex items-center justify-center text-xs font-bold text-blue-700">
                                            {{ strtoupper(substr($emp->full_name, 0, 1)) }}
                                        </div>
                                    @endif
                                    <div>
                                        <span class="block font-bold text-slate-900">{{ $emp->full_name }}</span>
                                        <span class="block text-xs text-slate-500 mt-0.5">
                                            <i class="fa-regular fa-calendar mr-1 text-slate-400"></i>Joined: {{ $emp->joining_date ? $emp->joining_date->format('d-M-Y') : 'N/A' }}
                                        </span>
                                    </div>
                                </div>
                            </td>
                            <td class="p-5">
                                <span class="block text-slate-800 font-medium">{{ $emp->email }}</span>
                                <span class="block text-xs text-slate-500 mt-0.5">
                                    <i class="fa-solid fa-phone mr-1 text-slate-400"></i>{{ $emp->contact_number ?? $emp->phone ?? 'N/A' }}
                                </span>
                            </td>
                            <td class="p-5">
                                @if($emp->company)
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-md text-xs font-semibold bg-slate-100 text-slate-800 mb-1">
                                        <i class="fa-solid fa-building mr-1.5 text-blue-600"></i>{{ $emp->company->name }}
                                    </span>
                                @endif
                                <span class="block text-xs text-slate-600 font-medium">
                                    {{ $emp->designationRelation ? $emp->designationRelation->name : 'Unassigned' }}
                                </span>
                                @if($emp->work_location)
                                    <span class="block text-xxs text-slate-400 mt-0.5">
                                        <i class="fa-solid fa-location-dot mr-1"></i>{{ $emp->work_location }}
                                    </span>
                                @endif
                            </td>
                            <td class="p-5">
                                <span class="px-3 py-1 rounded-full text-xs font-bold uppercase tracking-wider 
                                    @if($emp->status === 'active') bg-emerald-50 text-emerald-700 border border-emerald-200
                                    @elseif($emp->status === 'pending_review') bg-amber-50 text-amber-700 border border-amber-200
                                    @elseif($emp->status === 'on_leave') bg-blue-50 text-blue-700 border border-blue-200
                                    @else bg-rose-50 text-rose-700 border border-rose-200
                                    @endif">
                                    {{ str_replace('_', ' ', $emp->status) }}
                                </span>
                            </td>
                            <td class="p-5 text-right space-x-2">
                                <!-- Generate Offer Letter (Only if they do not have one already) -->
                                @if($emp->offerLetters->isEmpty() && $emp->status !== 'terminated')
                                    <a href="{{ route('admin.offer-letters.generate', ['employee_id' => $emp->id]) }}" title="Generate Offer Letter" class="inline-flex p-2 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-600 hover:bg-emerald-600 hover:text-white transition-all shadow-2xs">
                                        <i class="fa-solid fa-file-signature"></i>
                                    </a>
                                @endif

                                <!-- Secret Login / Switch Account -->
                                <form action="{{ route('admin.employees.login-as', $emp) }}" method="POST" class="inline" target="_blank">
                                    @csrf
                                    <button type="submit" title="Login As Employee" class="inline-flex p-2 rounded-xl bg-slate-50 border border-slate-200 text-slate-600 hover:text-blue-600 hover:border-blue-300 transition-all shadow-2xs">
                                        <i class="fa-solid fa-arrow-right-to-bracket"></i>
                                    </button>
                                </form>

                                <!-- Edit Candidate -->
                                <a href="{{ route('admin.employees.edit', $emp) }}" title="Edit Candidate" class="inline-flex p-2 rounded-xl bg-slate-50 border border-slate-200 text-slate-600 hover:text-blue-600 hover:border-blue-300 transition-all shadow-2xs">
                                    <i class="fa-solid fa-pen-to-square"></i>
                                </a>

                                <!-- Delete Candidate -->
                                <form action="{{ route('admin.employees.destroy', $emp) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure you want to delete this staff record?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" title="Delete Candidate" class="inline-flex p-2 rounded-xl bg-slate-50 border border-slate-200 text-slate-600 hover:text-rose-600 hover:border-rose-300 transition-all shadow-2xs">
                                        <i class="fa-solid fa-trash-can"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="p-12 text-center text-slate-500 text-sm">
                                <i class="fa-solid fa-folder-open text-3xl mb-3 text-slate-300 block"></i>
                                No candidates or staff members match your selected search filters.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            @if($employees->hasPages())
            <div class="px-6 py-4 border-t border-slate-100 bg-slate-50/50">
                {{ $employees->links() }}
            </div>
            @endif
        </div>
    </div>

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
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
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
                    <div>
                        <label for="template_salary" class="block text-xxs font-bold text-slate-600 uppercase tracking-wider mb-1">Default NTH Salary</label>
                        <input type="number" id="template_salary" name="salary" class="w-full px-3 py-2 bg-white border border-slate-300 rounded-xl text-slate-800 focus:outline-none focus:ring-2 focus:ring-blue-500 text-xs shadow-2xs" placeholder="Default (18000)" min="0">
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

@if(session()->has('import_summary'))
    @php $summary = session('import_summary'); @endphp
    <!-- CSV Import Results Modal (Popup) -->
    <div id="csv-results-modal" class="fixed inset-0 z-50 bg-slate-900/60 backdrop-blur-xs flex items-center justify-center p-4">
        <div class="bg-white p-6 sm:p-8 rounded-3xl max-w-2xl w-full border border-slate-200 shadow-2xl space-y-5 flex flex-col max-h-[90vh]">
            <div class="flex items-center justify-between border-b border-slate-100 pb-4 shrink-0">
                <div class="flex items-center space-x-3">
                    <div class="w-10 h-10 rounded-xl {{ $summary['fail_count'] > 0 ? 'bg-amber-50 text-amber-600' : 'bg-emerald-50 text-emerald-600' }} flex items-center justify-center text-lg">
                        <i class="fa-solid fa-file-invoice"></i>
                    </div>
                    <div>
                        <h3 class="font-outfit font-bold text-base text-slate-800">CSV Bulk Import Summary</h3>
                        <p class="text-xs text-slate-500">Overview of successfully imported and failed candidate records</p>
                    </div>
                </div>
                <button onclick="document.getElementById('csv-results-modal').classList.add('hidden')" class="text-slate-400 hover:text-slate-700 p-2 rounded-xl hover:bg-slate-100 transition-colors">
                    <i class="fa-solid fa-xmark text-lg"></i>
                </button>
            </div>

            <!-- Stats Grid -->
            <div class="grid grid-cols-2 gap-4 shrink-0">
                <div class="p-4 rounded-2xl bg-emerald-50 border border-emerald-100 text-center">
                    <span class="block text-xxs font-bold text-emerald-800 uppercase tracking-wider mb-1">Successfully Imported</span>
                    <span class="text-2xl font-bold text-emerald-700">{{ $summary['success_count'] }}</span>
                </div>
                <div class="p-4 rounded-2xl {{ $summary['fail_count'] > 0 ? 'bg-rose-50 border-rose-100 text-rose-700' : 'bg-slate-50 border-slate-100 text-slate-500' }} text-center">
                    <span class="block text-xxs font-bold uppercase tracking-wider mb-1 {{ $summary['fail_count'] > 0 ? 'text-rose-800' : 'text-slate-500' }}">Failed / Skipped</span>
                    <span class="text-2xl font-bold {{ $summary['fail_count'] > 0 ? 'text-rose-700' : 'text-slate-500' }}">{{ $summary['fail_count'] }}</span>
                </div>
            </div>

            @if($summary['fail_count'] > 0)
                <div class="space-y-2 flex-grow overflow-hidden flex flex-col">
                    <div class="flex items-center justify-between shrink-0">
                        <h4 class="text-xs font-bold text-slate-800 uppercase tracking-wider">Failed Record Details</h4>
                        <span class="text-xxs font-semibold bg-rose-100 text-rose-800 px-2.5 py-0.5 rounded-full">Requires Correction</span>
                    </div>
                    
                    <!-- Scrollable Table -->
                    <div class="border border-slate-200 rounded-2xl overflow-hidden flex-grow overflow-y-auto min-h-[150px]">
                        <table class="w-full text-left text-xs">
                            <thead class="bg-slate-50 border-b border-slate-200 font-semibold sticky top-0 z-10">
                                <tr>
                                    <th class="px-4 py-2 text-slate-600 w-16 text-center">Row</th>
                                    <th class="px-4 py-2 text-slate-650 w-44">Name</th>
                                    <th class="px-4 py-2 text-slate-650">Reasons</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100 bg-white">
                                @foreach($summary['errors'] as $err)
                                    <tr class="hover:bg-slate-50/50">
                                        <td class="px-4 py-2.5 font-bold text-slate-500 text-center">{{ $err['row'] }}</td>
                                        <td class="px-4 py-2.5">
                                            <span class="font-bold text-slate-800 block">{{ $err['name'] }}</span>
                                            <span class="text-xxs text-slate-400 font-medium">{{ $err['email'] }}</span>
                                        </td>
                                        <td class="px-4 py-2.5 text-rose-600 font-medium">
                                            <ul class="list-disc list-inside space-y-0.5 text-xxs">
                                                @foreach($err['reasons'] as $reason)
                                                    <li>{{ $reason }}</li>
                                                @endforeach
                                            </ul>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            @endif

            <div class="flex items-center justify-between pt-4 border-t border-slate-100 shrink-0">
                @if($summary['fail_count'] > 0 && !empty($summary['failed_file']))
                    <a href="{{ route('admin.employees.download-failed-import', ['filename' => $summary['failed_file']]) }}"
                       class="px-5 py-2.5 bg-gradient-to-r from-amber-500 to-orange-500 hover:from-amber-600 hover:to-orange-600 text-white rounded-xl text-xs font-bold shadow-md shadow-orange-500/10 transition-all flex items-center">
                        <i class="fa-solid fa-download mr-2 text-sm"></i> Download Failed Records CSV
                    </a>
                @else
                    <div></div>
                @endif
                <button type="button" onclick="document.getElementById('csv-results-modal').classList.add('hidden')"
                    class="px-6 py-2.5 bg-slate-900 hover:bg-black text-white rounded-xl text-xs font-semibold transition-all">
                    Close Summary
                </button>
            </div>
        </div>
    </div>
@endif
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        // Multi-select dropdown toggle and behavior
        const multiselects = document.querySelectorAll('.custom-multiselect');

        multiselects.forEach(ms => {
            const toggleBtn = ms.querySelector('.multiselect-toggle');
            const menu = ms.querySelector('.multiselect-menu');
            const labelSpan = ms.querySelector('.multiselect-label');
            const checkboxes = ms.querySelectorAll('input[type="checkbox"]');
            const clearBtn = ms.querySelector('.clear-multiselect');

            function updateLabel() {
                const checked = ms.querySelectorAll('input[type="checkbox"]:checked');
                if (checked.length === 0) {
                    if (ms.querySelector('input[name="company_id[]"]')) labelSpan.textContent = 'All Companies';
                    else if (ms.querySelector('input[name="designation_id[]"]')) labelSpan.textContent = 'All Designations';
                    else if (ms.querySelector('input[name="department_id[]"]')) labelSpan.textContent = 'All Departments';
                    else if (ms.querySelector('input[name="status[]"]')) labelSpan.textContent = 'All Statuses';
                } else if (checked.length === 1) {
                    labelSpan.textContent = checked[0].closest('label').querySelector('span').textContent.trim();
                } else {
                    labelSpan.textContent = checked.length + ' Selected';
                }
            }

            toggleBtn.addEventListener('click', function (e) {
                e.stopPropagation();
                // Close other open menus
                document.querySelectorAll('.multiselect-menu').forEach(m => {
                    if (m !== menu) m.classList.add('hidden');
                });
                menu.classList.toggle('hidden');
            });

            menu.addEventListener('click', function(e) {
                e.stopPropagation();
            });

            checkboxes.forEach(cb => {
                cb.addEventListener('change', updateLabel);
            });

            if (clearBtn) {
                clearBtn.addEventListener('click', function() {
                    checkboxes.forEach(cb => cb.checked = false);
                    updateLabel();
                });
            }
        });

        // Close multi-select menus when clicking anywhere outside
        document.addEventListener('click', function () {
            document.querySelectorAll('.multiselect-menu').forEach(m => m.classList.add('hidden'));
        });

        // Bulk selection checkboxes
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

        window.handleBulkActionChange = function() {
            const action = document.getElementById('bulk_action').value;
            const offerLetterParams = document.getElementById('bulk_offer_letter_params');
            const statusChangeParams = document.getElementById('bulk_status_change_params');
            
            if (action === 'offer_letter') {
                offerLetterParams.classList.remove('hidden');
                statusChangeParams.classList.add('hidden');
            } else if (action === 'status_change') {
                offerLetterParams.classList.add('hidden');
                statusChangeParams.classList.remove('hidden');
            } else {
                offerLetterParams.classList.add('hidden');
                statusChangeParams.classList.add('hidden');
            }
        };

        window.handleBulkGenerateSubmit = function(e) {
            const checked = document.querySelectorAll('.employee-checkbox:checked');
            if (checked.length === 0) {
                alert('Please select at least one candidate first.');
                e.preventDefault();
                return false;
            }

            const action = document.getElementById('bulk_action').value;
            if (action === 'delete') {
                if (!confirm('Are you sure you want to delete the selected ' + checked.length + ' candidates? This cannot be undone.')) {
                    e.preventDefault();
                    return false;
                }
            }

            const container = document.getElementById('bulk-hidden-inputs');
            container.innerHTML = '';
            checked.forEach(cb => {
                const input = document.createElement('input');
                input.type = 'hidden';
                input.name = 'employee_ids[]';
                input.value = cb.value;
                container.appendChild(input);
            });
            return true;
        };
    });
</script>
@endsection
