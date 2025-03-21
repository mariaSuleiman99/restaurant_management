<?php

use App\Http\Controllers\ItemController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\OrderItemController;
use App\Http\Controllers\ReservationController;
use App\Http\Controllers\RestaurantController;
use App\Http\Controllers\TableController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

// Group all restaurant-related routes under the 'restaurants' prefix
Route::prefix('restaurants')->group(function () {
    Route::get('/', [RestaurantController::class, 'index']); // List all restaurants
    Route::post('/', [RestaurantController::class, 'store']); // Create a new restaurant
    Route::get('/{id}', [RestaurantController::class, 'show']); // Get a single restaurant by ID
    Route::put('/{id}', [RestaurantController::class, 'update']); // Update a restaurant by ID
    Route::delete('/{id}', [RestaurantController::class, 'destroy']); // Delete a restaurant by ID
});
Route::prefix('tables')->group(function () {
    Route::get('/', [TableController::class, 'index']); // List all tables
    Route::post('/', [TableController::class, 'store']); // Create a table
    Route::get('/{id}', [TableController::class, 'show']); // Get a single table
    Route::put('/{id}', [TableController::class, 'update']); // Update a table
    Route::delete('/{id}', [TableController::class, 'destroy']); // Delete a table
    Route::get('/by-restaurant/{id}', [TableController::class, 'getTablesByRestaurantId']); // Get all tables by restaurant
});
Route::prefix('reservations')->group(function () {
    Route::get('/', [ReservationController::class, 'index']); // List all reservations
    Route::post('/', [ReservationController::class, 'store']); // Create a reservation
    Route::get('/{id}', [ReservationController::class, 'show']); // Get a single reservation
    Route::put('/{id}', [ReservationController::class, 'update']); // Update a reservation
    Route::delete('/{id}', [ReservationController::class, 'destroy']); // Delete a reservation
});
Route::prefix('orders')->group(function () {
    Route::get('/', [OrderController::class, 'index']); // List all orders
    Route::post('/', [OrderController::class, 'store']); // Create an order
    Route::get('/{id}', [OrderController::class, 'show']); // Get a single order
    Route::put('/{id}', [OrderController::class, 'update']); // Update an order
    Route::delete('/{id}', [OrderController::class, 'destroy']); // Delete an order
    Route::get('/status/{status}', [OrderController::class, 'getOrdersByStatus']); // Get orders by status
    Route::put('/status/{id}', [OrderController::class, 'updateStatus']); // Get orders by status
});
Route::prefix('items')->group(function () {
    Route::get('/', [ItemController::class, 'index']); // List all items
    Route::post('/', [ItemController::class, 'store']); // Create an item
    Route::get('/{id}', [ItemController::class, 'show']); // Get a single item
    Route::put('/{id}', [ItemController::class, 'update']); // Update an item
    Route::delete('/{id}', [ItemController::class, 'destroy']); // Delete an item
    Route::get('/restaurant/{restaurant}', [ItemController::class, 'getByRestaurantId']);
});

Route::prefix('order-items')->group(function () {
    Route::get('/', [OrderItemController::class, 'index']); // List all order items
    Route::post('/', [OrderItemController::class, 'store']); // Create an order item
    Route::get('/{id}', [OrderItemController::class, 'show']); // Get a single order item
    Route::put('/{id}', [OrderItemController::class, 'update']); // Update an order item
    Route::delete('/{id}', [OrderItemController::class, 'destroy']); // Delete an order item
    Route::get('/order/{orderId}', [OrderItemController::class, 'getByOrderId']); // Get order items by order ID
});
