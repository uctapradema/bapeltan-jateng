<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('registrasi_ulangs', function (Blueprint $table) {
            $table->string('sertifikat_path')->nullable()->after('catatan');
            $table->text('catatan_sertifikat')->nullable()->after('sertifikat_path');
            $table->date('tanggal_selesai_pelatihan')->nullable()->after('catatan_sertifikat');
        });
    }

    public function down(): void
    {
        Schema::table('registrasi_ulangs', function (Blueprint $table) {
            $table->dropColumn(['sertifikat_path', 'catatan_sertifikat', 'tanggal_selesai_pelatihan']);
        });
    }
};
