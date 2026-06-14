<?php

namespace App\Listeners;

use App\Enums\ReservationStatus;
use App\Events\ReservationCreated;
use App\Events\ReservationUpdated;
use App\Jobs\ProcessReservation;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Log;

class CreateReservationAuditLog
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
        $reservation = $event->reservation;

        if ($event instanceof ReservationCreated) {
            $reservation->events()->create([
                'type' => 'created',
                'description' => "Reservation created for guest '{$reservation->guest_name}'",
            ]);

        } else if ($event instanceof ReservationUpdated) {
            $changes = $reservation->getChanges();
            unset($changes['updated_at']);

            foreach ($changes as $field => $newValue) {
                $oldValue = $reservation->getRawOriginal($field); 
                
                $reservation->events()->create([
                    'type' => 'updated',
                    'description' => $this->buildDescription(
                        $field,
                        $oldValue,
                        $newValue
                    ),
                ]);
            }
        }
    }

    private function buildDescription(string $field, mixed $oldValue, mixed $newValue): string {
        return match ($field) {
            'guest_name' =>
                "Guest name changed from '{$oldValue}' to '{$newValue}'",

            'guest_email' =>
                "Guest email changed from '{$oldValue}' to '{$newValue}'",

            'property_name' =>
                "Property changed from '{$oldValue}' to '{$newValue}'",

            'check_in' =>
                "Check-in changed from '{$oldValue}' to '{$newValue}'",

            'check_out' =>
                "Check-out changed from '{$oldValue}' to '{$newValue}'",

            'status' =>
                "Status changed from '{$oldValue}' to '{$newValue}'",

            'amount' =>
                "Amount changed from '{$oldValue}' to '{$newValue}'",

            'notes' =>
                "Notes updated",

            default =>
                "{$field} changed from '{$oldValue}' to '{$newValue}'",
        };
    }
}
