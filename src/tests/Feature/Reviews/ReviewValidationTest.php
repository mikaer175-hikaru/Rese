<?php

namespace Tests\Feature\Reviews;

use App\Models\Reservation;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ReviewValidationTest extends TestCase
{
    use RefreshDatabase;

    public function testRatingOutOfRangeMessage(): void
    {
        $user = User::factory()->create();
        $reservation = Reservation::factory()->create([
            'user_id' => $user->id,
        ]);

        $this->actingAs($user);

        $response = $this->post('/reviews', [
            'reservation_id' => $reservation->id,
            'rating' => 6,
            'comment' => 'ok',
        ]); // TODO: URLを合わせる

        $response->assertStatus(302);
        $response->assertSessionHasErrors([
            'rating' => '評価は1〜5の範囲で入力してください',
        ]);
    }

    public function testCommentTooLongMessage(): void
    {
        $user = User::factory()->create();
        $reservation = Reservation::factory()->create([
            'user_id' => $user->id,
        ]);

        $this->actingAs($user);

        $response = $this->post('/reviews', [
            'reservation_id' => $reservation->id,
            'rating' => 5,
            'comment' => str_repeat('a', 256),
        ]);

        $response->assertStatus(302);
        $response->assertSessionHasErrors([
            'comment' => 'コメントは255文字以内で入力してください',
        ]);
    }
}
