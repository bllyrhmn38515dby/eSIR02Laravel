@extends('layouts.app')

@section('content')
<div class="container-fluid py-4 px-lg-5">
    <!-- Header & Status Stepper -->
    <div class="row mb-5 animate__animated animate__fadeIn">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h2 class="fw-bold mb-1"><i class="bi bi-shield-shaded me-2 text-primary"></i>Referral Mission Control</h2>
                    <p class="text-muted mb-0">No Rujukan: <span class="badge bg-light text-dark border px-3 py-2 fw-bold shadow-sm">#{{ $referral->referral_number }}</span></p>
                </div>
                <div class="d-flex gap-2">
                    <a href="{{ route('referrals.pdf', $referral) }}" target="_blank" class="btn btn-outline-danger rounded-pill px-4 shadow-sm fw-bold bg-white">
                        <i class="bi bi-file-earmark-pdf me-1"></i> Cetak Berkas
                    </a>
                    <a href="{{ route('referrals.index') }}" class="btn btn-light rounded-pill px-4 shadow-sm fw-bold">
                        <i class="bi bi-arrow-left me-1"></i> Kembali
                    </a>
                </div>
            </div>

            <!-- Status Stepper -->
            <div class="card border-0 shadow-sm rounded-4 overflow-hidden mb-4 bg-light">
                <div class="card-body p-0">
                    <div class="d-flex justify-content-between align-items-center p-4">
                        @php
                            $steps = [
                                'draft' => ['icon' => 'bi-pencil', 'label' => 'Draft'],
                                'sent' => ['icon' => 'bi-send', 'label' => 'Sent'],
                                'accepted' => ['icon' => 'bi-check-circle', 'label' => 'Accepted'],
                                'traveling' => ['icon' => 'bi-truck', 'label' => 'On Road', 'pulse' => true],
                                'arrived' => ['icon' => 'bi-hospital', 'label' => 'Arrived'],
                                'completed' => ['icon' => 'bi-flag-fill', 'label' => 'Completed']
                            ];
                            $currentStatus = $referral->status;
                            $statusIndex = array_search($currentStatus, array_keys($steps));
                            if ($statusIndex === false && $currentStatus == 'rejected') $statusIndex = -1;
                        @endphp
                        
                        @foreach($steps as $key => $step)
                            @php
                                $isActive = $key == $currentStatus;
                                $isDone = array_search($key, array_keys($steps)) < $statusIndex;
                                $stepColor = $isDone ? 'btn-success' : ($isActive ? 'btn-primary' : 'btn-white');
                            @endphp
                            <div class="text-center position-relative flex-grow-1">
                                <div class="mb-2">
                                    <span class="btn {{ $stepColor }} rounded-circle p-0 d-inline-flex align-items-center justify-content-center shadow-sm" style="width: 45px; height: 45px; z-index: 2; position: relative; border-width: 2px;">
                                        <i class="bi {{ $step['icon'] }} fs-5"></i>
                                    </span>
                                </div>
                                <div class="small fw-bold text-uppercase ls-1 {{ $isActive ? 'text-primary' : 'text-muted' }}">{{ $step['label'] }}</div>
                                
                                @if(!$loop->last)
                                    <div class="position-absolute top-50 start-100 translate-middle-y w-100 border-top" style="z-index: 1; margin-top: -12px; border-width: 3px !important; border-color: {{ $isDone ? '#198754' : '#dee2e6' }} !important;"></div>
                                @endif
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4">
        <!-- Left Column: Patient & Clinical info -->
        <div class="col-lg-4 animate__animated animate__fadeInLeft">
            <!-- Patient Mini Profile -->
            <div class="card border-0 shadow-lg rounded-4 overflow-hidden mb-4">
                <div class="card-header bg-primary text-white p-4 border-0" style="background: linear-gradient(135deg, #1e3a8a 0%, #3b82f6 100%);">
                    <div class="d-flex align-items-center">
                        <div class="bg-white bg-opacity-25 rounded-circle p-2 me-3">
                            <i class="bi bi-person-badge fs-4"></i>
                        </div>
                        <div>
                            <h5 class="mb-0 fw-bold">{{ $referral->patient->name }}</h5>
                            <p class="mb-0 small opacity-75">NIK: {{ $referral->patient->nik }}</p>
                        </div>
                    </div>
                </div>
                <div class="card-body p-4">
                    <div class="mb-3">
                        <label class="small text-muted text-uppercase fw-bold ls-1 d-block mb-1">Diagnosa Utama (ICD-10)</label>
                        <div class="p-3 bg-light rounded-3 fw-bold text-dark border-start border-4 border-primary shadow-xs">
                            {{ $referral->diagnosis }}
                        </div>
                    </div>
                    <div class="mb-4">
                        <label class="small text-muted text-uppercase fw-bold ls-1 d-block mb-1">Alasan Merujuk</label>
                        <p class="small text-dark mb-0 bg-light p-2 rounded">{{ $referral->reason ?? 'Tidak ada catatan alasan khusus.' }}</p>
                    </div>

                    <hr class="my-4">

                    <!-- Vitals Visual Gauges -->
                    <h6 class="fw-bold text-dark text-uppercase ls-1 mb-3"><i class="bi bi-activity me-2"></i> Kondisi Terakhir</h6>
                    <div class="row g-3">
                        <div class="col-6">
                            <div class="p-3 rounded-4 {{ $referral->heart_rate > 100 ? 'bg-danger-subtle border-danger' : 'bg-success-subtle border-success' }} border border-opacity-25">
                                <div class="small text-muted text-uppercase fw-bold ls-1 mb-1" style="font-size: 0.65rem;">Nadi</div>
                                <div class="d-flex align-items-baseline">
                                    <span class="fs-4 fw-bold me-1">{{ $referral->heart_rate ?? '--' }}</span>
                                    <small class="text-muted">bpm</small>
                                </div>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="p-3 rounded-4 {{ $referral->temperature > 38 ? 'bg-danger-subtle border-danger' : 'bg-success-subtle border-success' }} border border-opacity-25">
                                <div class="small text-muted text-uppercase fw-bold ls-1 mb-1" style="font-size: 0.65rem;">Suhu</div>
                                <div class="d-flex align-items-baseline">
                                    <span class="fs-4 fw-bold me-1">{{ $referral->temperature ?? '--' }}</span>
                                    <small class="text-muted">°C</small>
                                </div>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="p-3 rounded-4 bg-light border">
                                <div class="d-flex justify-content-between align-items-center mb-1">
                                    <div class="small text-muted text-uppercase fw-bold ls-1" style="font-size: 0.65rem;">GCS Score</div>
                                    <span class="badge bg-dark rounded-pill">{{ $referral->gcs_score ?? '--' }}/15</span>
                                </div>
                                <div class="progress" style="height: 6px; border-radius: 10px;">
                                    <div class="progress-bar bg-primary" role="progressbar" style="width: {{ (($referral->gcs_score ?? 0) / 15) * 100 }}%"></div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Boarding Pass QR -->
                    <div class="mt-4 p-4 rounded-4 border-2 border-dashed text-center bg-white shadow-sm" style="border-color: #dee2e6 !important;">
                        <h6 class="fw-bold text-dark mb-3"><i class="bi bi-qr-code-scan me-2"></i>Handover QR Pass</h6>
                        <div class="p-3 bg-white d-inline-block rounded-3 border mb-3">
                            {!! \SimpleSoftwareIO\QrCode\Facades\QrCode::size(140)->format('svg')->generate(route('referrals.handover', $referral)) !!}
                        </div>
                        <p class="small text-muted px-2 mb-0">Tunjukkan QR ini saat serah terima pasien di RS Tujuan untuk validasi sistem instan.</p>
                        @if(auth()->user()->role === 'admin_pusat' || auth()->user()->faskes_id === $referral->to_faskes_id)
                        <form action="{{ route('referrals.handover', $referral) }}" method="POST" class="mt-3">
                            @csrf
                            <button type="submit" class="btn btn-success btn-sm rounded-pill px-4 fw-bold shadow-sm">
                                <i class="bi bi-check2-all me-1"></i> Paksa Selesaikan
                            </button>
                        </form>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Center Column: Operations & Status Update -->
        <div class="col-lg-5 animate__animated animate__fadeInUp animate__delay-1s">
            <div class="card border-0 shadow-lg rounded-4 overflow-hidden mb-4 h-100">
                <div class="card-header bg-white p-4 border-bottom d-flex justify-content-between align-items-center">
                    <h5 class="fw-bold text-dark mb-0 text-uppercase ls-1">Operational Control</h5>
                    <span class="badge bg-light text-dark border fw-medium px-3 py-2">Last Update: {{ $referral->updated_at->diffForHumans() }}</span>
                </div>
                <div class="card-body p-4">
                    <form action="{{ route('referrals.update', $referral->id) }}" method="POST">
                        @csrf @method('PUT')
                        
                        <div class="row g-4">
                            <div class="col-12">
                                <label class="form-label fw-bold text-muted small text-uppercase ls-1">Status Rujukan Terkini</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-0"><i class="bi bi-flag-fill text-primary"></i></span>
                                    <select name="status" class="form-select border-0 bg-light py-3 fw-bold @error('status') is-invalid @enderror" required>
                                        <option value="draft" {{ $referral->status == 'draft' ? 'selected' : '' }}>Draft</option>
                                        <option value="sent" {{ $referral->status == 'sent' ? 'selected' : '' }}>Dikirim (Menunggu Respon)</option>
                                        <option value="accepted" {{ $referral->status == 'accepted' ? 'selected' : '' }}>Diterima (Siap Penjemputan)</option>
                                        <option value="rejected" {{ $referral->status == 'rejected' ? 'selected' : '' }}>Ditolak</option>
                                        <option value="traveling" {{ $referral->status == 'traveling' ? 'selected' : '' }}>Dalam Perjalanan (Ambulans)</option>
                                        <option value="arrived" {{ $referral->status == 'arrived' ? 'selected' : '' }}>Pasien Tiba (Handover)</option>
                                        <option value="completed" {{ $referral->status == 'completed' ? 'selected' : '' }}>Selesai / Ditangani</option>
                                        <option value="cancelled" {{ $referral->status == 'cancelled' ? 'selected' : '' }}>Dibatalkan</option>
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-bold text-muted small text-uppercase ls-1">Driver Penugasan</label>
                                <select name="driver_id" class="form-select border-1 py-2">
                                    <option value="">-- Pilih Supir --</option>
                                    @foreach($drivers as $d)
                                        <option value="{{ $d->id }}" {{ $referral->driver_id == $d->id ? 'selected' : '' }}>{{ $d->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-bold text-muted small text-uppercase ls-1">Armada Ambulans</label>
                                <select name="ambulance_id" class="form-select border-1 py-2">
                                    <option value="">-- Pilih Armada --</option>
                                    @foreach($ambulances as $a)
                                        <option value="{{ $a->id }}" {{ $referral->ambulance_id == $a->id ? 'selected' : '' }}>{{ $a->police_number }} ({{ $a->vehicle_type }})</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-12">
                                <label class="form-label fw-bold text-muted small text-uppercase ls-1">Catatan Tambahan (Opsional)</label>
                                <textarea name="notes" class="form-control border-1 p-3" rows="3" placeholder="Contoh: Pasien butuh pendampingan khusus oxygen selama jalan...">{{ $referral->notes }}</textarea>
                            </div>

                            <div class="col-12 text-end pt-3">
                                <button type="submit" class="btn btn-primary rounded-pill px-5 py-3 shadow-lg fw-bold w-100" style="background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%); border: none;">
                                    <i class="bi bi-save2 me-2"></i>Update Logistik & Status
                                </button>
                            </div>
                        </div>
                    </form>

                    <hr class="my-5">

                    <!-- Document Section -->
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h6 class="fw-bold text-dark text-uppercase ls-1 mb-0"><i class="bi bi-folder-fill me-2 text-warning"></i> Dokumen Penunjang</h6>
                        <span class="badge bg-light text-muted border px-2 py-1">{{ $referral->documents->count() }} Files</span>
                    </div>
                    <div class="bg-light p-4 rounded-4 border">
                        <ul class="list-group list-group-flush bg-transparent mb-4">
                            @forelse($referral->documents as $doc)
                                <li class="list-group-item bg-transparent d-flex justify-content-between align-items-center px-0">
                                    <div class="d-flex align-items-center">
                                        <div class="bg-white rounded p-2 me-3 shadow-xs border">
                                            <i class="bi bi-file-earmark-pdf text-danger fs-5"></i>
                                        </div>
                                        <div>
                                            <a href="{{ route('documents.download', $doc) }}" class="fw-bold text-decoration-none text-dark d-block small">{{ basename($doc->file_path) }}</a>
                                            <small class="text-muted text-uppercase" style="font-size: 0.65rem;">{{ $doc->document_type }}</small>
                                        </div>
                                    </div>
                                    @if(auth()->id() === $doc->uploaded_by)
                                    <form action="{{ route('documents.destroy', $doc) }}" method="POST" class="d-inline" onsubmit="return confirm('Hapus dokumen ini?');">
                                        @csrf @method('DELETE') 
                                        <button class="btn btn-link text-danger p-0"><i class="bi bi-x-circle-fill"></i></button>
                                    </form>
                                    @endif
                                </li>
                            @empty
                                <li class="list-group-item bg-transparent text-center py-4 text-muted small">Belum ada dokumen penunjang.</li>
                            @endforelse
                        </ul>

                        <form action="{{ route('documents.store', $referral) }}" method="POST" enctype="multipart/form-data" class="row g-2">
                            @csrf
                            <div class="col-md-5">
                                <input type="text" name="document_type" class="form-control form-control-sm rounded-pill px-3" placeholder="Nama Dokumen" required>
                            </div>
                            <div class="col-md-5">
                                <input type="file" name="document" class="form-control form-control-sm rounded-pill" accept=".pdf,.jpg,.jpeg,.png" required>
                            </div>
                            <div class="col-md-2 text-end">
                                <button class="btn btn-warning btn-sm rounded-circle d-flex align-items-center justify-content-center mx-auto shadow-sm" style="width: 32px; height: 32px;" type="submit">
                                    <i class="bi bi-upload"></i>
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Right Column: Live Chat & Timeline -->
        <div class="col-lg-3 d-flex flex-column gap-4 animate__animated animate__fadeInRight animate__delay-2s">
            <!-- Medical Chat -->
            <div class="card border-0 shadow-lg rounded-4 overflow-hidden flex-grow-1" style="min-height: 450px; display: flex; flex-direction: column;">
                <div class="card-header bg-white p-4 border-bottom d-flex justify-content-between align-items-center">
                    <h6 class="fw-bold text-dark mb-0 text-uppercase ls-1">Live Consulting</h6>
                    <div class="d-flex align-items-center">
                        <span class="bg-success rounded-circle me-1" style="width: 8px; height: 8px;"></span>
                        <small class="text-success fw-bold small">Online</small>
                    </div>
                </div>
                <div class="card-body overflow-auto p-4 bg-light" id="chat-box" style="flex-grow: 1;">
                    @forelse($referral->messages as $msg)
                        <div class="mb-4 {{ $msg->user_id == auth()->id() ? 'text-end' : 'text-start' }}">
                            <div class="d-inline-block px-3 py-2 rounded-4 shadow-xs {{ $msg->user_id == auth()->id() ? 'bg-primary text-white' : 'bg-white text-dark border' }}" style="max-width: 85%;">
                                <div class="small fw-bold mb-1" style="font-size: 0.6rem; opacity: {{ $msg->user_id == auth()->id() ? '0.75' : '0.5' }};">
                                    {{ $msg->user->name }} • {{ $msg->created_at->format('H:i') }}
                                </div>
                                <div class="small">{{ $msg->body }}</div>
                            </div>
                        </div>
                    @empty
                        <div id="no-chat" class="text-center text-muted mt-5 py-5 px-3">
                            <i class="bi bi-chat-dots fs-1 opacity-25 d-block mb-3"></i>
                            <small>Mulai koordinasi medis seputar pasien ini...</small>
                        </div>
                    @endforelse
                </div>
                <div class="card-footer bg-white p-4 border-top-0 pt-0">
                    <form id="chat-form" class="input-group bg-light rounded-pill p-1 border">
                        <input type="text" id="chat-input" class="form-control border-0 bg-transparent ps-3" placeholder="Ketik koordinasi..." required>
                        <button type="submit" class="btn btn-primary rounded-circle d-flex align-items-center justify-content-center shadow-sm" id="btn-send-chat" style="width: 40px; height: 40px; padding: 0;">
                            <i class="bi bi-send-fill" style="transform: rotate(45deg); margin-left: -2px; margin-top: -2px;"></i>
                        </button>
                    </form>
                </div>
            </div>

            <!-- Timeline Audit -->
            <div class="card border-0 shadow-lg rounded-4 overflow-hidden" style="max-height: 350px;">
                <div class="card-header bg-white p-4 border-bottom">
                    <h6 class="fw-bold text-dark mb-0 text-uppercase ls-1">Audit Timeline</h6>
                </div>
                <div class="card-body overflow-auto p-4">
                    <div class="vertical-timeline ps-4 border-start border-2 position-relative">
                        @forelse($referral->auditLogs as $log)
                            <div class="timeline-item position-relative mb-4">
                                <div class="position-absolute translate-middle-x bg-white border border-primary border-2 rounded-circle" style="width: 12px; height: 12px; left: -19px; top: 5px;"></div>
                                <div class="d-flex justify-content-between align-items-center mb-1">
                                    <h6 class="fw-bold text-primary mb-0" style="font-size: 0.75rem;">{{ $log->user->name ?? 'Sistem' }}</h6>
                                    <small class="text-muted" style="font-size: 0.65rem;">{{ $log->created_at->diffForHumans() }}</small>
                                </div>
                                <p class="text-dark small mb-0" style="font-size: 0.75rem; line-height: 1.4;">{{ $log->description }}</p>
                            </div>
                        @empty
                            <div class="text-center text-muted py-3 small">Belum ada catatan aktivitas.</div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .ls-1 { letter-spacing: 0.5px; }
    .shadow-xs { box-shadow: 0 .125rem .25rem rgba(0,0,0,.075); }
    .btn-white { background: #fff; border-color: #dee2e6; color: #6c757d; }
    .bg-purple { background-color: #6f42c1; color: white; }
    #chat-box::-webkit-scrollbar { width: 4px; }
    #chat-box::-webkit-scrollbar-thumb { background: #cbd5e0; border-radius: 10px; }
    .vertical-timeline::before {
        content: '';
        position: absolute;
        top: 0; bottom: 0; left: -2px;
        width: 2px; background: #e2e8f0;
    }
</style>
@endsection

@push('scripts')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
document.addEventListener("DOMContentLoaded", function() {
    const chatBox = document.getElementById('chat-box');
    const chatForm = document.getElementById('chat-form');
    const chatInput = document.getElementById('chat-input');
    const noChat = document.getElementById('no-chat');

    // Scroll down initially
    if(chatBox) chatBox.scrollTop = chatBox.scrollHeight;

    const authUserId = {{ auth()->id() }};
    
    chatForm.addEventListener('submit', function(e) {
        e.preventDefault();
        let message = chatInput.value.trim();
        if(!message) return;

        chatInput.value = '';
        
        fetch('{{ route('messages.store', $referral) }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({ body: message })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                if(noChat) noChat.remove();
                appendMessage(data.message, data.time, true);
            }
        })
        .catch(err => console.error(err));
    });

    function appendMessage(message, time, isMe) {
        const html = `
            <div class="mb-4 ${isMe ? 'text-end' : 'text-start'}">
                <div class="d-inline-block px-3 py-2 rounded-4 shadow-xs ${isMe ? 'bg-primary text-white' : 'bg-white text-dark border'}" style="max-width: 85%;">
                    <div class="small fw-bold mb-1" style="font-size: 0.6rem; opacity: ${isMe ? '0.75' : '0.5'};">
                        ${message.user.name} • ${time}
                    </div>
                    <div class="small">${message.body}</div>
                </div>
            </div>
        `;
        chatBox.insertAdjacentHTML('beforeend', html);
        chatBox.scrollTop = chatBox.scrollHeight;
    }

    if (window.Echo) {
        window.Echo.channel('referral.{{ $referral->id }}')
            .listen('.MessageSent', (e) => {
                if(noChat) noChat.remove();
                
                const isMe = e.message.user_id === authUserId;
                
                // Play sound if message is from others
                if (!isMe) {
                    const sound = document.getElementById('notification-sound');
                    if (sound) sound.play().catch(err => console.log('Audio blocked'));
                    
                    appendMessage(e.message, e.time, false);
                }
            });
    }
});
</script>
@endpush
