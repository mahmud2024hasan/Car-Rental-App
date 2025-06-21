<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Car;
use App\Models\Rental;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    // Display the admin dashboard view
    public function index()
    {
        // Total number of cars
        $totalCars = Car::count();

        // Total number of available cars
        $totalAvailableCars = Car::where('availability', 1)->count();

        // Total number of rentals
        $totalRentals = Rental::count();

        // Total earnings from rentals
        $totalEarnings = Rental::sum('total_cost');

        // Recent rentals with car and user details
        $recentRentals = Rental::with(['car', 'user'])->latest()->take(10)->get();

        // Recent added cars data with image URL
        $latestCars = Car::select('id', 'name', 'brand', 'model', 'year', 'car_type', 'image', 'created_at')
            ->latest()
            ->take(10)
            ->get()
            ->map(function ($car) {
                $car->image_url = $car->image ? asset('storage/' . $car->image) : null;
                return $car;
            });

        // Top rented cars
        $topRentedCars = DB::table('rentals')
            ->select(
                'cars.id',
                'cars.name',
                'cars.image',
                'cars.brand',
                'cars.model',
                DB::raw('COUNT(rentals.id) as rental_count')
            )
            ->join('cars', 'rentals.car_id', '=', 'cars.id')
            ->groupBy('cars.id', 'cars.name', 'cars.image', 'cars.brand', 'cars.model')
            ->orderByDesc('rental_count')
            ->limit(5)
            ->get();

        // Top rated customers
        $topCustomer = DB::table('rentals')
            ->select('users.id', 'users.name', 'users.email', DB::raw('COUNT(rentals.id) as total_rentals'))
            ->join('users', 'rentals.user_id', '=', 'users.id')
            ->groupBy('users.id', 'users.name', 'users.email')
            ->orderByDesc('total_rentals')
            ->first();

        // Return the dashboard view with the calculated data
        return inertia('Admin/Dashboard', [
            'totalCars' => $totalCars,
            'totalAvailableCars' => $totalAvailableCars,
            'totalRentals' => $totalRentals,
            'totalEarnings' => $totalEarnings,
            'recentRentals' => $recentRentals,
            'latestCars' => $latestCars,
            'topRentedCars' => $topRentedCars,
            'topCustomer' => $topCustomer,
        ]);
    }
}
