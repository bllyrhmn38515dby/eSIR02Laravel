@extends('layouts.app')

@push('styles')
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" crossorigin=""/>
<link rel="stylesheet" href="https://unpkg.com/leaflet-routing-machine@latest/dist/leaflet-routing-machine.css" />
<style>
    #map { height: 75vh; width: 100%; border-radius: 12px; box-shadow: 0 4px 15px rgba(0,0,0,0.1); position: relative; }
    .map-control-center {
        position: absolute;
        bottom: 25px;
        right: 10px;
        z-index: 1000;
        background: white;
        padding: 10px;
        border-radius: 50%;
        box-shadow: 0 2px 6px rgba(0,0,0,0.3);
        cursor: pointer;
        width: 50px;
        height: 50px;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: all 0.3s ease;
        border: 2px solid #fff;
    }
    .map-control-center:hover { background: #f8f9fa; transform: scale(1.1); }
    .map-control-center.active { color: #007bff; border-color: #007bff; }
    .map-control-center i { font-size: 24px; }
    
    #custom-toast-container {
        position: fixed;
        top: 20px;
        right: 20px;
        z-index: 9999;
    }
    .medical-toast {
        background: rgba(255, 255, 255, 0.9);
        backdrop-filter: blur(10px);
        border-left: 5px solid var(--medical-primary);
        box-shadow: 0 10px 30px rgba(0,0,0,0.1);
        padding: 12px 20px;
        border-radius: 8px;
        margin-bottom: 10px;
        animation: slideIn 0.3s ease-out;
    }
    @keyframes slideIn { from { transform: translateX(100%); opacity: 0; } to { transform: translateX(0); opacity: 1; } }

    /* Ambulance divIcon: hapus border/background default Leaflet */
    .ambulance-marker-wrapper {
        background: none !important;
        border: none !important;
    }
    .ambulance-icon-img {
        display: block;
        transform-origin: center center;
        transition: transform 0.6s ease;
        filter: drop-shadow(0 2px 4px rgba(0,0,0,0.4));
    }
</style>
@endpush

@section('content')
<div class="container-fluid px-lg-5">
    @php
        $isAssignedDriver = auth()->check() && auth()->user()->role === 'driver' && $referral->driver_id === auth()->id();
    @endphp

    <div class="row">
        <div class="col-md-3 mb-3 animate__animated animate__fadeInLeft">
            <div class="card h-100 shadow-sm border-0">
                <div class="card-header bg-primary text-white font-weight-bold border-0">
                    Panel Navigasi Ambulans
                </div>
                <div class="card-body">
                    <h5>ID: {{ $referral->referral_number }}</h5>
                    <p><strong>Pasien:</strong> {{ $referral->patient->name }}</p>
                    <p><strong>Tujuan:</strong> {{ $referral->toFaskes->name }}</p>
                    <hr>
                    
                    <h6><i class="bi bi-people"></i> Pengguna Aktif</h6>
                    <ul id="online-users" class="list-group list-group-flush mb-3" style="font-size: 0.85rem;">
                        <li class="list-group-item text-muted">Menunggu data...</li>
                    </ul>
                    <hr>

                    <div id="route-info" class="p-3 bg-light rounded mb-3 border d-none">
                        <h6 class="mb-1 text-primary"><i class="bi bi-clock-history"></i> Estimasi Kedatangan</h6>
                        <div class="d-flex justify-content-between">
                            <span>Sisa Jarak:</span>
                            <span id="eta-distance" class="fw-bold">-</span>
                        </div>
                        <div class="d-flex justify-content-between">
                            <span>Estimasi Waktu:</span>
                            <span id="eta-time" class="fw-bold text-success">-</span>
                        </div>
                    </div>

                    @if($isAssignedDriver)
                        <div class="alert alert-info py-2" style="font-size: 0.9rem;">Anda ditugaskan sebagai supir pada rujukan ini.</div>
                        <button class="btn btn-success w-100 mb-2 py-2 fw-bold shadow-sm" id="btn-start-tracking"><i class="bi bi-broadcast"></i> Mulai Live Sharing GPS</button>
                        <button class="btn btn-danger w-100 d-none mb-2 py-2 fw-bold shadow-sm" id="btn-stop-tracking"><i class="bi bi-stop-circle"></i> Hentikan Sharing Lokasi</button>
                        
                        <!-- Virtual Simulator for Developer/Testing -->
                        <div class="mt-2 border-top pt-2">
                            <button class="btn btn-warning btn-sm w-100 rounded-pill mb-2 fw-bold" id="btn-simulate-gps">
                                🎮 Simulasikan Pergerakan (Virtual)
                            </button>
                            <small class="text-muted d-block text-center" style="font-size: 0.7rem;">Gunakan ini jika GPS diblokir browser (Non-HTTPS) saat pengujian.</small>
                        </div>

                        <span id="tracker-status" class="badge bg-secondary w-100 py-3 mt-3 fs-6 rounded-pill border shadow-sm">
                            Tracker Offline (Ping: <span id="sync-count">0</span>x)
                        </span>
                    @else
                        <div class="alert alert-success d-flex align-items-center">
                            Memantau pergerakan ambulans secara *real-time* 📡
                        </div>
                        <p class="text-muted"><small>Marker akan bergeser otomatis ketika supir menyalakan radar GPS-nya dari jalan raya.</small></p>
                    @endif
                </div>
            </div>
        </div>

        <div class="col-md-9 mb-3">
            <div id="map">
                <div id="btn-center-map" class="map-control-center active" title="Tengahkan ke Ambulans">
                    <i class="bi bi-crosshair"></i>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Toast Container -->
<div id="custom-toast-container"></div>
@endsection

@push('scripts')
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" crossorigin=""></script>
<script src="https://unpkg.com/leaflet-routing-machine@latest/dist/leaflet-routing-machine.js"></script>
<script>
    document.addEventListener("DOMContentLoaded", function() {
        // Koordinat Default Monas, Jakarta jika ambulans belum menyala
        const defaultLat = -6.175110;
        const defaultLng = 106.827152;
        
        // Ambil riwayat titik yang sudah di database (jika refresh browser, garis gpx tetap tergambar)
        let initialPoints = @json($referral->trackingPoints->map(function($p) { return [$p->latitude, $p->longitude]; }));
        let lastLocation = initialPoints.length > 0 ? initialPoints[initialPoints.length - 1] : [defaultLat, defaultLng];
        
        const map = L.map('map', { maxZoom: 20 }).setView(lastLocation, 15);
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', { 
            maxZoom: 20,
            maxNativeZoom: 19 
        }).addTo(map);

        // Ikon Custom Mobil Ambulans — pakai divIcon agar bisa di-rotate via CSS
        function createAmbulanceIcon(bearing) {
            bearing = bearing || 0;
            return L.divIcon({
                className: 'ambulance-marker-wrapper',
                html: `<img src="https://cdn-icons-png.flaticon.com/512/2966/2966327.png"
                            class="ambulance-icon-img"
                            style="width:45px;height:45px;transform:rotate(${bearing}deg);" />`,
                iconSize: [45, 45],
                iconAnchor: [22, 22]
            });
        }

        // Setup Routing
        const destLat = {{ $referral->toFaskes->latitude }};
        const destLng = {{ $referral->toFaskes->longitude }};
        
        let routingControl = L.Routing.control({
            waypoints: [
                L.latLng(lastLocation[0], lastLocation[1]),
                L.latLng(destLat, destLng)
            ],
            routeWhileDragging: false,
            addWaypoints: false,
            draggableWaypoints: false,
            fitSelectedRoutes: false,
            show: false, // Sembunyikan panel instruksi teks OSRM
            lineOptions: {
                styles: [{ color: '#3a86ff', opacity: 0.6, weight: 6 }]
            },
            createMarker: function() { return null; } // Jangan buat marker baru dari routing
        }).addTo(map);

        let currentRouteCoords = []; // Array untuk menyimpan titik rute (untuk Snap-to-Road)

        routingControl.on('routesfound', function(e) {
            const routes = e.routes;
            const summary = routes[0].summary;
            currentRouteCoords = routes[0].coordinates; // Simpan koordinat rute
            
            const infoBox = document.getElementById('route-info');
            infoBox.classList.remove('d-none');
            
            document.getElementById('eta-distance').textContent = (summary.totalDistance / 1000).toFixed(1) + ' KM';
            document.getElementById('eta-time').textContent = Math.round(summary.totalTime / 60) + ' Menit';
        });

        // --- SNAP TO ROAD (Koreksi GPS Inakurasi ke Garis Rute) ---
        function snapToRoute(lat, lng) {
            if (!currentRouteCoords || currentRouteCoords.length === 0) return [lat, lng];
            
            let closest = [lat, lng];
            let minDistance = Infinity;
            const p = L.latLng(lat, lng);

            for (let i = 0; i < currentRouteCoords.length; i++) {
                const routePt = currentRouteCoords[i];
                const d = p.distanceTo(routePt);
                // Hanya memaku (snap) ke rute jika jaraknya kurang dari 60 meter
                // Jika melenceng jauh (> 60m), biarkan saja (kemungkinan supir mengambil jalur alternatif)
                if (d < minDistance && d < 60) {
                    minDistance = d;
                    closest = [routePt.lat, routePt.lng];
                }
            }
            return closest;
        }

        // Gambar Ikon Ambulans dan Garis Jejak (Breadcrumb)
        let marker = L.marker(lastLocation, {icon: createAmbulanceIcon(0)}).addTo(map);
        let polyline = L.polyline(initialPoints, {color: '#f72585', weight: 3, opacity: 0.5, dashArray: '5, 10' }).addTo(map);

        // State Kontrol Kamera
        let isFollowing = true;
        const btnCenter = document.getElementById('btn-center-map');

        // --- TOAST NOTIFICATION UTILITY ---
        function showToast(message, type = 'info') {
            const container = document.getElementById('custom-toast-container');
            const toast = document.createElement('div');
            toast.className = 'medical-toast';
            if(type === 'error') toast.style.borderLeftColor = 'var(--medical-danger)';
            if(type === 'success') toast.style.borderLeftColor = 'var(--medical-success)';
            
            toast.innerHTML = `<div class="small fw-bold">${message}</div>`;
            container.appendChild(toast);
            setTimeout(() => {
                toast.style.opacity = '0';
                setTimeout(() => toast.remove(), 500);
            }, 4000);
        }

        // --- BEARING CALCULATOR (Hitung sudut arah gerak) ---
        function calculateBearing(fromLat, fromLng, toLat, toLng) {
            const dLng  = (toLng - fromLng) * Math.PI / 180;
            const lat1  = fromLat * Math.PI / 180;
            const lat2  = toLat   * Math.PI / 180;
            const y     = Math.sin(dLng) * Math.cos(lat2);
            const x     = Math.cos(lat1) * Math.sin(lat2) - Math.sin(lat1) * Math.cos(lat2) * Math.cos(dLng);
            return (Math.atan2(y, x) * 180 / Math.PI + 360) % 360;
        }

        // --- SMOOTH MARKER ANIMATION (Interpolasi posisi marker) ---
        let animationFrameId = null;

        function animateMarkerTo(fromLatLng, toLatLng, durationMs) {
            durationMs = durationMs || 1500;
            // Batalkan frame animasi sebelumnya agar tidak overlap
            if (animationFrameId) cancelAnimationFrame(animationFrameId);

            const startTime = performance.now();
            const startLat  = fromLatLng[0], startLng = fromLatLng[1];
            const endLat    = toLatLng[0],   endLng   = toLatLng[1];

            function step(now) {
                const elapsed = now - startTime;
                // Ease-in-out cubic: gerakan alami, tidak kaku
                let t = Math.min(elapsed / durationMs, 1);
                t = t < 0.5 ? 4*t*t*t : 1 - Math.pow(-2*t + 2, 3) / 2;

                marker.setLatLng([
                    startLat + (endLat - startLat) * t,
                    startLng + (endLng - startLng) * t
                ]);

                if (t < 1) {
                    animationFrameId = requestAnimationFrame(step);
                } else {
                    animationFrameId = null;
                }
            }
            animationFrameId = requestAnimationFrame(step);
        }

        // --- THROTTLE OSRM ROUTING (Jangan request tiap GPS update) ---
        let lastRoutingUpdate  = 0;
        const ROUTING_THROTTLE = 25000; // Update rute setiap 25 detik saja
        let currentBearing = 0; // Simpan arah terakhir agar tidak reset saat berhenti
        let deviceHeading = null; // Menyimpan heading dari sensor kompas HP

        // Fungsi khusus untuk memutar ikon secara instan (tanpa perlu update GPS)
        function updateLocalRotationOnly(heading) {
            if (heading === null) return;
            let diff = heading - (currentBearing % 360);
            if (diff > 180) diff -= 360;
            else if (diff < -180) diff += 360;
            
            currentBearing += diff;
            const markerEl = marker.getElement();
            if (markerEl) {
                const img = markerEl.querySelector('img.ambulance-icon-img');
                if (img) img.style.transform = `rotate(${currentBearing}deg)`;
            }
        }

        function updateMarkerAndPolyline(rawLat, rawLng, headingFromServer = null, animDuration = 1500) {
            // SNAP TO ROAD: Tarik titik koordinat GPS ke jalan raya terdekat
            const snapped = snapToRoute(rawLat, rawLng);
            const lat = snapped[0];
            const lng = snapped[1];

            const from    = marker.getLatLng();
            const distance = from.distanceTo(L.latLng(lat, lng));
            
            // Deduplikasi: Abaikan jika koordinat sudah sama (misal dari Websocket Event yang terlambat dibanding Whisper)
            if (distance < 0.5) return;

            const fromArr = [from.lat, from.lng];
            const toArr   = [lat, lng];

            let newBearing = null;

            // Prioritas arah rotasi:
            if (headingFromServer !== null) {
                newBearing = headingFromServer;
            } else if (deviceHeading !== null) {
                newBearing = deviceHeading;
            } else if (distance > 1) {
                newBearing = calculateBearing(from.lat, from.lng, lat, lng);
            }

            if (newBearing !== null) {
                updateLocalRotationOnly(newBearing);
            }

            // 3. Animasi smooth movement
            animateMarkerTo(fromArr, toArr, animDuration);

            // 4. Tambah titik ke polyline jejak GPS
            polyline.addLatLng(toArr);

            // 5. Throttle OSRM
            const now = Date.now();
            if (now - lastRoutingUpdate > ROUTING_THROTTLE) {
                routingControl.setWaypoints([
                    L.latLng(lat, lng),
                    L.latLng(destLat, destLng)
                ]);
                lastRoutingUpdate = now;
            }

            // 6. Ikuti kamera ke posisi ambulans
            if (isFollowing) {
                map.panTo(toArr, { animate: true, duration: (animDuration / 1000) });
            }
        }

        // Matikan auto-follow jika user menggeser peta secara manual
        map.on('dragstart', function() {
            isFollowing = false;
            btnCenter.classList.remove('active');
        });

        // Klik tombol untuk menengahkan dan mengaktifkan auto-follow kembali
        btnCenter.addEventListener('click', function() {
            isFollowing = true;
            btnCenter.classList.add('active');
            map.panTo(marker.getLatLng());
            map.setZoom(18); // Zoom in agar lebih fokus ke posisi unit
        });

        // Membuka portal komunikasi WebSocket Presence Channel
        function initRealtime() {
            if (!window.Echo) {
                console.log('⏳ Menunggu modul Echo siap dari Vite...');
                setTimeout(initRealtime, 200); // Coba lagi dalam 200ms
                return;
            }

            console.log('📡 Menghubungkan ke Radar Presence eSIR... Channel: referral.{{ $referral->id }}');
            
            window.Echo.join('referral.{{ $referral->id }}')
                .here((users) => {
                    updateOnlineUsers(users);
                })
                .joining((user) => {
                    console.log('User joining:', user.name);
                })
                .leaving((user) => {
                    console.log('User leaving:', user.name);
                })
                .listenForWhisper('headingUpdate', (e) => {
                    if (e.heading !== undefined && e.heading !== null) {
                        updateLocalRotationOnly(e.heading);
                    }
                })
                .listenForWhisper('positionUpdate', (e) => {
                    if (e.lat !== undefined && e.lng !== undefined) {
                        const duration = e.duration || 1000;
                        updateMarkerAndPolyline(parseFloat(e.lat), parseFloat(e.lng), e.heading !== undefined ? e.heading : null, duration);
                    }
                })
                .listen('.AmbulanceLocationUpdated', (e) => {
                    console.log('📍 Sinyal GPS Diterima:', e);
                    const lat = parseFloat(e.latitude);
                    const lng = parseFloat(e.longitude);
                    const heading = e.heading !== null && e.heading !== undefined ? parseFloat(e.heading) : null;
                    updateMarkerAndPolyline(lat, lng, heading);
                    
                    // Update status di list pengguna jika perlu
                    updateUserLastSeen(e.user_id);
                })
                .error((error) => {
                    console.error('WebSocket Error:', error);
                });

            // Listen for Chat Messages (Live Consulting) to show Notifications
            window.Echo.channel('referral.{{ $referral->id }}')
                .listen('.MessageSent', (e) => {
                    const authUserId = {{ auth()->id() }};
                    if (e.message.user_id !== authUserId) {
                        // Play Sound
                        const sound = document.getElementById('notification-sound');
                        if (sound) sound.play().catch(err => console.log('Audio blocked'));

                        // Show Browser/Toast Notification
                        if ("Notification" in window && Notification.permission === "granted") {
                            new Notification("Konsultasi Baru: " + e.message.user.name, {
                                body: e.message.body,
                                icon: 'https://cdn-icons-png.flaticon.com/512/2966/2966327.png'
                            });
                        }

                        // Create a simple floating toast in the UI with Quick Reply
                        const toastId = 'toast-' + Date.now();
                        const toastHtml = `
                            <div class="position-fixed top-0 end-0 p-3" style="z-index: 9999;" id="${toastId}-container">
                                <div class="toast show animate__animated animate__fadeInRight" role="alert" aria-live="assertive" aria-atomic="true">
                                    <div class="toast-header bg-primary text-white">
                                        <i class="bi bi-chat-dots me-2"></i>
                                        <strong class="me-auto">${e.message.user.name}</strong>
                                        <small class="text-white-50">${e.time}</small>
                                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="toast" aria-label="Close"></button>
                                    </div>
                                    <div class="toast-body bg-white text-dark">
                                        <div class="mb-2">${e.message.body}</div>
                                        <div class="input-group input-group-sm mt-2">
                                            <input type="text" class="form-control border-primary" id="${toastId}-input" placeholder="Balas cepat...">
                                            <button class="btn btn-primary" type="button" id="${toastId}-btn">
                                                <i class="bi bi-send-fill"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        `;
                        document.body.insertAdjacentHTML('beforeend', toastHtml);

                        // Handle Quick Reply Click
                        const btnReply = document.getElementById(`${toastId}-btn`);
                        const inputReply = document.getElementById(`${toastId}-input`);
                        
                        const sendReply = () => {
                            const body = inputReply.value.trim();
                            if (!body) return;
                            
                            btnReply.disabled = true;
                            inputReply.disabled = true;

                            fetch('{{ route("messages.store", $referral->id) }}', {
                                method: 'POST',
                                headers: {
                                    'Content-Type': 'application/json',
                                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                                },
                                body: JSON.stringify({ body: body })
                            })
                            .then(res => res.json())
                            .then(data => {
                                if (data.success) {
                                    const toastBody = inputReply.closest('.toast-body');
                                    toastBody.innerHTML = '<div class="text-success small"><i class="bi bi-check-circle-fill me-1"></i> Balasan terkirim!</div>';
                                    setTimeout(() => {
                                        document.getElementById(`${toastId}-container`).remove();
                                    }, 2000);
                                }
                            })
                            .catch(err => {
                                console.error(err);
                                btnReply.disabled = false;
                                inputReply.disabled = false;
                            });
                        };

                        btnReply.addEventListener('click', sendReply);
                        inputReply.addEventListener('keypress', (event) => {
                            if (event.key === 'Enter') sendReply();
                        });

                        // Auto remove if not interacted
                        setTimeout(() => {
                            const container = document.getElementById(`${toastId}-container`);
                            if (container && !inputReply.matches(':focus')) {
                                container.remove();
                            }
                        }, 10000); // 10 seconds timeout for quick reply
                    }
                });
        }
        
        // Panggil inisialisasi!
        initRealtime();

        function updateOnlineUsers(users) {
            const list = document.getElementById('online-users');
            if(!list) return;
            
            list.innerHTML = '';
            users.forEach(user => {
                const li = document.createElement('li');
                li.className = 'list-group-item d-flex justify-content-between align-items-center bg-light rounded mb-1 border-0';
                li.innerHTML = `
                    <span>
                        <i class="bi bi-person-circle text-primary"></i> ${user.name} 
                        <small class="text-muted">(${user.role})</small>
                    </span>
                    <span class="badge bg-success p-1"><i class="bi bi-lightning-fill"></i></span>
                `;
                li.id = `user-online-${user.id}`;
                list.appendChild(li);
            });
        }

        function updateUserLastSeen(userId) {
            const el = document.getElementById(`user-online-${userId}`);
            if(el) {
                el.classList.add('bg-info-subtle');
                setTimeout(() => el.classList.remove('bg-info-subtle'), 1000);
            }
        }

        // ============================================================
        // VIEWER MODE: Polling otomatis untuk Faskes / Admin
        // Mengambil posisi terbaru dari database setiap 2 detik.
        // Ini adalah fallback utama agar peta SELALU update bahkan
        // jika WebSocket Whisper gagal terhubung.
        // ============================================================
        @if(!$isAssignedDriver)
        let lastPolledAt = null;
        let pollingActive = true;

        async function pollLatestPosition() {
            if (!pollingActive) return;

            try {
                const res = await fetch('{{ route("tracking.latest", $referral->id) }}', {
                    headers: { 'X-Requested-With': 'XMLHttpRequest' }
                });
                const data = await res.json();

                if (data.found) {
                    // Hanya update jika posisi beda dari sebelumnya
                    if (data.recorded_at !== lastPolledAt) {
                        lastPolledAt = data.recorded_at;
                        updateMarkerAndPolyline(
                            parseFloat(data.lat),
                            parseFloat(data.lng),
                            data.heading ? parseFloat(data.heading) : null,
                            1800 // animasi 1.8 detik (sedikit di atas interval polling 2 detik agar halus)
                        );
                        console.log('📍 Polling update:', data.lat, data.lng);
                    }
                }
            } catch (err) {
                console.warn('Polling gagal:', err);
            }

            // Jadwalkan polling berikutnya
            if (pollingActive) setTimeout(pollLatestPosition, 2000);
        }

        // Mulai polling segera
        pollLatestPosition();

        // Hentikan polling saat halaman ditinggalkan
        window.addEventListener('beforeunload', () => { pollingActive = false; });
        @endif

        // Logic Broadcast untuk Supir (Browser Supir akan agresif mengekstrak GPS Hardware HP)
        @if($isAssignedDriver)
        let watchId;
        const btnStart = document.getElementById('btn-start-tracking');
        const btnStop = document.getElementById('btn-stop-tracking');
        const statusBadge = document.getElementById('tracker-status');
        let count = 0;

        if (btnStart) {
            btnStart.addEventListener('click', () => {
                if ("geolocation" in navigator) {
                    btnStart.classList.add('d-none');
                    btnStop.classList.remove('d-none');
                    statusBadge.classList.replace('bg-secondary', 'bg-warning');
                    statusBadge.innerHTML = '<span class="spinner-grow spinner-grow-sm me-2"></span> Mencari Sinyal GPS...';

                    // Minta izin orientasi kompas (Device Orientation)
                    if (window.DeviceOrientationEvent) {
                        let lastWhisperTime = 0;
                        window.addEventListener('deviceorientationabsolute', function(event) {
                            if (event.alpha !== null) deviceHeading = 360 - event.alpha;
                        }, true);
                        window.addEventListener('deviceorientation', function(event) {
                            if (event.webkitCompassHeading) deviceHeading = event.webkitCompassHeading;
                            else if (event.absolute && event.alpha !== null) deviceHeading = 360 - event.alpha;
                            
                            // Putar ikon secara instan di HP supir (real-time tanpa tunggu GPS ping)
                            if (deviceHeading !== null) {
                                updateLocalRotationOnly(deviceHeading);
                                
                                // Broadcast via Whisper (Client-to-Client) ke Faskes/Admin 
                                // Di-throttle max 5x per detik (200ms) agar WebSocket server tidak overload
                                const now = Date.now();
                                if (window.Echo && (now - lastWhisperTime > 200)) {
                                    window.Echo.join('referral.{{ $referral->id }}').whisper('headingUpdate', {
                                        heading: deviceHeading
                                    });
                                    lastWhisperTime = now;
                                }
                            }
                        }, true);
                    }

                    // Minta izin lokasi ke HP Supir
                    watchId = navigator.geolocation.watchPosition(function(position) {
                        const lat = position.coords.latitude;
                        const lng = position.coords.longitude;
                        
                        // Set ke online jika sudah dapat koordinat pertama
                        if (count === 0) {
                            statusBadge.classList.replace('bg-warning', 'bg-success');
                            statusBadge.innerHTML = 'Tracker Online (Ping: <span id="sync-count">0</span>x)';
                        }

                        const nowTime = Date.now();
                        let pingDuration = 1500;
                        if (window.lastGpsTime) {
                            pingDuration = Math.max(500, Math.min(nowTime - window.lastGpsTime, 3000));
                        }
                        window.lastGpsTime = nowTime;

                        updateMarkerAndPolyline(lat, lng, null, pingDuration);
                        
                        // Broadcast koordinat langsung via Whisper ke Viewer agar instan (Bypass Server PHP)
                        if (window.Echo) {
                            window.Echo.join('referral.{{ $referral->id }}').whisper('positionUpdate', {
                                lat: lat,
                                lng: lng,
                                heading: deviceHeading !== null ? deviceHeading : null,
                                duration: pingDuration
                            });
                        }
                        
                        fetch('{{ route("tracking.location.update", $referral->id) }}', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}'
                            },
                            body: JSON.stringify({ 
                                latitude: lat, 
                                longitude: lng, 
                                heading: deviceHeading !== null ? deviceHeading : (position.coords.heading || null) 
                            })
                        }).then(res => res.json()).then(data => {
                            count++;
                            document.getElementById('sync-count').textContent = count;
                        });
                    }, function(error) {
                        console.error("Geolokasi gagal: ", error);
                        let msg = "Gagal membaca GPS: ";
                        
                        if (!window.isSecureContext) {
                            msg += "\n\n⚠️ Browser memblokir GPS karena koneksi tidak aman (Non-HTTPS). \nHarap gunakan tombol 'SIMULASI' atau gunakan HTTPS.";
                        } else if (error.code === 1) {
                            msg += "Izin lokasi ditolak oleh pengguna.";
                        } else if (error.code === 2) {
                            msg += "Posisi tidak tersedia / Sinyal lemah.";
                        } else if (error.code === 3) {
                            msg += "Waktu tunggu habis (Timeout).";
                        } else {
                            msg += "Harap nyalakan fitur Lokasi di perangkat Anda.";
                        }
                        
                        alert(msg);
                        stopTracking();
                    }, { 
                        enableHighAccuracy: true,
                        timeout: 10000,  // 10 detik sudah cukup untuk mendapatkan lock GPS
                        maximumAge: 0    // KRITIS: selalu ambil posisi segar dari hardware GPS, JANGAN pakai cache
                    });
                } else {
                    alert("Aplikasi browser ini tidak mendukun fitur GPS Loc.");
                }
            });

            btnStop.addEventListener('click', () => {
                stopTracking();
            });

            function stopTracking() {
                if (watchId) navigator.geolocation.clearWatch(watchId);
                if (simulationInterval) clearInterval(simulationInterval);
                btnStop.classList.add('d-none');
                btnStart.classList.remove('d-none');
                statusBadge.classList.replace('bg-success', 'bg-secondary');
                statusBadge.innerHTML = 'Tracker Offline (Ping: <span id="sync-count">'+count+'</span>x)';
            }

            // --- VIRTUAL SIMULATOR LOGIC (SMART ROUTING) ---
            let simulationInterval;
            document.getElementById('btn-simulate-gps').addEventListener('click', async function() {
                const btn = this;
                const startLat = {{ $referral->fromFaskes->latitude ?? -6.175 }};
                const startLng = {{ $referral->fromFaskes->longitude ?? 106.827 }};
                const endLat = {{ $referral->toFaskes->latitude }};
                const endLng = {{ $referral->toFaskes->longitude }};

                btn.disabled = true;
                btn.innerHTML = '<span class="spinner-border spinner-border-sm"></span> Menghitung Rute Jalur...';

                try {
                    // Penarikan Navigasi OSRM (Snap to Road)
                    const osrmUrl = `https://router.project-osrm.org/route/v1/driving/${startLng},${startLat};${endLng},${endLat}?overview=full&geometries=geojson`;
                    const response = await fetch(osrmUrl);
                    const data = await response.json();

                    if (!data.routes || data.routes.length === 0) throw new Error('Rute tidak ditemukan');

                    const points = data.routes[0].geometry.coordinates; // Format OSRM: [lng, lat]
                    
                    if (confirm(`Rute ditemukan (${(data.routes[0].distance/1000).toFixed(2)} KM). Jalankan simulasi menyusuri jalan raya?`)) {
                        btnStart.classList.add('d-none');
                        btnStop.classList.remove('d-none');
                        statusBadge.classList.replace('bg-secondary', 'bg-success');
                        statusBadge.innerHTML = 'Simulator Jalan Raya Aktif...';
                        
                        let step = 0;
                        let lastSyncTime = 0;
                        
                        simulationInterval = setInterval(() => {
                            if (step >= points.length) {
                                clearInterval(simulationInterval);
                                alert('Simulasi Selesai. Ambulans tiba di tujuan via jalur jalan raya.');
                                stopTracking();
                                return;
                            }

                            const [lng, lat] = points[step];
                            const now = Date.now();
                            
                            // 1. Update Visual (Sangat Halus, interval 200ms, presisi tinggi di jalur)
                            const fromArr = [marker.getLatLng().lat, marker.getLatLng().lng];
                            const toArr = [lat, lng];
                            
                            if (fromArr[0] !== lat || fromArr[1] !== lng) {
                                let diffBearing = calculateBearing(fromArr[0], fromArr[1], lat, lng);
                                updateLocalRotationOnly(diffBearing);
                            }
                            
                            animateMarkerTo(fromArr, toArr, 200);
                            polyline.addLatLng(toArr); // Update breadcrumb visual
                            
                            if (isFollowing) {
                                map.panTo(toArr, { animate: true, duration: 0.2 });
                            }
                            
                            // Whisper posisi ke Faskes/Admin (interval 200ms -> Ultra Smooth Simulation!)
                            if (window.Echo) {
                                window.Echo.join('referral.{{ $referral->id }}').whisper('positionUpdate', {
                                    lat: lat,
                                    lng: lng,
                                    heading: currentBearing,
                                    duration: 200
                                });
                            }

                            // 2. Sync Server (Hanya tiap 3 detik agar tidak membebani database)
                            if (now - lastSyncTime >= 3000 || step === points.length - 1) {
                                updateLocationOnServer(lat, lng, true); // true = skipVisual default
                                lastSyncTime = now;
                            }
                            
                            if (step >= points.length - 1) {
                                clearInterval(simulationInterval);
                                
                                // AUTO-ARRIVE LOGIC FOR SIMULATOR
                                fetch('{{ route("referrals.arrive", $referral->id) }}', {
                                    method: 'POST',
                                    headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' }
                                }).then(() => {
                                    alert('🏁 SIMULASI SELESAI: Ambulans telah tiba di RS Tujuan. Status otomatis diperbarui menjadi ARRIVED.');
                                    window.location.href = '{{ route("dashboard") }}';
                                });
                                
                                stopTracking();
                                return;
                            }

                            // Maju 2 titik per 200ms (Menyusuri seluruh kurva jalan dengan sempurna)
                            step += 2; 
                            if (step >= points.length) step = points.length - 1;
                        }, 200);
                    }
                } catch (err) {
                    console.error('Routing Error:', err);
                    alert('Gagal mengambil rute jalan: ' + err.message);
                } finally {
                    btn.disabled = false;
                    btn.innerHTML = '🎮 Simulasikan Pergerakan (Virtual)';
                }
            });

            function updateLocationOnServer(lat, lng, skipVisual = false) {
                if (!skipVisual) {
                    updateMarkerAndPolyline(lat, lng);
                }
                
                const payload = { 
                    latitude: lat, 
                    longitude: lng,
                    heading: deviceHeading !== null ? deviceHeading : null
                };
                
                fetch('{{ route("tracking.location.update", $referral->id) }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify(payload)
                }).then(res => {
                    if(!res.ok) throw new Error('Network response was not ok');
                    return res.json();
                }).then(data => {
                    count++;
                    const syncEl = document.getElementById('sync-count');
                    if(syncEl) syncEl.textContent = count;
                    processOfflineQueue(); // Coba bersihkan antrean jika ada
                }).catch(err => {
                    console.warn('Gagal sinkronisasi, menyimpan ke antrean offline...', err);
                    saveToOfflineQueue(payload);
                });
            }

            // --- OFFLINE SYNC LOGIC ---
            function saveToOfflineQueue(payload) {
                let queue = JSON.parse(localStorage.getItem('esir_offline_tracking') || '[]');
                queue.push({ ...payload, timestamp: new Date().toISOString() });
                localStorage.setItem('esir_offline_tracking', JSON.stringify(queue));
                showToast('Koneksi terganggu. Lokasi disimpan offline.', 'error');
            }

            async function processOfflineQueue() {
                let queue = JSON.parse(localStorage.getItem('esir_offline_tracking') || '[]');
                if (queue.length === 0) return;

                console.log(`Menyinkronkan ${queue.length} titik tertunda...`);
                
                for (let i = 0; i < queue.length; i++) {
                    try {
                        await fetch('{{ route("tracking.location.update", $referral->id) }}', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}'
                            },
                            body: JSON.stringify(queue[i])
                        });
                        console.log('Titik offline berhasil disinkronkan.');
                    } catch (err) {
                        console.error('Gagal sinkron ulang:', err);
                        return; // Berhenti jika masih offline
                    }
                }

                localStorage.removeItem('esir_offline_tracking');
                showToast('Semua data tertunda berhasil disinkronkan!', 'success');
            }

            window.addEventListener('online', processOfflineQueue);
        }
        @endif
    });
</script>
@endpush
