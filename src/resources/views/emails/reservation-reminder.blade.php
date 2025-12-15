<p>{{ $user->name }} 様</p>

<p>本日のご予約をお知らせします。</p>

<ul>
    @foreach ($reservations as $reservation)
        <li>
            {{ $reservation->shop->name }}
            / {{ $reservation->reserve_date }} {{ \Carbon\Carbon::parse($reservation->reserve_time)->format('H:i') }}
            / {{ $reservation->number_of_people }}名
        </li>
    @endforeach
</ul>

<p>ご来店をお待ちしております。</p>
<p>Rese運営事務局</p>