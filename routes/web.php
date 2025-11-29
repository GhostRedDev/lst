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
    Route::prefix('locations')->name('locations.')->group(function () {
        // Dashboard de ubicaciones
        Route::get('/', [LocationController::class, 'dashboard'])->name('dashboard');
        
        // ==================== ESTADOS ====================
        Route::prefix('states')->name('states.')->group(function () {
            Route::get('/', [LocationController::class, 'statesIndex'])->name('index');
            Route::get('/create', [LocationController::class, 'statesCreate'])->name('create');
            Route::post('/', [LocationController::class, 'statesStore'])->name('store');
            Route::get('/{state}/edit', [LocationController::class, 'statesEdit'])->name('edit');
            Route::put('/{state}', [LocationController::class, 'statesUpdate'])->name('update');
            Route::delete('/{state}', [LocationController::class, 'statesDestroy'])->name('destroy');
        });
        
        // ==================== MUNICIPIOS ====================
        Route::prefix('municipalities')->name('municipalities.')->group(function () {
            Route::get('/', [LocationController::class, 'municipalitiesIndex'])->name('index');
            Route::get('/create', [LocationController::class, 'municipalitiesCreate'])->name('create');
            Route::post('/', [LocationController::class, 'municipalitiesStore'])->name('store');
            Route::get('/{municipality}/edit', [LocationController::class, 'municipalitiesEdit'])->name('edit');
            Route::put('/{municipality}', [LocationController::class, 'municipalitiesUpdate'])->name('update');
            Route::delete('/{municipality}', [LocationController::class, 'municipalitiesDestroy'])->name('destroy');
        });
        
        // ==================== CENTROS DE SALUD ====================
        Route::prefix('health-centers')->name('health-centers.')->group(function () {
            Route::get('/', [LocationController::class, 'healthCentersIndex'])->name('index');
            Route::get('/create', [LocationController::class, 'healthCentersCreate'])->name('create');
            Route::post('/', [LocationController::class, 'healthCentersStore'])->name('store');
            Route::get('/{healthCenter}', [LocationController::class, 'healthCentersShow'])->name('show');
            Route::get('/{healthCenter}/edit', [LocationController::class, 'healthCentersEdit'])->name('edit');
            Route::put('/{healthCenter}', [LocationController::class, 'healthCentersUpdate'])->name('update');
            Route::delete('/{healthCenter}', [LocationController::class, 'healthCentersDestroy'])->name('destroy');
        });
        
        // ==================== COMUNIDADES ====================
        Route::prefix('communities')->name('communities.')->group(function () {
            Route::get('/', [LocationController::class, 'communitiesIndex'])->name('index');
            Route::get('/create', [LocationController::class, 'communitiesCreate'])->name('create');
            Route::post('/', [LocationController::class, 'communitiesStore'])->name('store');
            Route::get('/{community}', [LocationController::class, 'communitiesShow'])->name('show');
            Route::get('/{community}/edit', [LocationController::class, 'communitiesEdit'])->name('edit');
            Route::put('/{community}', [LocationController::class, 'communitiesUpdate'])->name('update');
            Route::delete('/{community}', [LocationController::class, 'communitiesDestroy'])->name('destroy');
        });
        
        // ==================== CALLES ====================
        Route::prefix('streets')->name('streets.')->group(function () {
            Route::get('/', [LocationController::class, 'streetsIndex'])->name('index');
            Route::get('/create', [LocationController::class, 'streetsCreate'])->name('create');
            // 🔥 RUTA ESPECÍFICA PARA CREAR CALLE DESDE UNA COMUNIDAD
            Route::get('/create/{community}', [LocationController::class, 'streetsCreate'])->name('create.with-community');
            Route::post('/', [LocationController::class, 'streetsStore'])->name('store');
            Route::get('/{street}', [LocationController::class, 'streetsShow'])->name('show');
            Route::get('/{street}/edit', [LocationController::class, 'streetsEdit'])->name('edit');
            Route::put('/{street}', [LocationController::class, 'streetsUpdate'])->name('update');
            Route::delete('/{street}', [LocationController::class, 'streetsDestroy'])->name('destroy');
        });
        
        // ==================== CASAS ====================
        Route::prefix('houses')->name('houses.')->group(function () {
            Route::get('/', [LocationController::class, 'housesIndex'])->name('index');
            Route::get('/create', [LocationController::class, 'housesCreate'])->name('create');
            // 🔥 RUTA ESPECÍFICA PARA CREAR CASA DESDE UNA CALLE
            Route::get('/create/{street}', [LocationController::class, 'housesCreate'])->name('create.with-street');
            Route::post('/', [LocationController::class, 'housesStore'])->name('store');
            Route::get('/{house}', [LocationController::class, 'housesShow'])->name('show');
            Route::get('/{house}/edit', [LocationController::class, 'housesEdit'])->name('edit');
            Route::put('/{house}', [LocationController::class, 'housesUpdate'])->name('update');
            Route::delete('/{house}', [LocationController::class, 'housesDestroy'])->name('destroy');
            Route::get('/bulk-create/{street}', [LocationController::class, 'housesBulkCreate'])->name('bulk-create');
            Route::post('/bulk-store/{street}', [LocationController::class, 'housesBulkStore'])->name('bulk-store');
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
});// En tu routes/web.php - ya deberían estar estas rutas:
Route::prefix('houses')->name('houses.')->group(function () {
    Route::get('/', [LocationController::class, 'housesIndex'])->name('index');
    Route::get('/create', [LocationController::class, 'housesCreate'])->name('create');
    Route::get('/create/{street}', [LocationController::class, 'housesCreate'])->name('create.with-street');
    Route::post('/', [LocationController::class, 'housesStore'])->name('store');
    Route::get('/{house}', [LocationController::class, 'housesShow'])->name('show');
    Route::get('/{house}/edit', [LocationController::class, 'housesEdit'])->name('edit');
    Route::put('/{house}', [LocationController::class, 'housesUpdate'])->name('update');
    Route::delete('/{house}', [LocationController::class, 'housesDestroy'])->name('destroy');
    Route::get('/bulk-create/{street}', [LocationController::class, 'housesBulkCreate'])->name('bulk-create');
    Route::post('/bulk-store/{street}', [LocationController::class, 'housesBulkStore'])->name('bulk-store');
});// Rutas AJAX para ubicaciones
Route::prefix('ajax')->group(function () {
    Route::get('/municipalities/{stateId}', [LocationController::class, 'getMunicipalitiesByState'])->name('ajax.municipalities');
    Route::get('/health-centers/{municipalityId}', [LocationController::class, 'getHealthCentersByMunicipality'])->name('ajax.health-centers');
    Route::get('/communities/{healthCenterId}', [LocationController::class, 'getCommunitiesByHealthCenter'])->name('ajax.communities');
    Route::get('/streets/{communityId}', [LocationController::class, 'getStreetsByCommunity'])->name('ajax.streets');
    Route::get('/houses/{streetId}', [LocationController::class, 'getHousesByStreet'])->name('ajax.houses');
    Route::get('/house-details/{houseId}', [LocationController::class, 'getHouseDetails'])->name('ajax.house-details');
});