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

        // Retrieve seeded dummy staff (has view_employees, create_employees, view_payslips)
        $staff = Staff::where('email', 'staff@staff.com')->first();
        $staff->is_password_changed = true;
        $staff->save();

        // 1. Staff accesses permitted Employees list (should pass)
        $response = $this->actingAs($staff, 'staff')->get(route('admin.employees.index'));
        $response->assertStatus(200);

        // 2. Staff accesses forbidden Companies list (should redirect back to previous page: employees.index)
        $response = $this->actingAs($staff, 'staff')->get(route('admin.companies.index'));
        $response->assertRedirect(route('admin.employees.index'));

        // 3. Staff accesses forbidden Staff management panel (should redirect back)
        $response = $this->actingAs($staff, 'staff')->get(route('admin.staff.index'));
        $response->assertRedirect();
    }

    /**
     * Test admin can perform CRUD on Staff and assign permissions.
     */
    public function test_admin_can_manage_staff_and_assign_permissions(): void
    {
        $this->seed();

        $admin = Admin::first();
        
        // Create new staff member
        $viewEmployeesPermission = Permission::where('name', 'view_employees')->first();
        $viewCompaniesPermission = Permission::where('name', 'view_companies')->first();

        $response = $this->actingAs($admin, 'admin')->post(route('admin.staff.store'), [
            'name' => 'John Assistant',
            'email' => 'john.assistant@agency.com',
            'password' => 'tempPass1234',
            'is_active' => '1',
            'permissions' => [$viewEmployeesPermission->id, $viewCompaniesPermission->id]
        ]);

        $response->assertRedirect(route('admin.staff.index'));
        $this->assertDatabaseHas('staff', ['email' => 'john.assistant@agency.com']);

        $newStaff = Staff::where('email', 'john.assistant@agency.com')->first();
        $this->assertTrue($newStaff->hasPermission('view_employees'));
        $this->assertTrue($newStaff->hasPermission('view_companies'));
        $this->assertFalse($newStaff->hasPermission('view_payslips'));

        // Update the staff member's permissions (revoke companies, add payslips)
        $viewPayslipsPermission = Permission::where('name', 'view_payslips')->first();

        $response = $this->actingAs($admin, 'admin')->put(route('admin.staff.update', $newStaff), [
            'name' => 'John Assistant Updated',
            'email' => 'john.assistant@agency.com',
            'is_active' => '1',
            'permissions' => [$viewEmployeesPermission->id, $viewPayslipsPermission->id]
        ]);

        $response->assertRedirect(route('admin.staff.index'));
        $newStaff->refresh();
        
        $this->assertTrue($newStaff->hasPermission('view_employees'));
        $this->assertTrue($newStaff->hasPermission('view_payslips'));
        $this->assertFalse($newStaff->hasPermission('view_companies'));
    }

    /**
     * Test admin can toggle staff active status and deactivated staff cannot log in.
     */
    public function test_admin_can_toggle_staff_status_and_deactivated_staff_cannot_login(): void
    {
        $this->seed();
        $admin = Admin::first();
        
        $staff = Staff::where('email', 'staff@staff.com')->first();
        $this->assertTrue($staff->is_active);

        // 1. Deactivate the staff member via admin PATCH route
        $response = $this->actingAs($admin, 'admin')->patch(route('admin.staff.toggle-status', $staff));
        $response->assertRedirect(route('admin.staff.index'));
        
        $staff->refresh();
        $this->assertFalse($staff->is_active);

        // 2. Attempt login as deactivated staff - should fail
        $response = $this->post(route('staff.login.submit'), [
            'email' => 'staff@staff.com',
            'password' => 'password123',
        ]);
        $response->assertSessionHasErrors(['email']);
        $this->assertFalse(auth()->guard('staff')->check());

        // 3. Reactivate the staff member via admin PATCH route
        $response = $this->actingAs($admin, 'admin')->patch(route('admin.staff.toggle-status', $staff));
        $response->assertRedirect(route('admin.staff.index'));
        
        $staff->refresh();
        $this->assertTrue($staff->is_active);

        // 4. Attempt login as reactivated staff - should succeed
        $response = $this->post(route('staff.login.submit'), [
            'email' => 'staff@staff.com',
            'password' => 'password123',
        ]);
        $response->assertRedirect(route('staff.password.change'));
        $this->assertAuthenticatedAs($staff, 'staff');
    }
}
