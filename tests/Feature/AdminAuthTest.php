<?php

namespace Tests\Feature;

use App\Models\Admin;
use App\Models\Company;
use App\Models\Department;
use App\Models\Designation;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
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

    /**
     * Test multi-select filtering for Company, Designation, Department, and Status.
     */
    public function test_admin_can_filter_and_export_with_multiselect_options(): void
    {
        $this->seed();
        $admin = Admin::first();
        $company = \App\Models\Company::first();
        $dept = \App\Models\Department::first();
        $desig = \App\Models\Designation::first();

        // 1. Multi-select on Web View
        $response = $this->actingAs($admin, 'admin')->get(route('admin.employees.index', [
            'company_id' => [$company->id],
            'department_id' => [$dept->id],
            'designation_id' => [$desig->id],
            'status' => ['active', 'pending_review'],
        ]));
        $response->assertStatus(200);

        // 2. Multi-select on Export
        $exportResponse = $this->actingAs($admin, 'admin')->get(route('admin.employees.export', [
            'company_id' => [$company->id],
            'department_id' => [$dept->id],
            'designation_id' => [$desig->id],
            'status' => ['active', 'pending_review'],
        ]));
        $exportResponse->assertStatus(200);
        $exportResponse->assertHeader('Content-Type', 'text/csv; charset=UTF-8');
    }

    public function test_company_annexure_salary_structure_and_offer_letter_generation(): void
    {
        $this->seed();
        $admin = Admin::first();

        // 1. Create company with Annexure values
        $response = $this->actingAs($admin, 'admin')->post(route('admin.companies.store'), [
            'name' => 'Apex Logistics Corp',
            'address' => 'Salt Lake Sector V, Kolkata',
            'basic' => 11927.00,
            'hra' => 596.00,
            'conveyance' => 0.00,
            'medical_allowance' => 0.00,
            'sp_allowance' => 0.00,
            'bonus' => 500.00,
            'employer_pf' => 1551.00,
            'employer_esic' => 426.00,
            'employer_lwf' => 0.00,
            'employee_pf' => 1431.00,
            'employee_esic' => 99.00,
            'employee_lwf' => 0.00,
            'professional_tax' => 110.00,
        ]);

        $response->assertRedirect(route('admin.companies.index'));
        
        $company = \App\Models\Company::where('name', 'Apex Logistics Corp')->first();
        $this->assertNotNull($company);
        $this->assertEquals(12523.00, (float)$company->gross_earning);
        $this->assertEquals(15000.00, (float)$company->ctc);
        $this->assertEquals(1640.00, (float)$company->total_deductions);
        $this->assertEquals(11383.00, (float)$company->net_salary);

        // 2. Assign employee to this company and generate offer letter
        $employee = \App\Models\Employee::create([
            'employee_id' => 'EMP-TEST-ANNEXURE',
            'aadhaar_full_name' => 'John Doe Annexure',
            'email' => 'annexure@test.com',
            'password' => bcrypt('password'),
            'company_id' => $company->id,
            'status' => 'active',
        ]);

        $genResponse = $this->actingAs($admin, 'admin')->post(route('admin.offer-letters.generate.submit'), [
            'employee_id' => $employee->id,
            'type' => 'internal',
        ]);

        $genResponse->assertRedirect(route('admin.employees.index'));
        $this->assertDatabaseHas('offer_letters', [
            'employee_id' => $employee->id,
        ]);
    }

    public function test_admin_can_update_profile_identity_and_password(): void
    {
        $this->seed();
        $admin = Admin::first();

        // 1. View profile page
        $viewResponse = $this->actingAs($admin, 'admin')->get(route('admin.profile'));
        $viewResponse->assertStatus(200);
        $viewResponse->assertSee($admin->name);
        $viewResponse->assertSee($admin->email);

        // 2. Update Name & Email (Admin ID)
        $updateResponse = $this->actingAs($admin, 'admin')->put(route('admin.profile.update'), [
            'name' => 'Super Administrator Updated',
            'email' => 'superadmin.new@propszy.com',
        ]);
        $updateResponse->assertRedirect(route('admin.profile'));
        $admin->refresh();
        $this->assertEquals('Super Administrator Updated', $admin->name);
        $this->assertEquals('superadmin.new@propszy.com', $admin->email);

        // 3. Fail update password with incorrect current password
        $failPassResponse = $this->actingAs($admin, 'admin')->put(route('admin.profile.password'), [
            'current_password' => 'wrongpassword123',
            'new_password' => 'BrandNewPassword!2026',
            'new_password_confirmation' => 'BrandNewPassword!2026',
        ]);
        $failPassResponse->assertSessionHasErrors(['current_password']);

        // 4. Successfully update password with correct current password ('password123')
        $passResponse = $this->actingAs($admin, 'admin')->put(route('admin.profile.password'), [
            'current_password' => 'password123',
            'new_password' => 'BrandNewPassword!2026',
            'new_password_confirmation' => 'BrandNewPassword!2026',
        ]);
        $passResponse->assertRedirect(route('admin.profile'));
        $admin->refresh();
        $this->assertTrue(Hash::check('BrandNewPassword!2026', $admin->password));
    }

    public function test_admin_can_bulk_delete_employees(): void
    {
        $this->seed();
        $admin = Admin::first();

        // Create 2 test employees
        $emp1 = \App\Models\Employee::create([
            'employee_id' => 'EMP-BULK-DEL-1',
            'aadhaar_full_name' => 'Bulk Del 1',
            'email' => 'bulkdel1@test.com',
            'password' => bcrypt('password'),
            'status' => 'pending_review',
        ]);
        $emp2 = \App\Models\Employee::create([
            'employee_id' => 'EMP-BULK-DEL-2',
            'aadhaar_full_name' => 'Bulk Del 2',
            'email' => 'bulkdel2@test.com',
            'password' => bcrypt('password'),
            'status' => 'pending_review',
        ]);

        $response = $this->actingAs($admin, 'admin')->post(route('admin.employees.bulk-action'), [
            'employee_ids' => [$emp1->id, $emp2->id],
            'action' => 'delete',
        ]);

        $response->assertRedirect(route('admin.employees.index'));
        $this->assertDatabaseMissing('employees', ['id' => $emp1->id]);
        $this->assertDatabaseMissing('employees', ['id' => $emp2->id]);
    }

    public function test_admin_can_bulk_change_status_of_employees(): void
    {
        $this->seed();
        $admin = Admin::first();

        // Create 2 test employees
        $emp1 = \App\Models\Employee::create([
            'employee_id' => 'EMP-BULK-STATUS-1',
            'aadhaar_full_name' => 'Bulk Stat 1',
            'email' => 'bulkstat1@test.com',
            'password' => bcrypt('password'),
            'status' => 'pending_review',
        ]);
        $emp2 = \App\Models\Employee::create([
            'employee_id' => 'EMP-BULK-STATUS-2',
            'aadhaar_full_name' => 'Bulk Stat 2',
            'email' => 'bulkstat2@test.com',
            'password' => bcrypt('password'),
            'status' => 'pending_review',
        ]);

        $response = $this->actingAs($admin, 'admin')->post(route('admin.employees.bulk-action'), [
            'employee_ids' => [$emp1->id, $emp2->id],
            'action' => 'status_change',
            'status' => 'active',
        ]);

        $response->assertRedirect(route('admin.employees.index'));
        $this->assertDatabaseHas('employees', ['id' => $emp1->id, 'status' => 'active']);
        $this->assertDatabaseHas('employees', ['id' => $emp2->id, 'status' => 'active']);
    }

    public function test_admin_can_bulk_generate_offer_letters(): void
    {
        $this->seed();
        $admin = Admin::first();

        // Create 2 test employees
        $emp1 = \App\Models\Employee::create([
            'employee_id' => 'EMP-BULK-OL-1',
            'aadhaar_full_name' => 'Bulk OL 1',
            'email' => 'bulkol1@test.com',
            'password' => bcrypt('password'),
            'status' => 'active',
        ]);
        $emp2 = \App\Models\Employee::create([
            'employee_id' => 'EMP-BULK-OL-2',
            'aadhaar_full_name' => 'Bulk OL 2',
            'email' => 'bulkol2@test.com',
            'password' => bcrypt('password'),
            'status' => 'active',
        ]);

        $response = $this->actingAs($admin, 'admin')->post(route('admin.employees.bulk-action'), [
            'employee_ids' => [$emp1->id, $emp2->id],
            'action' => 'offer_letter',
            'type' => 'internal',
        ]);

        $response->assertRedirect(route('admin.employees.index'));
        $this->assertDatabaseHas('offer_letters', ['employee_id' => $emp1->id]);
        $this->assertDatabaseHas('offer_letters', ['employee_id' => $emp2->id]);
    }

    public function test_custom_employee_id_sequence_and_reservation(): void
    {
        $this->seed();
        
        // Let's create an employee under company 1 (seeded company has ID 1)
        // Since we seeded RM010001 in DatabaseSeeder, the next ID for company 1 should be RM010002.
        $idCompany1 = \App\Models\Employee::generateNextEmployeeId(1);
        $this->assertEquals('RM010002', $idCompany1);
        
        // Let's create an employee under another company (e.g. company 2)
        // Since there is no employee starting with RM01 in that range, the next ID should start from RM010101.
        $idCompany2 = \App\Models\Employee::generateNextEmployeeId(2);
        $this->assertEquals('RM010101', $idCompany2);
        
        // Let's manually persist an employee with RM010101 to simulate it
        \App\Models\Employee::create([
            'employee_id' => 'RM010101',
            'first_name' => 'Jane',
            'last_name' => 'Doe',
            'email' => 'jane.doe@other.com',
            'password' => bcrypt('password123'),
            'status' => 'active',
            'company_id' => 2,
        ]);
        
        // Now next ID for company 2 should be RM010102
        $nextIdCompany2 = \App\Models\Employee::generateNextEmployeeId(2);
        $this->assertEquals('RM010102', $nextIdCompany2);
        
        // If we generate for company 1, it should still be RM010002
        $nextIdCompany1 = \App\Models\Employee::generateNextEmployeeId(1);
        $this->assertEquals('RM010002', $nextIdCompany1);
    }

    public function test_admin_import_handles_failures_and_generates_failed_csv(): void
    {
        $this->seed();
        $admin = Admin::first();

        // Let's create an existing email in DB to force validation failure
        \App\Models\Employee::create([
            'employee_id' => 'RM019999',
            'aadhaar_full_name' => 'Existing Person',
            'email' => 'existing.person@example.com',
            'password' => bcrypt('password'),
            'status' => 'active',
        ]);

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
        ]) . "\n" . 
        // 1. Success row
        implode(',', [
            'Success Person',
            '123456789012',
            'ABCDE1234F',
            'VOTER123',
            'Mr.',
            'Father Success',
            'Mother Success',
            'Male',
            '1995-01-01',
            'Hindi',
            '123 Address',
            'Near Landmark',
            '9876543210',
            'Kolkata',
            '9876543210',
            '700001',
            'West Bengal',
            'Graduate',
            '2018',
            'Single',
            'success.person@example.com',
            'UAN12345',
            'ESIC12345',
            'ACC12345',
            'IFSC12345',
            'Bank of India',
            'Acme Corp',
            'Kolkata Branch',
            'Support Staff',
            '15000'
        ]) . "\n" . 
        // 2. Failure row: duplicate email
        implode(',', [
            'Duplicate Email Person',
            '223456789012',
            'ABCDE1234F',
            'VOTER123',
            'Mr.',
            'Father Dup',
            'Mother Dup',
            'Male',
            '1995-01-01',
            'Hindi',
            '123 Address',
            'Near Landmark',
            '9876543210',
            'Kolkata',
            '9876543210',
            '700001',
            'West Bengal',
            'Graduate',
            '2018',
            'Single',
            'existing.person@example.com',
            'UAN12345',
            'ESIC12345',
            'ACC12345',
            'IFSC12345',
            'Bank of India',
            'Acme Corp',
            'Kolkata Branch',
            'Support Staff',
            '15000'
        ]) . "\n" . 
        // 3. Failure row: missing Aadhaar number and contact number
        implode(',', [
            'Missing Fields Person',
            '', // Missing Aadhaar
            'ABCDE1234F',
            'VOTER123',
            'Mr.',
            'Father Miss',
            'Mother Miss',
            'Male',
            '1995-01-01',
            'Hindi',
            '123 Address',
            'Near Landmark',
            '', // Missing Contact
            'Kolkata',
            '9876543210',
            '700001',
            'West Bengal',
            'Graduate',
            '2018',
            'Single',
            'missing.person@example.com',
            'UAN12345',
            'ESIC12345',
            'ACC12345',
            'IFSC12345',
            'Bank of India',
            'Acme Corp',
            'Kolkata Branch',
            'Support Staff',
            '15000'
        ]);

        $file = \Illuminate\Http\UploadedFile::fake()->createWithContent('import_failures.csv', $csvContent);

        $response = $this->actingAs($admin, 'admin')->post(route('admin.employees.import'), [
            'csv_file' => $file,
        ]);

        $response->assertRedirect(route('admin.employees.index'));
        $response->assertSessionHas('import_summary');

        $summary = session('import_summary');
        $this->assertEquals(1, $summary['success_count']);
        $this->assertEquals(2, $summary['fail_count']);
        $this->assertNotNull($summary['failed_file']);
        $this->assertCount(2, $summary['errors']);

        // Check details of first error (duplicate email)
        $this->assertEquals('Duplicate Email Person', $summary['errors'][0]['name']);
        $this->assertStringContainsString('already been taken', implode(' ', $summary['errors'][0]['reasons']));

        // Check details of second error (missing fields)
        $this->assertEquals('Missing Fields Person', $summary['errors'][1]['name']);
        $this->assertStringContainsString('Aadhaar Number is required', implode(' ', $summary['errors'][1]['reasons']));
        $this->assertStringContainsString('Contact Number is required', implode(' ', $summary['errors'][1]['reasons']));

        // Assert database has successfully imported candidate and not the failed ones
        $this->assertDatabaseHas('employees', ['email' => 'success.person@example.com']);
        $this->assertDatabaseMissing('employees', ['email' => 'missing.person@example.com']);

        // Test downloading the failed CSV file
        $downloadResponse = $this->actingAs($admin, 'admin')->get(route('admin.employees.download-failed-import', [
            'filename' => $summary['failed_file']
        ]));

        $downloadResponse->assertStatus(200);
        $downloadResponse->assertHeader('Content-Type', 'text/csv; charset=UTF-8');
        $downloadResponse->assertHeader('Content-Disposition', 'attachment; filename=failed_candidate_records.csv');

        // Clean up the generated file
        $filePath = storage_path('app/failed_imports/' . $summary['failed_file']);
        if (file_exists($filePath)) {
            unlink($filePath);
        }
    }

    public function test_admin_can_update_employee_password(): void
    {
        $this->seed();
        $admin = Admin::first();

        // Create an employee first
        $employee = \App\Models\Employee::create([
            'employee_id' => 'EMP-RESET-PWD',
            'aadhaar_full_name' => 'Reset Pwd Person',
            'email' => 'reset.pwd@example.com',
            'password' => bcrypt('oldpassword123'),
            'status' => 'active',
            'first_name' => 'Reset',
            'last_name' => 'Pwd',
        ]);

        $dept = \App\Models\Department::first();
        $desig = \App\Models\Designation::first();
        $company = \App\Models\Company::first();

        // Update employee details including password
        $response = $this->actingAs($admin, 'admin')->put(route('admin.employees.update', $employee), [
            'email' => 'reset.pwd@example.com',
            'status' => 'active',
            'department_id' => $dept->id,
            'designation_id' => $desig->id,
            'company_id' => $company->id,
            'joining_date' => '2026-08-17',
            'salary' => 16000.00,
            'aadhaar_full_name' => 'Reset Pwd Person',
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
            'password' => 'newpassword123',
        ]);

        $response->assertRedirect(route('admin.employees.index'));
        
        $employee->refresh();
        
        // Verify password was changed by trying to log in
        $this->assertTrue(\Illuminate\Support\Facades\Hash::check('newpassword123', $employee->password));
        $this->assertFalse(\Illuminate\Support\Facades\Hash::check('oldpassword123', $employee->password));

        // Test login with the updated password
        $loginResponse = $this->post(route('login.submit'), [
            'employee_id' => 'EMP-RESET-PWD',
            'password' => 'newpassword123',
        ]);
        
        $loginResponse->assertRedirect();
        $this->assertAuthenticatedAs($employee, 'employee');
    }

    public function test_admin_can_bulk_upload_offer_letters_and_distribute_by_filename()
    {
        $this->seed();
        $admin = Admin::first();
        
        $employee = \App\Models\Employee::create([
            'employee_id' => 'EMP-BULK-01',
            'first_name' => 'Bulk',
            'last_name' => 'One',
            'email' => 'bulk01@example.com',
            'password' => Hash::make('password123'),
            'status' => 'active',
        ]);

        // Visit upload page
        $response = $this->actingAs($admin, 'admin')->get(route('admin.documents.bulk-upload'));
        $response->assertStatus(200);

        // Upload matching and mismatching files
        $file1 = \Illuminate\Http\UploadedFile::fake()->create('EMP-BULK-01.pdf', 100, 'application/pdf');
        $file2 = \Illuminate\Http\UploadedFile::fake()->create('EMP-BULK-UNKNOWN.pdf', 100, 'application/pdf');

        $response = $this->actingAs($admin, 'admin')->post(route('admin.documents.bulk-upload.submit'), [
            'doc_type' => 'offer_letter',
            'files' => [$file1, $file2],
        ]);

        $response->assertRedirect(route('admin.documents.bulk-upload'));
        
        // Assert distribution logic succeeded
        $this->assertEquals(1, \App\Models\OfferLetter::where('employee_id', $employee->id)->count());
        
        // Check session flashes
        $response->assertSessionHas('bulk_upload_summary');
        $summary = session('bulk_upload_summary');
        $this->assertEquals(1, $summary['success_count']);
        $this->assertEquals(1, $summary['fail_count']);
        $this->assertStringContainsString('EMP-BULK-UNKNOWN.pdf', $summary['errors'][0]);
    }

    public function test_admin_can_bulk_upload_payslips_and_distribute_by_filename()
    {
        $this->seed();
        $admin = Admin::first();
        
        $employee = \App\Models\Employee::create([
            'employee_id' => 'EMP-BULK-02',
            'first_name' => 'Bulk',
            'last_name' => 'Two',
            'email' => 'bulk02@example.com',
            'password' => Hash::make('password123'),
            'status' => 'active',
            'salary' => 20000.00,
        ]);

        $file1 = \Illuminate\Http\UploadedFile::fake()->create('EMP-BULK-02.pdf', 100, 'application/pdf');

        $response = $this->actingAs($admin, 'admin')->post(route('admin.documents.bulk-upload.submit'), [
            'doc_type' => 'payslip',
            'files' => [$file1],
            'month' => '2026-09',
        ]);

        $response->assertRedirect(route('admin.documents.bulk-upload'));
        
        // Assert payslip created
        $payslip = \App\Models\Payslip::where('employee_id', $employee->id)->first();
        $this->assertNotNull($payslip);
        $this->assertEquals('September 2026', $payslip->month);
        $this->assertEquals('external', $payslip->type);
        $this->assertGreaterThan(0, $payslip->net_salary);
    }
}
