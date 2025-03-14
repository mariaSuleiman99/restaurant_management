<?php

namespace App\Http\Controllers;

use App\Http\Requests\RestaurantRequest;
use App\Http\Requests\UpdateRestaurantRequest;
use App\Models\Restaurant;
use App\Helpers\ResponseHelper;
use Illuminate\Http\JsonResponse;

class RestaurantController extends Controller
{
    /**
     * Display a listing of all restaurants.
     *
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        $restaurants = Restaurant::all();

        // Return success response with the list of restaurants
        return ResponseHelper::success("Restaurants retrieved successfully.", $restaurants);
    }

    /**
     * Store a newly created restaurant in the database.
     *
     * @param RestaurantRequest $request
     * @return JsonResponse
     */
    public function store(RestaurantRequest $request): JsonResponse
    {
        // Validation is handled by the RestaurantRequest class

        // Create the restaurant using validated data
        $restaurant = Restaurant::create($request->validated());

        // Return success response with the created restaurant
        return ResponseHelper::success("Restaurant created successfully.", $restaurant, 201);
    }

    /**
     * Display the specified restaurant.
     *
     * @param int $id
     * @return JsonResponse
     */
    public function show($id): JsonResponse
    {
        // Find the restaurant by ID
        $restaurant = Restaurant::find($id);

        if (!$restaurant) {
            // Return error response if the restaurant is not found
            return ResponseHelper::error("Restaurant not found.", 404);
        }

        // Return success response with the restaurant details
        return ResponseHelper::success("Restaurant retrieved successfully.", $restaurant);
    }

    /**
     * Update the specified restaurant in the database.
     *
     * @param UpdateRestaurantRequest $request
     * @param int $id
     * @return JsonResponse
     */
    public function update(UpdateRestaurantRequest $request, int $id): JsonResponse
    {
        // Find the restaurant by ID
        $restaurant = Restaurant::find($id);

        if (!$restaurant) {
            // Return error response if the restaurant is not found
            return ResponseHelper::error("Restaurant not found.", 404);
        }

        // Update the restaurant with validated data
        $restaurant->update($request->validated());

        // Return success response with the updated restaurant
        return ResponseHelper::success("Restaurant updated successfully.", $restaurant);
    }

    /**
     * Remove the specified restaurant from the database.
     *
     * @param int $id
     * @return JsonResponse
     */
    public function destroy(int $id): JsonResponse
    {
        // Find the restaurant by ID
        $restaurant = Restaurant::find($id);

        if (!$restaurant) {
            // Return error response if the restaurant is not found
            return ResponseHelper::error("Restaurant not found.", 404);
        }

        // Delete the restaurant
        $restaurant->delete();

        // Return success response
        return ResponseHelper::success("Restaurant deleted successfully.");
    }

}
