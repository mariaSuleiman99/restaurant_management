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
    public static function search(?array $filters): array
    {
        // Start with a base query
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
                    if (str_contains($columnType, 'string') || str_contains($columnType, 'text')) {
                        // Use LIKE for string/text columns
                        $query->where($field, 'LIKE', "%{$value}%");
                    } else {
                        // Use = for other types (e.g., integer, boolean)
                        $query->where($field, '=', $value);
                    }
                }
            }
        }
        // Extract pagination parameters from the request (no defaults)
        $page = request()->input('page');
        $perPage = request()->input('per_page');
        Log::info("page");
        Log::debug($page);
        Log::debug($perPage);
        if ($page !== null && $perPage !== null) {
            // Paginate the results
            return $query->paginate((int) $perPage, ['*'], 'page',(int) $page)->items();
        } else
            return $query->get()->toArray();
    }
}
