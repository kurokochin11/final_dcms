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
        Schema::create('patient_responses', function (Blueprint $table) {
            $table->id(); // PRIMARY KEY for this RESPONSE (e.g., answer #1, #2, etc.)

            // Link to the specific patient from your 'patients' table
            $table->foreignId('patient_id')->constrained('emergency_contacts')->onDelete('cascade'); 

            // Link to the specific question from your 'medical_questions' table
            $table->unsignedSmallInteger('medical_question_id');
            $table->foreign('medical_question_id')->references('id')->on('medical_questions')->onDelete('cascade');

            // The actual answer provided by the patient
            $table->text('answer_value')->nullable();

            $table->timestamps();

           
            $table->unique(['patient_id', 'medical_question_id']);
        });
    }

    /**
     * Reverse the migrations (Drops the table).
     */
    public function down(): void
    {
        Schema::dropIfExists('patient_responses');
    }
};