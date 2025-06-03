<?php

use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Staff\DashboardController as StaffDashboardController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\TodaController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\OperatorController;
use App\Http\Controllers\DriverController;
use App\Http\Controllers\ApplicationController;
use App\Http\Controllers\SettingsController;
use Illuminate\Support\Facades\Route;

// Public routes
Route::get('/', function () {
    return view('welcome');
});

// Main dashboard route - redirects based on role
Route::get('/dashboard', function () {
    if (auth()->check()) {
        return match(auth()->user()->role) {
            0 => redirect()->route('admin.dashboard'),
            1 => redirect()->route('staff.dashboard'),
            default => redirect('/login')
        };
    }
    return redirect('/login');
})->middleware(['auth', 'verified'])->name('dashboard');

// Authenticated routes
Route::middleware(['auth'])->group(function () {
    // Common routes for both roles
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Admin Routes
    Route::middleware(['admin'])->prefix('admin')->group(function () {
        Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('admin.dashboard');
        Route::resource('users', UserController::class);
        Route::resource('toda', \App\Http\Controllers\Admin\TodaController::class)->names([
            'index' => 'toda.index',
            'create' => 'toda.create',
            'store' => 'toda.store',
            'show' => 'toda.show',
            'edit' => 'toda.edit',
            'update' => 'toda.update',
            'destroy' => 'toda.destroy',
        ]);
        Route::get('/reports', [ReportController::class, 'index'])->name('reports.index');
        Route::get('/settings', [SettingsController::class, 'index'])->name('settings.index');
        Route::resource('operators', \App\Http\Controllers\Admin\OperatorController::class);
        Route::resource('drivers', DriverController::class);
        Route::resource('applications', ApplicationController::class);
        Route::resource('users', \App\Http\Controllers\Admin\UserController::class);
   
    });

    // Staff Routes
    Route::middleware(['staff'])->prefix('staff')->group(function () {
        Route::get('/dashboard', [StaffDashboardController::class, 'index'])->name('staff.dashboard');
    });
});

require __DIR__.'/auth.php';
