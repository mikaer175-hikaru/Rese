<?php

namespace App\Mail;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Collection;

class ReservationReminderMail extends Mailable
{
    use Queueable;
    use SerializesModels;

    public function __construct(
        public User $user,
        public Collection $reservations
    ) {
    }

    public function build(): self
    {
        return $this->subject('【Rese】本日のご予約リマインド')
            ->view('emails.reservation-reminder');
    }
}
