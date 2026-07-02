<?php

namespace Tests\Feature;

use App\Models\Kegiatan;
use App\Models\Kabupaten;
use App\Models\KegiatanType;
use App\Models\Peserta;
use App\Models\RegistrasiUlang;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class ApiTest extends TestCase
{
    use RefreshDatabase;

    private function createKabupaten(string $id = 'kab-1'): void
    {
        DB::table('kabupatens')->insert([
            'id' => $id,
            'kode' => '3301',
            'nama' => 'Temanggung',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    private function createKegiatanType(string $id = 'type-1'): void
    {
        DB::table('kegiatan_types')->insert([
            'id' => $id,
            'nama_type' => 'Test Type',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    private function createKegiatan(string $id = 'keg-1', string $typeId = 'type-1'): void
    {
        DB::table('kegiatans')->insert([
            'id' => $id,
            'kegiatan_type_id' => $typeId,
            'nama_pelatihan' => 'Test Pelatihan',
            'kode_pelatihan' => 'TST-01',
            'tanggal_mulai' => '2026-10-01',
            'tanggal_selesai' => '2026-10-05',
            'kuota' => 30,
            'status' => 'active',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    public function test_cek_nik_returns_404_for_unknown_nik(): void
    {
        $response = $this->getJson('/api/cek-nik?nik=1234567890123456');

        $response->assertStatus(404)
            ->assertJson([
                'success' => false,
            ]);
    }

    public function test_cek_nik_returns_data_for_existing_peserta(): void
    {
        $this->createKabupaten();

        DB::table('users')->insert([
            'id' => 'user-peserta-1',
            'name' => 'Test Peserta',
            'email' => 'peserta@test.com',
            'role' => 'peserta',
            'password' => bcrypt('password'),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('pesertas')->insert([
            'nik' => '3301010101010001',
            'user_id' => 'user-peserta-1',
            'nama' => 'Test Peserta',
            'tempat_lahir' => 'Temanggung',
            'tanggal_lahir' => '1990-01-01',
            'nomor_telepon' => '081234567890',
            'agama' => 'ISLAM',
            'jenis_kelamin' => 'LAKI-LAKI',
            'status_pernikahan' => 'BELUM MENIKAH',
            'pendidikan_terakhir' => 'SMA',
            'pekerjaan' => 'Petani',
            'usaha_tani' => 'Padi',
            'alamat_lengkap' => 'Jl. Test No. 1',
            'nama_poktan' => 'Poktan Test',
            'alamat_poktan' => 'Jl. Poktan No. 1',
            'kabupaten_id' => 'kab-1',
            'email' => 'peserta@test.com',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $response = $this->getJson('/api/cek-nik?nik=3301010101010001');

        $response->assertOk()
            ->assertJson([
                'success' => true,
                'data' => [
                    'nik' => '3301010101010001',
                    'nama' => 'Test Peserta',
                ],
            ]);
    }

    public function test_kegiatan_list_returns_active_kegiatan(): void
    {
        $this->createKegiatanType();
        $this->createKegiatan();

        $response = $this->getJson('/api/kegiatan');

        $response->assertOk()
            ->assertJson([
                'success' => true,
            ]);
    }

    public function test_daftar_pelatihan_requires_nik(): void
    {
        $response = $this->postJson('/api/daftar-pelatihan', []);

        $response->assertStatus(422);
    }
}
