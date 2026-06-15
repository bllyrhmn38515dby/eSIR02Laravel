<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Faskes;
use App\Models\User;
use App\Models\BedCapacity;
use Illuminate\Support\Facades\Hash;

class DummyHospitalSeeder extends Seeder
{
    public function run()
    {
        // 1. RSUD Leuwiliang (Terdekat dari Barat Cibungbulang)
        $faskes = Faskes::create([
            'name' => 'RSUD Leuwiliang Bogor',
            'type' => 'rs_tipe_c',
            'address' => 'Jl. Raya Cibeber 101, Leuwiliang, Kab. Bogor',
            'latitude' => -6.582103, // Titik real
            'longitude' => 106.622557, // Titik real
            'is_active' => true,
        ]);

        User::create([
            'name' => 'Kepala Jaga RSUD Leuwiliang',
            'email' => 'admin@rsudleuwiliang.com',
            'password' => Hash::make('password'),
            'faskes_id' => $faskes->id,
            'role' => 'admin_faskes',
            'is_active' => true,
        ]);

        BedCapacity::create(['faskes_id' => $faskes->id, 'room_name' => 'IGD Utama', 'capacity' => 25, 'available' => 12]);
        BedCapacity::create(['faskes_id' => $faskes->id, 'room_name' => 'ICU / ICCU', 'capacity' => 10, 'available' => 3]);
        BedCapacity::create(['faskes_id' => $faskes->id, 'room_name' => 'Ruang OK (Bedah)', 'capacity' => 5, 'available' => 1]);

        // 2. RS Karya Bhakti Pratiwi Dramaga (Terdekat dari Timur Cibungbulang)
        $faskes2 = Faskes::create([
            'name' => 'RS Karya Bhakti Pratiwi Dramaga',
            'type' => 'rs_tipe_c',
            'address' => 'Jl. Raya Dramaga No.KM 7, Kab. Bogor',
            'latitude' => -6.568340, // Titik Real
            'longitude' => 106.745672, // Titik Real
            'is_active' => true,
        ]);

        User::create([
            'name' => 'Admin RS KBP Dramaga',
            'email' => 'admin@rskbpdramaga.com',
            'password' => Hash::make('password'),
            'faskes_id' => $faskes2->id,
            'role' => 'admin_faskes',
            'is_active' => true,
        ]);

        BedCapacity::create(['faskes_id' => $faskes2->id, 'room_name' => 'IGD Khusus Rujukan', 'capacity' => 18, 'available' => 2]);
        BedCapacity::create(['faskes_id' => $faskes2->id, 'room_name' => 'Ruang Rawat Anak', 'capacity' => 10, 'available' => 8]);
        
        $this->command->info("Data Faskes lokal wilayah Cibungbulang Bogor berhasil dihasilkan!");
    }
}
