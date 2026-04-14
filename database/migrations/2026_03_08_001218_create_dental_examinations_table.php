<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
public function up()
{
    Schema::create('dental_charts', function (Blueprint $table) {
        $table->id();
        $table->foreignId('patient_id')->constrained()->onDelete('cascade');
        
        // The tooth grid data (JSON)
        $table->json('tooth_data')->nullable();
        $table->json('tooth_notes')->nullable();

        // Clinical History fields (Matching your image)
        $table->string('occlusion')->nullable();
        $table->string('periodontal_condition')->nullable();
        $table->string('oral_hygiene')->nullable();
        $table->text('abnormalities')->nullable();
        $table->text('general_condition')->nullable();
        $table->string('nature_of_treatment')->nullable();
        $table->text('allergies')->nullable();
        $table->string('blood_pressure')->nullable();
        $table->text('drugs_taken')->nullable();
        $table->text('chronic_ailments')->nullable();
        $table->string('spo2')->nullable();
        $table->string('physician')->nullable();
        $table->string('denture_upper_since')->nullable();
            $table->string('denture_lower_since')->nullable();

        $table->timestamps();
    });
}
};