<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Car;
use App\Models\User;

class Rental extends Model
{
    // Fillable attributes for mass assignment
    protected $fillable = [
        'user_id',
        'car_id',
        'start_date',
        'end_date',
        'total_cost',
        'status',
    ];


    // Defines a belongsTo relationship with the Car model, indicating that a rental is associated with one car.
    public function car()
    {
        return $this->belongsTo(Car::class);
    }
   

    // Defines a belongsTo relationship with the User model, indicating that a rental is associated with one user.
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
