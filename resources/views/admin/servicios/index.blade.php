@extends('layouts.admin')

@section('title', 'Gestión de Servicios')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4 fade-in">
    <div>
        <h1 class="h2 mb-1">✨ Gestión de Servicios Adicionales</h1>
        <p class="text-muted mb-0">Administra los servicios complementarios (decoración, catering, música, etc.)</p>
    </div>
    <a href="{{ route('admin.servicios.create') }}" class="btn btn-primary">
        ➕ Nuevo Servicio
    </a>
</div>

<div class="card fade-in">
    <div class="card-body">
        @if($servicios->isEmpty())
            <div class="text-center py-5">
                <h2 class="mb-3">📋</h2>
                <h4 class="text-muted">No hay servicios registrados</h4>
                <p class="text-muted">Comienza agregando tu primer servicio</p>
                <a href="{{ route('admin.servicios.create') }}" class="btn btn-primary mt-3">
                    Crear Servicio
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
                            <th>Precio</th>
                            <th>Estado</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($servicios as $servicio)
                            <tr>
                                <td><strong>#{{ $servicio->id }}</strong></td>
                                <td>{{ $servicio->nombre }}</td>
                                <td>{{ Str::limit($servicio->descripcion, 50) ?? 'Sin descripción' }}</td>
                                <td><strong>${{ number_format($servicio->precio, 2) }}</strong></td>
                                <td>
                                    <span class="badge bg-{{ $servicio->disponible ? 'success' : 'danger' }}">
                                        {{ $servicio->disponible ? '✓ Disponible' : '✗ No disponible' }}
                                    </span>
                                </td>
                                <td>
                                    <div class="d-flex gap-2">
                                        <a href="{{ route('admin.servicios.edit', $servicio) }}" 
                                           class="btn btn-warning btn-sm" 
                                           title="Editar">
                                            ✏️ Editar
                                        </a>
                                        <form action="{{ route('admin.servicios.destroy', $servicio) }}" 
                                              method="POST" 
                                              class="d-inline" 
                                              onsubmit="return confirm('¿Estás seguro de eliminar este servicio?')">
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
                    Mostrando {{ $servicios->firstItem() }} - {{ $servicios->lastItem() }} de {{ $servicios->total() }} servicios
                </p>
                {{ $servicios->links() }}
            </div>
        @endif
    </div>
</div>
@endsection