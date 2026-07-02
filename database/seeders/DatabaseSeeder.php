<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            UserSeeder::class,
            KabupatenSeeder::class,
            KegiatanTypeSeeder::class,
            KegiatanSeeder::class,
            TestDataSeeder::class,
            PelatihanTahapanSeeder::class,
            InitTahapanProgressSeeder::class,
            TahapanQuestionsSeeder::class,
            ArtikelSeeder::class,
            PartnerSeeder::class,
        ]);
    }
}
