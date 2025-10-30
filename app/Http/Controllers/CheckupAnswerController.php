<?php

namespace App\Http\Controllers;

use App\Models\Patient;
use App\Models\CheckupResult;
use App\Models\CheckupQuestion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CheckupAnswerController extends Controller
{
    /**
     * Show medical questionnaire form for a specific patient.
     */
    public function index(Patient $patient)
    {
        $questions = CheckupQuestion::all();
        $existingAnswers = $patient->checkupAnswers()->pluck('answer_value', 'medical_question_id');
        
        return view('check-up.checkup_index', compact('patient', 'questions', 'existingAnswers'));
    }

    /**
     * Store submitted answers.
     */
    public function store(Request $request, Patient $patient)
    {
        $answers = $request->input('checkup_questions', []);

        DB::transaction(function () use ($patient, $answers) {
            // Delete existing answers before saving new ones
            $patient->checkupAnswers()->delete();

            $data = [];
            foreach ($answers as $questionId => $value) {
                if (!empty($value)) {
                    $data[] = [
                        'patient_id' => $patient->id,
                        'checkup_question_id' => $questionId,
                        'answer_value' => $value,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ];
                }
            }

            if ($data) {
                CheckupResult::insert($data);
            }
        });
 
        return redirect()->route('check-up.checkup_answer_index')
            ->with('success', 'Results saved successfully!');
    }

    /**
     * View all patients with their submitted answers.
     */
    public function checkup_answersIndex()
    {
       $patients = Patient::with(['checkupAnswers.question'])->paginate(100);
        return view('check-up.checkup_answer_index', compact('patients'));
    }
}
