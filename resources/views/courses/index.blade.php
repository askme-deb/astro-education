@extends('layouts.app')

@section('title', 'Courses - Astrology Website')

@section('content')
@php
    $featuredCourse = $featuredCourse ?? ($courseItems[0] ?? null);
    $pagination = $pagination ?? ['current_page' => 1, 'last_page' => 1, 'total' => count($courseItems ?? []), 'from' => 0, 'to' => count($courseItems ?? [])];
    $levels = collect($courseItems ?? [])->pluck('level')->filter()->unique()->values();
    $durations = collect($courseItems ?? [])->pluck('duration_label')->filter()->unique()->values();
    $badgeClasses = [
        'Beginner' => 'bg-warning text-dark',
        'Intermediate' => 'bg-primary',
        'Advanced' => 'bg-danger',
    ];
@endphp

<div class="container mt-4 inner_back">

    <div class="banner">

        <img src="{{ asset('assets/images/consult.png') }}" alt="Astrology Banner">

        <div class="banner-overlay">

            <div class="banner-content">
                <h1>
                    Upcoming Courses
                </h1>

                @if ($featuredCourse)
                    <a href="{{ route('courses.show', ['id' => $featuredCourse['id']]) }}" class="appointment-btn text-decoration-none d-inline-flex align-items-center justify-content-center">
                        Explore 1{{ $featuredCourse['title'] }}
                    </a>
                @endif
            </div>

        </div>

    </div>

</div>


<div class="container mb-5">

    <form method="GET" action="{{ route('courses.index') }}" class="filter-box mb-4">
        <div class="row g-3">

            <div class="col-md-3">
                <input type="text" name="search" value="{{ $filters['search'] ?? '' }}" class="form-control" placeholder="Search Course..." autocomplete="off">
            </div>

            <div class="col-md-3">
                <select id="levelFilter" name="difficulty_level" class="form-select">
                    <option value="">All Levels</option>
                    @foreach ($levels as $level)
                        <option value="{{ $level }}" @selected(($filters['difficulty_level'] ?? '') === $level)>{{ $level }}</option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-3">
                <select id="durationFilter" name="duration" class="form-select">
                    <option value="">All Duration</option>
                    @foreach ($durations as $duration)
                        <option value="{{ $duration }}" @selected(($filters['duration'] ?? '') === $duration)>{{ $duration }}</option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-3">
                <div class="d-flex gap-2">
                    <select id="priceSort" name="price_sort" class="form-select">
                    <option value="">Sort By Price</option>
                        <option value="low" @selected(($filters['price_sort'] ?? '') === 'low')>Low to High</option>
                        <option value="high" @selected(($filters['price_sort'] ?? '') === 'high')>High to Low</option>
                    </select>
                    <button type="submit" class="btn btn-dark">Apply</button>
                </div>
            </div>

        </div>
    </form>

    <div class="row g-4" id="courseContainer">

        <div class="col-md-8">
            @forelse (array_chunk($courseItems ?? [], 3) as $courseRow)
                <div class="row">
                    @foreach ($courseRow as $course)
                        @php
                            $badgeClass = $badgeClasses[$course['level']] ?? 'bg-secondary';
                            $cardImage = $course['image'] ?: asset('assets/images/course1.png');
                        @endphp
                        <div class="col-md-4 course-item mb-4" data-level="{{ $course['level'] }}" data-duration="{{ $course['duration_label'] }}" data-price="{{ $course['price'] }}">
                            <div class="course-card">
                                <div class="card h-100">
                                    <img src="{{ $cardImage }}" class="card-img-top" alt="{{ $course['title'] }}">
                                    <div class="card-body">
                                        <div class="d-flex justify-content-between mb-2 gap-2">
                                            <span class="badge {{ $badgeClass }}">{{ $course['level'] }}</span>
                                            <span class="text-muted text-end"><i class="fas fa-clock"></i> {{ $course['duration_label'] }}</span>
                                        </div>
                                        <h5>{{ $course['title'] }}</h5>
                                        <p class="text-muted small mb-2">{{ $course['description'] }}</p>
                                        <p class="small mb-2"><strong>Instructor:</strong> {{ $course['instructor'] }}</p>
                                        <div class="progress mb-3">
                                            <div class="progress-bar {{ $badgeClass }}" style="width:{{ $course['progress'] }}%"></div>
                                        </div>
                                        <div class="d-flex justify-content-between align-items-center gap-2">
                                            <span class="price">{{ $course['price_label'] }}</span>
                                            <a href="{{ route('courses.show', ['id' => $course['id']]) }}" class="btn btn-dark btn-sm enrol_warp">View Course</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @empty
                <div class="alert alert-light border text-center">
                    No courses are available right now.
                </div>
            @endforelse

            @if (($pagination['last_page'] ?? 1) > 1)
                <nav>
                    <ul class="pagination justify-content-center">
                        <li class="page-item {{ ($pagination['current_page'] ?? 1) <= 1 ? 'disabled' : '' }}">
                            <a class="page-link" href="{{ ($pagination['current_page'] ?? 1) > 1 ? route('courses.index', array_merge(request()->query(), ['page' => ($pagination['current_page'] - 1)])) : '#' }}" tabindex="-1">Previous</a>
                        </li>

                        @for ($page = 1; $page <= ($pagination['last_page'] ?? 1); $page++)
                            <li class="page-item {{ ($pagination['current_page'] ?? 1) === $page ? 'active' : '' }}">
                                <a class="page-link" href="{{ route('courses.index', array_merge(request()->query(), ['page' => $page])) }}">{{ $page }}</a>
                            </li>
                        @endfor

                        <li class="page-item {{ ($pagination['current_page'] ?? 1) >= ($pagination['last_page'] ?? 1) ? 'disabled' : '' }}">
                            <a class="page-link" href="{{ ($pagination['current_page'] ?? 1) < ($pagination['last_page'] ?? 1) ? route('courses.index', array_merge(request()->query(), ['page' => ($pagination['current_page'] + 1)])) : '#' }}">Next</a>
                        </li>
                    </ul>
                </nav>
            @endif

        </div>

        <div class="col-md-4">
            <div class="lesson-card">

                <h5>{{ $featuredCourse ? 'Featured Course' : 'Course Snapshot' }}</h5>

                <p>{{ $featuredCourse['title'] ?? 'Fresh courses will appear here soon.' }}</p>

                <div class="progress mb-3">
                    <div class="progress-bar bg-warning" style="width:{{ $featuredCourse['progress'] ?? 0 }}%"></div>
                </div>

                <p>{{ $featuredCourse ? $featuredCourse['category'] . ' | ' . $featuredCourse['level'] : '0% Completed' }}</p>

                @if ($featuredCourse)
                    <a href="{{ route('courses.show', ['id' => $featuredCourse['id']]) }}" class="btn-main w-100 d-inline-flex align-items-center justify-content-center text-decoration-none">Continue Lesson</a>
                @else
                    <button class="btn-main w-100" type="button" disabled>Continue Lesson</button>
                @endif

            </div>
            <div class="sre_op">
                <div class="banner-content">
                    <h1>
                        {{ $pagination['total'] ?? 0 }} Courses
                    </h1>

                    <p class="mb-3 text-white">Showing {{ $pagination['from'] ?? 0 }}-{{ $pagination['to'] ?? 0 }} of {{ $pagination['total'] ?? 0 }}</p>
                    <a href="{{ route('courses.index') }}" class="appointment-btn text-decoration-none d-inline-flex align-items-center justify-content-center">
                        Browse All
                    </a>
                </div>
                <img src="{{ asset('assets/images/bannti.png') }}" class="card-img-top">
            </div>
        </div>
    </div>
</div>

@endsection
