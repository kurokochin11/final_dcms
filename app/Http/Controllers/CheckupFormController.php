<?php

namespace App\Http\Controllers;

use App\Models\CheckupQuestion;
use App\Models\CheckupResult;
use App\Models\CheckupSession; 
use App\Models\Patient;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CheckupFormController extends Controller
{
    /**
     * Show check-up questionnaire for a patient
     */
    public function index(Patient $patient)
    {
        $questions = CheckupQuestion::all();

        // ✅ NEW/OPTIONAL: Keep for showing last answers if needed
        $existingAnswers = $patient->checkupAnswers->keyBy('checkup_question_id');

        return view('check-up.checkup_index', compact('patient', 'questions', 'existingAnswers'));
    }

    /**
     * Handle submission of checkup answers
     */
    public function store(Request $request, Patient $patient)
    {
        // ✅ NEW: Validate submitted answers
        $validated = $request->validate([
            'checkup_questions' => 'nullable|array',
            'checkup_questions.*' => 'nullable|string|max:1000',
        ]);

        $submittedAnswers = $validated['checkup_questions'] ?? [];

        DB::transaction(function () use ($patient, $submittedAnswers) {

            // ✅ NEW: Create a new session for this submission
            $session = CheckupSession::create([
                'patient_id' => $patient->id,
            ]);

            // ✅ NEW: Prepare answers and attach them to the session
            $now = now();
            $responsesToInsert = [];
            foreach ($submittedAnswers as $questionId => $value) {
                if (trim($value) !== '') {
                    $responsesToInsert[] = [
                        'patient_id' => $patient->id,
                        'checkup_session_id' => $session->id, // ✅ NEW: Link answer to session
                        'checkup_question_id' => (int) $questionId,
                        'answer_value' => $value,
                        'created_at' => $now,
                        'updated_at' => $now,
                    ];
                }
            }

            // ✅ NEW: Insert all answers at once
            if (!empty($responsesToInsert)) {
                CheckupResult::insert($responsesToInsert);
            }
        });

        // ✅ CHANGED: Redirect remains the same
        return redirect()->route('check-up.checkup_answer_index')
            ->with('success', 'Checkup answers submitted successfully!');
    }
}
