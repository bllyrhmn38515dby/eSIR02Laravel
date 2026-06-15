@extends('layouts.app')

@section('content')
<div class="container py-4">
    <!-- Header -->
    <div class="row mb-5 align-items-center animate__animated animate__fadeIn">
        <div class="col">
            <h2 class="fw-bold mb-1"><i class="bi bi-person-gear me-2 text-primary"></i>Manajemen Pengguna</h2>
            <p class="text-muted mb-0">Kelola hak akses, peran, dan status operasional staf eSIR.</p>
        </div>
        <div class="col-auto">
            <a href="{{ route('users.create') }}" class="btn btn-primary rounded-pill px-4 shadow-sm py-2 fw-bold">
                <i class="bi bi-person-plus-fill me-1"></i> Tambah Pengguna
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
                <div class="small opacity-75 mb-1 fw-medium text-uppercase ls-1">Total Pengguna</div>
                <div class="fs-1 fw-bold lh-1">{{ $total_users }}</div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm rounded-4 text-white p-4 h-100 position-relative overflow-hidden" style="background: linear-gradient(135deg, #4361ee, #3a0ca3) !important;">
                <div class="position-absolute end-0 bottom-0 opacity-10 me-n3 mb-n3">
                    <i class="bi bi-shield-lock-fill" style="font-size: 6rem;"></i>
                </div>
                <div class="small opacity-75 mb-1 fw-medium text-uppercase ls-1">Total Admin</div>
                <div class="fs-1 fw-bold lh-1">{{ $total_admins }}</div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm rounded-4 text-white p-4 h-100 position-relative overflow-hidden" style="background: linear-gradient(135deg, #f72585, #b5179e) !important;">
                <div class="position-absolute end-0 bottom-0 opacity-10 me-n3 mb-n3">
                    <i class="bi bi-truck-front-fill" style="font-size: 6rem;"></i>
                </div>
                <div class="small opacity-75 mb-1 fw-medium text-uppercase ls-1">Total Driver</div>
                <div class="fs-1 fw-bold lh-1">{{ $total_drivers }}</div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm rounded-4 bg-success text-white p-4 h-100 position-relative overflow-hidden">
                <div class="position-absolute end-0 bottom-0 opacity-10 me-n3 mb-n3">
                    <i class="bi bi-person-check-fill" style="font-size: 6rem;"></i>
                </div>
                <div class="small opacity-75 mb-1 fw-medium text-uppercase ls-1">User Aktif</div>
                <div class="fs-1 fw-bold lh-1">{{ $active_users }}</div>
            </div>
        </div>
    </div>

    <!-- Alerts -->
    @if(session('success'))
        <div class="alert alert-success border-0 shadow-sm rounded-3 mb-4 d-flex align-items-center p-3 animate__animated animate__fadeInDown">
            <i class="bi bi-check-circle-fill me-2 fs-5"></i>
            {{ session('success') }}
            <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert"></button>
        </div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger border-0 shadow-sm rounded-3 mb-4 d-flex align-items-center p-3 animate__animated animate__fadeInDown">
            <i class="bi bi-exclamation-triangle-fill me-2 fs-5"></i>
            {{ session('error') }}
            <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <!-- Data Table -->
    <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="bg-light bg-opacity-50 border-bottom">
                    <tr>
                        <th class="ps-4 py-3 text-uppercase small fw-bold text-muted">Pengguna</th>
                        <th class="py-3 text-uppercase small fw-bold text-muted">Akses / Email</th>
                        <th class="py-3 text-uppercase small fw-bold text-muted">Peran (Role)</th>
                        <th class="py-3 text-uppercase small fw-bold text-muted">Penempatan Faskes</th>
                        <th class="py-3 text-uppercase small fw-bold text-muted text-center">Status</th>
                        <th class="pe-4 py-3 text-uppercase small fw-bold text-muted text-end">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($users as $user)
                    <tr>
                        <td class="ps-4">
                            <div class="d-flex align-items-center">
                                <div class="rounded-circle bg-primary bg-opacity-10 text-primary fw-bold d-flex align-items-center justify-content-center me-3" style="width: 40px; height: 40px; font-size: 0.9rem;">
                                    {{ strtoupper(substr($user->name, 0, 2)) }}
                                </div>
                                <div>
                                    <div class="fw-bold text-dark">{{ $user->name }}</div>
                                    <small class="text-muted">ID: #{{ $user->id }}</small>
                                </div>
                            </div>
                        </td>
                        <td>
                            <div class="text-dark fw-medium small">{{ $user->email }}</div>
                            <small class="text-muted">Dibuat: {{ $user->created_at->format('d/m/Y') }}</small>
                        </td>
                        <td>
                            @php
                                $roleClass = 'bg-secondary';
                                $roleLabel = $user->role;
                                if ($user->role == 'admin_pusat') { $roleClass = 'bg-primary'; $roleLabel = 'ADMIN PUSAT'; }
                                elseif ($user->role == 'admin_faskes') { $roleClass = 'bg-info text-dark'; $roleLabel = 'ADMIN FASKES'; }
                                elseif ($user->role == 'driver') { $roleClass = 'bg-warning text-dark'; $roleLabel = 'DRIVER'; }
                            @endphp
                            <span class="badge {{ $roleClass }} rounded-pill px-3 py-2 fw-bold" style="font-size: 0.65rem;">
                                <i class="bi bi-shield-check me-1"></i> {{ $roleLabel }}
                            </span>
                        </td>
                        <td>
                            <div class="fw-medium text-dark small">
                                @if($user->faskes)
                                    <i class="bi bi-building me-1 text-muted"></i> {{ $user->faskes->name }}
                                @else
                                    <span class="text-muted italic opacity-50">- Seluruh Jaringan -</span>
                                @endif
                            </div>
                        </td>
                        <td class="text-center">
                            @if($user->is_active)
                                <span class="badge bg-success bg-opacity-10 text-success border border-success border-opacity-25 rounded-pill px-3 py-1">
                                    <i class="bi bi-circle-fill small me-1" style="font-size: 0.5rem;"></i> Aktif
                                </span>
                            @else
                                <span class="badge bg-danger bg-opacity-10 text-danger border border-danger border-opacity-25 rounded-pill px-3 py-1">
                                    <i class="bi bi-circle-fill small me-1" style="font-size: 0.5rem;"></i> Non-Aktif
                                </span>
                            @endif
                        </td>
                        <td class="pe-4 text-end">
                            <div class="btn-group shadow-sm rounded-3">
                                <a href="{{ route('users.edit', $user) }}" class="btn btn-sm btn-white text-primary py-2 px-3 border" title="Edit Pengguna">
                                    <i class="bi bi-pencil-square"></i>
                                </a>
                                @if(auth()->id() !== $user->id)
                                <form action="{{ route('users.destroy', $user) }}" method="POST" class="d-inline" onsubmit="return confirm('Hapus pengguna ini? Tindakan ini tidak dapat dibatalkan.');">
                                    @csrf @method('DELETE')
                                    <button class="btn btn-sm btn-white text-danger py-2 px-3 border-start-0 border" title="Hapus">
                                        <i class="bi bi-trash3-fill"></i>
                                    </button>
                                </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center py-5 border-0">
                            <i class="bi bi-people fs-1 text-muted d-block mb-3 opacity-25"></i>
                            <h5 class="text-muted fw-normal">Belum ada pengguna terdaftar.</h5>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($users->hasPages())
        <div class="card-footer bg-white border-top-0 p-4">
            {{ $users->links() }}
        </div>
        @endif
    </div>
</div>

<style>
.btn-white {
    background: white;
}
.btn-white:hover {
    background: #f8f9fa;
}
</style>
@endsection
