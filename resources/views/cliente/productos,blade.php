@extends('layouts.cliente')

@section('title', 'Productos')

@section('content')
<h1 class="mb-4" style="color: var(--color-primary);">Nuestros Productos</h1>

<!-- Filtros por categoría -->
<div class="mb-4">
    <button class="btn btn-outline-primary active" data-filter="todos">Todos</button>
    <button class="btn btn-outline-primary" data-filter="comida">Comida</button>
    <button class="btn btn-outline-primary" data-filter="bebida">Bebida</button>
    <button class="btn btn-outline-primary" data-filter="snack">Snacks</button>
</div>

<div class="row" id="productos-container">
    @forelse($productos as $producto)
        <div class="col-md-3 mb-4 producto-item" data-categoria="{{ $producto->categoria }}">
            <div class="card h-100 shadow">
                @if($producto->imagen)
                    <img src="{{ $producto->imagen }}" class="card-img-top" alt="{{ $producto->nombre }}" style="height: 150px; object-fit: cover;">
                @else
                    <div class="card-img-top bg-secondary d-flex align-items-center justify-content-center" style="height: 150px;">
                        <span class="text-white fs-1">
                            @if($producto->categoria == 'comida') 🍽️
                            @elseif($producto->categoria == 'bebida') 🥤
                            @else 🍿
                            @endif
                        </span>
                    </div>
                @endif
                <div class="card-body">
                    <span class="badge bg-info mb-2">{{ ucfirst($producto->categoria) }}</span>
                    <h6 class="card-title" style="color: var(--color-primary);">{{ $producto->nombre }}</h6>
                    <p class="card-text small">{{ Str::limit($producto->descripcion, 60) }}</p>
                    <div class="d-flex justify-content-between align-items-center">
                        <span class="h6 mb-0" style="color: var(--color-secondary);">S/.{{ number_format($producto->precio, 2) }}</span>
                        <small class="text-muted">Stock: {{ $producto->stock }}</small>
                    </div>
                </div>
            </div>
        </div>
    @empty
        <div class="col-12">
            <div class="alert alert-info">No hay productos disponibles en este momento.</div>
        </div>
    @endforelse
</div>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const filterButtons = document.querySelectorAll('[data-filter]');
    const productos = document.querySelectorAll('.producto-item');
    
    filterButtons.forEach(button => {
        button.addEventListener('click', function() {
            // Remover clase active de todos los botones
            filterButtons.forEach(btn => btn.classList.remove('active'));
            // Agregar clase active al botón clickeado
            this.classList.add('active');
            
            const filter = this.getAttribute('data-filter');
            
            productos.forEach(producto => {
                if (filter === 'todos' || producto.getAttribute('data-categoria') === filter) {
                    producto.style.display = 'block';
                } else {
                    producto.style.display = 'none';
                }
            });
        });
    });
});
</script>
@endsection