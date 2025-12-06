@extends('layouts.admin')

@section('title', 'Detalle de Reserva')

@section('content')
<div class="container-fluid">
    <div class="mb-4">
        <a href="{{ route('admin.reservas.index') }}" class="btn btn-secondary">
            ← Volver al listado
        </a>
    </div>

    <div class="row">
        <div class="col-md-8">
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white">
                    <h4 class="mb-0">Reserva #{{ $reserva->id }}</h4>
                </div>
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <h5>Información del Cliente</h5>
                            <p class="mb-1"><strong>Nombre:</strong> {{ $reserva->user->name }}</p>
                            <p class="mb-1"><strong>Email:</strong> {{ $reserva->user->email }}</p>
                            <p class="mb-1"><strong>Teléfono:</strong> {{ $reserva->user->telefono ?? 'No registrado' }}</p>
                        </div>
                        <div class="col-md-6">
                            <h5>Información del Evento</h5>
                            <p class="mb-1"><strong>Fecha:</strong> {{ \Carbon\Carbon::parse($reserva->fecha_evento)->format('d/m/Y') }}</p>
                            <p class="mb-1"><strong>Espacio:</strong> {{ $reserva->horario->espacio->nombre }}</p>
                            <p class="mb-1"><strong>Horario:</strong> 
                                {{ \Carbon\Carbon::parse($reserva->horario->hora_inicio)->format('H:i') }} - 
                                {{ \Carbon\Carbon::parse($reserva->horario->hora_fin)->format('H:i') }}
                            </p>
                            <p class="mb-1"><strong>Personas:</strong> {{ $reserva->num_personas }}</p>
                        </div>
                    </div>

                    <hr>

                    <h5>Servicios Adicionales</h5>
                    @if($reserva->servicios->count() > 0)
                        <table class="table table-sm">
                            <thead>
                                <tr>
                                    <th>Servicio</th>
                                    <th>Precio</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($reserva->servicios as $servicio)
                                <tr>
                                    <td>{{ $servicio->nombre }}</td>
                                    <td>S/. {{ number_format($servicio->precio, 2) }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    @else
                        <p class="text-muted">No se agregaron servicios adicionales</p>
                    @endif

                    @if($reserva->observaciones)
                        <hr>
                        <h5>Observaciones</h5>
                        <p>{{ $reserva->observaciones }}</p>
                    @endif
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card shadow-sm">
                <div class="card-header bg-secondary text-white">
                    <h5 class="mb-0">Resumen</h5>
                </div>
                <div class="card-body">
                    <p class="mb-2">
                        <strong>Estado:</strong>
                        <span class="badge 
                            @if($reserva->estado === 'confirmada') bg-success
                            @elseif($reserva->estado === 'cancelada') bg-danger
                            @elseif($reserva->estado === 'completada') bg-primary
                            @else bg-warning
                            @endif">
                            {{ ucfirst($reserva->estado) }}
                        </span>
                    </p>
                    <p class="mb-2">
                        <strong>Método de Pago:</strong>
                        <span class="badge bg-info">{{ ucfirst($reserva->metodo_pago) }}</span>
                    </p>
                    <hr>
                    <h4 class="text-end">
                        <strong>Total: S/. {{ number_format($reserva->total, 2) }}</strong>
                    </h4>
                    <hr>
                    <p class="mb-2 small text-muted">
                        <strong>Creada:</strong> {{ $reserva->created_at->format('d/m/Y H:i') }}
                    </p>
                    <p class="mb-0 small text-muted">
                        <strong>Actualizada:</strong> {{ $reserva->updated_at->format('d/m/Y H:i') }}
                    </p>
                </div>
            </div>

            <div class="card shadow-sm mt-3">
                <div class="card-header bg-info text-white">
                    <h5 class="mb-0">Cambiar Estado</h5>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('admin.reservas.updateEstado', $reserva) }}">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label">Estado Actual</label>
                            <select name="estado" class="form-select">
                                <option value="pendiente" {{ $reserva->estado === 'pendiente' ? 'selected' : '' }}>Pendiente</option>
                                <option value="confirmada" {{ $reserva->estado === 'confirmada' ? 'selected' : '' }}>Confirmada</option>
                                <option value="completada" {{ $reserva->estado === 'completada' ? 'selected' : '' }}>Completada</option>
                                <option value="cancelada" {{ $reserva->estado === 'cancelada' ? 'selected' : '' }}>Cancelada</option>
                            </select>
                        </div>
                        <button type="submit" class="btn btn-primary w-100">Actualizar Estado</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection