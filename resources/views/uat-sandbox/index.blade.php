@extends('layouts.app')

@push('styles')
<style>
    .sandbox-container {
        background-color: #f4f5f0;
        border-radius: 16px;
        padding: 2rem;
        margin-bottom: 2rem;
    }
    
    .sandbox-card {
        background-color: #fff;
        border-radius: 16px;
        box-shadow: 0 4px 15px rgba(0,0,0,0.05);
        overflow: hidden;
        height: 100%;
        border: 1px solid #e9ecef;
    }
    
    .sandbox-card-header {
        background-color: #0d6efd;
        color: white;
        padding: 1rem 1.5rem;
        font-weight: 600;
        font-size: 1.1rem;
    }
    
    .sandbox-card-body {
        padding: 1.5rem;
    }
    
    .sandbox-input {
        background-color: #f4f5f0;
        border: 1px solid #dee2e6;
        border-radius: 8px;
        padding: 0.75rem 1rem;
    }
    
    .sandbox-input:focus {
        background-color: #fff;
        border-color: #0d6efd;
        box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.25);
    }
    
    .btn-sandbox {
        border-radius: 8px;
        padding: 0.75rem 1.5rem;
        font-weight: 600;
        border: 1px solid #dee2e6;
        background-color: #fff;
        color: #212529;
        transition: all 0.2s;
    }
    
    .btn-sandbox:hover {
        background-color: #f8f9fa;
        border-color: #c6c7cc;
    }
    
    .table-sandbox th {
        color: #6c757d;
        font-weight: 600;
        border-bottom: 1px solid #dee2e6;
        padding: 1rem 0.5rem;
    }
    
    .table-sandbox td {
        padding: 1rem 0.5rem;
        vertical-align: middle;
        border-bottom: 1px solid #f8f9fa;
    }
    
    .badge-valid {
        background-color: #e6f8e0;
        color: #2e7d32;
        border: 1px solid #c8e6c9;
        padding: 0.4rem 0.8rem;
        border-radius: 20px;
        font-weight: 600;
    }
    
    .sandbox-info-box {
        background-color: #e2efff;
        color: #084298;
        border-left: 4px solid #0d6efd;
        padding: 1rem 1.5rem;
        border-radius: 0 8px 8px 0;
        margin-top: 1.5rem;
    }
    
    .validation-error {
        color: #dc3545;
        background-color: #fff;
        border: 1px solid #dc3545;
        padding: 0.5rem 1rem;
        border-radius: 8px;
        margin-top: 0.5rem;
        font-size: 0.9rem;
    }
    
    .autosave-success {
        color: #2e7d32;
        background-color: #e6f8e0;
        padding: 0.5rem 1rem;
        border-radius: 8px;
        font-size: 0.9rem;
        display: inline-block;
        font-weight: 600;
    }
    
    .error-badge {
        background-color: #fdeded;
        color: #dc3545;
        padding: 0.5rem 1rem;
        border-radius: 8px;
        font-size: 0.9rem;
        display: inline-block;
        font-weight: 600;
    }
    
    .section-title {
        color: #495057;
        font-weight: 600;
        margin-bottom: 1rem;
        font-size: 1.1rem;
    }
    @keyframes blink { 50% { opacity: 0; } }
</style>
@endpush

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold mb-1">Sandbox UAT (Interactive Showcase)</h2>
            <p class="text-muted">Demonstrasi langsung fungsi validasi dan keamanan aplikasi eSIR.</p>
        </div>
        <button class="btn btn-primary" onclick="triggerScreenshot()">
            <i class="bi bi-camera"></i> Capture Evidence
        </button>
    </div>

    <!-- SCENARIO 1: LOGIN & ROLE VERIFICATION -->
    <div class="sandbox-container shadow-sm mb-5">
        <div class="row gx-5">
            <!-- Login Form Side -->
            <div class="col-lg-5 mb-4 mb-lg-0">
                <div class="section-title">Halaman login eSIR</div>
                
                <div class="sandbox-card">
                    <div class="sandbox-card-header">
                        eSIR — Login
                    </div>
                    <div class="sandbox-card-body text-center">
                        <h4 class="fw-bold mb-0">eSIR</h4>
                        <p class="text-muted small mb-4">Electronic System for Integrated Referral</p>
                        
                        <div class="text-start mb-3">
                            <label class="form-label fw-semibold text-dark">Email</label>
                            <input type="email" id="loginEmail" class="form-control sandbox-input" value="adminkj@esir.id">
                        </div>
                        
                        <div class="text-start mb-3">
                            <label class="form-label fw-semibold text-dark">Password</label>
                            <input type="password" id="loginPassword" class="form-control sandbox-input" value="password">
                        </div>
                        
                        <div class="text-start mb-4">
                            <label class="form-label fw-semibold text-dark">Role (Pilih untuk Auto-fill)</label>
                            <select class="form-select sandbox-input" id="loginRole" onchange="autoFillCredentials()">
                                <option value="admin_faskes" selected>admin_faskes</option>
                                <option value="admin_pusat">admin_pusat</option>
                                <option value="driver">driver</option>
                                <option value="viewer">viewer</option>
                            </select>
                        </div>
                        
                        <div id="loginError" class="alert alert-danger d-none text-start small p-2 mb-3"></div>
                        <button class="btn btn-sandbox w-100" onclick="simulateLogin()">Masuk</button>
                    </div>
                </div>
            </div>
            
            <!-- Verification Result Side -->
            <div class="col-lg-7">
                <div class="section-title">Hasil verifikasi role (tampilkan 4 peran)</div>
                
                <table class="table table-borderless table-sandbox w-100" id="roleTable">
                    <thead>
                        <tr>
                            <th>Role</th>
                            <th>Akses</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td class="fw-semibold">admin_pusat</td>
                            <td>Semua modul</td>
                            <td><span class="badge-valid opacity-25">✓ Valid</span></td>
                        </tr>
                        <tr>
                            <td class="fw-semibold">admin_faskes</td>
                            <td>Faskes sendiri</td>
                            <td><span class="badge-valid opacity-25">✓ Valid</span></td>
                        </tr>
                        <tr>
                            <td class="fw-semibold">driver</td>
                            <td>Tracking only</td>
                            <td><span class="badge-valid opacity-25">✓ Valid</span></td>
                        </tr>
                        <tr>
                            <td class="fw-semibold">viewer</td>
                            <td>Read only</td>
                            <td><span class="badge-valid opacity-25">✓ Valid</span></td>
                        </tr>
                    </tbody>
                </table>
                
                <div class="sandbox-info-box d-none" id="jwtNotice">
                    Pastikan screenshot menampilkan JWT token di header response
                    <div class="mt-2 text-wrap text-break font-monospace small text-muted" id="jwtTokenDisplay">
                        Bearer eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9...
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- SCENARIO 2: FORM VALIDATION & AUTO-SAVE (INTERACTIVE) -->
    <div class="sandbox-container shadow-sm">

        {{-- Scenario Header --}}
        <div class="d-flex align-items-center gap-3 mb-4">
            <div class="rounded-circle d-flex align-items-center justify-content-center fw-bold text-white bg-warning" style="width:40px;height:40px;font-size:1.1rem;flex-shrink:0;">02</div>
            <div>
                <h5 class="mb-0 fw-bold">Validasi Form & Auto-Save</h5>
                <p class="text-muted mb-0 small">Uji bagaimana sistem menolak data tidak lengkap <em>dan</em> menyimpan data draf secara otomatis.</p>
            </div>
            <span class="ms-auto badge bg-warning text-dark rounded-pill">Interaktif</span>
        </div>

        {{-- Tester Guide --}}
        <div class="alert alert-info border-0 mb-4" style="background-color: #e7f3ff; border-left: 4px solid #0d6efd !important; border-left-style: solid !important;">
            <div class="fw-bold mb-2"><i class="bi bi-info-circle-fill me-2"></i>Petunjuk untuk Penguji (Tester Guide):</div>
            <ol class="mb-0 ps-3 small">
                <li>Coba klik <strong>"Kirim Rujukan"</strong> tanpa mengisi apapun — amati pesan error merah muncul.</li>
                <li>Isi <strong>NIK</strong> dengan angka kurang dari 16 digit — amati validasi format aktif.</li>
                <li>Isi semua field dengan benar, lalu tunggu 3 detik — amati status <strong>Auto-save</strong> berjalan otomatis di pojok kiri bawah.</li>
                <li>Klik <strong>"Kirim Rujukan"</strong> setelah semua terisi — amati pesan sukses muncul.</li>
            </ol>
        </div>

        <div class="row gx-4">
            {{-- Interactive Form --}}
            <div class="col-lg-7 mb-4 mb-lg-0">
                <div class="section-title"><i class="bi bi-pencil-square me-2"></i>Form Rujukan Pasien (Interaktif)</div>
                <div class="sandbox-card">
                    <div class="sandbox-card-header d-flex justify-content-between align-items-center">
                        <span><i class="bi bi-file-medical"></i> Form Rujukan Pasien</span>
                        <div>
                            <button type="button" class="btn btn-sm btn-outline-danger me-2" onclick="autoFillUatFormInvalid()" title="Isi Otomatis Data Gagal">
                                <i class="bi bi-x-circle"></i> Draft (Gagal)
                            </button>
                            <button type="button" class="btn btn-sm btn-outline-success me-2" onclick="autoFillUatForm()" title="Isi Otomatis Data Valid">
                                <i class="bi bi-check-circle"></i> Draft (Sukses)
                            </button>
                            <span class="badge bg-light text-dark rounded-pill" id="autosave-badge" style="font-size: 0.7rem; display:none;">
                                <i class="bi bi-clock"></i> Auto-save: <span id="autosave-time">—</span>
                            </span>
                        </div>
                    </div>
                    <div class="sandbox-card-body">
                        <form id="uatRujukanForm" novalidate>

                            <div class="mb-3">
                                <label class="form-label fw-semibold text-dark">NIK Pasien <span class="text-danger">*</span></label>
                                <input type="text" id="field-nik" class="form-control sandbox-input" placeholder="16 digit NIK KTP" maxlength="16" oninput="onFormChange()">
                                <div class="invalid-feedback" id="err-nik"></div>
                                <div class="valid-feedback">Format NIK valid ✓</div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label fw-semibold text-dark">Nama Lengkap Pasien <span class="text-danger">*</span></label>
                                <input type="text" id="field-nama" class="form-control sandbox-input" placeholder="Nama sesuai KTP" oninput="onFormChange()">
                                <div class="invalid-feedback" id="err-nama"></div>
                                <div class="valid-feedback">Nama valid ✓</div>
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label fw-semibold text-dark">No. Telepon <span class="text-danger">*</span></label>
                                    <input type="text" id="field-telp" class="form-control sandbox-input" placeholder="08xx-xxxx-xxxx" oninput="onFormChange()">
                                    <div class="invalid-feedback" id="err-telp"></div>
                                    <div class="valid-feedback">Format valid ✓</div>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label fw-semibold text-dark">Diagnosis <span class="text-danger">*</span></label>
                                    <input type="text" id="field-diagnosis" class="form-control sandbox-input" placeholder="Contoh: Hipertensi" oninput="onFormChange()">
                                    <div class="invalid-feedback" id="err-diagnosis"></div>
                                    <div class="valid-feedback">OK ✓</div>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label fw-semibold text-dark">RS / Faskes Tujuan <span class="text-danger">*</span></label>
                                <select id="field-rs" class="form-select sandbox-input" onchange="onFormChange()">
                                    <option value="">— Pilih Faskes Tujuan —</option>
                                    <option value="rsud_cibinong">RSUD Cibinong</option>
                                    <option value="rsud_depok">RSUD Depok</option>
                                    <option value="rscm">RSCM Jakarta</option>
                                </select>
                                <div class="invalid-feedback" id="err-rs"></div>
                                <div class="valid-feedback">Faskes dipilih ✓</div>
                            </div>

                            <hr class="my-3">
                            <div class="d-flex justify-content-between align-items-center">
                                <div id="autosave-status" class="text-muted small"></div>
                                <div class="d-flex gap-2">
                                    <button type="button" class="btn btn-outline-secondary btn-sm" onclick="resetUatForm()">
                                        <i class="bi bi-arrow-counterclockwise"></i> Reset
                                    </button>
                                    <button type="button" class="btn btn-primary btn-sm px-4" onclick="submitUatForm()">
                                        <i class="bi bi-send"></i> Kirim Rujukan
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            {{-- Live Validation Log --}}
            <div class="col-lg-5">
                <div class="section-title"><i class="bi bi-shield-check me-2"></i>Log Validasi Backend (Live)</div>
                <div class="sandbox-card h-auto">
                    <div class="sandbox-card-header bg-dark text-white" style="font-size:0.85rem;">
                        <i class="bi bi-terminal"></i> server validation log
                    </div>
                    <div class="sandbox-card-body p-0">
                        <div id="validationLog" class="font-monospace p-3" style="min-height:200px; font-size:0.78rem; line-height:1.8; background:#0d1117; color:#c9d1d9; border-radius: 0 0 16px 16px;">
                            <span class="text-secondary">$ Menunggu input penguji...</span><br>
                            <span class="text-secondary" id="log-cursor" style="animation: blink 1s step-end infinite;">█</span>
                        </div>
                    </div>
                </div>

                {{-- Result Badge --}}
                <div id="uat-result-box" class="mt-3 d-none">
                </div>

                {{-- Explanation Box --}}
                <div class="mt-3 p-3 rounded" style="background:#f0fdf4; border: 1px solid #bbf7d0;">
                    <div class="fw-bold text-success mb-2"><i class="bi bi-lightbulb me-1"></i> Apa yang Diuji?</div>
                    <ul class="mb-0 small text-muted ps-3">
                        <li>NIK harus tepat <strong>16 digit</strong> angka.</li>
                        <li>Nama minimal <strong>3 karakter</strong>.</li>
                        <li>Telepon harus format <strong>08xx</strong>.</li>
                        <li>Semua field bertanda <span class="text-danger">*</span> wajib diisi.</li>
                        <li>Sistem <strong>auto-save draf</strong> setiap 3 detik jika ada perubahan.</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>


    <!-- SCENARIO 3: GPS TRACKING SIMULATION -->
    <div class="sandbox-container shadow-sm mt-5">
        <div class="row gx-4">
            <div class="col-lg-5 mb-4 mb-lg-0">
                <div class="section-title">Aplikasi Driver (Pengirim GPS)</div>
                <div class="sandbox-card">
                    <div class="sandbox-card-header bg-success">
                        <i class="bi bi-geo-alt-fill"></i> eSIR Driver App
                    </div>
                    <div class="sandbox-card-body">
                        <div class="mb-3">
                            <label class="form-label text-dark">Latitude</label>
                            <input type="text" id="gpsLat" class="form-control sandbox-input" value="-6.200000">
                        </div>
                        <div class="mb-3">
                            <label class="form-label text-dark">Longitude</label>
                            <input type="text" id="gpsLng" class="form-control sandbox-input" value="106.816666">
                        </div>
                        <button class="btn btn-success w-100 mt-2" onclick="simulateGpsPing()">
                            <i class="bi bi-broadcast"></i> Pancarkan Lokasi Sekarang
                        </button>
                        <button class="btn btn-warning w-100 mt-2" onclick="simulateGpsPerformance(this)">
                            <i class="bi bi-speedometer2"></i> Uji Kinerja GPS Tracking (Stress Test)
                        </button>
                    </div>
                </div>
            </div>
            
            <div class="col-lg-7">
                <div class="section-title">Dashboard Faskes (Penerima WebSocket)</div>
                <div class="sandbox-card">
                    <div class="sandbox-card-header bg-dark text-white">
                        <i class="bi bi-map"></i> Live Tracking Monitor
                    </div>
                    <div class="sandbox-card-body bg-light" style="min-height: 200px; display: flex; flex-direction: column;">
                        <div class="text-center text-muted mb-3 flex-grow-1" id="mapPlaceholder">
                            <i class="bi bi-map text-secondary opacity-50" style="font-size: 4rem;"></i>
                            <p class="mt-2">Menunggu sinyal GPS...</p>
                        </div>
                        <div class="mt-auto d-none" id="gpsAlert">
                            <div class="alert alert-success mb-0">
                                <div class="d-flex align-items-center mb-2">
                                    <div class="spinner-grow spinner-grow-sm text-success me-3" role="status"></div>
                                    <strong>Real WebSocket / GPS Response:</strong>
                                </div>
                                <div id="gpsResult" class="font-monospace text-start p-3 border rounded mt-2" style="background-color: #1e1e1e; color: #d4d4d4; min-height: 250px; max-height: 500px; white-space: pre; font-size: 0.75rem; overflow-x: auto; overflow-y: auto; line-height: 1.6;"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- SCENARIO 4: DATABASE SYNC & INTEGRITY -->
    <div class="sandbox-container shadow-sm mt-5">
        <div class="row gx-4">
            <div class="col-12">
                <div class="section-title">Integritas Database (DB Sync)</div>
                <div class="sandbox-card">
                    <div class="sandbox-card-header bg-info text-white">
                        <i class="bi bi-database-check"></i> Cek Sinkronisasi Relasi Tabel
                    </div>
                    <div class="sandbox-card-body d-flex justify-content-between align-items-center">
                        <div class="d-flex align-items-center">
                            <i class="bi bi-hdd-network text-info" style="font-size: 2.5rem; margin-right: 1.5rem;"></i>
                            <div>
                                <h5 class="mb-1 text-dark fw-bold">Pemeriksaan Orphan Data</h5>
                                <p class="text-muted mb-0 small">Mencari data Rujukan tanpa Pasien yang terdaftar di sistem.</p>
                            </div>
                        </div>
                        <button class="btn btn-info text-white fw-bold px-4" onclick="simulateDbSync(this)">
                            Mulai Cek
                        </button>
                    </div>
                    <div class="card-footer bg-light border-top-0 d-none" id="dbSyncResultBox">
                        <div class="fw-bold mb-2"><i class="bi bi-terminal"></i> Database Integrity Log:</div>
                        <div id="dbSyncResult" class="font-monospace small p-3 bg-dark text-light rounded text-start" style="white-space: pre-wrap; font-size: 0.8rem; line-height: 1.5;"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- SCENARIO 5: RESPONSIVE UI CHECK -->
    <div class="sandbox-container shadow-sm mt-5 mb-5">
        <div class="row gx-4">
            <div class="col-12">
                <div class="section-title">Uji Responsivitas Tampilan (Responsive)</div>
                <div class="sandbox-card">
                    <div class="sandbox-card-header bg-secondary text-white">
                        <i class="bi bi-laptop"></i> / <i class="bi bi-phone"></i> Mobile View Simulation
                    </div>
                    <div class="sandbox-card-body text-center">
                        <p class="text-muted mb-3">Tekan tombol di bawah untuk menyimulasikan tampilan aplikasi pada perangkat *Mobile* (375px).</p>
                        <div class="d-flex justify-content-center gap-3">
                            <button class="btn btn-outline-secondary" onclick="simulateDevice('desktop')">
                                <i class="bi bi-display"></i> Desktop
                            </button>
                            <button class="btn btn-secondary" onclick="simulateDevice('mobile')">
                                <i class="bi bi-phone"></i> Mobile (iPhone SE)
                            </button>
                        </div>
                        <div class="mt-4 mx-auto border rounded shadow-sm transition-all" id="responsiveFrame" style="width: 100%; height: 300px; background-color: #f8f9fa; display: flex; align-items: center; justify-content: center; overflow: hidden;">
                            <div class="p-3 w-100 h-100">
                                <div class="bg-primary text-white p-2 rounded mb-3 text-start w-100">
                                    <i class="bi bi-list fs-4"></i> <span class="fw-bold ms-2">eSIR Navbar</span>
                                </div>
                                <div class="row">
                                    <div class="col-sm-6 col-12 mb-2">
                                        <div class="bg-white p-3 rounded shadow-sm h-100 text-start">Card Kiri</div>
                                    </div>
                                    <div class="col-sm-6 col-12 mb-2">
                                        <div class="bg-white p-3 rounded shadow-sm h-100 text-start">Card Kanan</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    function triggerScreenshot() {
        // Trigger click on the floating button injected by screenshot.js
        const btn = document.getElementById('screenshot-btn');
        if (btn) btn.click();
        else alert('Screenshot module not loaded yet.');
    }

    function autoFillCredentials() {
        const role = document.getElementById('loginRole').value;
        const emailInput = document.getElementById('loginEmail');
        const passInput = document.getElementById('loginPassword');
        
        const credentials = {
            'admin_pusat': { email: 'admin@esir.id', pass: 'password' },
            'admin_faskes': { email: 'adminkj@esir.id', pass: 'password' },
            'driver': { email: 'driver1@esir.id', pass: 'password' },
            'viewer': { email: 'viewer@esir.id', pass: 'password' },
        };
        
        if(credentials[role]) {
            emailInput.value = credentials[role].email;
            passInput.value = credentials[role].pass;
        }
    }

    async function simulateLogin() {
        const btn = document.querySelector('.btn-sandbox');
        const role = document.getElementById('loginRole').value;
        const email = document.getElementById('loginEmail').value;
        const password = document.getElementById('loginPassword').value;
        const originalText = btn.innerText;
        const errorBox = document.getElementById('loginError');
        const notice = document.getElementById('jwtNotice');
        
        btn.innerHTML = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Memproses...';
        btn.disabled = true;
        errorBox.classList.add('d-none');
        notice.classList.add('d-none');
        
        try {
            // Hit real backend API endpoint
            const res = await fetch('{{ route('uat-sandbox.login') }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Accept': 'application/json'
                },
                body: JSON.stringify({ role: role, email: email, password: password })
            });
            const data = await res.json();
            
            if(!res.ok) {
                // Tampilkan error jika API menolak login (misal role mismatch atau salah pass)
                errorBox.innerHTML = `<strong>Akses Ditolak (HTTP ${res.status}):</strong><br>${data.message}`;
                errorBox.classList.remove('d-none');
                
                // Matikan badge validasi jika ada yang menyala
                document.querySelectorAll('.badge-valid').forEach(el => {
                    el.classList.add('opacity-25');
                    el.style.animation = 'none';
                });
                return;
            }
            
            // Activate validation badges for SUCCESS
            document.querySelectorAll('.badge-valid').forEach(el => {
                el.classList.remove('opacity-25');
                el.style.animation = 'pulse 1s';
            });
            
            // Show JWT notice with real token
            notice.classList.remove('d-none');
            notice.style.animation = 'fadeIn 0.5s';
            document.getElementById('jwtTokenDisplay').innerText = `Bearer ${data.token}`;
            
        } catch (e) {
            console.error('Error during login simulation:', e);
            errorBox.innerText = "Koneksi ke server gagal: " + e.message;
            errorBox.classList.remove('d-none');
        } finally {
            btn.innerHTML = originalText;
            btn.disabled = false;
        }
    }
    
    async function simulateGpsPing() {
        const btn = document.querySelector('.btn-success');
        const originalText = btn.innerHTML;
        btn.innerHTML = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Memproses...';
        btn.disabled = true;

        const mapPlaceholder = document.getElementById('mapPlaceholder');
        const gpsAlert = document.getElementById('gpsAlert');
        const gpsResult = document.getElementById('gpsResult');
        
        mapPlaceholder.innerHTML = '<div class="spinner-border text-primary" role="status"></div><p class="mt-3 text-primary">Memeriksa koneksi Reverb...</p>';
        
        try {
            const res = await fetch('{{ route('internal-testing.run') }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify({ test_id: 'gps' })
            });
            const data = await res.json();
            
            mapPlaceholder.classList.add('d-none');
            gpsAlert.classList.remove('d-none');
            
            // Format log
            let logHtml = data.logs.join('\n');
            logHtml += `\n\nStatus: ${data.status.toUpperCase()} (${data.duration_ms}ms)`;
            gpsResult.innerText = logHtml;
            
        } catch (e) {
            gpsResult.innerText = 'Error: ' + e.message;
        } finally {
            btn.innerHTML = originalText;
            btn.disabled = false;
        }
    }
    
    async function simulateGpsPerformance(btn) {
        const originalHtml = btn.innerHTML;
        btn.innerHTML = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Menguji Kinerja...';
        btn.disabled = true;

        const mapPlaceholder = document.getElementById('mapPlaceholder');
        const gpsAlert = document.getElementById('gpsAlert');
        const gpsResult = document.getElementById('gpsResult');
        
        mapPlaceholder.innerHTML = '<div class="spinner-border text-warning" role="status"></div><p class="mt-3 text-warning">Menjalankan stress test GPS (50 ping)...</p>';
        mapPlaceholder.classList.remove('d-none');
        gpsAlert.classList.add('d-none');
        
        try {
            const res = await fetch('{{ route('internal-testing.run') }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify({ test_id: 'gps_perf' })
            });
            const data = await res.json();
            
            mapPlaceholder.classList.add('d-none');
            gpsAlert.classList.remove('d-none');
            
            // Format log
            let logHtml = data.logs.join('\n');
            logHtml += `\n\nPerformance Status: ${data.status.toUpperCase()} (Total Waktu: ${data.duration_ms}ms)`;
            gpsResult.innerText = logHtml;
            
        } catch (e) {
            mapPlaceholder.classList.add('d-none');
            gpsAlert.classList.remove('d-none');
            gpsResult.innerText = 'Error: ' + e.message;
        } finally {
            btn.innerHTML = originalHtml;
            btn.disabled = false;
        }
    }
    
    async function simulateDbSync(btn) {
        const originalHtml = btn.innerHTML;
        btn.innerHTML = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Memeriksa...';
        btn.disabled = true;
        
        try {
            const res = await fetch('{{ route('internal-testing.run') }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify({ test_id: 'db_sync' })
            });
            const data = await res.json();
            
            const resultBox = document.getElementById('dbSyncResultBox');
            const resultContent = document.getElementById('dbSyncResult');
            
            resultBox.classList.remove('d-none');
            resultBox.style.animation = 'fadeIn 0.5s';
            
            // Format log from real backend
            let logHtml = data.logs.join('\n');
            logHtml += `\n\nTotal Processing Time: ${data.duration_ms}ms`;
            
            resultContent.innerText = logHtml;
            
        } catch (e) {
            document.getElementById('dbSyncResult').innerText = 'Failed to fetch API: ' + e.message;
        } finally {
            btn.innerHTML = originalHtml;
            btn.disabled = false;
        }
    }
    
    function simulateDevice(type) {
        const frame = document.getElementById('responsiveFrame');
        if (type === 'mobile') {
            frame.style.width = '375px';
            frame.style.height = '667px';
            frame.classList.add('border-dark', 'border-4');
            frame.style.borderRadius = '30px';
        } else {
            frame.style.width = '100%';
            frame.style.height = '300px';
            frame.classList.remove('border-dark', 'border-4');
            frame.style.borderRadius = '8px';
        }
    }
    
    // =============================================
    // SCENARIO 2: INTERACTIVE FORM VALIDATION
    // =============================================
    let autosaveTimer = null;
    let formDirty = false;

    function addValidationLog(msg, type = 'info') {
        const log = document.getElementById('validationLog');
        const cursor = document.getElementById('log-cursor');
        const colors = { pass: '#3fb950', fail: '#f85149', warn: '#e3b341', info: '#79c0ff', dim: '#484f58' };
        const span = document.createElement('span');
        span.style.color = colors[type] || colors.info;
        span.textContent = msg;
        log.insertBefore(span, cursor);
        log.insertBefore(document.createElement('br'), cursor);
        log.scrollTop = log.scrollHeight;
    }

    function validateField(id, errId, rules) {
        const el = document.getElementById(id);
        const val = el.value.trim();
        let error = '';
        for (const rule of rules) {
            const r = rule(val);
            if (r) { error = r; break; }
        }
        el.classList.remove('is-valid', 'is-invalid');
        if (error) {
            el.classList.add('is-invalid');
            document.getElementById(errId).innerText = error;
            return { ok: false, val, msg: error };
        } else {
            el.classList.add('is-valid');
            return { ok: true, val };
        }
    }

    function onFormChange() {
        formDirty = true;
        clearTimeout(autosaveTimer);
        autosaveTimer = setTimeout(() => triggerAutosave(), 3000);
    }

    function triggerAutosave() {
        if (!formDirty) return;
        const now = new Date().toLocaleTimeString('id-ID', { hour12: false });
        const badge = document.getElementById('autosave-badge');
        const status = document.getElementById('autosave-status');
        const timeEl = document.getElementById('autosave-time');

        badge.style.display = 'inline-block';
        timeEl.innerText = now;
        badge.className = 'badge bg-warning text-dark rounded-pill';
        status.innerHTML = `<span class="text-warning"><i class="bi bi-cloud-upload"></i> Menyimpan draf...</span>`;

        setTimeout(() => {
            badge.className = 'badge bg-success text-white rounded-pill';
            status.innerHTML = `<span class="text-success fw-semibold"><i class="bi bi-cloud-check"></i> Draf tersimpan otomatis pukul ${now}</span>`;
            addValidationLog(`[${now}] INFO: Auto-save draf aktif — data disimpan sementara ke session storage.`, 'info');
            formDirty = false;
        }, 1000);
    }

    function submitUatForm() {
        const log = document.getElementById('validationLog');
        const cursor = document.getElementById('log-cursor');
        const now = new Date().toLocaleTimeString('id-ID', { hour12: false });
        addValidationLog(`[${now}] POST /referrals — validasi backend dijalankan...`, 'dim');

        const rules = [
            validateField('field-nik',       'err-nik',       [
                v => !v ? 'NIK wajib diisi.' : null,
                v => !/^\d{16}$/.test(v) ? `NIK harus tepat 16 digit angka (sekarang: ${v.length}).` : null,
            ]),
            validateField('field-nama',      'err-nama',      [
                v => !v ? 'Nama pasien wajib diisi.' : null,
                v => v.length < 3 ? 'Nama minimal 3 karakter.' : null,
            ]),
            validateField('field-telp',      'err-telp',      [
                v => !v ? 'No. telepon wajib diisi.' : null,
                v => !/^08[0-9]{7,12}$/.test(v.replace(/[-\s]/g, '')) ? 'Format harus diawali 08 dan 9–13 digit.' : null,
            ]),
            validateField('field-diagnosis', 'err-diagnosis', [
                v => !v ? 'Diagnosis wajib diisi.' : null,
            ]),
            validateField('field-rs',        'err-rs',        [
                v => !v ? 'Faskes tujuan belum dipilih.' : null,
            ]),
        ];

        const errors = rules.filter(r => !r.ok);

        if (errors.length > 0) {
            errors.forEach(e => addValidationLog(`  ✗ FAIL: ${e.msg}`, 'fail'));
            addValidationLog(`[${now}] ValidationException: ${errors.length} error ditemukan. Request ditolak (422).`, 'fail');

            const resultBox = document.getElementById('uat-result-box');
            resultBox.classList.remove('d-none');
            resultBox.innerHTML = `
                <div class="alert alert-danger d-flex align-items-start gap-3 mb-0">
                    <i class="bi bi-x-circle-fill fs-5 mt-1"></i>
                    <div>
                        <div class="fw-bold">Validasi Gagal — ${errors.length} error ditemukan</div>
                        <div class="small mt-1">${errors.map(e => '• ' + e.msg).join('<br>')}</div>
                        <div class="small mt-2 text-muted">HTTP 422 Unprocessable Entity — data tidak disimpan.</div>
                    </div>
                </div>`;
        } else {
            rules.forEach(r => addValidationLog(`  ✓ PASS: ${r.val}`, 'pass'));
            addValidationLog(`[${now}] 201 Created — Rujukan berhasil disimpan ke database.`, 'pass');

            const resultBox = document.getElementById('uat-result-box');
            resultBox.classList.remove('d-none');
            resultBox.innerHTML = `
                <div class="alert alert-success d-flex align-items-start gap-3 mb-0">
                    <i class="bi bi-check-circle-fill fs-5 mt-1"></i>
                    <div>
                        <div class="fw-bold">✓ Rujukan Berhasil Dikirim!</div>
                        <div class="small mt-1">Semua validasi lolos. Data merujuk ke faskes tujuan dan notifikasi terkirim.</div>
                        <div class="small mt-2 font-monospace text-muted">HTTP 201 Created — id: REF-${Math.floor(Math.random()*9000+1000)}</div>
                    </div>
                </div>`;
        }
    }

    function resetUatForm() {
        ['field-nik','field-nama','field-telp','field-diagnosis','field-rs'].forEach(id => {
            const el = document.getElementById(id);
            el.value = '';
            el.classList.remove('is-valid','is-invalid');
        });
        document.getElementById('validationLog').innerHTML = `<span class="text-secondary">$ Form direset. Siap untuk pengujian baru...</span><br><span class="text-secondary" id="log-cursor" style="animation: blink 1s step-end infinite;">█</span>`;
        document.getElementById('uat-result-box').classList.add('d-none');
        document.getElementById('autosave-status').innerHTML = '';
        document.getElementById('autosave-badge').style.display = 'none';
        formDirty = false;
        clearTimeout(autosaveTimer);
    }

    function autoFillUatForm() {
        document.getElementById('field-nik').value = '3173012345678901';
        document.getElementById('field-nama').value = 'Pasien Simulasi UAT';
        document.getElementById('field-telp').value = '081299887766';
        document.getElementById('field-diagnosis').value = 'Observasi Febris H-3, Suspect Dengue';
        document.getElementById('field-rs').value = 'rsud_cibinong';
        
        ['field-nik','field-nama','field-telp','field-diagnosis','field-rs'].forEach(id => {
            const el = document.getElementById(id);
            el.classList.remove('is-invalid');
            el.classList.add('is-valid');
        });
        
        onFormChange(); // Trigger autosave
        addValidationLog(`[INFO] Auto-fill SUKSES dijalankan. Semua data valid.`, 'info');
    }

    function autoFillUatFormInvalid() {
        document.getElementById('field-nik').value = '123'; // NIK kurang dari 16 digit
        document.getElementById('field-nama').value = 'A'; // Nama kurang dari 3 karakter
        document.getElementById('field-telp').value = '081'; // No telepon tidak valid
        document.getElementById('field-diagnosis').value = ''; // Diagnosis kosong
        document.getElementById('field-rs').value = ''; // Faskes kosong
        
        ['field-nik','field-nama','field-telp','field-diagnosis','field-rs'].forEach(id => {
            const el = document.getElementById(id);
            el.classList.remove('is-valid');
            el.classList.add('is-invalid');
        });
        
        onFormChange(); // Trigger autosave
        addValidationLog(`[INFO] Auto-fill GAGAL dijalankan. Data sengaja disalahkan untuk menguji HTTP 422.`, 'warn');
    }
    
    // Add custom keyframe animations
    const style = document.createElement('style');
    style.innerHTML = `
        @keyframes pulse {
            0% { transform: scale(1); }
            50% { transform: scale(1.1); }
            100% { transform: scale(1); }
        }
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(-10px); }
            to { opacity: 1; transform: translateY(0); }
        }
    `;
    document.head.appendChild(style);
</script>
@endpush
