<?php

uses(Tests\TestCase::class);

use App\Models\Reservation;
use App\Models\ReservationEvents;

it('can calculate total nights correctly', function () {
    $reservation = new Reservation([
        'check_in' => '2026-06-10',
        'check_out' => '2026-06-15',
    ]);

    $nights = (int) abs($reservation->check_out->diffInDays($reservation->check_in));

    expect($nights)->toBe(5);
});

it('can create a reservation event instance', function () {
    $reservation = Reservation::factory()->create();

    $event = ReservationEvents::create([
        'reservation_id' => $reservation->id,
        'type' => 'created',
        'description' => 'Reservation created',
    ]);

    expect($event->reservation_id)->toBe($reservation->id)
        ->and($event->type)->toBe('created');
});