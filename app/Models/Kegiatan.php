<?php
// app/Models/Kegiatan.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class Kegiatan extends Model
{
    use HasFactory, HasUuids;

    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = [
        'kegiatan_type_id',
        'nama_pelatihan',
        'kode_pelatihan',
        'tanggal_mulai',
        'tanggal_selesai',
        'kuota',
        'status',
        'deskripsi',
        'group_id',
    ];

    protected $casts = [
        'tanggal_mulai' => 'date',
        'tanggal_selesai' => 'date',
    ];

    public function kegiatanType()
    {
        return $this->belongsTo(KegiatanType::class);
    }

    public function registrasiUlangs(): HasMany
    {
        return $this->hasMany(RegistrasiUlang::class, 'kegiatan_id');
    }

    public function pesertas(): BelongsToMany
    {
        return $this->belongsToMany(Peserta::class, 'registrasi_ulangs', 'kegiatan_id', 'peserta_nik')->withPivot('status', 'catatan')->withTimestamps();
    }

    public function getJumlahPesertaDiterimaAttribute(): int
    {
        return $this->registrasiUlangs()->where('status', 'diterima')->count();
    }

    public function getKuotaTersediaAttribute(): bool
    {
        return $this->jumlah_peserta_diterima < $this->kuota;
    }

    public function scopeAktif($query)
    {
        return $query->where('status', 'active');
    }

    public function group()
    {
        return $this->belongsTo(Group::class);
    }

    public function tahapans()
    {
        return $this->hasMany(PelatihanTahapan::class);
    }
}
