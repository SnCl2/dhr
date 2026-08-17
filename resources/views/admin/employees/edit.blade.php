@extends('layouts.admin')

@section('title', 'Edit Staff Member - Propszy')
@section('page_title', 'Modify Staff Member Profile')

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="mb-6">
        <a href="{{ route('admin.employees.index') }}" class="text-xs font-semibold text-slate-400 hover:text-white flex items-center">
            <i class="fa-solid fa-arrow-left mr-1.5"></i> Back to Database List
        </a>
    </div>

    <div class="glass-dark p-8 sm:p-10 rounded-3xl border border-slate-850 shadow-2xl">
        <form action="{{ route('admin.employees.update', $employee) }}" method="POST" class="space-y-6">
            @csrf
            @method('PUT')

            <div class="p-4 rounded-xl bg-slate-900 border border-slate-800 flex items-center justify-between mb-6">
                <div>
                    <span class="block text-xs text-slate-500 uppercase tracking-wider">System Assigned ID</span>
                    <span class="block font-outfit font-extrabold text-xl text-purple-400">{{ $employee->employee_id }}</span>
                </div>
                <div class="text-right">
                    <span class="block text-xs text-slate-500 uppercase tracking-wider">Password Status</span>
                    @if($employee->is_password_changed)
                        <span class="text-xs font-bold text-emerald-400"><i class="fa-solid fa-shield-halved mr-1"></i> Customized by User</span>
                    @else
                        <span class="text-xs font-bold text-amber-400"><i class="fa-solid fa-triangle-exclamation mr-1"></i> Temporary Default Password</span>
                    @endif
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                <!-- First Name -->
                <div>
                    <label for="first_name" class="block text-xs font-semibold uppercase tracking-wider text-slate-400">First Name</label>
                    <input type="text" id="first_name" name="first_name" required value="{{ old('first_name', $employee->first_name) }}"
                        class="mt-2 block w-full px-4 py-3 bg-slate-950/60 border border-slate-800 rounded-xl text-slate-200 focus:outline-none focus:ring-1 focus:ring-purple-500 text-sm">
                    @error('first_name')
                        <p class="mt-1.5 text-xs text-rose-400">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Last Name -->
                <div>
                    <label for="last_name" class="block text-xs font-semibold uppercase tracking-wider text-slate-400">Last Name</label>
                    <input type="text" id="last_name" name="last_name" required value="{{ old('last_name', $employee->last_name) }}"
                        class="mt-2 block w-full px-4 py-3 bg-slate-950/60 border border-slate-800 rounded-xl text-slate-200 focus:outline-none focus:ring-1 focus:ring-purple-500 text-sm">
                    @error('last_name')
                        <p class="mt-1.5 text-xs text-rose-400">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                <!-- Email -->
                <div>
                    <label for="email" class="block text-xs font-semibold uppercase tracking-wider text-slate-400">Email Address</label>
                    <input type="email" id="email" name="email" required value="{{ old('email', $employee->email) }}"
                        class="mt-2 block w-full px-4 py-3 bg-slate-950/60 border border-slate-800 rounded-xl text-slate-200 focus:outline-none focus:ring-1 focus:ring-purple-500 text-sm">
                    @error('email')
                        <p class="mt-1.5 text-xs text-rose-400">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Phone -->
                <div>
                    <label for="phone" class="block text-xs font-semibold uppercase tracking-wider text-slate-400">Phone Number</label>
                    <input type="tel" id="phone" name="phone" value="{{ old('phone', $employee->phone) }}"
                        class="mt-2 block w-full px-4 py-3 bg-slate-950/60 border border-slate-800 rounded-xl text-slate-200 focus:outline-none focus:ring-1 focus:ring-purple-500 text-sm">
                    @error('phone')
                        <p class="mt-1.5 text-xs text-rose-400">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                <!-- Status -->
                <div>
                    <label for="status" class="block text-xs font-semibold uppercase tracking-wider text-slate-400">Employment Status</label>
                    <select id="status" name="status" required
                        class="mt-2 block w-full px-3 py-3 bg-slate-950/60 border border-slate-800 rounded-xl text-slate-200 focus:outline-none focus:ring-1 focus:ring-purple-500 text-sm">
                        <option value="pending_review" {{ old('status', $employee->status) === 'pending_review' ? 'selected' : '' }}>Pending Review</option>
                        <option value="active" {{ old('status', $employee->status) === 'active' ? 'selected' : '' }}>Active</option>
                        <option value="inactive" {{ old('status', $employee->status) === 'inactive' ? 'selected' : '' }}>Inactive</option>
                        <option value="on_leave" {{ old('status', $employee->status) === 'on_leave' ? 'selected' : '' }}>On Leave</option>
                        <option value="terminated" {{ old('status', $employee->status) === 'terminated' ? 'selected' : '' }}>Terminated</option>
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
                            <option value="{{ $company->id }}" {{ old('company_id', $employee->company_id) == $company->id ? 'selected' : '' }}>{{ $company->name }}</option>
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
                            <option value="{{ $dept->id }}" {{ old('department_id', $employee->department_id) == $dept->id ? 'selected' : '' }}>{{ $dept->name }}</option>
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
                            <option value="{{ $desig->id }}" {{ old('designation_id', $employee->designation_id) == $desig->id ? 'selected' : '' }}>{{ $desig->name }}</option>
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
                    <input type="number" step="0.01" id="salary" name="salary" value="{{ old('salary', $employee->salary) }}"
                        class="mt-2 block w-full px-4 py-3 bg-slate-950/60 border border-slate-800 rounded-xl text-slate-200 focus:outline-none focus:ring-1 focus:ring-purple-500 text-sm">
                    @error('salary')
                        <p class="mt-1.5 text-xs text-rose-400">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Joining Date -->
                <div>
                    <label for="joining_date" class="block text-xs font-semibold uppercase tracking-wider text-slate-400">Joining Date</label>
                    <input type="date" id="joining_date" name="joining_date" value="{{ old('joining_date', $employee->joining_date ? $employee->joining_date->format('Y-m-d') : '') }}"
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
                        <label for="prefix" class="block text-xs font-semibold uppercase tracking-wider text-slate-400">Prefix</label>
                        <select id="prefix" name="prefix" class="mt-2 block w-full px-3 py-3 bg-slate-950/60 border border-slate-800 rounded-xl text-slate-200 focus:outline-none focus:ring-1 focus:ring-purple-500 text-sm">
                            <option value="">Select</option>
                            <option value="Mr." {{ old('prefix', $employee->prefix) === 'Mr.' ? 'selected' : '' }}>Mr.</option>
                            <option value="Mrs." {{ old('prefix', $employee->prefix) === 'Mrs.' ? 'selected' : '' }}>Mrs.</option>
                            <option value="Ms." {{ old('prefix', $employee->prefix) === 'Ms.' ? 'selected' : '' }}>Ms.</option>
                            <option value="Dr." {{ old('prefix', $employee->prefix) === 'Dr.' ? 'selected' : '' }}>Dr.</option>
                        </select>
                        @error('prefix') <p class="mt-1.5 text-xs text-rose-400">{{ $message }}</p> @enderror
                    </div>

                    <!-- Aadhaar Full Name -->
                    <div>
                        <label for="aadhaar_full_name" class="block text-xs font-semibold uppercase tracking-wider text-slate-400">Full Name as per Aadhaar</label>
                        <input type="text" id="aadhaar_full_name" name="aadhaar_full_name" value="{{ old('aadhaar_full_name', $employee->aadhaar_full_name) }}" class="mt-2 block w-full px-4 py-3 bg-slate-950/60 border border-slate-800 rounded-xl text-slate-200 focus:outline-none focus:ring-1 focus:ring-purple-500 text-sm" placeholder="Full Name">
                        @error('aadhaar_full_name') <p class="mt-1.5 text-xs text-rose-400">{{ $message }}</p> @enderror
                    </div>

                    <!-- Father's Name -->
                    <div>
                        <label for="father_name_aadhaar" class="block text-xs font-semibold uppercase tracking-wider text-slate-400">Father's Name (as Aadhaar)</label>
                        <input type="text" id="father_name_aadhaar" name="father_name_aadhaar" value="{{ old('father_name_aadhaar', $employee->father_name_aadhaar) }}" class="mt-2 block w-full px-4 py-3 bg-slate-950/60 border border-slate-800 rounded-xl text-slate-200 focus:outline-none focus:ring-1 focus:ring-purple-500 text-sm" placeholder="Father's Name">
                        @error('father_name_aadhaar') <p class="mt-1.5 text-xs text-rose-400">{{ $message }}</p> @enderror
                    </div>

                    <!-- Mother's Name -->
                    <div>
                        <label for="mother_name_aadhaar" class="block text-xs font-semibold uppercase tracking-wider text-slate-400">Mother's Name (as Aadhaar)</label>
                        <input type="text" id="mother_name_aadhaar" name="mother_name_aadhaar" value="{{ old('mother_name_aadhaar', $employee->mother_name_aadhaar) }}" class="mt-2 block w-full px-4 py-3 bg-slate-950/60 border border-slate-800 rounded-xl text-slate-200 focus:outline-none focus:ring-1 focus:ring-purple-500 text-sm" placeholder="Mother's Name">
                        @error('mother_name_aadhaar') <p class="mt-1.5 text-xs text-rose-400">{{ $message }}</p> @enderror
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-6">
                    <!-- Gender -->
                    <div>
                        <label for="gender" class="block text-xs font-semibold uppercase tracking-wider text-slate-400">Gender</label>
                        <select id="gender" name="gender" class="mt-2 block w-full px-3 py-3 bg-slate-950/60 border border-slate-800 rounded-xl text-slate-200 focus:outline-none focus:ring-1 focus:ring-purple-500 text-sm">
                            <option value="">Select</option>
                            <option value="Male" {{ old('gender', $employee->gender) === 'Male' ? 'selected' : '' }}>Male</option>
                            <option value="Female" {{ old('gender', $employee->gender) === 'Female' ? 'selected' : '' }}>Female</option>
                            <option value="Other" {{ old('gender', $employee->gender) === 'Other' ? 'selected' : '' }}>Other</option>
                        </select>
                        @error('gender') <p class="mt-1.5 text-xs text-rose-400">{{ $message }}</p> @enderror
                    </div>

                    <!-- DOB -->
                    <div>
                        <label for="dob" class="block text-xs font-semibold uppercase tracking-wider text-slate-400">Date of Birth</label>
                        <input type="date" id="dob" name="dob" value="{{ old('dob', $employee->dob ? $employee->dob->format('Y-m-d') : '') }}" class="mt-2 block w-full px-4 py-3 bg-slate-950/60 border border-slate-800 rounded-xl text-slate-200 focus:outline-none focus:ring-1 focus:ring-purple-500 text-sm">
                        @error('dob') <p class="mt-1.5 text-xs text-rose-400">{{ $message }}</p> @enderror
                    </div>

                    <!-- Mother Tongue -->
                    <div>
                        <label for="mother_tongue" class="block text-xs font-semibold uppercase tracking-wider text-slate-400">Mother Tongue</label>
                        <input type="text" id="mother_tongue" name="mother_tongue" value="{{ old('mother_tongue', $employee->mother_tongue) }}" class="mt-2 block w-full px-4 py-3 bg-slate-950/60 border border-slate-800 rounded-xl text-slate-200 focus:outline-none focus:ring-1 focus:ring-purple-500 text-sm" placeholder="Bengali, Hindi, etc.">
                        @error('mother_tongue') <p class="mt-1.5 text-xs text-rose-400">{{ $message }}</p> @enderror
                    </div>

                    <!-- Marital Status -->
                    <div>
                        <label for="marital_status" class="block text-xs font-semibold uppercase tracking-wider text-slate-400">Marital Status</label>
                        <select id="marital_status" name="marital_status" class="mt-2 block w-full px-3 py-3 bg-slate-950/60 border border-slate-800 rounded-xl text-slate-200 focus:outline-none focus:ring-1 focus:ring-purple-500 text-sm">
                            <option value="">Select</option>
                            <option value="Single" {{ old('marital_status', $employee->marital_status) === 'Single' ? 'selected' : '' }}>Single</option>
                            <option value="Married" {{ old('marital_status', $employee->marital_status) === 'Married' ? 'selected' : '' }}>Married</option>
                            <option value="Divorced" {{ old('marital_status', $employee->marital_status) === 'Divorced' ? 'selected' : '' }}>Divorced</option>
                            <option value="Widowed" {{ old('marital_status', $employee->marital_status) === 'Widowed' ? 'selected' : '' }}>Widowed</option>
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
                        <label for="aadhaar_number" class="block text-xs font-semibold uppercase tracking-wider text-slate-400">Aadhaar Number</label>
                        <input type="text" id="aadhaar_number" name="aadhaar_number" value="{{ old('aadhaar_number', $employee->aadhaar_number) }}" class="mt-2 block w-full px-4 py-3 bg-slate-950/60 border border-slate-800 rounded-xl text-slate-200 focus:outline-none focus:ring-1 focus:ring-purple-500 text-sm" placeholder="12-digit Aadhaar">
                        @error('aadhaar_number') <p class="mt-1.5 text-xs text-rose-400">{{ $message }}</p> @enderror
                    </div>

                    <!-- PAN Number -->
                    <div>
                        <label for="pan_number" class="block text-xs font-semibold uppercase tracking-wider text-slate-400">PAN Number</label>
                        <input type="text" id="pan_number" name="pan_number" value="{{ old('pan_number', $employee->pan_number) }}" class="mt-2 block w-full px-4 py-3 bg-slate-950/60 border border-slate-800 rounded-xl text-slate-200 focus:outline-none focus:ring-1 focus:ring-purple-500 text-sm" placeholder="10-digit PAN">
                        @error('pan_number') <p class="mt-1.5 text-xs text-rose-400">{{ $message }}</p> @enderror
                    </div>

                    <!-- Voter ID Number -->
                    <div>
                        <label for="voter_id_number" class="block text-xs font-semibold uppercase tracking-wider text-slate-400">Voter ID Number</label>
                        <input type="text" id="voter_id_number" name="voter_id_number" value="{{ old('voter_id_number', $employee->voter_id_number) }}" class="mt-2 block w-full px-4 py-3 bg-slate-950/60 border border-slate-800 rounded-xl text-slate-200 focus:outline-none focus:ring-1 focus:ring-purple-500 text-sm" placeholder="Voter ID Card No.">
                        @error('voter_id_number') <p class="mt-1.5 text-xs text-rose-400">{{ $message }}</p> @enderror
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                    <!-- Old UAN Number -->
                    <div>
                        <label for="old_uan_number" class="block text-xs font-semibold uppercase tracking-wider text-slate-400">Old UAN Number (PF)</label>
                        <input type="text" id="old_uan_number" name="old_uan_number" value="{{ old('old_uan_number', $employee->old_uan_number) }}" class="mt-2 block w-full px-4 py-3 bg-slate-950/60 border border-slate-800 rounded-xl text-slate-200 focus:outline-none focus:ring-1 focus:ring-purple-500 text-sm" placeholder="UAN Number">
                        @error('old_uan_number') <p class="mt-1.5 text-xs text-rose-400">{{ $message }}</p> @enderror
                    </div>

                    <!-- Old ESIC Number -->
                    <div>
                        <label for="old_esic_number" class="block text-xs font-semibold uppercase tracking-wider text-slate-400">Old ESIC Number</label>
                        <input type="text" id="old_esic_number" name="old_esic_number" value="{{ old('old_esic_number', $employee->old_esic_number) }}" class="mt-2 block w-full px-4 py-3 bg-slate-950/60 border border-slate-800 rounded-xl text-slate-200 focus:outline-none focus:ring-1 focus:ring-purple-500 text-sm" placeholder="ESIC Number">
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
                        <label for="contact_number" class="block text-xs font-semibold uppercase tracking-wider text-slate-400">Secondary Contact Number</label>
                        <input type="text" id="contact_number" name="contact_number" value="{{ old('contact_number', $employee->contact_number) }}" class="mt-2 block w-full px-4 py-3 bg-slate-950/60 border border-slate-800 rounded-xl text-slate-200 focus:outline-none focus:ring-1 focus:ring-purple-500 text-sm" placeholder="Mobile Number">
                        @error('contact_number') <p class="mt-1.5 text-xs text-rose-400">{{ $message }}</p> @enderror
                    </div>

                    <!-- Email ID -->
                    <div>
                        <label for="email_id" class="block text-xs font-semibold uppercase tracking-wider text-slate-400">Secondary Email Address</label>
                        <input type="email" id="email_id" name="email_id" value="{{ old('email_id', $employee->email_id) }}" class="mt-2 block w-full px-4 py-3 bg-slate-950/60 border border-slate-800 rounded-xl text-slate-200 focus:outline-none focus:ring-1 focus:ring-purple-500 text-sm" placeholder="secondary@example.com">
                        @error('email_id') <p class="mt-1.5 text-xs text-rose-400">{{ $message }}</p> @enderror
                    </div>

                    <!-- Emergency Contact -->
                    <div>
                        <label for="emergency_contact_number" class="block text-xs font-semibold uppercase tracking-wider text-slate-400">Emergency Contact Number</label>
                        <input type="text" id="emergency_contact_number" name="emergency_contact_number" value="{{ old('emergency_contact_number', $employee->emergency_contact_number) }}" class="mt-2 block w-full px-4 py-3 bg-slate-950/60 border border-slate-800 rounded-xl text-slate-200 focus:outline-none focus:ring-1 focus:ring-purple-500 text-sm" placeholder="Emergency Phone Number">
                        @error('emergency_contact_number') <p class="mt-1.5 text-xs text-rose-400">{{ $message }}</p> @enderror
                    </div>
                </div>

                <!-- Full Address -->
                <div>
                    <label for="aadhaar_address" class="block text-xs font-semibold uppercase tracking-wider text-slate-400">Full Address (as per Aadhaar)</label>
                    <textarea id="aadhaar_address" name="aadhaar_address" rows="3" class="mt-2 block w-full px-4 py-3 bg-slate-950/60 border border-slate-800 rounded-xl text-slate-200 focus:outline-none focus:ring-1 focus:ring-purple-500 text-sm" placeholder="House No, Street, Locality...">{{ old('aadhaar_address', $employee->aadhaar_address) }}</textarea>
                    @error('aadhaar_address') <p class="mt-1.5 text-xs text-rose-400">{{ $message }}</p> @enderror
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-6">
                    <!-- Landmark -->
                    <div>
                        <label for="landmark" class="block text-xs font-semibold uppercase tracking-wider text-slate-400">Landmark</label>
                        <input type="text" id="landmark" name="landmark" value="{{ old('landmark', $employee->landmark) }}" class="mt-2 block w-full px-4 py-3 bg-slate-950/60 border border-slate-800 rounded-xl text-slate-200 focus:outline-none focus:ring-1 focus:ring-purple-500 text-sm" placeholder="Near Temple, etc.">
                        @error('landmark') <p class="mt-1.5 text-xs text-rose-400">{{ $message }}</p> @enderror
                    </div>

                    <!-- City -->
                    <div>
                        <label for="city" class="block text-xs font-semibold uppercase tracking-wider text-slate-400">City</label>
                        <input type="text" id="city" name="city" value="{{ old('city', $employee->city) }}" class="mt-2 block w-full px-4 py-3 bg-slate-950/60 border border-slate-800 rounded-xl text-slate-200 focus:outline-none focus:ring-1 focus:ring-purple-500 text-sm" placeholder="Kolkata">
                        @error('city') <p class="mt-1.5 text-xs text-rose-400">{{ $message }}</p> @enderror
                    </div>

                    <!-- Pin Code -->
                    <div>
                        <label for="pin_code" class="block text-xs font-semibold uppercase tracking-wider text-slate-400">Pin Code</label>
                        <input type="text" id="pin_code" name="pin_code" value="{{ old('pin_code', $employee->pin_code) }}" class="mt-2 block w-full px-4 py-3 bg-slate-950/60 border border-slate-800 rounded-xl text-slate-200 focus:outline-none focus:ring-1 focus:ring-purple-500 text-sm" placeholder="700001">
                        @error('pin_code') <p class="mt-1.5 text-xs text-rose-400">{{ $message }}</p> @enderror
                    </div>

                    <!-- State -->
                    <div>
                        <label for="state" class="block text-xs font-semibold uppercase tracking-wider text-slate-400">State</label>
                        <input type="text" id="state" name="state" value="{{ old('state', $employee->state) }}" class="mt-2 block w-full px-4 py-3 bg-slate-950/60 border border-slate-800 rounded-xl text-slate-200 focus:outline-none focus:ring-1 focus:ring-purple-500 text-sm" placeholder="West Bengal">
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
                        <label for="bank_account_number" class="block text-xs font-semibold uppercase tracking-wider text-slate-400">Bank Account Number</label>
                        <input type="text" id="bank_account_number" name="bank_account_number" value="{{ old('bank_account_number', $employee->bank_account_number) }}" class="mt-2 block w-full px-4 py-3 bg-slate-950/60 border border-slate-800 rounded-xl text-slate-200 focus:outline-none focus:ring-1 focus:ring-purple-500 text-sm" placeholder="Account Number">
                        @error('bank_account_number') <p class="mt-1.5 text-xs text-rose-400">{{ $message }}</p> @enderror
                    </div>

                    <!-- IFSC Code -->
                    <div>
                        <label for="ifsc_code" class="block text-xs font-semibold uppercase tracking-wider text-slate-400">IFSC Code</label>
                        <input type="text" id="ifsc_code" name="ifsc_code" value="{{ old('ifsc_code', $employee->ifsc_code) }}" class="mt-2 block w-full px-4 py-3 bg-slate-950/60 border border-slate-800 rounded-xl text-slate-200 focus:outline-none focus:ring-1 focus:ring-purple-500 text-sm" placeholder="IFSC Code">
                        @error('ifsc_code') <p class="mt-1.5 text-xs text-rose-400">{{ $message }}</p> @enderror
                    </div>

                    <!-- Bank Name -->
                    <div>
                        <label for="bank_name" class="block text-xs font-semibold uppercase tracking-wider text-slate-400">Bank Name</label>
                        <input type="text" id="bank_name" name="bank_name" value="{{ old('bank_name', $employee->bank_name) }}" class="mt-2 block w-full px-4 py-3 bg-slate-950/60 border border-slate-800 rounded-xl text-slate-200 focus:outline-none focus:ring-1 focus:ring-purple-500 text-sm" placeholder="e.g. State Bank of India">
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
                        <label for="last_qualification" class="block text-xs font-semibold uppercase tracking-wider text-slate-400">Last Qualification</label>
                        <input type="text" id="last_qualification" name="last_qualification" value="{{ old('last_qualification', $employee->last_qualification) }}" class="mt-2 block w-full px-4 py-3 bg-slate-950/60 border border-slate-800 rounded-xl text-slate-200 focus:outline-none focus:ring-1 focus:ring-purple-500 text-sm" placeholder="B.Tech, B.Sc, 12th, etc.">
                        @error('last_qualification') <p class="mt-1.5 text-xs text-rose-400">{{ $message }}</p> @enderror
                    </div>

                    <!-- Pass Out Year -->
                    <div>
                        <label for="pass_out_year" class="block text-xs font-semibold uppercase tracking-wider text-slate-400">Pass Out Year</label>
                        <input type="text" id="pass_out_year" name="pass_out_year" value="{{ old('pass_out_year', $employee->pass_out_year) }}" class="mt-2 block w-full px-4 py-3 bg-slate-950/60 border border-slate-800 rounded-xl text-slate-200 focus:outline-none focus:ring-1 focus:ring-purple-500 text-sm" placeholder="e.g. 2024">
                        @error('pass_out_year') <p class="mt-1.5 text-xs text-rose-400">{{ $message }}</p> @enderror
                    </div>

                    <!-- Client Name -->
                    <div>
                        <label for="client_name" class="block text-xs font-semibold uppercase tracking-wider text-slate-400">Client Name</label>
                        <input type="text" id="client_name" name="client_name" value="{{ old('client_name', $employee->client_name) }}" class="mt-2 block w-full px-4 py-3 bg-slate-950/60 border border-slate-800 rounded-xl text-slate-200 focus:outline-none focus:ring-1 focus:ring-purple-500 text-sm" placeholder="Assigned Client">
                        @error('client_name') <p class="mt-1.5 text-xs text-rose-400">{{ $message }}</p> @enderror
                    </div>

                    <!-- Work Location -->
                    <div>
                        <label for="work_location" class="block text-xs font-semibold uppercase tracking-wider text-slate-400">Work Location</label>
                        <input type="text" id="work_location" name="work_location" value="{{ old('work_location', $employee->work_location) }}" class="mt-2 block w-full px-4 py-3 bg-slate-950/60 border border-slate-800 rounded-xl text-slate-200 focus:outline-none focus:ring-1 focus:ring-purple-500 text-sm" placeholder="Office Location">
                        @error('work_location') <p class="mt-1.5 text-xs text-rose-400">{{ $message }}</p> @enderror
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                    <!-- Text Designation -->
                    <div>
                        <label for="designation" class="block text-xs font-semibold uppercase tracking-wider text-slate-400">Job Title / Designation (Text)</label>
                        <input type="text" id="designation" name="designation" value="{{ old('designation', $employee->designation) }}" class="mt-2 block w-full px-4 py-3 bg-slate-950/60 border border-slate-800 rounded-xl text-slate-200 focus:outline-none focus:ring-1 focus:ring-purple-500 text-sm" placeholder="Official Designation">
                        @error('designation') <p class="mt-1.5 text-xs text-rose-400">{{ $message }}</p> @enderror
                    </div>

                    <!-- NTH Salary -->
                    <div>
                        <label for="nth_salary" class="block text-xs font-semibold uppercase tracking-wider text-slate-400">Net Take Home (NTH) Salary</label>
                        <input type="number" step="0.01" id="nth_salary" name="nth_salary" value="{{ old('nth_salary', $employee->nth_salary) }}" class="mt-2 block w-full px-4 py-3 bg-slate-950/60 border border-slate-800 rounded-xl text-slate-200 focus:outline-none focus:ring-1 focus:ring-purple-500 text-sm" placeholder="NTH Salary">
                        @error('nth_salary') <p class="mt-1.5 text-xs text-rose-400">{{ $message }}</p> @enderror
                    </div>
                </div>
            </div>

            <div class="pt-6 border-t border-slate-850 flex justify-end space-x-3">
                <a href="{{ route('admin.employees.index') }}"
                    class="px-6 py-3 bg-slate-850 hover:bg-slate-800 text-slate-400 hover:text-white rounded-xl text-sm font-semibold transition-all">
                    Cancel
                </a>
                <button type="submit"
                    class="px-6 py-3 bg-gradient-to-r from-purple-600 to-indigo-600 hover:from-purple-500 hover:to-indigo-500 text-white rounded-xl text-sm font-semibold transition-all shadow-lg shadow-purple-500/10">
                    <i class="fa-solid fa-save mr-2"></i> Save Changes
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
