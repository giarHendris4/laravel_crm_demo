<?php

namespace Tests\Feature;

use App\Models\Deal;
use App\Models\Lead;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BackendResourceCrudTest extends TestCase
{
    use RefreshDatabase;

    private function makeDeal(User $owner): Deal
    {
        $lead = Lead::factory()->create(['user_id' => $owner->id]);

        return Deal::create([
            'user_id' => $owner->id,
            'lead_id' => $lead->id,
            'title' => 'Deal Test',
            'deal_value' => 50000000,
            'stage' => 'proposal',
            'expected_close_date' => now()->addDays(3)->format('Y-m-d'),
        ]);
    }

    // ===================== DEALS =====================

    public function test_admin_can_access_deal_create_page(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);

        $response = $this->actingAs($admin)->get(route('deals.create'));

        $response->assertStatus(200);
        $response->assertViewIs('deals.create');
    }

    public function test_sales_can_access_show_and_edit_own_deal(): void
    {
        $sales = User::factory()->create(['role' => 'sales']);
        $deal = $this->makeDeal($sales);

        $this->actingAs($sales)->get(route('deals.show', $deal))->assertStatus(200)->assertViewIs('deals.show');
        $this->actingAs($sales)->get(route('deals.edit', $deal))->assertStatus(200)->assertViewIs('deals.edit');
    }

    public function test_sales_cannot_edit_other_sales_deal(): void
    {
        $sales1 = User::factory()->create(['role' => 'sales']);
        $sales2 = User::factory()->create(['role' => 'sales']);
        $deal = $this->makeDeal($sales1);

        $response = $this->actingAs($sales2)->get(route('deals.edit', $deal));

        $response->assertStatus(403);
    }

    public function test_admin_can_update_and_delete_deal(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $deal = $this->makeDeal($admin);

        $this->actingAs($admin)->put(route('deals.update', $deal), [
            'title' => 'Deal Terupdate',
            'deal_value' => 75000000,
            'stage' => 'closed_won',
            'expected_close_date' => now()->addDays(1)->format('Y-m-d'),
        ])->assertRedirect(route('deals.index'));

        $this->assertDatabaseHas('deals', ['id' => $deal->id, 'title' => 'Deal Terupdate', 'stage' => 'closed_won']);

        $this->actingAs($admin)->delete(route('deals.destroy', $deal))->assertRedirect(route('deals.index'));
        $this->assertDatabaseMissing('deals', ['id' => $deal->id]);
    }

    // ===================== ADMIN USERS =====================

    public function test_admin_can_access_user_show_and_edit(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $user = User::factory()->create(['role' => 'sales']);

        $this->actingAs($admin)->get(route('admin.users.show', $user))->assertStatus(200)->assertViewIs('admin.users.show');
        $this->actingAs($admin)->get(route('admin.users.edit', $user))->assertStatus(200)->assertViewIs('admin.users.edit');
    }

    public function test_admin_can_update_user(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $user = User::factory()->create(['role' => 'sales', 'email' => 'old@crm.com']);

        $this->actingAs($admin)->put(route('admin.users.update', $user), [
            'name' => 'Nama Baru',
            'email' => 'new@crm.com',
            'role' => 'partner',
        ])->assertRedirect(route('admin.users.index'));

        $this->assertDatabaseHas('users', ['id' => $user->id, 'name' => 'Nama Baru', 'email' => 'new@crm.com', 'role' => 'partner']);
    }

    public function test_admin_can_delete_other_user(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $user = User::factory()->create(['role' => 'sales']);

        $this->actingAs($admin)->delete(route('admin.users.destroy', $user))->assertRedirect(route('admin.users.index'));
        $this->assertDatabaseMissing('users', ['id' => $user->id]);
    }

    public function test_admin_cannot_delete_own_account(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);

        $this->actingAs($admin)->delete(route('admin.users.destroy', $admin));

        $this->assertDatabaseHas('users', ['id' => $admin->id]);
    }

    public function test_non_admin_cannot_access_admin_user_pages(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $sales = User::factory()->create(['role' => 'sales']);

        $this->actingAs($sales)->get(route('admin.users.show', $admin))->assertStatus(403);
        $this->actingAs($sales)->get(route('admin.users.edit', $admin))->assertStatus(403);
    }

    public function test_partner_cannot_access_leads_deals_customers(): void
    {
        $partner = User::factory()->create(['role' => 'partner']);

        $this->actingAs($partner)->get(route('leads.index'))->assertStatus(403);
        $this->actingAs($partner)->get(route('leads.create'))->assertStatus(403);
        $this->actingAs($partner)->get(route('deals.index'))->assertStatus(403);
        $this->actingAs($partner)->get(route('deals.create'))->assertStatus(403);
        $this->actingAs($partner)->get(route('customers.index'))->assertStatus(403);
        $this->actingAs($partner)->get(route('customers.create'))->assertStatus(403);
    }

    public function test_sales_can_access_leads_deals_customers(): void
    {
        $sales = User::factory()->create(['role' => 'sales']);

        $this->actingAs($sales)->get(route('leads.index'))->assertStatus(200);
        $this->actingAs($sales)->get(route('deals.index'))->assertStatus(200);
        $this->actingAs($sales)->get(route('customers.index'))->assertStatus(200);
    }
}
