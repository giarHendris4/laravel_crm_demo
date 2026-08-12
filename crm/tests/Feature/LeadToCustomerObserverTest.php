<?php

namespace Tests\Feature;

use App\Models\Customer;
use App\Models\Lead;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LeadToCustomerObserverTest extends TestCase
{
    use RefreshDatabase;

    public function test_lead_won_automatically_creates_customer_record(): void
    {
        $sales = User::factory()->create(['role' => 'sales']);

        $lead = Lead::factory()->create([
            'user_id' => $sales->id,
            'status' => 'negotiation',
            'opportunity_value' => 15000000,
        ]);

        // Pastikan belum ada customer
        $this->assertDatabaseCount('customers', 0);

        // Update status lead menjadi 'won'
        $lead->update(['status' => 'won']);

        // Assert bahwa record customer otomatis terbuat
        $this->assertDatabaseCount('customers', 1);
        $this->assertDatabaseHas('customers', [
            'lead_id' => $lead->id,
            'user_id' => $sales->id,
            'company_name' => $lead->company_name,
            'status' => 'active',
            'total_lifetime_value' => 15000000,
        ]);
    }
}
