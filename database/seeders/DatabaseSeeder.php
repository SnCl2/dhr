<?php

namespace Database\Seeders;

use App\Models\Admin;
use App\Models\Department;
use App\Models\Designation;
use App\Models\Bulletin;
use App\Models\SiteContent;
use App\Models\Employee;
use App\Models\Company;
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

        // 2. Seed Companies
        $companies = [
            ['name' => 'RMHRSolutions Plotters Private Limited', 'address' => 'Amtala, DH Road, South 24 Parganas, West Bengal, 743503'],
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
            'company_id' => 1, // RMHRSolutions Plotters Private Limited
            'joining_date' => '2026-08-01',
            'salary' => 25000.00,
            'is_password_changed' => false, // Forces password change on first login to let you test it!
        ]);
    }
}
