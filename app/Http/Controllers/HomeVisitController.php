<?php

namespace App\Http\Controllers;

use App\Models\HomeVisit;
use App\Models\Patient;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class HomeVisitController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display a listing of home visits.
     */
    public function index(Request $request)
    {
        $query = HomeVisit::with(['patient.house.street.community.healthCenter']);

        // Búsqueda
        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('findings', 'LIKE', "%{$search}%")
                  ->orWhere('recommendations', 'LIKE', "%{$search}%")
                  ->orWhere('visited_by', 'LIKE', "%{$search}%")
                  ->orWhereHas('patient', function($q) use ($search) {
                      $q->where('last_names', 'LIKE', "%{$search}%")
                        ->orWhere('names', 'LIKE', "%{$search}%")
                        ->orWhere('id_card', 'LIKE', "%{$search}%");
                  });
            });
        }

        // Filtros
        if ($request->has('patient_id') && $request->patient_id != '') {
            $query->where('patient_id', $request->patient_id);
        }

        if ($request->has('visited_by') && $request->visited_by != '') {
            $query->where('visited_by', 'LIKE', "%{$request->visited_by}%");
        }

        if ($request->has('visit_date') && $request->visit_date != '') {
            $query->whereDate('visit_date', $request->visit_date);
        }

        if ($request->has('visit_type') && $request->visit_type != '') {
            $query->where('visit_type', $request->visit_type);
        }

        $homeVisits = $query->latest()->paginate(15);
        $patients = Patient::all();

        return view('home-visits.index', compact('homeVisits', 'patients'));
    }

    /**
     * Show the form for creating a new home visit.
     */
    public function create()
    {
        $patients = Patient::with(['house.street.community'])->get();
        $healthPersonnel = $this->getHealthPersonnel();

        return view('home-visits.create', compact('patients', 'healthPersonnel'));
    }

    /**
     * Store a newly created home visit.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'patient_id' => 'required|exists:patients,id',
            'visit_date' => 'required|date|before_or_equal:today',
            'visit_type' => 'required|in:SEGUIMIENTO,URGENCIA,PREVENTIVA,CONTROL',
            'findings' => 'required|string|max:2000',
            'recommendations' => 'required|string|max:2000',
            'visited_by' => 'required|string|max:100',
            'next_visit_date' => 'nullable|date|after:visit_date',
            'medications_prescribed' => 'nullable|array',
            'medications_prescribed.*.name' => 'required|string|max:100',
            'medications_prescribed.*.dosage' => 'required|string|max:50',
            'medications_prescribed.*.frequency' => 'required|string|max:50',
            'vital_signs' => 'nullable|array',
            'vital_signs.blood_pressure' => 'nullable|string|max:20',
            'vital_signs.heart_rate' => 'nullable|integer|min:0',
            'vital_signs.temperature' => 'nullable|numeric|min:0',
            'vital_signs.oxygen_saturation' => 'nullable|numeric|min:0|max:100',
            'needs_referral' => 'boolean',
            'referral_notes' => 'nullable|string|max:500',
            'follow_up_required' => 'boolean',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $homeVisitData = $request->all();
            
            // Asignar el usuario actual si no se especifica
            if (empty($homeVisitData['visited_by'])) {
                $homeVisitData['visited_by'] = auth()->user()->name;
            }

            HomeVisit::create($homeVisitData);

            return response()->json([
                'success' => true,
                'message' => 'Visita domiciliaria registrada exitosamente.'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al registrar la visita: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified home visit.
     */
    public function show(HomeVisit $homeVisit)
    {
        $homeVisit->load([
            'patient.house.street.community.healthCenter.municipality.state',
            'patient.homeVisits' => function($query) use ($homeVisit) {
                $query->where('id', '!=', $homeVisit->id)->latest();
            }
        ]);

        return view('home-visits.show', compact('homeVisit'));
    }

    /**
     * Show the form for editing the specified home visit.
     */
    public function edit(HomeVisit $homeVisit)
    {
        $homeVisit->load(['patient.house.street.community']);
        $patients = Patient::with(['house.street.community'])->get();
        $healthPersonnel = $this->getHealthPersonnel();

        return response()->json([
            'homeVisit' => $homeVisit,
            'patients' => $patients,
            'healthPersonnel' => $healthPersonnel
        ]);
    }

    /**
     * Update the specified home visit.
     */
    public function update(Request $request, HomeVisit $homeVisit)
    {
        $validator = Validator::make($request->all(), [
            'patient_id' => 'required|exists:patients,id',
            'visit_date' => 'required|date|before_or_equal:today',
            'visit_type' => 'required|in:SEGUIMIENTO,URGENCIA,PREVENTIVA,CONTROL',
            'findings' => 'required|string|max:2000',
            'recommendations' => 'required|string|max:2000',
            'visited_by' => 'required|string|max:100',
            'next_visit_date' => 'nullable|date|after:visit_date',
            'medications_prescribed' => 'nullable|array',
            'medications_prescribed.*.name' => 'required|string|max:100',
            'medications_prescribed.*.dosage' => 'required|string|max:50',
            'medications_prescribed.*.frequency' => 'required|string|max:50',
            'vital_signs' => 'nullable|array',
            'vital_signs.blood_pressure' => 'nullable|string|max:20',
            'vital_signs.heart_rate' => 'nullable|integer|min:0',
            'vital_signs.temperature' => 'nullable|numeric|min:0',
            'vital_signs.oxygen_saturation' => 'nullable|numeric|min:0|max:100',
            'needs_referral' => 'boolean',
            'referral_notes' => 'nullable|string|max:500',
            'follow_up_required' => 'boolean',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $homeVisit->update($request->all());

            return response()->json([
                'success' => true,
                'message' => 'Visita domiciliaria actualizada exitosamente.'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al actualizar la visita: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified home visit.
     */
    public function destroy(HomeVisit $homeVisit)
    {
        try {
            $homeVisit->delete();

            return response()->json([
                'success' => true,
                'message' => 'Visita domiciliaria eliminada exitosamente.'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al eliminar la visita: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get home visits by patient.
     */
    public function getByPatient($patientId)
    {
        $visits = HomeVisit::where('patient_id', $patientId)
            ->with(['patient.house.street.community'])
            ->latest()
            ->get();

        return response()->json($visits);
    }

    /**
     * Get upcoming home visits.
     */
    public function getUpcomingVisits()
    {
        $visits = HomeVisit::whereDate('next_visit_date', '>=', today())
            ->whereDate('next_visit_date', '<=', today()->addDays(30))
            ->with(['patient.house.street.community'])
            ->orderBy('next_visit_date')
            ->get();

        return response()->json($visits);
    }

    /**
     * Get today's home visits.
     */
    public function getTodayVisits()
    {
        $visits = HomeVisit::whereDate('visit_date', today())
            ->with(['patient.house.street.community'])
            ->orderBy('visit_date')
            ->get();

        return response()->json($visits);
    }

    /**
     * Get health personnel list.
     */
    private function getHealthPersonnel()
    {
        // En un sistema real, esto vendría de la base de datos
        return [
            'Dr. Juan Pérez',
            'Dra. María García',
            'Lic. Carlos López',
            'Enf. Ana Martínez',
            'Tec. Pedro Rodríguez',
            'Prom. Luisa González'
        ];
    }

    /**
     * Export home visits to Excel.
     */
    public function export(Request $request)
    {
        $query = HomeVisit::with(['patient.house.street.community.healthCenter']);

        if ($request->has('start_date') && $request->start_date) {
            $query->whereDate('visit_date', '>=', $request->start_date);
        }

        if ($request->has('end_date') && $request->end_date) {
            $query->whereDate('visit_date', '<=', $request->end_date);
        }

        if ($request->has('visit_type') && $request->visit_type) {
            $query->where('visit_type', $request->visit_type);
        }

        $homeVisits = $query->get();

        // Implementar exportación a Excel
        return response()->json([
            'success' => true,
            'data' => $homeVisits,
            'message' => 'Export functionality to be implemented'
        ]);
    }

    /**
     * Get home visit statistics.
     */
    public function getStatistics()
    {
        $stats = [
            'total_visits' => HomeVisit::count(),
            'visits_this_month' => HomeVisit::whereMonth('visit_date', now()->month)->count(),
            'visits_this_week' => HomeVisit::whereBetween('visit_date', [now()->startOfWeek(), now()->endOfWeek()])->count(),
            'today_visits' => HomeVisit::whereDate('visit_date', today())->count(),
            'by_type' => HomeVisit::select('visit_type', \DB::raw('count(*) as count'))
                ->groupBy('visit_type')
                ->get()
                ->pluck('count', 'visit_type'),
            'by_personnel' => HomeVisit::select('visited_by', \DB::raw('count(*) as count'))
                ->groupBy('visited_by')
                ->orderBy('count', 'desc')
                ->take(10)
                ->get()
        ];

        return response()->json($stats);
    }
}