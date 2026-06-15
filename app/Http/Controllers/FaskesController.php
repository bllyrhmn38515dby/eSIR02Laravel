<?php

namespace App\Http\Controllers;

use App\Models\Faskes;
use Illuminate\Http\Request;

class FaskesController extends Controller
{
    public function index()
    {
        $faskes = Faskes::paginate(10);

        // Stats for summary cards
        $total_faskes = Faskes::count();
        $total_active = Faskes::where('is_active', true)->count();
        $total_rs = Faskes::where('type', 'like', 'rs_tipe%')->count();
        $total_puskesmas = Faskes::where('type', 'puskesmas')->count();

        return view('faskes.index', compact('faskes', 'total_faskes', 'total_active', 'total_rs', 'total_puskesmas'));
    }

    public function create()
    {
        return view('faskes.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|string',
            'address' => 'required|string',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
        ], [
            'required' => 'Kolom :attribute wajib diisi.',
            'numeric' => 'Kolom :attribute harus berupa angka.'
        ]);

        Faskes::create($request->all());

        return redirect()->route('faskes.index')->with('success', 'Faskes berhasil ditambahkan.');
    }

    public function edit(Faskes $faske)
    {
        return view('faskes.edit', ['faskes' => $faske]);
    }

    public function update(Request $request, Faskes $faske)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|string',
            'address' => 'required|string',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
            'is_active' => 'boolean'
        ]);

        $faske->update($request->all());

        return redirect()->route('faskes.index')->with('success', 'Faskes berhasil diperbarui.');
    }

    public function destroy(Faskes $faske)
    {
        $faske->delete();
        return redirect()->route('faskes.index')->with('success', 'Faskes berhasil dihapus.');
    }
}
