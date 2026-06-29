<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Evaluasi extends Model
{
    protected $fillable = [
        'kegiatan_id',
        'evaluasi_type_id',
        'judul',
    ];

    public function kegiatan()
    {
        return $this->belongsTo(Kegiatan::class);
    }

    public function type()
    {
        return $this->belongsTo(EvaluasiType::class, 'evaluasi_type_id');
    }

    public function questions()
    {
        return $this->hasMany(EvaluasiQuestion::class);
    }

    public function responses()
    {
        return $this->hasMany(EvaluasiResponse::class);
    }
}
