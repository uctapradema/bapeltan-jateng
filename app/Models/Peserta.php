<?php
// app/Models/Peserta.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Peserta extends Model
{
    use HasFactory;

    protected $primaryKey = 'nik';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = ['nik', 'nama', 'tempat_lahir', 'tanggal_lahir', 'role', 'nomor_telepon', 'agama', 'jenis_kelamin', 'status_pernikahan', 'pendidikan_terakhir', 'pekerjaan', 'usaha_tani', 'alamat_lengkap', 'nama_poktan', 'alamat_poktan', 'nip', 'email', 'kabupaten_id'];

    protected $casts = [
        'tanggal_lahir' => 'date',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function registrasiUlangs(): HasMany
    {
        return $this->hasMany(RegistrasiUlang::class, 'peserta_nik', 'nik');
    }

    public function kabupaten(): BelongsTo
    {
        return $this->belongsTo(Kabupaten::class);
    }

    public function kegiatans(): BelongsToMany
    {
        return $this->belongsToMany(Kegiatan::class, 'registrasi_ulangs', 'peserta_nik', 'kegiatan_id')->withPivot('status', 'catatan')->withTimestamps();
    }

    public function getUsiaAttribute(): int
    {
        return now()->diffInYears($this->tanggal_lahir);
    }

    public function scopeMemenuhiSyaratUsia($query)
    {
        return $query->whereRaw('TIMESTAMPDIFF(YEAR, tanggal_lahir, CURDATE()) <= 50');
    }
}
