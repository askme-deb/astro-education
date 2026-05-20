@extends('layouts.app')

@section('title', 'Live Class Room')

@push('styles')
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Syne:wght@400;600;700;800&family=DM+Sans:opsz,wght@9..40,300;9..40,400;9..40,500&display=swap" rel="stylesheet">

<style>

    /* ─── Tokens ──────────────────────────────────────────── */
    :root {
        --ink:       #07090f;
        --panel:     #111827;
        --panel-alt: #161e2e;
        --border:    rgba(255,255,255,.07);
        --border-hi: rgba(255,255,255,.14);
        --text:      #e2e8f0;
        --muted:     #64748b;
        --accent:    #f97316;
        --accent-lo: rgba(249,115,22,.12);
        --red:       #ef4444;
        --red-lo:    rgba(239,68,68,.12);
        --green:     #22c55e;
        --radius-sm: 10px;
        --radius-md: 16px;
        --radius-lg: 24px;
        --shadow:    0 8px 32px rgba(0,0,0,.5);
    }

    *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

    body {
        background: var(--ink);
        font-family: 'DM Sans', sans-serif;
        color: var(--text);
        min-height: 100vh;
        overflow-x: hidden;
    }

    body::before {
        content: '';
        position: fixed;
        inset: 0;
        background-image:
            linear-gradient(rgba(249,115,22,.025) 1px, transparent 1px),
            linear-gradient(90deg, rgba(249,115,22,.025) 1px, transparent 1px);
        background-size: 48px 48px;
        pointer-events: none;
        z-index: 0;
    }

    /* ─── Shell ───────────────────────────────────────────── */
    .room {
        position: relative;
        z-index: 1;
        display: grid;
        grid-template-rows: auto 1fr auto;
        min-height: 100vh;
    }

    /* ─── Header ──────────────────────────────────────────── */
    .room__header {
        padding: 18px 28px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 14px;
        flex-wrap: wrap;
        border-bottom: 1px solid var(--border);
        background: rgba(7,9,15,.8);
        backdrop-filter: blur(12px);
        position: sticky;
        top: 0;
        z-index: 10;
    }

    .room__title {
        font-family: 'Syne', sans-serif;
        font-size: clamp(16px, 2vw, 22px);
        font-weight: 800;
        color: #fff;
        letter-spacing: -.4px;
    }

    .room__subtitle {
        font-size: 13px;
        color: var(--muted);
        margin-top: 3px;
    }

    .room__subtitle span { color: var(--text); }

    .header-badges { display: flex; align-items: center; gap: 10px; }

    .live-badge {
        display: flex;
        align-items: center;
        gap: 7px;
        background: var(--red-lo);
        border: 1px solid rgba(239,68,68,.25);
        color: var(--red);
        padding: 6px 14px;
        border-radius: 999px;
        font-family: 'Syne', sans-serif;
        font-size: 11px;
        font-weight: 700;
        letter-spacing: 1.5px;
        text-transform: uppercase;
    }

    .live-dot {
        width: 7px; height: 7px;
        background: var(--red);
        border-radius: 50%;
        animation: livePulse 1.4s ease-in-out infinite;
    }

    @keyframes livePulse {
        0%, 100% { transform: scale(1); opacity: 1; }
        50%       { transform: scale(1.7); opacity: .35; }
    }

    .count-badge {
        display: flex;
        align-items: center;
        gap: 6px;
        background: var(--panel);
        border: 1px solid var(--border);
        color: var(--muted);
        padding: 6px 13px;
        border-radius: 999px;
        font-size: 13px;
    }

    .count-badge i { color: var(--accent); font-size: 12px; }

    /* ─── Body ────────────────────────────────────────────── */
    .room__body {
        padding: 20px 28px;
        display: grid;
        grid-template-columns: 1fr 300px;
        gap: 18px;
        align-items: start;
    }

    @media (max-width: 960px) {
        .room__body { grid-template-columns: 1fr; }
    }

    @media (max-width: 768px) {
        .room__header {
            flex-direction: column;
            align-items: flex-start;
            gap: 12px;
        }
        
        .header-badges {
            flex-wrap: wrap;
        }
    }

    /* ─── Stage ───────────────────────────────────────────── */
    .stage {
        background: #000;
        border-radius: var(--radius-lg);
        overflow: hidden;
        border: 1px solid var(--border);
        min-height: 520px;
        position: relative;
    }

    /* Lobby */
    .lobby {
        min-height: 520px;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        text-align: center;
        padding: 48px 28px;
        background: radial-gradient(ellipse 70% 55% at 50% 100%, rgba(249,115,22,.09) 0%, transparent 70%);
    }

    .lobby__icon {
        width: 88px; height: 88px;
        border-radius: 50%;
        background: var(--accent-lo);
        border: 1px solid rgba(249,115,22,.22);
        display: flex; align-items: center; justify-content: center;
        margin-bottom: 24px;
        position: relative;
    }

    .lobby__icon::after {
        content: '';
        position: absolute;
        inset: -9px;
        border-radius: 50%;
        border: 1px solid rgba(249,115,22,.08);
        animation: ringPulse 2.2s ease-in-out infinite;
    }

    @keyframes ringPulse {
        0%, 100% { transform: scale(1); opacity: 1; }
        50%       { transform: scale(1.1); opacity: .2; }
    }

    .lobby__icon i { font-size: 36px; color: var(--accent); }

    .lobby__title {
        font-family: 'Syne', sans-serif;
        font-size: clamp(20px, 2.8vw, 30px);
        font-weight: 800;
        color: #fff;
        letter-spacing: -.4px;
        margin-bottom: 18px;
    }

    .lobby__chips {
        display: flex;
        flex-wrap: wrap;
        gap: 8px;
        justify-content: center;
        margin-bottom: 32px;
    }

    .lobby__chip {
        display: flex; align-items: center; gap: 7px;
        background: var(--panel-alt);
        border: 1px solid var(--border-hi);
        border-radius: var(--radius-sm);
        padding: 7px 14px;
        font-size: 13px;
        color: var(--muted);
    }

    .lobby__chip strong { color: var(--text); font-weight: 500; }
    .lobby__chip i { color: var(--accent); font-size: 13px; }

    .join-btn {
        display: inline-flex;
        align-items: center;
        gap: 9px;
        background: var(--accent);
        color: #fff;
        border: none;
        padding: 15px 36px;
        border-radius: var(--radius-md);
        font-family: 'Syne', sans-serif;
        font-size: 15px;
        font-weight: 700;
        cursor: pointer;
        transition: all .2s;
    }

    .join-btn:hover:not(:disabled) {
        transform: translateY(-2px);
        box-shadow: 0 10px 26px rgba(249,115,22,.35);
    }

    .join-btn:disabled { opacity: .65; cursor: not-allowed; }

    /* ─── Spinner ─────────────────────────────────────────── */
    .spinner {
        width: 13px; height: 13px;
        border: 2px solid rgba(255,255,255,.3);
        border-top-color: #fff;
        border-radius: 50%;
        animation: spin .6s linear infinite;
        display: inline-block;
        vertical-align: middle;
    }

    @keyframes spin { to { transform: rotate(360deg); } }

    /* ─── Toast ───────────────────────────────────────────── */
    .toast-stack {
        position: fixed;
        bottom: 90px; right: 20px;
        display: flex;
        flex-direction: column;
        gap: 7px;
        z-index: 9999;
        pointer-events: none;
    }

    .toast {
        background: var(--panel);
        border: 1px solid var(--border-hi);
        border-radius: var(--radius-md);
        padding: 11px 16px;
        font-size: 13px;
        color: var(--text);
        box-shadow: var(--shadow);
        display: flex;
        align-items: center;
        gap: 9px;
        max-width: 270px;
        animation: tIn .22s ease both;
        pointer-events: auto;
    }

    .toast i { color: var(--accent); flex-shrink: 0; }

    @keyframes tIn  { from { opacity: 0; transform: translateX(16px); } to { opacity: 1; transform: none; } }
    @keyframes tOut { from { opacity: 1; transform: none; } to { opacity: 0; transform: translateX(16px); } }

    .room--prebuilt {
        min-height: 100vh;
        background: #05070d;
    }

    .room--prebuilt .room__header {
        position: static;
        padding: 14px 22px;
        background: #05070d;
    }

    .room--prebuilt .room__body {
        display: block;
        padding: 0 22px 22px;
    }

    .room--prebuilt .stage {
        min-height: calc(100vh - 112px);
        border-radius: 18px;
        border: 1px solid var(--border);
        box-shadow: none;
    }

    .videosdk-root {
        width: 100%;
        height: calc(100vh - 112px);
        min-height: 620px;
        background: #05070d;
        border-radius: 18px;
        overflow: hidden;
    }

</style>
@endpush

@section('content')

@php
    $jsPayload = $jsPayload ?? [];
    $classData = $jsPayload['live_class'] ?? [];
    $role = $jsPayload['role'] ?? 'participant';
    $meetingId = $jsPayload['meeting_id'] ?? '';
    $userName = $jsPayload['user_name'] ?? 'User';
    $instructor = trim(
        ($classData['instructor']['first_name'] ?? '') . ' ' .
        ($classData['instructor']['last_name']  ?? '')
    );
@endphp

<div class="room room--prebuilt">

    {{-- Header --}}
    <header class="room__header">
        <div>
            <div class="room__title">{{ $classData['title'] ?? 'Live Class' }}</div>
            <div class="room__subtitle">
                Instructor: <span>{{ $instructor ?: '—' }}</span>
            </div>
        </div>
        <div class="header-badges">
            <div class="status-badge" id="connectionStatus">
                <i class="bi bi-circle-fill text-success"></i>
                <span>Connected</span>
            </div>
            <div class="live-badge">
                <span class="live-dot"></span>
                Live Now
            </div>
        </div>
    </header>

    {{-- Body --}}
    <main class="room__body">

        {{-- Stage --}}
        <div class="stage" id="stage">
            <div class="lobby" id="lobby">

                <div class="lobby__icon">
                    <i class="bi bi-camera-video-fill"></i>
                </div>

                <div class="lobby__title">{{ $classData['title'] ?? 'Meeting Room' }}</div>

                <div class="lobby__chips">
                    <div class="lobby__chip">
                        <i class="bi bi-hash"></i>
                        Room ID:&nbsp;<strong>{{ $meetingId }}</strong>
                    </div>
                    <div class="lobby__chip">
                        <i class="bi bi-person-fill"></i>
                        <strong>{{ $userName }}</strong>
                    </div>
                    <div class="lobby__chip">
                        <i class="bi bi-shield-fill"></i>
                        <strong>{{ ucfirst($role) }}</strong>
                    </div>
                </div>

                <button class="join-btn" id="joinBtn" type="button">
                    <i class="bi bi-box-arrow-in-right"></i>
                    Join Live Class
                </button>

            </div>
        </div>

    </main>

</div>

<div class="toast-stack" id="toasts"></div>

@endsection

@push('scripts')

<script src="https://sdk.videosdk.live/rtc-js-prebuilt/0.3.43/rtc-js-prebuilt.js"></script>

<script>

    let PAYLOAD = @json($jsPayload);
    const USER_ROLE = PAYLOAD.role || 'participant';
    let meetingInitialized = false;

    const $ = id => document.getElementById(id);

    function toast(msg, icon = 'bi-info-circle-fill') {
        const el = document.createElement('div');
        el.className = 'toast';
        el.innerHTML = `<i class="bi ${icon}"></i><span>${msg}</span>`;
        $('toasts').appendChild(el);
        setTimeout(() => {
            el.style.animation = 'tOut .22s ease both';
            el.addEventListener('animationend', () => el.remove(), { once: true });
        }, 3400);
    }

    function updateConnectionStatus(status, message) {
        const statusEl = $('connectionStatus');
        if (!statusEl) return;

        const iconClass = {
            'connected': 'bi-circle-fill text-success',
            'connecting': 'bi-circle-fill text-warning',
            'disconnected': 'bi-circle-fill text-danger',
            'reconnecting': 'bi-arrow-repeat text-warning'
        }[status] || 'bi-circle-fill text-success';

        statusEl.innerHTML = `<i class="bi ${iconClass}"></i><span>${message}</span>`;
    }

    function disableJoinButton() {
        const btn = $('joinBtn');
        btn.disabled = true;
        btn.innerHTML = '<span class="spinner"></span> Joining…';
    }

    function resetJoinButton() {
        const btn = $('joinBtn');
        btn.disabled = false;
        btn.innerHTML = '<i class="bi bi-box-arrow-in-right"></i> Join Live Class';
    }

    function validatePayload() {
        if (!PAYLOAD.api_key) return 'VideoSDK API key is missing. Please set VIDEOSDK_API_KEY in .env.';
        if (!PAYLOAD.meeting_id) return 'No meeting ID found.';
        if (!PAYLOAD.participant_id) return 'No participant ID found.';
        return null;
    }

    function extractApiKeyFromJwt(token) {
        try {
            const payload = JSON.parse(atob(String(token || '').split('.')[1].replace(/-/g, '+').replace(/_/g, '/')));
            return payload.apikey || payload.apiKey || null;
        } catch (error) {
            return null;
        }
    }

    function normalizeJoinPayload(data) {
        const payload = data?.data?.join_payload || data?.join_payload || data?.data || data || {};

        return {
            ...PAYLOAD,
            ...payload,
            token: payload.token || payload.auth_token || payload.jwt || PAYLOAD.token,
            api_key: payload.api_key || payload.apiKey || PAYLOAD.api_key || extractApiKeyFromJwt(payload.token || PAYLOAD.token),
            meeting_id: payload.meeting_id || payload.room_id || payload.meetingId || PAYLOAD.meeting_id,
            participant_id: String(payload.participant_id || payload.user_id || PAYLOAD.participant_id || ''),
            user_name: payload.user_name || payload.name || PAYLOAD.user_name || 'User',
            role: payload.role || PAYLOAD.role || 'participant',
        };
    }

    async function refreshJoinPayload() {
        const liveClassId = PAYLOAD.live_class?.id;
        if (!liveClassId) return;

        const response = await fetch(`/student/live-classes/${liveClassId}/join`, {
            method: 'GET',
            credentials: 'same-origin',
            headers: {
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest',
            },
        });

        const result = await response.json().catch(() => ({}));

        if (!response.ok || result.success === false) {
            throw new Error(result.message || 'Unable to get live class join credentials.');
        }

        PAYLOAD = normalizeJoinPayload(result);
        console.log('Refreshed join payload status:', {
            meetingId: PAYLOAD.meeting_id,
            participantId: PAYLOAD.participant_id,
            hasToken: Boolean(PAYLOAD.token),
            hasApiKey: Boolean(PAYLOAD.api_key),
            role: PAYLOAD.role,
            debug: PAYLOAD.debug || {},
        });
    }

    window.joinMeeting = async function joinMeeting() {
        if (meetingInitialized) {
            toast('You are already in this class.', 'bi-info-circle-fill');
            return;
        }

        if (typeof window.VideoSDKMeeting !== 'function') {
            toast('Video SDK failed to load. Please refresh.', 'bi-exclamation-triangle-fill');
            return;
        }

        disableJoinButton();
        updateConnectionStatus('connecting', 'Connecting');

        try {
            await refreshJoinPayload();

            const payloadError = validatePayload();
            if (payloadError) {
                throw new Error(payloadError);
            }

            const isHost = USER_ROLE === 'host' || USER_ROLE === 'instructor';
            $('stage').innerHTML = '<div id="videosdk-meeting-root" class="videosdk-root"></div>';
            updateConnectionStatus('connecting', 'Joining');
            meetingInitialized = true;

            new window.VideoSDKMeeting().init({
                name: PAYLOAD.user_name || 'User',
                meetingId: PAYLOAD.meeting_id,
                apiKey: PAYLOAD.api_key,
                containerId: 'videosdk-meeting-root',
                micEnabled: true,
                webcamEnabled: true,
                participantCanToggleSelfWebcam: true,
                participantCanToggleSelfMic: true,
                chatEnabled: true,
                screenShareEnabled: isHost,
                joinScreen: {
                    visible: false,
                    title: PAYLOAD.live_class?.title || 'Live Class',
                    meetingUrl: window.location.href
                },
                joinWithoutUserInteraction: true,
                participantCanLeave: true,
                participantCanEndMeeting: isHost,
                redirectOnLeave: isHost ? "{{ route('instructor.dashboard') }}" : "{{ route('student.my-live-classes') }}",
                notificationSoundEnabled: true,
                layout: 'GRID'
            });

            updateConnectionStatus('connected', 'Connected');
            toast(`Joined as ${PAYLOAD.user_name}`, 'bi-check-circle-fill');
        } catch (error) {
            console.error('Join failed:', error);
            meetingInitialized = false;
            updateConnectionStatus('disconnected', 'Failed');
            toast(error.message || 'Failed to join live class.', 'bi-exclamation-triangle-fill');
            resetJoinButton();
        }
    };

    function attachJoinButton() {
        const joinButton = $('joinBtn');
        if (!joinButton) {
            console.error('Join button not found.');
            return;
        }

        joinButton.onclick = event => {
            event.preventDefault();
            console.log('Join Live Class button clicked.');
            toast('Joining live class...', 'bi-box-arrow-in-right');
            if (!meetingInitialized) window.joinMeeting();
        };
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', attachJoinButton);
    } else {
        attachJoinButton();
    }

</script>
@endpush
