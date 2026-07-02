<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Support\Str;

class Artikel extends Model
{
    use HasFactory, HasUuids;

    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = [
        'judul',
        'slug',
        'ringkasan',
        'konten',
        'gambar',
        'penulis',
        'status',
        'urutan',
    ];

    protected static function booted(): void
    {
        static::creating(function (Artikel $artikel) {
            if (empty($artikel->slug)) {
                $artikel->slug = Str::slug($artikel->judul);
            }
        });

        static::updating(function (Artikel $artikel) {
            if ($artikel->isDirty('judul') && !$artikel->isDirty('slug')) {
                $artikel->slug = Str::slug($artikel->judul);
            }
        });
    }

    public function scopePublished($query)
    {
        return $query->where('status', 'published');
    }
}
