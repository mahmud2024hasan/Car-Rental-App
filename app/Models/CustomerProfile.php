<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CustomerProfile extends Model
{
    // Fillable attributes for mass assignment
    protected $fillable = [
        'user_id',
        'mobile',
        'address',
        'district',
        'division',
        'postal_code',
        'image',
    ];

    // Defines a belongsTo relationship with the User model, indicating that a customer profile is associated with one user.
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
