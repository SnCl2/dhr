<?php

namespace Tests\Feature;

use App\Models\Admin;
use App\Models\Company;
use App\Models\Department;
use App\Models\Designation;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminAuthTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test admin login page loads.
     */
    public function test_admin_login_page_renders_successfully(): void
    {
        $response = $this->get(route('admin.login'));
        $response->assertStatus(200);
    }

    /**
     * Test protected admin dashboard redirects guest.
     */
    public function test_unauthenticated_admin_redirected_from_dashboard(): void
    {
        $response = $this->get(route('admin.dashboard'));
        $response->assertRedirect(route('admin.login'));
    }

    /**
     * Test successful login with correct admin credentials.
     */
    public function test_admin_logs_in_successfully_with_valid_credentials(): void
    {
        // Seed default admin via seeder
        $this->seed();

        $response = $this->post(route('admin.login.submit'), [
            'email' => 'admin@admin.com',
            'password' => 'password123',
        ]);

        $response->assertRedirect(route('admin.dashboard'));
        $this->assertAuthenticatedAs(Admin::first(), 'admin');
    }

    /**
     * Test admin can impersonate/login as employee.
     */
    public function test_admin_can_impersonate_employee(): void
    {
        $this->seed();

        $admin = Admin::first();
        $employee = \App\Models\Employee::first();

        // Unauthenticated admin call redirected to login
        $response = $this->post(route('admin.employees.login-as', $employee));
        $response->assertRedirect(route('admin.login'));

        // Authenticated admin logs in employee guard and redirects to dashboard
        $response = $this->actingAs($admin, 'admin')->post(route('admin.employees.login-as', $employee));
        $response->assertRedirect(route('employee.dashboard'));
        $this->assertAuthenticatedAs($employee, 'employee');
    }

    /**
     * Test admin can perform Company CRUD and assign it to an employee.
     */
    public function test_admin_can_manage_companies_and_assign_to_employee(): void
    {
        $this->seed();

        $admin = Admin::first();

        // 1. Create a Company
        $response = $this->actingAs($admin, 'admin')->post(route('admin.companies.store'), [
            'name' => 'RM HR Solutions Plotters 2',
            'address' => 'Test Address 2',
        ]);
        $response->assertRedirect(route('admin.companies.index'));
        $this->assertDatabaseHas('companies', ['name' => 'RM HR Solutions Plotters 2']);

        $company = Company::where('name', 'RM HR Solutions Plotters 2')->first();

        // 2. Edit the Company
        $response = $this->actingAs($admin, 'admin')->put(route('admin.companies.update', $company), [
            'name' => 'RM HR Solutions Plotters Updated',
            'address' => 'Updated Address',
        ]);
        $response->assertRedirect(route('admin.companies.index'));
        $this->assertDatabaseHas('companies', ['name' => 'RM HR Solutions Plotters Updated']);

        // 3. Create an Employee assigned to that Company
        $dept = Department::first();
        $desig = Designation::first();

        $response = $this->actingAs($admin, 'admin')->post(route('admin.employees.store'), [
            'email' => 'mark.taylor@example.com',
            'status' => 'active',
            'department_id' => $dept->id,
            'designation_id' => $desig->id,
            'company_id' => $company->id,
            'joining_date' => '2026-08-16',
            'salary' => 12000.00,
            'aadhaar_full_name' => 'Mark Taylor',
            'aadhaar_number' => '123456789012',
            'prefix' => 'Mr.',
            'father_name_aadhaar' => 'Robert Taylor',
            'mother_name_aadhaar' => 'Susan Taylor',
            'gender' => 'Male',
            'dob' => '1995-04-10',
            'mother_tongue' => 'English',
            'aadhaar_address' => '123 Main St, Kolkata',
            'landmark' => 'Near Central Park',
            'contact_number' => '9876543210',
            'city' => 'Kolkata',
            'emergency_contact_number' => '9876543211',
            'pin_code' => '700001',
            'state' => 'West Bengal',
            'last_qualification' => 'Graduate',
            'pass_out_year' => '2018',
            'marital_status' => 'Single',
            'old_uan_number' => '100987654321',
            'bank_account_number' => '1234567890',
            'ifsc_code' => 'UTIB0000123',
            'bank_name' => 'Axis Bank',
            'work_location' => 'Kolkata Office',
            'nth_salary' => 10000.00,
        ]);
        $response->assertRedirect(route('admin.employees.index'));
        $this->assertDatabaseHas('employees', [
            'aadhaar_full_name' => 'Mark Taylor',
            'company_id' => $company->id,
        ]);

        // 4. Delete the Company
        $response = $this->actingAs($admin, 'admin')->delete(route('admin.companies.destroy', $company));
        $response->assertRedirect(route('admin.companies.index'));
        $this->assertDatabaseMissing('companies', ['id' => $company->id]);
        
        // Assert the employee's company_id was set to null on delete cascade
        $this->assertDatabaseHas('employees', [
            'aadhaar_full_name' => 'Mark Taylor',
            'company_id' => null,
        ]);
    }

    /**
     * Test admin can create an employee with uploaded documents and update their details.
     */
    public function test_admin_can_create_and_update_employee_with_documents(): void
    {
        $this->seed();

        $admin = Admin::first();
        $dept = Department::first();
        $desig = Designation::first();
        $company = Company::first();

        // 1. Create Employee with simulated profile image and documents
        \Illuminate\Support\Facades\Storage::fake('public');
        $employeeDocument = \Illuminate\Http\UploadedFile::fake()->create('employee_doc.pdf', 500);
        $profileImage = \Illuminate\Http\UploadedFile::fake()->create('profile.jpg', 200, 'image/jpeg');

        $response = $this->actingAs($admin, 'admin')->post(route('admin.employees.store'), [
            'email' => 'unique.test.employee@example.com',
            'status' => 'active',
            'department_id' => $dept->id,
            'designation_id' => $desig->id,
            'company_id' => $company->id,
            'joining_date' => '2026-08-17',
            'salary' => 15000.00,
            'aadhaar_full_name' => 'John Doe',
            'aadhaar_number' => '987654321012',
            'prefix' => 'Mr.',
            'father_name_aadhaar' => 'Richard Doe',
            'mother_name_aadhaar' => 'Mary Doe',
            'gender' => 'Male',
            'dob' => '1990-05-15',
            'mother_tongue' => 'Hindi',
            'aadhaar_address' => '456 Park Avenue, Delhi',
            'landmark' => 'Near Metro Gate 3',
            'contact_number' => '9999988888',
            'city' => 'Delhi',
            'emergency_contact_number' => '9999988889',
            'pin_code' => '110001',
            'state' => 'Delhi',
            'last_qualification' => 'Post Graduate',
            'pass_out_year' => '2012',
            'marital_status' => 'Married',
            'old_uan_number' => '100888888888',
            'bank_account_number' => '9876543210',
            'ifsc_code' => 'SBIN0000123',
            'bank_name' => 'State Bank of India',
            'work_location' => 'Delhi Hub',
            'nth_salary' => 13000.00,
            'employee_document' => $employeeDocument,
            'profile_image' => $profileImage,
        ]);

        $response->assertRedirect(route('admin.employees.index'));
        
        $employee = \App\Models\Employee::where('email', 'unique.test.employee@example.com')->first();
        $this->assertNotNull($employee);
        $this->assertNotNull($employee->employee_document);
        $this->assertNotNull($employee->profile_image);
        $this->assertEquals('John Doe', $employee->aadhaar_full_name);

        // 2. Update Employee details and upload a new document & profile image
        $employeeDocumentUpdated = \Illuminate\Http\UploadedFile::fake()->create('employee_doc_updated.pdf', 600);
        $profileImageUpdated = \Illuminate\Http\UploadedFile::fake()->create('profile_updated.jpg', 300, 'image/jpeg');

        $response = $this->actingAs($admin, 'admin')->put(route('admin.employees.update', $employee), [
            'email' => 'unique.test.employee@example.com',
            'status' => 'inactive',
            'department_id' => $dept->id,
            'designation_id' => $desig->id,
            'company_id' => $company->id,
            'joining_date' => '2026-08-17',
            'salary' => 16000.00,
            'aadhaar_full_name' => 'John Updated',
            'aadhaar_number' => '987654321012',
            'prefix' => 'Mr.',
            'father_name_aadhaar' => 'Richard Doe',
            'mother_name_aadhaar' => 'Mary Doe',
            'gender' => 'Male',
            'dob' => '1990-05-15',
            'mother_tongue' => 'Hindi',
            'aadhaar_address' => '456 Park Avenue, Delhi',
            'landmark' => 'Near Metro Gate 3',
            'contact_number' => '9999988888',
            'city' => 'Delhi',
            'emergency_contact_number' => '9999988889',
            'pin_code' => '110001',
            'state' => 'Delhi',
            'last_qualification' => 'Post Graduate',
            'pass_out_year' => '2012',
            'marital_status' => 'Married',
            'old_uan_number' => '100888888888',
            'bank_account_number' => '9876543210',
            'ifsc_code' => 'SBIN0000123',
            'bank_name' => 'State Bank of India',
            'work_location' => 'Delhi Hub',
            'nth_salary' => 14000.00,
            'employee_document' => $employeeDocumentUpdated,
            'profile_image' => $profileImageUpdated,
        ]);

        $response->assertRedirect(route('admin.employees.index'));
        
        $employee->refresh();
        $this->assertEquals('John Updated', $employee->aadhaar_full_name);
        $this->assertEquals('inactive', $employee->status);
        $this->assertEquals(16000.00, $employee->salary);
        $this->assertNotNull($employee->employee_document);
        $this->assertNotNull($employee->profile_image);
    }

    /**
     * Test admin can download prefilled template and import employees matching Book 9 schema.
     */
    public function test_admin_can_download_csv_template_and_import_employees_matching_master_schema(): void
    {
        $this->seed();
        $admin = Admin::first();

        // 1. Download template
        $response = $this->actingAs($admin, 'admin')->get(route('admin.employees.download-template', ['company' => 'Acme Corp', 'designation' => 'Support Staff']));
        $response->assertStatus(200);
        $response->assertHeader('Content-Type', 'text/csv; charset=UTF-8');

        // 2. Upload and Import CSV
        $csvContent = implode(',', [
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
        ]) . "\n" . implode(',', [
            'Vikram Malhotra',
            '112233445566',
            'ABCDE5678G',
            'VOTER123',
            'Mr.',
            'Raj Malhotra',
            'Sunita Malhotra',
            'Male',
            '1992-08-20',
            'Hindi',
            '78 Sector 18',
            'Near Mall',
            '9811122233',
            'Noida',
            '9811122234',
            '201301',
            'Uttar Pradesh',
            'B.Sc',
            '2014',
            'Married',
            'vikram.malhotra@example.com',
            '100777888999',
            '99887766554433221',
            '554433221100',
            'HDFC0000123',
            'HDFC Bank',
            'Client Alpha',
            'Noida Branch',
            'Field Executive',
            '17500'
        ]);

        $file = \Illuminate\Http\UploadedFile::fake()->createWithContent('import.csv', $csvContent);

        $response = $this->actingAs($admin, 'admin')->post(route('admin.employees.import'), [
            'csv_file' => $file,
        ]);

        $response->assertRedirect(route('admin.employees.index'));

        $this->assertDatabaseHas('employees', [
            'email' => 'vikram.malhotra@example.com',
            'aadhaar_full_name' => 'Vikram Malhotra',
            'aadhaar_number' => '112233445566',
            'pan_number' => 'ABCDE5678G',
            'city' => 'Noida',
            'nth_salary' => 17500.00,
        ]);
    }

    /**
     * Test admin can export filtered employees to CSV in Book 9 schema.
     */
    public function test_admin_can_export_filtered_employees_to_csv(): void
    {
        $this->seed();
        $admin = Admin::first();

        $response = $this->actingAs($admin, 'admin')->get(route('admin.employees.export', [
            'status' => 'active',
            'search' => 'John',
        ]));

        $response->assertStatus(200);
        $response->assertHeader('Content-Type', 'text/csv; charset=UTF-8');
    }
}
