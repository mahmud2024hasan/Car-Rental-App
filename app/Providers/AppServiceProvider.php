<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Inertia::share([
            // Logged-in user
            'auth' => function () {
                return [
                    'user' => Auth::user(),
                ];
            },

            // Customer profile (if available)
            'profile' => function () {
                $user = Auth::user();
                return $user && $user->customerProfile ? $user->customerProfile : null;
            },
        ]);
    }
}

