@extends('layouts.cliente')

@section('title', 'Espacios Disponibles')



@section('content')
<div class="container py-4">
    <div class="mb-4">
        <a href="{{ route('cliente.reservas') }}" class="btn btn-outline-secondary">
            ← Volver al formulario
        </a>
    </div>

    <div class="card shadow-sm mb-4">
        <div class="card-body" style="background-color: var(--color-light);">
            <h5 class="mb-3" style="color: var(--color-primary);">📋 Datos de tu búsqueda:</h5>
            <div class="row">
                <div class="col-md-6">
                    <strong>Fecha:</strong> {{ \Carbon\Carbon::parse($request->fecha_evento)->format('d/m/Y') }}
                </div>
                <div class="col-md-6">
                    <strong>Personas:</strong> {{ $request->num_personas }}
                </div>
            </div>
        </div>
    </div>

    @if($espaciosDisponibles->isEmpty())
        <div class="alert alert-warning shadow-sm" role="alert">
            <h4 class="alert-heading">😔 Lo sentimos</h4>
            <p>No hay espacios disponibles para {{ $request->num_personas }} personas.</p>
            <hr>
            <p class="mb-0">Por favor, intenta con un número menor de personas.</p>
        </div>
    @else
        <h2 class="mb-4" style="color: var(--color-primary);">Espacios Disponibles ({{ $espaciosDisponibles->count() }})</h2>
        <p class="text-muted mb-4">Puedes seleccionar uno o varios espacios para ver sus horarios</p>

        <form action="{{ route('cliente.reservas.horarios') }}" method="GET">
            <input type="hidden" name="fecha_evento" value="{{ $request->fecha_evento }}">
            <input type="hidden" name="num_personas" value="{{ $request->num_personas }}">

            <div class="row">
                @foreach($espaciosDisponibles as $espacio)
                    <div class="col-md-6 mb-4">
                        <div class="form-check espacio-disponible-card">
                            <input class="form-check-input position-absolute" 
                                style="opacity: 0; z-index: -1;"
                                type="checkbox" 
                                name="espacios[]" 
                                value="{{ $espacio->id }}"
                                id="espacio{{ $espacio->id }}"
                                @if(is_array(request('espacios')) && in_array($espacio->id, request('espacios'))) checked @endif>
                            
                            <label class="w-100" for="espacio{{ $espacio->id }}" style="cursor: pointer;">
                                <div class="card h-100 @if(is_array(request('espacios')) && in_array($espacio->id, request('espacios'))) border-primary bg-light @endif">
                                    @if($espacio->imagen)
                                        <img src="{{ asset('storage/' . $espacio->imagen) }}" 
                                            class="card-img-top" 
                                            alt="{{ $espacio->nombre }}" 
                                            style="height: 240px; object-fit: cover;">
                                    @else
                                        <div class="card-img-top d-flex align-items-center justify-content-center" 
                                            style="height: 240px; background: linear-gradient(135deg, var(--color-light) 0%, var(--color-tertiary) 100%);">
                                            <div class="text-center">
                                                <h1 style="font-size: 5rem; margin: 0; filter: drop-shadow(0 5px 10px rgba(0,0,0,0.1));">🏛️</h1>
                                            </div>
                                        </div>
                                    @endif
                                    <div class="card-body">
                                        <h4 style="color: var(--color-primary);">
                                            {{ $espacio->nombre }}
                                        </h4>
                                        <p class="text-muted mb-3">{{ $espacio->descripcion }}</p>
                                        
                                        <div class="d-flex flex-wrap gap-2 mb-3">
                                            <span class="badge bg-info">
                                                <i class="bi bi-people"></i> 👥 {{ $espacio->capacidad }} personas
                                            </span>
                                            <span class="badge bg-success">
                                                ✓ Disponible
                                            </span>
                                        </div>
                                        
                                        <div class="mt-auto">
                                            <h5 style="color: var(--color-primary); margin-bottom: 0;">
                                                S/.{{ number_format($espacio->precio_hora, 2) }} 
                                                <small style="font-size: 0.9rem; font-weight: 500; color: var(--color-secondary);">/ hora</small>
                                            </h5>
                                        </div>
                                    </div>
                                </div>
                            </label>
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="alert alert-info">
                <strong>💡 Tip:</strong> Haz clic en los espacios que te interesen y luego presiona el botón para ver sus horarios.
            </div>

            <div class="text-center mt-4">
                <button type="submit" class="btn btn-primary btn-lg">
                    Ver Horarios de Espacios Seleccionados →
                </button>
            </div>
        </form>
    @endif
</div>
@endsection