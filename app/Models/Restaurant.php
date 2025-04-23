<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
//use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class Restaurant extends Generic
{
    use HasFactory;

    protected $fillable = [
        'name', 'mobile_number', 'description', 'location',
        'profile_image', 'cover_image','status','email_address'
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

    /**
     * Relationship: An item can have many ratings.
     */
    public function ratings(): MorphMany
    {
        return $this->morphMany(Rating::class, 'rateable');
    }
}
