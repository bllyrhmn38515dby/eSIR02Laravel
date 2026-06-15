@extends('layouts.app')

@section('content')
<div class="container py-4">
    <!-- Header Section -->
    <div class="row mb-5 align-items-center animate__animated animate__fadeIn">
        <div class="col">
            <h2 class="fw-bold mb-1"><i class="bi bi-send-check me-2 text-primary"></i>Manajemen Rujukan</h2>
            <p class="text-muted mb-0">Monitor alur rujukan pasien antar faskes secara real-time.</p>
        </div>
        <div class="col-auto d-flex gap-2">
            <a href="{{ route('referrals.export') }}" class="btn btn-outline-success rounded-pill px-4 shadow-sm py-2 bg-white">
                <i class="bi bi-file-earmark-spreadsheet me-1"></i> Export Data
            </a>
            @if(auth()->user()->role === 'admin_faskes')
            <a href="{{ route('referrals.create') }}" class="btn btn-primary rounded-pill px-4 shadow-sm py-2" style="background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%); border: none;">
                <i class="bi bi-plus-lg me-1"></i> Buat Rujukan Baru
            </a>
            @endif
        </div>
    </div>

    <!-- Referral Stats Summary -->
    <div class="row g-4 mb-5 animate__animated animate__fadeInUp">
        <div class="col-md-3">
            <div class="card border-0 shadow-lg rounded-4 overflow-hidden h-100">
                <div class="card-body p-4 border-start border-4 border-primary">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <div class="text-muted small text-uppercase fw-bold ls-1 mb-1">Total Rujukan</div>
                            <div class="fs-2 fw-bold">{{ $stats['total'] }}</div>
                        </div>
                        <div class="bg-primary bg-opacity-10 text-primary rounded-circle p-3">
                            <i class="bi bi-journal-medical fs-4"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-lg rounded-4 overflow-hidden h-100">
                <div class="card-body p-4 border-start border-4 border-warning">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <div class="text-muted small text-uppercase fw-bold ls-1 mb-1">Menunggu Respon</div>
                            <div class="fs-2 fw-bold">{{ $stats['pending'] }}</div>
                        </div>
                        <div class="bg-warning bg-opacity-10 text-warning rounded-circle p-3">
                            <i class="bi bi-hourglass-split fs-4"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-lg rounded-4 overflow-hidden h-100">
                <div class="card-body p-4 border-start border-4 border-info">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <div class="text-muted small text-uppercase fw-bold ls-1 mb-1">Sedang Jalan</div>
                            <div class="fs-2 fw-bold text-info">{{ $stats['active'] }}</div>
                        </div>
                        <div class="bg-info bg-opacity-10 text-info rounded-circle p-3">
                            <i class="bi bi-truck fs-4"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-lg rounded-4 overflow-hidden h-100">
                <div class="card-body p-4 border-start border-4 border-success">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <div class="text-muted small text-uppercase fw-bold ls-1 mb-1">Tiba Hari Ini</div>
                            <div class="fs-2 fw-bold text-success">{{ $stats['completed_today'] }}</div>
                        </div>
                        <div class="bg-success bg-opacity-10 text-success rounded-circle p-3">
                            <i class="bi bi-check-all fs-4"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success border-0 shadow-sm rounded-4 mb-4 d-flex align-items-center p-3 animate__animated animate__fadeInDown">
            <i class="bi bi-check-circle-fill me-3 fs-5 shadow-sm rounded-circle p-1 bg-white text-success"></i>
            <div class="fw-bold">{{ session('success') }}</div>
            <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <!-- Referral Inbox Table -->
    <div class="card border-0 shadow-lg rounded-4 overflow-hidden animate__animated animate__fadeInUp animate__delay-1s">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="bg-light bg-opacity-50">
                    <tr>
                        <th class="ps-4 py-4 text-uppercase small fw-bold text-muted ls-1">IDENTITAS RUJUKAN</th>
                        <th class="py-4 text-uppercase small fw-bold text-muted ls-1">ASAL → TUJUAN</th>
                        <th class="py-4 text-uppercase small fw-bold text-muted ls-1">STATUS OPS</th>
                        <th class="py-4 text-uppercase small fw-bold text-muted ls-1">DIAGNOSIS UMUM</th>
                        <th class="text-center pe-4 py-4 text-uppercase small fw-bold text-muted ls-1">AKSI</th>
                    </tr>
                </thead>
                <tbody class="border-top-0">
                    @forelse($referrals as $ref)
                    <tr id="referral-row-{{ $ref->id }}">
                        <td class="ps-4 py-4">
                            <div class="d-flex align-items-center">
                                <div class="bg-primary bg-opacity-10 text-primary p-2 rounded-3 me-3">
                                    <i class="bi bi-file-earmark-text fs-4"></i>
                                </div>
                                <div>
                                    <span class="fw-bold d-block text-dark fs-6">{{ $ref->referral_number }}</span>
                                    <div class="d-flex align-items-center mt-1">
                                        <i class="bi bi-person-circle text-muted me-1 small"></i>
                                        <span class="small fw-bold text-dark me-2">{{ $ref->patient->name ?? '-' }}</span>
                                        <span class="text-muted fs-xs">{{ $ref->created_at->format('d M y • H:i') }}</span>
                                    </div>
                                </div>
                            </div>
                        </td>
                        <td>
                            <div class="d-flex flex-column">
                                <span class="badge bg-light text-dark border fw-medium px-2 py-1 mb-1 shadow-xs align-self-start">{{ $ref->fromFaskes->name ?? '-' }}</span>
                                <div class="ps-3 border-start ms-2 py-1 text-muted"><i class="bi bi-arrow-down small me-1"></i></div>
                                <span class="badge bg-primary bg-opacity-10 text-primary border-primary border-opacity-25 border fw-bold px-2 py-1 shadow-xs align-self-start">{{ $ref->toFaskes->name ?? '-' }}</span>
                            </div>
                        </td>
                        <td>
                            @php
                                $statusMap = [
                                    'draft' => ['class' => 'bg-secondary', 'icon' => 'bi-pencil'],
                                    'sent' => ['class' => 'bg-warning', 'icon' => 'bi-send'],
                                    'accepted' => ['class' => 'bg-info', 'icon' => 'bi-check-circle'],
                                    'rejected' => ['class' => 'bg-danger', 'icon' => 'bi-x-circle'],
                                    'traveling' => ['class' => 'bg-primary', 'icon' => 'bi-truck', 'pulse' => true],
                                    'arrived' => ['class' => 'bg-purple', 'icon' => 'bi-hospital'],
                                    'completed' => ['class' => 'bg-success', 'icon' => 'bi-check-all'],
                                    'cancelled' => ['class' => 'bg-danger', 'icon' => 'bi-trash']
                                ];
                                $s = $statusMap[$ref->status] ?? ['class' => 'bg-secondary', 'icon' => 'bi-question'];
                            @endphp
                            <div class="d-flex align-items-center">
                                <span class="badge {{ $s['class'] }} rounded-pill px-3 py-2 fw-bold d-flex align-items-center shadow-xs">
                                    <i class="bi {{ $s['icon'] }} me-2 {{ isset($s['pulse']) ? 'animate__animated animate__pulse animate__infinite' : '' }}"></i>
                                    {{ strtoupper($ref->status) }}
                                </span>
                            </div>
                        </td>
                        <td>
                            <p class="mb-0 small text-dark fw-medium text-truncate" style="max-width: 200px;" title="{{ $ref->diagnosis }}">
                                {{ $ref->diagnosis }}
                            </p>
                        </td>
                        <td class="text-center pe-4">
                            <div class="d-flex justify-content-center gap-2">
                                @if(auth()->user()->role === 'driver')
                                <a href="{{ route('referrals.show', $ref) }}" class="btn btn-outline-primary btn-sm rounded-pill px-3 fw-bold">
                                    <i class="bi bi-eye me-1"></i> Detail
                                </a>
                                @else
                                <a href="{{ route('referrals.edit', $ref) }}" class="btn btn-outline-primary btn-sm rounded-pill px-3 fw-bold">
                                    <i class="bi bi-kanban me-1"></i> Kelola
                                </a>
                                @endif
                                @if(in_array($ref->status, ['accepted', 'traveling', 'arrived']))
                                <a href="{{ route('tracking.show', $ref) }}" class="btn btn-info btn-sm rounded-pill px-3 fw-bold text-white shadow-sm">
                                    <i class="bi bi-map me-1"></i> Live
                                </a>
                                @endif
                                @if(auth()->user()->role === 'admin_pusat' || (auth()->user()->role === 'admin_faskes' && $ref->status === 'draft'))
                                <form action="{{ route('referrals.destroy', $ref) }}" method="POST" class="d-inline" onsubmit="return confirm('Hapus rujukan ini?');">
                                    @csrf @method('DELETE')
                                    <button class="btn btn-outline-danger btn-sm rounded-circle shadow-xs" style="width: 32px; height: 32px; padding: 0;">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="text-center py-5">
                            <div class="py-5">
                                <div class="bg-light d-inline-block rounded-circle p-4 mb-3">
                                    <i class="bi bi-clipboard2-minus fs-1 text-muted"></i>
                                </div>
                                <h5 class="text-muted fw-bold">Belum Ada Rujukan</h5>
                                <p class="text-muted small">Mulai buat rujukan medis elektronik untuk mengirim pasien antar faskes.</p>
                                @if(auth()->user()->role === 'admin_faskes')
                                <a href="{{ route('referrals.create') }}" class="btn btn-primary rounded-pill px-4">Buat Baru</a>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($referrals->hasPages())
        <div class="card-footer bg-white border-top-0 p-4">
            <div class="d-flex justify-content-center">
                {{ $referrals->links() }}
            </div>
        </div>
        @endif
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener("DOMContentLoaded", function() {
        if (window.Echo) {
            window.Echo.channel('referrals')
                .listen('.ReferralStatusChanged', (e) => {
                    const row = document.getElementById(`referral-row-${e.referral.id}`);
                    if (row) {
                        const statusCell = row.querySelector('td:nth-child(3)');
                        if (statusCell) {
                            const statusMap = {
                                'draft': { class: 'bg-secondary', icon: 'bi-pencil' },
                                'sent': { class: 'bg-warning', icon: 'bi-send' },
                                'accepted': { class: 'bg-info', icon: 'bi-check-circle' },
                                'rejected': { class: 'bg-danger', icon: 'bi-x-circle' },
                                'traveling': { class: 'bg-primary', icon: 'bi-truck', pulse: true },
                                'arrived': { class: 'bg-purple', icon: 'bi-hospital' },
                                'completed': { class: 'bg-success', icon: 'bi-check-all' },
                                'cancelled': { class: 'bg-danger', icon: 'bi-trash' }
                            };
                            const s = statusMap[e.referral.status] || { class: 'bg-secondary', icon: 'bi-question' };
                            statusCell.innerHTML = `
                                <div class="d-flex align-items-center animate__animated animate__flash">
                                    <span class="badge ${s.class} rounded-pill px-3 py-2 fw-bold d-flex align-items-center shadow-xs">
                                        <i class="bi ${s.icon} me-2 ${s.pulse ? 'animate__animated animate__pulse animate__infinite' : ''}"></i>
                                        ${e.referral.status.toUpperCase()}
                                    </span>
                                </div>
                            `;
                        }
                    }
                });
        }
    });
</script>
@endpush

@push('styles')
<style>
    .ls-1 { letter-spacing: 0.5px; }
    .fs-xs { font-size: 0.75rem; }
    .shadow-xs { shadow: 0 .125rem .25rem rgba(0,0,0,.075); }
    .bg-purple { background-color: #6f42c1; color: white; }
    @keyframes pulse-soft {
        0% { transform: scale(1); opacity: 1; }
        50% { transform: scale(1.05); opacity: 0.8; }
        100% { transform: scale(1); opacity: 1; }
    }
</style>
@endpush
@endsection
