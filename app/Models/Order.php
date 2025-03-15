<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Collection;

class Order extends Model
{
    protected $fillable = [
        'total_price','status','user_id','count'
    ];

    function orderItems(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }
    /**
     * Get all orders with a specific status.
     *
     * @param string $status
     * @return Collection
     */
    public static function byStatus(string $status): Collection
    {
        return self::where('status', $status)->get();
    }
}
