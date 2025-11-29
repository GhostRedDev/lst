<?php

namespace App\Http\Controllers;

use App\Models\State;
use App\Models\Municipality;
use App\Models\HealthCenter;
use App\Models\Community;
use App\Models\Street;
use App\Models\House;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class LocationController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    // ==================== DASHBOARD DE UBICACIONES ====================
    public function dashboard()
    {
        $stats = [
            'states' => State::count(),
            'municipalities' => Municipality::count(),
            'health_centers' => HealthCenter::count(),
            'communities' => Community::count(),
            'streets' => Street::count(),
            'houses' => House::count(),
            'patients' => \App\Models\Patient::count(),
        ];

        $recent_communities = Community::with(['healthCenter.municipality.state'])
            ->latest()
            ->take(5)
            ->get();

        return view('locations.dashboard', compact('stats', 'recent_communities'));
    }

    // ==================== ESTADOS ====================
    public function statesIndex()
    {
        $states = State::withCount(['municipalities'])
            ->with(['municipalities' => function($query) {
                $query->withCount(['healthCenters', 'communities']);
            }])
            ->get()
            ->map(function($state) {
                // Calcular counts manualmente
                $state->health_centers_count = 0;
                $state->communities_count = 0;
                
                foreach ($state->municipalities as $municipality) {
                    $state->health_centers_count += $municipality->health_centers_count;
                    $state->communities_count += $municipality->communities_count;
                }
                
                return $state;
            });

        return view('locations.states.index', compact('states'));
    }

    public function statesCreate()
    {
        return view('locations.states.create');
    }

    public function statesStore(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|unique:states|max:100',
            'code' => 'required|unique:states|max:10',
        ]);

        State::create($validated);

        return redirect()->route('locations.states.index')
            ->with('success', 'Estado creado exitosamente.');
    }

    public function statesEdit(State $state)
    {
        return view('locations.states.edit', compact('state'));
    }

    public function statesUpdate(Request $request, State $state)
    {
        $validated = $request->validate([
            'name' => 'required|unique:states,name,' . $state->id . '|max:100',
            'code' => 'required|unique:states,code,' . $state->id . '|max:10',
        ]);

        $state->update($validated);

        return redirect()->route('locations.states.index')
            ->with('success', 'Estado actualizado exitosamente.');
    }

    public function statesDestroy(State $state)
    {
        if ($state->municipalities()->count() > 0) {
            return redirect()->route('locations.states.index')
                ->with('error', 'No se puede eliminar el estado porque tiene municipios asociados.');
        }

        $state->delete();

        return redirect()->route('locations.states.index')
            ->with('success', 'Estado eliminado exitosamente.');
    }

    // ==================== MUNICIPIOS ====================
    public function municipalitiesIndex(Request $request)
    {
        $query = Municipality::with(['state', 'healthCenters']);

        if ($request->has('state_id') && $request->state_id) {
            $query->where('state_id', $request->state_id);
        }

        $municipalities = $query->withCount(['healthCenters', 'communities'])
            ->get();

        $states = State::all();

        return view('locations.municipalities.index', compact('municipalities', 'states'));
    }

    public function municipalitiesCreate()
    {
        $states = State::all();
        return view('locations.municipalities.create', compact('states'));
    }

    public function municipalitiesStore(Request $request)
    {
        $validated = $request->validate([
            'state_id' => 'required|exists:states,id',
            'name' => 'required|max:100',
            'code' => 'required|max:10|unique:municipalities',
        ]);

        Municipality::create($validated);

        return redirect()->route('locations.municipalities.index')
            ->with('success', 'Municipio creado exitosamente.');
    }

    public function municipalitiesEdit(Municipality $municipality)
    {
        $states = State::all();
        return view('locations.municipalities.edit', compact('municipality', 'states'));
    }

    public function municipalitiesUpdate(Request $request, Municipality $municipality)
    {
        $validated = $request->validate([
            'state_id' => 'required|exists:states,id',
            'name' => 'required|max:100',
            'code' => 'required|max:10|unique:municipalities,code,' . $municipality->id,
        ]);

        $municipality->update($validated);

        return redirect()->route('locations.municipalities.index')
            ->with('success', 'Municipio actualizado exitosamente.');
    }

    public function municipalitiesDestroy(Municipality $municipality)
    {
        if ($municipality->healthCenters()->count() > 0) {
            return redirect()->route('locations.municipalities.index')
                ->with('error', 'No se puede eliminar el municipio porque tiene centros de salud asociados.');
        }

        $municipality->delete();

        return redirect()->route('locations.municipalities.index')
            ->with('success', 'Municipio eliminado exitosamente.');
    }

    // ==================== CENTROS DE SALUD ====================
    public function healthCentersIndex(Request $request)
    {
        $query = HealthCenter::with(['municipality.state']);

        if ($request->has('municipality_id') && $request->municipality_id) {
            $query->where('municipality_id', $request->municipality_id);
        }

        if ($request->has('type') && $request->type) {
            $query->where('type', $request->type);
        }

        $health_centers = $query->withCount(['communities', 'patients'])
            ->get();

        $municipalities = Municipality::with('state')->get();
        $types = [
            'CDI', 'CPT_I', 'CPT_II', 'CPT_III', 'CPT_IV',
            'AMBULATORIO_I', 'AMBULATORIO_II', 'AMBULATORIO_III', 'AMBULATORIO_IV'
        ];

        return view('locations.health-centers.index', compact('health_centers', 'municipalities', 'types'));
    }

    public function healthCentersCreate()
    {
        $municipalities = Municipality::with('state')->get();
        $types = [
            'CDI', 'CPT_I', 'CPT_II', 'CPT_III', 'CPT_IV',
            'AMBULATORIO_I', 'AMBULATORIO_II', 'AMBULATORIO_III', 'AMBULATORIO_IV'
        ];
        
        return view('locations.health-centers.create', compact('municipalities', 'types'));
    }

    public function healthCentersStore(Request $request)
    {
        $validated = $request->validate([
            'municipality_id' => 'required|exists:municipalities,id',
            'name' => 'required|max:200',
            'type' => 'required|in:CDI,CPT_I,CPT_II,CPT_III,CPT_IV,AMBULATORIO_I,AMBULATORIO_II,AMBULATORIO_III,AMBULATORIO_IV',
            'address' => 'required|max:500',
            'phone' => 'nullable|max:20',
        ]);

        HealthCenter::create($validated);

        return redirect()->route('locations.health-centers.index')
            ->with('success', 'Centro de salud creado exitosamente.');
    }

    public function healthCentersShow(HealthCenter $healthCenter)
    {
        $healthCenter->load([
            'municipality.state',
            'communities' => function($query) {
                $query->withCount(['streets', 'houses', 'patients']);
            }
        ]);

        return view('locations.health-centers.show', compact('healthCenter'));
    }

    public function healthCentersEdit(HealthCenter $healthCenter)
    {
        $municipalities = Municipality::with('state')->get();
        $types = [
            'CDI', 'CPT_I', 'CPT_II', 'CPT_III', 'CPT_IV',
            'AMBULATORIO_I', 'AMBULATORIO_II', 'AMBULATORIO_III', 'AMBULATORIO_IV'
        ];
        
        return view('locations.health-centers.edit', compact('healthCenter', 'municipalities', 'types'));
    }

    public function healthCentersUpdate(Request $request, HealthCenter $healthCenter)
    {
        $validated = $request->validate([
            'municipality_id' => 'required|exists:municipalities,id',
            'name' => 'required|max:200',
            'type' => 'required|in:CDI,CPT_I,CPT_II,CPT_III,CPT_IV,AMBULATORIO_I,AMBULATORIO_II,AMBULATORIO_III,AMBULATORIO_IV',
            'address' => 'required|max:500',
            'phone' => 'nullable|max:20',
            'latitude' => 'nullable|numeric|between:-90,90',
            'longitude' => 'nullable|numeric|between:-180,180',
        ]);

        $healthCenter->update($validated);

        return redirect()->route('locations.health-centers.index')
            ->with('success', 'Centro de salud actualizado exitosamente.');
    }

    public function healthCentersDestroy(HealthCenter $healthCenter)
    {
        if ($healthCenter->communities()->count() > 0) {
            return redirect()->route('locations.health-centers.index')
                ->with('error', 'No se puede eliminar el centro de salud porque tiene comunidades asociadas.');
        }

        $healthCenter->delete();

        return redirect()->route('locations.health-centers.index')
            ->with('success', 'Centro de salud eliminado exitosamente.');
    }

    // ==================== COMUNIDADES ====================
    public function communitiesIndex(Request $request)
    {
        $query = Community::with(['healthCenter.municipality.state']);

        if ($request->has('health_center_id') && $request->health_center_id) {
            $query->where('health_center_id', $request->health_center_id);
        }

        $communities = $query->withCount(['streets', 'houses', 'patients'])
            ->get()
            ->map(function($community) {
                $community->patients_count = $community->getPatientsCountAttribute();
                return $community;
            });

        $health_centers = HealthCenter::with('municipality.state')->get();

        return view('locations.communities.index', compact('communities', 'health_centers'));
    }

    public function communitiesCreate()
    {
        $health_centers = HealthCenter::with('municipality.state')->get();
        return view('locations.communities.create', compact('health_centers'));
    }

    public function communitiesStore(Request $request)
    {
        $validated = $request->validate([
            'health_center_id' => 'required|exists:health_centers,id',
            'name' => 'required|max:100',
            'sector' => 'nullable|max:100',
            'description' => 'nullable|max:500',
            'latitude' => 'nullable|numeric|between:-90,90',
            'longitude' => 'nullable|numeric|between:-180,180',
        ]);

        // Crear manualmente para asegurar los datos
        $community = new Community();
        $community->health_center_id = $validated['health_center_id'];
        $community->name = $validated['name'];
        $community->sector = $validated['sector'] ?? null;
        $community->description = $validated['description'] ?? null;
        $community->latitude = $validated['latitude'] ?? null;
        $community->longitude = $validated['longitude'] ?? null;
        $community->save();

        return redirect()->route('locations.communities.index')
            ->with('success', 'Comunidad creada exitosamente.');
    }

    public function communitiesShow(Community $community)
    {
        $community->load([
            'healthCenter.municipality.state',
            'streets' => function($query) {
                $query->withCount(['houses', 'patients']);
            },
            'streets.houses' => function($query) {
                $query->withCount('patients');
            }
        ]);

        return view('locations.communities.show', compact('community'));
    }

    public function communitiesEdit(Community $community)
    {
        $health_centers = HealthCenter::with('municipality.state')->get();
        return view('locations.communities.edit', compact('community', 'health_centers'));
    }

    public function communitiesUpdate(Request $request, Community $community)
    {
        $validated = $request->validate([
            'health_center_id' => 'required|exists:health_centers,id',
            'name' => 'required|max:100|unique:communities,name,' . $community->id,
            'sector' => 'nullable|max:100',
            'description' => 'nullable|max:500',
            'latitude' => 'nullable|numeric|between:-90,90',
            'longitude' => 'nullable|numeric|between:-180,180',
        ]);

        $community->update($validated);

        return redirect()->route('locations.communities.index')
            ->with('success', 'Comunidad actualizada exitosamente.');
    }

    public function communitiesDestroy(Community $community)
    {
        if ($community->streets()->count() > 0) {
            return redirect()->route('locations.communities.index')
                ->with('error', 'No se puede eliminar la comunidad porque tiene calles asociadas.');
        }

        $community->delete();

        return redirect()->route('locations.communities.index')
            ->with('success', 'Comunidad eliminada exitosamente.');
    }

    // ==================== CALLES ====================
    public function streetsIndex(Request $request)
    {
        $query = Street::with(['community.healthCenter.municipality.state']);

        if ($request->has('community_id') && $request->community_id) {
            $query->where('community_id', $request->community_id);
        }

        $streets = $query->withCount(['houses', 'patients'])->get();
        $communities = Community::with('healthCenter.municipality.state')->get();

        return view('locations.streets.index', compact('streets', 'communities'));
    }

    public function streetsCreate(Community $community = null)
    {
        $communities = Community::with('healthCenter.municipality.state')->get();
        
        return view('locations.streets.create', compact('communities', 'community'));
    }

    public function streetsStore(Request $request)
    {
        $validated = $request->validate([
            'community_id' => 'required|exists:communities,id',
            'name' => 'required|max:100',
            'description' => 'nullable|max:500',
        ]);

        // Verificar que no exista una calle con el mismo nombre en la misma comunidad
        $existingStreet = Street::where('community_id', $request->community_id)
            ->where('name', $request->name)
            ->first();

        if ($existingStreet) {
            return redirect()->route('locations.streets.create')
                ->with('error', 'Ya existe una calle con este nombre en la comunidad seleccionada.')
                ->withInput();
        }

        Street::create($validated);

        return redirect()->route('locations.streets.index')
            ->with('success', 'Calle creada exitosamente.');
    }

    public function streetsShow(Street $street)
    {
        $street->load([
            'community.healthCenter.municipality.state',
            'houses' => function($query) {
                $query->withCount('patients');
            },
            'houses.patients'
        ]);

        return view('locations.streets.show', compact('street'));
    }

    public function streetsEdit(Street $street)
    {
        $communities = Community::with('healthCenter.municipality.state')->get();
        return view('locations.streets.edit', compact('street', 'communities'));
    }

    public function streetsUpdate(Request $request, Street $street)
    {
        $validated = $request->validate([
            'community_id' => 'required|exists:communities,id',
            'name' => 'required|max:100|unique:streets,name,' . $street->id . ',id,community_id,' . $request->community_id,
            'description' => 'nullable|max:500',
        ]);

        $street->update($validated);

        return redirect()->route('locations.streets.index')
            ->with('success', 'Calle actualizada exitosamente.');
    }

    public function streetsDestroy(Street $street)
    {
        if ($street->houses()->count() > 0) {
            return redirect()->route('locations.streets.index')
                ->with('error', 'No se puede eliminar la calle porque tiene casas asociadas.');
        }

        $street->delete();

        return redirect()->route('locations.streets.index')
            ->with('success', 'Calle eliminada exitosamente.');
    }

    // ==================== CASAS ====================
    public function housesIndex(Request $request)
    {
        $query = House::with(['street.community.healthCenter.municipality.state']);

        if ($request->has('street_id') && $request->street_id) {
            $query->where('street_id', $request->street_id);
        }

        if ($request->has('community_id') && $request->community_id) {
            $query->whereHas('street', function($q) use ($request) {
                $q->where('community_id', $request->community_id);
            });
        }

        $houses = $query->withCount('patients')->get();
        $streets = Street::with('community.healthCenter.municipality.state')->get();
        $communities = Community::with('healthCenter.municipality.state')->get();

        return view('locations.houses.index', compact('houses', 'streets', 'communities'));
    }

    public function housesCreate(Street $street = null)
    {
        $streets = Street::with('community.healthCenter.municipality.state')->get();
        return view('locations.houses.create', compact('streets', 'street'));
    }

    public function housesStore(Request $request)
    {
        $validated = $request->validate([
            'street_id' => 'required|exists:streets,id',
            'house_number' => 'required|max:20',
            'description' => 'nullable|max:500',
        ]);

        // Verificar que no exista una casa con el mismo número en la misma calle
        $existingHouse = House::where('street_id', $request->street_id)
            ->where('house_number', $request->house_number)
            ->first();

        if ($existingHouse) {
            return redirect()->route('locations.houses.create')
                ->with('error', 'Ya existe una casa con este número en la calle seleccionada.')
                ->withInput();
        }

        House::create($validated);

        return redirect()->route('locations.houses.index')
            ->with('success', 'Casa creada exitosamente.');
    }

    public function housesShow(House $house)
    {
        $house->load([
            'street.community.healthCenter.municipality.state',
            'patients'
        ]);

        return view('locations.houses.show', compact('house'));
    }

    public function housesEdit(House $house)
    {
        $streets = Street::with('community.healthCenter.municipality.state')->get();
        return view('locations.houses.edit', compact('house', 'streets'));
    }

    public function housesUpdate(Request $request, House $house)
    {
        $validated = $request->validate([
            'street_id' => 'required|exists:streets,id',
            'house_number' => 'required|max:20|unique:houses,house_number,' . $house->id . ',id,street_id,' . $request->street_id,
            'description' => 'nullable|max:500',
        ]);

        $house->update($validated);

        return redirect()->route('locations.houses.index')
            ->with('success', 'Casa actualizada exitosamente.');
    }

    public function housesDestroy(House $house)
    {
        if ($house->patients()->count() > 0) {
            return redirect()->route('locations.houses.index')
                ->with('error', 'No se puede eliminar la casa porque tiene pacientes asociados.');
        }

        $house->delete();

        return redirect()->route('locations.houses.index')
            ->with('success', 'Casa eliminada exitosamente.');
    }

    public function housesBulkCreate(Street $street)
    {
        return view('locations.houses.bulk-create', compact('street'));
    }

    public function housesBulkStore(Request $request, Street $street)
    {
        $request->validate([
            'start_number' => 'required|integer|min:1',
            'end_number' => 'required|integer|min:1|gte:start_number',
            'prefix' => 'nullable|max:10',
            'suffix' => 'nullable|max:10',
        ]);

        $created = 0;
        $max_iterations = 1000; // Límite de seguridad

        for ($i = $request->start_number; $i <= $request->end_number && $created < $max_iterations; $i++) {
            $houseNumber = $request->prefix . $i . $request->suffix;
            
            // Verificar si ya existe
            $existingHouse = House::where('street_id', $street->id)
                ->where('house_number', $houseNumber)
                ->exists();

            if (!$existingHouse) {
                try {
                    // 🔥 CORRECCIÓN: Solo guardar campos que existen en la tabla
                    $street->houses()->create([
                        'house_number' => $houseNumber,
                        'description' => 'Casa generada automáticamente'
                        // No incluir 'total_residents' ya que no existe en la tabla
                    ]);
                    $created++;
                } catch (\Exception $e) {
                    continue;
                }
            }
        }

        return redirect()->route('locations.streets.show', $street)
            ->with('success', "Se crearon {$created} casas exitosamente.");
    }

    // ==================== MÉTODOS AJAX ====================
    public function getMunicipalitiesByState($stateId)
{
    $municipalities = Municipality::where('state_id', $stateId)->get();
    return response()->json($municipalities);
}

   public function getHealthCentersByMunicipality($municipalityId)
{
    $healthCenters = HealthCenter::where('municipality_id', $municipalityId)->get();
    return response()->json($healthCenters);
}
public function getCommunitiesByHealthCenter($healthCenterId)
{
    $communities = Community::where('health_center_id', $healthCenterId)->get();
    return response()->json($communities);
}

public function getStreetsByCommunity($communityId)
{
    $streets = Street::where('community_id', $communityId)->get();
    return response()->json($streets);
}

public function getHousesByStreet($streetId)
{
    $houses = House::where('street_id', $streetId)->get();
    return response()->json($houses);
}

   public function getHouseDetails($houseId)
{
    $house = House::with(['street.community.healthCenter.municipality.state'])->find($houseId);
    
    if ($house) {
        return response()->json([
            'full_address' => $house->full_address,
            'community_name' => $house->street->community->name,
            'health_center_name' => $house->street->community->healthCenter->name,
            'municipality_name' => $house->street->community->healthCenter->municipality->name,
            'state_name' => $house->street->community->healthCenter->municipality->state->name
        ]);
    }

    return response()->json(['error' => 'Casa no encontrada'], 404);
}
}