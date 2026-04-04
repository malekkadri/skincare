<?php

use App\Http\Controllers\Admin\AppointmentController;
use App\Http\Controllers\Admin\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Admin\AvailabilityController as AdminAvailabilityController;
use App\Http\Controllers\Admin\BlockedDateController;
use App\Http\Controllers\Admin\BlockedTimeRangeController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\ServiceCategoryController;
use App\Http\Controllers\Admin\ServiceController;
use App\Http\Controllers\Admin\SettingsController;
use App\Http\Controllers\Booking\AvailabilityController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/booking/available-slots', AvailabilityController::class)->name('booking.available-slots');

Route::prefix('admin')->name('admin.')->group(function () {
    Route::middleware('guest')->group(function () {
        Route::get('/login', [AuthenticatedSessionController::class, 'create'])->name('login');
        Route::post('/login', [AuthenticatedSessionController::class, 'store'])->name('login.store');
    });

    Route::middleware(['auth', 'admin'])->group(function () {
        Route::get('/', DashboardController::class)->name('dashboard');

        Route::get('/settings', [SettingsController::class, 'edit'])->name('settings.edit');
        Route::put('/settings', [SettingsController::class, 'update'])->name('settings.update');

        Route::resource('categories', ServiceCategoryController::class)->except('show');
        Route::resource('services', ServiceController::class)->except('show');

        Route::resource('appointments', AppointmentController::class)->except('destroy', 'show');
        Route::patch('appointments/{appointment}/status', [AppointmentController::class, 'updateStatus'])->name('appointments.status');

        Route::get('availability', [AdminAvailabilityController::class, 'edit'])->name('availability.edit');
        Route::put('availability/hours', [AdminAvailabilityController::class, 'updateHours'])->name('availability.hours.update');
        Route::put('availability/settings', [AdminAvailabilityController::class, 'updateSettings'])->name('availability.settings.update');

        Route::get('blocked-dates', [BlockedDateController::class, 'index'])->name('blocked-dates.index');
        Route::post('blocked-dates', [BlockedDateController::class, 'store'])->name('blocked-dates.store');
        Route::delete('blocked-dates/{blockedDate}', [BlockedDateController::class, 'destroy'])->name('blocked-dates.destroy');

        Route::get('blocked-times', [BlockedTimeRangeController::class, 'index'])->name('blocked-times.index');
        Route::post('blocked-times', [BlockedTimeRangeController::class, 'store'])->name('blocked-times.store');
        Route::delete('blocked-times/{blockedTime}', [BlockedTimeRangeController::class, 'destroy'])->name('blocked-times.destroy');

        Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])->name('logout');
    });
});

Route::redirect('/login', '/admin/login')->name('login');
