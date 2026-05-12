@extends('layouts.app')

@php
    $fullName = trim(collect([
        $user['first_name'] ?? null,
        $user['middle_name'] ?? null,
        $user['last_name'] ?? null,
    ])->filter()->implode(' '));
    $displayName = $fullName !== '' ? $fullName : ($user['user_name'] ?? 'Guest User');
    $roles = collect(session('auth.roles', []))->filter()->values();
    $memberSince = ! empty($user['created_at']) ? \Illuminate\Support\Carbon::parse($user['created_at'])->format('d M Y') : 'Recently joined';
    $profileItems = [
        'Email Address' => $user['email'] ?? 'Not provided',
        'Mobile Number' => $user['mobile_no'] ?? 'Not provided',
        'Username' => $user['user_name'] ?? 'Not provided',
        'Member Since' => $memberSince,
    ];
    $enrolledCourses = collect($enrollments ?? []);
    $recentEnrollments = $enrolledCourses->take(4);
    $stats = $stats ?? ['total' => 0, 'active' => 0, 'completed' => 0];
@endphp

@section('title', 'My Account Dashboard')

@push('styles')
<style>
    .account-dashboard {
        background:
            radial-gradient(circle at top right, rgba(255, 152, 0, 0.14), transparent 26%),
            linear-gradient(180deg, #fffaf3 0%, #ffffff 42%, #fff6ea 100%);
        padding: 56px 0 72px;
    }

    .account-shell {
        display: grid;
        grid-template-columns: 320px minmax(0, 1fr);
        gap: 28px;
        align-items: start;
    }

    .account-sidebar,
    .account-panel,
    .account-stat-card {
        background: rgba(255, 255, 255, 0.94);
        border: 1px solid rgba(245, 124, 0, 0.12);
        border-radius: 24px;
        box-shadow: 0 20px 40px rgba(104, 57, 5, 0.08);
    }

    .account-sidebar {
        overflow: hidden;
        position: sticky;
        top: 24px;
    }

    .account-cover {
        padding: 28px 26px 22px;
        background: linear-gradient(135deg, #ff9800, #f57c00);
        color: #fff;
        position: relative;
        overflow: hidden;
    }

    .account-cover::after {
        content: '';
        position: absolute;
        right: -34px;
        top: -34px;
        width: 132px;
        height: 132px;
        border-radius: 50%;
        background: rgba(255, 255, 255, 0.12);
    }

    .account-avatar {
        width: 74px;
        height: 74px;
        border-radius: 22px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        background: rgba(255, 255, 255, 0.18);
        border: 1px solid rgba(255, 255, 255, 0.28);
        font-size: 1.7rem;
        font-weight: 700;
        margin-bottom: 18px;
        backdrop-filter: blur(4px);
    }

    .account-cover h1 {
        font-size: 1.55rem;
        font-weight: 700;
        margin-bottom: 0.35rem;
        color: #fff;
    }

    .account-cover p {
        margin-bottom: 0;
        color: rgba(255, 255, 255, 0.86);
    }

    .account-meta {
        padding: 22px 24px 24px;
    }

    .account-meta-item + .account-meta-item {
        margin-top: 16px;
        padding-top: 16px;
        border-top: 1px solid rgba(15, 23, 42, 0.07);
    }

    .account-meta-label {
        font-size: 0.76rem;
        font-weight: 700;
        letter-spacing: 0.08em;
        text-transform: uppercase;
        color: #b36b16;
        margin-bottom: 0.35rem;
    }

    .account-meta-value {
        color: #2d210f;
        font-weight: 600;
        word-break: break-word;
    }

    .account-role-list {
        display: flex;
        flex-wrap: wrap;
        gap: 8px;
        margin-top: 14px;
    }

    .account-role-badge {
        display: inline-flex;
        align-items: center;
        padding: 8px 12px;
        border-radius: 999px;
        background: rgba(255, 152, 0, 0.12);
        color: #b35f00;
        font-size: 0.86rem;
        font-weight: 700;
    }

    .account-panel {
        padding: 28px;
    }

    .account-hero {
        padding: 32px;
        border-radius: 26px;
        background: linear-gradient(135deg, rgba(255, 152, 0, 0.12), rgba(245, 124, 0, 0.22));
        border: 1px solid rgba(245, 124, 0, 0.15);
        margin-bottom: 24px;
        position: relative;
        overflow: hidden;
    }

    .account-hero::before,
    .account-hero::after {
        content: '';
        position: absolute;
        border-radius: 50%;
        background: rgba(255, 255, 255, 0.45);
    }

    .account-hero::before {
        width: 180px;
        height: 180px;
        right: -70px;
        top: -90px;
    }

    .account-hero::after {
        width: 100px;
        height: 100px;
        right: 70px;
        bottom: -55px;
    }

    .account-kicker {
        display: inline-flex;
        align-items: center;
        gap: 10px;
        padding: 7px 14px;
        margin-bottom: 14px;
        border-radius: 999px;
        background: rgba(255, 255, 255, 0.8);
        color: #bb6400;
        font-size: 0.83rem;
        font-weight: 700;
        letter-spacing: 0.05em;
        text-transform: uppercase;
    }

    .account-hero h2 {
        position: relative;
        z-index: 1;
        font-size: 2rem;
        font-weight: 700;
        color: #24180b;
        margin-bottom: 0.7rem;
    }

    .account-hero p {
        position: relative;
        z-index: 1;
        max-width: 680px;
        margin-bottom: 1.2rem;
        color: #5a4834;
        font-size: 1rem;
    }

    .account-action-row {
        position: relative;
        z-index: 1;
        display: flex;
        flex-wrap: wrap;
        gap: 12px;
    }

    .account-btn-primary,
    .account-btn-secondary {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        padding: 12px 18px;
        border-radius: 12px;
        text-decoration: none;
        font-weight: 700;
        transition: transform 0.16s ease, box-shadow 0.16s ease;
    }

    .account-btn-primary {
        color: #fff;
        background: linear-gradient(135deg, #ff9800, #f57c00);
        box-shadow: 0 16px 24px rgba(245, 124, 0, 0.2);
    }

    .account-btn-secondary {
        color: #a45b00;
        background: rgba(255, 255, 255, 0.74);
        border: 1px solid rgba(255, 152, 0, 0.25);
    }

    .account-btn-primary:hover,
    .account-btn-secondary:hover {
        transform: translateY(-1px);
    }

    .account-grid {
        display: grid;
        grid-template-columns: repeat(3, minmax(0, 1fr));
        gap: 18px;
        margin-bottom: 22px;
    }

    .account-stat-card {
        padding: 24px 22px;
        background: linear-gradient(180deg, #ffffff 0%, #fff7ee 100%);
    }

    .account-stat-label {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        margin-bottom: 12px;
        color: #a86817;
        font-size: 0.82rem;
        font-weight: 700;
        letter-spacing: 0.06em;
        text-transform: uppercase;
    }

    .account-stat-value {
        font-size: 2.2rem;
        line-height: 1;
        font-weight: 800;
        color: #26170a;
        margin-bottom: 10px;
    }

    .account-stat-copy {
        color: #675645;
        margin-bottom: 0;
    }

    .account-section {
        margin-top: 24px;
    }

    .account-section-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 16px;
        margin-bottom: 18px;
    }

    .account-section-header h3 {
        font-size: 1.3rem;
        font-weight: 700;
        margin-bottom: 0;
        color: #24180b;
    }

    .account-section-header p {
        margin-bottom: 0;
        color: #8a725d;
    }

    .account-info-grid {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 16px;
    }

    .account-info-tile {
        padding: 18px;
        border-radius: 18px;
        background: linear-gradient(180deg, #fffaf5 0%, #ffffff 100%);
        border: 1px solid rgba(245, 124, 0, 0.12);
    }

    .account-info-tile span {
        display: block;
        margin-bottom: 6px;
        font-size: 0.78rem;
        font-weight: 700;
        letter-spacing: 0.08em;
        text-transform: uppercase;
        color: #be761a;
    }

    .account-info-tile strong {
        display: block;
        color: #24180b;
        font-size: 1rem;
        line-height: 1.5;
        word-break: break-word;
    }

    .account-enrollment-list {
        display: grid;
        gap: 14px;
    }

    .account-enrollment-item {
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 16px;
        padding: 18px;
        border-radius: 18px;
        background: linear-gradient(180deg, #fffaf5 0%, #ffffff 100%);
        border: 1px solid rgba(245, 124, 0, 0.12);
    }

    .account-enrollment-main {
        display: flex;
        align-items: center;
        gap: 16px;
        min-width: 0;
        flex: 1 1 auto;
    }

    .account-enrollment-thumb {
        width: 88px;
        height: 88px;
        border-radius: 18px;
        object-fit: cover;
        border: 1px solid rgba(245, 124, 0, 0.16);
        box-shadow: 0 10px 24px rgba(104, 57, 5, 0.08);
        background: #fff4e5;
        flex-shrink: 0;
    }

    .account-enrollment-copy {
        min-width: 0;
    }

    .account-enrollment-meta {
        display: flex;
        flex-wrap: wrap;
        gap: 10px;
        margin-bottom: 8px;
    }

    .account-enrollment-tag {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 6px 10px;
        border-radius: 999px;
        background: rgba(255, 152, 0, 0.12);
        color: #a85f0a;
        font-size: 0.78rem;
        font-weight: 700;
    }

    .account-enrollment-actions {
        display: flex;
        flex-direction: column;
        align-items: flex-end;
        gap: 10px;
        flex-shrink: 0;
    }

    .account-resume-link {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 10px 14px;
        border-radius: 12px;
        background: linear-gradient(135deg, #ff9800, #f57c00);
        color: #fff;
        font-weight: 700;
        text-decoration: none;
        box-shadow: 0 12px 24px rgba(245, 124, 0, 0.18);
    }

    .account-resume-link:hover {
        color: #fff;
        transform: translateY(-1px);
    }

    .account-enrollment-item h4 {
        font-size: 1rem;
        font-weight: 700;
        color: #24180b;
        margin-bottom: 4px;
    }

    .account-enrollment-item p {
        margin-bottom: 0;
        color: #77624d;
    }

    .account-status-pill {
        display: inline-flex;
        align-items: center;
        padding: 7px 12px;
        border-radius: 999px;
        background: rgba(255, 152, 0, 0.12);
        color: #b46808;
        font-size: 0.82rem;
        font-weight: 700;
        text-transform: capitalize;
        white-space: nowrap;
    }

    .account-empty-state {
        padding: 24px;
        border-radius: 20px;
        border: 1px dashed rgba(245, 124, 0, 0.28);
        background: rgba(255, 248, 240, 0.8);
        color: #765f49;
    }

    .account-empty-state h4 {
        color: #27170b;
        margin-bottom: 8px;
        font-weight: 700;
    }

    .account-highlight {
        margin-top: 20px;
        padding: 24px;
        border-radius: 22px;
        background: #2c1b0c;
        color: #fff;
        display: flex;
        flex-wrap: wrap;
        justify-content: space-between;
        gap: 18px;
        align-items: center;
    }

    .account-highlight p {
        margin-bottom: 0;
        color: rgba(255, 255, 255, 0.8);
        max-width: 560px;
    }

    .account-highlight-actions {
        display: flex;
        flex-wrap: wrap;
        gap: 12px;
    }

    .account-highlight .account-btn-secondary {
        background: rgba(255, 255, 255, 0.12);
        color: #fff;
        border-color: rgba(255, 255, 255, 0.16);
    }

    .account-logout-form {
        margin: 0;
    }

    .account-logout-btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        padding: 12px 18px;
        border-radius: 12px;
        border: 1px solid rgba(255, 255, 255, 0.16);
        background: rgba(255, 255, 255, 0.12);
        color: #fff;
        font-weight: 700;
    }

    .account-logout-btn:hover {
        background: rgba(255, 255, 255, 0.18);
    }

    @media (max-width: 991px) {
        .account-shell {
            grid-template-columns: 1fr;
        }

        .account-sidebar {
            position: static;
        }

        .account-grid,
        .account-info-grid {
            grid-template-columns: 1fr;
        }

        .account-enrollment-item {
            flex-direction: column;
            align-items: flex-start;
        }

        .account-enrollment-main,
        .account-enrollment-actions {
            width: 100%;
        }

        .account-enrollment-actions {
            align-items: stretch;
        }

        .account-resume-link {
            justify-content: center;
        }
    }

    @media (max-width: 575px) {
        .account-dashboard {
            padding: 32px 0 48px;
        }

        .account-panel,
        .account-cover,
        .account-meta {
            padding-left: 18px;
            padding-right: 18px;
        }

        .account-hero {
            padding: 22px 18px;
        }

        .account-hero h2 {
            font-size: 1.55rem;
        }
    }
</style>
@endpush

@section('content')
<section class="account-dashboard">
    <div class="container">
        <div class="account-shell">
            <aside class="account-sidebar">
                <div class="account-cover">
                    <div class="account-avatar">
                        {{ strtoupper(substr($displayName, 0, 1)) }}
                    </div>
                    <h1>{{ $displayName }}</h1>
                    <p>{{ $user['email'] ?? 'Personal account overview' }}</p>
                </div>

                <div class="account-meta">
                    <div class="account-meta-item">
                        <div class="account-meta-label">Account Status</div>
                        <div class="account-meta-value">
                            {{ ($user['status'] ?? 'active') === 'Y' ? 'Active Member' : ucfirst((string) ($user['status'] ?? 'Member')) }}
                        </div>
                    </div>

                    <div class="account-meta-item">
                        <div class="account-meta-label">User Code</div>
                        <div class="account-meta-value">{{ $user['user_code'] ?? 'Awaiting assignment' }}</div>
                    </div>

                    <div class="account-meta-item">
                        <div class="account-meta-label">Roles</div>
                        <div class="account-role-list">
                            @forelse ($roles as $role)
                                <span class="account-role-badge">{{ $role }}</span>
                            @empty
                                <span class="account-role-badge">Member</span>
                            @endforelse
                        </div>
                    </div>
                </div>
            </aside>

            <div class="account-panel">
                <div class="account-hero">
                    <div class="account-kicker">
                        <i class="bi bi-stars"></i>
                        Personal Dashboard
                    </div>
                    <h2>Welcome back, {{ $user['first_name'] ?? $displayName }}.</h2>
                    <p>
                        Keep your astrology learning journey and account details in one place. This dashboard stays aligned with the site theme while surfacing real account and enrollment activity.
                    </p>
                    <div class="account-action-row">
                        <a href="{{ route('courses.index') }}" class="account-btn-primary">
                            <i class="bi bi-journal-richtext"></i>
                            Explore Courses
                        </a>
                        <a href="{{ route('live-classes.index') }}" class="account-btn-secondary">
                            <i class="bi bi-broadcast-pin"></i>
                            Live Classes
                        </a>
                        <a href="{{ route('student.dashboard') }}" class="account-btn-secondary">
                            <i class="bi bi-mortarboard"></i>
                            Student Overview
                        </a>
                    </div>
                </div>

                <div class="account-grid">
                    <div class="account-stat-card">
                        <div class="account-stat-label"><i class="bi bi-journal-check"></i> Total Enrollments</div>
                        <div class="account-stat-value">{{ $stats['total'] }}</div>
                        <p class="account-stat-copy">Courses connected to your account right now.</p>
                    </div>
                    <div class="account-stat-card">
                        <div class="account-stat-label"><i class="bi bi-lightning-charge"></i> Active Courses</div>
                        <div class="account-stat-value">{{ $stats['active'] }}</div>
                        <p class="account-stat-copy">Enrollments that are still in progress or ready to continue.</p>
                    </div>
                    <div class="account-stat-card">
                        <div class="account-stat-label"><i class="bi bi-award"></i> Completed</div>
                        <div class="account-stat-value">{{ $stats['completed'] }}</div>
                        <p class="account-stat-copy">Courses already marked as completed in the LMS response.</p>
                    </div>
                </div>

                <div class="account-section">
                    <div class="account-section-header">
                        <div>
                            <h3>Account Details</h3>
                            <p>Primary information available from your authenticated profile.</p>
                        </div>
                    </div>

                    <div class="account-info-grid">
                        @foreach ($profileItems as $label => $value)
                            <div class="account-info-tile">
                                <span>{{ $label }}</span>
                                <strong>{{ $value }}</strong>
                            </div>
                        @endforeach
                    </div>
                </div>

                <div class="account-section">
                    <div class="account-section-header">
                        <div>
                            <h3>Recent Course Activity</h3>
                            <p>Live enrollment data pulled from the current LMS API response.</p>
                        </div>
                    </div>

                    @if ($recentEnrollments->isNotEmpty())
                        <div class="account-enrollment-list">
                            @foreach ($recentEnrollments as $enrollment)
                                <div class="account-enrollment-item">
                                    <div class="account-enrollment-main">
                                        <img src="{{ $enrollment['image'] }}" alt="{{ $enrollment['title'] }}" class="account-enrollment-thumb">
                                        <div class="account-enrollment-copy">
                                            <div class="account-enrollment-meta">
                                                <span class="account-enrollment-tag">
                                                    <i class="bi bi-bookmark-star-fill"></i>
                                                    {{ $enrollment['category'] ?? 'General' }}
                                                </span>
                                                <span class="account-enrollment-tag">
                                                    <i class="bi bi-calendar-event"></i>
                                                    {{ $enrollment['enrolled_at_label'] ?? 'Recently enrolled' }}
                                                </span>
                                            </div>
                                            <h4>{{ $enrollment['title'] }}</h4>
                                            <p>
                                                Progress: {{ max(0, min(100, (int) ($enrollment['progress'] ?? 0))) }}%
                                            </p>
                                        </div>
                                    </div>
                                    <div class="account-enrollment-actions">
                                        <span class="account-status-pill">{{ $enrollment['status'] ?: 'active' }}</span>
                                        <a href="{{ $enrollment['resume_url'] ?? $enrollment['course_url'] }}" class="account-resume-link">
                                            <i class="bi bi-play-circle-fill"></i>
                                            {{ $enrollment['resume_label'] ?? 'Resume Course' }}
                                        </a>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="account-empty-state">
                            <h4>No enrollments yet</h4>
                            <p class="mb-0">Your account is active, but there are no courses attached yet. Start by browsing the course catalog and enrolling in your first astrology program.</p>
                        </div>
                    @endif
                </div>

                <div class="account-section">
                    <div class="account-highlight">
                        <div>
                            <h3 class="mb-2">Continue your journey</h3>
                            <p>Browse available astrology courses, review your latest learning activity, or sign out securely when you are done.</p>
                        </div>
                        <div class="account-highlight-actions">
                            <a href="{{ route('courses.index') }}" class="account-btn-secondary">
                                <i class="bi bi-arrow-right-circle"></i>
                                Go to Courses
                            </a>
                            <a href="{{ route('live-classes.index') }}" class="account-btn-secondary">
                                <i class="bi bi-camera-video"></i>
                                Open Live Classes
                            </a>
                            <form method="POST" action="{{ route('logout') }}" class="account-logout-form">
                                @csrf
                                <button type="submit" class="account-logout-btn">
                                    <i class="bi bi-box-arrow-right"></i>
                                    Logout
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
