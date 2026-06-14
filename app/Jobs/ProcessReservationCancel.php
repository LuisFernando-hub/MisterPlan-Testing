<?php

namespace App\Jobs;

use App\Models\Reservation;
use App\Enums\ReservationStatus;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class ProcessReservationCancel implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $tries = 4;
    public $backoff = 20;
    public $timeout = 30;


    /**
     * Create a new job instance.
     */
    public function __construct(
        public Reservation $reservation
    ) {}

    /**
     * Execute the job.
     */
    public function handle(): void
    {
         try {
            $reservation = Reservation::findOrFail($this->reservation->id);

            $reservation->update([
                'status' => ReservationStatus::CANCELLED->value
            ]);

        } catch (\Throwable $e) {
            throw $e;
        }
    }
}
