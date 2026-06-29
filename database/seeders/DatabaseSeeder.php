<?php
// database/seeders/DatabaseSeeder.php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            UserSeeder::class,
            // KabupatenSeeder::class,
            // KegiatanTypeSeeder::class,
            // KegiatanSeeder::class,
        ]);
    }
}