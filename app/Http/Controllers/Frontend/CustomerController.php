<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\Rental;
use App\Models\Car;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use App\Mail\RentalConfirmationMail;
use App\Mail\RentalConfirmToAdminMail;
use Carbon\Carbon;




class CustomerController extends Controller
{
    use AuthorizesRequests;

    // Show the customer dashboard with stats, recent bookings, top rented cars
    public function index()
    {
        $user = Auth::user();

        // Recent rentals for this customer
        $recentRentals = Rental::with('car')
            ->where('user_id', $user->id)
            ->latest()
            ->take(5)
            ->get()
            ->map(function ($rental) {
                $rental->car_image = $rental->car?->image ? Storage::url($rental->car->image) : null;
                $rental->is_cancellable = in_array($rental->status, ['Pending', 'Confirmed']) && now()->lt($rental->start_date);
                return $rental;
            });

        // Top 5 rented cars globally
        $topRentedCars = Car::withCount('rentals')
            ->orderByDesc('rentals_count')
            ->take(5)
            ->get()
            ->map(function ($car) {
                $car->image = $car->image ? Storage::url($car->image) : null;
                return $car;
            });

        // Rental stats for current user
        $stats = [
            'totalBookings' => Rental::where('user_id', $user->id)->count(),
            'activeRentals' => Rental::where('user_id', $user->id)->where('status', 'Ongoing')->count(),
            'upcomingRentals' => Rental::where('user_id', $user->id)->where('start_date', '>', now())->count(),
            'completedRentals' => Rental::where('user_id', $user->id)->where('status', 'Completed')->count(),
            'canceledRentals' => Rental::where('user_id', $user->id)->where('status', 'Canceled')->count(),
        ];


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

        // Return the customer dashboard view with the data
        return Inertia::render('Customer/Dashboard', [
            'userName' => $user->name,
            'rentals' => $recentRentals,
            'topRentedCars' => $topRentedCars,
            'stats' => $stats,
            'featuredCars' => $featuredCars
        ]);
    }

    // Show customer's all bookings data
    public function customerBookings()
    {
        $user = Auth::user();

        $rentals = Rental::with('car')
            ->where('user_id', $user->id)
            ->orderByDesc('created_at')
            ->get()
            ->map(function ($rental) {
                $rental->is_cancellable = in_array($rental->status, ['Pending', 'Confirmed']) && now()->lt($rental->start_date);
                $rental->car_image = $rental->car?->image ? Storage::url($rental->car->image) : null;
                return $rental;
            });

        return Inertia::render('Customer/Bookings/Index', [
            'rentals' => $rentals,
        ]);
    }

    // Show customer's booking create form
    public function bookingCreate(Request $request)
    {   
        // Get the car_id from the query parameter
        $carId = $request->query('car_id'); 
        
        // Get the authenticated user
        $user = Auth::user();

        // Load car with its brand & model details
        $car = Car::select('id', 'name', 'brand', 'model', 'car_type', 'year', 'daily_rent_price', 'availability', 'image')
            ->findOrFail($carId);

        // Generate full image URL
        $car->image_url = $car->image ? Storage::url($car->image) : null;

        // Return the customer booking create view
        return Inertia::render('Customer/Bookings/Create', [
            'car' => $car,
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
            ],
        ]);
    }

    // Store a new booking in the database
    public function store(Request $request)
    {   
        // Only authenticated users can create bookings
        $this->authorize('create', Rental::class); 

        // Validate and store the rental data
        $request->validate([
            'car_id' => 'required|exists:cars,id',
            'start_date' => 'required|date|after_or_equal:today',
            'end_date' => 'required|date|after:start_date',
        ]);

        // Calculate total cost again in backend for security
        $car = Car::findOrFail($request->car_id);
        $start = new \Carbon\Carbon($request->start_date);
        $end = new \Carbon\Carbon($request->end_date);
        $diffInDays = $start->diffInDays($end) + 1;
        $totalCost = $car->daily_rent_price * $diffInDays;

        // Create the rental record
        $rental = Rental::create([
            'car_id' => $request->car_id,
            'user_id' => Auth::user()->id,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'total_cost' => $totalCost,
            'status' => 'Pending',
        ]);

        // Load the user associated with the rental
        $rental->load('car', 'user.customerProfile');

        // Send confirmation email
        Mail::to($rental->user->email)->queue(new RentalConfirmationMail($rental));
        Mail::to(env('MAIL_FROM_ADDRESS'))->queue(new RentalConfirmToAdminMail($rental));

        return redirect()->route('customer.bookings')->with('success', 'Congratulations! Your booking is created successfully. Please check your email to receive the confirmation, and wait for the approval.');
    }

    // Cancel a booking
    public function cancel(Rental $rental)
    {
        $this->authorize('cancel', $rental);

        $rental->status = 'Canceled';
        $rental->save();

        return back()->with('success', 'Booking cancelled successfully.');
    }

    // Show the customer's profile
    public function profile()
    {
        $user = Auth::user();

        $profile = $user->customerProfile;

        return inertia('Customer/Profile', [
            'user' => $user,
            'profile' => $profile,
        ]);
    }


    // Update the customer's profile
    public function update(Request $request)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'mobile' => 'nullable|string|max:20',
            'address' => 'nullable|string',
            'district' => 'nullable|string',
            'division' => 'nullable|string',
            'postal_code' => 'nullable|string',
        ]);

        $user->update([
            'name' => $validated['name'],
        ]);

        $user->customerProfile()->updateOrCreate(
            ['user_id' => $user->id],
            Arr::except($validated, ['name'])
        );

        return redirect()->back()->with('success', 'Your profile updated successfully.');
    }


    // Update the customer's profile image
    public function updateImage(Request $request)
    {
        $request->validate([
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        /** @var \App\Models\User $user */
        $user = Auth::user();

        if ($request->hasFile('image')) {
            // Delete old image if exists
            $oldImage = $user->customerProfile?->image;

            if ($oldImage && Storage::disk('public')->exists($oldImage)) {
                Storage::disk('public')->delete($oldImage);
            }

            // Upload new profile image
            $imagePath = $request->file('image')->store('profile-images', 'public');

            // Update or create customer profile image field
            $user->customerProfile()->updateOrCreate(
                ['user_id' => $user->id],
                ['image' => $imagePath]
            );
        }

        return redirect()->back()->with('success', 'Your profile image updated successfully.');
    }



    // Update the customer's password
    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required|string',
            'new_password' => 'required|string|min:8|same:confirm_password',
        ]);

        /** @var \App\Models\User $user */
        $user = Auth::user();

        if (!Hash::check($request->current_password, $user->password)) {
            return back(303)->withErrors(['current_password' => 'Current password is incorrect']);
        }

        $user->update([
            'password' => Hash::make($request->new_password),
        ]);

        return back(303)->with('success', 'Your account password updated successfully.');
    }
}
