<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Collection;

class Order extends Generic
{
    protected $fillable = [
        'total_price', 'status', 'user_id', 'count'
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
        return self::where('status', $status)->with('orderItems.item')->get();
    }

    public function updatePrice($id): void
    {
        $totalCount = 0;
        $totalPrice = 0;

        $order = self::with('orderItems.item')->find($id);
        foreach ($order['orderItems'] as $itemData) {
            $totalCount += $itemData['count'];
            $itemData['price'] = $itemData['count'] * $itemData['item']['price'];
            $totalPrice += $itemData['price'];
            $itemData->update();
        }
        $order['total_price'] = $totalPrice;
        $order['count'] = $totalCount;
        $order->update();
    }
}
