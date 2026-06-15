<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Ambulance;
use App\Models\Faskes;

class AmbulanceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. RS Pondok Indah
        $rspi = Faskes::where('name', 'RS Pondok Indah')->first();
        if ($rspi) {
            Ambulance::create(['faskes_id' => $rspi->id, 'police_number' => 'B 1234 ABC', 'vehicle_type' => 'Standard', 'status' => 'standby']);
            Ambulance::create(['faskes_id' => $rspi->id, 'police_number' => 'B 1234 BCD', 'vehicle_type' => 'Advance (ICU)', 'status' => 'standby']);
        }

        // 2. RS Pusat Pertamina
        $rspp = Faskes::where('name', 'RS Pusat Pertamina (RSPP)')->first();
        if ($rspp) {
            Ambulance::create(['faskes_id' => $rspp->id, 'police_number' => 'B 9999 XYZ', 'vehicle_type' => 'Advance (ICU)', 'status' => 'standby']);
        }

        // 3. RSUD Leuwiliang
        $rsudl = Faskes::where('name', 'RSUD Leuwiliang Bogor')->first();
        if ($rsudl) {
            Ambulance::create(['faskes_id' => $rsudl->id, 'police_number' => 'F 1010 RS', 'vehicle_type' => 'Standard', 'status' => 'standby']);
            Ambulance::create(['faskes_id' => $rsudl->id, 'police_number' => 'F 1011 RS', 'vehicle_type' => 'Standard', 'status' => 'standby']);
        }

        // 4. RS Karya Bhakti Pratiwi Dramaga
        $rskbp = Faskes::where('name', 'RS Karya Bhakti Pratiwi Dramaga')->first();
        if ($rskbp) {
            Ambulance::create(['faskes_id' => $rskbp->id, 'police_number' => 'F 2020 KB', 'vehicle_type' => 'Advance (ICU)', 'status' => 'standby']);
        }

        // 5. Puskesmas Cibungbulang
        $pkmc = Faskes::where('name', 'Puskesmas Cibungbulang (Lokasi Anda)')->first();
        if ($pkmc) {
            Ambulance::create(['faskes_id' => $pkmc->id, 'police_number' => 'F 8888 PKM', 'vehicle_type' => 'Standard', 'status' => 'standby']);
        }

        $this->command->info('Data Armada Ambulans (Standard & ICU) berhasil ditanamkan ke dalam Sistem!');
    }
}
