<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;


class Table extends Model
{
    use HasFactory;
    protected $fillable = [
        'capacity','number','restaurant_id'
    ];

    function restaurant():BelongsTo {
        return $this->belongsTo(Restaurant::class);
    }
//
//    public function  forRestaurant(Builder $query, int $restaurantId): Builder
//    {
//        return $query->where('restaurant_id', $restaurantId);
//    }
}
