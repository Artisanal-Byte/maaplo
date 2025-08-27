<?php

use App\Http\Controllers\Auth\ProfileController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DesignDetailsController;
use App\Http\Controllers\ErrorReportController;
use App\Http\Controllers\FeatureRequestAndSuggestionController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ItemTemplateController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\OrganizationController;
use App\Http\Controllers\SocialiteController;
use App\Http\Controllers\SubscriptionPlanController;
use App\Http\Controllers\TemplateController;
use App\Http\Controllers\UsersController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

// Public/Home Routes
Route::get('/', [HomeController::class, 'index'])->name('home');

// Google OAuth
Route::controller(SocialiteController::class)->group(function () {
    Route::get('auth/google', 'googleLogin')->name('auth.google');
    Route::get('auth/google-callback', 'googleAuthentication')->name('auth.google-callback');
});

// Demo
Route::get('/demo', function () {
    return view('demo');
})->name('demo');

// Autofill login defaults only for allowed domains
Route::get('/login-defaults', function () {
    if (env("APP_ENV") === "production") {
        return response()->json(['auto_fill' => false]);
    }

    return response()->json([
        'auto_fill' => true,
        'email' => 'demo@example.com',
        'password' => 'test@123',
    ]);
});


// Dashboard
Route::get('dashboard', DashboardController::class)
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

// ✅ Routes for all authenticated users (admin + user)
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/orders/closed', [DashboardController::class, 'closedOrdersPage'])->name('orders.closed');
    Route::get('/orders/close', [DashboardController::class, 'closeOrdersPage'])->name('orders.close');

    Route::get('/orders/view-closed', [DashboardController::class, 'viewClosedOrders'])->name('orders.viewClosed');
    Route::get('/orders/view-close', [DashboardController::class, 'viewCloseOrders'])->name('orders.viewClose');
    Route::post('/orders/deliverd', [DashboardController::class, 'deliverd'])->name('orders.deliverd');

    Route::post('/orders/close', [DashboardController::class, 'close'])->name('orders.close');
    Route::get('/reporterror/create', [ErrorReportController::class, 'create'])->name('reporterror.create');
    Route::post('/reporterror', [ErrorReportController::class, 'store'])->name('reporterror.store');
    // Order routes
    Route::resource('orders', OrderController::class)->only(
        'index',
        'create',
        'store',
        'show',
        'edit',
        'update',
        'destroy'
    );
    Route::post('/factory-seed', [DashboardController::class, 'seed'])->name('factory.seed')->middleware('auth');
    Route::resource('customers', CustomerController::class);
    Route::resource('items', TemplateController::class);
    Route::resource('organization', OrganizationController::class);
    Route::post('/orders/update-status', [OrderController::class, 'updateStatus'])->name('orders.updateStatus');

    // Allow all users to submit feature/suggestion
    Route::get('/feature-request/create', fn() => Inertia::render('featurerequest/Create'))->name('feature-request.create');
    Route::post('/feature-request', [FeatureRequestAndSuggestionController::class, 'storeFeatureRequest'])->name('feature-request.store');

    Route::get('/suggestion/create', fn() => Inertia::render('suggestion/Create'))->name('suggestion.create');
    Route::post('/suggestion', [FeatureRequestAndSuggestionController::class, 'storeSuggestion'])->name('suggestion.store');
    Route::post('/organization/no-organization', [OrganizationController::class, 'setNoOrganization']);
    // route fetch customer data
    Route::get('/orders/customers/fetch', [OrderController::class, 'fetchCustomers'])->name('orders.fetchCustomers');
    Route::get('/orders/customers/{customer}/measurements', [OrderController::class, 'getCustomerMeasurements']);



    // Charts and Exports
    Route::get('/dashboard/chart-data', [DashboardController::class, 'getChartData'])->name('dashboard.chart-data');
    Route::get('/export-orders-csv', [DashboardController::class, 'exportOrdersToCSV']);
});

// ✅ Admin-Only Routes
Route::middleware(['auth', 'verified', 'role:admin'])->group(function () {
    Route::resource('subscription-plans', SubscriptionPlanController::class);
    Route::resource('user', UsersController::class);
    Route::get('admin', fn() => Inertia::render('admin/Index'));
    Route::get('user-create', fn() => Inertia::render('admin/UserCreate'));
    Route::post('/admin/users/{id}/toggle-status', [UsersController::class, 'toggleStatus'])->name('admin.toggleStatus');
    Route::get('/reporterror', [ErrorReportController::class, 'index'])->name('reporterror.index');
    Route::get('/feature-request', [FeatureRequestAndSuggestionController::class, 'indexFeatureRequest'])->name('feature-request.index');
    Route::get('/suggestion', [FeatureRequestAndSuggestionController::class, 'indexSuggestion'])->name('suggestion.index');
    Route::resource('design-details', DesignDetailsController::class);
});

require __DIR__ . '/settings.php';
require __DIR__ . '/auth.php';
