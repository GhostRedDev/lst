<?php

namespace App\Http\Controllers;

use App\Models\ChildHealth;
use App\Models\Patient;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ChildHealthController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display a listing of child health controls.
     */
    public function index(Request $request)
    {
        $query = ChildHealth::with(['patient.house.street.community.healthCenter']);

        // Búsqueda
        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->whereHas('patient', function($q) use ($search) {
                $q->where('last_names', 'LIKE', "%{$search}%")
                  ->orWhere('names', 'LIKE', "%{$search}%")
                  ->orWhere('id_card', 'LIKE', "%{$search}%");
            });
        }

        // Filtros
        if ($request->has('nutritional_status') && $request->nutritional_status != '') {
            $query->where('nutritional_status', $request->nutritional_status);
        }

        if ($request->has('patient_id') && $request->patient_id != '') {
            $query->where('patient_id', $request->patient_id);
        }

        $childHealths = $query->latest()->paginate(15);
        $patients = Patient::where('age', '<', 12)->orWhere('classification', 'NIÑO')->get();

        return view('child-health.index', compact('childHealths', 'patients'));
    }

    /**
     * Show the form for creating a new child health control.
     */
    public function create()
    {
        $patients = Patient::where('age', '<', 12)
            ->orWhere('classification', 'NIÑO')
            ->with(['house.street.community'])
            ->get();

        return view('child-health.create', compact('patients'));
    }

    /**
     * Store a newly created child health control.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'patient_id' => 'required|exists:patients,id',
            'weight' => 'required|numeric|min:0|max:50',
            'height' => 'required|numeric|min:0|max:200',
            'head_circumference' => 'nullable|numeric|min:0|max:60',
            'development_notes' => 'required|string|max:1000',
            'vaccination_status' => 'nullable|array',
            'vaccination_status.*.vaccine' => 'required|string|max:100',
            'vaccination_status.*.date' => 'required|date',
            'vaccination_status.*.dose' => 'required|string|max:50',
            'nutritional_status' => 'required|in:OPTIMO,RIESGO,DESNUTRICION,OBESIDAD',
            'next_control_date' => 'required|date|after:today',
            'observations' => 'nullable|string|max:1000',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $childHealthData = $request->all();
            
            // Calcular IMC
            $weight = $request->weight;
            $height = $request->height / 100; // convertir a metros
            $childHealthData['bmi'] = $height > 0 ? round($weight / ($height * $height), 2) : 0;

            // Determinar estado nutricional automáticamente si no se especifica
            if (empty($childHealthData['nutritional_status'])) {
                $childHealthData['nutritional_status'] = $this->calculateNutritionalStatus(
                    $request->weight, 
                    $request->height, 
                    $request->patient_id
                );
            }

            ChildHealth::create($childHealthData);

            // Actualizar la clasificación del paciente si es necesario
            $patient = Patient::find($request->patient_id);
            if ($patient && $patient->classification !== 'NIÑO') {
                $patient->update(['classification' => 'NIÑO']);
            }

            return response()->json([
                'success' => true,
                'message' => 'Control de niño sano registrado exitosamente.'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al registrar el control: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified child health control.
     */
    public function show(ChildHealth $childHealth)
    {
        $childHealth->load([
            'patient.house.street.community.healthCenter.municipality.state',
            'patient.childHealths' => function($query) use ($childHealth) {
                $query->where('id', '!=', $childHealth->id)->latest();
            }
        ]);

        return view('child-health.show', compact('childHealth'));
    }

    /**
     * Show the form for editing the specified child health control.
     */
    public function edit(ChildHealth $childHealth)
    {
        $childHealth->load(['patient.house.street.community']);
        $patients = Patient::where('age', '<', 12)
            ->orWhere('classification', 'NIÑO')
            ->with(['house.street.community'])
            ->get();

        return response()->json([
            'childHealth' => $childHealth,
            'patients' => $patients
        ]);
    }

    /**
     * Update the specified child health control.
     */
    public function update(Request $request, ChildHealth $childHealth)
    {
        $validator = Validator::make($request->all(), [
            'patient_id' => 'required|exists:patients,id',
            'weight' => 'required|numeric|min:0|max:50',
            'height' => 'required|numeric|min:0|max:200',
            'head_circumference' => 'nullable|numeric|min:0|max:60',
            'development_notes' => 'required|string|max:1000',
            'vaccination_status' => 'nullable|array',
            'vaccination_status.*.vaccine' => 'required|string|max:100',
            'vaccination_status.*.date' => 'required|date',
            'vaccination_status.*.dose' => 'required|string|max:50',
            'nutritional_status' => 'required|in:OPTIMO,RIESGO,DESNUTRICION,OBESIDAD',
            'next_control_date' => 'required|date|after:today',
            'observations' => 'nullable|string|max:1000',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $childHealthData = $request->all();
            
            // Recalcular IMC
            $weight = $request->weight;
            $height = $request->height / 100;
            $childHealthData['bmi'] = $height > 0 ? round($weight / ($height * $height), 2) : 0;

            $childHealth->update($childHealthData);

            return response()->json([
                'success' => true,
                'message' => 'Control de niño sano actualizado exitosamente.'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al actualizar el control: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified child health control.
     */
    public function destroy(ChildHealth $childHealth)
    {
        try {
            $childHealth->delete();

            return response()->json([
                'success' => true,
                'message' => 'Control de niño sano eliminado exitosamente.'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al eliminar el control: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Calculate nutritional status based on weight, height and age.
     */
    private function calculateNutritionalStatus($weight, $height, $patientId)
    {
        $patient = Patient::find($patientId);
        if (!$patient) return 'OPTIMO';

        $age = $patient->age;
        $bmi = $height > 0 ? $weight / (($height / 100) * ($height / 100)) : 0;

        // Lógica simplificada para determinar estado nutricional
        if ($bmi < 16) return 'DESNUTRICION';
        if ($bmi >= 16 && $bmi < 18.5) return 'RIESGO';
        if ($bmi >= 25) return 'OBESIDAD';
        
        return 'OPTIMO';
    }

    /**
     * Get child health controls by patient.
     */
    public function getByPatient($patientId)
    {
        $controls = ChildHealth::where('patient_id', $patientId)
            ->with(['patient.house.street.community'])
            ->latest()
            ->get();

        return response()->json($controls);
    }

    /**
     * Get upcoming child health controls.
     */
    public function getUpcomingControls()
    {
        $controls = ChildHealth::whereDate('next_control_date', '>=', today())
            ->whereDate('next_control_date', '<=', today()->addDays(30))
            ->with(['patient.house.street.community'])
            ->orderBy('next_control_date')
            ->get();

        return response()->json($controls);
    }

    /**
     * Export child health controls to Excel.
     */
    public function export(Request $request)
    {
        $query = ChildHealth::with(['patient.house.street.community.healthCenter']);

        if ($request->has('start_date') && $request->start_date) {
            $query->whereDate('created_at', '>=', $request->start_date);
        }

        if ($request->has('end_date') && $request->end_date) {
            $query->whereDate('created_at', '<=', $request->end_date);
        }

        $childHealths = $query->get();

        // Implementar exportación a Excel
        return response()->json([
            'success' => true,
            'data' => $childHealths,
            'message' => 'Export functionality to be implemented'
        ]);
    }
}