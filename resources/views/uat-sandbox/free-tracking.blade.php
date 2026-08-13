@extends('layouts.app')

@push('styles')
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" crossorigin=""/>
<link rel="stylesheet" href="https://unpkg.com/leaflet-routing-machine@latest/dist/leaflet-routing-machine.css" />
<style>
    #map { height: 75vh; width: 100%; border-radius: 12px; box-shadow: 0 4px 15px rgba(0,0,0,0.1); position: relative; cursor: crosshair; }
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
    <div class="row">
        <div class="col-md-3 mb-3">
            <div class="card h-100 shadow-sm border-0">
                <div class="card-header bg-dark text-white font-weight-bold border-0">
                    <i class="bi bi-geo-alt-fill"></i> Free Tracking & Routing
                </div>
                <div class="card-body">
                    <p class="text-muted small">Halaman ini digunakan untuk menguji GPS dan Magnet Rute (OSRM) secara mandiri tanpa perlu membuat data Rujukan.</p>
                    
                    <div class="alert alert-info py-2" style="font-size: 0.85rem;">
                        <i class="bi bi-info-circle-fill"></i> <strong>Cara Pakai:</strong> Klik di mana saja pada peta untuk mengatur Titik Tujuan (Garis Biru).
                    </div>
                    <hr>
                    
                    <!-- Real-Time Speedometer -->
                    <div id="speedometer-panel" class="p-3 bg-dark text-white rounded mb-3 border text-center position-relative overflow-hidden d-none" style="transition: background-color 0.5s ease;">
                        <div class="position-absolute w-100 h-100 start-0 top-0 opacity-25" style="background: repeating-linear-gradient(45deg, transparent, transparent 10px, rgba(255,255,255,0.1) 10px, rgba(255,255,255,0.1) 20px);"></div>
                        <h6 class="mb-0 text-uppercase fw-bold text-info" style="position: relative; z-index: 2;"><i class="bi bi-speedometer2"></i> Kecepatan</h6>
                        <div class="display-4 fw-bolder mt-1 mb-0" id="current-speed" style="position: relative; z-index: 2;">
                            0 <span class="fs-4 text-white-50">km/j</span>
                        </div>
                    </div>

                    <button class="btn btn-success w-100 mb-2 py-2 fw-bold shadow-sm" id="btn-start-tracking"><i class="bi bi-play-circle"></i> Mulai Bergerak</button>
                    <button class="btn btn-danger w-100 d-none mb-2 py-2 fw-bold shadow-sm" id="btn-stop-tracking"><i class="bi bi-stop-circle"></i> Hentikan Tracking</button>
                    
                    <div class="mt-3 border-top pt-3">
                        <div class="form-check form-switch mb-2">
                            <input class="form-check-input" type="checkbox" id="toggle-snap-road" checked>
                            <label class="form-check-label small fw-bold text-dark" for="toggle-snap-road">Magnet Rute (Snap to Road)</label>
                        </div>
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" id="toggle-ema" checked>
                            <label class="form-check-label small" for="toggle-ema">Filter Anti-Jitter (EMA)</label>
                        </div>
                    </div>

                    <span id="tracker-status" class="badge bg-secondary w-100 py-3 mt-3 fs-6 rounded-pill border shadow-sm">
                        Tracker Offline
                    </span>
                </div>
            </div>
        </div>

        <div class="col-md-9 mb-3">
            <div id="map">
                <div id="btn-center-map" class="map-control-center active" title="Tengahkan ke Perangkat">
                    <i class="bi bi-crosshair"></i>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" crossorigin=""></script>
<script src="https://unpkg.com/leaflet-routing-machine@latest/dist/leaflet-routing-machine.js"></script>
<script>
    document.addEventListener("DOMContentLoaded", function() {
        const defaultLat = -6.175110;
        const defaultLng = 106.827152;
        
        let pathPoints = []; 
        let lastLocation = [defaultLat, defaultLng];
        
        const map = L.map('map', { maxZoom: 20 }).setView(lastLocation, 15);
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', { 
            maxZoom: 20,
            maxNativeZoom: 19 
        }).addTo(map);

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

        let marker = L.marker(lastLocation, {icon: createAmbulanceIcon(0)}).addTo(map);
        let polyline = L.polyline(pathPoints, {color: '#f72585', weight: 4, opacity: 0.8 }).addTo(map);
        let destMarker = null;

        // Routing Logic
        let routingControl = L.Routing.control({
            waypoints: [],
            routeWhileDragging: false,
            addWaypoints: false,
            draggableWaypoints: false,
            fitSelectedRoutes: false,
            show: false,
            lineOptions: {
                styles: [{ color: '#3a86ff', opacity: 0.6, weight: 6 }]
            },
            createMarker: function() { return null; }
        }).addTo(map);

        let currentRouteCoords = [];

        routingControl.on('routesfound', function(e) {
            currentRouteCoords = e.routes[0].coordinates;
        });

        // Click map to set destination
        map.on('click', function(e) {
            const destLat = e.latlng.lat;
            const destLng = e.latlng.lng;
            
            if (destMarker) {
                destMarker.setLatLng(e.latlng);
            } else {
                destMarker = L.marker(e.latlng, {
                    icon: L.icon({
                        iconUrl: 'https://raw.githubusercontent.com/pointhi/leaflet-color-markers/master/img/marker-icon-2x-red.png',
                        shadowUrl: 'https://cdnjs.cloudflare.com/ajax/libs/leaflet/0.7.7/images/marker-shadow.png',
                        iconSize: [25, 41],
                        iconAnchor: [12, 41],
                    })
                }).addTo(map);
            }

            const currentPos = marker.getLatLng();
            routingControl.setWaypoints([
                L.latLng(currentPos.lat, currentPos.lng),
                L.latLng(destLat, destLng)
            ]);
        });

        function snapToRoute(lat, lng) {
            const snapToggle = document.getElementById('toggle-snap-road');
            if (snapToggle && !snapToggle.checked) {
                return [lat, lng];
            }

            if (!currentRouteCoords || currentRouteCoords.length === 0) return [lat, lng];
            
            let closest = [lat, lng];
            let minDistance = Infinity;
            const p = L.latLng(lat, lng);

            for (let i = 0; i < currentRouteCoords.length; i++) {
                const routePt = currentRouteCoords[i];
                const d = p.distanceTo(routePt);
                if (d < minDistance && d < 60) {
                    minDistance = d;
                    closest = [routePt.lat, routePt.lng];
                }
            }
            return closest;
        }

        let isFollowing = true;
        const btnCenter = document.getElementById('btn-center-map');

        function calculateBearing(fromLat, fromLng, toLat, toLng) {
            const dLng  = (toLng - fromLng) * Math.PI / 180;
            const lat1  = fromLat * Math.PI / 180;
            const lat2  = toLat   * Math.PI / 180;
            const y     = Math.sin(dLng) * Math.cos(lat2);
            const x     = Math.cos(lat1) * Math.sin(lat2) - Math.sin(lat1) * Math.cos(lat2) * Math.cos(dLng);
            return (Math.atan2(y, x) * 180 / Math.PI + 360) % 360;
        }

        function updateSpeedometerUI(speedKmph) {
            const panel = document.getElementById('speedometer-panel');
            const speedEl = document.getElementById('current-speed');
            if (panel && speedEl) {
                panel.classList.remove('d-none');
                panel.classList.remove('bg-dark', 'bg-success', 'bg-warning', 'bg-danger', 'text-dark', 'text-white');
                let labelClass = 'text-white-50';
                if (speedKmph > 80) {
                    panel.classList.add('bg-danger', 'text-white');
                } else if (speedKmph > 40) {
                    panel.classList.add('bg-warning', 'text-dark');
                    labelClass = 'text-muted';
                } else if (speedKmph > 5) {
                    panel.classList.add('bg-success', 'text-white');
                } else {
                    panel.classList.add('bg-dark', 'text-white');
                }
                speedEl.innerHTML = `${Math.round(speedKmph)} <span class="fs-4 ${labelClass}">km/j</span>`;
            }
        }

        let animationFrameId = null;
        function animateMarkerTo(fromLatLng, toLatLng, durationMs) {
            durationMs = durationMs || 1000;
            if (animationFrameId) cancelAnimationFrame(animationFrameId);
            const startTime = performance.now();
            const startLat  = fromLatLng[0], startLng = fromLatLng[1];
            const endLat    = toLatLng[0],   endLng   = toLatLng[1];
            function step(now) {
                const elapsed = now - startTime;
                let t = Math.min(elapsed / durationMs, 1);
                t = t < 0.5 ? 4*t*t*t : 1 - Math.pow(-2*t + 2, 3) / 2;
                marker.setLatLng([
                    startLat + (endLat - startLat) * t,
                    startLng + (endLng - startLng) * t
                ]);
                if (t < 1) animationFrameId = requestAnimationFrame(step);
                else animationFrameId = null;
            }
            animationFrameId = requestAnimationFrame(step);
        }

        let currentBearing = 0;
        let deviceHeading = null;
        
        const EMA_ALPHA = 0.25;
        let emaLat = null;
        let emaLng = null;

        function applyEMA(rawLat, rawLng) {
            const useEma = document.getElementById('toggle-ema').checked;
            if (!useEma || emaLat === null || emaLng === null) {
                emaLat = rawLat;
                emaLng = rawLng;
            } else {
                emaLat = EMA_ALPHA * rawLat + (1 - EMA_ALPHA) * emaLat;
                emaLng = EMA_ALPHA * rawLng + (1 - EMA_ALPHA) * emaLng;
            }
            return [emaLat, emaLng];
        }

        const MAX_SPEED_MPS = 80;
        let lastValidTime = null;
        let lastValidLat  = null;
        let lastValidLng  = null;

        function isGPSSpike(lat, lng) {
            if (lastValidLat === null || lastValidLng === null || lastValidTime === null) return false;
            const dt = (Date.now() - lastValidTime) / 1000;
            if (dt <= 0) return false;
            const dist = L.latLng(lastValidLat, lastValidLng).distanceTo(L.latLng(lat, lng));
            const speed = dist / dt;
            if (speed > MAX_SPEED_MPS) return true;
            return false;
        }

        function recordValidPosition(lat, lng) {
            lastValidLat  = lat;
            lastValidLng  = lng;
            lastValidTime = Date.now();
        }

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

        map.on('dragstart', function() {
            isFollowing = false;
            btnCenter.classList.remove('active');
        });

        btnCenter.addEventListener('click', function() {
            isFollowing = true;
            btnCenter.classList.add('active');
            map.panTo(marker.getLatLng());
            map.setZoom(18);
        });

        let watchId;
        let count = 0;
        let lastRoutingUpdate = 0;
        const ROUTING_THROTTLE = 15000; // Update route max every 15s to save API

        const btnStart = document.getElementById('btn-start-tracking');
        const btnStop = document.getElementById('btn-stop-tracking');
        const statusBadge = document.getElementById('tracker-status');

        btnStart.addEventListener('click', () => {
            if ("geolocation" in navigator) {
                btnStart.classList.add('d-none');
                btnStop.classList.remove('d-none');
                statusBadge.classList.replace('bg-secondary', 'bg-warning');
                statusBadge.innerHTML = '<span class="spinner-grow spinner-grow-sm me-2"></span> Mencari Sinyal GPS...';

                if (window.DeviceOrientationEvent) {
                    window.addEventListener('deviceorientationabsolute', function(event) {
                        if (event.alpha !== null) deviceHeading = 360 - event.alpha;
                    }, true);
                    window.addEventListener('deviceorientation', function(event) {
                        if (event.webkitCompassHeading) deviceHeading = event.webkitCompassHeading;
                        else if (event.absolute && event.alpha !== null) deviceHeading = 360 - event.alpha;
                        if (deviceHeading !== null) updateLocalRotationOnly(deviceHeading);
                    }, true);
                }

                watchId = navigator.geolocation.watchPosition(function(position) {
                    const rawLat = position.coords.latitude;
                    const rawLng = position.coords.longitude;

                    if (isGPSSpike(rawLat, rawLng)) return;

                    let currentSpeedKmph = 0;
                    if (position.coords.speed !== null && !isNaN(position.coords.speed)) {
                        currentSpeedKmph = position.coords.speed * 3.6;
                    } else {
                        if (lastValidLat !== null && lastValidLng !== null && lastValidTime !== null) {
                            const dt = (Date.now() - lastValidTime) / 1000;
                            if (dt > 0) {
                                const dist = L.latLng(lastValidLat, lastValidLng).distanceTo(L.latLng(rawLat, rawLng));
                                currentSpeedKmph = (dist / dt) * 3.6;
                            }
                        }
                    }

                    recordValidPosition(rawLat, rawLng);
                    
                    // EMA
                    const [emaLatResult, emaLngResult] = applyEMA(rawLat, rawLng);
                    
                    // Snap to Road
                    const snapped = snapToRoute(emaLatResult, emaLngResult);
                    const lat = snapped[0];
                    const lng = snapped[1];

                    updateSpeedometerUI(currentSpeedKmph);

                    if (count === 0) {
                        statusBadge.classList.replace('bg-warning', 'bg-success');
                        map.setView([lat, lng], 18); // Zoom in on first lock
                    }
                    
                    count++;
                    statusBadge.innerHTML = `Tracker Aktif & Merekam (Ping: ${count}x)`;

                    const from = marker.getLatLng();
                    const toArr = [lat, lng];
                    const distance = from.distanceTo(L.latLng(lat, lng));

                    if (distance > 1) {
                        const calculatedBearing = calculateBearing(from.lat, from.lng, lat, lng);
                        if (deviceHeading === null) updateLocalRotationOnly(calculatedBearing);
                    }

                    animateMarkerTo([from.lat, from.lng], toArr, 1500);
                    polyline.addLatLng(toArr);

                    if (isFollowing) {
                        map.panTo(toArr, { animate: true, duration: 1.5 });
                    }

                    // Throttle routing recalculation based on current location
                    if (destMarker) {
                        const now = Date.now();
                        if (now - lastRoutingUpdate > ROUTING_THROTTLE) {
                            routingControl.setWaypoints([
                                L.latLng(lat, lng),
                                destMarker.getLatLng()
                            ]);
                            lastRoutingUpdate = now;
                        }
                    }

                }, function(error) {
                    console.error("Geolokasi gagal: ", error);
                    alert("Gagal membaca GPS. Harap periksa izin lokasi browser dan perangkat Anda.");
                    stopTracking();
                }, { 
                    enableHighAccuracy: true,
                    timeout: 10000,
                    maximumAge: 0
                });
            } else {
                alert("Browser tidak mendukung geolokasi.");
            }
        });

        btnStop.addEventListener('click', stopTracking);

        function stopTracking() {
            if (watchId) navigator.geolocation.clearWatch(watchId);
            btnStop.classList.add('d-none');
            btnStart.classList.remove('d-none');
            statusBadge.classList.replace('bg-warning', 'bg-secondary');
            statusBadge.classList.replace('bg-success', 'bg-secondary');
            statusBadge.innerHTML = `Tracker Berhenti (Total Titik: ${count})`;
        }
    });
</script>
@endpush
