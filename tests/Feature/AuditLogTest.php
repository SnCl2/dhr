<?php

namespace Tests\Feature;

use App\Models\Admin;
use App\Models\AuditLog;
use App\Models\Employee;
use App\Models\OfferLetter;
use App\Models\Payslip;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class AuditLogTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_cannot_access_audit_logs()
    {
        $response = $this->get(route('admin.audit-logs.index'));
        $response->assertRedirect(route('admin.login'));
    }

    public function test_admin_can_access_audit_logs()
    {
        $this->seed();
        $admin = Admin::first();

        $response = $this->actingAs($admin, 'admin')->get(route('admin.audit-logs.index'));
        $response->assertStatus(200);
        $response->assertSee('Audit &amp; Import Logs', false);
    }

    public function test_employee_import_creates_audit_log()
    {
        $this->seed();
        $admin = Admin::first();

        // Create a fake CSV file for import
        $csvContent = "Full Name,Email ID,Aadhaar Number,Contact Number,Bank Account Number,IFSC Code,NTH Salary,Client Name,Designation,Date of Birth\n"
                    . "John Doe,johndoe@example.com,123456789012,9876543210,123456789012345,SBIN0000123,25000,Acme Corp,Associate,1995-05-15\n"
                    . "Jane Smith,janesmith@example.com,123456,9876543211,123456789012346,SBIN0000123,20000,Acme Corp,Assistant,1996-06-16\n"; // Invalid Aadhaar

        $file = UploadedFile::fake()->createWithContent('import.csv', $csvContent);

        $response = $this->actingAs($admin, 'admin')->post(route('admin.employees.import'), [
            'csv_file' => $file,
        ]);

        $response->assertRedirect();
        
        // Assert AuditLog created
        $log = AuditLog::where('activity_type', 'employee_import')->first();
        $this->assertNotNull($log);
        $this->assertEquals(1, $log->success_count);
        $this->assertEquals(1, $log->failed_count);
        $this->assertEquals('import.csv', $log->filename);
        $this->assertNotNull($log->failed_csv_path);
        
        $details = $log->details;
        $this->assertCount(1, $details['success']);
        $this->assertCount(1, $details['failures']);
        $this->assertEquals('John Doe', $details['success'][0]['name']);
        $this->assertEquals('Row 3', $details['failures'][0]['row_or_file']);
        $this->assertStringContainsString('Aadhaar Number must be exactly 12 digits', $details['failures'][0]['reasons'][0]);
    }

    public function test_bulk_document_upload_creates_audit_log()
    {
        $this->seed();
        $admin = Admin::first();

        // Create a matching employee
        $employee = Employee::create([
            'employee_id' => 'EMP-TEST-99',
            'first_name' => 'Test',
            'last_name' => 'Employee',
            'email' => 'testemp@example.com',
            'password' => Hash::make('password123'),
            'status' => 'active',
        ]);

        $file1 = UploadedFile::fake()->create('EMP-TEST-99.pdf', 100, 'application/pdf');
        $file2 = UploadedFile::fake()->create('EMP-UNKNOWN.pdf', 100, 'application/pdf');

        $response = $this->actingAs($admin, 'admin')->post(route('admin.documents.bulk-upload.submit'), [
            'doc_type' => 'offer_letter',
            'files' => [$file1, $file2],
        ]);

        $response->assertRedirect(route('admin.documents.bulk-upload'));

        $log = AuditLog::where('activity_type', 'bulk_offer_letter_upload')->first();
        $this->assertNotNull($log);
        $this->assertEquals(1, $log->success_count);
        $this->assertEquals(1, $log->failed_count);
        
        $details = $log->details;
        $this->assertCount(1, $details['success']);
        $this->assertCount(1, $details['failures']);
        $this->assertEquals('EMP-TEST-99', $details['success'][0]['identifier']);
        $this->assertEquals('EMP-UNKNOWN.pdf', $details['failures'][0]['row_or_file']);
    }

    public function test_bulk_payslip_csv_generation_creates_audit_log()
    {
        $this->seed();
        $admin = Admin::first();

        $employee = Employee::create([
            'employee_id' => 'EMP-TEST-88',
            'first_name' => 'Payslip',
            'last_name' => 'Emp',
            'email' => 'payslipemp@example.com',
            'password' => Hash::make('password123'),
            'status' => 'active',
            'salary' => 20000.00,
        ]);

        $csvContent = "employee_id,month,basic_salary,working_days,net_payable_days,ot_days,pay_mode,hra,medical_allowance,special_allowance,leave_encashment,ot_allowance,professional_tax,provident_fund,esic,type\n"
                    . "EMP-TEST-88,September 2026,10000,30,30,0,Bank Transfer,500,0,0,0,0,150,1200,0,external\n"
                    . "EMP-NOTFOUND,September 2026,10000,30,30,0,Bank Transfer,500,0,0,0,0,150,1200,0,external\n";

        $file = UploadedFile::fake()->createWithContent('payslips.csv', $csvContent);

        $response = $this->actingAs($admin, 'admin')->post(route('admin.payslips.bulk'), [
            'csv_file' => $file,
        ]);

        $response->assertRedirect();

        $log = AuditLog::where('activity_type', 'bulk_payslip_generate')->first();
        $this->assertNotNull($log);
        $this->assertEquals(1, $log->success_count);
        $this->assertEquals(1, $log->failed_count);

        $details = $log->details;
        $this->assertCount(1, $details['success']);
        $this->assertCount(1, $details['failures']);
        $this->assertEquals('EMP-TEST-88', $details['success'][0]['identifier']);
        $this->assertEquals('EMP-NOTFOUND', $details['failures'][0]['identifier']);
    }

    public function test_get_log_details_json()
    {
        $this->seed();
        $admin = Admin::first();

        $log = AuditLog::create([
            'activity_type' => 'employee_import',
            'performed_by_type' => get_class($admin),
            'performed_by_id' => $admin->id,
            'performed_by_name' => $admin->name,
            'success_count' => 1,
            'failed_count' => 0,
            'details' => [
                'success' => [
                    ['identifier' => 'EMP-01', 'name' => 'John', 'message' => 'Created']
                ],
                'failures' => []
            ]
        ]);

        $response = $this->actingAs($admin, 'admin')->get(route('admin.audit-logs.json', $log));
        $response->assertStatus(200);
        $response->assertJson([
            'success' => [
                ['identifier' => 'EMP-01', 'name' => 'John', 'message' => 'Created']
            ]
        ]);
    }
}
