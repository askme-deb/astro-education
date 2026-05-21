<?php

namespace App\Http\Controllers;

class DashboardController extends Controller
{
    public function index()
    {
        if (! session()->has('auth.api_token') || ! session()->has('auth.user')) {
            return redirect()->route('home');
        }

        $roles = array_map(fn ($role) => strtolower((string) $role), session('auth.roles', []));

        if (in_array('instructor', $roles, true) || in_array('teacher', $roles, true) || in_array('admin', $roles, true)) {
            return redirect()->route('instructor.dashboard');
        }

        return redirect()->route('student.dashboard');
    }
}
