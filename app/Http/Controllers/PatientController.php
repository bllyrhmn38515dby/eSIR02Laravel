<?php

namespace App\Http\Controllers;

use App\Models\Patient;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class PatientController extends Controller
{
    public function index()
    {
        $patients = Patient::paginate(10);

        // Stats for summary cards
        $total_patients = Patient::count();
        $total_male = Patient::where('gender', 'L')->count();
        $total_female = Patient::where('gender', 'P')->count();
        $registered_today = Patient::whereDate('created_at', now())->count();

        return view('patients.index', compact('patients', 'total_patients', 'total_male', 'total_female', 'registered_today'));
    }

    public function create()
    {
        return view('patients.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nik' => ['required', 'string', 'digits:16', 'unique:patients'],
            'name' => ['required', 'string', 'max:255'],
            'dob' => ['nullable', 'date'],
            'gender' => ['nullable', 'in:L,P'],
            'address' => ['nullable', 'string'],
            'contact' => ['nullable', 'string'],
        ]);

        Patient::create($request->all());

        return redirect()->route('patients.index')->with('success', 'Data pasien berhasil ditambahkan.');
    }

    public function show(Patient $patient)
    {
        // Menyedot memori rekam medis secara kronologis (Descending)
        $patient->load(['referrals' => function($query) {
            $query->with(['fromFaskes', 'toFaskes'])->orderBy('created_at', 'desc');
        }]);

        return view('patients.show', compact('patient'));
    }

    public function edit(Patient $patient)
    {
        return view('patients.edit', compact('patient'));
    }

    public function update(Request $request, Patient $patient)
    {
        $request->validate([
            'nik' => ['required', 'string', 'digits:16', Rule::unique('patients')->ignore($patient->id)],
            'name' => ['required', 'string', 'max:255'],
            'dob' => ['nullable', 'date'],
            'gender' => ['nullable', 'in:L,P'],
            'address' => ['nullable', 'string'],
            'contact' => ['nullable', 'string'],
        ]);

        $patient->update($request->all());

        return redirect()->route('patients.index')->with('success', 'Data pasien berhasil diubah.');
    }

    public function destroy(Patient $patient)
    {
        $patient->delete();
        return redirect()->route('patients.index')->with('success', 'Data pasien dihapus.');
    }
}
