<?php

namespace Tests\Unit\Models;

use App\Models\Customer;
use App\Models\Lead;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CustomerModelTest extends TestCase
{
    use RefreshDatabase;

    public function test_fillable_contains_expected_attributes(): void
    {
        $customer = new Customer;

        $this->assertContains('company_name', $customer->getFillable());
        $this->assertContains('contact_name', $customer->getFillable());
        $this->assertContains('status', $customer->getFillable());
        $this->assertContains('total_lifetime_value', $customer->getFillable());
    }

    public function test_customer_belongs_to_user_and_lead(): void
    {
        $sales = User::factory()->create(['role' => 'sales']);
        $lead = Lead::factory()->create(['user_id' => $sales->id]);

        $customer = Customer::create([
            'user_id' => $sales->id,
            'lead_id' => $lead->id,
            'company_name' => 'PT Klien',
            'contact_name' => 'Andi',
            'status' => 'active',
            'total_lifetime_value' => 50000,
        ]);

        $this->assertInstanceOf(User::class, $customer->user);
        $this->assertInstanceOf(Lead::class, $customer->lead);
        $this->assertSame($sales->id, $customer->user->id);
        $this->assertSame($lead->id, $customer->lead->id);
    }
}
