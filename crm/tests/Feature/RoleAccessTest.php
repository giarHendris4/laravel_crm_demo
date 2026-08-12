<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RoleAccessTest extends TestCase
{
    use RefreshDatabase;

    public function test_public_registration_is_disabled(): void
    {
        $response = $this->get('/register');
        $response->assertStatus(404);
    }

    public function test_admin_is_redirected_to_admin_dashboard_after_login(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);

        $response = $this->post('/login', [
            'email' => $admin->email,
            'password' => 'password',
        ]);

        $response->assertRedirect(route('admin.dashboard', absolute: false));
    }

    public function test_sales_is_redirected_to_sales_dashboard_after_login(): void
    {
        $sales = User::factory()->create(['role' => 'sales']);

        $response = $this->post('/login', [
            'email' => $sales->email,
            'password' => 'password',
        ]);

        $response->assertRedirect(route('sales.dashboard', absolute: false));
    }

    public function test_partner_is_redirected_to_partner_dashboard_after_login(): void
    {
        $partner = User::factory()->create(['role' => 'partner']);

        $response = $this->post('/login', [
            'email' => $partner->email,
            'password' => 'password',
        ]);

        $response->assertRedirect(route('partner.dashboard', absolute: false));
    }
}
