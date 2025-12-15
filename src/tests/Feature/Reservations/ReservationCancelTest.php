<?php

namespace Tests\Feature\Reservations;

use App\Models\Reservation;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ReservationCancelTest extends TestCase
{
    use RefreshDatabase;

    public function testUserCanCancelOwnReservation(): void
    {
        $user = User::factory()->create();
        $reservation = Reservation::factory()->create([
            'user_id' => $user->id,
        ]);

        $this->actingAs($user);

        $this->post('/reservations/' . $reservation->id . '/cancel')
            ->assertStatus(302); // TODO: URL/メソッドを合わせる

        // 実装が「削除」か「status更新」かで変わる
        // 例：status カラムがある場合
        // $this->assertDatabaseHas('reservations', ['id' => $reservation->id, 'status' => 'canceled']);

        // 例：削除の場合
        // $this->assertDatabaseMissing('reservations', ['id' => $reservation->id]);
    }

    public function testUserCannotCancelOthersReservation(): void
    {
        $user = User::factory()->create();
        $other = User::factory()->create();

        $reservation = Reservation::factory()->create([
            'user_id' => $other->id,
        ]);

        $this->actingAs($user);

        $this->post('/reservations/' . $reservation->id . '/cancel')
            ->assertStatus(403);
    }
}
