<?php

namespace App\Http\Controllers;

use App\Helpers\ResponseHelper;
use App\Http\Requests\RatingRequest;
use App\Http\Requests\UpdateRatingRequest;
use App\Models\Rating;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;
use function Psy\debug;

class RatingController extends Controller
{
    /**
     * Store a newly created rating in storage.
     */
    public function store(RatingRequest $request): JsonResponse
    {
        $validated = $request->validated();
        // Create the rating
        $rating = Rating::create($validated);
        return ResponseHelper::success("Rating created successfully.", $rating, null, 201);
    }

//    /**
//     * Store a newly created rating for a restaurant.
//     */
//    public function addRatingForRestaurant(RatingRequest $request): JsonResponse
//    {
//        // Get the authenticated user's ID from the token
//        $userId = $request->user()->id;
//        // Validate the request data
//        $validated = $request->validated();
//        // Add the user_id and rateable_type to the validated data
//        $validated['user_id'] = $userId;
//        $validated['rateable_type'] = 'App\Models\Restaurant'; // Hardcode the rateable_type for restaurants
//        // Create the rating
//        $rating = Rating::create($validated);
//        return ResponseHelper::success("Rating created successfully.", $rating, null, 201);
//    }
//
//    public function addRatingForItem(RatingRequest $request): JsonResponse
//    {
//        // Get the authenticated user's ID from the token
//        $userId = $request->user()->id;
//// Merge the user_id and rateable_type into the request data
//        $request->merge([
//            'user_id' => $userId,
//            'rateable_type' => 'App\Models\Item', // Set the rateable_type based on the route parameter
//        ]);
//
//        // Validate the request data
//        $validated = $request->validated();
//
//        // Add the user_id and rateable_type to the validated data
//        $validated['user_id'] = $userId;
//        $validated['rateable_type'] = ; // Hardcode the rateable_type for items
//        // Create the rating
//        $rating = Rating::create($validated);
//        return ResponseHelper::success("Rating created successfully.", $rating, null, 201);
//    }

    public function addRating(RatingRequest $request, string $type): JsonResponse
    {
        // Map the type to the corresponding model class
        $rateableTypeMap = [
            'restaurant' => 'App\Models\Restaurant',
            'item' => 'App\Models\Item',
        ];
        if (!isset($rateableTypeMap[$type])) {
            return ResponseHelper::error("Invalid rateable type.", 400);
        }
        // Get the authenticated user's ID from the token
        $userId = $request->user()->id;
        // Merge the user_id and rateable_type into the validated data
        $validated = $request->validated();
        $validated['user_id'] = $userId;
        $validated['rateable_type'] = $rateableTypeMap[$type];
        // Create the rating
        $rating =
            Rating::where("user_id", $userId)->
                    where('rateable_id', $validated['rateable_id'])->
                    where('rateable_type', $validated['rateable_type'])->
                    first();
        if ($rating)
            $rating->update(['rating' => $validated['rating']]);
        else
            $rating = Rating::create($validated);
        return ResponseHelper::success("Rating created successfully.", $rating, null, 201);
    }

    /**
     * Update the specified rating in storage.
     */
    public function update(UpdateRatingRequest $request, $id): JsonResponse
    {
        $validated = $request->validated();

        // Find the rating
        $rating = Rating::findOrFail($id);

        // Update the rating
        if (isset($validated['rating'])) {
            $rating->rating = $validated['rating'];
        }
        $rating->save();

        return ResponseHelper::success("Rating updated successfully.", $rating);
    }

    /**
     * Remove the specified rating from storage.
     */
    public function destroy($id): JsonResponse
    {
        // Find the rating
        $rating = Rating::findOrFail($id);
        // Delete the rating
        $rating->delete();
        return ResponseHelper::success("Rating deleted successfully.");
    }

}
