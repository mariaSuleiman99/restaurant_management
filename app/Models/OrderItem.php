<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class OrderItem extends Model
{
    protected $fillable = [
        'count',
        'price','item_id','order_id'
    ];
    function items(): HasMany
    {
        return $this->hasMany(Item::class);
    }
}
