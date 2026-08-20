<?php

namespace Database\Seeders;

use App\Models\Permission;
use App\Models\Staff;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class PermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
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
            Permission::firstOrCreate(['name' => $p['name']], $p);
        }

        // Seed a dummy staff member safely
        $staff = Staff::firstOrCreate(
            ['email' => 'staff@staff.com'],
            [
                'name' => 'Management Staff',
                'password' => Hash::make('password123'),
                'is_password_changed' => false,
            ]
        );

        // Assign some permissions safely (e.g. view_employees, create_employees, view_payslips, create_payslips)
        $permsToAssign = Permission::whereIn('name', [
            'view_employees',
            'create_employees',
            'edit_employees',
            'view_payslips',
            'create_payslips'
        ])->pluck('id');
        
        $staff->permissions()->syncWithoutDetaching($permsToAssign);
    }
}
