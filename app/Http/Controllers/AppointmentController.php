<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Models\Patient;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AppointmentController extends Controller
{
    public function index()
    {
        // paginate appointments and also pass patients for the modal select
        $appointments = Appointment::with('patient')->latest()->paginate(10);
        $patients = Patient::orderBy('name')->get();

        return view('appointments.index', compact('appointments', 'patients'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'patient_id' => 'required|exists:patients,id',
            'appointment_date' => 'required|date|after:now',
            'notes' => 'nullable|string'
        ]);

        Appointment::create(array_merge($validated, [
            'created_by' => Auth::id(),
        ]));

        return redirect()->route('appointments.index')->with('success', 'Appointment scheduled successfully.');
    }

    /**
     * Return JSON for editing (used by modal).
     */
    public function edit(Appointment $appointment)
    {
        return response()->json([
            'id' => $appointment->id,
            'patient_id' => $appointment->patient_id,
            'appointment_date' => $appointment->appointment_date->format('Y-m-d\TH:i'), // for datetime-local input
            'status' => $appointment->status,
            'notes' => $appointment->notes,
        ]);
    }

    public function update(Request $request, Appointment $appointment)
    {
        $validated = $request->validate([
            'appointment_date' => 'required|date|after:now',
            'status' => 'required|in:Scheduled,Completed,Cancelled',
            'notes' => 'nullable|string'
        ]);

        $appointment->update($validated);

        return redirect()->route('appointments.index')->with('success', 'Appointment updated successfully.');
    }

    public function destroy(Appointment $appointment)
    {
        $appointment->delete();
        return redirect()->route('appointments.index')->with('success', 'Appointment deleted.');
    }
}
