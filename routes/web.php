<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PatientController;
use App\Http\Controllers\LocationController;
use App\Http\Controllers\PregnancyController;
use App\Http\Controllers\ChildHealthController;
use App\Http\Controllers\HomeVisitController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// ==================== RUTAS PÚBLICAS ====================
Route::get('/', [DashboardController::class, 'welcome'])->name('welcome');

// ==================== AUTENTICACIÓN ====================
Auth::routes();

// ==================== RUTAS PROTEGIDAS ====================
Route::middleware(['auth'])->group(function () {
    
    // ==================== DASHBOARD PRINCIPAL ====================
    Route::get('/home', [DashboardController::class, 'dashboard'])->name('home');
    Route::get('/dashboard', [DashboardController::class, 'dashboard'])->name('dashboard');
    Route::get('/dashboard/data', [DashboardController::class, 'getDashboardData'])->name('dashboard.data');
    Route::get('/dashboard/stats', [DashboardController::class, 'getDetailedStats'])->name('dashboard.stats');

    // ==================== GESTIÓN DE PACIENTES ====================
    Route::prefix('patients')->group(function () {
        Route::get('/', [PatientController::class, 'index'])->name('patients.index');
        Route::get('/create', [PatientController::class, 'create'])->name('patients.create');
        Route::post('/', [PatientController::class, 'store'])->name('patients.store');
        Route::get('/{patient}', [PatientController::class, 'show'])->name('patients.show');
        Route::get('/{patient}/edit', [PatientController::class, 'edit'])->name('patients.edit');
        Route::put('/{patient}', [PatientController::class, 'update'])->name('patients.update');
        Route::delete('/{patient}', [PatientController::class, 'destroy'])->name('patients.destroy');
        Route::get('/export/excel', [PatientController::class, 'export'])->name('patients.export');
        
        // Rutas AJAX para pacientes
        Route::get('/{community}/streets', [PatientController::class, 'getStreets'])->name('patients.community.streets');
        Route::get('/{street}/houses', [PatientController::class, 'getHouses'])->name('patients.street.houses');
        Route::get('/{house}/address', [PatientController::class, 'getHouseAddress'])->name('patients.house.address');
        Route::get('/pregnant/list', [PatientController::class, 'getPregnantPatients'])->name('patients.pregnant.list');
        Route::get('/children/list', [PatientController::class, 'getChildPatients'])->name('patients.children.list');
    });

    // ==================== SISTEMA DE UBICACIONES (UNIFICADO) ====================
    Route::prefix('locations')->group(function () {
        // Dashboard de ubicaciones
        Route::get('/', [LocationController::class, 'dashboard'])->name('locations.dashboard');
        
        // ==================== ESTADOS ====================
        Route::prefix('states')->group(function () {
            Route::get('/', [LocationController::class, 'statesIndex'])->name('locations.states.index');
            Route::get('/create', [LocationController::class, 'statesCreate'])->name('locations.states.create');
            Route::post('/', [LocationController::class, 'statesStore'])->name('locations.states.store');
            Route::get('/{state}/edit', [LocationController::class, 'statesEdit'])->name('locations.states.edit');
            Route::put('/{state}', [LocationController::class, 'statesUpdate'])->name('locations.states.update');
            Route::delete('/{state}', [LocationController::class, 'statesDestroy'])->name('locations.states.destroy');
        });
        
        // ==================== MUNICIPIOS ====================
        Route::prefix('municipalities')->group(function () {
            Route::get('/', [LocationController::class, 'municipalitiesIndex'])->name('locations.municipalities.index');
            Route::get('/create', [LocationController::class, 'municipalitiesCreate'])->name('locations.municipalities.create');
            Route::post('/', [LocationController::class, 'municipalitiesStore'])->name('locations.municipalities.store');
            Route::get('/{municipality}/edit', [LocationController::class, 'municipalitiesEdit'])->name('locations.municipalities.edit');
            Route::put('/{municipality}', [LocationController::class, 'municipalitiesUpdate'])->name('locations.municipalities.update');
            Route::delete('/{municipality}', [LocationController::class, 'municipalitiesDestroy'])->name('locations.municipalities.destroy');
        });
        
        // ==================== CENTROS DE SALUD ====================
        Route::prefix('health-centers')->group(function () {
            Route::get('/', [LocationController::class, 'healthCentersIndex'])->name('locations.health-centers.index');
            Route::get('/create', [LocationController::class, 'healthCentersCreate'])->name('locations.health-centers.create');
            Route::post('/', [LocationController::class, 'healthCentersStore'])->name('locations.health-centers.store');
            Route::get('/{healthCenter}', [LocationController::class, 'healthCentersShow'])->name('locations.health-centers.show');
            Route::get('/{healthCenter}/edit', [LocationController::class, 'healthCentersEdit'])->name('locations.health-centers.edit');
            Route::put('/{healthCenter}', [LocationController::class, 'healthCentersUpdate'])->name('locations.health-centers.update');
            Route::delete('/{healthCenter}', [LocationController::class, 'healthCentersDestroy'])->name('locations.health-centers.destroy');
        });
        
        // ==================== COMUNIDADES ====================
        Route::prefix('communities')->group(function () {
            Route::get('/', [LocationController::class, 'communitiesIndex'])->name('locations.communities.index');
            Route::get('/create', [LocationController::class, 'communitiesCreate'])->name('locations.communities.create');
            Route::post('/', [LocationController::class, 'communitiesStore'])->name('locations.communities.store');
            Route::get('/{community}', [LocationController::class, 'communitiesShow'])->name('locations.communities.show');
            Route::get('/{community}/edit', [LocationController::class, 'communitiesEdit'])->name('locations.communities.edit');
            Route::put('/{community}', [LocationController::class, 'communitiesUpdate'])->name('locations.communities.update');
            Route::delete('/{community}', [LocationController::class, 'communitiesDestroy'])->name('locations.communities.destroy');
        });
        
        // ==================== CALLES ====================
        Route::prefix('streets')->group(function () {
            Route::get('/', [LocationController::class, 'streetsIndex'])->name('locations.streets.index');
            Route::get('/create', [LocationController::class, 'streetsCreate'])->name('locations.streets.create');
            Route::post('/', [LocationController::class, 'streetsStore'])->name('locations.streets.store');
            Route::get('/{street}', [LocationController::class, 'streetsShow'])->name('locations.streets.show');
            Route::get('/{street}/edit', [LocationController::class, 'streetsEdit'])->name('locations.streets.edit');
            Route::put('/{street}', [LocationController::class, 'streetsUpdate'])->name('locations.streets.update');
            Route::delete('/{street}', [LocationController::class, 'streetsDestroy'])->name('locations.streets.destroy');
        });
        
        // ==================== CASAS ====================
        Route::prefix('houses')->group(function () {
            Route::get('/', [LocationController::class, 'housesIndex'])->name('locations.houses.index');
            Route::get('/create', [LocationController::class, 'housesCreate'])->name('locations.houses.create');
            Route::post('/', [LocationController::class, 'housesStore'])->name('locations.houses.store');
            Route::get('/{house}', [LocationController::class, 'housesShow'])->name('locations.houses.show');
            Route::get('/{house}/edit', [LocationController::class, 'housesEdit'])->name('locations.houses.edit');
            Route::put('/{house}', [LocationController::class, 'housesUpdate'])->name('locations.houses.update');
            Route::delete('/{house}', [LocationController::class, 'housesDestroy'])->name('locations.houses.destroy');
            Route::get('/bulk-create/{street}', [LocationController::class, 'housesBulkCreate'])->name('locations.houses.bulk-create');
            Route::post('/bulk-store/{street}', [LocationController::class, 'housesBulkStore'])->name('locations.houses.bulk-store');
        });
    });

    // ==================== RUTAS AJAX PARA UBICACIONES ====================
    Route::prefix('ajax')->group(function () {
        Route::get('/municipalities/{stateId}', [LocationController::class, 'getMunicipalitiesByState'])->name('ajax.municipalities');
        Route::get('/health-centers/{municipalityId}', [LocationController::class, 'getHealthCentersByMunicipality'])->name('ajax.health-centers');
        Route::get('/communities/{healthCenterId}', [LocationController::class, 'getCommunitiesByHealthCenter'])->name('ajax.communities');
        Route::get('/streets/{communityId}', [LocationController::class, 'getStreetsByCommunity'])->name('ajax.streets');
        Route::get('/houses/{streetId}', [LocationController::class, 'getHousesByStreet'])->name('ajax.houses');
        Route::get('/house-details/{houseId}', [LocationController::class, 'getHouseDetails'])->name('ajax.house-details');
    });

    // ==================== MÓDULOS ESPECIALIZADOS ====================
    
    // Gestantes
    Route::prefix('pregnancies')->group(function () {
        Route::get('/', [PregnancyController::class, 'index'])->name('pregnancies.index');
        Route::get('/create', [PregnancyController::class, 'create'])->name('pregnancies.create');
        Route::post('/', [PregnancyController::class, 'store'])->name('pregnancies.store');
        Route::get('/{pregnancy}', [PregnancyController::class, 'show'])->name('pregnancies.show');
        Route::get('/{pregnancy}/edit', [PregnancyController::class, 'edit'])->name('pregnancies.edit');
        Route::put('/{pregnancy}', [PregnancyController::class, 'update'])->name('pregnancies.update');
        Route::delete('/{pregnancy}', [PregnancyController::class, 'destroy'])->name('pregnancies.destroy');
    });

    // Niño Sano
    Route::prefix('child-health')->group(function () {
        Route::get('/', [ChildHealthController::class, 'index'])->name('child-health.index');
        Route::get('/create', [ChildHealthController::class, 'create'])->name('child-health.create');
        Route::post('/', [ChildHealthController::class, 'store'])->name('child-health.store');
        Route::get('/{childHealth}', [ChildHealthController::class, 'show'])->name('child-health.show');
        Route::get('/{childHealth}/edit', [ChildHealthController::class, 'edit'])->name('child-health.edit');
        Route::put('/{childHealth}', [ChildHealthController::class, 'update'])->name('child-health.update');
        Route::delete('/{childHealth}', [ChildHealthController::class, 'destroy'])->name('child-health.destroy');
    });

    // Visitas Domiciliarias
    Route::prefix('home-visits')->group(function () {
        Route::get('/', [HomeVisitController::class, 'index'])->name('home-visits.index');
        Route::get('/create', [HomeVisitController::class, 'create'])->name('home-visits.create');
        Route::post('/', [HomeVisitController::class, 'store'])->name('home-visits.store');
        Route::get('/{homeVisit}', [HomeVisitController::class, 'show'])->name('home-visits.show');
        Route::get('/{homeVisit}/edit', [HomeVisitController::class, 'edit'])->name('home-visits.edit');
        Route::put('/{homeVisit}', [HomeVisitController::class, 'update'])->name('home-visits.update');
        Route::delete('/{homeVisit}', [HomeVisitController::class, 'destroy'])->name('home-visits.destroy');
    });
});

// ==================== RUTAS DE FALLBACK ====================
Route::fallback(function () {
    return response()->view('errors.404', [], 404);
});