<?php

namespace App\Http\Controllers;

use App\Http\Requests\ItemRequest;
use App\Http\Requests\UpdateItemRequest;
use App\Models\Item;
use App\Helpers\ResponseHelper;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ItemController extends Controller
{
    /**
     * Display a listing of all items.
     *
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        $items = Item::all();
        return ResponseHelper::success("Items retrieved successfully.",null, $items);
    }

    /**
     * Store a newly created item in the database.
     *
     * @param ItemRequest $request
     * @return JsonResponse
     */
    public function store(ItemRequest $request): JsonResponse
    {
        $item = Item::create($request->validated());
        return ResponseHelper::success("Item created successfully.",null, $item, 201);
    }

    /**
     * Display the specified item.
     *
     * @param int $id
     * @return JsonResponse
     */
    public function show(int $id): JsonResponse
    {
        $item = Item::find($id);

        if (!$item) {
            return ResponseHelper::error("Item not found.", 404);
        }

        return ResponseHelper::success("Item retrieved successfully.", $item);
    }

    /**
     * Update the specified item in the database.
     *
     * @param UpdateItemRequest $request
     * @param int $id
     * @return JsonResponse
     */
    public function update(UpdateItemRequest $request, int $id): JsonResponse
    {
        $item = Item::find($id);

        if (!$item) {
            return ResponseHelper::error("Item not found.", 404);
        }

        $item->update($request->validated());
        return ResponseHelper::success("Item updated successfully.", $item);
    }

    /**
     * Remove the specified item from the database.
     *
     * @param int $id
     * @return JsonResponse
     */
    public function destroy(int $id): JsonResponse
    {
        $item = Item::find($id);

        if (!$item) {
            return ResponseHelper::error("Item not found.", 404);
        }

        $item->delete();
        return ResponseHelper::success("Item deleted successfully.");
    }

    /**
     * Get all items for a specific restaurant.
     *
     * @param int $restaurantId
     * @return JsonResponse
     */
    public function getByRestaurantId(int $restaurantId): JsonResponse
    {
        $items = Item::byRestaurant($restaurantId);

        if ($items->isEmpty()) {
            return ResponseHelper::success("No items found for the given restaurant ID.", []);
        }

        return ResponseHelper::success("Items retrieved successfully.",null, $items);
    }

    public function search(Request $request): JsonResponse
    {
        // Call the search method and get the results
        $searchResults = Item::search($request->all());
        // Extract items and total count
        $items = $searchResults['items'];
        $totalCount = $searchResults['total_count'];
        return ResponseHelper::success("Items retrieved successfully.", null, $items,$totalCount);
    }
    public function ItemsOrders($restaurantId): JsonResponse
    {
        $ItemsOrders=Item::itemsOrders($restaurantId);

        return ResponseHelper::success("Items retrieved successfully.", null, $ItemsOrders);
    }
}
