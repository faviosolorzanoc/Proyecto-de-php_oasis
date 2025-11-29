@extends('layouts.admin')

@section('title', 'Crear Horarios')

@section('content')
<div class="mb-4">
    <a href="{{ route('admin.horarios.index', $espacio) }}" class="btn btn-secondary">
        ← Volver a Horarios
    </a>
</div>

<div class="card fade-in">
    <div class="card-header">
        <h3 class="mb-0">➕ Crear Horarios para: {{ $espacio->nombre }}</h3>
    </div>
    <div class="card-body">
        @if($errors->any())
            <div class="alert alert-danger">
                <strong>¡Oops! Hay algunos errores:</strong>
                <ul class="mb-0 mt-2">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="alert alert-info">
            <strong>💡 Cómo funciona:</strong> Define un rango de fechas y un horario. El sistema creará automáticamente ese horario para todos los días en el rango seleccionado.
        </div>

        <form action="{{ route('admin.horarios.store', $espacio) }}" method="POST">
            @csrf
            
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="fecha_inicio" class="form-label">Fecha Inicio *</label>
                    <input type="date" 
                           class="form-control" 
                           id="fecha_inicio" 
                           name="fecha_inicio" 
                           value="{{ old('fecha_inicio', date('Y-m-d')) }}" 
                           min="{{ date('Y-m-d') }}"
                           required>
                </div>

                <div class="col-md-6 mb-3">
                    <label for="fecha_fin" class="form-label">Fecha Fin *</label>
                    <input type="date" 
                           class="form-control" 
                           id="fecha_fin" 
                           name="fecha_fin" 
                           value="{{ old('fecha_fin') }}" 
                           min="{{ date('Y-m-d') }}"
                           required>
                </div>

                <div class="col-md-4 mb-3">
                    <label for="hora_inicio" class="form-label">Hora Inicio *</label>
                    <input type="time" 
                           class="form-control" 
                           id="hora_inicio" 
                           name="hora_inicio" 
                           value="{{ old('hora_inicio', '09:00') }}" 
                           required>
                </div>

                <div class="col-md-4 mb-3">
                    <label for="hora_fin" class="form-label">Hora Fin *</label>
                    <input type="time" 
                           class="form-control" 
                           id="hora_fin" 
                           name="hora_fin" 
                           value="{{ old('hora_fin', '18:00') }}" 
                           required>
                </div>

                <div class="col-md-4 mb-3">
                    <label for="intervalo" class="form-label">Duración (horas) *</label>
                    <select class="form-select" id="intervalo" name="intervalo" required>
                        <option value="1">1 hora</option>
                        <option value="2" selected>2 horas</option>
                        <option value="3">3 horas</option>
                        <option value="4">4 horas</option>
                        <option value="5">5 horas</option>
                        <option value="6">6 horas</option>
                        <option value="7">7 horas</option>
                        <option value="8">8 horas</option>
                    </select>
                    <small class="text-muted">Duración de cada bloque de horario</small>
                </div>
            </div>

            <div class="alert alert-warning">
                <strong>⚠️ Ejemplo:</strong> Si seleccionas del 19/11/2024 al 23/11/2024 con horario 09:00-18:00, se crearán 5 horarios (uno por cada día) en ese rango de horas.
            </div>

            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-primary">
                    ✓ Crear Horarios
                </button>
                <a href="{{ route('admin.horarios.index', $espacio) }}" class="btn btn-secondary">
                    Cancelar
                </a>
            </div>
        </form>
    </div>
</div>

<div class="card mt-4">
    <div class="card-body">
        <h5 style="color: var(--color-primary);">📚 Guía Rápida:</h5>
        <ol class="mb-0">
            <li><strong>Fecha Inicio y Fin:</strong> Define el rango de días para crear horarios</li>
            <li><strong>Hora Inicio y Fin:</strong> Define el bloque de tiempo disponible cada día</li>
            <li><strong>Duración:</strong> El sistema NO divide en bloques (eso ya no se usa). Solo crea un horario por día con el tiempo completo</li>
        </ol>
    </div>
</div>
@endsection