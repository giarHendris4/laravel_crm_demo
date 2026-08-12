<?php

namespace Tests\Feature;

use App\Models\Lead;
use App\Models\LeadAssignment;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PartnerLeadsTest extends TestCase
{
    use RefreshDatabase;

    public function test_partner_only_sees_assigned_leads(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $partner = User::factory()->create(['role' => 'partner']);
        $sales = User::factory()->create(['role' => 'sales']);

        $assigned = Lead::factory()->create(['user_id' => $sales->id, 'title' => 'Lead Ditugaskan Untuk Partner']);
        Lead::factory()->create(['user_id' => $sales->id, 'title' => 'Lead Bukan Milik Partner']);

        LeadAssignment::create([
            'lead_id' => $assigned->id,
            'partner_id' => $partner->id,
            'assigned_by' => $admin->id,
        ]);

        $response = $this->actingAs($partner)->get(route('partner.leads.index'));

        $response->assertStatus(200);
        $response->assertSee('Lead Ditugaskan Untuk Partner');
        $response->assertDontSee('Lead Bukan Milik Partner');
    }

    public function test_partner_can_update_status_of_assigned_lead(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $partner = User::factory()->create(['role' => 'partner']);
        $sales = User::factory()->create(['role' => 'sales']);
        $lead = Lead::factory()->create(['user_id' => $sales->id, 'status' => 'new']);

        LeadAssignment::create([
            'lead_id' => $lead->id,
            'partner_id' => $partner->id,
            'assigned_by' => $admin->id,
        ]);

        $response = $this->actingAs($partner)->patch(route('partner.leads.update-status', $lead), [
            'status' => 'proposal',
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('leads', ['id' => $lead->id, 'status' => 'proposal']);
    }

    public function test_partner_cannot_update_status_of_unassigned_lead(): void
    {
        $partner = User::factory()->create(['role' => 'partner']);
        $sales = User::factory()->create(['role' => 'sales']);
        $lead = Lead::factory()->create(['user_id' => $sales->id, 'status' => 'new']);

        $response = $this->actingAs($partner)->patch(route('partner.leads.update-status', $lead), [
            'status' => 'won',
        ]);

        $response->assertStatus(403);
    }

    public function test_partner_cannot_access_public_leads_crud(): void
    {
        $partner = User::factory()->create(['role' => 'partner']);

        $this->actingAs($partner)->get(route('leads.index'))->assertStatus(403);
        $this->actingAs($partner)->get(route('deals.index'))->assertStatus(403);
        $this->actingAs($partner)->get(route('customers.index'))->assertStatus(403);
    }
}
