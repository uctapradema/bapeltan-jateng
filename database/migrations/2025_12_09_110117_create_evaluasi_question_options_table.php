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
        Schema::create('evaluasi_question_options', function (Blueprint $table) {
            $table->id();

            $table->foreignId('evaluasi_question_id')->constrained('evaluasi_questions')->cascadeOnDelete();

            $table->string('value');
            $table->boolean('is_correct')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('evaluasi_question_options');
    }
};
