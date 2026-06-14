<?php

namespace App\Jobs;

use App\Models\Reservation;
use App\Enums\ReservationStatus;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Log;

class GenerateDailyReservationSummary implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $tries = 4;
    public $backoff = 20;
    public $timeout = 30;


    /**
     * Create a new job instance.
     */
    public function __construct() {}

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $today = Carbon::today();

        $created = Reservation::query()
            ->whereDate('created_at', $today)
            ->count();

        $confirmed = Reservation::query()
            ->whereDate('updated_at', $today)
            ->where('status', 'confirmed')
            ->count();

        $cancelled = Reservation::query()
            ->whereDate('updated_at', $today)
            ->where('status', 'cancelled')
            ->count();

        $revenue = Reservation::query()
            ->whereDate('created_at', $today)
            ->sum('amount');

        Log::info('DAILY RESERVATION SUMMARY', [
            'date' => $today->toDateString(),
            'created' => $created,
            'confirmed' => $confirmed,
            'cancelled' => $cancelled,
            'revenue' => $revenue,
        ]);
    }
}
