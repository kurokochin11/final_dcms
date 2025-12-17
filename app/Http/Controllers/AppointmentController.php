<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Models\Patient;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class AppointmentController extends Controller
{
    /**
     * Show calendar + appointments page
     */
    public function index()
    {
        $appointments = Appointment::with('patient')
            ->latest()
            ->paginate(10);

        $patients = Patient::orderByRaw("CONCAT(first_name, ' ', last_name) ASC")->get();

        return view('appointments.index', compact('appointments', 'patients'));
    }

    /**
     * Store new appointment
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'patient_id'       => 'required|exists:patients,id',
            'appointment_date' => 'required|date|after:now',
            'notes'            => 'nullable|string',
        ]);

        Appointment::create([
            'patient_id'       => $validated['patient_id'],
            'appointment_date' => $validated['appointment_date'],
            'notes'            => $validated['notes'] ?? null,
            'created_by'       => Auth::id(),
            'status'           => 'Scheduled',
        ]);

        return redirect()
            ->route('appointments.index')
            ->with('success', 'Appointment scheduled successfully.');
    }

    /**
     * JSON for edit modal
     */
    public function edit(Appointment $appointment)
    {
        return response()->json([
            'id'               => $appointment->id,
            'patient_id'       => $appointment->patient_id,
            'appointment_date' => $appointment->appointment_date->format('Y-m-d\TH:i'),
            'status'           => $appointment->status,
            'notes'            => $appointment->notes,
        ]);
    }

    /**
     * Update appointment (detect reschedule)
     */
    public function update(Request $request, Appointment $appointment)
    {
        $validated = $request->validate([
            'patient_id'       => 'required|exists:patients,id',
            'appointment_date' => 'required|date',
            'status'           => 'required|in:Scheduled,Completed,Cancelled',
            'notes'            => 'nullable|string',
        ]);

        // Detect reschedule
        if ($appointment->appointment_date->ne(Carbon::parse($validated['appointment_date']))) {
            $validated['rescheduled_at'] = now();
        }

        // Update the appointment
        $appointment->update($validated);

        return redirect()
            ->route('appointments.index')
            ->with('success', 'Appointment updated successfully.');
    }

    /**
     * Delete appointment
     */
    public function destroy(Appointment $appointment)
    {
        $appointment->delete();

        return redirect()
            ->route('appointments.index')
            ->with('success', 'Appointment deleted.');
    }

    /**
     * Calendar JSON endpoint
     */
    public function calendar()
    {
        $appointments = Appointment::with('patient')->get();

        return response()->json(
            $appointments->map(function ($appointment) {
                return [
                    'id'    => $appointment->id,
                    'title' => $appointment->patient?->first_name . ' ' . $appointment->patient?->last_name,
                    'start' => $appointment->appointment_date->toIso8601String(),

                    // color logic
                    'backgroundColor' => match (true) {
                        $appointment->status === 'Completed'  => '#16a34a', // green
                        $appointment->status === 'Cancelled'  => '#dc2626', // red
                        $appointment->rescheduled_at !== null => '#f59e0b', // yellow
                        default                               => '#2563eb', // blue
                    },

                    'borderColor' => 'transparent',
                ];
            })
        );
    }
}
