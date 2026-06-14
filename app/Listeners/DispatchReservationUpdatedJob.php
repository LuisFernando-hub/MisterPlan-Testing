<?php

namespace App\Listeners;

use App\Enums\ReservationStatus;
use App\Events\ReservationUpdated;
use App\Jobs\ProcessReservation;
use App\Jobs\ProcessReservationCancel;
use App\Jobs\ProcessReservationConfirmed;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Log;

class DispatchReservationUpdatedJob
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
    public function handle(ReservationUpdated $event): void
    {
        if ($event->reservation->status === ReservationStatus::CONFIRMED->value) {
            ProcessReservationConfirmed::dispatch($event->reservation->id)->onQueue('reservations_confirmed');
        }

        if ($event->reservation->status === ReservationStatus::CANCELLED->value) {
            ProcessReservationCancel::dispatch($event->reservation)->onQueue('reservations_cancelled');
        }

    }
}
