<?php

namespace App\Http\Controllers;

use App\Models\Billing;
use App\Models\Patient;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class BillingController extends Controller
{
  public function index(): View
{
    $billings = Billing::with('patient')->latest()->get();
    $patients = Patient::orderBy('last_name')->get();

    return view('billings.index', compact('billings', 'patients'));
}


    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'patient_id'         => 'required|exists:patients,id',
            'date'               => 'required|date',
            'service_rendered'   => 'required|string|max:255',
            'amount'             => 'required|numeric|min:0',
            'payment_method'     => 'nullable|string|max:255',
            'receipt_no'         => 'nullable|string|max:255',
            'outstanding_balance'=> 'nullable|numeric|min:0',
        ]);

        Billing::create($validated);

        return back()->with('success', 'Billing added successfully.');
    }

    public function update(Request $request, Billing $billing): RedirectResponse
    {
        $validated = $request->validate([
            'date'               => 'required|date',
            'service_rendered'   => 'required|string|max:255',
            'amount'             => 'required|numeric|min:0',
            'payment_method'     => 'nullable|string|max:255',
            'receipt_no'         => 'nullable|string|max:255',
            'outstanding_balance'=> 'nullable|numeric|min:0',
        ]);

        $billing->update($validated);

        return back()->with('success', 'Billing updated successfully.');
    }

    public function destroy(Billing $billing): RedirectResponse
    {
        $billing->delete();

        return back()->with('success', 'Billing deleted successfully.');
    }
}
