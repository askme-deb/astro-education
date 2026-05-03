@extends('layouts.app')

@section('title', 'Home - Astrology Website')

@section('content')
    <!-- HERO SECTION -->
    <section class="hero py-5 bg-light">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-6">
                    <h1>Learn Authentic Astrology with Raju Maharaj</h1>
                    <p>Master the science of astrology through structured courses, practical chart analysis, and guidance from experienced practitioners.</p>
                    <a href="#courses" class="btn btn-main me-2">Explore Courses</a>
                    <a href="#" class="btn btn-outline">Join Free Intro Class</a>
                </div>
            </div>
        </div>
    </section>

    <!-- ABOUT SECTION -->
    <section class="about py-5">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-6 mb-4 mb-lg-0">
                    <h2>About Our Astrology Academy</h2>
                    <p>Our astrology learning platform is designed to teach traditional astrology through structured modules. Under the guidance of Raju Maharaj, students learn chart interpretation, planetary influence and consultation techniques.</p>
                    <a href="#" class="btn btn-main">Enroll Now</a>
                </div>
                <div class="col-lg-6 text-center">
                    <img src="{{ asset('assets/images/about.png') }}" alt="About Astrology Academy" class="img-fluid rounded">
                </div>
            </div>
        </div>
    </section>

    <!-- WHAT YOU LEARN SECTION -->
    <section class="learn py-5 bg-light">
        <div class="container">
            <h2 class="mb-5 text-center">What You Will Learn</h2>
            <div class="row g-4">
                <div class="col-md-3">
                    <div class="learn-card text-center p-3 bg-white rounded shadow-sm">
                        <div class="learn-icon mb-2"><img src="{{ asset('assets/images/astrology.png') }}" alt="Astrology Foundations" height="48"></div>
                        <h5>Astrology Foundations</h5>
                        <p>Understand the core principles of astrology.</p>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="learn-card text-center p-3 bg-white rounded shadow-sm">
                        <div class="learn-icon mb-2"><img src="{{ asset('assets/images/birth-chart.png') }}" alt="Birth Chart Analysis" height="48"></div>
                        <h5>Birth Chart Analysis</h5>
                        <p>Learn how to read and interpret natal charts.</p>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="learn-card text-center p-3 bg-white rounded shadow-sm">
                        <div class="learn-icon mb-2"><img src="{{ asset('assets/images/planetary.png') }}" alt="Planetary Influence" height="48"></div>
                        <h5>Planetary Influence</h5>
                        <p>Discover how planets impact life events.</p>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="learn-card text-center p-3 bg-white rounded shadow-sm">
                        <div class="learn-icon mb-2"><img src="{{ asset('assets/images/predictive-analysis.png') }}" alt="Predictive Techniques" height="48"></div>
                        <h5>Predictive Techniques</h5>
                        <p>Advanced prediction techniques for astrology.</p>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="learn-card text-center p-3 bg-white rounded shadow-sm">
                        <div class="learn-icon mb-2"><img src="{{ asset('assets/images/communication-skills.png') }}" alt="Consultation Skills" height="48"></div>
                        <h5>Consultation Skills</h5>
                        <p>Develop ethical and effective consultation methods.</p>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="learn-card text-center p-3 bg-white rounded shadow-sm">
                        <div class="learn-icon mb-2"><img src="{{ asset('assets/images/file-case.png') }}" alt="Practical Case Studies" height="48"></div>
                        <h5>Practical Case Studies</h5>
                        <p>Real horoscope examples for deeper learning.</p>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="learn-card text-center p-3 bg-white rounded shadow-sm">
                        <div class="learn-icon mb-2"><img src="{{ asset('assets/images/file-case.png') }}" alt="Predictive Charts" height="48"></div>
                        <h5>Predictive Charts</h5>
                        <p>Learn predictive charts and scenarios.</p>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="learn-card text-center p-3 bg-white rounded shadow-sm">
                        <div class="learn-icon mb-2"><img src="{{ asset('assets/images/online-course.png') }}" alt="Course Quiz" height="48"></div>
                        <h5>Course Quiz</h5>
                        <p>Test your knowledge after each module.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- COURSES SECTION -->
    <section class="courses py-5" id="courses">
        <div class="container">
            <h2 class="mb-4">Our Astrology Courses</h2>
            <div class="course-banner p-4 bg-primary text-white rounded mb-4">
                <div class="course-banner-content">
                    <h3>Beginner Astrology</h3>
                    <p>Start your journey into astrology with structured beginner lessons and expert guidance.</p>
                    <a href="#" class="btn btn-light">Enroll Now</a>
                </div>
            </div>
            <div class="row g-4" id="courseContainer">
                <div class="col-md-3 course-item" data-level="Beginner" data-duration="8" data-price="199">
                    <div class="course-card card h-100">
                        <img src="{{ asset('assets/images/course1.png') }}" class="card-img-top" alt="Diploma in Vedic Astrology">
                        <div class="card-body">
                            <div class="d-flex justify-content-between mb-2">
                                <span class="badge bg-warning text-dark">Beginner</span>
                                <span class="text-muted"><i class="bi bi-alarm"></i> 8 Weeks</span>
                            </div>
                            <h5>Diploma in Vedic Astrology</h5>
                            <p class="text-muted small">Master Houses, Rashis &amp; Planet Movements.</p>
                            <div class="progress mb-3">
                                <div class="progress-bar bg-warning" style="width:70%"></div>
                            </div>
                            <div class="d-flex justify-content-between align-items-center">
                                <span class="price">Rs.199</span>
                                <a href="#" class="btn btn-dark btn-sm">Enroll Now</a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3 course-item" data-level="Intermediate" data-duration="12" data-price="399">
                    <div class="course-card card h-100">
                        <img src="{{ asset('assets/images/course2.png') }}" class="card-img-top" alt="Advanced Horoscope Reading">
                        <div class="card-body">
                            <div class="d-flex justify-content-between mb-2">
                                <span class="badge bg-primary">Intermediate</span>
                                <span class="text-muted"><i class="bi bi-alarm"></i> 12 Weeks</span>
                            </div>
                            <h5>Advanced Horoscope Reading</h5>
                            <p class="text-muted small">Deep analysis of planetary combinations.</p>
                            <div class="progress mb-3">
                                <div class="progress-bar bg-primary" style="width:50%"></div>
                            </div>
                            <div class="d-flex justify-content-between align-items-center">
                                <span class="price">Rs.399</span>
                                <a href="#" class="btn btn-dark btn-sm">Enroll Now</a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3 course-item" data-level="Advanced" data-duration="4" data-price="599">
                    <div class="course-card card h-100">
                        <img src="{{ asset('assets/images/course3.png') }}" class="card-img-top" alt="Professional Astrologer Certification">
                        <div class="card-body">
                            <div class="d-flex justify-content-between mb-2">
                                <span class="badge bg-danger">Advanced</span>
                                <span class="text-muted"><i class="bi bi-alarm"></i> 4 Weeks</span>
                            </div>
                            <h5>Professional Astrologer Certification</h5>
                            <p class="text-muted small">Become a certified Vedic astrologer.</p>
                            <div class="progress mb-3">
                                <div class="progress-bar bg-danger" style="width:30%"></div>
                            </div>
                            <div class="d-flex justify-content-between align-items-center">
                                <span class="price">Rs.599</span>
                                <a href="#" class="btn btn-dark btn-sm">Enroll Now</a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3 course-item" data-level="Intermediate" data-duration="12" data-price="399">
                    <div class="course-card card h-100">
                        <img src="{{ asset('assets/images/course2.png') }}" class="card-img-top" alt="Advanced Horoscope Reading">
                        <div class="card-body">
                            <div class="d-flex justify-content-between mb-2">
                                <span class="badge bg-primary">Intermediate</span>
                                <span class="text-muted"><i class="bi bi-alarm"></i> 12 Weeks</span>
                            </div>
                            <h5>Advanced Horoscope Reading</h5>
                            <p class="text-muted small">Deep analysis of planetary combinations.</p>
                            <div class="progress mb-3">
                                <div class="progress-bar bg-primary" style="width:50%"></div>
                            </div>
                            <div class="d-flex justify-content-between align-items-center">
                                <span class="price">Rs.399</span>
                                <a href="#" class="btn btn-dark btn-sm">Enroll Now</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- VIDEO SECTION -->
    <section class="astrology-section py-5 bg-light">
        <div class="container">
            <h2 class="astrology-title mb-4">Discover the Secrets of Your Destiny Through Astrology</h2>
            <div class="video-box position-relative text-center mb-4">
                <img src="{{ asset('assets/images/video_cover.png') }}" alt="Astrology Learning" class="img-fluid rounded">
                <button class="play-btn btn btn-light position-absolute top-50 start-50 translate-middle" data-bs-toggle="modal" data-bs-target="#videoModal" aria-label="Play Video">▶</button>
            </div>
            <!-- Video Modal -->
            <div class="modal fade" id="videoModal" tabindex="-1">
                <div class="modal-dialog modal-lg modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-body p-0">
                            <iframe width="100%" height="400" src="https://www.youtube.com/embed/5upr5JFO-_w?si=mKjpHEebrKfhB8_T" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" referrerpolicy="strict-origin-when-cross-origin" allowfullscreen></iframe>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- BLOG SECTION -->
    <section class="blog-section py-5">
        <div class="container">
            <h2 class="section-title mb-4">Our Blogs</h2>
            <div class="row g-4">
                <div class="col-md-4">
                    <div class="blog-card bg-white rounded shadow-sm h-100">
                        <div class="blog-image bg1 position-relative">
                            <span class="badge-cat position-absolute top-0 start-0 m-2">Astrology Basics</span>
                            <img src="{{ asset('assets/images/about.png') }}" class="card-img-top" alt="Understanding Zodiac Signs">
                        </div>
                        <div class="blog-content p-3">
                            <div class="blog-date text-muted small">Mar 4, 2024</div>
                            <div class="blog-title fw-bold">How Zodiac Signs Influence Your Personality</div>
                            <div class="blog-text">Learn how the twelve zodiac signs shape your personality, behavior, strengths, and emotional traits according to Vedic astrology.</div>
                            <a href="#" class="read-more">Read more →</a>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="blog-card bg-white rounded shadow-sm h-100">
                        <div class="blog-image bg2 position-relative">
                            <span class="badge-cat position-absolute top-0 start-0 m-2">Vedic Astrology</span>
                            <img src="{{ asset('assets/images/about.png') }}" class="card-img-top" alt="Planetary Effects">
                        </div>
                        <div class="blog-content p-3">
                            <div class="blog-date text-muted small">Feb 28, 2024</div>
                            <div class="blog-title fw-bold">How Planets Affect Your Life and Destiny</div>
                            <div class="blog-text">Discover how planetary movements such as Saturn, Jupiter, and Rahu influence career, relationships, and life events.</div>
                            <a href="#" class="read-more">Read more →</a>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="blog-card bg-white rounded shadow-sm h-100">
                        <div class="blog-image bg3 position-relative">
                            <span class="badge-cat position-absolute top-0 start-0 m-2">Horoscope Reading</span>
                            <img src="{{ asset('assets/images/about.png') }}" class="card-img-top" alt="Birth Chart Guide">
                        </div>
                        <div class="blog-content p-3">
                            <div class="blog-date text-muted small">Feb 12, 2024</div>
                            <div class="blog-title fw-bold">Beginner’s Guide to Reading Your Birth Chart</div>
                            <div class="blog-text">Understand the basics of Kundli reading and how astrologers interpret houses, planets, and zodiac signs.</div>
                            <a href="#" class="read-more">Read more →</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- AUTH MODAL -->
    <div class="modal fade" id="authModal" tabindex="-1">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <div class="row g-0">
                    <div class="col-md-12 p-4">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h4>Welcome to Our Website</h4>
                            <button class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="d-flex justify-content-around mb-3">
                            <div class="auth-tab active" onclick="showTab('login')">Login</div>
                            <div class="auth-tab" onclick="showTab('register')">Register</div>
                        </div>
                        <div id="loginBox">
                            <div class="mb-2">
                                <label>Mobile Number</label>
                                <input type="tel" class="form-control">
                            </div>
                            <div class="mb-3">
                                <label>Password</label>
                                <div class="input-group">
                                    <input type="password" class="form-control" id="loginPassword">
                                    <button class="btn btn-outline-secondary" onclick="togglePassword('loginPassword')">
                                        <i class="bi bi-eye"></i>
                                    </button>
                                </div>
                            </div>
                            <div class="but_warp">
                                <button class="btn btn-primary w-100 mb-2">Login</button>
                                <div class="text-center my-2">OR</div>
                                <button class="btn btn-success w-100 mb-2" onclick="sendOTP()">Login with OTP</button>
                            </div>
                            <div class="otp-box mt-2" id="otpSection" style="display:none;">
                                <input type="text" class="form-control mb-2" placeholder="Enter OTP">
                                <button class="btn btn-success w-100">Verify OTP</button>
                            </div>
                            <hr>
                            <div class="soceal_login">
                                <button class="btn btn-google w-100 mb-2"><i class="bi bi-google"></i> Continue with Google</button>
                                <button class="btn btn-facebook w-100"><i class="bi bi-facebook"></i> Continue with Facebook</button>
                            </div>
                        </div>
                        <div id="registerBox" style="display:none">
                            <div class="mb-2">
                                <label>Full Name</label>
                                <input type="text" class="form-control">
                            </div>
                            <div class="mb-2">
                                <label>Mobile Number</label>
                                <input type="tel" class="form-control">
                            </div>
                            <div class="mb-2">
                                <label>Email</label>
                                <input type="email" class="form-control">
                            </div>
                            <div class="mb-2">
                                <label>Password</label>
                                <div class="input-group">
                                    <input type="password" class="form-control" id="regPassword">
                                    <button class="btn btn-outline-secondary" onclick="togglePassword('regPassword')">
                                        <i class="bi bi-eye"></i>
                                    </button>
                                </div>
                            </div>
                            <button class="btn btn-primary w-100">Register</button>
                            <hr>
                            <div class="soceal_login">
                                <button class="btn btn-google w-100 mb-2"><i class="bi bi-google"></i> Continue with Google</button>
                                <button class="btn btn-facebook w-100"><i class="bi bi-facebook"></i> Continue with Facebook</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
