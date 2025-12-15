<?php

namespace Tests\Feature\Reservations;

use App\Mail\ReservationConfirmedMail;
use App\Models\Reservation;
use App\Models\Shop;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class ReservationCreateTest extends TestCase
{
    use RefreshDatabase;

    public function testGuestIsRedirectedToLoginWhenReserving(): void
    {
        $shop = Shop::factory()->create();

        $response = $this->post('/reservations', [
            'shop_id' => $shop->id,
            'reserve_date' => now()->addDays(1)->format('Y-m-d'),
            'reserve_time' => '17:00',
            'number_of_people' => 1,
            'note' => '備考',
        ]); // TODO: URLを合わせる

        $response->assertStatus(302);
        $response->assertRedirect('/login');
    }

    public function testReservationValidationMessages(): void
    {
        $user = User::factory()->create();
        $shop = Shop::factory()->create();

        $this->actingAs($user);

        $response = $this->post('/reservations', [
            'shop_id' => $shop->id,
            'reserve_date' => '',
            'reserve_time' => '',
            'number_of_people' => '',
        ]);

        $response->assertStatus(302);
        $response->assertSessionHasErrors([
            'reserve_date' => '予約日は本日以降の日付を指定してください',
            'reserve_time' => '予約時間を正しい形式（HH:MM）で入力してください',
            'number_of_people' => '予約人数は1〜20の範囲で入力してください',
        ]);
    }

    public function testReservationMustBeInFutureMessage(): void
    {
        $user = User::factory()->create();
        $shop = Shop::factory()->create();

        $this->actingAs($user);

        $response = $this->post('/reservations', [
            'shop_id' => $shop->id,
            'reserve_date' => now()->format('Y-m-d'),
            'reserve_time' => now()->subMinutes(10)->format('H:i'),
            'number_of_people' => 1,
        ]);

        $response->assertStatus(302);
        $response->assertSessionHasErrors([
            'reserve_datetime' => '予約日時は現在より未来の時刻を指定してください',
        ]); // TODO: エラーキーを実装に合わせる（reserve_date / reserve_time / reserve_datetime 等）
    }

    public function testReservationCreatesRecordAndSendsEmail(): void
    {
        Mail::fake();

        $user = User::factory()->create(['email' => 'test@example.com']);
        $shop = Shop::factory()->create();

        $this->actingAs($user);

        $payload = [
            'shop_id' => $shop->id,
            'reserve_date' => now()->addDays(1)->format('Y-m-d'),
            'reserve_time' => '17:00',
            'number_of_people' => 2,
            'note' => '備考',
        ];

        $this->post('/reservations', $payload)->assertStatus(302);

        $this->assertDatabaseHas('reservations', [
            'user_id' => $user->id,
            'shop_id' => $shop->id,
            'reserve_date' => $payload['reserve_date'],
        ]);

        Mail::assertSent(ReservationConfirmedMail::class, function ($mail) use ($user) {
            return $mail->hasTo($user->email);
        });
    }
}
zz