<?php

use App\Http\Controllers\Dashboard\StudentDashboardController;
use App\Http\Controllers\LMS\CourseController;
use App\Http\Controllers\LMS\EnrollmentController;
use App\Http\Controllers\LMS\LiveClassController;

use App\Http\Controllers\Home;
use Illuminate\Support\Facades\Route;

Route::get('/', [Home::class, 'index'])->name('home');

Route::prefix('courses')->name('courses.')->group(function (): void {
	Route::get('/', [CourseController::class, 'index'])->name('index');
	Route::get('/{id}', [CourseController::class, 'show'])->name('show');
	Route::post('/{courseId}/enroll', [EnrollmentController::class, 'store'])->name('enroll');
});

Route::prefix('student')->group(function (): void {
	Route::get('/dashboard', [StudentDashboardController::class, 'index'])
		->name('dashboard');

	Route::prefix('enrollments')->name('enrollments.')->group(function (): void {
		Route::get('/', [EnrollmentController::class, 'index'])->name('index');
		Route::get('/{id}', [EnrollmentController::class, 'show'])->name('show');
	});

	Route::prefix('live-classes')->name('live-classes.')->group(function (): void {
		Route::get('/', [LiveClassController::class, 'index'])->name('index');
		Route::get('/{id}', [LiveClassController::class, 'show'])
			->middleware('enrollment')
			->name('show');
	});
});
