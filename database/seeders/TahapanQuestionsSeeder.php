<?php

namespace Database\Seeders;

use App\Models\PelatihanTahapan;
use App\Models\PelatihanTahapanQuestion;
use Illuminate\Database\Seeder;

class TahapanQuestionsSeeder extends Seeder
{
    public function run(): void
    {
        $questionTemplates = [
            'Evaluasi Harapan' => [
                ['pertanyaan' => 'Apa harapan Anda mengikuti pelatihan ini?', 'tipe' => 'textarea', 'wajib' => true],
                ['pertanyaan' => 'Tingkat pemahaman Anda tentang materi pelatihan saat ini', 'tipe' => 'rating', 'wajib' => true],
                ['pertanyaan' => 'Apakah Anda sudah menyiapkan waktu untuk mengikuti seluruh rangkaian pelatihan?', 'tipe' => 'konfirmasi', 'wajib' => true],
            ],
            'Evaluasi Materi Awal' => [
                [
                    'pertanyaan' => 'Seberapa familiar Anda dengan konsep dasar pertanian organik?',
                    'tipe' => 'pilihan_ganda',
                    'opsi' => ['Sangat Familiar', 'Cukup Familiar', 'Kurang Familiar', 'Tidak Familiar'],
                    'wajib' => true,
                ],
                [
                    'pertanyaan' => 'Materi apa yang ingin Anda pelajari lebih dalam?',
                    'tipe' => 'checkbox',
                    'opsi' => ['Tanaman Pangan', 'Hortikultura', 'Pertanian Organik', 'Pengelolaan Air', 'Pasar Pertanian'],
                    'wajib' => false,
                ],
                ['pertanyaan' => 'Pengalaman bertani Anda sebelumnya', 'tipe' => 'textarea', 'wajib' => false],
            ],
            'Pre Test' => [
                [
                    'pertanyaan' => 'Apa itu pertanian organik?',
                    'tipe' => 'pilihan_ganda',
                    'opsi' => [
                        'Pertanian menggunakan pestisida kimia',
                        'Pertanian tanpa bahan kimia sintetis',
                        'Pertanian hanya untuk padi',
                        'Pertanian di laboratorium',
                    ],
                    'wajib' => true,
                ],
                [
                    'pertanyaan' => 'Kompos adalah hasil dari...',
                    'tipe' => 'pilihan_ganda',
                    'opsi' => ['Pembakaran sampah', 'Penguraian bahan organik', 'Pencampuran tanah dan air', 'Pemanasan biji-bijian'],
                    'wajib' => true,
                ],
                [
                    'pertanyaan' => 'Cara terbaik mengendalikan hama secara alami adalah...',
                    'tipe' => 'pilihan_ganda',
                    'opsi' => ['Penyemprotan pestisida', 'Penggunaan predator alami', 'Pembakaran lahan', 'Penggunaan pupuk kimia'],
                    'wajib' => true,
                ],
                ['pertanyaan' => 'Jelaskan pengalaman Anda dalam bertani', 'tipe' => 'textarea', 'wajib' => false],
            ],
            'Evaluasi Materi Akhir' => [
                [
                    'pertanyaan' => 'Setelah mengikuti pelatihan, tingkat pemahaman Anda tentang materi',
                    'tipe' => 'rating',
                    'wajib' => true,
                ],
                [
                    'pertanyaan' => 'Materi mana yang paling bermanfaat bagi Anda?',
                    'tipe' => 'checkbox',
                    'opsi' => ['Tanaman Pangan', 'Hortikultura', 'Pertanian Organik', 'Pengelolaan Air', 'Pasar Pertanian'],
                    'wajib' => true,
                ],
                ['pertanyaan' => 'Apa yang masih ingin Anda pelajari lebih lanjut?', 'tipe' => 'textarea', 'wajib' => false],
            ],
            'Evaluasi Kenyataan' => [
                [
                    'pertanyaan' => 'Apakah pelatihan sesuai dengan harapan Anda?',
                    'tipe' => 'pilihan_ganda',
                    'opsi' => ['Sangat Sesuai', 'Cukup Sesuai', 'Kurang Sesuai', 'Tidak Sesuai'],
                    'wajib' => true,
                ],
                ['pertanyaan' => 'Apa kendala yang Anda hadapi selama pelatihan?', 'tipe' => 'textarea', 'wajib' => false],
                [
                    'pertanyaan' => 'Apakah Anda akan merekomendasikan pelatihan ini kepada orang lain?',
                    'tipe' => 'konfirmasi',
                    'wajib' => true,
                ],
            ],
            'Evaluasi Fasilitator' => [
                [
                    'pertanyaan' => 'Bagaimana kemampuan fasilitator dalam menyampaikan materi?',
                    'tipe' => 'rating',
                    'wajib' => true,
                ],
                [
                    'pertanyaan' => 'Bagaimana kemampuan fasilitator dalam menjawab pertanyaan?',
                    'tipe' => 'rating',
                    'wajib' => true,
                ],
                [
                    'pertanyaan' => 'Apakah fasilitator ramah dan komunikatif?',
                    'tipe' => 'pilihan_ganda',
                    'opsi' => ['Sangat Ramah', 'Ramah', 'Kurang Ramah', 'Tidak Ramah'],
                    'wajib' => true,
                ],
                ['pertanyaan' => 'Saran untuk fasilitator', 'tipe' => 'textarea', 'wajib' => false],
            ],
            'Post Test' => [
                [
                    'pertanyaan' => 'Teknik composting yang benar adalah...',
                    'tipe' => 'pilihan_ganda',
                    'opsi' => ['Campur semua sampah', 'Pisahkan organik dan anorganik', 'Gunakan hanya daun kering', 'Tambahkan pupuk kimia'],
                    'wajib' => true,
                ],
                [
                    'pertanyaan' => 'Rotasi tanaman bertujuan untuk...',
                    'tipe' => 'pilihan_ganda',
                    'opsi' => ['Menghemat air', 'Mencegah kehabisan nutrisi tanah', 'Mempercepat panen', 'Mengurangi biaya'],
                    'wajib' => true,
                ],
                [
                    'pertanyaan' => 'Makna "organik" dalam pertanian adalah...',
                    'tipe' => 'pilihan_ganda',
                    'opsi' => ['Murah', 'Tanpa bahan kimia sintetis', 'Tanpa pupuk', 'Untuk pasar modern'],
                    'wajib' => true,
                ],
                [
                    'pertanyaan' => 'Tingkat pemahaman Anda setelah mengikuti seluruh pelatihan',
                    'tipe' => 'rating',
                    'wajib' => true,
                ],
            ],
            'Rencana Tindak Lanjut' => [
                ['pertanyaan' => 'Apa rencana Anda setelah mengikuti pelatihan ini?', 'tipe' => 'textarea', 'wajib' => true],
                ['pertanyaan' => 'Apakah Anda akan menerapkan ilmu yang didapat?', 'tipe' => 'konfirmasi', 'wajib' => true],
                ['pertanyaan' => 'Dukungan apa yang Anda butuhkan setelah pelatihan?', 'tipe' => 'textarea', 'wajib' => false],
            ],
        ];

        $tahapans = PelatihanTahapan::all();

        foreach ($tahapans as $tahapan) {
            if (isset($questionTemplates[$tahapan->nama])) {
                foreach ($questionTemplates[$tahapan->nama] as $i => $q) {
                    PelatihanTahapanQuestion::updateOrCreate(
                        ['tahapan_id' => $tahapan->id, 'urutan' => $i + 1],
                        [
                            'pertanyaan' => $q['pertanyaan'],
                            'tipe' => $q['tipe'],
                            'opsi' => $q['opsi'] ?? null,
                            'wajib' => $q['wajib'],
                        ]
                    );
                }
            }
        }

        $this->command->info('Tahapan questions seeded.');
    }
}
