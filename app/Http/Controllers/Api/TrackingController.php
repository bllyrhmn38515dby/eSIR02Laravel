<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Referral;
use App\Models\TrackingPoint;
use App\Events\AmbulanceLocationUpdated;
use Illuminate\Http\Request;

class TrackingController extends Controller
{
    public function assignedReferrals(Request $request)
    {
        $referrals = Referral::with(['patient', 'fromFaskes', 'toFaskes'])
            ->where('driver_id', $request->user()->id)
            ->whereIn('status', ['accepted', 'traveling'])
            ->get()
            ->map(function ($ref) {
                return [
                    'id' => $ref->id,
                    'referral_number' => $ref->referral_number,
                    'patient_name' => $ref->patient->name,
                    'diagnosis' => $ref->diagnosis,
                    'from_faskes' => $ref->fromFaskes->name,
                    'to_faskes' => $ref->toFaskes->name,
                    'status' => $ref->status,
                ];
            });
            
        return response()->json(['success' => true, 'data' => $referrals]);
    }

    public function updateLocation(Request $request, Referral $referral)
    {
        if ($referral->driver_id !== $request->user()->id) {
            return response()->json(['success' => false, 'message' => 'Anda tidak ditugaskan untuk rujukan ini.'], 403);
        }

        $request->validate([
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
        ]);

        TrackingPoint::create([
            'referral_id' => $referral->id,
            'latitude' => $request->latitude,
            'longitude' => $request->longitude,
            'recorded_at' => now(),
        ]);

        // Trigger Laravel Reverb WebSockets
        broadcast(new AmbulanceLocationUpdated($referral, $request->latitude, $request->longitude))->toOthers();

        return response()->json(['success' => true, 'message' => 'Lokasi terkirim ke server pusat.']);
    }
}
