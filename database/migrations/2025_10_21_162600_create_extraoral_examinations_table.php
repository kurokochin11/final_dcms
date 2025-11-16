<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('extraoral_examinations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('patient_id')->constrained('patients')->onDelete('cascade');

            // Extraoral fields
            $table->string('facial_symmetry')->nullable(); // "Normal" or "Asymmetrical"
            $table->text('facial_symmetry_notes')->nullable();
            $table->string('lymph_nodes')->nullable(); // "Palpable" or "Non-palpable"
            $table->text('lymph_nodes_location')->nullable(); // specify locations if palpable

            // TMJ
            $table->boolean('tmj_pain')->nullable();
            $table->boolean('tmj_clicking')->nullable();
            $table->boolean('tmj_limited_opening')->nullable();

            // MIO in mm (integer)
            $table->integer('mio')->nullable()->comment('Maximum Interincisal Opening in mm');

            $table->text('notes')->nullable();

            $table->timestamps();
        });
    }
  public function store(Request $request)
    {
        $data = $request->validate([
            'patient_id' => 'required|exists:patients,id',
            'facial_symmetry' => 'nullable|string',
            'facial_symmetry_notes' => 'nullable|string',
            'lymph_nodes' => 'nullable|string',
            'lymph_nodes_location' => 'nullable|string',
            'tmj_pain' => 'nullable|boolean',
            'tmj_clicking' => 'nullable|boolean',
            'tmj_limited_opening' => 'nullable|boolean',
            'mio' => 'nullable|integer',
            'notes' => 'nullable|string',
        ]);

        ExtraoralExamination::create($data);

        return redirect()->route('extraoral-examinations.index')
                         ->with('success', 'Extraoral examination added successfully.');
    }

    // Show edit form
    public function edit(ExtraoralExamination $extraoral_examination)
    {
        return view('extraoral_examinations.edit', compact('extraoral_examination'));
    }

    // Update record
    public function update(Request $request, ExtraoralExamination $extraoral_examination)
    {
        $data = $request->validate([
            'facial_symmetry' => 'nullable|string',
            'facial_symmetry_notes' => 'nullable|string',
            'lymph_nodes' => 'nullable|string',
            'lymph_nodes_location' => 'nullable|string',
            'tmj_pain' => 'nullable|boolean',
            'tmj_clicking' => 'nullable|boolean',
            'tmj_limited_opening' => 'nullable|boolean',
            'mio' => 'nullable|integer',
            'notes' => 'nullable|string',
        ]);

        $extraoral_examination->update($data);

        return redirect()->route('extraoral-examinations.index')
                         ->with('success', 'Extraoral examination updated successfully.');
    }

    // Delete record
    public function destroy(ExtraoralExamination $extraoral_examination)
    {
        $extraoral_examination->delete();

        return redirect()->route('extraoral-examinations.index')
                         ->with('success', 'Extraoral examination deleted successfully.');
    }

    public function down(): void
    {
        Schema::dropIfExists('extraoral_examinations');
    }
};
