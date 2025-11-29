@extends('layouts.cliente')

@section('title', 'Pedir Comida')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 style="color: var(--color-primary);">Pedir Comida</h1>
        
        <a href="{{ route('cliente.carrito') }}" class="btn btn-primary position-relative">
            🛒 Ver Carrito
            @if($totalItems > 0)
                <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                    {{ $totalItems }}
                </span>
            @endif
        </a>
    </div>

    

    <div class="row g-4">
        @forelse($productos as $producto)
            <div class="col-md-4">
                <div class="card h-100 shadow-sm">
                    @if($producto->imagen)
                        <img src="{{ asset('storage/' . $producto->imagen) }}" class="card-img-top" alt="{{ $producto->nombre }}" style="height: 200px; object-fit: cover;">
                    @else
                        <div class="bg-secondary text-white d-flex align-items-center justify-content-center" style="height: 200px;">
                            <span>Sin imagen</span>
                        </div>
                    @endif
                    
                    <div class="card-body d-flex flex-column">
                        <h5 class="card-title">{{ $producto->nombre }}</h5>
                        <span class="badge bg-info mb-2 align-self-start">{{ ucfirst($producto->categoria) }}</span>
                        
                        @if($producto->descripcion)
                            <p class="card-text text-muted small flex-grow-1">{{ Str::limit($producto->descripcion, 80) }}</p>
                        @endif
                        
                        <div class="mt-auto">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <span class="h4 mb-0 text-success">S/.{{ number_format($producto->precio, 2) }}</span>
                                <small class="text-muted">Stock: {{ $producto->stock }}</small>
                            </div>
                            
                            <form action="{{ route('cliente.carrito.agregar', $producto) }}" method="POST">
                                @csrf
                                <div class="input-group">
                                    <input type="number" name="cantidad" class="form-control" value="1" min="1" max="{{ $producto->stock }}" required>
                                    <button type="submit" class="btn btn-success">
                                        Agregar
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12">
                <div class="alert alert-warning text-center">
                    <h5>No hay productos disponibles en este momento.</h5>
                    <p class="mb-0">Vuelve pronto para ver nuestras deliciosas opciones.</p>
                </div>
            </div>
        @endforelse
    </div>
</div>
@endsection