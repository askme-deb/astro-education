@extends('layouts.app')

@php
    $liveClass = $liveClass ?? [];
@endphp

@section('title', ($liveClass['title'] ?? 'Live Class') . ' | Live Session')

@push('styles')
<style>
    .live-class-page {
        background:
            radial-gradient(circle at top right, rgba(255, 152, 0, 0.14), transparent 24%),
            linear-gradient(180deg, #fffaf3 0%, #ffffff 42%, #fff6ea 100%);
        padding: 56px 0 72px;
    }

    .live-class-shell {
        display: grid;
        grid-template-columns: minmax(0, 1.28fr) minmax(320px, 0.72fr);
        gap: 26px;
        align-items: start;
    }

    .live-class-main,
    .live-class-sidebar {
        background: rgba(255, 255, 255, 0.96);
        border: 1px solid rgba(245, 124, 0, 0.12);
        border-radius: 26px;
        box-shadow: 0 20px 40px rgba(104, 57, 5, 0.08);
    }

    .live-class-hero {
        position: relative;
        overflow: hidden;
        border-radius: 26px 26px 0 0;
        min-height: 320px;
        background: linear-gradient(135deg, rgba(44, 27, 12, 0.82), rgba(245, 124, 0, 0.35));
    }

    .live-class-hero img {
        width: 100%;
        height: 320px;
        object-fit: cover;
        display: block;
        opacity: 0.38;
    }

    .live-class-overlay {
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

    .live-class-meta {
        display: flex;
        flex-wrap: wrap;
        gap: 10px;
    }

    .live-class-chip {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 8px 12px;
        border-radius: 999px;
        background: rgba(255, 255, 255, 0.14);
        font-size: 0.84rem;
        font-weight: 700;
    }

    .live-class-overlay h1 {
        font-size: 2rem;
        font-weight: 700;
        margin-bottom: 0;
    }

    .live-class-body {
        padding: 28px;
    }

    .live-class-body p {
        color: #655341;
        line-height: 1.75;
    }

    .live-class-player {
        margin-top: 24px;
        border-radius: 22px;
        overflow: hidden;
        border: 1px solid rgba(34, 53, 102, 0.12);
        background: #11192f;
    }

    .live-class-player iframe {
        width: 100%;
        aspect-ratio: 16 / 9;
        border: 0;
        display: block;
    }

    .live-class-player-empty {
        padding: 28px;
        color: #d5ddf4;
        background: linear-gradient(135deg, #17213d, #202c50);
    }

    .live-class-sidebar {
        padding: 24px;
        position: sticky;
        top: 24px;
    }

    .live-class-sidebar h2 {
        font-size: 1.15rem;
        font-weight: 700;
        color: #24180b;
        margin-bottom: 16px;
    }

    .live-class-info-list {
        display: grid;
        gap: 14px;
        margin-bottom: 22px;
    }

    .live-class-info-item {
        padding: 16px 18px;
        border-radius: 18px;
        background: linear-gradient(180deg, #fffaf5 0%, #ffffff 100%);
        border: 1px solid rgba(245, 124, 0, 0.12);
    }

    .live-class-info-item span {
        display: block;
        font-size: 0.78rem;
        font-weight: 700;
        letter-spacing: 0.06em;
        text-transform: uppercase;
        color: #b8731a;
        margin-bottom: 6px;
    }

    .live-class-info-item strong {
        color: #24180b;
        font-size: 1rem;
        word-break: break-word;
    }

    .live-class-actions {
        display: grid;
        gap: 12px;
    }

    .live-class-action,
    .live-class-action-secondary,
    .live-class-action-muted {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        width: 100%;
        padding: 12px 16px;
        border-radius: 14px;
        text-decoration: none;
        font-weight: 700;
        border: 0;
    }

    .live-class-action {
        color: #fff;
        background: linear-gradient(135deg, #ff9800, #f57c00);
        box-shadow: 0 16px 24px rgba(245, 124, 0, 0.18);
    }

    .live-class-action-secondary {
        color: #9d5b07;
        background: rgba(255, 152, 0, 0.08);
        border: 1px solid rgba(245, 124, 0, 0.18);
    }

    .live-class-action-muted {
        color: #8d755d;
        background: #fff7ee;
        border: 1px dashed rgba(245, 124, 0, 0.25);
    }

    .live-class-flash + .live-class-flash {
        margin-top: 12px;
    }

    @media (max-width: 991px) {
        .live-class-shell {
            grid-template-columns: 1fr;
        }

        .live-class-sidebar {
            position: static;
        }
    }

    @media (max-width: 575px) {
        .live-class-page {
            padding: 32px 0 48px;
        }

        .live-class-overlay,
        .live-class-body,
        .live-class-sidebar {
            padding: 18px;
        }

        .live-class-overlay h1 {
            font-size: 1.55rem;
        }
    }
</style>
@endpush

@section('content')
<section class="live-class-page">
    <div class="container">
        <div class="live-class-shell">
            <article class="live-class-main">
                <div class="live-class-hero">
                    <img src="{{ $liveClass['thumbnail'] }}" alt="{{ $liveClass['title'] }}">

                    <div class="live-class-overlay">
                        <div class="live-class-meta">
                            <span class="live-class-chip">
                                <i class="bi bi-broadcast-pin"></i>
                                {{ $liveClass['status_label'] }}
                            </span>
                            <span class="live-class-chip">
                                <i class="bi bi-calendar-event"></i>
                                {{ $liveClass['starts_at_label'] }}
                            </span>
                            <span class="live-class-chip">
                                <i class="bi bi-person-video3"></i>
                                {{ $liveClass['host'] }}
                            </span>
                            <span class="live-class-chip">
                                <i class="bi bi-collection-play"></i>
                                {{ $liveClass['course_label'] }}
                            </span>
                        </div>

                        <h1>{{ $liveClass['title'] }}</h1>
                    </div>
                </div>

                <div class="live-class-body">
                    @if (session('status'))
                        <div class="alert alert-success live-class-flash" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                    @if (session('error'))
                        <div class="alert alert-danger live-class-flash" role="alert">
                            {{ session('error') }}
                        </div>
                    @endif

                    <p>{{ trim(strip_tags($liveClass['description'] ?? '')) !== '' ? strip_tags($liveClass['description']) : 'This live class page gathers your meeting schedule, join access, and any available recording into one focused student view.' }}</p>

                    @if (! empty($liveClass['recording_url']))
                        <div class="live-class-player">
                            <iframe
                                src="{{ $liveClass['recording_url'] }}"
                                title="{{ $liveClass['recording_title'] ?? $liveClass['title'] }}"
                                allow="autoplay; fullscreen; picture-in-picture"
                                allowfullscreen
                            ></iframe>
                        </div>
                    @else
                        <div class="live-class-player live-class-player-empty">
                            <h3 class="h5 mb-2">Recording not available yet</h3>
                            <p class="mb-0">Use the join action when the class goes live. If the LMS exposes a recording later, it will appear in this area automatically.</p>
                        </div>
                    @endif
                </div>
            </article>

            <aside class="live-class-sidebar">
                <h2>Session Details</h2>

                <div class="live-class-info-list">
                    <div class="live-class-info-item">
                        <span>Status</span>
                        <strong>{{ $liveClass['status_label'] }}</strong>
                    </div>
                    <div class="live-class-info-item">
                        <span>Scheduled Time</span>
                        <strong>{{ $liveClass['starts_at_label'] }}</strong>
                    </div>
                    @if (! empty($liveClass['ends_at_label']))
                        <div class="live-class-info-item">
                            <span>Ends At</span>
                            <strong>{{ $liveClass['ends_at_label'] }}</strong>
                        </div>
                    @endif
                    <div class="live-class-info-item">
                        <span>Duration</span>
                        <strong>{{ $liveClass['duration_label'] }}</strong>
                    </div>
                    <div class="live-class-info-item">
                        <span>Recording</span>
                        <strong>{{ ! empty($liveClass['is_recorded']) ? 'Enabled' : 'Not enabled' }}</strong>
                    </div>
                    <div class="live-class-info-item">
                        <span>Meeting Code</span>
                        <strong>{{ $liveClass['meeting_code'] !== '' ? $liveClass['meeting_code'] : 'Shared by the LMS on join' }}</strong>
                    </div>
                    @if (($liveClass['passcode'] ?? '') !== '')
                        <div class="live-class-info-item">
                            <span>Passcode</span>
                            <strong>{{ $liveClass['passcode'] }}</strong>
                        </div>
                    @endif
                </div>

                <div class="live-class-actions">
                    @if (! empty($liveClass['join_url']))
                        <a href="{{ $liveClass['join_url'] }}" target="_blank" rel="noopener" class="live-class-action">
                            <i class="bi bi-camera-video-fill"></i>
                            Join Live Class
                        </a>
                    @elseif (! ($liveClass['is_enrolled'] ?? false))
                        <form method="POST" action="{{ route('live-classes.enroll', ['id' => $liveClass['id']]) }}">
                            @csrf
                            <button type="submit" class="live-class-action">
                                <i class="bi bi-check2-circle"></i>
                                Enroll in Live Class
                            </button>
                        </form>
                    @else
                        <div class="live-class-action-muted">
                            <i class="bi bi-hourglass-split"></i>
                            Join link will appear here once available
                        </div>
                    @endif

                    @if (! empty($liveClass['recording_url']))
                        <a href="{{ $liveClass['recording_url'] }}" target="_blank" rel="noopener" class="live-class-action-secondary">
                            <i class="bi bi-play-circle"></i>
                            Open Recording
                        </a>
                    @endif

                    <a href="{{ route('live-classes.index') }}" class="live-class-action-secondary">
                        <i class="bi bi-arrow-left-circle"></i>
                        Back to Live Classes
                    </a>
                </div>
            </aside>
        </div>
    </div>
</section>
@endsection
