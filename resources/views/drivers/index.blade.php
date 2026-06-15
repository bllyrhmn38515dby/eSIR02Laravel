@extends('layouts.app')

@section('content')
<div class="container-fluid py-4 px-lg-5">
    <!-- Header Section -->
    <div class="row mb-5 animate__animated animate__fadeIn">
        <div class="col-12 d-flex justify-content-between align-items-center">
            <div>
                <h2 class="fw-bold mb-1 text-dark"><i class="bi bi-person-badge me-2 text-primary"></i>Manajemen Sopir (Driver)</h2>
                <p class="text-muted mb-0">Kelola ketersediaan personel dan monitor performa misi sopir ambulans.</p>
            </div>
            <div>
                <a href="{{ route('drivers.create') }}" class="btn btn-primary rounded-pill px-4 py-2 shadow-sm fw-bold">
                    <i class="bi bi-person-plus-fill me-1"></i> Daftarkan Sopir Baru
                </a>
            </div>
        </div>
    </div>

    <!-- Stats Section -->
    <div class="row g-4 mb-5 animate__animated animate__fadeInUp">
        <div class="col-md-3">
            <div class="card border-0 shadow-sm rounded-4 h-100 bg-white overflow-hidden">
                <div class="card-body p-4 position-relative">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <div class="bg-primary bg-opacity-10 p-3 rounded-4">
                            <i class="bi bi-people-fill fs-4 text-primary"></i>
                        </div>
                        <span class="badge bg-light text-dark border fw-medium small">Total Sopir</span>
                    </div>
                    <h3 class="fw-bold mb-1">{{ $stats['total'] }}</h3>
                    <p class="text-muted small mb-0">Personel di faskes terpilih</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm rounded-4 h-100 bg-white overflow-hidden border-start border-4 border-success">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <div class="bg-success bg-opacity-10 p-3 rounded-4">
                            <i class="bi bi-check-lg fs-4 text-success"></i>
                        </div>
                        <span class="badge bg-success bg-opacity-10 text-success fw-bold">Available</span>
                    </div>
                    <h3 class="fw-bold mb-1 text-success">{{ $stats['available'] }}</h3>
                    <p class="text-muted small mb-0">Sopir siap mendapatkan misi</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm rounded-4 h-100 bg-white overflow-hidden border-start border-4 border-warning">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <div class="bg-warning bg-opacity-10 p-3 rounded-4">
                            <i class="bi bi-steering fs-4 text-warning"></i>
                        </div>
                        <span class="badge bg-warning bg-opacity-10 text-warning fw-bold">On Mission</span>
                    </div>
                    <h3 class="fw-bold mb-1 text-warning">{{ $stats['on_mission'] }}</h3>
                    <p class="text-muted small mb-0">Sedang menjalankan rujukan aktif</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm rounded-4 h-100 bg-white overflow-hidden">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <div class="bg-info bg-opacity-10 p-3 rounded-4">
                            <i class="bi bi-building fs-4 text-info"></i>
                        </div>
                        <span class="badge bg-info bg-opacity-10 text-info fw-bold">Faskes</span>
                    </div>
                    <h3 class="fw-bold mb-1 text-info">{{ $stats['assigned_faskes'] }}</h3>
                    <p class="text-muted small mb-0">Lokasi penugasan aktif</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Drivers Table Section -->
    <div class="card border-0 shadow-lg rounded-4 overflow-hidden animate__animated animate__fadeInUp animate__delay-1s">
        <div class="card-header bg-white p-4 border-0 border-bottom">
            <div class="row align-items-center">
                <div class="col">
                    <h5 class="fw-bold mb-0 text-uppercase ls-1"><i class="bi bi-list-stars me-2 text-primary"></i>Personnel Master List</h5>
                </div>
                <div class="col-auto">
                    <div class="input-group">
                        <span class="input-group-text bg-light border-0"><i class="bi bi-search"></i></span>
                        <input type="text" class="form-control bg-light border-0 small" placeholder="Cari nama sopir...">
                    </div>
                </div>
            </div>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="bg-light bg-opacity-50">
                        <tr>
                            <th class="ps-4 py-3 text-uppercase small fw-bold text-muted ls-1">Profil Sopir</th>
                            <th class="py-3 text-uppercase small fw-bold text-muted ls-1">Penugasan Faskes</th>
                            <th class="py-3 text-uppercase small fw-bold text-muted ls-1">Misi Selesai</th>
                            <th class="py-3 text-uppercase small fw-bold text-muted ls-1 text-center">Status Bertugas</th>
                            <th class="py-3 text-uppercase small fw-bold text-muted ls-1 text-end pe-4">Manajemen</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($drivers as $driver)
                        <tr>
                            <td class="ps-4 py-4">
                                <div class="d-flex align-items-center">
                                    <div class="avatar-sm rounded-circle p-1 me-3 position-relative" style="width: 45px; height: 45px; background: linear-gradient(135deg, #3b82f6 0%, #1e3a8a 100%);">
                                        <div class="w-100 h-100 rounded-circle bg-white d-flex align-items-center justify-content-center fw-bold text-primary">
                                            {{ strtoupper(substr($driver->name, 0, 2)) }}
                                        </div>
                                        @if($driver->is_active)
                                            <span class="position-absolute bottom-0 end-0 p-1 bg-success border border-white border-2 rounded-circle"></span>
                                        @else
                                            <span class="position-absolute bottom-0 end-0 p-1 bg-danger border border-white border-2 rounded-circle"></span>
                                        @endif
                                    </div>
                                    <div>
                                        <div class="fw-bold text-dark mb-0">{{ $driver->name }}</div>
                                        <div class="text-muted small ls-05">{{ $driver->email }}</div>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <div class="fw-medium text-dark"><i class="bi bi-geo-alt me-1 opacity-50"></i>{{ $driver->faskes->name ?? '-' }}</div>
                            </td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="fw-bold text-primary me-2 fs-5">{{ $driver->referrals_count ?? 0 }}</div>
                                    <div class="text-muted small">Total Misi</div>
                                </div>
                            </td>
                            <td class="text-center">
                                @php
                                    $hasActiveTrip = $driver->referrals()->whereIn('status', ['on_trip', 'process_boarding', 'arrived_at_destination'])->exists();
                                @endphp
                                @if($hasActiveTrip)
                                    <span class="badge bg-warning bg-opacity-10 text-warning rounded-pill px-3 py-2 fw-bold" style="font-size: 0.7rem;">
                                        <i class="bi bi-truck me-1"></i> ON MISSION
                                    </span>
                                @else
                                    <span class="badge bg-success bg-opacity-10 text-success rounded-pill px-3 py-2 fw-bold" style="font-size: 0.7rem;">
                                        <i class="bi bi-house-door me-1"></i> AVAILABLE
                                    </span>
                                @endif
                            </td>
                            <td class="text-end pe-4">
                                <div class="btn-group shadow-sm rounded-pill overflow-hidden border">
                                    <a href="{{ route('drivers.edit', $driver) }}" class="btn btn-white btn-sm px-3 border-end">
                                        <i class="bi bi-pencil-square text-primary"></i>
                                    </a>
                                    <form action="{{ route('drivers.destroy', $driver) }}" method="POST" class="d-inline" onsubmit="return confirm('Hapus sopir ini? Akun mereka tidak akan bisa login kembali.');">
                                        @csrf @method('DELETE')
                                        <button class="btn btn-white btn-sm px-3">
                                            <i class="bi bi-trash3 text-danger"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="text-center py-5">
                                <div class="opacity-25 pb-3">
                                    <i class="bi bi-person-slash" style="font-size: 4rem;"></i>
                                </div>
                                <p class="text-muted fw-medium">Belum ada sopir terdaftar dalam database departemen ini.</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        <div class="card-footer bg-white p-4 border-top">
            {{ $drivers->links() }}
        </div>
    </div>
</div>

<style>
    .ls-1 { letter-spacing: 1px; }
    .ls-05 { letter-spacing: 0.3px; }
    .btn-white { background: #fff; border: none; transition: all 0.2s; }
    .btn-white:hover { background: #f8fafc; }
    .avatar-sm { display: flex; align-items: center; justify-content: center; z-index: 1; }
    .table-hover tbody tr:hover { background-color: #f8faff; cursor: pointer; }
    .badge { border: 1px solid currentColor; }
</style>
@endsection
