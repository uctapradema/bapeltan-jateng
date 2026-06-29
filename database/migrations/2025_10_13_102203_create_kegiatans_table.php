<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('kegiatans', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('nama_pelatihan');
            $table->string('kode_pelatihan')->unique();
            $table->date('tanggal_mulai');
            $table->date('tanggal_selesai');
            $table->integer('kuota');
            $table->enum('status', ['active', 'inactive'])->default('active');
            $table->text('deskripsi')->nullable();

            $table->foreignId('kegiatan_type_id')->constrained('kegiatan_types')->cascadeOnDelete();

            $table->uuid('group_id')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('kegiatans');
    }
};
