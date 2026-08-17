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
        ];

        $recentEmployees = Employee::latest()->take(5)->get();
        $recentInquiries = Inquiry::latest()->take(5)->get();

        return view('admin.dashboard', compact('stats', 'recentEmployees', 'recentInquiries'));
    }

    /*
    |--------------------------------------------------------------------------
    | Employees CRUD & Import
    |--------------------------------------------------------------------------
    */
    public function employeesIndex(Request $request)
    {
        $query = Employee::with(['department', 'designation', 'company', 'offerLetters']);

        // Search filter
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('first_name', 'like', "%$search%")
                  ->orWhere('last_name', 'like', "%$search%")
                  ->orWhere('email', 'like', "%$search%")
                  ->orWhere('employee_id', 'like', "%$search%");
            });
        }

        // Status filter
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Offer letter filter
        if ($request->filled('offer_letter_status')) {
            if ($request->offer_letter_status === 'generated') {
                $query->has('offerLetters');
            } elseif ($request->offer_letter_status === 'not_generated') {
                $query->doesntHave('offerLetters');
            }
        }

        $employees = $query->latest()->paginate(10)->withQueryString();
        $departments = Department::all();
        $designations = Designation::all();
        $companies = Company::all();
        return view('admin.employees.index', compact('employees', 'departments', 'designations', 'companies'));
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
            'first_name' => ['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'unique:employees,email'],
            'phone' => ['nullable', 'string', 'max:20'],
            'status' => ['required', 'string'],
            'department_id' => ['nullable', 'exists:departments,id'],
            'designation_id' => ['nullable', 'exists:designations,id'],
            'company_id' => ['nullable', 'exists:companies,id'],
            'joining_date' => ['nullable', 'date'],
            'salary' => ['nullable', 'numeric', 'min:0'],

            // Aadhaar and KYC Attributes
            'aadhaar_full_name' => ['nullable', 'string', 'max:255'],
            'aadhaar_number' => ['nullable', 'string', 'max:20'],
            'pan_number' => ['nullable', 'string', 'max:20'],
            'voter_id_number' => ['nullable', 'string', 'max:20'],
            'prefix' => ['nullable', 'string', 'max:10'],
            'father_name_aadhaar' => ['nullable', 'string', 'max:255'],
            'mother_name_aadhaar' => ['nullable', 'string', 'max:255'],
            'gender' => ['nullable', 'string', 'max:20'],
            'dob' => ['nullable', 'date'],
            'mother_tongue' => ['nullable', 'string', 'max:100'],
            'aadhaar_address' => ['nullable', 'string'],
            'landmark' => ['nullable', 'string', 'max:255'],
            'contact_number' => ['nullable', 'string', 'max:20'],
            'city' => ['nullable', 'string', 'max:100'],
            'emergency_contact_number' => ['nullable', 'string', 'max:20'],
            'pin_code' => ['nullable', 'string', 'max:10'],
            'state' => ['nullable', 'string', 'max:100'],
            'last_qualification' => ['nullable', 'string', 'max:255'],
            'pass_out_year' => ['nullable', 'string', 'max:10'],
            'marital_status' => ['nullable', 'string', 'max:50'],
            'email_id' => ['nullable', 'string', 'email', 'max:255'],
            'old_uan_number' => ['nullable', 'string', 'max:50'],
            'old_esic_number' => ['nullable', 'string', 'max:50'],
            'bank_account_number' => ['nullable', 'string', 'max:50'],
            'ifsc_code' => ['nullable', 'string', 'max:20'],
            'bank_name' => ['nullable', 'string', 'max:255'],
            'client_name' => ['nullable', 'string', 'max:255'],
            'work_location' => ['nullable', 'string', 'max:255'],
            'designation' => ['nullable', 'string', 'max:255'],
            'nth_salary' => ['nullable', 'numeric', 'min:0'],

            // Documents
            'doc_aadhaar_front' => ['nullable', 'file', 'mimes:jpeg,jpg,png,pdf', 'max:5120'],
            'doc_aadhaar_back' => ['nullable', 'file', 'mimes:jpeg,jpg,png,pdf', 'max:5120'],
            'doc_pan' => ['nullable', 'file', 'mimes:jpeg,jpg,png,pdf', 'max:5120'],
            'doc_voter_front' => ['nullable', 'file', 'mimes:jpeg,jpg,png,pdf', 'max:5120'],
            'doc_voter_back' => ['nullable', 'file', 'mimes:jpeg,jpg,png,pdf', 'max:5120'],
            'doc_qualification_marksheet' => ['nullable', 'file', 'mimes:jpeg,jpg,png,pdf', 'max:5120'],
            'doc_qualification_certificate' => ['nullable', 'file', 'mimes:jpeg,jpg,png,pdf', 'max:5120'],
            'doc_photo' => ['nullable', 'image', 'mimes:jpeg,jpg,png', 'max:5120'],
            'doc_bank_passbook' => ['nullable', 'file', 'mimes:jpeg,jpg,png,pdf', 'max:5120'],
        ]);

        $documentFields = [
            'doc_aadhaar_front',
            'doc_aadhaar_back',
            'doc_pan',
            'doc_voter_front',
            'doc_voter_back',
            'doc_qualification_marksheet',
            'doc_qualification_certificate',
            'doc_photo',
            'doc_bank_passbook',
        ];

        foreach ($documentFields as $field) {
            if ($request->hasFile($field)) {
                $file = $request->file($field);
                $fileName = time() . '_' . $field . '_' . $file->getClientOriginalName();
                $file->move(public_path('storage/documents'), $fileName);
                $validated[$field] = 'storage/documents/' . $fileName;
            } else {
                $validated[$field] = null;
            }
        }

        // Auto-generate Employee ID: EMP-YYYY-XXXX
        $year = date('Y');
        $prefix = "EMP-{$year}-";
        $lastEmployee = Employee::where('employee_id', 'like', $prefix . '%')
            ->orderBy('employee_id', 'desc')
            ->first();

        if ($lastEmployee) {
            $lastNum = (int) substr($lastEmployee->employee_id, -4);
            $nextNum = str_pad($lastNum + 1, 4, '0', STR_PAD_LEFT);
        } else {
            $nextNum = '0001';
        }
        $employeeId = $prefix . $nextNum;

        // Auto-generate random temp password
        $plainPassword = strtolower($request->first_name) . rand(1000, 9999);

        $validated['employee_id'] = $employeeId;
        $validated['password'] = Hash::make($plainPassword);
        $validated['is_password_changed'] = false; // Forces change on login

        Employee::create($validated);

        return redirect()->route('admin.employees.index')
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
            'first_name' => ['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'unique:employees,email,' . $employee->id],
            'phone' => ['nullable', 'string', 'max:20'],
            'status' => ['required', 'string'],
            'department_id' => ['nullable', 'exists:departments,id'],
            'designation_id' => ['nullable', 'exists:designations,id'],
            'company_id' => ['nullable', 'exists:companies,id'],
            'joining_date' => ['nullable', 'date'],
            'salary' => ['nullable', 'numeric', 'min:0'],

            // Aadhaar and KYC Attributes
            'aadhaar_full_name' => ['nullable', 'string', 'max:255'],
            'aadhaar_number' => ['nullable', 'string', 'max:20'],
            'pan_number' => ['nullable', 'string', 'max:20'],
            'voter_id_number' => ['nullable', 'string', 'max:20'],
            'prefix' => ['nullable', 'string', 'max:10'],
            'father_name_aadhaar' => ['nullable', 'string', 'max:255'],
            'mother_name_aadhaar' => ['nullable', 'string', 'max:255'],
            'gender' => ['nullable', 'string', 'max:20'],
            'dob' => ['nullable', 'date'],
            'mother_tongue' => ['nullable', 'string', 'max:100'],
            'aadhaar_address' => ['nullable', 'string'],
            'landmark' => ['nullable', 'string', 'max:255'],
            'contact_number' => ['nullable', 'string', 'max:20'],
            'city' => ['nullable', 'string', 'max:100'],
            'emergency_contact_number' => ['nullable', 'string', 'max:20'],
            'pin_code' => ['nullable', 'string', 'max:10'],
            'state' => ['nullable', 'string', 'max:100'],
            'last_qualification' => ['nullable', 'string', 'max:255'],
            'pass_out_year' => ['nullable', 'string', 'max:10'],
            'marital_status' => ['nullable', 'string', 'max:50'],
            'email_id' => ['nullable', 'string', 'email', 'max:255'],
            'old_uan_number' => ['nullable', 'string', 'max:50'],
            'old_esic_number' => ['nullable', 'string', 'max:50'],
            'bank_account_number' => ['nullable', 'string', 'max:50'],
            'ifsc_code' => ['nullable', 'string', 'max:20'],
            'bank_name' => ['nullable', 'string', 'max:255'],
            'client_name' => ['nullable', 'string', 'max:255'],
            'work_location' => ['nullable', 'string', 'max:255'],
            'designation' => ['nullable', 'string', 'max:255'],
            'nth_salary' => ['nullable', 'numeric', 'min:0'],

            // Documents
            'doc_aadhaar_front' => ['nullable', 'file', 'mimes:jpeg,jpg,png,pdf', 'max:5120'],
            'doc_aadhaar_back' => ['nullable', 'file', 'mimes:jpeg,jpg,png,pdf', 'max:5120'],
            'doc_pan' => ['nullable', 'file', 'mimes:jpeg,jpg,png,pdf', 'max:5120'],
            'doc_voter_front' => ['nullable', 'file', 'mimes:jpeg,jpg,png,pdf', 'max:5120'],
            'doc_voter_back' => ['nullable', 'file', 'mimes:jpeg,jpg,png,pdf', 'max:5120'],
            'doc_qualification_marksheet' => ['nullable', 'file', 'mimes:jpeg,jpg,png,pdf', 'max:5120'],
            'doc_qualification_certificate' => ['nullable', 'file', 'mimes:jpeg,jpg,png,pdf', 'max:5120'],
            'doc_photo' => ['nullable', 'image', 'mimes:jpeg,jpg,png', 'max:5120'],
            'doc_bank_passbook' => ['nullable', 'file', 'mimes:jpeg,jpg,png,pdf', 'max:5120'],
        ]);

        $documentFields = [
            'doc_aadhaar_front',
            'doc_aadhaar_back',
            'doc_pan',
            'doc_voter_front',
            'doc_voter_back',
            'doc_qualification_marksheet',
            'doc_qualification_certificate',
            'doc_photo',
            'doc_bank_passbook',
        ];

        foreach ($documentFields as $field) {
            if ($request->hasFile($field)) {
                $file = $request->file($field);
                $fileName = time() . '_' . $field . '_' . $file->getClientOriginalName();
                $file->move(public_path('storage/documents'), $fileName);
                $validated[$field] = 'storage/documents/' . $fileName;
            } else {
                unset($validated[$field]);
            }
        }

        $employee->update($validated);

        return redirect()->route('admin.employees.index')
            ->with('success', "Employee {$employee->employee_id} updated successfully.");
    }

    public function employeesDestroy(Employee $employee)
    {
        $employee->delete();
        return redirect()->route('admin.employees.index')
            ->with('success', "Employee deleted successfully.");
    }

    public function downloadEmployeeTemplate(Request $request)
    {
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="employee_import_template.csv"',
        ];

        $callback = function() use ($request) {
            $file = fopen('php://output', 'w');
            
            // CSV headers
            fputcsv($file, [
                'first_name',
                'last_name',
                'email',
                'phone',
                'status',
                'salary',
                'joining_date',
                'company',
                'department',
                'designation'
            ]);

            // CSV row data prefilled with selected parameters
            fputcsv($file, [
                'John',
                'Doe',
                'john.doe@example.com',
                '+91 99999 88888',
                'active',
                '25000',
                date('Y-m-d'),
                $request->query('company', ''),
                $request->query('department', ''),
                $request->query('designation', '')
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

        // Parse header
        $header = fgetcsv($file);
        
        $importedCount = 0;
        $year = date('Y');
        $prefix = "EMP-{$year}-";

        while (($row = fgetcsv($file)) !== FALSE) {
            if (count($row) < 3) continue;

            $data = array_combine($header, $row);

            // Validate duplicate emails
            if (Employee::where('email', $data['email'])->exists()) {
                continue;
            }

            // Get next ID
            $lastEmployee = Employee::where('employee_id', 'like', $prefix . '%')
                ->orderBy('employee_id', 'desc')
                ->first();

            if ($lastEmployee) {
                $lastNum = (int) substr($lastEmployee->employee_id, -4);
                $nextNum = str_pad($lastNum + 1, 4, '0', STR_PAD_LEFT);
            } else {
                $nextNum = '0001';
            }
            $employeeId = $prefix . $nextNum;

            // Generate password
            $plainPassword = strtolower($data['first_name']) . rand(100, 999);

            // Match department / designation / company or keep null
            $dept = Department::firstOrCreate(['name' => trim($data['department'] ?? 'Operations & Logistics')]);
            $desig = Designation::firstOrCreate(['name' => trim($data['designation'] ?? 'Office Assistant')]);
            
            $companyId = null;
            if (!empty($data['company'])) {
                $comp = Company::firstOrCreate(['name' => trim($data['company'])]);
                $companyId = $comp->id;
            }

            Employee::create([
                'employee_id' => $employeeId,
                'first_name' => $data['first_name'],
                'last_name' => $data['last_name'],
                'email' => $data['email'],
                'phone' => $data['phone'] ?? null,
                'password' => Hash::make($plainPassword),
                'status' => $data['status'] ?? 'active',
                'department_id' => $dept->id,
                'designation_id' => $desig->id,
                'company_id' => $companyId,
                'joining_date' => $data['joining_date'] ?? date('Y-m-d'),
                'salary' => $data['salary'] ?? 15000.00,
                'is_password_changed' => false,
            ]);

            $importedCount++;
        }

        fclose($file);

        return redirect()->route('admin.employees.index')
            ->with('success', "Successfully imported {$importedCount} employees from CSV.");
    }

    public function loginAsEmployee(Employee $employee)
    {
        \Illuminate\Support\Facades\Auth::guard('employee')->login($employee);
        return redirect()->route('employee.dashboard')->with('success', 'Logged in as candidate: ' . $employee->full_name);
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
        $request->validate([
            'name' => ['required', 'string', 'max:255', 'unique:companies,name'],
            'address' => ['nullable', 'string', 'max:500'],
        ]);

        Company::create($request->all());

        return redirect()->route('admin.companies.index')
            ->with('success', 'Company registered successfully.');
    }

    public function companiesEdit(Company $company)
    {
        return view('admin.companies.edit', compact('company'));
    }

    public function companiesUpdate(Request $request, Company $company)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255', 'unique:companies,name,' . $company->id],
            'address' => ['nullable', 'string', 'max:500'],
        ]);

        $company->update($request->all());

        return redirect()->route('admin.companies.index')
            ->with('success', 'Company details updated successfully.');
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
        $employees = Employee::where('status', '!=', 'terminated')->get();
        $selectedEmployeeId = $request->employee_id;
        return view('admin.documents.generate-offer', compact('employees', 'selectedEmployeeId'));
    }

    public function generateOfferLetter(Request $request)
    {
        $request->validate([
            'employee_id' => ['required', 'exists:employees,id'],
            'type' => ['required', 'in:internal,external'],
            'salary' => ['nullable', 'numeric'],
            'joining_date' => ['nullable', 'date'],
        ]);

        $employee = Employee::findOrFail($request->employee_id);

        $customData = [
            'salary' => $request->salary ?? $employee->salary,
            'joining_date' => $request->joining_date ? date('d-M-Y', strtotime($request->joining_date)) : null,
        ];

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
            $employee = Employee::where('employee_id', trim($data['employee_id']))->first();

            if ($employee) {
                $customData = [
                    'salary' => $data['salary'] ?? $employee->salary,
                    'joining_date' => isset($data['joining_date']) ? date('d-M-Y', strtotime($data['joining_date'])) : null,
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
        $employees = Employee::where('status', 'active')->get();
        return view('admin.documents.generate-payslip', compact('employees'));
    }

    public function generatePayslip(Request $request)
    {
        $request->validate([
            'employee_id' => ['required', 'exists:employees,id'],
            'month' => ['required', 'string'],
            'basic_salary' => ['required', 'numeric', 'min:0'],
            'allowances' => ['nullable', 'numeric', 'min:0'],
            'deductions' => ['nullable', 'numeric', 'min:0'],
            'type' => ['required', 'string'], // internal, external
        ]);

        $employee = Employee::findOrFail($request->employee_id);
        $basic = (float) $request->basic_salary;
        $allowances = (float) ($request->allowances ?? 0);
        $deductions = (float) ($request->deductions ?? 0);
        $net = $basic + $allowances - $deductions;

        $pdfPath = $this->pdfService->generatePayslipPdf(
            $employee,
            $request->month,
            $basic,
            $allowances,
            $deductions,
            $net,
            $request->type
        );

        Payslip::create([
            'employee_id' => $employee->id,
            'month' => $request->month,
            'basic_salary' => $basic,
            'allowances' => $allowances,
            'deductions' => $deductions,
            'net_salary' => $net,
            'type' => $request->type,
            'pdf_path' => $pdfPath,
        ]);

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

        // Columns: employee_id, month, basic_salary, allowances, deductions, type
        $header = fgetcsv($file);

        $count = 0;
        while (($row = fgetcsv($file)) !== FALSE) {
            if (count($row) < 4) continue;

            $data = array_combine($header, $row);
            $employee = Employee::where('employee_id', trim($data['employee_id']))->first();

            if ($employee) {
                $basic = (float) $data['basic_salary'];
                $allowances = (float) ($data['allowances'] ?? 0);
                $deductions = (float) ($data['deductions'] ?? 0);
                $net = $basic + $allowances - $deductions;
                $type = $data['type'] ?? 'external';

                $pdfPath = $this->pdfService->generatePayslipPdf(
                    $employee,
                    $data['month'],
                    $basic,
                    $allowances,
                    $deductions,
                    $net,
                    $type
                );

                Payslip::create([
                    'employee_id' => $employee->id,
                    'month' => $data['month'],
                    'basic_salary' => $basic,
                    'allowances' => $allowances,
                    'deductions' => $deductions,
                    'net_salary' => $net,
                    'type' => $type,
                    'pdf_path' => $pdfPath,
                ]);

                $count++;
            }
        }
        fclose($file);

        return redirect()->route('admin.employees.index')
            ->with('success', "Successfully bulk generated {$count} payslips from CSV.");
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
        $data = $request->except('_token');

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
    public function inquiriesIndex()
    {
        $inquiries = Inquiry::latest()->paginate(10);
        return view('admin.inquiries.index', compact('inquiries'));
    }

    public function inquiriesReply(Request $request, Inquiry $inquiry)
    {
        // Simple reply action (mark read and record status message)
        $inquiry->status = 'replied';
        $inquiry->save();

        return back()->with('success', 'Marked inquiry as replied.');
    }

    public function bulkGenerateSelected(Request $request)
    {
        $request->validate([
            'employee_ids' => ['required', 'array'],
            'employee_ids.*' => ['exists:employees,id'],
            'type' => ['required', 'in:internal,external'],
        ]);

        $type = $request->type;
        $count = 0;

        foreach ($request->employee_ids as $empId) {
            $employee = Employee::find($empId);
            if ($employee) {
                // Ensure candidate only gets one offer letter
                if ($employee->offerLetters()->exists()) {
                    continue;
                }

                $customData = [
                    'salary' => $employee->salary,
                    'joining_date' => $employee->joining_date ? $employee->joining_date->format('d-M-Y') : null,
                ];

                $pdfPath = $this->pdfService->generateOfferLetterPdf($employee, $type, $customData);

                OfferLetter::create([
                    'employee_id' => $employee->id,
                    'pdf_path' => $pdfPath,
                ]);

                $count++;
            }
        }

        return redirect()->route('admin.employees.index')
            ->with('success', "Bulk generated {$count} offer letters successfully.");
    }
}
