<?php

namespace App\Http\Controllers;

use App\Models\Ambulance;
use Illuminate\Http\Request;

class AmbulanceController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        if ($user->role === 'admin_pusat') {
            $ambulances = Ambulance::with('faskes')->paginate(10);
            $stats = [
                'total' => Ambulance::count(),
                'standby' => Ambulance::where('status', 'standby')->count(),
                'on_trip' => Ambulance::where('status', 'on_trip')->count(),
                'maintenance' => Ambulance::where('status', 'maintenance')->count(),
            ];
        } else {
            $ambulances = Ambulance::with('faskes')->where('faskes_id', $user->faskes_id)->paginate(10);
            $stats = [
                'total' => Ambulance::where('faskes_id', $user->faskes_id)->count(),
                'standby' => Ambulance::where('faskes_id', $user->faskes_id)->where('status', 'standby')->count(),
                'on_trip' => Ambulance::where('faskes_id', $user->faskes_id)->where('status', 'on_trip')->count(),
                'maintenance' => Ambulance::where('faskes_id', $user->faskes_id)->where('status', 'maintenance')->count(),
            ];
        }
        return view('ambulances.index', compact('ambulances', 'stats'));
    }

    public function create()
    {
        $faskes = null;
        if (auth()->user()->role === 'admin_pusat') {
            $faskes = \App\Models\Faskes::orderBy('name')->get();
        }
        return view('ambulances.create', compact('faskes'));
    }

    public function store(Request $request)
    {
        $rules = [
            'police_number' => 'required|string|unique:ambulances',
            'vehicle_type' => 'required|string',
            'status' => 'required|in:standby,on_trip,maintenance',
        ];

        if (auth()->user()->role === 'admin_pusat') {
            $rules['faskes_id'] = 'required|exists:faskes,id';
        }

        $request->validate($rules);

        Ambulance::create([
            'faskes_id' => auth()->user()->role === 'admin_pusat' ? $request->faskes_id : auth()->user()->faskes_id,
            'police_number' => strtoupper($request->police_number),
            'vehicle_type' => $request->vehicle_type,
            'status' => $request->status,
        ]);

        return redirect()->route('ambulances.index')->with('success', 'Armada ambulans berhasil didaftarkan.');
    }

    public function edit(Ambulance $ambulance)
    {
        // Policy Check
        if (auth()->user()->role !== 'admin_pusat' && $ambulance->faskes_id !== auth()->user()->faskes_id) {
            abort(403);
        }

        return view('ambulances.edit', compact('ambulance'));
    }

    public function update(Request $request, Ambulance $ambulance)
    {
        if (auth()->user()->role !== 'admin_pusat' && $ambulance->faskes_id !== auth()->user()->faskes_id) {
            abort(403);
        }

        $request->validate([
            'police_number' => 'required|string|unique:ambulances,police_number,' . $ambulance->id,
            'vehicle_type' => 'required|string',
            'status' => 'required|in:standby,on_trip,maintenance',
        ]);

        $ambulance->update([
            'police_number' => strtoupper($request->police_number),
            'vehicle_type' => $request->vehicle_type,
            'status' => $request->status,
        ]);

        return redirect()->route('ambulances.index')->with('success', 'Data ambulans berhasil diperbarui.');
    }

    public function destroy(Ambulance $ambulance)
    {
        if (auth()->user()->role !== 'admin_pusat' && $ambulance->faskes_id !== auth()->user()->faskes_id) {
            abort(403);
        }

        $ambulance->delete();
        return redirect()->route('ambulances.index')->with('success', 'Ambulans dihapus dari sistem.');
    }
}
