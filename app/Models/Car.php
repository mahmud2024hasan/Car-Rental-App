<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Car extends Model
{
    // Fillable attributes for mass assignment
    protected $fillable = [
        'name',
        'brand',
        'model',
        'year',
        'car_type',
        'daily_rent_price',
        'availability',
        'image',
    ];

    // Defines a hasMany relationship with the Rental model, indicating that a car can have multiple rentals.
    public function rentals()
    {
        return $this->hasMany(Rental::class);
    }
}
