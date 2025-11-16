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
        $examinations = IntraoralExamination::with('patient')->latest()->paginate(10);
        $patients = Patient::orderBy('last_name')->get();

        return view('oral_examination.index_intraoral', compact('examinations', 'patients'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'patient_id' => 'required|exists:patients,id',
            'soft_tissues_status' => 'nullable|string|max:255',
            'soft_tissues_notes' => 'nullable|string',
            'gingiva_color' => 'nullable|string|max:100',
            'gingiva_texture' => 'nullable|string|max:100',
            'bleeding_on_probing' => 'nullable|boolean',
            'recession' => 'nullable|boolean',
            'bleeding_areas' => 'nullable|string',
            'recession_areas' => 'nullable|string',
            'hard_tissues_notes' => 'nullable|string',
            'odontogram' => 'nullable|string',
            'occlusion_class' => 'nullable|string|max:50',
            'occlusion_details' => 'nullable|string',
            'premature_contacts' => 'nullable|string',
            'mio' => 'nullable|integer|min:0|max:100',
            'oral_hygiene_status' => 'nullable|string|max:50',
            'plaque_index' => 'nullable|string',
            'calculus' => 'nullable|string',
            'notes' => 'nullable|string',
            'probing_depths_file' => 'nullable|image|mimes:jpg,jpeg,png,gif,webp|max:4096',
            'mobility_file' => 'nullable|image|mimes:jpg,jpeg,png,gif,webp|max:4096',
            'furcation_file' => 'nullable|image|mimes:jpg,jpeg,png,gif,webp|max:4096',
        ]);

        // Normalize checkbox booleans (checkboxes often send "1")
        $data['bleeding_on_probing'] = isset($data['bleeding_on_probing']) && (bool) $data['bleeding_on_probing'];
        $data['recession'] = isset($data['recession']) && (bool) $data['recession'];

        // Store files
        foreach (['probing_depths_file', 'mobility_file', 'furcation_file'] as $file) {
            if ($request->hasFile($file)) {
                $data[$file] = $request->file($file)->store('intraoral', 'public');
            }
        }

        IntraoralExamination::create($data);

        return redirect()->route('oral_examination.index_intraoral')->with('success', 'Intraoral examination added successfully!');
    }

    public function show(IntraoralExamination $intraoral_examination)
    {
        $intraoral_examination->load('patient');

        // Blade expects $exam variable in your show view — pass it that way
        return view('intraoral_examinations.show', ['exam' => $intraoral_examination]);
    }

    public function update(Request $request, IntraoralExamination $intraoral_examination)
    {
        $data = $request->validate([
            'patient_id' => 'required|exists:patients,id',
            'soft_tissues_status' => 'nullable|string|max:255',
            'soft_tissues_notes' => 'nullable|string',
            'gingiva_color' => 'nullable|string|max:100',
            'gingiva_texture' => 'nullable|string|max:100',
            'bleeding_on_probing' => 'nullable|boolean',
            'recession' => 'nullable|boolean',
            'bleeding_areas' => 'nullable|string',
            'recession_areas' => 'nullable|string',
            'hard_tissues_notes' => 'nullable|string',
            'odontogram' => 'nullable|string',
            'occlusion_class' => 'nullable|string|max:50',
            'occlusion_details' => 'nullable|string',
            'premature_contacts' => 'nullable|string',
            'mio' => 'nullable|integer|min:0|max:100',
            'oral_hygiene_status' => 'nullable|string|max:50',
            'plaque_index' => 'nullable|string',
            'calculus' => 'nullable|string',
            'notes' => 'nullable|string',
            'probing_depths_file' => 'nullable|image|mimes:jpg,jpeg,png,gif,webp|max:4096',
            'mobility_file' => 'nullable|image|mimes:jpg,jpeg,png,gif,webp|max:4096',
            'furcation_file' => 'nullable|image|mimes:jpg,jpeg,png,gif,webp|max:4096',
        ]);

        $data['bleeding_on_probing'] = isset($data['bleeding_on_probing']) && (bool) $data['bleeding_on_probing'];
        $data['recession'] = isset($data['recession']) && (bool) $data['recession'];

        foreach (['probing_depths_file', 'mobility_file', 'furcation_file'] as $file) {
            if ($request->hasFile($file)) {
                if ($intraoral_examination->$file) {
                    Storage::disk('public')->delete($intraoral_examination->$file);
                }
                $data[$file] = $request->file($file)->store('intraoral', 'public');
            } elseif ($request->filled('remove_' . $file)) {
                // optional: allow checkboxes named remove_probing_depths etc. to delete existing file
                if ($intraoral_examination->$file) {
                    Storage::disk('public')->delete($intraoral_examination->$file);
                }
                $data[$file] = null;
            }
        }

        $intraoral_examination->update($data);

        return redirect()->route('oral_examination.index_intraoral')->with('success', 'Intraoral examination updated successfully!');
    }

    public function destroy(IntraoralExamination $intraoral_examination)
    {
        foreach (['probing_depths_file', 'mobility_file', 'furcation_file'] as $file) {
            if ($intraoral_examination->$file) {
                Storage::disk('public')->delete($intraoral_examination->$file);
            }
        }

        $intraoral_examination->delete();

        return redirect()->route('oral_examination.index_intraoral')->with('success', 'Intraoral examination deleted successfully!');
    }
}
