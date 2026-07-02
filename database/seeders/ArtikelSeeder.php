<?php

namespace Database\Seeders;

use App\Models\Artikel;
use Illuminate\Database\Seeder;

class ArtikelSeeder extends Seeder
{
    public function run(): void
    {
        $artikels = [
            [
                'judul' => 'Pelatihan Agribisnis Tanaman Pangan Angkatan ke-7 Berhasil Dilaksanakan',
                'ringkasan' => 'Pelatihan agribisnis tanaman pangan untuk 30 peserta dari 5 kabupaten di Jawa Tengah telah berhasil dilaksanakan selama 5 hari.',
                'konten' => '<p>Balai Pelatihan Pertanian (Bapeltan) Jawa Tengah telah berhasil menyelenggarakan Pelatihan Agribisnis Tanaman Pangan Angkatan ke-7. Pelatihan yang berlangsung selama 5 hari ini diikuti oleh 30 peserta dari 5 kabupaten di Jawa Tengah.</p><p>Materi pelatihan meliputi teknologi budidaya padi modern, pengelolaan air, pengendalian hama terpadu, dan pascapanen. Para peserta mendapatkan praktik langsung di lahan percontohan Bapeltan.</p>',
                'penulis' => 'Admin Bapeltan',
                'status' => 'published',
                'urutan' => 1,
            ],
            [
                'judul' => 'Workshop Mekanisasi Pertanian untuk Poktan Se-Jawa Tengah',
                'ringkasan' => 'Workshop penggunaan alat mekanisasi pertanian modern dihadiri oleh 50 kelompok tani dari berbagai kabupaten.',
                'konten' => '<p>Bapeltan Jawa Tengah mengadakan Workshop Mekanisasi Pertanian yang dihadiri oleh perwakilan 50 kelompok tani dari seluruh Jawa Tengah. Workshop ini bertujuan untuk meningkatkan pemahaman petani tentang penggunaan alat mekanisasi modern.</p><p>Peserta mendapatkan materi tentang traktor tangan, pompa air tenaga surya, dan alat panen mekanis. Selain itu, dilakukan juga demontrasi penggunaan drone untuk pemupukan dan pengendalian hama.</p>',
                'penulis' => 'Admin Bapeltan',
                'status' => 'published',
                'urutan' => 2,
            ],
            [
                'judul' => 'Sosialisasi Program Pendampingan Petani 2026',
                'ringkasan' => 'Sosialisasi program pendampingan petani untuk tahun 2026 dengan fokus pada peningkatan produktivitas.',
                'konten' => '<p>Bapeltan Jawa Tengah mengadakan sosialisasi program pendampingan petani untuk tahun 2026. Program ini akan melibatkan 100 pendamping dari berbagai kabupaten.</p><p>Fokus program tahun ini adalah peningkatan produktivitas melalui penggunaan teknologi tepat guna, pengelolaan keuangan petani, dan pemasaran hasil pertanian secara digital.</p>',
                'penulis' => 'Admin Bapeltan',
                'status' => 'draft',
                'urutan' => 3,
            ],
        ];

        foreach ($artikels as $artikel) {
            Artikel::updateOrCreate(
                ['slug' => \Illuminate\Support\Str::slug($artikel['judul'])],
                $artikel
            );
        }
    }
}
