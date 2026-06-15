<!DOCTYPE html>
<html>
<head>
    <title>Surat Rujukan - {{ $referral->referral_number }}</title>
    <style>
        @page { margin: 0; }
        body { font-family: 'Helvetica', 'Arial', sans-serif; font-size: 10pt; line-height: 1.5; color: #1a1a1a; margin: 0; padding: 0; background: #fff; }
        .container { padding: 40px 50px; }
        
        /* Official Header Style */
        .official-header { border-bottom: 2px solid #000; padding-bottom: 15px; margin-bottom: 30px; position: relative; }
        .header-logo { position: absolute; left: 0; top: 0; width: 60px; height: 60px; border: 1px solid #ddd; text-align: center; line-height: 60px; font-weight: bold; color: #4361ee; font-size: 20pt; }
        .header-content { text-align: center; padding: 0 80px; }
        .header-content h1 { margin: 0; font-size: 16pt; text-transform: uppercase; color: #000; border-bottom: 1px solid #eee; display: inline-block; padding-bottom: 5px; margin-bottom: 5px; }
        .header-content p { margin: 2px 0; font-size: 9pt; color: #444; }
        .doc-meta { position: absolute; right: 0; top: 0; text-align: right; }
        .qr-verify { margin-bottom: 5px; }
        
        /* Layout Components */
        .section-header { background: #f8f9fa; padding: 6px 12px; font-weight: bold; font-size: 9pt; text-transform: uppercase; border-left: 4px solid #4361ee; margin: 20px 0 10px 0; color: #4361ee; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 15px; }
        th, td { padding: 10px; border: 1px solid #e9ecef; vertical-align: top; text-align: left; }
        th { background-color: #fdfdfd; width: 25%; font-weight: bold; color: #555; font-size: 8.5pt; text-transform: uppercase; }
        td { font-size: 9.5pt; }

        .diagnosa-alert { color: #d90429; font-weight: bold; font-size: 11pt; border: 1px dashed #d90429; padding: 5px; background: #fff5f5; }
        
        /* Vitals Dashboard */
        .vitals-row { width: 100%; margin: 15px 0; }
        .vital-card { width: 18%; float: left; margin-right: 2%; border: 1px solid #4361ee; border-radius: 5px; text-align: center; padding: 8px 0; background: #f0f3ff; }
        .vital-card:last-child { margin-right: 0; }
        .v-label { font-size: 7.5pt; text-transform: uppercase; color: #4361ee; display: block; margin-bottom: 3px; }
        .v-value { font-size: 12pt; font-weight: bold; color: #1a1a1a; }
        
        .footer-note { position: fixed; bottom: 30px; left: 50px; right: 50px; border-top: 1px solid #eee; padding-top: 10px; font-size: 7.5pt; color: #888; text-align: justify; }
        .signature-area { margin-top: 40px; }
        .sig-box { float: right; width: 200px; text-align: center; }
        .sig-placeholder { height: 70px; }
    </style>
</head>
<body>
    <div class="container">
        <!-- Official Header -->
        <div class="official-header">
            <div class="header-logo">eSIR</div>
            <div class="header-content">
                <h1>Surat Pengantar Rujukan Medis</h1>
                <p><strong>{{ $referral->fromFaskes->name }}</strong></p>
                <p>{{ $referral->fromFaskes->address }} | Telp: {{ $referral->fromFaskes->contact ?? 'N/A' }}</p>
                <p style="font-size: 8pt; color: #666; margin-top: 5px;">Nomor Dokumen: <span style="font-family: monospace;">{{ $referral->referral_number }}</span></p>
            </div>
            <div class="doc-meta">
                <div class="qr-verify">
                    <img src="data:image/svg+xml;base64,{!! base64_encode(\SimpleSoftwareIO\QrCode\Facades\QrCode::format('svg')->size(65)->margin(0)->generate(route('referrals.edit', $referral))) !!}">
                </div>
                <p style="font-size: 6pt; color: #999; margin: 0;">SCAN TO VERIFY</p>
            </div>
        </div>

        <div class="section-header">I. IDENTITAS PASIEN</div>
        <table>
            <tr>
                <th>Nama Pasien</th>
                <td>{{ strtoupper($referral->patient->name) }}</td>
                <th>NIK / No ID</th>
                <td>{{ $referral->patient->nik }}</td>
            </tr>
            <tr>
                <th>Tgl Lahir / Usia</th>
                <td>{{ \Carbon\Carbon::parse($referral->patient->dob)->format('d-m-Y') }} ({{ \Carbon\Carbon::parse($referral->patient->dob)->age }} Thn)</td>
                <th>Jenis Kelamin</th>
                <td>{{ $referral->patient->gender == 'L' ? 'LAKI-LAKI' : 'PEREMPUAN' }}</td>
            </tr>
        </table>

        <div class="section-header">II. RESUME KLINIS & DIAGNOSA</div>
        <table>
            <tr>
                <th>Anamnesa / Gejala</th>
                <td colspan="3">{{ $referral->reason }}</td>
            </tr>
            <tr>
                <th>Diagnosa Kerja</th>
                <td colspan="3"><span class="diagnosa-alert">{{ $referral->diagnosis }}</span></td>
            </tr>
            <tr>
                <th>Tujuan Rujukan</th>
                <td colspan="3"><strong>{{ $referral->toFaskes->name }}</strong></td>
            </tr>
        </table>

        <div class="section-header">III. PEMERIKSAAN FISIK (TANDA VITAL)</div>
        <div class="vitals-row">
            <div class="vital-card">
                <span class="v-label">TENSI</span>
                <span class="v-value">{{ $referral->blood_pressure ?? '-' }} <small style="font-size: 6pt; font-weight: normal;">mmHg</small></span>
            </div>
            <div class="vital-card">
                <span class="v-label">NADI</span>
                <span class="v-value">{{ $referral->heart_rate ?? '-' }} <small style="font-size: 6pt; font-weight: normal;">bpm</small></span>
            </div>
            <div class="vital-card">
                <span class="v-label">SUHU</span>
                <span class="v-value">{{ $referral->temperature ?? '-' }} <small style="font-size: 6pt; font-weight: normal;">°C</small></span>
            </div>
            <div class="vital-card">
                <span class="v-label">NAPAS</span>
                <span class="v-value">{{ $referral->respiratory_rate ?? '-' }} <small style="font-size: 6pt; font-weight: normal;">bpm</small></span>
            </div>
            <div class="vital-card">
                <span class="v-label">GCS</span>
                <span class="v-value">{{ $referral->gcs_score ?? '-' }}</span>
            </div>
            <div style="clear:both;"></div>
        </div>

        <div class="section-header">IV. LOGISTIK & AUDIT TRAIL</div>
        <table style="margin-bottom: 0;">
            <tr>
                <th>Status Terakhir</th>
                <td>{{ strtoupper($referral->status) }}</td>
                <th>Waktu Cetak</th>
                <td>{{ now()->format('d/m/Y H:i') }} WIB</td>
            </tr>
            <tr>
                <th>Driver / Armada</th>
                <td>{{ $referral->driver->name ?? 'N/A' }} / {{ $referral->ambulance->police_number ?? 'N/A' }}</td>
                <th>Verifikasi</th>
                <td>Tervalidasi Sistem eSIR 2.1</td>
            </tr>
        </table>

        <div class="signature-area">
            <div class="sig-box">
                <p style="font-size: 8pt;">Petugas Yang Merujuk,</p>
                <div class="sig-placeholder">
                    <img src="data:image/svg+xml;base64,{!! base64_encode(\SimpleSoftwareIO\QrCode\Facades\QrCode::format('svg')->size(55)->margin(0)->generate('VERIFIED: ' . ($referral->createdBy->name ?? 'System'))) !!}">
                </div>
                <p style="font-weight: bold; text-decoration: underline;">{{ $referral->createdBy->name ?? 'N/A' }}</p>
                <p style="font-size: 7pt; color: #666;">NIP/No Pegawai: {{ $referral->createdBy->id }}</p>
            </div>
        </div>
    </div>

    <div class="footer-note">
        <strong>PENTING:</strong> Dokumen rujukan elektronik ini merupakan salinan sah dari basis data Sistem Informasi Rujukan eSIR 2.1. Keaslian dokumen dapat dipastikan melalui pemindaian kode QR di bagian atas. Seluruh proses rujukan ini terpantau secara real-time dan tersimpan dalam Audit Trail sistem nasional.
    </div>
</body>
</html>
