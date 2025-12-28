<?php

namespace App\Http\Controllers;

use App\Models\Patient;
use App\Models\CheckupResult;
use App\Models\CheckupQuestion;
use App\Models\CheckupSession;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CheckupAnswerController extends Controller
{
    // Show check-up form for a patient (latest answers can be pre-filled)
    public function index(Patient $patient)
    {
        $questions = CheckupQuestion::all();

        // Get latest session
        $latestSession = $patient->checkupSessions()->latest()->first();

        // Pre-fill latest answers if available
        $existingAnswers = $latestSession 
            ? $latestSession->checkupResults->pluck('answer_value', 'checkup_question_id')
            : collect();

        return view('check-up.checkup_index', compact('patient', 'questions', 'existingAnswers'));
    }

    // Store submitted answers as a new session
    public function store(Request $request, Patient $patient)
    {
        $answers = $request->input('checkup_questions', []);

        DB::transaction(function () use ($patient, $answers) {
            // Create new session
            $session = CheckupSession::create([
                'patient_id' => $patient->id,
            ]);

            $data = [];
            foreach ($answers as $questionId => $value) {
                if (!empty($value)) {
                    $data[] = [
                        'patient_id' => $patient->id,
                        'checkup_question_id' => $questionId,
                        'answer_value' => $value,
                        'checkup_session_id' => $session->id,
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
            ->with('success', 'Check-up submitted successfully!');
    }

    // Show all patients with their sessions and answers
    public function checkup_answersIndex()
    {
        $patients = Patient::with(['checkupSessions.checkupResults.question'])->paginate(50);
        return view('check-up.checkup_answer_index', compact('patients'));
    }

    // Edit latest submission
    public function edit(Patient $patient)
    {
        $questions = CheckupQuestion::all();

        // Fetch latest session
        $latestSession = $patient->checkupSessions()->latest()->first();
        $answers = $latestSession ? $latestSession->checkupResults->pluck('answer_value', 'checkup_question_id') : collect();

        return view('check-up.checkup_edit', compact('patient', 'questions', 'latestSession', 'answers'));
    }

    // Update latest submission
public function update(Request $request, Patient $patient)
{
    $latestSession = $patient->checkupSessions()->latest()->first();

    if (!$latestSession) {
        return redirect()->back()->with('error', 'No submission found to update.');
    }

    $answers = $request->input('checkup_questions', []);

    DB::transaction(function () use ($latestSession, $answers) {
        foreach ($answers as $questionId => $value) {
            $result = $latestSession->checkupResults()
                        ->where('checkup_question_id', $questionId)
                        ->first();

            if ($result) {
                $result->update(['answer_value' => $value]);
            }
            // Removed creation of new answer
        }
    });

    return redirect()->route('check-up.checkup_answer_index')
                     ->with('success', 'Latest check-up updated successfully!');
}
}