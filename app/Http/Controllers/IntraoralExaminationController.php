<?php

namespace App\Http\Controllers;

use App\Models\IntraoralExamination;
use App\Models\Patient;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class IntraoralExaminationController extends Controller
{
    public function index()
    {
        $examinations = IntraoralExamination::with('patient')->latest()->paginate(12);
        $patients = Patient::orderBy('last_name')->get();

        return view('oral_examination.index_intraoral', compact('examinations', 'patients'));
    }

    public function create()
    {
        $patients = Patient::orderBy('last_name')->get();
        return view('oral_examination.create_intraoral', compact('patients'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'patient_id' => 'required|exists:patients,id',

            // Soft tissues
            'soft_tissues_status' => 'nullable|string|max:255',
            'soft_tissues_notes'  => 'nullable|string',

            // Gingiva
            'gingiva_color'   => 'nullable|string|max:100',
            'gingiva_texture' => 'nullable|string|max:100',
            'bleeding_on_probing' => 'nullable|in:1,0',
            'bleeding_areas'  => 'nullable|string',
            'recession'       => 'nullable|in:1,0',
            'recession_areas' => 'nullable|string',

            // Periodontium / Hard tissues (text)
            'hard_tissues_notes' => 'nullable|string',
            'odontogram' => 'nullable|string',

            // Occlusion
            'occlusion_class' => 'nullable|string|max:50',
            'occlusion_details' => 'nullable|string',
            'premature_contacts'=> 'nullable|string',

            // MIO
            'mio' => 'nullable|integer|min:0|max:100',

            // Oral hygiene
            'oral_hygiene_status' => 'nullable|string|max:50',
            'plaque_index' => 'nullable|string|max:50',
            'calculus' => 'nullable|string|max:50',
            'notes' => 'nullable|string',

            // files — image only
            'probing_depths_file' => 'nullable|image|mimes:jpg,jpeg,png,gif,webp|max:4096',
            'mobility_file'       => 'nullable|image|mimes:jpg,jpeg,png,gif,webp|max:4096',
            'furcation_file'      => 'nullable|image|mimes:jpg,jpeg,png,gif,webp|max:4096',
        ]);

        // Normalize booleans
        $data['bleeding_on_probing'] = isset($data['bleeding_on_probing']) && $data['bleeding_on_probing'] == '1';
        $data['recession'] = isset($data['recession']) && $data['recession'] == '1';

        // Store image files if provided
        if ($request->hasFile('probing_depths_file')) {
            $data['probing_depths_file'] = $request->file('probing_depths_file')->store('intraoral', 'public');
        }

        if ($request->hasFile('mobility_file')) {
            $data['mobility_file'] = $request->file('mobility_file')->store('intraoral', 'public');
        }

        if ($request->hasFile('furcation_file')) {
            $data['furcation_file'] = $request->file('furcation_file')->store('intraoral', 'public');
        }

        IntraoralExamination::create($data);

        return redirect()->back()->with('success', 'Intraoral examination saved.');
    }
 
    
    /**
     * Display a single intraoral examination record.
     */
    public function show(IntraoralExamination $intraoral_examination)
    {
        $intraoral_examination->load('patient');

        return view('intraoral_examinations.show', [
            'exam' => $intraoral_examination,
        ]);
    }

    public function update(Request $request, IntraoralExamination $intraoral_examination)
    {
        $data = $request->validate([
            'patient_id' => 'required|exists:patients,id',

            // Soft tissues
            'soft_tissues_status' => 'nullable|string|max:255',
            'soft_tissues_notes'  => 'nullable|string',

            // Gingiva
            'gingiva_color'   => 'nullable|string|max:100',
            'gingiva_texture' => 'nullable|string|max:100',
            'bleeding_on_probing' => 'nullable|in:1,0',
            'bleeding_areas'  => 'nullable|string',
            'recession'       => 'nullable|in:1,0',
            'recession_areas' => 'nullable|string',

            // Periodontium / Hard tissues (text)
            'hard_tissues_notes' => 'nullable|string',
            'odontogram' => 'nullable|string',

            // Occlusion
            'occlusion_class' => 'nullable|string|max:50',
            'occlusion_details' => 'nullable|string',
            'premature_contacts'=> 'nullable|string',

            // MIO
            'mio' => 'nullable|integer|min:0|max:100',

            // Oral hygiene
            'oral_hygiene_status' => 'nullable|string|max:50',
            'plaque_index' => 'nullable|string|max:50',
            'calculus' => 'nullable|string|max:50',
            'notes' => 'nullable|string',

            // files — image only
            'probing_depths_file' => 'nullable|image|mimes:jpg,jpeg,png,gif,webp|max:4096',
            'mobility_file'       => 'nullable|image|mimes:jpg,jpeg,png,gif,webp|max:4096',
            'furcation_file'      => 'nullable|image|mimes:jpg,jpeg,png,gif,webp|max:4096',

            // remove checkboxes
            'remove_probing_depths' => 'nullable',
            'remove_mobility' => 'nullable',
            'remove_furcation' => 'nullable',
        ]);

        $data['bleeding_on_probing'] = isset($data['bleeding_on_probing']) && $data['bleeding_on_probing'] == '1';
        $data['recession'] = isset($data['recession']) && $data['recession'] == '1';

        // Handle probing_depths_file upload / removal
        if ($request->hasFile('probing_depths_file')) {
            if ($intraoral_examination->probing_depths_file) {
                Storage::disk('public')->delete($intraoral_examination->probing_depths_file);
            }
            $data['probing_depths_file'] = $request->file('probing_depths_file')->store('intraoral', 'public');
        } elseif ($request->filled('remove_probing_depths')) {
            if ($intraoral_examination->probing_depths_file) {
                Storage::disk('public')->delete($intraoral_examination->probing_depths_file);
            }
            $data['probing_depths_file'] = null;
        }

        // Handle mobility_file upload / removal
        if ($request->hasFile('mobility_file')) {
            if ($intraoral_examination->mobility_file) {
                Storage::disk('public')->delete($intraoral_examination->mobility_file);
            }
            $data['mobility_file'] = $request->file('mobility_file')->store('intraoral', 'public');
        } elseif ($request->filled('remove_mobility')) {
            if ($intraoral_examination->mobility_file) {
                Storage::disk('public')->delete($intraoral_examination->mobility_file);
            }
            $data['mobility_file'] = null;
        }

        // Handle furcation_file upload / removal
        if ($request->hasFile('furcation_file')) {
            if ($intraoral_examination->furcation_file) {
                Storage::disk('public')->delete($intraoral_examination->furcation_file);
            }
            $data['furcation_file'] = $request->file('furcation_file')->store('intraoral', 'public');
        } elseif ($request->filled('remove_furcation')) {
            if ($intraoral_examination->furcation_file) {
                Storage::disk('public')->delete($intraoral_examination->furcation_file);
            }
            $data['furcation_file'] = null;
        }

        $intraoral_examination->update($data);

        return redirect()->back()->with('success', 'Intraoral examination updated.');
    }

    public function destroy(IntraoralExamination $intraoral_examination)
    {
        // delete stored files if any
        if ($intraoral_examination->probing_depths_file) {
            Storage::disk('public')->delete($intraoral_examination->probing_depths_file);
        }
        if ($intraoral_examination->mobility_file) {
            Storage::disk('public')->delete($intraoral_examination->mobility_file);
        }
        if ($intraoral_examination->furcation_file) {
            Storage::disk('public')->delete($intraoral_examination->furcation_file);
        }

        $intraoral_examination->delete();

        return redirect()->back()->with('success', 'Record deleted.');
    }
}
