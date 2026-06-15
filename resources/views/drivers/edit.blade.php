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
                    <h2 class="fw-bold mb-0">Update Data Sopir</h2>
                    <p class="text-muted mb-0">Perbarui profil dan status penugasan <span class="badge bg-light text-dark border ms-1">{{ $driver->name }}</span></p>
                </div>
            </div>

            <div class="card border-0 shadow-lg rounded-4 overflow-hidden animate__animated animate__zoomIn">
                <div class="card-header bg-primary py-4 px-4 border-0" style="background: linear-gradient(135deg, #3a0ca3 0%, #4361ee 100%);">
                    <div class="d-flex align-items-center">
                        <div class="bg-white bg-opacity-20 p-2 rounded-circle me-3">
                            <i class="bi bi-person-gear text-white fs-4"></i>
                        </div>
                        <h5 class="mb-0 text-white fw-bold">Modifikasi Akun Personel</h5>
                    </div>
                </div>
                <div class="card-body p-4 p-lg-5 bg-white">
                    <form action="{{ route('drivers.update', $driver) }}" method="POST">
                        @csrf @method('PUT')
                        
                        <div class="mb-4">
                            <label class="form-label fw-bold text-dark mb-1">Nama Lengkap</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0"><i class="bi bi-person text-muted"></i></span>
                                <input type="text" name="name" class="form-control bg-light border-start-0 py-3 @error('name') is-invalid @enderror" value="{{ old('name', $driver->name) }}" required>
                            </div>
                            @error('name') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                        </div>

                        <div class="mb-4">
                            <label class="form-label fw-bold text-dark mb-1">Email</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0"><i class="bi bi-envelope text-muted"></i></span>
                                <input type="email" name="email" class="form-control bg-light border-start-0 py-3 @error('email') is-invalid @enderror" value="{{ old('email', $driver->email) }}" required>
                            </div>
                            @error('email') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                        </div>

                        <div class="mb-4 p-3 rounded-4 bg-light border border-dashed text-center">
                            <p class="small text-muted mb-0"><i class="bi bi-info-circle me-1"></i> Kosongkan password jika tidak ingin mengubahnya.</p>
                        </div>

                        <div class="row mb-4">
                            <div class="col-md-6">
                                <label class="form-label fw-bold text-dark mb-1">Password Baru</label>
                                <input type="password" name="password" class="form-control bg-white py-3 border" placeholder="Minimal 8 karakter">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-bold text-dark mb-1">Konfirmasi Password</label>
                                <input type="password" name="password_confirmation" class="form-control bg-white py-3 border" placeholder="Ulangi password">
                            </div>
                        </div>

                        <div class="mb-4">
                            <label class="form-label fw-bold text-dark mb-1">Penugasan Faskes</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0"><i class="bi bi-building text-muted"></i></span>
                                <select name="faskes_id" class="form-select bg-light border-start-0 py-3 @error('faskes_id') is-invalid @enderror" required>
                                    @foreach($faskes as $f)
                                        <option value="{{ $f->id }}" {{ (old('faskes_id', $driver->faskes_id) == $f->id) ? 'selected' : '' }}>
                                            {{ $f->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="mb-5">
                            <div class="form-check form-switch p-3 bg-light rounded-4 border">
                                <input class="form-check-input ms-0 me-3 mt-1" type="checkbox" role="switch" name="is_active" id="is_active" value="1" {{ $driver->is_active ? 'checked' : '' }}>
                                <label class="form-check-label fw-bold text-dark mt-1" for="is_active">Status Personel Aktif</label>
                                <p class="text-muted small mb-0 ms-1 d-block mt-2">Akun yang dinonaktifkan tidak akan bisa login atau menerima tugas rujukan.</p>
                            </div>
                        </div>

                        <div class="pt-2">
                            <button type="submit" class="btn btn-primary w-100 py-3 rounded-pill fw-bold shadow-lg ls-1" style="background: linear-gradient(135deg, #3a0ca3 0%, #4361ee 100%); border: none;">
                                <i class="bi bi-save me-2"></i>Simpan Perubahan Profil
                            </button>
                        </div>
                    </form>
                </div>
            </div>
            
            <div class="mt-4 text-center animate__animated animate__fadeIn animate__delay-1s">
                <form action="{{ route('drivers.destroy', $driver) }}" method="POST" onsubmit="return confirm('Hapus sopir ini secara permanen dari database?');">
                    @csrf @method('DELETE')
                    <button type="submit" class="btn btn-link text-danger text-decoration-none small fw-bold">
                        <i class="bi bi-trash3 me-1"></i> Hapus Akun Personel Selamanya
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<style>
    .ls-1 { letter-spacing: 0.5px; }
    .btn-white { background: #fff; color: #64748b; border: 1px solid #e2e8f0; }
    .form-check-input { width: 3em !important; height: 1.5em; cursor: pointer; }
    .form-check-input:checked { background-color: #22c55e !important; border-color: #22c55e !important; }
</style>
@endsection
