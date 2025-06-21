<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use App\Enums\UserRole;
use App\Models\Rental;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }


    // The attributes that should be cast.
    protected $casts = [
        'role' => UserRole::class,
    ];

    // This function checks if the user is an admin or not. It returns true if the user is an admin, otherwise false
    public function isAdmin(): bool
    {
        return $this->role === UserRole::ADMIN;
    }

    // This function checks if the user is a customer or not. It returns true if the user is a customer, otherwise false
    public function isCustomer(): bool
    {
        return $this->role === UserRole::CUSTOMER;
    }

    // Defines a hasMany relationship with the Rental model, indicating that a user can have multiple rentals.
    public function rentals()
    {
        return $this->hasMany(Rental::class);
    }


    // Defines a hasOne relationship with the CustomerProfile model, indicating that a user can have one customer profile.
    public function customerProfile()
    {
        return $this->hasOne(CustomerProfile::class);
    }
}
