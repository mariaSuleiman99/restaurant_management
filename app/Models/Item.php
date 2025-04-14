<?php

namespace App\Models;

use App\Traits\WithUserRatings;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Support\Collection;

class Item extends Generic
{
    use  WithUserRatings;
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
    /**
     * Relationship: An item can have many ratings.
     */
    public function ratings(): MorphMany
    {
        return $this->morphMany(Rating::class, 'rateable');
    }

    public static function search(?array $filters): array
    {
        $results = parent::search($filters);
        $results['items'] = self::addUserRatings($results['items']);
        return $results;
    }

}
