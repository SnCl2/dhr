@extends('layouts.admin')

@section('title', 'Onboard Candidate/Staff - Propszy')
@section('page_title', 'Onboard Candidate / Staff Member')

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="mb-6">
        <a href="{{ route('admin.employees.index') }}" class="text-xs font-semibold text-slate-400 hover:text-white flex items-center">
            <i class="fa-solid fa-arrow-left mr-1.5"></i> Back to Database List
        </a>
    </div>

    <div class="glass-dark p-8 sm:p-10 rounded-3xl border border-slate-850 shadow-2xl">
        <form action="{{ route('admin.employees.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
            @csrf

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                <!-- First Name -->
                <div>
                    <label for="first_name" class="block text-xs font-semibold uppercase tracking-wider text-slate-400">First Name</label>
                    <input type="text" id="first_name" name="first_name" required value="{{ old('first_name') }}"
                        class="mt-2 block w-full px-4 py-3 bg-slate-950/60 border border-slate-800 rounded-xl text-slate-200 focus:outline-none focus:ring-1 focus:ring-purple-500 text-sm"
                        placeholder="John">
                    @error('first_name')
                        <p class="mt-1.5 text-xs text-rose-400">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Last Name -->
                <div>
                    <label for="last_name" class="block text-xs font-semibold uppercase tracking-wider text-slate-400">Last Name</label>
                    <input type="text" id="last_name" name="last_name" required value="{{ old('last_name') }}"
                        class="mt-2 block w-full px-4 py-3 bg-slate-950/60 border border-slate-800 rounded-xl text-slate-200 focus:outline-none focus:ring-1 focus:ring-purple-500 text-sm"
                        placeholder="Doe">
                    @error('last_name')
                        <p class="mt-1.5 text-xs text-rose-400">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                <!-- Email -->
                <div>
                    <label for="email" class="block text-xs font-semibold uppercase tracking-wider text-slate-400">Email Address</label>
                    <input type="email" id="email" name="email" required value="{{ old('email') }}"
                        class="mt-2 block w-full px-4 py-3 bg-slate-950/60 border border-slate-800 rounded-xl text-slate-200 focus:outline-none focus:ring-1 focus:ring-purple-500 text-sm"
                        placeholder="email@example.com">
                    @error('email')
                        <p class="mt-1.5 text-xs text-rose-400">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Phone -->
                <div>
                    <label for="phone" class="block text-xs font-semibold uppercase tracking-wider text-slate-400">Phone Number</label>
                    <input type="tel" id="phone" name="phone" value="{{ old('phone') }}"
                        class="mt-2 block w-full px-4 py-3 bg-slate-950/60 border border-slate-800 rounded-xl text-slate-200 focus:outline-none focus:ring-1 focus:ring-purple-500 text-sm"
                        placeholder="+91 98765 43210">
                    @error('phone')
                        <p class="mt-1.5 text-xs text-rose-400">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                <!-- Status -->
                <div>
                    <label for="status" class="block text-xs font-semibold uppercase tracking-wider text-slate-400">Initial Status</label>
                    <select id="status" name="status" required
                        class="mt-2 block w-full px-3 py-3 bg-slate-950/60 border border-slate-800 rounded-xl text-slate-200 focus:outline-none focus:ring-1 focus:ring-purple-500 text-sm">
                        <option value="pending_review" {{ old('status') === 'pending_review' ? 'selected' : '' }}>Pending Review (Candidate)</option>
                        <option value="active" {{ old('status') === 'active' ? 'selected' : '' }}>Active Staff Member</option>
                        <option value="inactive" {{ old('status') === 'inactive' ? 'selected' : '' }}>Inactive Staff</option>
                    </select>
                    @error('status')
                        <p class="mt-1.5 text-xs text-rose-400">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Company -->
                <div>
                    <label for="company_id" class="block text-xs font-semibold uppercase tracking-wider text-slate-400">Assigned Company</label>
                    <select id="company_id" name="company_id"
                        class="mt-2 block w-full px-3 py-3 bg-slate-950/60 border border-slate-800 rounded-xl text-slate-200 focus:outline-none focus:ring-1 focus:ring-purple-500 text-sm">
                        <option value="">Unassigned</option>
                        @foreach($companies as $company)
                            <option value="{{ $company->id }}" {{ old('company_id') == $company->id ? 'selected' : '' }}>{{ $company->name }}</option>
                        @endforeach
                    </select>
                    @error('company_id')
                        <p class="mt-1.5 text-xs text-rose-400">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Department -->
                <div>
                    <label for="department_id" class="block text-xs font-semibold uppercase tracking-wider text-slate-400">Department</label>
                    <select id="department_id" name="department_id"
                        class="mt-2 block w-full px-3 py-3 bg-slate-950/60 border border-slate-800 rounded-xl text-slate-200 focus:outline-none focus:ring-1 focus:ring-purple-500 text-sm">
                        <option value="">Unassigned</option>
                        @foreach($departments as $dept)
                            <option value="{{ $dept->id }}" {{ old('department_id') == $dept->id ? 'selected' : '' }}>{{ $dept->name }}</option>
                        @endforeach
                    </select>
                    @error('department_id')
                        <p class="mt-1.5 text-xs text-rose-400">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Designation -->
                <div>
                    <label for="designation_id" class="block text-xs font-semibold uppercase tracking-wider text-slate-400">Designation</label>
                    <select id="designation_id" name="designation_id"
                        class="mt-2 block w-full px-3 py-3 bg-slate-950/60 border border-slate-800 rounded-xl text-slate-200 focus:outline-none focus:ring-1 focus:ring-purple-500 text-sm">
                        <option value="">Unassigned</option>
                        @foreach($designations as $desig)
                            <option value="{{ $desig->id }}" {{ old('designation_id') == $desig->id ? 'selected' : '' }}>{{ $desig->name }}</option>
                        @endforeach
                    </select>
                    @error('designation_id')
                        <p class="mt-1.5 text-xs text-rose-400">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                <!-- Salary -->
                <div>
                    <label for="salary" class="block text-xs font-semibold uppercase tracking-wider text-slate-400">Salary (Monthly Payout)</label>
                    <input type="number" step="0.01" id="salary" name="salary" value="{{ old('salary') }}"
                        class="mt-2 block w-full px-4 py-3 bg-slate-950/60 border border-slate-800 rounded-xl text-slate-200 focus:outline-none focus:ring-1 focus:ring-purple-500 text-sm"
                        placeholder="18000.00">
                    @error('salary')
                        <p class="mt-1.5 text-xs text-rose-400">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Joining Date -->
                <div>
                    <label for="joining_date" class="block text-xs font-semibold uppercase tracking-wider text-slate-400">Joining Date</label>
                    <input type="date" id="joining_date" name="joining_date" value="{{ old('joining_date', date('Y-m-d')) }}"
                        class="mt-2 block w-full px-4 py-3 bg-slate-950/60 border border-slate-800 rounded-xl text-slate-200 focus:outline-none focus:ring-1 focus:ring-purple-500 text-sm">
                    @error('joining_date')
                        <p class="mt-1.5 text-xs text-rose-400">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Section: Personal Details -->
            <div class="border-t border-slate-850 pt-6 space-y-6">
                <h3 class="text-sm font-bold text-purple-400 uppercase tracking-wider"><i class="fa-solid fa-user mr-2"></i>Personal Details</h3>
                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-6">
                    <!-- Prefix -->
                    <div>
                        <label for="prefix" class="block text-xs font-semibold uppercase tracking-wider text-slate-400">Prefix <span class="text-rose-500">*</span></label>
                        <select id="prefix" name="prefix" class="mt-2 block w-full px-3 py-3 bg-slate-950/60 border border-slate-800 rounded-xl text-slate-200 focus:outline-none focus:ring-1 focus:ring-purple-500 text-sm" required>
                            <option value="">Select</option>
                            <option value="Mr." {{ old('prefix') === 'Mr.' ? 'selected' : '' }}>Mr.</option>
                            <option value="Mrs." {{ old('prefix') === 'Mrs.' ? 'selected' : '' }}>Mrs.</option>
                            <option value="Ms." {{ old('prefix') === 'Ms.' ? 'selected' : '' }}>Ms.</option>
                            <option value="Dr." {{ old('prefix') === 'Dr.' ? 'selected' : '' }}>Dr.</option>
                        </select>
                        @error('prefix') <p class="mt-1.5 text-xs text-rose-400">{{ $message }}</p> @enderror
                    </div>

                    <!-- Aadhaar Full Name -->
                    <div>
                        <label for="aadhaar_full_name" class="block text-xs font-semibold uppercase tracking-wider text-slate-400">Full Name as per Aadhaar <span class="text-rose-500">*</span></label>
                        <input type="text" id="aadhaar_full_name" name="aadhaar_full_name" value="{{ old('aadhaar_full_name') }}" class="mt-2 block w-full px-4 py-3 bg-slate-950/60 border border-slate-800 rounded-xl text-slate-200 focus:outline-none focus:ring-1 focus:ring-purple-500 text-sm" placeholder="Full Name" required>
                        @error('aadhaar_full_name') <p class="mt-1.5 text-xs text-rose-400">{{ $message }}</p> @enderror
                    </div>

                    <!-- Father's Name -->
                    <div>
                        <label for="father_name_aadhaar" class="block text-xs font-semibold uppercase tracking-wider text-slate-400">Father's Name (as Aadhaar) <span class="text-rose-500">*</span></label>
                        <input type="text" id="father_name_aadhaar" name="father_name_aadhaar" value="{{ old('father_name_aadhaar') }}" class="mt-2 block w-full px-4 py-3 bg-slate-950/60 border border-slate-800 rounded-xl text-slate-200 focus:outline-none focus:ring-1 focus:ring-purple-500 text-sm" placeholder="Father's Name" required>
                        @error('father_name_aadhaar') <p class="mt-1.5 text-xs text-rose-400">{{ $message }}</p> @enderror
                    </div>

                    <!-- Mother's Name -->
                    <div>
                        <label for="mother_name_aadhaar" class="block text-xs font-semibold uppercase tracking-wider text-slate-400">Mother's Name (as Aadhaar) <span class="text-rose-500">*</span></label>
                        <input type="text" id="mother_name_aadhaar" name="mother_name_aadhaar" value="{{ old('mother_name_aadhaar') }}" class="mt-2 block w-full px-4 py-3 bg-slate-950/60 border border-slate-800 rounded-xl text-slate-200 focus:outline-none focus:ring-1 focus:ring-purple-500 text-sm" placeholder="Mother's Name" required>
                        @error('mother_name_aadhaar') <p class="mt-1.5 text-xs text-rose-400">{{ $message }}</p> @enderror
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-6">
                    <!-- Gender -->
                    <div>
                        <label for="gender" class="block text-xs font-semibold uppercase tracking-wider text-slate-400">Gender <span class="text-rose-500">*</span></label>
                        <select id="gender" name="gender" class="mt-2 block w-full px-3 py-3 bg-slate-950/60 border border-slate-800 rounded-xl text-slate-200 focus:outline-none focus:ring-1 focus:ring-purple-500 text-sm" required>
                            <option value="">Select</option>
                            <option value="Male" {{ old('gender') === 'Male' ? 'selected' : '' }}>Male</option>
                            <option value="Female" {{ old('gender') === 'Female' ? 'selected' : '' }}>Female</option>
                            <option value="Other" {{ old('gender') === 'Other' ? 'selected' : '' }}>Other</option>
                        </select>
                        @error('gender') <p class="mt-1.5 text-xs text-rose-400">{{ $message }}</p> @enderror
                    </div>

                    <!-- DOB -->
                    <div>
                        <label for="dob" class="block text-xs font-semibold uppercase tracking-wider text-slate-400">Date of Birth <span class="text-rose-500">*</span></label>
                        <input type="date" id="dob" name="dob" value="{{ old('dob') }}" class="mt-2 block w-full px-4 py-3 bg-slate-950/60 border border-slate-800 rounded-xl text-slate-200 focus:outline-none focus:ring-1 focus:ring-purple-500 text-sm" required>
                        @error('dob') <p class="mt-1.5 text-xs text-rose-400">{{ $message }}</p> @enderror
                    </div>

                    <!-- Mother Tongue -->
                    <div>
                        <label for="mother_tongue" class="block text-xs font-semibold uppercase tracking-wider text-slate-400">Mother Tongue <span class="text-rose-500">*</span></label>
                        <input type="text" id="mother_tongue" name="mother_tongue" value="{{ old('mother_tongue') }}" class="mt-2 block w-full px-4 py-3 bg-slate-950/60 border border-slate-800 rounded-xl text-slate-200 focus:outline-none focus:ring-1 focus:ring-purple-500 text-sm" placeholder="Bengali, Hindi, etc." required>
                        @error('mother_tongue') <p class="mt-1.5 text-xs text-rose-400">{{ $message }}</p> @enderror
                    </div>

                    <!-- Marital Status -->
                    <div>
                        <label for="marital_status" class="block text-xs font-semibold uppercase tracking-wider text-slate-400">Marital Status <span class="text-rose-500">*</span></label>
                        <select id="marital_status" name="marital_status" class="mt-2 block w-full px-3 py-3 bg-slate-950/60 border border-slate-800 rounded-xl text-slate-200 focus:outline-none focus:ring-1 focus:ring-purple-500 text-sm" required>
                            <option value="">Select</option>
                            <option value="Single" {{ old('marital_status') === 'Single' ? 'selected' : '' }}>Single</option>
                            <option value="Married" {{ old('marital_status') === 'Married' ? 'selected' : '' }}>Married</option>
                            <option value="Divorced" {{ old('marital_status') === 'Divorced' ? 'selected' : '' }}>Divorced</option>
                            <option value="Widowed" {{ old('marital_status') === 'Widowed' ? 'selected' : '' }}>Widowed</option>
                        </select>
                        @error('marital_status') <p class="mt-1.5 text-xs text-rose-400">{{ $message }}</p> @enderror
                    </div>
                </div>
            </div>

            <!-- Section: Identity & KYC Documents -->
            <div class="border-t border-slate-850 pt-6 space-y-6">
                <h3 class="text-sm font-bold text-purple-400 uppercase tracking-wider"><i class="fa-solid fa-address-card mr-2"></i>Identity & KYC Documents</h3>
                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-6">
                    <!-- Aadhaar Number -->
                    <div>
                        <label for="aadhaar_number" class="block text-xs font-semibold uppercase tracking-wider text-slate-400">Aadhaar Number <span class="text-rose-500">*</span></label>
                        <input type="text" id="aadhaar_number" name="aadhaar_number" value="{{ old('aadhaar_number') }}" class="mt-2 block w-full px-4 py-3 bg-slate-950/60 border border-slate-800 rounded-xl text-slate-200 focus:outline-none focus:ring-1 focus:ring-purple-500 text-sm" placeholder="12-digit Aadhaar" required>
                        @error('aadhaar_number') <p class="mt-1.5 text-xs text-rose-400">{{ $message }}</p> @enderror
                    </div>

                    <!-- PAN Number -->
                    <div>
                        <label for="pan_number" class="block text-xs font-semibold uppercase tracking-wider text-slate-400">PAN Number <span class="text-emerald-500 font-bold ml-1">•</span></label>
                        <input type="text" id="pan_number" name="pan_number" value="{{ old('pan_number') }}" class="mt-2 block w-full px-4 py-3 bg-slate-950/60 border border-slate-800 rounded-xl text-slate-200 focus:outline-none focus:ring-1 focus:ring-purple-500 text-sm" placeholder="10-digit PAN">
                        @error('pan_number') <p class="mt-1.5 text-xs text-rose-400">{{ $message }}</p> @enderror
                    </div>

                    <!-- Voter ID Number -->
                    <div>
                        <label for="voter_id_number" class="block text-xs font-semibold uppercase tracking-wider text-slate-400">Voter ID Number <span class="text-emerald-500 font-bold ml-1">•</span></label>
                        <input type="text" id="voter_id_number" name="voter_id_number" value="{{ old('voter_id_number') }}" class="mt-2 block w-full px-4 py-3 bg-slate-950/60 border border-slate-800 rounded-xl text-slate-200 focus:outline-none focus:ring-1 focus:ring-purple-500 text-sm" placeholder="Voter ID Card No.">
                        @error('voter_id_number') <p class="mt-1.5 text-xs text-rose-400">{{ $message }}</p> @enderror
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                    <!-- Old UAN Number -->
                    <div>
                        <label for="old_uan_number" class="block text-xs font-semibold uppercase tracking-wider text-slate-400">Old UAN Number (PF) <span class="text-rose-500">*</span></label>
                        <input type="text" id="old_uan_number" name="old_uan_number" value="{{ old('old_uan_number') }}" class="mt-2 block w-full px-4 py-3 bg-slate-950/60 border border-slate-800 rounded-xl text-slate-200 focus:outline-none focus:ring-1 focus:ring-purple-500 text-sm" placeholder="UAN Number" required>
                        @error('old_uan_number') <p class="mt-1.5 text-xs text-rose-400">{{ $message }}</p> @enderror
                    </div>

                    <!-- Old ESIC Number -->
                    <div>
                        <label for="old_esic_number" class="block text-xs font-semibold uppercase tracking-wider text-slate-400">Old ESIC Number</label>
                        <input type="text" id="old_esic_number" name="old_esic_number" value="{{ old('old_esic_number') }}" class="mt-2 block w-full px-4 py-3 bg-slate-950/60 border border-slate-800 rounded-xl text-slate-200 focus:outline-none focus:ring-1 focus:ring-purple-500 text-sm" placeholder="ESIC Number">
                        @error('old_esic_number') <p class="mt-1.5 text-xs text-rose-400">{{ $message }}</p> @enderror
                    </div>
                </div>
            </div>

            <!-- Section: Contact & Address Details -->
            <div class="border-t border-slate-850 pt-6 space-y-6">
                <h3 class="text-sm font-bold text-purple-400 uppercase tracking-wider"><i class="fa-solid fa-map-location-dot mr-2"></i>Contact & Address Details</h3>
                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-6">
                    <!-- Contact Number -->
                    <div>
                        <label for="contact_number" class="block text-xs font-semibold uppercase tracking-wider text-slate-400">Secondary Contact Number <span class="text-rose-500">*</span></label>
                        <input type="text" id="contact_number" name="contact_number" value="{{ old('contact_number') }}" class="mt-2 block w-full px-4 py-3 bg-slate-950/60 border border-slate-800 rounded-xl text-slate-200 focus:outline-none focus:ring-1 focus:ring-purple-500 text-sm" placeholder="Mobile Number" required>
                        @error('contact_number') <p class="mt-1.5 text-xs text-rose-400">{{ $message }}</p> @enderror
                    </div>

                    <!-- Email ID -->
                    <div>
                        <label for="email_id" class="block text-xs font-semibold uppercase tracking-wider text-slate-400">Secondary Email Address</label>
                        <input type="email" id="email_id" name="email_id" value="{{ old('email_id') }}" class="mt-2 block w-full px-4 py-3 bg-slate-950/60 border border-slate-800 rounded-xl text-slate-200 focus:outline-none focus:ring-1 focus:ring-purple-500 text-sm" placeholder="secondary@example.com">
                        @error('email_id') <p class="mt-1.5 text-xs text-rose-400">{{ $message }}</p> @enderror
                    </div>

                    <!-- Emergency Contact -->
                    <div>
                        <label for="emergency_contact_number" class="block text-xs font-semibold uppercase tracking-wider text-slate-400">Emergency Contact Number <span class="text-rose-500">*</span></label>
                        <input type="text" id="emergency_contact_number" name="emergency_contact_number" value="{{ old('emergency_contact_number') }}" class="mt-2 block w-full px-4 py-3 bg-slate-950/60 border border-slate-800 rounded-xl text-slate-200 focus:outline-none focus:ring-1 focus:ring-purple-500 text-sm" placeholder="Emergency Phone Number" required>
                        @error('emergency_contact_number') <p class="mt-1.5 text-xs text-rose-400">{{ $message }}</p> @enderror
                    </div>
                </div>

                <!-- Full Address -->
                <div>
                    <label for="aadhaar_address" class="block text-xs font-semibold uppercase tracking-wider text-slate-400">Full Address (as per Aadhaar) <span class="text-rose-500">*</span></label>
                    <textarea id="aadhaar_address" name="aadhaar_address" rows="3" class="mt-2 block w-full px-4 py-3 bg-slate-950/60 border border-slate-800 rounded-xl text-slate-200 focus:outline-none focus:ring-1 focus:ring-purple-500 text-sm" placeholder="House No, Street, Locality..." required>{{ old('aadhaar_address') }}</textarea>
                    @error('aadhaar_address') <p class="mt-1.5 text-xs text-rose-400">{{ $message }}</p> @enderror
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-6">
                    <!-- Landmark -->
                    <div>
                        <label for="landmark" class="block text-xs font-semibold uppercase tracking-wider text-slate-400">Landmark <span class="text-rose-500">*</span></label>
                        <input type="text" id="landmark" name="landmark" value="{{ old('landmark') }}" class="mt-2 block w-full px-4 py-3 bg-slate-950/60 border border-slate-800 rounded-xl text-slate-200 focus:outline-none focus:ring-1 focus:ring-purple-500 text-sm" placeholder="Near Temple, etc." required>
                        @error('landmark') <p class="mt-1.5 text-xs text-rose-400">{{ $message }}</p> @enderror
                    </div>

                    <!-- City -->
                    <div>
                        <label for="city" class="block text-xs font-semibold uppercase tracking-wider text-slate-400">City <span class="text-rose-500">*</span></label>
                        <input type="text" id="city" name="city" value="{{ old('city') }}" class="mt-2 block w-full px-4 py-3 bg-slate-950/60 border border-slate-800 rounded-xl text-slate-200 focus:outline-none focus:ring-1 focus:ring-purple-500 text-sm" placeholder="Kolkata" required>
                        @error('city') <p class="mt-1.5 text-xs text-rose-400">{{ $message }}</p> @enderror
                    </div>

                    <!-- Pin Code -->
                    <div>
                        <label for="pin_code" class="block text-xs font-semibold uppercase tracking-wider text-slate-400">Pin Code <span class="text-rose-500">*</span></label>
                        <input type="text" id="pin_code" name="pin_code" value="{{ old('pin_code') }}" class="mt-2 block w-full px-4 py-3 bg-slate-950/60 border border-slate-800 rounded-xl text-slate-200 focus:outline-none focus:ring-1 focus:ring-purple-500 text-sm" placeholder="700001" required>
                        @error('pin_code') <p class="mt-1.5 text-xs text-rose-400">{{ $message }}</p> @enderror
                    </div>

                    <!-- State -->
                    <div>
                        <label for="state" class="block text-xs font-semibold uppercase tracking-wider text-slate-400">State <span class="text-rose-500">*</span></label>
                        <input type="text" id="state" name="state" value="{{ old('state') }}" class="mt-2 block w-full px-4 py-3 bg-slate-950/60 border border-slate-800 rounded-xl text-slate-200 focus:outline-none focus:ring-1 focus:ring-purple-500 text-sm" placeholder="West Bengal" required>
                        @error('state') <p class="mt-1.5 text-xs text-rose-400">{{ $message }}</p> @enderror
                    </div>
                </div>
            </div>

            <!-- Section: Bank Account Details -->
            <div class="border-t border-slate-850 pt-6 space-y-6">
                <h3 class="text-sm font-bold text-purple-400 uppercase tracking-wider"><i class="fa-solid fa-building-columns mr-2"></i>Bank Account Details</h3>
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-6">
                    <!-- Bank Account Number -->
                    <div>
                        <label for="bank_account_number" class="block text-xs font-semibold uppercase tracking-wider text-slate-400">Bank Account Number <span class="text-rose-500">*</span></label>
                        <input type="text" id="bank_account_number" name="bank_account_number" value="{{ old('bank_account_number') }}" class="mt-2 block w-full px-4 py-3 bg-slate-950/60 border border-slate-800 rounded-xl text-slate-200 focus:outline-none focus:ring-1 focus:ring-purple-500 text-sm" placeholder="Account Number" required>
                        @error('bank_account_number') <p class="mt-1.5 text-xs text-rose-400">{{ $message }}</p> @enderror
                    </div>

                    <!-- IFSC Code -->
                    <div>
                        <label for="ifsc_code" class="block text-xs font-semibold uppercase tracking-wider text-slate-400">IFSC Code <span class="text-rose-500">*</span></label>
                        <input type="text" id="ifsc_code" name="ifsc_code" value="{{ old('ifsc_code') }}" class="mt-2 block w-full px-4 py-3 bg-slate-950/60 border border-slate-800 rounded-xl text-slate-200 focus:outline-none focus:ring-1 focus:ring-purple-500 text-sm" placeholder="IFSC Code" required>
                        @error('ifsc_code') <p class="mt-1.5 text-xs text-rose-400">{{ $message }}</p> @enderror
                    </div>

                    <!-- Bank Name -->
                    <div>
                        <label for="bank_name" class="block text-xs font-semibold uppercase tracking-wider text-slate-400">Bank Name <span class="text-rose-500">*</span></label>
                        <input type="text" id="bank_name" name="bank_name" value="{{ old('bank_name') }}" class="mt-2 block w-full px-4 py-3 bg-slate-950/60 border border-slate-800 rounded-xl text-slate-200 focus:outline-none focus:ring-1 focus:ring-purple-500 text-sm" placeholder="e.g. State Bank of India" required>
                        @error('bank_name') <p class="mt-1.5 text-xs text-rose-400">{{ $message }}</p> @enderror
                    </div>
                </div>
            </div>

            <!-- Section: Professional Qualifications & Placement Details -->
            <div class="border-t border-slate-850 pt-6 space-y-6">
                <h3 class="text-sm font-bold text-purple-400 uppercase tracking-wider"><i class="fa-solid fa-graduation-cap mr-2"></i>Professional & Placement Details</h3>
                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-6">
                    <!-- Last Qualification -->
                    <div>
                        <label for="last_qualification" class="block text-xs font-semibold uppercase tracking-wider text-slate-400">Last Qualification <span class="text-rose-500">*</span></label>
                        <input type="text" id="last_qualification" name="last_qualification" value="{{ old('last_qualification') }}" class="mt-2 block w-full px-4 py-3 bg-slate-950/60 border border-slate-800 rounded-xl text-slate-200 focus:outline-none focus:ring-1 focus:ring-purple-500 text-sm" placeholder="B.Tech, B.Sc, 12th, etc." required>
                        @error('last_qualification') <p class="mt-1.5 text-xs text-rose-400">{{ $message }}</p> @enderror
                    </div>

                    <!-- Pass Out Year -->
                    <div>
                        <label for="pass_out_year" class="block text-xs font-semibold uppercase tracking-wider text-slate-400">Pass Out Year <span class="text-rose-500">*</span></label>
                        <input type="text" id="pass_out_year" name="pass_out_year" value="{{ old('pass_out_year') }}" class="mt-2 block w-full px-4 py-3 bg-slate-950/60 border border-slate-800 rounded-xl text-slate-200 focus:outline-none focus:ring-1 focus:ring-purple-500 text-sm" placeholder="e.g. 2024" required>
                        @error('pass_out_year') <p class="mt-1.5 text-xs text-rose-400">{{ $message }}</p> @enderror
                    </div>

                    <!-- Client Name -->
                    <div>
                        <label for="client_name" class="block text-xs font-semibold uppercase tracking-wider text-slate-400">Client Name <span class="text-rose-500">*</span></label>
                        <input type="text" id="client_name" name="client_name" value="{{ old('client_name') }}" class="mt-2 block w-full px-4 py-3 bg-slate-950/60 border border-slate-800 rounded-xl text-slate-200 focus:outline-none focus:ring-1 focus:ring-purple-500 text-sm" placeholder="Assigned Client" required>
                        @error('client_name') <p class="mt-1.5 text-xs text-rose-400">{{ $message }}</p> @enderror
                    </div>

                    <!-- Work Location -->
                    <div>
                        <label for="work_location" class="block text-xs font-semibold uppercase tracking-wider text-slate-400">Work Location <span class="text-rose-500">*</span></label>
                        <input type="text" id="work_location" name="work_location" value="{{ old('work_location') }}" class="mt-2 block w-full px-4 py-3 bg-slate-950/60 border border-slate-800 rounded-xl text-slate-200 focus:outline-none focus:ring-1 focus:ring-purple-500 text-sm" placeholder="Office Location" required>
                        @error('work_location') <p class="mt-1.5 text-xs text-rose-400">{{ $message }}</p> @enderror
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                    <!-- Text Designation -->
                    <div>
                        <label for="designation" class="block text-xs font-semibold uppercase tracking-wider text-slate-400">Job Title / Designation (Text) <span class="text-rose-500">*</span></label>
                        <input type="text" id="designation" name="designation" value="{{ old('designation') }}" class="mt-2 block w-full px-4 py-3 bg-slate-950/60 border border-slate-800 rounded-xl text-slate-200 focus:outline-none focus:ring-1 focus:ring-purple-500 text-sm" placeholder="Official Designation" required>
                        @error('designation') <p class="mt-1.5 text-xs text-rose-400">{{ $message }}</p> @enderror
                    </div>

                    <!-- NTH Salary -->
                    <div>
                        <label for="nth_salary" class="block text-xs font-semibold uppercase tracking-wider text-slate-400">Net Take Home (NTH) Salary <span class="text-rose-500">*</span></label>
                        <input type="number" step="0.01" id="nth_salary" name="nth_salary" value="{{ old('nth_salary') }}" class="mt-2 block w-full px-4 py-3 bg-slate-950/60 border border-slate-800 rounded-xl text-slate-200 focus:outline-none focus:ring-1 focus:ring-purple-500 text-sm" placeholder="NTH Salary" required>
                        @error('nth_salary') <p class="mt-1.5 text-xs text-rose-400">{{ $message }}</p> @enderror
                    </div>
                </div>
            </div>

            <!-- Section: Onboarding Documents Upload -->
            <div class="border-t border-slate-850 pt-6 space-y-6">
                <h3 class="text-sm font-bold text-purple-400 uppercase tracking-wider"><i class="fa-solid fa-file-arrow-up mr-2"></i>Onboarding Documents Upload</h3>
                
                <div class="max-w-xl">
                    <label for="employee_document" class="block text-xs font-semibold uppercase tracking-wider text-slate-400">Employee Onboarding Document <span class="text-slate-500">(PDF, ZIP, RAR, or Images, max 20MB)</span></label>
                    <input type="file" id="employee_document" name="employee_document" accept=".jpg,.jpeg,.png,.pdf,.zip,.rar,.doc,.docx"
                        class="mt-2 block w-full text-xs text-slate-400 file:mr-3 file:py-2.5 file:px-4 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-slate-800 file:text-purple-300 hover:file:bg-slate-700 cursor-pointer">
                    <p class="mt-2 text-xxs text-slate-500">Please upload all document attachments (Aadhaar, PAN, Voter ID, Qualification Marksheets, etc.) consolidated into a single PDF, ZIP, or doc/docx file.</p>
                    @error('employee_document') <p class="mt-1.5 text-xs text-rose-400">{{ $message }}</p> @enderror
                </div>
            </div>

            <div class="pt-6 border-t border-slate-850 flex justify-end space-x-3">
                <a href="{{ route('admin.employees.index') }}"
                    class="px-6 py-3 bg-slate-850 hover:bg-slate-800 text-slate-400 hover:text-white rounded-xl text-sm font-semibold transition-all">
                    Cancel
                </a>
                <button type="submit"
                    class="px-6 py-3 bg-gradient-to-r from-purple-600 to-indigo-600 hover:from-purple-500 hover:to-indigo-500 text-white rounded-xl text-sm font-semibold transition-all shadow-lg shadow-purple-500/10">
                    <i class="fa-solid fa-user-plus mr-2"></i> Onboard & Generate ID
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
