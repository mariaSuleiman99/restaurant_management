<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Item extends Model
{
    protected $fillable = [
        'name',
        'description',
        'price','image'
    ];
    function restaurant(): BelongsTo
    {
        return $this->belongsTo(Restaurant::class);
    }
}
