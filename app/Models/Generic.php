<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schema;

class Generic extends Model
{
    /**
     * Dynamic search method with pagination.
     *
     * @param array|null $filters Key-value pairs for search terms (e.g., ['name' => 'Pizza', 'location' => 'New York'])
     * @return array
     */
    public static  $mainQuery;

    public static function search(?array $filters): array
    {
        // Start with a base query
        if (self::$mainQuery != null)
            $query = self::$mainQuery;
        else
            $query = self::query();

        // Check if filters are provided and not empty
        if (!empty($filters)) {
            // Create an instance of the model to access $fillable
            $instance = new static();
            $table = $instance->getTable(); // Get the table name

            foreach ($filters as $field => $value) {
                // Only apply the filter if the field is in the $fillable array
                if (in_array($field, $instance->getFillable())) {
                    // Get the column type from the database schema
                    $columnType = Schema::getColumnType($table, $field);

                    // Apply the appropriate condition based on the column type
                    if (str_contains($columnType, 'string') || str_contains($columnType, 'text') || str_contains($columnType, 'varchar')) {
                        // Use LIKE for string/text columns
                        $query->where($field, 'LIKE', "%{$value}%");
                    } else {
                        // Use = for other types (e.g., integer, boolean)
                        $query->where($field, '=', $value);
                    }
                }
            }
        }
        $query->orderBy('created_at','Desc');
        // Extract pagination parameters from the request (no defaults)
        $page = request()->input('page');
        $perPage = request()->input('per_page');
        if ($page !== null && $perPage !== null) {
            // Paginate the results
            $paginatedResults = $query->paginate((int)$perPage, ['*'], 'page', (int)$page);
            // Return both the items and the total count
            return [
                'items' => $paginatedResults->items(),
                'total_count' => $paginatedResults->total(),
            ];
        } else {
            // Return all results and the total count
            $results = $query->get();
            return [
                'items' => $results->toArray(),
                'total_count' => $results->count(),
            ];
        }
    }
}
