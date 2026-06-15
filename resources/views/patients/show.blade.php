@extends('layouts.app')

@section('content')
<div class="container py-4">
    <!-- Breadcrumb & Actions -->
    <div class="row mb-4 align-items-center animate__animated animate__fadeIn">
        <div class="col">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-1">
                    <li class="breadcrumb-item"><a href="{{ route('patients.index') }}" class="text-decoration-none text-primary fw-medium">Manajemen Pasien</a></li>
                    <li class="breadcrumb-item active fw-bold" aria-current="page">Buku Rekam Medis</li>
                </ol>
            </nav>
            <h2 class="fw-bold text-dark mb-0">Patient Clinical Journey</h2>
        </div>
        <div class="col-auto">
            <a href="{{ route('patients.index') }}" class="btn btn-outline-secondary rounded-pill px-4 shadow-sm border-2 fw-bold">
                <i class="bi bi-arrow-left me-1"></i> Kembali
            </a>
        </div>
    </div>

    <div class="row g-4">
        <!-- Patient Profile Card -->
        <div class="col-lg-4">
            <div class="card border-0 shadow-lg rounded-4 overflow-hidden sticky-top" style="top: 2rem;">
                <div class="bg-primary p-5 text-center text-white position-relative" style="background: linear-gradient(135deg, #4361ee 0%, #3a0ca3 100%);">
                    <div class="position-absolute top-0 end-0 p-3 opacity-10">
                        <i class="bi bi-person-circle" style="font-size: 8rem;"></i>
                    </div>
                    <div class="mb-4 position-relative">
                        <div class="bg-white p-1 rounded-circle d-inline-block shadow-lg">
                            <div class="bg-light rounded-circle d-flex align-items-center justify-content-center" style="width: 100px; height: 100px;">
                                <i class="bi bi-person text-primary fs-1"></i>
                            </div>
                        </div>
                    </div>
                    <h3 class="fw-bold mb-1">{{ $patient->name }}</h3>
                    <div class="badge bg-white bg-opacity-25 rounded-pill px-3 py-2 fw-medium text-white mb-0" style="backdrop-filter: blur(5px);">
                        NIK: {{ $patient->nik }}
                    </div>
                </div>
                <div class="card-body p-4 p-md-5">
                    <div class="mb-4">
                        <label class="text-uppercase small fw-bold text-muted ls-1 mb-2 d-block">Informasi Demografis</label>
                        <div class="d-flex align-items-center mb-3">
                            <div class="rounded-3 bg-light p-2 me-3"><i class="bi bi-calendar3 text-primary"></i></div>
                            <div>
                                <small class="text-muted d-block">Tanggal Lahir</small>
                                <span class="fw-bold text-dark">{{ \Carbon\Carbon::parse($patient->dob)->format('d F Y') }}</span>
                                <small class="text-muted d-block mt-n1">{{ \Carbon\Carbon::parse($patient->dob)->age }} Tahun</small>
                            </div>
                        </div>
                        <div class="d-flex align-items-center mb-3">
                            <div class="rounded-3 bg-light p-2 me-3"><i class="bi bi-gender-ambiguous text-primary"></i></div>
                            <div>
                                <small class="text-muted d-block">Jenis Kelamin</small>
                                <span class="fw-bold text-dark">{{ $patient->gender == 'L' ? 'Laki-Laki' : 'Perempuan' }}</span>
                            </div>
                        </div>
                        <div class="d-flex align-items-center mb-3">
                            <div class="rounded-3 bg-light p-2 me-3"><i class="bi bi-telephone text-primary"></i></div>
                            <div>
                                <small class="text-muted d-block">Nomor Kontak</small>
                                <span class="fw-bold text-dark">{{ $patient->contact ?: '-' }}</span>
                            </div>
                        </div>
                    </div>

                    <div class="mb-0">
                        <label class="text-uppercase small fw-bold text-muted ls-1 mb-2 d-block">Alamat Domisili</label>
                        <div class="p-3 bg-light rounded-3">
                            <p class="mb-0 text-dark fw-medium" style="font-size: 0.9rem; line-height: 1.6;">
                                <i class="bi bi-geo-alt me-1 text-primary"></i>
                                {{ $patient->address ?: 'Alamat belum dilengkapi.' }}
                            </p>
                        </div>
                    </div>
                </div>
                <div class="card-footer bg-light border-0 p-4 text-center">
                    <small class="text-muted">Terdaftar sejak: <strong>{{ $patient->created_at->format('d/m/Y') }}</strong></small>
                </div>
            </div>
        </div>

        <!-- Timeline Column -->
        <div class="col-lg-8">
            <div class="card border-0 shadow-lg rounded-4 overflow-hidden h-100">
                <div class="card-header bg-white border-bottom-0 p-4 p-md-5">
                    <h4 class="fw-bold mb-1 text-dark">Data Rekam Medis & Riwayat Rujukan</h4>
                    <p class="text-muted mb-0 small">Berikut adalah seluruh interaksi klinis pasien yang tercatat dalam sistem eSIR.</p>
                </div>
                <div class="card-body p-4 p-md-5 pt-0">
                    @if($patient->referrals->count() > 0)
                    <div class="timeline ps-4 border-start border-2 border-light ms-2">
                        @foreach($patient->referrals as $ref)
                        @php $triage = $ref->getTriageInfo(); @endphp
                        <div class="timeline-item position-relative mb-5 animate__animated animate__fadeInUp">
                            <!-- Bullet -->
                            <div class="position-absolute start-0 top-0 translate-middle rounded-circle border border-4 border-white shadow-sm" style="width: 24px; height: 24px; background-color: {{ $triage['class'] == 'bg-danger' ? '#f72585' : ($triage['class'] == 'bg-warning' ? '#ffbe0b' : '#4cc9f0') }}; margin-left: -2px;"></div>
                            
                            <div class="ps-4">
                                <div class="card border-0 shadow-sm rounded-4 overflow-hidden mb-3">
                                    <div class="card-header bg-light border-0 py-3 px-4 d-flex justify-content-between align-items-center">
                                        <div>
                                            <span class="badge {{ $triage['class'] }} text-uppercase rounded-pill px-3 py-2 fw-bold" style="font-size: 0.7rem;">
                                                <i class="bi bi-activity me-1"></i> {{ $triage['label'] }}
                                            </span>
                                            <span class="ms-2 text-muted fw-bold small text-uppercase">#{{ $ref->referral_number }}</span>
                                        </div>
                                        <div class="text-muted small fw-bold">
                                            <i class="bi bi-calendar-event me-1"></i> {{ $ref->created_at->format('d M Y') }} 
                                            <span class="mx-1">•</span> 
                                            <i class="bi bi-clock me-1"></i> {{ $ref->created_at->format('H:i') }}
                                        </div>
                                    </div>
                                    <div class="card-body p-4">
                                        <div class="row g-4 align-items-center mb-4">
                                            <div class="col-md-5">
                                                <div class="p-3 bg-secondary-subtle rounded-3 text-center">
                                                    <small class="text-muted d-block text-uppercase fw-bold ls-1 mb-1" style="font-size: 0.65rem;">Faskes Perujuk</small>
                                                    <div class="fw-bold text-dark">{{ $ref->fromFaskes ? $ref->fromFaskes->name : '-' }}</div>
                                                </div>
                                            </div>
                                            <div class="col-md-2 text-center text-muted">
                                                <i class="bi bi-arrow-right fs-4"></i>
                                            </div>
                                            <div class="col-md-5">
                                                <div class="p-3 bg-primary-subtle rounded-3 text-center border border-primary border-opacity-10 shadow-sm">
                                                    <small class="text-primary d-block text-uppercase fw-bold ls-1 mb-1" style="font-size: 0.65rem;">Rumah Sakit Tujuan</small>
                                                    <div class="fw-bold text-primary">{{ $ref->toFaskes ? $ref->toFaskes->name : '-' }}</div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="mb-4 d-flex flex-wrap gap-2">
                                            <span class="badge bg-white text-dark border p-2 fw-medium shadow-xs">GCS: <strong>{{ $ref->gcs_score ?: '-' }}</strong></span>
                                            <span class="badge bg-white text-dark border p-2 fw-medium shadow-xs">Tensimeter: <strong>{{ $ref->blood_pressure ?: '-' }}</strong></span>
                                            <span class="badge bg-white text-dark border p-2 fw-medium shadow-xs">Temp: <strong>{{ $ref->temperature ?: '-' }}°C</strong></span>
                                        </div>

                                        <div class="p-3 rounded-4 bg-light border-start border-4 border-info mb-4">
                                            <label class="text-uppercase small fw-bold text-muted ls-1 mb-1 d-block">Diagnosis Klinis</label>
                                            <p class="mb-0 fw-bold text-dark fs-6">{{ $ref->diagnosis }}</p>
                                        </div>

                                        <div class="d-flex justify-content-between align-items-center">
                                            <div>
                                                @if($ref->status == 'completed')
                                                    <span class="badge bg-success bg-opacity-10 text-success border border-success border-opacity-25 px-3 py-2 rounded-pill"><i class="bi bi-check-circle-fill me-1"></i>Selesai</span>
                                                @elseif($ref->status == 'rejected')
                                                    <span class="badge bg-danger bg-opacity-10 text-danger border border-danger border-opacity-25 px-3 py-2 rounded-pill"><i class="bi bi-x-circle-fill me-1"></i>Ditolak</span>
                                                @else
                                                    <span class="badge bg-primary bg-opacity-10 text-primary border border-primary border-opacity-25 px-3 py-2 rounded-pill"><i class="bi bi-hourglass-split me-1"></i>{{ ucfirst($ref->status) }}</span>
                                                @endif
                                            </div>
                                            <a href="{{ route('referrals.edit', $ref->id) }}" class="btn btn-primary rounded-pill px-4 shadow-sm fw-bold btn-sm">
                                                Detail Rujukan <i class="bi bi-chevron-right small ms-1"></i>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                    @else
                    <div class="text-center py-5">
                        <div class="mb-4 opacity-10">
                            <i class="bi bi-journal-x" style="font-size: 10rem;"></i>
                        </div>
                        <h5 class="text-muted fw-normal">Belum ada riwayat rujukan yang tercatat.</h5>
                        <p class="text-muted small">Semua aktivitas rujukan pasien lintas faskes akan muncul secara otomatis di sini.</p>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.timeline-item:last-child {
    margin-bottom: 0 !important;
}
.shadow-xs {
    box-shadow: 0 1px 2px rgba(0,0,0,.05);
}
</style>
@endsection
