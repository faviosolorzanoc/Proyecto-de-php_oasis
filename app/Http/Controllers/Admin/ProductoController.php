<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Producto;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProductoController extends Controller
{
    public function index()
    {
        $productos = Producto::latest()->paginate(10);
        return view('admin.productos.index', compact('productos'));
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
            'imagen' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'stock' => 'required|integer|min:0',
        ]);

        $data = $request->except('imagen');
        
        // Manejar la imagen - MÉTODO ANTIGUO
        if ($request->hasFile('imagen')) {
            $imagen = $request->file('imagen');
            $nombreImagen = time() . '_' . $imagen->getClientOriginalName();
            $imagen->move(public_path('storage'), $nombreImagen);
            $data['imagen'] = $nombreImagen;
        }

        $data['disponible'] = $request->has('disponible') ? 1 : 0;

        Producto::create($data);

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
            'imagen' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'stock' => 'required|integer|min:0',
        ]);

        $data = $request->except('imagen');
        
        // Manejar la nueva imagen - MÉTODO ANTIGUO
        if ($request->hasFile('imagen')) {
            // Eliminar imagen anterior si existe
            if ($producto->imagen && file_exists(public_path('storage/' . $producto->imagen))) {
                unlink(public_path('storage/' . $producto->imagen));
            }
            
            $imagen = $request->file('imagen');
            $nombreImagen = time() . '_' . $imagen->getClientOriginalName();
            $imagen->move(public_path('storage'), $nombreImagen);
            $data['imagen'] = $nombreImagen;
        }

        $data['disponible'] = $request->has('disponible') ? 1 : 0;
        
        $producto->update($data);

        return redirect()->route('admin.productos.index')->with('success', 'Producto actualizado exitosamente');
    }

    public function destroy(Producto $producto)
    {
        // Eliminar imagen al borrar el producto
        if ($producto->imagen && file_exists(public_path('storage/' . $producto->imagen))) {
            unlink(public_path('storage/' . $producto->imagen));
        }
        
        $producto->delete();
        return redirect()->route('admin.productos.index')->with('success', 'Producto eliminado exitosamente');
    }

    public function show(Producto $producto)
    {
        return view('admin.productos.show', compact('producto'));
    }
}