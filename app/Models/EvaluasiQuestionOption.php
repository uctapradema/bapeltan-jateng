<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EvaluasiQuestionOption extends Model
{
    protected $fillable = ['evaluasi_question_id', 'value', 'is_correct'];

    protected $casts = [
        'is_correct' => 'boolean',
    ];    

    public function question()
    {
        return $this->belongsTo(EvaluasiQuestion::class);
    }
}
