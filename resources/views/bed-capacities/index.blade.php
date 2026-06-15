@extends('layouts.app')

@section('content')
<div class="container py-4">
    <!-- Header Section -->
    <div class="row mb-5 align-items-center animate__animated animate__fadeIn">
        <div class="col">
            <h2 class="fw-bold mb-1"><i class="bi bi-hospital me-2 text-primary"></i>Manajemen Kapasitas Kamar</h2>
            <p class="text-muted mb-0">Pemantauan ketersediaan tempat tidur rumah sakit secara real-time.</p>
        </div>
        <div class="col-auto">
            <a href="{{ route('bed-capacities.create') }}" class="btn btn-primary rounded-pill px-4 shadow-sm py-2" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); border: none;">
                <i class="bi bi-plus-lg me-1"></i> Tambah Ruangan
            </a>
        </div>
    </div>

    <!-- Enhanced Stats Summary -->
    <div class="row g-4 mb-5 animate__animated animate__fadeInUp">
        <div class="col-md-4">
            <div class="card border-0 shadow-lg rounded-4 overflow-hidden" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                <div class="card-body p-4 position-relative">
                    <div class="d-flex align-items-center text-white">
                        <div class="bg-white bg-opacity-25 rounded-circle p-3 me-3">
                            <i class="bi bi-door-open fs-3"></i>
                        </div>
                        <div>
                            <div class="small opacity-75 text-uppercase fw-bold ls-1">Total Kapasitas</div>
                            <div class="fs-2 fw-bold">{{ $totalCapacity }} <small class="fs-6 opacity-75 fw-normal">Bed</small></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card border-0 shadow-lg rounded-4 overflow-hidden" style="background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%);">
                <div class="card-body p-4 position-relative">
                    <div class="d-flex align-items-center text-white">
                        <div class="bg-white bg-opacity-25 rounded-circle p-3 me-3">
                            <i class="bi bi-check2-circle fs-3"></i>
                        </div>
                        <div>
                            <div class="small opacity-75 text-uppercase fw-bold ls-1">Tersedia (Ready)</div>
                            <div class="fs-2 fw-bold">{{ $totalAvailable }} <small class="fs-6 opacity-75 fw-normal">Bed</small></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card border-0 shadow-lg rounded-4 overflow-hidden" style="background: linear-gradient(135deg, #ff9966 0%, #ff5e62 100%);">
                <div class="card-body p-4 position-relative">
                    <div class="d-flex align-items-center text-white">
                        <div class="bg-white bg-opacity-25 rounded-circle p-3 me-3">
                            <i class="bi bi-person-fill-check fs-3"></i>
                        </div>
                        <div>
                            <div class="small opacity-75 text-uppercase fw-bold ls-1">Tingkat Okupansi</div>
                            <div class="fs-2 fw-bold text-white">{{ $occupancyRate }}%</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success border-0 shadow-sm rounded-4 mb-4 d-flex align-items-center p-3 animate__animated animate__fadeInDown">
            <div class="bg-white rounded-circle p-1 me-3 text-success shadow-sm">
                <i class="bi bi-check-lg fs-5"></i>
            </div>
            <div class="fw-bold">{{ session('success') }}</div>
            <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="card border-0 shadow-lg rounded-4 overflow-hidden animate__animated animate__fadeInUp animate__delay-1s">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="bg-light bg-opacity-50">
                    <tr>
                        <th class="ps-4 py-4 text-uppercase small fw-bold text-muted ls-1">Ruangan / Kamar</th>
                        @if(auth()->user()->role === 'admin_pusat')
                            <th class="py-4 text-uppercase small fw-bold text-muted ls-1">Lokasi Faskes</th>
                        @endif
                        <th class="text-center py-4 text-uppercase small fw-bold text-muted ls-1">Total</th>
                        <th class="text-center py-4 text-uppercase small fw-bold text-muted ls-1">Ready</th>
                        <th class="py-4 text-uppercase small fw-bold text-muted ls-1" style="min-width: 200px;">Visual Occupancy</th>
                        <th class="py-4 text-uppercase small fw-bold text-muted ls-1">Terakhir Update</th>
                        <th class="text-center pe-4 py-4 text-uppercase small fw-bold text-muted ls-1">Opsi</th>
                    </tr>
                </thead>
                <tbody class="border-top-0">
                    @forelse($beds as $bed)
                    @php
                        $percentage = $bed->capacity > 0 ? round((($bed->capacity - $bed->available) / $bed->capacity) * 100) : 0;
                        $colorClass = 'bg-success';
                        $bgSoft = 'bg-success-subtle';
                        $textCol = 'text-success';
                        
                        if($percentage >= 90) { $colorClass = 'bg-danger'; $bgSoft = 'bg-danger-subtle'; $textCol = 'text-danger'; }
                        elseif($percentage >= 70) { $colorClass = 'bg-warning'; $bgSoft = 'bg-warning-subtle'; $textCol = 'text-warning'; }
                    @endphp
                    <tr>
                        <td class="ps-4">
                            <div class="d-flex align-items-center">
                                <div class="bg-primary bg-opacity-10 text-primary p-2 rounded-3 me-3">
                                    <i class="bi bi-door-closed fs-5"></i>
                                </div>
                                <div>
                                    <span class="fw-bold d-block text-dark">{{ $bed->room_name }}</span>
                                    <small class="text-muted">Unit ID: #{{ str_pad($bed->id, 5, '0', STR_PAD_LEFT) }}</small>
                                </div>
                            </div>
                        </td>
                        @if(auth()->user()->role === 'admin_pusat')
                        <td>
                            <div class="d-flex align-items-center">
                                <i class="bi bi-building text-muted me-2 small"></i>
                                <span class="badge bg-light text-dark border fw-medium px-2 py-1">{{ $bed->faskes->name }}</span>
                            </div>
                        </td>
                        @endif
                        <td class="text-center">
                            <div class="bg-light text-dark fw-bold rounded-pill d-inline-block px-3 py-1 small">
                                {{ $bed->capacity }}
                            </div>
                        </td>
                        <td class="text-center">
                            <span class="badge {{ $bgSoft }} {{ $textCol }} fs-6 rounded-pill px-3 py-2 fw-bold">
                                {{ $bed->available }}
                            </span>
                        </td>
                        <td>
                            <div class="pe-3">
                                <div class="d-flex justify-content-between align-items-center mb-1">
                                    <small class="fw-bold {{ $textCol }}">{{ $percentage }}% Terisi</small>
                                    <small class="text-muted small">OKUPANSI</small>
                                </div>
                                <div class="progress overflow-hidden shadow-none border" style="height: 10px; border-radius: 20px;">
                                    <div class="progress-bar {{ $colorClass }} progress-bar-striped progress-bar-animated" role="progressbar" style="width: {{ $percentage }}%" aria-valuenow="{{ $percentage }}" aria-valuemin="0" aria-valuemax="100"></div>
                                </div>
                            </div>
                        </td>
                        <td>
                            <div class="d-flex align-items-center">
                                <i class="bi bi-clock-history text-muted me-2"></i>
                                <div class="d-flex flex-column">
                                    <span class="small text-dark fw-bold">{{ \Carbon\Carbon::parse($bed->last_updated)->diffForHumans() }}</span>
                                    <span class="text-muted" style="font-size: 0.7rem;">{{ \Carbon\Carbon::parse($bed->last_updated)->format('H:i, d/m/Y') }}</span>
                                </div>
                            </div>
                        </td>
                        <td class="text-center pe-4">
                            <div class="btn-group shadow-sm rounded-pill overflow-hidden border">
                                <a href="{{ route('bed-capacities.edit', $bed) }}" class="btn btn-white btn-sm px-3 text-primary border-0" title="Manage">
                                    <i class="bi bi-gear-fill"></i>
                                </a>
                                <form action="{{ route('bed-capacities.destroy', $bed) }}" method="POST" class="d-inline" onsubmit="return confirm('Hapus data ruangan ini?');">
                                    @csrf @method('DELETE')
                                    <button class="btn btn-white btn-sm px-3 text-danger border-0 border-start" title="Hapus">
                                        <i class="bi bi-trash3-fill"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="{{ auth()->user()->role === 'admin_pusat' ? 7 : 6 }}" class="text-center py-5">
                            <div class="py-5">
                                <div class="bg-light d-inline-block rounded-circle p-4 mb-3">
                                    <i class="bi bi-inbox fs-1 text-muted"></i>
                                </div>
                                <h5 class="text-muted fw-bold">Data Kamar Kosong</h5>
                                <p class="text-muted small">Silakan tambahkan data kapasitas tempat tidur untuk faskes Anda.</p>
                                <a href="{{ route('bed-capacities.create') }}" class="btn btn-primary rounded-pill px-4">Mulai Tambah</a>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($beds->hasPages())
        <div class="card-footer bg-white border-top-0 p-4">
            <div class="d-flex justify-content-center">
                {{ $beds->links() }}
            </div>
        </div>
        @endif
    </div>
</div>

<style>
    .ls-1 { letter-spacing: 0.5px; }
    .btn-white { background: #fff; }
    .btn-white:hover { background: #f8f9fa; }
    .progress-bar-animated {
        transition: width 0.6s ease;
    }
</style>
@endsection
