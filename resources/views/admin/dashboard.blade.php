@extends('layouts.admin')

@section('title', 'Dashboard')

@section('content')
<div class="fade-in">
    <h1 class="h2 mb-4">📊 Dashboard</h1>

    <div class="row g-4 mb-4">
        <div class="col-md-3">
            <div class="dashboard-card" style="background: linear-gradient(135deg, #005187, #4d82bc);">
                <h5>Total Clientes</h5>
                <h2>{{ $totalClientes }}</h2>
            </div>
        </div>

        <div class="col-md-3">
            <div class="dashboard-card" style="background: linear-gradient(135deg, #28a745, #20c997);">
                <h5>Total Servicios</h5>
                <h2>{{ $totalServicios }}</h2>
            </div>
        </div>

        <div class="col-md-3">
            <div class="dashboard-card" style="background: linear-gradient(135deg, #17a2b8, #138496);">
                <h5>Total Productos</h5>
                <h2>{{ $totalProductos }}</h2>
            </div>
        </div>

        <div class="col-md-3">
            <div class="dashboard-card" style="background: linear-gradient(135deg, #ffc107, #ff9800);">
                <h5>Pedidos Pendientes</h5>
                <h2>{{ $pedidosPendientes }}</h2>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <h5 class="mb-0">Pedidos Recientes</h5>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Cliente</th>
                            <th>Total</th>
                            <th>Estado</th>
                            <th>Fecha</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($pedidosRecientes as $pedido)
                            <tr>
                                <td><strong>#{{ $pedido->id }}</strong></td>
                                <td>{{ $pedido->user->name }}</td>
                                <td><strong>${{ number_format($pedido->total, 2) }}</strong></td>
                                <td>
                                    <span class="badge bg-{{ $pedido->estado == 'pendiente' ? 'warning' : ($pedido->estado == 'entregado' ? 'success' : 'info') }}">
                                        {{ ucfirst($pedido->estado) }}
                                    </span>
                                </td>
                                <td>{{ $pedido->created_at->format('d/m/Y H:i') }}</td>
                                <td>
                                    <a href="{{ route('admin.pedidos.show', $pedido) }}" class="btn btn-sm btn-info">Ver</a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center text-muted py-4">No hay pedidos recientes</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection