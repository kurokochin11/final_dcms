<?php

namespace App\Http\Controllers;

use App\Models\Patient;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\PatientResponse;
use App\Models\MedicalQuestion;
use App\Models\MedicalSession;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MedicalHistoryController extends Controller
{
    /**
     * Show medical questionnaire form (latest session pre-filled)
     */
    public function index(Patient $patient)
    {
        $questions = MedicalQuestion::all();

        $latestSession = $patient->medicalSessions()->latest()->first();

        $existingAnswers = $latestSession
            ? $latestSession->responses->pluck('answer_value', 'medical_question_id')
            : collect();

        return view('medical-history.index', compact(
            'patient',
            'questions',
            'existingAnswers'
        ));
    }

    /**
     * Store submitted answers as a NEW medical session
     */
    public function store(Request $request, Patient $patient)
    {
        $answers = $request->input('medical_questions', []);

        DB::transaction(function () use ($patient, $answers) {

            $session = MedicalSession::create([
                'patient_id' => $patient->id,
            ]);

            $data = [];

            foreach ($answers as $questionId => $value) {
                if (!empty(trim($value))) {
                    $data[] = [
                        'patient_id'          => $patient->id,
                        'medical_session_id'  => $session->id,
                        'medical_question_id' => $questionId,
                        'answer_value'        => $value,
                        'created_at'          => now(),
                        'updated_at'          => now(),
                    ];
                }
            }

            if ($data) {
                PatientResponse::insert($data);
            }
        });

        return redirect()
            ->route('medical-history.answer_index')
            ->with('success', 'Medical history submitted successfully!');
    }

    /**
     * Show all patients with medical sessions & answers
     */
   public function answersIndex()
{
    $patients = Patient::whereHas('medicalSessions', function ($q) {
        $q->whereHas('responses');
    })
    ->with([
        'medicalSessions.responses.question'
    ])
    ->paginate(10);

    return view('medical-history.answer_index', compact('patients'));
}


    /**
     * (OPTIONAL / LEGACY)
     * Edit latest medical submission
     */
    public function edit(Patient $patient)
    {
        $questions = MedicalQuestion::all();

        $latestSession = $patient->medicalSessions()->latest()->first();

        $answers = $latestSession
            ? $latestSession->responses->pluck('answer_value', 'medical_question_id')
            : collect();

        return view('medical-history.edit', compact(
            'patient',
            'questions',
            'latestSession',
            'answers'
        ));
    }

    /**
     * (OPTIONAL / LEGACY)
     * Update latest medical submission
     */
    public function update(Request $request, Patient $patient)
    {
        $latestSession = $patient->medicalSessions()->latest()->first();

        if (!$latestSession) {
            return redirect()->back()->with('error', 'No medical submission found.');
        }

        $answers = $request->input('medical_questions', []);

        DB::transaction(function () use ($latestSession, $answers) {
            foreach ($answers as $questionId => $value) {
                $latestSession->responses()
                    ->where('medical_question_id', $questionId)
                    ->update([
                        'answer_value' => $value,
                        'updated_at'   => now(),
                    ]);
            }
        });

        return redirect()
            ->route('medical-history.answer_index')
            ->with('success', 'Latest medical history updated successfully!');
    }

   //PDF Generation

    public function updateSession(Request $request, MedicalSession $session)
    {
        $answers = $request->input('medical_questions', []);

        DB::transaction(function () use ($session, $answers) {
            foreach ($answers as $questionId => $value) {
                $session->responses()
                    ->where('medical_question_id', $questionId)
                    ->update([
                        'answer_value' => $value,
                        'updated_at'   => now(),
                    ]);
            }
        });

        return redirect()
            ->route('medical-history.answer_index')
            ->with('success', 'Medical session updated successfully!');
    }
  public function downloadMedicalPdf(MedicalSession $session)
{
    // Load the specific session with its specific responses and questions
    $session->load(['patient', 'responses.question']);
    
    $patient = $session->patient;
    $physician = auth()->user()->name ?? 'Physician';

    $pdf = Pdf::loadView('medical-history.medical_pdf', compact('patient', 'session', 'physician'))
              ->setPaper('a4', 'portrait');

    return $pdf->stream('medical_record_' . $patient->last_name . '_' . $session->id . '.pdf');
}
}