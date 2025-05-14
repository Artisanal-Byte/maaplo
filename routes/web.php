<?php

use App\Http\Controllers\CustomerController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\TemplateController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('Welcome');
})->name('home');

Route::get('dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

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


    Route::resource('customers', CustomerController::class);
    // Route::resource('orders', OrderController::class)->names(["index"]);
    Route::resource('items', TemplateController::class);

});

require __DIR__ . '/settings.php';
require __DIR__ . '/auth.php';
