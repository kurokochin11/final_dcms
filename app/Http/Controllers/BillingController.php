<?php

namespace App\Http\Controllers;

use App\Models\Billing;
use Illuminate\Http\Request;

class BillingController extends Controller
{
    public function index()
    {
        $billings = Billing::latest()->get();
        return view('billings.index', compact('billings'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'date' => 'required|date',
            'service_rendered' => 'required|string|max:255',
            'amount' => 'required|numeric',
            'payment_method' => 'nullable|string|max:255',
            'receipt_no' => 'nullable|string|max:255',
            'outstanding_balance' => 'nullable|numeric',
        ]);

        Billing::create($request->all());

        return back()->with('success', 'Billing added successfully');
    }

    public function update(Request $request, Billing $billing)
    {
        $billing->update($request->all());

        return back()->with('success', 'Billing updated successfully');
    }

    public function destroy(Billing $billing)
    {
        $billing->delete();

        return back()->with('success', 'Billing deleted successfully');
    }
}
