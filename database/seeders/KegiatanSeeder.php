<?php
// database/seeders/KegiatanSeeder.php

namespace Database\Seeders;

use App\Models\Kegiatan;
use App\Models\KegiatanType;
use Illuminate\Database\Seeder;

class KegiatanSeeder extends Seeder
{
    public function run(): void
    {
        // Ambil ID kegiatan_type berdasarkan nama_type
        $tanamanPangan = KegiatanType::where('nama_type', 'Agribisnis Tanaman Pangan')->firstOrFail();
        $perkebunan     = KegiatanType::where('nama_type', 'Agribisnis Perkebunan')->firstOrFail();
        $mekanisasi     = KegiatanType::where('nama_type', 'Mekanisasi Pertanian')->firstOrFail();

        $kegiatans = [
            [
                'nama_pelatihan'   => 'Pelatihan Agribisnis Tanaman Pangan (Zilenial) 1',
                'kode_pelatihan'   => 'PATPZ-1',
                'tanggal_mulai'    => '2025-10-06',
                'tanggal_selesai'  => '2025-10-09',
                'kuota'            => 30,
                'status'           => 'active',
                'deskripsi'        => 'Pelatihan Agribisnis Tanaman Pangan',
                'kegiatan_type_id' => $tanamanPangan->id,
            ],
            [
                'nama_pelatihan'   => 'Pelatihan Agribisnis Tanaman Pangan (Zilenial) 2',
                'kode_pelatihan'   => 'PATPZ-2',
                'tanggal_mulai'    => '2025-10-06',
                'tanggal_selesai'  => '2025-10-09',
                'kuota'            => 30,
                'status'           => 'active',
                'deskripsi'        => 'Pelatihan Agribisnis Tanaman Pangan',
                'kegiatan_type_id' => $tanamanPangan->id,
            ],
            [
                'nama_pelatihan'   => 'Pelatihan Agribisnis Perkebunan (Zilenial) 1',
                'kode_pelatihan'   => 'PAPZ-1',
                'tanggal_mulai'    => '2025-10-06',
                'tanggal_selesai'  => '2025-10-09',
                'kuota'            => 30,
                'status'           => 'active',
                'deskripsi'        => 'Pelatihan Agribisnis Perkebunan',
                'kegiatan_type_id' => $perkebunan->id,
            ],
            [
                'nama_pelatihan'   => 'Pelatihan Agribisnis Perkebunan (Zilenial) 2',
                'kode_pelatihan'   => 'PAPZ-2',
                'tanggal_mulai'    => '2025-10-14',
                'tanggal_selesai'  => '2025-10-17',
                'kuota'            => 30,
                'status'           => 'active',
                'deskripsi'        => 'Pelatihan Agribisnis Perkebunan',
                'kegiatan_type_id' => $perkebunan->id,
            ],
            [
                'nama_pelatihan'   => 'Pelatihan Mekanisasi Pertanian (Zilenial) 1',
                'kode_pelatihan'   => 'PMPZ-1',
                'tanggal_mulai'    => '2025-10-14',
                'tanggal_selesai'  => '2025-10-17',
                'kuota'            => 30,
                'status'           => 'active',
                'deskripsi'        => 'Pelatihan Mekanisasi Pertanian',
                'kegiatan_type_id' => $mekanisasi->id,
            ],
        ];

        foreach ($kegiatans as $kegiatan) {
            Kegiatan::create($kegiatan);
        }
    }
}
