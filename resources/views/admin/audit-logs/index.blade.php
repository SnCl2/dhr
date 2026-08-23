@extends('layouts.admin')

@section('title', 'Audit & Import Logs - Propszy')
@section('page_title', 'Audit & Import Logs')

@section('content')
<!-- Filter Section -->
<div class="glass-dark border border-slate-200 rounded-3xl p-6 mb-6 shadow-xl">
    <form action="{{ route('admin.audit-logs.index') }}" method="GET" class="flex flex-col md:flex-row md:items-end gap-4">
        <div class="flex-1 grid grid-cols-1 sm:grid-cols-3 gap-4">
            <div>
                <label for="activity_type" class="block text-xxs font-bold text-slate-600 uppercase tracking-wider mb-2">Activity Type</label>
                <select id="activity_type" name="activity_type" class="w-full px-3 py-2.5 bg-white border border-slate-300 rounded-xl text-xs text-slate-800 focus:outline-none focus:ring-1 focus:ring-purple-500 transition-all">
                    <option value="">All Activities</option>
                    <option value="employee_import" {{ request('activity_type') === 'employee_import' ? 'selected' : '' }}>Employee CSV Import</option>
                    <option value="bulk_offer_letter_upload" {{ request('activity_type') === 'bulk_offer_letter_upload' ? 'selected' : '' }}>Bulk Offer Letters Upload</option>
                    <option value="bulk_payslip_upload" {{ request('activity_type') === 'bulk_payslip_upload' ? 'selected' : '' }}>Bulk Payslips Upload</option>
                    <option value="bulk_payslip_generate" {{ request('activity_type') === 'bulk_payslip_generate' ? 'selected' : '' }}>Bulk Payslips CSV Generate</option>
                </select>
            </div>
            <div>
                <label for="start_date" class="block text-xxs font-bold text-slate-600 uppercase tracking-wider mb-2">Start Date</label>
                <input type="date" id="start_date" name="start_date" value="{{ request('start_date') }}" 
                    class="w-full px-4 py-2.5 bg-white border border-slate-300 rounded-xl text-xs text-slate-800 focus:outline-none focus:ring-1 focus:ring-purple-500 transition-all">
            </div>
            <div>
                <label for="end_date" class="block text-xxs font-bold text-slate-600 uppercase tracking-wider mb-2">End Date</label>
                <input type="date" id="end_date" name="end_date" value="{{ request('end_date') }}" 
                    class="w-full px-4 py-2.5 bg-white border border-slate-300 rounded-xl text-xs text-slate-800 focus:outline-none focus:ring-1 focus:ring-purple-500 transition-all">
            </div>
        </div>
        <div class="flex gap-2">
            <button type="submit" class="px-5 py-2.5 bg-gradient-to-r from-purple-600 to-indigo-600 hover:from-purple-500 hover:to-indigo-500 text-white font-semibold text-xs rounded-xl shadow-md transition-all flex items-center">
                <i class="fa-solid fa-filter mr-1.5"></i> Filter
            </button>
            <a href="{{ route('admin.audit-logs.index') }}" class="px-5 py-2.5 bg-white border border-slate-300 hover:bg-slate-50 text-slate-700 font-semibold text-xs rounded-xl transition-all flex items-center">
                <i class="fa-solid fa-xmark mr-1.5"></i> Clear
            </a>
        </div>
    </form>
</div>

<!-- Logs Table -->
<div class="glass-dark rounded-3xl overflow-hidden shadow-xl mb-8">
    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="border-b border-slate-200 bg-slate-50 text-xs font-semibold text-slate-500 uppercase tracking-wider">
                    <th class="p-4 pl-6">ID</th>
                    <th class="p-4">Activity</th>
                    <th class="p-4">Performed By</th>
                    <th class="p-4">Date & Time</th>
                    <th class="p-4">Filename / Scope</th>
                    <th class="p-4 text-center">Success / Fail</th>
                    <th class="p-4">Status</th>
                    <th class="p-4 pr-6 text-right">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-200 text-sm">
                @forelse($logs as $log)
                <tr class="text-slate-700 hover:bg-slate-50/80">
                    <td class="p-4 pl-6 font-semibold text-slate-500">#{{ $log->id }}</td>
                    <td class="p-4">
                        <div class="flex items-center space-x-2">
                            @if($log->activity_type === 'employee_import')
                                <span class="p-1.5 bg-blue-500/10 text-blue-700 rounded-lg"><i class="fa-solid fa-file-csv text-xs"></i></span>
                                <span class="font-semibold text-slate-800 text-xs">Employee CSV Import</span>
                            @elseif($log->activity_type === 'bulk_offer_letter_upload')
                                <span class="p-1.5 bg-purple-500/10 text-purple-700 rounded-lg"><i class="fa-solid fa-file-pdf text-xs"></i></span>
                                <span class="font-semibold text-slate-800 text-xs">Bulk Offer Letters</span>
                            @elseif($log->activity_type === 'bulk_payslip_upload')
                                <span class="p-1.5 bg-pink-500/10 text-pink-700 rounded-lg"><i class="fa-solid fa-file-invoice-dollar text-xs"></i></span>
                                <span class="font-semibold text-slate-800 text-xs">Bulk Payslips Upload</span>
                            @elseif($log->activity_type === 'bulk_payslip_generate')
                                <span class="p-1.5 bg-emerald-500/10 text-emerald-700 rounded-lg"><i class="fa-solid fa-calculator text-xs"></i></span>
                                <span class="font-semibold text-slate-800 text-xs">Bulk Payslips Generate</span>
                            @endif
                        </div>
                    </td>
                    <td class="p-4">
                        <div class="flex flex-col">
                            <span class="font-semibold text-slate-800 text-xs">{{ $log->performed_by_name ?: 'System' }}</span>
                            <span class="text-[10px] text-slate-400 font-mono">{{ basename(str_replace('\\', '/', $log->performed_by_type)) }} (ID: {{ $log->performed_by_id }})</span>
                        </div>
                    </td>
                    <td class="p-4 text-xs text-slate-500">{{ $log->created_at ? $log->created_at->format('d-M-Y h:i A') : 'N/A' }}</td>
                    <td class="p-4 text-xs text-slate-650 max-w-[200px] truncate" title="{{ $log->filename }}">{{ $log->filename ?: 'N/A' }}</td>
                    <td class="p-4 text-center">
                        <div class="inline-flex items-center space-x-1.5 text-xs font-semibold">
                            <span class="px-2 py-0.5 bg-emerald-500/10 text-emerald-700 rounded-md">{{ $log->success_count }}</span>
                            <span class="text-slate-300">/</span>
                            <span class="px-2 py-0.5 bg-rose-500/10 text-rose-700 rounded-md">{{ $log->failed_count }}</span>
                        </div>
                    </td>
                    <td class="p-4">
                        @php
                            $success = $log->success_count;
                            $fail = $log->failed_count;
                        @endphp
                        @if($fail == 0 && $success > 0)
                            <span class="px-2.5 py-0.5 rounded-lg text-xxs font-bold uppercase tracking-wider bg-emerald-500/10 text-emerald-700 border border-emerald-500/20 inline-block">Success</span>
                        @elseif($fail > 0 && $success > 0)
                            <span class="px-2.5 py-0.5 rounded-lg text-xxs font-bold uppercase tracking-wider bg-amber-500/10 text-amber-700 border border-amber-500/20 inline-block">Warnings</span>
                        @elseif($fail > 0 && $success == 0)
                            <span class="px-2.5 py-0.5 rounded-lg text-xxs font-bold uppercase tracking-wider bg-rose-500/10 text-rose-700 border border-rose-500/20 inline-block">Failed</span>
                        @else
                            <span class="px-2.5 py-0.5 rounded-lg text-xxs font-bold uppercase tracking-wider bg-slate-100 text-slate-600 border border-slate-200 inline-block">Empty</span>
                        @endif
                    </td>
                    <td class="p-4 pr-6 text-right whitespace-nowrap space-x-1">
                        <button type="button" onclick="openLogModal({{ $log->id }}, '{{ $log->activity_type }}')" 
                                class="px-3 py-1.5 bg-purple-500/10 hover:bg-purple-500/20 text-xs font-semibold text-purple-700 hover:text-purple-800 rounded-xl transition-all" title="View Details">
                            <i class="fa-solid fa-eye"></i> View Logs
                        </button>
                        @if($log->failed_csv_path)
                        <a href="{{ route('admin.audit-logs.download-failed', $log) }}" 
                           class="px-3 py-1.5 bg-rose-500/10 hover:bg-rose-500/20 text-xs font-semibold text-rose-700 hover:text-rose-800 rounded-xl transition-all inline-block" title="Download Failed CSV">
                            <i class="fa-solid fa-download"></i> Failed CSV
                        </a>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" class="p-12 text-center text-slate-500 text-base">
                        <i class="fa-solid fa-clock-rotate-left text-3xl mb-4 block text-slate-400"></i>
                        No audit logs recorded yet.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    @if($logs->hasPages())
    <div class="px-6 py-4 border-t border-slate-200">
        {{ $logs->links() }}
    </div>
    @endif
</div>

<!-- View Log Details Modal -->
<div id="log-modal" class="hidden fixed inset-0 z-50 overflow-y-auto flex items-center justify-center bg-slate-950/85 backdrop-blur-sm">
    <div class="bg-white border border-slate-200 w-full max-w-4xl mx-4 rounded-3xl shadow-2xl p-6 sm:p-8 relative max-h-[85vh] flex flex-col">
        <button onclick="closeLogModal()" class="absolute top-5 right-5 text-slate-400 hover:text-slate-700 transition-colors">
            <i class="fa-solid fa-xmark text-xl"></i>
        </button>

        <h3 class="font-outfit font-bold text-xl text-slate-800 mb-4 flex items-center">
            <i class="fa-solid fa-list-check text-purple-600 mr-2.5"></i> Detailed Execution Log
        </h3>

        <!-- Tabs -->
        <div class="flex border-b border-slate-200 mb-4">
            <button id="tab-success" onclick="switchTab('success')" class="px-4 py-2 border-b-2 border-purple-600 text-purple-600 font-semibold text-xs transition-all uppercase tracking-wider">
                Success Logs (<span id="count-success">0</span>)
            </button>
            <button id="tab-failures" onclick="switchTab('failures')" class="px-4 py-2 border-b-2 border-transparent text-slate-500 font-semibold text-xs transition-all hover:text-slate-800 uppercase tracking-wider">
                Failed / Skipped Logs (<span id="count-failures">0</span>)
            </button>
        </div>

        <!-- Tab Content -->
        <div class="flex-grow overflow-y-auto min-h-[300px]">
            <!-- Success Content -->
            <div id="content-success" class="space-y-3">
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-xs border-collapse">
                        <thead>
                            <tr class="bg-slate-50 border-b border-slate-200 text-slate-500 font-bold uppercase tracking-wider">
                                <th class="p-3 pl-4">Identifier / ID</th>
                                <th class="p-3">Candidate / Staff Name</th>
                                <th class="p-3 pr-4">Details</th>
                            </tr>
                        </thead>
                        <tbody id="success-rows" class="divide-y divide-slate-100 text-slate-700 font-medium">
                            <!-- Populated dynamically -->
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Failures Content -->
            <div id="content-failures" class="space-y-3 hidden">
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-xs border-collapse">
                        <thead>
                            <tr class="bg-slate-50 border-b border-slate-200 text-slate-500 font-bold uppercase tracking-wider">
                                <th class="p-3 pl-4">Source Row / File</th>
                                <th class="p-3">Expected ID</th>
                                <th class="p-3 pr-4">Failure Reasons</th>
                            </tr>
                        </thead>
                        <tbody id="failure-rows" class="divide-y divide-slate-100 text-slate-700 font-medium">
                            <!-- Populated dynamically -->
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Footer -->
        <div class="pt-4 border-t border-slate-200 flex justify-end">
            <button onclick="closeLogModal()" class="px-5 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-xl text-xs font-semibold transition-all">
                Close
            </button>
        </div>
    </div>
</div>

<script>
    let activeTab = 'success';
    let logData = { success: [], failures: [] };

    function openLogModal(logId, activityType) {
        // Reset modal state
        document.getElementById('success-rows').innerHTML = `<tr><td colspan="3" class="p-6 text-center text-slate-400"><i class="fa-solid fa-spinner animate-spin text-xl mr-2"></i>Loading details...</td></tr>`;
        document.getElementById('failure-rows').innerHTML = `<tr><td colspan="3" class="p-6 text-center text-slate-400"><i class="fa-solid fa-spinner animate-spin text-xl mr-2"></i>Loading details...</td></tr>`;
        document.getElementById('count-success').textContent = "0";
        document.getElementById('count-failures').textContent = "0";

        // Show Modal
        document.getElementById('log-modal').classList.remove('hidden');

        // Fetch log JSON details
        fetch(`/admin/audit-logs/${logId}/json`)
            .then(res => {
                if (!res.ok) throw new Error("Network response error");
                return res.json();
            })
            .then(data => {
                logData = data || { success: [], failures: [] };
                if (!logData.success) logData.success = [];
                if (!logData.failures) logData.failures = [];

                document.getElementById('count-success').textContent = logData.success.length;
                document.getElementById('count-failures').textContent = logData.failures.length;

                renderRows();
            })
            .catch(err => {
                console.error(err);
                document.getElementById('success-rows').innerHTML = `<tr><td colspan="3" class="p-6 text-center text-rose-500 font-semibold"><i class="fa-solid fa-circle-exclamation mr-1.5"></i>Failed to fetch log details.</td></tr>`;
                document.getElementById('failure-rows').innerHTML = `<tr><td colspan="3" class="p-6 text-center text-rose-500 font-semibold"><i class="fa-solid fa-circle-exclamation mr-1.5"></i>Failed to fetch log details.</td></tr>`;
            });
    }

    function renderRows() {
        // Success rows
        const successBody = document.getElementById('success-rows');
        if (logData.success.length === 0) {
            successBody.innerHTML = `<tr><td colspan="3" class="p-8 text-center text-slate-400 font-medium">No success logs recorded for this operation.</td></tr>`;
        } else {
            successBody.innerHTML = logData.success.map(item => `
                <tr class="hover:bg-slate-50/50">
                    <td class="p-3 pl-4 font-mono text-purple-750 font-semibold">${escapeHtml(item.identifier || 'N/A')}</td>
                    <td class="p-3 text-slate-800">${escapeHtml(item.name || 'N/A')}</td>
                    <td class="p-3 pr-4 text-slate-500">${escapeHtml(item.message || 'Processed successfully')}</td>
                </tr>
            `).join('');
        }

        // Failure rows
        const failureBody = document.getElementById('failure-rows');
        if (logData.failures.length === 0) {
            failureBody.innerHTML = `<tr><td colspan="3" class="p-8 text-center text-slate-400 font-medium">No failure logs recorded. All files processed successfully!</td></tr>`;
        } else {
            failureBody.innerHTML = logData.failures.map(item => {
                const reasonsList = (item.reasons || []).map(r => `<span class="block text-rose-600 font-semibold text-[11px]">• ${escapeHtml(r)}</span>`).join('');
                return `
                    <tr class="hover:bg-slate-50/50">
                        <td class="p-3 pl-4 text-slate-800 font-mono">${escapeHtml(item.row_or_file || 'N/A')}</td>
                        <td class="p-3 text-slate-500 font-mono">${escapeHtml(item.identifier || 'N/A')}</td>
                        <td class="p-3 pr-4">${reasonsList || 'Unknown failure reason'}</td>
                    </tr>
                `;
            }).join('');
        }
    }

    function switchTab(tab) {
        activeTab = tab;
        const tabSuccess = document.getElementById('tab-success');
        const tabFailures = document.getElementById('tab-failures');
        const contentSuccess = document.getElementById('content-success');
        const contentFailures = document.getElementById('content-failures');

        if (tab === 'success') {
            tabSuccess.className = "px-4 py-2 border-b-2 border-purple-600 text-purple-600 font-semibold text-xs transition-all uppercase tracking-wider";
            tabFailures.className = "px-4 py-2 border-b-2 border-transparent text-slate-500 font-semibold text-xs transition-all hover:text-slate-800 uppercase tracking-wider";
            contentSuccess.classList.remove('hidden');
            contentFailures.classList.add('hidden');
        } else {
            tabFailures.className = "px-4 py-2 border-b-2 border-purple-600 text-purple-600 font-semibold text-xs transition-all uppercase tracking-wider";
            tabSuccess.className = "px-4 py-2 border-b-2 border-transparent text-slate-500 font-semibold text-xs transition-all hover:text-slate-800 uppercase tracking-wider";
            contentFailures.classList.remove('hidden');
            contentSuccess.classList.add('hidden');
        }
    }

    function closeLogModal() {
        document.getElementById('log-modal').classList.add('hidden');
        switchTab('success'); // reset tab default
    }

    function escapeHtml(str) {
        if (!str) return '';
        return String(str)
            .replace(/&/g, "&amp;")
            .replace(/</g, "&lt;")
            .replace(/>/g, "&gt;")
            .replace(/"/g, "&quot;")
            .replace(/'/g, "&#039;");
    }
</script>
@endsection
