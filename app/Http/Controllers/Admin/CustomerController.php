<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class CustomerController extends Controller
{
    // Show the customer management page
    public function index()
    {
        // Initialize query to fetch customers with their profiles
        $query = User::with('customerProfile')->where('role', 'customer');

        // Apply search filter if provided. This will search by customer name, email, mobile or city
        if ($search = request('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhereHas('customerProfile', function ($q) use ($search) {
                        $q->where('mobile', 'like', "%{$search}%")
                            ->orWhere('address', 'like', "%{$search}%")
                            ->orWhere('district', 'like', "%{$search}%")
                            ->orWhere('division', 'like', "%{$search}%");
                    });
            });
        }

        $customers = $query->latest()->paginate(10)->through(function ($customer) {
            $customer->customer_id = $customer->id;

            // Attach profile data if exists
            $customer->profile = $customer->customerProfile ? [
                'address' => $customer->customerProfile->address,
                'mobile' => $customer->customerProfile->mobile,
                'district' => $customer->customerProfile->district,
                'division' => $customer->customerProfile->division,
                'postal_code' => $customer->customerProfile->postal_code,
                'image' => $customer->customerProfile->image,
            ] : null;

            // Create image URL from profile image
            $customer->image_url = $customer->customerProfile && $customer->customerProfile->image
                ? Storage::url($customer->customerProfile->image)
                : null;

            return $customer;
        });



        // Return the customers index view with the customers data
        return inertia('Admin/Customers/Index', [
            'customers' => $customers,
            'filters' => request()->only('search'),
        ]);
    }

    // Show the create customer form
    public function create()
    {
        return inertia('Admin/Customers/Create');
    }

    // Store a new customer in the database
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string',
            'email' => 'required|email|unique:users',
            'mobile' => 'required|string|unique:customer_profiles',
            'password' => 'required|string|min:8|same:password_confirmation',
            'address' => 'nullable|string',
            'district' => 'nullable|string',
            'division' => 'nullable|string',
            'postal_code' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        // Check if the image file is valid and store it
        if ($request->hasFile('image') && $request->file('image')->isValid()) {
            $validated['image'] = $request->file('image')->store('profile-images', 'public');
        } else {
            return back()->withErrors(['image' => 'The image failed to upload.']);
        }

        DB::transaction(function () use ($validated) {
            $user = User::create([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'password' => bcrypt($validated['password']),
                'role' => 'customer',
            ]);

            $user->customerProfile()->create([
                'address' => $validated['address'] ?? '',
                'mobile' => $validated['mobile'] ?? '',
                'district' => $validated['district'] ?? '',
                'division' => $validated['division'] ?? '',
                'postal_code' => $validated['postal_code'] ?? '',
                'image' => $validated['image'] ?? '',
            ]);
        });

        return redirect()->route('admin.customers.index')->with('success', 'A new customer created successfully.');
    }

    // Show the customer details from users table with his customer profile
    public function show(User $customer)
    {
        // Load the user data with customer profile relationship
        $customer->load('customerProfile');

        // Image URL from profile image
        $customer->image_url = $customer->customerProfile && $customer->customerProfile->image
            ? Storage::url($customer->customerProfile->image)
            : null;

        // Return the customer details view with the customer data
        return inertia('Admin/Customers/Show', [
            'customer' => $customer,
        ]);
    }

    // Edit the user data with his customer details
    public function edit(User $customer)
    {
        // Load the user data with customer profile relationship
        $customer->load('customerProfile');

        // Image URL from profile image
        $customer->image_url = $customer->customerProfile && $customer->customerProfile->image
            ? Storage::url($customer->customerProfile->image)
            : null;

        // Return the customer details view with the customer data
        return inertia('Admin/Customers/Edit', [
            'customer' => $customer,
        ]);
    }

    // Update the user data with his customer details
    public function update(Request $request, User $customer)
    {
        // Check if the customer has a profile and get its ID
        $customerProfileId = $customer->customerProfile ? $customer->customerProfile->id : null;

        // Validate the request data
        $validated = $request->validate([
            'name' => 'required|string',
            'email' => 'required|email|unique:users,email,' . $customer->id,
            'mobile' => 'required|string|unique:customer_profiles,mobile,' . $customerProfileId,
            'address' => 'nullable|string',
            'district' => 'nullable|string',
            'division' => 'nullable|string',
            'postal_code' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        // Handle image upload if provided
        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('profile-images', 'public');
        }

        // Start a database transaction to ensure data integrity
        DB::transaction(function () use ($validated, $customer, $imagePath) {

            // Update user table
            $customer->update([
                'name' => $validated['name'],
                'email' => $validated['email'],
            ]);

            // Prepare profile data
            $profileData = [
                'address' => $validated['address'] ?? '',
                'mobile' => $validated['mobile'] ?? '',
                'district' => $validated['district'] ?? '',
                'division' => $validated['division'] ?? '',
                'postal_code' => $validated['postal_code'] ?? '',
            ];

            // Add image path if image was uploaded
            if ($imagePath) {
                $profileData['image'] = $imagePath;
            }

            // Update or create profile
            if ($customer->customerProfile) {
                $customer->customerProfile->update($profileData);
            } else {
                $customer->customerProfile()->create($profileData);
            }
        });

        // Redirect back
        return redirect()->route('admin.customers.index')->with('success', 'Customer details updated successfully.');
    }


    // Delete a customer from the database
    public function destroy(User $customer)
    {
        // Start a database transaction to ensure data integrity
        DB::transaction(function () use ($customer) {

            // Delete the customer profile if it exists
            if ($customer->customerProfile) {
                $customer->customerProfile->delete();
            }

            // Delete the user
            $customer->delete();
        });

        // Redirect back to the customers index with a success message
        return redirect()->route('admin.customers.index')->with('success', 'Customer deleted successfully.');
    }
}
