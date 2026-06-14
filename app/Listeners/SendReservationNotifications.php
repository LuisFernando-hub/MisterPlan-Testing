<?php

namespace App\Listeners;

use App\Enums\ReservationStatus;
use App\Events\ReservationCreated;
use App\Events\ReservationUpdated;
use App\Jobs\ProcessReservation;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Log;

class SendReservationNotifications
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
    public function handle(ReservationCreated|ReservationUpdated $event): void
    {
        if ($event->reservation->status === ReservationStatus::CONFIRMED->value) {
            Log::info("Listner SendReservationNotificationsConfirmed:handle | reservation: {$event->reservation->id} | status: {$event->reservation->status}");
        }
    }
}
