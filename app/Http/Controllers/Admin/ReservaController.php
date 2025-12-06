<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Reserva;
use Illuminate\Http\Request;

class ReservaController extends Controller
{
    public function index()
    {
        $reservas = Reserva::with(['user', 'horario.espacio'])
            ->latest()
            ->paginate(15);
        
        return view('admin.reservas.index', compact('reservas'));
    }
    
    public function show(Reserva $reserva)
    {
        $reserva->load(['user', 'horario.espacio']);
        return view('admin.reservas.show', compact('reserva'));
    }
    
    public function updateEstado(Request $request, Reserva $reserva)
    {
        $request->validate([
            'estado' => 'required|in:pendiente,confirmada,cancelada,completada',
        ]);
        
        $reserva->update(['estado' => $request->estado]);
        
        return back()->with('success', 'Estado de reserva actualizado');
    }
}