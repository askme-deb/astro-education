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

window.LiveClassService = {
    baseURL: window.location.origin.replace(':8001', ':8000') + '/api/v1',

    csrfToken() {
        return document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';
    },

    async request(endpoint, options = {}) {
        const response = await fetch(`${this.baseURL}${endpoint}`, {
            ...options,
            credentials: 'include',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': this.csrfToken(),
                ...(options.headers || {}),
            },
        });

        const data = await response.json().catch(() => ({}));

        if (!response.ok) {
            throw new Error(data.message || 'Request failed.');
        }

        return data;
    },

    getMyLiveClasses() {
        return this.request('/my-live-classes');
    },

    getLiveClass(id) {
        return this.request(`/live-classes/${id}`);
    },

    joinLiveClass(id) {
        return this.request(`/live-classes/${id}/join`);
    },

    startLiveClass(id) {
        return this.webRequest(`/student/live-classes/${id}/start`, { method: 'POST' });
    },

    endLiveClass(id) {
        return this.request(`/live-classes/${id}/end`, { method: 'POST' });
    },

    createLiveClass(data) {
        return this.request('/live-classes', {
            method: 'POST',
            body: JSON.stringify(data),
        });
    },

    updateLiveClass(id, data) {
        return this.request(`/live-classes/${id}`, {
            method: 'PUT',
            body: JSON.stringify(data),
        });
    },

    deleteLiveClass(id) {
        return this.request(`/live-classes/${id}`, { method: 'DELETE' });
    },

    async webRequest(endpoint, options = {}) {
        const response = await fetch(endpoint, {
            ...options,
            credentials: 'same-origin',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': this.csrfToken(),
                ...(options.headers || {}),
            },
        });

        const data = await response.json().catch(() => ({}));

        if (!response.ok) {
            throw new Error(data.message || 'Request failed.');
        }

        return data;
    },
};
