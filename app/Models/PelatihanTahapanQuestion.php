<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PelatihanTahapanQuestion extends Model
{
    use HasUuids;

    protected $fillable = [
        'tahapan_id',
        'pertanyaan',
        'tipe',
        'opsi',
        'wajib',
        'urutan',
    ];

    protected $casts = [
        'opsi' => 'array',
        'wajib' => 'boolean',
    ];

    public function tahapan(): BelongsTo
    {
        return $this->belongsTo(PelatihanTahapan::class, 'tahapan_id');
    }
}
