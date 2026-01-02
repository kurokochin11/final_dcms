<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('patient_responses', function (Blueprint $table) {
            // Add medical_session_id column as nullable first for safety
            $table->unsignedBigInteger('medical_session_id')
                  ->nullable()
                  ->after('patient_id');
        });
    }

    public function down(): void
    {
        Schema::table('patient_responses', function (Blueprint $table) {
            $table->dropColumn('medical_session_id');
        });
    }
};
