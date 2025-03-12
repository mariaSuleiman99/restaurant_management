<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Restaurant extends Model
{
    protected $fillable = [
        'name', 'status', 'email', 'mobile_number', 'description', 'location'
    ];

    function tables(): HasMany
    {
        return $this->hasMany(Table::class);
    }
    function employees(): HasMany
    {
        return $this->hasMany(User::class);
    }
    function items(): HasMany
    {
        return $this->hasMany(Item::class);
    }
}
