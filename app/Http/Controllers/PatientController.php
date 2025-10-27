<?php

namespace App\Http\Controllers;

use App\Models\Patient;
use App\Models\EmergencyContact;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PatientController extends Controller
{
    public function index()
    {
        $patients = Patient::with('emergencyContact')->latest()->paginate(10);
        return view('patients.index', compact('patients'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'first_name'             => 'required|string|max:100',
            'last_name'              => 'required|string|max:100',
            'middle_name'            => 'nullable|string|max:100',
            'date_of_birth'          => 'required|date',
            'age'                    =>'nullable|string|max:100',
            'sex'                    => 'required|in:Male,Female,Prefer not to say',
           'civil_status' => 'required|in:Single,Married,Widowed,Separated,Divorced,Annulled,Commonlaw',
             'nationality'           => 'nullable|string|max:100',
              'religion'             => 'nullable|string|max:100',
               'occupation'          => 'nullable|string|max:100',
                  'referred_by'      => 'nullable|string|max:100',
            'mobile_number'          => 'nullable|string|max:20',
            'landline_number'        => 'nullable|string|max:20',
            'email'                  => 'nullable|email|max:100',
            'address'                => 'nullable|string|max:255',
            'city'                   => 'nullable|string|max:100',
            'province'               => 'nullable|string|max:100',
            'zip_code'               => 'nullable|string|max:20',
            'emergency_full_name'    => 'required|string|max:150',
            'emergency_relationship' => 'nullable|string|max:100',
            'emergency_mobile'       => 'nullable|string|max:20',
            'emergency_landline'     => 'nullable|string|max:20',
        ]);

        DB::transaction(function () use ($validated) {
            $patient = Patient::create([
                'date_registered' => now(),
                'first_name'      => $validated['first_name'],
                'last_name'       => $validated['last_name'],
                'middle_name'     => $validated['middle_name'] ?? null,
                'date_of_birth'   => $validated['date_of_birth'],
                    'age'    => $validated['age'] ?? null,
                'sex'             => $validated['sex'],
                'civil_status'    => $validated['civil_status'],
                 'nationality'    => $validated['nationality'] ?? null,
                 'religion'       => $validated['religion'] ?? null,
               'occupation'       => $validated['occupation'] ?? null,
                  'referred_by'    => $validated['referred_by'] ?? null,
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

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'first_name'             => 'required|string|max:100',
            'last_name'              => 'required|string|max:100',
            'middle_name'            => 'nullable|string|max:100',
            'date_of_birth'          => 'required|date',
              'age'                    =>'nullable|string|max:100',
            'sex'                    => 'required|in:Male,Female,Prefer not to say',
           'civil_status' => 'required|in:Single,Married,Widowed,Separated,Divorced,Annulled,Commonlaw',
             'nationality'           => 'nullable|string|max:100',
              'religion'             => 'nullable|string|max:100',
               'occupation'          => 'nullable|string|max:100',
                  'referred_by'      => 'nullable|string|max:100',
            'mobile_number'          => 'nullable|string|max:20',
            'landline_number'        => 'nullable|string|max:20',
            'email'                  => 'nullable|email|max:100',
            'address'                => 'nullable|string|max:255',
            'city'                   => 'nullable|string|max:100',
            'province'               => 'nullable|string|max:100',
            'zip_code'               => 'nullable|string|max:20',
            'emergency_full_name'    => 'required|string|max:150',
            'emergency_relationship' => 'nullable|string|max:100',
            'emergency_mobile'       => 'nullable|string|max:20',
            'emergency_landline'     => 'nullable|string|max:20',
        ]);

        DB::transaction(function () use ($validated, $id) {
            $patient = Patient::findOrFail($id);

            $patient->update([
                'first_name'      => $validated['first_name'],
                'last_name'       => $validated['last_name'],
                'middle_name'     => $validated['middle_name'] ?? null,
                'date_of_birth'   => $validated['date_of_birth'],
                         'age'    => $validated['age'] ?? null,  
                'sex'             => $validated['sex'],
                'civil_status'    => $validated['civil_status'],
                  'nationality'    => $validated['nationality'] ?? null,
                 'religion'       => $validated['religion'] ?? null,
               'occupation'       => $validated['occupation'] ?? null,
                  'referred_by'    =>$validated['referred_by'] ?? null,
                'mobile_number'   => $validated['mobile_number'] ?? null,
                'landline_number' => $validated['landline_number'] ?? null,
                'email'           => $validated['email'] ?? null,
                'address'         => $validated['address'] ?? null,
                'city'            => $validated['city'] ?? null,
                'province'        => $validated['province'] ?? null,
                'zip_code'        => $validated['zip_code'] ?? null,
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

    public function destroy($id)
    {
        DB::transaction(function () use ($id) {
            $patient = Patient::findOrFail($id);
            $patient->emergencyContact()->delete();
            $patient->delete();
        });

        return redirect()->route('patients.index')->with('success', 'Patient deleted successfully.');
    }
}