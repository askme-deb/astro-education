<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class Courses extends Controller
{
    function index() {
        return view('courses');
    }
}
