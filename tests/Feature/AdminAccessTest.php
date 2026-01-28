<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use Spatie\Permission\Models\Role;

class AdminAccessTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        Role::create([
            'name' => 'admin',
            'guard_name' => 'web'
        ]);
    }

    public function test_admin_can_access_admin_page(): void
    {
        $admin = User::factory()->create();
        $admin->assignRole('admin');

        $response = $this->actingAs($admin)->get('/submit');

        $response->assertOk();
    }

    public function test_user_cannot_access_admin_page(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)
            ->get('/submit')
            ->assertForbidden();
    }

    public function test_guest_is_redirected_to_login(): void
    {
        $this->get('/submit')
            ->assertRedirect(route('login'));
    }
}
