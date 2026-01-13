<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('extraoral_examinations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('patient_id')->constrained('patients')->onDelete('cascade');
       
            $table->date('examination_date');

            // Extraoral fields
            $table->string('facial_symmetry')->nullable(); // "Normal" or "Asymmetrical"
            $table->text('facial_symmetry_notes')->nullable();
            $table->string('lymph_nodes')->nullable(); // "Palpable" or "Non-palpable"
            $table->text('lymph_nodes_location')->nullable(); // specify locations if palpable

            // TMJ flags — prefer default false to avoid nulls (optional)
            $table->boolean('tmj_pain')->default(false);
            $table->boolean('tmj_clicking')->default(false);
            $table->boolean('tmj_limited_opening')->default(false);

            // MIO in mm (integer)
            $table->integer('mio')->nullable()->comment('Maximum Interincisal Opening in mm');

            $table->text('notes')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('extraoral_examinations');
    }
};
