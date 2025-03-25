<?php

namespace App\Http\Controllers;

use App\Http\Requests\OrderItemRequest;
use App\Http\Requests\UpdateOrderItemRequest;
use App\Models\OrderItem;
use App\Helpers\ResponseHelper;
use Illuminate\Http\JsonResponse;

class OrderItemController extends Controller
{
    /**
     * Display a listing of all order items.
     *
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        $orderItems = OrderItem::all();
        return ResponseHelper::success("Order items retrieved successfully.",null, $orderItems);
    }

    /**
     * Store a newly created order item in the database.
     *
     * @param OrderItemRequest $request
     * @return JsonResponse
     */
    public function store(OrderItemRequest $request): JsonResponse
    {
        $orderItem = OrderItem::create($request->validated());
        return ResponseHelper::success("Order item created successfully.", $orderItem, null,201);
    }

    /**
     * Display the specified order item.
     *
     * @param int $id
     * @return JsonResponse
     */
    public function show(int $id): JsonResponse
    {
        $orderItem = OrderItem::find($id);

        if (!$orderItem) {
            return ResponseHelper::error("Order item not found.", 404);
        }

        return ResponseHelper::success("Order item retrieved successfully.", $orderItem);
    }

    /**
     * Update the specified order item in the database.
     *
     * @param UpdateOrderItemRequest $request
     * @param int $id
     * @return JsonResponse
     */
    public function update(UpdateOrderItemRequest $request, int $id): JsonResponse
    {
        $orderItem = OrderItem::find($id);

        if (!$orderItem) {
            return ResponseHelper::error("Order item not found.", 404);
        }

        $orderItem->update($request->validated());
        return ResponseHelper::success("Order item updated successfully.", $orderItem);
    }

    /**
     * Remove the specified order item from the database.
     *
     * @param int $id
     * @return JsonResponse
     */
    public function destroy(int $id): JsonResponse
    {
         $orderItem = OrderItem::find($id);

        if (!$orderItem) {
            return ResponseHelper::error("Order item not found.", 404);
        }
        $orderItem->delete();
        $orderItem->order()->first()->updatePrice( $orderItem->order()->first()['id']);
        return ResponseHelper::success("Order item deleted successfully.");
    }
    /**
     * Get all order items for a specific order.
     *
     * @param int $orderId
     * @return JsonResponse
     */
    public function getByOrderId(int $orderId): JsonResponse
    {
        // Option 1: Using a query scope
        $orderItems = OrderItem::byOrderId($orderId);

        // Option 2: Using a static method
        // $orderItems = OrderItem::getByOrderId($orderId);

        if ($orderItems->isEmpty()) {
            return ResponseHelper::success("No order items found for the given order ID.", []);
        }

        return ResponseHelper::success("Order items retrieved successfully.", $orderItems);
    }
}
