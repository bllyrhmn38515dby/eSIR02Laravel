@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-md-7">
            <!-- Breadcrumb for context -->
            <nav aria-label="breadcrumb" class="mb-4">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('bed-capacities.index') }}" class="text-decoration-none text-primary">Manajemen Kapasitas</a></li>
                    <li class="breadcrumb-item active fw-medium" aria-current="page">Tambah Ruangan</li>
                </ol>
            </nav>

            <div class="card border-0 shadow-lg rounded-4 overflow-hidden animate__animated animate__fadeInUp">
                <!-- Premium Header Gradient -->
                <div class="card-header bg-primary text-white p-4 border-0" style="background: linear-gradient(135deg, #4361ee 0%, #3a0ca3 100%);">
                    <div class="d-flex align-items-center">
                        <div class="bg-white bg-opacity-25 rounded-circle p-2 me-3">
                            <i class="bi bi-plus-circle-dotted fs-4"></i>
                        </div>
                        <div>
                            <h4 class="mb-0 fw-bold">Tambah Ruangan Baru</h4>
                            <p class="mb-0 small opacity-75">Lengkapi data kapasitas untuk sistem rujukan real-time.</p>
                        </div>
                    </div>
                </div>

                <div class="card-body p-4 p-lg-5">
                    <form action="{{ route('bed-capacities.store') }}" method="POST">
                        @csrf
                        
                        <!-- Room Name Input -->
                        <div class="mb-4">
                            <label class="form-label fw-bold text-dark small text-uppercase ls-1">Nama Ruangan / Kamar</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-0 text-muted px-3"><i class="bi bi-door-open"></i></span>
                                <input type="text" name="room_name" class="form-control border-0 bg-light py-3 ps-2 @error('room_name') is-invalid @enderror" 
                                    placeholder="Contoh: IGD, ICU, Mawar 1" value="{{ old('room_name') }}" required>
                            </div>
                            @error('room_name')
                                <div class="invalid-feedback d-block mt-1">{{ $message }}</div>
                            @enderror
                            <div class="form-text mt-2 small text-muted">Gunakan nama yang mudah dikenali oleh petugas medis lain.</div>
                        </div>

                        <!-- Capacity Grid -->
                        <div class="row g-4 mb-5">
                            <div class="col-md-6">
                                <label class="form-label fw-bold text-dark small text-uppercase ls-1">Kapasitas Total</label>
                                <div class="input-group shadow-none">
                                    <span class="input-group-text bg-light border-0 text-muted px-3"><i class="bi bi-bricks"></i></span>
                                    <input type="number" min="0" name="capacity" class="form-control border-0 bg-light py-3 ps-2 @error('capacity') is-invalid @enderror" 
                                        value="{{ old('capacity', 0) }}" required>
                                </div>
                                @error('capacity')
                                    <div class="invalid-feedback d-block mt-1">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-bold text-dark small text-uppercase ls-1">Tersedia Saat Ini</label>
                                <div class="input-group shadow-none">
                                    <span class="input-group-text bg-light border-0 text-muted px-3"><i class="bi bi-calendar-check"></i></span>
                                    <input type="number" min="0" name="available" class="form-control border-0 bg-light py-3 ps-2 @error('available') is-invalid @enderror" 
                                        value="{{ old('available', 0) }}" required>
                                </div>
                                @error('available')
                                    <div class="invalid-feedback d-block mt-1">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Action Buttons -->
                        <div class="d-flex flex-column flex-md-row justify-content-between align-items-center bg-light p-3 rounded-4 mt-2">
                            <a href="{{ route('bed-capacities.index') }}" class="btn btn-link text-decoration-none text-muted fw-bold order-2 order-md-1 mt-3 mt-md-0">
                                <i class="bi bi-arrow-left me-1"></i> Batal Kembali
                            </a>
                            <button type="submit" class="btn btn-primary px-5 py-3 rounded-pill shadow fw-bold order-1 order-md-2" style="background: linear-gradient(135deg, #4361ee 0%, #3f37c9 100%); border: none;">
                                <i class="bi bi-save2 me-2"></i>Simpan Ruangan
                            </button>
                        </div>
                    </form>
                </div>
            </div>
            
            <!-- Contextual Note -->
            <div class="mt-4 p-4 rounded-4 bg-info-subtle border border-info border-opacity-10 d-flex align-items-start">
                <div class="text-info me-3">
                    <i class="bi bi-info-circle-fill fs-3"></i>
                </div>
                <div>
                    <h6 class="fw-bold mb-1">Informasi Penting</h6>
                    <p class="mb-0 small text-dark opacity-75">
                        Data yang Anda simpan akan secara otomatis terintegrasi dengan Dashboard Pusat dan sistem Push Notification petugas lapangan. Mohon isi ketersediaan sesuai kondisi fisik yang ada.
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
