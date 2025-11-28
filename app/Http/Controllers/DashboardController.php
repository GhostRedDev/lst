<?php

namespace App\Http\Controllers;

use App\Models\Patient;
use App\Models\Community;
use App\Models\House;
use App\Models\Street;
use App\Models\State;
use App\Models\Municipality;
use App\Models\HealthCenter;
use App\Models\Pregnancy;
use App\Models\ChildHealth;
use App\Models\HomeVisit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Support\Collection;

class DashboardController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Dashboard principal del sistema
     */
    public function dashboard()
    {
        try {
            // ==================== ESTADÍSTICAS PRINCIPALES ====================
            $stats = $this->getMainStats();
            
            // ==================== ESTADÍSTICAS POR UBICACIÓN ====================
            $locationStats = $this->getLocationStats();
            
            // ==================== ESTADÍSTICAS DEMOGRÁFICAS ====================
            $demographicStats = $this->getDemographicStats();
            
            // ==================== ACTIVIDAD RECIENTE ====================
            $recentActivity = $this->getRecentActivity();

            return view('dashboard.index', compact(
                'stats',
                'locationStats', 
                'demographicStats',
                'recentActivity'
            ));

        } catch (\Exception $e) {
            \Log::error('Error en dashboard: ' . $e->getMessage());
            
            return view('dashboard.index', [
                'stats' => $this->getEmptyStats(),
                'locationStats' => $this->getEmptyLocationStats(),
                'demographicStats' => $this->getEmptyDemographicStats(),
                'recentActivity' => $this->getEmptyRecentActivity(),
                'error' => 'Error al cargar los datos del dashboard'
            ]);
        }
    }

    /**
     * Página de bienvenida pública
     */
    public function welcome()
    {
        try {
            $stats = [
                'total_patients' => Patient::count(),
                'total_communities' => Community::count(),
                'total_health_centers' => HealthCenter::count(),
                'today_consultations' => Patient::whereDate('next_appointment', today())->count(),
            ];

            return view('welcome', compact('stats'));
        } catch (\Exception $e) {
            return view('welcome', [
                'stats' => [
                    'total_patients' => 0,
                    'total_communities' => 0,
                    'total_health_centers' => 0,
                    'today_consultations' => 0,
                ]
            ]);
        }
    }

    /**
     * Datos del dashboard via AJAX
     */
    public function getDashboardData()
    {
        try {
            $data = [
                'stats' => $this->getMainStats(),
                'timestamp' => now()->toDateTimeString()
            ];

            return response()->json([
                'success' => true,
                'data' => $data
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al cargar datos',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    // ==================== MÉTODOS PRIVADOS PARA ESTADÍSTICAS ====================

    private function getMainStats()
    {
        return [
            'total_patients' => Patient::count(),
            'today_consultations' => Patient::whereDate('next_appointment', today())->count(),
            'pending_consultations' => Patient::whereDate('next_appointment', '>', today())->count(),
            'active_pregnancies' => Pregnancy::where('active', true)->count(),
            'child_controls' => ChildHealth::whereDate('created_at', '>=', now()->subMonth())->count(),
            'home_visits' => HomeVisit::whereDate('visit_date', '>=', now()->subWeek())->count(),
            'patients_with_disability' => Patient::where('disability', 'SI')->count(),
            'high_risk_patients' => $this->getHighRiskPatientsCount(),
        ];
    }

    private function getHighRiskPatientsCount()
    {
        return Patient::where(function($query) {
            $query->where('risk_factors', 'LIKE', '%DIABETES%')
                  ->orWhere('risk_factors', 'LIKE', '%HIPERTENSION%')
                  ->orWhere('risk_factors', 'LIKE', '%CARDIACO%')
                  ->orWhere('risk_factors', 'LIKE', '%ALTO%')
                  ->orWhere('classification', 'CRONICO')
                  ->orWhere('age', '>=', 65);
        })->count();
    }

    private function getLocationStats()
    {
        return [
            'states' => State::count(),
            'municipalities' => Municipality::count(),
            'health_centers' => HealthCenter::count(),
            'communities' => Community::count(),
            'streets' => Street::count(),
            'houses' => House::count(),
            
            'top_communities' => Community::with(['healthCenter'])
                ->withCount(['patients'])
                ->having('patients_count', '>', 0)
                ->orderBy('patients_count', 'desc')
                ->take(5)
                ->get()
                ->map(function($community) {
                    return [
                        'name' => $community->name,
                        'patients_count' => $community->patients_count,
                        'health_center' => $community->healthCenter->name ?? 'N/A'
                    ];
                }),
                
            'top_health_centers' => HealthCenter::with(['municipality'])
                ->withCount(['patients'])
                ->having('patients_count', '>', 0)
                ->orderBy('patients_count', 'desc')
                ->take(5)
                ->get()
                ->map(function($healthCenter) {
                    return [
                        'name' => $healthCenter->name,
                        'type' => $healthCenter->type,
                        'patients_count' => $healthCenter->patients_count,
                        'municipality' => $healthCenter->municipality->name ?? 'N/A'
                    ];
                })
        ];
    }

    private function getDemographicStats()
    {
        // Grupos de edad
        $ageGroups = [
            '0-17' => Patient::whereBetween('age', [0, 17])->count(),
            '18-30' => Patient::whereBetween('age', [18, 30])->count(),
            '31-45' => Patient::whereBetween('age', [31, 45])->count(),
            '46-60' => Patient::whereBetween('age', [46, 60])->count(),
            '61+' => Patient::where('age', '>=', 61)->count()
        ];

        // Distribución por género
        $genderDistribution = Patient::select('gender', DB::raw('count(*) as count'))
            ->groupBy('gender')
            ->get()
            ->pluck('count', 'gender');

        // Niveles de educación
        $educationLevels = Patient::select('education', DB::raw('count(*) as count'))
            ->whereNotNull('education')
            ->groupBy('education')
            ->get()
            ->mapWithKeys(function($item) {
                return [$item->education => $item->count];
            });

        // Factores de riesgo
        $riskFactors = Patient::select('risk_factors')
            ->whereNotNull('risk_factors')
            ->get()
            ->flatMap(function($patient) {
                if (is_array($patient->risk_factors)) {
                    return $patient->risk_factors;
                }
                // Si es string, intentar decodificar JSON
                try {
                    return json_decode($patient->risk_factors, true) ?? [$patient->risk_factors];
                } catch (\Exception $e) {
                    return [$patient->risk_factors];
                }
            })
            ->filter()
            ->countBy()
            ->sortDesc()
            ->take(10);

        // Clasificaciones
        $classifications = Patient::select('classification', DB::raw('count(*) as count'))
            ->whereNotNull('classification')
            ->groupBy('classification')
            ->get()
            ->pluck('count', 'classification');

        return [
            'age_groups' => $ageGroups,
            'gender_distribution' => $genderDistribution,
            'education_levels' => $educationLevels,
            'risk_factors' => $riskFactors,
            'classifications' => $classifications
        ];
    }

    private function getRecentActivity()
    {
        return [
            'recent_patients' => Patient::with([
                    'house.street.community.healthCenter'
                ])
                ->latest()
                ->take(5)
                ->get(),
                
            'recent_pregnancies' => Pregnancy::with([
                    'patient.house.street.community'
                ])
                ->where('active', true)
                ->latest()
                ->take(5)
                ->get(),
                
            'recent_child_controls' => ChildHealth::with([
                    'patient.house.street.community'
                ])
                ->latest()
                ->take(5)
                ->get(),
                
            'recent_home_visits' => HomeVisit::with([
                    'patient.house.street.community'
                ])
                ->latest()
                ->take(5)
                ->get()
        ];
    }

    // ==================== MÉTODOS PARA DATOS VACÍOS ====================

    private function getEmptyStats()
    {
        return [
            'total_patients' => 0,
            'today_consultations' => 0,
            'pending_consultations' => 0,
            'active_pregnancies' => 0,
            'child_controls' => 0,
            'home_visits' => 0,
            'patients_with_disability' => 0,
            'high_risk_patients' => 0,
        ];
    }

    private function getEmptyLocationStats()
    {
        return [
            'states' => 0,
            'municipalities' => 0,
            'health_centers' => 0,
            'communities' => 0,
            'streets' => 0,
            'houses' => 0,
            'top_communities' => collect(),
            'top_health_centers' => collect()
        ];
    }

    private function getEmptyDemographicStats()
    {
        return [
            'age_groups' => [
                '0-17' => 0,
                '18-30' => 0,
                '31-45' => 0,
                '46-60' => 0,
                '61+' => 0
            ],
            'gender_distribution' => collect(['M' => 0, 'F' => 0]),
            'education_levels' => collect(),
            'risk_factors' => collect(),
            'classifications' => collect()
        ];
    }

    private function getEmptyRecentActivity()
    {
        return [
            'recent_patients' => collect(),
            'recent_pregnancies' => collect(),
            'recent_child_controls' => collect(),
            'recent_home_visits' => collect()
        ];
    }

    // ==================== MÉTODOS ADICIONALES PARA REPORTES ====================

    /**
     * Estadísticas detalladas para reportes
     */
    public function getDetailedStats(Request $request)
    {
        try {
            $period = $request->get('period', 'month');

            $stats = [
                'patient_registrations' => $this->getPatientRegistrationsByPeriod($period),
                'consultation_metrics' => $this->getConsultationMetrics(),
                'module_activity' => $this->getModuleActivity(),
                'geographic_distribution' => $this->getGeographicDistribution(),
                'period' => $period
            ];

            return response()->json([
                'success' => true,
                'data' => $stats
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al generar estadísticas',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    private function getPatientRegistrationsByPeriod($period)
    {
        $query = Patient::query();

        switch ($period) {
            case 'week':
                $query->where('created_at', '>=', now()->subWeek());
                break;
            case 'month':
                $query->where('created_at', '>=', now()->subMonth());
                break;
            case 'quarter':
                $query->where('created_at', '>=', now()->subQuarter());
                break;
            case 'year':
                $query->where('created_at', '>=', now()->subYear());
                break;
            default:
                $query->where('created_at', '>=', now()->subMonth());
        }

        return $query->count();
    }

    private function getConsultationMetrics()
    {
        return [
            'total_scheduled' => Patient::whereDate('next_appointment', '>=', today())->count(),
            'completed_today' => Patient::whereDate('next_appointment', today())->count(),
            'overdue' => Patient::whereDate('next_appointment', '<', today())->count(),
            'average_daily' => $this->getAverageDailyConsultations(),
            'completion_rate' => $this->getConsultationCompletionRate()
        ];
    }

    private function getAverageDailyConsultations()
    {
        $lastMonthConsultations = Patient::whereDate('created_at', '>=', now()->subMonth())->count();
        return round($lastMonthConsultations / 30, 1);
    }

    private function getConsultationCompletionRate()
    {
        $totalScheduled = Patient::whereDate('next_appointment', '>=', today())->count();
        $completedToday = Patient::whereDate('next_appointment', today())->count();
        
        if ($totalScheduled > 0) {
            return round(($completedToday / $totalScheduled) * 100, 1);
        }
        
        return 0;
    }

    private function getModuleActivity()
    {
        return [
            'pregnancies' => [
                'active' => Pregnancy::where('active', true)->count(),
                'new_this_month' => Pregnancy::where('created_at', '>=', now()->subMonth())->count(),
                'with_risk_factors' => Pregnancy::whereNotNull('risk_factors')->count()
            ],
            'child_health' => [
                'total_controls' => ChildHealth::count(),
                'this_month' => ChildHealth::where('created_at', '>=', now()->subMonth())->count(),
                'optimal_nutrition' => ChildHealth::where('nutritional_status', 'OPTIMO')->count()
            ],
            'home_visits' => [
                'total' => HomeVisit::count(),
                'this_week' => HomeVisit::where('visit_date', '>=', now()->subWeek())->count(),
                'follow_up_required' => HomeVisit::where('follow_up_required', true)->count()
            ]
        ];
    }

    private function getGeographicDistribution()
    {
        return [
            'by_state' => State::withCount(['patients'])
                ->get()
                ->pluck('patients_count', 'name')
                ->sortDesc()
                ->take(10),
                
            'by_municipality' => Municipality::withCount(['patients'])
                ->get()
                ->pluck('patients_count', 'name')
                ->sortDesc()
                ->take(10),
                
            'by_health_center' => HealthCenter::withCount(['patients'])
                ->get()
                ->pluck('patients_count', 'name')
                ->sortDesc()
                ->take(10),
                
            'by_community' => Community::withCount(['patients'])
                ->get()
                ->pluck('patients_count', 'name')
                ->sortDesc()
                ->take(10)
        ];
    }

    /**
     * Obtener tendencias mensuales
     */
    public function getMonthlyTrends(Request $request)
    {
        try {
            $months = $request->get('months', 6);
            
            $patientTrends = Patient::select(
                DB::raw('YEAR(created_at) as year'),
                DB::raw('MONTH(created_at) as month'),
                DB::raw('COUNT(*) as count')
            )
            ->where('created_at', '>=', now()->subMonths($months))
            ->groupBy('year', 'month')
            ->orderBy('year', 'asc')
            ->orderBy('month', 'asc')
            ->get()
            ->map(function($item) {
                return [
                    'month' => Carbon::create($item->year, $item->month)->format('M Y'),
                    'count' => $item->count
                ];
            });

            $consultationTrends = Patient::select(
                DB::raw('YEAR(next_appointment) as year'),
                DB::raw('MONTH(next_appointment) as month'),
                DB::raw('COUNT(*) as count')
            )
            ->where('next_appointment', '>=', now()->subMonths($months))
            ->groupBy('year', 'month')
            ->orderBy('year', 'asc')
            ->orderBy('month', 'asc')
            ->get()
            ->map(function($item) {
                return [
                    'month' => Carbon::create($item->year, $item->month)->format('M Y'),
                    'count' => $item->count
                ];
            });

            return response()->json([
                'success' => true,
                'data' => [
                    'patient_registrations' => $patientTrends,
                    'consultations' => $consultationTrends,
                    'months_analyzed' => $months
                ]
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al generar tendencias',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Alertas y recordatorios
     */
    public function getAlerts()
    {
        try {
            $alerts = [
                'overdue_consultations' => Patient::whereDate('next_appointment', '<', today())->count(),
                'pending_pregnancies_controls' => Pregnancy::where('active', true)
                    ->where('next_control_date', '<', now()->addDays(7))
                    ->count(),
                'pending_child_controls' => ChildHealth::whereDate('next_control_date', '<', now()->addDays(7))->count(),
                'high_risk_patients_no_visit' => Patient::where(function($query) {
                    $query->where('risk_factors', 'LIKE', '%ALTO%')
                          ->orWhere('classification', 'CRONICO');
                })->whereDoesntHave('homeVisits', function($q) {
                    $q->where('visit_date', '>=', now()->subMonth());
                })->count()
            ];

            return response()->json([
                'success' => true,
                'data' => $alerts
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al cargar alertas',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Exportar datos del dashboard
     */
    public function exportDashboardData(Request $request)
    {
        try {
            $type = $request->get('type', 'summary');
            
            $data = [];
            
            switch ($type) {
                case 'summary':
                    $data = [
                        'main_stats' => $this->getMainStats(),
                        'location_stats' => $this->getLocationStats(),
                        'demographic_stats' => $this->getDemographicStats(),
                        'exported_at' => now()->toDateTimeString()
                    ];
                    break;
                    
                case 'detailed':
                    $data = [
                        'main_stats' => $this->getMainStats(),
                        'location_stats' => $this->getLocationStats(),
                        'demographic_stats' => $this->getDemographicStats(),
                        'recent_activity' => $this->getRecentActivity(),
                        'consultation_metrics' => $this->getConsultationMetrics(),
                        'module_activity' => $this->getModuleActivity(),
                        'exported_at' => now()->toDateTimeString()
                    ];
                    break;
            }

            return response()->json([
                'success' => true,
                'data' => $data,
                'type' => $type
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al exportar datos',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}