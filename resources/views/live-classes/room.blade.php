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
        .room__sidebar { display: none; }
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

    /* Video grid */
    #video-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(260px, 1fr));
        gap: 10px;
        padding: 14px;
        min-height: 520px;
        align-content: start;
    }

    .p-card {
        border-radius: var(--radius-md);
        overflow: hidden;
        background: var(--panel);
        border: 1px solid var(--border);
        position: relative;
        aspect-ratio: 16/10;
        animation: cardIn .3s ease both;
    }

    @keyframes cardIn {
        from { opacity: 0; transform: scale(.94); }
        to   { opacity: 1; transform: scale(1); }
    }

    .p-card video {
        width: 100%; height: 100%;
        object-fit: cover;
        display: block;
    }

    /* hidden audio elements — required by VideoSDK for remote audio */
    .p-card audio { display: none; }

    .p-card__placeholder {
        position: absolute;
        inset: 0;
        display: flex;
        align-items: center;
        justify-content: center;
        background: linear-gradient(135deg, var(--panel) 0%, var(--panel-alt) 100%);
    }

    .p-card__avatar {
        width: 52px; height: 52px;
        border-radius: 50%;
        background: var(--accent-lo);
        border: 2px solid rgba(249,115,22,.28);
        display: flex; align-items: center; justify-content: center;
        font-family: 'Syne', sans-serif;
        font-size: 20px;
        font-weight: 800;
        color: var(--accent);
    }

    .p-card__name {
        position: absolute;
        left: 10px; bottom: 10px;
        background: rgba(7,9,15,.72);
        backdrop-filter: blur(8px);
        color: #fff;
        padding: 4px 11px;
        border-radius: 999px;
        font-size: 12px;
        font-weight: 500;
        display: flex;
        align-items: center;
        gap: 5px;
        border: 1px solid var(--border-hi);
        pointer-events: none;
    }

    .p-card__name .mic-on  { font-size: 10px; color: var(--green); }
    .p-card__name .mic-off { font-size: 10px; color: var(--red); }

    /* ─── Sidebar ─────────────────────────────────────────── */
    .room__sidebar { display: flex; flex-direction: column; gap: 14px; }

    .s-card {
        background: var(--panel);
        border: 1px solid var(--border);
        border-radius: var(--radius-md);
        overflow: hidden;
    }

    .s-card__head {
        padding: 13px 16px;
        border-bottom: 1px solid var(--border);
        font-family: 'Syne', sans-serif;
        font-size: 12px;
        font-weight: 700;
        color: var(--text);
        text-transform: uppercase;
        letter-spacing: 1px;
        display: flex;
        align-items: center;
        gap: 7px;
    }

    .s-card__head i { color: var(--accent); }
    .s-card__head .badge-count {
        margin-left: auto;
        font-family: 'DM Sans', sans-serif;
        font-size: 11px;
        color: var(--muted);
        font-weight: 400;
        text-transform: none;
        letter-spacing: 0;
    }

    /* Participants list */
    .p-list {
        padding: 8px;
        display: flex;
        flex-direction: column;
        gap: 2px;
        max-height: 240px;
        overflow-y: auto;
    }

    .p-list::-webkit-scrollbar { width: 3px; }
    .p-list::-webkit-scrollbar-thumb { background: var(--border-hi); border-radius: 3px; }

    .p-row {
        display: flex;
        align-items: center;
        gap: 9px;
        padding: 8px 9px;
        border-radius: 8px;
        transition: background .15s;
    }

    .p-row:hover { background: var(--panel-alt); }

    .p-row__av {
        width: 30px; height: 30px;
        border-radius: 50%;
        background: var(--accent-lo);
        border: 1px solid rgba(249,115,22,.2);
        display: flex; align-items: center; justify-content: center;
        font-family: 'Syne', sans-serif;
        font-size: 12px;
        font-weight: 700;
        color: var(--accent);
        flex-shrink: 0;
    }

    .p-row__name {
        font-size: 13px;
        color: var(--text);
        flex: 1;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    .p-row__icons { display: flex; gap: 5px; }
    .p-row__icons i { font-size: 11px; color: var(--muted); }
    .p-row__icons .on  { color: var(--green); }
    .p-row__icons .off { color: var(--red); }

    /* Chat */
    .chat-msgs {
        padding: 10px;
        display: flex;
        flex-direction: column;
        gap: 9px;
        min-height: 160px;
        max-height: 200px;
        overflow-y: auto;
    }

    .chat-msgs::-webkit-scrollbar { width: 3px; }
    .chat-msgs::-webkit-scrollbar-thumb { background: var(--border-hi); border-radius: 3px; }

    .chat-msg__author { font-size: 11px; font-weight: 600; color: var(--accent); }
    .chat-msg__text   { font-size: 13px; color: var(--text); line-height: 1.45; margin-top: 2px; }

    .s-empty {
        display: flex; align-items: center; justify-content: center;
        padding: 20px;
        font-size: 12px;
        color: var(--muted);
        font-style: italic;
    }

    .chat-bar {
        display: flex;
        gap: 7px;
        padding: 10px;
        border-top: 1px solid var(--border);
    }

    .chat-bar input {
        flex: 1;
        background: var(--panel-alt);
        border: 1px solid var(--border-hi);
        border-radius: 8px;
        color: var(--text);
        font-family: 'DM Sans', sans-serif;
        font-size: 13px;
        padding: 8px 11px;
        outline: none;
        transition: border-color .2s;
    }

    .chat-bar input::placeholder { color: var(--muted); }
    .chat-bar input:focus { border-color: var(--accent); }

    .chat-bar button {
        background: var(--accent);
        border: none;
        border-radius: 8px;
        color: #fff;
        padding: 8px 13px;
        cursor: pointer;
        font-size: 13px;
        transition: opacity .15s;
    }

    .chat-bar button:hover { opacity: .85; }

    /* ─── Footer Controls ─────────────────────────────────── */
    .room__footer {
        padding: 18px 28px;
        border-top: 1px solid var(--border);
        background: rgba(7,9,15,.85);
        backdrop-filter: blur(12px);
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 10px;
        flex-wrap: wrap;
        position: sticky;
        bottom: 0;
        z-index: 10;
    }

    .ctrl {
        display: inline-flex;
        align-items: center;
        gap: 7px;
        border: none;
        border-radius: var(--radius-md);
        font-family: 'DM Sans', sans-serif;
        font-size: 13px;
        font-weight: 500;
        padding: 11px 20px;
        cursor: pointer;
        transition: all .2s;
    }

    .ctrl:hover:not(:disabled) { transform: translateY(-2px); }
    .ctrl:active:not(:disabled) { transform: translateY(0); }

    .ctrl:disabled {
        opacity: .35;
        cursor: not-allowed;
        transform: none !important;
    }

    .ctrl--default {
        background: var(--panel);
        border: 1px solid var(--border-hi);
        color: var(--text);
    }

    .ctrl--default:hover:not(:disabled) { background: var(--panel-alt); }

    .ctrl--muted {
        background: var(--accent-lo);
        border: 1px solid rgba(249,115,22,.3);
        color: var(--accent);
    }

    .ctrl--danger {
        background: var(--red-lo);
        border: 1px solid rgba(239,68,68,.3);
        color: var(--red);
    }

    .ctrl--danger:hover:not(:disabled) {
        background: var(--red);
        color: #fff;
        border-color: var(--red);
    }

    .ctrl-sep {
        width: 1px;
        height: 32px;
        background: var(--border-hi);
        border-radius: 1px;
    }

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

</style>
@endpush

@section('content')

@php
    $meetingId  = $joinPayload['room_id']   ?? $joinPayload['meeting_id'] ?? '';
    $userId     = $joinPayload['user_id']   ?? '';
    $token      = $joinPayload['token']     ?? '';
    $userName   = auth()->user()->name ?? auth()->user()->email ?? 'User';
    $role       = $joinPayload['role']         ?? 'participant';
    $classData  = $joinPayload['live_class']   ?? [];
    $instructor = trim(
        ($classData['instructor']['first_name'] ?? '') . ' ' .
        ($classData['instructor']['last_name']  ?? '')
    );

    // Extract participant_id from live_class data if not in join_payload
    // For instructors, use instructor_id; for students, use auth user ID
    if (empty($userId)) {
        if ($role === 'host' || $role === 'instructor') {
            $userId = $classData['instructor']['id'] ?? auth()->id();
        } else {
            // For students, use the authenticated user's ID
            $userId = auth()->id();
        }
    }

    // Construct payload for JavaScript with correct field names
    $jsPayload = [
        'token' => $token,
        'meeting_id' => $meetingId,
        'participant_id' => (string) $userId,
        'user_name' => $userName,
        'role' => $role,
        'live_class' => $classData,
    ];
@endphp

<div class="room">

    {{-- Header --}}
    <header class="room__header">
        <div>
            <div class="room__title">{{ $classData['title'] ?? 'Live Class' }}</div>
            <div class="room__subtitle">
                Instructor: <span>{{ $instructor ?: '—' }}</span>
            </div>
        </div>
        <div class="header-badges">
            <div class="count-badge">
                <i class="bi bi-people-fill"></i>
                <span id="countLabel">0</span>&nbsp;in room
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

                <button class="join-btn" id="joinBtn">
                    <i class="bi bi-box-arrow-in-right"></i>
                    Join Live Class
                </button>

            </div>
        </div>

        {{-- Sidebar --}}
        <aside class="room__sidebar">

            <div class="s-card">
                <div class="s-card__head">
                    <i class="bi bi-people-fill"></i>
                    Participants
                    <span class="badge-count" id="sidebarCount">0 joined</span>
                </div>
                <div class="p-list" id="pList">
                    <div class="s-empty">Waiting for participants…</div>
                </div>
            </div>

            <div class="s-card">
                <div class="s-card__head">
                    <i class="bi bi-chat-dots-fill"></i>
                    Class Chat
                </div>
                <div class="chat-msgs" id="chatMsgs">
                    <div class="s-empty" id="chatEmpty">No messages yet</div>
                </div>
                <div class="chat-bar">
                    <input id="chatInput" type="text" placeholder="Send a message…" maxlength="200" autocomplete="off" />
                    <button id="chatSend"><i class="bi bi-send-fill"></i></button>
                </div>
            </div>

        </aside>

    </main>

    {{-- Footer --}}
    <footer class="room__footer">

        <button class="ctrl ctrl--default" id="btnMic" disabled>
            <i class="bi bi-mic-fill"></i> Mute
        </button>

        <button class="ctrl ctrl--default" id="btnCam" disabled>
            <i class="bi bi-camera-video-fill"></i> Stop Video
        </button>

        <button class="ctrl ctrl--default" id="btnScreen" disabled>
            <i class="bi bi-display"></i> Share Screen
        </button>

        <div class="ctrl-sep"></div>

        <button class="ctrl ctrl--danger" id="btnLeave">
            <i class="bi bi-box-arrow-left"></i> Leave Class
        </button>

    </footer>

</div>

<div class="toast-stack" id="toasts"></div>

@endsection

@push('scripts')

{{-- Latest VideoSDK JS SDK (browser-native build) --}}
<script src="https://sdk.videosdk.live/js-sdk/0.1.6/videosdk.js"></script>

<script>

    /* ── Payload from server ──────────────────────────────── */
    const PAYLOAD = @json($jsPayload);

    /* ── State ────────────────────────────────────────────── */
    let meeting      = null;
    let micOn        = true;
    let camOn        = true;
    let screenOn     = false;
    let participants = {};   // id → participant

    /* ── DOM refs ─────────────────────────────────────────── */
    const $ = id => document.getElementById(id);

    /* ── Toast ────────────────────────────────────────────── */
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

    /* ── Utilities ────────────────────────────────────────── */
    function initials(name = '') {
        return name.trim().split(/\s+/).map(w => w[0] || '').join('').toUpperCase().slice(0, 2) || '?';
    }

    function refreshCount() {
        const n = Object.keys(participants).length;
        $('countLabel').textContent  = n;
        $('sidebarCount').textContent = `${n} joined`;
    }

    /* ── Sidebar participant row ──────────────────────────── */
    function addPRow(p) {
        const list = $('pList');
        list.querySelector('.s-empty')?.remove();
        const row = document.createElement('div');
        row.className = 'p-row';
        row.id = `pr-${p.id}`;
        row.innerHTML = `
            <div class="p-row__av">${initials(p.displayName)}</div>
            <div class="p-row__name">${p.displayName || 'Guest'}</div>
            <div class="p-row__icons">
                <i class="bi bi-mic-fill on"          id="prm-${p.id}"></i>
                <i class="bi bi-camera-video-fill on" id="prc-${p.id}"></i>
            </div>`;
        list.appendChild(row);
    }

    function removePRow(id) {
        $(`pr-${id}`)?.remove();
        if (!$('pList').querySelector('.p-row')) {
            $('pList').innerHTML = '<div class="s-empty">No participants yet</div>';
        }
    }

    /* ── Video + Audio card ───────────────────────────────── */
    function createCard(p) {
        const grid = $('video-grid');
        if (!grid) return;

        const isLocal = p.id === meeting.localParticipant.id;

        const card = document.createElement('div');
        card.className = 'p-card';
        card.id = `pc-${p.id}`;

        // Placeholder avatar
        const placeholder = document.createElement('div');
        placeholder.className = 'p-card__placeholder';
        placeholder.id = `ph-${p.id}`;
        placeholder.innerHTML = `<div class="p-card__avatar">${initials(p.displayName)}</div>`;

        // Video element
        const video = document.createElement('video');
        video.autoplay    = true;
        video.playsInline = true;
        video.muted       = isLocal;   // prevent echo for self
        video.style.display = 'none';
        video.id = `v-${p.id}`;

        // Audio element (required for remote audio, hidden)
        const audio = document.createElement('audio');
        audio.autoplay   = true;
        audio.playsInline = true;
        audio.id = `a-${p.id}`;

        // Name tag
        const name = document.createElement('div');
        name.className = 'p-card__name';
        name.innerHTML  = `
            <i class="bi bi-mic-fill mic-on" id="cm-${p.id}"></i>
            ${p.displayName || 'Guest'}
            ${isLocal ? '<em style="opacity:.4;font-style:normal"> (you)</em>' : ''}`;

        card.append(placeholder, video, audio, name);
        grid.appendChild(card);

        /* ── Stream enabled ── */
        p.on('stream-enabled', stream => {
            if (stream.kind === 'video') {
                const ms = new MediaStream([stream.track]);
                video.srcObject = ms;
                video.play().catch(() => {});
                video.style.display = 'block';
                placeholder.style.display = 'none';
                $(`prc-${p.id}`)?.classList.replace('off', 'on');
            }
            if (stream.kind === 'audio' && !isLocal) {
                const ms = new MediaStream([stream.track]);
                audio.srcObject = ms;
                audio.play().catch(() => {});
                $(`cm-${p.id}`)?.classList.replace('mic-off', 'mic-on');
                $(`prm-${p.id}`)?.classList.replace('off', 'on');
            }
        });

        /* ── Stream disabled ── */
        p.on('stream-disabled', stream => {
            if (stream.kind === 'video') {
                video.srcObject = null;
                video.style.display = 'none';
                placeholder.style.display = 'flex';
                $(`prc-${p.id}`)?.classList.replace('on', 'off');
            }
            if (stream.kind === 'audio') {
                audio.srcObject = null;
                $(`cm-${p.id}`)?.classList.replace('mic-on', 'mic-off');
                $(`prm-${p.id}`)?.classList.replace('on', 'off');
            }
        });

        /* ── Hydrate streams already active when we joined ── */
        p.streams.forEach(stream => {
            if (stream.kind === 'video') {
                const ms = new MediaStream([stream.track]);
                video.srcObject = ms;
                video.play().catch(() => {});
                video.style.display = 'block';
                placeholder.style.display = 'none';
            }
            if (stream.kind === 'audio' && !isLocal) {
                const ms = new MediaStream([stream.track]);
                audio.srcObject = ms;
                audio.play().catch(() => {});
            }
        });
    }

    /* ── Chat ─────────────────────────────────────────────── */
    function appendMsg(author, text) {
        $('chatEmpty')?.remove();
        const div = document.createElement('div');
        div.innerHTML = `
            <div class="chat-msg__author">${author}</div>
            <div class="chat-msg__text">${text}</div>`;
        $('chatMsgs').appendChild(div);
        $('chatMsgs').scrollTop = 9999;
    }

    function sendMsg() {
        if (!meeting) return;
        const inp = $('chatInput');
        const txt = inp.value.trim();
        if (!txt) return;
        meeting.pubSub.publish('CHAT', txt, { persist: false });
        appendMsg('You', txt);
        inp.value = '';
    }

    /* ── Join ─────────────────────────────────────────────── */
    function joinMeeting() {
        if (typeof window.VideoSDK === 'undefined') {
            toast('VideoSDK script failed to load — please refresh.', 'bi-exclamation-triangle-fill');
            return;
        }

        // Debug: Check payload
        console.log('Join payload:', PAYLOAD);

        if (!PAYLOAD.token) {
            toast('No token provided — cannot join meeting.', 'bi-exclamation-triangle-fill');
            return;
        }

        if (!PAYLOAD.meeting_id) {
            toast('No meeting ID provided — cannot join meeting.', 'bi-exclamation-triangle-fill');
            return;
        }

        if (!PAYLOAD.participant_id) {
            toast('No participant ID provided — cannot join meeting.', 'bi-exclamation-triangle-fill');
            return;
        }

        const btn = $('joinBtn');
        btn.disabled = true;
        btn.innerHTML = `<span class="spinner"></span> Joining…`;

        try {
            /* 1. Configure auth token */
            window.VideoSDK.config(PAYLOAD.token);
            console.log('VideoSDK configured with token');

            /* 2. Init meeting */
            meeting = window.VideoSDK.initMeeting({
                meetingId:     PAYLOAD.meeting_id,
                participantId: PAYLOAD.participant_id,
                name:          PAYLOAD.user_name,
                micEnabled:    true,
                webcamEnabled: true,
            });

            /* 3. Wire up LOCAL participant stream BEFORE joining
                  (docs say: set up localParticipant events right after initMeeting) */
            meeting.localParticipant.on('stream-enabled', stream => {
                const grid = $('video-grid');
                if (!grid) return;   // grid not yet mounted (unlikely but safe)
                const video = $(`v-${meeting.localParticipant.id}`);
                if (stream.kind === 'video' && video) {
                    const ms = new MediaStream([stream.track]);
                    video.srcObject = ms;
                    video.play().catch(() => {});
                    video.style.display = 'block';
                    $(`ph-${meeting.localParticipant.id}`).style.display = 'none';
                }
            });

            /* 4. Join */
            meeting.join();

            /* 5. Meeting joined → swap lobby for grid */
            meeting.on('meeting-joined', () => {
                $('stage').innerHTML = '<div id="video-grid"></div>';

                /* Enable control buttons */
                ['btnMic', 'btnCam', 'btnScreen'].forEach(id => $(id).disabled = false);

                /* Render local participant card */
                const lp = meeting.localParticipant;
                participants[lp.id] = lp;
                addPRow(lp);
                createCard(lp);
                refreshCount();

                toast(`Joined as ${PAYLOAD.user_name}`, 'bi-check-circle-fill');

                /* Subscribe to chat */
                meeting.pubSub.subscribe('CHAT', ({ message, senderName }) => {
                    appendMsg(senderName, message);
                });
            });

            /* 6. Remote participant joined */
            meeting.on('participant-joined', p => {
                participants[p.id] = p;
                addPRow(p);
                createCard(p);
                refreshCount();
                toast(`${p.displayName} joined`, 'bi-person-plus-fill');
            });

            /* 7. Remote participant left */
            meeting.on('participant-left', p => {
                delete participants[p.id];
                $(`pc-${p.id}`)?.remove();
                removePRow(p.id);
                refreshCount();
                toast(`${p.displayName} left`, 'bi-person-dash-fill');
            });

            /* 8. Meeting left */
            meeting.on('meeting-left', () => {
                window.location.href = "{{ route('live-classes.index') }}";
            });

            /* 9. Error handling */
            meeting.on('error', err => {
                console.error('VideoSDK error:', err);
                toast(err?.message || 'A meeting error occurred.', 'bi-exclamation-triangle-fill');
            });

        } catch (err) {
            console.error(err);
            toast(err.message || 'Failed to join.', 'bi-exclamation-triangle-fill');
            btn.disabled = false;
            btn.innerHTML = `<i class="bi bi-box-arrow-in-right"></i> Join Live Class`;
        }
    }

    /* ── Button listeners ─────────────────────────────────── */
    $('joinBtn').addEventListener('click', joinMeeting);

    $('btnMic').addEventListener('click', function () {
        if (!meeting) return;
        micOn = !micOn;
        micOn ? meeting.unmuteMic() : meeting.muteMic();
        this.className = micOn ? 'ctrl ctrl--default' : 'ctrl ctrl--muted';
        this.innerHTML = micOn
            ? `<i class="bi bi-mic-fill"></i> Mute`
            : `<i class="bi bi-mic-mute-fill"></i> Unmute`;
    });

    $('btnCam').addEventListener('click', function () {
        if (!meeting) return;
        camOn = !camOn;
        camOn ? meeting.enableWebcam() : meeting.disableWebcam();
        this.className = camOn ? 'ctrl ctrl--default' : 'ctrl ctrl--muted';
        this.innerHTML = camOn
            ? `<i class="bi bi-camera-video-fill"></i> Stop Video`
            : `<i class="bi bi-camera-video-off-fill"></i> Start Video`;
    });

    $('btnScreen').addEventListener('click', function () {
        if (!meeting) return;
        screenOn = !screenOn;
        if (screenOn) {
            meeting.enableScreenShare();
            this.className = 'ctrl ctrl--muted';
            this.innerHTML = `<i class="bi bi-display-fill"></i> Stop Share`;
        } else {
            meeting.disableScreenShare();
            this.className = 'ctrl ctrl--default';
            this.innerHTML = `<i class="bi bi-display"></i> Share Screen`;
        }
    });

    $('btnLeave').addEventListener('click', () => {
        meeting ? meeting.leave() : (window.location.href = "{{ route('live-classes.index') }}");
    });

    $('chatSend').addEventListener('click', sendMsg);
    $('chatInput').addEventListener('keydown', e => { if (e.key === 'Enter') sendMsg(); });

</script>
@endpush
