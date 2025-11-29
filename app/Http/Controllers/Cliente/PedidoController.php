<?php

namespace App\Http\Controllers\Cliente;

use App\Http\Controllers\Controller;
use App\Models\Mesa;
use App\Models\Pedido;
use App\Models\Producto;
use App\Models\Reserva;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PedidoController extends Controller
{
    // Mostrar productos disponibles
    public function crear()
    {
        // Forzar recarga desde la BD (sin caché)
        $productos = Producto::where('disponible', true)
            ->where('stock', '>', 0)
            ->get()
            ->fresh(); // ← Esto fuerza recarga
        
        $carrito = session()->get('carrito', []);
        $totalItems = array_sum(array_column($carrito, 'cantidad'));
        
        return view('cliente.pedir', compact('productos', 'totalItems'));
    }

    // Agregar producto al carrito
    public function agregarAlCarrito(Request $request, Producto $producto)
    {
        $request->validate([
            'cantidad' => 'required|integer|min:1',
        ]);

        // ✅ Verificar stock REAL en la BD
        $producto->refresh(); // Refrescar desde BD
        
        if ($producto->stock < $request->cantidad) {
            return back()->with('error', "❌ Stock insuficiente. Solo quedan {$producto->stock} unidades disponibles.");
        }

        $carrito = session()->get('carrito', []);

        // Si el producto ya existe en el carrito
        if (isset($carrito[$producto->id])) {
            $nuevaCantidad = $carrito[$producto->id]['cantidad'] + $request->cantidad;
            
            // Validar que no exceda el stock REAL
            if ($nuevaCantidad > $producto->stock) {
                return back()->with('error', "❌ Stock insuficiente. Solo hay {$producto->stock} disponibles y ya tienes {$carrito[$producto->id]['cantidad']} en el carrito.");
            }
            
            $carrito[$producto->id]['cantidad'] = $nuevaCantidad;
            $carrito[$producto->id]['stock'] = $producto->stock; // Actualizar stock
        } else {
            // Agregar nuevo producto
            $carrito[$producto->id] = [
                'nombre' => $producto->nombre,
                'precio' => $producto->precio,
                'cantidad' => $request->cantidad,
                'stock' => $producto->stock,
                'imagen' => $producto->imagen,
            ];
        }

        session()->put('carrito', $carrito);

        return back()->with('success', "✅ {$producto->nombre} agregado al carrito");
    }

    // Ver carrito
    public function verCarrito()
    {
        $carrito = session()->get('carrito', []);
        $mesas = Mesa::where('estado', 'disponible')->get();
        
        // Verificar si tiene reserva activa
        $reservaActiva = Reserva::where('user_id', auth()->id())
            ->where('estado', 'confirmada')
            ->where('fecha_evento', '>=', now()->toDateString())
            ->first();
        
        //  ACTUALIZAR el stock en el carrito desde la BD
        foreach ($carrito as $productoId => $item) {
            $producto = Producto::find($productoId);
            if ($producto) {
                $carrito[$productoId]['stock'] = $producto->stock; // Actualizar stock real
            }
        }
        session()->put('carrito', $carrito);
        
        $total = 0;
        foreach ($carrito as $item) {
            $total += $item['precio'] * $item['cantidad'];
        }
        
        return view('cliente.carrito', compact('carrito', 'total', 'mesas', 'reservaActiva'));
    }

    // Actualizar cantidad en carrito
    public function actualizarCarrito(Request $request, $productoId)
    {
        $request->validate([
            'cantidad' => 'required|integer|min:1',
        ]);

        $carrito = session()->get('carrito', []);

        if (isset($carrito[$productoId])) {
            // Validar stock
            if ($request->cantidad > $carrito[$productoId]['stock']) {
                return back()->with('error', "Stock insuficiente. Disponible: {$carrito[$productoId]['stock']}");
            }
            
            $carrito[$productoId]['cantidad'] = $request->cantidad;
            session()->put('carrito', $carrito);
            
            return back()->with('success', 'Cantidad actualizada');
        }

        return back()->with('error', 'Producto no encontrado en el carrito');
    }

    // Eliminar del carrito
    public function eliminarDelCarrito($productoId)
    {
        $carrito = session()->get('carrito', []);

        if (isset($carrito[$productoId])) {
            unset($carrito[$productoId]);
            session()->put('carrito', $carrito);
            
            return back()->with('success', 'Producto eliminado del carrito');
        }

        return back()->with('error', 'Producto no encontrado');
    }

    // Vaciar carrito
    public function vaciarCarrito()
    {
        session()->forget('carrito');
        return back()->with('success', 'Carrito vaciado');
    }

    public function store(Request $request)
    {
        $carrito = session()->get('carrito', []);

        if (empty($carrito)) {
            return redirect()->route('cliente.pedir')->with('error', 'El carrito está vacío');
        }

        $metodoPago = session('metodo_pago_seleccionado');
        
        if (!$metodoPago) {
            return redirect()->route('cliente.carrito')->with('error', 'Selecciona un método de pago');
        }

        // Validaciones según método
        $rules = [];

        if ($metodoPago === 'yape') {
            $rules['yape_codigo'] = 'required|digits:6';
            $rules['yape_telefono'] = 'required|digits:9';
        } elseif ($metodoPago === 'tarjeta') {
            $rules['tarjeta_numero'] = 'required|string|min:13|max:19';
            $rules['tarjeta_nombre'] = 'required|string|max:100';
            $rules['tarjeta_expiracion'] = 'required|size:5';
            $rules['tarjeta_cvv'] = 'required|digits_between:3,4';
        }

        // Solo validar si hay reglas
        if (!empty($rules)) {
            $request->validate($rules, [
                'yape_codigo.digits' => 'El código debe tener 6 dígitos',
                'yape_telefono.digits' => 'El teléfono debe tener 9 dígitos',
                'tarjeta_numero.required' => 'El número de tarjeta es obligatorio',
                'tarjeta_nombre.required' => 'El nombre del titular es obligatorio',
                'tarjeta_expiracion.required' => 'La fecha de expiración es obligatoria',
                'tarjeta_expiracion.size' => 'Formato inválido (MM/AA)',
                'tarjeta_cvv.required' => 'El CVV es obligatorio',
                'tarjeta_cvv.digits_between' => 'El CVV debe tener 3 o 4 dígitos',
            ]);
        }

        // Validaciones simuladas
        if ($metodoPago === 'yape' && $request->yape_codigo === '000000') {
            return back()->with('error', '❌ Código de operación inválido');
        }

        if ($metodoPago === 'tarjeta') {
            // Validar formato MM/YY
            if (!preg_match('/^\d{2}\/\d{2}$/', $request->tarjeta_expiracion)) {
                return back()->with('error', '❌ Formato de fecha inválido. Use MM/AA');
            }
            
            list($mes, $anio) = explode('/', $request->tarjeta_expiracion);
            $fechaExpiracion = "20" . $anio . "-" . $mes . "-01";
            
            if (strtotime($fechaExpiracion) < strtotime(date('Y-m-01'))) {
                return back()->with('error', '❌ Tarjeta vencida');
            }
        }

        DB::beginTransaction();

        try {
            $total = 0;

            // Verificar stock
            foreach ($carrito as $productoId => $item) {
                $producto = Producto::findOrFail($productoId);
                
                if ($producto->stock < $item['cantidad']) {
                    DB::rollBack();
                    return back()->with('error', "❌ Stock insuficiente para {$producto->nombre}");
                }
                
                $total += $item['precio'] * $item['cantidad'];
            }

            // Recuperar datos de sesión
            $reservaId = session('reserva_id_temp');
            $mesaId = session('mesa_id_temp');
            $observaciones = session('observaciones_temp');

            // Crear pedido
            $pedido = Pedido::create([
                'user_id' => auth()->id(),
                'reserva_id' => $reservaId,
                'mesa_id' => $mesaId,
                'total' => $total,
                'metodo_pago' => $metodoPago,
                'observaciones' => $observaciones,
            ]);

            // Crear detalles y actualizar stock
            foreach ($carrito as $productoId => $item) {
                $pedido->detalles()->create([
                    'producto_id' => $productoId,
                    'cantidad' => $item['cantidad'],
                    'precio_unitario' => $item['precio'],
                    'subtotal' => $item['precio'] * $item['cantidad'],
                ]);
                
                $producto = Producto::find($productoId);
                $producto->decrement('stock', $item['cantidad']);
            }

            // Actualizar mesa si existe
            if ($mesaId) {
                Mesa::find($mesaId)->update(['estado' => 'ocupada']);
            }

            // Limpiar sesiones
            session()->forget(['carrito', 'metodo_pago_seleccionado', 'reserva_id_temp', 'mesa_id_temp', 'observaciones_temp']);

            DB::commit();

            // Mensaje según método de pago
            $mensaje = '✅ Pedido realizado exitosamente.';
            
            if ($metodoPago === 'efectivo') {
                $mensaje = '✅ Pedido confirmado. Paga en efectivo al recoger.';
            } elseif ($metodoPago === 'yape') {
                $mensaje = '✅ Pago Yape registrado. Pedido confirmado.';
            } elseif ($metodoPago === 'tarjeta') {
                $mensaje = '✅ Pago procesado exitosamente.';
            }

            return redirect()->route('cliente.mis-pedidos')->with('success', $mensaje);
            
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Error al procesar el pedido: ' . $e->getMessage());
        }
    }

    // Mis pedidos
    public function misPedidos()
    {
        $pedidos = Pedido::where('user_id', auth()->id())
            ->with(['detalles.producto', 'mesa', 'reserva.espacio'])
            ->latest()
            ->paginate(10);
        
        return view('cliente.mis-pedidos', compact('pedidos'));
    }

    // Seleccionar método de pago
    public function seleccionarMetodoPago(Request $request)
    {
        $request->validate([
            'metodo_pago' => 'required|in:efectivo,yape,tarjeta',
        ]);

        // Guardar en sesión
        session()->put('metodo_pago_seleccionado', $request->metodo_pago);
        session()->put('reserva_id_temp', $request->reserva_id);
        session()->put('mesa_id_temp', $request->mesa_id);
        session()->put('observaciones_temp', $request->observaciones);

        return redirect()->route('cliente.carrito');
    }

    // Cancelar selección de método
    public function cancelarMetodoPago()
    {
        session()->forget(['metodo_pago_seleccionado', 'reserva_id_temp', 'mesa_id_temp', 'observaciones_temp']);
        return redirect()->route('cliente.carrito');
    }
}