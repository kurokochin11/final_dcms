<?php

namespace App\Http\Controllers;

use App\Models\Radiograph;
use App\Models\Patient;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;


class RadiographController extends Controller
{
    /**
     * Display a listing of radiographs with optional filtering by type.
     */
   public function index(Request $request)
{
    $patientId = $request->patient_id;
    $type = $request->type;
    $yearRange = $request->year_range;

    // Get all types for dropdown
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

       $patients = Patient::orderBy('last_name')->get();
    
    return view('radiographs.index', [
        'radiographs' => $radiographs,
        'patients' => $patients,
        'types' => $allTypes,
    ]);
}

    /**
     * Store a newly created radiograph.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'patient_id' => 'required|exists:patients,id',
            'date_taken' => 'required|date',
            'type' => 'required|string|max:255',
            'image' => 'required|image|max:4096',
            'findings' => 'nullable|string',
        ]);

        // Store uploaded image
        $data['image_path'] = $request->file('image')->store('radiographs', 'public');

        // Create radiograph record
        Radiograph::create([
            'patient_id' => $data['patient_id'],
            'date_taken' => $data['date_taken'],
            'type' => $data['type'],
            'findings' => $data['findings'] ?? null,
            'image_path' => $data['image_path'],
        ]);

        return redirect()->route('radiographs.index')->with('success', 'Radiograph added successfully!');
    }

    /**
     * Update an existing radiograph.
     */
    public function update(Request $request, Radiograph $radiograph)
    {
        $data = $request->validate([
            'patient_id' => 'required|exists:patients,id',
            'date_taken' => 'required|date',
            'type' => 'required|string|max:255',
            'image' => 'nullable|image|max:4096',
            'findings' => 'nullable|string',
        ]);

        // Replace existing image if uploaded
        if ($request->hasFile('image')) {
            Storage::disk('public')->delete($radiograph->image_path);
            $data['image_path'] = $request->file('image')->store('radiographs', 'public');
        }

        // Update radiograph record
        $radiograph->update([
            'patient_id' => $data['patient_id'],
            'date_taken' => $data['date_taken'],
            'type' => $data['type'],
            'findings' => $data['findings'] ?? $radiograph->findings,
            'image_path' => $data['image_path'] ?? $radiograph->image_path,
        ]);

        return redirect()->route('radiographs.index')->with('success', 'Radiograph updated successfully!');
    }

    /**
     * Remove a radiograph.
     */
    public function destroy(Radiograph $radiograph)
    {
        // Delete the stored image
        Storage::disk('public')->delete($radiograph->image_path);

        // Delete the radiograph record
        $radiograph->delete();

        return redirect()->back()->with('success', 'Radiograph deleted successfully!');
    }
}
