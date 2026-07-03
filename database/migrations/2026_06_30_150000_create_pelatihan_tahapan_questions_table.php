<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pelatihan_tahapan_questions', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('tahapan_id')->constrained('pelatihan_tahapans')->cascadeOnDelete();
            $table->text('pertanyaan');
            $table->enum('tipe', ['pilihan_ganda', 'checkbox', 'text', 'textarea', 'rating', 'konfirmasi'])->default('pilihan_ganda');
            $table->json('opsi')->nullable();
            $table->boolean('wajib')->default(true);
            $table->unsignedSmallInteger('urutan')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pelatihan_tahapan_questions');
    }
};
