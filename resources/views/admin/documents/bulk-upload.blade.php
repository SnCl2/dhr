@extends('layouts.admin')

@section('title', 'Bulk Upload Documents - Propszy')
@section('page_title', 'Bulk Document Upload & Assignment')

@section('content')
<div class="max-w-4xl mx-auto">
    <!-- Summary Alert if upload just finished -->
    @if(session('bulk_upload_summary'))
        @php
            $summary = session('bulk_upload_summary');
        @endphp
        <div class="mb-8 p-6 rounded-2xl bg-white border border-slate-200 shadow-xl overflow-hidden">
            <h3 class="font-outfit font-bold text-lg text-slate-800 mb-4 flex items-center">
                <i class="fa-solid fa-list-check text-purple-600 mr-2"></i> Upload Processing Summary
            </h3>
            
            <div class="grid grid-cols-2 gap-4 mb-4">
                <div class="bg-emerald-50 border border-emerald-100 p-4 rounded-xl text-center">
                    <span class="block text-2xl font-bold text-emerald-600">{{ $summary['success_count'] }}</span>
                    <span class="text-xs font-semibold text-emerald-800 uppercase tracking-wider">Assigned Successfully</span>
                </div>
                <div class="bg-rose-50 border border-rose-100 p-4 rounded-xl text-center">
                    <span class="block text-2xl font-bold text-rose-600">{{ $summary['fail_count'] }}</span>
                    <span class="text-xs font-semibold text-rose-800 uppercase tracking-wider">Skipped / Failed</span>
                </div>
            </div>

            @if(count($summary['errors']) > 0)
                <div class="bg-slate-50 border border-slate-200 p-4 rounded-xl max-h-60 overflow-y-auto">
                    <span class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Detailed Skip Logs:</span>
                    <ul class="space-y-1.5 text-xs text-rose-600 font-mono">
                        @foreach($summary['errors'] as $err)
                            <li class="flex items-start">
                                <span class="mr-1.5">•</span>
                                <span>{{ $err }}</span>
                            </li>
                        @endforeach
                    </ul>
                </div>
            @endif
        </div>
    @endif

    <div class="glass-dark p-8 sm:p-10 rounded-3xl border border-slate-200 shadow-xl">
        <form action="{{ route('admin.documents.bulk-upload.submit') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
            @csrf

            <h3 class="font-outfit font-bold text-lg text-slate-800 mb-6 flex items-center">
                <i class="fa-solid fa-cloud-arrow-up text-purple-600 mr-2 text-sm"></i> Bulk Document Distribution
            </h3>

            <p class="text-sm text-slate-650 leading-relaxed mb-6">
                Upload multiple candidate PDF files at once. The system will look at the file names (e.g. <code class="bg-slate-100 px-1.5 py-0.5 rounded text-purple-600 font-mono text-xs">RM010001.pdf</code>) to automatically match the candidate ID and assign the documents.
            </p>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                <!-- Document Type Select -->
                <div class="sm:col-span-2">
                    <label for="doc_type" class="block text-xs font-semibold uppercase tracking-wider text-slate-600">Select Document Type</label>
                    <select id="doc_type" name="doc_type" required
                        class="mt-2 block w-full px-4 py-3 bg-white border border-slate-300 rounded-xl text-slate-800 focus:outline-none focus:ring-1 focus:ring-purple-500 text-sm">
                        <option value="offer_letter" {{ old('doc_type') === 'offer_letter' ? 'selected' : '' }}>Candidate Offer Letters</option>
                        <option value="payslip" {{ old('doc_type') === 'payslip' ? 'selected' : '' }}>Candidate Payslips</option>
                    </select>
                </div>

                <!-- Payslip Specific Month Section -->
                <div id="payslip_fields" class="sm:col-span-2 hidden space-y-6">
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                        <div>
                            <label for="month" class="block text-xs font-semibold uppercase tracking-wider text-slate-600">Payslip Month & Year</label>
                            <input type="text" id="month" name="month" placeholder="e.g., August 2026"
                                value="{{ old('month') }}"
                                class="mt-2 block w-full px-4 py-3 bg-white border border-slate-300 rounded-xl text-slate-800 focus:outline-none focus:ring-1 focus:ring-purple-500 text-sm">
                        </div>

                        <div>
                            <label for="type" class="block text-xs font-semibold uppercase tracking-wider text-slate-600">Payroll Type</label>
                            <select id="type" name="type"
                                class="mt-2 block w-full px-4 py-3 bg-white border border-slate-300 rounded-xl text-slate-800 focus:outline-none focus:ring-1 focus:ring-purple-500 text-sm">
                                <option value="external" {{ old('type') === 'external' ? 'selected' : '' }}>External Staff</option>
                                <option value="internal" {{ old('type') === 'internal' ? 'selected' : '' }}>Internal Staff</option>
                            </select>
                        </div>
                    </div>
                </div>

                <!-- Multiple File Select -->
                <div class="sm:col-span-2">
                    <label class="block text-xs font-semibold uppercase tracking-wider text-slate-600">Select PDF Files</label>
                    <div class="mt-2 border-2 border-dashed border-slate-300 hover:border-purple-500 transition-colors rounded-2xl flex flex-col items-center justify-center p-8 bg-white relative">
                        <input type="file" name="files[]" id="files" multiple accept=".pdf" required
                            class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10" />
                        <i class="fa-solid fa-file-pdf text-4xl text-slate-400 mb-3" id="upload-icon"></i>
                        <span class="block text-sm font-semibold text-slate-700" id="file-count-text">Drag and drop PDF files here, or click to browse</span>
                        <span class="block text-xs text-slate-400 mt-1">Accepts multiple .pdf files named as Employee ID</span>
                    </div>
                </div>
            </div>

            <!-- Submit Button -->
            <div class="pt-6 border-t border-slate-200 flex justify-end">
                <button type="submit"
                    class="w-full sm:w-auto px-6 py-3.5 bg-gradient-to-r from-purple-600 to-indigo-600 hover:from-purple-500 hover:to-indigo-500 text-white rounded-xl text-sm font-semibold transition-all shadow-lg shadow-purple-500/10">
                    <i class="fa-solid fa-upload mr-2"></i> Start Document Distribution
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const docType = document.getElementById('doc_type');
        const payslipFields = document.getElementById('payslip_fields');
        const monthInput = document.getElementById('month');
        const fileInput = document.getElementById('files');
        const fileCountText = document.getElementById('file-count-text');
        const uploadIcon = document.getElementById('upload-icon');

        function toggleFields() {
            if (docType.value === 'payslip') {
                payslipFields.classList.remove('hidden');
                monthInput.setAttribute('required', 'required');
            } else {
                payslipFields.classList.add('hidden');
                monthInput.removeAttribute('required');
            }
        }

        docType.addEventListener('change', toggleFields);
        toggleFields(); // Initial run

        fileInput.addEventListener('change', function () {
            const count = fileInput.files.length;
            if (count > 0) {
                fileCountText.textContent = `${count} PDF file(s) selected`;
                fileCountText.classList.add('text-purple-650');
                uploadIcon.className = "fa-solid fa-file-circle-check text-4xl text-purple-600 mb-3 animate-bounce";
            } else {
                fileCountText.textContent = "Drag and drop PDF files here, or click to browse";
                fileCountText.classList.remove('text-purple-650');
                uploadIcon.className = "fa-solid fa-file-pdf text-4xl text-slate-400 mb-3";
            }
        });
    });
</script>
@endsection
