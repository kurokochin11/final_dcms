<?php

namespace App\Http\Controllers;

use App\Models\Radiograph;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\Patient;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

class RadiographController extends Controller
{
   public function index(Request $request)
{
    $patientId = $request->patient_id;
    $type = $request->type;
    $exactDate = $request->exact_date; // Capture selected date

    $filterPatients = Patient::whereHas('radiographs')->orderBy('last_name')->get();
    $allPatients = Patient::orderBy('last_name')->get();
    $allTypes = Radiograph::select('type')->distinct()->pluck('type');

    // 1. GET ALL UNIQUE DATES (The "Scroll" list)
    // This looks at every record and creates a list like "March 16, 2023"
    $availableDates = Radiograph::selectRaw("DATE(date_taken) as date_val, DATE_FORMAT(date_taken, '%M %d, %Y') as date_label")
        ->whereNotNull('date_taken')
        ->groupBy('date_val', 'date_label')
        ->orderBy('date_val', 'desc')
        ->get();

    // 2. APPLY FILTERS
    $radiographs = Radiograph::with('patient')
        ->when($patientId, function ($query) use ($patientId) {
            $query->where('patient_id', $patientId);
        })
        ->when($type, function ($query) use ($type) {
            $query->where('type', $type);
        })
        ->when($exactDate, function ($query) use ($exactDate) {
            // Filter by the exact day selected from the scroll list
            $query->whereDate('date_taken', $exactDate);
        })
        ->latest('date_taken')
        ->paginate(10)
        ->withQueryString();

    return view('radiographs.index', compact(
        'radiographs', 'filterPatients', 'allPatients', 'availableDates', 'allTypes'
    ));
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
            // Delete old image if it exists
            if ($radiograph->image_path) {
                Storage::disk('public')->delete($radiograph->image_path);
            }
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
        if ($radiograph->image_path) {
            Storage::disk('public')->delete($radiograph->image_path);
        }
        
        $radiograph->delete();
        return redirect()->back()->with('success', 'Radiograph deleted successfully!');
    }

    public function downloadPdf(Radiograph $radiograph)
    {
        $physician = auth()->user()->name ?? 'Physician';

        $radiograph->load('patient');
      'physician' => $physician;
      
        $pdf = Pdf::loadView('radiographs.radiograph_pdf', compact('radiograph'))
                 ->setPaper('a4', 'portrait');
                 
        return $pdf->stream('radiograph-'.$radiograph->id.'.pdf');
    }
}