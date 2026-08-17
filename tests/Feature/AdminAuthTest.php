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
            'name' => 'RMHRSolutions Plotters 2',
            'address' => 'Test Address 2',
        ]);
        $response->assertRedirect(route('admin.companies.index'));
        $this->assertDatabaseHas('companies', ['name' => 'RMHRSolutions Plotters 2']);

        $company = Company::where('name', 'RMHRSolutions Plotters 2')->first();

        // 2. Edit the Company
        $response = $this->actingAs($admin, 'admin')->put(route('admin.companies.update', $company), [
            'name' => 'RMHRSolutions Plotters Updated',
            'address' => 'Updated Address',
        ]);
        $response->assertRedirect(route('admin.companies.index'));
        $this->assertDatabaseHas('companies', ['name' => 'RMHRSolutions Plotters Updated']);

        // 3. Create an Employee assigned to that Company
        $dept = Department::first();
        $desig = Designation::first();

        $response = $this->actingAs($admin, 'admin')->post(route('admin.employees.store'), [
            'first_name' => 'Mark',
            'last_name' => 'Taylor',
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
            'client_name' => 'Client A',
            'work_location' => 'Kolkata Office',
            'designation' => 'Software Engineer',
            'nth_salary' => 10000.00,
        ]);
        $response->assertRedirect(route('admin.employees.index'));
        $this->assertDatabaseHas('employees', [
            'first_name' => 'Mark',
            'company_id' => $company->id,
        ]);

        // 4. Delete the Company
        $response = $this->actingAs($admin, 'admin')->delete(route('admin.companies.destroy', $company));
        $response->assertRedirect(route('admin.companies.index'));
        $this->assertDatabaseMissing('companies', ['id' => $company->id]);
        
        // Assert the employee's company_id was set to null on delete cascade
        $this->assertDatabaseHas('employees', [
            'first_name' => 'Mark',
            'company_id' => null,
        ]);
    }
}
