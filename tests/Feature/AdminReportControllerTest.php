<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminReportControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_bookings_report_is_accessible_to_admins()
    {
        $admin = User::factory()->create(['role' => 'admin']);

        $response = $this->actingAs($admin)->get('/admin/reports/bookings');

        $response->assertStatus(200);
        $response->assertViewIs('admin.reports.bookings');
    }
}
