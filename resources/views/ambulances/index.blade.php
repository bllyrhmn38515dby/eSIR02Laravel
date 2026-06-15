@extends('layouts.app')

@section('content')
<div class="container-fluid py-4 px-lg-5">
    <!-- Header Section -->
    <div class="row mb-5 animate__animated animate__fadeIn">
        <div class="col-12 d-flex justify-content-between align-items-center">
            <div>
                <h2 class="fw-bold mb-1 text-dark"><i class="bi bi-truck-flatbed me-2 text-primary"></i>Management Armada Ambulans</h2>
                <p class="text-muted mb-0">Monitor ketersediaan, status operasional, dan pemeliharaan unit ambulans.</p>
            </div>
            <div>
                <a href="{{ route('ambulances.create') }}" class="btn btn-primary rounded-pill px-4 py-2 shadow-sm fw-bold">
                    <i class="bi bi-plus-circle me-1"></i> Daftarkan Unit Baru
                </a>
            </div>
        </div>
    </div>

    <!-- Stats Cards Section -->
    <div class="row g-4 mb-5 animate__animated animate__fadeInUp">
        <div class="col-md-3">
            <div class="card border-0 shadow-sm rounded-4 h-100 bg-white">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <div class="bg-primary bg-opacity-10 p-3 rounded-4">
                            <i class="bi bi-hospital fs-4 text-primary"></i>
                        </div>
                        <span class="badge bg-light text-dark border fw-medium">Unit Terdaftar</span>
                    </div>
                    <h3 class="fw-bold mb-1">{{ $stats['total'] }}</h3>
                    <p class="text-muted small mb-0">Total armada di faskes Anda</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm rounded-4 h-100 bg-white">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <div class="bg-success bg-opacity-10 p-3 rounded-4">
                            <i class="bi bi-check-circle-fill fs-4 text-success"></i>
                        </div>
                        <span class="badge bg-success bg-opacity-10 text-success fw-bold">Ready</span>
                    </div>
                    <h3 class="fw-bold mb-1 text-success">{{ $stats['standby'] }}</h3>
                    <p class="text-muted small mb-0">Unit siap siaga di pangkalan</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm rounded-4 h-100 bg-white">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <div class="bg-warning bg-opacity-10 p-3 rounded-4">
                            <i class="bi bi-truck fs-4 text-warning"></i>
                        </div>
                        <span class="badge bg-warning bg-opacity-10 text-warning fw-bold">On Mission</span>
                    </div>
                    <h3 class="fw-bold mb-1 text-warning">{{ $stats['on_trip'] }}</h3>
                    <p class="text-muted small mb-0">Sedang dalam perjalanan rujukan</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm rounded-4 h-100 bg-white">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <div class="bg-danger bg-opacity-10 p-3 rounded-4">
                            <i class="bi bi-tools fs-4 text-danger"></i>
                        </div>
                        <span class="badge bg-danger bg-opacity-10 text-danger fw-bold">Repair</span>
                    </div>
                    <h3 class="fw-bold mb-1 text-danger">{{ $stats['maintenance'] }}</h3>
                    <p class="text-muted small mb-0">Sedang dalam pemeliharaan rutin</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Fleet Table Section -->
    <div class="card border-0 shadow-lg rounded-4 overflow-hidden animate__animated animate__fadeInUp animate__delay-1s">
        <div class="card-header bg-white p-4 border-0">
            <div class="d-flex justify-content-between align-items-center">
                <h5 class="fw-bold mb-0 text-uppercase ls-1"><i class="bi bi-list-task me-2"></i>Unit Fleet List</h5>
                <div class="input-group w-auto">
                    <span class="input-group-text bg-light border-0"><i class="bi bi-search"></i></span>
                    <input type="text" class="form-control bg-light border-0 small" placeholder="Cari plat nomor...">
                </div>
            </div>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="bg-light">
                        <tr>
                            <th class="ps-4 py-3 text-uppercase small fw-bold text-muted ls-1">Faskes / Lokasi</th>
                            <th class="py-3 text-uppercase small fw-bold text-muted ls-1">Nomor Polisi</th>
                            <th class="py-3 text-uppercase small fw-bold text-muted ls-1">Tipe Kendaraan</th>
                            <th class="py-3 text-uppercase small fw-bold text-muted ls-1">Status Operasional</th>
                            <th class="py-3 text-uppercase small fw-bold text-muted ls-1 text-end pe-4">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($ambulances as $amb)
                        <tr class="animate__animated animate__fadeIn">
                            <td class="ps-4 py-4">
                                <div class="d-flex align-items-center">
                                    <div class="avatar-sm bg-light rounded-circle p-2 me-3 text-center" style="width: 40px; height: 40px;">
                                        <i class="bi bi-building text-primary"></i>
                                    </div>
                                    <span class="fw-medium text-dark">{{ $amb->faskes->name }}</span>
                                </div>
                            </td>
                            <td>
                                <span class="badge bg-dark text-white p-2 rounded-3 fw-bold ls-1 shadow-sm">{{ $amb->police_number }}</span>
                            </td>
                            <td>
                                <div class="text-dark fw-medium"><i class="bi bi-truck me-2 opacity-50"></i>{{ $amb->vehicle_type }}</div>
                            </td>
                            <td>
                                @php
                                    $statusConfig = [
                                        'standby' => ['color' => 'success', 'label' => 'STANDBY', 'icon' => 'bi-check2-circle'],
                                        'on_trip' => ['color' => 'warning', 'label' => 'ON MISSION', 'icon' => 'bi-truck'],
                                        'maintenance' => ['color' => 'danger', 'label' => 'MAINTENANCE', 'icon' => 'bi-tools'],
                                    ];
                                    $conf = $statusConfig[$amb->status] ?? ['color' => 'secondary', 'label' => 'UNKNOWN', 'icon' => 'bi-question-circle'];
                                @endphp
                                <span class="badge bg-{{ $conf['color'] }} bg-opacity-10 text-{{ $conf['color'] }} rounded-pill px-3 py-2 fw-bold" style="font-size: 0.75rem;">
                                    <i class="bi {{ $conf['icon'] }} me-1"></i> {{ $conf['label'] }}
                                </span>
                            </td>
                            <td class="text-end pe-4">
                                <div class="btn-group shadow-sm rounded-pill overflow-hidden">
                                    <a href="{{ route('ambulances.edit', $amb) }}" class="btn btn-white btn-sm px-3 hover-primary">
                                        <i class="bi bi-pencil-square"></i>
                                    </a>
                                    <form action="{{ route('ambulances.destroy', $amb) }}" method="POST" class="d-inline" onsubmit="return confirm('Hapus armada dari sistem?');">
                                        @csrf @method('DELETE')
                                        <button class="btn btn-white btn-sm px-3 hover-danger">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="text-center py-5">
                                <i class="bi bi-inbox fs-1 text-muted d-block mb-3 opacity-25"></i>
                                <p class="text-muted">Belum ada armada terdaftar dalam unit fleet ini.</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        <div class="card-footer bg-white p-4 border-top">
            {{ $ambulances->links() }}
        </div>
    </div>
</div>

<style>
    .ls-1 { letter-spacing: 0.5px; }
    .avatar-sm { display: flex; align-items: center; justify-content: center; }
    .btn-white { background: #fff; border-right: 1px solid #f1f5f9; color: #64748b; transition: all 0.2s; }
    .btn-white:last-child { border-right: none; }
    .hover-primary:hover { color: #3b82f6; background: #eff6ff; }
    .hover-danger:hover { color: #ef4444; background: #fef2f2; }
    .table-hover tbody tr:hover { background-color: #f8fafc; cursor: pointer; }
</style>
@endsection
