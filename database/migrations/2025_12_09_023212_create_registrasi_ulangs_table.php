<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('registrasi_ulangs', function (Blueprint $table) {
            $table->id();
            $table->uuid('peserta_id');

            $table->uuid('kegiatan_id')->constrained()->cascadeOnDelete();
            $table->foreignId('kegiatan_type_id')->constrained('kegiatan_types')->cascadeOnDelete();

            $table->year('tahun');

            $table->enum('status', ['pending', 'diterima', 'ditolak', 'selesai'])->default('pending');
            $table->timestamp('tanggal_daftar')->useCurrent();
            $table->text('catatan')->nullable();
            $table->timestamps();

            $table->foreign('peserta_id')->references('id')->on('pesertas')->cascadeOnDelete();

            $table->unique(['peserta_id', 'kegiatan_type_id', 'tahun']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('registrasi_ulangs');
    }
};
