<?php

namespace Tests\Unit\Models;

use App\Models\Deal;
use App\Models\Lead;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DealModelTest extends TestCase
{
    use RefreshDatabase;

    public function test_fillable_contains_expected_attributes(): void
    {
        $deal = new Deal;

        $this->assertContains('deal_value', $deal->getFillable());
        $this->assertContains('stage', $deal->getFillable());
        $this->assertContains('expected_close_date', $deal->getFillable());
    }

    public function test_deal_value_is_cast_to_decimal(): void
    {
        $sales = User::factory()->create(['role' => 'sales']);
        $lead = Lead::factory()->create(['user_id' => $sales->id]);
        $deal = Deal::create([
            'user_id' => $sales->id,
            'lead_id' => $lead->id,
            'title' => 'Deal A',
            'deal_value' => 100000.50,
            'stage' => 'proposal',
            'expected_close_date' => now()->addDays(3)->format('Y-m-d'),
        ]);

        $this->assertSame('100000.50', $deal->deal_value);
    }

    public function test_expected_close_date_is_cast_to_carbon(): void
    {
        $sales = User::factory()->create(['role' => 'sales']);
        $lead = Lead::factory()->create(['user_id' => $sales->id]);
        $deal = Deal::create([
            'user_id' => $sales->id,
            'lead_id' => $lead->id,
            'title' => 'Deal A',
            'deal_value' => 50000,
            'stage' => 'proposal',
            'expected_close_date' => now()->addDays(3)->format('Y-m-d'),
        ]);

        $this->assertInstanceOf(Carbon::class, $deal->expected_close_date);
    }

    public function test_deal_belongs_to_user_and_lead(): void
    {
        $sales = User::factory()->create(['role' => 'sales']);
        $lead = Lead::factory()->create(['user_id' => $sales->id]);
        $deal = Deal::create([
            'user_id' => $sales->id,
            'lead_id' => $lead->id,
            'title' => 'Deal A',
            'deal_value' => 50000,
            'stage' => 'proposal',
            'expected_close_date' => now()->addDays(3)->format('Y-m-d'),
        ]);

        $this->assertInstanceOf(User::class, $deal->user);
        $this->assertInstanceOf(Lead::class, $deal->lead);
        $this->assertSame($sales->id, $deal->user->id);
        $this->assertSame($lead->id, $deal->lead->id);
    }
}
