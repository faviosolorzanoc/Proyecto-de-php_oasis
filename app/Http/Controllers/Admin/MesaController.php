<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Mesa;
use Illuminate\Http\Request;

class MesaController extends Controller
{
    public function index()
    {
        $mesas = Mesa::latest()->paginate(10);
        return view('admin.mesas.index', compact('mesas'));
    }

    public function create()
    {
        return view('admin.mesas.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'numero' => 'required|string|max:255|unique:mesas',
            'capacidad' => 'required|integer|min:1',
            'ubicacion' => 'nullable|string',
        ]);

        Mesa::create($request->all());

        return redirect()->route('admin.mesas.index')->with('success', 'Mesa creada exitosamente');
    }

    public function edit(Mesa $mesa)
    {
        return view('admin.mesas.edit', compact('mesa'));
    }

    public function update(Request $request, Mesa $mesa)
    {
        $request->validate([
            'numero' => 'required|string|max:255|unique:mesas,numero,' . $mesa->id,
            'capacidad' => 'required|integer|min:1',
            'ubicacion' => 'nullable|string',
            'estado' => 'required|in:disponible,ocupada,reservada',
        ]);

        $mesa->update($request->all());

        return redirect()->route('admin.mesas.index')->with('success', 'Mesa actualizada exitosamente');
    }

    public function destroy(Mesa $mesa)
    {
        $mesa->delete();
        return redirect()->route('admin.mesas.index')->with('success', 'Mesa eliminada exitosamente');
    }

    public function show(Mesa $mesa)
    {
        return view('admin.mesas.show', compact('mesa'));
    }
}