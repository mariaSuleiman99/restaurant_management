<?php

namespace App\Http\Controllers;

use App\Http\Requests\ChangeTableStatusRequest;
use App\Http\Requests\TableAvailabilityRequest;
use App\Http\Requests\TableRequest;
use App\Http\Requests\UpdateTableRequest;
use App\Models\Restaurant;
use App\Models\Table;
use App\Helpers\ResponseHelper;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
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
        return ResponseHelper::success("Tables retrieved successfully.", null, $tables);
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
        return ResponseHelper::success("Tables retrieved successfully.", null, $tables);
    }

    /**
     * Get available tables based on date, time, and people count.
     */
    public function getAvailableTables(TableAvailabilityRequest $request): JsonResponse
    {
        // Extract request data
        $date = $request->input('date');
        $restaurant_id = $request->input('restaurant_id');
        $start_time = $request->input('start_time');

        $duration = intval($request->input('duration'));
        $startDateTime = Carbon::parse("$date $start_time");
        $end_time = $startDateTime->addHours($duration)->format('H:i:s');

        $people_count = $request->input('people_count');

        // Query available tables
        $availableTables = Table::available($restaurant_id,$date, $start_time, $end_time, $people_count)->get();

        // Return response
        return ResponseHelper::success(
            'Available tables retrieved successfully.', null, $availableTables);
    }

    public function changeStatus(ChangeTableStatusRequest $request, int $id): JsonResponse
    {
        // Find the table by ID
        $table = Table::find($id);

        if (!$table) {
            return response()->json(['message' => 'Table not found.'], 404);
        }

        // Extract the new status from the request
        $newStatus = $request->input('status');

        // Update the table's status
        $table->update(['status' => $newStatus]);

        // Return success response
        return response()->json([
            'message' => 'Table status updated successfully.',
            'data' => $table,
        ]);
    }
    public function search(Request $request): JsonResponse
    {
        // Call the search method and get the results
        $searchResults = Table::search($request->all());
        // Extract items and total count
        $tables = $searchResults['items'];
        $totalCount = $searchResults['total_count'];
        return ResponseHelper::success("Tables retrieved successfully.", null, $tables, $totalCount);
    }
}
