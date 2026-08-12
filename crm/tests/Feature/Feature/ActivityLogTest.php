<?php

namespace Tests\Feature;

use App\Models\Activity;
use App\Models\Lead;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class ActivityLogTest extends TestCase
{
    use RefreshDatabase;

    protected User $sales;

    protected Lead $lead;

    protected function setUp(): void
    {
        parent::setUp();

        $this->sales = User::factory()->create(['role' => 'sales']);

        $this->lead = Lead::create([
            'user_id' => $this->sales->id,
            'title' => 'Project POS Resto',
            'company_name' => 'Resto Sedap',
            'contact_name' => 'Bpk. Budi',
            'email' => 'budi@restosedap.com',
            'phone' => '081234567890',
            'opportunity_value' => 15000000,
            'status' => 'new',
        ]);
    }

    #[Test]
    public function test_sales_dapat_mencatat_aktivitas_interaksi_pada_lead()
    {
        $payload = [
            'lead_id' => $this->lead->id,
            'type' => 'call', // call, meeting, email, note
            'subject' => 'Follow up via Telepon',
            'description' => 'Klien tertarik tetapi minta diskon 10%.',
            'performed_at' => now()->format('Y-m-d H:i:s'),
        ];

        $response = $this->actingAs($this->sales)
            ->post(route('activities.store'), $payload);

        $response->assertRedirect();
        $this->assertDatabaseHas('activities', [
            'lead_id' => $this->lead->id,
            'user_id' => $this->sales->id,
            'type' => 'call',
            'subject' => 'Follow up via Telepon',
        ]);
    }

    #[Test]
    public function test_sales_dapat_melihat_daftar_aktivitas_pada_detail_lead()
    {
        Activity::create([
            'lead_id' => $this->lead->id,
            'user_id' => $this->sales->id,
            'type' => 'meeting',
            'subject' => 'Meeting Demo Produk',
            'description' => 'Demo fitur POS via Zoom berjalan lancar.',
            'performed_at' => now(),
        ]);

        $response = $this->actingAs($this->sales)
            ->get(route('leads.show', $this->lead));

        $response->assertStatus(200);
        $response->assertSee('Meeting Demo Produk');
    }
}
