<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Table extends Generic
{
    use HasFactory;
    protected $fillable = [
        'capacity', 'number', 'status', 'restaurant_id'
    ];

    /**
     * Get the restaurant associated with the table.
     */
    public function restaurant()
    {
        return $this->belongsTo(Restaurant::class);
    }

    /**
     * Get the reservations associated with the table.
     */
    public function reservations(): HasMany
    {
        return $this->hasMany(Reservations::class);
    }

    /**
     * Scope to filter available tables based on status, time, and capacity.
     */
    public function scopeAvailable($query, $restaurant_id,$date, $start_time, $end_time, $people_count)
    {
        return $query
            ->where('restaurant_id', $restaurant_id) // Only available tables
            ->where('status', 'available') // Only available tables
            ->where('capacity', '>=', $people_count) // Capacity must be sufficient
            ->whereDoesntHave('reservations', function ($q) use ($date, $start_time, $end_time) {
                // Exclude tables reserved during the requested time period
                $q->whereNotIn('status', ['Pending', 'Rejected'])->where('date', $date)
                    ->where(function ($q) use ($start_time, $end_time) {
                        $q->whereBetween('start_time', [$start_time, $end_time])
                            ->orWhereBetween('end_time', [$start_time, $end_time])
                            ->orWhere(function ($q) use ($start_time, $end_time) {
                                $q->where('start_time', '<=', $start_time)
                                    ->where('end_time', '>=', $end_time);
                            });
                    });
            });
    }
}
