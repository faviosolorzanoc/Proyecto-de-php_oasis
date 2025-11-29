<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Espacio;
use App\Models\Horario;
use Illuminate\Http\Request;
use Carbon\Carbon;

class HorarioController extends Controller
{
    // Ver horarios de un espacio específico
    public function index(Espacio $espacio)
    {
        $horarios = Horario::where('espacio_id', $espacio->id)
            ->orderBy('fecha')
            ->orderBy('hora_inicio')
            ->paginate(20);

        return view('admin.espacios.horarios.index', compact('espacio', 'horarios'));
    }

    // Formulario para crear horarios masivos
    public function create(Espacio $espacio)
    {
        return view('admin.espacios.horarios.create', compact('espacio'));
    }

    // Guardar horarios
    public function store(Request $request, Espacio $espacio)
    {
        $request->validate([
            'fecha_inicio' => 'required|date',
            'fecha_fin' => 'required|date|after_or_equal:fecha_inicio',
            'hora_inicio' => 'required',
            'hora_fin' => 'required|after:hora_inicio',
            'intervalo' => 'required|integer|min:1|max:8',
        ]);

        $fechaInicio = Carbon::parse($request->fecha_inicio);
        $fechaFin = Carbon::parse($request->fecha_fin);
        $horaInicio = $request->hora_inicio;
        $horaFin = $request->hora_fin;
        $intervalo = $request->intervalo;

        $horariosCreados = 0;

        // Recorrer cada día en el rango
        while ($fechaInicio <= $fechaFin) {
            $horarioInicio = Carbon::parse($fechaInicio->format('Y-m-d') . ' ' . $horaInicio);
            $horarioFin = Carbon::parse($fechaInicio->format('Y-m-d') . ' ' . $horaFin);

            // Crear horario para ese día
            try {
                Horario::create([
                    'espacio_id' => $espacio->id,
                    'fecha' => $fechaInicio->format('Y-m-d'),
                    'hora_inicio' => $horarioInicio->format('H:i'),
                    'hora_fin' => $horarioFin->format('H:i'),
                    'estado' => 'disponible',
                ]);
                $horariosCreados++;
            } catch (\Exception $e) {
                // Si ya existe ese horario, continuar
            }

            $fechaInicio->addDays(1);
        }

        return redirect()
            ->route('admin.horarios.index', $espacio)
            ->with('success', "Se crearon {$horariosCreados} horario(s) exitosamente");
    }

    // Cambiar estado de un horario
    public function updateEstado(Request $request, Espacio $espacio, Horario $horario)
    {
        $request->validate([
            'estado' => 'required|in:disponible,bloqueado',
        ]);

        if ($horario->estado == 'ocupado') {
            return back()->with('error', 'No se puede cambiar el estado de un horario ocupado');
        }

        $horario->update(['estado' => $request->estado]);

        return back()->with('success', 'Estado actualizado correctamente');
    }

    // Eliminar horario
    public function destroy(Espacio $espacio, Horario $horario)
    {
        if ($horario->estado == 'ocupado') {
            return back()->with('error', 'No se puede eliminar un horario ocupado (tiene una reserva activa)');
        }

        $horario->delete();

        return back()->with('success', 'Horario eliminado correctamente');
    }
}