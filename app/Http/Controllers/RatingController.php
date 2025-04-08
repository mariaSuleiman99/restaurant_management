<?php

namespace App\Http\Controllers;

use App\Helpers\ResponseHelper;
use App\Http\Requests\RatingRequest;
use App\Http\Requests\UpdateRatingRequest;
use App\Models\Rating;
use Illuminate\Http\JsonResponse;

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

        // Update avg_rate for the associated entity
        $this->updateAvgRate($rating);

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

        // Update avg_rate for the associated entity
        $this->updateAvgRate($rating);

        return ResponseHelper::success("Rating updated successfully.", $rating);
    }

    /**
     * Remove the specified rating from storage.
     */
    public function destroy($id): JsonResponse
    {
        // Find the rating
        $rating = Rating::findOrFail($id);

        // Store the rateable entity before deleting the rating
        $rateable = $rating->rateable;

        // Delete the rating
        $rating->delete();

        // Update avg_rate for the associated entity
        if ($rateable) {
            $this->updateAvgRateForEntity($rateable);
        }

        return ResponseHelper::success("Rating deleted successfully.");
    }

    /**
     * Update the avg_rate for the associated entity (Restaurant or Item).
     */
    private function updateAvgRate(Rating $rating): void
    {
        $rateable = $rating->rateable; // Get the associated entity (Restaurant or Item)

        if ($rateable) {
            $this->updateAvgRateForEntity($rateable);
        }
    }

    /**
     * Update the avg_rate for a given entity (Restaurant or Item).
     */
    private function updateAvgRateForEntity($entity): void
    {
        $avgRate = $entity->ratings()->avg('rating') ?? 0; // Calculate the average rating
        $entity->update(['avg_rate' => $avgRate]); // Update the avg_rate column
    }
}
