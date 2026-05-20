@extends('layouts.app')

@section('title', 'Instructor Dashboard')

@section('content')
    <section class="py-5">
        <div class="container">
            <div class="d-flex align-items-center justify-content-between mb-4">
                <div>
                    <h1 class="h3 mb-1">Instructor Dashboard</h1>
                    <p class="text-muted mb-0">Manage your live classes, schedule sessions, and notify students in real time.</p>
                </div>
                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createModal">
                    Create Live Class
                </button>
            </div>

            <div class="row g-4 mb-4">
                <div class="col-12 col-lg-4">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-body">
                            <h2 class="h5">Create Live Class</h2>
                            <p class="text-muted">Schedule a new session and notify enrolled students instantly.</p>
                            <a href="#" class="btn btn-sm btn-primary">Open Creator</a>
                        </div>
                    </div>
                </div>

                <div class="col-12 col-lg-4">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-body">
                            <h2 class="h5">My Live Classes</h2>
                            <p class="text-muted">See your upcoming and recorded sessions in one place.</p>
                            <a href="#" class="btn btn-sm btn-success">View Classes</a>
                        </div>
                    </div>
                </div>

                <div class="col-12 col-lg-4">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-body">
                            <h2 class="h5">Analytics</h2>
                            <p class="text-muted">Track attendance, recordings, and student engagement.</p>
                            <a href="#" class="btn btn-sm btn-info">View Analytics</a>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row g-4">
                <div class="col-12">
                    <div class="card border-0 shadow-sm">
                        <div class="card-body">
                            <h2 class="h5 mb-4">My Live Classes</h2>
                            @if(isset($liveClasses) && is_array($liveClasses) && count($liveClasses) > 0)
                                <div class="table-responsive">
                                    <table class="table table-hover mb-0">
                                        <thead class="table-light">
                                            <tr>
                                                <th>Title</th>
                                                <th>Course ID</th>
                                                <th>Status</th>
                                                <th>Start Time</th>
                                                <th>End Time</th>
                                                <th>Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($liveClasses as $liveClass)
                                                @if(is_array($liveClass))
                                                <tr>
                                                    <td>
                                                        <strong>{{ $liveClass['title'] ?? 'N/A' }}</strong>
                                                    </td>
                                                    <td>{{ $liveClass['course_id'] ?? 'N/A' }}</td>
                                                    <td>
                                                        <span class="badge bg-{{ ($liveClass['status'] ?? null) === 'live' ? 'success' : (($liveClass['status'] ?? null) === 'scheduled' ? 'primary' : 'secondary') }}">
                                                            {{ ucfirst($liveClass['status'] ?? 'Unknown') }}
                                                        </span>
                                                    </td>
                                                    <td class="text-muted">{{ $liveClass['start_time'] ?? 'N/A' }}</td>
                                                    <td class="text-muted">{{ $liveClass['end_time'] ?? 'N/A' }}</td>
                                                    <td>
                                                        <div class="btn-group btn-group-sm" role="group">
                                                            <button type="button" class="btn btn-outline-primary" onclick="editLiveClass({{ $liveClass['id'] ?? '' }})">Edit</button>
                                                            @if(($liveClass['status'] ?? null) === 'scheduled')
                                                                <button type="button" class="btn btn-outline-success" onclick="startLiveClass({{ $liveClass['id'] ?? '' }})">Start</button>
                                                            @endif
                                                            <button type="button" class="btn btn-outline-danger" onclick="deleteLiveClass({{ $liveClass['id'] ?? '' }})">Delete</button>
                                                        </div>
                                                    </td>
                                                </tr>
                                                @endif
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @else
                                <div class="alert alert-info mb-0">
                                    <p class="mb-0">No live classes yet. <a href="#" data-bs-toggle="modal" data-bs-target="#createModal">Create your first live class</a>.</p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Create Live Class Modal -->
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
                                <label for="course_id" class="form-label">Course ID <span class="text-danger">*</span></label>
                                <input type="number" id="course_id" name="course_id" required class="form-control" placeholder="e.g., 5">
                            </div>
                            <div class="col-12">
                                <label for="description" class="form-label">Description</label>
                                <textarea id="description" name="description" rows="3" class="form-control" placeholder="e.g., Q&A and assignment review."></textarea>
                            </div>
                            <div class="col-12 col-md-6">
                                <label for="start_time" class="form-label">Start Time <span class="text-danger">*</span></label>
                                <input type="datetime-local" id="start_time" name="start_time" required class="form-control">
                            </div>
                            <div class="col-12 col-md-6">
                                <label for="end_time" class="form-label">End Time <span class="text-danger">*</span></label>
                                <input type="datetime-local" id="end_time" name="end_time" required class="form-control">
                            </div>

                            <div class="col-12 col-md-6 d-flex align-items-center">
                                <div class="form-check">
                                    <input type="checkbox" id="is_recorded" name="is_recorded" value="1" class="form-check-input">
                                    <label class="form-check-label" for="is_recorded">Is Recorded</label>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" form="createLiveClassForm" class="btn btn-primary">Create Live Class</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script>
    const csrfToken = document
        .querySelector('meta[name="csrf-token"]')
        .getAttribute('content');

    let editingLiveClassId = null;

    /*
    |--------------------------------------------------------------------------
    | Create / Update Live Class
    |--------------------------------------------------------------------------
    */

    document
        .getElementById('createLiveClassForm')
        .addEventListener('submit', async function (e) {
            e.preventDefault();

            try {
                const formData = new FormData(this);
                const data = Object.fromEntries(formData.entries());

                data.is_recorded = formData.has('is_recorded');

                if (data.start_time) {
                    data.start_time = data.start_time.replace('T', ' ') + ':00';
                }

                if (data.end_time) {
                    data.end_time = data.end_time.replace('T', ' ') + ':00';
                }

                const result = editingLiveClassId
                    ? await window.LiveClassService.updateLiveClass(editingLiveClassId, data)
                    : await window.LiveClassService.createLiveClass(data);

                if (!result.success) {
                    alert(result.message || 'Unable to save live class.');
                    return;
                }

                alert(editingLiveClassId ? 'Live class updated successfully!' : 'Live class created successfully!');

                this.reset();
                editingLiveClassId = null;

                const modal = bootstrap.Modal.getInstance(document.getElementById('createModal'));
                if (modal) {
                    modal.hide();
                }

                window.location.reload();
            } catch (error) {
                console.error('FULL ERROR:', error);
                alert(error.message || 'Server error occurred.');
            }
        });

    /*
    |--------------------------------------------------------------------------
    | Edit Live Class
    |--------------------------------------------------------------------------
    */

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

    /*
    |--------------------------------------------------------------------------
    | Delete Live Class
    |--------------------------------------------------------------------------
    */

    async function deleteLiveClass(id) {
        if (!confirm('Delete this live class?')) {
            return;
        }

        try {
            const result = await window.LiveClassService.deleteLiveClass(id);

            if (!result.success) {
                alert(result.message || 'Delete failed');
                return;
            }

            alert('Live class deleted successfully!');
            window.location.reload();
        } catch (error) {
            console.error(error);
            alert('Server error while deleting.');
        }
    }

    /*
    |--------------------------------------------------------------------------
    | Start Live Class
    |--------------------------------------------------------------------------
    */

    async function startLiveClass(id) {
        if (!confirm('Start this live class now?')) {
            return;
        }

        try {
            const result = await window.LiveClassService.startLiveClass(id);

            if (!result.success) {
                alert(result.message || 'Failed to start live class');
                return;
            }

            alert('Live class started successfully!');
            
            // Redirect to room
            if (result.data && result.data.live_class) {
                window.location.href = `/student/live-classes/${id}/room`;
            } else {
                window.location.reload();
            }

        } catch (error) {

            console.error(error);

            alert('Server error while starting class.');
        }
    }

    /*
    |--------------------------------------------------------------------------
    | Format Date
    |--------------------------------------------------------------------------
    */

    function formatDateTimeLocal(dateString) {

        if (!dateString) {
            return '';
        }

        const date = new Date(dateString);

        return date.toISOString().slice(0, 16);
    }

    /*
    |--------------------------------------------------------------------------
    | Reset Modal
    |--------------------------------------------------------------------------
    */

    document
        .getElementById('createModal')
        .addEventListener('hidden.bs.modal', function () {

            editingLiveClassId = null;

            document
                .getElementById('createLiveClassForm')
                .reset();

            document.querySelector(
                '#createModal .modal-title'
            ).textContent = 'Create New Live Class';
        });
</script>
@endpush
