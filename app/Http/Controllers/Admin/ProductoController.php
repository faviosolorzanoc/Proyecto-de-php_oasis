<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Producto;
use Illuminate\Http\Request;

class ProductoController extends Controller
{
    public function index()
    {
        #$productos = Producto::latest()->paginate(10);
        #return view('admin.productos.index', compact('productos'));

        return view('mantenimiento');
    }

    public function create()
    {
        return view('admin.productos.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'descripcion' => 'nullable|string',
            'precio' => 'required|numeric|min:0',
            'categoria' => 'required|in:comida,bebida,snack',
            'imagen' => 'nullable|string',
            'stock' => 'required|integer|min:0',
        ]);

        Producto::create($request->all());

        return redirect()->route('admin.productos.index')->with('success', 'Producto creado exitosamente');
    }

    public function edit(Producto $producto)
    {
        return view('admin.productos.edit', compact('producto'));
    }

    public function update(Request $request, Producto $producto)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'descripcion' => 'nullable|string',
            'precio' => 'required|numeric|min:0',
            'categoria' => 'required|in:comida,bebida,snack',
            'imagen' => 'nullable|string',
            'stock' => 'required|integer|min:0',
        ]);

        $data = $request->all();
        $data['disponible'] = $request->has('disponible') ? 1 : 0;
        
        $producto->update($data);

        return redirect()->route('admin.productos.index')->with('success', 'Producto actualizado exitosamente');
    }

    public function destroy(Producto $producto)
    {
        $producto->delete();
        return redirect()->route('admin.productos.index')->with('success', 'Producto eliminado exitosamente');
    }

    public function show(Producto $producto)
    {
        return view('admin.productos.show', compact('producto'));
    }
}