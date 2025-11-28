<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('patients', function (Blueprint $table) {
            $table->id();
            $table->string('medical_history_number')->unique();
            $table->string('last_name');
            $table->string('first_name');
            $table->text('address');
            $table->enum('dispensary_group', ['I', 'II', 'III', 'IV', 'V']);
            $table->enum('housing_type', ['CASA', 'APARTAMENTO', 'RANCHO', 'OTRO']);
            $table->enum('risk_factor', ['TABAQUISMO', 'ALCOHOLISMO', 'OBESIDAD', 'DIABETES', 'HIPERTENSION', 'OTRO']);
            $table->date('birth_date');
            $table->integer('age');
            $table->enum('gender', ['M', 'F']);
            $table->string('id_number')->unique();
            $table->string('phone')->nullable();
            $table->date('consultation_date');
            $table->date('next_consultation')->nullable();
            $table->text('diagnosis');
            $table->enum('education_level', ['NINGUNA', 'PRIMARIA', 'SECUNDARIA', 'TECNICO', 'UNIVERSITARIO', 'POSTGRADO']);
            $table->string('occupation');
            $table->string('profession')->nullable();
            $table->enum('disability', ['SI', 'NO']);
            $table->string('classification')->nullable();
            $table->text('observation')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('patients');
    }
};