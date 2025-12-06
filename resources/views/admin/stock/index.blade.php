@extends('layouts.admin')

@section('title', 'Gestión de Stock Diario')

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Stock Disponible Hoy</h2>
        <form method="POST" action="{{ route('admin.stock.reiniciar') }}">
            @csrf
            <button type="submit" class="btn btn-warning" 
                    onclick="return confirm('¿Reiniciar stock de todos los productos?')">
                Reiniciar Stock del Día
            </button>
        </form>
    </div>
    
    <div class="card">
        <div class="card-body">
            <table class="table">
                <thead>
                    <tr>
                        <th>Producto</th>
                        <th>Categoría</th>
                        <th>Precio</th>
                        <th>Stock Actual</th>
                        <th>Actualizar Stock</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($productos as $producto)
                    <tr>
                        <td>{{ $producto->nombre }}</td>
                        <td>
                            <span class="badge bg-secondary">{{ ucfirst($producto->categoria) }}</span>
                        </td>
                        <td>S/. {{ number_format($producto->precio, 2) }}</td>
                        <td>
                            <strong>{{ $producto->stock }}</strong> disponibles
                        </td>
                        <td>
                            <form method="POST" action="{{ route('admin.stock.update', $producto) }}" 
                                  class="d-flex gap-2" style="max-width: 200px;">
                                @csrf
                                @method('PUT')
                                <input type="number" 
                                       name="stock" 
                                       value="{{ $producto->stock }}"
                                       min="0"
                                       class="form-control form-control-sm" 
                                       required>
                                <button type="submit" class="btn btn-sm btn-primary">
                                    Actualizar
                                </button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    
    <div class="alert alert-info mt-4">
        <strong>Instrucciones:</strong>
        <ul>
            <li>Al inicio del día, actualiza el stock según lo que preparaste</li>
            <li>El stock se descuenta automáticamente cuando los clientes hacen pedidos</li>
            <li>Puedes reiniciar todo al día siguiente</li>
        </ul>
    </div>
</div>
@endsection