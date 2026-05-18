<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Home;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\OtpAuthController;
use App\Http\Controllers\InstructorDashboardController;

use App\Http\Controllers\Dashboard\StudentDashboardController;

use App\Http\Controllers\LMS\CourseController;
use App\Http\Controllers\LMS\EnrollmentController;
use App\Http\Controllers\LMS\LiveClassController as LMSLiveClassController;

use App\Http\Controllers\Student\LiveClassController as StudentLiveClassController;

// Main API Live Class Controller
use App\Http\Controllers\LiveClassController as ApiLiveClassController;

Route::get('/', [Home::class, 'index'])->name('home');

/*
|--------------------------------------------------------------------------
| Courses
|--------------------------------------------------------------------------
*/

Route::prefix('courses')
    ->name('courses.')
    ->group(function (): void {

        Route::get('/', [CourseController::class, 'index'])
            ->name('index');

        Route::get('/{id}', [CourseController::class, 'show'])
            ->name('show');

        Route::post('/{courseId}/enroll', [EnrollmentController::class, 'store'])
            ->name('enroll');
    });

/*
|--------------------------------------------------------------------------
| Student
|--------------------------------------------------------------------------
*/

Route::prefix('student')
    ->group(function (): void {

        Route::get('/dashboard', [StudentDashboardController::class, 'index'])
            ->name('student.dashboard');

        Route::get('/my-live-classes', [StudentLiveClassController::class, 'myClasses'])
            ->name('student.my-live-classes');

        /*
        |--------------------------------------------------------------------------
        | Enrollments
        |--------------------------------------------------------------------------
        */

        Route::prefix('enrollments')
            ->name('enrollments.')
            ->group(function (): void {

                Route::get('/', [EnrollmentController::class, 'index'])
                    ->name('index');

                Route::get('/{id}', [EnrollmentController::class, 'show'])
                    ->name('show');
            });

        /*
        |--------------------------------------------------------------------------
        | Live Classes
        |--------------------------------------------------------------------------
        */

        Route::prefix('live-classes')
            ->name('live-classes.')
            ->middleware(['api.user.auth'])
            ->group(function (): void {

                Route::get('/', [LMSLiveClassController::class, 'index'])
                    ->name('index');

                Route::get('/{id}', [LMSLiveClassController::class, 'show'])
                    ->name('show');

                Route::post('/{id}/enroll', [LMSLiveClassController::class, 'enroll'])
                    ->name('enroll');

                /*
                |--------------------------------------------------------------------------
                | Join Live Class
                |--------------------------------------------------------------------------
                */

                Route::get('/{id}/join', [ApiLiveClassController::class, 'join'])
                    ->name('join');

                /*
                |--------------------------------------------------------------------------
                | Start Live Class
                |--------------------------------------------------------------------------
                */

                Route::post('/{id}/start', [ApiLiveClassController::class, 'start'])
                    ->name('start');

                Route::get('/{id}/room', [LMSLiveClassController::class, 'room'])
                    ->name('room');
            });
    });

/*
|--------------------------------------------------------------------------
| Instructor
|--------------------------------------------------------------------------
*/

Route::prefix('instructor')
    ->group(function (): void {

        Route::get('/dashboard', [InstructorDashboardController::class, 'index'])
            ->name('instructor.dashboard');
    });

/*
|--------------------------------------------------------------------------
| Guest Routes
|--------------------------------------------------------------------------
*/

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

/*
|--------------------------------------------------------------------------
| Authenticated Routes
|--------------------------------------------------------------------------
*/

Route::middleware(['api.user.auth'])->group(function () {

    Route::get('/dashboard', [DashboardController::class, 'index'])
        ->name('dashboard');

    Route::post('/logout', [OtpAuthController::class, 'logout'])
        ->name('logout');
});
