@extends('layouts.app')

@section('title', 'Crear Múltiples Casas - SPRL')

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <h1 class="h3 mb-0">
                    <i class="fas fa-layer-group"></i> Crear Múltiples Casas
                </h1>
                <a href="{{ route('streets.show', $street) }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Volver
                </a>
            </div>
            <p class="text-muted">Calle: {{ $street->name }} - Comunidad: {{ $street->community->name }}</p>
        </div>
    </div>

    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card card-app">
                <div class="card-header-sprl">
                    <h5 class="mb-0">Generación Masiva de Casas</h5>
                </div>
                <div class="card-body-sprl">
                    <form action="{{ route('houses.bulk-store', $street) }}" method="POST">
                        @csrf
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="start_number" class="form-label">Número Inicial *</label>
                                    <input type="number" class="form-control" id="start_number" name="start_number" 
                                           min="1" required value="1">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="end_number" class="form-label">Número Final *</label>
                                    <input type="number" class="form-control" id="end_number" name="end_number" 
                                           min="1" required value="10">
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="prefix" class="form-label">Prefijo (Opcional)</label>
                                    <input type="text" class="form-control" id="prefix" name="prefix" 
                                           placeholder="Ej: #, Casa">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="suffix" class="form-label">Sufijo (Opcional)</label>
                                    <input type="text" class="form-control" id="suffix" name="suffix" 
                                           placeholder="Ej: A, -A">
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <div class="form-text">
                                <strong>Ejemplo:</strong> Del 1 al 5 con prefijo "Casa " y sufijo "A" generará:<br>
                                Casa 1A, Casa 2A, Casa 3A, Casa 4A, Casa 5A
                            </div>
                        </div>

                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-magic"></i> Generar Casas
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection