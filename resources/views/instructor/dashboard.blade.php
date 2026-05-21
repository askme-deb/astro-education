@extends('layouts.dashboard')

@section('title', 'Instructor Dashboard')
@section('page_title', 'Instructor Dashboard')
@section('page_subtitle', 'Manage your live classes and track engagement')

@section('page_actions')
    <button type="button" class="db-btn db-btn-primary" data-bs-toggle="modal" data-bs-target="#createModal">
        <i class="bi bi-plus-lg"></i> Create Live Class
    </button>
@endsection

@section('dashboard_content')
    @php
        $liveClasses = is_array($liveClasses ?? null) ? $liveClasses : [];
        $totalClasses = count($liveClasses);
        $liveNow = count(array_filter($liveClasses, fn ($lc) => is_array($lc) && ($lc['status'] ?? null) === 'live'));
        $scheduled = count(array_filter($liveClasses, fn ($lc) => is_array($lc) && ($lc['status'] ?? null) === 'scheduled'));
        $recordings = count(array_filter($liveClasses, fn ($lc) => is_array($lc) && !empty($lc['recording_url'])));
    @endphp

    <div class="db-stats-grid">
        <div class="db-stat-card">
            <div class="db-stat-icon"><i class="bi bi-collection"></i></div>
            <div class="db-stat-info">
                <div class="db-stat-num">{{ $totalClasses }}</div>
                <div class="db-stat-title">Total Classes</div>
            </div>
        </div>
        <div class="db-stat-card">
            <div class="db-stat-icon"><i class="bi bi-broadcast"></i></div>
            <div class="db-stat-info">
                <div class="db-stat-num">{{ $liveNow }}</div>
                <div class="db-stat-title">Live Now</div>
            </div>
        </div>
        <div class="db-stat-card">
            <div class="db-stat-icon"><i class="bi bi-calendar-event"></i></div>
            <div class="db-stat-info">
                <div class="db-stat-num">{{ $scheduled }}</div>
                <div class="db-stat-title">Scheduled</div>
            </div>
        </div>
        <div class="db-stat-card">
            <div class="db-stat-icon"><i class="bi bi-record-circle"></i></div>
            <div class="db-stat-info">
                <div class="db-stat-num">{{ $recordings }}</div>
                <div class="db-stat-title">Recordings</div>
            </div>
        </div>
    </div>

    <section id="live-classes" class="db-card" style="padding: 0; overflow: hidden;">
        <div class="db-section-head" style="padding: 16px 20px; border-bottom: 1px solid var(--db-border); margin: 0;">
            <h2>My Live Classes</h2>
            <button type="button" class="db-btn db-btn-primary" data-bs-toggle="modal" data-bs-target="#createModal">
                <i class="bi bi-plus-lg"></i> New Class
            </button>
        </div>

        @if($totalClasses > 0)
            <div style="overflow-x:auto;">
                <table class="db-table">
                    <thead>
                        <tr>
                            <th>Title</th>
                            <th>Course</th>
                            <th>Status</th>
                            <th>Start</th>
                            <th>End</th>
                            <th style="text-align:right;">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($liveClasses as $liveClass)
                            @if(is_array($liveClass))
                                @php
                                    $status = $liveClass['status'] ?? 'unknown';
                                    $pillClass = match($status) {
                                        'live' => 'db-pill db-pill--success',
                                        'scheduled' => 'db-pill',
                                        'completed', 'ended' => 'db-pill db-pill--muted',
                                        default => 'db-pill db-pill--warning',
                                    };
                                    $linkedCourse = collect($courses ?? [])->firstWhere('id', (string) ($liveClass['course_id'] ?? ''));
                                    $courseName = $linkedCourse['title'] ?? ('Course #' . ($liveClass['course_id'] ?? '—'));
                                @endphp
                                <tr>
                                    <td><strong>{{ $liveClass['title'] ?? 'Untitled' }}</strong></td>
                                    <td class="text-muted">{{ $courseName }}</td>
                                    <td><span class="{{ $pillClass }}">{{ ucfirst($status) }}</span></td>
                                    <td>{{ $liveClass['start_time'] ?? '—' }}</td>
                                    <td>{{ $liveClass['end_time'] ?? '—' }}</td>
                                    <td style="text-align:right; white-space:nowrap;">
                                        <button type="button" class="db-btn" onclick="editLiveClass({{ $liveClass['id'] ?? 0 }})">
                                            <i class="bi bi-pencil"></i> Edit
                                        </button>
                                        @if($status === 'scheduled')
                                            <button type="button" class="db-btn db-btn-primary" onclick="startLiveClass({{ $liveClass['id'] ?? 0 }})">
                                                <i class="bi bi-play-fill"></i> Start
                                            </button>
                                        @elseif($status === 'live')
                                            <a href="{{ route('live-classes.room', ['id' => $liveClass['id'] ?? 0]) }}" class="db-btn db-btn-primary">
                                                <i class="bi bi-box-arrow-in-right"></i> Join
                                            </a>
                                        @endif
                                        <button type="button" class="db-btn db-btn-danger" onclick="deleteLiveClass({{ $liveClass['id'] ?? 0 }})">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </td>
                                </tr>
                            @endif
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div class="db-empty">
                <i class="bi bi-camera-video"></i>
                <div>No live classes yet.</div>
                <button type="button" class="db-btn db-btn-primary mt-3" data-bs-toggle="modal" data-bs-target="#createModal">
                    <i class="bi bi-plus-lg"></i> Create your first class
                </button>
            </div>
        @endif
    </section>

    {{-- Create / Edit Modal --}}
    <div id="createModal" class="modal fade" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Create New Live Class</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="createLiveClassForm">
                        @csrf
                        <div class="row g-3">
                            <div class="col-12 col-md-6">
                                <label for="title" class="form-label">Title <span class="text-danger">*</span></label>
                                <input type="text" id="title" name="title" required class="form-control" placeholder="e.g., Week 3 Live Revision">
                            </div>
                            <div class="col-12 col-md-6">
                                <label for="course_id" class="form-label">Linked Course <span class="text-danger">*</span></label>
                                <select id="course_id" name="course_id" required class="form-select">
                                    <option value="" disabled selected>-- Select Course --</option>
                                    @foreach($courses ?? [] as $course)
                                        <option value="{{ $course['id'] ?? '' }}">{{ $course['title'] ?? 'Untitled Course' }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-12">
                                <label for="description" class="form-label">Description</label>
                                <textarea id="description" name="description" rows="3" class="form-control" placeholder="Brief agenda for this session"></textarea>
                            </div>
                            <div class="col-12 col-md-6">
                                <label for="start_time" class="form-label">Start Time <span class="text-danger">*</span></label>
                                <input type="datetime-local" id="start_time" name="start_time" required class="form-control">
                            </div>
                            <div class="col-12 col-md-6">
                                <label for="end_time" class="form-label">End Time <span class="text-danger">*</span></label>
                                <input type="datetime-local" id="end_time" name="end_time" required class="form-control">
                            </div>
                            <div class="col-12">
                                <div class="form-check">
                                    <input type="checkbox" id="is_recorded" name="is_recorded" value="1" class="form-check-input">
                                    <label class="form-check-label" for="is_recorded">Record this session</label>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" form="createLiveClassForm" class="btn btn-primary">Save Live Class</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script>
    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    let editingLiveClassId = null;

    document.getElementById('createLiveClassForm').addEventListener('submit', async function (e) {
        e.preventDefault();
        try {
            const formData = new FormData(this);
            const data = Object.fromEntries(formData.entries());
            data.is_recorded = formData.has('is_recorded');

            // Format course_id as integer (as expected by App\Http\Requests\StoreLiveClassRequest)
            if (data.course_id) {
                data.course_id = parseInt(data.course_id);
            } else {
                delete data.course_id;
            }

            // Convert to the exact format expected by our local validation (Y-m-d H:i:s)
            if (data.start_time) {
                data.start_time = data.start_time.replace('T', ' ');
                if (data.start_time.length <= 16) {
                    data.start_time = data.start_time + ':00';
                }
            }
            if (data.end_time) {
                data.end_time = data.end_time.replace('T', ' ');
                if (data.end_time.length <= 16) {
                    data.end_time = data.end_time + ':00';
                }
            }

            const result = editingLiveClassId
                ? await window.LiveClassService.updateLiveClass(editingLiveClassId, data)
                : await window.LiveClassService.createLiveClass(data);

            if (!result.success) {
                alert(result.message || 'Unable to save live class.');
                return;
            }

            alert(editingLiveClassId ? 'Live class updated!' : 'Live class created!');
            this.reset();
            editingLiveClassId = null;
            bootstrap.Modal.getInstance(document.getElementById('createModal'))?.hide();
            window.location.reload();
        } catch (error) {
            console.error(error);
            alert(error.message || 'Server error.');
        }
    });
window.LiveClassService = (function () {
    const BASE_URL = '/api/v1';

    const csrfToken = () =>
        document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') ?? '';

    async function request(method, path, body = null) {
        const options = {
            method,
            credentials: 'same-origin',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': csrfToken(),
            },
        };

        if (body) {
            options.body = JSON.stringify(body);
        }

        const res = await fetch(`${BASE_URL}${path}`, options);
        const json = await res.json();

        return json;
    }

    return {
        listLiveClasses:  ()         => request('GET',    '/live-classes'),
        getLiveClass:     (id)       => request('GET',    `/live-classes/${id}`),
        createLiveClass:  (data)     => request('POST',   '/live-classes', data),
        updateLiveClass:  (id, data) => request('PUT',    `/live-classes/${id}`, data),
        deleteLiveClass:  (id)       => request('DELETE', `/live-classes/${id}`),
        startLiveClass:   (id)       => request('POST',   `/live-classes/${id}/start`),
        endLiveClass:     (id)       => request('POST',   `/live-classes/${id}/end`),
        joinLiveClass:    (id)       => request('GET',    `/live-classes/${id}/join`),
        enrollLiveClass:  (id)       => request('POST',   `/live-classes/${id}/enroll`),
    };
})();
    async function editLiveClass(id) {
        try {
            const result = await window.LiveClassService.getLiveClass(id);
            if (!result.success) {
                alert(result.message || 'Failed to load live class');
                return;
            }
            const data = result.data;
            editingLiveClassId = id;
            document.getElementById('title').value = data.title || '';
            document.getElementById('description').value = data.description || '';
            document.getElementById('course_id').value = data.course_id || '';
            document.getElementById('start_time').value = formatDateTimeLocal(data.start_time);
            document.getElementById('end_time').value = formatDateTimeLocal(data.end_time);
            document.getElementById('is_recorded').checked = data.is_recorded || false;
            document.querySelector('#createModal .modal-title').textContent = 'Edit Live Class';
            bootstrap.Modal.getOrCreateInstance(document.getElementById('createModal')).show();
        } catch (error) {
            console.error(error);
            alert('Failed to load live class.');
        }
    }

    async function deleteLiveClass(id) {
        if (!confirm('Delete this live class?')) return;
        try {
            const result = await window.LiveClassService.deleteLiveClass(id);
            if (!result.success) {
                alert(result.message || 'Delete failed');
                return;
            }
            alert('Live class deleted.');
            window.location.reload();
        } catch (error) {
            console.error(error);
            alert('Server error while deleting.');
        }
    }

    async function startLiveClass(id) {
        if (!confirm('Start this live class now?')) return;
        try {
            const result = await window.LiveClassService.startLiveClass(id);
            if (!result.success) {
                alert(result.message || 'Failed to start live class');
                return;
            }
            window.location.href = `/student/live-classes/${id}/room`;
        } catch (error) {
            console.error(error);
            alert('Server error while starting class.');
        }
    }

    function formatDateTimeLocal(dateString) {
        if (!dateString) return '';
        // If already in a standard date-time local sliceable format: YYYY-MM-DDTHH:MM
        if (dateString.includes('T')) {
            return dateString.slice(0, 16);
        }
        // If it is space-separated like "YYYY-MM-DD HH:MM:SS"
        return dateString.replace(' ', 'T').slice(0, 16);
    }

    document.getElementById('createModal').addEventListener('hidden.bs.modal', function () {
        editingLiveClassId = null;
        document.getElementById('createLiveClassForm').reset();
        document.querySelector('#createModal .modal-title').textContent = 'Create New Live Class';
    });
</script>
@endpush
