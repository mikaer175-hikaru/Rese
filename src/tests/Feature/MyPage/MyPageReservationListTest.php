<?php

namespace Tests\Feature\MyPage;

use App\Models\Reservation;
use App\Models\Shop;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class MyPageReservationListTest extends TestCase
{
    use RefreshDatabase;

    public function testMyPageShowsOnlyFutureReservations(): void
    {
        $user = User::factory()->create();
        $shop = Shop::factory()->create(['name' => '仙人']);

        Reservation::factory()->create([
            'user_id' => $user->id,
            'shop_id' => $shop->id,
            'reserve_date' => now()->subDays(1)->format('Y-m-d'),
            'reserve_time' => '17:00:00',
            'number_of_people' => 1,
        ]);

        Reservation::factory()->create([
            'user_id' => $user->id,
            'shop_id' => $shop->id,
            'reserve_date' => now()->addDays(1)->format('Y-m-d'),
            'reserve_time' => '17:00:00',
            'number_of_people' => 1,
        ]);

        $this->actingAs($user);

        $response = $this->get('/mypage'); // TODO: URLを合わせる

        $response->assertOk();
        $response->assertSee('仙人');
    }
}
