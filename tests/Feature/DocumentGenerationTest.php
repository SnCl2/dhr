<?php

namespace Tests\Feature;

use App\Models\Employee;
use App\Models\Department;
use App\Models\Designation;
use App\Models\OfferLetterTemplate;
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

        $template = OfferLetterTemplate::create([
            'name' => 'Test Template',
            'subject' => 'Welcome to the Team',
            'type' => 'external',
            'content' => "Dear {full_name},\nWe are pleased to offer you the position of {designation} in our {department} department with a salary of Rs. {salary} starting on {joining_date}.\nWelcome aboard!",
        ]);

        $service = new DocumentGeneratorService();

        // 1. Test Offer Letter PDF generation
        $offerLetterPath = $service->generateOfferLetterPdf($employee, $template, [
            'salary' => 25000.00,
            'joining_date' => '01-Aug-2026',
        ]);

        $this->assertNotEmpty($offerLetterPath);
        $this->assertFileExists(public_path($offerLetterPath));

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
}
