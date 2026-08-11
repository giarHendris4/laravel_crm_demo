<?php

namespace Tests\Feature;

use App\Models\Lead;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LeadAuthorizationTest extends TestCase
{
    use RefreshDatabase;

    protected User $admin;
    protected User $salesA;
    protected User $salesB;
    protected Lead $leadSalesA;

    protected function setUp(): void
    {
        parent::setUp();

        $this->admin = User::factory()->create(['role' => 'admin']);
        $this->salesA = User::factory()->create(['role' => 'sales']);
        $this->salesB = User::factory()->create(['role' => 'sales']);

        $this->leadSalesA = Lead::create([
            'user_id' => $this->salesA->id,
            'title' => 'Project Restoran ABC',
            'company_name' => 'PT Restoran ABC',
            'contact_name' => 'Bpk. Budi',
            'email' => 'budi@abc.com',
            'phone' => '08123456789',
            'opportunity_value' => 15000000,
            'status' => 'new',
        ]);
    }

    /** @test */
    public function test_sales_dapat_melihat_halaman_index_lead()
    {
        $response = $this->actingAs($this->salesA)->get(route('leads.index'));

        $response->assertStatus(200);
        $response->assertViewHas('leads');
    }

    /** @test */
    public function test_sales_hanya_melihat_lead_miliknya_sendiri_di_index()
    {
        $leadSalesB = Lead::create([
            'user_id' => $this->salesB->id,
            'title' => 'Project Hotel XYZ',
            'company_name' => 'PT Hotel XYZ',
            'contact_name' => 'Ibu Siska',
            'opportunity_value' => 50000000,
            'status' => 'proposal',
        ]);

        $response = $this->actingAs($this->salesA)->get(route('leads.index'));

        $response->assertStatus(200);
        $response->assertSee($this->leadSalesA->title);
        $response->assertDontSee($leadSalesB->title);
    }

    /** @test */
    public function test_admin_dapat_melihat_semua_lead_termasuk_milik_sales_lain()
    {
        $leadSalesB = Lead::create([
            'user_id' => $this->salesB->id,
            'title' => 'Project Hotel XYZ',
            'company_name' => 'PT Hotel XYZ',
            'contact_name' => 'Ibu Siska',
            'opportunity_value' => 50000000,
            'status' => 'proposal',
        ]);

        $response = $this->actingAs($this->admin)->get(route('leads.index'));

        $response->assertStatus(200);
        $response->assertSee($this->leadSalesA->title);
        $response->assertSee($leadSalesB->title);
    }

    /** @test */
    public function test_sales_dapat_mengedit_lead_miliknya_sendiri()
    {
        $response = $this->actingAs($this->salesA)->get(route('leads.edit', $this->leadSalesA));

        // Memastikan tidak diblokir oleh middleware / policy otorisasi (bukan 403)
        $response->assertDontSee('403');
        $response->assertStatus(200);
    }

    /** @test */
    public function test_sales_dilarang_mengedit_lead_milik_sales_lain()
    {
        $response = $this->actingAs($this->salesB)->get(route('leads.edit', $this->leadSalesA));

        $response->assertStatus(403);
    }

    /** @test */
    public function test_sales_dilarang_mengupdate_lead_milik_sales_lain()
    {
        $payload = [
            'title' => 'Pembajakan Title Lead',
            'company_name' => 'PT Restoran ABC',
            'contact_name' => 'Bpk. Budi',
            'opportunity_value' => 20000000,
            'status' => 'negotiation',
        ];

        $response = $this->actingAs($this->salesB)->put(route('leads.update', $this->leadSalesA), $payload);

        $response->assertStatus(403);
    }

    /** @test */
    public function test_admin_dapat_mengedit_dan_mengupdate_lead_milik_sales_manapun()
    {
        $payload = [
            'title' => 'Title Diubah Admin',
            'company_name' => 'PT Restoran ABC',
            'contact_name' => 'Bpk. Budi',
            'opportunity_value' => 20000000,
            'status' => 'won',
        ];

        $response = $this->actingAs($this->admin)->put(route('leads.update', $this->leadSalesA), $payload);

        $response->assertRedirect(route('leads.index'));
        $this->assertDatabaseHas('leads', [
            'id' => $this->leadSalesA->id,
            'title' => 'Title Diubah Admin',
            'status' => 'won',
        ]);
    }

    /** @test */
    public function test_sales_dilarang_menghapus_lead_milik_sales_lain()
    {
        $response = $this->actingAs($this->salesB)->delete(route('leads.destroy', $this->leadSalesA));

        $response->assertStatus(403);
        $this->assertDatabaseHas('leads', ['id' => $this->leadSalesA->id]);
    }

    /** @test */
    public function test_sales_dapat_menghapus_lead_miliknya_sendiri()
    {
        $response = $this->actingAs($this->salesA)->delete(route('leads.destroy', $this->leadSalesA));

        $response->assertRedirect(route('leads.index'));
        $this->assertDatabaseMissing('leads', ['id' => $this->leadSalesA->id]);
    }
}