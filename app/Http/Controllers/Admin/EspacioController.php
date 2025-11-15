<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Espacio;
use Illuminate\Http\Request;

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
            'imagen' => 'nullable|string',
        ]);

        Espacio::create($request->all());

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
            'imagen' => 'nullable|string',
        ]);

        $data = $request->all();
        $data['disponible'] = $request->has('disponible') ? 1 : 0;
        
        $espacio->update($data);

        return redirect()->route('admin.espacios.index')->with('success', 'Espacio actualizado exitosamente');
    }

    public function destroy(Espacio $espacio)
    {
        $espacio->delete();
        return redirect()->route('admin.espacios.index')->with('success', 'Espacio eliminado exitosamente');
    }

    public function show(Espacio $espacio)
    {
        return view('admin.espacios.show', compact('espacio'));
    }
}