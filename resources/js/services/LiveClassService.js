/**
 * LiveClass API Service
 * Handles all API calls for live class functionality
 */
class LiveClassService {
    constructor() {
        this.baseURL = window.location.origin.replace(':8001', ':8000') + '/api/v1';
        this.csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
    }

    /**
     * Make API request with proper headers
     */
    async request(endpoint, options = {}) {
        const url = `${this.baseURL}${endpoint}`;
        const config = {
            ...options,
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': this.csrfToken,
                ...options.headers
            }
        };

        try {
            const response = await fetch(url, config);
            const data = await response.json();

            if (!response.ok) {
                throw new Error(data.message || 'Request failed');
            }

            return data;
        } catch (error) {
            console.error(`API Error [${endpoint}]:`, error);
            throw error;
        }
    }

    /**
     * Get my live classes
     */
    async getMyLiveClasses() {
        return this.request('/my-live-classes');
    }

    /**
     * Get live class details
     */
    async getLiveClass(id) {
        return this.request(`/live-classes/${id}`);
    }

    /**
     * Join live class
     */
    async joinLiveClass(id) {
        return this.request(`/live-classes/${id}/join`);
    }

    /**
     * Get room access
     */
    async getRoomAccess(id) {
        return this.request(`/live-classes/${id}/room`);
    }

    /**
     * Start live class (instructor only)
     */
    async startLiveClass(id) {
        return this.webRequest(`/student/live-classes/${id}/start`, {
            method: 'POST'
        });
    }

    /**
     * End live class (instructor only)
     */
    async endLiveClass(id) {
        return this.request(`/live-classes/${id}/end`, {
            method: 'POST'
        });
    }

    /**
     * Create live class (instructor only)
     */
    async createLiveClass(data) {
        return this.request('/live-classes', {
            method: 'POST',
            body: JSON.stringify(data)
        });
    }

    /**
     * Update live class (instructor only)
     */
    async updateLiveClass(id, data) {
        return this.request(`/live-classes/${id}`, {
            method: 'PUT',
            body: JSON.stringify(data)
        });
    }

    /**
     * Delete live class (instructor only)
     */
    async deleteLiveClass(id) {
        return this.request(`/live-classes/${id}`, {
            method: 'DELETE'
        });
    }

    /**
     * Enroll in live class
     */
    async enrollLiveClass(id) {
        return this.request(`/live-classes/${id}/enroll`, {
            method: 'POST'
        });
    }

    /**
     * Get recording access
     */
    async getRecording(id) {
        return this.request(`/live-classes/${id}/recording`);
    }

    async webRequest(endpoint, options = {}) {
        const response = await fetch(endpoint, {
            ...options,
            credentials: 'same-origin',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': this.csrfToken,
                ...options.headers
            }
        });

        const data = await response.json().catch(() => ({}));

        if (!response.ok) {
            throw new Error(data.message || 'Request failed');
        }

        return data;
    }
}

// Export singleton instance
window.LiveClassService = new LiveClassService();
