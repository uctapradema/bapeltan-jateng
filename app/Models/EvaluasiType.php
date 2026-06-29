<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EvaluasiType extends Model
{
    protected $fillable = ['nama', 'deskripsi'];

    public function evaluasis()
    {
        return $this->hasMany(Evaluasi::class);
    }
}
