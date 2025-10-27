<?php

namespace App\Http\Controllers;

use App\Models\MedicalQuestion;
use App\Models\PatientResponse;
use App\Models\Patient;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MedicalFormController extends Controller
{
    // ✅ Show medical questionnaire for a patient
    public function index(Patient $patient)
    {
        $questions = MedicalQuestion::all();
        $existingAnswers = $patient->medicalAnswers->keyBy('medical_question_id');
        return view('medical-history.index', compact('patient', 'questions', 'existingAnswers'));
    }

     // ✅ Handle submission
    //  public function submitForm(Request $request, Patient $patient)
     public function store(Request $request, Patient $patient)
     {
       $validated = $request->validate([
            'medical_questions' => 'nullable|array',
          'medical_questions.*' => 'nullable|string|max:1000',

     
        ]);

        $patientId = $patient->id;
        $responsesToInsert = [];
        $submittedAnswers = $validated['medical_questions'] ?? [];

        DB::transaction(function () use ($patientId, $submittedAnswers, &$responsesToInsert) {
            PatientResponse::where('patient_id', $patientId)->delete();

            $now = now();
            foreach ($submittedAnswers as $questionId => $value) {
                if (trim($value) !== '') {
                    $responsesToInsert[] = [
                        'patient_id' => $patientId,
                        'medical_question_id' => (int) $questionId,
                        'answer_value' => $value,
                        'created_at' => $now,
                        'updated_at' => $now,
                    ];
                }
            }

            if (!empty($responsesToInsert)) {
                PatientResponse::insert($responsesToInsert);
            }
        });

        return redirect()->route('medical-history.answer_index')
            ->with('success', 'Medical history submitted successfully.');
    }
}
