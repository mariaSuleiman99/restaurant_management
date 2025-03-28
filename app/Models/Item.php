<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Collection;

class Item extends Generic
{
    protected $fillable = [
        'name',
        'description',
        'price','image','restaurant_id'
    ];
    function restaurant(): BelongsTo
    {
        return $this->belongsTo(Restaurant::class);
    }
    /**
     * Scope a query to only include items for a specific restaurant.
     *
     * @param int $restaurantId
     * @return Collection
     */
    public static function byRestaurant(int $restaurantId): Collection
    {
        return self::where('restaurant_id', $restaurantId)->get();
    }
}
