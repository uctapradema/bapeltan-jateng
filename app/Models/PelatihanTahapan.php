<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PelatihanTahapan extends Model
{
    protected $fillable = [
        'kegiatan_id',
        'nama',
        'deskripsi',
        'urutan',
        'tipe',
        'link',
        'wajib',
    ];

    protected $casts = [
        'wajib' => 'boolean',
        'urutan' => 'integer',
    ];

    public function kegiatan(): BelongsTo
    {
        return $this->belongsTo(Kegiatan::class);
    }

    public function progress(): HasMany
    {
        return $this->hasMany(PelatihanTahapanProgress::class, 'tahapan_id');
    }
}
