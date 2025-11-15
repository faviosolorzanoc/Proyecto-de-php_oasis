@extends('layouts.admin')

@section('title', 'Gestión de Mesas')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h2">Gestión de Mesas</h1>
    <a href="{{ route('admin.mesas.create') }}" class="btn btn-primary">Nueva Mesa</a>
</div>

<div class="card">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Número</th>
                        <th>Capacidad</th>
                        <th>Ubicación</th>
                        <th>Estado</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($mesas as $mesa)
                        <tr>
                            <td>{{ $mesa->id }}</td>
                            <td>{{ $mesa->numero }}</td>
                            <td>{{ $mesa->capacidad }} personas</td>
                            <td>{{ $mesa->ubicacion ?? 'N/A' }}</td>
                            <td>
                                <span class="badge bg-{{ $mesa->estado == 'disponible' ? 'success' : ($mesa->estado == 'ocupada' ? 'danger' : 'warning') }}">
                                    {{ ucfirst($mesa->estado) }}
                                </span>
                            </td>
                            <td>
                                <a href="{{ route('admin.mesas.edit', $mesa) }}" class="btn btn-sm btn-warning">Editar</a>
                                <form action="{{ route('admin.mesas.destroy', $mesa) }}" method="POST" class="d-inline" onsubmit="return confirm('¿Estás seguro de eliminar esta mesa?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger">Eliminar</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center">No hay mesas registradas</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <div class="mt-3">
            {{ $mesas->links() }}
        </div>
    </div>
</div>
@endsection