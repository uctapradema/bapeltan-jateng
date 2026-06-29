<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pelatihan_tahapan_progress', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tahapan_id')->constrained('pelatihan_tahapans')->cascadeOnDelete();
            $table->string('peserta_nik', 16);
            $table->enum('status', ['locked', 'active', 'completed', 'skipped'])->default('locked');
            $table->text('catatan')->nullable();
            $table->json('jawaban')->nullable()->comment('JSON data untuk evaluasi/test');
            $table->timestamp('completed_at')->nullable();
            $table->timestamps();

            $table->foreign('peserta_nik')->references('nik')->on('pesertas')->cascadeOnDelete();
            $table->unique(['tahapan_id', 'peserta_nik']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pelatihan_tahapan_progress');
    }
};
