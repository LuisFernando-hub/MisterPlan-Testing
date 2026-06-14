<?php

namespace App\Providers;

use App\Events\ReservationCreated;
use App\Events\ReservationUpdated;
use App\Listeners\CreateReservationAuditLog;
use App\Listeners\DispatchReservationCreatedJob;
use App\Listeners\DispatchReservationUpdatedJob;
use App\Listeners\SendReservationNotifications;
use Illuminate\Support\ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    protected $listen = [
        ReservationCreated::class => [
            DispatchReservationCreatedJob::class,
            SendReservationNotifications::class,
            CreateReservationAuditLog::class,
        ],
        ReservationUpdated::class => [
            DispatchReservationUpdatedJob::class,
            SendReservationNotifications::class,
            CreateReservationAuditLog::class,
        ],
    ];

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
