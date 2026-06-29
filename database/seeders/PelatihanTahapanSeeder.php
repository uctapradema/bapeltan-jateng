<?php

namespace Database\Seeders;

use App\Models\Kegiatan;
use App\Models\PelatihanTahapan;
use Illuminate\Database\Seeder;

class PelatihanTahapanSeeder extends Seeder
{
    public function run(): void
    {
        $tahapanTemplate = [
            ['nama' => 'Registrasi Ulang', 'deskripsi' => 'Konfirmasi kehadiran dan melengkapi data peserta', 'tipe' => 'sekali'],
            ['nama' => 'Grup WhatsApp', 'deskripsi' => 'Gabung grup WhatsApp untuk koordinasi selama pelatihan', 'tipe' => 'sekali'],
            ['nama' => 'Evaluasi Harapan', 'deskripsi' => 'Isi evaluasi harapan dan tujuan mengikuti pelatihan', 'tipe' => 'sekali'],
            ['nama' => 'Evaluasi Materi Awal', 'deskripsi' => 'Evaluasi pemahaman awal sebelum pelatihan dimulai', 'tipe' => 'sekali'],
            ['nama' => 'Pre Test', 'deskripsi' => 'Tes pengetahuan awal sebelum mengikuti materi', 'tipe' => 'sekali'],
            ['nama' => 'Registrasi Zilenial', 'deskripsi' => 'Pendaftaran data zilenial peserta', 'tipe' => 'sekali'],
            ['nama' => 'Rencana Tindak Lanjut', 'deskripsi' => 'Rencana aksi setelah pelatihan selesai', 'tipe' => 'sekali'],
            ['nama' => 'Daily Mood', 'deskripsi' => 'Catat mood harian selama pelatihan berlangsung', 'tipe' => 'harian'],
            ['nama' => 'Evaluasi Materi Akhir', 'deskripsi' => 'Evaluasi pemahaman materi setelah pelatihan', 'tipe' => 'sekali'],
            ['nama' => 'Evaluasi Kenyataan', 'deskripsi' => 'Evaluasi kesesuaian harapan dengan kenyataan', 'tipe' => 'sekali'],
            ['nama' => 'Evaluasi Fasilitator', 'deskripsi' => 'Penilaian terhadap kinerja fasilitator', 'tipe' => 'sekali'],
            ['nama' => 'Post Test', 'deskripsi' => 'Tes akhir untuk mengukur pemahaman akhir', 'tipe' => 'sekali'],
        ];

        $activeKegiatans = Kegiatan::where('status', 'active')->get();

        foreach ($activeKegiatans as $kegiatan) {
            foreach ($tahapanTemplate as $i => $t) {
                PelatihanTahapan::updateOrCreate(
                    ['kegiatan_id' => $kegiatan->id, 'urutan' => $i + 1],
                    [
                        'nama' => $t['nama'],
                        'deskripsi' => $t['deskripsi'],
                        'tipe' => $t['tipe'],
                        'wajib' => true,
                    ]
                );
            }
        }

        $this->command->info('Pelatihan tahapan seeded for ' . $activeKegiatans->count() . ' kegiatan.');
    }
}
