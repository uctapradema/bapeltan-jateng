<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pelatihan_tahapans', function (Blueprint $table) {
            $table->id();
            $table->uuid('kegiatan_id')->constrained('kegiatans')->cascadeOnDelete();
            $table->string('nama');
            $table->text('deskripsi')->nullable();
            $table->unsignedSmallInteger('urutan');
            $table->enum('tipe', ['sekali', 'harian'])->default('sekali');
            $table->string('link')->nullable()->comment('URL untuk Grup WA atau link eksternal');
            $table->boolean('wajib')->default(true);
            $table->timestamps();

            $table->unique(['kegiatan_id', 'urutan']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pelatihan_tahapans');
    }
};
