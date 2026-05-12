@extends('layouts.app')

@php
    $instructors = collect($course['instructors'] ?? []);
    $lessons = collect($course['lessons'] ?? []);
    $overview = trim((string) ($courseContent ?? ''));
    $enrollment = $enrollment ?? null;
    $progress = max(0, min(100, (int) ($enrollment['progress'] ?? ($course['progress'] ?? 0))));
    $lessonCount = max((int) ($course['lesson_count'] ?? $lessons->count()), 0);
    $completedLessons = $lessonCount > 0 ? min($lessonCount, (int) floor(($progress / 100) * $lessonCount)) : 0;
    $selectedLessonId = request()->integer('lesson');
    $playableLessons = $lessons->filter(fn (array $lesson): bool => ! empty($lesson['embed_url'] ?? $lesson['video_url'] ?? null))->values();
    $activeLesson = $playableLessons->firstWhere('id', $selectedLessonId) ?? $playableLessons->first();
    $activeLessonId = $activeLesson['id'] ?? null;
    $sessionGroups = $lessons
        ->groupBy(fn (array $lesson): string => (string) ($lesson['topic_id'] ?? 'general'))
        ->map(function ($groupedLessons, $topicKey) {
            $lessonItems = collect($groupedLessons)->values();
            $topicLabel = $topicKey !== 'general'
                ? 'Topic ' . $topicKey
                : 'General Session';

            return [
                'topic_key' => (string) $topicKey,
                'title' => $topicLabel,
                'lesson_count' => $lessonItems->count(),
                'lessons' => $lessonItems,
            ];
        })
        ->values();
    $enrollmentValidity = $course['raw']['enrollment_expiration'] ?? null;
    $enrollmentValidityLabel = filled($enrollmentValidity) ? (is_numeric($enrollmentValidity) ? $enrollmentValidity . ' days' : (string) $enrollmentValidity) : 'Lifetime access';
    $lastUpdated = $course['raw']['updated_at'] ?? $course['raw']['created_at'] ?? null;
    $startLearningUrl = ($isEnrolled ?? false) && $activeLessonId
        ? route('courses.show', ['id' => $course['id'] ?? 0, 'lesson' => $activeLessonId]) . '#course-player'
        : (! empty($enrollment['id']) ? route('enrollments.show', ['id' => $enrollment['id']]) : null);
@endphp

@section('title', ($course['title'] ?? 'Course Details') . ' - Astrology Website')

@push('styles')
<style>
    .course-single-page {
        background:
            radial-gradient(circle at top right, rgba(255, 152, 0, 0.14), transparent 24%),
            linear-gradient(180deg, #fffaf3 0%, #ffffff 42%, #fff5e8 100%);
        padding: 56px 0 72px;
    }

    .course-single-grid {
        display: grid;
        grid-template-columns: minmax(0, 1.55fr) minmax(320px, 0.72fr);
        gap: 32px;
        align-items: start;
    }

    .course-single-main,
    .course-single-sidebar,
    .course-instructor-card {
        background: rgba(255, 255, 255, 0.96);
        border: 1px solid rgba(245, 124, 0, 0.12);
        border-radius: 16px;
        box-shadow: 0 18px 36px rgba(104, 57, 5, 0.08);
    }

    .course-single-main {
        padding: 30px;
    }

    .course-page-title {
        font-size: 2rem;
        font-weight: 700;
        color: #15213a;
        margin-bottom: 10px;
    }

    .course-page-summary {
        color: #6c5a48;
        margin-bottom: 28px;
        max-width: 760px;
        line-height: 1.8;
    }

    .course-top-meta {
        display: flex;
        flex-wrap: wrap;
        gap: 10px;
        margin-bottom: 28px;
    }

    .course-meta-chip {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 8px 12px;
        border-radius: 999px;
        background: rgba(255, 152, 0, 0.12);
        color: #c56b00;
        font-size: 0.83rem;
        font-weight: 700;
    }

    .course-section-title {
        font-size: 1.7rem;
        font-weight: 700;
        color: #15213a;
        margin-bottom: 18px;
    }

    .course-player-card {
        margin-bottom: 26px;
        border: 1px solid rgba(34, 53, 102, 0.12);
        border-radius: 16px;
        overflow: hidden;
        background: #11192f;
        box-shadow: 0 18px 36px rgba(17, 25, 47, 0.12);
    }

    .course-player-frame {
        width: 100%;
        aspect-ratio: 16 / 9;
        display: block;
        border: 0;
        background: #0e1527;
    }

    .course-player-empty {
        padding: 36px 28px;
        color: #d5ddf4;
        background: linear-gradient(135deg, #17213d, #202c50);
    }

    .course-player-empty h3,
    .course-player-meta h3 {
        font-size: 1.2rem;
        font-weight: 700;
        margin-bottom: 8px;
    }

    .course-player-empty p,
    .course-player-meta p {
        margin-bottom: 0;
        color: #b7c1de;
    }

    .course-player-meta {
        padding: 20px 24px 22px;
        background: linear-gradient(180deg, rgba(17, 25, 47, 0.98) 0%, rgba(23, 33, 61, 0.98) 100%);
        color: #fff;
    }

    .course-flash + .course-flash {
        margin-top: 12px;
    }

    .course-flash {
        border-radius: 16px;
        border: 1px solid rgba(69, 101, 217, 0.14);
    }

    .course-accordion {
        display: grid;
        gap: 14px;
    }

    .course-session-card {
        border: 1px solid rgba(245, 124, 0, 0.14);
        border-radius: 10px;
        overflow: hidden;
        background: #fff;
        box-shadow: 0 8px 22px rgba(104, 57, 5, 0.04);
    }

    .course-session-toggle {
        width: 100%;
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 14px;
        padding: 16px 20px;
        background: #fff5e8;
        color: #c56b00;
        font-size: 1rem;
        font-weight: 700;
        text-align: left;
    }

    .course-session-toggle.collapsed {
        background: #fff;
        color: #2f2418;
    }

    .course-session-toggle:focus {
        box-shadow: none;
    }

    .course-session-toggle::after {
        margin-left: auto;
        filter: sepia(1) saturate(5) hue-rotate(355deg) brightness(0.92);
    }

    .course-session-label {
        display: inline-flex;
        align-items: center;
        gap: 12px;
        min-width: 0;
    }

    .course-session-copy {
        display: inline-flex;
        flex-direction: column;
        gap: 2px;
    }

    .course-session-heading {
        display: inline-flex;
        align-items: center;
        gap: 10px;
        color: inherit;
    }

    .course-session-copy small {
        font-size: 0.78rem;
        font-weight: 600;
        color: #9c7a58;
    }

    .course-session-body {
        padding: 0;
        background: #fff;
    }

    .course-lesson-list {
        display: grid;
        gap: 0;
    }

    .course-lesson-row {
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 16px;
        padding: 14px 18px;
        border-top: 1px solid rgba(245, 124, 0, 0.1);
        background: #fff;
        transition: background 0.18s ease;
    }

    .course-lesson-row.is-active {
        background: #fff6ea;
        box-shadow: inset 3px 0 0 #f57c00;
    }

    .course-lesson-row:hover {
        background: #fffaf3;
    }

    .course-lesson-row:first-child {
        border-top: 0;
    }

    .course-lesson-link {
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 16px;
        width: 100%;
        color: inherit;
        text-decoration: none;
    }

    .course-lesson-link:hover {
        color: inherit;
    }

    .course-lesson-row.is-active .course-lesson-link {
        position: relative;
    }

    .course-lesson-main {
        display: flex;
        align-items: center;
        gap: 12px;
        min-width: 0;
    }

    .course-lesson-index {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 26px;
        height: 26px;
        border-radius: 6px;
        color: #b46a17;
        background: rgba(255, 152, 0, 0.12);
        flex-shrink: 0;
    }

    .course-lesson-copy {
        min-width: 0;
    }

    .course-lesson-row h3 {
        font-size: 0.98rem;
        font-weight: 500;
        color: #24180b;
        margin-bottom: 0;
    }

    .course-lesson-row.is-active h3 {
        font-weight: 700;
        color: #a65a00;
    }

    .course-lesson-side {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        color: #8b735c;
        font-size: 0.92rem;
        white-space: nowrap;
    }

    .course-lesson-status {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 4px 8px;
        border-radius: 999px;
        background: rgba(245, 124, 0, 0.12);
        color: #c56b00;
        font-size: 0.78rem;
        font-weight: 700;
    }

    .course-single-sidebar {
        padding: 0;
        position: sticky;
        top: 24px;
        overflow: hidden;
    }

    .course-progress-card {
        padding: 26px 22px 20px;
        background: linear-gradient(180deg, #ffffff 0%, #fffaf3 100%);
    }

    .course-progress-card h2 {
        font-size: 1.05rem;
        font-weight: 700;
        color: #15213a;
        margin-bottom: 20px;
    }

    .course-progress-meta {
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 12px;
        color: #2a3558;
        margin-bottom: 14px;
        font-size: 0.98rem;
    }

    .course-progress-meta span:last-child {
        color: #a65a00;
        font-weight: 700;
    }

    .course-progress-track {
        height: 6px;
        border-radius: 999px;
        background: #e7ebf6;
        overflow: hidden;
        margin-bottom: 22px;
    }

    .course-progress-fill {
        height: 100%;
        border-radius: inherit;
        background: linear-gradient(135deg, #ff9800, #f57c00);
    }

    .course-sidebar-actions {
        display: grid;
        gap: 12px;
        margin-bottom: 16px;
    }

    .course-btn-primary,
    .course-btn-secondary,
    .course-btn-muted {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        width: 100%;
        padding: 12px 16px;
        border-radius: 10px;
        font-weight: 700;
        text-decoration: none;
        border: 0;
    }

    .course-btn-primary {
        background: linear-gradient(135deg, #ff9800, #f57c00);
        color: #fff;
        box-shadow: 0 14px 22px rgba(245, 124, 0, 0.18);
    }

    .course-btn-primary:hover,
    .course-btn-secondary:hover {
        color: #fff;
        transform: translateY(-1px);
    }

    .course-btn-muted {
        background: rgba(255, 152, 0, 0.1);
        color: #b86400;
    }

    .course-btn-secondary {
        background: linear-gradient(135deg, #ff9800, #f57c00);
        color: #fff;
    }

    .course-validity {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        color: #8a745e;
        font-size: 0.95rem;
    }

    .course-sidebar-stats {
        padding: 18px 22px 22px;
        border-top: 1px solid rgba(245, 124, 0, 0.12);
        display: grid;
        gap: 12px;
        background: #fff;
    }

    .course-sidebar-stat {
        display: flex;
        align-items: center;
        gap: 12px;
        color: #2d2114;
        font-size: 0.98rem;
        line-height: 1.45;
    }

    .course-sidebar-stat i {
        color: #b86400;
    }

    .course-sidebar-stat span {
        color: #7c6852;
    }

    .course-instructor-card {
        margin-top: 20px;
        padding: 20px 22px;
        background: linear-gradient(180deg, #ffffff 0%, #fffaf3 100%);
    }

    .course-instructor-card h2 {
        font-size: 1.05rem;
        font-weight: 700;
        color: #24180b;
        margin-bottom: 14px;
    }

    .course-instructor-item {
        display: flex;
        align-items: center;
        gap: 14px;
    }

    .course-instructor-avatar {
        width: 54px;
        height: 54px;
        border-radius: 50%;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        background: linear-gradient(135deg, #ffe4bf, #fff4df);
        color: #c56b00;
        font-weight: 700;
        font-size: 1.1rem;
        flex-shrink: 0;
        border: 1px solid rgba(245, 124, 0, 0.16);
    }

    .course-instructor-copy strong {
        display: block;
        color: #24180b;
        margin-bottom: 4px;
        font-size: 1rem;
    }

    .course-instructor-copy p,
    .course-instructor-copy a {
        color: #7d6750;
        margin-bottom: 0;
        word-break: break-word;
    }

    @media (max-width: 991px) {
        .course-single-grid {
            grid-template-columns: 1fr;
        }

        .course-single-sidebar {
            position: static;
        }

        .course-lesson-row {
            gap: 10px;
        }

        .course-lesson-side {
            width: 100%;
            justify-content: space-between;
        }
    }

    @media (max-width: 575px) {
        .course-single-page {
            padding: 32px 0 48px;
        }

        .course-single-main,
        .course-single-sidebar,
        .course-instructor-card {
            padding: 18px;
        }

        .course-progress-card,
        .course-sidebar-stats {
            padding-left: 18px;
            padding-right: 18px;
        }

        .course-page-title,
        .course-section-title {
            font-size: 1.55rem;
        }
    }
</style>
@endpush

@section('content')
<section class="course-single-page">
    <div class="container">
        <div class="course-single-grid">
            <article class="course-single-main">
                @if (session('status'))
                    <div class="alert alert-success course-flash" role="alert">
                        {{ session('status') }}
                    </div>
                @endif

                @if (session('error'))
                    <div class="alert alert-danger course-flash" role="alert">
                        {{ session('error') }}
                    </div>
                @endif

                <div class="course-top-meta">
                    <span class="course-meta-chip">
                        <i class="bi bi-bookmark-star-fill"></i>
                        {{ $course['category'] ?? 'General' }}
                    </span>
                    <span class="course-meta-chip">
                        <i class="bi bi-mortarboard-fill"></i>
                        {{ $course['level'] ?? 'Beginner' }}
                    </span>
                    <span class="course-meta-chip">
                        <i class="bi bi-collection-play-fill"></i>
                        {{ $lessonCount }} Lessons
                    </span>
                </div>

                <h1 class="course-page-title">{{ $course['title'] ?? 'Course Details' }}</h1>

                @if ($overview !== '')
                    <div class="course-page-summary">{!! $overview !!}</div>
                @else
                    <p class="course-page-summary">Follow the full curriculum below and track your learning progress from the sidebar. This layout is optimized for course consumption rather than a catalog-style product page.</p>
                @endif

                <section>
                    @if (($isEnrolled ?? false) && $activeLesson)
                        <div class="course-player-card" id="course-player">
                            <iframe
                                src="{{ $activeLesson['embed_url'] ?? $activeLesson['video_url'] }}"
                                title="{{ $activeLesson['title'] }}"
                                class="course-player-frame"
                                allow="autoplay; fullscreen; picture-in-picture"
                                allowfullscreen
                            ></iframe>
                            <div class="course-player-meta">
                                <h3>{{ $activeLesson['title'] }}</h3>
                                <p>Now playing from your enrolled course curriculum.</p>
                            </div>
                        </div>
                    @elseif (! ($isEnrolled ?? false))
                        <div class="course-player-card" id="course-player">
                            <div class="course-player-empty">
                                <h3>Enroll to start learning</h3>
                                <p>Once you enroll, the first available lesson will open directly here and the session rows will become playable.</p>
                            </div>
                        </div>
                    @endif

                    <h2 class="course-section-title">Course Content</h2>

                    @if ($sessionGroups->isNotEmpty())
                        <div class="accordion course-accordion" id="courseContentAccordion">
                            @foreach ($sessionGroups as $sessionIndex => $session)
                                @php
                                    $sessionId = 'course-session-' . $sessionIndex;
                                    $collapseId = 'course-session-collapse-' . $sessionIndex;
                                    $sessionLessons = $session['lessons'];
                                    $containsActiveLesson = $activeLessonId !== null && collect($sessionLessons)->contains(fn (array $lesson): bool => (int) ($lesson['id'] ?? 0) === (int) $activeLessonId);
                                    $isFirst = $activeLessonId !== null ? $containsActiveLesson : $sessionIndex === 0;
                                @endphp
                                <div class="course-session-card accordion-item">
                                    <h2 class="accordion-header" id="{{ $sessionId }}">
                                        <button class="course-session-toggle accordion-button {{ $isFirst ? '' : 'collapsed' }}" type="button" data-bs-toggle="collapse" data-bs-target="#{{ $collapseId }}" aria-expanded="{{ $isFirst ? 'true' : 'false' }}" aria-controls="{{ $collapseId }}">
                                            <span class="course-session-label">
                                                <span class="course-session-copy">
                                                    <span class="course-session-heading">
                                                        <span>{{ $session['title'] }}</span>
                                                        <i class="bi bi-info-circle"></i>
                                                    </span>
                                                    <small>{{ $session['lesson_count'] }} {{ \Illuminate\Support\Str::plural('lesson', $session['lesson_count']) }}</small>
                                                </span>
                                            </span>
                                        </button>
                                    </h2>
                                    <div id="{{ $collapseId }}" class="accordion-collapse collapse {{ $isFirst ? 'show' : '' }}" aria-labelledby="{{ $sessionId }}" data-bs-parent="#courseContentAccordion">
                                        <div class="course-session-body accordion-body">
                                            <div class="course-lesson-list">
                                                @foreach ($sessionLessons as $lesson)
                                                    @php
                                                        $isPlayable = ($isEnrolled ?? false) && ! empty($lesson['embed_url'] ?? $lesson['video_url'] ?? null);
                                                        $isActiveLesson = $activeLessonId !== null && (int) ($lesson['id'] ?? 0) === (int) $activeLessonId;
                                                        $lessonHref = $isPlayable ? route('courses.show', ['id' => $course['id'] ?? 0, 'lesson' => $lesson['id']]) . '#course-player' : null;
                                                    @endphp
                                                    <div class="course-lesson-row {{ $isActiveLesson ? 'is-active' : '' }}">
                                                        @if ($lessonHref)
                                                            <a href="{{ $lessonHref }}" class="course-lesson-link" @if ($isActiveLesson) aria-current="true" @endif>
                                                                <div class="course-lesson-main">
                                                                    <span class="course-lesson-index"><i class="bi bi-play-btn-fill"></i></span>
                                                                    <div class="course-lesson-copy">
                                                                        <h3>{{ $lesson['title'] }}</h3>
                                                                    </div>
                                                                </div>
                                                                <div class="course-lesson-side">
                                                                    @if ($isActiveLesson)
                                                                        <span class="course-lesson-status">
                                                                            <i class="bi bi-broadcast-pin"></i>
                                                                            Current
                                                                        </span>
                                                                    @endif
                                                                    <span>00:00</span>
                                                                    <i class="bi bi-unlock"></i>
                                                                </div>
                                                            </a>
                                                        @else
                                                            <div class="course-lesson-link">
                                                                <div class="course-lesson-main">
                                                                    <span class="course-lesson-index"><i class="bi bi-play-btn-fill"></i></span>
                                                                    <div class="course-lesson-copy">
                                                                        <h3>{{ $lesson['title'] }}</h3>
                                                                    </div>
                                                                </div>
                                                                <div class="course-lesson-side">
                                                                    <span>00:00</span>
                                                                    <i class="bi {{ ($isEnrolled ?? false) ? 'bi-unlock' : 'bi-lock' }}"></i>
                                                                </div>
                                                            </div>
                                                        @endif
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <p class="text-muted mb-0">Course sessions will appear here once the curriculum is published.</p>
                    @endif
                </section>
            </article>

            <aside>
                <div class="course-single-sidebar">
                    <div class="course-progress-card">
                        <h2>Course Progress</h2>

                        <div class="course-progress-meta">
                            <span>{{ $completedLessons }} / {{ $lessonCount }}</span>
                            <span>{{ $progress }}% Complete</span>
                        </div>

                        <div class="course-progress-track">
                            <div class="course-progress-fill" style="width: {{ $progress }}%;"></div>
                        </div>

                        <div class="course-sidebar-actions">
                            @if ($progress >= 100 && $startLearningUrl)
                                <a href="{{ $startLearningUrl }}" class="course-btn-primary">
                                    View Certificate
                                </a>
                            @endif

                            @if ($isEnrolled ?? false)
                                <a href="{{ $startLearningUrl ?? route('dashboard') }}" class="course-btn-secondary">
                                    Start Learning
                                </a>
                            @else
                                <form method="POST" action="{{ route('courses.enroll', ['courseId' => $course['id'] ?? 0]) }}">
                                    @csrf
                                    <button type="submit" class="course-btn-secondary">Enroll Now</button>
                                </form>
                            @endif
                        </div>

                        <div class="course-validity">
                            <i class="bi bi-calendar3"></i>
                            Enrollment validity: {{ $enrollmentValidityLabel }}
                        </div>
                    </div>

                    <div class="course-sidebar-stats">
                        <div class="course-sidebar-stat">
                            <i class="bi bi-mortarboard"></i>
                            <strong>1 Total Enrolled</strong>
                        </div>
                        <div class="course-sidebar-stat">
                            <i class="bi bi-clock"></i>
                            <strong>{{ $course['duration_label'] ?? 'Self paced' }} <span>Duration</span></strong>
                        </div>
                        <div class="course-sidebar-stat">
                            <i class="bi bi-arrow-repeat"></i>
                            <strong>{{ $lastUpdated ? \Illuminate\Support\Carbon::parse($lastUpdated)->format('F j, Y') : 'Recently updated' }} <span>Last Updated</span></strong>
                        </div>
                        <div class="course-sidebar-stat">
                            <i class="bi bi-award"></i>
                            <strong>Certificate of completion</strong>
                        </div>
                    </div>
                </div>

                <div class="course-instructor-card">
                    <h2>A course by</h2>

                    @if ($instructors->isNotEmpty())
                        @php($primaryInstructor = $instructors->first())
                        <div class="course-instructor-item">
                            <div class="course-instructor-avatar">
                                {{ strtoupper(substr($primaryInstructor['name'] ?? 'A', 0, 1)) }}
                            </div>
                            <div class="course-instructor-copy">
                                <strong>{{ $primaryInstructor['name'] }}</strong>
                                @if (! empty($primaryInstructor['designation']))
                                    <p>{{ $primaryInstructor['designation'] }}</p>
                                @elseif (! empty($primaryInstructor['email']))
                                    <a href="mailto:{{ $primaryInstructor['email'] }}">{{ $primaryInstructor['email'] }}</a>
                                @endif
                            </div>
                        </div>
                    @else
                        <p class="mb-0 text-muted">Instructor details will appear once faculty is assigned.</p>
                    @endif
                </div>
            </aside>
        </div>
    </div>
</section>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        if (window.location.hash !== '#course-player') {
            return;
        }

        const player = document.getElementById('course-player');

        if (!player) {
            return;
        }

        window.requestAnimationFrame(function () {
            player.scrollIntoView({ behavior: 'smooth', block: 'start' });
        });
    });
</script>
@endpush
