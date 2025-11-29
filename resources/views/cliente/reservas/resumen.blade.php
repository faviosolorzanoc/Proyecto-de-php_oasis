@extends('layouts.cliente')

@section('title', 'Resumen de Reserva')

@section('content')
<div class="container py-4">
    <div class="mb-4">
        <a href="javascript:history.back()" class="btn btn-outline-secondary">
            ← Volver
        </a>
    </div>

    <h2 class="mb-4" style="color: var(--color-primary);">📋 Resumen de tu Reserva</h2>

    <div class="row">
        <div class="col-md-8">
            <!-- Detalles del Evento -->
            <div class="card shadow-sm mb-4">
                <div class="card-header" style="background-color: var(--color-primary); color: white;">
                    <h5 class="mb-0">Detalles del Evento</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <strong>📅 Fecha:</strong> {{ \Carbon\Carbon::parse($request->fecha_evento)->format('d/m/Y') }}
                        </div>
                        <div class="col-md-6 mb-3">
                            <strong>👥 Personas:</strong> {{ $request->num_personas }}
                        </div>
                        <div class="col-md-12 mb-3">
                            <strong>📍 Espacio:</strong> {{ $espacio->nombre }}
                        </div>
                        <div class="col-md-12 mb-3">
                            <strong>🕐 Horario:</strong> 
                            {{ \Carbon\Carbon::parse($horario->hora_inicio)->format('H:i') }} 
                            - 
                            {{ \Carbon\Carbon::parse($horario->hora_fin)->format('H:i') }}
                            @php
                                $inicio = \Carbon\Carbon::parse($horario->hora_inicio);
                                $fin = \Carbon\Carbon::parse($horario->hora_fin);
                                $horas = $inicio->diffInHours($fin);
                            @endphp
                            ({{ $horas }} hora(s))
                        </div>
                    </div>
                </div>
            </div>

            <!-- Método de Pago -->
            <div class="card shadow-sm mb-4">
                <div class="card-header" style="background-color: var(--color-secondary); color: white;">
                    <h5 class="mb-0">💳 Selecciona Método de Pago</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('cliente.reservas.seleccionar-metodo') }}" method="POST">
                        @csrf

                        <!-- Opciones de Pago -->
                        <div class="d-grid gap-3 mb-4">
                            <!-- Efectivo -->
                            <button type="submit" name="metodo_pago" value="efectivo" class="btn btn-outline-success btn-lg text-start">
                                <span class="fs-4">💵</span>
                                <strong class="ms-2">Efectivo</strong>
                                <small class="d-block ms-5 text-muted">Paga al llegar el día del evento</small>
                            </button>

                            <!-- Yape -->
                            <button type="submit" name="metodo_pago" value="yape" class="btn btn-outline-primary btn-lg text-start">
                                <span class="fs-4">📱</span>
                                <strong class="ms-2">Yape</strong>
                                <small class="d-block ms-5 text-muted">Transferencia rápida con código</small>
                            </button>

                            <!-- Tarjeta -->
                            <button type="submit" name="metodo_pago" value="tarjeta" class="btn btn-outline-info btn-lg text-start">
                                <span class="fs-4">💳</span>
                                <strong class="ms-2">Tarjeta</strong>
                                <small class="d-block ms-5 text-muted">Débito o crédito</small>
                            </button>
                        </div>

                        @if($errors->has('metodo_pago'))
                            <div class="alert alert-danger">
                                {{ $errors->first('metodo_pago') }}
                            </div>
                        @endif

                        <!-- Observaciones -->
                        <div class="mb-4">
                            <label for="observaciones" class="form-label">Observaciones (Opcional)</label>
                            <textarea class="form-control" 
                                      id="observaciones" 
                                      name="observaciones" 
                                      rows="3" 
                                      placeholder="Alguna solicitud especial...">{{ old('observaciones') }}</textarea>
                        </div>

                        <!-- Políticas de Cancelación -->
                        <div class="alert alert-warning">
                            <h6>⚠️ Políticas de Cancelación</h6>
                            <ul class="mb-0 small">
                                <li>Cancelaciones con mínimo <strong>3 días hábiles</strong> de anticipación</li>
                                <li>Contacto: <strong>+51 999 888 777</strong></li>
                                <li>Reprogramación por mal clima sin costo</li>
                            </ul>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Resumen de Costos -->
        <div class="col-md-4">
            <div class="card shadow-sm sticky-top" style="top: 20px;">
                <div class="card-header" style="background-color: var(--color-tertiary);">
                    <h5 class="mb-0">💰 Resumen de Costos</h5>
                </div>
                <div class="card-body">
                    <div class="d-flex justify-content-between mb-2">
                        <span>Espacio ({{ $horas }}h):</span>
                        <span>S/.{{ number_format($costoEspacio, 2) }}</span>
                    </div>

                    @if(isset($servicios) && $servicios->count() > 0)
                        <hr>
                        <h6 class="mb-2">Servicios Adicionales:</h6>
                        @foreach($servicios as $servicio)
                            <div class="d-flex justify-content-between mb-2 small">
                                <span>{{ $servicio->nombre }}</span>
                                <span>S/.{{ number_format($servicio->precio, 2) }}</span>
                            </div>
                        @endforeach
                    @endif

                    <hr>
                    <div class="d-flex justify-content-between">
                        <strong>Total:</strong>
                        <strong style="color: var(--color-primary); font-size: 1.5rem;">
                            S/.{{ number_format($total, 2) }}
                        </strong>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection