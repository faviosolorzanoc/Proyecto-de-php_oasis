@extends('layouts.cliente')

@section('title', 'Mis Reservas')

@section('content')
<div class="container py-4">
    <h1 class="mb-4" style="color: var(--color-primary);">📋 Mis Reservas</h1>

    @if($reservas->isEmpty())
        <div class="card shadow-sm">
            <div class="card-body text-center py-5">
                <h2 class="mb-3">📅</h2>
                <h4 class="text-muted mb-3">No tienes reservas aún</h4>
                <p class="text-muted mb-4">¡Organiza tu primer evento con nosotros!</p>
                <a href="{{ route('cliente.reservas') }}" class="btn btn-primary btn-lg">
                    Hacer una Reserva
                </a>
            </div>
        </div>
    @else
        <div class="row">
            @foreach($reservas as $reserva)
                <div class="col-md-12 mb-4">
                    <div class="card shadow-sm">
                        <div class="card-header d-flex justify-content-between align-items-center" 
                             style="background-color: {{ 
                                $reserva->estado == 'confirmada' ? '#28a745' : 
                                ($reserva->estado == 'completada' ? 'var(--color-primary)' : '#3572dcff')
                             }}; color: white;">
                            <div>
                                <h5 class="mb-0">
                                    Reserva #{{ str_pad($reserva->id, 6, '0', STR_PAD_LEFT) }}
                                </h5>
                                <small>Creada el {{ $reserva->created_at->format('d/m/Y H:i') }}</small>
                            </div>
                            <span class="badge bg-light text-dark fs-6">
                                @if($reserva->estado == 'confirmada')
                                    ✓ Confirmada
                                @elseif($reserva->estado == 'completada')
                                    ✓ Completada
                                @else
                                    ✗ Cancelada
                                @endif
                            </span>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-8">
                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <strong style="color: var(--color-secondary);">📅 Fecha del Evento:</strong><br>
                                            {{ \Carbon\Carbon::parse($reserva->fecha_evento)->format('d/m/Y') }}
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <strong style="color: var(--color-secondary);">🕐 Horario:</strong><br>
                                            {{ $reserva->hora_inicio }} - {{ $reserva->hora_fin }}
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <strong style="color: var(--color-secondary);">📍 Espacio:</strong><br>
                                            {{ $reserva->espacio->nombre }}
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <strong style="color: var(--color-secondary);">👥 Personas:</strong><br>
                                            {{ $reserva->num_personas }}
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <strong style="color: var(--color-secondary);">💳 Método de Pago:</strong><br>
                                            {{ ucfirst($reserva->metodo_pago) }}
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <strong style="color: var(--color-secondary);">💰 Estado de Pago:</strong><br>
                                            @if($reserva->metodo_pago == 'efectivo')
                                                <span class="badge bg-warning text-dark">⏳ Pagar al llegar</span>
                                            @else
                                                <span class="badge bg-success">✓ Pago confirmado</span>
                                            @endif
                                        </div>
                                    </div>

                                    @if($reserva->servicios_adicionales && count($reserva->servicios_adicionales) > 0)
                                        <div class="mt-2">
                                            <strong style="color: var(--color-secondary);">✨ Servicios Adicionales:</strong>
                                            <ul class="mb-0">
                                                @foreach($reserva->servicios_adicionales as $servicioId)
                                                    @php
                                                        $servicio = \App\Models\Servicio::find($servicioId);
                                                    @endphp
                                                    @if($servicio)
                                                        <li>{{ $servicio->nombre }}</li>
                                                    @endif
                                                @endforeach
                                            </ul>
                                        </div>
                                    @endif

                                    @if($reserva->observaciones)
                                        <div class="mt-2">
                                            <strong style="color: var(--color-secondary);">📝 Observaciones:</strong>
                                            <p class="mb-0">{{ $reserva->observaciones }}</p>
                                        </div>
                                    @endif
                                </div>

                                <div class="col-md-4">
                                    <div class="card" style="background-color: var(--color-light); border: none;">
                                        <div class="card-body text-center">
                                            <h6 class="mb-3" style="color: var(--color-primary);">Total de la Reserva</h6>
                                            <h2 class="mb-3" style="color: var(--color-primary);">
                                                ${{ number_format($reserva->total, 2) }}
                                            </h2>

                                            @if($reserva->estado == 'confirmada')
                                                <div class="d-grid gap-2">
                                                    <a href="https://wa.me/51999888777?text=Hola, tengo una consulta sobre mi reserva #{{ str_pad($reserva->id, 6, '0', STR_PAD_LEFT) }}" 
                                                       class="btn btn-success btn-sm" 
                                                       target="_blank">
                                                        💬 Contactar por WhatsApp
                                                    </a>
                                                    <a href="tel:+51999888777" class="btn btn-primary btn-sm">
                                                        📞 Llamar
                                                    </a>
                                                </div>
                                            @elseif($reserva->estado == 'completada')
                                                <div class="alert alert-success small mb-0">
                                                    <strong>✓ Evento Realizado</strong><br>
                                                    ¡Gracias por elegirnos!
                                                </div>
                                            @else
                                                <div class="alert alert-danger small mb-0">
                                                    <strong>✗ Reserva Cancelada</strong>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        @if($reservas->hasPages())
            <div class="mt-4">
                {{ $reservas->links() }}
            </div>
        @endif
    @endif
</div>
@endsection