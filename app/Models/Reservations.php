<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class Reservations extends Generic
{
    protected $fillable = [
        'date', 'duration','user_id','table_id','start_time','end_time','status'
    ];

    function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    function table(): BelongsTo
    {
        return $this->belongsTo(Table::class);
    }

//    /**
//     * Mutator to calculate and set the end_time when saving.
//     */
//    public function setEndTimeAttribute()
//    {
//        $this->attributes['end_time'] = Carbon::parse($this->date)->addMinutes($this->duration);
//    }
    /**
     * Automatically calculate and set end_time before saving.
     */
    protected static function boot()
    {
        parent::boot();

        static::saving(function ($reservation) {
            $startDateTime = Carbon::parse("{$reservation->date} {$reservation->start_time}");
            $reservation->end_time = $startDateTime->addHours($reservation->duration)->format('H:i:s');
        });
    }
    public static function search(?array $filters): array
    {
        self::$mainQuery = self::query();
        if (array_key_exists('restaurant_id', $filters)) {
            $restaurantId = $filters['restaurant_id'];
            self::$mainQuery->whereHas('table', function ($query) use ($restaurantId) {
                $query->where('restaurant_id', $restaurantId);
            });
        }
        self::$mainQuery->with('table.restaurant')->with('user');
        $results = parent::search($filters);
        return $results;
    }
    /**
     * Scope to filter reservations by table ID and date (today or later).
     */
    public function scopeForTableFromToday($query, $tableId)
    {
        return $query
            ->where('table_id', $tableId)
            ->where('date', '>=', now()->toDateString())
            ->with('user')->OrderBy('created_at', 'DESC'); // Eager load the user relationship
    }

    public static function restaurantsVisits(): Collection
    {
        return self::query()
            ->join('tables', 'tables.id', '=', 'reservations.table_id')
            ->join('restaurants', 'restaurants.id', '=', 'tables.restaurant_id')
            ->select([
                    'restaurants.name as name',
                    DB::raw('COUNT(tables.restaurant_id) as value')
                ]
            )
            ->groupBy('restaurants.name')->get();
    }
}
