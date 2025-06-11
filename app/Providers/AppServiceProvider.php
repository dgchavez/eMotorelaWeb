<?php

namespace App\Providers;

use App\Models\User;
use App\Observers\UserObserver;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Route;

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
        User::observe(UserObserver::class);
    }

    /**
     * Get the redirect path based on user role
     */
    public static function redirectTo(): string
    {
        if (auth()->check()) {
            return match(auth()->user()->role) {
                0 => '/admin/dashboard',  // Admin
                1 => '/staff/dashboard',  // Staff
                default => '/login'
            };
        }

        return '/login';
    }
}
