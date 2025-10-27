<?php

namespace App\Http\Controllers;

use App\Models\Patient;
use App\Models\PatientResponse;
use App\Models\MedicalQuestion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MedicalHistoryController extends Controller
{
    /**
     * Show medical questionnaire form for a specific patient.
     */
    public function index(Patient $patient)
    {
        $questions = MedicalQuestion::all();
        $existingAnswers = $patient->medicalAnswers()->pluck('answer_value', 'medical_question_id');
        
        return view('medical-history.index', compact('patient', 'questions', 'existingAnswers'));
    }

    /**
     * Store submitted answers.
     */
    public function store(Request $request, Patient $patient)
    {
        $answers = $request->input('medical_questions', []);

        DB::transaction(function () use ($patient, $answers) {
            // Delete existing answers before saving new ones
            $patient->medicalAnswers()->delete();

            $data = [];
            foreach ($answers as $questionId => $value) {
                if (!empty($value)) {
                    $data[] = [
                        'patient_id' => $patient->id,
                        'medical_question_id' => $questionId,
                        'answer_value' => $value,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ];
                }
            }

            if ($data) {
                PatientResponse::insert($data);
            }
        });
 
        return redirect()->route('medical-history.answer_index')
            ->with('success', 'Medical history saved successfully!');
    }

    /**
     * View all patients with their submitted answers.
     */
    public function answersIndex()
    {
       $patients = Patient::with(['medicalAnswers.question'])->paginate(10);
        return view('medical-history.answer_index', compact('patients'));
    }
}
