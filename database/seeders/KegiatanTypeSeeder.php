<?php
// database/seeders/KegiatanSeeder.php

namespace Database\Seeders;

use App\Models\KegiatanType;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class KegiatanTypeSeeder extends Seeder
{
    public function run()
    {
        KegiatanType::insert([
            ['id' => 1, 'nama_type' => 'Agribisnis Tanaman Pangan'],
            ['id' => 2, 'nama_type' => 'Agribisnis Perkebunan'],
            ['id' => 3, 'nama_type' => 'Mekanisasi Pertanian'],
        ]);
    }
}
