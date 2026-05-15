<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class LiveClassController extends Controller
{
    /**
     * Display my live classes
     */
    public function myClasses()
    {
        return view('student.my-live-classes');
    }
}
