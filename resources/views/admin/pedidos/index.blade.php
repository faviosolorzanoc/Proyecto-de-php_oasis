@extends('layouts.admin')

@section('title', 'Gestión de Pedidos')

@section('content')
<div class="mb-4">
    <h1 class="h2">Gestión de Pedidos</h1>
</div>

<div class="card">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Cliente</th>
                        <th>Mesa</th>
                        <th>Total</th>
                        <th>Estado</th>
                        <th>Fecha</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($pedidos as $pedido)
                        <tr>
                            <td>{{ $pedido->id }}</td>
                            <td>{{ $pedido->user->name }}</td>
                            <td>{{ $pedido->mesa ? 'Mesa ' . $pedido->mesa->numero : 'Para llevar' }}</td>
                            <td>${{ number_format($pedido->total, 2) }}</td>
                            <td>
                                <span class="badge bg-{{ 
                                    $pedido->estado == 'pendiente' ? 'warning' : 
                                    ($pedido->estado == 'en_preparacion' ? 'info' : 
                                    ($pedido->estado == 'listo' ? 'primary' : 
                                    ($pedido->estado == 'entregado' ? 'success' : 'danger'))) 
                                }}">
                                    {{ ucfirst(str_replace('_', ' ', $pedido->estado)) }}
                                </span>
                            </td>
                            <td>{{ $pedido->created_at->format('d/m/Y H:i') }}</td>
                            <td>
                                <a href="{{ route('admin.pedidos.show', $pedido) }}" class="btn btn-sm btn-info">Ver Detalle</a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center">No hay pedidos registrados</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <div class="mt-3">
            {{ $pedidos->links() }}
        </div>
    </div>
</div>
@endsection