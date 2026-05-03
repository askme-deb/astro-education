<!-- Modal -->
<div class="modal fade" id="authModal">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">

            <div class="row g-0">

                <!-- LEFT IMAGE -->
                <!-- <div class="col-md-6 d-none d-md-block auth-image"></div> -->


                <!-- RIGHT FORM -->
                <div class="col-md-12 p-4">

                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h4>Welcome to Our Website</h4>
                        <button class="btn-close" data-bs-dismiss="modal"></button>
                    </div>

                    <!-- Tabs -->
                    <div class="d-flex justify-content-around mb-3">
                        <div class="auth-tab active" onclick="showTab('login')">Login</div>
                        <div class="auth-tab" onclick="showTab('register')">Register</div>
                    </div>

                    <!-- LOGIN -->
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



                        <div class="otp-box mt-2" id="otpSection">
                            <input type="text" class="form-control mb-2" placeholder="Enter OTP">
                            <button class="btn btn-success w-100">Verify OTP</button>
                        </div>

                        <hr>

                        <div class="soceal_login">
                            <!-- Social Login -->
                            <button class="btn btn-google w-100 mb-2">
                                <i class="bi bi-google"></i> Continue with Google
                            </button>

                            <button class="btn btn-facebook w-100">
                                <i class="bi bi-facebook"></i> Continue with Facebook
                            </button>
                        </div>
                    </div>


                    <!-- REGISTER -->
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
                            <!-- Social Login -->
                            <button class="btn btn-google w-100 mb-2">
                                <i class="bi bi-google"></i> Continue with Google
                            </button>

                            <button class="btn btn-facebook w-100">
                                <i class="bi bi-facebook"></i> Continue with Facebook
                            </button>
                        </div>
                    </div>

                </div>

            </div>
        </div>

    </div>
</div>
</div>



<!-- Footer Section -->
<footer class="footer">
    <div class="container-fluid">

        <div class="row">

            <!-- Logo & App Section -->
            <div class="col-lg-3 col-md-6 mb-4">
                <div class="logo_foot">
                    <img src="{{ asset('assets/images/Logo.png') }}" alt="logo" class="mb-3">
                </div>

                <h5>Astroyogi Mobile Apps</h5>

                <div class="app-btn">
                    <img src="https://developer.apple.com/assets/elements/badges/download-on-the-app-store.svg">
                    <img src="https://upload.wikimedia.org/wikipedia/commons/7/78/Google_Play_Store_badge_EN.svg">
                </div>

                <h5 class="mt-4">Follow us on</h5>
                <div class="social-icons">
                    <a href="#"><i class="fab fa-facebook-f"></i></a>
                    <a href="#"><i class="fab fa-youtube"></i></a>
                    <a href="#"><i class="fab fa-instagram"></i></a>
                    <a href="#"><i class="fab fa-x-twitter"></i></a>
                    <a href="#"><i class="fab fa-linkedin-in"></i></a>
                </div>

            </div>


            <!-- Quick Links -->
            <div class="col-lg-3 col-md-6 mb-4">
                <h5>Quick Links</h5>
                <a href="#">Chat with Astrologer</a>
                <a href="#">Astrologer</a>
                <a href="#">Tarot Readers</a>
                <a href="#">Numerologist</a>
                <a href="#">Vastu Experts</a>
                <a href="#">Fengshui Astrologer</a>
                <a href="#">Love Astrologer</a>
                <a href="#">Financial Astrologer</a>
                <a href="#">Marriage Astrologer</a>
                <a href="#">Free Astrology Consultation</a>
                <a href="#">Horoscope 2026</a>
            </div>


            <!-- Useful Links -->
            <div class="col-lg-3 col-md-6 mb-4">
                <h5>Useful Links</h5>
                <a href="#">About Us</a>
                <a href="#">Contact Us</a>
                <a href="#">Astrologer Registration</a>
                <a href="#">Partner Us</a>
                <a href="#">Career</a>
                <a href="#">Site Map</a>
                <a href="#">Karma & Destiny</a>
                <a href="#">Refund Policy</a>
                <a href="#">Yogii Mall Refund Policy</a>
                <a href="#">Shipping Policy</a>
                <a href="#">Astroyogi Academy</a>
                <a href="#">Media Coverage</a>
                <a href="#">Authors</a>
            </div>

            <div class="col-lg-3 col-md-6 mb-4">

                <div class="footer-col newsletter">

                    <h4>Subscribe</h4>
                    <p>Get exclusive offers & updates</p>

                    <form>
                        <input type="email" placeholder="Enter your email" required>
                        <button type="submit">Subscribe</button>
                    </form>

                </div>
            </div>

        </div>




        <section class="payment-section">
            <div class="container">
                <ul class="payment-icons">

                    <li><img src="https://imgcdn1.gempundit.com/media/wysiwyg/visa.svg" alt="Visa"></li>
                    <li><img src="https://imgcdn1.gempundit.com/media/wysiwyg/mastercard.svg" alt="Mastercard"></li>
                    <li><img src="https://imgcdn1.gempundit.com/media/wysiwyg/PayPal.svg" alt="PayPal"></li>
                    <li><img src="https://imgcdn1.gempundit.com/media/wysiwyg/Netbanking__.svg" alt="Net Banking"></li>
                    <li><img src="https://imgcdn1.gempundit.com/media/wysiwyg/american-express.svg"
                            alt="American Express"></li>
                    <li><img src="https://imgcdn1.gempundit.com/media/wysiwyg/rupay.png" alt="RuPay"></li>
                    <li><img src="https://imgcdn1.gempundit.com/media/wysiwyg/Bhim.svg" alt="BHIM UPI"></li>

                    <li class="custom-option">
                        <a href="#">
                            <img src="https://imgcdn1.gempundit.com/media/wysiwyg/footer-icon1.svg" alt="">
                            <span>Cash on Delivery</span>
                        </a>
                    </li>

                    <li class="custom-option">
                        <a href="#">
                            <img src="https://imgcdn1.gempundit.com/media/wysiwyg/footer-icon2.svg" alt="">
                            <span>Lab Certified</span>
                        </a>
                    </li>

                    <li class="custom-option">
                        <a href="#">
                            <img src="https://imgcdn1.gempundit.com/media/wysiwyg/footer-icon3.svg" alt="">
                            <span>Easy Returns</span>
                        </a>
                    </li>

                </ul>
            </div>
        </section>


</footer>







<!-- Bottom Footer -->
<div class="footer-bottom">

    <div class="container">

        <div class="row">
            <div class="col-lg-6 col-md-6 mb-4">
                ©astrorajumaharaj . 2001-2026. All rights reserved
            </div>
            <div class="col-lg-6 col-md-6 mb-4 trwcsaq">
                <a href="#">Privacy Policy</a>
                <a href="#">FAQs</a>
                <a href="#">T&C</a>
            </div>
        </div>

    </div>
</div>
</div>



<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

<!-- JS -->
<script>
    function bookService(service){
    alert("Booking page for: " + service);
}

function showPrediction(sign){
    alert("Showing today's prediction for " + sign);
}

</script>

<script>
    function openReading(name){
    alert("Opening " + name + " page...");
}
</script>


<!-- JS -->
<script>
    function openBlog(title){
    alert("Opening blog: " + title);
}
</script>


<script>
    /* Toggle Tabs */
function showTab(tab){

document.querySelectorAll('.auth-tab').forEach(t=>t.classList.remove('active'));

if(tab==='login'){
    document.getElementById('loginBox').style.display='block';
    document.getElementById('registerBox').style.display='none';
    document.querySelectorAll('.auth-tab')[0].classList.add('active');
}
else{
    document.getElementById('loginBox').style.display='none';
    document.getElementById('registerBox').style.display='block';
    document.querySelectorAll('.auth-tab')[1].classList.add('active');
}

}


/* Show Password */
function togglePassword(id){
let input = document.getElementById(id);
input.type = input.type === "password" ? "text" : "password";
}


/* OTP Simulation */
function sendOTP(){

alert("OTP Sent: 1234");

document.getElementById("otpSection").style.display="block";

}

</script>


<script>
    let currentStep = 0;
const sections = document.querySelectorAll(".form-section");
const steps = document.querySelectorAll(".step");

function showStep(index){
    sections.forEach((sec,i)=>{
        sec.classList.toggle("active", i===index);
        steps[i].classList.toggle("active", i<=index);
    });
}

document.querySelectorAll(".next").forEach(btn=>{
    btn.addEventListener("click", ()=>{
        if(currentStep < sections.length-1){
            currentStep++;
            showStep(currentStep);
        }
    });
});

document.querySelectorAll(".prev").forEach(btn=>{
    btn.addEventListener("click", ()=>{
        if(currentStep > 0){
            currentStep--;
            showStep(currentStep);
        }
    });
});

document.getElementById("multiStepForm").addEventListener("submit", function(e){
    e.preventDefault();
    alert("Form Submitted Successfully!");
});
</script>


<script>
    function showTab(tab){

document.querySelectorAll('.auth-tab').forEach(el=>el.classList.remove('active'));

if(tab === 'login'){
document.getElementById('loginBox').style.display='block';
document.getElementById('registerBox').style.display='none';
document.querySelectorAll('.auth-tab')[0].classList.add('active');
}else{
document.getElementById('loginBox').style.display='none';
document.getElementById('registerBox').style.display='block';
document.querySelectorAll('.auth-tab')[1].classList.add('active');
}

}

function togglePassword(id){
let input = document.getElementById(id);
input.type = input.type === "password" ? "text" : "password";
}

function sendOTP(){
document.getElementById('otpSection').style.display='block';
alert("OTP Sent (Demo)");
}

</script>

<script>
    const slotButtons = document.querySelectorAll('.slot-btn');
const slotInput = document.getElementById('slotTimeInput');
const slotText = document.getElementById('slotText');

slotButtons.forEach(btn => {
    btn.addEventListener('click', () => {

        slotButtons.forEach(b => b.classList.remove('active'));

        btn.classList.add('active');

        const value = btn.getAttribute('data-value');

        slotInput.value = value;
        slotText.innerText = value;

    });
});

</script>

<script>
    const faqItems = document.querySelectorAll(".faq-item");

faqItems.forEach(item => {
    item.querySelector(".faq-question").addEventListener("click", () => {

        item.classList.toggle("active");

    });
});

</script>

<script>
    // More reviews data
const moreReviews = [
    {name:"A P.", text:"Very accurate prediction. Thank you!"},
    {name:"S D.", text:"Helpful consultation and friendly nature."},
    {name:"M K.", text:"Highly recommended astrologer."},
    {name:"P R.", text:"Good experience overall."}
];

let index = 0;

function loadMore(){

    const list = document.getElementById("reviewList");

    if(index < moreReviews.length){

        const review = moreReviews[index];

        const div = document.createElement("div");
        div.className = "review-card";

        div.innerHTML = `
            <strong>${review.name} ⭐⭐⭐⭐⭐</strong>
            <p class="mb-0 text-muted">${review.text}</p>
        `;

        list.appendChild(div);

        index++;

    } else {

        alert("No more reviews available");
    }

}

</script>

<script>
    document.addEventListener('DOMContentLoaded', function() {
    const slides = document.querySelectorAll('.hero-slide');
    const dots = document.querySelectorAll('.carousel-dot');
    const prevBtn = document.querySelector('.carousel-prev');
    const nextBtn = document.querySelector('.carousel-next');
    let currentSlide = 0;
    let autoPlayInterval = null;
    const SLIDE_DURATION = 7000;

    function showSlide(index) {
        slides.forEach(slide => slide.classList.remove('active'));
        dots.forEach(dot => dot.classList.remove('active'));

        slides[index].classList.add('active');
        if(dots[index]) dots[index].classList.add('active');
    }

    function nextSlide() {
        currentSlide = (currentSlide + 1) % slides.length;
        showSlide(currentSlide);
    }

    function prevSlide() {
        currentSlide = (currentSlide - 1 + slides.length) % slides.length;
        showSlide(currentSlide);
    }

    function startAutoPlay() {
        stopAutoPlay();
        autoPlayInterval = setInterval(nextSlide, SLIDE_DURATION);
    }

    function stopAutoPlay() {
        if (autoPlayInterval) clearInterval(autoPlayInterval);
    }

    nextBtn.addEventListener('click', () => { stopAutoPlay(); nextSlide(); startAutoPlay(); });
    prevBtn.addEventListener('click', () => { stopAutoPlay(); prevSlide(); startAutoPlay(); });

    dots.forEach((dot, idx) => {
        dot.addEventListener('click', () => {
            stopAutoPlay();
            currentSlide = idx;
            showSlide(currentSlide);
            startAutoPlay();
        });
    });

    startAutoPlay();
});
</script>
<script>
    const searchInput = document.getElementById('searchInput');
const levelFilter = document.getElementById('levelFilter');
const durationFilter = document.getElementById('durationFilter');
const priceSort = document.getElementById('priceSort');
const courses = document.querySelectorAll('.course-item');
const container = document.getElementById('courseContainer');

function filterCourses() {

    let searchValue = searchInput.value.toLowerCase();
    let levelValue = levelFilter.value;
    let durationValue = durationFilter.value;

    courses.forEach(course => {

        let title = course.querySelector("h5").innerText.toLowerCase();
        let level = course.dataset.level;
        let duration = course.dataset.duration;

        let matchSearch = title.includes(searchValue);
        let matchLevel = levelValue === "" || level === levelValue;
        let matchDuration = durationValue === "" || duration === durationValue;

        if (matchSearch && matchLevel && matchDuration) {
            course.style.display = "block";
        } else {
            course.style.display = "none";
        }

    });

}

function sortCourses() {

    let sortValue = priceSort.value;
    let courseArray = Array.from(courses);

    courseArray.sort((a, b) => {
        let priceA = parseInt(a.dataset.price);
        let priceB = parseInt(b.dataset.price);

        return sortValue === "low" ? priceA - priceB : priceB - priceA;
    });

    courseArray.forEach(course => container.appendChild(course));

}

searchInput.addEventListener('keyup', filterCourses);
levelFilter.addEventListener('change', filterCourses);
durationFilter.addEventListener('change', filterCourses);
priceSort.addEventListener('change', sortCourses);

</script>

<script>
    // Counter Animation
const counter = document.getElementById("counter");
const targetNumber = 2500;
let current = 0;

function updateCounter(){
    current += Math.ceil(targetNumber / 100);
    if(current > targetNumber) current = targetNumber;

    let str = current.toString().padStart(4,"0");
    counter.innerHTML = "";
    str.split("").forEach(num=>{
        const span = document.createElement("span");
        span.classList.add("counter-item");
        span.textContent = num;
        counter.appendChild(span);
    });

    if(current < targetNumber){
        requestAnimationFrame(updateCounter);
    }
}

updateCounter();
</script>

<script>
    /* TAB FILTERING */
const tabs = document.querySelectorAll(".course-tab");
const cards = document.querySelectorAll(".course-grid-card");
const viewBtns = document.querySelectorAll(".view-all-courses-btn");

tabs.forEach(tab=>{
tab.addEventListener("click",()=>{

tabs.forEach(t=>t.classList.remove("active"));
tab.classList.add("active");

let category = tab.dataset.tab;

cards.forEach(card=>{
if(category === "all"){
card.style.display="flex";
}else{
card.style.display = card.dataset.category === category ? "flex" : "none";
}
});

viewBtns.forEach(btn=>{
btn.classList.remove("active");
if(btn.dataset.tab === category){
btn.classList.add("active");
}
});

});
});

/* ENROLL MODAL */
const enrollBtns = document.querySelectorAll(".enroll-btn");
const modal = document.getElementById("enrollModal");
const modalTitle = document.getElementById("modalTitle");

enrollBtns.forEach(btn=>{
btn.addEventListener("click",()=>{
modal.style.display="flex";
modalTitle.textContent = btn.dataset.title;
});
});

function closeModal(){
modal.style.display="none";
}

</script>


</body>

</html>
