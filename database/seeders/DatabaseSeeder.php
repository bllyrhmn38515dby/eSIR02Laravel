<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Faskes;
use App\Models\Patient;
use App\Models\BedCapacity;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Seed Faskes
        $puskesmasA = Faskes::create([
            'name' => 'Puskesmas Kebon Jeruk',
            'type' => 'puskesmas',
            'address' => 'Jl. Kebon Jeruk No 1',
            'latitude' => -6.1944,
            'longitude' => 106.7644,
        ]);

        $rsKembangan = Faskes::create([
            'name' => 'RSUD Kembangan',
            'type' => 'rs_tipe_c',
            'address' => 'Jl. Kembangan Raya',
            'latitude' => -6.1822,
            'longitude' => 106.7370,
        ]);

        $rsTarakan = Faskes::create([
            'name' => 'RSUD Tarakan',
            'type' => 'rs_tipe_a',
            'address' => 'Jl. Kyai Caringin',
            'latitude' => -6.1738,
            'longitude' => 106.8091,
        ]);

        // 2. Seed Users
        User::create([
            'name' => 'Admin Pusat eSIR',
            'email' => 'admin@esir.id',
            'password' => Hash::make('password'),
            'role' => 'admin_pusat',
            'faskes_id' => null,
        ]);

        User::create([
            'name' => 'Admin Puskesmas KJ',
            'email' => 'adminkj@esir.id',
            'password' => Hash::make('password'),
            'role' => 'admin_faskes',
            'faskes_id' => $puskesmasA->id,
        ]);

        User::create([
            'name' => 'Admin RS Kembangan',
            'email' => 'adminrsk@esir.id',
            'password' => Hash::make('password'),
            'role' => 'admin_faskes',
            'faskes_id' => $rsKembangan->id,
        ]);

        User::create([
            'name' => 'Supir Ambulans Budi',
            'email' => 'driver1@esir.id',
            'password' => Hash::make('password'),
            'role' => 'driver',
            'faskes_id' => $puskesmasA->id,
        ]);

        // 3. Seed Patients
        Patient::create([
            'nik' => '3173000000000001',
            'name' => 'Pasien Gawat 1',
            'dob' => '1990-01-01',
            'gender' => 'L',
            'address' => 'Jl. Pasien No. 1',
            'contact' => '08123456789',
        ]);

        Patient::create([
            'nik' => '3173000000000002',
            'name' => 'Pasien Ibu Hamil',
            'dob' => '1995-05-05',
            'gender' => 'P',
            'address' => 'Jl. Pasien No. 2',
            'contact' => '08198765432',
        ]);

        // 4. Seed Bed Capacities
        BedCapacity::create([
            'faskes_id' => $rsKembangan->id,
            'room_name' => 'IGD',
            'capacity' => 10,
            'available' => 5,
        ]);
        
        BedCapacity::create([
            'faskes_id' => $rsKembangan->id,
            'room_name' => 'ICU',
            'capacity' => 4,
            'available' => 1,
        ]);

        BedCapacity::create([
            'faskes_id' => $rsTarakan->id,
            'room_name' => 'IGD',
            'capacity' => 20,
            'available' => 2,
        ]);
        
        BedCapacity::create([
            'faskes_id' => $rsTarakan->id,
            'room_name' => 'ICU',
            'capacity' => 10,
            'available' => 0,
        ]);
        
        BedCapacity::create([
            'faskes_id' => $rsTarakan->id,
            'room_name' => 'NICU',
            'capacity' => 5,
            'available' => 2,
        ]);
    }
}
