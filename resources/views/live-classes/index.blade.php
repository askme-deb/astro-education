@extends('layouts.app')

@php
    $items = collect($items ?? []);
    $liveNow = collect($liveNow ?? []);
    $upcoming = collect($upcoming ?? []);
    $recordings = collect($recordings ?? []);
@endphp

@section('title', 'Live Classes')

@push('styles')
<style>
    .live-classes-page {
        background:
            radial-gradient(circle at top right, rgba(255, 152, 0, 0.14), transparent 24%),
            linear-gradient(180deg, #fffaf3 0%, #ffffff 42%, #fff6ea 100%);
        padding: 56px 0 72px;
    }

    .live-classes-hero,
    .live-classes-section,
    .live-class-card,
    .live-class-empty {
        background: rgba(255, 255, 255, 0.96);
        border: 1px solid rgba(245, 124, 0, 0.12);
        border-radius: 24px;
        box-shadow: 0 20px 40px rgba(104, 57, 5, 0.08);
    }

    .live-classes-hero {
        padding: 34px;
        margin-bottom: 24px;
        background: linear-gradient(135deg, rgba(255, 152, 0, 0.14), rgba(245, 124, 0, 0.24));
    }

    .live-classes-kicker {
        display: inline-flex;
        align-items: center;
        gap: 10px;
        padding: 7px 14px;
        margin-bottom: 14px;
        border-radius: 999px;
        background: rgba(255, 255, 255, 0.82);
        color: #bb6400;
        font-size: 0.83rem;
        font-weight: 700;
        letter-spacing: 0.05em;
        text-transform: uppercase;
    }

    .live-classes-hero h1 {
        font-size: 2.1rem;
        font-weight: 700;
        color: #24180b;
        margin-bottom: 0.75rem;
    }

    .live-classes-hero p {
        max-width: 780px;
        color: #5a4834;
        margin-bottom: 0;
    }

    .live-classes-stats {
        display: grid;
        grid-template-columns: repeat(3, minmax(0, 1fr));
        gap: 18px;
        margin-bottom: 24px;
    }

    .live-classes-section {
        padding: 28px;
    }

    .live-classes-section + .live-classes-section {
        margin-top: 24px;
    }

    .live-classes-section-header {
        display: flex;
        justify-content: space-between;
        align-items: end;
        gap: 16px;
        margin-bottom: 20px;
    }

    .live-classes-section-header h2 {
        font-size: 1.35rem;
        font-weight: 700;
        color: #24180b;
        margin-bottom: 0.35rem;
    }

    .live-classes-section-header p {
        color: #7a654f;
        margin-bottom: 0;
    }

    .live-class-grid {
        display: grid;
        grid-template-columns: repeat(3, minmax(0, 1fr));
        gap: 18px;
    }

    .live-class-card {
        overflow: hidden;
    }

    .live-class-thumb {
        width: 100%;
        height: 200px;
        object-fit: cover;
        display: block;
        background: #fff4e2;
    }

    .live-class-body {
        padding: 22px;
    }

    .live-class-meta {
        display: flex;
        flex-wrap: wrap;
        gap: 10px;
        margin-bottom: 14px;
    }

    .live-class-pill {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 7px 12px;
        border-radius: 999px;
        font-size: 0.82rem;
        font-weight: 700;
        background: rgba(255, 152, 0, 0.1);
        color: #b86400;
    }

    .live-class-pill.is-live {
        background: rgba(214, 48, 49, 0.1);
        color: #b3261e;
    }

    .live-class-card h3 {
        font-size: 1.15rem;
        font-weight: 700;
        color: #24180b;
        margin-bottom: 0.55rem;
    }

    .live-class-card p {
        color: #6c5843;
        margin-bottom: 14px;
        line-height: 1.65;
    }

    .live-class-details {
        display: grid;
        gap: 8px;
        margin-bottom: 18px;
        color: #614d39;
    }

    .live-class-details div {
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .live-class-actions {
        display: flex;
        flex-wrap: wrap;
        gap: 10px;
    }

    .live-class-btn,
    .live-class-btn-secondary,
    .live-class-btn-muted {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        padding: 11px 14px;
        border-radius: 12px;
        text-decoration: none;
        font-weight: 700;
        border: 0;
    }

    .live-class-btn {
        background: linear-gradient(135deg, #ff9800, #f57c00);
        color: #fff;
    }

    .live-class-btn-secondary {
        background: rgba(255, 152, 0, 0.1);
        color: #a55c00;
    }

    .live-class-btn-muted {
        width: 100%;
        background: #fff7ee;
        color: #8d755d;
        border: 1px dashed rgba(245, 124, 0, 0.25);
    }

    .live-class-empty {
        padding: 28px;
        text-align: center;
        color: #6c5843;
    }

    @media (max-width: 991px) {
        .live-classes-stats,
        .live-class-grid {
            grid-template-columns: 1fr;
        }
    }

    @media (max-width: 575px) {
        .live-classes-page {
            padding: 32px 0 48px;
        }

        .live-classes-hero,
        .live-classes-section,
        .live-class-body,
        .live-class-empty {
            padding: 18px;
        }

        .live-classes-hero h1 {
            font-size: 1.65rem;
        }
    }
</style>
@endpush

@section('content')
<section class="live-classes-page">
    <div class="container">
        <div class="live-classes-hero">
            <span class="live-classes-kicker">
                <i class="bi bi-broadcast"></i>
                Student Live Classes
            </span>
            <h1>Attend, revisit, and track every live astrology session.</h1>
            <p>The live class area keeps your scheduled sessions, direct join links, and recording access in one place while staying aligned with the current site theme.</p>
        </div>

        <div class="live-classes-stats">
            <div class="live-classes-section">
                <div class="live-classes-section-header mb-0">
                    <div>
                        <h2>{{ $liveNow->count() }}</h2>
                        <p>Live right now</p>
                    </div>
                    <i class="bi bi-broadcast-pin fs-3 text-danger"></i>
                </div>
            </div>
            <div class="live-classes-section">
                <div class="live-classes-section-header mb-0">
                    <div>
                        <h2>{{ $upcoming->count() }}</h2>
                        <p>Upcoming sessions</p>
                    </div>
                    <i class="bi bi-calendar-event fs-3 text-warning"></i>
                </div>
            </div>
            <div class="live-classes-section">
                <div class="live-classes-section-header mb-0">
                    <div>
                        <h2>{{ $recordings->count() }}</h2>
                        <p>Available recordings</p>
                    </div>
                    <i class="bi bi-play-btn fs-3 text-warning"></i>
                </div>
            </div>
        </div>

        <div class="live-classes-section">
            <div class="live-classes-section-header">
                <div>
                    <h2>All Sessions</h2>
                    <p>Open the session details page to join live, review credentials, or watch the recording when available.</p>
                </div>
            </div>

            @if ($items->isNotEmpty())
                <div class="live-class-grid">
                    @foreach ($items as $liveClass)
                        <article class="live-class-card">
                            <img src="{{ $liveClass['thumbnail'] }}" alt="{{ $liveClass['title'] }}" class="live-class-thumb">

                            <div class="live-class-body">
                                <div class="live-class-meta">
                                    <span class="live-class-pill {{ ($liveClass['status'] ?? null) === 'live' ? 'is-live' : '' }}">
                                        <i class="bi {{ ($liveClass['status'] ?? null) === 'live' ? 'bi-broadcast-pin' : 'bi-calendar3' }}"></i>
                                        {{ $liveClass['status_label'] }}
                                    </span>
                                    <span class="live-class-pill">
                                        <i class="bi bi-person-video3"></i>
                                        {{ $liveClass['host'] }}
                                    </span>
                                    <span class="live-class-pill">
                                        <i class="bi bi-collection-play"></i>
                                        {{ $liveClass['course_label'] }}
                                    </span>
                                </div>

                                <h3>{{ $liveClass['title'] }}</h3>
                                <p>{{ Illuminate\Support\Str::limit(strip_tags($liveClass['description']), 120) ?: 'Join this guided live class session from your student area.' }}</p>

                                <div class="live-class-details">
                                    <div>
                                        <i class="bi bi-clock-history"></i>
                                        <span>{{ $liveClass['starts_at_label'] }}</span>
                                    </div>
                                    @if (! empty($liveClass['ends_at_label']))
                                        <div>
                                            <i class="bi bi-stopwatch"></i>
                                            <span>Ends {{ $liveClass['ends_at_label'] }}</span>
                                        </div>
                                    @endif
                                    <div>
                                        <i class="bi bi-hourglass-split"></i>
                                        <span>{{ $liveClass['duration_label'] }}</span>
                                    </div>
                                    <div>
                                        <i class="bi {{ ! empty($liveClass['is_recorded']) ? 'bi-badge-hd' : 'bi-camera-video-off' }}"></i>
                                        <span>{{ ! empty($liveClass['is_recorded']) ? 'Recording enabled' : 'Recording not scheduled' }}</span>
                                    </div>
                                </div>

                                <div class="live-class-actions">
                                    <a href="{{ route('live-classes.show', ['id' => $liveClass['id']]) }}" class="live-class-btn">
                                        <i class="bi bi-arrow-up-right-circle"></i>
                                        Open Session
                                    </a>

                                    @if (! empty($liveClass['recording_url']))
                                        <a href="{{ $liveClass['recording_url'] }}" target="_blank" rel="noopener" class="live-class-btn-secondary">
                                            <i class="bi bi-play-circle"></i>
                                            Recording
                                        </a>
                                    @endif
                                </div>
                            </div>
                        </article>
                    @endforeach
                </div>
            @else
                <div class="live-class-empty">
                    <h3 class="h5 mb-2">No live classes available yet</h3>
                    <p class="mb-0">As soon as the LMS exposes scheduled sessions for your account, they will appear here with direct join and recording actions.</p>
                </div>
            @endif
        </div>
    </div>
</section>
@endsection
