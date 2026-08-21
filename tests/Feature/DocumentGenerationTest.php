<?php

namespace Tests\Feature;

use App\Models\Employee;
use App\Models\Department;
use App\Models\Designation;

use App\Services\DocumentGeneratorService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DocumentGenerationTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test FPDF PDF Offer Letter and Payslip generation.
     */
    public function test_fpdf_generation_writes_pdf_files_to_disk(): void
    {
        $this->seed();

        $employee = Employee::create([
            'employee_id' => 'EMP-2026-TEST',
            'first_name' => 'Jane',
            'last_name' => 'Smith',
            'email' => 'jane.smith@example.com',
            'password' => bcrypt('password123'),
            'status' => 'active',
            'salary' => 25000.00,
            'joining_date' => '2026-08-01',
            'department_id' => Department::first()->id,
            'designation_id' => Designation::first()->id,
        ]);

        $service = new DocumentGeneratorService();

        // 1. Test Offer Letter PDF generation
        $offerLetterPath = $service->generateOfferLetterPdf($employee, 'external', [
            'salary' => 25000.00,
            'joining_date' => '01-Aug-2026',
        ]);

        $this->assertNotEmpty($offerLetterPath);
        $this->assertFileExists(public_path($offerLetterPath));

        // 1b. Test Internal Offer Letter PDF generation
        $internalOfferLetterPath = $service->generateOfferLetterPdf($employee, 'internal', [
            'salary' => 25000.00,
            'joining_date' => '01-Aug-2026',
        ]);

        $this->assertNotEmpty($internalOfferLetterPath);
        $this->assertFileExists(public_path($internalOfferLetterPath));

        // 2. Test Payslip PDF generation
        $payslipPath = $service->generatePayslipPdf(
            $employee,
            'August 2026',
            20000.00,
            5000.00,
            1200.00,
            23800.00,
            'external'
        );

        $this->assertNotEmpty($payslipPath);
        $this->assertFileExists(public_path($payslipPath));

        // Clean up files after testing
        if (file_exists(public_path($offerLetterPath))) {
            unlink(public_path($offerLetterPath));
        }
        if (file_exists(public_path($payslipPath))) {
            unlink(public_path($payslipPath));
        }
    }

    public function test_payslip_generation_stores_and_renders_all_detailed_fields(): void
    {
        $this->seed();
        $employee = Employee::first();
        $admin = \App\Models\Admin::first();
        if (!$admin) {
            $admin = \App\Models\Admin::create([
                'name' => 'Test Admin',
                'email' => 'admin.test@example.com',
                'password' => bcrypt('password123'),
            ]);
        }

        $response = $this->actingAs($admin, 'admin')->post(route('admin.payslips.generate.submit'), [
            'employee_id' => $employee->id,
            'month' => 'August 2026',
            'type' => 'external',
            'working_days' => 31,
            'net_payable_days' => 30,
            'ot_days' => 2,
            'pay_mode' => 'Bank Transfer',
            'basic_salary' => 15000.00,
            'hra' => 750.00,
            'medical_allowance' => 500.00,
            'special_allowance' => 1000.00,
            'leave_encashment' => 0.00,
            'ot_allowance' => 1016.13,
            'professional_tax' => 130.00,
            'provident_fund' => 1800.00,
            'esic' => 118.13,
        ]);

        $response->assertRedirect(route('admin.employees.index'));

        // Check if database record exists
        $this->assertDatabaseHas('payslips', [
            'employee_id' => $employee->id,
            'month' => 'August 2026',
            'working_days' => 31,
            'net_payable_days' => 30,
            'ot_days' => 2,
            'pay_mode' => 'Bank Transfer',
            'basic_salary' => 15000.00,
            'hra' => 750.00,
        ]);

        $payslip = \App\Models\Payslip::where('employee_id', $employee->id)->where('month', 'August 2026')->first();
        $this->assertNotNull($payslip);
        $this->assertFileExists(public_path($payslip->pdf_path));

        // Cleanup
        if (file_exists(public_path($payslip->pdf_path))) {
            unlink(public_path($payslip->pdf_path));
        }
    }
}
