<?php

namespace App\Listeners;

use App\Enums\ReservationStatus;
use App\Events\ReservationCreated;
use App\Jobs\ProcessReservationConfirmed;
use Illuminate\Support\Facades\Log;

class DispatchReservationCreatedJob
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(ReservationCreated $event): void
    {
        if ($event->reservation->status === ReservationStatus::CONFIRMED->value) {
            ProcessReservationConfirmed::dispatch($event->reservation->id)->onQueue('reservations');
        }
    }
}
