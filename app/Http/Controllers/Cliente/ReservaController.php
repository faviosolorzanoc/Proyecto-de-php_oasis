<?php

namespace App\Http\Controllers\Cliente;

use App\Http\Controllers\Controller;
use App\Models\Reserva;
use App\Models\Espacio;
use App\Models\Servicio;
use App\Models\Horario;
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
            'num_personas' => 'required|integer|min:1',
        ]);

        $espaciosDisponibles = Espacio::where('disponible', true)
            ->where('capacidad', '>=', $request->num_personas)
            ->get();

        return view('cliente.reservas.espacios-disponibles', compact(
            'espaciosDisponibles',
            'request'
        ));
    }

    public function verHorarios(Request $request)
    {
        $request->validate([
            'espacios' => 'required|array|min:1',
            'espacios.*' => 'exists:espacios,id',
            'fecha_evento' => 'required|date',
            'num_personas' => 'required|integer',
        ], [
            'espacios.required' => 'Debes seleccionar al menos un espacio',
            'espacios.min' => 'Debes seleccionar al menos un espacio',
        ]);

        $espaciosSeleccionados = Espacio::whereIn('id', $request->espacios)->get();
        
        $horariosDisponibles = Horario::whereIn('espacio_id', $request->espacios)
            ->where('fecha', $request->fecha_evento)
            ->where('estado', 'disponible')
            ->with('espacio')
            ->orderBy('espacio_id')
            ->orderBy('hora_inicio')
            ->get()
            ->groupBy('espacio_id');

        $servicios = Servicio::where('disponible', true)->get();

        return view('cliente.reservas.horarios-multiples', compact(
            'espaciosSeleccionados',
            'horariosDisponibles',
            'servicios',
            'request'
        ));
    }

    public function resumen(Request $request)
    {
        $request->validate([
            'horario_id' => 'required|exists:horarios,id',
            'fecha_evento' => 'required|date',
            'num_personas' => 'required|integer',
        ]);

        // Guardar TODO en sesión
        session()->put('reserva_temp', [
            'horario_id' => $request->horario_id,
            'fecha_evento' => $request->fecha_evento,
            'num_personas' => $request->num_personas,
            'servicios' => $request->servicios ?? [],
            'observaciones' => $request->observaciones,
        ]);

        $horario = Horario::with('espacio')->findOrFail($request->horario_id);
        $espacio = $horario->espacio;
        
        $inicio = \Carbon\Carbon::parse($horario->hora_inicio);
        $fin = \Carbon\Carbon::parse($horario->hora_fin);
        $duracion = $inicio->diffInHours($fin);
        $costoEspacio = $duracion * $espacio->precio_hora;
        
        $servicios = collect([]);
        $costoServicios = 0;
        if ($request->servicios) {
            $servicios = Servicio::whereIn('id', $request->servicios)->get();
            $costoServicios = $servicios->sum('precio');
        }
        
        $total = $costoEspacio + $costoServicios;

        session()->put('reserva_costos', [
            'costo_espacio' => $costoEspacio,
            'costo_servicios' => $costoServicios,
            'total' => $total,
        ]);

        return view('cliente.reservas.resumen', compact(
            'espacio',
            'horario',
            'servicios',
            'costoEspacio',
            'costoServicios',
            'total',
            'request'
        ));
    }

    public function seleccionarMetodoPago(Request $request)
    {
        $request->validate([
            'metodo_pago' => 'required|in:efectivo,yape,tarjeta',
        ]);

        if (!session('reserva_temp')) {
            return redirect()->route('cliente.reservas')->with('error', 'Sesión expirada. Inicia el proceso nuevamente.');
        }

        session()->put('reserva_metodo_pago', $request->metodo_pago);

        return redirect()->route('cliente.reservas.confirmar-pago');
    }

    public function mostrarConfirmarPago()
    {
        if (!session('reserva_temp') || !session('reserva_metodo_pago')) {
            return redirect()->route('cliente.reservas')->with('error', 'Sesión expirada');
        }

        $reservaTemp = session('reserva_temp');
        $costos = session('reserva_costos');
        $metodoPago = session('reserva_metodo_pago');

        $horario = Horario::with('espacio')->findOrFail($reservaTemp['horario_id']);
        $espacio = $horario->espacio;
        
        $servicios = collect([]);
        if (!empty($reservaTemp['servicios'])) {
            $servicios = Servicio::whereIn('id', $reservaTemp['servicios'])->get();
        }

        return view('cliente.reservas.confirmar-pago', compact(
            'espacio',
            'horario',
            'servicios',
            'metodoPago',
            'reservaTemp',
            'costos'
        ));
    }

    public function cancelarMetodoPago()
    {
        session()->forget('reserva_metodo_pago');
        return redirect()->route('cliente.reservas.resumen-actual');
    }

    public function mostrarResumenActual()
    {
        if (!session('reserva_temp')) {
            return redirect()->route('cliente.reservas')->with('error', 'Sesión expirada');
        }

        $reservaTemp = session('reserva_temp');
        $costos = session('reserva_costos');

        $horario = Horario::with('espacio')->findOrFail($reservaTemp['horario_id']);
        $espacio = $horario->espacio;
        
        $servicios = collect([]);
        if (!empty($reservaTemp['servicios'])) {
            $servicios = Servicio::whereIn('id', $reservaTemp['servicios'])->get();
        }

        $request = (object) $reservaTemp;

        return view('cliente.reservas.resumen', compact(
            'espacio',
            'horario',
            'servicios',
            'request'
        ))->with([
            'costoEspacio' => $costos['costo_espacio'],
            'costoServicios' => $costos['costo_servicios'],
            'total' => $costos['total'],
        ]);
    }

    public function store(Request $request)
    {
        if (!session('reserva_temp') || !session('reserva_metodo_pago')) {
            return redirect()->route('cliente.reservas')->with('error', 'Sesión expirada');
        }

        $reservaTemp = session('reserva_temp');
        $costos = session('reserva_costos');
        $metodoPago = session('reserva_metodo_pago');

        $rules = [];

        if ($metodoPago === 'yape') {
            $rules['yape_codigo'] = 'required|digits:6';
            $rules['yape_telefono'] = 'required|digits:9';
        } elseif ($metodoPago === 'tarjeta') {
            $rules['tarjeta_numero'] = 'required|string|min:13|max:19';
            $rules['tarjeta_nombre'] = 'required|string|max:100';
            $rules['tarjeta_expiracion'] = 'required|regex:/^\d{2}\/\d{2}$/';
            $rules['tarjeta_cvv'] = 'required|digits_between:3,4';
        }

        if (!empty($rules)) {
            $validated = $request->validate($rules, [
                'yape_codigo.digits' => 'El código debe tener 6 dígitos',
                'yape_telefono.digits' => 'El teléfono debe tener 9 dígitos',
                'tarjeta_expiracion.regex' => 'Formato inválido (MM/AA)',
            ]);
        }

        if ($metodoPago === 'yape' && $request->yape_codigo === '000000') {
            return back()->with('error', '❌ Código de operación inválido');
        }

        if ($metodoPago === 'tarjeta') {
            list($mes, $anio) = explode('/', $request->tarjeta_expiracion);
            $fechaExpiracion = "20" . $anio . "-" . $mes . "-01";
            
            if (strtotime($fechaExpiracion) < strtotime(date('Y-m-01'))) {
                return back()->with('error', '❌ Tarjeta vencida');
            }
        }

        DB::beginTransaction();
        try {
            $horario = Horario::findOrFail($reservaTemp['horario_id']);
            
            if ($horario->estado !== 'disponible') {
                DB::rollBack();
                return back()->with('error', 'Este horario ya no está disponible');
            }

            $reserva = Reserva::create([
                'user_id' => auth()->id(),
                'espacio_id' => $horario->espacio_id,
                'horario_id' => $reservaTemp['horario_id'],
                'fecha_evento' => $reservaTemp['fecha_evento'],
                'hora_inicio' => $horario->hora_inicio,
                'hora_fin' => $horario->hora_fin,
                'num_personas' => $reservaTemp['num_personas'],
                'total' => $costos['total'],
                'metodo_pago' => $metodoPago,
                'estado' => 'confirmada',
                'estado_pago' => $metodoPago == 'efectivo' ? 'pendiente' : 'pagado',
                'observaciones' => $reservaTemp['observaciones'],
                'servicios_adicionales' => $reservaTemp['servicios'],
            ]);

            $horario->marcarComoOcupado($reserva->id);

            session()->forget(['reserva_temp', 'reserva_costos', 'reserva_metodo_pago']);

            DB::commit();

            $mensaje = match($metodoPago) {
                'efectivo' => '✅ Reserva confirmada. Paga S/.' . number_format($costos['total'], 2) . ' al llegar.',
                'yape' => '✅ Pago Yape registrado por S/.' . number_format($costos['total'], 2),
                'tarjeta' => '✅ Pago procesado por S/.' . number_format($costos['total'], 2),
                default => '✅ Reserva confirmada.'
            };

            return redirect()->route('cliente.reservas.confirmacion', $reserva->id)->with('success', $mensaje);
                
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Error: ' . $e->getMessage());
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

    public function cancelar(Reserva $reserva)
    {
        if ($reserva->user_id !== auth()->id()) {
            abort(403);
        }

        if ($reserva->estado === 'cancelada') {
            return back()->with('error', 'Esta reserva ya está cancelada');
        }

        DB::beginTransaction();
        try {
            if ($reserva->horario_id) {
                $horario = Horario::find($reserva->horario_id);
                if ($horario) {
                    $horario->liberar();
                }
            }

            $reserva->update(['estado' => 'cancelada']);

            DB::commit();

            return back()->with('success', 'Reserva cancelada exitosamente');
            
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Error: ' . $e->getMessage());
        }
    }
}