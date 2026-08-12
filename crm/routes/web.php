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
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\LeadAssignmentController;
use App\Http\Controllers\Partner\LeadController as PartnerLeadController;
use App\Http\Controllers\ExportController;
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

Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard.index');
});

Route::middleware(['auth'])->group(function () {
    // Admin Lead Assignment
    Route::post('/lead-assignments', [LeadAssignmentController::class, 'store'])->name('lead-assignments.store');

    // Portal Partner
    Route::get('/partner/leads', [PartnerLeadController::class, 'index'])->name('partner.leads.index');

    // Portal Partner
    Route::get('/partner/leads', [PartnerLeadController::class, 'index'])->name('partner.leads.index');
    Route::patch('/partner/leads/{lead}/status', [PartnerLeadController::class, 'updateStatus'])->name('partner.leads.update-status');
});

Route::middleware(['auth'])->group(function () {
    // Export Routes
    Route::get('/export/leads', [ExportController::class, 'exportLeads'])->name('export.leads');
    Route::get('/export/sales', [ExportController::class, 'exportSales'])->name('export.sales');
});

require __DIR__.'/auth.php';
