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
    /**
     * Show check-up form (new submission)
     */
    public function index(Patient $patient)
    {
        $questions = CheckupQuestion::all();

        $latestSession = $patient->checkupSessions()->latest()->first();

        $existingAnswers = $latestSession
            ? $latestSession->checkupResults->pluck('answer_value', 'checkup_question_id')
            : collect();

        return view(
            'check-up.checkup_index',
            compact('patient', 'questions', 'existingAnswers')
        );
    }

    /**
     * Store a NEW check-up session
     */
    public function store(Request $request, Patient $patient)
    {
        $answers = $request->input('checkup_questions', []);

        DB::transaction(function () use ($patient, $answers) {

            $session = CheckupSession::create([
                'patient_id' => $patient->id,
            ]);

            $data = [];

            foreach ($answers as $questionId => $value) {
                if ($value !== null && $value !== '') {
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

            if (!empty($data)) {
                CheckupResult::insert($data);
            }
        });

        return redirect()
            ->route('check-up.checkup_answer_index')
            ->with('success', 'Check-up submitted successfully!');
    }

    /**
     * Index page — patients + sessions
     */
    public function checkup_answersIndex()
    {
        $patients = Patient::with([
            'checkupSessions.checkupResults.question'
        ])->paginate(50);

        return view(
            'check-up.checkup_answer_index',
            compact('patients')
        );
    }

    /**
     * (OPTIONAL / LEGACY)
     * Edit latest session by patient
     * — not used by modal anymore
     */
    public function edit(Patient $patient)
    {
        $questions = CheckupQuestion::all();

        $latestSession = $patient->checkupSessions()->latest()->first();

        $answers = $latestSession
            ? $latestSession->checkupResults->pluck('answer_value', 'checkup_question_id')
            : collect();

        return view(
            'check-up.checkup_edit',
            compact('patient', 'questions', 'latestSession', 'answers')
        );
    }

    /**
     * (OPTIONAL / LEGACY)
     * Update latest session by patient
     * — not used by modal anymore
     */
    public function update(Request $request, Patient $patient)
    {
        $latestSession = $patient->checkupSessions()->latest()->first();

        if (!$latestSession) {
            return redirect()->back()
                ->with('error', 'No submission found to update.');
        }

        $answers = $request->input('checkup_questions', []);

        DB::transaction(function () use ($latestSession, $answers) {
            foreach ($answers as $questionId => $value) {
                $latestSession->checkupResults()
                    ->where('checkup_question_id', $questionId)
                    ->update([
                        'answer_value' => $value,
                        'updated_at' => now(),
                    ]);
            }
        });

        return redirect()
            ->route('check-up.checkup_answer_index')
            ->with('success', 'Latest check-up updated successfully!');
    }

    /**
     * ✅ MAIN METHOD USED BY YOUR EDIT MODAL
     * Update a SPECIFIC check-up session
     */
    public function updateSession(Request $request, CheckupSession $session)
    {
        $answers = $request->input('checkup_questions', []);

        DB::transaction(function () use ($session, $answers) {
            foreach ($answers as $questionId => $value) {
                $session->checkupResults()
                    ->where('checkup_question_id', $questionId)
                    ->update([
                        'answer_value' => $value,
                        'updated_at' => now(),
                    ]);
            }
        });

        return redirect()
            ->route('check-up.checkup_answer_index')
            ->with('success', 'Check-up session updated successfully!');
    }
}
