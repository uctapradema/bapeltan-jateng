<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('registrasi_zilenials', function (Blueprint $table) {
            $table->uuid('peserta_id')->after('id');
            $table->uuid('kegiatan_id')->after('peserta_id');
            $table->year('tahun')->after('kegiatan_id');
            $table->enum('status', ['pending', 'diterima', 'ditolak', 'selesai'])->default('pending')->after('tahun');
            $table->text('catatan')->nullable()->after('status');

            $table->foreign('peserta_id')->references('id')->on('pesertas')->cascadeOnDelete();
            $table->foreign('kegiatan_id')->references('id')->on('kegiatans')->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('registrasi_zilenials', function (Blueprint $table) {
            $table->dropForeign(['peserta_id', 'kegiatan_id']);
            $table->dropColumn(['peserta_id', 'kegiatan_id', 'tahun', 'status', 'catatan']);
        });
    }
};
