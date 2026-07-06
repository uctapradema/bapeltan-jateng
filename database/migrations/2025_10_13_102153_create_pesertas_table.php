<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pesertas', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('nik', 16)->unique();
            $table->uuid('user_id')->nullable()->constrained()->nullOnDelete();
            $table->string('nama');
            $table->string('tempat_lahir');
            $table->date('tanggal_lahir');
            $table->string('nomor_telepon')->nullable();
            $table->enum('agama', ['ISLAM', 'KRISTEN', 'KATOLIK', 'HINDU', 'BUDDHA', 'KONGHUCU']);
            $table->enum('jenis_kelamin', ['LAKI-LAKI', 'PEREMPUAN']);
            $table->enum('status_pernikahan', ['BELUM MENIKAH', 'MENIKAH', 'CERAI HIDUP', 'CERAI MATI']);
            $table->enum('pendidikan_terakhir', ['SD', 'SMP', 'SMA', 'D1', 'D2', 'D3', 'S1', 'S2', 'S3']);
            $table->string('pekerjaan');
            $table->string('usaha_tani');
            $table->text('alamat_lengkap');
            $table->string('nama_poktan');
            $table->text('alamat_poktan');
            $table->string('nip')->nullable();
            $table->string('email');
            $table->uuid('kabupaten_id')->constrained();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pesertas');
    }
};
