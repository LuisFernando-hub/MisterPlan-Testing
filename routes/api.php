<?php

use App\Http\Controllers\ReservationController;
use Illuminate\Support\Facades\Route;

Route::post('/', function () {
    return response()->json([
        "message" => "Running API"
    ]);
});

Route::group(['prefix' => 'reservations'], function () {
    Route::get('/', [ReservationController::class, 'index'])->middleware(['throttle:60,1']);
    Route::get('/details', [ReservationController::class, 'index'])->middleware(['throttle:60,1']);
    Route::post('/create', [ReservationController::class, 'create'])->middleware(['throttle:5000,1']);
    Route::put('/update/{id}', [ReservationController::class, 'update'])->whereNumber('id')->middleware(['throttle:5000,1']);
    Route::put('/cancel/{id}', [ReservationController::class, 'cancel'])->middleware(['throttle:5000,1']);
});