<?php

namespace App\Http\Controllers;
use App\Models\Radiograph;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class RadiographController extends Controller
{
    // Show all radiographs
    public function index()
    {
        $radiographs = Radiograph::paginate(10); // paginate 10 per page
    return view('radiographs.index', compact('radiographs'));
    }

    // Store new radiograph
    public function store(Request $request)
    {
        $data = $request->validate([
            'patient_name' => 'required|string',
            'date_taken' => 'required|date',
            'type' => 'required|string',
            'image' => 'required|image|max:4096',
            'findings' => 'nullable|string',
        ]);

        $data['image_path'] = $request->file('image')->store('radiographs', 'public');

        Radiograph::create($data);

        return redirect()->back()->with('success', 'Radiograph added!');
    }

    // Update radiograph
    public function update(Request $request, Radiograph $radiograph)
    {
        $data = $request->validate([
            'patient_name' => 'required|string',
            'date_taken' => 'required|date',
            'type' => 'required|string',
            'image' => 'nullable|image|max:4096',
            'findings' => 'nullable|string',
        ]);

        if ($request->hasFile('image')) {
            Storage::disk('public')->delete($radiograph->image_path);
            $data['image_path'] = $request->file('image')->store('radiographs', 'public');
        }

        $radiograph->update($data);

        return redirect()->back()->with('success', 'Radiograph updated!');
    }

    // Delete radiograph
    public function destroy(Radiograph $radiograph)
    {
        Storage::disk('public')->delete($radiograph->image_path);
        $radiograph->delete();

        return redirect()->back()->with('success', 'Radiograph deleted!');
    }
}
