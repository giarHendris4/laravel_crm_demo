<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SecurityTest extends TestCase
{
    use RefreshDatabase;

    public function test_logout_redirects_to_login_page(): void
    {
        $user = User::factory()->create(['role' => 'admin']);

        $response = $this->actingAs($user)->post('/logout');

        $response->assertRedirect(route('login', absolute: false));
        $this->assertGuest();
    }

    public function test_login_is_rate_limited_after_five_attempts(): void
    {
        $user = User::factory()->create(['role' => 'admin']);

        for ($i = 0; $i < 5; $i++) {
            $this->post('/login', [
                'email' => $user->email,
                'password' => 'wrong-password',
            ]);
        }

        // Percobaan ke-6 harus diblokir oleh rate limiter (HTTP 429)
        $response = $this->post('/login', [
            'email' => $user->email,
            'password' => 'wrong-password',
        ]);

        $this->assertSame(429, $response->getStatusCode());
    }

    public function test_mass_assignment_is_guarded_on_lead_creation(): void
    {
        $sales = User::factory()->create(['role' => 'sales']);

        // user_id tidak bisa dimanipulasi dari request saat sales membuat lead
        $response = $this->actingAs($sales)->post(route('leads.store'), [
            'user_id' => 99999, // mencoba set user_id orang lain
            'title' => 'Lead Aman',
            'company_name' => 'PT Aman',
            'contact_name' => 'Andi',
            'email' => 'andi@aman.com',
            'phone' => '081',
            'opportunity_value' => 50000,
            'status' => 'new',
        ]);

        $response->assertRedirect(route('leads.index'));
        $this->assertDatabaseHas('leads', ['title' => 'Lead Aman', 'user_id' => $sales->id]);
        $this->assertDatabaseMissing('leads', ['user_id' => 99999]);
    }
}
