<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Faskes;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    public function index()
    {
        $users = User::with('faskes')->paginate(10);

        // Stats for summary cards
        $total_users = User::count();
        $total_admins = User::whereIn('role', ['admin_pusat', 'admin_faskes'])->count();
        $total_drivers = User::where('role', 'driver')->count();
        $active_users = User::where('is_active', true)->count();

        return view('users.index', compact('users', 'total_users', 'total_admins', 'total_drivers', 'active_users'));
    }

    public function create()
    {
        $faskes = Faskes::all();
        return view('users.create', compact('faskes'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'role' => ['required', 'in:admin_pusat,admin_faskes,driver'],
            'faskes_id' => ['nullable', 'exists:faskes,id', 'required_unless:role,admin_pusat'],
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
            'faskes_id' => $request->role === 'admin_pusat' ? null : $request->faskes_id,
            'is_active' => true,
        ]);

        return redirect()->route('users.index')->with('success', 'Pengguna berhasil ditambahkan.');
    }

    public function edit(User $user)
    {
        $faskes = Faskes::all();
        return view('users.edit', compact('user', 'faskes'));
    }

    public function update(Request $request, User $user)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'password' => ['nullable', 'string', 'min:8', 'confirmed'],
            'role' => ['required', 'in:admin_pusat,admin_faskes,driver'],
            'faskes_id' => ['nullable', 'exists:faskes,id', 'required_unless:role,admin_pusat'],
            'is_active' => ['boolean']
        ]);

        $data = [
            'name' => $request->name,
            'email' => $request->email,
            'role' => $request->role,
            'faskes_id' => $request->role === 'admin_pusat' ? null : $request->faskes_id,
            'is_active' => $request->has('is_active') ? $request->is_active : 0,
        ];

        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        $user->update($data);

        return redirect()->route('users.index')->with('success', 'Pengguna berhasil diperbarui.');
    }

    public function destroy(User $user)
    {
        // Cegah hapus diri sendiri
        if ($user->id === auth()->id()) {
            return redirect()->route('users.index')->with('error', 'Tidak dapat menghapus akun sendiri.');
        }

        $user->delete();
        return redirect()->route('users.index')->with('success', 'Pengguna berhasil dihapus.');
    }
}
