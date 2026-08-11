<?php

namespace Tests\Feature;

use App\Models\Lead;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class LeadAssignmentTest extends TestCase
{
    use RefreshDatabase;

    protected User $admin;
    protected User $sales;
    protected User $partnerA;
    protected User $partnerB;
    protected Lead $lead;

    protected function setUp(): void
    {
        parent::setUp();

        $this->admin = User::factory()->create(['role' => 'admin']);
        $this->sales = User::factory()->create(['role' => 'sales']);
        
        // Buat 2 Partner Company terpisah
        $this->partnerA = User::factory()->create(['role' => 'partner', 'name' => 'PT Partner A']);
        $this->partnerB = User::factory()->create(['role' => 'partner', 'name' => 'PT Partner B']);

        $this->lead = Lead::create([
            'user_id' => $this->sales->id,
            'title' => 'Project Aplikasi POS Kuliner',
            'company_name' => 'Resto Nusantara',
            'contact_name' => 'Bpk. Herman',
            'email' => 'herman@resto.com',
            'phone' => '081234567891',
            'opportunity_value' => 30000000,
            'status' => 'new',
        ]);
    }

    #[Test]
    public function test_admin_dapat_menugaskan_lead_ke_partner_company()
    {
        $payload = [
            'lead_id' => $this->lead->id,
            'partner_id' => $this->partnerA->id,
            'notes' => 'Tolong ditindaklanjuti untuk demo produk.',
        ];

        $response = $this->actingAs($this->admin)
            ->post(route('lead-assignments.store'), $payload);

        $response->assertRedirect();
        $this->assertDatabaseHas('lead_assignments', [
            'lead_id' => $this->lead->id,
            'partner_id' => $this->partnerA->id,
        ]);
    }

    #[Test]
    public function test_partner_hanya_dapat_melihat_lead_yang_ditugaskan_kepadanya()
    {
        // Admin assign lead ke Partner A
        $this->partnerA->assignedLeads()->attach($this->lead->id, [
            'assigned_by' => $this->admin->id,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Partner A mengakses -> Harus BISA melihat Lead
        $responseA = $this->actingAs($this->partnerA)->get(route('partner.leads.index'));
        $responseA->assertStatus(200);
        $responseA->assertSee('Project Aplikasi POS Kuliner');

        // Partner B mengakses -> DILARANG / TIDAK BISA melihat Lead milik Partner A
        $responseB = $this->actingAs($this->partnerB)->get(route('partner.leads.index'));
        $responseB->assertStatus(200);
        $responseB->assertDontSee('Project Aplikasi POS Kuliner');
    }

    #[Test]
    public function test_partner_dapat_memperbarui_status_lead_yang_ditugaskan_kepadanya()
    {
        // Admin assign lead ke Partner A
        $this->partnerA->assignedLeads()->attach($this->lead->id, [
            'assigned_by' => $this->admin->id,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $payload = [
            'status' => 'contacted',
        ];

        // Partner A update status lead
        $response = $this->actingAs($this->partnerA)
            ->patch(route('partner.leads.update-status', $this->lead->id), $payload);

        $response->assertRedirect();
        
        $this->assertDatabaseHas('leads', [
            'id' => $this->lead->id,
            'status' => 'contacted',
        ]);
    }
}
