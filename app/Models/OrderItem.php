<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Collection;

class OrderItem extends Model
{
    protected $fillable = [
        'count',
        'price', 'item_id', 'order_id'
    ];

    function items(): HasMany
    {
        return $this->hasMany(Item::class);
    }
    public static function byOrderId($orderId)
    {
        return self::where('order_id', $orderId)->get();
    }

    public function item()
    {
        return $this->belongsTo(Item::class); // 'item_id' is the foreign key
    }

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

}
