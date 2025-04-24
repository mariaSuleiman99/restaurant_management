<?php

namespace App\Http\Controllers;

use App\Http\Requests\OrderRequest;
use App\Http\Requests\UpdateOrderRequest;
use App\Models\Order;
use App\Helpers\ResponseHelper;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use function Laravel\Prompts\error;
use function Psy\debug;

class OrderController extends Controller
{
    /**
     * Display a listing of all orders.
     *
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        $orders = Order::where("status", "<>", "InCart")->get();
        return ResponseHelper::success("Orders retrieved successfully.", null, $orders);
    }

    /**
     * Store a newly created order in the database.
     *
     * @param OrderRequest $request
     * @return JsonResponse
     * @throws \Exception
     */
    public function store(OrderRequest $request): JsonResponse
    {
        $validated = $request->validated();
        // Retrieve the authenticated user
        $user = Auth::user();
        // Group order items by restaurant_id
        $groupedItems = $this->groupItemsByRestaurant($validated['order_items']);

        // Create separate orders for each restaurant
        $orders = [];
        foreach ($groupedItems as $restaurantId => $items) {
            $orderData = $validated;
            $orderData['order_items'] = $items;
            $orderData['total_price'] = 0;
            $orderData['user_id'] = $user->id;
            $orderData['count'] = 0;
            // Create the order
            $order = Order::create($orderData);

            // Sync order items
            $this->syncOrderItems($request, $order);
            // Update the total_count and total_price
            $order->updateOrderTotals();
            $orders[] = $order;
        }

        return ResponseHelper::success("Orders created successfully.", null, $orders, 201);
    }

    /**
     * Group order items by restaurant_id.
     */
    private function groupItemsByRestaurant(array $items): array
    {
        $groupedItems = [];

        foreach ($items as $itemData) {
            $itemId = $itemData['item_id'];
            $item = \App\Models\Item::find($itemId);

            if (!$item) {
                throw new \Exception("Item with ID {$itemId} not found.");
            }

            $restaurantId = $item->restaurant_id;
            $groupedItems[$restaurantId][] = $itemData;
        }

        return $groupedItems;
    }

    /**
     * Display the specified order.
     *
     * @param int $id
     * @return JsonResponse
     */
    public function show(int $id): JsonResponse
    {
        $order = Order::with('orderItems.item')->find($id);

        if (!$order) {
            return ResponseHelper::error("Order not found.", 404);
        }

        return ResponseHelper::success("Order retrieved successfully.", $order);
    }

    /**
     * Update the specified order in the database.
     *
     * @param UpdateOrderRequest $request
     * @param int $id
     * @return JsonResponse
     * @throws \Exception
     */
    public function update(UpdateOrderRequest $request, int $id): JsonResponse
    {
        $order = Order::find($id);

        if (!$order) {
            return ResponseHelper::error("Order not found.", 404);
        }

        $validated = $request->validated();

        // Validate that all items belong to the same restaurant
        $this->validateOrderItemsForRestaurant($validated['order_items'], $order->restaurant_id);

        // Sync order items
        $this->syncOrderItems($request, $order);

        // Update the order
        $order->update($validated);
        // Update the total_count and total_price
        $order->updateOrderTotals();

        return ResponseHelper::success("Order updated successfully.", $order);
    }

    /**
     * Remove the specified order from the database.
     *
     * @param int $id
     * @return JsonResponse
     */
    public function destroy(int $id): JsonResponse
    {
        $order = Order::find($id);

        if (!$order) {
            return ResponseHelper::error("Order not found.", 404);
        }

        $order->delete();
        return ResponseHelper::success("Order deleted successfully.");
    }

    public function getOrdersByStatus(string $status): JsonResponse
    {
        $allowedStatuses = ['InDelivery', 'InProcess', 'Pending', 'InCart'];

        if (!in_array($status, $allowedStatuses)) {
            return ResponseHelper::error("Invalid status provided.");
        }

        $orders = Order::byStatus($status);
        if ($orders->isEmpty()) {
            return ResponseHelper::success("No orders found for the given status.", null, []);
        }

        return ResponseHelper::success("Orders retrieved successfully.", null, $orders);
    }

    public function getUserCart(): JsonResponse
    {
        $user = Auth::user();
        $orders = Order::getCart($user->getAuthIdentifier());
        if ($orders->isEmpty()) {
            return ResponseHelper::success("No Cart found for the given status.", null, []);
        }

        return ResponseHelper::success("Cart retrieved successfully.", null, $orders);
    }

    public function getUserOrders(): JsonResponse
    {
        $user = Auth::user();
        $orders = Order::getUserOrders($user->getAuthIdentifier());
        if ($orders->isEmpty()) {
            return ResponseHelper::success("No Cart found for the given status.", null, []);
        }

        return ResponseHelper::success("Cart retrieved successfully.", null, $orders);
    }

    public function updateStatus(int $id, Request $request): JsonResponse
    {
        // Validate the request
        $validated = request()->validate([
            'status' => 'required|in:InDelivery,InProcess,Pending,InCart',
        ]);

        // Find the order by ID
        $order = Order::find($id);

        if (!$order) {
            return ResponseHelper::error("Order not found.", 404);
        }

        // Update the status
        $order->update(['status' => $validated['status']]);

        // Return success response
        return ResponseHelper::success("Order status updated successfully.", $order);
    }

    public function search(Request $request): JsonResponse
    {
        // Call the search method and get the results
        $searchResults = Order::search($request->all());
        // Extract items and total count
        $orders = $searchResults['items'];
        $totalCount = $searchResults['total_count'];
        return ResponseHelper::success("Orders retrieved successfully.", null, $orders, $totalCount);
    }

    function syncOrderItems($request, $order): void
    {
        if (!$request->has('order_items')) return;

        $orderItems = $request->input('order_items');
        $user = Auth::user();

        // Validate that all items belong to the same restaurant
        $firstItemId = $orderItems[0]['item_id'] ?? null;
        $firstItem = \App\Models\Item::find($firstItemId);

        if (!$firstItem) {
            throw new \Exception("First item in the order is invalid.");
        }

        $restaurantId = $firstItem->restaurant_id;

        foreach ($orderItems as $itemData) {
            $itemId = $itemData['item_id'];
            $item = \App\Models\Item::find($itemId);

            if (!$item) {
                throw new \Exception("Item with ID {$itemId} not found.");
            }


            // Update or create the order item
            if (isset($itemData['id'])) {
                $orderItem = $order->orderItems()->find($itemData['id']);
                if ($orderItem)
                    $orderItem->update($itemData);
            } else {
                $order->orderItems()->create($itemData);
            }
        }

        // Update the order price
        $order->updatePrice($order['id']);
    }

    /**
     * Validate that all items belong to the same restaurant.
     */
    private function validateOrderItemsForRestaurant(array $items, int $restaurantId): void
    {
        foreach ($items as $itemData) {
            $itemId = $itemData['item_id'];
            $item = \App\Models\Item::find($itemId);

            if (!$item) {
                throw new \Exception("Item with ID {$itemId} not found.");
            }

            if ($item->restaurant_id !== $restaurantId) {
                throw new \Exception("All items in the order must belong to the same restaurant.");
            }
        }
    }
//    function syncOrderItems($request, $order): void
//    {
//        if (!$request->has('order_items')) return;
//        $orderItems = $request->input('order_items');
//
//        foreach ($orderItems as $itemData) {
//            if (isset($itemData['id'])) {
//                $orderItem = $order->orderItems()->find($itemData['id']);
//                if ($orderItem)
//                    $orderItem->update($itemData);
//            } else
//                $order->orderItems()->create($itemData);
//        }
//        $order->updatePrice($order['id']);
//    }
}
