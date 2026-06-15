<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Faskes;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class TempCibungbulangSeeder extends Seeder
{
    public function run()
    {
        $faskes = Faskes::create([
            'name' => 'Puskesmas Cibungbulang (Lokasi Anda)',
            'type' => 'puskesmas',
            'address' => 'Jl. Cimangir Ilir Dukuh, Kec. Cibungbulang, Kabupaten Bogor 16630',
            'latitude' => -6.563410,
            'longitude' => 106.652631,
            'is_active' => true,
        ]);

        User::create([
            'name' => 'Dokter Jaga Cibungbulang',
            'email' => 'dokter@pkmcibungbulang.com',
            'password' => Hash::make('password'),
            'faskes_id' => $faskes->id,
            'role' => 'admin_faskes', // Fix role to admin_faskes!
            'is_active' => true,
        ]);
        
        $this->command->info('User Cibungbulang ditambahkan!');
    }
}
