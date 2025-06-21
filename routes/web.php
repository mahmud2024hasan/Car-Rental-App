<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\CarController;
use App\Http\Controllers\Admin\RentalController;
use App\Http\Controllers\Admin\CustomerController;
use App\Http\Controllers\Frontend\PageController;
use App\Http\Controllers\Frontend\CarController as FrontendCarController;
use App\Http\Controllers\Frontend\RentalController as FrontendRentalController;
use App\Http\Controllers\Frontend\CustomerController as FrontendCustomerController;


// Admin routes 
Route::middleware(['auth', 'verified', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Car management routes
    Route::get('/cars', [CarController::class, 'index'])->name('cars.index');
    Route::get('/cars/create', [CarController::class, 'create'])->name('cars.create');
    Route::post('/cars', [CarController::class, 'store'])->name('cars.store');
    Route::get('/cars/{car}', [CarController::class, 'show'])->name('cars.show');
    Route::get('/cars/{car}/edit', [CarController::class, 'edit'])->name('cars.edit');
    Route::put('/cars/{car}', [CarController::class, 'update'])->name('cars.update');
    Route::delete('/cars/{car}', [CarController::class, 'destroy'])->name('cars.destroy');

    // Rental management routes
    Route::get('/rentals', [RentalController::class, 'index'])->name('rentals.index');
    Route::get('/rentals/create', [RentalController::class, 'create'])->name('rentals.create');
    Route::post('/rentals', [RentalController::class, 'store'])->name('rentals.store');
    Route::get('/rentals/{rental}', [RentalController::class, 'show'])->name('rentals.show');
    Route::get('/rentals/{rental}/edit', [RentalController::class, 'edit'])->name('rentals.edit');
    Route::put('/rentals/{rental}', [RentalController::class, 'update'])->name('rentals.update');
    Route::delete('/rentals/{rental}', [RentalController::class, 'destroy'])->name('rentals.destroy');

    // Customer management routes
    Route::get('/customers', [CustomerController::class, 'index'])->name('customers.index');
    Route::get('/customers/create', [CustomerController::class, 'create'])->name('customers.create');
    Route::post('/customers', [CustomerController::class, 'store'])->name('customers.store');
    Route::get('/customers/{customer}', [CustomerController::class, 'show'])->name('customers.show');
    Route::get('/customers/{customer}/edit', [CustomerController::class, 'edit'])->name('customers.edit');
    Route::put('/customers/{customer}', [CustomerController::class, 'update'])->name('customers.update');
    Route::delete('/customers/{customer}', [CustomerController::class, 'destroy'])->name('customers.destroy');
});


// Customer routes
Route::middleware(['auth', 'verified', 'role:customer'])->prefix('customer')->name('customer.')->group(function () {
    Route::get('/dashboard', [FrontendCustomerController::class, 'index'])->name('dashboard');

    // Bookings by customer
    Route::get('/bookings', [FrontendCustomerController::class, 'customerBookings'])->name('bookings');
    Route::get('/bookings/create', [FrontendCustomerController::class, 'bookingCreate'])->name('bookings.create');
    Route::post('/bookings', [FrontendCustomerController::class, 'store'])->name('bookings.store');
    Route::delete('/bookings/{rental}/cancel', [FrontendCustomerController::class, 'cancel'])->name('bookings.cancel')->middleware('can:cancel,rental');

    // Profile management by customer
    Route::get('/profile/{customer}', [FrontendCustomerController::class, 'profile'])->name('profile.settings');
    Route::get('/profile/{customer}/edit', [FrontendCustomerController::class, 'edit'])->name('profile.edit');
    Route::post('/customer/profile', [FrontendCustomerController::class, 'update'])->name('profile.update');
    Route::post('/profile/image', [FrontendCustomerController::class, 'updateImage'])->name('profile.image.update');
    Route::post('/profile/password', [FrontendCustomerController::class, 'updatePassword'])->name('profile.password.update');

    Route::delete('/profile/{customer}', [FrontendCustomerController::class, 'destroy'])->name('profile.destroy');
});


// Frontend routes - Public Site
Route::prefix('/')->name('frontend.')->group(function () {

    // Static Pages
    Route::get('/', [PageController::class, 'home'])->name('home');
    Route::get('/about', [PageController::class, 'about'])->name('about');
    Route::get('/contact', [PageController::class, 'contact'])->name('contact');

    // Cars
    Route::get('/cars', [FrontendCarController::class, 'index'])->name('cars');
});


require __DIR__ . '/settings.php';
require __DIR__ . '/auth.php';
