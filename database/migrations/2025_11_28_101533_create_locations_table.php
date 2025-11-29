<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        // Estados
        Schema::create('states', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('code')->unique();
            $table->timestamps();
        });

        // Municipios
        Schema::create('municipalities', function (Blueprint $table) {
            $table->id();
            $table->foreignId('state_id')->constrained();
            $table->string('name');
            $table->string('code')->unique();
            $table->timestamps();
        });

        // CDI
        Schema::create('health_centers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('municipality_id')->constrained();
            $table->string('name');
            $table->enum('type', ['CDI', 'CPT_I', 'CPT_II', 'CPT_III', 'CPT_IV', 'AMBULATORIO_I', 'AMBULATORIO_II', 'AMBULATORIO_III', 'AMBULATORIO_IV']);
            $table->string('address');
            $table->string('phone')->nullable();
            $table->timestamps();
        });

        // Comunidades
       Schema::create('communities', function (Blueprint $table) {
    $table->id();
    $table->foreignId('health_center_id')->constrained(); // Esto hace que sea NOT NULL por defecto
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

      

        // Gestantes
        Schema::create('pregnancies', function (Blueprint $table) {
            $table->id();
            $table->foreignId('patient_id')->constrained();
            $table->date('last_menstrual_period');
            $table->date('estimated_delivery');
            $table->integer('prenatal_controls')->default(0);
            $table->text('risk_factors')->nullable();
            $table->timestamps();
        });

        // Niños sanos
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

        // Farmacia
        Schema::create('pharmacy_inventories', function (Blueprint $table) {
            $table->id();
            $table->string('medicine_name');
            $table->string('generic_name');
            $table->integer('quantity');
            $table->date('expiration_date');
            $table->decimal('price', 8, 2)->nullable();
            $table->timestamps();
        });

        // Insumos
        Schema::create('medical_supplies', function (Blueprint $table) {
            $table->id();
            $table->string('supply_name');
            $table->integer('quantity');
            $table->integer('min_stock');
            $table->timestamps();
        });

        // Visitas domiciliarias
        Schema::create('home_visits', function (Blueprint $table) {
            $table->id();
            $table->foreignId('patient_id')->constrained();
            $table->date('visit_date');
            $table->text('findings');
            $table->text('recommendations');
            $table->string('visited_by');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('home_visits');
        Schema::dropIfExists('medical_supplies');
        Schema::dropIfExists('pharmacy_inventories');
        Schema::dropIfExists('child_healths');
        Schema::dropIfExists('pregnancies');
        Schema::dropIfExists('patients');
        Schema::dropIfExists('houses');
        Schema::dropIfExists('streets');
        Schema::dropIfExists('communities');
        Schema::dropIfExists('health_centers');
        Schema::dropIfExists('municipalities');
        Schema::dropIfExists('states');
    }
};