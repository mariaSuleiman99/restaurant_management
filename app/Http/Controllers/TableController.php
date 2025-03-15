<?php

namespace App\Http\Controllers;

use App\Http\Requests\TableRequest;
use App\Http\Requests\UpdateTableRequest;
use App\Models\Restaurant;
use App\Models\Table;
use App\Helpers\ResponseHelper;
use Illuminate\Http\JsonResponse;
use PhpParser\Node\Expr\List_;

class TableController extends Controller
{
    /**
     * Display a listing of all tables.
     *
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        $tables = Table::all();
        return ResponseHelper::success("Tables retrieved successfully.",null, $tables);
    }

    /**
     * Store a newly created table in the database.
     *
     * @param TableRequest $request
     * @return JsonResponse
     */
    public function store(TableRequest $request): JsonResponse
    {
        $table = Table::create($request->validated());
        return ResponseHelper::success("Table created successfully.", $table, 201);
    }

    /**
     * Display the specified table.
     *
     * @param int $id
     * @return JsonResponse
     */
    public function show(int $id): JsonResponse
    {
        $table = Table::find($id);

        if (!$table) {
            return ResponseHelper::error("Table not found.", 404);
        }

        return ResponseHelper::success("Table retrieved successfully.", $table);
    }

    /**
     * Update the specified table in the database.
     *
     * @param UpdateTableRequest $request
     * @param int $id
     * @return JsonResponse
     */
    public function update(UpdateTableRequest $request, int $id): JsonResponse
    {
        $table = Table::find($id);

        if (!$table) {
            return ResponseHelper::error("Table not found.", 404);
        }

        $table->update($request->validated());
        return ResponseHelper::success("Table updated successfully.", $table);
    }

    /**
     * Remove the specified table from the database.
     *
     * @param int $id
     * @return JsonResponse
     */
    public function destroy(int $id): JsonResponse
    {
        $table = Table::find($id);

        if (!$table) {
            return ResponseHelper::error("Table not found.", 404);
        }

        $table->delete();
        return ResponseHelper::success("Table deleted successfully.");
    }
    public function getTablesByRestaurantId(int $restaurantId): JsonResponse
    {
        // Find the restaurant by ID
        $restaurant = Restaurant::find($restaurantId);

        // Check if the restaurant exists
        if (!$restaurant) {
            return ResponseHelper::error("Restaurant not found.", 404);
        }

        // Retrieve tables for the restaurant
        $tables = $restaurant->tables()->get();

        // If no tables are found, return an empty list
        if ($tables->isEmpty()) {
            return ResponseHelper::success("No tables found for the given restaurant ID.", []);
        }

        // Return success response with the list of tables
        return ResponseHelper::success("Tables retrieved successfully.",null, $tables);
    }
}
