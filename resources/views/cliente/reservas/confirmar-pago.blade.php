@extends('layouts.cliente')

@section('title', 'Confirmar Pago')

@section('content')
<div class="container py-4">
    <h2 class="mb-4" style="color: var(--color-primary);">💳 Confirmar Pago</h2>

    <div class="row">
        <div class="col-md-8">
            <!-- Resumen de Reserva -->
            <div class="card shadow-sm mb-4">
                <div class="card-header" style="background-color: var(--color-primary); color: white;">
                    <h5 class="mb-0">📋 Resumen de tu Reserva</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <p><strong>📅 Fecha:</strong> {{ \Carbon\Carbon::parse($reservaTemp['fecha_evento'])->format('d/m/Y') }}</p>
                            <p><strong>📍 Espacio:</strong> {{ $espacio->nombre }}</p>
                        </div>
                        <div class="col-md-6">
                            <p><strong>🕐 Horario:</strong> 
                                {{ \Carbon\Carbon::parse($horario->hora_inicio)->format('H:i') }} - 
                                {{ \Carbon\Carbon::parse($horario->hora_fin)->format('H:i') }}
                            </p>
                            <p><strong>👥 Personas:</strong> {{ $reservaTemp['num_personas'] }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Formulario según método -->
            @if($metodoPago === 'efectivo')
                <form action="{{ route('cliente.reservas.store') }}" method="POST">
                    @csrf
                    
                    <div class="card shadow-sm mb-4">
                        <div class="card-header bg-success text-white">
                            <h5 class="mb-0">💵 Pago en Efectivo</h5>
                        </div>
                        <div class="card-body">
                            <div class="alert alert-success">
                                <h6>✓ Método Seleccionado: Efectivo</h6>
                                <p class="mb-0">Pagarás S/.{{ number_format($costos['total'], 2) }} al llegar el día del evento.</p>
                            </div>
                        </div>
                    </div>

                    <div class="alert alert-warning">
                        <h6>⚠️ Políticas de Cancelación</h6>
                        <ul class="mb-0 small">
                            <li>Cancelaciones con mínimo 3 días hábiles</li>
                            <li>Contacto: +51 999 888 777</li>
                        </ul>
                    </div>

                    <div class="form-check mb-3">
                        <input class="form-check-input" type="checkbox" id="acepto" required>
                        <label class="form-check-label" for="acepto">
                            Acepto los términos y condiciones
                        </label>
                    </div>

                    <button type="submit" class="btn btn-success btn-lg w-100 mb-2">
                        ✓ Confirmar Reserva
                    </button>
                </form>

            @elseif($metodoPago === 'yape')
                <div class="card shadow-sm mb-4">
                    <div class="card-header bg-info text-white">
                        <h5 class="mb-0">📱 Pago con Yape</h5>
                    </div>
                    <div class="card-body text-center">
                        <h6 class="mb-3">Escanea el QR o Yapea al número:</h6>
                        
                        <div class="bg-white p-3 rounded shadow-sm d-inline-block mb-3">
                            <div style="width: 200px; height: 200px; background: linear-gradient(135deg, #722F87 0%, #4A1D5E 100%); border-radius: 10px; display: flex; align-items: center; justify-content: center; color: white; font-size: 3rem; font-weight: bold;">
                                QR
                            </div>
                            <p class="mt-2 mb-0"><strong>999 888 777</strong></p>
                            <small class="text-muted">Monto: S/.{{ number_format($costos['total'], 2) }}</small>
                        </div>

                        <form action="{{ route('cliente.reservas.store') }}" method="POST">
                            @csrf
                            
                            <div class="mb-3 text-start">
                                <label for="yape_codigo" class="form-label">Código de Operación (6 dígitos) *</label>
                                <input type="text" class="form-control form-control-lg text-center" 
                                       id="yape_codigo" name="yape_codigo" 
                                       placeholder="000000" maxlength="6" 
                                       pattern="[0-9]{6}" required
                                       style="letter-spacing: 0.5rem; font-size: 1.5rem; font-weight: bold;">
                                <small class="form-text text-muted">Ingresa los 6 dígitos del código Yape</small>
                            </div>

                            <div class="mb-3 text-start">
                                <label for="yape_telefono" class="form-label">Tu número Yape *</label>
                                <input type="tel" class="form-control" 
                                       id="yape_telefono" name="yape_telefono" 
                                       placeholder="999 888 777" 
                                       pattern="[0-9]{9}" required>
                            </div>

                            <div class="form-check mb-3 text-start">
                                <input class="form-check-input" type="checkbox" id="acepto" required>
                                <label class="form-check-label" for="acepto">
                                    Acepto los términos y condiciones
                                </label>
                            </div>

                            <button type="submit" class="btn btn-success btn-lg w-100 mb-2">
                                ✓ Confirmar Pago Yape
                            </button>
                        </form>
                    </div>
                </div>

            @else
                {{-- Tarjeta --}}
                <form action="{{ route('cliente.reservas.store') }}" method="POST">
                    @csrf
                    
                    <div class="card shadow-sm mb-4">
                        <div class="card-header bg-secondary text-white">
                            <h5 class="mb-0">💳 Datos de la Tarjeta</h5>
                        </div>
                        <div class="card-body">
                            <div class="mb-3">
                                <label class="form-label">Número de Tarjeta *</label>
                                <input type="text" class="form-control" 
                                       name="tarjeta_numero" id="tarjeta_numero"
                                       placeholder="1234 5678 9012 3456" 
                                       maxlength="19" required>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Nombre del Titular *</label>
                                <input type="text" class="form-control" 
                                       name="tarjeta_nombre" id="tarjeta_nombre"
                                       placeholder="JUAN PEREZ"
                                       style="text-transform: uppercase;" required>
                            </div>

                            <div class="row">
                                <div class="col-6 mb-3">
                                    <label class="form-label">Vencimiento *</label>
                                    <input type="text" class="form-control" 
                                           name="tarjeta_expiracion" id="tarjeta_expiracion"
                                           placeholder="MM/AA" 
                                           maxlength="5" required>
                                </div>

                                <div class="col-6 mb-3">
                                    <label class="form-label">CVV *</label>
                                    <input type="text" class="form-control" 
                                           name="tarjeta_cvv" id="tarjeta_cvv"
                                           placeholder="123" 
                                           maxlength="4" required>
                                </div>
                            </div>

                            <div class="alert alert-warning small">
                                🔒 Simulación de pago - No se realizarán cargos reales
                            </div>
                        </div>
                    </div>

                    <div class="form-check mb-3">
                        <input class="form-check-input" type="checkbox" id="acepto" required>
                        <label class="form-check-label" for="acepto">
                            Acepto los términos y condiciones
                        </label>
                    </div>

                    <button type="submit" class="btn btn-success btn-lg w-100 mb-2">
                        ✓ Procesar Pago
                    </button>
                </form>
            @endif

            <!-- Botón volver -->
            <form action="{{ route('cliente.reservas.cancelar-metodo') }}" method="POST">
                @csrf
                <button type="submit" class="btn btn-outline-secondary w-100">
                    ← Cambiar método de pago
                </button>
            </form>
        </div>

        <!-- Resumen lateral -->
        <div class="col-md-4">
            <div class="card shadow-sm sticky-top" style="top: 20px;">
                <div class="card-header" style="background-color: var(--color-tertiary);">
                    <h5 class="mb-0">💰 Desglose</h5>
                </div>
                <div class="card-body">
                    <div class="d-flex justify-content-between mb-2">
                        <span>Espacio:</span>
                        <span>S/.{{ number_format($costos['costo_espacio'], 2) }}</span>
                    </div>
                    @if($servicios->isNotEmpty())
                        <hr>
                        <h6 class="mb-2">Servicios:</h6>
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
                        <h4 class="mb-0 text-success">S/.{{ number_format($costos['total'], 2) }}</h4>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection