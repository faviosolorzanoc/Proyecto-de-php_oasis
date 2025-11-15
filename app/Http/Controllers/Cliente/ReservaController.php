<?php

namespace App\Http\Controllers\Cliente;

use App\Http\Controllers\Controller;
use App\Models\Reserva;
use App\Models\Espacio;
use App\Models\Servicio;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReservaController extends Controller
{
    public function index()
    {
        return view('cliente.reservas.index');
    }

    public function verificarDisponibilidad(Request $request)
    {
        $request->validate([
            'fecha_evento' => 'required|date|after_or_equal:today',
            'hora_inicio' => 'required',
            'hora_fin' => 'required|after:hora_inicio',
            'num_personas' => 'required|integer|min:1',
        ]);

        // Buscar espacios disponibles
        $espaciosDisponibles = Espacio::where('disponible', true)
            ->where('capacidad', '>=', $request->num_personas)
            ->whereNotIn('id', function($query) use ($request) {
                $query->select('espacio_id')
                    ->from('reservas')
                    ->where('fecha_evento', $request->fecha_evento)
                    ->where('estado', '!=', 'cancelada')
                    ->where(function($q) use ($request) {
                        $q->whereBetween('hora_inicio', [$request->hora_inicio, $request->hora_fin])
                          ->orWhereBetween('hora_fin', [$request->hora_inicio, $request->hora_fin])
                          ->orWhere(function($q2) use ($request) {
                              $q2->where('hora_inicio', '<=', $request->hora_inicio)
                                 ->where('hora_fin', '>=', $request->hora_fin);
                          });
                    });
            })
            ->get();

        $servicios = Servicio::where('disponible', true)->get();

        return view('cliente.reservas.espacios-disponibles', compact('espaciosDisponibles', 'servicios', 'request'));
    }

    public function resumen(Request $request)
    {
        $espacio = Espacio::findOrFail($request->espacio_id);
        $servicios = [];
        
        if ($request->servicios) {
            $servicios = Servicio::whereIn('id', $request->servicios)->get();
        }

        return view('cliente.reservas.resumen', compact('espacio', 'servicios', 'request'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'espacio_id' => 'required|exists:espacios,id',
            'fecha_evento' => 'required|date',
            'hora_inicio' => 'required',
            'hora_fin' => 'required',
            'num_personas' => 'required|integer',
            'total' => 'required|numeric',
            'metodo_pago' => 'required|in:efectivo,yape,tarjeta',
        ]);

        DB::beginTransaction();
        try {
            $reserva = Reserva::create([
                'user_id' => auth()->id(),
            'espacio_id' => $request->espacio_id,
            'fecha_evento' => $request->fecha_evento,
            'hora_inicio' => $request->hora_inicio,
            'hora_fin' => $request->hora_fin,
            'num_personas' => $request->num_personas,
            'total' => $request->total,
            'metodo_pago' => $request->metodo_pago,
            'estado' => 'confirmada',  // ← CAMBIO AQUÍ: directamente confirmada
            'estado_pago' => $request->metodo_pago == 'efectivo' ? 'pendiente' : 'pagado',
            'observaciones' => $request->observaciones,
            'servicios_adicionales' => $request->servicios ?? [],
            ]);

            DB::commit();

            return redirect()->route('cliente.reservas.confirmacion', $reserva->id);
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Error al procesar la reserva: ' . $e->getMessage());
        }
    }

    public function confirmacion(Reserva $reserva)
    {
        if ($reserva->user_id !== auth()->id()) {
            abort(403);
        }

        return view('cliente.reservas.confirmacion', compact('reserva'));
    }

    public function misReservas()
    {
        $reservas = Reserva::where('user_id', auth()->id())
            ->with('espacio')
            ->latest()
            ->paginate(10);

        return view('cliente.reservas.mis-reservas', compact('reservas'));
    }
}