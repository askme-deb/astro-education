@extends('layouts.dashboard')

@section('title', 'My Enrollments')
@section('page_title', 'My Enrollments')
@section('page_subtitle', 'Browse and manage all your active course enrollments')

@section('page_actions')
    <a href="{{ route('courses.index') }}" class="db-btn db-btn-primary">
        <i class="bi bi-search"></i> Browse More Courses
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

    <section class="db-card">
        <div class="db-section-head">
            <h2>Course Enrollments</h2>
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
                                    $pillClass = $status === 'completed' ? 'db-pill db-pill-success' : 'db-pill';
                                @endphp
                                <tr>
                                    <td>
                                        <div style="font-weight:600; color: #0f172a;">{{ $enrollment['title'] ?? 'Untitled' }}</div>
                                        <div style="font-size:12px; color:var(--db-muted);">{{ $enrollment['category'] ?? 'General' }}</div>
                                    </td>
                                    <td>
                                        <div style="display:flex; align-items:center; gap:10px;">
                                            <div style="flex:1; height:6px; background:#e2e8f0; border-radius:3px; overflow:hidden;">
                                                <div style="width:{{ $progress }}%; height:100%; background:var(--db-primary);"></div>
                                            </div>
                                            <span style="font-size:12.5px; font-weight:600; color: #0f172a;">{{ $progress }}%</span>
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
                <i class="bi bi-mortarboard-fill"></i>
                <div>You haven’t enrolled in any courses yet.</div>
                <a href="{{ route('courses.index') }}" class="db-btn db-btn-primary mt-3">
                    <i class="bi bi-search"></i> Browse Courses
                </a>
            </div>
        @endif
    </section>
@endsection
