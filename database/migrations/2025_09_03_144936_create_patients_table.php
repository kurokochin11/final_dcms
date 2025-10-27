<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('patients', function (Blueprint $table) {
            $table->id();
            $table->date('date_registered');
            $table->string('last_name');
            $table->string('first_name');
            $table->string('middle_name')->nullable();
             $table->text('age')->nullable();
            $table->date('date_of_birth');
            $table->enum('sex', ['Male', 'Female', 'Prefer not to say']);
            $table->enum('civil_status', ['Single','Married', 'Widowed','Separated','Divorced','Annulled','Commonlaw']);
            $table->string('nationality')->nullable();
            $table->string('religion')->nullable();
            $table->string('occupation')->nullable();
            $table->text('address')->nullable();
            $table->string('city')->nullable();
            $table->string('province')->nullable();
            $table->string('zip_code')->nullable();
            $table->string('mobile_number')->nullable();
            $table->string('landline_number')->nullable();
            $table->string('email')->nullable();
            $table->string('referred_by')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('patients');
    }
};