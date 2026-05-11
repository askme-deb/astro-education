@extends('layouts.app')

@section('title', ($course['title'] ?? 'Course Details') . ' - Astrology Website')

@section('content')
<div class="container py-5">
    <div class="row g-4">
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm overflow-hidden">
                <img src="{{ $course['image'] ?? asset('assets/images/course1.png') }}" class="card-img-top" alt="{{ $course['title'] ?? 'Course image' }}">
                <div class="card-body p-4">
                    <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-3">
                        <div>
                            <span class="badge bg-dark mb-2">{{ $course['category'] ?? 'General' }}</span>
                            <h1 class="h3 mb-1">{{ $course['title'] ?? 'Course Details' }}</h1>
                            <p class="text-muted mb-0">Instructor: {{ $course['instructor'] ?? 'Faculty assigned soon' }}</p>
                        </div>
                        <div class="text-end">
                            <div class="h4 mb-1">{{ $course['price_label'] ?? 'Rs.0' }}</div>
                            <div class="text-muted">{{ $course['level'] ?? 'Beginner' }} | {{ $course['duration_label'] ?? 'Self paced' }}</div>
                        </div>
                    </div>

                    <div class="progress mb-4" role="progressbar" aria-label="Course progress" aria-valuenow="{{ $course['progress'] ?? 0 }}" aria-valuemin="0" aria-valuemax="100">
                        <div class="progress-bar bg-warning" style="width:{{ $course['progress'] ?? 0 }}%"></div>
                    </div>

                    <div class="mb-4">
                        <h2 class="h5">Overview</h2>
                        @if (! empty($courseContent))
                            {!! $courseContent !!}
                        @else
                            <p class="text-muted mb-0">Course details will appear here once content is available.</p>
                        @endif
                    </div>

                    <div class="d-flex gap-2">
                        <form method="POST" action="{{ route('courses.enroll', ['courseId' => $course['id'] ?? 0]) }}">
                            @csrf
                            <button type="submit" class="btn btn-dark">Enroll Now</button>
                        </form>
                        <a href="{{ route('courses.index') }}" class="btn btn-outline-secondary">Back to Courses</a>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-body">
                    <h2 class="h5">Course Snapshot</h2>
                    <ul class="list-unstyled mb-0">
                        <li class="mb-2"><strong>Status:</strong> {{ $course['status'] ?? 'Draft' }}</li>
                        <li class="mb-2"><strong>Category:</strong> {{ $course['category'] ?? 'General' }}</li>
                        <li class="mb-2"><strong>Level:</strong> {{ $course['level'] ?? 'Beginner' }}</li>
                        <li class="mb-2"><strong>Duration:</strong> {{ $course['duration_label'] ?? 'Self paced' }}</li>
                        <li><strong>Price:</strong> {{ $course['price_label'] ?? 'Rs.0' }}</li>
                    </ul>
                </div>
            </div>

            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <h2 class="h5">Need Help?</h2>
                    <p class="text-muted mb-3">Review the course details, then enroll when you are ready to start learning.</p>
                    <a href="{{ route('courses.index') }}" class="btn btn-outline-dark w-100">Browse More Courses</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
