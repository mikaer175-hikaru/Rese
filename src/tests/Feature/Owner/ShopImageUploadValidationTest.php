<?php

namespace Tests\Feature\Owner;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Tests\TestCase;

class ShopImageUploadValidationTest extends TestCase
{
    use RefreshDatabase;

    public function testImageRequiredMessage(): void
    {
        $owner = User::factory()->create(['role' => 'owner']);
        $this->actingAs($owner);

        $response = $this->post('/owner/shops/store', [
            'name' => 'テスト店',
            'description' => '説明',
            'area_id' => 1,
            'genre_id' => 1,
            'image' => null,
        ]); // TODO: URL/必須項目に合わせる

        $response->assertStatus(302);
        $response->assertSessionHasErrors([
            'image' => '店舗画像を選択してください',
        ]);
    }

    public function testInvalidExtensionMessage(): void
    {
        $owner = User::factory()->create(['role' => 'owner']);
        $this->actingAs($owner);

        $file = UploadedFile::fake()->create('test.gif', 100, 'image/gif');

        $response = $this->post('/owner/shops/store', [
            'name' => 'テスト店',
            'description' => '説明',
            'area_id' => 1,
            'genre_id' => 1,
            'image' => $file,
        ]);

        $response->assertStatus(302);
        $response->assertSessionHasErrors([
            'image' => '店舗画像はjpegまたはpng形式でアップロードしてください',
        ]);
    }

    public function testTooLargeMessage(): void
    {
        $owner = User::factory()->create(['role' => 'owner']);
        $this->actingAs($owner);

        $file = UploadedFile::fake()->create('test.png', 6000, 'image/png'); // 6MB想定

        $response = $this->post('/owner/shops/store', [
            'name' => 'テスト店',
            'description' => '説明',
            'area_id' => 1,
            'genre_id' => 1,
            'image' => $file,
        ]);

        $response->assertStatus(302);
        $response->assertSessionHasErrors([
            'image' => '店舗画像のサイズが大きすぎます',
        ]);
    }
}
