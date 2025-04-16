<?php

namespace App\Models;

use App\Traits\WithReservationRestaurants;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Reservations extends Generic
{
    use WithReservationRestaurants;
    protected $fillable = [
        'date', 'duration','user_id','table_id','start_time','end_time'
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
        $results = parent::search($filters);
        $results['items'] = self::addReservationRestaurants($results['items']);
        return $results;
    }
}
