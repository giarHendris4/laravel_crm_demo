<?php

namespace Tests\Feature;

use App\Models\Lead;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LeadFeatureTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_cannot_access_leads_index(): void
    {
        $response = $this->get(route('leads.index'));

        $response->assertRedirect(route('login'));
    }

    public function test_authenticated_user_can_view_leads_index(): void
    {
        $user = User::factory()->create(['role' => 'sales']);

        $response = $this->actingAs($user)->get(route('leads.index'));

        $response->assertStatus(200);
        $response->assertViewIs('leads.index');
    }

    public function test_sales_can_create_a_lead(): void
    {
        $sales = User::factory()->create(['role' => 'sales']);

        $leadData = [
            'title' => 'Pengadaan Server PT Teknologi',
            'company_name' => 'PT Teknologi Jaya',
            'contact_name' => 'Budi Santoso',
            'email' => 'budi@teknologi.com',
            'phone' => '081234567890',
            'opportunity_value' => 50000000,
            'status' => 'new',
        ];

        $response = $this->actingAs($sales)->post(route('leads.store'), $leadData);

        $response->assertRedirect(route('leads.index'));
        $this->assertDatabaseHas('leads', [
            'title' => 'Pengadaan Server PT Teknologi',
            'company_name' => 'PT Teknologi Jaya',
            'user_id' => $sales->id,
        ]);
    }

    public function test_sales_cannot_edit_other_sales_lead(): void
    {
        $sales1 = User::factory()->create(['role' => 'sales']);
        $sales2 = User::factory()->create(['role' => 'sales']);

        $leadSales1 = Lead::factory()->create(['user_id' => $sales1->id]);

        // Sales 2 mencoba akses halaman edit milik Sales 1
        $response = $this->actingAs($sales2)->get(route('leads.edit', $leadSales1));

        $response->assertStatus(403);
    }
}