<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Referral;

class DashboardController extends Controller
{
    public function __construct()
    {
        // Auth is handled by routes
    }

    public function index(Request $request)
    {
        $user = auth()->user();
        
        $start_date = $request->get('start_date', now()->subDays(30)->format('Y-m-d'));
        $end_date = $request->get('end_date', now()->format('Y-m-d'));

        if ($user->role === 'admin_pusat') {
            $query = Referral::query();
        } else {
            $f_id = $user->faskes_id;
            $query = Referral::where(function($q) use ($f_id) {
                $q->where('from_faskes_id', $f_id)
                  ->orWhere('to_faskes_id', $f_id);
            });
        }

        // Apply Date Filter
        $query->whereBetween('created_at', [$start_date . ' 00:00:00', $end_date . ' 23:59:59']);

        // Stats
        $referrals_active = (clone $query)->whereNotIn('status', ['completed', 'cancelled', 'draft'])->count();
        $referrals_waiting = (clone $query)->whereIn('status', ['draft', 'sent'])->count();
        $referrals_completed = (clone $query)->where('status', 'completed')->count();
        $avg_response_time = (clone $query)->whereNotNull('response_time_minutes')->avg('response_time_minutes') ?? 0;

        $recent_referrals = (clone $query)->with(['patient', 'fromFaskes', 'toFaskes'])
            ->latest()
            ->take(5)
            ->get();
        
        $referral_stats = (clone $query)->selectRaw('DATE(created_at) as date, count(*) as count')
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        // Analitik Lanjutan: Top Diagnosa (ICD-10)
        $top_diagnoses = (clone $query)->selectRaw('diagnosis, count(*) as count')
            ->groupBy('diagnosis')
            ->orderBy('count', 'desc')
            ->take(5)
            ->get();
        
        // Tren Bulanan
        $monthly_stats = (clone $query)->selectRaw('MONTHNAME(created_at) as month, count(*) as count')
            ->whereYear('created_at', date('Y'))
            ->groupBy('month')
            ->get();

        // Distribusi Triase (GCS Based)
        $triage_stats = (clone $query)->selectRaw("
            CASE 
                WHEN gcs_score IS NULL THEN 'Non-Emergency'
                WHEN gcs_score <= 8 THEN 'P1 - Red'
                WHEN gcs_score <= 12 THEN 'P2 - Yellow'
                ELSE 'P3 - Green'
            END as triage,
            count(*) as count
        ")
        ->groupBy('triage')
        ->get();
        
        if ($user->role === 'driver') {
            $active_mission = Referral::with(['patient', 'fromFaskes', 'toFaskes'])
                ->where('driver_id', $user->id)
                ->whereIn('status', ['accepted', 'traveling'])
                ->first();
            
            $total_missions = Referral::where('driver_id', $user->id)
                ->whereIn('status', ['arrived', 'completed'])
                ->count();

            $recent_missions = Referral::with(['patient', 'toFaskes'])
                ->where('driver_id', $user->id)
                ->whereIn('status', ['completed', 'arrived'])
                ->latest()
                ->take(3)
                ->get();

            return view('dashboard.driver', compact('active_mission', 'total_missions', 'recent_missions'));
        }

        return view('dashboard.index', compact(
            'referrals_active', 
            'referrals_waiting', 
            'referrals_completed',
            'avg_response_time',
            'recent_referrals',
            'referral_stats',
            'top_diagnoses',
            'monthly_stats',
            'triage_stats',
            'start_date',
            'end_date'
        ));
    }

    public function markNotificationsAsRead()
    {
        auth()->user()->unreadNotifications->markAsRead();
        return back()->with('success', 'Semua notifikasi ditandai telah dibaca.');
    }
}
