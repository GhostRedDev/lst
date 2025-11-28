<?php

namespace App\Http\Controllers;

use App\Models\Pregnancy;
use App\Models\Patient;
use Illuminate\Http\Request;

class PregnancyController extends Controller
{
    public function index()
    {
        $pregnancies = Pregnancy::with(['patient.house.street.community'])
            ->where('active', true)
            ->latest()
            ->paginate(15);

        return view('pregnancies.index', compact('pregnancies'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'patient_id' => 'required|exists:patients,id',
            'last_menstrual_period' => 'required|date',
            'estimated_delivery' => 'required|date',
            'prenatal_controls' => 'required|integer|min:0',
            'risk_factors' => 'nullable|array',
            'notes' => 'nullable|string|max:1000',
        ]);

        $validated['active'] = true;

        Pregnancy::create($validated);

        return response()->json([
            'success' => true,
            'message' => 'Control de gestante registrado exitosamente.'
        ]);
    }
}