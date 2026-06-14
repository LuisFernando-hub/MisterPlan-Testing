<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateReservationRequest;
use App\Http\Requests\UpdateReservationRequest;
use App\Http\Resources\ReservationResource;
use App\Jobs\ProcessReservation;
use App\Services\ReservationService;
use Carbon\Carbon;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ReservationController extends Controller
{
    public function __construct(
        private ReservationService $reservationService
    ) {}

    public function index(Request $request): JsonResponse
    {
        try {
            $reservations = $this->reservationService->index($request->all());

            return response()->json([
                "success" => true,
                "message" => "Reservation Data.",
                "data" => $reservations
            ], 200);

        } catch(\Exception $e) {
            return response()->json([
                "success" => false,
                "message" => "Error",
                "error" => $e->getMessage()
            ], 500);
        }
    }

    public function create(CreateReservationRequest $request): JsonResponse
    {
        try {
            $reservation = $this->reservationService->create($request->all());

            return response()->json([
                "success" => true,
                "message" => "Reservation Created.",
                "data" => $reservation
            ], 201);

        } catch(\Exception $e) {
            return response()->json([
                "success" => false,
                "message" => "Error",
                "error" => $e->getMessage()
            ], 500);
        }
    }

    public function update(UpdateReservationRequest $request, int $id): JsonResponse
    {
        try {
            $reservation = $this->reservationService->update($id, $request->all());

            return response()->json([
                "success" => true,
                "message" => "Reservation updated.",
                "data" => $reservation
            ], 200);

        } catch (ModelNotFoundException $e) {
            return response()->json([
                "success" => false,
                "message" => "Reserva not found.",
                "data" => []
            ], 404);

        } catch (Exception $e) {
            return response()->json([
                "success" => false,
                "message" => "Error",
                "error" => $e->getMessage()
            ], 500);
        }
    }

    public function cancel(Request $request, int $id): JsonResponse
    {
        try {
            $request->validate([
                'status' => 'in:cancelled'
            ]);

            $reservation = $this->reservationService->cancel($id, $request->all());

            return response()->json([
                "success" => true,
                "message" => "Reservation canceled.",
                "data" => $reservation
            ], 200);

        } catch(\Exception $e) {
            return response()->json([
                "success" => false,
                "message" => "Error",
                "error" => $e->getMessage()
            ], 500);
        }
    }
}
