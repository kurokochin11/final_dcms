<?php

namespace App\Http\Controllers;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\Billing;
use App\Models\Patient;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\View\View;

class BillingController extends Controller
{
    /**
     * Display a listing of the billings.
     */
    public function index(Request $request): View
{
    $query = Billing::with('patient');

    // Filter by Patient ID
    if ($request->filled('patient_id')) {
        $query->where('patient_id', $request->patient_id);
    }

    // Filter by Date
    if ($request->filled('date')) {
        $query->whereDate('date', $request->date);
    }

    $billings = $query->latest()->get();
    $patients = Patient::orderBy('last_name')->get();

    return view('billings.index', compact('billings', 'patients'));
}

    /**
     * Store a newly created billing in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'patient_id'          => 'required|exists:patients,id',
            'date'                => 'required|date',
            'service_rendered'    => 'required|string|max:255',
            'amount'              => 'required|numeric|min:0',
            'payment_method'      => 'nullable|string|max:255',
            'receipt_no'          => 'nullable|string|max:255',
            'outstanding_balance' => 'nullable|numeric|min:0',
        ]);

        Billing::create($validated);

        return back()->with('success', 'Billing added successfully.');
    }

    /**
     * Display the specified billing (JSON for Modals).
     */
    public function show(Billing $billing): JsonResponse
    {
        // Load the patient relationship to show the name in the modal
        return response()->json($billing->load('patient'));
    }

    /**
     * Update the specified billing in storage.
     */
    public function update(Request $request, Billing $billing): RedirectResponse
    {
        $validated = $request->validate([
            'date'                => 'required|date',
            'service_rendered'    => 'required|string|max:255',
            'amount'              => 'required|numeric|min:0',
            'payment_method'      => 'nullable|string|max:255',
            'receipt_no'          => 'nullable|string|max:255',
            'outstanding_balance' => 'nullable|numeric|min:0',
        ]);

        $billing->update($validated);

        return back()->with('success', 'Billing updated successfully.');
    }
    
public function streamSinglePDF($id)
{
    $billing = Billing::with('patient')->findOrFail($id);
    
    // We pass the billing record as a collection of one to reuse the same PDF view
    $billings = collect([$billing]);
    
    $pdf = Pdf::loadView('billings.pdf', compact('billings'));
    return $pdf->stream("Billing_{$billing->id}.pdf");
}
    /**
     * Remove the specified billing from storage.
     */
    public function destroy(Billing $billing): RedirectResponse
    {
        $billing->delete();

        return back()->with('success', 'Billing deleted successfully.');
    }
}