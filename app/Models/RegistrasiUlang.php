<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class RegistrasiUlang extends Model
{
    use HasFactory;

    protected $fillable = [
        'peserta_nik',
        'kegiatan_id',
        'kegiatan_type_id',
        'tahun',
        'status',
        'catatan',
        'sertifikat_path',
        'catatan_sertifikat',
        'tanggal_selesai_pelatihan',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'tanggal_selesai_pelatihan' => 'date',
    ];

    public function peserta(): BelongsTo
    {
        return $this->belongsTo(Peserta::class, 'peserta_nik', 'nik');
    }

    public function kegiatan(): BelongsTo
    {
        return $this->belongsTo(Kegiatan::class);
    }

    public function getSertifikatUrlAttribute(): ?string
    {
        return $this->sertifikat_path ? asset('storage/' . $this->sertifikat_path) : null;
    }
}
