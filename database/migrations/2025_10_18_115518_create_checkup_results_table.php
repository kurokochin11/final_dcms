<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('checkup_results', function (Blueprint $table) {
            $table->id();

            // Link to patient
            $table->foreignId('patient_id')->constrained('patients')->onDelete('cascade');

            // Link to checkup question
            $table->unsignedSmallInteger('checkup_question_id');
            $table->foreign('checkup_question_id')->references('id')->on('checkup_questions')->onDelete('cascade');

            // Store the answer value
            $table->text('answer_value')->nullable();

            $table->timestamps();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('checkup_results');
    }
};
