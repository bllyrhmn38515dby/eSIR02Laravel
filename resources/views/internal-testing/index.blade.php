@extends('layouts.app')

@push('styles')
<link href="https://fonts.googleapis.com/css2?family=JetBrains+Mono:wght@400;600&family=Inter:wght@400;600;700;800&display=swap" rel="stylesheet">
<style>
/* ═══════════════════════════════════════════════
   BASE & HERO
═══════════════════════════════════════════════ */
body { font-family: 'Inter', sans-serif; }

.tr-page {
    min-height: 100vh;
    padding: 2rem 0 5rem;
}

.hero {
    position: relative; overflow: hidden;
    background: linear-gradient(135deg, #0f0c29, #302b63, #24243e);
    border-radius: 24px; padding: 2.5rem 2.5rem 2rem;
    color: #fff; margin-bottom: 2rem;
    box-shadow: 0 20px 60px rgba(0,0,0,0.4);
}
.hero::before {
    content: ''; position: absolute; top: -80px; right: -80px;
    width: 320px; height: 320px; border-radius: 50%;
    background: radial-gradient(circle, rgba(99,102,241,0.3) 0%, transparent 70%); pointer-events: none;
}
.hero-grid {
    display: flex; align-items: flex-start; justify-content: space-between;
    flex-wrap: wrap; gap: 1.5rem; position: relative; z-index: 1;
}
.hero-eyebrow {
    font-size: 0.72rem; font-weight: 700; letter-spacing: 0.15em;
    text-transform: uppercase; color: #a5b4fc; margin-bottom: 0.4rem;
}
.hero h3 { font-size: 1.6rem; font-weight: 800; margin: 0 0 0.3rem; letter-spacing: -0.5px; }
.hero p { margin: 0; color: rgba(255,255,255,0.6); font-size: 0.9rem; }

.hero-progress-wrap { position: relative; z-index: 1; margin-top: 1.5rem; display: flex; align-items: center; gap: 1rem; }
.hero-progress-bar { flex: 1; height: 6px; background: rgba(255,255,255,0.12); border-radius: 99px; overflow: hidden; }
.hero-progress-fill { height: 100%; width: 0%; background: linear-gradient(90deg, #06d6a0, #4361ee); border-radius: 99px; transition: width 0.5s; }
.hero-progress-label { font-size: 0.78rem; color: rgba(255,255,255,0.55); font-family: 'JetBrains Mono', monospace; }

.btn-run {
    background: linear-gradient(135deg, #06d6a0, #059669); color: #fff; font-weight: 700;
    border: none; border-radius: 14px; padding: 0.75rem 1.8rem; font-size: 0.95rem;
    box-shadow: 0 4px 20px rgba(6,214,160,0.4); transition: all 0.25s; display: inline-flex; align-items: center; gap: 0.5rem;
}
.btn-run:hover:not(:disabled) { transform: translateY(-2px); box-shadow: 0 8px 28px rgba(6,214,160,0.5); }
.btn-run:disabled { opacity: 0.5; cursor: not-allowed; }

.btn-reset {
    background: rgba(255,255,255,0.08); color: rgba(255,255,255,0.75); font-weight: 600;
    border: 1px solid rgba(255,255,255,0.18); border-radius: 14px; padding: 0.75rem 1.5rem; font-size: 0.9rem;
    transition: all 0.2s; display: inline-flex; align-items: center; gap: 0.5rem;
}
.btn-reset:hover:not(:disabled) { background: rgba(255,255,255,0.15); color: #fff; }
.btn-reset:disabled { opacity: 0.35; cursor: not-allowed; }

/* ═══════════════════════════════════════════════
   SUITE ITEMS
═══════════════════════════════════════════════ */
.suite-timeline { position: relative; padding-left: 3.2rem; margin-bottom: 1.8rem; }
.suite-timeline::before {
    content: ''; position: absolute; left: 1.25rem; top: 1.5rem; bottom: 1.5rem;
    width: 2px; background: #e2e8f0;
}

.suite-item {
    position: relative; background: rgba(255,255,255,0.9); backdrop-filter: blur(16px);
    border: 1.5px solid #e8edf8; border-radius: 18px; padding: 1.1rem 1.4rem;
    margin-bottom: 0.8rem; display: flex; align-items: center; gap: 1rem;
    transition: all 0.3s; box-shadow: 0 2px 12px rgba(31,38,135,0.05);
}

.timeline-dot {
    position: absolute; left: -2.55rem; top: 50%; transform: translateY(-50%);
    width: 22px; height: 22px; border-radius: 50%; border: 2.5px solid #e2e8f0; background: #fff;
    transition: all 0.3s; box-shadow: 0 0 0 4px #f8faff; z-index: 1;
}

.suite-item.is-running .timeline-dot { border-color: #4361ee; background: #4361ee; box-shadow: 0 0 0 6px rgba(67,97,238,0.15), 0 0 16px rgba(67,97,238,0.4); animation: pulseDot 1.2s infinite; }
.suite-item.is-pass .timeline-dot { border-color: #06d6a0; background: #06d6a0; box-shadow: 0 0 0 5px rgba(6,214,160,0.15); }
.suite-item.is-fail .timeline-dot { border-color: #ef476f; background: #ef476f; box-shadow: 0 0 0 5px rgba(239,71,111,0.15); }
@keyframes pulseDot { 0%,100%{box-shadow: 0 0 0 4px rgba(67,97,238,0.15), 0 0 14px rgba(67,97,238,0.3);} 50%{box-shadow: 0 0 0 8px rgba(67,97,238,0.08), 0 0 24px rgba(67,97,238,0.5);} }

.suite-item.is-running { border-color: #a5b4fc; background: rgba(99,102,241,0.04); transform: translateX(6px); }
.suite-item.is-pass { border-color: #6ee7b7; background: rgba(6,214,160,0.04); }
.suite-item.is-fail { border-color: #fca5a5; background: rgba(239,71,111,0.04); }

.step-num {
    width: 40px; height: 40px; border-radius: 12px; display: flex; align-items: center; justify-content: center;
    font-size: 1rem; font-weight: 700; flex-shrink: 0; font-family: 'JetBrains Mono', monospace;
}
.step-num.idle    { background: #f1f5f9; color: #94a3b8; }
.step-num.running { background: rgba(67,97,238,0.12); color: #4361ee; }
.step-num.pass    { background: rgba(6,214,160,0.15); color: #05a87e; }
.step-num.fail    { background: rgba(239,71,111,0.15); color: #c0392b; }

.mini-spinner { width: 20px; height: 20px; border: 2.5px solid rgba(67,97,238,0.2); border-top-color: #4361ee; border-radius: 50%; animation: spin 0.65s linear infinite; }
@keyframes spin { to { transform: rotate(360deg); } }

.suite-info { flex: 1; min-width: 0; }
.suite-name { font-weight: 700; color: #1e293b; font-size: 0.95rem; margin: 0 0 0.15rem; }
.suite-desc { font-size: 0.8rem; color: #94a3b8; margin: 0; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }

.dur-badge { font-size: 0.76rem; font-weight: 700; padding: 0.3rem 0.85rem; border-radius: 20px; font-family: 'JetBrains Mono', monospace; }
.dur-idle    { background: #f1f5f9; color: #94a3b8; }
.dur-running { background: rgba(67,97,238,0.1); color: #4361ee; animation: pulseBadge 1s infinite; }
.dur-pass    { background: rgba(6,214,160,0.12); color: #05a87e; }
.dur-fail    { background: rgba(239,71,111,0.12); color: #c0392b; }
@keyframes pulseBadge { 0%,100%{opacity:1} 50%{opacity:0.6} }

/* Targeted Re-run button */
.btn-target-rerun {
    background: #fff; border: 1px solid #e2e8f0; color: #64748b;
    width: 32px; height: 32px; border-radius: 50%;
    display: flex; align-items: center; justify-content: center;
    cursor: pointer; transition: all 0.2s; box-shadow: 0 2px 5px rgba(0,0,0,0.05);
    opacity: 0; pointer-events: none; transform: scale(0.8);
}
.suite-item.is-pass .btn-target-rerun,
.suite-item.is-fail .btn-target-rerun {
    opacity: 1; pointer-events: auto; transform: scale(1);
}
.btn-target-rerun:hover { background: #f8fafc; color: #4361ee; border-color: #cbd5e1; transform: scale(1.1) rotate(15deg) !important; }
.btn-target-rerun:active { transform: scale(0.95) !important; }

/* ═══════════════════════════════════════════════
   TERMINAL
═══════════════════════════════════════════════ */
.terminal-wrap { background: #090c10; border-radius: 20px; overflow: hidden; box-shadow: 0 20px 60px rgba(0,0,0,0.4); margin-bottom: 1.8rem; }
.terminal-topbar { background: #161b22; padding: 0.75rem 1.2rem; display: flex; align-items: center; gap: 0.55rem; border-bottom: 1px solid #21262d; }
.tb-dot { width: 12px; height: 12px; border-radius: 50%; }
.tb-red { background: #ff5f57; } .tb-yellow { background: #ffbd2e; } .tb-green { background: #28c940; }
.tb-label { margin-left: 0.5rem; color: #484f58; font-size: 0.8rem; font-family: 'JetBrains Mono', monospace; font-weight: 600; flex: 1; }
.tb-status { font-size: 0.72rem; font-family: 'JetBrains Mono', monospace; padding: 0.2rem 0.6rem; border-radius: 6px; font-weight: 600; }
.tb-idle { background: #21262d; color: #484f58; }
.tb-running { background: rgba(67,97,238,0.2); color: #79c0ff; }
.tb-done-ok { background: rgba(6,214,160,0.15); color: #3fb950; }
.tb-done-fail { background: rgba(239,71,111,0.15); color: #f85149; }

#terminal-body {
    padding: 1.3rem 1.6rem 1.5rem; min-height: 220px; max-height: 340px; overflow-y: auto;
    font-family: 'JetBrains Mono', monospace; font-size: 0.82rem; line-height: 1.75; color: #c9d1d9; scroll-behavior: smooth;
}
#terminal-body::-webkit-scrollbar { width: 5px; }
#terminal-body::-webkit-scrollbar-track { background: #090c10; }
#terminal-body::-webkit-scrollbar-thumb { background: #30363d; border-radius: 3px; }

.log-line { display: block; animation: fadeLine 0.18s ease forwards; opacity: 0; }
@keyframes fadeLine { to { opacity: 1; } }
.log-pass  { color: #3fb950; } .log-fail  { color: #f85149; } .log-warn  { color: #e3b341; }
.log-info  { color: #79c0ff; } .log-dim   { color: #3d444d; } .log-sep   { color: #21262d; }
.log-header { color: #a5b4fc; font-weight: 600; }
.terminal-cursor { display: inline-block; width: 7px; height: 15px; background: #58a6ff; animation: blinkCursor 1.1s step-end infinite; vertical-align: middle; margin-left: 2px; }
@keyframes blinkCursor { 50% { opacity: 0; } }

/* ═══════════════════════════════════════════════
   SUMMARY BAR & CHART
═══════════════════════════════════════════════ */
#summary-bar { display: none; animation: slideUpFade 0.5s; margin-bottom: 2rem; }
@keyframes slideUpFade { from { opacity:0; transform:translateY(16px); } to { opacity:1; transform:none; } }

.summary-inner { background: rgba(255,255,255,0.92); backdrop-filter: blur(16px); border: 1.5px solid rgba(255,255,255,0.6); box-shadow: 0 8px 32px rgba(31,38,135,0.1); border-radius: 20px; padding: 1.5rem 2rem; }
.sum-stat { text-align: center; }
.sum-val  { font-size: 2.2rem; font-weight: 800; line-height: 1; font-family: 'JetBrains Mono', monospace; }
.sum-lbl  { font-size: 0.72rem; color: #94a3b8; text-transform: uppercase; letter-spacing: 0.08em; margin-top: 0.25rem; }
.sum-pass { color: #06d6a0; } .sum-fail { color: #ef476f; } .sum-dur  { color: #6366f1; }
.overall-pill { padding: 0.55rem 1.5rem; border-radius: 99px; font-weight: 800; font-size: 0.95rem; letter-spacing: 0.02em; display: inline-flex; align-items: center; gap: 0.5rem; }
.pill-pass { background: linear-gradient(135deg, #06d6a0, #059669); color: #fff; box-shadow: 0 4px 16px rgba(6,214,160,0.4); }
.pill-fail { background: linear-gradient(135deg, #ef476f, #b91c1c); color: #fff; box-shadow: 0 4px 16px rgba(239,71,111,0.4); }
.sum-divider { width: 1.5px; background: #e8edf8; align-self: stretch; border-radius: 99px; }

/* Chart Container */
.chart-container { width: 120px; height: 120px; position: relative; margin-left: 2rem; }

#confetti-canvas { position: fixed; top: 0; left: 0; width: 100%; height: 100%; pointer-events: none; z-index: 9999; }
</style>
@endpush

@section('content')
<canvas id="confetti-canvas"></canvas>

{{-- AUDIO FEEDBACK --}}
<audio id="audio-success" src="https://assets.mixkit.co/active_storage/sfx/2013/2013-preview.mp3" preload="auto"></audio>
<audio id="audio-error" src="https://assets.mixkit.co/active_storage/sfx/2997/2997-preview.mp3" preload="auto"></audio>

<div class="tr-page">
<div class="container" style="max-width: 900px;">

    {{-- ── HERO HEADER ── --}}
    <div class="hero">
        <div class="hero-grid">
            <div>
                <p class="hero-eyebrow">🛡️ eSIR 2.1 — Admin Pusat</p>
                <h3>Live Internal Test Runner</h3>
                <p>Jalankan pengujian fungsional secara real-time. Setiap skenario dieksekusi langsung terhadap sistem yang aktif.</p>
            </div>
            <div class="d-flex flex-column gap-2 align-items-end">
                <div class="d-flex gap-2">
                    <button id="btn-reset" class="btn-reset" onclick="resetAll()" disabled>
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="1 4 1 10 7 10"/><path d="M3.51 15a9 9 0 1 0 .49-3.5"/></svg>
                        Reset
                    </button>
                    <button id="btn-run" class="btn-run" onclick="startAllTests()">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="currentColor"><polygon points="5 3 19 12 5 21 5 3"/></svg>
                        Jalankan Semua
                    </button>
                </div>
                <span style="font-size:0.72rem; color:rgba(255,255,255,0.35); font-family:'JetBrains Mono',monospace;">
                    5 skenario &nbsp;|&nbsp; Laravel {{ app()->version() }}
                </span>
            </div>
        </div>
        <div class="hero-progress-wrap">
            <div class="hero-progress-bar"><div class="hero-progress-fill" id="progress-fill"></div></div>
            <span class="hero-progress-label" id="progress-label">0 / 5</span>
        </div>
    </div>

    {{-- ── SUITE TIMELINE ── --}}
    <div class="suite-timeline">
        @php
        $suites = [
            ['id'=>'auth',       'n'=>'01', 'name'=>'Autentikasi & Hak Akses',          'desc'=>'Validasi tabel users, role middleware, dan sesi aktif'],
            ['id'=>'form',       'n'=>'02', 'name'=>'Validasi Form & Auto-save',         'desc'=>'Rules validator backend: data valid & tidak valid'],
            ['id'=>'db_sync',    'n'=>'03', 'name'=>'Sinkronisasi Data Database',        'desc'=>'Koneksi MySQL, jumlah record, integritas relasi data'],
            ['id'=>'responsive', 'n'=>'04', 'name'=>'Responsivitas Antarmuka (UI)',      'desc'=>'Keberadaan view kritis, Bootstrap 5, dan Leaflet.js'],
            ['id'=>'gps',        'n'=>'05', 'name'=>'GPS Real-time (WebSocket Reverb)',  'desc'=>'Konfigurasi Reverb, event class, dan TCP ping port'],
        ];
        @endphp

        @foreach($suites as $s)
        <div class="suite-item" id="item-{{ $s['id'] }}">
            <span class="timeline-dot" id="dot-{{ $s['id'] }}"></span>
            <div class="step-num idle" id="step-{{ $s['id'] }}">{{ $s['n'] }}</div>
            <div class="suite-info">
                <p class="suite-name">{{ $s['name'] }}</p>
                <p class="suite-desc">{{ $s['desc'] }}</p>
            </div>
            <span class="dur-badge dur-idle" id="badge-{{ $s['id'] }}">pending</span>
            
            {{-- TARGETED RE-RUN BUTTON --}}
            <button class="btn-target-rerun" onclick="runTargetedTest('{{ $s['id'] }}')" title="Ulangi skenario ini">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="1 4 1 10 7 10"/><path d="M3.51 15a9 9 0 1 0 .49-3.5"/></svg>
            </button>
        </div>
        @endforeach
    </div>

    {{-- ── SUMMARY BAR (With Chart) ── --}}
    <div id="summary-bar">
        <div class="summary-inner">
            <div class="d-flex align-items-center flex-wrap">
                <div class="d-flex gap-4 align-items-center flex-grow-1">
                    <div class="sum-stat">
                        <div class="sum-val sum-pass" id="sum-pass">0</div>
                        <div class="sum-lbl">Passed</div>
                    </div>
                    <div class="sum-divider"></div>
                    <div class="sum-stat">
                        <div class="sum-val sum-fail" id="sum-fail">0</div>
                        <div class="sum-lbl">Failed</div>
                    </div>
                    <div class="sum-divider"></div>
                    <div class="sum-stat">
                        <div class="sum-val sum-dur" id="sum-dur">0ms</div>
                        <div class="sum-lbl">Total Time</div>
                    </div>
                </div>

                {{-- CHART JS CANVAS --}}
                <div class="chart-container d-none d-md-block">
                    <canvas id="perfChart"></canvas>
                </div>

                <div class="ms-auto ps-4 d-flex align-items-center gap-3 border-start ms-4">
                    <form id="pdf-form" action="{{ route('internal-testing.pdf') }}" method="POST" style="display:none;">
                        @csrf
                        <input type="hidden" name="logs" id="pdf-logs">
                        <input type="hidden" name="pass" id="pdf-pass">
                        <input type="hidden" name="fail" id="pdf-fail">
                        <input type="hidden" name="duration" id="pdf-dur">
                    </form>
                    <div class="d-flex flex-column gap-2 align-items-end">
                        <span class="overall-pill" id="overall-pill">—</span>
                        <button class="btn btn-sm btn-outline-primary fw-bold" onclick="downloadPdf()" style="border-radius: 99px; font-size:0.75rem;">
                            <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" class="me-1"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="12" y1="18" x2="12" y2="12"/><polyline points="9 15 12 18 15 15"/></svg> Unduh PDF Report
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- ── TERMINAL LOG ── --}}
    <div class="terminal-wrap">
        <div class="terminal-topbar">
            <span class="tb-dot tb-red"></span>
            <span class="tb-dot tb-yellow"></span>
            <span class="tb-dot tb-green"></span>
            <span class="tb-label">esir@test-runner: ~/eSIR2.1</span>
            <span class="tb-status tb-idle" id="tb-status">● idle</span>
        </div>
        <div id="terminal-body">
            <span class="log-line log-dim">$ Tekan "▶ Jalankan Semua" untuk memulai sesi pengujian...</span><br>
            <span class="terminal-cursor" id="cursor"></span>
        </div>
    </div>

</div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
const TESTS = ['auth', 'form', 'db_sync', 'responsive', 'gps'];
const CSRF  = document.querySelector('meta[name="csrf-token"]').content;

// Store results dynamically for Targeted Re-run & Chart
let testResults = {};
let isRunning = false;
let chartInstance = null;

// Initialize results object
TESTS.forEach(id => {
    testResults[id] = { state: 'idle', dur: 0, hasRun: false };
});

function ts() { return new Date().toLocaleTimeString('id-ID', { hour12: false, hour:'2-digit', minute:'2-digit', second:'2-digit' }); }

// ── Log to terminal ──
function log(text, cls = '') {
    const body   = document.getElementById('terminal-body');
    const cursor = document.getElementById('cursor');
    const span   = document.createElement('span');
    span.className = 'log-line ' + cls;
    span.textContent = text;
    body.insertBefore(span, cursor);
    const br = document.createElement('br');
    body.insertBefore(br, cursor);
    body.scrollTop = body.scrollHeight;
}
function logTs(text, cls = '') { log(`[${ts()}]  ${text}`, cls); }
function logSep() { log('─────────────────────────────────────────────────────────', 'log-sep'); }

// ── Set suite item state ──
function setState(id, state, ms = 0) {
    const item  = document.getElementById('item-' + id);
    const step  = document.getElementById('step-' + id);
    const badge = document.getElementById('badge-' + id);

    item.classList.remove('is-running','is-pass','is-fail');
    step.className = 'step-num ' + state;

    if (state === 'idle') {
        step.textContent = { auth:'01', form:'02', db_sync:'03', responsive:'04', gps:'05' }[id];
        badge.className  = 'dur-badge dur-idle';
        badge.textContent = 'pending';
    } else if (state === 'running') {
        item.classList.add('is-running');
        step.innerHTML   = '<span class="mini-spinner"></span>';
        badge.className  = 'dur-badge dur-running';
        badge.textContent = 'running...';
    } else if (state === 'pass') {
        item.classList.add('is-pass');
        step.textContent = '✓';
        badge.className  = 'dur-badge dur-pass';
        badge.textContent = 'PASS  ' + ms + 'ms';
    } else if (state === 'fail') {
        item.classList.add('is-fail');
        step.textContent = '✕';
        badge.className  = 'dur-badge dur-fail';
        badge.textContent = 'FAIL  ' + ms + 'ms';
    }
    
    // Play sound on failure instantly (optional, or just at the end)
    if(state === 'fail') playSound('error');
}

// ── Calculate Metrics & Chart ──
function calculateMetrics() {
    let pass = 0, fail = 0, totalMs = 0, done = 0;
    let chartData = []; let chartColors = []; let chartLabels = [];
    
    TESTS.forEach(id => {
        const res = testResults[id];
        if (res.hasRun) {
            done++;
            if (res.state === 'pass') pass++;
            if (res.state === 'fail') fail++;
            totalMs += res.dur;
            
            chartLabels.push(id.toUpperCase());
            chartData.push(res.dur > 0 ? res.dur : 5); // Fallback so chart shows something even if 0ms
            chartColors.push(res.state === 'pass' ? '#06d6a0' : '#ef476f');
        }
    });
    
    return { pass, fail, totalMs, done, chartLabels, chartData, chartColors };
}

function updateProgress(done) {
    const pct = Math.round((done / TESTS.length) * 100);
    document.getElementById('progress-fill').style.width = pct + '%';
    document.getElementById('progress-label').textContent = done + ' / ' + TESTS.length;
}

function setTbStatus(state) {
    const el = document.getElementById('tb-status');
    el.className = 'tb-status tb-' + state;
    el.textContent = state === 'idle' ? '● idle' : state === 'running' ? '◉ running' : state === 'done-ok' ? '✓ all passed' : '✕ failed';
}

// ── Run single test function ──
async function runTestLogic(testId) {
    const names = { auth: 'Autentikasi & Hak Akses', form: 'Validasi Form & Auto-save', db_sync: 'Sinkronisasi Database', responsive: 'Responsivitas UI', gps: 'GPS Real-time (WebSocket)' };
    
    setState(testId, 'running');
    logSep();
    logTs(`▶  Memulai: ${names[testId]}`, 'log-header');

    try {
        const resp = await fetch("{{ route('internal-testing.run') }}", {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': CSRF, 'Accept': 'application/json' },
            body: JSON.stringify({ test_id: testId }),
        });

        if (!resp.ok) throw new Error(`HTTP ${resp.status}`);
        const data = await resp.json();

        for (const line of (data.logs || [])) {
            await new Promise(r => setTimeout(r, 80));
            const cls = line.startsWith('✅') ? 'log-pass' : line.startsWith('❌') ? 'log-fail' : line.startsWith('⚠️') ? 'log-warn' : 'log-dim';
            logTs(line, cls);
        }

        const dur   = data.duration_ms ?? 0;
        const state = data.status === 'pass' ? 'pass' : 'fail';

        await new Promise(r => setTimeout(r, 80));
        logTs(`${state === 'pass' ? '✅' : '❌'}  ${names[testId]} — ${state.toUpperCase()} (${dur}ms)`, state === 'pass' ? 'log-pass' : 'log-fail');

        testResults[testId] = { state, dur, hasRun: true };
        setState(testId, state, dur);

    } catch (err) {
        logTs('❌  Network/server error: ' + err.message, 'log-fail');
        testResults[testId] = { state: 'fail', dur: 0, hasRun: true };
        setState(testId, 'fail', 0);
    }
}

// ── MAIN RUN ALL ──
async function startAllTests() {
    if (isRunning) return;
    isRunning = true;
    
    // Reset state
    TESTS.forEach(id => { testResults[id] = { state: 'idle', dur: 0, hasRun: false }; setState(id, 'idle'); });
    document.getElementById('btn-run').disabled = true; document.getElementById('btn-reset').disabled = true;
    document.getElementById('summary-bar').style.display = 'none';
    updateProgress(0); setTbStatus('running');

    document.getElementById('terminal-body').innerHTML = '<span class="terminal-cursor" id="cursor"></span>';
    logTs('🚀  eSIR Internal Test Suite dimulai.', 'log-info');

    for (const id of TESTS) {
        await runTestLogic(id);
        const m = calculateMetrics();
        updateProgress(m.done);
        await new Promise(r => setTimeout(r, 250));
    }

    finishSuite();
}

// ── TARGETED RE-RUN ──
async function runTargetedTest(testId) {
    if (isRunning) return;
    isRunning = true;
    
    document.getElementById('btn-run').disabled = true; document.getElementById('btn-reset').disabled = true;
    setTbStatus('running');
    
    logTs(`🔄  Memulai ulang spesifik: ${testId.toUpperCase()}...`, 'log-info');
    
    await runTestLogic(testId);
    
    finishSuite(true);
}

// ── FINISH LOGIC (Audio, Chart, Summary) ──
function finishSuite(isTargeted = false) {
    const m = calculateMetrics();
    const allOk = m.fail === 0 && m.done === TESTS.length;
    
    if(!isTargeted) {
        logSep();
        logTs(`${allOk ? '🎉' : '⚠️ '} Suite selesai — ${m.pass} PASS | ${m.fail} FAIL | ${m.totalMs}ms`, allOk ? 'log-pass' : 'log-warn');
        logTs('$', 'log-dim');
    } else {
        logTs(`🔄  Uji ulang selesai. Total Status: ${m.pass} PASS | ${m.fail} FAIL`, 'log-info');
        logTs('$', 'log-dim');
    }

    setTbStatus(allOk ? 'done-ok' : 'done-fail');
    showSummary(m, allOk);

    isRunning = false;
    document.getElementById('btn-run').disabled = false;
    document.getElementById('btn-reset').disabled = false;

    if (allOk) {
        if(!isTargeted) launchConfetti(); // don't spam confetti on single targeted rerun
        playSound('success');
    } else {
        playSound('error');
    }
}

// ── AUDIO ──
function playSound(type) {
    try {
        const audio = document.getElementById('audio-' + type);
        if(audio) {
            audio.currentTime = 0;
            audio.play().catch(e => console.log("Audio play prevented by browser:", e));
        }
    } catch(e) {}
}

// ── Summary & Chart ──
function showSummary(m, allOk) {
    document.getElementById('sum-pass').textContent = m.pass;
    document.getElementById('sum-fail').textContent = m.fail;
    document.getElementById('sum-dur').textContent  = m.totalMs + 'ms';

    const pill = document.getElementById('overall-pill');
    pill.className = 'overall-pill ' + (allOk ? 'pill-pass' : 'pill-fail');
    pill.textContent = allOk ? '🎉  ALL PASSED' : `⚠️  ${m.fail} FAILED`;

    document.getElementById('summary-bar').style.display = 'block';
    
    renderChart(m.chartLabels, m.chartData, m.chartColors);
}

function renderChart(labels, data, colors) {
    const ctx = document.getElementById('perfChart').getContext('2d');
    if (chartInstance) { chartInstance.destroy(); }
    
    chartInstance = new Chart(ctx, {
        type: 'doughnut',
        data: { labels: labels, datasets: [{ data: data, backgroundColor: colors, borderWidth: 2, borderColor: '#ffffff', hoverOffset: 4 }] },
        options: {
            responsive: true, maintainAspectRatio: false,
            plugins: {
                legend: { display: false },
                tooltip: { callbacks: { label: function(context) { return context.label + ': ' + context.raw + 'ms'; } } }
            },
            cutout: '65%'
        }
    });
}

// ── Download PDF ──
function downloadPdf() {
    const logs = Array.from(document.getElementById('terminal-body').querySelectorAll('.log-line')).map(span => span.textContent);
    document.getElementById('pdf-logs').value = JSON.stringify(logs);
    
    const m = calculateMetrics();
    document.getElementById('pdf-pass').value = m.pass;
    document.getElementById('pdf-fail').value = m.fail;
    document.getElementById('pdf-dur').value  = m.totalMs;
    
    document.getElementById('pdf-form').submit();
}

// ── Reset ──
function resetAll() {
    if (isRunning) return;
    TESTS.forEach(id => { testResults[id] = { state: 'idle', dur: 0, hasRun: false }; setState(id, 'idle'); });
    updateProgress(0); setTbStatus('idle');
    document.getElementById('summary-bar').style.display = 'none'; document.getElementById('btn-reset').disabled = true;
    document.getElementById('terminal-body').innerHTML = `<span class="log-line log-dim">$ Tekan "▶ Jalankan Semua" untuk memulai sesi pengujian...</span><br><span class="terminal-cursor" id="cursor"></span>`;
}

// ── Confetti ──
function launchConfetti() {
    const canvas = document.getElementById('confetti-canvas');
    const ctx = canvas.getContext('2d');
    canvas.width = window.innerWidth; canvas.height = window.innerHeight;
    const COLORS = ['#06d6a0','#4361ee','#ffd166','#ef476f','#a5b4fc','#6ee7b7'];
    const pieces = Array.from({ length: 120 }, () => ({
        x: Math.random() * canvas.width, y: Math.random() * -canvas.height,
        r: Math.random() * 6 + 3, d: Math.random() * 80 + 30,
        color: COLORS[Math.floor(Math.random() * COLORS.length)],
        tilt: Math.random() * 10 - 10, tiltInc: (Math.random() * 0.07) + 0.05, angle: 0, vy: Math.random() * 3 + 2,
    }));
    let frame, elapsed = 0;
    function draw() {
        ctx.clearRect(0, 0, canvas.width, canvas.height);
        pieces.forEach(p => {
            p.angle += p.tiltInc; p.tilt = Math.sin(p.angle) * 12; p.y += p.vy;
            ctx.beginPath(); ctx.lineWidth = p.r; ctx.strokeStyle = p.color;
            ctx.moveTo(p.x + p.tilt + p.r / 2, p.y); ctx.lineTo(p.x + p.tilt, p.y + p.tilt + p.r / 2); ctx.stroke();
        });
        elapsed++;
        if (elapsed < 200) frame = requestAnimationFrame(draw); else { ctx.clearRect(0,0,canvas.width,canvas.height); }
    }
    draw();
}
</script>
@endpush
