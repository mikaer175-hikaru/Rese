<?php

namespace Tests\Feature\Shops;

use App\Models\Area;
use App\Models\Genre;
use App\Models\Shop;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ShopIndexSearchTest extends TestCase
{
    use RefreshDatabase;

    public function testGuestCanSeeShopIndex(): void
    {
        $response = $this->get('/shops'); // TODO: URLを合わせる

        $response->assertOk();
    }

    public function testShopNamePartialMatchSearchWorks(): void
    {
        Shop::factory()->create(['name' => 'Shop 仙人']);
        Shop::factory()->create(['name' => 'Shop 銀座']);
        Shop::factory()->create(['name' => '別の店']);

        $response = $this->get('/shops?keyword=Shop'); // TODO: クエリ名を合わせる

        $response->assertOk();
        $response->assertSee('Shop 仙人');
        $response->assertSee('Shop 銀座');
        $response->assertDontSee('別の店');
    }

    public function testFilterByAreaAndGenreWorks(): void
    {
        $tokyo = Area::factory()->create(['name' => '東京都']);
        $osaka = Area::factory()->create(['name' => '大阪府']);

        $sushi = Genre::factory()->create(['name' => '寿司']);
        $ramen = Genre::factory()->create(['name' => 'ラーメン']);

        Shop::factory()->create(['name' => 'A', 'area_id' => $tokyo->id, 'genre_id' => $sushi->id]);
        Shop::factory()->create(['name' => 'B', 'area_id' => $tokyo->id, 'genre_id' => $ramen->id]);
        Shop::factory()->create(['name' => 'C', 'area_id' => $osaka->id, 'genre_id' => $sushi->id]);

        $response = $this->get('/shops?area_id=' . $tokyo->id . '&genre_id=' . $sushi->id);

        $response->assertOk();
        $response->assertSee('A');
        $response->assertDontSee('B');
        $response->assertDontSee('C');
    }

    public function testInvalidAreaShowsValidationMessage(): void
    {
        $response = $this->get('/shops?area_id=999999');

        // 実装が FormRequest で 302 back の場合
        $response->assertStatus(302);
        $response->assertSessionHasErrors([
            'area_id' => '選択されたエリアは存在しません',
        ]);
    }

    public function testInvalidGenreShowsValidationMessage(): void
    {
        $response = $this->get('/shops?genre_id=999999');

        $response->assertStatus(302);
        $response->assertSessionHasErrors([
            'genre_id' => '選択されたジャンルは存在しません',
        ]);
    }
}
