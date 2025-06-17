<?php

use App\Http\Controllers\Auth\ProfileController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DesignDetailsController;
use App\Http\Controllers\ItemTemplateController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\OrganizationController;
use App\Http\Controllers\SubscriptionPlanController;
use App\Http\Controllers\TemplateController;
use App\Http\Controllers\UsersController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return view('home');
});

Route::get('/demo', function () {
    return view('demo');
})->name('demo');

// Route::get('dashboard', function () {
//     return Inertia::render('Dashboard');
// })->middleware(['auth', 'verified'])->name('dashboard');

Route::get('dashboard', DashboardController::class)
    ->middleware(['auth', 'verified'])
    ->name('dashboard');
Route::middleware(["auth", "verified"])->group(function () {

    //-- Make resource Route For Orders Process
    Route::resource('orders', OrderController::class)->only(
        'index',
        'create',
        'store',
        'show', // Display a specific order
        'edit',
        'update',
        'destroy'
    );

    Route::post('/organization/no-organization', [OrganizationController::class, 'setNoOrganization']);
    Route::resource('customers', CustomerController::class);
    Route::resource('items', TemplateController::class);
    Route::resource('subscription-plans', SubscriptionPlanController::class);
    Route::resource('user', UsersController::class);
    Route::resource('organization', OrganizationController::class);
    Route::post('/admin/users/{id}/toggle-status', [UsersController::class, 'toggleStatus'])->name('admin.toggleStatus');
    Route::resource('design-details', DesignDetailsController::class);
    Route::get('admin', function () {
        return Inertia::render('admin/Index');
    });
    Route::get('user-create', function () {
        return Inertia::render('admin/UserCreate');
    });
});

require __DIR__ . '/settings.php';
require __DIR__ . '/auth.php';
