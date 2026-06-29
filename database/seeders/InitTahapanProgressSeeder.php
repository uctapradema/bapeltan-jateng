<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\RegistrasiUlang;
use App\Models\PelatihanTahapan;
use App\Models\PelatihanTahapanProgress;

class InitTahapanProgressSeeder extends Seeder
{
    public function run(): void
    {
        $accepted = RegistrasiUlang::where('status', 'diterima')->get();
        $count = 0;
        foreach ($accepted as $reg) {
            $firstTahapan = PelatihanTahapan::where('kegiatan_id', $reg->kegiatan_id)->where('urutan', 1)->first();
            if ($firstTahapan && !PelatihanTahapanProgress::where('tahapan_id', $firstTahapan->id)->where('peserta_nik', $reg->peserta_nik)->exists()) {
                PelatihanTahapanProgress::create([
                    'tahapan_id' => $firstTahapan->id,
                    'peserta_nik' => $reg->peserta_nik,
                    'status' => 'active',
                    'completed_at' => null,
                ]);
                $count++;
            }
        }
        $this->command->info("Activated first tahapan for $count registrations");
    }
}
