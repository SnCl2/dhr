@extends('layouts.admin')

@section('title', 'Onboard New Candidate / Employee - RM HR Solutions')
@section('page_title', 'Onboard New Candidate / Employee')

@section('content')
<div class="max-w-6xl mx-auto space-y-6">
    <div class="flex items-center justify-between">
        <a href="{{ route('admin.employees.index') }}" class="inline-flex items-center text-xs font-semibold text-blue-600 hover:text-blue-700 transition-all">
            <i class="fa-solid fa-arrow-left mr-2"></i> Back to Employees List
        </a>
    </div>

    <div class="bg-white p-6 sm:p-10 rounded-3xl border border-slate-200 shadow-sm">
        <form action="{{ route('admin.employees.store') }}" method="POST" enctype="multipart/form-data" class="space-y-8">
            @csrf

            <!-- Section 1: Personal & Profile Details -->
            <div class="space-y-6">
                <div class="flex items-center justify-between border-b border-slate-100 pb-4">
                    <h3 class="text-sm font-bold text-blue-600 uppercase tracking-wider flex items-center">
                        <i class="fa-solid fa-user-circle mr-2 text-base"></i> 1. Personal & Profile Details
                    </h3>
                    <span class="text-xs text-slate-400 font-medium">* Required fields</span>
                </div>

                <!-- Modern Profile Picture Upload Card -->
                <div class="p-6 rounded-2xl bg-gradient-to-r from-slate-50 to-blue-50/30 border border-slate-200/90 flex flex-col sm:flex-row items-center space-y-4 sm:space-y-0 sm:space-x-6">
                    <div class="relative group">
                        <div id="profile_image_preview_container" class="w-24 h-24 rounded-2xl bg-white border-2 border-dashed border-blue-300 shadow-sm flex items-center justify-center text-blue-400 text-3xl shrink-0 overflow-hidden transition-all group-hover:border-blue-500">
                            <i class="fa-solid fa-camera text-blue-400/80" id="profile_placeholder_icon"></i>
                            <img id="profile_preview_img" src="" alt="Profile preview" class="w-full h-full object-cover hidden">
                        </div>
                        <label for="profile_image" class="absolute -bottom-2 -right-2 w-8 h-8 bg-blue-600 hover:bg-blue-700 text-white rounded-full flex items-center justify-center shadow-md cursor-pointer transition-all">
                            <i class="fa-solid fa-pen text-xs"></i>
                        </label>
                    </div>

                    <div class="flex-grow space-y-2 text-center sm:text-left">
                        <div>
                            <h4 class="text-sm font-bold text-slate-800">Employee Profile Picture</h4>
                            <p class="text-xs text-slate-500">Upload a formal passport-size photo (JPG, PNG, WebP up to 5MB)</p>
                        </div>
                        <div class="flex flex-wrap items-center justify-center sm:justify-start gap-3">
                            <label for="profile_image" class="inline-flex items-center px-4 py-2 bg-white hover:bg-blue-50 border border-slate-300 hover:border-blue-400 text-slate-700 hover:text-blue-600 rounded-xl text-xs font-semibold shadow-xs cursor-pointer transition-all">
                                <i class="fa-solid fa-cloud-arrow-up mr-2 text-blue-500"></i> Browse Photo
                            </label>
                            <span id="profile_file_name" class="text-xs text-slate-500 italic">No photo selected</span>
                        </div>
                        <input type="file" id="profile_image" name="profile_image" accept=".jpg,.jpeg,.png,.webp" class="hidden" onchange="previewProfileImage(this)">
                        @error('profile_image') <p class="text-xs text-rose-500 font-medium">{{ $message }}</p> @enderror
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-12 gap-6">
                    <!-- Prefix -->
                    <div class="sm:col-span-3">
                        <label for="prefix" class="block text-xs font-semibold uppercase tracking-wider text-slate-600">Prefix <span class="text-rose-500">*</span></label>
                        <select id="prefix" name="prefix" class="mt-2 block w-full px-4 py-3 bg-white border border-slate-300 rounded-xl text-slate-800 focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm shadow-xs" required>
                            <option value="Mr." {{ old('prefix') === 'Mr.' ? 'selected' : '' }}>Mr.</option>
                            <option value="Mrs." {{ old('prefix') === 'Mrs.' ? 'selected' : '' }}>Mrs.</option>
                            <option value="Ms." {{ old('prefix') === 'Ms.' ? 'selected' : '' }}>Ms.</option>
                            <option value="Dr." {{ old('prefix') === 'Dr.' ? 'selected' : '' }}>Dr.</option>
                        </select>
                        @error('prefix') <p class="mt-1.5 text-xs text-rose-500 font-medium">{{ $message }}</p> @enderror
                    </div>

                    <!-- Full Name as per Aadhaar -->
                    <div class="sm:col-span-9">
                        <label for="aadhaar_full_name" class="block text-xs font-semibold uppercase tracking-wider text-slate-600">Full Name <span class="text-slate-400 font-normal">(As on Aadhaar / Official Records)</span> <span class="text-rose-500">*</span></label>
                        <input type="text" id="aadhaar_full_name" name="aadhaar_full_name" value="{{ old('aadhaar_full_name') }}" class="mt-2 block w-full px-4 py-3 bg-white border border-slate-300 rounded-xl text-slate-800 focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm font-medium shadow-xs" placeholder="e.g. John Doe" required>
                        @error('aadhaar_full_name') <p class="mt-1.5 text-xs text-rose-500 font-medium">{{ $message }}</p> @enderror
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-6">
                    <!-- Gender -->
                    <div>
                        <label for="gender" class="block text-xs font-semibold uppercase tracking-wider text-slate-600">Gender <span class="text-rose-500">*</span></label>
                        <select id="gender" name="gender" class="mt-2 block w-full px-4 py-3 bg-white border border-slate-300 rounded-xl text-slate-800 focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm shadow-xs" required>
                            <option value="Male" {{ old('gender') === 'Male' ? 'selected' : '' }}>Male</option>
                            <option value="Female" {{ old('gender') === 'Female' ? 'selected' : '' }}>Female</option>
                            <option value="Other" {{ old('gender') === 'Other' ? 'selected' : '' }}>Other</option>
                        </select>
                        @error('gender') <p class="mt-1.5 text-xs text-rose-500 font-medium">{{ $message }}</p> @enderror
                    </div>

                    <!-- Date of Birth -->
                    <div>
                        <label for="dob" class="block text-xs font-semibold uppercase tracking-wider text-slate-600">Date of Birth <span class="text-rose-500">*</span></label>
                        <input type="date" id="dob" name="dob" value="{{ old('dob') }}" class="mt-2 block w-full px-4 py-3 bg-white border border-slate-300 rounded-xl text-slate-800 focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm shadow-xs" required>
                        @error('dob') <p class="mt-1.5 text-xs text-rose-500 font-medium">{{ $message }}</p> @enderror
                    </div>

                    <!-- Marital Status -->
                    <div>
                        <label for="marital_status" class="block text-xs font-semibold uppercase tracking-wider text-slate-600">Marital Status <span class="text-rose-500">*</span></label>
                        <select id="marital_status" name="marital_status" class="mt-2 block w-full px-4 py-3 bg-white border border-slate-300 rounded-xl text-slate-800 focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm shadow-xs" required>
                            <option value="Single" {{ old('marital_status') === 'Single' ? 'selected' : '' }}>Single</option>
                            <option value="Married" {{ old('marital_status') === 'Married' ? 'selected' : '' }}>Married</option>
                            <option value="Divorced" {{ old('marital_status') === 'Divorced' ? 'selected' : '' }}>Divorced</option>
                            <option value="Widowed" {{ old('marital_status') === 'Widowed' ? 'selected' : '' }}>Widowed</option>
                        </select>
                        @error('marital_status') <p class="mt-1.5 text-xs text-rose-500 font-medium">{{ $message }}</p> @enderror
                    </div>

                    <!-- Mother Tongue -->
                    <div>
                        <label for="mother_tongue" class="block text-xs font-semibold uppercase tracking-wider text-slate-600">Mother Tongue <span class="text-rose-500">*</span></label>
                        <input type="text" id="mother_tongue" name="mother_tongue" value="{{ old('mother_tongue', 'Bengali') }}" class="mt-2 block w-full px-4 py-3 bg-white border border-slate-300 rounded-xl text-slate-800 focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm shadow-xs" placeholder="e.g. Bengali, Hindi" required>
                        @error('mother_tongue') <p class="mt-1.5 text-xs text-rose-500 font-medium">{{ $message }}</p> @enderror
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                    <!-- Father's Name -->
                    <div>
                        <label for="father_name_aadhaar" class="block text-xs font-semibold uppercase tracking-wider text-slate-600">Father's Name <span class="text-rose-500">*</span></label>
                        <input type="text" id="father_name_aadhaar" name="father_name_aadhaar" value="{{ old('father_name_aadhaar') }}" class="mt-2 block w-full px-4 py-3 bg-white border border-slate-300 rounded-xl text-slate-800 focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm shadow-xs" placeholder="Father's Full Name" required>
                        @error('father_name_aadhaar') <p class="mt-1.5 text-xs text-rose-500 font-medium">{{ $message }}</p> @enderror
                    </div>

                    <!-- Mother's Name -->
                    <div>
                        <label for="mother_name_aadhaar" class="block text-xs font-semibold uppercase tracking-wider text-slate-600">Mother's Name <span class="text-rose-500">*</span></label>
                        <input type="text" id="mother_name_aadhaar" name="mother_name_aadhaar" value="{{ old('mother_name_aadhaar') }}" class="mt-2 block w-full px-4 py-3 bg-white border border-slate-300 rounded-xl text-slate-800 focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm shadow-xs" placeholder="Mother's Full Name" required>
                        @error('mother_name_aadhaar') <p class="mt-1.5 text-xs text-rose-500 font-medium">{{ $message }}</p> @enderror
                    </div>
                </div>
            </div>

            <!-- Section 2: Contact & Address Information -->
            <div class="border-t border-slate-100 pt-6 space-y-6">
                <h3 class="text-sm font-bold text-blue-600 uppercase tracking-wider flex items-center">
                    <i class="fa-solid fa-location-dot mr-2 text-base"></i> 2. Contact & Address Information
                </h3>

                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-6">
                    <!-- Primary Email (Username) -->
                    <div>
                        <label for="email" class="block text-xs font-semibold uppercase tracking-wider text-slate-600">Primary Email <span class="text-slate-400 font-normal">(Login ID)</span> <span class="text-rose-500">*</span></label>
                        <input type="email" id="email" name="email" required value="{{ old('email') }}"
                            class="mt-2 block w-full px-4 py-3 bg-white border border-slate-300 rounded-xl text-slate-800 focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm shadow-xs" placeholder="employee@example.com">
                        @error('email') <p class="mt-1.5 text-xs text-rose-500 font-medium">{{ $message }}</p> @enderror
                    </div>

                    <!-- Primary Mobile Number -->
                    <div>
                        <label for="phone" class="block text-xs font-semibold uppercase tracking-wider text-slate-600">Primary Phone / Mobile</label>
                        <input type="text" id="phone" name="phone" value="{{ old('phone') }}"
                            class="mt-2 block w-full px-4 py-3 bg-white border border-slate-300 rounded-xl text-slate-800 focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm shadow-xs" placeholder="+91 9876543210">
                        @error('phone') <p class="mt-1.5 text-xs text-rose-500 font-medium">{{ $message }}</p> @enderror
                    </div>

                    <!-- Secondary Email -->
                    <div>
                        <label for="email_id" class="block text-xs font-semibold uppercase tracking-wider text-slate-600">Secondary Email</label>
                        <input type="email" id="email_id" name="email_id" value="{{ old('email_id') }}" class="mt-2 block w-full px-4 py-3 bg-white border border-slate-300 rounded-xl text-slate-800 focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm shadow-xs" placeholder="personal@example.com">
                        @error('email_id') <p class="mt-1.5 text-xs text-rose-500 font-medium">{{ $message }}</p> @enderror
                    </div>

                    <!-- Secondary Contact Number -->
                    <div>
                        <label for="contact_number" class="block text-xs font-semibold uppercase tracking-wider text-slate-600">Secondary Mobile <span class="text-rose-500">*</span></label>
                        <input type="text" id="contact_number" name="contact_number" value="{{ old('contact_number') }}" class="mt-2 block w-full px-4 py-3 bg-white border border-slate-300 rounded-xl text-slate-800 focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm shadow-xs" placeholder="Alternate Mobile" required>
                        @error('contact_number') <p class="mt-1.5 text-xs text-rose-500 font-medium">{{ $message }}</p> @enderror
                    </div>

                    <!-- Emergency Contact -->
                    <div class="sm:col-span-2 md:col-span-2">
                        <label for="emergency_contact_number" class="block text-xs font-semibold uppercase tracking-wider text-slate-600">Emergency Contact Number <span class="text-rose-500">*</span></label>
                        <input type="text" id="emergency_contact_number" name="emergency_contact_number" value="{{ old('emergency_contact_number') }}" class="mt-2 block w-full px-4 py-3 bg-white border border-slate-300 rounded-xl text-slate-800 focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm shadow-xs" placeholder="Emergency Phone" required>
                        @error('emergency_contact_number') <p class="mt-1.5 text-xs text-rose-500 font-medium">{{ $message }}</p> @enderror
                    </div>
                </div>

                <!-- Full Address -->
                <div>
                    <label for="aadhaar_address" class="block text-xs font-semibold uppercase tracking-wider text-slate-600">Residential Address <span class="text-slate-400 font-normal">(As per Aadhaar)</span> <span class="text-rose-500">*</span></label>
                    <textarea id="aadhaar_address" name="aadhaar_address" rows="2" class="mt-2 block w-full px-4 py-3 bg-white border border-slate-300 rounded-xl text-slate-800 focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm leading-relaxed shadow-xs" placeholder="Village / House No, Post Office, Police Station, District" required>{{ old('aadhaar_address') }}</textarea>
                    @error('aadhaar_address') <p class="mt-1.5 text-xs text-rose-500 font-medium">{{ $message }}</p> @enderror
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-6">
                    <!-- Landmark -->
                    <div>
                        <label for="landmark" class="block text-xs font-semibold uppercase tracking-wider text-slate-600">Landmark <span class="text-rose-500">*</span></label>
                        <input type="text" id="landmark" name="landmark" value="{{ old('landmark') }}" class="mt-2 block w-full px-4 py-3 bg-white border border-slate-300 rounded-xl text-slate-800 focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm shadow-xs" placeholder="e.g. Near High School" required>
                        @error('landmark') <p class="mt-1.5 text-xs text-rose-500 font-medium">{{ $message }}</p> @enderror
                    </div>

                    <!-- City -->
                    <div>
                        <label for="city" class="block text-xs font-semibold uppercase tracking-wider text-slate-600">City / Town <span class="text-rose-500">*</span></label>
                        <input type="text" id="city" name="city" value="{{ old('city') }}" class="mt-2 block w-full px-4 py-3 bg-white border border-slate-300 rounded-xl text-slate-800 focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm shadow-xs" placeholder="City" required>
                        @error('city') <p class="mt-1.5 text-xs text-rose-500 font-medium">{{ $message }}</p> @enderror
                    </div>

                    <!-- State -->
                    <div>
                        <label for="state" class="block text-xs font-semibold uppercase tracking-wider text-slate-600">State <span class="text-rose-500">*</span></label>
                        <input type="text" id="state" name="state" value="{{ old('state', 'West Bengal') }}" class="mt-2 block w-full px-4 py-3 bg-white border border-slate-300 rounded-xl text-slate-800 focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm shadow-xs" placeholder="State" required>
                        @error('state') <p class="mt-1.5 text-xs text-rose-500 font-medium">{{ $message }}</p> @enderror
                    </div>

                    <!-- Pin Code -->
                    <div>
                        <label for="pin_code" class="block text-xs font-semibold uppercase tracking-wider text-slate-600">PIN Code <span class="text-rose-500">*</span></label>
                        <input type="text" id="pin_code" name="pin_code" value="{{ old('pin_code') }}" class="mt-2 block w-full px-4 py-3 bg-white border border-slate-300 rounded-xl text-slate-800 focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm shadow-xs" placeholder="e.g. 721630" required>
                        @error('pin_code') <p class="mt-1.5 text-xs text-rose-500 font-medium">{{ $message }}</p> @enderror
                    </div>
                </div>
            </div>

            <!-- Section 3: Government ID & Statutory KYC -->
            <div class="border-t border-slate-100 pt-6 space-y-6">
                <h3 class="text-sm font-bold text-blue-600 uppercase tracking-wider flex items-center">
                    <i class="fa-solid fa-id-card mr-2 text-base"></i> 3. Government ID & Statutory KYC
                </h3>

                <div class="grid grid-cols-1 sm:grid-cols-3 gap-6">
                    <!-- Aadhaar Number -->
                    <div>
                        <label for="aadhaar_number" class="block text-xs font-semibold uppercase tracking-wider text-slate-600">Aadhaar Number <span class="text-rose-500">*</span></label>
                        <input type="text" id="aadhaar_number" name="aadhaar_number" value="{{ old('aadhaar_number') }}" class="mt-2 block w-full px-4 py-3 bg-white border border-slate-300 rounded-xl text-slate-800 focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm font-mono shadow-xs" placeholder="12-digit Aadhaar" required>
                        @error('aadhaar_number') <p class="mt-1.5 text-xs text-rose-500 font-medium">{{ $message }}</p> @enderror
                    </div>

                    <!-- PAN Number -->
                    <div>
                        <label for="pan_number" class="block text-xs font-semibold uppercase tracking-wider text-slate-600">PAN Card Number</label>
                        <input type="text" id="pan_number" name="pan_number" value="{{ old('pan_number') }}" class="mt-2 block w-full px-4 py-3 bg-white border border-slate-300 rounded-xl text-slate-800 focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm font-mono uppercase shadow-xs" placeholder="10-character PAN">
                        @error('pan_number') <p class="mt-1.5 text-xs text-rose-500 font-medium">{{ $message }}</p> @enderror
                    </div>

                    <!-- Voter ID Number -->
                    <div>
                        <label for="voter_id_number" class="block text-xs font-semibold uppercase tracking-wider text-slate-600">Voter ID (EPIC) Number</label>
                        <input type="text" id="voter_id_number" name="voter_id_number" value="{{ old('voter_id_number') }}" class="mt-2 block w-full px-4 py-3 bg-white border border-slate-300 rounded-xl text-slate-800 focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm font-mono shadow-xs" placeholder="Voter ID Number">
                        @error('voter_id_number') <p class="mt-1.5 text-xs text-rose-500 font-medium">{{ $message }}</p> @enderror
                    </div>

                    <!-- UAN Number -->
                    <div>
                        <label for="old_uan_number" class="block text-xs font-semibold uppercase tracking-wider text-slate-600">UAN Number (PF) <span class="text-rose-500">*</span></label>
                        <input type="text" id="old_uan_number" name="old_uan_number" value="{{ old('old_uan_number', 'NA') }}" class="mt-2 block w-full px-4 py-3 bg-white border border-slate-300 rounded-xl text-slate-800 focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm font-mono shadow-xs" placeholder="Universal Account Number" required>
                        @error('old_uan_number') <p class="mt-1.5 text-xs text-rose-500 font-medium">{{ $message }}</p> @enderror
                    </div>

                    <!-- ESIC Number -->
                    <div class="sm:col-span-2">
                        <label for="old_esic_number" class="block text-xs font-semibold uppercase tracking-wider text-slate-600">ESIC / IP Number</label>
                        <input type="text" id="old_esic_number" name="old_esic_number" value="{{ old('old_esic_number') }}" class="mt-2 block w-full px-4 py-3 bg-white border border-slate-300 rounded-xl text-slate-800 focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm font-mono shadow-xs" placeholder="ESIC Insurance Number">
                        @error('old_esic_number') <p class="mt-1.5 text-xs text-rose-500 font-medium">{{ $message }}</p> @enderror
                    </div>
                </div>
            </div>

            <!-- Section 4: Employment & Organization Assignment -->
            <div class="border-t border-slate-100 pt-6 space-y-6">
                <h3 class="text-sm font-bold text-blue-600 uppercase tracking-wider flex items-center">
                    <i class="fa-solid fa-briefcase mr-2 text-base"></i> 4. Employment & Organization Assignment
                </h3>

                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-6">
                    <!-- Client Company -->
                    <div>
                        <label for="company_id" class="block text-xs font-semibold uppercase tracking-wider text-slate-600">Client / Company <span class="text-rose-500">*</span></label>
                        <select id="company_id" name="company_id" class="mt-2 block w-full px-3 py-3 bg-white border border-slate-300 rounded-xl text-slate-800 focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm shadow-xs">
                            <option value="">Unassigned</option>
                            @foreach($companies as $company)
                                <option value="{{ $company->id }}" {{ old('company_id') == $company->id ? 'selected' : '' }}>{{ $company->name }}</option>
                            @endforeach
                        </select>
                        @error('company_id') <p class="mt-1.5 text-xs text-rose-500 font-medium">{{ $message }}</p> @enderror
                    </div>

                    <!-- Department -->
                    <div>
                        <label for="department_id" class="block text-xs font-semibold uppercase tracking-wider text-slate-600">Department</label>
                        <select id="department_id" name="department_id" class="mt-2 block w-full px-3 py-3 bg-white border border-slate-300 rounded-xl text-slate-800 focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm shadow-xs">
                            <option value="">Unassigned</option>
                            @foreach($departments as $dept)
                                <option value="{{ $dept->id }}" {{ old('department_id') == $dept->id ? 'selected' : '' }}>{{ $dept->name }}</option>
                            @endforeach
                        </select>
                        @error('department_id') <p class="mt-1.5 text-xs text-rose-500 font-medium">{{ $message }}</p> @enderror
                    </div>

                    <!-- Designation -->
                    <div>
                        <label for="designation_id" class="block text-xs font-semibold uppercase tracking-wider text-slate-600">Official Designation <span class="text-rose-500">*</span></label>
                        <select id="designation_id" name="designation_id" class="mt-2 block w-full px-3 py-3 bg-white border border-slate-300 rounded-xl text-slate-800 focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm shadow-xs">
                            <option value="">Unassigned</option>
                            @foreach($designations as $desig)
                                <option value="{{ $desig->id }}" {{ old('designation_id') == $desig->id ? 'selected' : '' }}>{{ $desig->name }}</option>
                            @endforeach
                        </select>
                        @error('designation_id') <p class="mt-1.5 text-xs text-rose-500 font-medium">{{ $message }}</p> @enderror
                    </div>

                    <!-- Employment Status -->
                    <div>
                        <label for="status" class="block text-xs font-semibold uppercase tracking-wider text-slate-600">Status <span class="text-rose-500">*</span></label>
                        <select id="status" name="status" required class="mt-2 block w-full px-4 py-3 bg-white border border-slate-300 rounded-xl text-slate-800 focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm shadow-xs">
                            <option value="pending_review" {{ old('status') === 'pending_review' ? 'selected' : '' }}>Pending Review</option>
                            <option value="active" {{ old('status', 'active') === 'active' ? 'selected' : '' }}>Active</option>
                            <option value="inactive" {{ old('status') === 'inactive' ? 'selected' : '' }}>Inactive</option>
                            <option value="on_leave" {{ old('status') === 'on_leave' ? 'selected' : '' }}>On Leave</option>
                            <option value="terminated" {{ old('status') === 'terminated' ? 'selected' : '' }}>Terminated</option>
                        </select>
                        @error('status') <p class="mt-1.5 text-xs text-rose-500 font-medium">{{ $message }}</p> @enderror
                    </div>

                    <!-- Joining Date -->
                    <div>
                        <label for="joining_date" class="block text-xs font-semibold uppercase tracking-wider text-slate-600">Joining Date</label>
                        <input type="date" id="joining_date" name="joining_date" value="{{ old('joining_date', date('Y-m-d')) }}" class="mt-2 block w-full px-4 py-3 bg-white border border-slate-300 rounded-xl text-slate-800 focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm shadow-xs">
                        @error('joining_date') <p class="mt-1.5 text-xs text-rose-500 font-medium">{{ $message }}</p> @enderror
                    </div>

                    <!-- Work Location -->
                    <div>
                        <label for="work_location" class="block text-xs font-semibold uppercase tracking-wider text-slate-600">Work Location <span class="text-rose-500">*</span></label>
                        <input type="text" id="work_location" name="work_location" value="{{ old('work_location') }}" class="mt-2 block w-full px-4 py-3 bg-white border border-slate-300 rounded-xl text-slate-800 focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm shadow-xs" placeholder="e.g. Kolkata Hub" required>
                        @error('work_location') <p class="mt-1.5 text-xs text-rose-500 font-medium">{{ $message }}</p> @enderror
                    </div>

                    <!-- Gross Monthly Salary -->
                    <div>
                        <label for="salary" class="block text-xs font-semibold uppercase tracking-wider text-slate-600">Gross Monthly CTC (₹)</label>
                        <input type="number" step="0.01" id="salary" name="salary" value="{{ old('salary') }}" class="mt-2 block w-full px-4 py-3 bg-white border border-slate-300 rounded-xl text-slate-800 focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm font-mono shadow-xs" placeholder="e.g. 18000">
                        @error('salary') <p class="mt-1.5 text-xs text-rose-500 font-medium">{{ $message }}</p> @enderror
                    </div>

                    <!-- NTH Salary -->
                    <div>
                        <label for="nth_salary" class="block text-xs font-semibold uppercase tracking-wider text-slate-600">Net Take Home (NTH) (₹) <span class="text-rose-500">*</span></label>
                        <input type="number" step="0.01" id="nth_salary" name="nth_salary" value="{{ old('nth_salary') }}" class="mt-2 block w-full px-4 py-3 bg-white border border-slate-300 rounded-xl text-slate-800 focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm font-mono shadow-xs" placeholder="e.g. 15000" required>
                        @error('nth_salary') <p class="mt-1.5 text-xs text-rose-500 font-medium">{{ $message }}</p> @enderror
                    </div>
                </div>
            </div>

            <!-- Section 5: Bank Account & Professional Details -->
            <div class="border-t border-slate-100 pt-6 space-y-6">
                <h3 class="text-sm font-bold text-blue-600 uppercase tracking-wider flex items-center">
                    <i class="fa-solid fa-building-columns mr-2 text-base"></i> 5. Bank Account & Education Details
                </h3>

                <div class="grid grid-cols-1 sm:grid-cols-3 gap-6">
                    <!-- Bank Name -->
                    <div>
                        <label for="bank_name" class="block text-xs font-semibold uppercase tracking-wider text-slate-600">Bank Name <span class="text-rose-500">*</span></label>
                        <input type="text" id="bank_name" name="bank_name" value="{{ old('bank_name') }}" class="mt-2 block w-full px-4 py-3 bg-white border border-slate-300 rounded-xl text-slate-800 focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm shadow-xs" placeholder="e.g. State Bank of India" required>
                        @error('bank_name') <p class="mt-1.5 text-xs text-rose-500 font-medium">{{ $message }}</p> @enderror
                    </div>

                    <!-- Bank Account Number -->
                    <div>
                        <label for="bank_account_number" class="block text-xs font-semibold uppercase tracking-wider text-slate-600">Account Number <span class="text-rose-500">*</span></label>
                        <input type="text" id="bank_account_number" name="bank_account_number" value="{{ old('bank_account_number') }}" class="mt-2 block w-full px-4 py-3 bg-white border border-slate-300 rounded-xl text-slate-800 focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm font-mono shadow-xs" placeholder="Bank Account Number" required>
                        @error('bank_account_number') <p class="mt-1.5 text-xs text-rose-500 font-medium">{{ $message }}</p> @enderror
                    </div>

                    <!-- IFSC Code -->
                    <div>
                        <label for="ifsc_code" class="block text-xs font-semibold uppercase tracking-wider text-slate-600">IFSC Code <span class="text-rose-500">*</span></label>
                        <input type="text" id="ifsc_code" name="ifsc_code" value="{{ old('ifsc_code') }}" class="mt-2 block w-full px-4 py-3 bg-white border border-slate-300 rounded-xl text-slate-800 focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm font-mono uppercase shadow-xs" placeholder="e.g. SBIN0001234" required>
                        @error('ifsc_code') <p class="mt-1.5 text-xs text-rose-500 font-medium">{{ $message }}</p> @enderror
                    </div>

                    <!-- Last Qualification -->
                    <div class="sm:col-span-2">
                        <label for="last_qualification" class="block text-xs font-semibold uppercase tracking-wider text-slate-600">Highest Qualification <span class="text-rose-500">*</span></label>
                        <input type="text" id="last_qualification" name="last_qualification" value="{{ old('last_qualification') }}" class="mt-2 block w-full px-4 py-3 bg-white border border-slate-300 rounded-xl text-slate-800 focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm shadow-xs" placeholder="e.g. B.Tech, B.Com, 12th" required>
                        @error('last_qualification') <p class="mt-1.5 text-xs text-rose-500 font-medium">{{ $message }}</p> @enderror
                    </div>

                    <!-- Pass Out Year -->
                    <div>
                        <label for="pass_out_year" class="block text-xs font-semibold uppercase tracking-wider text-slate-600">Passing Year <span class="text-rose-500">*</span></label>
                        <input type="text" id="pass_out_year" name="pass_out_year" value="{{ old('pass_out_year') }}" class="mt-2 block w-full px-4 py-3 bg-white border border-slate-300 rounded-xl text-slate-800 focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm font-mono shadow-xs" placeholder="e.g. 2024" required>
                        @error('pass_out_year') <p class="mt-1.5 text-xs text-rose-500 font-medium">{{ $message }}</p> @enderror
                    </div>
                </div>
            </div>

            <!-- Section 6: Modern Onboarding Document Upload Dropzone -->
            <div class="border-t border-slate-100 pt-6 space-y-6">
                <h3 class="text-sm font-bold text-blue-600 uppercase tracking-wider flex items-center">
                    <i class="fa-solid fa-file-arrow-up mr-2 text-base"></i> 6. Onboarding Document Attachment
                </h3>
                
                <!-- Modern Drag & Drop Zone -->
                <div class="relative">
                    <label for="employee_document" 
                           id="document_dropzone"
                           class="flex flex-col items-center justify-center p-8 border-2 border-dashed border-slate-300 hover:border-blue-500 bg-slate-50/60 hover:bg-blue-50/20 rounded-2xl cursor-pointer transition-all duration-200 text-center group">
                        
                        <div class="w-16 h-16 rounded-2xl bg-blue-100/80 text-blue-600 flex items-center justify-center text-2xl mb-4 group-hover:scale-105 group-hover:bg-blue-600 group-hover:text-white transition-all shadow-xs">
                            <i class="fa-solid fa-cloud-arrow-up"></i>
                        </div>

                        <h4 class="text-sm font-bold text-slate-800 group-hover:text-blue-600 transition-colors">
                            Click to upload <span class="font-normal text-slate-500">or drag and drop document</span>
                        </h4>
                        
                        <p class="text-xs text-slate-500 mt-1">
                            PDF, ZIP, RAR, DOC, DOCX, or Images <span class="font-semibold text-slate-700">(Max 20MB)</span>
                        </p>

                        <div class="mt-4 inline-flex items-center px-4 py-2 bg-white border border-slate-200 rounded-xl text-xs font-semibold text-slate-700 shadow-2xs group-hover:border-blue-300">
                            <i class="fa-solid fa-paperclip mr-2 text-blue-500"></i> Browse File from Device
                        </div>

                        <!-- Selected File Badge (dynamic) -->
                        <div id="doc_file_info" class="hidden mt-4 p-3 bg-emerald-50 border border-emerald-200 text-emerald-800 rounded-xl text-xs font-medium flex items-center space-x-2">
                            <i class="fa-solid fa-circle-check text-emerald-600 text-sm"></i>
                            <span id="doc_file_name_display">Selected File</span>
                            <span id="doc_file_size_display" class="text-emerald-600 text-xxs font-mono"></span>
                        </div>
                    </label>
                    <input type="file" id="employee_document" name="employee_document" accept=".jpg,.jpeg,.png,.pdf,.zip,.rar,.doc,.docx" class="hidden" onchange="handleDocFileChange(this)">
                </div>

                <p class="text-xs text-slate-500 leading-relaxed">
                    <i class="fa-solid fa-circle-info mr-1 text-blue-500"></i> Please bundle all onboarding documents (Aadhaar, PAN, Voter ID, educational marksheets & certificates, passbook) into a single PDF or ZIP archive.
                </p>
                @error('employee_document') <p class="text-xs text-rose-500 font-medium">{{ $message }}</p> @enderror
            </div>

            <!-- Form Action Buttons -->
            <div class="pt-6 border-t border-slate-100 flex items-center justify-end space-x-4">
                <a href="{{ route('admin.employees.index') }}"
                    class="px-6 py-3 bg-slate-100 hover:bg-slate-200 text-slate-600 hover:text-slate-800 rounded-xl text-sm font-semibold transition-all">
                    Cancel
                </a>
                <button type="submit"
                    class="px-8 py-3.5 bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 text-white rounded-xl text-sm font-semibold transition-all shadow-md shadow-blue-500/20 flex items-center">
                    <i class="fa-solid fa-user-plus mr-2"></i> Onboard & Generate Employee ID
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    // Live Profile Image Preview
    function previewProfileImage(input) {
        const file = input.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                const img = document.getElementById('profile_preview_img');
                const placeholder = document.getElementById('profile_placeholder_icon');
                const fileName = document.getElementById('profile_file_name');
                
                img.src = e.target.result;
                img.classList.remove('hidden');
                placeholder.classList.add('hidden');
                fileName.textContent = file.name + ' (' + (file.size / (1024 * 1024)).toFixed(2) + ' MB)';
                fileName.classList.remove('text-slate-500', 'italic');
                fileName.classList.add('text-blue-600', 'font-semibold');
            }
            reader.readAsDataURL(file);
        }
    }

    // Document File Selection Info
    function handleDocFileChange(input) {
        const file = input.files[0];
        const infoBox = document.getElementById('doc_file_info');
        const nameDisplay = document.getElementById('doc_file_name_display');
        const sizeDisplay = document.getElementById('doc_file_size_display');
        
        if (file) {
            infoBox.classList.remove('hidden');
            infoBox.classList.add('inline-flex');
            nameDisplay.textContent = file.name;
            sizeDisplay.textContent = '(' + (file.size / (1024 * 1024)).toFixed(2) + ' MB)';
        }
    }
</script>
@endsection