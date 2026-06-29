<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('registrasi_zilenials', function (Blueprint $table) {
            $table->string('peserta_nik', 16)->after('id');
            $table->uuid('kegiatan_id')->after('peserta_nik');
            $table->year('tahun')->after('kegiatan_id');
            $table->enum('status', ['pending', 'diterima', 'ditolak', 'selesai'])->default('pending')->after('tahun');
            $table->text('catatan')->nullable()->after('status');

            $table->foreign('peserta_nik')->references('nik')->on('pesertas')->cascadeOnDelete();
            $table->foreign('kegiatan_id')->references('id')->on('kegiatans')->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('registrasi_zilenials', function (Blueprint $table) {
            $table->dropForeign(['peserta_nik', 'kegiatan_id']);
            $table->dropColumn(['peserta_nik', 'kegiatan_id', 'tahun', 'status', 'catatan']);
        });
    }
};
