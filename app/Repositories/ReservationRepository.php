<?php

namespace App\Repositories;

use App\Models\Reservation;
use Illuminate\Pagination\LengthAwarePaginator;

class ReservationRepository
{
    public function index(array $filters = []): LengthAwarePaginator
    {
        $query = Reservation::query();

        $this->applyFilters($query, $filters);

        return $query->paginate($filters['per_page'] ?? 20);
    } 

    public function create(array $data): Reservation
    {
        return Reservation::create($data);
    }

    public function update(Reservation $reservation, array $data): Reservation
    {
        $reservation->update($data);
        return $reservation;
    }

    private function applyFilters($query, array $filters): void
    {
        $query->when(!empty($filters['status']), function($query) use($filters) {
            $query->where('status', $filters['status']);
        })
        ->when(!empty($filters['search']), function($query) use($filters) {
            $query->where(function ($query) use ($filters) {
                $query->where('guest_name', 'like', '%' . $filters['search'] . '%')
                    ->orWhere('guest_email', 'like', '%' . $filters['search'] . '%')
                    ->orWhere('property_name', 'like', '%' . $filters['search'] . '%');
            });
        })
        ->when(!empty($filters['check_in']), function($query) use($filters) {
            $query->whereDate('check_in', $filters['check_in']);
        })
        ->when(!empty($filters['check_out']), function($query) use($filters) {
            $query->whereDate('check_out', $filters['check_out']);
        });
    }
}