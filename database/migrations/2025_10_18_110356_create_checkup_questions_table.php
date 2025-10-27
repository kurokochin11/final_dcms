<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // 1. CREATE TABLE STRUCTURE
        Schema::create('checkup_questions', function (Blueprint $table) {
            $table->unsignedSmallInteger('id')->primary();
            $table->char('question_set', 1)->default('I')->comment('Dental Check-up Section');
            $table->text('question_text')->comment('The full text of the dental check-up question.');
            $table->string('input_type', 50)->comment('e.g., radio_yes_no, text, date, textarea');
            $table->string('placeholder_text', 255)->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
        });

        // 2. INSERT CHECK-UP QUESTIONS (Section I)
        $questions = [
            ['id' => 41, 'question_set' => 'I', 'question_text' => 'Reason for today’s visit / Chief Complaint', 'input_type' => 'text', 'placeholder_text' => 'Describe reason for visit', 'notes' => null],
            ['id' => 42, 'question_set' => 'I', 'question_text' => 'Are you experiencing any pain or discomfort?', 'input_type' => 'radio_yes_no', 'placeholder_text' => null, 'notes' => null],
            ['id' => 43, 'question_set' => 'I', 'question_text' => 'If yes, location and duration', 'input_type' => 'text', 'placeholder_text' => 'Specify location and duration', 'notes' => null],
            ['id' => 44, 'question_set' => 'I', 'question_text' => 'Have you had any previous dental treatments (e.g., fillings, extractions, root canals, crowns, bridges, dentures, orthodontics)?', 'input_type' => 'radio_yes_no', 'placeholder_text' => null, 'notes' => null],
            ['id' => 45, 'question_set' => 'I', 'question_text' => 'If yes, please specify and approximate dates', 'input_type' => 'textarea', 'placeholder_text' => 'Describe previous dental treatments and approximate dates', 'notes' => null],
            ['id' => 46, 'question_set' => 'I', 'question_text' => 'Do your gums bleed when you brush or floss?', 'input_type' => 'radio_yes_no', 'placeholder_text' => null, 'notes' => null],
            ['id' => 47, 'question_set' => 'I', 'question_text' => 'Are your teeth sensitive to hot, cold, or sweets?', 'input_type' => 'radio_yes_no', 'placeholder_text' => null, 'notes' => null],
            ['id' => 48, 'question_set' => 'I', 'question_text' => 'Do you grind or clench your teeth (bruxism)?', 'input_type' => 'radio_yes_no', 'placeholder_text' => null, 'notes' => null],
            ['id' => 49, 'question_set' => 'I', 'question_text' => 'Do you have any loose teeth?', 'input_type' => 'radio_yes_no', 'placeholder_text' => null, 'notes' => null],
            ['id' => 50, 'question_set' => 'I', 'question_text' => 'Have you noticed any clicking, popping, or pain in your jaw (TMJ issues)?', 'input_type' => 'radio_yes_no', 'placeholder_text' => null, 'notes' => null],
            ['id' => 51, 'question_set' => 'I', 'question_text' => 'How often do you brush your teeth?', 'input_type' => 'text', 'placeholder_text' => 'e.g., Twice daily', 'notes' => null],
            ['id' => 52, 'question_set' => 'I', 'question_text' => 'How often do you floss?', 'input_type' => 'text', 'placeholder_text' => 'e.g., Once a day', 'notes' => null],
            ['id' => 53, 'question_set' => 'I', 'question_text' => 'Do you use any other oral hygiene aids (e.g., mouthwash)?', 'input_type' => 'text', 'placeholder_text' => 'List oral hygiene aids', 'notes' => null],
            ['id' => 54, 'question_set' => 'I', 'question_text' => 'Physician’s Name (for medical clearance, if required)', 'input_type' => 'text', 'placeholder_text' => 'Enter physician name', 'notes' => null],
            ['id' => 55, 'question_set' => 'I', 'question_text' => 'Physician’s Contact Number', 'input_type' => 'text', 'placeholder_text' => 'Enter contact number', 'notes' => null],
            ['id' => 56, 'question_set' => 'I', 'question_text' => 'Reason for Medical Clearance', 'input_type' => 'textarea', 'placeholder_text' => null, 'notes' => null],
            ['id' => 57, 'question_set' => 'I', 'question_text' => 'Date of Medical Clearance', 'input_type' => 'date', 'placeholder_text' => 'MM/DD/YYYY', 'notes' => null],
        ];

        DB::table('checkup_questions')->insert($questions);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('checkup_questions');
    }
};
