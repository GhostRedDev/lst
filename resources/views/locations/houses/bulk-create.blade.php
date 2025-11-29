@extends('layouts.app')

@section('title', 'Creación Masiva de Casas - SPRL')

@section('content')
<div class="container-fluid">
    <!-- Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h1 class="h3 mb-0">
                        <i class="fas fa-layer-group"></i> Creación Masiva de Casas
                    </h1>
                    <p class="text-muted mb-0">
                        Calle: {{ $street->name }} - {{ $street->community->name }}
                    </p>
                </div>
                <a href="{{ route('locations.streets.show', $street) }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Volver
                </a>
            </div>
        </div>
    </div>

    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card card-app">
                <div class="card-header-sprl">
                    <h5 class="mb-0">Configuración de Casas</h5>
                </div>
                <div class="card-body-sprl">
                    <form action="{{ route('locations.houses.bulk-store', $street) }}" method="POST">
                        @csrf
                        
                        <div class="alert alert-info">
                            <i class="fas fa-info-circle"></i>
                            Esta herramienta le permite crear múltiples casas automáticamente con números consecutivos.
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="start_number" class="form-label">Número Inicial *</label>
                                    <input type="number" class="form-control @error('start_number') is-invalid @enderror" 
                                           id="start_number" name="start_number" value="{{ old('start_number', 1) }}" 
                                           min="1" required>
                                    @error('start_number')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="end_number" class="form-label">Número Final *</label>
                                    <input type="number" class="form-control @error('end_number') is-invalid @enderror" 
                                           id="end_number" name="end_number" value="{{ old('end_number', 10) }}" 
                                           min="1" required>
                                    @error('end_number')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="prefix" class="form-label">Prefijo (Opcional)</label>
                                    <input type="text" class="form-control @error('prefix') is-invalid @enderror" 
                                           id="prefix" name="prefix" value="{{ old('prefix') }}" 
                                           placeholder="Ej: A, B, C" maxlength="10">
                                    @error('prefix')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <small class="form-text text-muted">Texto que va antes del número (Ej: A1, A2...)</small>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="suffix" class="form-label">Sufijo (Opcional)</label>
                                    <input type="text" class="form-control @error('suffix') is-invalid @enderror" 
                                           id="suffix" name="suffix" value="{{ old('suffix') }}" 
                                           placeholder="Ej: -A, -B" maxlength="10">
                                    @error('suffix')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <small class="form-text text-muted">Texto que va después del número (Ej: 1A, 2A...)</small>
                                </div>
                            </div>
                        </div>

                        <!-- Vista previa -->
                        <div class="mb-3">
                            <label class="form-label">Vista Previa:</label>
                            <div id="preview" class="border rounded p-3 bg-light">
                                <small class="text-muted">Los números se verán así:</small>
                                <div id="preview-numbers" class="mt-2"></div>
                            </div>
                        </div>

                        <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                            <a href="{{ route('locations.streets.show', $street) }}" class="btn btn-secondary me-md-2">
                                <i class="fas fa-times"></i> Cancelar
                            </a>
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

<script>
document.addEventListener('DOMContentLoaded', function() {
    const startNumber = document.getElementById('start_number');
    const endNumber = document.getElementById('end_number');
    const prefix = document.getElementById('prefix');
    const suffix = document.getElementById('suffix');
    const preview = document.getElementById('preview-numbers');

    function updatePreview() {
        const start = parseInt(startNumber.value) || 1;
        const end = parseInt(endNumber.value) || 10;
        const pre = prefix.value || '';
        const suf = suffix.value || '';

        if (start > end) {
            preview.innerHTML = '<span class="text-danger">El número inicial no puede ser mayor al final</span>';
            return;
        }

        let html = '';
        const total = Math.min(end - start + 1, 10); // Mostrar máximo 10 ejemplos
        
        for (let i = start; i < start + total && i <= end; i++) {
            const number = pre + i + suf;
            html += `<span class="badge bg-primary me-1">${number}</span>`;
        }

        if (end - start + 1 > 10) {
            html += `<span class="badge bg-secondary">... +${end - start + 1 - 10} más</span>`;
        }

        preview.innerHTML = html;
    }

    [startNumber, endNumber, prefix, suffix].forEach(element => {
        element.addEventListener('input', updatePreview);
    });

    updatePreview();
});
</script>
@endsection