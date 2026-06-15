@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-md-7">
            <!-- Breadcrumb -->
            <nav aria-label="breadcrumb" class="mb-4">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('users.index') }}" class="text-decoration-none text-primary fw-medium">Manajemen Pengguna</a></li>
                    <li class="breadcrumb-item active fw-bold" aria-current="page">Perbarui Pengguna</li>
                </ol>
            </nav>

            <div class="card border-0 shadow-lg rounded-4 overflow-hidden animate__animated animate__fadeInUp">
                <!-- Header with Gradient (Indigo/Purple for Edit) -->
                <div class="card-header bg-primary text-white p-4 border-0" style="background: linear-gradient(135deg, #6366f1 0%, #4338ca 100%);">
                    <div class="d-flex align-items-center">
                        <div class="bg-white bg-opacity-25 rounded-circle p-2 me-3">
                            <i class="bi bi-person-check fs-4"></i>
                        </div>
                        <div>
                            <h4 class="mb-0 fw-bold">Perbarui Akun Pengguna</h4>
                            <p class="mb-0 small opacity-75">Update hak akses dan informasi untuk <span class="fw-bold">{{ $user->name }}</span>.</p>
                        </div>
                    </div>
                </div>

                <div class="card-body p-4 p-md-5">
                    <form action="{{ route('users.update', $user) }}" method="POST">
                        @csrf
                        @method('PUT')
                        
                        <div class="mb-4">
                            <label class="form-label fw-bold text-dark small text-uppercase ls-1">Nama Lengkap</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-0 text-muted px-3"><i class="bi bi-person"></i></span>
                                <input type="text" name="name" class="form-control border-0 bg-light py-3 ps-2 @error('name') is-invalid @enderror" 
                                    placeholder="Nama lengkap personel" value="{{ old('name', $user->name) }}" required>
                            </div>
                            @error('name')
                                <div class="invalid-feedback d-block mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label class="form-label fw-bold text-dark small text-uppercase ls-1">Alamat Email</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-0 text-muted px-3"><i class="bi bi-envelope"></i></span>
                                <input type="email" name="email" class="form-control border-0 bg-light py-3 ps-2 @error('email') is-invalid @enderror" 
                                    placeholder="email@esir.com" value="{{ old('email', $user->email) }}" required>
                            </div>
                            @error('email')
                                <div class="invalid-feedback d-block mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="row g-4 mb-4">
                            <div class="col-md-6">
                                <label class="form-label fw-bold text-dark small text-uppercase ls-1">Password Baru</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-0 text-muted px-3"><i class="bi bi-key"></i></span>
                                    <input type="password" name="password" class="form-control border-0 bg-light py-3 ps-2 @error('password') is-invalid @enderror" 
                                        placeholder="Kosongkan jika tidak diubah">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-bold text-dark small text-uppercase ls-1">Konfirmasi Password</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-0 text-muted px-3"><i class="bi bi-check2-circle"></i></span>
                                    <input type="password" name="password_confirmation" class="form-control border-0 bg-light py-3 ps-2" 
                                        placeholder="Ulangi password">
                                </div>
                            </div>
                            @error('password')
                                <div class="col-12"><div class="invalid-feedback d-block mt-n2">{{ $message }}</div></div>
                            @enderror
                        </div>

                        <div class="row g-4 mb-4">
                            <div class="col-md-6">
                                <label class="form-label fw-bold text-dark small text-uppercase ls-1">Peran / Role</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-0 text-muted px-3"><i class="bi bi-shield-lock"></i></span>
                                    <select name="role" id="roleSelect" class="form-select border-0 bg-light py-3 ps-2" required>
                                        <option value="admin_pusat" {{ old('role', $user->role) == 'admin_pusat' ? 'selected' : '' }}>Admin Pusat</option>
                                        <option value="admin_faskes" {{ old('role', $user->role) == 'admin_faskes' ? 'selected' : '' }}>Admin Faskes</option>
                                        <option value="driver" {{ old('role', $user->role) == 'driver' ? 'selected' : '' }}>Driver Ambulans</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-bold text-dark small text-uppercase ls-1">Status Akun</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-0 text-muted px-3"><i class="bi bi-toggle-on"></i></span>
                                    <select name="is_active" class="form-select border-0 bg-light py-3 ps-2" required>
                                        <option value="1" {{ old('is_active', $user->is_active) == 1 ? 'selected' : '' }}>Aktif</option>
                                        <option value="0" {{ old('is_active', $user->is_active) == 0 ? 'selected' : '' }}>Non-Aktif</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="mb-5" id="faskesWrapper">
                            <label class="form-label fw-bold text-dark small text-uppercase ls-1">Penempatan Faskes</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-0 text-muted px-3"><i class="bi bi-building"></i></span>
                                <select name="faskes_id" class="form-select border-0 bg-light py-3 ps-2 @error('faskes_id') is-invalid @enderror">
                                    <option value="">-- Pilih Fasilitas Kesehatan --</option>
                                    @foreach($faskes as $f)
                                    <option value="{{ $f->id }}" {{ old('faskes_id', $user->faskes_id) == $f->id ? 'selected' : '' }}>[{{ strtoupper($f->type) }}] {{ $f->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            @error('faskes_id')
                                <div class="invalid-feedback d-block mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-flex flex-column flex-md-row justify-content-between align-items-center bg-light p-3 rounded-4 mt-2">
                            <a href="{{ route('users.index') }}" class="btn btn-link text-decoration-none text-muted fw-bold order-2 order-md-1 mt-3 mt-md-0">
                                <i class="bi bi-arrow-left me-1"></i> Batal Kembali
                            </a>
                            <button type="submit" class="btn btn-primary px-5 py-3 rounded-pill shadow fw-bold order-1 order-md-2" style="background: linear-gradient(135deg, #6366f1 0%, #4338ca 100%); border: none;">
                                <i class="bi bi-check-all me-2"></i>Simpan Perubahan
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const roleSelect = document.getElementById('roleSelect');
        const faskesWrapper = document.getElementById('faskesWrapper');

        function toggleFaskes() {
            if (roleSelect.value === 'admin_pusat') {
                faskesWrapper.style.opacity = '0.5';
                faskesWrapper.style.pointerEvents = 'none';
                faskesWrapper.querySelector('select').value = '';
            } else {
                faskesWrapper.style.opacity = '1';
                faskesWrapper.style.pointerEvents = 'auto';
            }
        }

        roleSelect.addEventListener('change', toggleFaskes);
        toggleFaskes(); // Run on load
    });
</script>
@endpush
@endsection
