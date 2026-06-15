<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Faskes;
use App\Models\Referral;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class DriverController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $query = User::with(['faskes', 'referrals'])
                    ->where('role', 'driver');

        if ($user->role !== 'admin_pusat') {
            $query->where('faskes_id', $user->faskes_id);
        }

        $drivers = $query->paginate(10);

        // Calculate statistics
        $statsQuery = User::where('role', 'driver');
        if ($user->role !== 'admin_pusat') {
            $statsQuery->where('faskes_id', $user->faskes_id);
        }

        $total_drivers = $statsQuery->count();
        
        // Count drivers currently on mission (having at least one referral with status 'on_trip' or similar)
        // Note: Logic depends on how 'on_mission' is defined. We check active referrals.
        $on_mission_drivers = User::where('role', 'driver')
            ->when($user->role !== 'admin_pusat', fn($q) => $q->where('faskes_id', $user->faskes_id))
            ->whereHas('referrals', function($q) {
                $q->whereIn('status', ['on_trip', 'process_boarding', 'arrived_at_destination']);
            })->count();

        $stats = [
            'total' => $total_drivers,
            'on_mission' => $on_mission_drivers,
            'available' => $total_drivers - $on_mission_drivers,
            'assigned_faskes' => $user->role === 'admin_pusat' ? Faskes::count() : 1
        ];

        return view('drivers.index', compact('drivers', 'stats'));
    }

    public function create()
    {
        $faskes = Faskes::all();
        return view('drivers.create', compact('faskes'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'faskes_id' => 'required|exists:faskes,id',
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'driver',
            'faskes_id' => $request->faskes_id,
            'is_active' => true,
        ]);

        return redirect()->route('drivers.index')->with('success', 'Sopir baru berhasil didaftarkan.');
    }

    public function edit(User $driver)
    {
        // Policy check
        if (auth()->user()->role !== 'admin_pusat' && $driver->faskes_id !== auth()->user()->faskes_id) {
            abort(403);
        }

        if ($driver->role !== 'driver') {
            abort(404, 'User is not a driver');
        }

        $faskes = Faskes::all();
        return view('drivers.edit', compact('driver', 'faskes'));
    }

    public function update(Request $request, User $driver)
    {
        if (auth()->user()->role !== 'admin_pusat' && $driver->faskes_id !== auth()->user()->faskes_id) {
            abort(403);
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($driver->id)],
            'password' => 'nullable|string|min:8|confirmed',
            'faskes_id' => 'required|exists:faskes,id',
            'is_active' => 'boolean'
        ]);

        $data = [
            'name' => $request->name,
            'email' => $request->email,
            'faskes_id' => $request->faskes_id,
            'is_active' => $request->has('is_active') ? $request->is_active : 0,
        ];

        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        $driver->update($data);

        return redirect()->route('drivers.index')->with('success', 'Data sopir berhasil diperbarui.');
    }

    public function destroy(User $driver)
    {
        if (auth()->user()->role !== 'admin_pusat' && $driver->faskes_id !== auth()->user()->faskes_id) {
            abort(403);
        }

        $driver->delete();
        return redirect()->route('drivers.index')->with('success', 'Sopir telah dihapus dari sistem.');
    }
}
