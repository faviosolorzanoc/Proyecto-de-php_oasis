@extends('layouts.cliente')

@section('title', 'Servicios')

@section('content')
<h1 class="mb-4" style="color: var(--color-primary);">Nuestros Servicios</h1>

<div class="row">
    @forelse($servicios as $servicio)
        <div class="col-md-4 mb-4">
            <div class="card h-100 shadow">
                @if($servicio->imagen)
                    <img src="{{ $servicio->imagen }}" class="card-img-top" alt="{{ $servicio->nombre }}" style="height: 200px; object-fit: cover;">
                @else
                    <div class="card-img-top bg-secondary d-flex align-items-center justify-content-center" style="height: 200px;">
                        <span class="text-white fs-1">🎯</span>
                    </div>
                @endif
                <div class="card-body">
                    <h5 class="card-title" style="color: var(--color-primary);">{{ $servicio->nombre }}</h5>
                    <p class="card-text">{{ $servicio->descripcion ?? 'Sin descripción' }}</p>
                    <div class="d-flex justify-content-between align-items-center">
                        <span class="h5 mb-0" style="color: var(--color-secondary);">${{ number_format($servicio->precio, 2) }}</span>
                        <span class="badge bg-success">Disponible</span>
                    </div>
                </div>
            </div>
        </div>
    @empty
        <div class="col-12">
            <div class="alert alert-info">No hay servicios disponibles en este momento.</div>
        </div>
    @endforelse
</div>
@endsection