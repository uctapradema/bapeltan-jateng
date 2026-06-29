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
        Schema::create('evaluasi_responses', function (Blueprint $table) {
            $table->id();

            $table->foreignId('evaluasi_id')->constrained('evaluasis')->cascadeOnDelete();
            $table->foreignId('question_id')->constrained('evaluasi_questions')->cascadeOnDelete();

            $table->foreignId('registrasi_ulang_id')->constrained('registrasi_ulangs')->cascadeOnDelete();

            $table->text('jawaban')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('evaluasi_responses');
    }
};
