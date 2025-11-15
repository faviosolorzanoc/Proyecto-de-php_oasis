<?php

namespace App\Http\Controllers\Cliente;

use App\Http\Controllers\Controller;
use App\Models\DetallePedido;
use App\Models\Mesa;
use App\Models\Pedido;
use App\Models\Producto;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PedidoController extends Controller
{
    public function crear()
    {
        $productos = Producto::where('disponible', true)->where('stock', '>', 0)->get();
        $mesas = Mesa::where('estado', 'disponible')->get();
        return view('cliente.pedir', compact('productos', 'mesas'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'mesa_id' => 'nullable|exists:mesas,id',
            'productos' => 'required|array|min:1',
            'productos.*.id' => 'required|exists:productos,id',
            'productos.*.cantidad' => 'required|integer|min:1',
            'observaciones' => 'nullable|string',
        ]);

        DB::beginTransaction();

        try {
            $total = 0;
            $detalles = [];

            foreach ($request->productos as $item) {
                $producto = Producto::findOrFail($item['id']);
                $subtotal = $producto->precio * $item['cantidad'];
                $total += $subtotal;

                $detalles[] = [
                    'producto_id' => $producto->id,
                    'cantidad' => $item['cantidad'],
                    'precio_unitario' => $producto->precio,
                    'subtotal' => $subtotal,
                ];
            }

            $pedido = Pedido::create([
                'user_id' => auth()->id(),
                'mesa_id' => $request->mesa_id,
                'total' => $total,
                'observaciones' => $request->observaciones,
            ]);

            foreach ($detalles as $detalle) {
                $pedido->detalles()->create($detalle);
            }

            if ($request->mesa_id) {
                Mesa::find($request->mesa_id)->update(['estado' => 'ocupada']);
            }

            DB::commit();

            return redirect()->route('cliente.home')->with('success', 'Pedido realizado exitosamente');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Error al procesar el pedido: ' . $e->getMessage());
        }
    }

    public function misPedidos()
    {
        $pedidos = Pedido::where('user_id', auth()->id())
            ->with('detalles.producto', 'mesa')
            ->latest()
            ->paginate(10);
        
        return view('cliente.mis-pedidos', compact('pedidos'));
    }
}