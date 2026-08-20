<?php

namespace Database\Seeders;

use App\Models\Admin;
use App\Models\Department;
use App\Models\Designation;
use App\Models\Bulletin;
use App\Models\SiteContent;
use App\Models\Employee;
use App\Models\Company;
use App\Models\Staff;
use App\Models\Permission;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Seed Admin
        Admin::create([
            'name' => 'System Administrator',
            'email' => 'admin@admin.com',
            'password' => Hash::make('password123'),
        ]);

        // Seed CRUD-level Permissions
        $permissions = [
            // Employees
            ['name' => 'view_employees', 'label' => 'View Candidates / Employees'],
            ['name' => 'create_employees', 'label' => 'Create Candidates / Employees'],
            ['name' => 'edit_employees', 'label' => 'Edit Candidates / Employees'],
            ['name' => 'delete_employees', 'label' => 'Delete Candidates / Employees'],

            // Companies
            ['name' => 'view_companies', 'label' => 'View Companies'],
            ['name' => 'create_companies', 'label' => 'Create Companies'],
            ['name' => 'edit_companies', 'label' => 'Edit Companies'],
            ['name' => 'delete_companies', 'label' => 'Delete Companies'],

            // Departments
            ['name' => 'view_departments', 'label' => 'View Departments'],
            ['name' => 'create_departments', 'label' => 'Create Departments'],
            ['name' => 'edit_departments', 'label' => 'Edit Departments'],
            ['name' => 'delete_departments', 'label' => 'Delete Departments'],

            // Designations
            ['name' => 'view_designations', 'label' => 'View Designations'],
            ['name' => 'create_designations', 'label' => 'Create Designations'],
            ['name' => 'edit_designations', 'label' => 'Edit Designations'],
            ['name' => 'delete_designations', 'label' => 'Delete Designations'],

            // Payslips
            ['name' => 'view_payslips', 'label' => 'View Payslips'],
            ['name' => 'create_payslips', 'label' => 'Create / Generate Payslips'],

            // Offer Letters
            ['name' => 'view_offer_letters', 'label' => 'View Offer Letters'],
            ['name' => 'create_offer_letters', 'label' => 'Create / Generate Offer Letters'],

            // Bulletins
            ['name' => 'view_bulletins', 'label' => 'View Bulletins'],
            ['name' => 'create_bulletins', 'label' => 'Create Bulletins'],
            ['name' => 'edit_bulletins', 'label' => 'Edit Bulletins'],
            ['name' => 'delete_bulletins', 'label' => 'Delete Bulletins'],

            // Inquiries
            ['name' => 'view_inquiries', 'label' => 'View Inbox Inquiries'],
            ['name' => 'reply_inquiries', 'label' => 'Reply / Mark Inquiries'],

            // CMS
            ['name' => 'view_cms', 'label' => 'View CMS Configuration'],
            ['name' => 'edit_cms', 'label' => 'Update CMS Contents'],

            // Staff Management
            ['name' => 'view_staff', 'label' => 'View Management Staff'],
            ['name' => 'create_staff', 'label' => 'Register Management Staff'],
            ['name' => 'edit_staff', 'label' => 'Edit Management Staff'],
            ['name' => 'delete_staff', 'label' => 'Delete Management Staff'],
        ];

        foreach ($permissions as $p) {
            Permission::create($p);
        }

        // Seed a dummy staff member
        $staff = Staff::create([
            'name' => 'Management Staff',
            'email' => 'staff@staff.com',
            'password' => Hash::make('password123'),
            'is_password_changed' => false,
        ]);

        // Assign some permissions (e.g. view_employees, create_employees, view_payslips, create_payslips)
        $permsToAssign = Permission::whereIn('name', [
            'view_employees',
            'create_employees',
            'edit_employees',
            'view_payslips',
            'create_payslips'
        ])->get();
        $staff->permissions()->attach($permsToAssign);

        // 2. Seed Companies
        $companies = [
            ['name' => 'RM HR Solutions Plotters Private Limited', 'address' => 'Amtala, DH Road, South 24 Parganas, West Bengal, 743503'],
            ['name' => 'Propszy Staffing Solutions', 'address' => 'Salt Lake Sector V, Kolkata, West Bengal, 700091'],
            ['name' => 'Global Logistics Inc.', 'address' => 'Haldia Port Area, Purba Medinipur, West Bengal, 721606']
        ];
        foreach ($companies as $comp) {
            Company::create($comp);
        }

        // 3. Seed Departments
        $depts = ['IT & Development', 'Operations & Logistics', 'Marketing & Sales', 'Human Resources', 'Finance'];
        foreach ($depts as $dept) {
            Department::create(['name' => $dept]);
        }

        // 3. Seed Designations
        $desigs = ['Software Engineer', 'Project Manager', 'Office Assistant', 'Operations Supervisor', 'Accountant', 'HR Coordinator', 'Recruitment Executive'];
        foreach ($desigs as $desig) {
            Designation::create(['name' => $desig]);
        }

        // 4. Seed Bulletins
        Bulletin::create([
            'title' => 'Welcome to our Employee Management System!',
            'content' => 'We are excited to launch our custom employee and candidate portal. Here you can download joining/offer letters, payslips, and check notices.',
            'is_active' => true,
        ]);
        Bulletin::create([
            'title' => 'Annual Performance Reviews 2026',
            'content' => 'Please update your profiles. Review schedules will be sent out shortly.',
            'is_active' => true,
        ]);

        // 5. Seed Site Content for CMS
        SiteContent::create([
            'key' => 'home_banner_title',
            'value' => 'Elite Manpower Hiring & Staffing Solutions',
        ]);
        SiteContent::create([
            'key' => 'home_banner_subtitle',
            'value' => 'Empowering businesses with top-tier talent. Providing workers with seamless career opportunities.',
        ]);
        SiteContent::create([
            'key' => 'about_us_text',
            'value' => 'We are a premier manpower supplier, staffing agency, and recruitment consulting firm with over 10 years of expertise. We connect companies with skilled personnel across multiple domains including IT, operations, finance, and logistics.',
        ]);
        SiteContent::create([
            'key' => 'contact_email',
            'value' => 'info@manpoweragency.com',
        ]);
        SiteContent::create([
            'key' => 'contact_phone',
            'value' => '+91 98765 43210',
        ]);
        SiteContent::create([
            'key' => 'contact_address',
            'value' => 'Amtala, DH Road, Regent Super Market, South 24 Parganas, West Bengal, 743503',
        ]);

        // 6. Seed a Dummy Employee
        Employee::create([
            'employee_id' => 'EMP-2026-0001',
            'first_name' => 'John',
            'last_name' => 'Doe',
            'email' => 'john.doe@example.com',
            'phone' => '+91 99999 88888',
            'password' => Hash::make('password123'),
            'status' => 'active',
            'department_id' => 1, // IT & Development
            'designation_id' => 1, // Software Engineer
            'company_id' => 1, // RM HR Solutions Plotters Private Limited
            'joining_date' => '2026-08-01',
            'salary' => 25000.00,
            'is_password_changed' => false, // Forces password change on first login to let you test it!
        ]);
    }
}
