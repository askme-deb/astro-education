<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LiveClassController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });

Route::prefix('/v1')->group(function () {
    // Instructor/Admin live class endpoints
   // Route::middleware('auth:sanctum')->group(function () {
        Route::post('/live-classes', [LiveClassController::class, 'create']);
        Route::get('/live-classes', [LiveClassController::class, 'index']);
        Route::get('/live-classes/{id}', [LiveClassController::class, 'show']);
        Route::put('/live-classes/{id}', [LiveClassController::class, 'update']);
        Route::delete('/live-classes/{id}', [LiveClassController::class, 'destroy']);
        Route::post('/live-classes/{id}/start', [LiveClassController::class, 'start']);

        // Student live class endpoints
        Route::get('/my-live-classes', [LiveClassController::class, 'myClasses']);
        Route::post('/live-classes/{id}/enroll', [LiveClassController::class, 'enroll']);
        Route::get('/live-classes/{id}/join', [LiveClassController::class, 'join']);
        Route::get('/live-classes/{id}/recording', [LiveClassController::class, 'getRecording']);

        // Room access endpoints
        Route::get('/live-classes/{id}/room', [LiveClassController::class, 'room']);
        Route::post('/live-classes/{id}/end', [LiveClassController::class, 'end']);
        Route::get('/live-classes/{id}/room/recording', [LiveClassController::class, 'getRecording']);
    // });
});
