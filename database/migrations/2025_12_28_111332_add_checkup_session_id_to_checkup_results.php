<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('checkup_results', function (Blueprint $table) {
            // Add session_id column as nullable first for safety
            $table->unsignedBigInteger('checkup_session_id')
                  ->nullable()
                  ->after('patient_id');
        });
    }

    public function down(): void
    {
        Schema::table('checkup_results', function (Blueprint $table) {
            $table->dropColumn('checkup_session_id');
        });
    }
};
