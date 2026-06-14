<?php

namespace App\Services;

use App\Enums\ReservationStatus;
use App\Events\ReservationUpdated;
use App\Models\Reservation;
use App\Repositories\ReservationRepository;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Log;

class ReservationService
{
    public function __construct(
        private ReservationRepository $reservationRepository
    ) {}

    public function index(array $filters = []): LengthAwarePaginator
    {
        return $this->reservationRepository->index($filters);
    }

    public function create(array $data): Reservation
    {
        $reservation = $this->reservationRepository->create($data);

        return $reservation;
    }

    public function update(int $id, array $data): ?Reservation
    {
        $checkReservation = Reservation::findOrFail($id);

        $reservation = $this->reservationRepository->update($checkReservation, $data);

        return $reservation;
    }

    public function cancel(int $id, array $data): ?Reservation
    {
        $checkReservation = Reservation::findOrFail($id);

        $checkReservation = $this->reservationRepository->update($checkReservation, $data);

        return $checkReservation;
    }
}