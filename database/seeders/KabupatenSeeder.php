<?php
// database/seeders/KabupatenSeeder.php

namespace Database\Seeders;

use App\Models\Kabupaten;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class KabupatenSeeder extends Seeder
{
    public function run(): void
    {
        $kabupatens = [
            ['kode' => 'TEM', 'nama' => 'TEMANGGUNG'],
            ['kode' => 'MGL', 'nama' => 'MAGELANG'],
            ['kode' => 'SRG', 'nama' => 'SEMARANG'],
            ['kode' => 'KDL', 'nama' => 'KENDAL'],
            ['kode' => 'DMK', 'nama' => 'DEMAK'],
            ['kode' => 'GG', 'nama' => 'GROBOGAN'],
            ['kode' => 'PAT', 'nama' => 'PATI'],
            ['kode' => 'JPA', 'nama' => 'JEPARA'],
            ['kode' => 'KDU', 'nama' => 'KUDUS'],
            ['kode' => 'RMB', 'nama' => 'REMBANG'],
            ['kode' => 'BLR', 'nama' => 'BLORA'],
            ['kode' => 'BTS', 'nama' => 'BATANG'],
            ['kode' => 'PML', 'nama' => 'PEMALANG'],
            ['kode' => 'TGL', 'nama' => 'TEGAL'],
            ['kode' => 'BRB', 'nama' => 'BREBES'],
            ['kode' => 'PKL', 'nama' => 'PEKALONGAN'],
            ['kode' => 'BMS', 'nama' => 'BANYUMAS'],
            ['kode' => 'PBL', 'nama' => 'PURBALINGGA'],
            ['kode' => 'BNR', 'nama' => 'BANJARNEGARA'],
            ['kode' => 'KBM', 'nama' => 'KEBUMEN'],
            ['kode' => 'PWG', 'nama' => 'PURWOREJO'],
            ['kode' => 'WNS', 'nama' => 'WONOSOBO'],
            ['kode' => 'BJG', 'nama' => 'BOYOLALI'],
            ['kode' => 'SKH', 'nama' => 'SUKOHARJO'],
            ['kode' => 'KLN', 'nama' => 'KLATEN'],
            ['kode' => 'SRK', 'nama' => 'SRAGEN'],
            ['kode' => 'WNG', 'nama' => 'WONOGIRI'],
            ['kode' => 'KRW', 'nama' => 'KARANGANYAR'],
            ['kode' => 'SLT', 'nama' => 'SALATIGA'],
        ];

        foreach ($kabupatens as $kabupaten) {
            Kabupaten::create($kabupaten);
        }
    }
}