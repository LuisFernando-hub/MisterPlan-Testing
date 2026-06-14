<?php

uses(Tests\TestCase::class);

use App\Models\Reservation;
use App\Models\ReservationEvents;

it('creates reservation and dispatches created event', function () {
    $reservation = Reservation::factory()->create();

    $event = $reservation->events()->create([
        'type' => 'created',
        'description' => 'Reservation created',
    ]);

    expect($event)->toBeInstanceOf(ReservationEvents::class);

    $this->assertDatabaseHas('reservation_events', [
        'reservation_id' => $reservation->id,
        'type' => 'created',
    ]);
});


it('updates reservation and creates update event', function () {
    $reservation = Reservation::factory()->create([
        'status' => 'pending',
    ]);

    $reservation->update([
        'status' => 'confirmed',
    ]);

    $reservation->events()->create([
        'type' => 'updated',
        'description' => 'Status changed to confirmed',
    ]);

    $this->assertDatabaseHas('reservation_events', [
        'reservation_id' => $reservation->id,
        'type' => 'updated',
    ]);
});