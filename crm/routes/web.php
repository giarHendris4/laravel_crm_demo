<?php

namespace App\Http\Controllers;

use App\Http\Controllers\DealController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboard;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\LeadController;
use App\Http\Controllers\Partner\DashboardController as PartnerDashboard;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Sales\DashboardController as SalesDashboard;
use App\Http\Controllers\ActivityController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    $user = auth()->user();

    return match ($user->role) {
        'admin'   => redirect()->route('admin.dashboard'),
        'sales'   => redirect()->route('sales.dashboard'),
        'partner' => redirect()->route('partner.dashboard'),
        default   => abort(403, 'Role tidak dikenali.'),
    };
})->middleware(['auth'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Route untuk Admin
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminDashboard::class, 'index'])->name('dashboard');
    
    // Resource route untuk User Management
    Route::resource('users', UserController::class);
});

// Route untuk Sales
Route::middleware(['auth', 'role:sales'])->prefix('sales')->name('sales.')->group(function () {
    Route::get('/dashboard', [SalesDashboard::class, 'index'])->name('dashboard');
});

// Route untuk Partner
Route::middleware(['auth', 'role:partner'])->prefix('partner')->name('partner.')->group(function () {
    Route::get('/dashboard', [PartnerDashboard::class, 'index'])->name('dashboard');
});

// Route Leads Management
Route::middleware(['auth'])->group(function () {
    Route::resource('leads', LeadController::class);
});

Route::middleware(['auth'])->group(function () {
    Route::resource('deals', DealController::class);
});

Route::middleware(['auth'])->group(function () {
    Route::post('activities', [ActivityController::class, 'store'])->name('activities.store');
});

require __DIR__.'/auth.php';
