<?php

namespace App\Http\Controllers;

use App\Models\Patient;
use App\Models\Community;
use App\Models\Street;
use App\Models\House;
use App\Models\State;
use App\Models\Municipality;
use App\Models\HealthCenter;
use Illuminate\Http\Request;
use App\Exports\PatientsExport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Validator;

class PatientController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request)
    {
        $query = Patient::with(['house.street.community.healthCenter.municipality.state']);

        // Búsqueda mejorada
        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('history_number', 'LIKE', "%{$search}%")
                  ->orWhere('last_names', 'LIKE', "%{$search}%")
                  ->orWhere('names', 'LIKE', "%{$search}%")
                  ->orWhere('id_card', 'LIKE', "%{$search}%")
                  ->orWhere('phone', 'LIKE', "%{$search}%")
                  ->orWhereHas('house.street.community', function($q) use ($search) {
                      $q->where('name', 'LIKE', "%{$search}%");
                  })
                  ->orWhereHas('house.street', function($q) use ($search) {
                      $q->where('name', 'LIKE', "%{$search}%");
                  })
                  ->orWhereHas('house', function($q) use ($search) {
                      $q->where('house_number', 'LIKE', "%{$search}%");
                  });
            });
        }

        // Filtros mejorados
        $filters = [
            'gender', 'risk_factors', 'classification', 'dispensary_group',
            'housing_type', 'education', 'disability'
        ];

        foreach ($filters as $filter) {
            if ($request->has($filter) && $request->$filter != '') {
                $query->where($filter, $request->$filter);
            }
        }

        // Filtros por ubicación
        if ($request->has('state_id') && $request->state_id != '') {
            $query->whereHas('house.street.community.healthCenter.municipality', function($q) use ($request) {
                $q->where('state_id', $request->state_id);
            });
        }

        if ($request->has('municipality_id') && $request->municipality_id != '') {
            $query->whereHas('house.street.community.healthCenter', function($q) use ($request) {
                $q->where('municipality_id', $request->municipality_id);
            });
        }

        if ($request->has('health_center_id') && $request->health_center_id != '') {
            $query->whereHas('house.street.community', function($q) use ($request) {
                $q->where('health_center_id', $request->health_center_id);
            });
        }

        if ($request->has('community_id') && $request->community_id != '') {
            $query->whereHas('house.street', function($q) use ($request) {
                $q->where('community_id', $request->community_id);
            });
        }

        if ($request->has('street_id') && $request->street_id != '') {
            $query->whereHas('house', function($q) use ($request) {
                $q->where('street_id', $request->street_id);
            });
        }

        if ($request->has('house_id') && $request->house_id != '') {
            $query->where('house_id', $request->house_id);
        }

        $patients = $query->latest()->paginate(15);
        
        // Datos para filtros
        $states = State::all();
        $municipalities = Municipality::all();
        $health_centers = HealthCenter::all();
        $communities = Community::all();
        $houses = House::with(['street.community'])->get();

        if ($request->ajax()) {
            return view('patients.partials.table', compact('patients'))->render();
        }

        return view('patients.index', compact(
            'patients', 'states', 'municipalities', 'health_centers', 
            'communities', 'houses'
        ));
    }

    public function create(Request $request)
    {
        $states = State::all();
        $selectedHouse = null;
        
        if ($request->has('house_id')) {
            $selectedHouse = House::with(['street.community.healthCenter.municipality.state'])->find($request->house_id);
        }

        return view('patients.create', compact('states', 'selectedHouse'));
    }

   public function store(Request $request)
{
    // Validación corregida
    $validator = Validator::make($request->all(), [
        'history_number' => 'required|unique:patients|max:50',
        'code' => 'nullable|string|max:50',
        'id_card' => 'required|unique:patients|max:20',
        'last_names' => 'required|string|max:100',
        'names' => 'required|string|max:100',
        'house_id' => 'required|exists:houses,id',
        'birth_date' => 'required|date|before:today',
        'age' => 'required|integer|min:0|max:150',
        'gender' => 'required|in:M,F',
        'phone' => 'nullable|string|max:15',
        'housing_type' => 'required|in:CASA,APARTAMENTO,RANCHO,QUINTA,OTRO',
        'dispensary_group' => 'required|in:I,II,III,IV,V,VI',
        'risk_factors' => 'nullable|array',
        'disability' => 'required|in:SI,NO',
        'education' => 'required|in:ANALFABETA,PRIMARIA,SECUNDARIA,TECNICO,UNIVERSITARIO,POSTGRADO',
        'occupation' => 'required|string|max:100',
        'profession' => 'nullable|string|max:100',
        'next_appointment' => 'required|date|after:today',
        'classification' => 'required|in:AGUDO,CRONICO,DISCAPACITADO,GESTANTE,NIÑO,ADULTO,ADULTO_MAYOR',
        'diagnosis' => 'nullable|string|max:1000',
        'observation' => 'nullable|string|max:1000',
        'is_annexed' => 'boolean',
        'state_id' => 'required|exists:states,id',
        'municipality_id' => 'required|exists:municipalities,id',
        'health_center_id' => 'required|exists:health_centers,id',
        'community_id' => 'required|exists:communities,id',
        'street_id' => 'required|exists:streets,id',
    ], [
        'history_number.unique' => 'El número de historia ya existe.',
        'id_card.unique' => 'La cédula de identidad ya está registrada.',
        'birth_date.before' => 'La fecha de nacimiento debe ser anterior a hoy.',
        'next_appointment.after' => 'La próxima consulta debe ser posterior a hoy.',
        'house_id.exists' => 'La casa seleccionada no existe.',
    ]);

    if ($validator->fails()) {
        return redirect()->back()
            ->withErrors($validator)
            ->withInput()
            ->with('error', 'Por favor corrige los errores en el formulario.');
    }

    try {
        $patientData = $request->all();
        
        // Calcular edad automáticamente
        if (!empty($patientData['birth_date'])) {
            $birthDate = new \DateTime($patientData['birth_date']);
            $today = new \DateTime();
            $patientData['age'] = $today->diff($birthDate)->y;
        }

        // Procesar risk_factors como JSON
        if ($request->has('risk_factors') && is_array($request->risk_factors)) {
            $patientData['risk_factors'] = json_encode($request->risk_factors);
        } else {
            $patientData['risk_factors'] = null;
        }

        // Generar dirección automática
        if ($request->house_id) {
            $house = House::with(['street.community.healthCenter.municipality.state'])->find($request->house_id);
            if ($house) {
                $patientData['address'] = "Casa {$house->house_number}, {$house->street->name}, " .
                                         "{$house->street->community->name}, " .
                                         "{$house->street->community->healthCenter->municipality->name}, " .
                                         "{$house->street->community->healthCenter->municipality->state->name}";
            }
        }

        // Asegurar que is_annexed sea booleano
        $patientData['is_annexed'] = $request->has('is_annexed') ? true : false;

        // Limpiar campos opcionales
        $patientData['code'] = $patientData['code'] ?? null;
        $patientData['phone'] = $patientData['phone'] ?? null;
        $patientData['profession'] = $patientData['profession'] ?? null;
        $patientData['diagnosis'] = $patientData['diagnosis'] ?? null;
        $patientData['observation'] = $patientData['observation'] ?? null;

        // Crear el paciente
        Patient::create($patientData);
        
        return redirect()->route('patients.index')
            ->with('success', 'Paciente registrado exitosamente.');
            
    } catch (\Exception $e) {
        \Log::error('Error al crear paciente: ' . $e->getMessage());
        return redirect()->back()
            ->with('error', 'Error al registrar el paciente: ' . $e->getMessage())
            ->withInput();
    }
}

    public function show(Patient $patient)
    {
        $patient->load([
            'house.street.community.healthCenter.municipality.state',
            'pregnancies',
            'childHealths',
            'homeVisits'
        ]);
        
        return view('patients.show', compact('patient'));
    }

    public function edit(Patient $patient)
    {
        $patient->load(['house.street.community.healthCenter.municipality.state']);
        $states = State::all();
        
        return view('patients.edit', compact('patient', 'states'));
    }

    public function update(Request $request, Patient $patient)
    {
        $validator = Validator::make($request->all(), [
            'history_number' => 'required|unique:patients,history_number,' . $patient->id . '|max:50',
            'code' => 'nullable|string|max:50',
            'last_names' => 'required|string|max:100',
            'names' => 'required|string|max:100',
            'house_id' => 'required|exists:houses,id',
            'dispensary_group' => 'required|in:I,II,III,IV,V,VI',
            'housing_type' => 'required|in:CASA,APARTAMENTO,RANCHO,QUINTA,OTRO',
            'risk_factors' => 'nullable|array',
            'birth_date' => 'required|date|before:today',
            'age' => 'required|integer|min:0|max:150',
            'gender' => 'required|in:M,F',
            'id_card' => 'required|unique:patients,id_card,' . $patient->id . '|max:20',
            'phone' => 'nullable|string|max:15',
            'next_appointment' => 'required|date|after:today',
            'diagnosis' => 'nullable|string|max:1000',
            'education' => 'required|in:ANALFABETA,PRIMARIA,SECUNDARIA,TECNICO,UNIVERSITARIO,POSTGRADO',
            'occupation' => 'required|string|max:100',
            'profession' => 'nullable|string|max:100',
            'disability' => 'required|in:SI,NO',
            'classification' => 'required|in:AGUDO,CRONICO,DISCAPACITADO,GESTANTE,NIÑO,ADULTO,ADULTO_MAYOR',
            'observation' => 'nullable|string|max:1000',
            'is_annexed' => 'boolean',
            'state_id' => 'required|exists:states,id',
            'municipality_id' => 'required|exists:municipalities,id',
            'health_center_id' => 'required|exists:health_centers,id',
            'community_id' => 'required|exists:communities,id',
            'street_id' => 'required|exists:streets,id',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput()
                ->with('error', 'Por favor corrige los errores en el formulario.');
        }

        try {
            $patientData = $request->all();
            
            // Recalcular edad
            if (!empty($patientData['birth_date'])) {
                $birthDate = new \DateTime($patientData['birth_date']);
                $today = new \DateTime();
                $patientData['age'] = $today->diff($birthDate)->y;
            }

            // Procesar risk_factors
            if ($request->has('risk_factors') && is_array($request->risk_factors)) {
                $patientData['risk_factors'] = json_encode($request->risk_factors);
            }

            // Actualizar dirección si cambió la casa
            if ($request->house_id && $request->house_id != $patient->house_id) {
                $house = House::with(['street.community.healthCenter.municipality.state'])->find($request->house_id);
                if ($house) {
                    $patientData['address'] = "Casa {$house->house_number}, {$house->street->name}, " .
                                             "{$house->street->community->name}, " .
                                             "{$house->street->community->healthCenter->municipality->name}, " .
                                             "{$house->street->community->healthCenter->municipality->state->name}";
                }
            }

            $patientData['is_annexed'] = $request->has('is_annexed') ? true : false;

            $patient->update($patientData);
            
            return redirect()->route('patients.index')
                ->with('success', 'Paciente actualizado exitosamente.');
                
        } catch (\Exception $e) {
            \Log::error('Error al actualizar paciente: ' . $e->getMessage());
            return redirect()->back()
                ->with('error', 'Error al actualizar el paciente: ' . $e->getMessage())
                ->withInput();
        }
    }

    public function destroy(Patient $patient)
    {
        try {
            // Verificar si tiene registros relacionados antes de eliminar
            if ($patient->pregnancies()->count() > 0 || 
                $patient->childHealths()->count() > 0 ||
                $patient->homeVisits()->count() > 0) {
                return redirect()->route('patients.index')
                    ->with('error', 'No se puede eliminar el paciente porque tiene registros relacionados.');
            }

            $patient->delete();
            
            return redirect()->route('patients.index')
                ->with('success', 'Paciente eliminado exitosamente.');
                
        } catch (\Exception $e) {
            \Log::error('Error al eliminar paciente: ' . $e->getMessage());
            return redirect()->route('patients.index')
                ->with('error', 'Error al eliminar el paciente: ' . $e->getMessage());
        }
    }

    public function export(Request $request)
    {
        // Aplicar los mismos filtros que en el index
        $query = Patient::with(['house.street.community.healthCenter.municipality.state']);

        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('history_number', 'LIKE', "%{$search}%")
                  ->orWhere('last_names', 'LIKE', "%{$search}%")
                  ->orWhere('names', 'LIKE', "%{$search}%")
                  ->orWhere('id_card', 'LIKE', "%{$search}%");
            });
        }
        
        $patients = $query->get();

        return Excel::download(new PatientsExport($patients), 'pacientes_' . date('Y-m-d_His') . '.xlsx');
    }

    // Métodos AJAX para el formulario
    public function getStreets($communityId)
    {
        $streets = Street::where('community_id', $communityId)->get();
        return response()->json($streets);
    }

    public function getHouses($streetId)
    {
        $houses = House::where('street_id', $streetId)->get();
        return response()->json($houses);
    }

    public function getHouseAddress($houseId)
    {
        $house = House::with(['street.community.healthCenter.municipality.state'])->find($houseId);
        
        if ($house) {
            return response()->json([
                'address' => "Casa {$house->house_number}, {$house->street->name}, " .
                           "{$house->street->community->name}, " .
                           "{$house->street->community->healthCenter->municipality->name}, " .
                           "{$house->street->community->healthCenter->municipality->state->name}"
            ]);
        }

        return response()->json(['error' => 'Casa no encontrada'], 404);
    }

    // Métodos para módulos especializados
    public function getPregnantPatients()
    {
        $patients = Patient::where('classification', 'GESTANTE')
            ->orWhereHas('pregnancies', function($query) {
                $query->where('active', true);
            })
            ->with(['house.street.community'])
            ->get();

        return response()->json($patients);
    }

    public function getChildPatients()
    {
        $patients = Patient::where('classification', 'NIÑO')
            ->orWhere('age', '<', 12)
            ->with(['house.street.community', 'childHealths'])
            ->get();

        return response()->json($patients);
    }
}