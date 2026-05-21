@extends('layouts.dashboard')

@section('title', 'Student Dashboard')
@section('page_title', 'Student Dashboard')
@section('page_subtitle', 'Track your learning progress and enrollments')

@section('page_actions')
    <a href="{{ route('courses.index') }}" class="db-btn db-btn-primary">
        <i class="bi bi-search"></i> Browse Courses
    </a>
@endsection

@section('dashboard_content')
    @if(!empty($apiError))
        <div class="alert alert-warning border-0 shadow-sm d-flex align-items-center gap-3 mb-4" style="background-color: #fffbeb; color: #b45309; border-radius: var(--db-radius); padding: 16px 20px;">
            <i class="bi bi-exclamation-triangle-fill fs-4 text-warning"></i>
            <div>
                <strong style="display:block; margin-bottom: 2px;">LMS Service Alert</strong>
                <span style="font-size: 13.5px; opacity: 0.9;">The learning management system is currently experiencing issues: {{ $apiError }}. Some enrollments or progress details might be temporarily unavailable.</span>
            </div>
        </div>
    @endif

    @php
        $enrollments = is_array($enrollments ?? null) ? $enrollments : [];
        $stats = is_array($stats ?? null) ? $stats : ['total' => 0, 'active' => 0, 'completed' => 0];
    @endphp

    <div class="db-stats-grid">
        <div class="db-stat-card">
            <div class="db-stat-icon"><i class="bi bi-journal-check"></i></div>
            <div class="db-stat-info">
                <div class="db-stat-num">{{ $stats['total'] }}</div>
                <div class="db-stat-title">Enrolled Courses</div>
            </div>
        </div>
        <div class="db-stat-card">
            <div class="db-stat-icon"><i class="bi bi-play-circle"></i></div>
            <div class="db-stat-info">
                <div class="db-stat-num">{{ $stats['active'] }}</div>
                <div class="db-stat-title">In Progress</div>
            </div>
        </div>
        <div class="db-stat-card">
            <div class="db-stat-icon"><i class="bi bi-check-circle"></i></div>
            <div class="db-stat-info">
                <div class="db-stat-num">{{ $stats['completed'] }}</div>
                <div class="db-stat-title">Completed</div>
            </div>
        </div>
        <div class="db-stat-card">
            <div class="db-stat-icon"><i class="bi bi-collection-play"></i></div>
            <div class="db-stat-info">
                <div class="db-stat-num">—</div>
                <div class="db-stat-title">Available Courses</div>
            </div>
        </div>
    </div>

    <section class="db-card">
        <div class="db-section-head">
            <h2>My Enrollments</h2>
            <a href="{{ route('enrollments.index') }}" class="db-btn">View All</a>
        </div>

        @if(count($enrollments) > 0)
            <div style="overflow-x:auto;">
                <table class="db-table">
                    <thead>
                        <tr>
                            <th>Course</th>
                            <th>Progress</th>
                            <th>Status</th>
                            <th>Enrolled</th>
                            <th style="text-align:right;">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($enrollments as $enrollment)
                            @if(is_array($enrollment))
                                @php
                                    $progress = $enrollment['progress'] ?? 0;
                                    $status = $enrollment['status'] ?? 'active';
                                    $pillClass = $status === 'completed' ? 'db-pill db-pill--success' : 'db-pill';
                                @endphp
                                <tr>
                                    <td>
                                        <div style="font-weight:600;">{{ $enrollment['title'] ?? 'Untitled' }}</div>
                                        <div style="font-size:12px;color:var(--db-muted);">{{ $enrollment['category'] ?? 'General' }}</div>
                                    </td>
                                    <td>
                                        <div style="display:flex;align-items:center;gap:8px;">
                                            <div style="flex:1;height:6px;background:#e5e7eb;border-radius:3px;overflow:hidden;">
                                                <div style="width:{{ $progress }}%;height:100%;background:var(--db-primary);"></div>
                                            </div>
                                            <span style="font-size:12px;font-weight:600;">{{ $progress }}%</span>
                                        </div>
                                    </td>
                                    <td><span class="{{ $pillClass }}">{{ ucfirst($status) }}</span></td>
                                    <td>{{ $enrollment['enrolled_at_label'] ?? 'Recently' }}</td>
                                    <td style="text-align:right;">
                                        <a href="{{ $enrollment['resume_url'] ?? route('courses.index') }}" class="db-btn">
                                            {{ $enrollment['resume_label'] ?? 'Resume' }}
                                        </a>
                                    </td>
                                </tr>
                            @endif
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div class="db-empty">
                <i class="bi bi-book"></i>
                <div>You haven’t enrolled in any courses yet.</div>
                <a href="{{ route('courses.index') }}" class="db-btn db-btn-primary mt-3">
                    <i class="bi bi-search"></i> Browse Courses
                </a>
            </div>
        @endif
    </section>

    @if(isset($user) && is_array($user))
        <section class="db-card" style="margin-top: 22px;">
            <div class="db-section-head">
                <h2>Profile Quick View</h2>
            </div>
            <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(200px,1fr));gap:16px;">
                <div>
                    <div style="font-size:11.5px;color:var(--db-muted);text-transform:uppercase;letter-spacing:.7px;margin-bottom:4px;">Name</div>
                    <div style="font-weight:600;">{{ $user['first_name'] ?? '' }} {{ $user['last_name'] ?? '' }}</div>
                </div>
                <div>
                    <div style="font-size:11.5px;color:var(--db-muted);text-transform:uppercase;letter-spacing:.7px;margin-bottom:4px;">Email</div>
                    <div style="font-weight:600;">{{ $user['email'] ?? '—' }}</div>
                </div>
                <div>
                    <div style="font-size:11.5px;color:var(--db-muted);text-transform:uppercase;letter-spacing:.7px;margin-bottom:4px;">Phone</div>
                    <div style="font-weight:600;">{{ $user['mobile_no'] ?? '—' }}</div>
                </div>
            </div>
        </section>
    @endif
@endsection
