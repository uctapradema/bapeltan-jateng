<?php

namespace Database\Seeders;

use App\Models\Evaluasi;
use App\Models\EvaluasiQuestion;
use App\Models\EvaluasiQuestionOption;
use App\Models\EvaluasiType;
use App\Models\Group;
use App\Models\Kegiatan;
use App\Models\KegiatanType;
use App\Models\Peserta;
use App\Models\RegistrasiUlang;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class TestDataSeeder extends Seeder
{
    public function run(): void
    {
        // ── Kegiatan Types ──
        $types = collect([
            ['nama_type' => 'Pelatihan Keahlian Pertanian'],
            ['nama_type' => 'Penyuluhan Pertanian'],
            ['nama_type' => 'Pelatihan Pengolahan Pangan'],
            ['nama_type' => 'Pelatihan Manajemen Usaha Tani'],
        ])->map(fn ($t) => KegiatanType::updateOrCreate(['nama_type' => $t['nama_type']], $t));

        // ── Kegiatans ──
        $kegiatans = collect([
            [
                'kegiatan_type_id' => $types[0]->id,
                'nama_pelatihan' => 'Pelatihan Teknis Budidaya Padi Organik',
                'kode_pelatihan' => 'PMA-001',
                'tanggal_mulai' => '2026-07-01',
                'tanggal_selesai' => '2026-07-05',
                'kuota' => 30,
                'status' => 'active',
                'deskripsi' => 'Pelatihan budidaya padi organik selama 5 hari.',
            ],
            [
                'kegiatan_type_id' => $types[0]->id,
                'nama_pelatihan' => 'Pelatihan Keamanan Pangan dan Good Handling Practices',
                'kode_pelatihan' => 'PMA-002',
                'tanggal_mulai' => '2026-07-10',
                'tanggal_selesai' => '2026-07-12',
                'kuota' => 25,
                'status' => 'active',
                'deskripsi' => 'Pelatihan keamanan pangan untuk petani millenial.',
            ],
            [
                'kegiatan_type_id' => $types[1]->id,
                'nama_pelatihan' => 'Penyuluhan Penggunaan Pupuk Organik',
                'kode_pelatihan' => 'PEN-001',
                'tanggal_mulai' => '2026-07-15',
                'tanggal_selesai' => '2026-07-15',
                'kuota' => 50,
                'status' => 'active',
                'deskripsi' => 'Penyuluhan satu hari tentang pupuk organik.',
            ],
            [
                'kegiatan_type_id' => $types[2]->id,
                'nama_pelatihan' => 'Pelatihan Olahan Makanan Berbahan Baku Jagung',
                'kode_pelatihan' => 'OLH-001',
                'tanggal_mulai' => '2026-07-20',
                'tanggal_selesai' => '2026-07-22',
                'kuota' => 20,
                'status' => 'active',
                'deskripsi' => 'Pelatihan pengolahan jagung menjadi produk siap jual.',
            ],
            [
                'kegiatan_type_id' => $types[3]->id,
                'nama_pelatihan' => 'Pelatihan Manajemen Keuangan Kelompok Tani',
                'kode_pelatihan' => 'MGT-001',
                'tanggal_mulai' => '2026-08-01',
                'tanggal_selesai' => '2026-08-03',
                'kuota' => 35,
                'status' => 'active',
                'deskripsi' => 'Pelatihan pengelolaan keuangan untuk kelompok tani.',
            ],
            [
                'kegiatan_type_id' => $types[0]->id,
                'nama_pelatihan' => 'Pelatihan Sertifikasi Keterampilan Pertanian',
                'kode_pelatihan' => 'PMA-003',
                'tanggal_mulai' => '2026-06-01',
                'tanggal_selesai' => '2026-06-05',
                'kuota' => 15,
                'status' => 'inactive',
                'deskripsi' => 'Pelatihan yang sudah selesai.',
            ],
        ])->map(fn ($k) => Kegiatan::updateOrCreate(['kode_pelatihan' => $k['kode_pelatihan']], $k));

        // ── Users + Pesertas ──
        $pesertaData = [
            [
                'user_email' => 'andi@gmail.com',
                'user_name' => 'ANDI SAPUTRA',
                'nik' => '3323011234560001',
                'kabupaten_id' => 1,
                'nama' => 'ANDI SAPUTRA',
                'tempat_lahir' => 'Semarang',
                'tanggal_lahir' => '1990-05-15',
                'nomor_telepon' => '081234567890',
                'agama' => 'Islam',
                'jenis_kelamin' => 'Laki-laki',
                'status_pernikahan' => 'MENIKAH',
                'pendidikan_terakhir' => 'S1',
                'pekerjaan' => 'Petani',
                'usaha_tani' => 'Padi Organik',
                'alamat_lengkap' => 'Jl. Merdeka No. 10, Semarang',
                'nama_poktan' => 'Poktan Tani Makmur',
                'alamat_poktan' => 'Jl. Desa No. 5',
            ],
            [
                'user_email' => 'siti@gmail.com',
                'user_name' => 'SITI RAHAYU',
                'nik' => '3323011234560002',
                'kabupaten_id' => 2,
                'nama' => 'SITI RAHAYU',
                'tempat_lahir' => 'Solo',
                'tanggal_lahir' => '1992-08-20',
                'nomor_telepon' => '082345678901',
                'agama' => 'Islam',
                'jenis_kelamin' => 'Perempuan',
                'status_pernikahan' => 'MENIKAH',
                'pendidikan_terakhir' => 'SMA',
                'pekerjaan' => 'Petani',
                'usaha_tani' => 'Sayuran',
                'alamat_lengkap' => 'Jl. Pandanaran No. 20, Surakarta',
                'nama_poktan' => 'Poktan Harapan Jaya',
                'alamat_poktan' => 'Jl. Sawah No. 3',
            ],
            [
                'user_email' => 'budi@gmail.com',
                'user_name' => 'BUDI CAHYONO',
                'nik' => '3323011234560003',
                'kabupaten_id' => 3,
                'nama' => 'BUDI CAHYONO',
                'tempat_lahir' => 'Magelang',
                'tanggal_lahir' => '1988-03-10',
                'nomor_telepon' => '083456789012',
                'agama' => 'Islam',
                'jenis_kelamin' => 'Laki-laki',
                'status_pernikahan' => 'MENIKAH',
                'pendidikan_terakhir' => 'SMP',
                'pekerjaan' => 'Petani',
                'usaha_tani' => 'Jagung',
                'alamat_lengkap' => 'Jl. Pemuda No. 30, Magelang',
                'nama_poktan' => 'Poktan Subur',
                'alamat_poktan' => 'Jl. Dukuh No. 7',
            ],
            [
                'user_email' => 'dewi@gmail.com',
                'user_name' => 'DEWI LESTARI',
                'nik' => '3323011234560004',
                'kabupaten_id' => 4,
                'nama' => 'DEWI LESTARI',
                'tempat_lahir' => 'Purwokerto',
                'tanggal_lahir' => '1995-11-25',
                'nomor_telepon' => '084567890123',
                'agama' => 'Hindu',
                'jenis_kelamin' => 'Perempuan',
                'status_pernikahan' => 'BELUM MENIKAH',
                'pendidikan_terakhir' => 'D3',
                'pekerjaan' => 'Wiraswasta',
                'usaha_tani' => 'Olahan Pangan',
                'alamat_lengkap' => 'Jl. Jendral Sudirman No. 40, Purwokerto',
                'nama_poktan' => 'Poktan Wanita Tani',
                'alamat_poktan' => 'Jl. Pasar No. 2',
            ],
            [
                'user_email' => 'eko@gmail.com',
                'user_name' => 'EKO WIDODO',
                'nik' => '3323011234560005',
                'kabupaten_id' => 5,
                'nama' => 'EKO WIDODO',
                'tempat_lahir' => 'Tegal',
                'tanggal_lahir' => '1985-07-30',
                'nomor_telepon' => '085678901234',
                'agama' => 'Islam',
                'jenis_kelamin' => 'Laki-laki',
                'status_pernikahan' => 'CERAI HIDUP',
                'pendidikan_terakhir' => 'S1',
                'pekerjaan' => 'Penyuluh Pertanian',
                'usaha_tani' => 'Padi',
                'alamat_lengkap' => 'Jl. Gajah Mada No. 50, Tegal',
                'nama_poktan' => 'Poktan Maju Bersama',
                'alamat_poktan' => 'Jl. Raya No. 10',
            ],
        ];

        $pesertas = collect();
        foreach ($pesertaData as $pd) {
            $user = User::updateOrCreate(
                ['email' => $pd['user_email']],
                [
                    'name' => $pd['user_name'],
                    'password' => Hash::make('password'),
                    'role' => 'peserta',
                    'email_verified_at' => now(),
                ]
            );

            $peserta = Peserta::updateOrCreate(
                ['nik' => $pd['nik']],
                [
                    'user_id' => $user->id,
                    'kabupaten_id' => $pd['kabupaten_id'],
                    'nama' => $pd['nama'],
                    'tempat_lahir' => $pd['tempat_lahir'],
                    'tanggal_lahir' => $pd['tanggal_lahir'],
                    'nomor_telepon' => $pd['nomor_telepon'],
                    'agama' => $pd['agama'],
                    'jenis_kelamin' => $pd['jenis_kelamin'],
                    'status_pernikahan' => $pd['status_pernikahan'],
                    'pendidikan_terakhir' => $pd['pendidikan_terakhir'],
                    'pekerjaan' => $pd['pekerjaan'],
                    'usaha_tani' => $pd['usaha_tani'],
                    'alamat_lengkap' => $pd['alamat_lengkap'],
                    'nama_poktan' => $pd['nama_poktan'],
                    'alamat_poktan' => $pd['alamat_poktan'],
                    'email' => $pd['user_email'],
                ]
            );
            $pesertas->push($peserta);
        }

        // ── Registrasi Ulangs ──
        // Unique constraint: (peserta_nik, kegiatan_type_id, tahun) — 1 peserta per jenis per tahun
        // kegiatans[0] = type 1, [1] = type 1, [2] = type 2, [3] = type 3, [4] = type 4, [5] = type 1
        $registrasiData = [
            ['peserta_nik' => $pesertas[0]->nik, 'kegiatan_id' => $kegiatans[0]->id, 'status' => 'diterima'],
            ['peserta_nik' => $pesertas[1]->nik, 'kegiatan_id' => $kegiatans[2]->id, 'status' => 'selesai'],
            ['peserta_nik' => $pesertas[2]->nik, 'kegiatan_id' => $kegiatans[3]->id, 'status' => 'diterima'],
            ['peserta_nik' => $pesertas[3]->nik, 'kegiatan_id' => $kegiatans[4]->id, 'status' => 'pending'],
            ['peserta_nik' => $pesertas[4]->nik, 'kegiatan_id' => $kegiatans[0]->id, 'status' => 'ditolak'],
            ['peserta_nik' => $pesertas[0]->nik, 'kegiatan_id' => $kegiatans[2]->id, 'status' => 'pending'],
        ];

        foreach ($registrasiData as $rd) {
            $kegiatan = Kegiatan::find($rd['kegiatan_id']);
            RegistrasiUlang::updateOrCreate(
                ['peserta_nik' => $rd['peserta_nik'], 'kegiatan_id' => $rd['kegiatan_id']],
                [
                    'kegiatan_type_id' => $kegiatan->kegiatan_type_id,
                    'tahun' => now()->year,
                    'status' => $rd['status'],
                ]
            );
        }

        // ── Groups ──
        Group::updateOrCreate(
            ['name' => 'Grup Budidaya Padi'],
            [
                'group_link' => 'https://chat.whatsapp.com/example1',
                'group_username' => 'padi_group',
                'description' => 'Grup diskusi budidaya padi organik',
                'status' => 'active',
                'kegiatan_id' => $kegiatans[0]->id,
            ]
        );
        Group::updateOrCreate(
            ['name' => 'Grup Keamanan Pangan'],
            [
                'group_link' => 'https://chat.whatsapp.com/example2',
                'group_username' => 'pangan_group',
                'description' => 'Grup diskusi keamanan pangan',
                'status' => 'active',
                'kegiatan_id' => $kegiatans[1]->id,
            ]
        );

        // ── Evaluasi Types ──
        $evalTypes = collect([
            ['nama' => 'Pre-Test', 'deskripsi' => 'Evaluasi sebelum pelatihan'],
            ['nama' => 'Post-Test', 'deskripsi' => 'Evaluasi setelah pelatihan'],
            ['nama' => 'Evaluasi Kepuasan', 'deskripsi' => 'Survei kepuasan peserta'],
        ])->map(fn ($t) => EvaluasiType::updateOrCreate(['nama' => $t['nama']], $t));

        // ── Evaluasi + Questions ──
        $evaluasi = Evaluasi::updateOrCreate(
            ['judul' => 'Post-Test Pelatihan Padi Organik'],
            [
                'kegiatan_id' => $kegiatans[0]->id,
                'evaluasi_type_id' => $evalTypes[1]->id,
            ]
        );

        $questions = [
            ['pertanyaan' => 'Apa manfaat utama pupuk organik?', 'tipe_jawaban' => 'pilihan_ganda', 'urutan' => 1],
            ['pertanyaan' => 'Sebutkan minimal 3 teknik pengolahan tanah!', 'tipe_jawaban' => 'text', 'urutan' => 2],
            ['pertanyaan' => 'Apakah pelatihan ini bermanfaat?', 'tipe_jawaban' => 'pilihan_ganda', 'urutan' => 3],
        ];

        foreach ($questions as $q) {
            $question = EvaluasiQuestion::updateOrCreate(
                ['evaluasi_id' => $evaluasi->id, 'urutan' => $q['urutan']],
                [
                    'pertanyaan' => $q['pertanyaan'],
                    'tipe_jawaban' => $q['tipe_jawaban'],
                ]
            );

            if ($q['tipe_jawaban'] === 'pilihan_ganda') {
                if ($q['urutan'] === 1) {
                    $options = [
                        ['value' => 'Meningkatkan kesuburan tanah', 'is_correct' => true],
                        ['value' => 'Mempercepat pertumbuhan gulma', 'is_correct' => false],
                        ['value' => 'Mengurangi hasil panen', 'is_correct' => false],
                    ];
                } else {
                    $options = [
                        ['value' => 'Sangat Bermanfaat', 'is_correct' => false],
                        ['value' => 'Bermanfaat', 'is_correct' => false],
                        ['value' => 'Kurang Bermanfaat', 'is_correct' => false],
                        ['value' => 'Tidak Bermanfaat', 'is_correct' => false],
                    ];
                }
                foreach ($options as $opt) {
                    EvaluasiQuestionOption::updateOrCreate(
                        ['evaluasi_question_id' => $question->id, 'value' => $opt['value']],
                        ['is_correct' => $opt['is_correct']]
                    );
                }
            }
        }

        // ── Pengaturan ──
        \App\Models\Pengaturan::updateOrCreate(
            ['id' => 1],
            [
                'judul' => 'PEMBUKAAN PELATIHAN KEAHLIAN BIDANG PERTANIAN',
                'sub_judul' => 'Balai Pelatihan Pertanian (Bapeltan) Jawa Tengah Tahun 2026',
                'tanggal_tutup' => '2026-12-31',
                'info' => 'Pelatihan berlangsung selama 3 hari di Bapeltan Jawa Tengah',
                'lokasi' => 'Jl. Raya Magelang-Semarang Km.12,8 Soropadan Pringsurat, Kab. Temanggung',
                'persyaratan' => ['Usia 18-50 tahun', 'Warga Jawa Tengah', 'Petani aktif'],
                'fasilitas' => ['Sertifikat', 'Konsumsi', 'Materi Pelatihan', 'Praktik Lapang'],
            ]
        );

        $this->command->info('Test data seeded successfully!');
    }
}
