@extends('layouts.admin')

@section('title', 'Gestión de Espacios')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4 fade-in">
    <div>
        <h1 class="h2 mb-1">🏛️ Gestión de Espacios Físicos</h1>
        <p class="text-muted mb-0">Administra las áreas disponibles para eventos (piscina, cancha, salón, etc.)</p>
    </div>
    <a href="{{ route('admin.espacios.create') }}" class="btn btn-primary">
        ➕ Nuevo Espacio
    </a>
</div>

<div class="card fade-in">
    <div class="card-body">
        @if($espacios->isEmpty())
            <div class="text-center py-5">
                <h2 class="mb-3">🏛️</h2>
                <h4 class="text-muted">No hay espacios registrados</h4>
                <p class="text-muted">Comienza agregando tu primer espacio</p>
                <a href="{{ route('admin.espacios.create') }}" class="btn btn-primary mt-3">
                    Crear Espacio
                </a>
            </div>
        @else
            <div class="table-responsive">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Nombre</th>
                            <th>Descripción</th>
                            <th>Capacidad</th>
                            <th>Precio/Hora</th>
                            <th>Estado</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($espacios as $espacio)
                            <tr>
                                <td><strong>#{{ $espacio->id }}</strong></td>
                                <td>{{ $espacio->nombre }}</td>
                                <td>{{ Str::limit($espacio->descripcion, 40) ?? 'Sin descripción' }}</td>
                                <td><span class="badge bg-info">👥 {{ $espacio->capacidad }} personas</span></td>
                                <td><strong>S/.{{ number_format($espacio->precio_hora, 2) }}</strong></td>
                                <td>
                                    <span class="badge bg-{{ $espacio->disponible ? 'success' : 'danger' }}">
                                        {{ $espacio->disponible ? '✓ Disponible' : '✗ No disponible' }}
                                    </span>
                                </td>
                                <td>
                                    <div class="d-flex gap-2 flex-wrap">
                                        <a href="{{ route('admin.horarios.index', $espacio) }}" 
                                           class="btn btn-info btn-sm" 
                                           title="Gestionar Horarios">
                                            🕐 Horarios
                                        </a>
                                        <a href="{{ route('admin.espacios.edit', $espacio) }}" 
                                           class="btn btn-warning btn-sm" 
                                           title="Editar">
                                            ✏️ Editar
                                        </a>
                                        <form action="{{ route('admin.espacios.destroy', $espacio) }}" 
                                              method="POST" 
                                              class="d-inline" 
                                              onsubmit="return confirm('¿Estás seguro de eliminar este espacio?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger btn-sm" title="Eliminar">
                                                🗑️ Eliminar
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            
            <div class="mt-3 d-flex justify-content-between align-items-center">
                <p class="text-muted mb-0">
                    Mostrando {{ $espacios->firstItem() }} - {{ $espacios->lastItem() }} de {{ $espacios->total() }} espacios
                </p>
                {{ $espacios->links() }}
            </div>
        @endif
    </div>
</div>
@endsection