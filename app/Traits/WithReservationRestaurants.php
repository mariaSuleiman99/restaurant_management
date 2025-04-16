<?php

namespace App\Traits;

trait WithReservationRestaurants
{
    public static function addReservationRestaurants($results)
    {
        $results = collect($results)->map(function ($model) {
            $restaurant= $model->table()->first()->restaurant()->first();
            $model['restaurant'] = $restaurant;
            return $model;
        });

        return $results;
    }
}
