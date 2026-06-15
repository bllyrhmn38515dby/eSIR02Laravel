<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Referral;
use App\Models\Faskes;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Response;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        $user = auth()->user();
        
        $start_date = $request->get('start_date', now()->subDays(30)->format('Y-m-d'));
        $end_date = $request->get('end_date', now()->format('Y-m-d'));
        $status = $request->get('status');
        $from_faskes_id = $request->get('from_faskes_id');
        $to_faskes_id = $request->get('to_faskes_id');
        $triage = $request->get('triage');

        $query = Referral::with(['patient', 'fromFaskes', 'toFaskes', 'driver']);

        // Scope by Role
        if ($user->role !== 'admin_pusat') {
            $f_id = $user->faskes_id;
            $query->where(function($q) use ($f_id) {
                $q->where('from_faskes_id', $f_id)
                  ->orWhere('to_faskes_id', $f_id);
            });
        }

        // Filters
        if ($start_date && $end_date) {
            $query->whereBetween('created_at', [$start_date . ' 00:00:00', $end_date . ' 23:59:59']);
        }

        if ($status) {
            $query->where('status', $status);
        }

        if ($from_faskes_id) {
            $query->where('from_faskes_id', $from_faskes_id);
        }

        if ($to_faskes_id) {
            $query->where('to_faskes_id', $to_faskes_id);
        }

        if ($triage) {
            if ($triage === 'P1') {
                $query->where('gcs_score', '<=', 8);
            } elseif ($triage === 'P2') {
                $query->whereBetween('gcs_score', [9, 12]);
            } elseif ($triage === 'P3') {
                $query->where('gcs_score', '>', 12);
            } elseif ($triage === 'none') {
                $query->whereNull('gcs_score');
            }
        }

        $referrals = $query->latest()->paginate(15)->withQueryString();
        $faskes = Faskes::orderBy('name')->get();

        return view('reports.index', compact('referrals', 'faskes', 'start_date', 'end_date', 'status', 'from_faskes_id', 'to_faskes_id', 'triage'));
    }

    public function exportCsv(Request $request)
    {
        $user = auth()->user();
        $query = Referral::with(['patient', 'fromFaskes', 'toFaskes', 'driver']);

        // Apply same filters as index
        if ($user->role !== 'admin_pusat') {
            $f_id = $user->faskes_id;
            $query->where(function($q) use ($f_id) {
                $q->where('from_faskes_id', $f_id)
                  ->orWhere('to_faskes_id', $f_id);
            });
        }

        if ($request->start_date && $request->end_date) {
            $query->whereBetween('created_at', [$request->start_date . ' 00:00:00', $request->end_date . ' 23:59:59']);
        }
        
        if ($request->status) $query->where('status', $request->status);
        if ($request->from_faskes_id) $query->where('from_faskes_id', $request->from_faskes_id);
        if ($request->to_faskes_id) $query->where('to_faskes_id', $request->to_faskes_id);

        $referrals = $query->get();

        $headers = [
            "Content-type"        => "text/csv",
            "Content-Disposition" => "attachment; filename=esir_report_" . date('Ymd_His') . ".csv",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0"
        ];

        $columns = ['ID', 'Nomor Rujukan', 'Pasien', 'NIK', 'Asal Faskes', 'Tujuan Faskes', 'Diagnosis', 'GCS', 'Status', 'Waktu Dibuat'];

        $callback = function() use($referrals, $columns) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $columns);

            foreach ($referrals as $ref) {
                fputcsv($file, [
                    $ref->id,
                    $ref->referral_number,
                    $ref->patient->name,
                    $ref->patient->nik,
                    $ref->fromFaskes->name,
                    $ref->toFaskes->name,
                    $ref->diagnosis,
                    $ref->gcs_score ?? '-',
                    strtoupper($ref->status),
                    $ref->created_at->format('Y-m-d H:i:s'),
                ]);
            }

            fclose($file);
        };

        return Response::stream($callback, 200, $headers);
    }

    public function exportPdf(Request $request)
    {
        $user = auth()->user();
        $query = Referral::with(['patient', 'fromFaskes', 'toFaskes']);

        if ($user->role !== 'admin_pusat') {
            $f_id = $user->faskes_id;
            $query->where(function($q) use ($f_id) {
                $q->where('from_faskes_id', $f_id)
                  ->orWhere('to_faskes_id', $f_id);
            });
        }

        if ($request->start_date && $request->end_date) {
            $query->whereBetween('created_at', [$request->start_date . ' 00:00:00', $request->end_date . ' 23:59:59']);
        }

        $referrals = $query->get();
        $stats = [
            'total' => $referrals->count(),
            'completed' => $referrals->where('status', 'completed')->count(),
            'cancelled' => $referrals->where('status', 'cancelled')->count(),
            'avg_response' => $referrals->avg('response_time_minutes') ?? 0,
        ];

        $pdf = Pdf::loadView('reports.pdf', compact('referrals', 'stats', 'request'));
        return $pdf->download('esir_summary_' . date('Ymd') . '.pdf');
    }
}
