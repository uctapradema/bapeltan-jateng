<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('registrasi_ulangs', function (Blueprint $table) {
            $table->enum('status', ['pending', 'diterima', 'ditolak', 'bersedia', 'selesai'])->default('pending')->change();
        });
    }

    public function down(): void
    {
        Schema::table('registrasi_ulangs', function (Blueprint $table) {
            $table->enum('status', ['pending', 'diterima', 'ditolak', 'selesai'])->default('pending')->change();
        });
    }
};
