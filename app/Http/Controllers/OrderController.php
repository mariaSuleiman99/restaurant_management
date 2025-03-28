<?php

namespace App\Http\Controllers;

use App\Http\Requests\OrderRequest;
use App\Http\Requests\UpdateOrderRequest;
use App\Models\Order;
use App\Helpers\ResponseHelper;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    /**
     * Display a listing of all orders.
     *
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        $orders = Order::all();
        return ResponseHelper::success("Orders retrieved successfully.",null, $orders);
    }

    /**
     * Store a newly created order in the database.
     *
     * @param OrderRequest $request
     * @return JsonResponse
     */
    public function store(OrderRequest $request): JsonResponse
    {
        $order = Order::create($request->validated());
        return ResponseHelper::success("Order created successfully.",null, $order, 201);
    }

    /**
     * Display the specified order.
     *
     * @param int $id
     * @return JsonResponse
     */
    public function show(int $id): JsonResponse
    {
        $order = Order::find($id);

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
     */
    public function update(UpdateOrderRequest $request, int $id): JsonResponse
    {
        $order = Order::find($id);

        if (!$order) {
            return ResponseHelper::error("Order not found.", 404);
        }

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
            return ResponseHelper::success("No orders found for the given status.",null, []);
        }

        return ResponseHelper::success("Orders retrieved successfully.",null, $orders);
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
        return ResponseHelper::success("Orders retrieved successfully.", null, $orders,$totalCount);
    }
}
