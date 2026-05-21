@extends('layouts.app')

@push('styles')
    <style>
        :root {
            --db-bg: #f8fafc;
            --db-surface: #ffffff;
            --db-border: #e2e8f0;
            --db-text: #334155;
            --db-muted: #64748b;
            --db-primary: #ff9800; /* Matching existing orange theme */
            --db-primary-soft: rgba(255, 152, 0, 0.08);
            --db-success: #10b981;
            --db-danger: #ef4444;
            --db-radius: 12px;
            --db-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05), 0 2px 4px -1px rgba(0, 0, 0, 0.03);
        }

        body {
            background-color: var(--db-bg);
            color: var(--db-text);
        }

        .db-wrapper {
            padding-top: 40px;
            padding-bottom: 60px;
        }

        /* Dashboard Sidebar Card */
        .db-sidebar-card {
            background: var(--db-surface);
            border: 1px solid var(--db-border);
            border-radius: var(--db-radius);
            box-shadow: var(--db-shadow);
            padding: 24px 16px;
            position: sticky;
            top: 24px;
        }

        .db-user-profile {
            text-align: center;
            padding-bottom: 20px;
            border-bottom: 1px solid var(--db-border);
            margin-bottom: 20px;
        }

        .db-user-avatar {
            width: 64px;
            height: 64px;
            border-radius: 50%;
            background: var(--db-primary-soft);
            color: var(--db-primary);
            font-size: 24px;
            font-weight: 700;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 12px;
            border: 2px solid #fff;
            box-shadow: 0 0 0 2px var(--db-primary);
        }

        .db-user-name {
            font-size: 16px;
            font-weight: 700;
            color: #0f172a;
            margin-bottom: 4px;
        }

        .db-user-role {
            font-size: 12px;
            font-weight: 600;
            color: var(--db-muted);
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        /* Dashboard Navigation Links */
        .db-sidebar-nav {
            display: flex;
            flex-direction: column;
            gap: 4px;
        }

        .db-nav-item {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 10px 16px;
            border-radius: 8px;
            color: var(--db-text);
            text-decoration: none;
            font-size: 14px;
            font-weight: 500;
            transition: all 0.2s ease;
        }

        .db-nav-item i {
            font-size: 16px;
            width: 20px;
            text-align: center;
            color: var(--db-muted);
            transition: color 0.2s ease;
        }

        .db-nav-item:hover {
            background-color: var(--db-primary-soft);
            color: var(--db-primary);
        }

        .db-nav-item:hover i {
            color: var(--db-primary);
        }

        .db-nav-item.active {
            background-color: var(--db-primary);
            color: #fff;
        }

        .db-nav-item.active i {
            color: #fff;
        }

        /* Content Header */
        .db-header-panel {
            background: var(--db-surface);
            border: 1px solid var(--db-border);
            border-radius: var(--db-radius);
            box-shadow: var(--db-shadow);
            padding: 24px 28px;
            margin-bottom: 24px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 20px;
        }

        .db-header-title h1 {
            font-size: 22px;
            font-weight: 700;
            color: #0f172a;
            margin: 0 0 4px;
        }

        .db-header-title p {
            font-size: 14px;
            color: var(--db-muted);
            margin: 0;
        }

        /* Standard Cards */
        .db-card {
            background: var(--db-surface);
            border: 1px solid var(--db-border);
            border-radius: var(--db-radius);
            box-shadow: var(--db-shadow);
            padding: 24px;
            margin-bottom: 24px;
        }

        /* Dashboard Stats Grid */
        .db-stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 16px;
            margin-bottom: 24px;
        }

        .db-stat-card {
            background: var(--db-surface);
            border: 1px solid var(--db-border);
            border-radius: var(--db-radius);
            box-shadow: var(--db-shadow);
            padding: 20px;
            display: flex;
            align-items: center;
            gap: 16px;
        }

        .db-stat-icon {
            width: 48px;
            height: 48px;
            border-radius: 10px;
            background: var(--db-primary-soft);
            color: var(--db-primary);
            font-size: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .db-stat-info {
            flex: 1;
        }

        .db-stat-num {
            font-size: 24px;
            font-weight: 700;
            color: #0f172a;
            line-height: 1.1;
            margin-bottom: 2px;
        }

        .db-stat-title {
            font-size: 12px;
            font-weight: 600;
            color: var(--db-muted);
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        /* Buttons Styling */
        .db-btn {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 9px 18px;
            border-radius: 8px;
            font-size: 13.5px;
            font-weight: 600;
            border: 1px solid var(--db-border);
            background: #fff;
            color: var(--db-text);
            cursor: pointer;
            text-decoration: none;
            transition: all 0.15s ease;
        }

        .db-btn:hover {
            border-color: #cbd5e1;
            background-color: #f8fafc;
            color: #0f172a;
        }

        .db-btn-primary {
            background-color: var(--db-primary);
            border-color: var(--db-primary);
            color: #fff;
        }

        .db-btn-primary:hover {
            background-color: #e65100;
            border-color: #e65100;
            color: #fff;
        }

        .db-btn-danger {
            color: var(--db-danger);
            border-color: rgba(239, 68, 68, 0.2);
        }

        .db-btn-danger:hover {
            background-color: var(--db-danger);
            border-color: var(--db-danger);
            color: #fff;
        }

        /* Tables and elements */
        .db-section-head {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 20px;
        }

        .db-section-head h2 {
            font-size: 18px;
            font-weight: 700;
            color: #0f172a;
            margin: 0;
        }

        .db-table {
            width: 100%;
            margin-bottom: 0;
        }

        .db-table th {
            background: #f8fafc;
            font-size: 11px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            color: var(--db-muted);
            padding: 12px 16px;
            border-bottom: 1px solid var(--db-border);
        }

        .db-table td {
            padding: 16px;
            font-size: 14px;
            color: var(--db-text);
            border-bottom: 1px solid var(--db-border);
            vertical-align: middle;
        }

        .db-table tr:last-child td {
            border-bottom: none;
        }

        .db-pill {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 4px 10px;
            border-radius: 999px;
            font-size: 11.5px;
            font-weight: 600;
            background: #e0f2fe;
            color: #0369a1;
        }

        .db-pill-success { background-color: #d1fae5; color: #065f46; }
        .db-pill-warning { background-color: #fef3c7; color: #92400e; }
        .db-pill-danger { background-color: #fee2e2; color: #991b1b; }

        .db-empty {
            text-align: center;
            padding: 40px 20px;
            color: var(--db-muted);
        }

        .db-empty i {
            font-size: 36px;
            color: var(--db-primary);
            display: block;
            margin-bottom: 12px;
        }

        @media (max-width: 991px) {
            .db-sidebar-card {
                position: relative;
                top: 0;
                margin-bottom: 24px;
            }
            .db-header-panel {
                flex-direction: column;
                align-items: flex-start;
                padding: 20px;
            }
            .db-header-actions {
                width: 100%;
                display: flex;
                justify-content: flex-start;
            }
        }
    </style>
@endpush

@section('content')
@php
    $sessionUser = session('auth.user', []);
    $sessionRoles = array_map(fn ($role) => strtolower((string) $role), session('auth.roles', []));
    $isInstructor = in_array('instructor', $sessionRoles, true) || in_array('teacher', $sessionRoles, true) || in_array('admin', $sessionRoles, true);
    $displayName = trim(($sessionUser['first_name'] ?? '') . ' ' . ($sessionUser['last_name'] ?? '')) ?: ($sessionUser['name'] ?? $sessionUser['email'] ?? 'User');
    $initials = strtoupper(substr($displayName, 0, 1) ?: 'U');
    $roleLabel = $isInstructor ? 'Instructor' : 'Student';
    $currentRoute = request()->route() ? request()->route()->getName() : '';
@endphp

<div class="container db-wrapper">
    <div class="row">
        <!-- Sidebar Navigation (3 columns) -->
        <div class="col-lg-3">
            <div class="db-sidebar-card">
                <div class="db-user-profile">
                    <div class="db-user-avatar">{{ $initials }}</div>
                    <div class="db-user-name">{{ $displayName }}</div>
                    <div class="db-user-role">{{ $roleLabel }}</div>
                </div>

                <nav class="db-sidebar-nav">
                    @if($isInstructor)
                        <a href="{{ route('instructor.dashboard') }}" class="db-nav-item {{ $currentRoute === 'instructor.dashboard' ? 'active' : '' }}">
                            <i class="bi bi-grid-1x2-fill"></i> Dashboard
                        </a>
                        <a href="{{ route('instructor.dashboard') }}#live-classes" class="db-nav-item">
                            <i class="bi bi-camera-video-fill"></i> Live Classes
                        </a>
                        <a href="{{ route('courses.index') }}" class="db-nav-item">
                            <i class="bi bi-journal-album"></i> Browse Courses
                        </a>
                    @else
                        <a href="{{ route('student.dashboard') }}" class="db-nav-item {{ $currentRoute === 'student.dashboard' ? 'active' : '' }}">
                            <i class="bi bi-grid-1x2-fill"></i> Dashboard
                        </a>
                        <a href="{{ route('courses.index') }}" class="db-nav-item {{ str_starts_with($currentRoute, 'courses.') ? 'active' : '' }}">
                            <i class="bi bi-search"></i> Browse Courses
                        </a>
                        <a href="{{ route('student.my-live-classes') }}" class="db-nav-item {{ $currentRoute === 'student.my-live-classes' ? 'active' : '' }}">
                            <i class="bi bi-camera-video-fill"></i> My Live Classes
                        </a>
                        <a href="{{ route('enrollments.index') }}" class="db-nav-item {{ str_starts_with($currentRoute, 'enrollments.') ? 'active' : '' }}">
                            <i class="bi bi-mortarboard-fill"></i> My Enrollments
                        </a>
                    @endif

                    <hr style="margin: 12px 0; border-color: var(--db-border);">

                    <a href="{{ url('/') }}" class="db-nav-item">
                        <i class="bi bi-house-door-fill"></i> Home Website
                    </a>
                    <a href="#" onclick="document.getElementById('db-logout-form').submit(); return false;" class="db-nav-item text-danger">
                        <i class="bi bi-box-arrow-right text-danger"></i> Logout
                    </a>
                </nav>

                <form id="db-logout-form" action="{{ route('logout') }}" method="POST" style="display:none;">@csrf</form>
            </div>
        </div>

        <!-- Content Area (9 columns) -->
        <div class="col-lg-9">
            <!-- Header Panel -->
            <div class="db-header-panel">
                <div class="db-header-title">
                    <h1>@yield('page_title', 'Dashboard')</h1>
                    <p>@yield('page_subtitle', 'Overview of your learning progress')</p>
                </div>
                <div class="db-header-actions">
                    @yield('page_actions')
                </div>
            </div>

            <!-- Dashboard Content -->
            @yield('dashboard_content')
        </div>
    </div>
</div>
@endsection
