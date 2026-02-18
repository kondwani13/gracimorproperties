<?php

use App\Http\Controllers\ApartmentController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\ApartmentController as AdminApartmentController;
use App\Http\Controllers\Admin\BookingController as AdminBookingController;
use App\Http\Controllers\Admin\UserController as AdminUserController;
use App\Http\Controllers\Admin\ReportController;
use App\Http\Controllers\Admin\TenantController;
use App\Http\Controllers\Admin\EmployeeController;
use App\Http\Controllers\Admin\EmployeeSalaryController;
use App\Http\Controllers\Admin\EmployeeLeaveController;
use App\Http\Controllers\Admin\RentPaymentController;
use App\Http\Controllers\Admin\MaintenanceCostController;
use App\Http\Controllers\Admin\BillController;
use App\Http\Controllers\Admin\ComplaintController;
use App\Http\Controllers\Admin\ConsentFormController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Public routes
Route::get('/', function () {
    $featuredApartments = \App\Models\Apartment::where('is_featured', true)
        ->with('images')
        ->take(6)
        ->get();
    return view('welcome', compact('featuredApartments'));
})->name('home');

// Apartment browsing (public)
Route::get('/apartments', [ApartmentController::class, 'index'])->name('apartments.index');
Route::get('/apartments/{apartment}', [ApartmentController::class, 'show'])->name('apartments.show');

// API endpoints for AJAX requests
Route::post('/api/check-availability', [ApartmentController::class, 'checkAvailability'])->name('api.check-availability');
Route::get('/api/booking/{booking}/status', [BookingController::class, 'checkStatus'])->name('api.booking.status')->middleware('auth');

// Authenticated user routes
Route::middleware(['auth', 'verified'])->group(function () {
    // Dashboard
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    // Profile
    Route::prefix('profile')->name('profile.')->group(function () {
        Route::get('/', [ProfileController::class, 'show'])->name('show');
        Route::get('/edit', [ProfileController::class, 'edit'])->name('edit');
        Route::put('/', [ProfileController::class, 'update'])->name('update');
        Route::put('/password', [ProfileController::class, 'updatePassword'])->name('password.update');
        Route::delete('/', [ProfileController::class, 'destroy'])->name('destroy');
    });

    // Favorites
    Route::post('/apartments/{apartment}/favorite', [ApartmentController::class, 'toggleFavorite'])->name('apartments.favorite');
    Route::get('/favorites', [ApartmentController::class, 'favorites'])->name('favorites');

    // Bookings
    Route::prefix('bookings')->name('bookings.')->group(function () {
        Route::get('/', [BookingController::class, 'index'])->name('index');
        Route::get('/create', [BookingController::class, 'create'])->name('create');
        Route::post('/', [BookingController::class, 'store'])->name('store');
        Route::get('/{booking}', [BookingController::class, 'show'])->name('show');
        Route::get('/{booking}/edit', [BookingController::class, 'edit'])->name('edit');
        Route::put('/{booking}', [BookingController::class, 'update'])->name('update');
        Route::put('/{booking}/cancel', [BookingController::class, 'cancel'])->name('cancel');
        Route::get('/{booking}/invoice', [BookingController::class, 'downloadInvoice'])->name('invoice');
    });

    // Payments
    Route::prefix('payments')->name('payments.')->group(function () {
        Route::get('/{booking}', [PaymentController::class, 'show'])->name('show');
        Route::post('/{booking}', [PaymentController::class, 'process'])->name('process');
        Route::get('/{booking}/success', [PaymentController::class, 'success'])->name('success');
        Route::get('/cancelled', [PaymentController::class, 'cancelled'])->name('cancelled');
        Route::get('/{booking}/lenco/callback', [PaymentController::class, 'lencoCallback'])->name('lenco.callback');
    });

    // Reviews
    Route::prefix('reviews')->name('reviews.')->group(function () {
        Route::get('/bookings/{booking}/create', [ReviewController::class, 'create'])->name('create');
        Route::post('/bookings/{booking}', [ReviewController::class, 'store'])->name('store');
        Route::get('/{review}/edit', [ReviewController::class, 'edit'])->name('edit');
        Route::put('/{review}', [ReviewController::class, 'update'])->name('update');
        Route::delete('/{review}', [ReviewController::class, 'destroy'])->name('destroy');
    });
});

// Payment webhooks (outside auth middleware)
Route::post('/webhook/stripe', [PaymentController::class, 'webhook'])->name('webhook.stripe');
Route::post('/webhook/lenco', [PaymentController::class, 'lencoWebhook'])->name('webhook.lenco');

// Public consent form signing
Route::get('consent-form/{consentForm}/sign', [ConsentFormController::class, 'showSignPage'])
    ->name('consent-forms.sign-page');
Route::post('consent-form/{consentForm}/sign', [ConsentFormController::class, 'sign'])
    ->name('consent-forms.sign');

// Admin routes
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    // Dashboard
    Route::get('/', [AdminDashboardController::class, 'index'])->name('dashboard');

    // Apartments
    Route::resource('apartments', AdminApartmentController::class);
    Route::post('apartments/{apartment}/toggle-availability', [AdminApartmentController::class, 'toggleAvailability'])
        ->name('apartments.toggle-availability');
    Route::delete('apartments/{apartment}/images/{image}', [AdminApartmentController::class, 'destroyImage'])
        ->name('apartments.images.destroy');

    // Bookings
    Route::resource('bookings', AdminBookingController::class)->only(['index', 'show']);
    Route::patch('bookings/{booking}/update-status', [AdminBookingController::class, 'updateStatus'])
        ->name('bookings.update-status');
    Route::post('bookings/{booking}/cancel', [AdminBookingController::class, 'cancel'])
        ->name('bookings.cancel');
    Route::post('bookings/{booking}/refund', [AdminBookingController::class, 'refund'])
        ->name('bookings.refund');
    Route::get('bookings/export', [AdminBookingController::class, 'export'])
        ->name('bookings.export');

    // Users
    Route::resource('users', AdminUserController::class);
    Route::post('users/{user}/toggle-status', [AdminUserController::class, 'toggleStatus'])
        ->name('users.toggle-status');

    // Tenants
    Route::resource('tenants', TenantController::class);

    // Employees
    Route::resource('employees', EmployeeController::class);
    Route::get('employees/{employee}/salary/create', [EmployeeSalaryController::class, 'create'])
        ->name('employees.salary.create');
    Route::post('employees/{employee}/salary', [EmployeeSalaryController::class, 'store'])
        ->name('employees.salary.store');

    // Employee Leave Requests
    Route::get('employee-leaves', [EmployeeLeaveController::class, 'index'])->name('employee-leaves.index');
    Route::get('employee-leaves/create', [EmployeeLeaveController::class, 'create'])->name('employee-leaves.create');
    Route::post('employee-leaves', [EmployeeLeaveController::class, 'store'])->name('employee-leaves.store');
    Route::post('employee-leaves/{leaveRequest}/update-status', [EmployeeLeaveController::class, 'updateStatus'])
        ->name('employee-leaves.update-status');

    // Rent Payments
    Route::resource('rent-payments', RentPaymentController::class)->only(['index', 'create', 'store', 'show']);

    // Maintenance Costs
    Route::resource('maintenance', MaintenanceCostController::class)->except(['show']);

    // Bills
    Route::resource('bills', BillController::class)->except(['show']);

    // Complaints
    Route::resource('complaints', ComplaintController::class)->only(['index', 'create', 'store', 'show']);
    Route::post('complaints/{complaint}/update-status', [ComplaintController::class, 'updateStatus'])
        ->name('complaints.update-status');

    // Consent Forms
    Route::get('consent-forms', [ConsentFormController::class, 'index'])->name('consent-forms.index');
    Route::get('consent-forms/{consentForm}', [ConsentFormController::class, 'adminView'])->name('consent-forms.show');
    Route::post('consent-forms/generate-for-tenant/{tenant}', [ConsentFormController::class, 'generateForTenant'])
        ->name('consent-forms.generate-tenant');
    Route::post('consent-forms/generate-for-booking/{booking}', [ConsentFormController::class, 'generateForBooking'])
        ->name('consent-forms.generate-booking');
    Route::get('consent-forms/{consentForm}/pdf', [ConsentFormController::class, 'downloadPdf'])
        ->name('consent-forms.pdf');

    // Reports
    Route::prefix('reports')->name('reports.')->group(function () {
        Route::get('/', [ReportController::class, 'index'])->name('index');
        Route::get('/revenue', [ReportController::class, 'revenue'])->name('revenue');
        Route::get('/bookings', [ReportController::class, 'bookings'])->name('bookings');
        Route::get('/users', [ReportController::class, 'users'])->name('users');
        Route::get('/occupancy', [ReportController::class, 'occupancy'])->name('occupancy');
        Route::get('/maintenance', [ReportController::class, 'maintenance'])->name('maintenance');
        Route::get('/bills', [ReportController::class, 'bills'])->name('bills');
    });
});

// Auth routes (provided by Laravel Breeze/Jetstream or custom auth)
require __DIR__.'/auth.php';
