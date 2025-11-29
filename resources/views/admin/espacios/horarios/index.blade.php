@extends('layouts.admin')

@section('title', 'Horarios - ' . $espacio->nombre)

@section('content')
<div class="mb-4">
    <a href="{{ route('admin.espacios.index') }}" class="btn btn-secondary">
        ← Volver a Espacios
    </a>
</div>

<div class="d-flex justify-content-between align-items-center mb-4 fade-in">
    <div>
        <h1 class="h2 mb-1">🕐 Horarios de: {{ $espacio->nombre }}</h1>
        <p class="text-muted mb-0">Gestiona los horarios disponibles para este espacio</p>
    </div>
    <a href="{{ route('admin.horarios.create', $espacio) }}" class="btn btn-primary">
        ➕ Crear Horarios
    </a>
</div>

<div class="card fade-in">
    <div class="card-body">
        @if($horarios->isEmpty())
            <div class="text-center py-5">
                <h2 class="mb-3">🕐</h2>
                <h4 class="text-muted">No hay horarios registrados</h4>
                <p class="text-muted">Comienza creando horarios para este espacio</p>
                <a href="{{ route('admin.horarios.create', $espacio) }}" class="btn btn-primary mt-3">
                    Crear Horarios
                </a>
            </div>
        @else
            <div class="table-responsive">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Fecha</th>
                            <th>Horario</th>
                            <th>Duración</th>
                            <th>Estado</th>
                            <th>Reserva</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($horarios as $horario)
                            <tr>
                                <td><strong>{{ \Carbon\Carbon::parse($horario->fecha)->format('d/m/Y') }}</strong></td>
                                <td>
                                    {{ \Carbon\Carbon::parse($horario->hora_inicio)->format('H:i') }} 
                                    - 
                                    {{ \Carbon\Carbon::parse($horario->hora_fin)->format('H:i') }}
                                </td>
                                <td>
                                    @php
                                        $inicio = \Carbon\Carbon::parse($horario->hora_inicio);
                                        $fin = \Carbon\Carbon::parse($horario->hora_fin);
                                        $horas = $inicio->diffInHours($fin);
                                    @endphp
                                    {{ $horas }} hora(s)
                                </td>
                                <td>
                                    @if($horario->estado == 'disponible')
                                        <span class="badge bg-success">✓ Disponible</span>
                                    @elseif($horario->estado == 'ocupado')
                                        <span class="badge bg-danger">✗ Ocupado</span>
                                    @else
                                        <span class="badge bg-warning">🔒 Bloqueado</span>
                                    @endif
                                </td>
                                <td>
                                    @if($horario->reserva_id)
                                        <span class="badge bg-info">Reserva #{{ $horario->reserva_id }}</span>
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                                <td>
                                    @if($horario->estado != 'ocupado')
                                        <form action="{{ route('admin.horarios.updateEstado', [$espacio, $horario]) }}" 
                                              method="POST" 
                                              class="d-inline">
                                            @csrf
                                            @if($horario->estado == 'disponible')
                                                <input type="hidden" name="estado" value="bloqueado">
                                                <button type="submit" class="btn btn-sm btn-warning" title="Bloquear">
                                                    🔒 Bloquear
                                                </button>
                                            @else
                                                <input type="hidden" name="estado" value="disponible">
                                                <button type="submit" class="btn btn-sm btn-success" title="Desbloquear">
                                                    ✓ Disponible
                                                </button>
                                            @endif
                                        </form>

                                        <form action="{{ route('admin.horarios.destroy', [$espacio, $horario]) }}" 
                                              method="POST" 
                                              class="d-inline" 
                                              onsubmit="return confirm('¿Eliminar este horario?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger">
                                                🗑️
                                            </button>
                                        </form>
                                    @else
                                        <span class="text-muted">Con reserva</span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            
            <div class="mt-3">
                {{ $horarios->links() }}
            </div>
        @endif
    </div>
</div>
@endsection