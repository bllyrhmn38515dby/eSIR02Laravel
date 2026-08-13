<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

Route::get('/', function () {
    return redirect()->route('login');
});

Auth::routes([
    'register' => false, // Registrasi hanya dilakukan oleh admin
]);

Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', [App\Http\Controllers\DashboardController::class, 'index'])->name('dashboard');
    Route::post('/notifications/mark-all-read', [App\Http\Controllers\DashboardController::class, 'markNotificationsAsRead'])->name('notifications.mark-all-read');
    Route::redirect('/home', '/dashboard');

    // Khusus Admin Pusat
    Route::middleware(['role:admin_pusat'])->group(function () {
        Route::resource('faskes', App\Http\Controllers\FaskesController::class);
        Route::resource('users', App\Http\Controllers\UserController::class);
        Route::get('/internal-testing', [App\Http\Controllers\InternalTestController::class, 'index'])->name('internal-testing.index');
        Route::post('/internal-testing/run', [App\Http\Controllers\InternalTestController::class, 'run'])->name('internal-testing.run');
        Route::post('/internal-testing/pdf', [App\Http\Controllers\InternalTestController::class, 'exportPdf'])->name('internal-testing.pdf');
        
        // UAT Sandbox Interactive
        Route::get('/uat-sandbox', [App\Http\Controllers\UatSandboxController::class, 'index'])->name('uat-sandbox.index');
        Route::post('/uat-sandbox/login', [App\Http\Controllers\UatSandboxController::class, 'mockLogin'])->name('uat-sandbox.login');
        Route::get('/uat-sandbox/free-tracking', function() {
            return view('uat-sandbox.free-tracking');
        })->name('uat-sandbox.free-tracking');
    });

    // Admin Pusat & Admin Faskes
    // Admin Pusat, Admin Faskes, & Driver (Akses Operasional)
    Route::middleware(['role:admin_pusat,admin_faskes,driver'])->group(function () {
        Route::post('/referrals/{referral}/start-trip', [App\Http\Controllers\ReferralController::class, 'startTrip'])->name('referrals.start-trip');
        Route::get('/tracking/{referral}', [App\Http\Controllers\TrackingController::class, 'show'])->name('tracking.show');
        Route::get('/tracking/{referral}/latest', [App\Http\Controllers\TrackingController::class, 'latestPosition'])->name('tracking.latest');
        Route::post('/tracking/{referral}/location', [App\Http\Controllers\TrackingController::class, 'updateLocation'])->name('tracking.location.update');
        Route::post('/referrals/{referral}/chat', [\App\Http\Controllers\MessageController::class, 'store'])->name('messages.store');
        Route::get('/documents/{document}/download', [App\Http\Controllers\DocumentController::class, 'download'])->name('documents.download');
        Route::post('/referrals/{referral}/arrive', [App\Http\Controllers\ReferralController::class, 'markAsArrived'])->name('referrals.arrive');
    });

    // Admin Pusat & Admin Faskes (Akses Manajerial)
    Route::middleware(['role:admin_pusat,admin_faskes'])->group(function () {
        Route::resource('patients', App\Http\Controllers\PatientController::class);
        Route::resource('bed-capacities', App\Http\Controllers\BedCapacityController::class);
        Route::resource('ambulances', App\Http\Controllers\AmbulanceController::class);
        Route::get('/referrals/export', [App\Http\Controllers\ReferralController::class, 'exportCsv'])->name('referrals.export');
        Route::resource('referrals', App\Http\Controllers\ReferralController::class);
        Route::resource('drivers', App\Http\Controllers\DriverController::class);
        
        // Pelaporan & Analitik
        Route::get('/reports', [App\Http\Controllers\ReportController::class, 'index'])->name('reports.index');
        Route::get('/reports/export/csv', [App\Http\Controllers\ReportController::class, 'exportCsv'])->name('reports.csv');
        Route::get('/reports/export/pdf', [App\Http\Controllers\ReportController::class, 'exportPdf'])->name('reports.pdf');
        
        // Handover & Cetak PDF Rujukan Individual
        Route::get('/referrals/{referral}/pdf', [\App\Http\Controllers\ReferralController::class, 'generatePdf'])->name('referrals.pdf');
        Route::post('/referrals/{referral}/scan-handover', [\App\Http\Controllers\ReferralController::class, 'scanHandover'])->name('referrals.handover');
        
        // Dokumen Rujukan (Upload/Delete)
        Route::post('/referrals/{referral}/documents', [App\Http\Controllers\DocumentController::class, 'store'])->name('documents.store');
        Route::delete('/documents/{document}', [App\Http\Controllers\DocumentController::class, 'destroy'])->name('documents.destroy');
    });
});
