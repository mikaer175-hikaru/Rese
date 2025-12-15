<?php

namespace Tests\Feature\Reservations;

use App\Models\Reservation;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ReservationUpdateTest extends TestCase
{
    use RefreshDatabase;

    public function testReservationUpdateValidation(): void
    {
        $user = User::factory()->create();
        $reservation = Reservation::factory()->create([
            'user_id' => $user->id,
        ]);

        $this->actingAs($user);

        $response = $this->post('/reservations/' . $reservation->id . '/update', [
            'reserve_date' => now()->format('Y-m-d'),
            'reserve_time' => '',
            'number_of_people' => 0,
        ]);

        $response->assertStatus(302);
        $response->assertSessionHasErrors([
            'reserve_date',
            'reserve_time',
            'number_of_people',
        ]);
    }
}
