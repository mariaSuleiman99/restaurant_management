<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\OrderItemController;
use App\Http\Controllers\RatingController;
use App\Http\Controllers\ReservationController;
use App\Http\Controllers\RestaurantController;
use App\Http\Controllers\TableController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Public routes
Route::prefix('auth')->group(function () {
    Route::post('/login', [AuthController::class, 'login']);
    Route::post('/register', [AuthController::class, 'register']);
});
// Routes accessible to both admin and user
Route::prefix('restaurants')->group(function () {
    Route::get('/', [RestaurantController::class, 'index']); // List all restaurants
    Route::get('/search', [RestaurantController::class, 'search']); // Search restaurants
    Route::get('/{id}', [RestaurantController::class, 'show']); // Get a single restaurant
});

// Protected routes (require authentication)
Route::middleware('auth:sanctum')->group(function () {

    // User route
    Route::get('/user', function (Request $request) {
        return $request->user();
    });

    // Admin-only routes
    Route::middleware('role:Restaurant_Admin')->group(function () {
        Route::prefix('restaurants')->group(function () {
            Route::post('/', [RestaurantController::class, 'store']); // Create a new restaurant
            Route::put('/{id}', [RestaurantController::class, 'update']); // Update a restaurant
            Route::delete('/{id}', [RestaurantController::class, 'destroy']); // Delete a restaurant
        });
        Route::prefix('tables')->group(function () {
            Route::post('/', [TableController::class, 'store']); // Create a table
            Route::put('/{id}', [TableController::class, 'update']); // Update a table
            Route::delete('/{id}', [TableController::class, 'destroy']); // Delete a table
        });
        Route::prefix('reservations')->group(function () {
            Route::put('/{id}', [ReservationController::class, 'update']); // Update a reservation
            Route::delete('/{id}', [ReservationController::class, 'destroy']); // Delete a reservation
        });
        Route::prefix('orders')->group(function () {
            Route::put('/status/{id}', [OrderController::class, 'updateStatus']); // Update order status
        });
        Route::prefix('items')->group(function () {
            Route::post('/', [ItemController::class, 'store']); // Create an item
            Route::put('/{id}', [ItemController::class, 'update']); // Update an item
            Route::delete('/{id}', [ItemController::class, 'destroy']); // Delete an item
        });
        Route::prefix('order-items')->group(function () {
            Route::post('/', [OrderItemController::class, 'store']); // Create an order item
            Route::put('/{id}', [OrderItemController::class, 'update']); // Update an order item
            Route::delete('/{id}', [OrderItemController::class, 'destroy']); // Delete an order item
        });
    });

    // User-only routes
    Route::middleware("role:User|Restaurant_Admin")->group(function () {
        Route::prefix('restaurants')->group(function () {
            Route::get('/', [RestaurantController::class, 'index']); // List all restaurants
            Route::get('/search', [RestaurantController::class, 'search']); // Search restaurants
            Route::get('/{id}', [RestaurantController::class, 'show']); // Get a single restaurant
        });
        Route::prefix('tables')->group(function () {
            Route::get('/', [TableController::class, 'index']); // List all tables
            Route::get('/{id}', [TableController::class, 'show']); // Get a single table
            Route::get('/by-restaurant/{id}', [TableController::class, 'getTablesByRestaurantId']); // Get tables by restaurant
        });
        Route::prefix('reservations')->group(function () {
            Route::get('/', [ReservationController::class, 'index']); // List all reservations
            Route::post('/', [ReservationController::class, 'store']); // Create a reservation
            Route::get('/{id}', [ReservationController::class, 'show']); // Get a single reservation
        });

        Route::prefix('ratings')->group(function () {
            Route::post('/', [RatingController::class, 'store']); // add rating
            Route::put('/{id}', [RatingController::class, 'update']); // update rating
//            Route::get('/{id}', [RatingController::class, 'show']); // Get a single rating
        });

        Route::prefix('orders')->group(function () {
            Route::get('/', [OrderController::class, 'index']); // List all orders
            Route::get('/search', [OrderController::class, 'search']); // Search orders
            Route::post('/', [OrderController::class, 'store']); // Create an order
            Route::get('/{id}', [OrderController::class, 'show']); // Get a single order
            Route::get('/status/{status}', [OrderController::class, 'getOrdersByStatus']); // Get orders by status
        });
        Route::prefix('items')->group(function () {
            Route::get('/', [ItemController::class, 'index']); // List all items
            Route::get('/search', [ItemController::class, 'search']); // Search items
            Route::get('/{id}', [ItemController::class, 'show']); // Get a single item
            Route::get('/restaurant/{restaurant}', [ItemController::class, 'getByRestaurantId']); // Get items by restaurant
        });
        Route::prefix('order-items')->group(function () {
            Route::get('/', [OrderItemController::class, 'index']); // List all order items
            Route::get('/{id}', [OrderItemController::class, 'show']); // Get a single order item
            Route::get('/order/{orderId}', [OrderItemController::class, 'getByOrderId']); // Get order items by order ID
        });
    });
});
