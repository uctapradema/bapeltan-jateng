<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pengaturan extends Model
{
    use HasFactory;

    protected $fillable = [
        'judul',
        'sub_judul',
        'tanggal_tutup',
        'info',
        'lokasi',
        'persyaratan',
        'fasilitas',
    ];

    protected $casts = [
        'tanggal_tutup' => 'date',
        'persyaratan' => 'array',
        'fasilitas' => 'array',
    ];
}
