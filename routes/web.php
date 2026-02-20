<?php

use App\Http\Controllers\AssetController;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\FaultReportController;
use App\Http\Controllers\UserController;
use App\Http\Middleware\AdminMiddleware;
use Illuminate\Support\Facades\Route;

// Guest Routes (Login & Registration)
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
    Route::get('/register', [AuthController::class, 'showRegistrationForm'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
});

// Authenticated Routes
Route::middleware('auth')->group(function () {
    // Redirect root to dashboard
    Route::get('/', function () {
        return redirect()->route('dashboard');
    });

    // Logout
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // User Profile
    Route::get('/profile', [UserController::class, 'profile'])->name('profile');
    Route::put('/profile', [UserController::class, 'updateProfile'])->name('profile.update');

    // Assets
    Route::resource('assets', AssetController::class);

    // Fault Reports
    Route::resource('faults', FaultReportController::class);
    Route::patch('/faults/{fault}/assign-technician', [FaultReportController::class, 'assignTechnician'])
        ->name('faults.assign-technician');
    Route::patch('/faults/{fault}/update-status', [FaultReportController::class, 'updateStatus'])
        ->name('faults.update-status');
    Route::patch('/faults/{fault}/close', [FaultReportController::class, 'close'])
        ->name('faults.close');

    // User Management (Admin Only)
    Route::middleware(AdminMiddleware::class)->group(function () {
        Route::resource('users', UserController::class)->except(['profile']);
    });
});
