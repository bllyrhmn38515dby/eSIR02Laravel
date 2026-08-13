@extends('layouts.app')

@section('content')
<div class="container-fluid py-4 px-lg-4">
    <!-- Personnel Header Card -->
    <div class="row mb-4 animate__animated animate__fadeIn">
        <div class="col-12">
            <div class="card glass-card border-0 shadow-lg overflow-hidden" style="background: linear-gradient(135deg, var(--medical-primary) 0%, var(--medical-secondary) 100%) !important;">
                <div class="card-body p-4 p-md-5">
                    <div class="row align-items-center">
                        <div class="col-auto">
                            <div class="avatar-lg bg-white rounded-circle p-1 shadow-lg d-flex align-items-center justify-content-center" style="width: 80px; height: 80px;">
                                <div class="w-100 h-100 rounded-circle bg-primary bg-opacity-10 d-flex align-items-center justify-content-center fw-bold fs-2 text-primary">
                                    {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                                </div>
                            </div>
                        </div>
                        <div class="col ms-md-2 mt-3 mt-md-0">
                            <h2 class="text-white fw-bold mb-1">Halo, {{ auth()->user()->name }}!</h2>
                            <div class="d-flex flex-wrap align-items-center gap-2">
                                <span class="badge bg-success bg-opacity-25 text-white border border-white border-opacity-50 px-3 py-2 rounded-pill">
                                    <i class="bi bi-shield-check me-1"></i> DUTY STATUS: ACTIVE
                                </span>
                                <span class="text-white text-opacity-75 small"><i class="bi bi-building me-1"></i> {{ auth()->user()->faskes->name }}</span>
                            </div>
                        </div>
                        <div class="col-md-auto mt-4 mt-md-0">
                            <div class="text-white text-md-end">
                                <div class="small opacity-75">TANGGAL OPERASIONAL</div>
                                <div class="fs-4 fw-bold">{{ now()->format('d F Y') }}</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4">
        <!-- Main Control Region -->
        <div class="col-xl-8">
            @if($active_mission)
                <!-- Active Mission Hub -->
                <div class="card glass-card border-0 mb-4 animate__animated animate__fadeInLeft">
                    <div class="card-header bg-white bg-opacity-50 py-3 px-4 border-0 d-flex justify-content-between align-items-center">
                        <h5 class="fw-bold mb-0 text-primary ls-1"><i class="bi bi-broadcast me-2"></i>LIVE MISSION RADAR</h5>
                        <span class="badge bg-primary text-uppercase px-3 py-2 rounded-pill shadow-sm">{{ str_replace('_', ' ', $active_mission->status) }}</span>
                    </div>
                    <div class="card-body p-4 p-lg-5">
                        <div class="row align-items-center">
                            <div class="col-lg-7">
                                <!-- Patient Info Radar -->
                                <div class="p-4 rounded-4 mb-4" style="background: rgba(59, 130, 246, 0.05); border: 2px dashed rgba(59, 130, 246, 0.2);">
                                    <small class="text-primary text-uppercase fw-bold ls-1 mb-2 d-block" style="font-size: 0.7rem;">Target Profile</small>
                                    <h3 class="fw-bold text-dark mb-1">{{ $active_mission->patient->name }}</h3>
                                    <div class="d-flex align-items-center gap-3">
                                        <span class="text-muted small"><i class="bi bi-hash me-1"></i>{{ $active_mission->referral_number }}</span>
                                        <span class="badge bg-white text-dark border rounded-pill px-2">Kondisi: STABIL</span>
                                    </div>
                                </div>

                                <!-- Route Timeline Explorer -->
                                <div class="route-timeline position-relative ps-4 ms-2">
                                    <div class="line position-absolute start-0 top-0 bottom-0 bg-primary opacity-10" style="width: 4px; left: -2px !important; border-radius: 2px;"></div>
                                    
                                    <div class="mb-5 position-relative">
                                        <div class="dot position-absolute start-0 rounded-circle bg-white border border-4 border-primary" style="width: 20px; height: 20px; left: -10px; top: 2px;"></div>
                                        <div class="ms-3">
                                            <small class="text-muted fw-bold d-block text-uppercase" style="font-size: 0.65rem;">ASAL (PENJEMPUTAN)</small>
                                            <h6 class="fw-bold mb-0 mt-1">{{ $active_mission->fromFaskes->name }}</h6>
                                            <p class="text-muted small mb-0">{{ $active_mission->fromFaskes->address }}</p>
                                        </div>
                                    </div>

                                    <div class="position-relative">
                                        <div class="dot active-pulse position-absolute start-0 rounded-circle bg-primary" style="width: 20px; height: 20px; left: -10px; top: 2px;"></div>
                                        <div class="ms-3">
                                            <small class="text-primary fw-bold d-block text-uppercase" style="font-size: 0.65rem;">TUJUAN (RS TUJUAN)</small>
                                            <h6 class="fw-bold mb-0 mt-1 text-primary">{{ $active_mission->toFaskes->name }}</h6>
                                            <p class="text-muted small mb-0">{{ $active_mission->toFaskes->address }}</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-5 mt-4 mt-lg-0">
                                <div class="bg-light p-4 rounded-4 text-center">
                                    <div class="radar-pulse mb-3">
                                        <div class="pulse-ring"></div>
                                        <i class="bi bi-geo-alt-fill fs-1 text-primary"></i>
                                    </div>
                                    <p class="text-muted small mb-4 px-3">Sinyal GPS Anda terdeteksi. Tim RS Tujuan sedang memantau posisi Anda.</p>
                                    
                                    @if($active_mission->status === 'accepted')
                                        <form action="{{ route('referrals.start-trip', $active_mission->id) }}" method="POST">
                                            @csrf
                                            <button type="submit" class="btn btn-primary w-100 py-3 rounded-4 fw-bold shadow-lg mb-3">
                                                <i class="bi bi-play-circle me-2"></i>MULAI PERJALANAN
                                            </button>
                                        </form>
                                    @endif

                                    <div class="d-grid gap-2">
                                        <a href="https://www.google.com/maps/dir/?api=1&destination={{ $active_mission->toFaskes->latitude }},{{ $active_mission->toFaskes->longitude }}" target="_blank" class="btn btn-outline-success border-2 rounded-pill fw-bold py-2">
                                            <i class="bi bi-google me-2"></i>BUKA NAVIGASI
                                        </a>
                                        <a href="{{ route('tracking.show', $active_mission->id) }}" class="btn btn-link text-decoration-none text-muted small">
                                            <i class="bi bi-broadcast me-1"></i> Periksa Sinyal GPS
                                        </a>
                                    </div>

                                    <div class="mt-4 pt-3 border-top">
                                        <h6 class="fw-bold mb-2 small text-uppercase">Handover QR Pass</h6>
                                        <div class="bg-white p-2 d-inline-block rounded-3 border shadow-sm mb-2">
                                            {!! \SimpleSoftwareIO\QrCode\Facades\QrCode::size(120)->generate(route('referrals.handover', $active_mission->id)) !!}
                                        </div>
                                        <p class="text-muted small mb-0" style="font-size: 0.65rem;">Tunjukkan kode ini ke petugas RS Tujuan saat serah terima pasien.</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @else
                <!-- Standby Hub -->
                <div class="card border-0 shadow-lg rounded-4 p-5 text-center mb-4 animate__animated animate__fadeIn">
                    <div class="py-5">
                        <div class="bg-light d-inline-block p-4 rounded-circle mb-4">
                            <i class="bi bi-clipboard2-check text-muted" style="font-size: 4rem;"></i>
                        </div>
                        <h2 class="fw-bold mb-2">Pusat Kendali Siaga</h2>
                        <p class="text-muted fs-5 mb-0">Status Anda saat ini: <span class="badge bg-success bg-opacity-10 text-success rounded-pill px-3">STANDBY</span></p>
                        <p class="text-muted small mt-2">Menunggu instruksi penjemputan baru dari Admin Faskes.</p>
                    </div>
                </div>
            @endif

            <!-- Management Tools (Optional/Placeholders for Look) -->
            <div class="row g-3 animate__animated animate__fadeInUp animate__delay-1s">
                <div class="col-6 col-md-3">
                    <a href="#" data-bs-toggle="modal" data-bs-target="#comingSoonModal" class="card border-0 shadow-sm rounded-4 text-center p-3 text-decoration-none hover-up bg-white">
                        <i class="bi bi-exclamation-triangle text-danger fs-3 mb-2"></i>
                        <span class="text-dark small fw-bold d-block">Lapor Kendala</span>
                    </a>
                </div>
                <div class="col-6 col-md-3">
                    <a href="#" data-bs-toggle="modal" data-bs-target="#comingSoonModal" class="card border-0 shadow-sm rounded-4 text-center p-3 text-decoration-none hover-up bg-white">
                        <i class="bi bi-fuel-pump text-primary fs-3 mb-2"></i>
                        <span class="text-dark small fw-bold d-block">Lapor Bahan Bakar</span>
                    </a>
                </div>
                <div class="col-6 col-md-3">
                    <a href="#" data-bs-toggle="modal" data-bs-target="#comingSoonModal" class="card border-0 shadow-sm rounded-4 text-center p-3 text-decoration-none hover-up bg-white">
                        <i class="bi bi-chat-dots text-info fs-3 mb-2"></i>
                        <span class="text-dark small fw-bold d-block">Pusat Bantuan</span>
                    </a>
                </div>
                <div class="col-6 col-md-3">
                    <a href="#" data-bs-toggle="modal" data-bs-target="#comingSoonModal" class="card border-0 shadow-sm rounded-4 text-center p-3 text-decoration-none hover-up bg-white">
                        <i class="bi bi-gear text-secondary fs-3 mb-2"></i>
                        <span class="text-dark small fw-bold d-block">Pengaturan</span>
                    </a>
                </div>
            </div>
        </div>

        <!-- Sidebar Region -->
        <div class="col-xl-4 col-12">
            <!-- Performance Section -->
            <div class="card border-0 shadow-sm rounded-4 mb-4 animate__animated animate__fadeInRight">
                <div class="card-body p-4">
                    <h5 class="fw-bold mb-4 text-uppercase ls-1 small text-muted">RINGKASAN PERFORMA</h5>
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <div>
                            <div class="fs-1 fw-bold text-dark lh-1 mb-1">{{ $total_missions }}</div>
                            <div class="text-muted small">Total Misi Selesai</div>
                        </div>
                        <div class="bg-warning bg-opacity-10 p-4 rounded-4">
                            <i class="bi bi-award-fill fs-1 text-warning"></i>
                        </div>
                    </div>
                    <div class="progress mb-2" style="height: 10px; border-radius: 5px;">
                        <div class="progress-bar bg-primary" style="width: 85%;"></div>
                    </div>
                    <p class="text-muted small mb-0"><i class="bi bi-star-fill text-warning me-1"></i> Performa Anda sangat baik bulan ini!</p>
                </div>
            </div>

            <!-- Activity Log Section -->
            <div class="card border-0 shadow-sm rounded-4 animate__animated animate__fadeInRight animate__delay-1s">
                <div class="card-header bg-white p-4 border-0">
                    <h5 class="fw-bold mb-0 text-uppercase ls-1 small text-muted">MISI TERBARU</h5>
                </div>
                <div class="card-body p-0 pb-3">
                    <div class="list-group list-group-flush">
                        @forelse($recent_missions as $m)
                        <div class="list-group-item bg-transparent px-4 py-3 border-0 border-bottom">
                            <div class="d-flex justify-content-between align-items-start mb-2">
                                <span class="badge bg-light text-muted fw-bold">#{{ substr($m->referral_number, -4) }}</span>
                                <small class="text-muted italic">{{ $m->created_at->diffForHumans() }}</small>
                            </div>
                            <h6 class="fw-bold mb-1 text-dark">{{ $m->patient->name }}</h6>
                            <p class="text-muted small mb-0"><i class="bi bi-hospital me-1"></i>Ke: {{ $m->toFaskes->name }}</p>
                            <span class="badge bg-success bg-opacity-10 text-success rounded-pill mt-2">SUKSES</span>
                        </div>
                        @empty
                        <div class="text-center py-5">
                            <i class="bi bi-clock-history fs-2 text-muted opacity-25 d-block mb-2"></i>
                            <p class="text-muted small">Belum ada riwayat misi.</p>
                        </div>
                        @endforelse
                    </div>
                </div>
                <div class="card-footer bg-white text-center py-3 border-outer-0">
                    <a href="{{ route('referrals.index') }}" class="text-primary fw-bold text-decoration-none small">LIHAT SEMUA RIWAYAT <i class="bi bi-arrow-right"></i></a>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Coming Soon Modal -->
<div class="modal fade" id="comingSoonModal" tabindex="-1" aria-labelledby="comingSoonModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg rounded-4 text-center p-4">
            <div class="modal-body">
                <i class="bi bi-tools text-primary d-block mb-3" style="font-size: 3rem;"></i>
                <h5 class="fw-bold mb-2">Segera Hadir</h5>
                <p class="text-muted small mb-4">Fitur ini sedang dalam tahap pengembangan dan akan segera tersedia pada pembaruan berikutnya.</p>
                <button type="button" class="btn btn-primary rounded-pill px-4" data-bs-dismiss="modal">Mengerti</button>
            </div>
        </div>
    </div>
</div>

<style>
    .ls-1 { letter-spacing: 1px; }
    .hover-up { transition: all 0.3s; }
    .hover-up:hover { transform: translateY(-5px); }
    
    /* Radar Animation */
    .radar-pulse { position: relative; width: 80px; height: 80px; margin: 0 auto; display: flex; align-items: center; justify-content: center; }
    .pulse-ring { border: 3px solid #3b82f6; position: absolute; height: 100%; width: 100%; border-radius: 50%; opacity: 0; animation: pulse 2s infinite cubic-bezier(0.21, 0.53, 0.56, 0.8); }
    @keyframes pulse { 0% { transform: scale(0.1); opacity: 0; } 50% { opacity: 0.5; } 100% { transform: scale(1.2); opacity: 0; } }

    /* Route Active Pulse */
    .active-pulse { box-shadow: 0 0 0 0 rgba(59, 130, 246, 0.7); animation: active-pulse-anim 2s infinite; }
    @keyframes active-pulse-anim {
        0% { transform: scale(0.95); box-shadow: 0 0 0 0 rgba(59, 130, 246, 0.7); }
        70% { transform: scale(1.1); box-shadow: 0 0 0 10px rgba(59, 130, 246, 0); }
        100% { transform: scale(0.95); box-shadow: 0 0 0 0 rgba(59, 130, 246, 0); }
    }
</style>
@endsection
