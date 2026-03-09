<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Models\Patient;
use Illuminate\Http\Request;

class AppointmentController extends Controller
{
    /**
     * Display a listing of the appointments.
     */
    public function index()
    {
        $appointments = Appointment::with('patient')
            ->orderBy('appointment_date')
            ->orderBy('appointment_time')
            ->get();

        $patients = Patient::orderBy('last_name')->get();
      //  dd($appointments);
 //return response()->json($appointments);
        return view('appointments.index', compact('appointments', 'patients'));
    }
    public function sampleCalendar(){
         $appointments = Appointment::with('patient')
        ->orderBy('appointment_date')
        ->orderBy('appointment_time')
        ->get();

    $events = $appointments->map(function ($a) {
        // Determine color based on status
        $color = match($a->status) {
            'Completed' => '#16a34a',  // green
            'Cancelled' => '#dc2626',  // red
            'Rescheduled' => '#ea580c',  // Orange (Amber-600)
            default => '#2563eb',      // blue for Scheduled
        };

        return [
            'title' => $a->patient->first_name . ' (' . $a->status . ')',
            'start' => $a->appointment_date->format('Y-m-d') . 'T' . $a->appointment_time,
            'end' => $a->appointment_date->format('Y-m-d') . 'T' . $a->appointment_time, // optional if no duration
            'color' => $color,
        ];
    });

    return view('appointments.sampleCalendar', compact('events'));
    }

    /**
     * Store a newly created appointment.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'patient_id' => 'required|exists:patients,id',
            'appointment_date' => 'required|date',
            'appointment_time' => 'required',
            'purpose' => 'nullable|string',
            'status' => 'nullable|in:Scheduled,Completed,Cancelled,Rescheduled',
        ]);

        Appointment::create($validated + [
            'status' => $validated['status'] ?? 'Scheduled',
        ]);

        return redirect()
            ->route('appointments.index')
            ->with('success', 'Appointment created successfully.');
    }

    /**
     * Update the specified appointment.
     */
    public function update(Request $request, Appointment $appointment)
    {
        $validated = $request->validate([
            'patient_id' => 'required|exists:patients,id',
            'appointment_date' => 'required|date',
            'appointment_time' => 'required',
            'purpose' => 'nullable|string',
            'status' => 'required|in:Scheduled,Completed,Cancelled',
        ]);

        $appointment->update($validated);

        return redirect()
            ->route('appointments.index')
            ->with('success', 'Appointment updated successfully.');
    }

    /**
     * Remove the specified appointment.
     */
    public function destroy(Appointment $appointment)
    {
        $appointment->delete();

        return redirect()
            ->route('appointments.index')
            ->with('success', 'Appointment deleted successfully.');
    }
}
