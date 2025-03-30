<?php

namespace App\Http\Controllers;

use App\Helpers\ImageHelper;
use App\Http\Requests\RestaurantRequest;
use App\Http\Requests\UpdateRestaurantRequest;
use App\Models\Restaurant;
use App\Helpers\ResponseHelper;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;

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
//        $restaurants = Restaurant::paginate(3, ['*'], 'page', 1);

        // Return success response with the list of restaurants
        return ResponseHelper::success("Restaurants retrieved successfully.", null, $restaurants);
    }

    public function search(Request $request): JsonResponse
    {

        $restaurants = Restaurant::search($request->all());
//        $restaurants = Restaurant::paginate(3, ['*'], 'page', 1);

        // Return success response with the list of restaurants
        return ResponseHelper::success("Restaurants retrieved successfully.", null, $restaurants);
    }

    /**
     * Store a newly created restaurant in the database.
     *
     * @param RestaurantRequest $request
     * @return JsonResponse
     */
    public function store(RestaurantRequest $request): JsonResponse
    {


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
    public function show(int $id): JsonResponse
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
    public function update(Request $request, int $id): JsonResponse
    {
        // Find the restaurant by ID
        $restaurant = Restaurant::find($id);

        if (!$restaurant) {
            // Return error response if the restaurant is not found
            return ResponseHelper::error("Restaurant not found.", 404);
        }
        // Get the validated data from the request
//        $validatedData = $request->validated();

        // Log the validated data for debugging
//        Log::info('$validatedData:', $validatedData);
        Log::info('$request->all():', $request->all());
        // Update the restaurant with validated data
        $restaurant->update($request->all());

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
