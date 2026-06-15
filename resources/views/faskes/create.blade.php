@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-md-7">
            <!-- Breadcrumb -->
            <nav aria-label="breadcrumb" class="mb-4">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('faskes.index') }}" class="text-decoration-none text-primary">Manajemen Faskes</a></li>
                    <li class="breadcrumb-item active fw-medium" aria-current="page">Tambah Faskes</li>
                </ol>
            </nav>

            <div class="card border-0 shadow-lg rounded-4 overflow-hidden animate__animated animate__fadeInUp">
                <!-- Header with Gradient -->
                <div class="card-header bg-primary text-white p-4 border-0" style="background: linear-gradient(135deg, #4361ee 0%, #3a0ca3 100%);">
                    <div class="d-flex align-items-center">
                        <div class="bg-white bg-opacity-25 rounded-circle p-2 me-3">
                            <i class="bi bi-building-add fs-4"></i>
                        </div>
                        <div>
                            <h4 class="mb-0 fw-bold">Tambah Faskes Baru</h4>
                            <p class="mb-0 small opacity-75">Daftarkan fasilitas kesehatan baru ke dalam jaringan eSIR.</p>
                        </div>
                    </div>
                </div>

                <div class="card-body p-4 p-lg-5">
                    <form action="{{ route('faskes.store') }}" method="POST">
                        @csrf
                        
                        <div class="mb-4">
                            <label class="form-label fw-bold text-dark small text-uppercase ls-1">Nama Fasilitas Kesehatan</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-0 text-muted px-3"><i class="bi bi-building"></i></span>
                                <input type="text" name="name" class="form-control border-0 bg-light py-3 ps-2 @error('name') is-invalid @enderror" 
                                    placeholder="Masukkan nama lengkap faskes" value="{{ old('name') }}" required>
                            </div>
                            @error('name')
                                <div class="invalid-feedback d-block mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label class="form-label fw-bold text-dark small text-uppercase ls-1">Tipe Faskes</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-0 text-muted px-3"><i class="bi bi-tags"></i></span>
                                <select name="type" class="form-select border-0 bg-light py-3 ps-2 @error('type') is-invalid @enderror" required>
                                    <option value="" disabled selected>Pilih tipe faskes...</option>
                                    <option value="puskesmas">Puskesmas</option>
                                    <option value="rs_tipe_a">Rumah Sakit Tipe A</option>
                                    <option value="rs_tipe_b">Rumah Sakit Tipe B</option>
                                    <option value="rs_tipe_c">Rumah Sakit Tipe C</option>
                                    <option value="rs_tipe_d">Rumah Sakit Tipe D</option>
                                    <option value="klinik">Klinik Pratama/Utama</option>
                                </select>
                            </div>
                            @error('type')
                                <div class="invalid-feedback d-block mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label class="form-label fw-bold text-dark small text-uppercase ls-1">Alamat Lengkap</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-0 text-muted px-3"><i class="bi bi-geo-alt"></i></span>
                                <textarea name="address" class="form-control border-0 bg-light py-3 ps-2 @error('address') is-invalid @enderror" 
                                    placeholder="Alamat jalan, nomor, kecamatan, kabupaten/kota" rows="3" required>{{ old('address') }}</textarea>
                            </div>
                            @error('address')
                                <div class="invalid-feedback d-block mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="row g-4 mb-5">
                            <div class="col-md-6">
                                <label class="form-label fw-bold text-dark small text-uppercase ls-1">Latitude</label>
                                <div class="input-group shadow-none">
                                    <span class="input-group-text bg-light border-0 text-muted px-3"><i class="bi bi-map"></i></span>
                                    <input type="text" name="latitude" class="form-control border-0 bg-light py-3 ps-2 @error('latitude') is-invalid @enderror" 
                                        placeholder="-6.2088" value="{{ old('latitude') }}">
                                </div>
                                @error('latitude')
                                    <div class="invalid-feedback d-block mt-1">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-bold text-dark small text-uppercase ls-1">Longitude</label>
                                <div class="input-group shadow-none">
                                    <span class="input-group-text bg-light border-0 text-muted px-3"><i class="bi bi-map-fill"></i></span>
                                    <input type="text" name="longitude" class="form-control border-0 bg-light py-3 ps-2 @error('longitude') is-invalid @enderror" 
                                        placeholder="106.8456" value="{{ old('longitude') }}">
                                </div>
                                @error('longitude')
                                    <div class="invalid-feedback d-block mt-1">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Action Buttons -->
                        <div class="d-flex flex-column flex-md-row justify-content-between align-items-center bg-light p-3 rounded-4 mt-2">
                            <a href="{{ route('faskes.index') }}" class="btn btn-link text-decoration-none text-muted fw-bold order-2 order-md-1 mt-3 mt-md-0">
                                <i class="bi bi-arrow-left me-1"></i> Batal Kembali
                            </a>
                            <button type="submit" class="btn btn-primary px-5 py-3 rounded-pill shadow fw-bold order-1 order-md-2" style="background: linear-gradient(135deg, #4361ee 0%, #3f37c9 100%); border: none;">
                                <i class="bi bi-save2 me-2"></i>Simpan Faskes
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
