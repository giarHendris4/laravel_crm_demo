<?php

namespace Tests\Unit\Models;

use App\Models\Activity;
use App\Models\Customer;
use App\Models\Lead;
use App\Models\LeadCategory;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LeadModelTest extends TestCase
{
    use RefreshDatabase;

    public function test_fillable_contains_expected_attributes(): void
    {
        $lead = new Lead;

        $this->assertContains('lead_category_id', $lead->getFillable());
        $this->assertContains('title', $lead->getFillable());
        $this->assertContains('opportunity_value', $lead->getFillable());
        $this->assertContains('status', $lead->getFillable());
    }

    public function test_lead_belongs_to_user(): void
    {
        $sales = User::factory()->create(['role' => 'sales']);
        $lead = Lead::factory()->create(['user_id' => $sales->id]);

        $this->assertInstanceOf(User::class, $lead->user);
        $this->assertSame($sales->id, $lead->user->id);
    }

    public function test_lead_belongs_to_category(): void
    {
        $sales = User::factory()->create(['role' => 'sales']);
        $category = LeadCategory::create(['name' => 'Korporasi', 'slug' => 'korporasi', 'description' => 'test']);
        $lead = Lead::factory()->create(['user_id' => $sales->id, 'lead_category_id' => $category->id]);

        $this->assertInstanceOf(LeadCategory::class, $lead->category);
        $this->assertSame($category->id, $lead->category->id);
    }

    public function test_lead_has_one_customer(): void
    {
        $sales = User::factory()->create(['role' => 'sales']);
        $lead = Lead::factory()->create(['user_id' => $sales->id]);

        $lead->customer()->create([
            'user_id' => $sales->id,
            'company_name' => 'PT Klien',
            'contact_name' => 'Andi',
            'status' => 'active',
            'total_lifetime_value' => 50000,
        ]);

        $this->assertInstanceOf(Customer::class, $lead->customer);
    }

    public function test_lead_has_many_activities(): void
    {
        $sales = User::factory()->create(['role' => 'sales']);
        $lead = Lead::factory()->create(['user_id' => $sales->id]);

        Activity::create([
            'lead_id' => $lead->id,
            'user_id' => $sales->id,
            'type' => 'call',
            'subject' => 'Follow up',
            'description' => 'call',
            'performed_at' => now(),
        ]);

        $this->assertInstanceOf(Activity::class, $lead->activities->first());
    }

    public function test_lead_has_many_partners_via_pivot(): void
    {
        $sales = User::factory()->create(['role' => 'sales']);
        $partner = User::factory()->create(['role' => 'partner']);
        $lead = Lead::factory()->create(['user_id' => $sales->id]);

        $lead->partners()->attach($partner->id, ['assigned_by' => $sales->id]);

        $this->assertInstanceOf(User::class, $lead->partners->first());
    }
}
