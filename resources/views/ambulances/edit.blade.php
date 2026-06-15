@extends('layouts.app')

@section('content')
<div class="container-fluid py-5 px-lg-5" style="background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%); min-height: 90vh;">
    <div class="row justify-content-center">
        <div class="col-lg-6">
            <div class="d-flex align-items-center mb-4 animate__animated animate__fadeIn">
                <a href="{{ route('ambulances.index') }}" class="btn btn-white rounded-circle shadow-sm me-3" style="width: 45px; height: 45px; display: flex; align-items: center; justify-content: center;">
                    <i class="bi bi-arrow-left fs-5"></i>
                </a>
                <div>
                    <h2 class="fw-bold mb-0">Update Armada</h2>
                    <p class="text-muted mb-0">Edit informasi unit ambulans <span class="badge bg-light text-dark border ms-1">{{ $ambulance->police_number }}</span></p>
                </div>
            </div>

            <div class="card border-0 shadow-lg rounded-4 overflow-hidden animate__animated animate__zoomIn">
                <div class="card-header bg-primary py-4 px-4 border-0" style="background: linear-gradient(135deg, #1e3a8a 0%, #3b82f6 100%);">
                    <div class="d-flex align-items-center">
                        <div class="bg-white bg-opacity-20 p-2 rounded-circle me-3">
                            <i class="bi bi-truck text-white fs-4"></i>
                        </div>
                        <h5 class="mb-0 text-white fw-bold">Modifikasi Data Unit</h5>
                    </div>
                </div>
                <div class="card-body p-4 p-lg-5 bg-white">
                    <form action="{{ route('ambulances.update', $ambulance) }}" method="POST">
                        @csrf @method('PUT')
                        
                        @if(auth()->user()->role === 'admin_pusat')
                        <div class="mb-4">
                            <label class="form-label fw-bold text-dark mb-1">Unit Faskes</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0"><i class="bi bi-hospital text-muted"></i></span>
                                <select name="faskes_id" class="form-select bg-light border-start-0 py-3 @error('faskes_id') is-invalid @enderror" required>
                                    @foreach($faskes as $f)
                                        <option value="{{ $f->id }}" {{ old('faskes_id', $ambulance->faskes_id) == $f->id ? 'selected' : '' }}>{{ $f->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            @error('faskes_id')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>
                        @endif

                        <div class="mb-4">
                            <label class="form-label fw-bold text-dark mb-1">Nomor Polisi (Plat Nomor)</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0"><i class="bi bi-person-badge text-muted"></i></span>
                                <input type="text" name="police_number" class="form-control bg-light border-start-0 py-3 fw-bold ls-1 @error('police_number') is-invalid @enderror" placeholder="B 1234 ABC" required value="{{ old('police_number', $ambulance->police_number) }}" style="text-transform: uppercase;">
                            </div>
                            @error('police_number')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label class="form-label fw-bold text-dark mb-1">Tipe / Model Kendaraan</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0"><i class="bi bi-info-circle text-muted"></i></span>
                                <input type="text" name="vehicle_type" class="form-control bg-light border-start-0 py-3 @error('vehicle_type') is-invalid @enderror" placeholder="Contoh: Toyota Hiace / Daihatsu GranMax" required value="{{ old('vehicle_type', $ambulance->vehicle_type) }}">
                            </div>
                        </div>

                        <div class="mb-5">
                            <label class="form-label fw-bold text-dark mb-1">Status Operasional Saat Ini</label>
                            <div class="row g-3">
                                <div class="col-md-4">
                                    <input type="radio" class="btn-check" name="status" id="status_standby" value="standby" {{ $ambulance->status == 'standby' ? 'checked' : '' }}>
                                    <label class="btn btn-outline-success w-100 py-3 rounded-4 fw-bold" for="status_standby">
                                        <i class="bi bi-check2-circle d-block fs-4 mb-2"></i> Ready
                                    </label>
                                </div>
                                <div class="col-md-4">
                                    <input type="radio" class="btn-check" name="status" id="status_ontrip" value="on_trip" {{ $ambulance->status == 'on_trip' ? 'checked' : '' }}>
                                    <label class="btn btn-outline-warning w-100 py-3 rounded-4 fw-bold" for="status_ontrip">
                                        <i class="bi bi-truck d-block fs-4 mb-2"></i> On Road
                                    </label>
                                </div>
                                <div class="col-md-4">
                                    <input type="radio" class="btn-check" name="status" id="status_maintenance" value="maintenance" {{ $ambulance->status == 'maintenance' ? 'checked' : '' }}>
                                    <label class="btn btn-outline-danger w-100 py-3 rounded-4 fw-bold" for="status_maintenance">
                                        <i class="bi bi-tools d-block fs-4 mb-2"></i> Repair
                                    </label>
                                </div>
                            </div>
                        </div>

                        <div class="pt-3">
                            <button type="submit" class="btn btn-primary w-100 py-3 rounded-pill fw-bold shadow-lg ls-1" style="background: linear-gradient(135deg, #1e3a8a 0%, #3b82f6 100%); border: none;">
                                <i class="bi bi-save2 me-2"></i>Simpan Perubahan Data
                            </button>
                        </div>
                    </form>
                </div>
            </div>
            
            <div class="mt-4 text-center animate__animated animate__fadeIn animate__delay-1s">
                <form action="{{ route('ambulances.destroy', $ambulance) }}" method="POST" onsubmit="return confirm('Hapus armada ini secara permanen dari sistem?');">
                    @csrf @method('DELETE')
                    <button type="submit" class="btn btn-link text-danger text-decoration-none small fw-bold">
                        <i class="bi bi-trash3 me-1"></i> Hapuskan Unit dari Fleet Management
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<style>
    .ls-1 { letter-spacing: 0.5px; }
    .btn-white { background: #fff; color: #64748b; border: 1px solid #e2e8f0; }
    .btn-check:checked + .btn-outline-success { background-color: #dcfce7 !important; border-width: 2px; }
    .btn-check:checked + .btn-outline-warning { background-color: #fef9c3 !important; border-width: 2px; }
    .btn-check:checked + .btn-outline-danger { background-color: #fee2e2 !important; border-width: 2px; }
</style>
@endsection
