<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateIntraoralExaminationsTable extends Migration
{
    public function up()
    {
        Schema::create('intraoral_examinations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('patient_id')->constrained()->onDelete('cascade');

            // Soft Tissues
            $table->string('soft_tissues_status')->nullable(); // Normal / Abnormal
            $table->text('soft_tissues_notes')->nullable();

            // Gingiva
            $table->string('gingiva_color')->nullable();       // Pink / Red / Cyanotic
            $table->string('gingiva_texture')->nullable();     // Stippled / Edematous
            $table->boolean('bleeding_on_probing')->default(false);
            $table->text('bleeding_areas')->nullable();
            $table->boolean('recession')->default(false);
            $table->text('recession_areas')->nullable();

            // Periodontium / Hard tissues
            $table->string('probing_depths')->nullable();       // could store JSON or textual chart
            $table->string('mobility')->nullable();
            $table->text('furcation_involvement')->nullable();
            $table->text('hard_tissues_notes')->nullable();
            $table->text('odontogram')->nullable();           // json or encoded chart

            // File uploads for furcation
            $table->string('furcation_file')->nullable();

            // Occlusion
            $table->string('occlusion_class')->nullable();    // Class I / II / III
            $table->text('occlusion_details')->nullable();    // Open bite / deep bite / overjet etc
            $table->text('premature_contacts')->nullable();

            // Oral hygiene
            $table->string('oral_hygiene_status')->nullable(); // Good / Fair / Poor
            $table->string('plaque_index')->nullable();
            $table->string('calculus')->nullable();            // Light / Moderate / Heavy
            // MIO
            $table->integer('mio')->nullable();
            $table->text('notes')->nullable();

            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('intraoral_examinations');
    }
}