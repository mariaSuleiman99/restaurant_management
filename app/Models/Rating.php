<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;

class Rating extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [
        'user_id',
        'rateable_type',
        'rateable_id',
        'rating',
        'rating_date',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function rateable()
    {
        return $this->morphTo();
    }

    /**
     * Boot the model and register event listeners.
     */
    protected static function boot(): void
    {
        parent::boot();
        // Recalculate avgRate when a rating is created
        static::created(function ($rating) {
            $rating->updateAvgRate();
        });

        // Recalculate avgRate when a rating is updated
        static::updated(function ($rating) {
            $rating->updateAvgRate();
        });

        // Recalculate avgRate when a rating is deleted
        static::deleted(function ($rating) {
            $rating->updateAvgRate();
        });
    }

    /**
     * Update the avgRate for the associated entity (restaurant or item).
     */
    public function updateAvgRate(): void
    {
        $rateable = $this->rateable; // Get the associated entity (restaurant or item)

        if (!$rateable) {
            return; // Exit early if the rateable entity doesn't exist
        }
        $avgRate = $rateable->ratings()->avg('rating') ?? 0; // Calculate the average rating
        // Only update if the avg_rate has changed
        if ($rateable->avg_rate !== $avgRate) {
            $rateable->avg_rate =$avgRate;
            $rateable->save();
        }
    }
}
