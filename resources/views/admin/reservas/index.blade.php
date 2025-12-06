@extends('layouts.admin')

@section('title', 'Gestión de Reservas')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Gestión de Reservas</h2>
        <div>
            <span class="badge bg-secondary">Total: {{ $reservas->total() }}</span>
        </div>
    </div>

    <div class="card shadow-sm">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead class="table-light">
                        <tr>
                            <th>ID</th>
                            <th>Cliente</th>
                            <th>Espacio</th>
                            <th>Fecha Evento</th>
                            <th>Horario</th>
                            <th>Personas</th>
                            <th>Total</th>
                            <th>Método Pago</th>
                            <th>Estado</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($reservas as $reserva)
                        <tr>
                            <td><strong>#{{ $reserva->id }}</strong></td>
                            <td>
                                {{ $reserva->user->name }}<br>
                                <small class="text-muted">{{ $reserva->user->email }}</small>
                            </td>
                            <td>
                                <strong>{{ $reserva->horario->espacio->nombre }}</strong>
                            </td>
                            <td>
                                {{ \Carbon\Carbon::parse($reserva->fecha_evento)->format('d/m/Y') }}
                            </td>
                            <td>
                                {{ \Carbon\Carbon::parse($reserva->horario->hora_inicio)->format('H:i') }} - 
                                {{ \Carbon\Carbon::parse($reserva->horario->hora_fin)->format('H:i') }}
                            </td>
                            <td>
                                <span class="badge bg-info">{{ $reserva->num_personas }} personas</span>
                            </td>
                            <td>
                                <strong>S/. {{ number_format($reserva->total, 2) }}</strong>
                            </td>
                            <td>
                                <span class="badge 
                                    @if($reserva->metodo_pago === 'efectivo') bg-success
                                    @elseif($reserva->metodo_pago === 'yape') bg-primary
                                    @else bg-info
                                    @endif">
                                    {{ ucfirst($reserva->metodo_pago) }}
                                </span>
                            </td>
                            <td>
                                <form method="POST" action="{{ route('admin.reservas.updateEstado', $reserva) }}" class="d-inline">
                                    @csrf
                                    <select name="estado" class="form-select form-select-sm
                                        @if($reserva->estado === 'confirmada') bg-success text-white
                                        @elseif($reserva->estado === 'cancelada') bg-danger text-white
                                        @elseif($reserva->estado === 'completada') bg-primary text-white
                                        @else bg-warning
                                        @endif"
                                        onchange="this.form.submit()" style="min-width: 120px;">
                                        <option value="pendiente" {{ $reserva->estado === 'pendiente' ? 'selected' : '' }}>Pendiente</option>
                                        <option value="confirmada" {{ $reserva->estado === 'confirmada' ? 'selected' : '' }}>Confirmada</option>
                                        <option value="completada" {{ $reserva->estado === 'completada' ? 'selected' : '' }}>Completada</option>
                                        <option value="cancelada" {{ $reserva->estado === 'cancelada' ? 'selected' : '' }}>Cancelada</option>
                                    </select>
                                </form>
                            </td>
                            <td>
                                <a href="{{ route('admin.reservas.show', $reserva) }}" class="btn btn-sm btn-info">
                                    Ver Detalles
                                </a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="10" class="text-center text-muted py-4">
                                No hay reservas registradas
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-3">
                {{ $reservas->links() }}
            </div>
        </div>
    </div>
</div>
@endsection