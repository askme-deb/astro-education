@extends('layouts.app')

@php
    $status = ucfirst((string) ($enrollment['status'] ?? 'active'));
    $progress = max(0, min(100, (int) ($enrollment['progress'] ?? 0)));
@endphp

@section('title', $enrollment['title'] . ' | My Learning')

@push('styles')
<style>
    .enrollment-player-page {
        background:
            radial-gradient(circle at top right, rgba(255, 152, 0, 0.12), transparent 26%),
            linear-gradient(180deg, #fffaf3 0%, #ffffff 42%, #fff6ea 100%);
        padding: 56px 0 72px;
    }

    .enrollment-player-shell {
        display: grid;
        grid-template-columns: minmax(0, 1.3fr) minmax(320px, 0.7fr);
        gap: 26px;
        align-items: start;
    }

    .enrollment-player-card,
    .enrollment-player-sidebar {
        background: rgba(255, 255, 255, 0.96);
        border: 1px solid rgba(245, 124, 0, 0.12);
        border-radius: 26px;
        box-shadow: 0 20px 40px rgba(104, 57, 5, 0.08);
    }

    .enrollment-player-hero {
        position: relative;
        overflow: hidden;
        border-radius: 26px 26px 0 0;
        min-height: 320px;
        background: linear-gradient(135deg, rgba(44, 27, 12, 0.8), rgba(245, 124, 0, 0.35));
    }

    .enrollment-player-hero img {
        width: 100%;
        height: 320px;
        object-fit: cover;
        display: block;
        opacity: 0.42;
    }

    .enrollment-player-overlay {
        position: absolute;
        inset: 0;
        display: flex;
        flex-direction: column;
        justify-content: flex-end;
        gap: 14px;
        padding: 28px;
        color: #fff;
        background: linear-gradient(180deg, rgba(36, 24, 11, 0.08) 0%, rgba(36, 24, 11, 0.84) 100%);
    }

    .enrollment-player-meta {
        display: flex;
        flex-wrap: wrap;
        gap: 10px;
    }

    .enrollment-player-chip {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 8px 12px;
        border-radius: 999px;
        background: rgba(255, 255, 255, 0.14);
        font-size: 0.84rem;
        font-weight: 700;
    }

    .enrollment-player-overlay h1 {
        font-size: 2rem;
        font-weight: 700;
        margin-bottom: 0;
    }

    .enrollment-player-body {
        padding: 28px;
    }

    .enrollment-player-body p {
        color: #655341;
        line-height: 1.75;
    }

    .enrollment-progress-block {
        margin: 24px 0;
        padding: 20px;
        border-radius: 20px;
        background: linear-gradient(180deg, #fff8f0 0%, #ffffff 100%);
        border: 1px solid rgba(245, 124, 0, 0.12);
    }

    .enrollment-progress-head {
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 12px;
        margin-bottom: 12px;
        color: #2d210f;
        font-weight: 700;
    }

    .enrollment-progress-bar {
        height: 12px;
        border-radius: 999px;
        background: rgba(245, 124, 0, 0.12);
        overflow: hidden;
    }

    .enrollment-progress-fill {
        height: 100%;
        border-radius: inherit;
        background: linear-gradient(135deg, #ff9800, #f57c00);
    }

    .enrollment-player-sidebar {
        padding: 24px;
        position: sticky;
        top: 24px;
    }

    .enrollment-sidebar-title {
        font-size: 1.15rem;
        font-weight: 700;
        color: #24180b;
        margin-bottom: 16px;
    }

    .enrollment-sidebar-list {
        display: grid;
        gap: 14px;
        margin-bottom: 22px;
    }

    .enrollment-sidebar-item {
        padding: 16px 18px;
        border-radius: 18px;
        background: linear-gradient(180deg, #fffaf5 0%, #ffffff 100%);
        border: 1px solid rgba(245, 124, 0, 0.12);
    }

    .enrollment-sidebar-item span {
        display: block;
        font-size: 0.78rem;
        font-weight: 700;
        letter-spacing: 0.06em;
        text-transform: uppercase;
        color: #b8731a;
        margin-bottom: 6px;
    }

    .enrollment-sidebar-item strong {
        color: #24180b;
        font-size: 1rem;
    }

    .enrollment-sidebar-actions {
        display: grid;
        gap: 12px;
    }

    .enrollment-sidebar-btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        width: 100%;
        padding: 12px 16px;
        border-radius: 14px;
        text-decoration: none;
        font-weight: 700;
    }

    .enrollment-sidebar-btn.primary {
        color: #fff;
        background: linear-gradient(135deg, #ff9800, #f57c00);
        box-shadow: 0 16px 24px rgba(245, 124, 0, 0.18);
    }

    .enrollment-sidebar-btn.secondary {
        color: #9d5b07;
        background: rgba(255, 152, 0, 0.08);
        border: 1px solid rgba(245, 124, 0, 0.18);
    }

    @media (max-width: 991px) {
        .enrollment-player-shell {
            grid-template-columns: 1fr;
        }

        .enrollment-player-sidebar {
            position: static;
        }
    }

    @media (max-width: 575px) {
        .enrollment-player-page {
            padding: 32px 0 48px;
        }

        .enrollment-player-overlay,
        .enrollment-player-body,
        .enrollment-player-sidebar {
            padding: 18px;
        }

        .enrollment-player-overlay h1 {
            font-size: 1.55rem;
        }
    }
</style>
@endpush

@section('content')
<section class="enrollment-player-page">
    <div class="container">
        <div class="enrollment-player-shell">
            <article class="enrollment-player-card">
                <div class="enrollment-player-hero">
                    <img src="{{ $enrollment['image'] }}" alt="{{ $enrollment['title'] }}">
                    <div class="enrollment-player-overlay">
                        <div class="enrollment-player-meta">
                            <span class="enrollment-player-chip">
                                <i class="bi bi-bookmark-star-fill"></i>
                                {{ $enrollment['category'] }}
                            </span>
                            <span class="enrollment-player-chip">
                                <i class="bi bi-graph-up-arrow"></i>
                                {{ $progress }}% Complete
                            </span>
                            <span class="enrollment-player-chip">
                                <i class="bi bi-calendar-event"></i>
                                Joined {{ $enrollment['enrolled_at_label'] }}
                            </span>
                        </div>
                        <h1>{{ $enrollment['title'] }}</h1>
                    </div>
                </div>

                <div class="enrollment-player-body">
                    <p>{{ $enrollment['description'] !== '' ? strip_tags($enrollment['description']) : 'Your course progress is synced from the LMS. The lesson-by-lesson player is not available in this integration yet, but this page keeps the learner context in one place and sends you back to the full course overview when needed.' }}</p>

                    <div class="enrollment-progress-block">
                        <div class="enrollment-progress-head">
                            <span>Current Learning Progress</span>
                            <strong>{{ $progress }}%</strong>
                        </div>
                        <div class="enrollment-progress-bar">
                            <div class="enrollment-progress-fill" style="width: {{ $progress }}%;"></div>
                        </div>
                    </div>

                    <p class="mb-0">This enrollment page is the current bridge between your account dashboard and the course experience. Once the LMS exposes a stable lesson or player URL, the resume action can point directly into the next lesson without changing the dashboard UI again.</p>
                </div>
            </article>

            <aside class="enrollment-player-sidebar">
                <h2 class="enrollment-sidebar-title">Learning Snapshot</h2>

                <div class="enrollment-sidebar-list">
                    <div class="enrollment-sidebar-item">
                        <span>Status</span>
                        <strong>{{ $status }}</strong>
                    </div>
                    <div class="enrollment-sidebar-item">
                        <span>Instructor</span>
                        <strong>{{ $enrollment['instructor'] }}</strong>
                    </div>
                    <div class="enrollment-sidebar-item">
                        <span>Duration</span>
                        <strong>{{ $enrollment['duration_label'] }}</strong>
                    </div>
                </div>

                <div class="enrollment-sidebar-actions">
                    <a href="{{ $enrollment['course_url'] }}" class="enrollment-sidebar-btn primary">
                        <i class="bi bi-play-circle-fill"></i>
                        Open Course Overview
                    </a>
                    <a href="{{ route('dashboard') }}" class="enrollment-sidebar-btn secondary">
                        <i class="bi bi-arrow-left-circle"></i>
                        Back to Dashboard
                    </a>
                </div>
            </aside>
        </div>
    </div>
</section>
@endsection
