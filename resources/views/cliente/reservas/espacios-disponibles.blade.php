@extends('layouts.cliente')

@section('title', 'Espacios Disponibles')

@section('styles')
<style>
    .espacio-card {
        transition: all 0.3s ease;
        border: 2px solid transparent;
    }
    .espacio-card:hover {
        transform: translateY(-5px);
        border-color: var(--color-primary);
        box-shadow: 0 8px 25px rgba(0,81,135,0.15);
    }
    .espacio-card.selected {
        border-color: var(--color-primary);
        background-color: var(--color-light);
    }
</style>
@endsection

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
                <div class="col-md-3">
                    <strong>Fecha:</strong> {{ $request->fecha_evento }}
                </div>
                <div class="col-md-3">
                    <strong>Horario:</strong> {{ $request->hora_inicio }} - {{ $request->hora_fin }}
                </div>
                <div class="col-md-3">
                    <strong>Personas:</strong> {{ $request->num_personas }}
                </div>
            </div>
        </div>
    </div>

    @if($espaciosDisponibles->isEmpty())
        <div class="alert alert-warning shadow-sm" role="alert">
            <h4 class="alert-heading">😔 Lo sentimos</h4>
            <p>No hay espacios disponibles para la fecha y horario seleccionados.</p>
            <hr>
            <p class="mb-0">Por favor, intenta con otra fecha u horario diferente.</p>
        </div>
    @else
        <h2 class="mb-4" style="color: var(--color-primary);">Espacios Disponibles ({{ $espaciosDisponibles->count() }})</h2>

        <form action="{{ route('cliente.reservas.resumen') }}" method="POST" id="form-seleccion">
            @csrf
            <input type="hidden" name="fecha_evento" value="{{ $request->fecha_evento }}">
            <input type="hidden" name="hora_inicio" value="{{ $request->hora_inicio }}">
            <input type="hidden" name="hora_fin" value="{{ $request->hora_fin }}">
            <input type="hidden" name="num_personas" value="{{ $request->num_personas }}">

            <div class="row">
                @foreach($espaciosDisponibles as $espacio)
                    <div class="col-md-6 mb-4">
                        <div class="card espacio-card h-100">
                            <div class="card-body">
                                <div class="form-check">
                                    <input class="form-check-input espacio-radio" 
                                           type="radio" 
                                           name="espacio_id" 
                                           id="espacio{{ $espacio->id }}" 
                                           value="{{ $espacio->id }}"
                                           required>
                                    <label class="form-check-label w-100" for="espacio{{ $espacio->id }}">
                                        <div class="d-flex justify-content-between align-items-start">
                                            <div>
                                                <h4 style="color: var(--color-primary);">{{ $espacio->nombre }}</h4>
                                                <p class="text-muted mb-2">{{ $espacio->descripcion }}</p>
                                            </div>
                                        </div>
                                        
                                        <div class="mt-3">
                                            <span class="badge bg-info me-2">👥 Capacidad: {{ $espacio->capacidad }} personas</span>
                                            <span class="badge bg-success">✓ Disponible</span>
                                        </div>
                                        
                                        <div class="mt-3">
                                            <h5 style="color: var(--color-secondary);">${{ number_format($espacio->precio_hora, 2) }} por hora</h5>
                                        </div>
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Servicios Adicionales -->
            
            <div class="card shadow-sm mt-4">
                <div class="card-header" style="background-color: var(--color-primary); color: white;">
                    <h5 class="mb-0">✨ Servicios Complementarios (Opcional)</h5>
                </div>
                <div class="card-body">
                    <p class="text-muted mb-3">Agrega servicios extras a tu evento como decoración, catering, música, etc.</p>
                    @if($servicios->isEmpty())
                        <p class="text-muted">No hay servicios adicionales disponibles</p>
                    @else
                        <div class="row">
                            @foreach($servicios as $servicio)
                                <div class="col-md-4 mb-3">
                                    <div class="form-check">
                                        <input class="form-check-input servicio-check" 
                                               type="checkbox" 
                                               name="servicios[]" 
                                               value="{{ $servicio->id }}"
                                               data-precio="{{ $servicio->precio }}"
                                               id="servicio{{ $servicio->id }}">
                                        <label class="form-check-label" for="servicio{{ $servicio->id }}">
                                            <strong>{{ $servicio->nombre }}</strong><br>
                                            <small class="text-muted">{{ $servicio->descripcion }}</small><br>
                                            <span class="text-success">${{ number_format($servicio->precio, 2) }}</span>
                                        </label>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>

            <div class="text-center mt-4">
                <button type="submit" class="btn btn-primary btn-lg" id="btn-continuar" disabled>
                    Continuar con la Reserva →
                </button>
            </div>
        </form>
    @endif
</div>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const espacioRadios = document.querySelectorAll('.espacio-radio');
    const espacioCards = document.querySelectorAll('.espacio-card');
    const btnContinuar = document.getElementById('btn-continuar');

    espacioRadios.forEach((radio, index) => {
        radio.addEventListener('change', function() {
            // Remover clase selected de todas las tarjetas
            espacioCards.forEach(card => card.classList.remove('selected'));
            
            // Agregar clase selected a la tarjeta seleccionada
            if (this.checked) {
                espacioCards[index].classList.add('selected');
                btnContinuar.disabled = false;
            }
        });
    });
});
</script>
@endsection