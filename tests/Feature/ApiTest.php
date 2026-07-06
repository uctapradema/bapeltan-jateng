<?php

namespace Tests\Feature;

use App\Models\Kegiatan;
use App\Models\Kabupaten;
use App\Models\KegiatanType;
use App\Models\Peserta;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;
use Tests\TestCase;

class ApiTest extends TestCase
{
    use RefreshDatabase;

    private function createKabupaten(): Kabupaten
    {
        return Kabupaten::create([
            'kode' => '3301',
            'nama' => 'Temanggung',
        ]);
    }

    private function createKegiatanType(): KegiatanType
    {
        return KegiatanType::create(['nama_type' => 'Test Type']);
    }

    private function createKegiatan(KegiatanType $type): Kegiatan
    {
        return Kegiatan::create([
            'kegiatan_type_id' => $type->id,
            'nama_pelatihan' => 'Test Pelatihan',
            'kode_pelatihan' => 'TST-01',
            'tanggal_mulai' => '2026-10-01',
            'tanggal_selesai' => '2026-10-05',
            'kuota' => 30,
            'status' => 'active',
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
        $kab = $this->createKabupaten();

        $user = User::create([
            'name' => 'Test Peserta',
            'email' => 'peserta@test.com',
            'role' => 'peserta',
            'password' => bcrypt('password'),
        ]);

        Peserta::create([
            'id' => (string) Str::uuid(),
            'nik' => '3301010101010001',
            'user_id' => $user->id,
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
            'kabupaten_id' => $kab->id,
            'email' => 'peserta@test.com',
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
        $type = $this->createKegiatanType();
        $this->createKegiatan($type);

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
