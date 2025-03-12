<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Table extends Model
{
    protected $fillable = [
        'capacity','number'
    ];

    function restaurant():BelongsTo {
        return $this->belongsTo(Restaurant::class);
    }
}
