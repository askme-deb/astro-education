@extends('layouts.app')

@section('title', 'Live Class Room')

@push('styles')
<style>
    .room-container {
        min-height: 100vh;
        display: flex;
        flex-direction: column;
        background: linear-gradient(135deg, #1a1a2e 0%, #16213e 100%);
    }

    .room-header {
        background: rgba(255, 255, 255, 0.05);
        backdrop-filter: blur(10px);
        border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        padding: 16px 24px;
    }

    .room-content {
        flex: 1;
        display: flex;
        flex-direction: column;
        padding: 24px;
    }

    .video-container {
        flex: 1;
        background: #000;
        border-radius: 16px;
        overflow: hidden;
        position: relative;
        min-height: 500px;
    }

    .video-placeholder {
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        text-align: center;
        color: rgba(255, 255, 255, 0.6);
    }

    .room-controls {
        background: rgba(255, 255, 255, 0.05);
        backdrop-filter: blur(10px);
        border-top: 1px solid rgba(255, 255, 255, 0.1);
        padding: 16px 24px;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .control-btn {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 12px 20px;
        border-radius: 12px;
        font-weight: 600;
        border: none;
        cursor: pointer;
        transition: all 0.2s;
    }

    .control-btn-primary {
        background: linear-gradient(135deg, #ff9800, #f57c00);
        color: #fff;
    }

    .control-btn-primary:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 20px rgba(255, 152, 0, 0.3);
    }

    .control-btn-danger {
        background: rgba(214, 48, 49, 0.1);
        color: #d63031;
        border: 1px solid rgba(214, 48, 49, 0.3);
    }

    .control-btn-danger:hover {
        background: rgba(214, 48, 49, 0.2);
    }

    .room-info {
        color: rgba(255, 255, 255, 0.8);
    }

    .room-info h2 {
        color: #fff;
        margin-bottom: 4px;
    }

    .status-badge {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 6px 12px;
        border-radius: 20px;
        font-size: 0.85rem;
        font-weight: 600;
        background: rgba(76, 175, 80, 0.2);
        color: #4caf50;
    }

    .status-badge.live {
        background: rgba(214, 48, 49, 0.2);
        color: #d63031;
    }
</style>
@endpush

@section('content')
<div class="room-container">
    <div class="room-header">
        <div class="d-flex justify-content-between align-items-center">
            <div class="room-info">
                <h2>{{ $liveClass['title'] ?? 'Live Class' }}</h2>
                <span class="status-badge live">
                    <span class="badge-dot"></span>
                    Live Now
                </span>
            </div>
            <div class="d-flex gap-2">
                <a href="{{ route('live-classes.index') }}" class="btn btn-outline-light">
                    <i class="bi bi-arrow-left me-2"></i>
                    Back to Classes
                </a>
            </div>
        </div>
    </div>

    <div class="room-content">
        <div class="video-container" id="videoContainer">
            <div class="video-placeholder">
                <i class="bi bi-camera-video fs-1 mb-3"></i>
                <h4>Connecting to meeting room...</h4>
                <p class="mb-0">Please wait while we establish the connection</p>
            </div>
        </div>
    </div>

    <div class="room-controls">
        <div class="d-flex gap-2">
            <button class="control-btn control-btn-primary" id="toggleMic">
                <i class="bi bi-mic"></i>
                Mute
            </button>
            <button class="control-btn control-btn-primary" id="toggleVideo">
                <i class="bi bi-camera-video"></i>
                Stop Video
            </button>
        </div>
        <div class="d-flex gap-2">
            <button class="control-btn control-btn-danger" id="endClassBtn">
                <i class="bi bi-stop-circle"></i>
                End Class
            </button>
        </div>
    </div>
</div>

@push('scripts')
<script>
    const liveClassId = {{ $liveClass['id'] ?? 0 }};
    const joinPayload = @json($joinPayload ?? []);

    // Initialize meeting room
    document.addEventListener('DOMContentLoaded', function() {
        if (joinPayload.server_url && joinPayload.room_id && joinPayload.token) {
            initializeMeetingRoom(joinPayload);
        } else {
            showErrorMessage('Unable to connect to meeting room. Invalid credentials.');
        }
    });

    function initializeMeetingRoom(payload) {
        const container = document.getElementById('videoContainer');
        container.innerHTML = `
            <div class="video-placeholder">
                <i class="bi bi-wifi fs-1 mb-3"></i>
                <h4>Meeting Room Ready</h4>
                <p class="mb-0">Room ID: ${payload.room_id}</p>
                <p class="text-muted small">Server: ${payload.server_url}</p>
            </div>
        `;

        // TODO: Integrate with actual video SDK (e.g., Jitsi, Zoom, WebRTC)
        // This is a placeholder for the actual video integration
        console.log('Meeting room initialized:', payload);
    }

    function showErrorMessage(message) {
        const container = document.getElementById('videoContainer');
        container.innerHTML = `
            <div class="video-placeholder text-danger">
                <i class="bi bi-exclamation-triangle fs-1 mb-3"></i>
                <h4>Connection Error</h4>
                <p class="mb-0">${message}</p>
            </div>
        `;
    }

    // Toggle microphone
    document.getElementById('toggleMic').addEventListener('click', function() {
        const icon = this.querySelector('i');
        if (icon.classList.contains('bi-mic')) {
            icon.classList.replace('bi-mic', 'bi-mic-mute');
            this.innerHTML = '<i class="bi bi-mic-mute"></i> Unmute';
        } else {
            icon.classList.replace('bi-mic-mute', 'bi-mic');
            this.innerHTML = '<i class="bi bi-mic"></i> Mute';
        }
        // TODO: Implement actual mic toggle
    });

    // Toggle video
    document.getElementById('toggleVideo').addEventListener('click', function() {
        const icon = this.querySelector('i');
        if (icon.classList.contains('bi-camera-video')) {
            icon.classList.replace('bi-camera-video', 'bi-camera-video-off');
            this.innerHTML = '<i class="bi bi-camera-video-off"></i> Start Video';
        } else {
            icon.classList.replace('bi-camera-video-off', 'bi-camera-video');
            this.innerHTML = '<i class="bi bi-camera-video"></i> Stop Video';
        }
        // TODO: Implement actual video toggle
    });

    // End class
    document.getElementById('endClassBtn').addEventListener('click', function() {
        if (confirm('Are you sure you want to end this live class? This action cannot be undone.')) {
            fetch(`/api/live-classes/${liveClassId}/end`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    window.location.href = '{{ route('live-classes.index') }}';
                } else {
                    alert('Failed to end class: ' + (data.message || 'Unknown error'));
                }
            })
            .catch(error => {
                alert('Error ending class: ' + error.message);
            });
        }
    });
</script>
@endpush
@endsection
