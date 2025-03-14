<?php

use App\Http\Controllers\RestaurantController;
use App\Http\Controllers\TableController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

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
Route::prefix('tables')->group(function () {
    Route::get('/', [TableController::class, 'index']); // List all tables
    Route::post('/', [TableController::class, 'store']); // Create a table
    Route::get('/{id}', [TableController::class, 'show']); // Get a single table
    Route::put('/{id}', [TableController::class, 'update']); // Update a table
    Route::delete('/{id}', [TableController::class, 'destroy']); // Delete a table
    Route::get('/by-restaurant/{id}', [TableController::class, 'getTablesByRestaurantId']); // Get all tables by restaurant
});
