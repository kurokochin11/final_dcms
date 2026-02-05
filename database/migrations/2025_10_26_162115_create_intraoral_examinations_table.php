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
        Schema::create('intraoral_examinations', function (Blueprint $table) {
            $table->id();
            
            // Foreign key to patients table
            $table->foreignId('patient_id')->constrained('patients')->onDelete('cascade');

             $table->date('date')->nullable();
            // Soft Tissues
            $table->text('soft_tissues')->nullable();
            $table->string('soft_tissues_status')->nullable();
            // Gingiva
            $table->string('gingiva_color')->nullable();
            $table->string('gingiva_texture')->nullable();
            $table->string('bleeding')->nullable();
            $table->string('bleeding_area')->nullable();
            $table->string('recession')->nullable();
            $table->string('recession_area')->nullable();

            // Periodontium (files)
            $table->string('probing_depths')->nullable();
            $table->string('mobility')->nullable();
            $table->string('furcation')->nullable();
            $table->string('odontogram')->nullable();

            // Hard Tissues
            $table->string('teeth_condition')->nullable();

            // Occlusion
            $table->string('occlusion_class')->nullable();
            $table->string('occlusion_other')->nullable();
            $table->string('premature_contacts')->nullable();

            // Oral Hygiene
            $table->string('hygiene_status')->nullable();
            $table->string('plaque_index')->nullable();
            $table->string('calculus')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('intraoral_examinations');
    }
};
