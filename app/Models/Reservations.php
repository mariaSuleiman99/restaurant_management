<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Reservations extends Model
{
    protected $fillable = [
        'date', 'duration','user_id','table_id'
    ];

    function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    function table(): BelongsTo
    {
        return $this->belongsTo(Table::class);
    }
}
