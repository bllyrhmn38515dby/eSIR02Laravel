@extends('layouts.app')

@section('content')
<div class="container py-4">
    <!-- Header Section -->
    <div class="row mb-5 align-items-center animate__animated animate__fadeIn">
        <div class="col">
            <h2 class="fw-bold text-dark mb-1">Pusat Laporan & Audit</h2>
            <p class="text-muted mb-0">Kelola dan ekspor data rujukan untuk kebutuhan administrasi dan evaluasi.</p>
        </div>
        <div class="col-auto">
            <div class="btn-group shadow-sm rounded-pill p-1 bg-white border border-white">
                <a href="{{ route('reports.csv', request()->all()) }}" class="btn btn-outline-success border-0 rounded-pill px-3 fw-bold small">
                    <i class="bi bi-file-earmark-spreadsheet me-1"></i> Ekspor CSV
                </a>
                <a href="{{ route('reports.pdf', request()->all()) }}" class="btn btn-outline-danger border-0 rounded-pill px-3 fw-bold small">
                    <i class="bi bi-file-earmark-pdf me-1"></i> Ekspor PDF
                </a>
            </div>
        </div>
    </div>

    <!-- Filter Card -->
    <div class="card glass-card border-0 shadow-sm rounded-4 mb-4 animate__animated animate__fadeInUp">
        <div class="card-body p-4">
            <form action="{{ route('reports.index') }}" method="GET" class="row g-3">
                <div class="col-md-3">
                    <label class="form-label small fw-bold text-muted">Tanggal Mulai</label>
                    <input type="date" name="start_date" class="form-control rounded-3 border-light bg-light bg-opacity-50" value="{{ $start_date }}">
                </div>
                <div class="col-md-3">
                    <label class="form-label small fw-bold text-muted">Tanggal Selesai</label>
                    <input type="date" name="end_date" class="form-control rounded-3 border-light bg-light bg-opacity-50" value="{{ $end_date }}">
                </div>
                <div class="col-md-2">
                    <label class="form-label small fw-bold text-muted">Status</label>
                    <select name="status" class="form-select rounded-3 border-light bg-light bg-opacity-50">
                        <option value="">Semua Status</option>
                        <option value="draft" {{ $status == 'draft' ? 'selected' : '' }}>Draft</option>
                        <option value="sent" {{ $status == 'sent' ? 'selected' : '' }}>Sent</option>
                        <option value="accepted" {{ $status == 'accepted' ? 'selected' : '' }}>Accepted</option>
                        <option value="traveling" {{ $status == 'traveling' ? 'selected' : '' }}>Traveling</option>
                        <option value="arrived" {{ $status == 'arrived' ? 'selected' : '' }}>Arrived</option>
                        <option value="completed" {{ $status == 'completed' ? 'selected' : '' }}>Completed</option>
                        <option value="cancelled" {{ $status == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label small fw-bold text-muted">Prioritas (Triase)</label>
                    <select name="triage" class="form-select rounded-3 border-light bg-light bg-opacity-50">
                        <option value="">Semua Prioritas</option>
                        <option value="P1" {{ $triage == 'P1' ? 'selected' : '' }}>P1 - Merah</option>
                        <option value="P2" {{ $triage == 'P2' ? 'selected' : '' }}>P2 - Kuning</option>
                        <option value="P3" {{ $triage == 'P3' ? 'selected' : '' }}>P3 - Hijau</option>
                        <option value="none" {{ $triage == 'none' ? 'selected' : '' }}>Non-Emergency</option>
                    </select>
                </div>
                <div class="col-md-2 d-flex align-items-end">
                    <button type="submit" class="btn btn-primary w-100 rounded-pill fw-bold shadow-sm">
                        <i class="bi bi-filter-right me-1"></i> Terapkan
                    </button>
                </div>

                @if(auth()->user()->role === 'admin_pusat')
                <div class="col-md-6">
                    <label class="form-label small fw-bold text-muted">Faskes Asal</label>
                    <select name="from_faskes_id" class="form-select rounded-3 border-light bg-light bg-opacity-50">
                        <option value="">Semua Faskes</option>
                        @foreach($faskes as $f)
                        <option value="{{ $f->id }}" {{ $from_faskes_id == $f->id ? 'selected' : '' }}>{{ $f->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-6">
                    <label class="form-label small fw-bold text-muted">Faskes Tujuan</label>
                    <select name="to_faskes_id" class="form-select rounded-3 border-light bg-light bg-opacity-50">
                        <option value="">Semua Faskes</option>
                        @foreach($faskes as $f)
                        <option value="{{ $f->id }}" {{ $to_faskes_id == $f->id ? 'selected' : '' }}>{{ $f->name }}</option>
                        @endforeach
                    </select>
                </div>
                @endif
            </form>
        </div>
    </div>

    <!-- Results Table -->
    <div class="card border-0 shadow-sm rounded-4 overflow-hidden mb-5 animate__animated animate__fadeInUp animate__delay-1s">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="bg-light bg-opacity-50">
                    <tr>
                        <th class="ps-4 py-3 text-uppercase small fw-bold text-muted">ID & Pasien</th>
                        <th class="py-3 text-uppercase small fw-bold text-muted">Detail Rujukan</th>
                        <th class="py-3 text-uppercase small fw-bold text-muted text-center">Triase</th>
                        <th class="py-3 text-uppercase small fw-bold text-muted text-center">Status</th>
                        <th class="py-3 text-uppercase small fw-bold text-muted">Waktu</th>
                        <th class="pe-4 py-3 text-uppercase small fw-bold text-muted text-end">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($referrals as $ref)
                    <tr>
                        <td class="ps-4">
                            <div class="d-flex align-items-center">
                                <div class="bg-primary-subtle rounded-3 p-2 me-3 text-primary fw-bold small">
                                    #{{ $ref->id }}
                                </div>
                                <div>
                                    <span class="fw-bold d-block text-dark">{{ $ref->patient->name }}</span>
                                    <small class="text-muted">NIK: {{ $ref->patient->nik }}</small>
                                </div>
                            </div>
                        </td>
                        <td>
                            <div class="small">
                                <div class="text-muted">Dari: <span class="text-dark">{{ $ref->fromFaskes->name }}</span></div>
                                <div class="text-muted">Ke: <span class="text-dark fw-medium">{{ $ref->toFaskes->name }}</span></div>
                            </div>
                        </td>
                        <td class="text-center">
                            @php $triageInfo = $ref->getTriageInfo(); @endphp
                            <span class="badge rounded-pill px-3 py-1 {{ $triageInfo['class'] }} small shadow-sm">
                                {{ $triageInfo['label'] }}
                            </span>
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
                            <span class="badge rounded-pill px-3 py-2 {{ $statusClasses[$ref->status] ?? 'bg-secondary' }} small">
                                {{ strtoupper($ref->status) }}
                            </span>
                        </td>
                        <td>
                            <div class="d-flex flex-column">
                                <span class="small text-dark fw-medium">{{ $ref->created_at->format('d M Y') }}</span>
                                <span class="text-muted" style="font-size: 0.7rem;">{{ $ref->created_at->format('H:i') }} WIB</span>
                            </div>
                        </td>
                        <td class="pe-4 text-end">
                            <a href="{{ route('referrals.show', $ref->id) }}" class="btn btn-sm btn-light border rounded-pill px-3 fw-semibold">
                                <i class="bi bi-eye"></i>
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center py-5">
                            <i class="bi bi-search fs-1 text-muted d-block mb-3"></i>
                            <h6 class="text-muted fw-normal">Tidak ada data rujukan yang sesuai dengan filter.</h6>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($referrals->hasPages())
        <div class="card-footer bg-white border-0 py-3">
            {{ $referrals->links() }}
        </div>
        @endif
    </div>
</div>
@endsection
