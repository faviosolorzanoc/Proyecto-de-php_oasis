@extends('layouts.cliente')

@section('title', 'Hacer Pedido')

@section('content')
<h1 class="mb-4" style="color: var(--color-primary);">Realizar Pedido</h1>

<div class="row">
    <div class="col-md-8">
        <div class="card shadow">
            <div class="card-body">
                <h5 class="card-title">Selecciona tus productos</h5>
                
                <div class="row" id="productos-list">
                    @forelse($productos as $producto)
                        <div class="col-md-6 mb-3">
                            <div class="card">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between align-items-start">
                                        <div>
                                            <h6>{{ $producto->nombre }}</h6>
                                            <span class="badge bg-info">{{ ucfirst($producto->categoria) }}</span>
                                            <p class="mb-1 mt-2"><strong>${{ number_format($producto->precio, 2) }}</strong></p>
                                            <small class="text-muted">Stock: {{ $producto->stock }}</small>
                                        </div>
                                        <div>
                                            <input type="number" 
                                                   class="form-control form-control-sm producto-cantidad" 
                                                   data-id="{{ $producto->id }}"
                                                   data-nombre="{{ $producto->nombre }}"
                                                   data-precio="{{ $producto->precio }}"
                                                   min="0" 
                                                   max="{{ $producto->stock }}" 
                                                   value="0"
                                                   style="width: 70px;">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="col-12">
                            <div class="alert alert-warning">No hay productos disponibles para pedir.</div>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card shadow sticky-top" style="top: 20px;">
            <div class="card-body">
                <h5 class="card-title">Resumen del Pedido</h5>
                
                <form action="{{ route('cliente.pedir.store') }}" method="POST" id="form-pedido">
                    @csrf
                    
                    <div class="mb-3">
                        <label for="mesa_id" class="form-label">Mesa (Opcional)</label>
                        <select class="form-select" id="mesa_id" name="mesa_id">
                            <option value="">Para llevar</option>
                            @foreach($mesas as $mesa)
                                <option value="{{ $mesa->id }}">Mesa {{ $mesa->numero }} ({{ $mesa->capacidad }} personas)</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="observaciones" class="form-label">Observaciones</label>
                        <textarea class="form-control" id="observaciones" name="observaciones" rows="2"></textarea>
                    </div>

                    <div id="productos-seleccionados" class="mb-3">
                        <h6>Productos:</h6>
                        <div id="lista-productos" class="small">
                            <p class="text-muted">No has seleccionado productos</p>
                        </div>
                    </div>

                    <div class="border-top pt-3 mb-3">
                        <div class="d-flex justify-content-between">
                            <strong>Total:</strong>
                            <strong id="total-pedido" style="color: var(--color-primary);">$0.00</strong>
                        </div>
                    </div>

                    <button type="submit" class="btn btn-success w-100" id="btn-realizar-pedido" disabled>
                        Realizar Pedido
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const cantidadInputs = document.querySelectorAll('.producto-cantidad');
    const listaProductos = document.getElementById('lista-productos');
    const totalElement = document.getElementById('total-pedido');
    const btnRealizarPedido = document.getElementById('btn-realizar-pedido');
    const formPedido = document.getElementById('form-pedido');
    
    let productosSeleccionados = [];

    cantidadInputs.forEach(input => {
        input.addEventListener('input', actualizarResumen);
    });

    function actualizarResumen() {
        productosSeleccionados = [];
        let total = 0;

        cantidadInputs.forEach(input => {
            const cantidad = parseInt(input.value) || 0;
            if (cantidad > 0) {
                const producto = {
                    id: input.dataset.id,
                    nombre: input.dataset.nombre,
                    precio: parseFloat(input.dataset.precio),
                    cantidad: cantidad
                };
                productosSeleccionados.push(producto);
                total += producto.precio * producto.cantidad;
            }
        });

        // Actualizar lista de productos
        if (productosSeleccionados.length === 0) {
            listaProductos.innerHTML = '<p class="text-muted">No has seleccionado productos</p>';
            btnRealizarPedido.disabled = true;
        } else {
            let html = '';
            productosSeleccionados.forEach(p => {
                html += `<div class="d-flex justify-content-between mb-1">
                    <span>${p.cantidad}x ${p.nombre}</span>
                    <span>$${(p.precio * p.cantidad).toFixed(2)}</span>
                </div>`;
            });
            listaProductos.innerHTML = html;
            btnRealizarPedido.disabled = false;
        }

        // Actualizar total
        totalElement.textContent = '$' + total.toFixed(2);
    }

    formPedido.addEventListener('submit', function(e) {
        e.preventDefault();
        
        if (productosSeleccionados.length === 0) {
            alert('Debes seleccionar al menos un producto');
            return;
        }

        // Agregar productos al formulario
        productosSeleccionados.forEach((producto, index) => {
            const inputId = document.createElement('input');
            inputId.type = 'hidden';
            inputId.name = `productos[${index}][id]`;
            inputId.value = producto.id;
            formPedido.appendChild(inputId);

            const inputCantidad = document.createElement('input');
            inputCantidad.type = 'hidden';
            inputCantidad.name = `productos[${index}][cantidad]`;
            inputCantidad.value = producto.cantidad;
            formPedido.appendChild(inputCantidad);
        });

        formPedido.submit();
    });
});
</script>
@endsection