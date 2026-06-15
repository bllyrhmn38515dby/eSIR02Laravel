@extends('layouts.app')

@section('content')
<div class="container py-4">
    <!-- Header Section -->
    <div class="row mb-5 align-items-center animate__animated animate__fadeIn">
        <div class="col">
            <h2 class="fw-bold text-dark mb-1">Pusat Kendali Rujukan</h2>
            <p class="text-muted mb-0">Selamat datang kembali, <span class="fw-semibold text-primary">{{ auth()->user()->name }}</span>. Pantau rujukan secara real-time.</p>
        </div>
        <div class="col-auto d-flex align-items-center">
            <form action="{{ route('dashboard') }}" method="GET" class="d-flex g-2 align-items-center bg-white bg-opacity-50 p-2 rounded-4 border border-white shadow-sm me-3">
                <div class="input-group input-group-sm me-2" style="width: 300px;">
                    <span class="input-group-text bg-transparent border-0 text-muted small">Periode:</span>
                    <input type="date" name="start_date" class="form-control border-0 bg-transparent small" value="{{ $start_date }}">
                    <span class="input-group-text bg-transparent border-0 text-muted small">-</span>
                    <input type="date" name="end_date" class="form-control border-0 bg-transparent small" value="{{ $end_date }}">
                    <button type="submit" class="btn btn-primary btn-sm rounded-3 ms-2 px-3">Filter</button>
                </div>
            </form>
            <div class="d-flex align-items-center bg-white bg-opacity-50 p-2 rounded-4 border border-white shadow-sm">
                <span class="badge bg-primary-subtle text-primary border border-primary-subtle rounded-pill px-3 py-2 me-3 d-none d-md-inline-block">
                    <i class="bi bi-shield-check me-1"></i> Mode: {{ strtoupper(auth()->user()->role) }}
                </span>
                <div class="text-end pe-2">
                    <div class="fw-bold small text-dark">{{ now()->format('l, d M Y') }}</div>
                    <div class="text-muted small" style="font-size: 0.7rem;">Sistem Sinkron: <span class="text-success"><i class="bi bi-circle-fill" style="font-size: 0.4rem; vertical-align: middle;"></i> Aktif</span></div>
                </div>
            </div>
        </div>
    </div>

    <!-- Stats Grid -->
    <div class="row g-4 mb-5">
        <div class="col-md-3">
            <div class="card border-0 shadow-sm rounded-4 bg-primary text-white p-4 h-100 position-relative overflow-hidden">
                <div class="position-absolute end-0 bottom-0 opacity-10 me-n3 mb-n3">
                    <i class="bi bi-activity" style="font-size: 8rem;"></i>
                </div>
                <div class="small opacity-75 mb-1 fw-medium text-uppercase ls-1">Rujukan Aktif</div>
                <div class="fs-1 fw-bold mb-0 lh-1">{{ $referrals_active }}</div>
                <div class="mt-3 small py-1 px-2 bg-white bg-opacity-25 rounded-pill d-inline-block">
                    Dalam proses pengiriman
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm rounded-4 bg-warning text-white p-4 h-100 position-relative overflow-hidden">
                <div class="position-absolute end-0 bottom-0 opacity-10 me-n3 mb-n3">
                    <i class="bi bi-hourglass-split" style="font-size: 8rem;"></i>
                </div>
                <div class="small opacity-75 mb-1 fw-medium text-uppercase ls-1">Menunggu Konfirmasi</div>
                <div class="fs-1 fw-bold mb-0 lh-1">{{ $referrals_waiting }}</div>
                <div class="mt-3 small py-1 px-2 bg-white bg-opacity-25 rounded-pill d-inline-block">
                    Butuh tindak lanjut
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm rounded-4 bg-success text-white p-4 h-100 position-relative overflow-hidden">
                <div class="position-absolute end-0 bottom-0 opacity-10 me-n3 mb-n3">
                    <i class="bi bi-check2-all" style="font-size: 8rem;"></i>
                </div>
                <div class="small opacity-75 mb-1 fw-medium text-uppercase ls-1">Total Selesai</div>
                <div class="fs-1 fw-bold mb-0 lh-1">{{ $referrals_completed }}</div>
                <div class="mt-3 small py-1 px-2 bg-white bg-opacity-25 rounded-pill d-inline-block">
                    Berhasil ditangani
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm rounded-4 text-white p-4 h-100 position-relative overflow-hidden" style="background: linear-gradient(135deg, #4cc9f0, #4361ee) !important;">
                <div class="position-absolute end-0 bottom-0 opacity-10 me-n3 mb-n3">
                    <i class="bi bi-lightning-charge-fill" style="font-size: 8rem;"></i>
                </div>
                <div class="small opacity-75 mb-1 fw-medium text-uppercase ls-1">Avg. Response Time</div>
                <div class="fs-1 fw-bold mb-0 lh-1">{{ round($avg_response_time, 1) }}m</div>
                <div class="mt-3 small py-1 px-2 bg-white bg-opacity-25 rounded-pill d-inline-block">
                    Metrik Mutu (KPI)
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4 mb-5">
        <!-- Visual Insights: Trend Harian -->
        <div class="col-lg-12">
            <div class="card glass-card border-0 shadow-sm animate__animated animate__fadeInUp">
                <div class="card-header bg-transparent border-0 p-4 pb-0 d-flex justify-content-between align-items-center">
                    <div>
                        <h5 class="fw-bold text-dark mb-1">Analitik Tren Rujukan</h5>
                        <p class="text-muted small mb-0">Pemantauan volume rujukan harian dan bulanan.</p>
                    </div>
                    <div class="btn-group p-1 bg-light rounded-pill">
                        <button type="button" class="btn btn-white btn-sm rounded-pill px-3 active shadow-sm" id="btn-daily">Harian</button>
                        <button type="button" class="btn btn-sm rounded-pill px-3" id="btn-monthly">Bulanan</button>
                    </div>
                </div>
                <div class="card-body p-4">
                    <div style="height: 300px;">
                        <canvas id="trendChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4 mb-5">
        <!-- Top Diagnoses -->
        <div class="col-lg-8">
            <div class="card glass-card border-0 h-100 shadow-sm animate__animated animate__fadeInLeft">
                <div class="card-header bg-transparent border-0 p-4 pb-0">
                    <h5 class="fw-bold text-dark mb-0"><i class="bi bi-diagram-3 me-2"></i> Top 5 Diagnosa (ICD-10)</h5>
                </div>
                <div class="card-body p-4">
                    <div class="row align-items-center">
                        <div class="col-md-5">
                            <div style="height: 250px;">
                                <canvas id="diagnosesChart"></canvas>
                            </div>
                        </div>
                        <div class="col-md-7">
                            <div class="ps-md-4 mt-4 mt-md-0">
                                @foreach($top_diagnoses as $diag)
                                <div class="mb-3">
                                    <div class="d-flex justify-content-between mb-1">
                                        <span class="small fw-bold text-dark truncate-1" style="max-width: 80%;">{{ $diag->diagnosis }}</span>
                                        <span class="small text-muted">{{ $diag->count }} Kasus</span>
                                    </div>
                                    <div class="progress" style="height: 6px;">
                                        <div class="progress-bar bg-primary rounded-pill" role="progressbar" style="width: {{ ($diag->count / ($top_diagnoses->sum('count') ?: 1)) * 100 }}%"></div>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Distribution & Quick Actions -->
        <div class="col-lg-4">
            <div class="card glass-card border-0 h-100 overflow-hidden shadow-sm animate__animated animate__fadeInRight">
                <div class="card-header bg-transparent border-0 p-4 pb-0">
                    <ul class="nav nav-pills nav-fill bg-light rounded-pill p-1" id="summaryTab" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active rounded-pill small py-1" id="status-tab" data-bs-toggle="tab" data-bs-target="#status-content" type="button" role="tab">Status</button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link rounded-pill small py-1" id="triage-tab" data-bs-toggle="tab" data-bs-target="#triage-content" type="button" role="tab">Triase</button>
                        </li>
                    </ul>
                </div>
                <div class="card-body p-4 pt-3 text-center">
                    <div class="tab-content" id="summaryTabContent">
                        <div class="tab-pane fade show active" id="status-content" role="tabpanel">
                            <div style="height: 180px;" class="mb-3">
                                <canvas id="statusChart"></canvas>
                            </div>
                            <p class="small text-muted mb-0">Distribusi Status Rujukan</p>
                        </div>
                        <div class="tab-pane fade" id="triage-content" role="tabpanel">
                            <div style="height: 180px;" class="mb-3">
                                <canvas id="triageChart"></canvas>
                            </div>
                            <p class="small text-muted mb-0">Distribusi Kondisi Pasien (GCS)</p>
                        </div>
                    </div>
                    
                    <div class="d-grid gap-2 mt-4">
                        <a href="{{ route('referrals.create') }}" class="btn btn-primary rounded-pill py-2 fw-bold shadow-sm">
                            <i class="bi bi-plus-circle me-1"></i> Rujukan Baru
                        </a>
                        <button type="button" class="btn btn-indigo rounded-pill py-2 fw-bold shadow-sm text-white" style="background-color: #6610f2;" data-bs-toggle="modal" data-bs-target="#qrScanModal">
                            <i class="bi bi-qr-code-scan me-1"></i> Scan QR Handover
                        </button>
                        <div class="row g-2">
                            <div class="col-6">
                                <a href="{{ route('bed-capacities.index') }}" class="btn btn-sm btn-outline-primary w-100 rounded-pill py-2 small fw-bold">
                                    <i class="bi bi-hospital me-1"></i> Bed Kap
                                </a>
                            </div>
                            <div class="col-6">
                                <a href="{{ route('referrals.index') }}" class="btn btn-sm btn-outline-secondary w-100 rounded-pill py-2 small fw-bold">
                                    <i class="bi bi-table me-1"></i> Laporan
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Activity Table -->
    <div class="card border-0 shadow-sm rounded-4 overflow-hidden mb-5">
        <div class="card-header bg-transparent border-0 p-4">
            <div class="d-flex justify-content-between align-items-center">
                <h5 class="fw-bold text-dark mb-0">Aktivitas Rujukan Terkini</h5>
                <a href="{{ route('referrals.index') }}" class="btn btn-sm btn-link text-decoration-none fw-bold">Lihat Semua <i class="bi bi-arrow-right small"></i></a>
            </div>
        </div>
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="bg-light bg-opacity-50">
                    <tr>
                        <th class="ps-4 py-3 text-uppercase small fw-bold text-muted">ID & Pasien</th>
                        <th class="py-3 text-uppercase small fw-bold text-muted">Dari / Ke</th>
                        <th class="py-3 text-uppercase small fw-bold text-muted text-center">Status</th>
                        <th class="py-3 text-uppercase small fw-bold text-muted">Waktu</th>
                        <th class="pe-4 py-3 text-uppercase small fw-bold text-muted text-end">Detail</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($recent_referrals as $ref)
                    <tr id="referral-row-{{ $ref->id }}">
                        <td class="ps-4">
                            <div class="d-flex align-items-center">
                                <div class="bg-primary-subtle rounded-3 p-2 me-3 text-primary fw-bold small">
                                    #{{ $ref->id }}
                                </div>
                                <div>
                                    <span class="fw-bold d-block text-dark">{{ $ref->patient->name }}</span>
                                    <small class="text-muted"><i class="bi bi-person-vcard small me-1"></i>NIK: {{ $ref->patient->nik }}</small>
                                </div>
                            </div>
                        </td>
                        <td>
                            <div class="small">
                                <span class="text-muted">Asal:</span> {{ $ref->fromFaskes->name }}<br>
                                <span class="text-muted">Tujuan:</span> <span class="fw-medium text-dark">{{ $ref->toFaskes->name }}</span>
                            </div>
                        </td>
                        <td class="text-center">
                            @php
                                $statusClasses = [
                                    'draft' => 'bg-secondary-subtle text-secondary',
                                    'sent' => 'bg-warning-subtle text-warning',
                                    'accepted' => 'bg-info-subtle text-info',
                                    'traveling' => 'bg-primary-subtle text-primary',
                                    'arrived' => 'bg-indigo-subtle text-indigo',
                                    'completed' => 'bg-success-subtle text-success',
                                    'cancelled' => 'bg-danger-subtle text-danger',
                                ];
                            @endphp
                            <span class="badge rounded-pill px-3 py-2 {{ $statusClasses[$ref->status] ?? 'bg-secondary' }}">
                                {{ strtoupper($ref->status) }}
                            </span>
                        </td>
                        <td>
                            <div class="d-flex flex-column">
                                <span class="small text-dark fw-medium">{{ $ref->created_at->diffForHumans() }}</span>
                                <span class="text-muted" style="font-size: 0.7rem;">{{ $ref->created_at->format('H:i') }} WIB</span>
                            </div>
                        </td>
                        <td class="pe-4 text-end">
                            <a href="{{ route('referrals.show', $ref->id) }}" class="btn btn-sm btn-light border rounded-pill px-3 fw-semibold">
                                <i class="bi bi-chevron-right"></i>
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="text-center py-5">
                            <i class="bi bi-inbox fs-1 text-muted d-block mb-3"></i>
                            <h6 class="text-muted fw-normal">Belum ada aktivitas rujukan baru-baru ini.</h6>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
</div>

<!-- QR Scan Modal -->
<div class="modal fade" id="qrScanModal" tabindex="-1" aria-labelledby="qrScanModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg rounded-4">
            <div class="modal-header bg-indigo text-white border-0 py-3" style="background-color: #6610f2;">
                <h5 class="modal-title fw-bold" id="qrScanModalLabel"><i class="bi bi-qr-code-scan me-2"></i> Smart Handover Scanner</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close" id="btn-close-scanner"></button>
            </div>
            <div class="modal-body p-4 text-center">
                <p class="text-muted small mb-3">Arahkan kamera ke QR Code yang dibawa oleh supir ambulans.</p>
                <div id="reader" style="width: 100%; border-radius: 12px; overflow: hidden; border: 2px solid #eee;"></div>
                <div id="scan-result" class="mt-3"></div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://unpkg.com/html5-qrcode"></script>
<script>
    document.addEventListener("DOMContentLoaded", function() {
        // Real-time Status Update Listener
        if (window.Echo) {
            window.Echo.channel('referrals')
                .listen('.ReferralStatusChanged', (e) => {
                    console.log('Referral Status Updated:', e.referral);
                    
                    // Cari baris di tabel aktivitas terkini
                    const row = document.getElementById(`referral-row-${e.referral.id}`);
                    if (row) {
                        const statusCell = row.querySelector('td:nth-child(3)');
                        if (statusCell) {
                            const statusClasses = {
                                'draft': 'bg-secondary-subtle text-secondary',
                                'sent': 'bg-warning-subtle text-warning',
                                'accepted': 'bg-info-subtle text-info',
                                'traveling': 'bg-primary-subtle text-primary',
                                'arrived': 'bg-indigo-subtle text-indigo',
                                'completed': 'bg-success-subtle text-success',
                                'cancelled': 'bg-danger-subtle text-danger',
                            };
                            const badgeClass = statusClasses[e.referral.status] || 'bg-secondary';
                            statusCell.innerHTML = `
                                <span class="badge rounded-pill px-3 py-2 ${badgeClass} animate__animated animate__flash">
                                    ${e.referral.status.toUpperCase()}
                                </span>
                            `;
                        }
                    }
                    
                    // Opsional: Jika ingin angka statistik di atas juga update, reload halaman adalah cara termudah
                    // Namun untuk pengalaman premium, kita biarkan row saja yang kedip update.
                });
        }

        // QR Scanner Logic
        const html5QrCode = new Html5Qrcode("reader");
        const qrModal = document.getElementById('qrScanModal');
        const scanResult = document.getElementById('scan-result');
        const btnClose = document.getElementById('btn-close-scanner');

        qrModal.addEventListener('shown.bs.modal', function () {
            Html5Qrcode.getCameras().then(cameras => {
                if (cameras && cameras.length > 0) {
                    html5QrCode.start(
                        { facingMode: "environment" }, 
                        { fps: 10, qrbox: { width: 250, height: 250 } },
                        (decodedText, decodedResult) => {
                            // Scanned!
                            html5QrCode.stop();
                            processHandover(decodedText);
                        },
                        (errorMessage) => { /* scanning... */ }
                    ).catch(err => {
                        scanResult.innerHTML = `<div class="alert alert-danger small">${err}</div>`;
                    });
                }
            }).catch(err => {
                scanResult.innerHTML = `<div class="alert alert-danger small">Kamera tidak ditemukan atau diblokir.</div>`;
            });
        });

        qrModal.addEventListener('hidden.bs.modal', function () {
            if (html5QrCode.isScanning) {
                html5QrCode.stop();
            }
            scanResult.innerHTML = '';
        });

        function processHandover(url) {
            console.log("Scanned URL:", url);
            scanResult.innerHTML = `<div class="spinner-border text-primary" role="status"><span class="visually-hidden">Loading...</span></div><p class="mt-2 small">Memproses Handover...</p>`;
            
            // Periksa apakah URL valid (berisi endpoint handover)
            if (!url.toLowerCase().includes('handover')) {
                scanResult.innerHTML = `<div class="alert alert-danger small">
                    <strong>QR Code tidak valid.</strong><br>
                    Teks terdeteksi: <code class="small text-break">${url}</code><br>
                    Pastikan men-scan QR Pass dari dashboard supir.
                </div>`;
                return;
            }

            // Kirim POST request ke URL yang di-scan
            fetch(url, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json'
                }
            })
            .then(response => {
                if (response.redirected) {
                    window.location.href = response.url;
                    return;
                }
                return response.json();
            })
            .then(data => {
                if (data && data.success) {
                    scanResult.innerHTML = `<div class="alert alert-success small">Handover Berhasil! Mengalihkan...</div>`;
                    setTimeout(() => window.location.reload(), 1500);
                } else if (data && data.message) {
                    scanResult.innerHTML = `<div class="alert alert-danger small">${data.message}</div>`;
                }
            })
            .catch(err => {
                console.error(err);
                // Jika redirected, biarkan saja karena sudah diproses browser
                if (!err.message.includes('redirected')) {
                    scanResult.innerHTML = `<div class="alert alert-danger small">Terjadi kesalahan koneksi atau akses ditolak.</div>`;
                }
            });
        }

        // Shared Chart Configuration
        const primaryColor = '#4361ee';
        const secondaryColor = '#f72585';
        const successColor = '#06d6a0';
        const infoColor = '#4cc9f0';

        // Distribution Chart
        let statusCtx = document.getElementById('statusChart');
        if (statusCtx) {
            new Chart(statusCtx, {
                type: 'doughnut',
                data: {
                    labels: ['Aktif', 'Menunggu', 'Selesai'],
                    datasets: [{
                        data: [{{ $referrals_active }}, {{ $referrals_waiting }}, {{ $referrals_completed }}],
                        backgroundColor: [primaryColor, secondaryColor, successColor],
                        borderWidth: 0,
                        hoverOffset: 12
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    cutout: '80%',
                    plugins: {
                        legend: { display: false }
                    }
                }
            });
        }

        // Daily Trend Chart
        let trendChart;
        const trendCtx = document.getElementById('trendChart');
        if (trendCtx) {
            const gradient = trendCtx.getContext('2d').createLinearGradient(0, 0, 0, 400);
            gradient.addColorStop(0, 'rgba(67, 97, 238, 0.4)');
            gradient.addColorStop(1, 'rgba(67, 97, 238, 0.0)');

            const dailyData = {
                labels: {!! json_encode($referral_stats->pluck('date')) !!},
                datasets: [{
                    label: 'Rujukan Harian',
                    data: {!! json_encode($referral_stats->pluck('count')) !!},
                    borderColor: primaryColor,
                    backgroundColor: gradient,
                    fill: true,
                    tension: 0.4,
                    pointBackgroundColor: '#fff',
                    pointBorderColor: primaryColor,
                    pointBorderWidth: 3,
                    pointRadius: 6,
                    pointHoverRadius: 8
                }]
            };

            const monthlyData = {
                labels: {!! json_encode($monthly_stats->pluck('month')) !!},
                datasets: [{
                    label: 'Rujukan Bulanan',
                    data: {!! json_encode($monthly_stats->pluck('count')) !!},
                    backgroundColor: primaryColor,
                    borderRadius: 10,
                    barThickness: 30
                }]
            };

            trendChart = new Chart(trendCtx, {
                type: 'line',
                data: dailyData,
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    interaction: { intersect: false, mode: 'index' },
                    plugins: { legend: { display: false } },
                    scales: {
                        y: { beginAtZero: true, grid: { borderDash: [5, 5], color: 'rgba(0,0,0,0.03)' } },
                        x: { grid: { display: false } }
                    }
                }
            });

            // Toggle logic
            $('#btn-daily').on('click', function() {
                $(this).addClass('active shadow-sm').siblings().removeClass('active shadow-sm');
                trendChart.config.type = 'line';
                trendChart.config.data = dailyData;
                trendChart.update();
            });

            $('#btn-monthly').on('click', function() {
                $(this).addClass('active shadow-sm').siblings().removeClass('active shadow-sm');
                trendChart.config.type = 'bar';
                trendChart.config.data = monthlyData;
                trendChart.update();
            });
        }

        // Triage Chart
        let triageCtx = document.getElementById('triageChart');
        if (triageCtx) {
            new Chart(triageCtx, {
                type: 'doughnut',
                data: {
                    labels: {!! json_encode($triage_stats->pluck('triage')) !!},
                    datasets: [{
                        data: {!! json_encode($triage_stats->pluck('count')) !!},
                        backgroundColor: [
                            '#ef476f', // Red
                            '#ffd166', // Yellow
                            '#06d6a0', // Green
                            '#118ab2', // Blue/Non
                            '#073b4c'
                        ],
                        borderWidth: 0,
                        hoverOffset: 12
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    cutout: '70%',
                    plugins: {
                        legend: { display: false }
                    }
                }
            });
        }

        // Diagnoses Chart
        const diagCtx = document.getElementById('diagnosesChart');
        if (diagCtx) {
            new Chart(diagCtx, {
                type: 'pie',
                data: {
                    labels: {!! json_encode($top_diagnoses->pluck('diagnosis')) !!},
                    datasets: [{
                        data: {!! json_encode($top_diagnoses->pluck('count')) !!},
                        backgroundColor: [primaryColor, infoColor, successColor, secondaryColor, '#3a0ca3'],
                        borderWidth: 2,
                        borderColor: '#fff'
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { display: false }
                    }
                }
            });
        }
    });
</script>
@endpush
