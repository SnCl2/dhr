<?php

namespace App\Http\Controllers;

use App\Models\Admin;
use App\Models\Employee;
use App\Models\Company;
use App\Models\Department;
use App\Models\Designation;
use App\Models\OfferLetterTemplate;
use App\Models\OfferLetter;
use App\Models\Payslip;
use App\Models\Inquiry;
use App\Models\Bulletin;
use App\Models\SiteContent;
use App\Models\Staff;
use App\Models\AuditLog;
use App\Services\DocumentGeneratorService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class AdminDashboardController extends Controller
{
    protected $pdfService;

    public function __construct(DocumentGeneratorService $pdfService)
    {
        $this->pdfService = $pdfService;
    }

    /**
     * Dashboard home.
     */
    public function index()
    {
        $stats = [
            'total_employees' => Employee::count(),
            'active_employees' => Employee::where('status', 'active')->count(),
            'pending_reviews' => Employee::where('status', 'pending_review')->count(),
            'unread_inquiries' => Inquiry::where('status', 'unread')->count(),
            'total_staff' => Staff::count(),
            'internal_staff' => Employee::where('company_id', 1)->count(),
        ];

        $recentEmployees = Employee::latest()->take(5)->get();
        $recentInquiries = Inquiry::latest()->take(5)->get();

        return view('admin.dashboard', compact('stats', 'recentEmployees', 'recentInquiries'));
    }

    /*
    |--------------------------------------------------------------------------
    | Employees CRUD & Import / Export
    |--------------------------------------------------------------------------
    */
    protected function buildEmployeeQuery(Request $request)
    {
        $query = Employee::with(['department', 'designationRelation', 'company', 'offerLetters']);

        // Filter by staff_type (employee = company_id != 1 or null, staff = company_id == 1)
        $staffType = $request->input('staff_type', 'employee');
        if ($staffType === 'staff') {
            $query->where('company_id', 1);
        } else {
            $query->where(function($q) {
                $q->where('company_id', '!=', 1)
                  ->orWhereNull('company_id');
            });
        }

        // Comprehensive search filter
        if ($request->filled('search')) {
            $search = trim($request->search);
            $query->where(function($q) use ($search) {
                $q->where('aadhaar_full_name', 'like', "%$search%")
                  ->orWhere('first_name', 'like', "%$search%")
                  ->orWhere('last_name', 'like', "%$search%")
                  ->orWhere('email', 'like', "%$search%")
                  ->orWhere('phone', 'like', "%$search%")
                  ->orWhere('contact_number', 'like', "%$search%")
                  ->orWhere('employee_id', 'like', "%$search%")
                  ->orWhere('aadhaar_number', 'like', "%$search%")
                  ->orWhere('pan_number', 'like', "%$search%")
                  ->orWhere('voter_id_number', 'like', "%$search%")
                  ->orWhere('old_uan_number', 'like', "%$search%")
                  ->orWhere('bank_account_number', 'like', "%$search%")
                  ->orWhere('city', 'like', "%$search%")
                  ->orWhere('work_location', 'like', "%$search%");
            });
        }

        // Company multi-select filter
        if ($request->filled('company_id')) {
            $companies = is_array($request->company_id) ? array_filter($request->company_id) : [$request->company_id];
            if (!empty($companies)) {
                $query->whereIn('company_id', $companies);
            }
        }

        // Department multi-select filter
        if ($request->filled('department_id')) {
            $departments = is_array($request->department_id) ? array_filter($request->department_id) : [$request->department_id];
            if (!empty($departments)) {
                $query->whereIn('department_id', $departments);
            }
        }

        // Designation multi-select filter
        if ($request->filled('designation_id')) {
            $designations = is_array($request->designation_id) ? array_filter($request->designation_id) : [$request->designation_id];
            if (!empty($designations)) {
                $query->whereIn('designation_id', $designations);
            }
        }

        // Status multi-select filter
        if ($request->filled('status')) {
            $statuses = is_array($request->status) ? array_filter($request->status) : [$request->status];
            if (!empty($statuses)) {
                $query->whereIn('status', $statuses);
            }
        }

        // Work Location filter
        if ($request->filled('work_location')) {
            $query->where('work_location', 'like', '%' . trim($request->work_location) . '%');
        }

        // Date Range filter
        if ($request->filled('from_date')) {
            $query->whereDate('joining_date', '>=', $request->from_date);
        }
        if ($request->filled('to_date')) {
            $query->whereDate('joining_date', '<=', $request->to_date);
        }

        // Offer letter status filter
        if ($request->filled('offer_letter_status')) {
            if ($request->offer_letter_status === 'generated') {
                $query->has('offerLetters');
            } elseif ($request->offer_letter_status === 'not_generated') {
                $query->doesntHave('offerLetters');
            }
        }

        return $query;
    }

    public function employeesIndex(Request $request)
    {
        $query = $this->buildEmployeeQuery($request);

        $employees = $query->latest()->paginate(15)->withQueryString();
        $departments = Department::all();
        $designations = Designation::all();
        if ($request->input('staff_type', 'employee') === 'staff') {
            $companies = Company::where('id', 1)->get();
        } else {
            $companies = Company::where('id', '!=', 1)->get();
        }
        
        // Distinct work locations for filter dropdown
        $workLocations = Employee::whereNotNull('work_location')
            ->where('work_location', '!=', '')
            ->distinct()
            ->pluck('work_location');

        return view('admin.employees.index', compact('employees', 'departments', 'designations', 'companies', 'workLocations'));
    }

    public function employeesExport(Request $request)
    {
        $fileName = 'employees_export_' . date('Y-m-d_His') . '.csv';
        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => "attachment; filename=\"{$fileName}\"",
        ];

        $query = $this->buildEmployeeQuery($request)->latest();

        $callback = function() use ($query) {
            $file = fopen('php://output', 'w');
            
            // Output UTF-8 BOM for Excel compatibility
            fputs($file, "\xEF\xBB\xBF");

            // 30 Column headers matching Book 9.xlsx
            fputcsv($file, [
                'Full Name as per Aadhaar',
                'Aadhaar Number',
                'PAN Number',
                'Voter ID Number',
                'Prefix',
                "Father's Name as per Aadhaar",
                "Mother's Name as per Aadhaar",
                'Gender',
                'Date of Birth',
                'Mother Tongue',
                'Full Address as per Aadhaar',
                'Landmark',
                'Contact Number',
                'City',
                'Emargency Contact Number',
                'Pin Code',
                'State',
                'Last Qualification',
                'Pass out Year',
                'Marital Status',
                'Email ID',
                'Old UAN Number',
                'Old ESIC Number',
                'Bank Account Number',
                'IFSC Code Number',
                'Bank Name',
                'Client Name',
                'Work Location',
                'Designation',
                'NTH Salary'
            ]);

            $query->chunk(100, function($employees) use ($file) {
                foreach ($employees as $emp) {
                    fputcsv($file, [
                        $emp->aadhaar_full_name ?? $emp->full_name,
                        $emp->aadhaar_number ?? '',
                        $emp->pan_number ?? '',
                        $emp->voter_id_number ?? '',
                        $emp->prefix ?? '',
                        $emp->father_name_aadhaar ?? '',
                        $emp->mother_name_aadhaar ?? '',
                        $emp->gender ?? '',
                        $emp->dob ? $emp->dob->format('Y-m-d') : '',
                        $emp->mother_tongue ?? '',
                        $emp->aadhaar_address ?? '',
                        $emp->landmark ?? '',
                        $emp->contact_number ?? $emp->phone ?? '',
                        $emp->city ?? '',
                        $emp->emergency_contact_number ?? '',
                        $emp->pin_code ?? '',
                        $emp->state ?? '',
                        $emp->last_qualification ?? '',
                        $emp->pass_out_year ?? '',
                        $emp->marital_status ?? '',
                        $emp->email ?? $emp->email_id ?? '',
                        $emp->old_uan_number ?? '',
                        $emp->old_esic_number ?? '',
                        $emp->bank_account_number ?? '',
                        $emp->ifsc_code ?? '',
                        $emp->bank_name ?? '',
                        $emp->company ? $emp->company->name : '',
                        $emp->work_location ?? '',
                        $emp->designationRelation ? $emp->designationRelation->name : '',
                        $emp->nth_salary ?? $emp->salary ?? '',
                    ]);
                }
            });

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    public function employeesCreate()
    {
        $departments = Department::all();
        $designations = Designation::all();
        $companies = Company::all();
        return view('admin.employees.create', compact('departments', 'designations', 'companies'));
    }

    public function employeesStore(Request $request)
    {
        $validated = $request->validate([
            'email' => ['required', 'email', 'unique:employees,email'],
            'phone' => ['nullable', 'string', 'max:20'],
            'status' => ['required', 'string'],
            'department_id' => ['nullable', 'exists:departments,id'],
            'designation_id' => ['nullable', 'exists:designations,id'],
            'company_id' => ['nullable', 'exists:companies,id'],
            'joining_date' => ['nullable', 'date'],
            'salary' => ['nullable', 'numeric', 'min:0'],

            // Aadhaar and KYC Attributes
            'aadhaar_full_name' => ['required', 'string', 'max:255'],
            'aadhaar_number' => ['required', 'string', 'max:20'],
            'pan_number' => ['nullable', 'string', 'max:20'],
            'voter_id_number' => ['nullable', 'string', 'max:20'],
            'prefix' => ['required', 'string', 'max:10'],
            'father_name_aadhaar' => ['required', 'string', 'max:255'],
            'mother_name_aadhaar' => ['required', 'string', 'max:255'],
            'gender' => ['required', 'string', 'max:20'],
            'dob' => ['required', 'date'],
            'mother_tongue' => ['required', 'string', 'max:100'],
            'aadhaar_address' => ['required', 'string'],
            'landmark' => ['required', 'string', 'max:255'],
            'contact_number' => ['required', 'string', 'max:20'],
            'city' => ['required', 'string', 'max:100'],
            'emergency_contact_number' => ['required', 'string', 'max:20'],
            'pin_code' => ['required', 'string', 'max:10'],
            'state' => ['required', 'string', 'max:100'],
            'last_qualification' => ['required', 'string', 'max:255'],
            'pass_out_year' => ['required', 'string', 'max:10'],
            'marital_status' => ['required', 'string', 'max:50'],
            'email_id' => ['nullable', 'string', 'email', 'max:255'],
            'old_uan_number' => ['required', 'string', 'max:50'],
            'old_esic_number' => ['nullable', 'string', 'max:50'],
            'bank_account_number' => ['required', 'string', 'max:50'],
            'ifsc_code' => ['required', 'string', 'max:20'],
            'bank_name' => ['required', 'string', 'max:255'],
            'work_location' => ['required', 'string', 'max:255'],
            'nth_salary' => ['required', 'numeric', 'min:0'],

            // Profile Image & Documents
            'profile_image' => ['nullable', 'image', 'mimes:jpeg,jpg,png,webp', 'max:5120'],
            'employee_document' => ['nullable', 'file', 'mimes:jpeg,jpg,png,pdf,zip,rar,doc,docx', 'max:20480'],
        ]);

        if ($request->hasFile('profile_image')) {
            $image = $request->file('profile_image');
            $imageName = time() . '_avatar_' . $image->getClientOriginalName();
            $path = $image->storeAs('avatars', $imageName, 'public');
            $validated['profile_image'] = 'storage/' . $path;
        } else {
            $validated['profile_image'] = null;
        }

        if ($request->hasFile('employee_document')) {
            $file = $request->file('employee_document');
            $fileName = time() . '_doc_' . $file->getClientOriginalName();
            $path = $file->storeAs('documents', $fileName, 'public');
            $validated['employee_document'] = 'storage/' . $path;
        } else {
            $validated['employee_document'] = null;
        }

        // Auto-generate Employee ID starting with RM01 prefix
        $employeeId = Employee::generateNextEmployeeId($request->company_id);

        // Auto-generate random temp password from full name
        $namePart = $request->aadhaar_full_name ? explode(' ', trim($request->aadhaar_full_name))[0] : 'emp';
        $cleanName = preg_replace('/[^a-zA-Z0-9]/', '', $namePart) ?: 'emp';
        $plainPassword = strtolower($cleanName) . rand(1000, 9999);

        $validated['employee_id'] = $employeeId;
        $validated['password'] = Hash::make($plainPassword);
        $validated['is_password_changed'] = false; // Forces change on login

        Employee::create($validated);

        $staffType = ($request->company_id == 1) ? 'staff' : 'employee';
        return redirect()->route('admin.employees.index', ['staff_type' => $staffType])
            ->with('success', "Employee created successfully! ID: {$employeeId} | Temp Password: {$plainPassword}");
    }

    public function employeesEdit(Employee $employee)
    {
        $departments = Department::all();
        $designations = Designation::all();
        $companies = Company::all();
        return view('admin.employees.edit', compact('employee', 'departments', 'designations', 'companies'));
    }

    public function employeesUpdate(Request $request, Employee $employee)
    {
        $validated = $request->validate([
            'email' => ['required', 'email', 'unique:employees,email,' . $employee->id],
            'phone' => ['nullable', 'string', 'max:20'],
            'status' => ['required', 'string'],
            'department_id' => ['nullable', 'exists:departments,id'],
            'designation_id' => ['nullable', 'exists:designations,id'],
            'company_id' => ['nullable', 'exists:companies,id'],
            'joining_date' => ['nullable', 'date'],
            'salary' => ['nullable', 'numeric', 'min:0'],

            // Aadhaar and KYC Attributes
            'aadhaar_full_name' => ['required', 'string', 'max:255'],
            'aadhaar_number' => ['required', 'string', 'max:20'],
            'pan_number' => ['nullable', 'string', 'max:20'],
            'voter_id_number' => ['nullable', 'string', 'max:20'],
            'prefix' => ['required', 'string', 'max:10'],
            'father_name_aadhaar' => ['required', 'string', 'max:255'],
            'mother_name_aadhaar' => ['required', 'string', 'max:255'],
            'gender' => ['required', 'string', 'max:20'],
            'dob' => ['required', 'date'],
            'mother_tongue' => ['required', 'string', 'max:100'],
            'aadhaar_address' => ['required', 'string'],
            'landmark' => ['required', 'string', 'max:255'],
            'contact_number' => ['required', 'string', 'max:20'],
            'city' => ['required', 'string', 'max:100'],
            'emergency_contact_number' => ['required', 'string', 'max:20'],
            'pin_code' => ['required', 'string', 'max:10'],
            'state' => ['required', 'string', 'max:100'],
            'last_qualification' => ['required', 'string', 'max:255'],
            'pass_out_year' => ['required', 'string', 'max:10'],
            'marital_status' => ['required', 'string', 'max:50'],
            'email_id' => ['nullable', 'string', 'email', 'max:255'],
            'old_uan_number' => ['required', 'string', 'max:50'],
            'old_esic_number' => ['nullable', 'string', 'max:50'],
            'bank_account_number' => ['required', 'string', 'max:50'],
            'ifsc_code' => ['required', 'string', 'max:20'],
            'bank_name' => ['required', 'string', 'max:255'],
            'work_location' => ['required', 'string', 'max:255'],
            'nth_salary' => ['required', 'numeric', 'min:0'],

            // Profile Image & Documents
            'profile_image' => ['nullable', 'image', 'mimes:jpeg,jpg,png,webp', 'max:5120'],
            'employee_document' => ['nullable', 'file', 'mimes:jpeg,jpg,png,pdf,zip,rar,doc,docx', 'max:20480'],

            // Optional Login Password override
            'password' => ['nullable', 'string', 'min:8'],
        ]);

        if ($request->hasFile('profile_image')) {
            if ($employee->profile_image) {
                $rel = str_replace('storage/', '', $employee->profile_image);
                \Illuminate\Support\Facades\Storage::disk('public')->delete($rel);
            }
            $image = $request->file('profile_image');
            $imageName = time() . '_avatar_' . $image->getClientOriginalName();
            $path = $image->storeAs('avatars', $imageName, 'public');
            $validated['profile_image'] = 'storage/' . $path;
        } else {
            unset($validated['profile_image']);
        }

        if ($request->hasFile('employee_document')) {
            if ($employee->employee_document) {
                $rel = str_replace('storage/', '', $employee->employee_document);
                \Illuminate\Support\Facades\Storage::disk('public')->delete($rel);
            }
            $file = $request->file('employee_document');
            $fileName = time() . '_doc_' . $file->getClientOriginalName();
            $path = $file->storeAs('documents', $fileName, 'public');
            $validated['employee_document'] = 'storage/' . $path;
        } else {
            unset($validated['employee_document']);
        }

        if ($request->filled('password')) {
            $validated['password'] = Hash::make($request->password);
        } else {
            unset($validated['password']);
        }

        $employee->update($validated);

        $staffType = ($employee->company_id == 1) ? 'staff' : 'employee';
        return redirect()->route('admin.employees.index', ['staff_type' => $staffType])
            ->with('success', "Employee {$employee->employee_id} updated successfully.");
    }

    public function employeesDestroy(Employee $employee)
    {
        $staffType = ($employee->company_id == 1) ? 'staff' : 'employee';
        $employee->delete();
        return redirect()->route('admin.employees.index', ['staff_type' => $staffType])
            ->with('success', "Employee deleted successfully.");
    }

    public function employeesBulkAction(Request $request)
    {
        $request->validate([
            'employee_ids' => ['required', 'array'],
            'employee_ids.*' => ['exists:employees,id'],
            'action' => ['required', 'in:offer_letter,status_change,delete'],
            'status' => ['nullable', 'in:pending_review,active,inactive,on_leave,terminated'],
            'type' => ['nullable', 'in:internal,external'],
        ]);

        $employeeIds = $request->employee_ids;
        $action = $request->action;

        if ($action === 'delete') {
            \Illuminate\Support\Facades\Gate::authorize('delete_employees');
            Employee::whereIn('id', $employeeIds)->delete();
            $staffType = $request->input('staff_type', 'employee');
            return redirect()->route('admin.employees.index', ['staff_type' => $staffType])
                ->with('success', 'Successfully deleted ' . count($employeeIds) . ' employees.');
        }

        if ($action === 'status_change') {
            \Illuminate\Support\Facades\Gate::authorize('edit_employees');
            Employee::whereIn('id', $employeeIds)->update(['status' => $request->status]);
            $staffType = $request->input('staff_type', 'employee');
            return redirect()->route('admin.employees.index', ['staff_type' => $staffType])
                ->with('success', 'Successfully updated status for ' . count($employeeIds) . ' employees.');
        }

        if ($action === 'offer_letter') {
            \Illuminate\Support\Facades\Gate::authorize('create_offer_letters');
            $employees = Employee::with('company')->whereIn('id', $employeeIds)->get();
            $count = 0;
            $type = $request->type ?? 'external';

            foreach ($employees as $employee) {
                if ($employee->status === 'terminated') continue;

                $customData = [
                    'salary' => $employee->salary,
                    'joining_date' => $employee->joining_date ? $employee->joining_date->format('d-m-Y') : null,
                ];

                $pdfPath = $this->pdfService->generateOfferLetterPdf($employee, $type, $customData);

                OfferLetter::create([
                    'employee_id' => $employee->id,
                    'pdf_path' => $pdfPath,
                ]);

                $count++;
            }

            $staffType = $request->input('staff_type', 'employee');
            return redirect()->route('admin.employees.index', ['staff_type' => $staffType])
                ->with('success', 'Successfully generated ' . $count . ' offer letters.');
        }

        $staffType = $request->input('staff_type', 'employee');
        return redirect()->route('admin.employees.index', ['staff_type' => $staffType]);
    }

    public function downloadEmployeeTemplate(Request $request)
    {
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="employee_import_template.csv"',
        ];

        $companyName = $request->query('company');
        $defaultSalary = '';
        if ($companyName) {
            $company = \App\Models\Company::where('name', $companyName)->first();
            if ($company && $company->net_salary !== null) {
                $defaultSalary = (float)$company->net_salary == (int)$company->net_salary 
                    ? (string)(int)$company->net_salary 
                    : (string)$company->net_salary;
            } else {
                $defaultSalary = '18000';
            }
        }

        if ($request->filled('salary')) {
            $defaultSalary = $request->query('salary');
        }

        $callback = function() use ($request, $defaultSalary) {
            $file = fopen('php://output', 'w');
            
            // CSV headers matching Book 9.xlsx
            fputcsv($file, [
                'Full Name as per Aadhaar',
                'Aadhaar Number',
                'PAN Number',
                'Voter ID Number',
                'Prefix',
                "Father's Name as per Aadhaar",
                "Mother's Name as per Aadhaar",
                'Gender',
                'Date of Birth',
                'Mother Tongue',
                'Full Address as per Aadhaar',
                'Landmark',
                'Contact Number',
                'City',
                'Emargency Contact Number',
                'Pin Code',
                'State',
                'Last Qualification',
                'Pass out Year',
                'Marital Status',
                'Email ID',
                'Old UAN Number',
                'Old ESIC Number',
                'Bank Account Number',
                'IFSC Code Number',
                'Bank Name',
                'Client Name',
                'Work Location',
                'Designation',
                'NTH Salary'
            ]);

            // Prefilled row with only dynamic selection values
            fputcsv($file, [
                '',
                '',
                '',
                '',
                '',
                '',
                '',
                '',
                '',
                '',
                '',
                '',
                '',
                '',
                '',
                '',
                '',
                '',
                '',
                '',
                '',
                '',
                '',
                '',
                '',
                '',
                $request->query('company') ?: '',
                '',
                $request->query('designation') ?: '',
                $defaultSalary ?: ''
            ]);

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    public function employeesImport(Request $request)
    {
        $request->validate([
            'csv_file' => ['required', 'file', 'mimes:csv,txt'],
        ]);

        $path = $request->file('csv_file')->getRealPath();
        $file = fopen($path, 'r');

        // Parse header and normalize keys
        $rawHeader = fgetcsv($file);
        if (!$rawHeader) {
            return redirect()->route('admin.employees.index')->with('error', 'Empty CSV file uploaded.');
        }

        // Helper to normalize column names
        $normalizeKey = function($k) {
            $k = trim(strtolower($k));
            $k = str_replace([' ', "'", '"', '-', '_', '/', '\\', '(', ')', '.'], '', $k);
            return $k;
        };

        $normalizedHeaders = array_map($normalizeKey, $rawHeader);
        
        $importedCount = 0;
        $failedCount = 0;
        $seenEmails = [];
        $failedRowsInfo = [];
        $failedFileName = null;
        $failedFileHandle = null;
        $importedCandidates = [];

        $rowNum = 1; // Header is row 1
        while (($row = fgetcsv($file)) !== FALSE) {
            $rowNum++;
            if (empty(array_filter($row))) continue;

            $data = [];
            foreach ($row as $index => $value) {
                if (isset($normalizedHeaders[$index])) {
                    $data[$normalizedHeaders[$index]] = trim($value);
                }
            }

            // Extract fields with multiple header alias support
            $fullName = $data['fullnameasperaadhaar'] ?? $data['fullname'] ?? $data['name'] ?? $data['aadhaarfullname'] ?? '';
            $email = $data['emailid'] ?? $data['email'] ?? '';
            
            // Default email if missing and name exists, matching original behavior
            if (empty($email) && !empty($fullName)) {
                $cleanName = preg_replace('/[^a-zA-Z0-9]/', '', strtolower($fullName)) ?: 'emp';
                $email = $cleanName . rand(100, 9999) . '@rmhrsolutions.in';
            }

            $rowErrors = [];

            // 1. Validate Full Name
            if (empty($fullName)) {
                $rowErrors[] = "Full Name as per Aadhaar is required.";
            }

            // 2. Validate Email
            if (empty($email)) {
                $rowErrors[] = "Email ID is required.";
            } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $rowErrors[] = "Email ID '$email' has an invalid format.";
            } elseif (Employee::where('email', $email)->exists()) {
                $rowErrors[] = "Email ID '$email' has already been taken.";
            } elseif (in_array($email, $seenEmails)) {
                $rowErrors[] = "Duplicate Email ID '$email' in this CSV batch.";
            }

            // 3. Validate Aadhaar Number
            $aadhaar = $data['aadhaarnumber'] ?? '';
            if (empty($aadhaar)) {
                $rowErrors[] = "Aadhaar Number is required.";
            } elseif ($aadhaar !== 'NA' && !preg_match('/^\d{12}$/', $aadhaar)) {
                $rowErrors[] = "Aadhaar Number must be exactly 12 digits.";
            }

            // 4. Validate Contact Number
            $contact = $data['contactnumber'] ?? $data['phone'] ?? '';
            if (empty($contact)) {
                $rowErrors[] = "Contact Number is required.";
            }

            // 5. Validate Bank Details
            $bankAcc = $data['bankaccountnumber'] ?? '';
            if (empty($bankAcc)) {
                $rowErrors[] = "Bank Account Number is required.";
            }

            $ifsc = $data['ifsccodenumber'] ?? $data['ifsccode'] ?? '';
            if (empty($ifsc)) {
                $rowErrors[] = "IFSC Code is required.";
            }

            // 6. Validate NTH Salary
            $nthSalaryRaw = $data['nthsalary'] ?? $data['salary'] ?? '';
            if ($nthSalaryRaw === '') {
                $rowErrors[] = "NTH Salary is required.";
            } elseif (!is_numeric($nthSalaryRaw)) {
                $rowErrors[] = "NTH Salary must be a number.";
            }

            // If there are validation failures, skip database insertion and write to failed CSV
            if (!empty($rowErrors)) {
                $failedCount++;
                $failedRowsInfo[] = [
                    'row' => $rowNum,
                    'name' => $fullName ?: 'Unknown',
                    'email' => $email ?: 'Unknown',
                    'reasons' => $rowErrors,
                ];

                // Lazy initialize the failed CSV file
                if (!$failedFileHandle) {
                    $directory = storage_path('app/failed_imports');
                    if (!file_exists($directory)) {
                        mkdir($directory, 0755, true);
                    }
                    $failedFileName = 'failed_import_' . time() . '_' . \Illuminate\Support\Str::random(10) . '.csv';
                    $failedFilePath = $directory . '/' . $failedFileName;
                    $failedFileHandle = fopen($failedFilePath, 'w');
                    
                    // Add UTF-8 BOM for Excel compatibility
                    fputs($failedFileHandle, "\xEF\xBB\xBF");

                    // Write header with an extra "Failure Reason" column
                    $failedHeader = $rawHeader;
                    $failedHeader[] = 'Failure Reason';
                    fputcsv($failedFileHandle, $failedHeader);
                }

                // Write the failed row to file
                $failedRowData = $row;
                $headerCount = count($rawHeader);
                $rowLength = count($failedRowData);
                if ($rowLength < $headerCount) {
                    $failedRowData = array_pad($failedRowData, $headerCount, '');
                }
                $failedRowData[] = implode('; ', $rowErrors);
                fputcsv($failedFileHandle, $failedRowData);

                continue;
            }

            // Date of birth parser
            $dob = null;
            $rawDob = $data['dateofbirth'] ?? $data['dob'] ?? null;
            if (!empty($rawDob)) {
                try {
                    $dob = \Carbon\Carbon::parse($rawDob)->format('Y-m-d');
                } catch (\Exception $e) {
                    $dob = '1995-01-01';
                }
            }

            // Company
            $companyName = $data['clientname'] ?? $data['company'] ?? $data['companyname'] ?? null;
            $companyId = null;
            if (!empty($companyName)) {
                $comp = Company::firstOrCreate(['name' => trim($companyName)]);
                $companyId = $comp->id;
            }

            // Designation
            $desigName = $data['designation'] ?? $data['jobtitle'] ?? 'Staff Member';
            $desig = Designation::firstOrCreate(['name' => trim($desigName)]);

            // Department (default or matched)
            $dept = Department::firstOrCreate(['name' => 'Operations & Logistics']);

            // Get next Employee ID starting with RM01 prefix
            $employeeId = Employee::generateNextEmployeeId($companyId);

            // Generate temporary password
            $plainPassword = 'password1234';

            $nthSalary = (float) $nthSalaryRaw;
            $grossSalary = $nthSalary > 0 ? round($nthSalary * 1.15, 2) : 15000.00;

            Employee::create([
                'employee_id' => $employeeId,
                'email' => $email,
                'phone' => $data['contactnumber'] ?? $data['phone'] ?? null,
                'password' => Hash::make($plainPassword),
                'status' => 'pending_review',
                'department_id' => $dept->id,
                'designation_id' => $desig->id,
                'company_id' => $companyId,
                'joining_date' => date('Y-m-d'),
                'salary' => $grossSalary,
                'is_password_changed' => false,

                // KYC & Personal
                'aadhaar_full_name' => $fullName,
                'aadhaar_number' => $data['aadhaarnumber'] ?? 'NA',
                'pan_number' => $data['pannumber'] ?? null,
                'voter_id_number' => $data['voteridnumber'] ?? null,
                'prefix' => $data['prefix'] ?? 'Mr.',
                'father_name_aadhaar' => $data['fathersnameasperaadhaar'] ?? $data['fathername'] ?? 'NA',
                'mother_name_aadhaar' => $data['mothersnameasperaadhaar'] ?? $data['mothername'] ?? 'NA',
                'gender' => $data['gender'] ?? 'Male',
                'dob' => $dob ?? '1995-01-01',
                'mother_tongue' => $data['mothertongue'] ?? 'Bengali',
                'aadhaar_address' => $data['fulladdressasperaadhaar'] ?? $data['address'] ?? 'NA',
                'landmark' => $data['landmark'] ?? 'NA',
                'contact_number' => $data['contactnumber'] ?? $data['phone'] ?? 'NA',
                'city' => $data['city'] ?? 'NA',
                'emergency_contact_number' => $data['emargencycontactnumber'] ?? $data['emergencycontactnumber'] ?? ($data['contactnumber'] ?? 'NA'),
                'pin_code' => $data['pincode'] ?? 'NA',
                'state' => $data['state'] ?? 'West Bengal',
                'last_qualification' => $data['lastqualification'] ?? 'Graduate',
                'pass_out_year' => $data['passoutyear'] ?? '2020',
                'marital_status' => $data['maritalstatus'] ?? 'Single',
                'email_id' => $email,
                'old_uan_number' => $data['olduannumber'] ?? $data['uannumber'] ?? 'NA',
                'old_esic_number' => $data['oldesicnumber'] ?? $data['esicnumber'] ?? null,
                'bank_account_number' => $data['bankaccountnumber'] ?? 'NA',
                'ifsc_code' => $data['ifsccodenumber'] ?? $data['ifsccode'] ?? 'NA',
                'bank_name' => $data['bankname'] ?? 'NA',
                'work_location' => $data['worklocation'] ?? 'Office Location',
                'nth_salary' => $nthSalary,
            ]);

            $seenEmails[] = $email;
            $importedCount++;
            $importedCandidates[] = [
                'identifier' => $employeeId,
                'name' => $fullName,
                'message' => "Successfully imported employee {$employeeId}."
            ];
        }

        fclose($file);

        if ($failedFileHandle) {
            fclose($failedFileHandle);
        }

        // Create audit log entry
        $guard = \Illuminate\Support\Facades\Auth::guard('admin')->check() ? 'admin' : 'staff';
        $user = \Illuminate\Support\Facades\Auth::guard($guard)->user();

        $logDetails = [
            'success' => $importedCandidates,
            'failures' => array_map(function($info) {
                return [
                    'row_or_file' => 'Row ' . $info['row'],
                    'identifier' => $info['email'] ?: 'Unknown',
                    'reasons' => $info['reasons'],
                ];
            }, $failedRowsInfo),
        ];

        AuditLog::create([
            'activity_type' => 'employee_import',
            'performed_by_type' => get_class($user),
            'performed_by_id' => $user->id,
            'performed_by_name' => $user->name,
            'filename' => $request->file('csv_file')->getClientOriginalName(),
            'success_count' => $importedCount,
            'failed_count' => $failedCount,
            'failed_csv_path' => $failedFileName ? 'failed_imports/' . $failedFileName : null,
            'details' => $logDetails,
        ]);

        // Store the result summary in the session to show in popup modal
        $summary = [
            'success_count' => $importedCount,
            'fail_count' => $failedCount,
            'errors' => $failedRowsInfo,
            'failed_file' => $failedFileName,
        ];
        session()->flash('import_summary', $summary);

        if ($failedCount > 0) {
            return redirect()->route('admin.employees.index')
                ->with('warning', "CSV Import completed with some issues. Successfully imported: {$importedCount}, Failed: {$failedCount}.");
        }

        return redirect()->route('admin.employees.index')
            ->with('success', "Successfully imported all {$importedCount} candidates/employees from CSV.");
    }

    public function downloadFailedImport($filename)
    {
        // Simple security checks to prevent directory traversal
        if (str_contains($filename, '..') || str_contains($filename, '/') || str_contains($filename, '\\')) {
            abort(404);
        }

        $filePath = storage_path('app/failed_imports/' . $filename);

        if (!file_exists($filePath)) {
            abort(404, 'File not found or has expired.');
        }

        return response()->download($filePath, 'failed_candidate_records.csv', [
            'Content-Type' => 'text/csv; charset=UTF-8',
        ]);
    }

    public function loginAsEmployee(Employee $employee)
    {
        \Illuminate\Support\Facades\Auth::guard('employee')->login($employee);
        session(['admin_impersonating' => true]);
        return redirect()->route('employee.dashboard')->with('success', 'Logged in as candidate: ' . ($employee->aadhaar_full_name ?? $employee->full_name));
    }

    /*
    |--------------------------------------------------------------------------
    | Companies CRUD
    |--------------------------------------------------------------------------
    */
    public function companiesIndex()
    {
        $companies = Company::withCount('employees')->get();
        return view('admin.companies.index', compact('companies'));
    }

    public function companiesCreate()
    {
        return view('admin.companies.create');
    }

    public function companiesStore(Request $request)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255', 'unique:companies,name'],
            'address' => ['nullable', 'string', 'max:500'],
            'basic' => ['nullable', 'numeric', 'min:0'],
            'hra' => ['nullable', 'numeric', 'min:0'],
            'conveyance' => ['nullable', 'numeric', 'min:0'],
            'medical_allowance' => ['nullable', 'numeric', 'min:0'],
            'sp_allowance' => ['nullable', 'numeric', 'min:0'],
            'bonus' => ['nullable', 'numeric', 'min:0'],
            'employer_pf' => ['nullable', 'numeric', 'min:0'],
            'employer_esic' => ['nullable', 'numeric', 'min:0'],
            'employer_lwf' => ['nullable', 'numeric', 'min:0'],
            'employee_pf' => ['nullable', 'numeric', 'min:0'],
            'employee_esic' => ['nullable', 'numeric', 'min:0'],
            'employee_lwf' => ['nullable', 'numeric', 'min:0'],
            'professional_tax' => ['nullable', 'numeric', 'min:0'],
        ]);

        Company::create($data);

        return redirect()->route('admin.companies.index')
            ->with('success', 'Company registered successfully with salary structure.');
    }

    public function companiesEdit(Company $company)
    {
        return view('admin.companies.edit', compact('company'));
    }

    public function companiesUpdate(Request $request, Company $company)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255', 'unique:companies,name,' . $company->id],
            'address' => ['nullable', 'string', 'max:500'],
            'basic' => ['nullable', 'numeric', 'min:0'],
            'hra' => ['nullable', 'numeric', 'min:0'],
            'conveyance' => ['nullable', 'numeric', 'min:0'],
            'medical_allowance' => ['nullable', 'numeric', 'min:0'],
            'sp_allowance' => ['nullable', 'numeric', 'min:0'],
            'bonus' => ['nullable', 'numeric', 'min:0'],
            'employer_pf' => ['nullable', 'numeric', 'min:0'],
            'employer_esic' => ['nullable', 'numeric', 'min:0'],
            'employer_lwf' => ['nullable', 'numeric', 'min:0'],
            'employee_pf' => ['nullable', 'numeric', 'min:0'],
            'employee_esic' => ['nullable', 'numeric', 'min:0'],
            'employee_lwf' => ['nullable', 'numeric', 'min:0'],
            'professional_tax' => ['nullable', 'numeric', 'min:0'],
        ]);

        $company->update($data);

        return redirect()->route('admin.companies.index')
            ->with('success', 'Company details and salary structure updated successfully.');
    }

    public function companiesDestroy(Company $company)
    {
        $company->delete();
        return redirect()->route('admin.companies.index')
            ->with('success', 'Company deleted successfully.');
    }

    /*
    |--------------------------------------------------------------------------
    | Departments CRUD
    |--------------------------------------------------------------------------
    */
    public function departmentsIndex()
    {
        $departments = Department::withCount('employees')->get();
        return view('admin.departments.index', compact('departments'));
    }

    public function departmentsCreate()
    {
        return view('admin.departments.create');
    }

    public function departmentsStore(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255', 'unique:departments,name'],
        ]);

        Department::create($request->all());

        return redirect()->route('admin.departments.index')
            ->with('success', 'Department created successfully.');
    }

    public function departmentsEdit(Department $department)
    {
        return view('admin.departments.edit', compact('department'));
    }

    public function departmentsUpdate(Request $request, Department $department)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255', 'unique:departments,name,' . $department->id],
        ]);

        $department->update($request->all());

        return redirect()->route('admin.departments.index')
            ->with('success', 'Department updated successfully.');
    }

    public function departmentsDestroy(Department $department)
    {
        $department->delete();
        return redirect()->route('admin.departments.index')
            ->with('success', 'Department deleted successfully.');
    }

    /*
    |--------------------------------------------------------------------------
    | Designations CRUD
    |--------------------------------------------------------------------------
    */
    public function designationsIndex()
    {
        $designations = Designation::withCount('employees')->get();
        return view('admin.designations.index', compact('designations'));
    }

    public function designationsCreate()
    {
        return view('admin.designations.create');
    }

    public function designationsStore(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255', 'unique:designations,name'],
        ]);

        Designation::create($request->all());

        return redirect()->route('admin.designations.index')
            ->with('success', 'Designation created successfully.');
    }

    public function designationsEdit(Designation $designation)
    {
        return view('admin.designations.edit', compact('designation'));
    }

    public function designationsUpdate(Request $request, Designation $designation)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255', 'unique:designations,name,' . $designation->id],
        ]);

        $designation->update($request->all());

        return redirect()->route('admin.designations.index')
            ->with('success', 'Designation updated successfully.');
    }

    public function designationsDestroy(Designation $designation)
    {
        $designation->delete();
        return redirect()->route('admin.designations.index')
            ->with('success', 'Designation deleted successfully.');
    }



    /*
    |--------------------------------------------------------------------------
    | Offer Letter Generation
    |--------------------------------------------------------------------------
    */
    public function showGenerateOfferLetter(Request $request)
    {
        $employees = Employee::with('company')->where('status', '!=', 'terminated')->get();
        $selectedEmployeeId = $request->employee_id;
        $companies = Company::all();
        return view('admin.documents.generate-offer', compact('employees', 'selectedEmployeeId', 'companies'));
    }

    public function generateOfferLetter(Request $request)
    {
        $request->validate([
            'employee_id' => ['required', 'exists:employees,id'],
            'type' => ['required', 'in:internal,external'],
            'salary' => ['nullable', 'numeric'],
            'joining_date' => ['nullable', 'date'],
            'basic' => ['nullable', 'numeric', 'min:0'],
            'hra' => ['nullable', 'numeric', 'min:0'],
            'conveyance' => ['nullable', 'numeric', 'min:0'],
            'medical_allowance' => ['nullable', 'numeric', 'min:0'],
            'sp_allowance' => ['nullable', 'numeric', 'min:0'],
            'bonus' => ['nullable', 'numeric', 'min:0'],
            'employer_pf' => ['nullable', 'numeric', 'min:0'],
            'employer_esic' => ['nullable', 'numeric', 'min:0'],
            'employer_lwf' => ['nullable', 'numeric', 'min:0'],
            'employee_pf' => ['nullable', 'numeric', 'min:0'],
            'employee_esic' => ['nullable', 'numeric', 'min:0'],
            'employee_lwf' => ['nullable', 'numeric', 'min:0'],
            'professional_tax' => ['nullable', 'numeric', 'min:0'],
        ]);

        $employee = Employee::with('company')->findOrFail($request->employee_id);

        $customData = [
            'salary' => $request->salary ?? $employee->salary,
            'joining_date' => $request->joining_date ? date('d-m-Y', strtotime($request->joining_date)) : null,
            'basic' => $request->filled('basic') ? $request->basic : null,
            'hra' => $request->filled('hra') ? $request->hra : null,
            'conveyance' => $request->filled('conveyance') ? $request->conveyance : null,
            'medical_allowance' => $request->filled('medical_allowance') ? $request->medical_allowance : null,
            'sp_allowance' => $request->filled('sp_allowance') ? $request->sp_allowance : null,
            'bonus' => $request->filled('bonus') ? $request->bonus : null,
            'employer_pf' => $request->filled('employer_pf') ? $request->employer_pf : null,
            'employer_esic' => $request->filled('employer_esic') ? $request->employer_esic : null,
            'employer_lwf' => $request->filled('employer_lwf') ? $request->employer_lwf : null,
            'employee_pf' => $request->filled('employee_pf') ? $request->employee_pf : null,
            'employee_esic' => $request->filled('employee_esic') ? $request->employee_esic : null,
            'employee_lwf' => $request->filled('employee_lwf') ? $request->employee_lwf : null,
            'professional_tax' => $request->filled('professional_tax') ? $request->professional_tax : null,
        ];

        // Filter out nulls so service falls back to company defaults when not customized
        $customData = array_filter($customData, fn($v) => !is_null($v));

        // Generate PDF path using service
        $pdfPath = $this->pdfService->generateOfferLetterPdf($employee, $request->type, $customData);

        // Store letter in database
        OfferLetter::create([
            'employee_id' => $employee->id,
            'pdf_path' => $pdfPath,
        ]);

        return redirect()->route('admin.employees.index')
            ->with('success', "Offer letter generated successfully for {$employee->full_name}.");
    }

    public function bulkGenerateSelected(Request $request)
    {
        $request->validate([
            'employee_ids' => ['required', 'array'],
            'employee_ids.*' => ['exists:employees,id'],
            'type' => ['required', 'in:internal,external'],
        ]);

        $employees = Employee::with('company')->whereIn('id', $request->employee_ids)->get();
        $count = 0;

        foreach ($employees as $employee) {
            $customData = [
                'salary' => $employee->salary,
                'joining_date' => $employee->joining_date ? $employee->joining_date->format('d-m-Y') : null,
            ];

            $pdfPath = $this->pdfService->generateOfferLetterPdf($employee, $request->type, $customData);

            OfferLetter::create([
                'employee_id' => $employee->id,
                'pdf_path' => $pdfPath,
            ]);

            $count++;
        }

        return redirect()->route('admin.employees.index')
            ->with('success', "Bulk generated {$count} offer letters successfully.");
    }

    public function generateOfferLettersBulk(Request $request)
    {
        $request->validate([
            'csv_file' => ['required', 'file', 'mimes:csv,txt'],
            'type' => ['required', 'in:internal,external'],
        ]);

        $type = $request->type;
        $path = $request->file('csv_file')->getRealPath();
        $file = fopen($path, 'r');

        // Expecting columns: employee_id, salary, joining_date
        $header = fgetcsv($file);
        
        $count = 0;
        while (($row = fgetcsv($file)) !== FALSE) {
            if (count($row) < 1) continue;

            $data = array_combine($header, $row);
            $employee = Employee::with('company')->where('employee_id', trim($data['employee_id']))->first();

            if ($employee) {
                $customData = [
                    'salary' => $data['salary'] ?? $employee->salary,
                    'joining_date' => isset($data['joining_date']) ? date('d-m-Y', strtotime($data['joining_date'])) : null,
                ];

                $pdfPath = $this->pdfService->generateOfferLetterPdf($employee, $type, $customData);

                OfferLetter::create([
                    'employee_id' => $employee->id,
                    'pdf_path' => $pdfPath,
                ]);

                $count++;
            }
        }
        fclose($file);

        return redirect()->route('admin.employees.index')
            ->with('success', "Bulk generated {$count} offer letters successfully.");
    }

    /*
    |--------------------------------------------------------------------------
    | Payslip Generation
    |--------------------------------------------------------------------------
    */
    public function showGeneratePayslip()
    {
        $employees = Employee::with(['department', 'designationRelation', 'company'])->get();
        $departments = Department::all();
        $designations = Designation::all();
        $companies = Company::all();
        $clientNames = Employee::whereNotNull('client_name')->where('client_name', '!=', '')->distinct()->pluck('client_name');
        $workLocations = Employee::whereNotNull('work_location')->where('work_location', '!=', '')->distinct()->pluck('work_location');
        
        return view('admin.documents.generate-payslip', compact(
            'employees',
            'departments',
            'designations',
            'companies',
            'clientNames',
            'workLocations'
        ));
    }

    public function downloadPrefilledPayslipTemplate(Request $request)
    {
        $request->validate([
            'employee_ids' => ['required', 'array'],
            'employee_ids.*' => ['exists:employees,id'],
            'month' => ['required', 'string'],
            'type' => ['required', 'string', 'in:internal,external'],
        ]);

        $employeeIds = $request->input('employee_ids');
        $month = $request->input('month');
        $type = $request->input('type');

        $employees = Employee::with('company')->whereIn('id', $employeeIds)->get();

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="payslips_prefilled_' . str_replace(' ', '_', strtolower($month)) . '.csv"',
            'Pragma' => 'no-cache',
            'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',
            'Expires' => '0'
        ];

        $callback = function() use ($employees, $month, $type) {
            $file = fopen('php://output', 'w');
            
            // Header columns matching the remote's expected structure
            fputcsv($file, [
                'employee_id',
                'month',
                'type',
                'working_days',
                'net_payable_days',
                'ot_days',
                'pay_mode',
                'basic_salary',
                'hra',
                'medical_allowance',
                'special_allowance',
                'leave_encashment',
                'ot_allowance',
                'professional_tax',
                'provident_fund',
                'esic',
                'company_name'
            ]);

            foreach ($employees as $employee) {
                // CTC reverse calculation
                $ctc = (float) ($employee->salary ?? 0);
                $workingDays = 31;
                $netPayableDays = 31;
                $proRatedCTC = $ctc * ($netPayableDays / $workingDays);

                $basic = max(0.0, ($proRatedCTC - 500.0) / 1.215721);
                $hra = $basic * 0.05;
                $gross = $basic + $hra;
                
                // PF (12% of basic)
                $pf = $basic * 0.12;
                
                // ESIC (0.75% of gross)
                $esic = $gross * 0.0075;
                
                // West Bengal Professional Tax Slab
                $ptax = 0.0;
                if ($gross > 40000) {
                    $ptax = 200.0;
                } else if ($gross > 25000) {
                    $ptax = 150.0;
                } else if ($gross > 15000) {
                    $ptax = 130.0;
                } else if ($gross > 10000) {
                    $ptax = 110.0;
                }

                fputcsv($file, [
                    $employee->employee_id,
                    $month,
                    $type,
                    $workingDays,
                    $netPayableDays,
                    0, // ot_days
                    'Bank Transfer', // pay_mode
                    round($basic, 2),
                    round($hra, 2),
                    0.00, // medical
                    0.00, // special
                    0.00, // leave
                    0.00, // ot_allowance
                    round($ptax, 2),
                    round($pf, 2),
                    round($esic, 2),
                    $employee->company->name ?? 'N/A'
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    public function generatePayslip(Request $request)
    {
        $request->validate([
            'employee_id' => ['required', 'exists:employees,id'],
            'month' => ['required', 'string'],
            'type' => ['required', 'string'], // internal, external
            'working_days' => ['required', 'integer', 'min:1'],
            'net_payable_days' => ['required', 'integer', 'min:0'],
            'ot_days' => ['required', 'integer', 'min:0'],
            'pay_mode' => ['required', 'string'],
            
            'basic_salary' => ['required', 'numeric', 'min:0'],
            'hra' => ['nullable', 'numeric', 'min:0'],
            'medical_allowance' => ['nullable', 'numeric', 'min:0'],
            'special_allowance' => ['nullable', 'numeric', 'min:0'],
            'leave_encashment' => ['nullable', 'numeric', 'min:0'],
            'ot_allowance' => ['nullable', 'numeric', 'min:0'],
            
            'professional_tax' => ['nullable', 'numeric', 'min:0'],
            'provident_fund' => ['nullable', 'numeric', 'min:0'],
            'esic' => ['nullable', 'numeric', 'min:0'],
        ]);

        $employee = Employee::findOrFail($request->employee_id);
        
        $extra = [
            'working_days' => (int) $request->working_days,
            'net_payable_days' => (int) $request->net_payable_days,
            'ot_days' => (int) $request->ot_days,
            'pay_mode' => $request->pay_mode,
            'hra' => (float) ($request->hra ?? 0),
            'medical_allowance' => (float) ($request->medical_allowance ?? 0),
            'special_allowance' => (float) ($request->special_allowance ?? 0),
            'leave_encashment' => (float) ($request->leave_encashment ?? 0),
            'ot_allowance' => (float) ($request->ot_allowance ?? 0),
            'professional_tax' => (float) ($request->professional_tax ?? 0),
            'provident_fund' => (float) ($request->provident_fund ?? 0),
            'esic' => (float) ($request->esic ?? 0),
        ];

        $basic = (float) $request->basic_salary;
        $totalEarnings = $basic + $extra['hra'] + $extra['medical_allowance'] + $extra['special_allowance'] + $extra['leave_encashment'] + $extra['ot_allowance'];
        $totalDeductions = $extra['professional_tax'] + $extra['provident_fund'] + $extra['esic'];
        $net = $totalEarnings - $totalDeductions;

        $pdfPath = $this->pdfService->generatePayslipPdf(
            $employee,
            $request->month,
            $basic,
            $extra['hra'],
            $totalDeductions,
            $net,
            $request->type,
            $extra
        );

        $payslipData = array_merge([
            'employee_id' => $employee->id,
            'month' => $request->month,
            'basic_salary' => $basic,
            'allowances' => $totalEarnings - $basic,
            'deductions' => $totalDeductions,
            'net_salary' => $net,
            'type' => $request->type,
            'pdf_path' => $pdfPath,
        ], $extra);

        Payslip::create($payslipData);

        return redirect()->route('admin.employees.index')
            ->with('success', "Payslip generated successfully for {$employee->full_name} for {$request->month}.");
    }

    public function generatePayslipsBulk(Request $request)
    {
        $request->validate([
            'csv_file' => ['required', 'file', 'mimes:csv,txt'],
        ]);

        $path = $request->file('csv_file')->getRealPath();
        $file = fopen($path, 'r');

        $header = fgetcsv($file);
        if (!$header) {
            fclose($file);
            return redirect()->route('admin.employees.index')->with('error', 'Empty CSV file uploaded.');
        }

        $successCount = 0;
        $failedCount = 0;
        $successLogs = [];
        $failedLogs = [];
        $rowNum = 1;

        while (($row = fgetcsv($file)) !== FALSE) {
            $rowNum++;
            if (empty(array_filter($row))) continue;

            if (count($row) !== count($header)) {
                $failedCount++;
                $failedLogs[] = [
                    'row_or_file' => 'Row ' . $rowNum,
                    'identifier' => 'Unknown',
                    'reasons' => ["Column count mismatch. Expected " . count($header) . ", got " . count($row)]
                ];
                continue;
            }

            $data = array_combine($header, $row);
            $employeeId = trim($data['employee_id'] ?? '');
            $employee = Employee::where('employee_id', $employeeId)->first();

            if (!$employee) {
                $failedCount++;
                $failedLogs[] = [
                    'row_or_file' => 'Row ' . $rowNum,
                    'identifier' => $employeeId,
                    'reasons' => ["No candidate/employee found with ID '{$employeeId}'"]
                ];
                continue;
            }

            try {
                $month = $data['month'] ?? '';
                $type = $data['type'] ?? 'external';
                
                $basic = (float) ($data['basic_salary'] ?? 0);
                $extra = [
                    'working_days' => (int) ($data['working_days'] ?? 31),
                    'net_payable_days' => (int) ($data['net_payable_days'] ?? 31),
                    'ot_days' => (int) ($data['ot_days'] ?? 0),
                    'pay_mode' => $data['pay_mode'] ?? 'Bank Transfer',
                    'hra' => (float) ($data['hra'] ?? 0),
                    'medical_allowance' => (float) ($data['medical_allowance'] ?? 0),
                    'special_allowance' => (float) ($data['special_allowance'] ?? 0),
                    'leave_encashment' => (float) ($data['leave_encashment'] ?? 0),
                    'ot_allowance' => (float) ($data['ot_allowance'] ?? 0),
                    'professional_tax' => (float) ($data['professional_tax'] ?? 0),
                    'provident_fund' => (float) ($data['provident_fund'] ?? 0),
                    'esic' => (float) ($data['esic'] ?? 0),
                ];

                $totalEarnings = $basic + $extra['hra'] + $extra['medical_allowance'] + $extra['special_allowance'] + $extra['leave_encashment'] + $extra['ot_allowance'];
                $totalDeductions = $extra['professional_tax'] + $extra['provident_fund'] + $extra['esic'];
                $net = $totalEarnings - $totalDeductions;

                $pdfPath = $this->pdfService->generatePayslipPdf(
                    $employee,
                    $month,
                    $basic,
                    $extra['hra'],
                    $totalDeductions,
                    $net,
                    $type,
                    $extra
                );

                $payslipData = array_merge([
                    'employee_id' => $employee->id,
                    'month' => $month,
                    'basic_salary' => $basic,
                    'allowances' => $totalEarnings - $basic,
                    'deductions' => $totalDeductions,
                    'net_salary' => $net,
                    'type' => $type,
                    'pdf_path' => $pdfPath,
                ], $extra);

                Payslip::create($payslipData);

                $successCount++;
                $successLogs[] = [
                    'identifier' => $employee->employee_id,
                    'name' => $employee->full_name,
                    'message' => "Successfully generated payslip for {$month}."
                ];
            } catch (\Exception $e) {
                $failedCount++;
                $failedLogs[] = [
                    'row_or_file' => 'Row ' . $rowNum,
                    'identifier' => $employee->employee_id,
                    'reasons' => [$e->getMessage()]
                ];
            }
        }
        fclose($file);

        // Create audit log entry
        $guard = \Illuminate\Support\Facades\Auth::guard('admin')->check() ? 'admin' : 'staff';
        $user = \Illuminate\Support\Facades\Auth::guard($guard)->user();

        AuditLog::create([
            'activity_type' => 'bulk_payslip_generate',
            'performed_by_type' => get_class($user),
            'performed_by_id' => $user->id,
            'performed_by_name' => $user->name,
            'filename' => $request->file('csv_file')->getClientOriginalName(),
            'success_count' => $successCount,
            'failed_count' => $failedCount,
            'details' => [
                'success' => $successLogs,
                'failures' => $failedLogs,
            ],
        ]);

        if ($failedCount > 0) {
            return redirect()->route('admin.employees.index')
                ->with('warning', "Successfully generated {$successCount} payslips from CSV. {$failedCount} rows skipped due to column formatting or missing data issues.");
        }

        return redirect()->route('admin.employees.index')
            ->with('success', "Successfully bulk generated all {$successCount} payslips from CSV.");
    }

    public function downloadPayslipTemplate()
    {
        $headers = [
            "Content-type"        => "text/csv",
            "Content-Disposition" => "attachment; filename=payslip_bulk_template.csv",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0"
        ];

        $columns = [
            'employee_id',
            'month',
            'type',
            'working_days',
            'net_payable_days',
            'ot_days',
            'pay_mode',
            'basic_salary',
            'hra',
            'medical_allowance',
            'special_allowance',
            'leave_encashment',
            'ot_allowance',
            'professional_tax',
            'provident_fund',
            'esic'
        ];

        $callback = function() use($columns) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $columns);
            
            // Add a sample row
            fputcsv($file, [
                'EMP-2026-0001',
                'August 2026',
                'external',
                '31',
                '31',
                '0',
                'Bank Transfer',
                '15000.00',
                '750.00',
                '0.00',
                '0.00',
                '0.00',
                '0.00',
                '130.00',
                '1800.00',
                '118.13'
            ]);
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /*
    |--------------------------------------------------------------------------
    | Bulletins Notice Board
    |--------------------------------------------------------------------------
    */
    public function bulletinsIndex()
    {
        $bulletins = Bulletin::latest()->get();
        return view('admin.bulletins.index', compact('bulletins'));
    }

    public function bulletinsStore(Request $request)
    {
        $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'content' => ['required', 'string'],
        ]);

        Bulletin::create([
            'title' => $request->title,
            'content' => $request->content,
            'is_active' => true,
        ]);

        return back()->with('success', 'Announcement published successfully.');
    }

    public function bulletinsUpdate(Request $request, Bulletin $bulletin)
    {
        $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'content' => ['required', 'string'],
        ]);

        $bulletin->update([
            'title' => $request->title,
            'content' => $request->content,
            'is_active' => $request->boolean('is_active', true),
        ]);

        return back()->with('success', 'Announcement updated successfully.');
    }

    public function bulletinsDestroy(Bulletin $bulletin)
    {
        $bulletin->delete();
        return back()->with('success', 'Announcement deleted successfully.');
    }

    /*
    |--------------------------------------------------------------------------
    | CMS Site Content
    |--------------------------------------------------------------------------
    */
    public function cmsIndex()
    {
        $content = SiteContent::all()->pluck('value', 'key')->toArray();
        return view('admin.cms.index', compact('content'));
    }

    public function cmsUpdate(Request $request)
    {
        $data = $request->validate([
            'home_banner_title' => ['required', 'string', 'max:255'],
            'home_banner_subtitle' => ['required', 'string'],
            'about_us_text' => ['required', 'string'],
            'contact_phone' => ['required', 'string', 'max:255'],
            'contact_email' => ['required', 'email', 'max:255'],
            'contact_address' => ['required', 'string', 'max:500'],
        ]);

        foreach ($data as $key => $val) {
            SiteContent::updateOrCreate(
                ['key' => $key],
                ['value' => $val]
            );
        }

        return back()->with('success', 'CMS marketing contents updated successfully.');
    }
    /*
    |--------------------------------------------------------------------------
    | Inquiries Inbox
    |--------------------------------------------------------------------------
    */
    public function inquiriesIndex(Request $request)
    {
        $query = Inquiry::query();

        if ($request->filled('start_date')) {
            $query->whereDate('created_at', '>=', $request->start_date);
        }

        if ($request->filled('end_date')) {
            $query->whereDate('created_at', '<=', $request->end_date);
        }

        $inquiries = $query->latest()->paginate(10)->withQueryString();
        return view('admin.inquiries.index', compact('inquiries'));
    }

    public function inquiriesExport(Request $request)
    {
        $query = Inquiry::query();

        if ($request->filled('start_date')) {
            $query->whereDate('created_at', '>=', $request->start_date);
        }

        if ($request->filled('end_date')) {
            $query->whereDate('created_at', '<=', $request->end_date);
        }

        $inquiries = $query->latest()->get();

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="inquiries_export.csv"',
        ];

        $callback = function() use ($inquiries) {
            $file = fopen('php://output', 'w');
            
            fputcsv($file, [
                'ID',
                'Name',
                'Email',
                'Phone',
                'Subject',
                'Message',
                'Status',
                'Received At'
            ]);

            foreach ($inquiries as $inq) {
                fputcsv($file, [
                    $inq->id,
                    $inq->name,
                    $inq->email,
                    $inq->phone,
                    $inq->subject,
                    $inq->message,
                    $inq->status,
                    $inq->created_at->format('Y-m-d H:i:s'),
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    public function inquiriesReply(Request $request, Inquiry $inquiry)
    {
        // Simple reply action (mark read and record status message)
        $inquiry->status = 'replied';
        $inquiry->save();

        return back()->with('success', 'Marked inquiry as replied.');
    }

    /*
    |--------------------------------------------------------------------------
    | Admin Profile & Password Security
    |--------------------------------------------------------------------------
    */
    public function profileShow()
    {
        $guard = \Illuminate\Support\Facades\Auth::guard('admin')->check() ? 'admin' : 'staff';
        $admin = \Illuminate\Support\Facades\Auth::guard($guard)->user();
        return view('admin.profile', compact('admin'));
    }

    public function profileUpdate(Request $request)
    {
        $guard = \Illuminate\Support\Facades\Auth::guard('admin')->check() ? 'admin' : 'staff';
        $admin = \Illuminate\Support\Facades\Auth::guard($guard)->user();
        $table = $guard === 'admin' ? 'admins' : 'staff';

        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:' . $table . ',email,' . $admin->id],
        ]);

        $admin->name = $request->name;
        $admin->email = $request->email;
        $admin->save();

        return redirect()->route('admin.profile')
            ->with('success', 'Profile ID (Name & Email) updated successfully.');
    }

    public function passwordUpdate(Request $request)
    {
        $guard = \Illuminate\Support\Facades\Auth::guard('admin')->check() ? 'admin' : 'staff';
        $admin = \Illuminate\Support\Facades\Auth::guard($guard)->user();

        $request->validate([
            'current_password' => ['required', 'string'],
            'new_password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        if (!Hash::check($request->current_password, $admin->password)) {
            return back()->withErrors(['current_password' => 'The current password provided is incorrect.'])
                ->with('error', 'Current password verification failed.');
        }

        $admin->password = Hash::make($request->new_password);
        $admin->save();

        return redirect()->route('admin.profile')
            ->with('success', 'Security password changed successfully.');
    }

    public function showBulkUpload()
    {
        return view('admin.documents.bulk-upload');
    }

    public function bulkUpload(Request $request)
    {
        $request->validate([
            'doc_type' => ['required', 'string', 'in:offer_letter,payslip'],
            'files' => ['required', 'array', 'min:1'],
            'files.*' => ['file', 'mimes:pdf'],
            'month' => ['required_if:doc_type,payslip', 'nullable', 'date_format:Y-m'],
        ]);

        $docType = $request->doc_type;
        $files = $request->file('files');
        
        $month = null;
        if ($request->filled('month')) {
            $month = \Carbon\Carbon::parse($request->month)->format('F Y');
        }

        $successCount = 0;
        $failedCount = 0;
        $messages = [];
        $successLogs = [];
        $failedLogs = [];

        foreach ($files as $file) {
            $originalName = $file->getClientOriginalName();
            $employeeId = pathinfo($originalName, PATHINFO_FILENAME);
            $employeeId = trim($employeeId);

            // Match employee by employee_id
            $employee = Employee::where('employee_id', $employeeId)->first();

            if (!$employee) {
                $failedCount++;
                $messages[] = "Skipped '{$originalName}': No employee found with ID '{$employeeId}'.";
                $failedLogs[] = [
                    'row_or_file' => $originalName,
                    'identifier' => $employeeId,
                    'reasons' => ["No employee found with ID '{$employeeId}'"]
                ];
                continue;
            }

            try {
                if ($docType === 'offer_letter') {
                    // Create storage directory if not exists
                    $dir = public_path('storage/offer_letters');
                    if (!file_exists($dir)) {
                        mkdir($dir, 0777, true);
                    }
                    $fileName = 'Offer_Letter_' . $employee->employee_id . '_' . time() . '_' . rand(1000, 9999) . '.pdf';
                    $file->move($dir, $fileName);
                    $pdfPath = 'storage/offer_letters/' . $fileName;

                    OfferLetter::create([
                        'employee_id' => $employee->id,
                        'pdf_path' => $pdfPath,
                    ]);

                    $successCount++;
                    $successLogs[] = [
                        'identifier' => $employee->employee_id,
                        'name' => $employee->full_name,
                        'message' => "Successfully uploaded offer letter PDF '{$originalName}'"
                    ];
                } else {
                    // Create storage directory if not exists
                    $dir = public_path('storage/payslips');
                    if (!file_exists($dir)) {
                        mkdir($dir, 0777, true);
                    }
                    $fileName = 'Payslip_' . $employee->employee_id . '_' . str_replace(' ', '_', $month) . '_' . time() . '_' . rand(1000, 9999) . '.pdf';
                    $file->move($dir, $fileName);
                    $pdfPath = 'storage/payslips/' . $fileName;

                    // Resolve basic, allowances, deductions, net salary from employee profile or defaults
                    $basic = 0.00;
                    $allowances = 0.00;
                    $deductions = 0.00;
                    $net = 0.00;
                    $hra = 0.00;
                    $spAllowance = 0.00;
                    $employeePf = 0.00;
                    $employeeEsic = 0.00;
                    $pTax = 0.00;

                    if ($employee->salary) {
                        $salaryVal = (float) $employee->salary;
                        $bonus = ($salaryVal > 5000) ? 500.00 : 0.00;
                        $basic = round(($salaryVal - $bonus) / 1.215721, 2);
                        $hra = round($basic * 0.05, 2);
                        $gross = $basic + $hra;
                        
                        $employeePf = round($basic * 0.12, 2);
                        $employeeEsic = round($gross * 0.00793, 2);
                        $pTax = ($gross > 15000) ? 150 : (($gross > 10000) ? 110 : 0);
                        
                        $deductions = $employeePf + $employeeEsic + $pTax;
                        $allowances = $gross - $basic;
                        $net = $gross - $deductions + $bonus;
                    }

                    Payslip::create([
                        'employee_id' => $employee->id,
                        'month' => $month,
                        'basic_salary' => $basic,
                        'allowances' => $allowances,
                        'deductions' => $deductions,
                        'net_salary' => $net,
                        'type' => ($employee->company_id == 1) ? 'internal' : 'external',
                        'pdf_path' => $pdfPath,
                        'working_days' => 31,
                        'net_payable_days' => 31,
                        'ot_days' => 0,
                        'pay_mode' => 'Bank Transfer',
                        'hra' => $hra,
                        'provident_fund' => $employeePf,
                        'esic' => $employeeEsic,
                        'professional_tax' => $pTax,
                    ]);

                    $successCount++;
                    $successLogs[] = [
                        'identifier' => $employee->employee_id,
                        'name' => $employee->full_name,
                        'message' => "Successfully uploaded payslip PDF '{$originalName}'"
                    ];
                }
            } catch (\Exception $e) {
                $failedCount++;
                $messages[] = "Failed to upload '{$originalName}': " . $e->getMessage();
                $failedLogs[] = [
                    'row_or_file' => $originalName,
                    'identifier' => $employeeId,
                    'reasons' => [$e->getMessage()]
                ];
            }
        }

        // Create audit log entry
        $guard = \Illuminate\Support\Facades\Auth::guard('admin')->check() ? 'admin' : 'staff';
        $user = \Illuminate\Support\Facades\Auth::guard($guard)->user();

        AuditLog::create([
            'activity_type' => $docType === 'offer_letter' ? 'bulk_offer_letter_upload' : 'bulk_payslip_upload',
            'performed_by_type' => get_class($user),
            'performed_by_id' => $user->id,
            'performed_by_name' => $user->name,
            'filename' => "Uploaded " . count($files) . " PDF file(s)",
            'success_count' => $successCount,
            'failed_count' => $failedCount,
            'details' => [
                'success' => $successLogs,
                'failures' => $failedLogs,
            ],
        ]);

        $summary = [
            'success_count' => $successCount,
            'fail_count' => $failedCount,
            'errors' => $messages,
        ];
        
        session()->flash('bulk_upload_summary', $summary);

        if ($failedCount > 0) {
            return redirect()->route('admin.documents.bulk-upload')
                ->with('warning', "Bulk upload completed. Successfully processed: {$successCount}, Skipped/Failed: {$failedCount}.");
        }

        return redirect()->route('admin.documents.bulk-upload')
            ->with('success', "Successfully uploaded and assigned {$successCount} document(s).");
    }

    public function auditLogsIndex(Request $request)
    {
        $query = AuditLog::latest();

        if ($request->filled('activity_type')) {
            $query->where('activity_type', $request->activity_type);
        }

        if ($request->filled('start_date')) {
            $query->whereDate('created_at', '>=', $request->start_date);
        }

        if ($request->filled('end_date')) {
            $query->whereDate('created_at', '<=', $request->end_date);
        }

        $logs = $query->paginate(15)->withQueryString();

        return view('admin.audit-logs.index', compact('logs'));
    }

    public function auditLogsShowJson(AuditLog $auditLog)
    {
        return response()->json($auditLog->details);
    }

    public function downloadFailedImportFromLog(AuditLog $auditLog)
    {
        $filename = $auditLog->failed_csv_path;
        if (!$filename) {
            abort(404, 'No failed CSV file associated with this log.');
        }

        $filename = basename($filename);
        $filePath = storage_path('app/failed_imports/' . $filename);

        if (!file_exists($filePath)) {
            abort(404, 'Failed CSV file not found or has expired.');
        }

        return response()->download($filePath, 'failed_candidate_records.csv', [
            'Content-Type' => 'text/csv; charset=UTF-8',
        ]);
    }
}
