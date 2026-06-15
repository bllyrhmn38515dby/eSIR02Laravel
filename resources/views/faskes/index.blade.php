@extends('layouts.app')

@section('content')
<div class="container py-4">
    <!-- Header -->
    <div class="row mb-5 align-items-center animate__animated animate__fadeIn">
        <div class="col">
            <h2 class="fw-bold mb-1"><i class="bi bi-building-gear me-2 text-primary"></i>Manajemen Fasilitas Kesehatan</h2>
            <p class="text-muted mb-0">Kelola daftar rumah sakit, puskesmas, dan klinik dalam sistem rujukan.</p>
        </div>
        <div class="col-auto">
            <a href="{{ route('faskes.create') }}" class="btn btn-primary rounded-pill px-4 shadow-sm py-2 fw-bold">
                <i class="bi bi-plus-lg me-1"></i> Tambah Faskes
            </a>
        </div>
    </div>

    <!-- Stats Summary -->
    <div class="row g-4 mb-5">
        <div class="col-md-3">
            <div class="card border-0 shadow-sm rounded-4 bg-primary text-white p-4 h-100 position-relative overflow-hidden">
                <div class="position-absolute end-0 bottom-0 opacity-10 me-n3 mb-n3">
                    <i class="bi bi-buildings" style="font-size: 6rem;"></i>
                </div>
                <div class="small opacity-75 mb-1 fw-medium text-uppercase ls-1">Total Faskes</div>
                <div class="fs-1 fw-bold lh-1">{{ $total_faskes }}</div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm rounded-4 bg-success text-white p-4 h-100 position-relative overflow-hidden">
                <div class="position-absolute end-0 bottom-0 opacity-10 me-n3 mb-n3">
                    <i class="bi bi-check-circle" style="font-size: 6rem;"></i>
                </div>
                <div class="small opacity-75 mb-1 fw-medium text-uppercase ls-1">Faskes Aktif</div>
                <div class="fs-1 fw-bold lh-1">{{ $total_active }}</div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm rounded-4 bg-info text-white p-4 h-100 position-relative overflow-hidden" style="background: linear-gradient(135deg, #4cc9f0, #4361ee) !important;">
                <div class="position-absolute end-0 bottom-0 opacity-10 me-n3 mb-n3">
                    <i class="bi bi-hospital" style="font-size: 6rem;"></i>
                </div>
                <div class="small opacity-75 mb-1 fw-medium text-uppercase ls-1">Total RS (A-D)</div>
                <div class="fs-1 fw-bold lh-1">{{ $total_rs }}</div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm rounded-4 bg-warning text-white p-4 h-100 position-relative overflow-hidden">
                <div class="position-absolute end-0 bottom-0 opacity-10 me-n3 mb-n3">
                    <i class="bi bi-house-heart" style="font-size: 6rem;"></i>
                </div>
                <div class="small opacity-75 mb-1 fw-medium text-uppercase ls-1">Puskesmas</div>
                <div class="fs-1 fw-bold lh-1">{{ $total_puskesmas }}</div>
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
                        <th class="ps-4 py-3 text-uppercase small fw-bold text-muted">ID</th>
                        <th class="py-3 text-uppercase small fw-bold text-muted">Fasilitas Kesehatan</th>
                        <th class="py-3 text-uppercase small fw-bold text-muted">Tipe</th>
                        <th class="py-3 text-uppercase small fw-bold text-muted">Alamat & Lokasi</th>
                        <th class="py-3 text-uppercase small fw-bold text-muted text-center">Status</th>
                        <th class="pe-4 py-3 text-uppercase small fw-bold text-muted text-end">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($faskes as $f)
                    <tr>
                        <td class="ps-4">
                            <span class="text-muted small fw-bold">#{{ $f->id }}</span>
                        </td>
                        <td>
                            <span class="fw-bold d-block text-dark">{{ $f->name }}</span>
                            <small class="text-muted"><i class="bi bi-geo-alt small me-1"></i>{{ $f->latitude ?? '0' }}, {{ $f->longitude ?? '0' }}</small>
                        </td>
                        <td>
                            @php
                                $typeBadge = match($f->type) {
                                    'puskesmas' => 'bg-success-subtle text-success',
                                    'rs_tipe_a', 'rs_tipe_b' => 'bg-danger-subtle text-danger',
                                    'rs_tipe_c', 'rs_tipe_d' => 'bg-warning-subtle text-warning',
                                    default => 'bg-primary-subtle text-primary'
                                };
                            @endphp
                            <span class="badge rounded-pill px-3 py-2 {{ $typeBadge }} fw-medium">
                                {{ strtoupper(str_replace('_', ' ', $f->type)) }}
                            </span>
                        </td>
                        <td>
                            <div class="text-wrap" style="max-width: 250px;">
                                <small class="text-muted">{{ $f->address }}</small>
                            </div>
                        </td>
                        <td class="text-center">
                            <div class="d-flex flex-column align-items-center">
                                <span class="badge {{ $f->is_active ? 'bg-success' : 'bg-danger' }} rounded-circle p-1 mb-1" title="{{ $f->is_active ? 'Aktif' : 'Non-Aktif' }}">
                                    <i class="bi {{ $f->is_active ? 'bi-check' : 'bi-x' }}" style="font-size: 0.8rem;"></i>
                                </span>
                                <small class="{{ $f->is_active ? 'text-success' : 'text-danger' }} fw-bold" style="font-size: 0.65rem; text-transform: uppercase;">
                                    {{ $f->is_active ? 'Aktif' : 'Non-Aktif' }}
                                </small>
                            </div>
                        </td>
                        <td class="pe-4 text-end">
                            <div class="btn-group shadow-sm rounded-3">
                                <a href="{{ route('faskes.edit', $f->id) }}" class="btn btn-sm btn-white text-primary py-2 px-3 border" title="Edit">
                                    <i class="bi bi-pencil-square"></i>
                                </a>
                                <form action="{{ route('faskes.destroy', $f->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Yakin hapus faskes ini?');">
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
                            <i class="bi bi-building-x fs-1 text-muted d-block mb-3 opacity-25"></i>
                            <h5 class="text-muted fw-normal">Belum ada faskes terdaftar.</h5>
                            <a href="{{ route('faskes.create') }}" class="btn btn-link">Tambah sekarang</a>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($faskes->hasPages())
        <div class="card-footer bg-white border-top-0 p-4">
            {{ $faskes->links() }}
        </div>
        @endif
    </div>
</div>
@endsection
