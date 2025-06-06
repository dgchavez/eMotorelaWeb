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
use App\Http\Controllers\ApplicationTrackingController;
use App\Http\Controllers\DocumentController;
use App\Http\Controllers\FranchiseCancellationController;
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
        Route::resource('toda', \App\Http\Controllers\Admin\TodaController::class)->except(['destroy']);
        Route::put('toda/{toda}/toggle-status', [\App\Http\Controllers\Admin\TodaController::class, 'toggleStatus'])
             ->name('toda.toggle-status');
        Route::get('/reports', [ReportController::class, 'index'])->name('reports.index');
        Route::get('/settings', [SettingsController::class, 'index'])->name('settings.index');
        Route::resource('operators', \App\Http\Controllers\Admin\OperatorController::class);
        Route::resource('drivers', \App\Http\Controllers\Admin\DriverController::class)->names([
            'index' => 'admin.drivers.index',
            'create' => 'admin.drivers.create',
            'store' => 'admin.drivers.store',
            'show' => 'admin.drivers.show',
            'edit' => 'admin.drivers.edit', 
            'update' => 'admin.drivers.update',
            'destroy' => 'admin.drivers.destroy',
        ]);
        Route::get('/drivers/{driver}/motorcycles', [\App\Http\Controllers\Admin\DriverController::class, 'getMotorcycles'])
            ->name('admin.drivers.motorcycles');
        Route::resource('applications', ApplicationController::class);
        Route::resource('users', \App\Http\Controllers\Admin\UserController::class);

 
    });

    // Staff Routes
    Route::middleware(['staff'])->prefix('staff')->group(function () {
        Route::get('/dashboard', [StaffDashboardController::class, 'index'])->name('staff.dashboard');
        Route::resource('drivers', DriverController::class);
    });

    // Application Tracking Routes
    Route::get('/track/{trackingCode}', [ApplicationTrackingController::class, 'track'])
        ->name('application.track');
    Route::post('/track', [ApplicationTrackingController::class, 'search'])
        ->name('application.search');

    // Document routes
    Route::get('/documents/{type}/{operator}', [DocumentController::class, 'generate'])->name('documents.generate');
    Route::get('/documents/preview/{type}/{operator}', [DocumentController::class, 'preview'])->name('documents.preview');
    Route::get('/documents/monthly-report', [DocumentController::class, 'monthlyReport'])->name('documents.monthly-report');

    // Franchise Cancellation Routes
    Route::get('/operators/{operator}/cancel-franchise', [FranchiseCancellationController::class, 'create'])
        ->name('franchise-cancellations.create');
    Route::post('/operators/{operator}/cancel-franchise', [FranchiseCancellationController::class, 'store'])
        ->name('franchise-cancellations.store');

    // Document Generation Routes
    Route::middleware(['auth', 'verified'])->group(function () {
        Route::get('/documents/franchise-certificate/{operator}', [DocumentController::class, 'generateFranchiseCertificate'])
            ->name('documents.franchise-certificate');
        Route::get('/documents/motorela-permit/{operator}', [DocumentController::class, 'generateMotorelaPermit'])
            ->name('documents.motorela-permit');
    });
});

require __DIR__.'/auth.php';
