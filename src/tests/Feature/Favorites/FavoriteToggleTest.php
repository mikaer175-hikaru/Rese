<?php

namespace Tests\Feature\Favorites;

use App\Models\Favorite;
use App\Models\Shop;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class FavoriteToggleTest extends TestCase
{
    use RefreshDatabase;

    public function testGuestCannotToggleFavorite(): void
    {
        $shop = Shop::factory()->create();

        $response = $this->post('/favorites/toggle', [
            'shop_id' => $shop->id,
        ]); // TODO: URLを合わせる

        $response->assertStatus(302);
        $response->assertRedirect('/login');
    }

    public function testUserCanAddFavorite(): void
    {
        $user = User::factory()->create();
        $shop = Shop::factory()->create();

        $this->actingAs($user);

        $this->post('/favorites/toggle', [
            'shop_id' => $shop->id,
        ])->assertStatus(302);

        $this->assertDatabaseHas('favorites', [
            'user_id' => $user->id,
            'shop_id' => $shop->id,
        ]);
    }

    public function testUserCanRemoveFavorite(): void
    {
        $user = User::factory()->create();
        $shop = Shop::factory()->create();

        Favorite::factory()->create([
            'user_id' => $user->id,
            'shop_id' => $shop->id,
        ]);

        $this->actingAs($user);

        $this->post('/favorites/toggle', [
            'shop_id' => $shop->id,
        ])->assertStatus(302);

        $this->assertDatabaseMissing('favorites', [
            'user_id' => $user->id,
            'shop_id' => $shop->id,
        ]);
    }

    public function testFavoriteShopsAppearOnMyPage(): void
    {
        $user = User::factory()->create();
        $shop = Shop::factory()->create(['name' => '仙人']);

        Favorite::factory()->create([
            'user_id' => $user->id,
            'shop_id' => $shop->id,
        ]);

        $this->actingAs($user);

        $response = $this->get('/mypage'); // TODO: URLを合わせる

        $response->assertOk();
        $response->assertSee('仙人');
    }
}
