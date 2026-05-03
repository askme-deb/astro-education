// Main JS for the astrology website
// Place all custom scripts here or import modules as needed

// Example: Modal tab switching for login/register
window.showTab = function(tab) {
    document.querySelectorAll('.auth-tab').forEach(el => el.classList.remove('active'));
    if(tab === 'login') {
        document.getElementById('loginBox').style.display = 'block';
        document.getElementById('registerBox').style.display = 'none';
        document.querySelectorAll('.auth-tab')[0].classList.add('active');
    } else {
        document.getElementById('loginBox').style.display = 'none';
        document.getElementById('registerBox').style.display = 'block';
        document.querySelectorAll('.auth-tab')[1].classList.add('active');
    }
};

window.togglePassword = function(id) {
    let input = document.getElementById(id);
    input.type = input.type === 'password' ? 'text' : 'password';
};

window.sendOTP = function() {
    document.getElementById('otpSection').style.display = 'block';
    alert('OTP Sent (Demo)');
};

// Add more modular JS as needed for your site
