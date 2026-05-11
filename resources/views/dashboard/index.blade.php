@extends('layouts.app')

@section('title', 'Student Dashboard')

@section('content')
    <section class="py-5">
        <div class="container">
            <div class="d-flex align-items-center justify-content-between mb-4">
                <div>
                    <h1 class="h3 mb-1">Student Dashboard</h1>
                    <p class="text-muted mb-0">Your LMS summary will render here from the API layer.</p>
                </div>
                <a href="{{ route('courses.index') }}" class="btn btn-primary">Browse Courses</a>
            </div>

            <div class="row g-4">
                <div class="col-12 col-lg-6">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-body">
                            <h2 class="h5">Overview</h2>
                            <pre class="small bg-light rounded p-3 mb-0">{{ json_encode($dashboard, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) }}</pre>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
