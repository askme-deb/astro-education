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
                    {{-- <i class="bi bi-bell fs-5"></i> --}}
                    @if(session()->has('auth.api_token'))
                        <div class="dropdown account-dropdown-shell">
                            <button class="sign-btnk dropdown-toggle account-dropdown-toggle" id="header-account-toggle" type="button" data-bs-toggle="dropdown" data-bs-display="static" aria-expanded="false">
                                <i class="bi bi-person-circle"></i>
                                <span>My Account</span>
                            </button>
                            <div class="dropdown-menu dropdown-menu-end account-dropdown-menu border-0 shadow-sm" aria-labelledby="header-account-toggle">
                                <a class="dropdown-item account-dropdown-item" href="{{ route('dashboard') }}">
                                    <i class="bi bi-grid-1x2-fill"></i>
                                    Dashboard
                                </a>
                                <a class="dropdown-item account-dropdown-item" href="{{ route('student.dashboard') }}">
                                    <i class="bi bi-mortarboard-fill"></i>
                                    Student Area
                                </a>
                                <a class="dropdown-item account-dropdown-item" href="{{ route('live-classes.index') }}">
                                    <i class="bi bi-broadcast-pin"></i>
                                    Live Classes
                                </a>
                                <div class="dropdown-divider account-dropdown-divider"></div>
                                <button type="button" class="dropdown-item account-dropdown-item account-dropdown-logout" id="header-logout-trigger">
                                    <i class="bi bi-box-arrow-right"></i>
                                    Logout
                                </button>
                            </div>
                        </div>

                        <form id="header-logout-form" method="POST" action="{{ route('logout') }}" class="d-none">
                            @csrf
                        </form>
                    @else
                        <button class="sign-btnk" data-bs-toggle="modal" data-bs-target="#authModal"><i
                                class="bi bi-person-circle"></i> Login / Register</button>
                    @endif
                </div>
            </div>
        </div>
    </div>
    <!-- Talk -->
</div>

@push('styles')
<style>
    .auth-modal .modal-dialog {
        max-width: 760px;
    }

    .auth-modal .modal-content {
        border: 0;
        border-radius: 1.15rem;
        overflow: hidden;
        box-shadow: 0 20px 46px rgba(15, 23, 42, 0.16);
        background: #ffffff;
    }

    .auth-side-panel {
        min-height: 100%;
        background: linear-gradient(135deg, #ff9800 0%, #ffb74d 100%);
    }

    .auth-logo-shell {
        width: 112px;
        height: 112px;
        margin: 0 auto 0.85rem;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 0.9rem;
        background: rgba(255, 248, 236, 0.88);
        box-shadow: inset 0 1px 0 rgba(255, 255, 255, 0.4);
    }

    .auth-logo-shell img {
        max-width: 78px;
        display: block;
    }

    .auth-side-panel h3 {
        margin-bottom: 0.45rem;
        font-size: 1.7rem;
        font-weight: 700;
        color: #fff;
    }

    .auth-side-panel p {
        max-width: 220px;
        margin: 0 auto;
        color: rgba(255, 255, 255, 0.95);
        font-size: 0.9rem;
        line-height: 1.45;
    }

    .auth-stars {
        color: #fff;
        opacity: 0.95;
    }

    .auth-panel-right {
        padding: 1.25rem 1.1rem !important;
        background: #ffffff;
        color: #ff9800;
    }

    .auth-tabs {
        gap: 0.45rem;
    }

    .auth-tabs .nav-item {
        flex: 1 1 0;
    }

    .auth-tab {
        width: 100%;
        min-height: 2.4rem;
        border: 1px solid #ff9800;
        border-radius: 0.35rem;
        background: #ffffff !important;
        color: #000000 !important;
        justify-content: center;
        font-weight: 600;
        font-size: 0.95rem;
        transition: all 0.18s ease;
    }

    .auth-modal .nav-pills .nav-link.auth-tab,
    #login-tab,
    #register-tab {
        color: #000000 !important;
        background-color: #ffffff !important;
        border-color: #ff9800 !important;
    }

    .auth-modal .nav-pills .nav-link.auth-tab.active,
    .auth-modal .nav-pills .show > .nav-link.auth-tab,
    #login-tab.active,
    #register-tab.active {
        color: #ffffff !important;
        background-color: #ff9800 !important;
        border-color: #ff9800 !important;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08) !important;
    }
    .auth-modal .form-label {
        color: #ff9800;
        font-weight: 500;
        margin-bottom: 0.35rem;
        font-size: 0.92rem;
    }

    .auth-modal h4 {
        font-size: 1.6rem;
    }

    .auth-modal h4,
    .auth-modal .text-muted,
    .auth-modal .btn-link,
    .auth-modal .invalid-feedback {
        color: #ff9800 !important;
    }

    .auth-modal .form-control,
    .auth-modal .input-group-text,
    .auth-modal .btn,
    .auth-modal .btn-outline-secondary {
        border-radius: 0.45rem;
    }

    .auth-modal .form-control,
    .auth-modal .input-group-text {
        min-height: 40px;
        border-color: #bdbdbd;
        box-shadow: none;
        font-size: 0.95rem;
    }

    .auth-modal .form-control:focus,
    .auth-modal .input-group-text:focus-within {
        border-color: #ff9800;
        box-shadow: 0 0 0 0.18rem rgba(255, 152, 0, 0.22);
    }

    .auth-modal .btn-primary,
    .auth-modal .btn-warning {
        border: 1px solid #ff9800;
        background: #ff9800;
        color: #ffffff;
        min-height: 42px;
        font-size: 0.95rem;
        padding-top: 0.45rem;
        padding-bottom: 0.45rem;
    }

    .auth-modal .btn-primary:hover,
    .auth-modal .btn-warning:hover {
        background: #ff9800;
        border-color: #ff9800;
        color: #ffffff;
    }

    .auth-modal .btn-outline-secondary {
        border-color: #ff9800;
        color: #ff9800;
        background: #fff;
        min-width: 44px;
    }

    .auth-modal .otp-box {
        padding-top: 0.15rem;
    }

    .auth-modal .header-otp-digit {
        text-align: center;
        font-size: 1.25rem;
        font-weight: 700;
    }

    .auth-modal .alert {
        border-radius: 0.45rem;
    }

    .auth-modal .btn-google,
    .auth-modal .btn-facebook {
        border: 1px solid #e5e7eb;
        background: #fff;
    }

    .auth-modal .text-link-orange {
        color: #ff9800 !important;
    }

    .account-dropdown-shell {
        display: inline-block;
        position: relative;
        z-index: 40;
    }

    .top-header,
    .account_warp {
        overflow: visible;
    }

    .account-dropdown-toggle {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        position: relative;
        z-index: 41;
    }

    .account-dropdown-toggle::after {
        margin-left: 0.15rem;
        vertical-align: middle;
    }

    .account-dropdown-menu {
        min-width: 210px;
        padding: 10px;
        margin-top: 12px !important;
        border-radius: 18px;
        background: rgba(255, 255, 255, 0.98);
        border: 1px solid rgba(245, 124, 0, 0.14) !important;
        box-shadow: 0 18px 38px rgba(95, 54, 9, 0.14) !important;
        z-index: 1055;
    }

    .account-dropdown-menu.show {
        display: block;
    }

    .account-dropdown-item {
        display: flex;
        align-items: center;
        gap: 10px;
        padding: 10px 12px;
        border-radius: 12px;
        color: #5e4023;
        font-weight: 600;
        transition: background 0.18s ease, color 0.18s ease;
    }

    .account-dropdown-item i {
        color: #f57c00;
        font-size: 0.95rem;
    }

    .account-dropdown-item:hover,
    .account-dropdown-item:focus {
        background: rgba(255, 152, 0, 0.1);
        color: #b45f00;
    }

    .account-dropdown-divider {
        margin: 8px 4px;
        border-top-color: rgba(15, 23, 42, 0.08);
    }

    .account-dropdown-logout {
        background: transparent;
        border: 0;
        width: 100%;
    }

    @media (max-width: 767px) {
        .auth-modal .modal-dialog {
            margin: 12px;
        }

        .auth-panel-right {
            padding: 1rem !important;
        }

        .auth-tabs {
            gap: 0.35rem;
        }

        .auth-tab {
            font-size: 0.88rem;
            padding-inline: 0.45rem;
        }

        .account-dropdown-menu {
            min-width: 190px;
        }
    }
</style>
@endpush
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


<div class="modal fade auth-modal" id="authModal" tabindex="-1" data-bs-backdrop="static" aria-labelledby="authModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg rounded-4 overflow-hidden">
            <div class="row g-0">
               <div class="col-md-5 d-none d-md-flex align-items-center justify-content-center text-white p-4" style="background: linear-gradient(135deg, #ff9800 0%, #ffb74d 100%);">
                    <div class="text-center w-100">
                        <div style="background:rgba(255,255,255,0.85);border-radius:1rem;display:inline-block;padding:0.5rem 1rem;box-shadow:0 2px 8px rgba(0,0,0,0.08);">
                            <img src="{{ asset('assets/images/Logo.png') }}" alt="Logo" class="mb-4 animate__animated animate__fadeInDown" style="max-width:100px;display:block;">
                        </div>
                        <h3 class="fw-bold mb-2 animate__animated animate__fadeInUp">Welcome!</h3>
                        <p class="mb-0 animate__animated animate__fadeInUp animate__delay-1s">Sign in or create an account to access personalized astrology services.</p>
                        <div class="mt-4 animate__animated animate__fadeIn animate__delay-2s">
                            <i class="bi bi-stars" style="font-size:2rem;"></i>
                        </div>
                    </div>
                </div>
                <div class="col-md-7 col-12 p-4 bg-white auth-panel-right">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h4 class="fw-semibold mb-0" id="authModalLabel">Account Access</h4>
                        <button class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <ul class="nav nav-pills nav-justified mb-4 auth-tabs" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link auth-tab active fw-semibold d-flex align-items-center gap-2" id="login-tab" type="button" role="tab" aria-selected="true" onclick="showTab('login')">
                                <i class="bi bi-box-arrow-in-right"></i> Login
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link auth-tab fw-semibold d-flex align-items-center gap-2" id="register-tab" type="button" role="tab" aria-selected="false" onclick="showTab('register')">
                                <i class="bi bi-person-plus"></i> Register
                            </button>
                        </li>
                    </ul>
                    <div id="loginBox">
                        <div id="loginFields">
                            <div class="mb-3">
                                <label class="form-label">Email</label>
                                <input type="email" class="form-control" id="header-login-email" placeholder="Enter your email" autocomplete="username">
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Password</label>
                                <div class="input-group">
                                    <input type="password" class="form-control" id="header-login-password" placeholder="Enter your password" autocomplete="current-password">
                                    <button class="btn btn-outline-secondary" type="button" onclick="togglePassword('header-login-password')" aria-label="Show or hide password">
                                        <i class="bi bi-eye"></i>
                                    </button>
                                </div>
                                <div class="text-end mt-1">
                                    <a href="#" class="small text-decoration-none text-link-orange" onclick="showForgotForm(event)" style="color: #ff9800;">Forgot Password?</a>
                                </div>
                            </div>
                            <div id="header-login-error" class="alert alert-danger mt-2 d-none"></div>
                        </div>
                        <form id="forgotForm" class="flex-column gap-2 mt-2" style="max-width: 100%; display:none;">
                            <input type="email" class="form-control mb-2" id="header-forgot-email" placeholder="Enter your email for reset" style="font-size:0.95rem;">
                            <button type="button" class="btn w-100" id="header-forgot-submit" style="border:1px solid #ff9800;color:#ff9800;background:#fff;">Send Reset Link</button>
                            <button type="button" class="btn btn-link w-100 text-link-orange" onclick="hideForgotForm(event)" style="color:#ff9800;">Back to Login</button>
                        </form>
                        <div id="loginButtons">
                            <button class="btn btn-primary w-100 mb-2" type="button" id="header-login-submit" style="border:1px solid #ff9800;color:#fff;background:#ff9800;">Login</button>
                            <div class="text-center my-2 text-muted">OR</div>
                            <button class="btn w-100 mb-2" type="button" onclick="showOtpLogin()" style="border:1px solid #ff9800;color:#ff9800;background:#fff;">Login with OTP</button>
                        </div>
                        <div class="otp-box mt-2" id="otpSection" style="display:none">
                            <div id="header-otp-alert" class="alert d-none mb-2" role="alert"></div>
                            <div id="header-otp-step-mobile">
                                <label class="form-label">Mobile Number</label>
                                <div class="input-group mb-2">
                                    <span class="input-group-text" style="border-color: #ff9800; color:#ff9800;">+91</span>
                                    <input type="tel" class="form-control" id="header-otp-mobile" style="border-color: #ff9800; color:#ff9800;" maxlength="15" placeholder="Enter your mobile number" autocomplete="tel">
                                </div>
                                <button class="btn btn-warning w-100 mb-2" id="header-otp-send-btn" type="button" style="background: #ff9800; border-color:#ff9800; color: #ffffff;">Send OTP</button>
                            </div>
                            <div id="header-otp-step-verify" style="display:none;">
                                <label class="form-label fw-semibold text-link-orange">Enter OTP</label>
                                <div class="d-flex align-items-center mb-3 gap-2">
                                    <input type="tel" class="form-control border border-warning" id="header-otp-mobile-readonly" readonly style="width:100%; font-weight:500; color:#ff9800; background:#fffbe6; border-color:#ff9800 !important;">
                                    <a href="#" id="header-otp-change-mobile" style="color:#ff9800; font-size:0.97rem; text-decoration:underline; cursor:pointer;">Change</a>
                                </div>
                                <div class="d-flex gap-2 justify-content-center mb-3">
                                    <input type="text" class="form-control text-center header-otp-digit border-2 border-warning" maxlength="1" style="width:2.5rem; font-size:1.5rem; background:#fffbe6; color:#ff9800; box-shadow:none; border-color:#ff9800 !important;" autocomplete="one-time-code">
                                    <input type="text" class="form-control text-center header-otp-digit border-2 border-warning" maxlength="1" style="width:2.5rem; font-size:1.5rem; background:#fffbe6; color:#ff9800; box-shadow:none; border-color:#ff9800 !important;" autocomplete="one-time-code">
                                    <input type="text" class="form-control text-center header-otp-digit border-2 border-warning" maxlength="1" style="width:2.5rem; font-size:1.5rem; background:#fffbe6; color:#ff9800; box-shadow:none; border-color:#ff9800 !important;" autocomplete="one-time-code">
                                    <input type="text" class="form-control text-center header-otp-digit border-2 border-warning" maxlength="1" style="width:2.5rem; font-size:1.5rem; background:#fffbe6; color:#ff9800; box-shadow:none; border-color:#ff9800 !important;" autocomplete="one-time-code">
                                </div>
                                <div class="d-flex align-items-center justify-content-between mb-2">
                                    <button class="btn btn-warning flex-grow-1 me-2 fw-semibold px-4 py-2" id="header-otp-verify-btn" type="button" style="background: #ff9800; border-color:#ff9800; color: #ffffff;">Verify OTP</button>
                                    <button class="btn btn-link p-0 fw-semibold text-link-orange" id="header-otp-resend-btn" type="button">Resend</button>
                                    <span id="header-otp-resend-timer" style="display:none; margin-left:0.5rem; color:#888; font-size:0.95rem;"></span>
                                </div>
                            </div>
                            <button class="btn btn-link w-100 mt-2 text-link-orange" type="button" onclick="showNormalLogin()" style="color:#ff9800;">Back to Password Login</button>
                        </div>
                    </div>
                    <div id="registerBox" style="display:none">
                        <form id="registerForm">
                            <div class="mb-3">
                                <label class="form-label">First Name</label>
                                <input type="text" class="form-control" id="regFirstName" name="first_name" placeholder="Enter your first name" required autocomplete="given-name">
                                <div class="invalid-feedback" id="regFirstNameError"></div>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Last Name</label>
                                <input type="text" class="form-control" id="regLastName" name="last_name" placeholder="Enter your last name" required autocomplete="family-name">
                                <div class="invalid-feedback" id="regLastNameError"></div>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Mobile Number</label>
                                <input type="tel" class="form-control" id="regMobile" name="mobile_no" placeholder="Enter your mobile number" required autocomplete="tel">
                                <div class="invalid-feedback" id="regMobileError"></div>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Email</label>
                                <input type="email" class="form-control" id="regEmail" name="email" placeholder="Enter your email" required autocomplete="email">
                                <div class="invalid-feedback" id="regEmailError"></div>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Password</label>
                                <div class="input-group">
                                    <input type="password" class="form-control" id="regPassword" name="password" placeholder="Create a password" required autocomplete="new-password">
                                    <button class="btn btn-outline-secondary" type="button" onclick="togglePassword('regPassword')" aria-label="Show or hide password">
                                        <i class="bi bi-eye"></i>
                                    </button>
                                </div>
                                <div class="invalid-feedback" id="regPasswordError"></div>
                            </div>
                            <div id="register-error" class="alert alert-danger mt-2 d-none"></div>
                            <div id="register-success" class="alert alert-success mt-2 d-none"></div>
                            <button type="submit" class="btn btn-primary w-100" style="border:1px solid #ff9800;color:#fff;background:#ff9800;">Register</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@push('scripts')
   <script>
        // Clear auth modal state when modal is closed
        var authModalEl = document.getElementById('authModal');
        if (authModalEl) {
            authModalEl.addEventListener('hidden.bs.modal', function () {
                var otpInputs = document.querySelectorAll('.header-otp-digit');
                otpInputs.forEach(function(input) {
                    input.value = '';
                });
                if (typeof window.showTab === 'function') window.showTab('login');
                if (typeof window.showNormalLogin === 'function') window.showNormalLogin();
                var mobileStep = document.getElementById('header-otp-step-mobile');
                var verifyStep = document.getElementById('header-otp-step-verify');
                var otpAlert = document.getElementById('header-otp-alert');
                var mobileField = document.getElementById('header-otp-mobile');
                var loginError = document.getElementById('header-login-error');
                var registerError = document.getElementById('register-error');
                var registerSuccess = document.getElementById('register-success');

                if (mobileStep) mobileStep.style.display = 'block';
                if (verifyStep) verifyStep.style.display = 'none';
                if (otpAlert) otpAlert.classList.add('d-none');
                if (mobileField) mobileField.value = '';
                if (loginError) {
                    loginError.classList.add('d-none');
                    loginError.textContent = '';
                }
                if (registerError) {
                    registerError.classList.add('d-none');
                    registerError.textContent = '';
                }
                if (registerSuccess) {
                    registerSuccess.classList.add('d-none');
                    registerSuccess.textContent = '';
                }
            });
        }

    document.addEventListener('DOMContentLoaded', function() {
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

        // Delivery Pincode popup logic
        const pincodeTrigger = document.getElementById('openPincodePopup');
        const pincodeModal = document.getElementById('pincodeModal');
        const pincodeInput = document.getElementById('pincodeInput');
        const pincodeCloseBtn = pincodeModal ? pincodeModal.querySelector('.pincode-close') : null;
        const pincodeSubmitBtn = pincodeModal ? pincodeModal.querySelector('.pincode-submit') : null;
        const updateDelText = document.getElementById('update-del-text');

        function openPincodeModal() {
            if (!pincodeModal) return;
            pincodeModal.style.display = 'flex';
        }

        function closePincodeModal() {
            if (!pincodeModal) return;
            pincodeModal.style.display = 'none';
        }

        // Load saved pincode from localStorage on page load
        try {
            const savedPincode = window.localStorage.getItem('delivery_pincode');
            if (savedPincode && updateDelText) {
                updateDelText.textContent = 'Deliver to ' + savedPincode;
            }
            if (savedPincode && pincodeInput) {
                pincodeInput.value = savedPincode;
            }
        } catch (e) {
            // localStorage may be unavailable; fail silently
        }

        if (pincodeTrigger && pincodeModal) {
            pincodeTrigger.addEventListener('click', function () {
                openPincodeModal();
            });
        }

        if (pincodeCloseBtn) {
            pincodeCloseBtn.addEventListener('click', function () {
                closePincodeModal();
            });
        }

        // Close when clicking outside the modal content
        if (pincodeModal) {
            pincodeModal.addEventListener('click', function (event) {
                if (event.target === pincodeModal) {
                    closePincodeModal();
                }
            });
        }

        if (pincodeSubmitBtn && pincodeInput) {
            pincodeSubmitBtn.addEventListener('click', function () {
                const raw = (pincodeInput.value || '').trim();

                // Basic validation: 6-digit numeric pincode
                if (!/^\d{6}$/.test(raw)) {
                    alert('Please enter a valid 6-digit pincode.');
                    return;
                }

                try {
                    window.localStorage.setItem('delivery_pincode', raw);
                } catch (e) {
                    // Ignore storage errors
                }

                if (updateDelText) {
                    updateDelText.textContent = 'Deliver to ' + raw;
                }

                closePincodeModal();
            });
        }

        const logoutTrigger = document.getElementById('header-logout-trigger');
        const accountToggle = document.getElementById('header-account-toggle');
        const accountMenu = document.querySelector('.account-dropdown-menu');

        if (accountToggle && accountMenu && typeof bootstrap !== 'undefined' && bootstrap.Dropdown) {
            const accountDropdown = bootstrap.Dropdown.getOrCreateInstance(accountToggle, {
                autoClose: true,
                popperConfig: function(defaultConfig) {
                    return {
                        ...defaultConfig,
                        strategy: 'fixed',
                    };
                },
            });

            accountToggle.addEventListener('click', function(event) {
                event.preventDefault();
                accountDropdown.toggle();
            });
        }

        if (logoutTrigger) {
            logoutTrigger.addEventListener('click', function() {
                const form = document.getElementById('header-logout-form');
                if (!form) return;

                fetch(form.action, {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': csrfToken,
                            'Accept': 'application/json',
                        },
                        credentials: 'include',
                    })
                    .then(() => window.location.reload());
            });
        }

        const alertBox = document.getElementById('header-otp-alert');
        const stepMobile = document.getElementById('header-otp-step-mobile');
        const stepVerify = document.getElementById('header-otp-step-verify');
        const mobileInput = document.getElementById('header-otp-mobile');
        const mobileReadonly = document.getElementById('header-otp-mobile-readonly');
        const otpInputs = Array.from(document.querySelectorAll('.header-otp-digit'));
        const sendBtn = document.getElementById('header-otp-send-btn');
        const verifyBtn = document.getElementById('header-otp-verify-btn');
        const changeMobileBtn = document.getElementById('header-otp-change-mobile');
        const resendBtn = document.getElementById('header-otp-resend-btn');
        const resendTimer = document.getElementById('header-otp-resend-timer');
        const loginBox = document.getElementById('loginBox');
        const registerBox = document.getElementById('registerBox');
        const loginTab = document.getElementById('login-tab');
        const registerTab = document.getElementById('register-tab');
        const loginFields = document.getElementById('loginFields');
        const loginButtons = document.getElementById('loginButtons');
        const otpSection = document.getElementById('otpSection');
        const forgotForm = document.getElementById('forgotForm');
        const loginError = document.getElementById('header-login-error');
        const loginSubmit = document.getElementById('header-login-submit');
        const registerForm = document.getElementById('registerForm');
        const registerError = document.getElementById('register-error');
        const registerSuccess = document.getElementById('register-success');
        const forgotSubmit = document.getElementById('header-forgot-submit');

        let headerResendCountdown = null;

        function resetRegisterMessages() {
            if (registerError) {
                registerError.classList.add('d-none');
                registerError.textContent = '';
            }
            if (registerSuccess) {
                registerSuccess.classList.add('d-none');
                registerSuccess.textContent = '';
            }
        }

        function showInlineMessage(element, message) {
            if (!element) return;
            element.textContent = message;
            element.classList.remove('d-none');
        }

        function clearRegisterFieldErrors() {
            [
                ['regFirstName', 'regFirstNameError'],
                ['regLastName', 'regLastNameError'],
                ['regMobile', 'regMobileError'],
                ['regEmail', 'regEmailError'],
                ['regPassword', 'regPasswordError'],
            ].forEach(function(field) {
                var input = document.getElementById(field[0]);
                var error = document.getElementById(field[1]);
                if (input) input.classList.remove('is-invalid');
                if (error) error.textContent = '';
            });
        }

        function showHeaderAlert(message, type = 'info') {
            if (!alertBox) return;
            alertBox.classList.remove('d-none', 'alert-info', 'alert-danger', 'alert-success');
            alertBox.classList.add('alert-' + type);
            alertBox.textContent = message;
        }

        function clearHeaderAlert() {
            if (!alertBox) return;
            alertBox.classList.add('d-none');
            alertBox.textContent = '';
        }

        function setHeaderLoading(button, isLoading, loadingText) {
            if (!button) return;
            button.disabled = isLoading;
            if (isLoading) {
                button.dataset.originalText = button.innerText;
                button.innerText = loadingText || 'Please wait...';
            } else if (button.dataset.originalText) {
                button.innerText = button.dataset.originalText;
            }
        }

        function startHeaderResendCountdown(seconds) {
            if (!resendTimer || !resendBtn) return;
            let remaining = seconds;
            resendTimer.style.display = 'inline';
            resendBtn.style.pointerEvents = 'none';
            resendBtn.style.opacity = '0.5';
            resendTimer.textContent = '(' + remaining + 's)';

            if (headerResendCountdown) clearInterval(headerResendCountdown);
            headerResendCountdown = setInterval(function() {
                remaining -= 1;
                if (remaining <= 0) {
                    clearInterval(headerResendCountdown);
                    resendTimer.style.display = 'none';
                    resendBtn.style.pointerEvents = 'auto';
                    resendBtn.style.opacity = '1';
                } else {
                    resendTimer.textContent = '(' + remaining + 's)';
                }
            }, 1000);
        }

        function headerPostJson(url, payload, onSuccess) {
            clearHeaderAlert();
            fetch(url, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json',
                    },
                    credentials: 'include',
                    body: JSON.stringify(payload),
                })
                .then(async (response) => {
                    const data = await response.json().catch(() => ({
                        success: false,
                        message: 'Unexpected server response.'
                    }));

                    if (!response.ok || data.success === false) {
                        const message = data.message || 'Unable to process request.';
                        showHeaderAlert(message, 'danger');
                        return;
                    }

                    onSuccess(data);
                })
                .catch(() => {
                    showHeaderAlert('Unable to reach authentication service. Please try again.', 'danger');
                });
        }

        function getHeaderOtp() {
            if (!otpInputs.length) return '';
            return otpInputs.map(function(input) {
                return (input.value || '').trim();
            }).join('');
        }

        function clearHeaderOtp() {
            otpInputs.forEach(function(input) {
                input.value = '';
            });
            if (otpInputs[0]) {
                otpInputs[0].focus();
            }
        }

        window.togglePassword = function(fieldId) {
            const field = document.getElementById(fieldId);
            if (!field) return;
            field.type = field.type === 'password' ? 'text' : 'password';
        };

        window.showNormalLogin = function() {
            if (loginFields) loginFields.style.display = 'block';
            if (loginButtons) loginButtons.style.display = 'block';
            if (forgotForm) forgotForm.style.display = 'none';
            if (otpSection) otpSection.style.display = 'none';
            if (stepMobile) stepMobile.style.display = 'block';
            if (stepVerify) stepVerify.style.display = 'none';
            if (mobileInput) mobileInput.value = '';
            if (mobileReadonly) mobileReadonly.value = '';
            clearHeaderAlert();
            clearHeaderOtp();
            if (loginError) {
                loginError.classList.add('d-none');
                loginError.textContent = '';
            }
        };

        window.showOtpLogin = function() {
            if (loginFields) loginFields.style.display = 'none';
            if (loginButtons) loginButtons.style.display = 'none';
            if (forgotForm) forgotForm.style.display = 'none';
            if (otpSection) otpSection.style.display = 'block';
            if (stepMobile) stepMobile.style.display = 'block';
            if (stepVerify) stepVerify.style.display = 'none';
            if (mobileInput) mobileInput.value = '';
            if (mobileReadonly) mobileReadonly.value = '';
            clearHeaderAlert();
            clearHeaderOtp();
            if (loginError) {
                loginError.classList.add('d-none');
                loginError.textContent = '';
            }
        };

        window.showForgotForm = function(event) {
            if (event) event.preventDefault();
            if (forgotForm) forgotForm.style.display = 'flex';
            if (loginFields) loginFields.style.display = 'none';
            if (loginButtons) loginButtons.style.display = 'none';
            if (otpSection) otpSection.style.display = 'none';
            clearHeaderAlert();
            if (loginError) {
                loginError.classList.add('d-none');
                loginError.textContent = '';
            }
        };

        window.hideForgotForm = function(event) {
            if (event) event.preventDefault();
            window.showNormalLogin();
        };

        window.showTab = function(tab) {
            var isRegister = tab === 'register';

            if (loginBox) loginBox.style.display = isRegister ? 'none' : 'block';
            if (registerBox) registerBox.style.display = isRegister ? 'block' : 'none';

            if (!isRegister) {
                window.showNormalLogin();
            }

            if (loginTab) {
                loginTab.classList.toggle('active', !isRegister);
                loginTab.setAttribute('aria-selected', isRegister ? 'false' : 'true');
            }

            if (registerTab) {
                registerTab.classList.toggle('active', isRegister);
                registerTab.setAttribute('aria-selected', isRegister ? 'true' : 'false');
            }

            if (loginTab) {
                loginTab.style.backgroundColor = isRegister ? '#ffffff' : '#ff9800';
                loginTab.style.borderColor = '#ff9800';
                loginTab.style.color = isRegister ? '#000000' : '#ffffff';
            }

            if (registerTab) {
                registerTab.style.backgroundColor = isRegister ? '#ff9800' : '#ffffff';
                registerTab.style.borderColor = '#ff9800';
                registerTab.style.color = isRegister ? '#ffffff' : '#000000';
            }

            clearHeaderAlert();
            if (loginError) {
                loginError.classList.add('d-none');
                loginError.textContent = '';
            }
            resetRegisterMessages();
            clearRegisterFieldErrors();
        };

        if (loginSubmit) {
            loginSubmit.addEventListener('click', function(event) {
                event.preventDefault();

                var email = document.getElementById('header-login-email')?.value.trim();
                var password = document.getElementById('header-login-password')?.value;

                if (loginError) {
                    loginError.classList.add('d-none');
                    loginError.textContent = '';
                }

                if (!email || !password) {
                    showInlineMessage(loginError, 'Please enter both email and password.');
                    return;
                }

                setHeaderLoading(loginSubmit, true, 'Logging in...');

                fetch("{{ route('login.password') }}", {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json',
                    },
                    credentials: 'include',
                    body: JSON.stringify({
                        email: email,
                        password: password,
                    })
                })
                    .then(async function(response) {
                        var data = await response.json().catch(function() {
                            return {
                                success: false,
                                message: 'Unexpected server response.'
                            };
                        });

                        if (!response.ok || data.success === false) {
                            showInlineMessage(loginError, data.message || 'Login failed.');
                            return;
                        }

                        if (loginError) loginError.classList.add('d-none');
                        if (data.token) sessionStorage.setItem('auth_api_token', data.token);
                        if (data.user) sessionStorage.setItem('auth_user', JSON.stringify(data.user));

                        var modal = document.getElementById('authModal');
                        if (modal) {
                            var bsModal = bootstrap.Modal.getInstance(modal) || new bootstrap.Modal(modal);
                            bsModal.hide();
                        }

                        if (data.redirect_url) {
                            window.location.href = data.redirect_url;
                        } else {
                            window.location.reload();
                        }
                    })
                    .catch(function() {
                        showInlineMessage(loginError, 'Unable to reach authentication service.');
                    })
                    .finally(function() {
                        setHeaderLoading(loginSubmit, false);
                    });
            });
        }

        if (forgotSubmit) {
            forgotSubmit.addEventListener('click', function() {
                var forgotEmail = document.getElementById('header-forgot-email')?.value.trim();
                if (!forgotEmail) {
                    showInlineMessage(loginError, 'Please enter your email address.');
                    window.showNormalLogin();
                    return;
                }

                showInlineMessage(loginError, 'Password reset API is not configured yet.');
                window.showNormalLogin();
            });
        }

        if (registerForm) {
            registerForm.addEventListener('submit', function(event) {
                event.preventDefault();

                var firstName = document.getElementById('regFirstName')?.value.trim();
                var lastName = document.getElementById('regLastName')?.value.trim();
                var mobileNo = document.getElementById('regMobile')?.value.trim();
                var email = document.getElementById('regEmail')?.value.trim();
                var password = document.getElementById('regPassword')?.value;
                var submitButton = registerForm.querySelector('button[type="submit"]');

                resetRegisterMessages();
                clearRegisterFieldErrors();

                if (!firstName || !lastName || !mobileNo || !email || !password) {
                    showInlineMessage(registerError, 'All fields are required.');
                    return;
                }

                setHeaderLoading(submitButton, true, 'Registering...');

                fetch("{{ route('register.store') }}", {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json',
                    },
                    credentials: 'include',
                    body: JSON.stringify({
                        first_name: firstName,
                        last_name: lastName,
                        mobile_no: mobileNo,
                        email: email,
                        password: password,
                    })
                })
                    .then(async function(response) {
                        var data = await response.json().catch(function() {
                            return {
                                success: false,
                                message: 'Unexpected server response.'
                            };
                        });

                        if (!response.ok || data.success === false) {
                            if (data.errors && typeof data.errors === 'object') {
                                Object.keys(data.errors).forEach(function(key) {
                                    var fieldMap = {
                                        first_name: ['regFirstName', 'regFirstNameError'],
                                        last_name: ['regLastName', 'regLastNameError'],
                                        mobile_no: ['regMobile', 'regMobileError'],
                                        email: ['regEmail', 'regEmailError'],
                                        password: ['regPassword', 'regPasswordError'],
                                    };
                                    var mappedField = fieldMap[key];
                                    if (!mappedField) return;

                                    var input = document.getElementById(mappedField[0]);
                                    var error = document.getElementById(mappedField[1]);
                                    if (input) input.classList.add('is-invalid');
                                    if (error) error.textContent = data.errors[key][0];
                                });
                            }

                            showInlineMessage(registerError, data.message || 'Registration failed.');
                            return;
                        }

                        showInlineMessage(registerSuccess, data.message || 'Registration successful! You can now log in.');
                        if (data.redirect_url) {
                            window.location.href = data.redirect_url;
                        } else {
                            window.location.reload();
                        }
                    })
                    .catch(function() {
                        showInlineMessage(registerError, 'Unable to reach registration service.');
                    })
                    .finally(function() {
                        setHeaderLoading(submitButton, false);
                    });
            });
        }

        window.showTab('login');
        window.showNormalLogin();

        // OTP input UX: auto-advance and backspace behavior
        otpInputs.forEach(function(input, index) {
            input.addEventListener('input', function(e) {
                const value = input.value.replace(/[^0-9]/g, '');
                input.value = value.slice(-1);

                if (value && index < otpInputs.length - 1) {
                    otpInputs[index + 1].focus();
                }
            });

            input.addEventListener('keydown', function(e) {
                if (e.key === 'Backspace' && !input.value && index > 0) {
                    otpInputs[index - 1].focus();
                }
            });
        });

        if (sendBtn) {
            sendBtn.addEventListener('click', function() {
                const mobile = (mobileInput?.value || '').trim();
                if (!mobile) {
                    showHeaderAlert('Please enter your mobile number.', 'danger');
                    return;
                }

                setHeaderLoading(sendBtn, true);

                headerPostJson("{{ route('login.otp.request') }}", {
                    mobile_no: mobile,
                    country_code: '91',
                    context: 'header',
                }, function(data) {
                    showHeaderAlert(data.message || 'OTP sent successfully.', 'success');
                    if (mobileReadonly) mobileReadonly.value = mobile;
                    if (stepMobile && stepVerify) {
                        stepMobile.style.display = 'none';
                        stepVerify.style.display = 'block';
                    }
                    startHeaderResendCountdown(30);
                });

                setTimeout(function() {
                    setHeaderLoading(sendBtn, false);
                }, 600);
            });
        }

        if (verifyBtn) {
            verifyBtn.addEventListener('click', function() {
                const mobile = (mobileReadonly?.value || '').trim();
                const otp = getHeaderOtp();

                if (!otp || otp.length < 4) {
                    showHeaderAlert('Please enter the 4-digit OTP.', 'danger');
                    return;
                }

                setHeaderLoading(verifyBtn, true);

                headerPostJson("{{ route('login.otp.verify') }}", {
                    mobile_no: mobile,
                    country_code: '91',
                    otp: otp,
                    context: 'header',
                }, function(data) {
                    showHeaderAlert(data.message || 'Logged in successfully.', 'success');
                    const modal = document.getElementById('authModal');
                    if (modal) {
                        const bsModal = bootstrap.Modal.getInstance(modal) || new bootstrap.Modal(modal);
                        bsModal.hide();
                    }
                    if (data.redirect_url) {
                        window.location.href = data.redirect_url;
                    } else {
                        window.location.reload();
                    }
                });

                setTimeout(function() {
                    setHeaderLoading(verifyBtn, false);
                }, 600);
            });
        }

        if (changeMobileBtn) {
            changeMobileBtn.addEventListener('click', function(event) {
                event.preventDefault();
                if (stepMobile && stepVerify) {
                    stepVerify.style.display = 'none';
                    stepMobile.style.display = 'block';
                    clearHeaderAlert();
                    clearHeaderOtp();
                }
            });
        }

        if (resendBtn) {
            resendBtn.addEventListener('click', function() {
                const mobile = (mobileReadonly?.value || '').trim();
                if (!mobile) {
                    showHeaderAlert('Mobile number is missing. Please go back and enter it again.', 'danger');
                    return;
                }

                setHeaderLoading(resendBtn, true);

                headerPostJson("{{ route('login.otp.resend') }}", {
                    mobile_no: mobile,
                    country_code: '91',
                    context: 'header',
                }, function(data) {
                    showHeaderAlert(data.message || 'OTP resent.', 'success');
                    startHeaderResendCountdown(30);
                });

                setTimeout(function() {
                    setHeaderLoading(resendBtn, false);
                }, 600);
            });
        }
    });


(function() {
    const searchInput = document.getElementById('searchInput');
    const suggestionsBox = document.getElementById('searchSuggestions');
    let debounceTimeout = null;

    function showSuggestions(items) {
        if (!items.length) {
            suggestionsBox.classList.remove('show');
            suggestionsBox.innerHTML = '';
            return;
        }
        suggestionsBox.innerHTML = items.map(item => {
            const imageUrl = item.image_url || '/assets/images/product-1.jpg';
            const price = item.total_price || item.price || '';
            const slug = item.slug || item.id;
            return `
                <a href="/products/${encodeURIComponent(slug)}" class="suggestion-item d-flex align-items-center gap-2 py-2 px-2 border-bottom text-decoration-none" data-id="${item.id}" style="transition:background 0.15s;">
                    <img src="${imageUrl}" style="width: 48px; height: 48px; object-fit: cover; border-radius: 6px; border: 1px solid #eee; background: #fafafa;">
                    <div class="flex-grow-1 ms-1" style="min-width:0;">
                        <div class="fw-semibold text-dark text-truncate" style="font-size: 1rem;">${item.name || 'Product'}</div>
                        ${price ? `<div class="suggestion-price fw-bold text-success mt-1" style="font-size: 1.05rem;">₹${price}</div>` : ''}
                    </div>
                </a>
            `;
        }).join('');
        suggestionsBox.classList.add('show');
    // Add styles for professional suggestion dropdown
    const suggestionStyles = `
    .search-suggestions {
        box-shadow: 0 4px 24px rgba(0,0,0,0.10), 0 1.5px 4px rgba(0,0,0,0.04);
        border-radius: 0.6rem;
        background: #fff;
        border: 1px solid #eee;
        max-height: 420px;
        overflow-y: auto;
        min-width: 320px;
        padding: 0;
        margin-top: 0.25rem;
        z-index: 1002;
    }
    .search-suggestions .suggestion-item {
        cursor: pointer;
        border-bottom: 1px solid #f2f2f2;
        transition: background 0.13s;
    }
    .search-suggestions .suggestion-item:last-child {
        border-bottom: none;
    }
    .search-suggestions .suggestion-item:hover, .search-suggestions .suggestion-item:focus {
        background: #f7f7fa;
        text-decoration: none;
    }
    .search-suggestions .suggestion-price {
        color: #388e3c;
        font-weight: 600;
    }
    `;
    if (!document.getElementById('search-suggestion-styles')) {
        const styleTag = document.createElement('style');
        styleTag.id = 'search-suggestion-styles';
        styleTag.innerHTML = suggestionStyles;
        document.head.appendChild(styleTag);
    }
    }

    function fetchSuggestions(query) {
        fetch(`/api/product/search?q=${encodeURIComponent(query)}`)
            .then(res => res.json())
            .then(data => {
                if (data.status && Array.isArray(data.data)) {
                    showSuggestions(data.data.slice(0, 8));
                } else {
                    showSuggestions([]);
                }
            })
            .catch(() => showSuggestions([]));
    }

    if (searchInput) {
        searchInput.addEventListener('input', function() {
            const query = searchInput.value.trim();
            clearTimeout(debounceTimeout);
            if (query.length < 2) {
                showSuggestions([]);
                return;
            }
            debounceTimeout = setTimeout(() => fetchSuggestions(query), 250);
        });
    }

    if (suggestionsBox) {
        suggestionsBox.addEventListener('mousedown', function(e) {
            const item = e.target.closest('.suggestion-item');
            if (item) {
                searchInput.value = item.querySelector('span').textContent;
                suggestionsBox.classList.remove('show');
                // Optionally redirect to product page:
                // window.location.href = `/products/${item.dataset.id}`;
            }
        });
    }

    document.addEventListener('click', function(e) {
        if (suggestionsBox && !suggestionsBox.contains(e.target) && e.target !== searchInput) {
            suggestionsBox.classList.remove('show');
        }
    });
})();
</script>
@endpush
