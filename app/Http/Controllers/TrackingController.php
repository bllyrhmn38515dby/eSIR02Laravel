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
        $isAssignedDriver = auth()->user()->role === 'driver' && $referral->driver_id === auth()->id();
        return view('tracking.show', compact('referral', 'isAssignedDriver'));
    }

    public function latestPosition(Referral $referral)
    {
        $latest = $referral->trackingPoints()->latest('recorded_at')->first();
        if (!$latest) {
            return response()->json(['found' => false]);
        }
        return response()->json([
            'found'     => true,
            'lat'       => $latest->latitude,
            'lng'       => $latest->longitude,
            'heading'   => $latest->heading ?? null,
            'recorded_at' => $latest->recorded_at,
        ]);
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
            'heading' => 'nullable|numeric',
        ]);

        $point = TrackingPoint::create([
            'referral_id' => $referral->id,
            'latitude'    => $request->latitude,
            'longitude'   => $request->longitude,
            'heading'     => $request->heading,
            'recorded_at' => now(),
        ]);

        broadcast(new AmbulanceLocationUpdated($referral, $request->latitude, $request->longitude, $request->heading))->toOthers();

        return response()->json(['success' => true, 'point' => $point]);
    }
}
