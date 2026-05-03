<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Courses;
use App\Http\Controllers\Home;
use App\Http\Controllers\Blog;
use App\Http\Controllers\CourseDetsils;

// Home Route
Route::get('/', [Home::class, 'index']);

// Courses Route
Route::get('/courses', [Courses::class, 'index']);
