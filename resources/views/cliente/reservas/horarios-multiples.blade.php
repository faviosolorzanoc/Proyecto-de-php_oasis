@extends('layouts.cliente')

@section('title', 'Horarios Disponibles')



@section('content')
<div class="container py-4">
    <div class="mb-4">
        <form action="{{ route('cliente.reservas.verificar') }}" method="POST" class="d-inline">
            @csrf
            <input type="hidden" name="fecha_evento" value="{{ $request->fecha_evento }}">
            <input type="hidden" name="num_personas" value="{{ $request->num_personas }}">
            <button type="submit" class="btn btn-outline-secondary">← Volver a seleccionar espacios</button>
        </form>
    </div>

    <div class="card shadow-sm mb-4">
        <div class="card-body" style="background-color: var(--color-light);">
            <h5 class="mb-3" style="color: var(--color-primary);">📋 Datos de tu búsqueda:</h5>
            <div class="row">
                <div class="col-md-4">
                    <strong>📅 Fecha:</strong> {{ \Carbon\Carbon::parse($request->fecha_evento)->format('d/m/Y') }}
                </div>
                <div class="col-md-4">
                    <strong>👥 Personas:</strong> {{ $request->num_personas }}
                </div>
                <div class="col-md-4">
                    <strong>🏛️ Espacios:</strong> {{ $espaciosSeleccionados->count() }} seleccionados
                </div>
            </div>
        </div>
    </div>

    @if($horariosDisponibles->isEmpty())
        <div class="alert alert-warning shadow-sm" role="alert">
            <h4 class="alert-heading">😔 No hay horarios disponibles</h4>
            <p>Lo sentimos, no hay horarios disponibles para los espacios seleccionados en esta fecha.</p>
            <hr>
            <p class="mb-0">
                <form action="{{ route('cliente.reservas.verificar') }}" method="POST" class="d-inline">
                    @csrf
                    <input type="hidden" name="fecha_evento" value="{{ $request->fecha_evento }}">
                    <input type="hidden" name="num_personas" value="{{ $request->num_personas }}">
                    <button type="submit" class="btn btn-link p-0">← Volver a elegir otros espacios</button>
                </form>
            </p>
        </div>
    @else
        <h2 class="mb-4" style="color: var(--color-primary);">Horarios Disponibles por Espacio</h2>
        <p class="text-muted mb-4">Selecciona el espacio y horario que prefieras para tu evento</p>

        <form action="{{ route('cliente.reservas.resumen') }}" method="POST">
            @csrf
            <input type="hidden" name="fecha_evento" value="{{ $request->fecha_evento }}">
            <input type="hidden" name="num_personas" value="{{ $request->num_personas }}">

            @foreach($espaciosSeleccionados as $espacio)
                <div class="card mb-4 shadow-sm espacio-section">
                    <div class="card-header" style="background-color: var(--color-primary); color: white;">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h5 class="mb-0">🏛️ {{ $espacio->nombre }}</h5>
                                <small>{{ $espacio->descripcion }}</small>
                            </div>
                            <span class="badge bg-light text-dark">
                                S/.{{ number_format($espacio->precio_hora, 2) }}/hora
                            </span>
                        </div>
                    </div>
                    <div class="card-body">
                        @if(isset($horariosDisponibles[$espacio->id]) && $horariosDisponibles[$espacio->id]->isNotEmpty())
                            <div class="row">
                                @foreach($horariosDisponibles[$espacio->id] as $horario)
                                    <div class="col-md-3 mb-3">
                                        <input type="radio" 
                                               class="horario-radio"
                                               name="horario_id" 
                                               id="horario{{ $horario->id }}" 
                                               value="{{ $horario->id }}"
                                               required>
                                        
                                        <label for="horario{{ $horario->id }}" class="horario-label">
                                            <div class="horario-card">
                                                <div class="card-body text-center">
                                                    <h5 style="color: var(--color-secondary);">
                                                        🕐 {{ \Carbon\Carbon::parse($horario->hora_inicio)->format('H:i') }}
                                                        -
                                                        {{ \Carbon\Carbon::parse($horario->hora_fin)->format('H:i') }}
                                                    </h5>
                                                    
                                                    @php
                                                        $inicio = \Carbon\Carbon::parse($horario->hora_inicio);
                                                        $fin = \Carbon\Carbon::parse($horario->hora_fin);
                                                        $duracion = $inicio->diffInHours($fin);
                                                        $costo = $duracion * $espacio->precio_hora;
                                                    @endphp
                                                    
                                                    <p class="mb-1 small text-muted">{{ $duracion }} hora(s)</p>
                                                    <h6 style="color: var(--color-primary);">
                                                        S/.{{ number_format($costo, 2) }}
                                                    </h6>
                                                    <span class="badge bg-success mt-2">✓ Disponible</span>
                                                </div>
                                            </div>
                                        </label>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <p class="text-muted text-center py-3">No hay horarios disponibles en este espacio para esta fecha</p>
                        @endif
                    </div>
                </div>
            @endforeach

            @if($errors->has('horario_id'))
                <div class="alert alert-danger">
                    {{ $errors->first('horario_id') }}
                </div>
            @endif

            <!-- Servicios Adicionales -->
            <div class="card shadow-sm mt-4">
                <div class="card-header" style="background-color: var(--color-secondary); color: white;">
                    <h5 class="mb-0">✨ Servicios Complementarios (Opcional)</h5>
                </div>
                <div class="card-body">
                    <p class="text-muted mb-3">Agrega servicios extras para hacer tu evento más especial</p>
                    @if($servicios->isEmpty())
                        <p class="text-muted">No hay servicios adicionales disponibles</p>
                    @else
                        <div class="row">
                            @foreach($servicios as $servicio)
                                <div class="col-md-4 mb-3">
                                    <div class="card h-100">
                                        <div class="card-body">
                                            <div class="form-check">
                                                <input class="form-check-input" 
                                                       type="checkbox" 
                                                       name="servicios[]" 
                                                       value="{{ $servicio->id }}"
                                                       id="servicio{{ $servicio->id }}">
                                                <label class="form-check-label w-100" for="servicio{{ $servicio->id }}">
                                                    <strong>{{ $servicio->nombre }}</strong><br>
                                                    <small class="text-muted">{{ Str::limit($servicio->descripcion, 50) }}</small><br>
                                                    <span class="badge bg-success mt-2">S/.{{ number_format($servicio->precio, 2) }}</span>
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>

            <div class="text-center mt-4">
                <button type="submit" class="btn btn-primary btn-lg">
                    Continuar al Resumen →
                </button>
            </div>
        </form>
    @endif
</div>
@endsection