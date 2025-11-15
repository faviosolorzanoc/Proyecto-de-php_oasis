<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Pedido;
use Illuminate\Http\Request;

class PedidoController extends Controller
{
    public function index()
    {
        #$pedidos = Pedido::with('user', 'mesa')->latest()->paginate(15);
        #return view('admin.pedidos.index', compact('pedidos'));

        return view('mantenimiento');
    }

    public function show(Pedido $pedido)
    {
        $pedido->load('user', 'mesa', 'detalles.producto');
        return view('admin.pedidos.show', compact('pedido'));
    }

    public function updateEstado(Request $request, Pedido $pedido)
    {
        $request->validate([
            'estado' => 'required|in:pendiente,en_preparacion,listo,entregado,cancelado',
        ]);

        $pedido->update(['estado' => $request->estado]);

        return redirect()->back()->with('success', 'Estado del pedido actualizado');
    }
}