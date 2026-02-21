<?php

namespace App\Http\Controllers;

use App\Models\Radiograph;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\Patient;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class RadiographController extends Controller
{
    public function index(Request $request)
    {
        $patientId = $request->patient_id;
        $type = $request->type;
        $yearRange = $request->year_range;

        // 1. FOR FILTER: Only patients who HAVE radiographs
        $filterPatients = Patient::whereHas('radiographs')
            ->orderBy('last_name')
            ->get();

        // 2. FOR MODAL: ALL patients so you can add a new record for anyone
        $allPatients = Patient::orderBy('last_name')->get();

        // Get distinct types for dropdown
        $allTypes = Radiograph::select('type')->distinct()->pluck('type');

        $radiographs = Radiograph::with('patient')
            ->when($patientId, function ($query) use ($patientId) {
                $query->where('patient_id', $patientId);
            })
            ->when($type, function ($query) use ($type) {
                $query->where('type', $type);
            })
            ->when($yearRange, function ($query) use ($yearRange) {
                [$from, $to] = explode('-', $yearRange);
                $query->whereYear('date_taken', '>=', $from)
                      ->whereYear('date_taken', '<', $to);
            })
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('radiographs.index', [
            'radiographs' => $radiographs,
            'filterPatients' => $filterPatients, // Used for the top filter
            'allPatients' => $allPatients,       // Used for the Add/Edit Modal
            'types' => $allTypes,
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'patient_id' => 'required|exists:patients,id',
            'date_taken' => 'required|date',
            'type' => 'required|string|max:255',
            'image' => 'required|image|max:4096',
            'findings' => 'nullable|string',
        ]);

        $data['image_path'] = $request->file('image')->store('radiographs', 'public');

        Radiograph::create([
            'patient_id' => $data['patient_id'],
            'date_taken' => $data['date_taken'],
            'type' => $data['type'],
            'findings' => $data['findings'] ?? null,
            'image_path' => $data['image_path'],
        ]);

        return redirect()->route('radiographs.index')->with('success', 'Radiograph added successfully!');
    }

    public function update(Request $request, Radiograph $radiograph)
    {
        $data = $request->validate([
            'patient_id' => 'required|exists:patients,id',
            'date_taken' => 'required|date',
            'type' => 'required|string|max:255',
            'image' => 'nullable|image|max:4096',
            'findings' => 'nullable|string',
        ]);

        if ($request->hasFile('image')) {
            Storage::disk('public')->delete($radiograph->image_path);
            $data['image_path'] = $request->file('image')->store('radiographs', 'public');
        }

        $radiograph->update([
            'patient_id' => $data['patient_id'],
            'date_taken' => $data['date_taken'],
            'type' => $data['type'],
            'findings' => $data['findings'] ?? $radiograph->findings,
            'image_path' => $data['image_path'] ?? $radiograph->image_path,
        ]);

        return redirect()->route('radiographs.index')->with('success', 'Radiograph updated successfully!');
    }

    public function destroy(Radiograph $radiograph)
    {
        Storage::disk('public')->delete($radiograph->image_path);
        $radiograph->delete();
        return redirect()->back()->with('success', 'Radiograph deleted successfully!');
    }

    public function downloadPdf(Radiograph $radiograph)
    {
        $radiograph->load('patient');
        $pdf = Pdf::loadView('radiographs.radiograph_pdf', compact('radiograph'))
             ->setPaper('a4', 'portrait');
        return $pdf->stream('radiograph-'.$radiograph->id.'.pdf');
    }
}