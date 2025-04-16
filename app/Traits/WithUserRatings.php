<?php

namespace App\Traits;

use Illuminate\Support\Facades\Auth;

trait WithUserRatings
{
    public static function addUserRatings($results)
    {
//        $user = Auth::user();
        $user = Auth::guard('sanctum')->user(); // Change 'sanctum' based on your authentication setup
        if ($user) {
            $results = collect($results)->map(function ($model) use ($user) {
                $userRating = $model->ratings()
                    ->where('user_id', $user->id)
                    ->value('rating');

                $model['user_rating'] = $userRating;
                return $model;
            });
        }

        return $results;
    }
}
