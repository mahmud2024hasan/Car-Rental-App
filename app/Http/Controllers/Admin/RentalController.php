<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Car;
use App\Models\User;
use Inertia\Inertia;
use App\Models\Rental;
use Illuminate\Support\Facades\Mail;
use App\Mail\RentalConfirmationMail;
use App\Mail\RentalConfirmToAdminMail;


class RentalController extends Controller
{
    // Show the rental management page
    public function index()
    {
        // Initialize query
        $query = Rental::with(['car', 'user.customerProfile']);

        // Apply search filter if provided. This will search by customer name, car name, start date, end date or status
        if ($search = request()->input('search')) {
            $query->whereHas('user', function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%");
            })->orWhereHas('car', function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('brand', 'like', "%{$search}%")
                    ->orWhere('model', 'like', "%{$search}%");
            })->orWhere('start_date', 'like', "%{$search}%")
                ->orWhere('end_date', 'like', "%{$search}%")
                ->orWhere('status', 'like', "%{$search}%");
        }

        // Fetch all rentals from the database, paginate results, and add car and customer details
        $rentals = $query->latest()->paginate(10)->through(function ($rental) {
            // Car Details
            $rental->car_details = [
                'name' => $rental->car->name,
                'brand' => $rental->car->brand,
                'model' => $rental->car->model,
                'car_type' => $rental->car->car_type,
                'daily_rent_price' => $rental->car->daily_rent_price,
                'image_url' => $rental->car->image ? asset('storage/' . $rental->car->image) : null,
            ];

            // Customer Details
            $customerProfile = optional($rental->user->customerProfile);
            $image = $customerProfile->image;

            $rental->customer_details = [
                'name' => $rental->user->name,
                'email' => $rental->user->email,
                'mobile' => $customerProfile->mobile,
                'image_url' => $image ? asset('storage/' . $image) : null,
            ];

            return $rental;
        });

        // Return the rentals index view with the rentals data
        return Inertia::render('Admin/Rentals/Index', [
            'rentals' => $rentals,
            'filters' => request()->all('search'),
        ]);
    }


    // Show the rental create form
    public function create(Request $request)
    {   
        // Get the car_id from the query parameter
        $initialCarId = $request->query('car_id'); 

        // Return the rental create view
        return Inertia::render('Admin/Rentals/Create', [
            'initialCarId' => $initialCarId,
            'customers' => User::with('customerProfile:id,user_id,mobile,address,district,division,postal_code,image')
                ->where('role', 'customer')
                ->get(['id', 'name', 'email'])
                ->map(function ($customer) {
                    return [
                        'customer_id' => $customer->id,
                        'name' => $customer->name,
                        'email' => $customer->email,
                        'mobile' => optional($customer->customerProfile)->mobile,
                        'address' => optional($customer->customerProfile)->address,
                        'district' => optional($customer->customerProfile)->district,
                        'division' => optional($customer->customerProfile)->division,
                        'postal_code' => optional($customer->customerProfile)->postal_code,
                        'image_url' => optional($customer->customerProfile)->image
                            ? asset('storage/' . $customer->customerProfile->image)
                            : null,
                    ];
                }),
            'cars' => Car::all(['id', 'name', 'brand', 'model', 'car_type', 'year', 'daily_rent_price', 'image'])
                ->map(function ($car) {
                    return [
                        'id' => $car->id,
                        'name' => $car->name,
                        'brand' => $car->brand,
                        'model' => $car->model,
                        'car_type' => $car->car_type,
                        'year' => $car->year,
                        'daily_rent_price' => $car->daily_rent_price,
                        'image_url' => $car->image ? asset('storage/' . $car->image) : null,
                    ];
                }),
        ]);
    }


    // Store a new rental in the database
    public function store(Request $request)
    {
        $validated = $request->validate([
            'customer_id' => 'required',
            'car_id' => 'required|exists:cars,id',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'status' => 'required|in:Pending,Confirmed,Ongoing,Completed,Canceled',
        ]);

        // Calculate total cost again in backend for security
        $car = Car::findOrFail($validated['car_id']);
        $start = new \Carbon\Carbon($validated['start_date']);
        $end = new \Carbon\Carbon($validated['end_date']);
        $diffInDays = $start->diffInDays($end) + 1;

        $totalCost = $car->daily_rent_price * $diffInDays;

        // Create the rental record
        $rental = Rental::create([
            'user_id' => $validated['customer_id'],
            'car_id' => $validated['car_id'],
            'start_date' => $validated['start_date'],
            'end_date' => $validated['end_date'],
            'total_cost' => $totalCost,
            'status' => $validated['status'],
        ]);

        // Load the user associated with the rental
        $rental->load('car', 'user.customerProfile');

        // Send confirmation email
        Mail::to($rental->user->email)->queue(new RentalConfirmationMail($rental));
        Mail::to(env('MAIL_FROM_ADDRESS'))->queue(new RentalConfirmToAdminMail($rental));


        // Redirect to the rentals index page
        return redirect()->route('admin.rentals.index')->with('success', 'New rental created successfully and confirmation email sent to customer and admin.');
    }

    // Show the rental details
    public function show(Rental $rental)
    {
        // Load the rantal with car and user details
        $rental->load(['car', 'user.customerProfile']);

        // Add car details to the rental object for easier access in the view
        $rental->car_details = [
            'name' => $rental->car->name,
            'brand' => $rental->car->brand,
            'model' => $rental->car->model,
            'car_type' => $rental->car->car_type,
            'daily_rent_price' => $rental->car->daily_rent_price,
            'image_url' => $rental->car->image ? asset('storage/' . $rental->car->image) : null,
        ];

        // Add customer details to the rental object for easier access in the view
        $rental->customer_details = [
            'name' => $rental->user->name,
            'email' => $rental->user->email,
            'mobile' => optional($rental->user->customerProfile)->mobile,
        ];

        // Return the rental show view with the rental data
        return Inertia::render('Admin/Rentals/Show', [
            'rental' => $rental,
        ]);
    }


    // Show the edit rental form
    public function edit(Rental $rental)
    {
        // Load the rental with car and user details
        $rental->load(['car', 'user.customerProfile']);

        // Add car details to the rental object for easier access in the view
        $rental->car_details = [
            'name' => $rental->car->name,
            'brand' => $rental->car->brand,
            'model' => $rental->car->model,
        ];
        // Add customer details to the rental object for easier access in the view
        $rental->customer_details = [
            'name' => $rental->user->name,
            'email' => $rental->user->email,
            'mobile' => optional($rental->user->customerProfile)->mobile,
        ];

        // Return the rental edit view with the rental data
        return Inertia::render('Admin/Rentals/Edit', [
            'rental' => $rental,
            'customers' => User::with('customerProfile:id,user_id,mobile')
                ->where('role', 'customer')
                ->get(['id', 'name', 'email'])
                ->map(function ($customer) {
                    return [
                        'customer_id' => $customer->id,
                        'name' => $customer->name,
                        'email' => $customer->email,
                        'mobile' => optional($customer->customerProfile)->mobile,
                    ];
                }),
            'cars' => Car::all(['id', 'name', 'brand', 'model', 'daily_rent_price']),
        ]);
    }


    // Update an existing rental in the database
    public function update(Request $request, Rental $rental)
    {
        $validated = $request->validate([
            'customer_id' => 'required|exists:users,id',
            'car_id' => 'required|exists:cars,id',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'status' => 'required|in:Pending,Confirmed,Ongoing,Completed,Canceled',
        ]);

        // Calculate total cost again in backend for security
        $car = Car::findOrFail($validated['car_id']);
        $start = new \Carbon\Carbon($validated['start_date']);
        $end = new \Carbon\Carbon($validated['end_date']);
        $diffInDays = $start->diffInDays($end) + 1;

        $totalCost = $car->daily_rent_price * $diffInDays;

        // Update the rental record
        $rental->update([
            'user_id' => $validated['customer_id'],
            'car_id' => $validated['car_id'],
            'start_date' => $validated['start_date'],
            'end_date' => $validated['end_date'],
            'total_cost' => $totalCost,
            'status' => $validated['status'],
        ]);

        return redirect()->route('admin.rentals.index')->with('success', 'Rental updated successfully.');
    }


    // Delete a rental from the database
    public function destroy(Rental $rental)
    {
        // Delete the rental record from the database
        $rental->delete();
        return redirect()->route('admin.rentals.index')->with('success', 'Rental deleted successfully.');
    }
}
