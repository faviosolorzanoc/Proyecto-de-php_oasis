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
            ->fresh();
        
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

        // Verificar stock REAL en la BD
        $producto->refresh();
        
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
            $carrito[$producto->id]['stock'] = $producto->stock;
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
        
        // ACTUALIZAR el stock en el carrito desde la BD
        foreach ($carrito as $productoId => $item) {
            $producto = Producto::find($productoId);
            if ($producto) {
                $carrito[$productoId]['stock'] = $producto->stock;
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
        $rules['yape_codigo'] = 'required|numeric|digits:6';
        $rules['yape_telefono'] = 'required|numeric|digits:9';
    } elseif ($metodoPago === 'tarjeta') {
        $rules['tarjeta_numero'] = 'required|numeric|min:13';
        $rules['tarjeta_nombre'] = 'required|string|max:100';
        $rules['tarjeta_expiracion'] = 'required|size:5';
        $rules['tarjeta_cvv'] = 'required|numeric|digits_between:3,4';
    }

    // Validar formulario
    try {
        if (!empty($rules)) {
            $request->validate($rules, [
                'yape_codigo.required' => 'El código de operación es obligatorio',
                'yape_codigo.numeric' => 'Solo números',
                'yape_codigo.digits' => 'Debe tener 6 dígitos',
                'yape_telefono.required' => 'El teléfono es obligatorio',
                'yape_telefono.numeric' => 'Solo números',
                'yape_telefono.digits' => 'Debe tener 9 dígitos',
                'tarjeta_numero.required' => 'El número de tarjeta es obligatorio',
                'tarjeta_numero.numeric' => 'Solo números permitidos',
                'tarjeta_numero.min' => 'Mínimo 13 dígitos',
                'tarjeta_nombre.required' => 'El nombre es obligatorio',
                'tarjeta_expiracion.required' => 'La fecha es obligatoria',
                'tarjeta_expiracion.size' => 'Formato MM/AA',
                'tarjeta_cvv.required' => 'El CVV es obligatorio',
                'tarjeta_cvv.numeric' => 'Solo números',
                'tarjeta_cvv.digits_between' => '3 o 4 dígitos',
            ]);
        }
    } catch (\Illuminate\Validation\ValidationException $e) {
        return back()->withErrors($e->validator)->withInput();
    }

    // Validaciones simples de simulación
    if ($metodoPago === 'yape') {
        if ($request->yape_codigo === '000000') {
            return back()->withInput()->with('error', '❌ Código inválido');
        }
        
        if (substr($request->yape_codigo, 0, 3) === '999') {
            return back()->withInput()->with('error', '❌ Transacción rechazada');
        }
        
        if (!str_starts_with($request->yape_telefono, '9')) {
            return back()->withInput()->with('error', '❌ El teléfono debe empezar con 9');
        }
    }

    if ($metodoPago === 'tarjeta') {
        // Validar fecha vencida
        if (preg_match('/^(\d{2})\/(\d{2})$/', $request->tarjeta_expiracion, $matches)) {
            $mes = (int)$matches[1];
            $anio = (int)$matches[2];
            $mesActual = (int)date('m');
            $anioActual = (int)date('y');
            
            if ($mes < 1 || $mes > 12) {
                return back()->withInput()->with('error', '❌ Mes inválido. Use 01-12');
            }
            
            if ($anio < $anioActual || ($anio == $anioActual && $mes < $mesActual)) {
                return back()->withInput()->with('error', '❌ Tarjeta vencida');
            }
        } else {
            return back()->withInput()->with('error', '❌ Formato inválido. Use MM/AA');
        }
        
        // Validar nombre sin números
        if (preg_match('/\d/', $request->tarjeta_nombre)) {
            return back()->withInput()->with('error', '❌ El nombre no puede contener números');
        }
        
        // Validar nombre completo
        if (str_word_count($request->tarjeta_nombre) < 2) {
            return back()->withInput()->with('error', '❌ Ingrese nombre y apellido');
        }
        
        // Simulación: rechazar tarjetas terminadas en 0000
        if (substr($request->tarjeta_numero, -4) === '0000') {
            return back()->withInput()->with('error', '❌ Tarjeta rechazada');
        }
        
        // Simulación: CVV inválidos
        if (in_array($request->tarjeta_cvv, ['000', '999'])) {
            return back()->withInput()->with('error', '❌ CVV inválido');
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
                return back()->withInput()->with('error', "❌ Stock insuficiente para {$producto->nombre}. Solo quedan {$producto->stock} unidades.");
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
        $mensaje = match($metodoPago) {
            'efectivo' => '✅ Pedido confirmado. Paga S/.' . number_format($total, 2) . ' en efectivo al recoger.',
            'yape' => '✅ Pago Yape procesado. Pedido confirmado por S/.' . number_format($total, 2),
            'tarjeta' => '✅ Pago procesado exitosamente. Pedido confirmado por S/.' . number_format($total, 2),
            default => '✅ Pedido realizado.'
        };

        return redirect()->route('cliente.mis-pedidos')->with('success', $mensaje);
        
    } catch (\Exception $e) {
        DB::rollBack();
        return back()->withInput()->with('error', '❌ Error: ' . $e->getMessage());
    }
}

    /**
     * Validar número de tarjeta usando algoritmo de Luhn
     * Este es el algoritmo matemático REAL que usan los bancos
     */
    private function validarLuhn($numero)
    {
        // Limpiar cualquier caracter que no sea número
        $numero = preg_replace('/\D/', '', $numero);
        
        // Verificar longitud válida (13-19 dígitos)
        if (strlen($numero) < 13 || strlen($numero) > 19) {
            return false;
        }
        
        $suma = 0;
        $longitud = strlen($numero);
        $paridad = $longitud % 2;
        
        for ($i = 0; $i < $longitud; $i++) {
            $digito = (int)$numero[$i];
            
            // Duplicar cada segundo dígito
            if ($i % 2 == $paridad) {
                $digito *= 2;
                // Si el resultado es mayor a 9, restar 9
                if ($digito > 9) {
                    $digito -= 9;
                }
            }
            
            $suma += $digito;
        }
        
        // El número es válido si la suma es múltiplo de 10
        return ($suma % 10) == 0;
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
