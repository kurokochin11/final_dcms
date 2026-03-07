<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('dental_examinations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('patient_id')->constrained()->onDelete('cascade');
            
            // Side Credentials (Text Inputs)
            $table->string('occlusion')->nullable();
            $table->string('periodontal_condition')->nullable();
            $table->string('oral_hygiene')->nullable();
            $table->text('abnormalities')->nullable();
            $table->text('general_condition')->nullable();
            $table->string('physician')->nullable();
            $table->string('nature_of_treatment')->nullable();
            $table->string('allergies')->nullable();
            $table->string('previous_bleeding')->nullable();
            $table->string('chronic_ailments')->nullable();
            $table->string('blood_pressure')->nullable();
            $table->string('drugs_taken')->nullable();

            // Tooth Data (Stored as JSON: { "18": "check", "17": "wrong" })
            $table->json('tooth_data')->nullable(); 
            $table->timestamps();
        });
    }

    public function down(): void {
        Schema::dropIfExists('dental_examinations');
    }
};