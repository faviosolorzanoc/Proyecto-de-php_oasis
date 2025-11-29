@extends('layouts.cliente')

@section('title', 'Mis Pedidos')

@section('content')
<h1 class="mb-4" style="color: var(--color-primary);">Mis Pedidos</h1>

<div class="row">
    @forelse($pedidos as $pedido)
        <div class="col-md-12 mb-4">
            <div class="card shadow">
                <div class="card-header d-flex justify-content-between align-items-center" style="background-color: var(--color-light);">
                    <div>
                        <h5 class="mb-0">Pedido #{{ $pedido->id }}</h5>
                        <small class="text-muted">{{ $pedido->created_at->format('d/m/Y H:i') }}</small>
                    </div>
                    <span class="badge bg-{{ 
                        $pedido->estado == 'pendiente' ? 'warning' : 
                        ($pedido->estado == 'en_preparacion' ? 'info' : 
                        ($pedido->estado == 'listo' ? 'primary' : 
                        ($pedido->estado == 'entregado' ? 'success' : 'danger'))) 
                    }} fs-6">
                        {{ ucfirst(str_replace('_', ' ', $pedido->estado)) }}
                    </span>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-8">
                            <h6>Productos:</h6>
                            <table class="table table-sm">
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
                                            <td>S/.{{ number_format($detalle->precio_unitario, 2) }}</td>
                                            <td>S/.{{ number_format($detalle->subtotal, 2) }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div class="col-md-4">
                            <div class="card bg-light">
                                <div class="card-body">
                                    <p class="mb-2"><strong>Mesa:</strong> {{ $pedido->mesa ? 'Mesa ' . $pedido->mesa->numero : 'Para llevar' }}</p>
                                    @if($pedido->observaciones)
                                        <p class="mb-2"><strong>Observaciones:</strong><br>{{ $pedido->observaciones }}</p>
                                    @endif
                                    <hr>
                                    <div class="d-flex justify-content-between">
                                        <strong>Total:</strong>
                                        <strong style="color: var(--color-primary); font-size: 1.2rem;">S/.{{ number_format($pedido->total, 2) }}</strong>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @empty
        <div class="col-12">
            <div class="alert alert-info">
                No has realizado ningún pedido aún.
                <a href="{{ route('cliente.pedir') }}" class="alert-link">¡Haz tu primer pedido aquí!</a>
            </div>
        </div>
    @endforelse
</div>

@if($pedidos->hasPages())
    <div class="mt-4">
        {{ $pedidos->links() }}
    </div>
@endif
@endsection