<?php

namespace Tests\Feature;

use App\Models\Lead;
use App\Models\LeadAssignment;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DashboardPortalTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_dashboard_renders_with_stats(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        User::factory()->create(['role' => 'sales']);
        User::factory()->create(['role' => 'partner']);

        $response = $this->actingAs($admin)->get(route('admin.dashboard'));

        $response->assertStatus(200);
        $response->assertSee('Total User Terdaftar');
        $response->assertSee('Tim Sales Rep');
        $response->assertSee('Partner Terkoneksi');
    }

    public function test_sales_dashboard_renders_with_own_stats(): void
    {
        $sales = User::factory()->create(['role' => 'sales']);
        Lead::factory()->create(['user_id' => $sales->id]);

        $response = $this->actingAs($sales)->get(route('sales.dashboard'));

        $response->assertStatus(200);
        $response->assertSee('Total Leads');
    }

    public function test_partner_dashboard_renders_assigned_leads_count(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $partner = User::factory()->create(['role' => 'partner']);
        $sales = User::factory()->create(['role' => 'sales']);
        $lead = Lead::factory()->create(['user_id' => $sales->id]);

        LeadAssignment::create([
            'lead_id' => $lead->id,
            'partner_id' => $partner->id,
            'assigned_by' => $admin->id,
            'notes' => 'test',
        ]);

        $response = $this->actingAs($partner)->get(route('partner.dashboard'));

        $response->assertStatus(200);
        $response->assertSee('Total Lead Ditugaskan');
    }

    public function test_role_restricted_dashboard_access(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $sales = User::factory()->create(['role' => 'sales']);
        $partner = User::factory()->create(['role' => 'partner']);

        // Admin tidak bisa akses dashboard sales
        $this->actingAs($admin)->get(route('sales.dashboard'))->assertStatus(403);
        // Sales tidak bisa akses dashboard admin
        $this->actingAs($sales)->get(route('admin.dashboard'))->assertStatus(403);
        // Partner tidak bisa akses dashboard admin
        $this->actingAs($partner)->get(route('admin.dashboard'))->assertStatus(403);
    }
}
