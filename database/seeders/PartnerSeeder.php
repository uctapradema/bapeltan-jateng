<?php

namespace Database\Seeders;

use App\Models\Partner;
use Illuminate\Database\Seeder;

class PartnerSeeder extends Seeder
{
    public function run(): void
    {
        $partners = [
            [
                'nama' => 'Dinas Pertanian Provinsi Jawa Tengah',
                'deskripsi' => 'Dinas pertanian tingkat provinsi yang menangani kebijakan pertanian di Jawa Tengah.',
                'website' => 'https://pertanian.jateng.go.id',
                'status' => 'active',
                'urutan' => 1,
            ],
            [
                'nama' => 'Universitas Diponegoro',
                'deskripsi' => 'Universitas negeri di Semarang yang memiliki fakultas pertanian.',
                'website' => 'https://www.undip.ac.id',
                'status' => 'active',
                'urutan' => 2,
            ],
            [
                'nama' => 'Bank Indonesia Perwakilan Jawa Tengah',
                'deskripsi' => 'Kantor perwakilan BI yang mendukung program pemberdayaan petani.',
                'website' => 'https://www.bi.go.id',
                'status' => 'active',
                'urutan' => 3,
            ],
        ];

        foreach ($partners as $partner) {
            Partner::updateOrCreate(
                ['nama' => $partner['nama']],
                $partner
            );
        }
    }
}
