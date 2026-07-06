<?php

namespace Tests\Unit;

use App\Models\Kegiatan;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;
use Tests\TestCase;

class KegiatanTest extends TestCase
{
    use RefreshDatabase;

    public function test_kegiatan_has_uuid_primary_key(): void
    {
        $typeId = (string) Str::uuid();

        $type = \App\Models\KegiatanType::create(['nama_type' => 'Test Type']);

        $kegiatan = Kegiatan::create([
            'kegiatan_type_id' => $type->id,
            'nama_pelatihan' => 'Test Pelatihan',
            'kode_pelatihan' => 'TEST-01',
            'tanggal_mulai' => '2026-10-01',
            'tanggal_selesai' => '2026-10-05',
            'kuota' => 30,
            'status' => 'active',
        ]);

        $this->assertNotNull($kegiatan);
        $this->assertIsString($kegiatan->id);
        $this->assertTrue(Str::isUuid($kegiatan->id));
    }

    public function test_kegiatan_kuota_tersedia(): void
    {
        $type = \App\Models\KegiatanType::create(['nama_type' => 'Test Type 2']);

        $kegiatan = Kegiatan::create([
            'kegiatan_type_id' => $type->id,
            'nama_pelatihan' => 'Test Pelatihan 2',
            'kode_pelatihan' => 'TEST-02',
            'tanggal_mulai' => '2026-10-01',
            'tanggal_selesai' => '2026-10-05',
            'kuota' => 10,
            'status' => 'active',
        ]);

        $this->assertTrue($kegiatan->kuota_tersedia);
    }

    public function test_kegiatan_scope_aktif(): void
    {
        $type = \App\Models\KegiatanType::create(['nama_type' => 'Test Type 3']);

        Kegiatan::create([
            'kegiatan_type_id' => $type->id,
            'nama_pelatihan' => 'Active',
            'kode_pelatihan' => 'ACT-01',
            'tanggal_mulai' => '2026-10-01',
            'tanggal_selesai' => '2026-10-05',
            'kuota' => 30,
            'status' => 'active',
        ]);

        Kegiatan::create([
            'kegiatan_type_id' => $type->id,
            'nama_pelatihan' => 'Inactive',
            'kode_pelatihan' => 'INA-01',
            'tanggal_mulai' => '2026-10-01',
            'tanggal_selesai' => '2026-10-05',
            'kuota' => 30,
            'status' => 'inactive',
        ]);

        $this->assertEquals(1, Kegiatan::aktif()->count());
    }
}
