<?php

namespace Tests\Feature;

use App\Models\Admin;
use App\Models\Staff;
use App\Models\Permission;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class StaffAuthAndPermissionTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test staff login page renders successfully.
     */
    public function test_staff_login_page_renders_successfully(): void
    {
        $response = $this->get(route('staff.login'));
        $response->assertStatus(200);
        $response->assertSee('Management Staff Login');
    }

    /**
     * Test unauthenticated staff redirected from dashboard.
     */
    public function test_unauthenticated_staff_redirected_from_dashboard(): void
    {
        $response = $this->get(route('admin.dashboard'));
        $response->assertRedirect(route('admin.login'));
    }

    /**
     * Test staff logs in successfully and is forced to change password.
     */
    public function test_staff_logs_in_successfully_and_is_redirected_to_password_change(): void
    {
        $this->seed();

        $response = $this->post(route('staff.login.submit'), [
            'email' => 'staff@staff.com',
            'password' => 'password123',
        ]);

        $response->assertRedirect(route('staff.password.change'));
        $this->assertAuthenticatedAs(Staff::where('email', 'staff@staff.com')->first(), 'staff');
    }

    /**
     * Test staff can change password on first login and access dashboard.
     */
    public function test_staff_can_change_password_on_first_login(): void
    {
        $this->seed();

        $staff = Staff::where('email', 'staff@staff.com')->first();
        
        // Logged in as staff (password not changed)
        $response = $this->actingAs($staff, 'staff')->get(route('admin.dashboard'));
        $response->assertRedirect(route('staff.password.change'));

        // Post password change
        $response = $this->actingAs($staff, 'staff')->post(route('staff.password.change.update'), [
            'password' => 'newsecurepassword',
            'password_confirmation' => 'newsecurepassword',
        ]);

        $response->assertRedirect(route('admin.dashboard'));
        $staff->refresh();
        $this->assertTrue($staff->is_password_changed);
        $this->assertTrue(Hash::check('newsecurepassword', $staff->password));
    }

    /**
     * Test staff access control limits actions based on permissions.
     */
    public function test_staff_can_only_access_permitted_routes(): void
    {
        $this->seed();

        // Retrieve seeded dummy staff (has manage_employees and manage_payslips)
        $staff = Staff::where('email', 'staff@staff.com')->first();
        $staff->is_password_changed = true;
        $staff->save();

        // 1. Staff accesses permitted Employees list (should pass)
        $response = $this->actingAs($staff, 'staff')->get(route('admin.employees.index'));
        $response->assertStatus(200);

        // 2. Staff accesses forbidden Companies list (should return 403 Forbidden)
        $response = $this->actingAs($staff, 'staff')->get(route('admin.companies.index'));
        $response->assertStatus(403);

        // 3. Staff accesses forbidden Staff management panel (should return 403 Forbidden)
        $response = $this->actingAs($staff, 'staff')->get(route('admin.staff.index'));
        $response->assertStatus(403);
    }

    /**
     * Test admin can perform CRUD on Staff and assign permissions.
     */
    public function test_admin_can_manage_staff_and_assign_permissions(): void
    {
        $this->seed();

        $admin = Admin::first();
        
        // Create new staff member
        $manageEmployeesPermission = Permission::where('name', 'manage_employees')->first();
        $manageCompaniesPermission = Permission::where('name', 'manage_companies')->first();

        $response = $this->actingAs($admin, 'admin')->post(route('admin.staff.store'), [
            'name' => 'John Assistant',
            'email' => 'john.assistant@agency.com',
            'password' => 'tempPass1234',
            'permissions' => [$manageEmployeesPermission->id, $manageCompaniesPermission->id]
        ]);

        $response->assertRedirect(route('admin.staff.index'));
        $this->assertDatabaseHas('staff', ['email' => 'john.assistant@agency.com']);

        $newStaff = Staff::where('email', 'john.assistant@agency.com')->first();
        $this->assertTrue($newStaff->hasPermission('manage_employees'));
        $this->assertTrue($newStaff->hasPermission('manage_companies'));
        $this->assertFalse($newStaff->hasPermission('manage_payslips'));

        // Update the staff member's permissions (revoke companies, add payslips)
        $managePayslipsPermission = Permission::where('name', 'manage_payslips')->first();

        $response = $this->actingAs($admin, 'admin')->put(route('admin.staff.update', $newStaff), [
            'name' => 'John Assistant Updated',
            'email' => 'john.assistant@agency.com',
            'permissions' => [$manageEmployeesPermission->id, $managePayslipsPermission->id]
        ]);

        $response->assertRedirect(route('admin.staff.index'));
        $newStaff->refresh();
        
        $this->assertTrue($newStaff->hasPermission('manage_employees'));
        $this->assertTrue($newStaff->hasPermission('manage_payslips'));
        $this->assertFalse($newStaff->hasPermission('manage_companies'));
    }
}
