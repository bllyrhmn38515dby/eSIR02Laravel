@extends('layouts.app')

@section('content')
<div class="container py-4">
    <!-- Header -->
    <div class="row mb-5 align-items-center animate__animated animate__fadeIn">
        <div class="col">
            <h2 class="fw-bold mb-1"><i class="bi bi-people me-2 text-primary"></i>Manajemen Pasien</h2>
            <p class="text-muted mb-0">Kelola basis data pasien dan pantau riwayat perjalanan klinis mereka.</p>
        </div>
        <div class="col-auto">
            <a href="{{ route('patients.create') }}" class="btn btn-primary rounded-pill px-4 shadow-sm py-2 fw-bold">
                <i class="bi bi-person-plus me-1"></i> Tambah Pasien
            </a>
        </div>
    </div>

    <!-- Stats Summary -->
    <div class="row g-4 mb-5">
        <div class="col-md-3">
            <div class="card border-0 shadow-sm rounded-4 bg-primary text-white p-4 h-100 position-relative overflow-hidden">
                <div class="position-absolute end-0 bottom-0 opacity-10 me-n3 mb-n3">
                    <i class="bi bi-people-fill" style="font-size: 6rem;"></i>
                </div>
                <div class="small opacity-75 mb-1 fw-medium text-uppercase ls-1">Total Pasien</div>
                <div class="fs-1 fw-bold lh-1">{{ $total_patients }}</div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm rounded-4 bg-info text-white p-4 h-100 position-relative overflow-hidden" style="background: linear-gradient(135deg, #4cc9f0, #4361ee) !important;">
                <div class="position-absolute end-0 bottom-0 opacity-10 me-n3 mb-n3">
                    <i class="bi bi-gender-male" style="font-size: 6rem;"></i>
                </div>
                <div class="small opacity-75 mb-1 fw-medium text-uppercase ls-1">Laki-Laki</div>
                <div class="fs-1 fw-bold lh-1">{{ $total_male }}</div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm rounded-4 text-white p-4 h-100 position-relative overflow-hidden" style="background: linear-gradient(135deg, #f72585, #7209b7) !important;">
                <div class="position-absolute end-0 bottom-0 opacity-10 me-n3 mb-n3">
                    <i class="bi bi-gender-female" style="font-size: 6rem;"></i>
                </div>
                <div class="small opacity-75 mb-1 fw-medium text-uppercase ls-1">Perempuan</div>
                <div class="fs-1 fw-bold lh-1">{{ $total_female }}</div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm rounded-4 bg-success text-white p-4 h-100 position-relative overflow-hidden">
                <div class="position-absolute end-0 bottom-0 opacity-10 me-n3 mb-n3">
                    <i class="bi bi-calendar-check" style="font-size: 6rem;"></i>
                </div>
                <div class="small opacity-75 mb-1 fw-medium text-uppercase ls-1">Terdaftar Hari Ini</div>
                <div class="fs-1 fw-bold lh-1">{{ $registered_today }}</div>
            </div>
        </div>
    </div>

    <!-- Alert Success -->
    @if(session('success'))
        <div class="alert alert-success border-0 shadow-sm rounded-3 mb-4 d-flex align-items-center p-3 animate__animated animate__fadeInDown">
            <i class="bi bi-check-circle-fill me-2 fs-5"></i>
            {{ session('success') }}
            <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <!-- Data Table -->
    <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="bg-light bg-opacity-50 border-bottom">
                    <tr>
                        <th class="ps-4 py-3 text-uppercase small fw-bold text-muted">ID & NIK</th>
                        <th class="py-3 text-uppercase small fw-bold text-muted">Nama Pasien</th>
                        <th class="py-3 text-uppercase small fw-bold text-muted">Tgl Lahir / Usia</th>
                        <th class="py-3 text-uppercase small fw-bold text-muted text-center">Gender</th>
                        <th class="py-3 text-uppercase small fw-bold text-muted">Kontak</th>
                        <th class="pe-4 py-3 text-uppercase small fw-bold text-muted text-end">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($patients as $patient)
                    <tr>
                        <td class="ps-4">
                            <span class="text-muted small fw-bold">#{{ $patient->id }}</span>
                            <div class="fw-medium text-dark" style="font-size: 0.85rem;">{{ $patient->nik }}</div>
                        </td>
                        <td>
                            <span class="fw-bold d-block text-dark">{{ $patient->name }}</span>
                            <small class="text-muted"><i class="bi bi-clock-history small me-1"></i>Terdaftar: {{ $patient->created_at->format('d/m/Y') }}</small>
                        </td>
                        <td>
                            <div class="fw-medium text-dark">{{ $patient->dob ? \Carbon\Carbon::parse($patient->dob)->format('d M Y') : '-' }}</div>
                            @if($patient->dob)
                                <small class="text-muted">{{ \Carbon\Carbon::parse($patient->dob)->age }} Tahun</small>
                            @endif
                        </td>
                        <td class="text-center">
                            @if($patient->gender == 'L')
                                <span class="badge bg-info-subtle text-info rounded-pill px-3 py-2 fw-medium">
                                    <i class="bi bi-gender-male me-1"></i> LAKI-LAKI
                                </span>
                            @elseif($patient->gender == 'P')
                                <span class="badge rounded-pill px-3 py-2 fw-medium text-white" style="background-color: #f72585;">
                                    <i class="bi bi-gender-female me-1"></i> PEREMPUAN
                                </span>
                            @else
                                <span class="badge bg-secondary-subtle text-secondary rounded-pill px-3 py-2 fw-medium">-</span>
                            @endif
                        </td>
                        <td>
                            <div class="small text-dark fw-medium">{{ $patient->contact ?: '-' }}</div>
                        </td>
                        <td class="pe-4 text-end">
                            <div class="btn-group shadow-sm rounded-3">
                                <a href="{{ route('patients.show', $patient) }}" class="btn btn-sm btn-white text-info py-2 px-3 border" title="Timeline Rekam Medis">
                                    <i class="bi bi-journal-medical"></i>
                                </a>
                                <a href="{{ route('patients.edit', $patient) }}" class="btn btn-sm btn-white text-primary py-2 px-3 border-start-0 border" title="Edit">
                                    <i class="bi bi-pencil-square"></i>
                                </a>
                                <form action="{{ route('patients.destroy', $patient) }}" method="POST" class="d-inline" onsubmit="return confirm('Hapus data pasien ini?');">
                                    @csrf @method('DELETE')
                                    <button class="btn btn-sm btn-white text-danger py-2 px-3 border-start-0 border" title="Hapus">
                                        <i class="bi bi-trash3-fill"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center py-5 border-0">
                            <i class="bi bi-person-x fs-1 text-muted d-block mb-3 opacity-25"></i>
                            <h5 class="text-muted fw-normal">Belum ada pasien terdaftar.</h5>
                            <a href="{{ route('patients.create') }}" class="btn btn-link">Tambah sekarang</a>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($patients->hasPages())
        <div class="card-footer bg-white border-top-0 p-4">
            {{ $patients->links() }}
        </div>
        @endif
    </div>
</div>
@endsection
