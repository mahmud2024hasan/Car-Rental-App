<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Car;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

class PageController extends Controller
{
    // Show the home page
    public function home()
    {
        // Featured cars
        $cars = Car::latest()->where('availability', 1)->take(12)->get();

        $today = Carbon::today();

        $featuredCars = $cars->map(function ($car) use ($today) {
            $car->image_url = $car->image ? Storage::url($car->image) : null;

            // Check if the car is booked today
            $rental = $car->rentals()
                ->whereDate('start_date', '<=', $today)
                ->whereDate('end_date', '>=', $today)
                ->first();

            $car->is_booked = $rental ? true : false;
            $car->booked_until = $rental ? Carbon::parse($rental->end_date)->toFormattedDateString() : null;

            return $car;
        });

        return inertia('Frontend/Home', [
            'featuredCars' => $featuredCars,
        ]);
    }

    // Show the about page
    public function about()
    {
        return inertia('Frontend/About');
    }

    // Show the contact page
    public function contact()
    {
        return inertia('Frontend/Contact');
    }
}
