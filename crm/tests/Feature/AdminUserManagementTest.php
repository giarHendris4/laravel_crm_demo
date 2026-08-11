<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminUserManagementTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_access_user_management_page(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);

        $response = $this->actingAs($admin)->get('/admin/users');

        $response->assertStatus(200);
    }

    public function test_non_admin_cannot_access_user_management_page(): void
    {
        $sales = User::factory()->create(['role' => 'sales']);

        $response = $this->actingAs($sales)->get('/admin/users');

        // Harus terblokir (403 Forbidden)
        $response->assertStatus(403);
    }

    public function test_admin_can_create_new_user_with_role(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);

        $response = $this->actingAs($admin)->post('/admin/users', [
            'name' => 'New Sales Person',
            'email' => 'newsales@crm.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
            'role' => 'sales',
        ]);

        $response->assertRedirect(route('admin.users.index'));

        $this->assertDatabaseHas('users', [
            'email' => 'newsales@crm.com',
            'role' => 'sales',
        ]);
    }
}