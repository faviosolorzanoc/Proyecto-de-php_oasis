@extends('layouts.admin')

@section('title', 'Detalle del Pedido')

@section('content')
<div class="mb-4">
    <h1 class="h2">Detalle del Pedido #{{ $pedido->id }}</h1>
</div>

<div class="row">
    <div class="col-md-8">
        <div class="card mb-4">
            <div class="card-header">
                <h5>Información del Pedido</h5>
            </div>
            <div class="card-body">
                <p><strong>Cliente:</strong> {{ $pedido->user->name }}</p>
                <p><strong>Email:</strong> {{ $pedido->user->email }}</p>
                <p><strong>Teléfono:</strong> {{ $pedido->user->telefono ?? 'N/A' }}</p>
                <p><strong>Mesa:</strong> {{ $pedido->mesa ? 'Mesa ' . $pedido->mesa->numero : 'Para llevar' }}</p>
                <p><strong>Fecha:</strong> {{ $pedido->created_at->format('d/m/Y H:i:s') }}</p>
                <p><strong>Observaciones:</strong> {{ $pedido->observaciones ?? 'Ninguna' }}</p>
            </div>
        </div>

        <div class="card">
            <div class="card-header">
                <h5>Productos del Pedido</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Producto</th>
                                <th>Cantidad</th>
                                <th>Precio Unit.</th>
                                <th>Subtotal</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($pedido->detalles as $detalle)
                                <tr>
                                    <td>{{ $detalle->producto->nombre }}</td>
                                    <td>{{ $detalle->cantidad }}</td>
                                    <td>${{ number_format($detalle->precio_unitario, 2) }}</td>
                                    <td>${{ number_format($detalle->subtotal, 2) }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr>
                                <th colspan="3" class="text-end">Total:</th>
                                <th>${{ number_format($pedido->total, 2) }}</th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card">
            <div class="card-header">
                <h5>Cambiar Estado</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('admin.pedidos.updateEstado', $pedido) }}" method="POST">
                    @csrf
                    
                    <div class="mb-3">
                        <label for="estado" class="form-label">Estado Actual</label>
                        <select class="form-select" id="estado" name="estado">
                            <option value="pendiente" {{ $pedido->estado == 'pendiente' ? 'selected' : '' }}>Pendiente</option>
                            <option value="en_preparacion" {{ $pedido->estado == 'en_preparacion' ? 'selected' : '' }}>En Preparación</option>
                            <option value="listo" {{ $pedido->estado == 'listo' ? 'selected' : '' }}>Listo</option>
                            <option value="entregado" {{ $pedido->estado == 'entregado' ? 'selected' : '' }}>Entregado</option>
                            <option value="cancelado" {{ $pedido->estado == 'cancelado' ? 'selected' : '' }}>Cancelado</option>
                        </select>
                    </div>

                    <button type="submit" class="btn btn-primary w-100">Actualizar Estado</button>
                </form>

                <a href="{{ route('admin.pedidos.index') }}" class="btn btn-secondary w-100 mt-2">Volver a la Lista</a>
            </div>
        </div>
    </div>
</div>
@endsection