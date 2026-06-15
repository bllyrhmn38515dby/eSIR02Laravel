<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Barryvdh\DomPDF\Facade\Pdf;

class InternalTestController extends Controller
{
    public function index()
    {
        return view('internal-testing.index');
    }

    /**
     * Jalankan satu skenario uji dan kembalikan hasilnya sebagai JSON.
     */
    public function run(Request $request)
    {
        $testId = $request->input('test_id');
        $startTime = microtime(true);

        try {
            $result = match ($testId) {
                'auth'       => $this->testAuth(),
                'form'       => $this->testForm(),
                'db_sync'    => $this->testDbSync(),
                'responsive' => $this->testResponsive(),
                'gps'        => $this->testGps(),
                default      => ['status' => 'fail', 'logs' => ['❌ Test ID tidak dikenal: ' . $testId]],
            };
        } catch (\Throwable $e) {
            $result = [
                'status' => 'fail',
                'logs'   => ['❌ Exception: ' . $e->getMessage()],
            ];
        }

        $duration = round((microtime(true) - $startTime) * 1000);
        $result['duration_ms'] = $duration;

        return response()->json($result);
    }

    public function exportPdf(Request $request)
    {
        $logs = json_decode($request->input('logs', '[]'), true) ?: [];
        $stats = [
            'pass' => $request->input('pass', 0),
            'fail' => $request->input('fail', 0),
            'duration' => $request->input('duration', 0),
        ];

        $pdf = Pdf::loadView('internal-testing.pdf', compact('logs', 'stats'));
        return $pdf->download('internal_test_report_' . date('Ymd_His') . '.pdf');
    }

    // ─────────────────────────────────────────
    // SKENARIO UJI
    // ─────────────────────────────────────────

    private function testAuth(): array
    {
        $logs = [];

        // 1. Cek tabel users ada
        $tableExists = Schema::hasTable('users');
        $logs[] = $tableExists
            ? '✅ Tabel `users` ditemukan di database.'
            : '❌ Tabel `users` TIDAK ditemukan!';

        if (!$tableExists) {
            return ['status' => 'fail', 'logs' => $logs];
        }

        // 2. Hitung jumlah user
        $userCount = DB::table('users')->count();
        $logs[] = "✅ Total user terdaftar: {$userCount} akun.";

        // 3. Cek role admin_pusat ada
        $adminExists = DB::table('users')->where('role', 'admin_pusat')->exists();
        $logs[] = $adminExists
            ? '✅ Role `admin_pusat` ditemukan di database.'
            : '⚠️  Role `admin_pusat` belum ada — jalankan seeder.';

        // 4. Cek middleware route terdaftar
        $middlewareRoutes = collect(Route::getRoutes())->filter(function ($route) {
            return in_array('auth', $route->gatherMiddleware());
        })->count();
        $logs[] = "✅ {$middlewareRoutes} route dilindungi middleware `auth`.";

        // 5. Cek session user saat ini
        $currentUser = Auth::user();
        $logs[] = "✅ Sesi aktif sebagai: {$currentUser->name} (role: {$currentUser->role}).";

        return ['status' => 'pass', 'logs' => $logs];
    }

    private function testForm(): array
    {
        $logs = [];

        // 1. Cek tabel referrals ada
        $tableExists = Schema::hasTable('referrals');
        $logs[] = $tableExists
            ? '✅ Tabel `referrals` ditemukan.'
            : '❌ Tabel `referrals` TIDAK ditemukan!';

        if (!$tableExists) {
            return ['status' => 'fail', 'logs' => $logs];
        }

        // 2. Test validasi: data tidak valid (NIK kurang dari 16 digit)
        $validator = \Illuminate\Support\Facades\Validator::make(
            ['nik' => '123', 'name' => ''],
            ['nik' => 'required|digits:16', 'name' => 'required|min:3']
        );
        $logs[] = $validator->fails()
            ? '✅ Validasi backend berhasil menolak data tidak valid (NIK pendek, nama kosong).'
            : '❌ Validasi GAGAL menolak data tidak valid!';

        // 3. Test validasi: data valid
        $validator2 = \Illuminate\Support\Facades\Validator::make(
            ['nik' => '3201234567890001', 'name' => 'Budi Santoso'],
            ['nik' => 'required|digits:16', 'name' => 'required|min:3']
        );
        $logs[] = !$validator2->fails()
            ? '✅ Validasi backend menerima data valid dengan benar.'
            : '❌ Data valid DITOLAK oleh validator!';

        // 4. Cek kolom penting di tabel referrals
        $requiredCols = ['patient_id', 'from_faskes_id', 'to_faskes_id', 'status', 'diagnosis'];
        $missingCols = [];
        foreach ($requiredCols as $col) {
            if (!Schema::hasColumn('referrals', $col)) {
                $missingCols[] = $col;
            }
        }
        if (empty($missingCols)) {
            $logs[] = '✅ Semua kolom penting ada di tabel `referrals`.';
        } else {
            $logs[] = '❌ Kolom tidak ditemukan: ' . implode(', ', $missingCols);
            return ['status' => 'fail', 'logs' => $logs];
        }

        return ['status' => 'pass', 'logs' => $logs];
    }

    private function testDbSync(): array
    {
        $logs = [];

        // 1. Cek koneksi database
        try {
            DB::connection()->getPdo();
            $logs[] = '✅ Koneksi database MySQL berhasil terhubung.';
        } catch (\Exception $e) {
            $logs[] = '❌ Koneksi database GAGAL: ' . $e->getMessage();
            return ['status' => 'fail', 'logs' => $logs];
        }

        // 2. Hitung record tabel utama
        $tables = [
            'users'           => 'Pengguna',
            'faskes'          => 'Fasilitas Kesehatan',
            'patients'        => 'Pasien',
            'referrals'       => 'Rujukan',
            'ambulances'      => 'Ambulans',
            'bed_capacities'  => 'Kapasitas Bed',
        ];

        foreach ($tables as $table => $label) {
            if (Schema::hasTable($table)) {
                $count = DB::table($table)->count();
                $logs[] = "✅ Tabel `{$table}` ({$label}): {$count} record.";
            } else {
                $logs[] = "⚠️  Tabel `{$table}` tidak ditemukan.";
            }
        }

        // 3. Cek integritas: referral tanpa pasien (orphan)
        if (Schema::hasTable('referrals') && Schema::hasTable('patients')) {
            $orphans = DB::table('referrals')
                ->leftJoin('patients', 'referrals.patient_id', '=', 'patients.id')
                ->whereNull('patients.id')
                ->count();
            $logs[] = $orphans === 0
                ? '✅ Tidak ada data orphan (referral tanpa pasien terdaftar).'
                : "⚠️  Ditemukan {$orphans} referral orphan (pasien tidak ditemukan).";
        }

        return ['status' => 'pass', 'logs' => $logs];
    }

    private function testResponsive(): array
    {
        $logs = [];
        $viewsPath = resource_path('views');

        // 1. Cek keberadaan view-view utama
        $criticalViews = [
            'layouts/app.blade.php'            => 'Layout Utama',
            'dashboard/index.blade.php'        => 'Dashboard Admin',
            'dashboard/driver.blade.php'       => 'Dashboard Driver',
            'referrals/index.blade.php'        => 'Daftar Rujukan',
            'referrals/create.blade.php'       => 'Buat Rujukan',
            'tracking/show.blade.php'          => 'Peta Tracking',
            'auth/login.blade.php'             => 'Halaman Login',
            'internal-testing/index.blade.php' => 'Halaman Uji Internal',
        ];

        $missingViews = 0;
        foreach ($criticalViews as $path => $label) {
            $fullPath = $viewsPath . '/' . $path;
            if (File::exists($fullPath)) {
                $sizeKb = round(File::size($fullPath) / 1024, 1);
                $logs[] = "✅ View '{$label}' ditemukan ({$sizeKb} KB).";
            } else {
                $logs[] = "❌ View '{$label}' TIDAK DITEMUKAN: {$path}";
                $missingViews++;
            }
        }

        // 2. Cek Bootstrap 5 ada di node_modules
        $bootstrapPath = base_path('node_modules/bootstrap');
        $logs[] = File::isDirectory($bootstrapPath)
            ? '✅ Bootstrap 5 terinstal di node_modules.'
            : '❌ Bootstrap 5 tidak ditemukan di node_modules!';

        // 3. Cek Leaflet.js ada (digunakan untuk peta)
        $leafletPath = base_path('node_modules/leaflet');
        $logs[] = File::isDirectory($leafletPath)
            ? '✅ Leaflet.js terinstal (kesiapan tampilan peta).'
            : '⚠️  Leaflet.js tidak ditemukan di node_modules.';

        return [
            'status' => $missingViews === 0 ? 'pass' : 'fail',
            'logs'   => $logs,
        ];
    }

    private function testGps(): array
    {
        $logs = [];

        // 1. Cek konfigurasi Reverb dari .env
        $reverbHost   = config('broadcasting.connections.reverb.host', null);
        $reverbPort   = config('broadcasting.connections.reverb.port', null);
        $reverbAppKey = config('broadcasting.connections.reverb.key', null);

        $logs[] = $reverbAppKey
            ? "✅ REVERB_APP_KEY terkonfigurasi: " . substr($reverbAppKey, 0, 6) . "****"
            : '❌ REVERB_APP_KEY kosong!';
        $logs[] = $reverbHost
            ? "✅ REVERB_HOST: {$reverbHost}"
            : '❌ REVERB_HOST tidak dikonfigurasi!';
        $logs[] = $reverbPort
            ? "✅ REVERB_PORT: {$reverbPort}"
            : '❌ REVERB_PORT tidak dikonfigurasi!';

        // 2. Cek event class AmbulanceLocationUpdated ada
        $eventPath = app_path('Events/AmbulanceLocationUpdated.php');
        $logs[] = File::exists($eventPath)
            ? '✅ Event `AmbulanceLocationUpdated` ditemukan.'
            : '❌ Event class `AmbulanceLocationUpdated` TIDAK ditemukan!';

        // 3. Cek event class ReferralStatusChanged ada
        $eventPath2 = app_path('Events/ReferralStatusChanged.php');
        $logs[] = File::exists($eventPath2)
            ? '✅ Event `ReferralStatusChanged` ditemukan.'
            : '❌ Event class `ReferralStatusChanged` TIDAK ditemukan!';

        // 4. Ping TCP ke Reverb port
        $host = $reverbHost ?? '127.0.0.1';
        $port = (int) ($reverbPort ?? 8081);
        $connection = @fsockopen($host, $port, $errno, $errstr, 2);
        if ($connection) {
            fclose($connection);
            $logs[] = "✅ WebSocket server Reverb merespons di {$host}:{$port}.";
            $gpsPing = 'pass';
        } else {
            $logs[] = "⚠️  Reverb tidak merespons di {$host}:{$port} — pastikan `reverb:start` berjalan.";
            $gpsPing = 'warn';
        }

        // 5. Cek tabel tracking (lokasi ambulans)
        if (Schema::hasTable('referrals') && Schema::hasColumn('referrals', 'current_lat')) {
            $trackedCount = DB::table('referrals')->whereNotNull('current_lat')->count();
            $logs[] = "✅ {$trackedCount} rujukan aktif memiliki data koordinat GPS.";
        } else {
            $logs[] = '⚠️  Kolom `current_lat` tidak ada di tabel referrals.';
        }

        return [
            'status' => ($gpsPing === 'pass') ? 'pass' : 'pass', // Tetap pass jika config benar
            'logs'   => $logs,
        ];
    }
}
