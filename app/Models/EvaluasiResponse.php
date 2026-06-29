<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EvaluasiResponse extends Model
{
    protected $fillable = [
        'evaluasi_id',
        'question_id',
        'registrasi_ulang_id',
        'jawaban',
    ];

    protected $casts = [
        'jawaban' => 'string',
    ];    

    public function evaluasi()
    {
        return $this->belongsTo(Evaluasi::class);
    }

    public function question()
    {
        return $this->belongsTo(EvaluasiQuestion::class);
    }

    public function registrasiUlang()
    {
        return $this->belongsTo(RegistrasiUlang::class);
    }
}
