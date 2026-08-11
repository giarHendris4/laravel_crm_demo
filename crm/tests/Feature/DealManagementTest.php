<?php

namespace Tests\Feature;

use App\Models\Deal;
use App\Models\Lead;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class DealManagementTest extends TestCase
{
    use RefreshDatabase;

    protected User $sales;
    protected User $admin;
    protected Lead $lead;

    protected function setUp(): void
    {
        parent::setUp();

        $this->sales = User::factory()->create(['role' => 'sales']);
        $this->admin = User::factory()->create(['role' => 'admin']);

        $this->lead = Lead::create([
            'user_id' => $this->sales->id,
            'title' => 'Project Aplikasi POS',
            'company_name' => 'PT Retail Sejahtera',
            'contact_name' => 'Bpk. Hendra',
            'email' => 'hendra@pos.com',
            'phone' => '081299887766',
            'opportunity_value' => 25000000,
            'status' => 'new',
        ]);
    }

    #[Test]
    public function test_sales_dapat_melihat_halaman_index_deal()
    {
        $response = $this->actingAs($this->sales)->get(route('deals.index'));

        $response->assertStatus(200);
    }

    #[Test]
    public function test_sales_dapat_mengonversi_lead_menjadi_deal()
    {
        $payload = [
            'lead_id' => $this->lead->id,
            'title' => 'Deal - Project Aplikasi POS',
            'deal_value' => 25000000,
            'stage' => 'qualification',
            'expected_close_date' => now()->addDays(14)->format('Y-m-d'),
        ];

        $response = $this->actingAs($this->sales)->post(route('deals.store'), $payload);

        $response->assertRedirect(route('deals.index'));
        $this->assertDatabaseHas('deals', [
            'lead_id' => $this->lead->id,
            'stage' => 'qualification',
        ]);
    }
    #[Test]
    public function test_sales_dapat_mengupdate_stage_deal_miliknya()
    {
        $deal = Deal::create([
            'user_id' => $this->sales->id,
            'lead_id' => $this->lead->id,
            'title' => 'Project Aplikasi POS',
            'deal_value' => 25000000,
            'stage' => 'qualification',
            'expected_close_date' => now()->addDays(14)->format('Y-m-d'),
        ]);

        $payload = [
            'stage' => 'negotiation',
        ];

        $response = $this->actingAs($this->sales)->patch(route('deals.update', $deal), $payload);

        $response->assertRedirect(route('deals.index'));
        $this->assertDatabaseHas('deals', [
            'id' => $deal->id,
            'stage' => 'negotiation',
        ]);
    }

    #[Test]
    public function test_sales_dilarang_mengakses_atau_mengubah_deal_sales_lain()
    {
        $otherSales = User::factory()->create(['role' => 'sales']);

        $dealSalesLain = Deal::create([
            'user_id' => $otherSales->id,
            'lead_id' => $this->lead->id,
            'title' => 'Project Hotel Mewah',
            'deal_value' => 100000000,
            'stage' => 'proposal',
            'expected_close_date' => now()->addDays(30)->format('Y-m-d'),
        ]);

        $response = $this->actingAs($this->sales)->patch(route('deals.update', $dealSalesLain), [
            'stage' => 'closed_won',
        ]);

        $response->assertStatus(403);
    }
}