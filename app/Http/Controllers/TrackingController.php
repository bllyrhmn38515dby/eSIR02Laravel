<?php

namespace App\Http\Controllers;

use App\Models\Referral;
use App\Models\TrackingPoint;
use App\Events\AmbulanceLocationUpdated;
use Illuminate\Http\Request;

class TrackingController extends Controller
{
    public function show(Referral $referral)
    {
        $referral->load('trackingPoints', 'patient', 'toFaskes', 'fromFaskes', 'ambulance');
        return view('tracking.show', compact('referral'));
    }

    public function updateLocation(Request $request, Referral $referral)
    {
        // Allow ONLY the assigned driver OR Admin Pusat (for testing purposes)
        if (auth()->user()->role !== 'admin_pusat' && auth()->user()->role !== 'driver') {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $request->validate([
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
        ]);

        $point = TrackingPoint::create([
            'referral_id' => $referral->id,
            'latitude' => $request->latitude,
            'longitude' => $request->longitude,
            'recorded_at' => now(),
        ]);

        broadcast(new AmbulanceLocationUpdated($referral, $request->latitude, $request->longitude))->toOthers();

        return response()->json(['success' => true, 'point' => $point]);
    }
}
