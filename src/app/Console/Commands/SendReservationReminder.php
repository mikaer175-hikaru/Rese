<?php

namespace App\Console\Commands;

use App\Mail\ReservationReminderMail;
use App\Models\Reservation;
use Carbon\CarbonImmutable;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class SendReservationReminder extends Command
{
    protected $signature = 'reminder:reservations-today';

    protected $description = 'Send reminder emails for today reservations at 9:00';

    public function handle(): int
    {
        $today = CarbonImmutable::today(config('app.timezone'));

        $query = Reservation::query()
            ->with(['user', 'shop'])
            ->whereDate('reserve_date', $today)
            ->whereNull('reminder_sent_at');

        $sentCount = 0;

        $query->chunkById(200, function ($reservations) use (&$sentCount) {
            $grouped = $reservations->groupBy('user_id');

            foreach ($grouped as $userReservations) {
                $user = $userReservations->first()->user;

                if (!$user || !$user->email) {
                    continue;
                }

                Mail::to($user->email)->send(new ReservationReminderMail($user, $userReservations));

                $reservationIds = $userReservations->pluck('id')->all();

                Reservation::whereIn('id', $reservationIds)->update([
                    'reminder_sent_at' => now(),
                ]);

                $sentCount += count($reservationIds);
            }
        });

        $this->info("Reminder sent. reservations count={$sentCount}");

        return self::SUCCESS;
    }
}
