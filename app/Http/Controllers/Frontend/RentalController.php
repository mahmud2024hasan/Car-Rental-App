<?php

// namespace App\Http\Controllers\Frontend;

// use App\Http\Controllers\Controller;
// use Illuminate\Http\Request;
// use Inertia\Inertia;
// use App\Models\Rental;
// use App\Models\Car;
// use Illuminate\Support\Facades\Auth;
// use Illuminate\Support\Facades\Storage;
// use Illuminate\Foundation\Auth\Access\AuthorizesRequests;


// class RentalController extends Controller
// {
//     use AuthorizesRequests;

//     // Show the customer dashboard with stats, recent bookings, top rented cars
//     public function index()
//     {
//         $user = Auth::user();

//         // Recent rentals for this customer
//         $recentRentals = Rental::with('car')
//             ->where('user_id', $user->id)
//             ->latest()
//             ->take(5)
//             ->get()
//             ->map(function ($rental) {
//                 $rental->car_image = $rental->car?->image ? Storage::url($rental->car->image) : null;
//                 $rental->is_cancellable = in_array($rental->status, ['Pending', 'Confirmed']) && now()->lt($rental->start_date);
//                 return $rental;
//             });

//         // Top 5 rented cars globally
//         $topRentedCars = Car::withCount('rentals')
//             ->orderByDesc('rentals_count')
//             ->take(5)
//             ->get()
//             ->map(function ($car) {
//                 $car->image = $car->image ? Storage::url($car->image) : null;
//                 return $car;
//             });

//         // Rental stats for current user
//         $stats = [
//             'totalBookings' => Rental::where('user_id', $user->id)->count(),
//             'activeRentals' => Rental::where('user_id', $user->id)->where('status', 'Ongoing')->count(),
//             'upcomingRentals' => Rental::where('user_id', $user->id)->where('start_date', '>', now())->count(),
//             'completedRentals' => Rental::where('user_id', $user->id)->where('status', 'Completed')->count(),
//             'canceledRentals' => Rental::where('user_id', $user->id)->where('status', 'Canceled')->count(),
//         ];

//         return Inertia::render('Customer/Dashboard', [
//             'userName' => $user->name,
//             'rentals' => $recentRentals,
//             'topRentedCars' => $topRentedCars,
//             'stats' => $stats,
//         ]);
//     }

//     // Show customer's all bookings data
//     public function customerBookings()
//     {
//         $user = Auth::user();

//         $rentals = Rental::with('car')
//             ->where('user_id', $user->id)
//             ->orderByDesc('created_at')
//             ->get()
//             ->map(function ($rental) {
//                 $rental->is_cancellable = in_array($rental->status, ['Pending', 'Confirmed']) && now()->lt($rental->start_date);
//                 $rental->car_image = $rental->car?->image ? Storage::url($rental->car->image) : null;
//                 return $rental;
//             });

//         return Inertia::render('Customer/Bookings/Index', [
//             'rentals' => $rentals,
//         ]);
//     }

//     // Show customer's booking create form
//     public function bookingCreate($carId)
//     {
//         $user = Auth::user();

//         // Load car with its brand & model details
//         $car = Car::select('id', 'name', 'brand', 'model', 'car_type', 'year', 'daily_rent_price', 'availability', 'image')
//             ->findOrFail($carId);

//         // Generate full image URL
//         $car->image_url = $car->image ? Storage::url($car->image) : null;

//         return Inertia::render('Customer/Bookings/Create', [
//             'car' => $car,
//             'user' => [
//                 'id' => $user->id,
//                 'name' => $user->name,
//             ],
//         ]);
//     }

//     // Store a new booking in the database
//     public function store(Request $request)
//     {
//         $this->authorize('create', Rental::class); // Only authenticated users can create rentals

//         // Validate and store the rental data
//         $request->validate([
//             'car_id' => 'required|exists:cars,id',
//             'start_date' => 'required|date|after_or_equal:today',
//             'end_date' => 'required|date|after:start_date',
//         ]);

//         // Calculate total cost again in backend for security
//         $car = Car::findOrFail($request->car_id);
//         $start = new \Carbon\Carbon($request->start_date);
//         $end = new \Carbon\Carbon($request->end_date);
//         $diffInDays = $start->diffInDays($end) + 1;
//         $totalCost = $car->daily_rent_price * $diffInDays;

//         // Create the rental record
//         $rental = Rental::create([
//             'car_id' => $request->car_id,
//             'user_id' => Auth::user()->id,
//             'start_date' => $request->start_date,
//             'end_date' => $request->end_date,
//             'total_cost' => $totalCost,
//             'status' => 'Pending',
//         ]);

//         return redirect()->route('customer.bookings')->with('success', 'Congratulations! Your booking is created successfully. Please check your email to receive the confirmation, and wait for the approval.');
//     }


//     // Cancel a booking
//     public function cancel(Rental $rental)
//     {
//         $this->authorize('cancel', $rental);

//         $rental->status = 'Canceled';
//         $rental->save();

//         return back()->with('success', 'Booking cancelled successfully.');
//     }
// }
