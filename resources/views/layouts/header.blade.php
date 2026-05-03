<div class="top-header">
    <div class="container">
        <div class="row">
            <div class="col-md-6">
                <div class="top-bar">
                    <i class="bi bi-telephone-forward"></i> +91 1236548790 | <i class="bi bi-envelope-at"></i>
                    info@test.com
                </div>
            </div>

            <div class="col-md-6">
                <div class="account_warp">
                    <!-- <button class="btn btn-light btn-sm">Eng</button> -->
                    <i class="bi bi-bell fs-5"></i>
                    <button class="sign-btnk" data-bs-toggle="modal" data-bs-target="#authModal"><i
                            class="bi bi-person-circle"></i> Account</button>
                </div>
            </div>
        </div>
    </div>
    <!-- Talk -->
</div>


<!-- Navbar -->
<nav class="navbar navbar-expand-lg navbar-dark">
    <div class="container">

        <a class="navbar-brand" href="https://astrorajumaharaj.com"> <img src="{{ asset('assets/images/Logo.png') }}" alt="Logo"></a>

        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navMenu">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navMenu">

            <ul class="navbar-nav ms-auto">
                <li class="nav-item"><a class="nav-link" href="{{ url('/') }}">Home</a></li>
                <li class="nav-item"><a class="nav-link" href="{{ url('/about') }}">About Us</a></li>
                <!-- <li class="nav-item"><a class="nav-link" href="#">Services</a></li> -->
                <li class="nav-item"><a class="nav-link" href="{{ url('/courses') }}">Courses</a></li>
                <li class="nav-item"><a class="nav-link" href="{{ url('/contact') }}">Contact</a></li>
                <li class="nav-item">
                    <a href="https://jyotish.astrorajumaharaj.com" class="btn btn-primary header-btn">Book Consultation</a>
                </li>
            </ul>
            <!-- <a href="consultation.php" class="btn btn-astro ms-3">Book Consultation</a> -->
        </div>
    </div>
</nav>
