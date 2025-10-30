<?php

namespace App\Http\Controllers;

use App\Models\Patient;
use App\Models\EmergencyContact;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PatientController extends Controller
{
    /**
     * Display a listing of patients with optional search.
     *
     * If you want client-side autosuggest, the $names collection returns id + full name.
     */
    public function index(Request $request)
    {
        $search = $request->query('search');

        // Query with search and pagination, preserving query string for pagination links
        $patients = Patient::with('emergencyContact','checkupAnswers', 'medicalAnswers')
            ->when($search, function ($query, $search) {
                $query->where(function ($q) use ($search) {
                    $q->where('first_name', 'like', "%{$search}%")
                      ->orWhere('last_name', 'like', "%{$search}%")
                      ->orWhere('middle_name', 'like', "%{$search}%");
                });
            })
            ->latest()
            ->paginate(100)
            ->withQueryString();

        // Optional: names for client-side autosuggest (id + full_name)
        $names = Patient::select('id', 'first_name', 'last_name')
            ->orderBy('last_name')
            ->get()
            ->map(function ($p) {
                return [
                    'id' => $p->id,
                    'full_name' => trim("{$p->first_name} {$p->last_name}"),
                ];
            });

        return view('patients.index', compact('patients', 'names'));
    }

    /**
     * Store a newly created patient.
     */
    public function store(Request $request)
    {
        $validated = $this->validatePatient($request);

        DB::transaction(function () use ($validated) {
            $patient = Patient::create([
                'date_registered' => $validated['date_registered'] ?? now(),
                'first_name'      => $validated['first_name'],
                'last_name'       => $validated['last_name'],
                'middle_name'     => $validated['middle_name'] ?? null,
                'date_of_birth'   => $validated['date_of_birth'],
                'age'             => $validated['age'] ?? null,
                'sex'             => $validated['sex'],
                'civil_status'    => $validated['civil_status'],
                'nationality'     => $validated['nationality'] ?? null,
                'religion'        => $validated['religion'] ?? null,
                'occupation'      => $validated['occupation'] ?? null,
                'referred_by'     => $validated['referred_by'] ?? null,
                'mobile_number'   => $validated['mobile_number'] ?? null,
                'landline_number' => $validated['landline_number'] ?? null,
                'email'           => $validated['email'] ?? null,
                'address'         => $validated['address'] ?? null,
                'city'            => $validated['city'] ?? null,
                'province'        => $validated['province'] ?? null,
                'zip_code'        => $validated['zip_code'] ?? null,
            ]);

            EmergencyContact::create([
                'patient_id'      => $patient->id,
                'full_name'       => $validated['emergency_full_name'],
                'relationship'    => $validated['emergency_relationship'] ?? null,
                'mobile_number'   => $validated['emergency_mobile'] ?? null,
                'landline_number' => $validated['emergency_landline'] ?? null,
            ]);
        });

        return redirect()->route('patients.index')->with('success', 'Patient added successfully.');
    }

    /**
     * Update the specified patient.
     * Uses route model binding: update(Request $request, Patient $patient)
     */
    public function update(Request $request, Patient $patient)
    {
        $validated = $this->validatePatient($request);

        DB::transaction(function () use ($validated, $patient) {
            $patient->update([
                'first_name'      => $validated['first_name'],
                'last_name'       => $validated['last_name'],
                'middle_name'     => $validated['middle_name'] ?? null,
                'date_of_birth'   => $validated['date_of_birth'],
                'age'             => $validated['age'] ?? null,
                'sex'             => $validated['sex'],
                'civil_status'    => $validated['civil_status'],
                'nationality'     => $validated['nationality'] ?? null,
                'religion'        => $validated['religion'] ?? null,
                'occupation'      => $validated['occupation'] ?? null,
                'referred_by'     => $validated['referred_by'] ?? null,
                'mobile_number'   => $validated['mobile_number'] ?? null,
                'landline_number' => $validated['landline_number'] ?? null,
                'email'           => $validated['email'] ?? null,
                'address'         => $validated['address'] ?? null,
                'city'            => $validated['city'] ?? null,
                'province'        => $validated['province'] ?? null,
                'zip_code'        => $validated['zip_code'] ?? null,
                'date_registered' => $validated['date_registered'] ?? $patient->date_registered,
            ]);

            $patient->emergencyContact()->updateOrCreate(
                ['patient_id' => $patient->id],
                [
                    'full_name'       => $validated['emergency_full_name'],
                    'relationship'    => $validated['emergency_relationship'] ?? null,
                    'mobile_number'   => $validated['emergency_mobile'] ?? null,
                    'landline_number' => $validated['emergency_landline'] ?? null,
                ]
            );
        });

        return redirect()->route('patients.index')->with('success', 'Patient updated successfully.');
    }

    /**
     * Remove the specified patient.
     * Uses route model binding: destroy(Patient $patient)
     */
    public function destroy(Patient $patient)
    {
        DB::transaction(function () use ($patient) {
            if ($patient->emergencyContact) {
                $patient->emergencyContact()->delete();
            }
            $patient->delete();
        });

        return redirect()->route('patients.index')->with('success', 'Patient deleted successfully.');
    }

    /**
     * Centralized validation rules to avoid duplication.
     */
    protected function validatePatient(Request $request): array
    {
        return $request->validate([
            'first_name'             => 'required|string|max:100',
            'last_name'              => 'required|string|max:100',
            'middle_name'            => 'nullable|string|max:100',
            'date_of_birth'          => 'required|date',
            'age'                    => 'nullable|string|max:100',
            'sex'                    => 'required|in:Male,Female,Prefer not to say',
            'civil_status'           => 'required|in:Single,Married,Widowed,Separated,Divorced,Annulled,Commonlaw',
            'nationality'            => 'nullable|string|max:100',
            'religion'               => 'nullable|string|max:100',
            'occupation'             => 'nullable|string|max:100',
            'referred_by'            => 'nullable|string|max:100',
            'mobile_number'          => 'nullable|string|max:20',
            'landline_number'        => 'nullable|string|max:20',
            'email'                  => 'nullable|email|max:100',
            'address'                => 'nullable|string|max:255',
            'city'                   => 'nullable|string|max:100',
            'province'               => 'nullable|string|max:100',
            'zip_code'               => 'nullable|string|max:20',
            'date_registered'        => 'nullable|date',
            'emergency_full_name'    => 'required|string|max:150',
            'emergency_relationship' => 'nullable|string|max:100',
            'emergency_mobile'       => 'nullable|string|max:20',
            'emergency_landline'     => 'nullable|string|max:20',
        ]);
    }
}
