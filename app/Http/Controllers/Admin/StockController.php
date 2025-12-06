<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Producto;
use Illuminate\Http\Request;

class StockController extends Controller
{
    // Ver stock actual de productos
    public function index()
    {
        $productos = Producto::where('disponible', true)->get();
        return view('admin.stock.index', compact('productos'));
    }
    
    // Actualizar stock de un producto
    public function update(Request $request, Producto $producto)
    {
        $request->validate([
            'stock' => 'required|integer|min:0',
        ]);
        
        $producto->update(['stock' => $request->stock]);
        
        return back()->with('success', 'Stock actualizado para hoy');
    }
    
    // Reiniciar stock de todos los productos (al inicio del día)
    public function reiniciar()
    {
        // Opcional: poner todos en 0 al inicio del día
        Producto::query()->update(['stock' => 0]);
        
        return back()->with('success', 'Stock reiniciado. Registra el inventario del día.');
    }
}