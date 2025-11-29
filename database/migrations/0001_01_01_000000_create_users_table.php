<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        // Tabla de roles de usuario
        Schema::create('roles', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('description')->nullable();
            $table->timestamps();
        });

        // Estados (ya existente)
        Schema::create('states', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('code')->unique();
            $table->timestamps();
        });

        // Ejes nacionales (Oriente, Occidente, Centro, etc.)
        Schema::create('national_axes', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('code')->unique();
            $table->text('description')->nullable();
            $table->timestamps();
        });

        // Núcleos nacionales
        Schema::create('national_cores', function (Blueprint $table) {
            $table->id();
            $table->foreignId('national_axis_id')->constrained();
            $table->string('name');
            $table->string('code')->unique();
            $table->text('description')->nullable();
            $table->timestamps();
        });

        // Núcleos estadales
        Schema::create('state_cores', function (Blueprint $table) {
            $table->id();
            $table->foreignId('state_id')->constrained();
            $table->foreignId('national_core_id')->constrained();
            $table->string('name');
            $table->string('code')->unique();
            $table->text('description')->nullable();
            $table->timestamps();
        });

        // Ejes estadales
        Schema::create('state_axes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('state_id')->constrained();
            $table->string('name');
            $table->string('code')->unique();
            $table->text('description')->nullable();
            $table->timestamps();
        });

        // Municipios (modificada para incluir eje estadal)
        Schema::create('municipalities', function (Blueprint $table) {
            $table->id();
            $table->foreignId('state_id')->constrained();
            $table->foreignId('state_axis_id')->constrained();
            $table->string('name');
            $table->string('code')->unique();
            $table->timestamps();
        });

        // ASIC (Áreas de Salud Integral Comunitaria)
        Schema::create('asics', function (Blueprint $table) {
            $table->id();
            $table->foreignId('municipality_id')->constrained();
            $table->string('name');
            $table->string('code')->unique();
            $table->text('description')->nullable();
            $table->timestamps();
        });

        // Tipos de centros médicos
        Schema::create('health_center_types', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // CDI, CPT_I, CPT_II, CPT_III, CPT_IV, AMBULATORIO_I, etc.
            $table->string('description')->nullable();
            $table->timestamps();
        });

        // Centros médicos (modificada)
        Schema::create('health_centers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('asic_id')->constrained();
            $table->foreignId('health_center_type_id')->constrained();
            $table->string('name');
            $table->string('address');
            $table->string('phone')->nullable();
            $table->string('email')->nullable();
            $table->boolean('has_pharmacy')->default(false);
            $table->boolean('has_supplies')->default(false);
            $table->timestamps();
        });

        // Consultorios dentro de centros médicos
        Schema::create('consulting_rooms', function (Blueprint $table) {
            $table->id();
            $table->foreignId('health_center_id')->constrained();
            $table->string('name');
            $table->string('code')->unique();
            $table->text('description')->nullable();
            $table->timestamps();
        });

        // Comunidades (modificada para incluir ASIC)
        Schema::create('communities', function (Blueprint $table) {
            $table->id();
            $table->foreignId('asic_id')->constrained();
            $table->string('name');
            $table->string('sector')->nullable();
            $table->text('description')->nullable();
            $table->decimal('latitude', 10, 8)->nullable();
            $table->decimal('longitude', 11, 8)->nullable();
            $table->timestamps();
        });

        // Calles
        Schema::create('streets', function (Blueprint $table) {
            $table->id();
            $table->foreignId('community_id')->constrained();
            $table->string('name');
            $table->text('description')->nullable();
            $table->timestamps();
        });

        // Casas
        Schema::create('houses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('street_id')->constrained();
            $table->string('house_number');
            $table->text('description')->nullable();
            $table->integer('family_count')->default(1);
            $table->timestamps();
        });

        // Pacientes (tabla que faltaba)
        Schema::create('patients', function (Blueprint $table) {
            $table->id();
            $table->foreignId('house_id')->constrained();
            $table->string('national_id')->unique();
            $table->string('first_name');
            $table->string('last_name');
            $table->date('birth_date');
            $table->enum('gender', ['M', 'F']);
            $table->string('phone')->nullable();
            $table->string('email')->nullable();
            $table->text('medical_history')->nullable();
            $table->text('allergies')->nullable();
            $table->timestamps();
        });

        // Ubicaciones de pacientes (para seguimiento)
        Schema::create('patient_locations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('patient_id')->constrained();
            $table->foreignId('asic_id')->constrained();
            $table->foreignId('health_center_id')->constrained();
            $table->foreignId('consulting_room_id')->nullable()->constrained();
            $table->text('notes')->nullable();
            $table->timestamps();
        });

        // Ubicaciones de sectores de salud
        Schema::create('health_sector_locations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('national_axis_id')->constrained();
            $table->foreignId('national_core_id')->constrained();
            $table->foreignId('state_core_id')->constrained();
            $table->foreignId('state_axis_id')->constrained();
            $table->foreignId('asic_id')->constrained();
            $table->text('description')->nullable();
            $table->timestamps();
        });

        // Usuarios con ubicaciones asignadas (modificación de la tabla users)
        Schema::table('users', function (Blueprint $table) {
            $table->foreignId('role_id')->constrained();
            $table->foreignId('asic_id')->nullable()->constrained();
            $table->foreignId('health_center_id')->nullable()->constrained();
            $table->foreignId('consulting_room_id')->nullable()->constrained();
            $table->json('location_access')->nullable(); // Permisos específicos por JSON
            $table->boolean('is_active')->default(true);
        });

        // Permisos de ubicación por rol
        Schema::create('role_location_access', function (Blueprint $table) {
            $table->id();
            $table->foreignId('role_id')->constrained();
            $table->foreignId('national_axis_id')->nullable()->constrained();
            $table->foreignId('national_core_id')->nullable()->constrained();
            $table->foreignId('state_id')->nullable()->constrained();
            $table->foreignId('state_core_id')->nullable()->constrained();
            $table->foreignId('state_axis_id')->nullable()->constrained();
            $table->foreignId('municipality_id')->nullable()->constrained();
            $table->foreignId('asic_id')->nullable()->constrained();
            $table->foreignId('health_center_id')->nullable()->constrained();
            $table->foreignId('consulting_room_id')->nullable()->constrained();
            $table->timestamps();
        });

        // Catálogo de medicamentos
        Schema::create('medicines', function (Blueprint $table) {
            $table->id();
            $table->string('commercial_name');
            $table->string('generic_name');
            $table->string('active_principle');
            $table->text('composition')->nullable();
            $table->text('indications')->nullable();
            $table->text('contraindications')->nullable();
            $table->text('side_effects')->nullable();
            $table->string('presentation');
            $table->string('concentration');
            $table->string('laboratory');
            $table->string('registration_number')->unique();
            $table->timestamps();
        });

        // Farmacias de medicamentos por centro médico
        Schema::create('medicine_pharmacies', function (Blueprint $table) {
            $table->id();
            $table->foreignId('health_center_id')->constrained();
            $table->string('name');
            $table->string('responsible_name');
            $table->string('phone')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // Inventario de medicamentos
        Schema::create('medicine_inventories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('medicine_pharmacy_id')->constrained();
            $table->foreignId('medicine_id')->constrained();
            $table->integer('quantity');
            $table->integer('min_stock');
            $table->integer('max_stock');
            $table->date('expiration_date');
            $table->string('batch_number');
            $table->decimal('unit_price', 8, 2);
            $table->decimal('sale_price', 8, 2)->nullable();
            $table->timestamps();
        });

        // Catálogo de insumos médicos
        Schema::create('medical_supplies_catalog', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('category');
            $table->text('description')->nullable();
            $table->string('unit_of_measure');
            $table->text('specifications')->nullable();
            $table->timestamps();
        });

        // Farmacias de insumos por centro médico
        Schema::create('supply_pharmacies', function (Blueprint $table) {
            $table->id();
            $table->foreignId('health_center_id')->constrained();
            $table->string('name');
            $table->string('responsible_name');
            $table->string('phone')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // Inventario de insumos
        Schema::create('supply_inventories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('supply_pharmacy_id')->constrained();
            $table->foreignId('medical_supply_id')->constrained('medical_supplies_catalog');
            $table->integer('quantity');
            $table->integer('min_stock');
            $table->integer('max_stock');
            $table->date('expiration_date')->nullable();
            $table->string('batch_number')->nullable();
            $table->decimal('unit_cost', 8, 2);
            $table->timestamps();
        });

        // Tablas existentes que mantienes
        Schema::create('pregnancies', function (Blueprint $table) {
            $table->id();
            $table->foreignId('patient_id')->constrained();
            $table->date('last_menstrual_period');
            $table->date('estimated_delivery');
            $table->integer('prenatal_controls')->default(0);
            $table->text('risk_factors')->nullable();
            $table->timestamps();
        });

        Schema::create('child_healths', function (Blueprint $table) {
            $table->id();
            $table->foreignId('patient_id')->constrained();
            $table->decimal('weight', 5, 2);
            $table->decimal('height', 5, 2);
            $table->decimal('head_circumference', 5, 2)->nullable();
            $table->text('vaccines')->nullable();
            $table->text('development_notes')->nullable();
            $table->timestamps();
        });

        Schema::create('home_visits', function (Blueprint $table) {
            $table->id();
            $table->foreignId('patient_id')->constrained();
            $table->date('visit_date');
            $table->text('findings');
            $table->text('recommendations');
            $table->string('visited_by');
            $table->timestamps();
        });

        // Tabla de estadísticas por ubicación
        Schema::create('location_statistics', function (Blueprint $table) {
            $table->id();
            $table->foreignId('asic_id')->constrained();
            $table->foreignId('health_center_id')->constrained();
            $table->foreignId('community_id')->nullable()->constrained();
            $table->foreignId('street_id')->nullable()->constrained();
            $table->string('statistic_type'); // pregnancies, child_health, visits, etc.
            $table->json('statistic_data');
            $table->date('statistic_date');
            $table->timestamps();
        });
    }

    public function down()
    {
        // Eliminar en orden inverso para evitar errores de claves foráneas
        Schema::dropIfExists('location_statistics');
        Schema::dropIfExists('supply_inventories');
        Schema::dropIfExists('supply_pharmacies');
        Schema::dropIfExists('medical_supplies_catalog');
        Schema::dropIfExists('medicine_inventories');
        Schema::dropIfExists('medicine_pharmacies');
        Schema::dropIfExists('medicines');
        Schema::dropIfExists('role_location_access');
        Schema::dropIfExists('health_sector_locations');
        Schema::dropIfExists('patient_locations');
        Schema::dropIfExists('home_visits');
        Schema::dropIfExists('child_healths');
        Schema::dropIfExists('pregnancies');
        Schema::dropIfExists('patients');
        Schema::dropIfExists('houses');
        Schema::dropIfExists('streets');
        Schema::dropIfExists('communities');
        Schema::dropIfExists('consulting_rooms');
        Schema::dropIfExists('health_centers');
        Schema::dropIfExists('health_center_types');
        Schema::dropIfExists('asics');
        Schema::dropIfExists('municipalities');
        Schema::dropIfExists('state_axes');
        Schema::dropIfExists('state_cores');
        Schema::dropIfExists('national_cores');
        Schema::dropIfExists('national_axes');
        Schema::dropIfExists('states');
        Schema::dropIfExists('roles');
        
        // Eliminar columnas agregadas a users
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['role_id']);
            $table->dropForeign(['asic_id']);
            $table->dropForeign(['health_center_id']);
            $table->dropForeign(['consulting_room_id']);
            $table->dropColumn(['role_id', 'asic_id', 'health_center_id', 'consulting_room_id', 'location_access', 'is_active']);
        });
    }
};