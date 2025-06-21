<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Car;
use Illuminate\Support\Facades\Storage;

class CarController extends Controller
{
    // Show all data from database and render car management index page
    public function index(Request $request)
    {
        // Initialize query
        $query = Car::query();

        // Apply search filter if provided. This will search by name, brand, or model
        if ($search = $request->input('search')) {
            $query->where('name', 'like', "%{$search}%")
                ->orWhere('brand', 'like', "%{$search}%")
                ->orWhere('model', 'like', "%{$search}%")
                ->orWhere('car_type', 'like', "%{$search}%");
        }

        // Fetch all cars from the database, paginate results, and add image URL for each car
        $cars = $query->latest()->paginate(10)->through(function ($car) {
            $car->image_url = $car->image ? asset('storage/' . $car->image) : null;
            return $car;
        });

        return inertia('Admin/Cars/Index', [
            'cars' => $cars,
        ]);
    }


    // Show a specific car's details
    public function show(Car $car)
    {
        // Add image URL for preview
        $car->image_url = $car->image ? Storage::url($car->image) : null;

        // Return the car show view with the car data
        return inertia('Admin/Cars/Show', [
            'car' => $car,
        ]);
    }


    // Create a new car
    public function create()
    {
        return inertia('Admin/Cars/Create');
    }


    // Store a new car in the database
    public function store(Request $request)
    {

        // Validate and store the car data
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'brand' => 'required|string|max:255',
            'model' => 'required|string|max:255',
            'year' => 'required|integer|min:1886|max:' . date('Y'),
            'car_type' => 'required|string|max:255',
            'daily_rent_price' => 'required|numeric|min:0',
            'availability' => 'required|boolean',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        // Check if the image file is valid and store it
        if ($request->hasFile('image') && $request->file('image')->isValid()) {
            $data['image'] = $request->file('image')->store('cars', 'public');
        } else {
            return back()->withErrors(['image' => 'The image failed to upload.']);
        }

        // Create the car record in the database
        Car::create($data);

        // Redirect back to the car index with a success message
        return redirect()->route('admin.cars.index')->with('success', 'A new car added successfully.');
    }


    // Edit an existing car
    public function edit(Car $car)
    {
        // Add image URL for preview
        $car->image_url = $car->image ? Storage::url($car->image) : null;

        return inertia('Admin/Cars/Edit', [
            'car' => $car,
        ]);
    }


    // Update an existing car in the database
    public function update(Request $request, Car $car)
    {

        // Validate and update the car data
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'brand' => 'required|string|max:255',
            'model' => 'required|string|max:255',
            'year' => 'required|integer|min:1886|max:' . date('Y'),
            'car_type' => 'required|string|max:255',
            'daily_rent_price' => 'required|numeric|min:0',
            'availability' => 'required|boolean',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        if ($request->hasFile('image') && $request->file('image')->isValid()) {
            // Delete old image if exists
            if ($car->image) {
                Storage::disk('public')->delete($car->image);
            }
            $data['image'] = $request->file('image')->store('cars', 'public');
        } else {
            $data['image'] = $car->image;
        }

        // Update the car record in the database
        $car->update($data);

        // Redirect back to the car index with a success message
        return redirect()->route('admin.cars.index')->with('success', 'Car updated successfully.');
    }


    // Delete a car from the database
    public function destroy(Car $car)
    {
        // Delete the car image if it exists
        if ($car->image) {
            Storage::disk('public')->delete($car->image);
        }

        // Delete the car record from the database
        $car->delete();

        // Redirect back to the car index with a success message
        return redirect()->route('admin.cars.index')->with('success', 'Car deleted successfully.');
    }
}
