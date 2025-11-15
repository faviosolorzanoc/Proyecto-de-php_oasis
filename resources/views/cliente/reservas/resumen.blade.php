@extends('layouts.cliente')

@section('title', 'Resumen de Reserva')

@section('styles')
<style>
    .payment-option {
        cursor: pointer;
        transition: all 0.3s ease;
        border: 2px solid #e0e0e0;
    }
    .payment-option:hover {
        border-color: var(--color-primary);
        transform: scale(1.02);
    }
    .payment-option.selected {
        border-color: var(--color-primary);
        background-color: var(--color-light);
    }
    .payment-details {
        display: none;
    }
    .payment-details.active {
        display: block;
    }
</style>
@endsection

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
                            <strong>📅 Fecha:</strong> {{ $request->fecha_evento }}
                        </div>
                        <div class="col-md-6 mb-3">
                            <strong>🕐 Horario:</strong> {{ $request->hora_inicio }} - {{ $request->hora_fin }}
                        </div>
                        <div class="col-md-6 mb-3">
                            <strong>👥 Personas:</strong> {{ $request->num_personas }}
                        </div>
                        <div class="col-md-6 mb-3">
                            <strong>📍 Espacio:</strong> {{ $espacio->nombre }}
                        </div>
                    </div>
                </div>
            </div>

            <!-- Método de Pago -->
            <div class="card shadow-sm mb-4">
                <div class="card-header" style="background-color: var(--color-secondary); color: white;">
                    <h5 class="mb-0">💳 Método de Pago</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('cliente.reservas.store') }}" method="POST" id="form-reserva">
                        @csrf
                        <input type="hidden" name="espacio_id" value="{{ $request->espacio_id }}">
                        <input type="hidden" name="fecha_evento" value="{{ $request->fecha_evento }}">
                        <input type="hidden" name="hora_inicio" value="{{ $request->hora_inicio }}">
                        <input type="hidden" name="hora_fin" value="{{ $request->hora_fin }}">
                        <input type="hidden" name="num_personas" value="{{ $request->num_personas }}">
                        <input type="hidden" name="total" id="input-total" value="0">
                        @if($request->servicios)
                            @foreach($request->servicios as $servId)
                                <input type="hidden" name="servicios[]" value="{{ $servId }}">
                            @endforeach
                        @endif

                        <div class="row g-3">
                            <!-- Efectivo -->
                            <div class="col-md-4">
                                <div class="payment-option card p-3 text-center" data-method="efectivo">
                                    <input type="radio" name="metodo_pago" value="efectivo" id="efectivo" class="d-none" required>
                                    <label for="efectivo" class="w-100 mb-0" style="cursor: pointer;">
                                        <h2>💵</h2>
                                        <h5>Efectivo</h5>
                                        <small class="text-muted">Paga al llegar</small>
                                    </label>
                                </div>
                            </div>

                            <!-- Yape -->
                            <div class="col-md-4">
                                <div class="payment-option card p-3 text-center" data-method="yape">
                                    <input type="radio" name="metodo_pago" value="yape" id="yape" class="d-none" required>
                                    <label for="yape" class="w-100 mb-0" style="cursor: pointer;">
                                        <h2>📱</h2>
                                        <h5>Yape</h5>
                                        <small class="text-muted">Transferencia rápida</small>
                                    </label>
                                </div>
                            </div>

                            <!-- Tarjeta -->
                            <div class="col-md-4">
                                <div class="payment-option card p-3 text-center" data-method="tarjeta">
                                    <input type="radio" name="metodo_pago" value="tarjeta" id="tarjeta" class="d-none" required>
                                    <label for="tarjeta" class="w-100 mb-0" style="cursor: pointer;">
                                        <h2>💳</h2>
                                        <h5>Tarjeta</h5>
                                        <small class="text-muted">Débito o crédito</small>
                                    </label>
                                </div>
                            </div>
                        </div>

                        <!-- Detalles de Yape -->
                        <div class="payment-details mt-4" id="yape-details">
                            <div class="alert alert-info">
                                <h6>Escanea el código QR de Yape</h6>
                                <div class="text-center my-3">
                                    <div style="width: 200px; height: 200px; background: #ddd; margin: 0 auto; display: flex; align-items: center; justify-content: center; border-radius: 10px;">
                                        <span>Código QR Yape</span>
                                    </div>
                                </div>
                                <p class="mb-0"><strong>Número:</strong> 999 888 777</p>
                                <p class="mb-0"><strong>Nombre:</strong> Sitio Campestre Oasis</p>
                            </div>
                        </div>

                        <!-- Detalles de Tarjeta -->
                        <div class="payment-details mt-4" id="tarjeta-details">
                            <div class="row">
                                <div class="col-md-12 mb-3">
                                    <label class="form-label">Número de Tarjeta</label>
                                    <input type="text" class="form-control" placeholder="1234 5678 9012 3456" maxlength="19">
                                </div>
                                <div class="col-md-8 mb-3">
                                    <label class="form-label">Fecha de Vencimiento</label>
                                    <input type="text" class="form-control" placeholder="MM/AA" maxlength="5">
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label class="form-label">CVV</label>
                                    <input type="text" class="form-control" placeholder="123" maxlength="3">
                                </div>
                            </div>
                        </div>

                        <!-- Detalles de Efectivo -->
                        <div class="payment-details mt-4" id="efectivo-details">
                            <div class="alert alert-success">
                                <h6>✓ Pago en Efectivo</h6>
                                <p class="mb-0">Podrás pagar cuando llegues al establecimiento el día del evento.</p>
                            </div>
                        </div>

                        <!-- Observaciones -->
                        <div class="mt-4">
                            <label for="observaciones" class="form-label">Observaciones (Opcional)</label>
                            <textarea class="form-control" id="observaciones" name="observaciones" rows="3" placeholder="Alguna solicitud especial..."></textarea>
                        </div>

                        <!-- Políticas de Cancelación -->
                        <div class="alert alert-warning mt-4">
                            <h6>⚠️ Políticas de Cancelación</h6>
                            <ul class="mb-0 small">
                                <li>Las cancelaciones deben realizarse con mínimo <strong>3 días hábiles</strong> de anticipación</li>
                                <li>No se aceptan cancelaciones después de este plazo</li>
                                <li>Para cancelar, contacta al: <strong>+51 999 888 777</strong></li>
                                <li>En caso de mal clima, se reprogramará sin costo adicional</li>
                            </ul>
                        </div>

                        <div class="form-check mb-3">
                            <input class="form-check-input" type="checkbox" id="acepto-politicas" required>
                            <label class="form-check-label" for="acepto-politicas">
                                Acepto los términos y condiciones y las políticas de cancelación
                            </label>
                        </div>

                        <button type="submit" class="btn btn-success btn-lg w-100" id="btn-confirmar" disabled>
                            Confirmar Reserva
                        </button>
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
                        <span>Espacio:</span>
                        <span id="costo-espacio">${{ number_format($espacio->precio_hora, 2) }}/hora</span>
                    </div>

                    @if($servicios->isNotEmpty())
                        <hr>
                        <h6 class="mb-2">Servicios Adicionales:</h6>
                        @foreach($servicios as $servicio)
                            <div class="d-flex justify-content-between mb-2 small">
                                <span>{{ $servicio->nombre }}</span>
                                <span>${{ number_format($servicio->precio, 2) }}</span>
                            </div>
                        @endforeach
                    @endif

                    <hr>
                    <div class="d-flex justify-content-between">
                        <strong>Total:</strong>
                        <strong style="color: var(--color-primary); font-size: 1.5rem;" id="total-final">
                            $0.00
                        </strong>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const paymentOptions = document.querySelectorAll('.payment-option');
    const paymentDetails = document.querySelectorAll('.payment-details');
    const aceptoPoliticas = document.getElementById('acepto-politicas');
    const btnConfirmar = document.getElementById('btn-confirmar');
    
    // Calcular total
    const precioEspacio = {{ $espacio->precio_hora }};
    let totalServicios = 0;
    @if($servicios->isNotEmpty())
        @foreach($servicios as $servicio)
            totalServicios += {{ $servicio->precio }};
        @endforeach
    @endif
    
    const horaInicio = '{{ $request->hora_inicio }}';
    const horaFin = '{{ $request->hora_fin }}';
    const horas = calcularHoras(horaInicio, horaFin);
    const total = (precioEspacio * horas) + totalServicios;
    
    document.getElementById('total-final').textContent = '$' + total.toFixed(2);
    document.getElementById('input-total').value = total.toFixed(2);

    // Método de pago
    paymentOptions.forEach(option => {
        option.addEventListener('click', function() {
            const method = this.dataset.method;
            const radio = document.getElementById(method);
            
            // Remover selected de todos
            paymentOptions.forEach(opt => opt.classList.remove('selected'));
            paymentDetails.forEach(det => det.classList.remove('active'));
            
            // Agregar selected al clickeado
            this.classList.add('selected');
            radio.checked = true;
            document.getElementById(method + '-details').classList.add('active');
            
            verificarFormulario();
        });
    });

    aceptoPoliticas.addEventListener('change', verificarFormulario);

    function verificarFormulario() {
        const metodoPagoSeleccionado = document.querySelector('input[name="metodo_pago"]:checked');
        btnConfirmar.disabled = !(metodoPagoSeleccionado && aceptoPoliticas.checked);
    }

    function calcularHoras(inicio, fin) {
        const [hI, mI] = inicio.split(':').map(Number);
        const [hF, mF] = fin.split(':').map(Number);
        const minutos = (hF * 60 + mF) - (hI * 60 + mI);
        return Math.ceil(minutos / 60);
    }
});
</script>
@endsection