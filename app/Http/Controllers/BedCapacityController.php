<?php

namespace App\Http\Controllers;

use App\Models\BedCapacity;
use Illuminate\Http\Request;

class BedCapacityController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $query = BedCapacity::with('faskes');
        
        if ($user->role !== 'admin_pusat') {
            $query->where('faskes_id', $user->faskes_id);
        }
        
        $beds = $query->paginate(10);

        // Stats for summary cards
        $statsQuery = BedCapacity::query();
        if ($user->role !== 'admin_pusat') {
            $statsQuery->where('faskes_id', $user->faskes_id);
        }
        
        $totalCapacity = $statsQuery->sum('capacity');
        $totalAvailable = $statsQuery->sum('available');
        $occupancyRate = $totalCapacity > 0 ? round((($totalCapacity - $totalAvailable) / $totalCapacity) * 100, 1) : 0;
        
        return view('bed-capacities.index', compact('beds', 'totalCapacity', 'totalAvailable', 'occupancyRate'));
    }

    public function create()
    {
        return view('bed-capacities.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'room_name' => 'required|string|max:255',
            'capacity' => 'required|integer|min:0',
            'available' => 'required|integer|min:0|lte:capacity',
        ]);

        BedCapacity::create([
            'faskes_id' => auth()->user()->faskes_id,
            'room_name' => $request->room_name,
            'capacity' => $request->capacity,
            'available' => $request->available,
            'last_updated' => now(),
        ]);

        return redirect()->route('bed-capacities.index')->with('success', 'Informasi kapasitas tempat tidur ditambahkan.');
    }

    public function edit(BedCapacity $bedCapacity)
    {
        if (auth()->user()->role !== 'admin_pusat' && $bedCapacity->faskes_id !== auth()->user()->faskes_id) {
            abort(403);
        }
        
        return view('bed-capacities.edit', compact('bedCapacity'));
    }

    public function update(Request $request, BedCapacity $bedCapacity)
    {
        if (auth()->user()->role !== 'admin_pusat' && $bedCapacity->faskes_id !== auth()->user()->faskes_id) {
            abort(403);
        }

        $request->validate([
            'room_name' => 'required|string|max:255',
            'capacity' => 'required|integer|min:0',
            'available' => 'required|integer|min:0|lte:capacity',
        ]);

        $bedCapacity->update([
            'room_name' => $request->room_name,
            'capacity' => $request->capacity,
            'available' => $request->available,
            'last_updated' => now(),
        ]);

        return redirect()->route('bed-capacities.index')->with('success', 'Kapasitas berhasil diubah.');
    }

    public function destroy(BedCapacity $bedCapacity)
    {
        if (auth()->user()->role !== 'admin_pusat' && $bedCapacity->faskes_id !== auth()->user()->faskes_id) {
            abort(403);
        }
        
        $bedCapacity->delete();
        return redirect()->route('bed-capacities.index')->with('success', 'Data kapasitas dihapus.');
    }
}
