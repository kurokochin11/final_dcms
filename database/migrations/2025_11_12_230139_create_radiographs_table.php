<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('radiographs', function (Blueprint $table) {
            $table->id();
           $table->foreignId('patient_id')->constrained('patients')->onDelete('cascade');
            $table->date('date_taken');
            $table->string('type');
            $table->string('image_path');
            $table->text('findings')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('radiographs');
    }
};
