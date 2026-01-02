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
        Schema::create('diagnoses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('patient_id')->constrained('patients')
                  ->onDelete('cascade'); // Delete diagnoses if patient is deleted
            $table->string('dental_caries')->nullable();
            $table->string('periodontal_disease')->nullable();
            $table->string('pulpal_periapical')->nullable();
            $table->string('occlusal_diagnosis')->nullable();
            $table->string('other_oral_conditions')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('diagnoses');
    }
};
