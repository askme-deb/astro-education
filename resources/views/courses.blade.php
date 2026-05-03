@extends('layouts.app')

@section('title', 'Courses - Astrology Website')

@section('content')
<div class="container mt-4 inner_back">

    <div class="banner">

        <!-- Background Image -->
        <img src="{{ asset('assets/images/consult.png') }}" alt="Astrology Banner">

        <!-- Overlay -->
        <div class="banner-overlay">

            <div class="banner-content">
                <h1>
                    Upcoming Courses
                </h1>

                <button class="appointment-btn">
                    Enroll Now Now
                </button>
            </div>

        </div>

    </div>

</div>


<div class="container mb-5">

    <!-- <h2 class="mb-4 text-center">Our Courses</h2> -->

    <!-- FILTER SECTION -->
    <div class="filter-box mb-4">
        <div class="row g-3">

            <div class="col-md-3">
                {{-- id="searchInput" --}}
                <input type="text"  class="form-control" placeholder="Search Course..." autocomplete="off">
            </div>

            <div class="col-md-3">
                <select id="levelFilter" class="form-select">
                    <option value="">All Levels</option>
                    <option value="Beginner">Beginner</option>
                    <option value="Intermediate">Intermediate</option>
                    <option value="Advanced">Advanced</option>
                </select>
            </div>

            <div class="col-md-3">
                <select id="durationFilter" class="form-select">
                    <option value="">All Duration</option>
                    <option value="4">4 Weeks</option>
                    <option value="8">8 Weeks</option>
                    <option value="12">12 Weeks</option>
                </select>
            </div>

            <div class="col-md-3">
                <select id="priceSort" class="form-select">
                    <option value="">Sort By Price</option>
                    <option value="low">Low to High</option>
                    <option value="high">High to Low</option>
                </select>
            </div>

        </div>
    </div>

    <!-- COURSE GRID -->
    <div class="row g-4" id="courseContainer">

        <div class="col-md-8">
            <div class="row">
                <!-- COURSE 1 -->
                <div class="col-md-4 course-item" data-level="Beginner" data-duration="8" data-price="199">
                    <div class="course-card">
                        <div class="card h-100">
                            <img src="{{ asset('assets/images/course1.png') }}" class="card-img-top">
                            <div class="card-body">
                                <div class="d-flex justify-content-between mb-2">
                                    <span class="badge bg-warning text-dark">Beginner</span>
                                    <span class="text-muted"><i class="fas fa-clock"></i> 8 Weeks</span>
                                </div>
                                <h5>Diploma in Vedic Astrology</h5>
                                <p class="text-muted small">Master Houses, Rashis & Planet Movements.</p>
                                <div class="progress mb-3">
                                    <div class="progress-bar bg-warning" style="width:70%"></div>
                                </div>
                                <div class="d-flex justify-content-between align-items-center">
                                    <span class="price">Rs.199</span>
                                    <a href="courses_ditels.php" class="btn btn-dark btn-sm enrol_warp">Enroll Now</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- COURSE 2 -->
                <div class="col-md-4 course-item" data-level="Intermediate" data-duration="12" data-price="399">
                    <div class="course-card">
                        <div class="card h-100">
                            <img src="{{ asset('assets/images/course2.png') }}" class="card-img-top">
                            <div class="card-body">
                                <div class="d-flex justify-content-between mb-2">
                                    <span class="badge bg-primary">Intermediate</span>
                                    <span class="text-muted"><i class="fas fa-clock"></i> 12 Weeks</span>
                                </div>
                                <h5>Advanced Horoscope Reading</h5>
                                <p class="text-muted small">Deep analysis of planetary combinations.</p>
                                <div class="progress mb-3">
                                    <div class="progress-bar bg-primary" style="width:50%"></div>
                                </div>
                                <div class="d-flex justify-content-between align-items-center">
                                    <span class="price">Rs.399</span>
                                    <a href="courses_ditels.php" class="btn btn-dark btn-sm enrol_warp">Enroll Now</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- COURSE 3 -->
                <div class="col-md-4 course-item" data-level="Advanced" data-duration="4" data-price="599">
                    <div class="course-card">
                        <div class="card h-100">
                            <img src="{{ asset('assets/images/course3.png') }}" class="card-img-top">
                            <div class="card-body">
                                <div class="d-flex justify-content-between mb-2">
                                    <span class="badge bg-danger">Advanced</span>
                                    <span class="text-muted"><i class="fas fa-clock"></i> 4 Weeks</span>
                                </div>
                                <h5>Professional Astrologer Certification</h5>
                                <p class="text-muted small">Become a certified Vedic astrologer.</p>
                                <div class="progress mb-3">
                                    <div class="progress-bar bg-danger" style="width:30%"></div>
                                </div>
                                <div class="d-flex justify-content-between align-items-center">
                                    <span class="price">Rs.599</span>
                                    <a href="courses_ditels.php" class="btn btn-dark btn-sm enrol_warp">Enroll Now</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <!-- COURSE 1 -->
                <div class="col-md-4 course-item" data-level="Beginner" data-duration="8" data-price="199">
                    <div class="course-card">
                        <div class="card h-100">
                            <img src="{{ asset('assets/images/course1.png') }}" class="card-img-top">
                            <div class="card-body">
                                <div class="d-flex justify-content-between mb-2">
                                    <span class="badge bg-warning text-dark">Beginner</span>
                                    <span class="text-muted"><i class="fas fa-clock"></i> 8 Weeks</span>
                                </div>
                                <h5>Diploma in Vedic Astrology</h5>
                                <p class="text-muted small">Master Houses, Rashis & Planet Movements.</p>
                                <div class="progress mb-3">
                                    <div class="progress-bar bg-warning" style="width:70%"></div>
                                </div>
                                <div class="d-flex justify-content-between align-items-center">
                                    <span class="price">Rs.199</span>
                                    <a href="courses_ditels.php" class="btn btn-dark btn-sm enrol_warp">Enroll Now</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- COURSE 2 -->
                <div class="col-md-4 course-item" data-level="Intermediate" data-duration="12" data-price="399">
                    <div class="course-card">
                        <div class="card h-100">
                            <img src="{{ asset('assets/images/course2.png') }}" class="card-img-top">
                            <div class="card-body">
                                <div class="d-flex justify-content-between mb-2">
                                    <span class="badge bg-primary">Intermediate</span>
                                    <span class="text-muted"><i class="fas fa-clock"></i> 12 Weeks</span>
                                </div>
                                <h5>Advanced Horoscope Reading</h5>
                                <p class="text-muted small">Deep analysis of planetary combinations.</p>
                                <div class="progress mb-3">
                                    <div class="progress-bar bg-primary" style="width:50%"></div>
                                </div>
                                <div class="d-flex justify-content-between align-items-center">
                                    <span class="price">Rs.399</span>
                                    <a href="courses_ditels.php" class="btn btn-dark btn-sm enrol_warp">Enroll Now</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- COURSE 3 -->
                <div class="col-md-4 course-item" data-level="Advanced" data-duration="4" data-price="599">
                    <div class="course-card">
                        <div class="card h-100">
                            <img src="{{ asset('assets/images/course3.png') }}" class="card-img-top">
                            <div class="card-body">
                                <div class="d-flex justify-content-between mb-2">
                                    <span class="badge bg-danger">Advanced</span>
                                    <span class="text-muted"><i class="fas fa-clock"></i> 4 Weeks</span>
                                </div>
                                <h5>Professional Astrologer Certification</h5>
                                <p class="text-muted small">Become a certified Vedic astrologer.</p>
                                <div class="progress mb-3">
                                    <div class="progress-bar bg-danger" style="width:30%"></div>
                                </div>
                                <div class="d-flex justify-content-between align-items-center">
                                    <span class="price">Rs.599</span>
                                    <a href="courses_ditels.php" class="btn btn-dark btn-sm enrol_warp">Enroll Now</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <nav>
                <ul class="pagination justify-content-center">

                    <!-- Previous -->
                    <li class="page-item disabled">
                        <a class="page-link" href="#" tabindex="-1">Previous</a>
                    </li>

                    <!-- Page Numbers -->
                    <li class="page-item active">
                        <a class="page-link" href="#">1</a>
                    </li>

                    <li class="page-item">
                        <a class="page-link" href="#">2</a>
                    </li>

                    <li class="page-item">
                        <a class="page-link" href="#">3</a>
                    </li>

                    <li class="page-item">
                        <a class="page-link" href="#">4</a>
                    </li>

                    <li class="page-item">
                        <a class="page-link" href="#">5</a>
                    </li>

                    <!-- Next -->
                    <li class="page-item">
                        <a class="page-link" href="#">Next</a>
                    </li>

                </ul>
            </nav>

        </div>

        <div class="col-md-4">
            <div class="lesson-card">

                <h5>Next Lesson</h5>

                <p>Zodiac Signs &amp; Houses</p>

                <div class="progress mb-3">
                    <div class="progress-bar bg-warning" style="width:40%"></div>
                </div>

                <p>40% Completed</p>

                <button class="btn-main w-100">Continue Lesson</button>

            </div>
            <div class="sre_op">
                <div class="banner-content">
                    <h1>
                        New Courses
                    </h1>

                    <button class="appointment-btn">
                        Enroll Now Now
                    </button>
                </div>
                <img src="{{ asset('assets/images/bannti.png') }}" class="card-img-top">
            </div>
        </div>
    </div>
</div>

@endsection
