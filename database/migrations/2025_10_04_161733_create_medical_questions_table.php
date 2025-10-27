<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations (Create table and insert data).
     */
    public function up(): void
    {
        // 1. CREATE TABLE STRUCTURE (DDL)
        Schema::create('medical_questions', function (Blueprint $table) {
            // The ID is the numerical index (1 to 40) used in the HTML form.
            $table->unsignedSmallInteger('id')->primary(); 
            $table->char('question_set', 1)->comment('Section of the form (A, B, C, etc.)');
            $table->text('question_text')->comment('The full text of the question.');
            $table->string('input_type', 50)->comment('e.g., radio_yes_no, text, date, textarea');
            $table->string('placeholder_text', 255)->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
        });

        // 2. INSERT QUESTION DATA (DML)
        $questions = [
            // A. General Health Conditions (1-11)
            ['id' => 1, 'question_set' => 'A', 'question_text' => 'Are you currently under the care of a physician?', 'input_type' => 'radio_yes_no', 'placeholder_text' => null, 'notes' => null],
            ['id' => 2, 'question_set' => 'A', 'question_text' => 'Condition(s) if under a physician\'s care', 'input_type' => 'text', 'placeholder_text' => 'If Yes, for what condition(s)?', 'notes' => null],
            ['id' => 3, 'question_set' => 'A', 'question_text' => 'Have you been hospitalized in the past 5 years?', 'input_type' => 'radio_yes_no', 'placeholder_text' => null, 'notes' => null],
            ['id' => 4, 'question_set' => 'A', 'question_text' => 'Reason(s) for hospitalization if applicable', 'input_type' => 'text', 'placeholder_text' => 'If Yes, for what reason(s)?', 'notes' => null],
            ['id' => 5, 'question_set' => 'A', 'question_text' => 'Do you have any significant illnesses or medical conditions?', 'input_type' => 'radio_yes_no', 'placeholder_text' => null, 'notes' => null],
            ['id' => 6, 'question_set' => 'A', 'question_text' => 'Specify significant illness/condition.', 'input_type' => 'text', 'placeholder_text' => 'If Yes, please specify', 'notes' => '(e.g., Heart Disease, High Blood Pressure, Diabetes, Asthma, etc.)'],
            
            ['id' => 7, 'question_set' => 'A', 'question_text' => 'Do you experience frequent headaches or migraines?', 'input_type' => 'radio_yes_no', 'placeholder_text' => null, 'notes' => null],
            ['id' => 8, 'question_set' => 'A', 'question_text' => 'Are you pregnant or suspect you might be? (For female patients)', 'input_type' => 'radio_yes_no', 'placeholder_text' => null, 'notes' => null],
            ['id' => 9, 'question_set' => 'A', 'question_text' => 'Do you have any blood disorders (e.g., hemophilia, anemia)?', 'input_type' => 'radio_yes_no', 'placeholder_text' => null, 'notes' => null],
            ['id' => 10, 'question_set' => 'A', 'question_text' => 'Have you ever had any adverse reactions to anesthesia?', 'input_type' => 'radio_yes_no', 'placeholder_text' => null, 'notes' => null],
            ['id' => 11, 'question_set' => 'A', 'question_text' => 'Have you ever had a serious injury to your head, neck, or jaw?', 'input_type' => 'radio_yes_no', 'placeholder_text' => null, 'notes' => null],

            // B. COVID-19 History and Exposure (12-18)
            ['id' => 12, 'question_set' => 'B', 'question_text' => 'Have you tested positive for COVID-19 in the past 3 months?', 'input_type' => 'radio_yes_no', 'placeholder_text' => null, 'notes' => null],
            ['id' => 13, 'question_set' => 'B', 'question_text' => 'Date of positive test:', 'input_type' => 'text', 'placeholder_text' => 'If Yes, date of positive test:', 'notes' => null],
            ['id' => 14, 'question_set' => 'B', 'question_text' => 'Have you experienced any COVID-19 symptoms in the past 14 days?', 'input_type' => 'radio_yes_no', 'placeholder_text' => null, 'notes' => null],
            ['id' => 15, 'question_set' => 'B', 'question_text' => 'Specify COVID-19 symptoms.', 'input_type' => 'text', 'placeholder_text' => 'If Yes, please specify symptoms', 'notes' => null],
            ['id' => 16, 'question_set' => 'B', 'question_text' => 'Have you been in close contact with anyone diagnosed with COVID-19 in the past 14 days?', 'input_type' => 'radio_yes_no', 'placeholder_text' => null, 'notes' => null],
            ['id' => 17, 'question_set' => 'B', 'question_text' => 'Have you received any COVID-19 vaccine doses?', 'input_type' => 'radio_yes_no', 'placeholder_text' => null, 'notes' => null],
            ['id' => 18, 'question_set' => 'B', 'question_text' => 'Specify type and date(s) of last vaccine dose.', 'input_type' => 'text', 'placeholder_text' => 'If Yes, please specify type and date(s) of last dose', 'notes' => null],

            // C. Allergies & Reactions (19-24)
            ['id' => 19, 'question_set' => 'C', 'question_text' => 'Are you allergic to any medications?', 'input_type' => 'radio_yes_no', 'placeholder_text' => null, 'notes' => null],
            ['id' => 20, 'question_set' => 'C', 'question_text' => 'Specify medication allergies.', 'input_type' => 'text', 'placeholder_text' => 'If Yes, please specify', 'notes' => '(e.g., Penicillin, Aspirin, Ibuprofen, Local Anesthetics)'],
            
            ['id' => 21, 'question_set' => 'C', 'question_text' => 'Are you allergic to latex, metals, or any dental materials?', 'input_type' => 'radio_yes_no', 'placeholder_text' => null, 'notes' => null],
            ['id' => 22, 'question_set' => 'C', 'question_text' => 'Specify material allergies.', 'input_type' => 'text', 'placeholder_text' => 'If Yes, please specify: ', 'notes' => null],
            ['id' => 23, 'question_set' => 'C', 'question_text' => 'Do you have any other allergies (e.g., food, environmental)?', 'input_type' => 'radio_yes_no', 'placeholder_text' => null, 'notes' => null],
            ['id' => 24, 'question_set' => 'C', 'question_text' => 'List other allergies.', 'input_type' => 'text', 'placeholder_text' => 'If Yes, please list: ', 'notes' => null],

            // D. Current Medications (25-28)
            ['id' => 25, 'question_set' => 'D', 'question_text' => 'Are you currently taking any prescription medications?', 'input_type' => 'radio_yes_no', 'placeholder_text' => null, 'notes' => null],
            ['id' => 26, 'question_set' => 'D', 'question_text' => 'List prescription medications.', 'input_type' => 'text', 'placeholder_text' => 'If Yes, please list', 'notes' => null],
            ['id' => 27, 'question_set' => 'D', 'question_text' => 'Are you currently taking any over-the-counter medications, supplements, or herbal remedies?', 'input_type' => 'radio_yes_no', 'placeholder_text' => null, 'notes' => null],
            ['id' => 28, 'question_set' => 'D', 'question_text' => 'List OTC/supplements/remedies.', 'input_type' => 'text', 'placeholder_text' => 'If Yes, please list', 'notes' => null],

            // E. Habits & Lifestyle (29-34)
            ['id' => 29, 'question_set' => 'E', 'question_text' => 'Do you smoke?', 'input_type' => 'radio_yes_no', 'placeholder_text' => null, 'notes' => null],
            ['id' => 30, 'question_set' => 'E', 'question_text' => 'Smoking frequency/amount.', 'input_type' => 'text', 'placeholder_text' => 'If Yes, how much/often?', 'notes' => null],
            ['id' => 31, 'question_set' => 'E', 'question_text' => 'Do you consume alcoholic beverages?', 'input_type' => 'radio_yes_no', 'placeholder_text' => null, 'notes' => null],
            ['id' => 32, 'question_set' => 'E', 'question_text' => 'Alcohol consumption frequency/amount.', 'input_type' => 'text', 'placeholder_text' => 'If Yes, how much/often?', 'notes' => null],
            ['id' => 33, 'question_set' => 'E', 'question_text' => 'Do you use recreational drugs?', 'input_type' => 'radio_yes_no', 'placeholder_text' => null, 'notes' => null],
            ['id' => 34, 'question_set' => 'E', 'question_text' => 'Have you ever experienced any adverse reactions to dental procedures in the past?', 'input_type' => 'radio_yes_no', 'placeholder_text' => null, 'notes' => null],

            // F. Blood Pressure (35)
            ['id' => 35, 'question_set' => 'F', 'question_text' => 'Blood Pressure Reading (BP)', 'input_type' => 'text', 'placeholder_text' => null, 'notes' => null],

            // G. Additional Notes (36)
            ['id' => 36, 'question_set' => 'G', 'question_text' => 'Additional General Notes/Details', 'input_type' => 'text', 'placeholder_text' => null, 'notes' => null],

            // H. Medical Clearance (37-40)
            ['id' => 37, 'question_set' => 'H', 'question_text' => 'Date of Medical Clearance', 'input_type' => 'date', 'placeholder_text' => 'MM/DD/YYYY', 'notes' => null],
            ['id' => 38, 'question_set' => 'H', 'question_text' => 'Physician\'s Name for Clearance', 'input_type' => 'text', 'placeholder_text' => null, 'notes' => null],
            ['id' => 39, 'question_set' => 'H', 'question_text' => 'Physician Contact Number', 'input_type' => 'text', 'placeholder_text' => null, 'notes' => null],
            ['id' => 40, 'question_set' => 'H', 'question_text' => 'Reason for Medical Clearance', 'input_type' => 'textarea', 'placeholder_text' => null, 'notes' => null],

        ];

        DB::table('medical_questions')->insert($questions);
    }

    /**
     * Reverse the migrations (Drop table).
     */
    public function down(): void
    {
        Schema::dropIfExists('medical_questions');
    }
};