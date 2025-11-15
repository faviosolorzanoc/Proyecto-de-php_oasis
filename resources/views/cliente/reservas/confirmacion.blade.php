@extends('layouts.cliente')

@section('title', 'Reserva Confirmada')

@section('styles')
<style>
    .check-animation {
        width: 100px;
        height: 100px;
        border-radius: 50%;
        background: linear-gradient(135deg, #28a745, #20c997);
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 30px;
        animation: scaleIn 0.5s ease-out;
    }
    @keyframes scaleIn {
        from {
            transform: scale(0);
        }
        to {
            transform: scale(1);
        }
    }
    .confirmation-card {
        border-radius: 20px;
        box-shadow: 0 10px 40px rgba(0,0,0,0.1);
    }
</style>
@endsection

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card confirmation-card">
                <div class="card-body p-5 text-center">
                    <div class="check-animation">
                        <svg width="50" height="50" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="3" stroke-linecap="round" stroke-linejoin="round">
                            <polyline points="20 6 9 17 4 12"></polyline>
                        </svg>
                    </div>

                    <h1 class="mb-3" style="color: var(--color-primary);">¡Reserva Confirmada!</h1>
                    <p class="lead text-muted mb-4">Tu evento ha sido registrado exitosamente</p>

                    <div class="alert alert-success">
                        <h5 class="mb-3">Código de Reserva: <strong>#{{ str_pad($reserva->id, 6, '0', STR_PAD_LEFT) }}</strong></h5>
                    </div>

                    <!-- Detalles de la Reserva -->
                    <div class="card mt-4" style="background-color: var(--color-light);">
                        <div class="card-body text-start">
                            <h5 class="card-title mb-3" style="color: var(--color-primary);">📋 Detalles de tu Reserva</h5>
                            
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <strong>📅 Fecha del Evento:</strong><br>
                                    {{ \Carbon\Carbon::parse($reserva->fecha_evento)->format('d/m/Y') }}
                                </div>
                                <div class="col-md-6 mb-3">
                                    <strong>🕐 Horario:</strong><br>
                                    {{ $reserva->hora_inicio }} - {{ $reserva->hora_fin }}
                                </div>
                                <div class="col-md-6 mb-3">
                                    <strong>📍 Espacio:</strong><br>
                                    {{ $reserva->espacio->nombre }}
                                </div>
                                <div class="col-md-6 mb-3">
                                    <strong>👥 Número de Personas:</strong><br>
                                    {{ $reserva->num_personas }} personas
                                </div>
                                <div class="col-md-6 mb-3">
                                    <strong>💳 Método de Pago:</strong><br>
                                    {{ ucfirst($reserva->metodo_pago) }}
                                </div>
                                <div class="col-md-6 mb-3">
                                    <strong>💰 Total:</strong><br>
                                    <span style="color: var(--color-primary); font-size: 1.2rem; font-weight: bold;">
                                        ${{ number_format($reserva->total, 2) }}
                                    </span>
                                </div>
                            </div>

                            @if($reserva->servicios_adicionales && count($reserva->servicios_adicionales) > 0)
                                <hr>
                                <strong>✨ Servicios Adicionales:</strong>
                                <ul class="mt-2 mb-0">
                                    @foreach($reserva->servicios_adicionales as $servicioId)
                                        @php
                                            $servicio = \App\Models\Servicio::find($servicioId);
                                        @endphp
                                        @if($servicio)
                                            <li>{{ $servicio->nombre }}</li>
                                        @endif
                                    @endforeach
                                </ul>
                            @endif

                            @if($reserva->observaciones)
                                <hr>
                                <strong>📝 Observaciones:</strong>
                                <p class="mb-0 mt-2">{{ $reserva->observaciones }}</p>
                            @endif
                        </div>
                    </div>

                    <!-- Información Importante -->
                    <div class="alert alert-info mt-4 text-start">
                        <h6 class="mb-2">📞 Información de Contacto</h6>
                        <p class="mb-1"><strong>Teléfono:</strong> +51 999 888 777</p>
                        <p class="mb-1"><strong>Email:</strong> info@sitiocampestre.com</p>
                        <p class="mb-0"><strong>WhatsApp:</strong> +51 999 888 777</p>
                    </div>

                    <div class="alert alert-warning text-start">
                        <h6 class="mb-2">⚠️ Recordatorios Importantes</h6>
                        <ul class="mb-0 small">
                            <li>Llega 15 minutos antes de tu horario reservado</li>
                            <li>Trae tu código de reserva (#{{ str_pad($reserva->id, 6, '0', STR_PAD_LEFT) }})</li>
                            @if($reserva->metodo_pago == 'efectivo')
                                <li>Recuerda llevar el efectivo para el pago</li>
                            @endif
                            <li>Para cancelaciones, contacta con 3 días de anticipación</li>
                            <li>Revisa nuestras políticas de uso en la información del sitio</li>
                        </ul>
                    </div>

                    <!-- Botones de Acción -->
                    <div class="d-flex gap-2 justify-content-center mt-4 flex-wrap">
                        <a href="{{ route('cliente.mis-reservas') }}" class="btn btn-primary btn-lg">
                            Ver Mis Reservas
                        </a>
                        <a href="{{ route('cliente.home') }}" class="btn btn-outline-secondary btn-lg">
                            Volver al Inicio
                        </a>
                        
                    </div>
                </div>
            </div>

            <!-- Tarjeta de Agradecimiento -->
            <div class="card mt-4 text-center">
                <div class="card-body">
                    <h5 style="color: var(--color-primary);">¡Gracias por confiar en nosotros! 🎉</h5>
                    <p class="mb-0 text-muted">Esperamos que disfrutes de tu evento en nuestro sitio campestre</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection