<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('materis', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('kegiatan_id')->constrained()->cascadeOnDelete();
            $table->string('judul');
            $table->text('deskripsi')->nullable();
            $table->enum('tipe', ['video_url', 'video_file', 'dokumen', 'gambar'])->default('video_url');
            $table->string('url')->nullable();
            $table->string('file_path')->nullable();
            $table->smallInteger('urutan')->default(0);
            $table->timestamps();

            $table->index(['kegiatan_id', 'urutan']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('materis');
    }
};
