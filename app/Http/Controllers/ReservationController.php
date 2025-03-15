<?php

namespace App\Http\Controllers;

use App\Http\Requests\ReservationRequest;
use App\Http\Requests\UpdateReservationRequest;
use App\Helpers\ResponseHelper;
use App\Models\Reservations;
use Illuminate\Http\JsonResponse;

class ReservationController extends Controller
{
    /**
     * Display a listing of all reservations.
     *
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        $reservations = Reservations::all();
        return ResponseHelper::success("Reservations retrieved successfully.",null, $reservations);
    }

    /**
     * Store a newly created reservation in the database.
     *
     * @param ReservationRequest $request
     * @return JsonResponse
     */
    public function store(ReservationRequest $request): JsonResponse
    {
        $reservation = Reservations::create($request->validated());
        return ResponseHelper::success("Reservation created successfully.",null, $reservation, 201);
    }

    /**
     * Display the specified reservation.
     *
     * @param int $id
     * @return JsonResponse
     */
    public function show(int $id): JsonResponse
    {
        $reservation = Reservations::find($id);

        if (!$reservation) {
            return ResponseHelper::error("Reservation not found.", 404);
        }

        return ResponseHelper::success("Reservation retrieved successfully.", $reservation);
    }

    /**
     * Update the specified reservation in the database.
     *
     * @param UpdateReservationRequest $request
     * @param int $id
     * @return JsonResponse
     */
    public function update(UpdateReservationRequest $request, int $id): JsonResponse
    {
        $reservation = Reservations::find($id);

        if (!$reservation) {
            return ResponseHelper::error("Reservation not found.", 404);
        }

        $reservation->update($request->validated());
        return ResponseHelper::success("Reservation updated successfully.", $reservation);
    }

    /**
     * Remove the specified reservation from the database.
     *
     * @param int $id
     * @return JsonResponse
     */
    public function destroy(int $id): JsonResponse
    {
        $reservation = Reservations::find($id);

        if (!$reservation) {
            return ResponseHelper::error("Reservation not found.", 404);
        }

        $reservation->delete();
        return ResponseHelper::success("Reservation deleted successfully.");
    }
}
