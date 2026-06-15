<?php

namespace App\Http\Controllers;

use App\Models\Referral;
use App\Models\Faskes;
use App\Models\Patient;
use App\Models\User;
use App\Notifications\ReferralStatusUpdated;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Str;

class ReferralController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $query = Referral::query();
        
        if ($user->role === 'driver') {
            $query->where('driver_id', $user->id);
        } elseif ($user->role !== 'admin_pusat') {
            $query->where(function($q) use ($user) {
                $q->where('from_faskes_id', $user->faskes_id)
                  ->orWhere('to_faskes_id', $user->faskes_id);
            });
        }
        
        $stats = [
            'total' => (clone $query)->count(),
            'pending' => (clone $query)->whereIn('status', ['draft', 'sent'])->count(),
            'active' => (clone $query)->whereIn('status', ['accepted', 'traveling', 'arrived'])->count(),
            'completed_today' => (clone $query)->where('status', 'completed')->whereDate('updated_at', today())->count(),
        ];

        $referrals = $query->with(['patient', 'fromFaskes', 'toFaskes'])->latest()->paginate(10);
        
        return view('referrals.index', compact('referrals', 'stats'));
    }

    public function create()
    {
        $patients = Patient::all();
        $userFaskes = auth()->user()->faskes;
        $faskesQuery = Faskes::with('bedCapacities')->where('id', '!=', auth()->user()->faskes_id)->where('is_active', true)->get();
        
        if ($userFaskes && $userFaskes->latitude && $userFaskes->longitude) {
            $faskes = $faskesQuery->map(function ($f) use ($userFaskes) {
                if ($f->latitude && $f->longitude) {
                    $latFrom = deg2rad($userFaskes->latitude);
                    $lonFrom = deg2rad($userFaskes->longitude);
                    $latTo = deg2rad($f->latitude);
                    $lonTo = deg2rad($f->longitude);
                    $latDelta = $latTo - $latFrom;
                    $lonDelta = $lonTo - $lonFrom;
                    $a = sin($latDelta / 2) * sin($latDelta / 2) + cos($latFrom) * cos($latTo) * sin($lonDelta / 2) * sin($lonDelta / 2);
                    $c = 2 * atan2(sqrt($a), sqrt(1 - $a));
                    $f->distance = 6371 * $c;
                } else {
                    $f->distance = 999999;
                }
                return $f;
            })->sortBy('distance');
        } else {
            $faskes = $faskesQuery;
        }

        return view('referrals.create', compact('patients', 'faskes', 'userFaskes'));
    }

    public function store(Request $request)
    {
        // Check policy
        // $this->authorize('create', Referral::class);

        $request->validate([
            'patient_type' => 'required|in:existing,new',
            'patient_id' => 'required_if:patient_type,existing|exists:patients,id',
            'to_faskes_id' => 'required|exists:faskes,id',
            'diagnosis' => 'required|string',
            'reason' => 'required|string',
            'blood_pressure' => 'nullable|string',
            'heart_rate' => 'nullable|integer',
            'respiratory_rate' => 'nullable|integer',
            'temperature' => 'nullable|numeric',
            'gcs_score' => 'nullable|integer|between:3,15',
            'bed_capacity_id' => 'nullable|exists:bed_capacities,id',
            // Validation for new patient
            'patient_nik' => 'required_if:patient_type,new|nullable|string|size:16|unique:patients,nik',
            'patient_name' => 'required_if:patient_type,new|nullable|string',
            'patient_dob' => 'required_if:patient_type,new|nullable|date',
            'patient_gender' => 'required_if:patient_type,new|nullable|in:L,P',
            'patient_address' => 'nullable|string',
            'patient_contact' => 'nullable|string',
        ]);

        $patientId = $request->patient_id;

        // Jika pasien baru, simpan ke database
        if ($request->patient_type === 'new') {
            $patient = Patient::create([
                'nik' => $request->patient_nik,
                'name' => $request->patient_name,
                'dob' => $request->patient_dob,
                'gender' => $request->patient_gender,
                'address' => $request->patient_address,
                'contact' => $request->patient_contact,
            ]);
            $patientId = $patient->id;
        }

        $referral = Referral::create([
            'referral_number' => 'REF-' . strtoupper(Str::random(6)),
            'patient_id' => $patientId,
            'from_faskes_id' => auth()->user()->faskes_id, // faskes si pembuat saat ini
            'to_faskes_id' => $request->to_faskes_id,
            'created_by' => auth()->id(),
            'diagnosis' => $request->diagnosis,
            'reason' => $request->reason,
            'blood_pressure' => $request->blood_pressure,
            'heart_rate' => $request->heart_rate,
            'respiratory_rate' => $request->respiratory_rate,
            'temperature' => $request->temperature,
            'gcs_score' => $request->gcs_score,
            'bed_capacity_id' => $request->bed_capacity_id,
            'status' => 'draft',
        ]);

        return redirect()->route('referrals.index')->with('success', 'Rujukan berhasil dibuat (Draft).');
    }

    public function generatePdf(Referral $referral)
    {
        $referral->load('patient', 'fromFaskes', 'toFaskes', 'ambulance', 'driver', 'auditLogs', 'createdBy');
        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('referrals.pdf', compact('referral'));
        return $pdf->stream('Surat_Rujukan_'.$referral->referral_number.'.pdf');
    }

    public function scanHandover(Request $request, Referral $referral)
    {
        // Pengecekan keamanan: Hanya faskes tujuan atau admin pusat yang berhak scan arrival
        if (auth()->user()->role !== 'admin_pusat' && auth()->user()->faskes_id !== $referral->to_faskes_id) {
            abort(403, 'Akses scan QR ditolak. Hanya Faskes Tujuan yang berhak menerima Handover.');
        }

        if ($referral->status !== 'arrived') {
            $oldStatus = $referral->status;
            $referral->update(['status' => 'arrived']);
            
            \App\Models\AuditLog::create([
                'user_id' => auth()->id(),
                'referral_id' => $referral->id,
                'action' => 'Smart Handover Scan',
                'description' => "Proses Handover via QR Code Berhasil dilakukan Instalasi Gawat Darurat."
            ]);

            $usersToNotify = \App\Models\User::whereIn('faskes_id', [$referral->from_faskes_id, $referral->to_faskes_id])->get();
            \Illuminate\Support\Facades\Notification::send($usersToNotify, new \App\Notifications\ReferralStatusUpdated($referral));

            // Broadcast real-time update
            broadcast(new \App\Events\ReferralStatusChanged($referral))->toOthers();
        }

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json(['success' => true, 'message' => 'Handover Berhasil!']);
        }

        return redirect()->route('referrals.edit', $referral)->with('success', 'Handover Pasien Berhasil! Status resmi Tiba (Arrived).');
    }

    public function edit(Referral $referral)
    {
        // Check policy
        // $this->authorize('update', $referral);
        
        $referral->load('documents', 'patient', 'fromFaskes', 'toFaskes', 'messages.user', 'auditLogs.user');
        
        $drivers = User::where('role', 'driver')->get();
        // Ambil list armada ambulans milik faskes pengirim
        $ambulances = \App\Models\Ambulance::where('faskes_id', $referral->from_faskes_id)->get();
        
        return view('referrals.edit', compact('referral', 'drivers', 'ambulances'));
    }

    public function update(Request $request, Referral $referral)
    {
        $request->validate([
            'status' => 'required|in:draft,sent,accepted,rejected,traveling,arrived,completed,cancelled',
            'driver_id' => 'nullable|exists:users,id',
            'notes' => 'nullable|string'
        ]);
        $oldStatus = $referral->status;
        
        $dataToUpdate = $request->only('status', 'driver_id', 'ambulance_id', 'notes');

        // Logika Eksekusi Smart Bed Booking (Reservasi Kapasitas) dan Hitung KPI Response Time
        if ($oldStatus !== 'accepted' && $request->status === 'accepted') {
            // Kurangi ketersediaan kamar
            if ($referral->bed_capacity_id) {
                $bed = \App\Models\BedCapacity::find($referral->bed_capacity_id);
                if ($bed && $bed->available > 0) {
                    $bed->decrement('available');
                }
            }
            // Hitung menit waktu respon sejak Faskes A merubah jadi 'Sent' (Updated At terakhhir)
            if (!$referral->response_time_minutes) {
                $dataToUpdate['response_time_minutes'] = now()->diffInMinutes($referral->updated_at);
            }
        } elseif ($oldStatus === 'accepted' && in_array($request->status, ['cancelled', 'rejected'])) {
            // Refund Ketersediaan Kamar
            if ($referral->bed_capacity_id) {
                $bed = \App\Models\BedCapacity::find($referral->bed_capacity_id);
                if ($bed) {
                    $bed->increment('available');
                }
            }
        }

        $referral->update($dataToUpdate);

        // Rekam Jejak Audit
        if ($oldStatus !== $request->status) {
            \App\Models\AuditLog::create([
                'user_id' => auth()->id(),
                'referral_id' => $referral->id,
                'action' => 'Update Status',
                'description' => "Status berubah dari '{$oldStatus}' menjadi '{$request->status}'."
            ]);
        }

        // Kirim Notifikasi ke faskes tujuan dan asal
        $usersToNotify = User::whereIn('faskes_id', [$referral->from_faskes_id, $referral->to_faskes_id])
            ->where('id', '!=', auth()->id()) // Jangan kirimi diri sendiri
            ->get();
        Notification::send($usersToNotify, new ReferralStatusUpdated($referral));

        // Broadcast real-time update
        broadcast(new \App\Events\ReferralStatusChanged($referral))->toOthers();

        return redirect()->route('referrals.index')->with('success', 'Status rujukan berhasil diperbarui.');
    }

    public function startTrip(Referral $referral)
    {
        // Validasi: Hanya sopir yang ditugaskan atau Admin Pusat yang bisa mulai jalan
        if (auth()->user()->role !== 'admin_pusat' && auth()->id() !== $referral->driver_id) {
            abort(403, 'Akses ditolak. Hanya supir yang bertugas atau Admin yang bisa memulai perjalanan.');
        }

        if ($referral->status !== 'accepted') {
            return back()->with('error', 'Status rujukan tidak memungkinkan untuk memulai perjalanan.');
        }

        $referral->update(['status' => 'traveling']);

        // Audit Trail
        \App\Models\AuditLog::create([
            'user_id' => auth()->id(),
            'referral_id' => $referral->id,
            'action' => 'Start Trip',
            'description' => "Sopir " . auth()->user()->name . " telah menyalakan mesin dan memulai perjalanan."
        ]);

        // Broadcast real-time update
        broadcast(new \App\Events\ReferralStatusChanged($referral))->toOthers();

        return redirect()->route('dashboard')->with('success', 'Perjalanan dimulai! Monitor GPS aktif.');
    }

    public function markAsArrived(Referral $referral)
    {
        if ($referral->status === 'traveling') {
            $referral->update(['status' => 'arrived']);
            
            \App\Models\AuditLog::create([
                'user_id' => auth()->id(),
                'referral_id' => $referral->id,
                'action' => 'Auto Arrived',
                'description' => "Ambulans telah tiba di lokasi tujuan (Automated Tracking Arrival)."
            ]);

            // Notifikasi ke faskes tujuan dan asal
            $usersToNotify = User::whereIn('faskes_id', [$referral->from_faskes_id, $referral->to_faskes_id])->get();
            Notification::send($usersToNotify, new ReferralStatusUpdated($referral));

            // Broadcast real-time update
            broadcast(new \App\Events\ReferralStatusChanged($referral))->toOthers();
        }

        return response()->json(['success' => true]);
    }

    public function exportCsv()
    {
        $user = auth()->user();
        if ($user->role === 'admin_pusat') {
            $referrals = Referral::with(['patient', 'fromFaskes', 'toFaskes'])->get();
        } else {
            $referrals = Referral::with(['patient', 'fromFaskes', 'toFaskes'])
                ->where('from_faskes_id', $user->faskes_id)
                ->orWhere('to_faskes_id', $user->faskes_id)
                ->get();
        }

        $filename = "laporan_rujukan_" . date('Y-m-d') . ".csv";
        $handle = fopen('php://output', 'w');

        header('Content-Type: text/csv');
        header('Content-Disposition: attachment; filename="' . $filename . '"');

        fputcsv($handle, ['Nomor Rujukan', 'Pasien', 'Asal Faskes', 'Tujuan Faskes', 'Status', 'Diagnosis', 'Tanggal Buat']);

        foreach ($referrals as $ref) {
            fputcsv($handle, [
                $ref->referral_number,
                $ref->patient->name,
                $ref->fromFaskes ? $ref->fromFaskes->name : '-',
                $ref->toFaskes ? $ref->toFaskes->name : '-',
                $ref->status,
                $ref->diagnosis,
                $ref->created_at->format('Y-m-d H:i:s'),
            ]);
        }

        fclose($handle);
        exit;
    }
}
