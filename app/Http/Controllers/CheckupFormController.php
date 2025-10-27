<?php

namespace App\Http\Controllers;

use App\Models\CheckupQuestion;
use App\Models\CheckupResult;
use App\Models\Patient;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CheckupFormController extends Controller
{
    // ✅ Show check-up questionnaire for a patient
    public function index(Patient $patient)
    {
        $questions = CheckupQuestion::all();
        $existingAnswers = $patient->checkupAnswers->keyBy('checkup_question_id');
        return view('check-up.checkup_index', compact('patient', 'questions', 'existingAnswers'));
    }

     // ✅ Handle submission
    //  public function submitForm(Request $request, Patient $patient)
     public function store(Request $request, Patient $patient)
     {
       $validated = $request->validate([
            'checkup_questions' => 'nullable|array',
          'checkup_questions.*' => 'nullable|string|max:1000',

     
        ]);

        $patientId = $patient->id;
        $responsesToInsert = [];
        $submittedAnswers = $validated['checkup_questions'] ?? [];

        DB::transaction(function () use ($patientId, $submittedAnswers, &$responsesToInsert) {
            CheckupResult::where('patient_id', $patientId)->delete();

            $now = now();
            foreach ($submittedAnswers as $questionId => $value) {
                if (trim($value) !== '') {
                    $responsesToInsert[] = [
                        'patient_id' => $patientId,
                        'checkup_question_id' => (int) $questionId,
                        'answer_value' => $value,
                        'created_at' => $now,
                        'updated_at' => $now,
                    ];
                }
            }

            if (!empty($responsesToInsert)) {
                CheckupResult::insert($responsesToInsert);
            }
        });

        return redirect()->route('check-up.checkup_answer_index')
            ->with('success', 'Results submitted successfully.');
    }
}
