<?php

namespace Tests\Feature;

use App\Models\Admin;
use App\Models\Inquiry;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class InquiryExportTest extends TestCase
{
    use RefreshDatabase;

    public function test_unauthenticated_admin_redirected_from_inquiries(): void
    {
        $response = $this->get(route('admin.inquiries.index'));
        $response->assertRedirect(route('admin.login'));
    }

    public function test_admin_can_view_and_filter_inquiries_by_date(): void
    {
        $this->seed();
        $admin = Admin::first();

        // Create inquiries with different dates
        $inq1 = new Inquiry([
            'name' => 'John Filtered',
            'email' => 'john@filter.com',
            'subject' => 'Help',
            'message' => 'Need help',
            'status' => 'unread',
        ]);
        $inq1->created_at = '2026-08-10 10:00:00';
        $inq1->save();

        $inq2 = new Inquiry([
            'name' => 'Jane Filtered',
            'email' => 'jane@filter.com',
            'subject' => 'Support',
            'message' => 'Need support',
            'status' => 'unread',
        ]);
        $inq2->created_at = '2026-08-15 10:00:00';
        $inq2->save();

        // Filter for 2026-08-12 to 2026-08-18
        $response = $this->actingAs($admin, 'admin')->get(route('admin.inquiries.index', [
            'start_date' => '2026-08-12',
            'end_date' => '2026-08-18',
        ]));

        $response->assertStatus(200);
        $response->assertSee('Jane Filtered');
        $response->assertDontSee('John Filtered');
    }

    public function test_admin_can_export_filtered_inquiries_to_csv(): void
    {
        $this->seed();
        $admin = Admin::first();

        // Create inquiries
        $inq1 = new Inquiry([
            'name' => 'Export One',
            'email' => 'one@export.com',
            'subject' => 'Sales',
            'message' => 'Looking to buy',
            'status' => 'unread',
        ]);
        $inq1->created_at = '2026-08-10 10:00:00';
        $inq1->save();

        $inq2 = new Inquiry([
            'name' => 'Export Two',
            'email' => 'two@export.com',
            'subject' => 'HR',
            'message' => 'Job query',
            'status' => 'unread',
        ]);
        $inq2->created_at = '2026-08-15 10:00:00';
        $inq2->save();

        // Export with filter
        $response = $this->actingAs($admin, 'admin')->get(route('admin.inquiries.export', [
            'start_date' => '2026-08-12',
            'end_date' => '2026-08-18',
        ]));

        $response->assertStatus(200);
        $response->assertHeader('Content-Type', 'text/csv; charset=UTF-8');
        
        $content = $response->streamedContent();
        $this->assertStringContainsString('Export Two', $content);
        $this->assertStringNotContainsString('Export One', $content);
    }
}
