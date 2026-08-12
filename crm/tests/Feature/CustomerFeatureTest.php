<?php

namespace Tests\Feature;

use App\Models\Customer;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CustomerFeatureTest extends TestCase
{
    use RefreshDatabase;

    private function customerData(array $overrides = []): array
    {
        return array_merge([
            'user_id'              => null,
            'lead_id'              => null,
            'company_name'         => 'PT Klien Utama',
            'contact_name'         => 'Andi Pratama',
            'email'                => 'andi@klien.com',
            'phone'                => '081234567890',
            'address'              => 'Jakarta Selatan',
            'status'               => 'active',
            'total_lifetime_value' => 100000000,
            'notes'                => 'Customer prioritas.',
        ], $overrides);
    }

    public function test_guest_cannot_access_customers_index(): void
    {
        $response = $this->get(route('customers.index'));

        $response->assertRedirect(route('login'));
    }

    public function test_admin_can_view_customers_index(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $sales = User::factory()->create(['role' => 'sales']);

        Customer::create($this->customerData(['user_id' => $sales->id]));

        $response = $this->actingAs($admin)->get(route('customers.index'));

        $response->assertStatus(200);
        $response->assertViewIs('customers.index');
        $response->assertSee('PT Klien Utama');
    }

    public function test_sales_can_create_a_customer(): void
    {
        $sales = User::factory()->create(['role' => 'sales']);

        $response = $this->actingAs($sales)->post(route('customers.store'), $this->customerData(['user_id' => $sales->id]));

        $response->assertRedirect(route('customers.index'));
        $this->assertDatabaseHas('customers', [
            'company_name'         => 'PT Klien Utama',
            'contact_name'         => 'Andi Pratama',
            'user_id'              => $sales->id,
            'status'               => 'active',
            'total_lifetime_value' => 100000000,
        ]);
    }

    public function test_customer_store_validates_to_prevent_mass_assignment(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);

        // status invalid harus ditolak oleh validasi
        $response = $this->actingAs($admin)->post(route('customers.store'), $this->customerData([
            'user_id' => $admin->id,
            'status'  => 'invalid_status',
        ]));

        $response->assertSessionHasErrors('status');
        $this->assertDatabaseCount('customers', 0);
    }

    public function test_admin_can_update_a_customer(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $sales = User::factory()->create(['role' => 'sales']);

        $customer = Customer::create($this->customerData(['user_id' => $sales->id]));

        $response = $this->actingAs($admin)->put(route('customers.update', $customer), $this->customerData([
            'user_id'      => $sales->id,
            'company_name' => 'PT Klien Terupdate',
            'status'       => 'inactive',
        ]));

        $response->assertRedirect(route('customers.index'));
        $this->assertDatabaseHas('customers', [
            'id'           => $customer->id,
            'company_name' => 'PT Klien Terupdate',
            'status'       => 'inactive',
        ]);
    }

    public function test_admin_can_delete_a_customer(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $sales = User::factory()->create(['role' => 'sales']);

        $customer = Customer::create($this->customerData(['user_id' => $sales->id]));

        $response = $this->actingAs($admin)->delete(route('customers.destroy', $customer));

        $response->assertRedirect(route('customers.index'));
        $this->assertDatabaseMissing('customers', ['id' => $customer->id]);
    }

    public function test_sales_only_sees_their_own_customers(): void
    {
        $sales1 = User::factory()->create(['role' => 'sales']);
        $sales2 = User::factory()->create(['role' => 'sales']);

        Customer::create($this->customerData([
            'user_id'      => $sales1->id,
            'company_name' => 'PT Milik Sales 1',
        ]));
        Customer::create($this->customerData([
            'user_id'      => $sales2->id,
            'company_name' => 'PT Milik Sales 2',
        ]));

        $response = $this->actingAs($sales1)->get(route('customers.index'));

        $response->assertStatus(200);
        $response->assertSee('PT Milik Sales 1');
        $response->assertDontSee('PT Milik Sales 2');
    }

    public function test_sales_cannot_edit_other_sales_customer(): void
    {
        $sales1 = User::factory()->create(['role' => 'sales']);
        $sales2 = User::factory()->create(['role' => 'sales']);

        $customerSales1 = Customer::create($this->customerData(['user_id' => $sales1->id]));

        $response = $this->actingAs($sales2)->get(route('customers.edit', $customerSales1));

        $response->assertStatus(403);
    }
}
