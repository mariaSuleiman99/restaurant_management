<?php

namespace App\Http\Controllers;

use App\Http\Requests\OrderRequest;
use App\Http\Requests\UpdateOrderRequest;
use App\Models\Order;
use App\Helpers\ResponseHelper;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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
        $order = Order::create($request->validated());
        $this->syncOrderItems($request, $order);
        return ResponseHelper::success("Order created successfully.", null, $order, 201);
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
        $this->syncOrderItems($request, $order);
        $order->update($request->validated());

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

        foreach ($orderItems as $itemData) {
            if (isset($itemData['id'])) {
                $orderItem = $order->orderItems()->find($itemData['id']);
                if ($orderItem)
                    $orderItem->update($itemData);
            } else
                $order->orderItems()->create($itemData);
        }
        $order->updatePrice($order['id']);
    }

    public function submitCart(int $id): JsonResponse
    {
        // Find the order by ID
        $order = Order::find($id);
        if (!$order) {
            return ResponseHelper::error("Order not found.", 404);
        }

        $orderItems = $order->orderItems()->get();

        $orders = collect();
        foreach ($orderItems as $orderItem) {
            $restaurant_id = $orderItem->item()->first()['restaurant_id'];
            $restaurant_order = $orders->get($restaurant_id);
            if (!$restaurant_order) {
                $createdOrder = Order::create([
                    'user_id' => $order['user_id'],
                    'total_price' => $orderItem['price'],
                    'count' => $orderItem['count'],
                    'status' => 'Pending',
                ]);
                $orders->put($restaurant_id, $createdOrder['id']);
                $restaurant_order = $createdOrder['id'];
            } else {
                $restaurantOrder = Order::find($restaurant_order);
                $restaurantOrder->update([
                    'count' => $restaurantOrder->count + $orderItem['count'],
                    'total_price' => $restaurantOrder->total_price + $orderItem['price'],
                ]);
            }
            $orderItem->update(['order_id' => $restaurant_order]);
        }
        $order->delete();
        // Return success response
        return ResponseHelper::success("Order status updated successfully.", $order);
    }

}
