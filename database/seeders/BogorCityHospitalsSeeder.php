<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Faskes;

class BogorCityHospitalsSeeder extends Seeder
{
    public function run(): void
    {
        $hospitals = [
            [
                'name' => 'RSUD Kota Bogor',
                'type' => 'RS',
                'address' => 'Jalan Dr. Sumeru No. 120, Menteng, Kec. Bogor Barat',
                'latitude' => -6.5843,
                'longitude' => 106.7773,
                'is_active' => true
            ],
            [
                'name' => 'RS dr. H. Marzoeki Mahdi',
                'type' => 'RS',
                'address' => 'Jalan Dr. Sumeru No. 114, Menteng, Kec. Bogor Barat',
                'latitude' => -6.5857,
                'longitude' => 106.7772,
                'is_active' => true
            ],
            [
                'name' => 'RS Hermina Bogor',
                'type' => 'RS',
                'address' => 'Jalan Ring Road Taman Yasmin No. 23, Curugmekar, Kec. Bogor Barat',
                'latitude' => -6.5593,
                'longitude' => 106.7738,
                'is_active' => true
            ],
            [
                'name' => 'RSIA Sawojajar',
                'type' => 'RSIA',
                'address' => 'Jalan Sawojajar No. 9, Pabaton, Kec. Bogor Tengah',
                'latitude' => -6.5912,
                'longitude' => 106.7958,
                'is_active' => true
            ],
            [
                'name' => 'RSIA Pasutri Bogor',
                'type' => 'RSIA',
                'address' => 'Jalan Merak No. 3, Tanahsareal, Kec. Tanah Sereal',
                'latitude' => -6.5789,
                'longitude' => 106.7990,
                'is_active' => true
            ],
            [
                'name' => 'RSU Azra Bogor',
                'type' => 'RS',
                'address' => 'Jalan Raya Pajajaran No. 219, Bantarjati, Kec. Bogor Utara',
                'latitude' => -6.5771,
                'longitude' => 106.8087,
                'is_active' => true
            ],
            [
                'name' => 'RSU Bhayangkara Bogor',
                'type' => 'RS',
                'address' => 'Jalan Kapten Muslihat No. 18, Paledang, Kec. Bogor Tengah',
                'latitude' => -6.5955,
                'longitude' => 106.7932,
                'is_active' => true
            ],
            [
                'name' => 'RSU Bogor Medical Centre (BMC)',
                'type' => 'RS',
                'address' => 'Jalan Pajajaran Indah V No. 97, Baranangsiang, Kec. Bogor Timur',
                'latitude' => -6.6062,
                'longitude' => 106.8090,
                'is_active' => true
            ],
            [
                'name' => 'RSU Islam Bogor',
                'type' => 'RS',
                'address' => 'Jalan Perdana Raya No. 22, Kedungbadak, Kec. Tanah Sereal',
                'latitude' => -6.5588,
                'longitude' => 106.7905,
                'is_active' => true
            ],
            [
                'name' => 'RSU Juliana',
                'type' => 'RS',
                'address' => 'Jalan Raya Tajur No. 75, Tajur, Kec. Bogor Timur',
                'latitude' => -6.6265,
                'longitude' => 106.8245,
                'is_active' => true
            ],
            [
                'name' => 'RSU Medika Dramaga',
                'type' => 'RS',
                'address' => 'Jalan Raya Dramaga No. 7, Margajaya, Kec. Bogor Barat',
                'latitude' => -6.5866,
                'longitude' => 106.7383,
                'is_active' => true
            ],
            [
                'name' => 'RSU Melania',
                'type' => 'RS',
                'address' => 'Jalan Pahlawan No. 91, Bondongan, Kec. Bogor Selatan',
                'latitude' => -6.6133,
                'longitude' => 106.7944,
                'is_active' => true
            ],
            [
                'name' => 'RSU Mulia Pajajaran',
                'type' => 'RS',
                'address' => 'Jalan Raya Pajajaran No. 119, Bantarjati, Kec. Bogor Utara',
                'latitude' => -6.5845,
                'longitude' => 106.8085,
                'is_active' => true
            ],
            [
                'name' => 'Siloam Hospitals Bogor',
                'type' => 'RS',
                'address' => 'Jalan Raya Pajajaran No. 27, Babakan, Kec. Bogor Tengah',
                'latitude' => -6.5968,
                'longitude' => 106.8062,
                'is_active' => true
            ],
            [
                'name' => 'Bogor Senior Hospital',
                'type' => 'RS',
                'address' => 'Jalan Raya Tajur No. 168, Muarasari, Kec. Bogor Selatan',
                'latitude' => -6.6412,
                'longitude' => 106.8375,
                'is_active' => true
            ],
        ];

        foreach ($hospitals as $hospital) {
            Faskes::updateOrCreate(
                ['name' => $hospital['name']],
                $hospital
            );
        }
    }
}
