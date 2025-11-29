@extends('layouts.cliente')

@section('title', 'Carrito de Compras')

@section('content')
<div class="container-fluid">
    <h1 class="mb-4" style="color: var(--color-primary);">🛒 Carrito de Compras</h1>

   

    @if(empty($carrito))
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card shadow text-center">
                    <div class="card-body p-5">
                        <h3 class="mb-3">Tu carrito está vacío</h3>
                        <p class="text-muted mb-4">¡Agrega algunos productos deliciosos!</p>
                        <a href="{{ route('cliente.pedir') }}" class="btn btn-primary btn-lg">
                            Ver Productos
                        </a>
                    </div>
                </div>
            </div>
        </div>
    @else
        {{-- Alerta de reserva activa --}}
        @if($reservaActiva)
        <div class="alert alert-info alert-dismissible fade show" role="alert">
            <h5 class="alert-heading mb-2">🎉 Tienes una reserva activa</h5>
            <p class="mb-0">
                <strong>Reserva #{{ $reservaActiva->id }}</strong> - {{ \Carbon\Carbon::parse($reservaActiva->fecha_evento)->format('d/m/Y') }}<br>
                {{ $reservaActiva->espacio->nombre }} - {{ $reservaActiva->num_personas }} personas
            </p>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        @endif

        <div class="row">
            <div class="col-lg-8 mb-4">
                <div class="card shadow">
                    <div class="card-header bg-white">
                        <div class="d-flex justify-content-between align-items-center">
                            <h5 class="mb-0">Productos en tu carrito</h5>
                            <form action="{{ route('cliente.carrito.vaciar') }}" method="POST" onsubmit="return confirm('¿Estás seguro de vaciar el carrito?')">
                                @csrf
                                <button type="submit" class="btn btn-sm btn-outline-danger">
                                    🗑️ Vaciar carrito
                                </button>
                            </form>
                        </div>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th>Producto</th>
                                        <th class="text-center">Precio</th>
                                        <th class="text-center">Cantidad</th>
                                        <th class="text-center">Subtotal</th>
                                        <th class="text-center">Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($carrito as $id => $item)
                                    <tr>
                                        <td>
                                            <strong>{{ $item['nombre'] }}</strong>
                                            <br><small class="text-muted">Stock disponible: {{ $item['stock'] }}</small>
                                        </td>
                                        <td class="text-center align-middle">
                                            <span class="text-success">S/.{{ number_format($item['precio'], 2) }}</span>
                                        </td>
                                        <td class="text-center align-middle">
                                            <form action="{{ route('cliente.carrito.actualizar', $id) }}" method="POST" class="d-inline-flex align-items-center gap-2">
                                                @csrf
                                                <input type="number" name="cantidad" value="{{ $item['cantidad'] }}" min="1" max="{{ $item['stock'] }}" class="form-control form-control-sm text-center" style="width: 70px;">
                                                <button type="submit" class="btn btn-sm btn-primary">✓</button>
                                            </form>
                                        </td>
                                        <td class="text-center align-middle">
                                            <strong class="text-success">S/.{{ number_format($item['precio'] * $item['cantidad'], 2) }}</strong>
                                        </td>
                                        <td class="text-center align-middle">
                                            <form action="{{ route('cliente.carrito.eliminar', $id) }}" method="POST" class="d-inline">
                                                @csrf
                                                <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('¿Eliminar este producto?')">
                                                    🗑️
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                
                <div class="mt-3">
                    <a href="{{ route('cliente.pedir') }}" class="btn btn-outline-secondary">
                        ← Seguir comprando
                    </a>
                </div>
            </div>

            <div class="col-lg-4">
                <div class="card shadow sticky-top" style="top: 20px;">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0">Confirmar Pedido</h5>
                    </div>
                    <div class="card-body">
                        {{-- PASO 1: Seleccionar método de pago --}}
                        @if(!session('metodo_pago_seleccionado'))
                        <form action="{{ route('cliente.carrito.seleccionar-metodo') }}" method="POST">
                            @csrf
                            
                            @if($reservaActiva)
                            <div class="mb-3">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="asociar_reserva" name="reserva_id" value="{{ $reservaActiva->id }}">
                                    <label class="form-check-label" for="asociar_reserva">
                                        <strong>Asociar a Reserva #{{ $reservaActiva->id }}</strong>
                                    </label>
                                </div>
                                <small class="text-muted">Para llevar un control de tu consumo</small>
                            </div>
                            <hr>
                            @endif
                            
                            <div class="mb-3">
                                <label for="mesa_id" class="form-label">Mesa (Opcional)</label>
                                <select class="form-select" id="mesa_id" name="mesa_id">
                                    <option value="">Para llevar</option>
                                    @foreach($mesas as $mesa)
                                        <option value="{{ $mesa->id }}">Mesa {{ $mesa->numero }} (Cap: {{ $mesa->capacidad }})</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="mb-3">
                                <label for="observaciones" class="form-label">Observaciones</label>
                                <textarea class="form-control" id="observaciones" name="observaciones" rows="2" placeholder="Ej: Sin cebolla, extra picante"></textarea>
                            </div>

                            <hr>

                            <div class="mb-3">
                                <label class="form-label fw-bold">Selecciona Método de Pago *</label>
                                
                                <div class="d-grid gap-2">
                                    <button type="submit" name="metodo_pago" value="efectivo" class="btn btn-outline-success btn-lg text-start">
                                        <span class="fs-4">💵</span>
                                        <strong class="ms-2">Efectivo</strong>
                                        <small class="d-block ms-5 text-muted">Paga al recoger</small>
                                    </button>

                                    <button type="submit" name="metodo_pago" value="yape" class="btn btn-outline-primary btn-lg text-start">
                                        <span class="fs-4">📱</span>
                                        <strong class="ms-2">Yape</strong>
                                        <small class="d-block ms-5 text-muted">Pago con QR o código</small>
                                    </button>

                                    <button type="submit" name="metodo_pago" value="tarjeta" class="btn btn-outline-info btn-lg text-start">
                                        <span class="fs-4">💳</span>
                                        <strong class="ms-2">Tarjeta</strong>
                                        <small class="d-block ms-5 text-muted">Débito o Crédito</small>
                                    </button>
                                </div>
                            </div>

                            <div class="border-top pt-3">
                                <div class="d-flex justify-content-between mb-2">
                                    <span>Subtotal:</span>
                                    <strong>S/.{{ number_format($total, 2) }}</strong>
                                </div>
                                <div class="d-flex justify-content-between">
                                    <h5 class="mb-0">TOTAL:</h5>
                                    <h4 class="mb-0 text-success">S/.{{ number_format($total, 2) }}</h4>
                                </div>
                            </div>
                        </form>

                        {{-- PASO 2: Formulario según método seleccionado --}}
                        @else
                            <div class="alert alert-info mb-3">
                                <strong>Método seleccionado:</strong> 
                                @if(session('metodo_pago_seleccionado') === 'efectivo')
                                    💵 Efectivo
                                @elseif(session('metodo_pago_seleccionado') === 'yape')
                                    📱 Yape
                                @else
                                    💳 Tarjeta
                                @endif
                            </div>

                            {{-- FORMULARIO YAPE --}}
                            @if(session('metodo_pago_seleccionado') === 'yape')
                            <div class="text-center mb-3">
                                <h6 class="mb-3">Escanea el QR o Yapea al número:</h6>
                                
                                {{-- QR Simulado --}}
                                <div class="bg-white p-3 rounded shadow-sm mb-3" style="display: inline-block;">
                                    <div style="width: 200px; height: 200px; background: linear-gradient(135deg, #722F87 0%, #4A1D5E 100%); border-radius: 10px; display: flex; align-items: center; justify-content: center; color: white; font-size: 3rem; font-weight: bold;">
                                        QR
                                    </div>
                                    <p class="mt-2 mb-0"><strong>999 888 777</strong></p>
                                    <small class="text-muted">Monto: S/.{{ number_format($total, 2) }}</small>
                                </div>

                                <form action="{{ route('cliente.pedido.confirmar') }}" method="POST">
                                    @csrf
                                    <div class="mb-3 text-start">
                                        <label for="yape_codigo" class="form-label">Código de Operación *</label>
                                        <input type="text" class="form-control form-control-lg text-center" 
                                            id="yape_codigo" name="yape_codigo" 
                                            placeholder="000000" maxlength="6" 
                                            pattern="[0-9]{6}" required
                                            style="letter-spacing: 0.5rem; font-size: 1.5rem; font-weight: bold;">
                                        <small class="form-text text-muted">Ingresa los 6 dígitos del código Yape</small>
                                    </div>

                                    <div class="mb-3 text-start">
                                        <label for="yape_telefono" class="form-label">Tu número Yape *</label>
                                        <input type="tel" class="form-control" 
                                            id="yape_telefono" name="yape_telefono" 
                                            placeholder="999 888 777" 
                                            pattern="[0-9]{9}" required>
                                    </div>

                                    <div class="border-top pt-3 mb-3 text-start">
                                        <div class="d-flex justify-content-between">
                                            <h5>TOTAL A PAGAR:</h5>
                                            <h4 class="text-success">S/.{{ number_format($total, 2) }}</h4>
                                        </div>
                                    </div>

                                    <button type="submit" class="btn btn-success btn-lg w-100 mb-2">
                                        ✓ Confirmar Pago Yape
                                    </button>
                                </form>
                            </div>

                            {{-- FORMULARIO TARJETA --}}
                            @elseif(session('metodo_pago_seleccionado') === 'tarjeta')
                            <form action="{{ route('cliente.pedido.confirmar') }}" method="POST">
                                @csrf
                                
                                <div class="mb-3">
                                    <label for="tarjeta_numero" class="form-label">Número de Tarjeta *</label>
                                    <input type="text" class="form-control" id="tarjeta_numero" name="tarjeta_numero" 
                                        placeholder="1234 5678 9012 3456" maxlength="19" required>
                                </div>
                                
                                <div class="mb-3">
                                    <label for="tarjeta_nombre" class="form-label">Nombre del Titular *</label>
                                    <input type="text" class="form-control" id="tarjeta_nombre" name="tarjeta_nombre" 
                                        placeholder="JUAN PEREZ" style="text-transform: uppercase;" required>
                                </div>
                                
                                <div class="row">
                                    <div class="col-6 mb-3">
                                        <label for="tarjeta_expiracion" class="form-label">Vencimiento *</label>
                                        <input type="text" class="form-control" id="tarjeta_expiracion" name="tarjeta_expiracion" 
                                            placeholder="MM/AA" maxlength="5" required>
                                    </div>
                                    
                                    <div class="col-6 mb-3">
                                        <label for="tarjeta_cvv" class="form-label">CVV *</label>
                                        <input type="text" class="form-control" id="tarjeta_cvv" name="tarjeta_cvv" 
                                            placeholder="123" maxlength="4" required>
                                    </div>
                                </div>

                                <div class="alert alert-warning small">
                                    🔒 Simulación de pago - No se realizarán cargos reales
                                </div>

                                <div class="border-top pt-3 mb-3">
                                    <div class="d-flex justify-content-between">
                                        <h5>TOTAL A PAGAR:</h5>
                                        <h4 class="text-success">S/.{{ number_format($total, 2) }}</h4>
                                    </div>
                                </div>

                                <button type="submit" class="btn btn-success btn-lg w-100 mb-2">
                                    ✓ Procesar Pago
                                </button>
                            </form>

                            {{-- EFECTIVO (confirma directo) --}}
                            @else
                            <form action="{{ route('cliente.pedido.confirmar') }}" method="POST">
                                @csrf
                                
                                <div class="alert alert-info">
                                    <h6>💵 Pago en Efectivo</h6>
                                    <p class="mb-0">Pagarás al recoger tu pedido</p>
                                </div>

                                <div class="border-top pt-3 mb-3">
                                    <div class="d-flex justify-content-between">
                                        <h5>TOTAL:</h5>
                                        <h4 class="text-success">S/.{{ number_format($total, 2) }}</h4>
                                    </div>
                                </div>

                                <button type="submit" class="btn btn-success btn-lg w-100 mb-2">
                                    ✓ Confirmar Pedido
                                </button>
                            </form>
                            @endif

                            {{-- Botón para cambiar método --}}
                            <form action="{{ route('cliente.carrito.cancelar-metodo') }}" method="POST">
                                @csrf
                                <button type="submit" class="btn btn-outline-secondary w-100">
                                    ← Cambiar método de pago
                                </button>
                            </form>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
@endsection