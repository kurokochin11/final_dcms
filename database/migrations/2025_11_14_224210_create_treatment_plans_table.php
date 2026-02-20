<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('treatment_plans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('patient_id')->constrained('patients')->cascadeOnDelete();
            // store phases as JSON: phase1..phase4 each with date + procedures (string or array)
            $table->json('phases')->nullable();
            // discussion fields
            $table->text('treatment_options')->nullable();
            $table->text('risks_and_benefits')->nullable();
            $table->text('alternatives')->nullable();
            $table->string('estimated_costs')->nullable();
            $table->string('payment_options')->nullable();
            // consent
            $table->boolean('consent_given')->default(false);
            $table->date('consent_date')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('treatment_plans');
    }
};
