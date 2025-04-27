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

    public static function getCart(int $userId): Collection
    {
        return self::where('user_id', $userId)->where('status', 'InCart')->with('orderItems.item')->get();
    }

    public static function getUserOrders(int $userId): Collection
    {
        return self::where('user_id', $userId)->where('status', '<>', 'InCart')->with('orderItems.item')->OrderBy('created_at', 'DESC')->get();
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

    static function search(?array $filters): array
    {
        self::$mainQuery = self::query();
        self::$mainQuery->where('status','<>','InCart');
        if (array_key_exists('restaurant_id', $filters)) {
            $restaurantId = $filters['restaurant_id'];
            self::$mainQuery->whereHas('orderItems', function ($query) use ($restaurantId) {
                $query->whereHas('item', function ($subQuery) use ($restaurantId) {
                    $subQuery->where('restaurant_id', $restaurantId);
                });
            });
        }
        self::$mainQuery->with('orderItems.item');
        $results = parent::search($filters);
        return $results;
    }


}
