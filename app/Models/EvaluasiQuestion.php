<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EvaluasiQuestion extends Model
{
    protected $fillable = [
        'evaluasi_id',
        'pertanyaan',
        'tipe_jawaban',
        'urutan',
    ];

    public function evaluasi()
    {
        return $this->belongsTo(Evaluasi::class);
    }

    public function responses()
    {
        return $this->hasMany(EvaluasiResponse::class, 'question_id');
    }

    public function options()
    {
        return $this->hasMany(EvaluasiQuestionOption::class, 'evaluasi_question_id');
    }
}
