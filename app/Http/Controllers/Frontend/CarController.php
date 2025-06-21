<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\Car;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

class CarController extends Controller
{
    // Show all cars in cars page with filters and search functionality
    public function index(Request $request)
    {
        // Initialize query
        $query = Car::query()->where('availability', true);

        // Apply search filter
        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                    ->orWhere('model', 'like', '%' . $request->search . '%')
                    ->orWhere('brand', 'like', '%' . $request->search . '%');
            });
        }

        // Apply filters by car type
        if ($request->filled('car_type')) {
            $query->where('car_type', $request->car_type);
        }

        // Apply filters by brand
        if ($request->filled('brand')) {
            $query->where('brand', $request->brand);
        }

        // Apply filters by min price
        if ($request->filled('min_price')) {
            $query->where('daily_rent_price', '>=', $request->min_price);
        }

        // Apply filters by max price
        if ($request->filled('max_price')) {
            $query->where('daily_rent_price', '<=', $request->max_price);
        }

        // Apply pagination + attach image_url and booking status
        $cars = $query->latest()->paginate(16)->through(function ($car) {
            $car->image_url = $car->image ? Storage::url($car->image) : null;

            // Check if the car is booked today
            $today = Carbon::today();
            $rental = $car->rentals()
                ->whereDate('start_date', '<=', $today)
                ->whereDate('end_date', '>=', $today)
                ->first();

            $car->is_booked = $rental ? true : false;
            $car->booked_until = $rental ? Carbon::parse($rental->end_date)->toFormattedDateString() : null;

            return $car;
        })->withQueryString();

        // Get unique car types and brands
        $carTypes = Car::select('car_type')->distinct()->pluck('car_type');
        $brands = Car::select('brand')->distinct()->pluck('brand');

        // return response to cars page
        return inertia('Frontend/Cars', [
            'cars' => $cars,
            'filters' => [
                'carTypes' => $carTypes,
                'brands' => $brands,
            ],
            'query' => $request->all(),
        ]);
    }

}
