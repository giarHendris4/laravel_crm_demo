<?php

namespace Tests\Unit\Models;

use App\Models\Category;
use App\Models\Customer;
use App\Models\Deal;
use App\Models\Lead;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserModelTest extends TestCase
{
    use RefreshDatabase;

    public function test_fillable_contains_expected_attributes(): void
    {
        $user = new User;

        $this->assertContains('name', $user->getFillable());
        $this->assertContains('email', $user->getFillable());
        $this->assertContains('role', $user->getFillable());
    }

    public function test_hidden_contains_password_and_remember_token(): void
    {
        $user = new User;

        $this->assertContains('password', $user->getHidden());
        $this->assertContains('remember_token', $user->getHidden());
    }

    public function test_user_has_many_leads(): void
    {
        $sales = User::factory()->create(['role' => 'sales']);
        Lead::factory()->count(2)->create(['user_id' => $sales->id]);

        $this->assertInstanceOf(Lead::class, $sales->leads->first());
        $this->assertCount(2, $sales->leads);
    }

    public function test_user_has_many_deals(): void
    {
        $sales = User::factory()->create(['role' => 'sales']);
        $lead = Lead::factory()->create(['user_id' => $sales->id]);

        Deal::create([
            'user_id' => $sales->id,
            'lead_id' => $lead->id,
            'title' => 'Deal A',
            'deal_value' => 50000,
            'stage' => 'proposal',
            'expected_close_date' => now()->addDays(3)->format('Y-m-d'),
        ]);

        $this->assertInstanceOf(Deal::class, $sales->deals->first());
        $this->assertCount(1, $sales->deals);
    }

    public function test_user_has_many_customers(): void
    {
        $sales = User::factory()->create(['role' => 'sales']);

        Customer::create([
            'user_id' => $sales->id,
            'company_name' => 'PT Klien',
            'contact_name' => 'Andi',
            'status' => 'active',
            'total_lifetime_value' => 50000,
        ]);

        $this->assertInstanceOf(Customer::class, $sales->customers->first());
        $this->assertCount(1, $sales->customers);
    }

    public function test_user_can_have_categories(): void
    {
        $partner = User::factory()->create(['role' => 'partner']);
        $category = Category::create(['name' => 'KPR', 'description' => 'Kredit']);

        $partner->categories()->attach($category->id);

        $this->assertInstanceOf(Category::class, $partner->categories->first());
    }

    public function test_partner_has_assigned_leads_via_pivot(): void
    {
        $partner = User::factory()->create(['role' => 'partner']);
        $admin = User::factory()->create(['role' => 'admin']);
        $sales = User::factory()->create(['role' => 'sales']);
        $lead = Lead::factory()->create(['user_id' => $sales->id]);

        $partner->assignedLeads()->attach($lead->id, ['assigned_by' => $admin->id, 'notes' => 'test']);

        $this->assertInstanceOf(Lead::class, $partner->assignedLeads->first());
        $this->assertSame($admin->id, $partner->assignedLeads->first()->pivot->assigned_by);
    }
}
