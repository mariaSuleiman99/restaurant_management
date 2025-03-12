<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Order extends Model
{
    protected $fillable = [
        'total_price','status'
    ];

    function orderItems(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }
}
