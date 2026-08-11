<?php

namespace Tests\Feature;

use App\Models\Deal;
use App\Models\Lead;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class SalesDashboardTest extends TestCase
{
    use RefreshDatabase;

    protected User $admin;
    protected User $sales;

    protected function setUp(): void
    {
        parent::setUp();
        $this->admin = User::factory()->create(['role' => 'admin']);
        $this->sales = User::factory()->create(['role' => 'sales']);
    }

    #[Test]
    public function test_admin_dapat_melihat_statistik_seluruh_sales()
    {
        // Lead 1 & Deal Closed Won (1.000.000)
        $lead1 = Lead::create([
            'user_id' => $this->sales->id,
            'title' => 'Project Aplikasi POS',
            'company_name' => 'PT Toko Jaya',
            'contact_name' => 'Bpk. Hendra',
            'email' => 'hendra@tokojaya.com',
            'phone' => '081234567890',
            'opportunity_value' => 1000000,
            'status' => 'new'
        ]);

        Deal::create([
            'user_id' => $this->sales->id,
            'lead_id' => $lead1->id,
            'title' => 'Deal POS Jaya',
            'deal_value' => 1000000,
            'stage' => 'closed_won',
            'expected_close_date' => now()->addDays(7)->format('Y-m-d'),
        ]);

        $response = $this->actingAs($this->admin)->get(route('dashboard.index'));

        $response->assertStatus(200);
        $response->assertSee('1.000.000'); 
    }

    #[Test]
    public function test_sales_hanya_melihat_statistik_milik_sendiri()
    {
        $salesLain = User::factory()->create(['role' => 'sales']);
        
        // Lead & Deal Milik Sales sendiri (1.000.000)
        $lead1 = Lead::create([
            'user_id' => $this->sales->id,
            'title' => 'Project POS Resto',
            'company_name' => 'Resto Berkah',
            'contact_name' => 'Ibu Maria',
            'email' => 'maria@restoberkah.com',
            'phone' => '081298765432',
            'opportunity_value' => 1000000,
            'status' => 'new'
        ]);

        Deal::create([
            'user_id' => $this->sales->id,
            'lead_id' => $lead1->id,
            'title' => 'Deal Resto',
            'deal_value' => 1000000,
            'stage' => 'closed_won',
            'expected_close_date' => now()->addDays(7)->format('Y-m-d'),
        ]);

        // Lead & Deal Milik Sales Lain (100.000.000)
        $lead2 = Lead::create([
            'user_id' => $salesLain->id,
            'title' => 'Project ERP Enterprise',
            'company_name' => 'PT Megah Utama',
            'contact_name' => 'Bpk. Agung',
            'email' => 'agung@megah.com',
            'phone' => '081122334455',
            'opportunity_value' => 100000000,
            'status' => 'new'
        ]);

        Deal::create([
            'user_id' => $salesLain->id,
            'lead_id' => $lead2->id,
            'title' => 'Deal ERP',
            'deal_value' => 100000000,
            'stage' => 'closed_won',
            'expected_close_date' => now()->addDays(14)->format('Y-m-d'),
        ]);

        $response = $this->actingAs($this->sales)->get(route('dashboard.index'));

        $response->assertStatus(200);
        $response->assertSee('1.000.000');
        $response->assertDontSee('100.000.000');
    }
}