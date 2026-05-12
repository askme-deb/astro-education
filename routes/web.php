<?php

use App\Http\Controllers\Dashboard\StudentDashboardController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\LMS\CourseController;
use App\Http\Controllers\LMS\EnrollmentController;
use App\Http\Controllers\LMS\LiveClassController;

use App\Http\Controllers\Home;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\OtpAuthController;
use App\Http\Controllers\RegisterController;
use Illuminate\Support\Facades\Route;

Route::get('/', [Home::class, 'index'])->name('home');

Route::prefix('courses')->name('courses.')->group(function (): void {
	Route::get('/', [CourseController::class, 'index'])->name('index');
	Route::get('/{id}', [CourseController::class, 'show'])->name('show');
	Route::post('/{courseId}/enroll', [EnrollmentController::class, 'store'])->name('enroll');
});

Route::prefix('student')->group(function (): void {
	Route::get('/dashboard', [StudentDashboardController::class, 'index'])
        ->name('student.dashboard');

	Route::prefix('enrollments')->name('enrollments.')->group(function (): void {
		Route::get('/', [EnrollmentController::class, 'index'])->name('index');
		Route::get('/{id}', [EnrollmentController::class, 'show'])->name('show');
	});

	Route::prefix('live-classes')->name('live-classes.')->group(function (): void {
        Route::middleware(['api.user.auth'])->group(function (): void {
            Route::get('/', [LiveClassController::class, 'index'])->name('index');
            Route::get('/{id}', [LiveClassController::class, 'show'])->name('show');
            Route::post('/{id}/enroll', [LiveClassController::class, 'enroll'])->name('enroll');
        });
	});
});



Route::middleware(['guest'])->group(function () {
    Route::post('/login', [LoginController::class, 'login'])
        ->name('login.password');

    Route::post('/register', [RegisterController::class, 'register'])
        ->name('register.store');

    Route::post('/login/otp/request', [OtpAuthController::class, 'requestOtp'])
        ->middleware('throttle:otp')
        ->name('login.otp.request');

    Route::post('/login/otp/resend', [OtpAuthController::class, 'resendOtp'])
        ->middleware('throttle:otp')
        ->name('login.otp.resend');

    Route::post('/login/otp/verify', [OtpAuthController::class, 'verifyOtp'])
        ->middleware('throttle:otp')
        ->name('login.otp.verify');
});


Route::middleware(['api.user.auth'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::post('/logout', [OtpAuthController::class, 'logout'])->name('logout');
    // Route::get('/orders', [\App\Http\Controllers\OrderController::class, 'index'])->name('orders.index');
    //     Route::get('/orders/{order}', [\App\Http\Controllers\OrderController::class, 'show'])->name('orders.details');
    // // Address management
    // Route::get('/account/address', [AddressController::class, 'index'])->name('account.address');
    // Route::post('/account/address/save', [AddressController::class, 'save'])->name('account.address.save');
    // Route::post('/account/address/update/{id}', [AddressController::class, 'update'])->name('account.address.update');
    // Route::post('/account/address/delete/{id}', [AddressController::class, 'delete'])->name('account.address.delete');
    // Route::post('/account/address/default/{id}', [AddressController::class, 'setDefault'])->name('account.address.default');
    //     // Address AJAX endpoints for state/city dropdowns
    //     Route::post('/account/address/state-list', [\App\Http\Controllers\AddressController::class, 'stateList'])->name('account.address.state-list');
    //     Route::post('/account/address/city-list', [\App\Http\Controllers\AddressController::class, 'cityList'])->name('account.address.city-list');
    // Route::get('/account/settings', function () {
    //     // Placeholder account settings page
    //     return view('account.settings');
    // })->name('account.settings');
    // Route::get('/wishlist', function () {
    //     // Placeholder wishlist page
    //     return view('wishlist.index');
    // })->name('wishlist.index');
});
