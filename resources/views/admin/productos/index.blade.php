@extends('layouts.admin')

@section('title', 'Gestión de Productos')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h2">Gestión de Productos</h1>
    <a href="{{ route('admin.productos.create') }}" class="btn btn-primary">Nuevo Producto</a>
</div>

<div class="card">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nombre</th>
                        <th>Categoría</th>
                        <th>Precio</th>
                        <th>Stock</th>
                        <th>Disponible</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($productos as $producto)
                        <tr>
                            <td>{{ $producto->id }}</td>
                            <td>{{ $producto->nombre }}</td>
                            <td><span class="badge bg-info">{{ ucfirst($producto->categoria) }}</span></td>
                            <td>S/.{{ number_format($producto->precio, 2) }}</td>
                            <td>{{ $producto->stock }}</td>
                            <td>
                                <span class="badge bg-{{ $producto->disponible ? 'success' : 'danger' }}">
                                    {{ $producto->disponible ? 'Sí' : 'No' }}
                                </span>
                            </td>
                            <td>
                                <a href="{{ route('admin.productos.edit', $producto) }}" class="btn btn-sm btn-warning">Editar</a>
                                <form action="{{ route('admin.productos.destroy', $producto) }}" method="POST" class="d-inline" onsubmit="return confirm('¿Estás seguro de eliminar este producto?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger">Eliminar</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center">No hay productos registrados</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <div class="mt-3">
            {{ $productos->links() }}
        </div>
    </div>
</div>
@endsection