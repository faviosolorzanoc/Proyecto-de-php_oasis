<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Servicio;
use Illuminate\Http\Request;

class ServicioController extends Controller
{
    public function index()
    {
        $servicios = Servicio::latest()->paginate(10);
        return view('admin.servicios.index', compact('servicios'));
    }

    public function create()
    {
        return view('admin.servicios.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'descripcion' => 'nullable|string',
            'precio' => 'required|numeric|min:0',
            'imagen' => 'nullable|string',
        ]);

        Servicio::create($request->all());

        return redirect()->route('admin.servicios.index')->with('success', 'Servicio creado exitosamente');
    }

    public function edit(Servicio $servicio)
    {
        return view('admin.servicios.edit', compact('servicio'));
    }

    public function update(Request $request, Servicio $servicio)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'descripcion' => 'nullable|string',
            'precio' => 'required|numeric|min:0',
            'imagen' => 'nullable|string',
        ]);

        $data = $request->all();
        $data['disponible'] = $request->has('disponible') ? 1 : 0;
        
        $servicio->update($data);

        return redirect()->route('admin.servicios.index')->with('success', 'Servicio actualizado exitosamente');
    }

    public function destroy(Servicio $servicio)
    {
        $servicio->delete();
        return redirect()->route('admin.servicios.index')->with('success', 'Servicio eliminado exitosamente');
    }

    public function show(Servicio $servicio)
    {
        return view('admin.servicios.show', compact('servicio'));
    }
}