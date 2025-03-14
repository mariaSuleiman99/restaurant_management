<?php

use App\Http\Controllers\RestaurantController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

//// Define resourceful routes for restaurants
//Route::apiResource('restaurants', RestaurantController::class);

// Group all restaurant-related routes under the 'restaurants' prefix
Route::prefix('restaurants')->group(function () {
    // List all restaurants
    Route::get('/', [RestaurantController::class, 'index']);
    // Create a new restaurant
    Route::post('/', [RestaurantController::class, 'store']);
    // Get a single restaurant by ID
    Route::get('/{id}', [RestaurantController::class, 'show']);
    // Update a restaurant by ID
    Route::put('/{id}', [RestaurantController::class, 'update']);
    // Delete a restaurant by ID
    Route::delete('/{id}', [RestaurantController::class, 'destroy']);
});
