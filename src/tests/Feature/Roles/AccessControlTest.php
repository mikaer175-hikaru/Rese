<?php

namespace Tests\Feature\Roles;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AccessControlTest extends TestCase
{
    use RefreshDatabase;

    public function testUserCannotAccessAdminPages(): void
    {
        $user = User::factory()->create([
            'role' => 'user', // TODO: role設計に合わせる（role_id / enum / spatie等）
        ]);

        $this->actingAs($user);

        $this->get('/admin')
            ->assertStatus(403); // TODO: 403 or admin login redirect に合わせる
    }

    public function testOwnerCanAccessOwnerPagesButNotAdminPages(): void
    {
        $owner = User::factory()->create([
            'role' => 'owner',
        ]);

        $this->actingAs($owner);

        $this->get('/owner')
            ->assertOk(); // TODO: URLを合わせる

        $this->get('/admin')
            ->assertStatus(403);
    }

    public function testAdminCanAccessAdminPages(): void
    {
        $admin = User::factory()->create([
            'role' => 'admin',
        ]);

        $this->actingAs($admin);

        $this->get('/admin')
            ->assertOk();
    }
}
