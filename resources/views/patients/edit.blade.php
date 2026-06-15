@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-md-7">
            <!-- Breadcrumb -->
            <nav aria-label="breadcrumb" class="mb-4">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('patients.index') }}" class="text-decoration-none text-primary">Manajemen Pasien</a></li>
                    <li class="breadcrumb-item active fw-medium" aria-current="page">Edit Pasien</li>
                </ol>
            </nav>

            <div class="card border-0 shadow-lg rounded-4 overflow-hidden animate__animated animate__fadeInUp">
                <!-- Header with Gradient (Indigo/Purple for Edit) -->
                <div class="card-header bg-primary text-white p-4 border-0" style="background: linear-gradient(135deg, #6366f1 0%, #4338ca 100%);">
                    <div class="d-flex align-items-center">
                        <div class="bg-white bg-opacity-25 rounded-circle p-2 me-3">
                            <i class="bi bi-pencil-square fs-4"></i>
                        </div>
                        <div>
                            <h4 class="mb-0 fw-bold">Perbarui Data Pasien</h4>
                            <p class="mb-0 small opacity-75">Update informasi untuk <span class="fw-bold">{{ $patient->name }}</span> (NIK: {{ $patient->nik }}).</p>
                        </div>
                    </div>
                </div>

                <div class="card-body p-4 p-lg-5">
                    <form action="{{ route('patients.update', $patient) }}" method="POST">
                        @csrf
                        @method('PUT')
                        
                        <div class="mb-4">
                            <label class="form-label fw-bold text-dark small text-uppercase ls-1">Nomor Induk Kependudukan (NIK)</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-0 text-muted px-3"><i class="bi bi-card-heading"></i></span>
                                <input type="text" name="nik" class="form-control border-0 bg-light py-3 ps-2 @error('nik') is-invalid @enderror" 
                                    placeholder="16 digit NIK" value="{{ old('nik', $patient->nik) }}" maxlength="16" required>
                            </div>
                            @error('nik')
                                <div class="invalid-feedback d-block mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label class="form-label fw-bold text-dark small text-uppercase ls-1">Nama Lengkap Pasien</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-0 text-muted px-3"><i class="bi bi-person"></i></span>
                                <input type="text" name="name" class="form-control border-0 bg-light py-3 ps-2 @error('name') is-invalid @enderror" 
                                    placeholder="Masukkan nama lengkap" value="{{ old('name', $patient->name) }}" required>
                            </div>
                            @error('name')
                                <div class="invalid-feedback d-block mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="row g-4 mb-4">
                            <div class="col-md-6">
                                <label class="form-label fw-bold text-dark small text-uppercase ls-1">Tanggal Lahir</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-0 text-muted px-3"><i class="bi bi-calendar3"></i></span>
                                    <input type="date" name="dob" class="form-control border-0 bg-light py-3 ps-2 @error('dob') is-invalid @enderror" value="{{ old('dob', $patient->dob) }}">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-bold text-dark small text-uppercase ls-1">Jenis Kelamin</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-0 text-muted px-3"><i class="bi bi-gender-ambiguous"></i></span>
                                    <select name="gender" class="form-select border-0 bg-light py-3 ps-2 @error('gender') is-invalid @enderror">
                                        <option value="L" {{ old('gender', $patient->gender) == 'L' ? 'selected' : '' }}>Laki-Laki</option>
                                        <option value="P" {{ old('gender', $patient->gender) == 'P' ? 'selected' : '' }}>Perempuan</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="mb-4">
                            <label class="form-label fw-bold text-dark small text-uppercase ls-1">Nomor Kontak (HP/WhatsApp)</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-0 text-muted px-3"><i class="bi bi-telephone"></i></span>
                                <input type="text" name="contact" class="form-control border-0 bg-light py-3 ps-2 @error('contact') is-invalid @enderror" 
                                    placeholder="Contoh: 08123456789" value="{{ old('contact', $patient->contact) }}">
                            </div>
                        </div>

                        <div class="mb-5">
                            <label class="form-label fw-bold text-dark small text-uppercase ls-1">Alamat Domisili</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-0 text-muted px-3"><i class="bi bi-geo-alt"></i></span>
                                <textarea name="address" class="form-control border-0 bg-light py-3 ps-2 @error('address') is-invalid @enderror" 
                                    placeholder="Alamat lengkap" rows="3">{{ old('address', $patient->address) }}</textarea>
                            </div>
                        </div>

                        <!-- Action Buttons -->
                        <div class="d-flex flex-column flex-md-row justify-content-between align-items-center bg-light p-3 rounded-4 mt-2">
                            <a href="{{ route('patients.index') }}" class="btn btn-link text-decoration-none text-muted fw-bold order-2 order-md-1 mt-3 mt-md-0">
                                <i class="bi bi-arrow-left me-1"></i> Batal Kembali
                            </a>
                            <button type="submit" class="btn btn-primary px-5 py-3 rounded-pill shadow fw-bold order-1 order-md-2" style="background: linear-gradient(135deg, #6366f1 0%, #4f46e5 100%); border: none;">
                                <i class="bi bi-check-all me-2"></i>Simpan Perubahan
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
