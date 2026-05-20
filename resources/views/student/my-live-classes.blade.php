@extends('layouts.app')

@section('title', 'My Live Classes')

@section('content')
    <section class="py-5">
        <div class="container">
            <div class="d-flex align-items-center justify-content-between mb-4">
                <div>
                    <h1 class="h3 mb-1">My Live Classes</h1>
                    <p class="text-muted mb-0">View upcoming sessions, join live classes, and access recordings.</p>
                </div>
                <a href="{{ route('courses.index') }}" class="btn btn-primary">Browse More Courses</a>
            </div>

            <div class="row g-4 mb-4">
                <div class="col-12 col-md-4">
                    <div class="card border-0 shadow-sm">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-start">
                                <div>
                                    <h2 class="h3 mb-1">0</h2>
                                    <p class="text-muted mb-0">Live Now</p>
                                </div>
                                <i class="bi bi-broadcast-pin text-danger fs-4"></i>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-md-4">
                    <div class="card border-0 shadow-sm">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-start">
                                <div>
                                    <h2 class="h3 mb-1">0</h2>
                                    <p class="text-muted mb-0">Upcoming</p>
                                </div>
                                <i class="bi bi-calendar-event text-warning fs-4"></i>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-md-4">
                    <div class="card border-0 shadow-sm">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-start">
                                <div>
                                    <h2 class="h3 mb-1">0</h2>
                                    <p class="text-muted mb-0">Recordings</p>
                                </div>
                                <i class="bi bi-play-btn text-info fs-4"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <h2 class="h5 mb-4">Scheduled & Live Classes</h2>
                    <div id="liveClassesList">
                        <div class="alert alert-info">Loading your live classes...</div>
                    </div>
                </div>
            </div>

            <div class="card border-0 shadow-sm mt-4">
                <div class="card-body">
                    <h2 class="h5 mb-4">Recordings</h2>
                    <div id="recordingsList">
                        <div class="alert alert-info">Loading your recordings...</div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Join Live Class Modal -->
    <div id="joinModal" class="modal fade" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Join Live Class</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div id="joinDetails"></div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <a id="joinLiveBtn" href="#" target="_blank" class="btn btn-primary">Join Live Class</a>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script>
    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

    // Fetch my live classes on page load
    document.addEventListener('DOMContentLoaded', function() {
        fetchMyLiveClasses();
        setupWebSocketListener();
    });

    async function fetchMyLiveClasses() {
        try {
            const data = await window.LiveClassService.getMyLiveClasses();
            
            if (data.success || data.data) {
                displayLiveClasses(data.data || []);
                displayRecordings(data.recorded || []);
            } else {
                document.getElementById('liveClassesList').innerHTML =
                    '<div class="alert alert-warning">Unable to load live classes. Please refresh the page.</div>';
            }
        } catch (error) {
            console.error('Error fetching live classes:', error);
            document.getElementById('liveClassesList').innerHTML =
                '<div class="alert alert-danger">Error loading live classes. Please try again later.</div>';
        }
    }

    function displayLiveClasses(liveClasses) {
        const container = document.getElementById('liveClassesList');

        if (liveClasses.length === 0) {
            container.innerHTML = '<div class="alert alert-info">No live classes scheduled. Check back soon!</div>';
            return;
        }

        let html = '<div class="list-group">';
        liveClasses.forEach(liveClass => {
            html += `
                <div class="list-group-item">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <h6 class="mb-1">${liveClass.title}</h6>
                            <p class="mb-2 text-muted small">
                                <i class="bi bi-calendar-event"></i>
                                ${formatDateTime(liveClass.start_time)}
                            </p>
                            <span class="badge bg-${liveClass.status === 'live' ? 'danger' : 'primary'}">
                                ${liveClass.status.charAt(0).toUpperCase() + liveClass.status.slice(1)}
                            </span>
                        </div>
                        <button class="btn btn-sm btn-outline-primary" onclick="openJoinModal(${liveClass.id})">
                            ${liveClass.status === 'live' ? 'Join Now' : 'View Details'}
                        </button>
                    </div>
                </div>
            `;
        });
        html += '</div>';
        container.innerHTML = html;
    }

    function displayRecordings(recordings) {
        const container = document.getElementById('recordingsList');

        if (recordings.length === 0) {
            container.innerHTML = '<div class="alert alert-info">No recordings available yet.</div>';
            return;
        }

        let html = '<div class="list-group">';
        recordings.forEach(recording => {
            html += `
                <div class="list-group-item">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <h6 class="mb-1">${recording.title}</h6>
                            <p class="mb-2 text-muted small">
                                <i class="bi bi-play-circle"></i>
                                Recording available
                            </p>
                        </div>
                        <a href="${recording.recording_url}" target="_blank" rel="noopener" class="btn btn-sm btn-outline-success">
                            Watch Recording
                        </a>
                    </div>
                </div>
            `;
        });
        html += '</div>';
        container.innerHTML = html;
    }

    async function openJoinModal(liveClassId) {
        try {
            const data = await window.LiveClassService.joinLiveClass(liveClassId);
            
            if (data.success) {
                const joinData = data.data;
                const detailsHtml = `
                    <p><strong>Meeting ID:</strong> ${joinData.meeting_id}</p>
                    <p><strong>Your Name:</strong> ${joinData.user_name}</p>
                    <p><strong>Role:</strong> ${joinData.role}</p>
                    <p class="text-muted"><small>You will be redirected to the live session when you join.</small></p>
                `;
                document.getElementById('joinDetails').innerHTML = detailsHtml;
                document.getElementById('joinLiveBtn').href = `/student/live-classes/${liveClassId}/room`;

                const modal = bootstrap.Modal.getOrCreateInstance(document.getElementById('joinModal'));
                modal.show();
            } else {
                alert('Error retrieving join details. Please try again.');
            }
        } catch (error) {
            console.error('Error:', error);
            alert('An error occurred while preparing to join.');
        }
    }

    function setupWebSocketListener() {
        if (typeof Echo !== 'undefined') {
            // Listen for live class creation notifications
            Echo.channel('live-classes')
                .listen('.live-class.created', (e) => {
                    console.log('New live class notification:', e.liveClass);
                    showNotification('New Live Class Available', `${e.liveClass.title} has been scheduled!`);
                    fetchMyLiveClasses();
                });
        }
    }

    function showNotification(title, message) {
        // Show a toast notification (requires Bootstrap 5.2+ or custom implementation)
        const alertHtml = `
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <strong>${title}:</strong> ${message}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        `;
        const container = document.querySelector('section.py-5 .container');
        const alertElement = document.createElement('div');
        alertElement.innerHTML = alertHtml;
        container.insertBefore(alertElement.firstElementChild, container.firstElementChild);
    }

    function formatDateTime(dateString) {
        if (!dateString) return 'N/A';
        const date = new Date(dateString);
        return date.toLocaleString('en-US', {
            month: 'short',
            day: 'numeric',
            year: 'numeric',
            hour: '2-digit',
            minute: '2-digit'
        });
    }
</script>
@endpush
