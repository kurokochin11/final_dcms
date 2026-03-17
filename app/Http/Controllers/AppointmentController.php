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
        // For the main table
        $appointments = Appointment::with('patient')
            ->orderBy('appointment_date')
            ->orderBy('appointment_time')
            ->get();

        $patients = Patient::orderBy('last_name')->get();

        // For the notification bell: 
        // We fetch ALL today's appointments so that the dropdown can show 
        // both "read" (faded) and "unread" (bold) items.
        $todayScheduledAppointments = Appointment::with('patient')
            ->whereDate('appointment_date', today())
            ->get();

        return view('appointments.index', compact('appointments', 'patients', 'todayScheduledAppointments'));
    }

    /**
     * API: Get updates for the real-time heartbeat
     */
    public function getUpdates()
    {
        $updates = Appointment::with('patient')
            ->whereDate('appointment_date', today())
            ->get();

        return response()->json($updates);
    }

    /**
     * API: Mark a specific notification as read
     */
    public function markAsRead($id)
    {
        $appointment = Appointment::findOrFail($id);
        $appointment->update(['read_at' => now()]);

        return response()->json(['success' => true]);
    }

    /**
     * API: Mark all today's notifications as read
     */
    public function markAllAsRead()
    {
        Appointment::whereDate('appointment_date', today())
            ->whereNull('read_at')
            ->update(['read_at' => now()]);

        return response()->json(['success' => true]);
    }

    /**
     * Display the calendar view.
     */
    public function sampleCalendar()
    {
        $appointments = Appointment::with('patient')
            ->orderBy('appointment_date')
            ->orderBy('appointment_time')
            ->get();

        $events = $appointments->map(function ($a) {
            $color = match($a->status) {
                'Completed' => '#16a34a',
                'Cancelled' => '#dc2626',
                default     => '#2563eb',
            };

            return [
                'title' => $a->patient->first_name . ' (' . $a->status . ')',
                'start' => $a->appointment_date->format('Y-m-d') . 'T' . $a->appointment_time,
                'end'   => $a->appointment_date->format('Y-m-d') . 'T' . $a->appointment_time,
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
            'status' => 'nullable|in:Scheduled,Completed,Cancelled',
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