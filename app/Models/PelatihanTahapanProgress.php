<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PelatihanTahapanProgress extends Model
{
    protected $fillable = [
        'tahapan_id',
        'peserta_id',
        'status',
        'catatan',
        'jawaban',
        'completed_at',
    ];

    protected $casts = [
        'jawaban' => 'array',
        'completed_at' => 'datetime',
    ];

    public function tahapan(): BelongsTo
    {
        return $this->belongsTo(PelatihanTahapan::class, 'tahapan_id');
    }

    public function peserta(): BelongsTo
    {
        return $this->belongsTo(Peserta::class, 'peserta_id');
    }
}
