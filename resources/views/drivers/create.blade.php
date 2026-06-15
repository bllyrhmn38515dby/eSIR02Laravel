@extends('layouts.app')

@section('content')
<div class="container-fluid py-5 px-lg-5" style="background: linear-gradient(135deg, #f1f5f9 0%, #e2e8f0 100%); min-height: 90vh;">
    <div class="row justify-content-center">
        <div class="col-lg-6">
            <!-- Header -->
            <div class="d-flex align-items-center mb-4 animate__animated animate__fadeIn">
                <a href="{{ route('drivers.index') }}" class="btn btn-white rounded-circle shadow-sm me-3" style="width: 45px; height: 45px; display: flex; align-items: center; justify-content: center;">
                    <i class="bi bi-arrow-left fs-5"></i>
                </a>
                <div>
                    <h2 class="fw-bold mb-0">Registrasi Sopir Baru</h2>
                    <p class="text-muted mb-0">Daftarkan personel sopir ambulans baru ke faskes terkait.</p>
                </div>
            </div>

            <div class="card border-0 shadow-lg rounded-4 overflow-hidden animate__animated animate__zoomIn">
                <div class="card-header bg-primary py-4 px-4 border-0" style="background: linear-gradient(135deg, #3a0ca3 0%, #4361ee 100%);">
                    <div class="d-flex align-items-center">
                        <div class="bg-white bg-opacity-20 p-2 rounded-circle me-3">
                            <i class="bi bi-person-plus text-white fs-4"></i>
                        </div>
                        <h5 class="mb-0 text-white fw-bold">Akun & Identitas Personel</h5>
                    </div>
                </div>
                <div class="card-body p-4 p-lg-5 bg-white">
                    <form action="{{ route('drivers.store') }}" method="POST">
                        @csrf
                        
                        <div class="mb-4">
                            <label class="form-label fw-bold text-dark mb-1">Nama Lengkap Personel</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0"><i class="bi bi-person text-muted"></i></span>
                                <input type="text" name="name" class="form-control bg-light border-start-0 py-3 @error('name') is-invalid @enderror" placeholder="Contoh: Budi Santoso" required value="{{ old('name') }}">
                            </div>
                            @error('name') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                        </div>

                        <div class="mb-4">
                            <label class="form-label fw-bold text-dark mb-1">Email Operasional</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0"><i class="bi bi-envelope text-muted"></i></span>
                                <input type="email" name="email" class="form-control bg-light border-start-0 py-3 @error('email') is-invalid @enderror" placeholder="budi@esir.id" required value="{{ old('email') }}">
                            </div>
                            @error('email') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                        </div>

                        <div class="row mb-4">
                            <div class="col-md-6">
                                <label class="form-label fw-bold text-dark mb-1">Password</label>
                                <input type="password" name="password" class="form-control bg-light py-3" required placeholder="Minimal 8 karakter">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-bold text-dark mb-1">Konfirmasi Password</label>
                                <input type="password" name="password_confirmation" class="form-control bg-light py-3" required placeholder="Ulangi password">
                            </div>
                        </div>

                        <div class="mb-5">
                            <label class="form-label fw-bold text-dark mb-1">Penugasan Faskes</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0"><i class="bi bi-building text-muted"></i></span>
                                <select name="faskes_id" class="form-select bg-light border-start-0 py-3 @error('faskes_id') is-invalid @enderror" required>
                                    <option value="" disabled selected>Pilih Faskes Lokasi Tugas...</option>
                                    @foreach($faskes as $f)
                                        <option value="{{ $f->id }}" {{ (old('faskes_id') == $f->id || (auth()->user()->role !== 'admin_pusat' && auth()->user()->faskes_id == $f->id)) ? 'selected' : '' }}>
                                            {{ $f->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="pt-2">
                            <button type="submit" class="btn btn-primary w-100 py-3 rounded-pill fw-bold shadow-lg ls-1" style="background: linear-gradient(135deg, #3a0ca3 0%, #4361ee 100%); border: none;">
                                <i class="bi bi-check-circle-fill me-2"></i>Daftarkan Personel Driver
                            </button>
                            <p class="text-center mt-3 small text-muted">Role akan otomatis diset sebagai <strong>DRIVER</strong> di dalam sistem.</p>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .ls-1 { letter-spacing: 0.5px; }
    .btn-white { background: #fff; color: #64748b; border: 1px solid #e2e8f0; }
</style>
@endsection
