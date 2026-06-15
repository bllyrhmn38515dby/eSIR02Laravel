@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-lg-11">
            <!-- Breadcrumb -->
            <nav aria-label="breadcrumb" class="mb-4">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('referrals.index') }}" class="text-decoration-none text-primary fw-medium">Manajemen Rujukan</a></li>
                    <li class="breadcrumb-item active fw-bold" aria-current="page">Buat Rujukan Elektronik</li>
                </ol>
            </nav>

            <div class="card border-0 shadow-lg rounded-4 overflow-hidden animate__animated animate__fadeInUp">
                <!-- Header with Gradient -->
                <div class="card-header bg-primary text-white p-4 border-0" style="background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);">
                    <div class="d-flex justify-content-between align-items-center">
                        <div class="d-flex align-items-center">
                            <div class="bg-white bg-opacity-25 rounded-circle p-2 me-3">
                                <i class="bi bi-file-earmark-medical fs-4"></i>
                            </div>
                            <div>
                                <h4 class="mb-0 fw-bold underline">Entry Rujukan Medis Baru</h4>
                                <p class="mb-0 small opacity-75">Lengkapi parameter klinis untuk proses triase RS tujuan.</p>
                            </div>
                        </div>
                        <div class="text-end d-none d-md-block">
                            <button type="button" id="btn-magic-fill" class="btn btn-warning btn-sm rounded-pill px-3 fw-bold shadow-sm">
                                <i class="bi bi-magic me-1"></i> Auto-Fill Test
                            </button>
                        </div>
                    </div>
                </div>

                <div class="card-body p-4 p-md-5">
                    <form action="{{ route('referrals.store') }}" method="POST">
                        @csrf
                        
                        <div class="row g-5">
                            <!-- Section 1: Administrasi & Tujuan -->
                            <div class="col-lg-5 border-end">
                                <h5 class="fw-bold text-dark mb-4 d-flex align-items-center">
                                    <span class="bg-primary text-white rounded-circle me-2 d-flex align-items-center justify-content-center" style="width: 24px; height: 24px; font-size: 0.8rem;">1</span>
                                    Identitas & Tujuan
                                </h5>
                                
                                <div class="mb-4">
                                    <label class="form-label fw-bold text-muted small text-uppercase ls-1">Metode Input Pasien</label>
                                    <div class="row g-2">
                                        <div class="col-6">
                                            <input type="radio" class="btn-check" name="patient_type" id="type-existing" value="existing" checked>
                                            <label class="btn btn-outline-primary w-100 rounded-pill py-2 fw-600" for="type-existing">
                                                <i class="bi bi-search me-1"></i> Cari Unit
                                            </label>
                                        </div>
                                        <div class="col-6">
                                            <input type="radio" class="btn-check" name="patient_type" id="type-new" value="new">
                                            <label class="btn btn-outline-primary w-100 rounded-pill py-2 fw-600" for="type-new">
                                                <i class="bi bi-person-plus me-1"></i> Input Baru
                                            </label>
                                        </div>
                                    </div>
                                </div>

                                <!-- Section: Existing Patient -->
                                <div id="section-existing" class="mb-4 animate__animated animate__fadeIn">
                                    <label class="form-label fw-bold text-muted small text-uppercase ls-1">Pilih Pasien Terdaftar</label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-light border-0"><i class="bi bi-person-bounding-box"></i></span>
                                        <select name="patient_id" id="patient_id" class="form-select select2-simple border-0 bg-light py-2">
                                            <option value="">-- Cari NIK atau Nama --</option>
                                            @foreach($patients as $p)
                                                <option value="{{ $p->id }}">{{ $p->nik }} - {{ $p->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    @error('patient_id') <div class="invalid-feedback d-block mt-1">{{ $message }}</div> @enderror
                                </div>

                                <!-- Section: New Patient -->
                                <div id="section-new" class="d-none animate__animated animate__fadeIn">
                                    <div class="p-3 bg-light rounded-4 mb-4 border">
                                        <div class="d-flex justify-content-between align-items-center mb-3">
                                            <h6 class="fw-bold text-primary mb-0 small text-uppercase ls-1">Identitas Pasien Baru</h6>
                                            <button type="button" id="btn-generate-patient" class="btn btn-outline-warning btn-xs rounded-pill px-2 py-0 fw-bold" style="font-size: 0.65rem;">
                                                <i class="bi bi-magic"></i> Generate Mock
                                            </button>
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label small text-muted text-uppercase mb-1">NIK (16 Digit)</label>
                                            <input type="text" name="patient_nik" class="form-control border-0 @error('patient_nik') is-invalid @enderror" placeholder="16 Digit NIK" maxlength="16">
                                            @error('patient_nik') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label small text-muted text-uppercase mb-1">Nama Lengkap</label>
                                            <input type="text" name="patient_name" class="form-control border-0 @error('patient_name') is-invalid @enderror" placeholder="Nama Lengkap Pasien">
                                            @error('patient_name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                        </div>
                                        <div class="row g-2 mb-3">
                                            <div class="col-md-6">
                                                <label class="form-label small text-muted text-uppercase mb-1">Tgl Lahir</label>
                                                <input type="date" name="patient_dob" class="form-control border-0 @error('patient_dob') is-invalid @enderror">
                                                @error('patient_dob') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                            </div>
                                            <div class="col-md-6">
                                                <label class="form-label small text-muted text-uppercase mb-1">Gender</label>
                                                <select name="patient_gender" class="form-select border-0 @error('patient_gender') is-invalid @enderror">
                                                    <option value="L">Laki-laki</option>
                                                    <option value="P">Perempuan</option>
                                                </select>
                                                @error('patient_gender') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                            </div>
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label small text-muted text-uppercase mb-1">Alamat</label>
                                            <textarea name="patient_address" class="form-control border-0" rows="2" placeholder="Alamat lengkap..."></textarea>
                                        </div>
                                        <div class="mb-0">
                                            <label class="form-label small text-muted text-uppercase mb-1">Kontak/HP</label>
                                            <input type="text" name="patient_contact" class="form-control border-0" placeholder="08xxx">
                                        </div>
                                    </div>
                                </div>

                                <div class="mb-4">
                                    <label class="form-label fw-bold text-muted small text-uppercase ls-1">Smart RS Finder (Rekomendasi Terbaik)</label>
                                    <div id="smart-recommendation-container" class="d-none">
                                        <!-- Recomendations injected here by JS -->
                                    </div>
                                    <div id="smart-recommendation-loading" class="text-center p-3 text-muted">
                                        <div class="spinner-border spinner-border-sm text-primary me-2" role="status"></div>
                                        <small>Menganalisa jarak jalan raya dan kapasitas bed...</small>
                                    </div>
                                </div>

                                <div class="mb-4">
                                    <label class="form-label fw-bold text-muted small text-uppercase ls-1">Faskes Tujuan</label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-light border-0"><i class="bi bi-building"></i></span>
                                        <select name="to_faskes_id" id="to_faskes_id" class="form-select border-0 bg-light py-2 @error('to_faskes_id') is-invalid @enderror" required>
                                            <option value="">-- Pilih RS / Puskesmas --</option>
                                            @foreach($faskes as $f)
                                                <option value="{{ $f->id }}" 
                                                    data-beds='{{ json_encode($f->bedCapacities) }}' 
                                                    data-lat="{{ $f->latitude }}" 
                                                    data-lng="{{ $f->longitude }}">
                                                    {{ $f->name }} 
                                                    @if(isset($f->distance) && $f->distance < 99999) 
                                                        (Garis Lurus: ~{{ number_format($f->distance, 1) }} KM)
                                                    @endif
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    @error('to_faskes_id') <div class="invalid-feedback d-block mt-1">{{ $message }}</div> @enderror
                                </div>

                                <!-- Smart Bed Booking Section -->
                                <div class="mb-4 d-none animate__animated animate__fadeIn" id="bed_capacity_container">
                                    <div class="bg-primary bg-opacity-10 p-4 rounded-4 border border-primary border-opacity-25 shadow-xs">
                                        <label class="form-label fw-bold text-primary mb-3 small d-flex align-items-center text-uppercase ls-1">
                                            <i class="bi bi-door-open-fill me-2 fs-5"></i> Smart Bed Booking
                                        </label>
                                        <select name="bed_capacity_id" id="bed_capacity_id" class="form-select border-0 shadow-sm py-2">
                                            <option value="">-- Bebas / Tidak Memesan Bed Tipe Khusus --</option>
                                        </select>
                                        <p class="text-muted mt-3 mb-0" style="font-size: 0.75rem; line-height: 1.4;">
                                            <i class="bi bi-shield-check me-1"></i> Sistem akan secara otomatis mengunci (holding) ketersediaan bed setelah rujukan disetujui oleh RS Tujuan.
                                        </p>
                                    </div>
                                </div>
                            </div>

                            <!-- Section 2: Klinis & Vitals -->
                            <div class="col-lg-7">
                                <h5 class="fw-bold text-dark mb-4 d-flex align-items-center">
                                    <span class="bg-primary text-white rounded-circle me-2 d-flex align-items-center justify-content-center" style="width: 24px; height: 24px; font-size: 0.8rem;">2</span>
                                    Indikator Klinis & Diagnosa
                                </h5>
                                
                                <div class="mb-4">
                                    <label class="form-label fw-bold text-muted small text-uppercase ls-1">Diagnosa (ICD-10)</label>
                                    <select name="diagnosis" id="diagnosis" class="form-select @error('diagnosis') is-invalid @enderror" required>
                                        <option value="{{ old('diagnosis') }}">{{ old('diagnosis') }}</option>
                                    </select>
                                    @error('diagnosis') <div class="invalid-feedback d-block mt-1">{{ $message }}</div> @enderror
                                </div>

                                <div class="mb-4">
                                    <label class="form-label fw-bold text-muted small text-uppercase ls-1">Alasan Merujuk</label>
                                    <textarea name="reason" class="form-control border-0 bg-light p-3" rows="2" placeholder="Sebutkan alasan medis mengapa pasien tidak dapat ditangani..." required>{{ old('reason') }}</textarea>
                                </div>

                                <!-- Vitals Dashboard Style -->
                                <div class="p-4 bg-light bg-opacity-50 rounded-4 border">
                                    <div class="d-flex justify-content-between align-items-center mb-4">
                                        <h6 class="fw-bold text-primary text-uppercase ls-1 mb-0"><i class="bi bi-heart-pulse-fill me-2"></i> Tanda Vital (Triase)</h6>
                                    </div>
                                    <div class="row g-4">
                                        <div class="col-md-4">
                                            <label class="form-label small text-muted text-uppercase mb-1">Tekanan Darah</label>
                                            <div class="input-group">
                                                <input type="text" name="blood_pressure" class="form-control border-0 py-2" placeholder="120/80">
                                                <span class="input-group-text border-0 small">mmHg</span>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <label class="form-label small text-muted text-uppercase mb-1">Nadi (HR)</label>
                                            <div class="input-group">
                                                <input type="number" name="heart_rate" class="form-control border-0 py-2" placeholder="80">
                                                <span class="input-group-text border-0 small">bpm</span>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <label class="form-label small text-muted text-uppercase mb-1">Pernapasan (RR)</label>
                                            <div class="input-group">
                                                <input type="number" name="respiratory_rate" class="form-control border-0 py-2" placeholder="20">
                                                <span class="input-group-text border-0 small">bpm</span>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label small text-muted text-uppercase mb-1">Suhu Tubuh</label>
                                            <div class="input-group">
                                                <input type="number" step="0.1" name="temperature" class="form-control border-0 py-2" placeholder="36.5">
                                                <span class="input-group-text border-0 small">°C</span>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label small text-muted text-uppercase mb-1">Eyelid/Motor (GCS)</label>
                                            <div class="input-group">
                                                <input type="number" min="3" max="15" name="gcs_score" class="form-control border-0 py-2" placeholder="15">
                                                <span class="input-group-text border-0 small">Skor</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Footer Actions -->
                        <div class="mt-5 pt-4 border-top d-flex flex-column flex-md-row justify-content-between align-items-center">
                            <a href="{{ route('referrals.index') }}" class="btn btn-link text-decoration-none text-muted fw-bold order-2 order-md-1 mt-3 mt-md-0">
                                <i class="bi bi-arrow-left me-1"></i> Kembali ke Inbox
                            </a>
                            <button type="submit" class="btn btn-primary px-5 py-3 rounded-pill shadow-lg fw-bold order-1 order-md-2" style="background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%); border: none;">
                                <i class="bi bi-send-plus-fill me-2"></i>Kirim Berkas Elektronik
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<link href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" rel="stylesheet" />
<style>
    .select2-container--bootstrap-5 .select2-selection { border-radius: 8px; border: none; background-color: #f8f9fa; padding: 0.5rem; }
    .ls-1 { letter-spacing: 0.5px; }
    .shadow-xs { box-shadow: 0 .125rem .25rem rgba(0,0,0,.075); }
    .btn-warning { background-color: #ff9f43; border: none; color: white; }
    .btn-warning:hover { background-color: #ee8e30; color: white; }
    .fw-600 { font-weight: 600; }
    .btn-check:checked + .btn-outline-primary {
        background-color: var(--medical-primary);
        border-color: var(--medical-primary);
        color: white;
        box-shadow: 0 4px 10px rgba(67, 97, 238, 0.3);
    }
</style>
@endpush

@push('scripts')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
    $(document).ready(function() {
        $('.select2-simple').select2({ theme: 'bootstrap-5' });

        // Patient Type Toggle
        $('input[name="patient_type"]').on('change', function() {
            if (this.value === 'existing') {
                $('#section-existing').removeClass('d-none');
                $('#section-new').addClass('d-none');
                $('#patient_id').prop('required', true);
            } else {
                $('#section-existing').addClass('d-none');
                $('#section-new').removeClass('d-none');
                $('#patient_id').prop('required', false);
            }
        });

        // Mock Patient Generator
        $('#btn-generate-patient').on('click', function() {
            const firstNames = ['Budi', 'Siti', 'Agus', 'Dewi', 'Eko', 'Rina', 'Andi', 'Maya'];
            const lastNames = ['Santoso', 'Rahayu', 'Saputra', 'Lestari', 'Kusuma', 'Wijaya', 'Pratama'];
            const genders = ['L', 'P'];
            const addresses = ['Jl. Merdeka No. 12', 'Jl. Sudirman Kav. 5', 'Perum Kirana Blok A', 'Gg. Kelinci IV', 'Jl. Thamrin No. 88'];

            const randomNIK = Math.floor(Math.random() * 9000000000000000 + 1000000000000000).toString();
            const randomName = firstNames[Math.floor(Math.random() * firstNames.length)] + ' ' + lastNames[Math.floor(Math.random() * lastNames.length)];
            const randomGender = genders[Math.floor(Math.random() * genders.length)];
            const randomDate = new Date(1970 + Math.random() * 50, Math.floor(Math.random() * 12), Math.floor(Math.random() * 28)).toISOString().split('T')[0];

            $('input[name="patient_nik"]').val(randomNIK);
            $('input[name="patient_name"]').val(randomName);
            $('input[name="patient_dob"]').val(randomDate);
            $('select[name="patient_gender"]').val(randomGender);
            $('textarea[name="patient_address"]').val(addresses[Math.floor(Math.random() * addresses.length)]);
            $('input[name="patient_contact"]').val('0812' + Math.floor(Math.random() * 100000000).toString().padStart(8, '0'));

            $(this).removeClass('btn-outline-warning').addClass('btn-success').html('✅ Loaded');
            setTimeout(() => $(this).addClass('btn-outline-warning').removeClass('btn-success').html('<i class="bi bi-magic"></i> Generate Mock'), 1000);
        });
        
        // Handle Validation Old Values
        @if(old('patient_type') === 'new')
            $('#type-new').prop('checked', true).trigger('change');
        @endif

        // Bed Reservation Logic
        $('#to_faskes_id').on('change', function() {
            let selectedOption = $(this).find('option:selected');
            let bedsJson = selectedOption.attr('data-beds');
            let bedSelect = $('#bed_capacity_id');
            bedSelect.empty().append('<option value="">-- Bebas / Tidak Memesan Bed Tipe Khusus --</option>');
            
            if (bedsJson) {
                let beds = JSON.parse(bedsJson);
                if (beds.length > 0) {
                    $('#bed_capacity_container').removeClass('d-none').addClass('animate__fadeIn');
                    beds.forEach(b => {
                        let colorHex = b.available > 0 ? '#198754' : '#dc3545';
                        let strDisabled = b.available > 0 ? '' : 'disabled';
                        bedSelect.append(`<option value="${b.id}" style="color:${colorHex}; font-weight:bold;" ${strDisabled}>${b.room_name} (Ready: ${b.available})</option>`);
                    });
                } else {
                    $('#bed_capacity_container').addClass('d-none');
                }
            } else {
                $('#bed_capacity_container').addClass('d-none');
            }
        });

        // ICD-10 AJAX Integration
        $('#diagnosis').select2({
            theme: 'bootstrap-5',
            placeholder: '🔎 Cari Kode ICD-10...',
            ajax: {
                url: 'https://clinicaltables.nlm.nih.gov/api/icd10cm/v3/search?sf=code,name',
                dataType: 'json',
                delay: 400,
                data: (params) => ({ terms: params.term, maxList: 12 }),
                processResults: (data) => {
                    let results = [];
                    if (data && data[3]) {
                        data[3].forEach(item => {
                            let formatICD = `[${item[0]}] ${item[1]}`;
                            results.push({ id: formatICD, text: formatICD });
                        });
                    }
                    return { results };
                },
                cache: true
            },
            minimumInputLength: 3,
            tags: true
        });

        // MAGIC FILL
        $('#btn-magic-fill').on('click', function() {
            const scenarios = [
                { diagnosis: "[A01.0] Typhoid fever", reason: "Demam tinggi berkelanjutan, lidah kotor, bradikardia relatif.", bp: "110/70", hr: 72, rr: 20, temp: 39.1, gcs: 15 },
                { diagnosis: "[I21.9] Acute myocardial infarction", reason: "Nyeri dada substernal menjalar, keringat dingin, elevasi segmen ST.", bp: "140/90", hr: 98, rr: 28, temp: 36.8, gcs: 15 },
                { diagnosis: "[S06.0] Concussion", reason: "Penurunan kesadaran post-trauma kepala, muntah proyektil.", bp: "130/80", hr: 64, rr: 16, temp: 36.5, gcs: 9 }
            ];
            const pick = scenarios[Math.floor(Math.random() * scenarios.length)];
            
            // Randomize Patient & Faskes
            const patientSelect = $('select[name="patient_id"]');
            const ptOptions = patientSelect.find('option').not(':first');
            patientSelect.val($(ptOptions[Math.floor(Math.random() * ptOptions.length)]).val()).trigger('change');

            const faskesSelect = $('#to_faskes_id');
            const fsOptions = faskesSelect.find('option').not(':first');
            faskesSelect.val($(fsOptions[Math.floor(Math.random() * fsOptions.length)]).val()).trigger('change');

            if ($('#diagnosis').find("option[value='" + pick.diagnosis + "']").length) {
                $('#diagnosis').val(pick.diagnosis).trigger('change');
            } else {
                let newOption = new Option(pick.diagnosis, pick.diagnosis, true, true);
                $('#diagnosis').append(newOption).trigger('change');
            }

            $('textarea[name="reason"]').val(pick.reason + " (GENERATED TEST DATA)");
            $('input[name="blood_pressure"]').val(pick.bp);
            $('input[name="heart_rate"]').val(pick.hr);
            $('input[name="respiratory_rate"]').val(pick.rr);
            $('input[name="temperature"]').val(pick.temp);
            $('input[name="gcs_score"]').val(pick.gcs);

            $(this).addClass('btn-success').removeClass('btn-warning').html('✅ Data Loaded!');
            setTimeout(() => $(this).addClass('btn-warning').removeClass('btn-success').html('<i class="bi bi-magic me-1"></i> Auto-Fill Test'), 2000);
        });

        // SMART RS RECOMMENDATION VIA OSRM
        @if(isset($userFaskes) && $userFaskes->latitude && $userFaskes->longitude)
            const originLat = {{ $userFaskes->latitude }};
            const originLng = {{ $userFaskes->longitude }};
            const osrmUrl = 'https://router.project-osrm.org/route/v1/driving/';
            
            // Collect Top 5 closest Faskes (Haversine via backend) to query OSRM
            let candidateFaskes = [];
            $('#to_faskes_id option').each(function() {
                const id = $(this).val();
                if(id) {
                    const lat = $(this).data('lat');
                    const lng = $(this).data('lng');
                    const bedsJson = $(this).attr('data-beds');
                    let totalAvailableBeds = 0;
                    
                    if (bedsJson) {
                        try {
                            const beds = JSON.parse(bedsJson);
                            beds.forEach(b => { totalAvailableBeds += parseInt(b.available || 0); });
                        } catch (e) {}
                    }
                    
                    if(lat && lng) {
                        candidateFaskes.push({
                            id: id,
                            name: $(this).text().split('(')[0].trim(),
                            lat: lat,
                            lng: lng,
                            beds: totalAvailableBeds
                        });
                    }
                }
            });

            // Limit to max 5 for OSRM queries to prevent rate limiting
            candidateFaskes = candidateFaskes.slice(0, 5);

            if(candidateFaskes.length > 0) {
                let promises = candidateFaskes.map(faskes => {
                    const routingUrl = `${osrmUrl}${originLng},${originLat};${faskes.lng},${faskes.lat}?overview=false`;
                    return fetch(routingUrl)
                        .then(res => res.json())
                        .then(data => {
                            if(data.routes && data.routes.length > 0) {
                                faskes.distanceMiles = data.routes[0].distance; // meters
                                faskes.durationSecs = data.routes[0].duration; // seconds
                                faskes.distanceKm = (faskes.distanceMiles / 1000).toFixed(1);
                                faskes.durationMins = Math.ceil(faskes.durationSecs / 60);
                                faskes.score = (faskes.durationSecs) - (faskes.beds * 300); // lower is better, beds give -5 mins bonus
                            } else {
                                faskes.score = 999999; 
                            }
                            return faskes;
                        })
                        .catch(err => {
                            faskes.score = 999999;
                            return faskes;
                        });
                });

                Promise.all(promises).then(results => {
                    // Sort by smart score
                    results.sort((a, b) => a.score - b.score);
                    
                    // Take Top 3
                    const topRecommendations = results.slice(0, 3);
                    
                    $('#smart-recommendation-loading').addClass('d-none');
                    let html = `<div class="d-flex flex-column gap-2 mb-3">`;
                    
                    topRecommendations.forEach((rec, index) => {
                        if(rec.score === 999999) return; // Skip if no route
                        
                        let rankBadge = index === 0 ? '<span class="badge bg-warning text-dark"><i class="bi bi-star-fill"></i> Best</span>' : '';
                        let bedColor = rec.beds > 0 ? 'text-success' : 'text-danger';
                        
                        html += `
                            <div class="card border border-primary border-opacity-25 shadow-sm recommendation-card" role="button" data-id="${rec.id}">
                                <div class="card-body p-3 d-flex justify-content-between align-items-center">
                                    <div>
                                        <h6 class="mb-1 text-primary fw-bold">${rankBadge} ${rec.name}</h6>
                                        <div class="small text-muted d-flex gap-3">
                                            <span><i class="bi bi-car-front text-secondary"></i> ${rec.distanceKm} KM</span>
                                            <span><i class="bi bi-clock-history text-secondary"></i> ~${rec.durationMins} Menit</span>
                                            <span class="fw-bold ${bedColor}"><i class="bi bi-hospital"></i> ${rec.beds} Bed Ready</span>
                                        </div>
                                    </div>
                                    <button type="button" class="btn btn-sm btn-outline-primary rounded-pill px-3 fw-bold btn-select-rs">Pilih</button>
                                </div>
                            </div>
                        `;
                    });
                    
                    html += `</div>`;
                    $('#smart-recommendation-container').html(html).removeClass('d-none').addClass('animate__animated animate__fadeIn');

                    // Bind click event
                    $('.recommendation-card').on('click', function() {
                        const targetId = $(this).data('id');
                        $('#to_faskes_id').val(targetId).trigger('change');
                        
                        // Visual feedback
                        $('.recommendation-card').removeClass('bg-primary bg-opacity-10 border-primary');
                        $(this).addClass('bg-primary bg-opacity-10 border-primary');
                        
                        // Scroll to faskes select
                        $('html, body').animate({
                            scrollTop: $("#to_faskes_id").offset().top - 100
                        }, 500);
                    });
                });
            } else {
                 $('#smart-recommendation-loading').addClass('d-none');
            }
        @else
            $('#smart-recommendation-loading').addClass('d-none');
        @endif
    });
</script>
@endpush
