<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Espacio;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class EspacioController extends Controller
{
    public function index()
    {
        $espacios = Espacio::latest()->paginate(10);
        return view('admin.espacios.index', compact('espacios'));
    }

    public function create()
    {
        return view('admin.espacios.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'descripcion' => 'nullable|string',
            'capacidad' => 'required|integer|min:1',
            'precio_hora' => 'required|numeric|min:0',
            'imagen' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
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

        Espacio::create($data);

        return redirect()->route('admin.espacios.index')->with('success', 'Espacio creado exitosamente');
    }

    public function edit(Espacio $espacio)
    {
        return view('admin.espacios.edit', compact('espacio'));
    }

    public function update(Request $request, Espacio $espacio)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'descripcion' => 'nullable|string',
            'capacidad' => 'required|integer|min:1',
            'precio_hora' => 'required|numeric|min:0',
            'imagen' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $data = $request->except('imagen');
        
        // Manejar la nueva imagen - MÉTODO ANTIGUO
        if ($request->hasFile('imagen')) {
            // Eliminar imagen anterior si existe
            if ($espacio->imagen && file_exists(public_path('storage/' . $espacio->imagen))) {
                unlink(public_path('storage/' . $espacio->imagen));
            }
            
            $imagen = $request->file('imagen');
            $nombreImagen = time() . '_' . $imagen->getClientOriginalName();
            $imagen->move(public_path('storage'), $nombreImagen);
            $data['imagen'] = $nombreImagen;
        }

        $data['disponible'] = $request->has('disponible') ? 1 : 0;
        
        $espacio->update($data);

        return redirect()->route('admin.espacios.index')->with('success', 'Espacio actualizado exitosamente');
    }

    public function destroy(Espacio $espacio)
    {
        // Eliminar imagen al borrar el espacio
        if ($espacio->imagen && file_exists(public_path('storage/' . $espacio->imagen))) {
            unlink(public_path('storage/' . $espacio->imagen));
        }
        
        $espacio->delete();
        return redirect()->route('admin.espacios.index')->with('success', 'Espacio eliminado exitosamente');
    }

    public function show(Espacio $espacio)
    {
        return view('admin.espacios.show', compact('espacio'));
    }
}