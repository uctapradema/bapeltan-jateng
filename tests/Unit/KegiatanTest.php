<?php

namespace Tests\Unit;

use App\Models\Kegiatan;
use App\Models\Peserta;
use App\Models\RegistrasiUlang;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class KegiatanTest extends TestCase
{
    use RefreshDatabase;

    public function test_kegiatan_has_uuid_primary_key(): void
    {
        DB::table('kegiatan_types')->insert([
            'id' => 'test-type-id',
            'nama_type' => 'Test Type',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('kegiatans')->insert([
            'id' => 'test-kegiatan-id',
            'kegiatan_type_id' => 'test-type-id',
            'nama_pelatihan' => 'Test Pelatihan',
            'kode_pelatihan' => 'TEST-01',
            'tanggal_mulai' => '2026-10-01',
            'tanggal_selesai' => '2026-10-05',
            'kuota' => 30,
            'status' => 'active',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $kegiatan = Kegiatan::find('test-kegiatan-id');

        $this->assertNotNull($kegiatan);
        $this->assertIsString($kegiatan->id);
    }

    public function test_kegiatan_kuota_tersedia(): void
    {
        DB::table('kegiatan_types')->insert([
            'id' => 'test-type-2',
            'nama_type' => 'Test Type 2',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('kegiatans')->insert([
            'id' => 'test-kegiatan-2',
            'kegiatan_type_id' => 'test-type-2',
            'nama_pelatihan' => 'Test Pelatihan 2',
            'kode_pelatihan' => 'TEST-02',
            'tanggal_mulai' => '2026-10-01',
            'tanggal_selesai' => '2026-10-05',
            'kuota' => 10,
            'status' => 'active',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $kegiatan = Kegiatan::find('test-kegiatan-2');

        $this->assertTrue($kegiatan->kuota_tersedia);
    }

    public function test_kegiatan_scope_aktif(): void
    {
        DB::table('kegiatan_types')->insert([
            'id' => 'test-type-3',
            'nama_type' => 'Test Type 3',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('kegiatans')->insert([
            'id' => 'test-kegiatan-active',
            'kegiatan_type_id' => 'test-type-3',
            'nama_pelatihan' => 'Active',
            'kode_pelatihan' => 'ACT-01',
            'tanggal_mulai' => '2026-10-01',
            'tanggal_selesai' => '2026-10-05',
            'kuota' => 30,
            'status' => 'active',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('kegiatans')->insert([
            'id' => 'test-kegiatan-inactive',
            'kegiatan_type_id' => 'test-type-3',
            'nama_pelatihan' => 'Inactive',
            'kode_pelatihan' => 'INA-01',
            'tanggal_mulai' => '2026-10-01',
            'tanggal_selesai' => '2026-10-05',
            'kuota' => 30,
            'status' => 'inactive',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $this->assertEquals(1, Kegiatan::aktif()->count());
    }
}
