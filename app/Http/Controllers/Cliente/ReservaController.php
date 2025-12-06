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
        // VALIDACIÓN ACTUALIZADA: Ahora acepta múltiples horarios
        $validated = $request->validate([
            'horarios' => 'required|array|min:1',
            'horarios.*' => 'exists:horarios,id',
            'fecha_evento' => 'required|date',
            'num_personas' => 'required|integer',
            'servicios' => 'nullable|array',
            'servicios.*' => 'exists:servicios,id',
        ], [
            'horarios.required' => 'Debes seleccionar al menos un horario',
            'horarios.min' => 'Debes seleccionar al menos un horario',
        ]);

        // Obtener los horarios seleccionados con sus espacios
        $horariosSeleccionados = Horario::with('espacio')
            ->whereIn('id', $request->horarios)
            ->get();

        // VALIDACIÓN: Solo UN horario por espacio
        $espaciosPorHorario = $horariosSeleccionados->groupBy('espacio_id');
        
        foreach ($espaciosPorHorario as $espacioId => $horarios) {
            if ($horarios->count() > 1) {
                return back()
                    ->withErrors(['horarios' => 'Solo puedes seleccionar UN horario por cada espacio'])
                    ->withInput();
            }
        }

        // VALIDACIÓN: Verificar disponibilidad de cada horario
        foreach ($horariosSeleccionados as $horario) {
            if ($horario->estado !== 'disponible') {
                return back()
                    ->withErrors(['horarios' => 'Uno de los horarios seleccionados ya no está disponible'])
                    ->withInput();
            }
        }

        // CALCULAR COSTOS DE TODOS LOS HORARIOS
        $costoEspacioTotal = 0;
        $detallesEspacios = [];

        foreach ($horariosSeleccionados as $horario) {
            $espacio = $horario->espacio;
            $inicio = \Carbon\Carbon::parse($horario->hora_inicio);
            $fin = \Carbon\Carbon::parse($horario->hora_fin);
            $duracion = $inicio->diffInHours($fin);
            $costoEspacio = $duracion * $espacio->precio_hora;
            
            $costoEspacioTotal += $costoEspacio;
            
            $detallesEspacios[] = [
                'espacio' => $espacio,
                'horario' => $horario,
                'duracion' => $duracion,
                'costo' => $costoEspacio,
            ];
        }
        
        // CALCULAR SERVICIOS
        $servicios = collect([]);
        $costoServicios = 0;
        if ($request->servicios) {
            $servicios = Servicio::whereIn('id', $request->servicios)->get();
            $costoServicios = $servicios->sum('precio');
        }
        
        $total = $costoEspacioTotal + $costoServicios;

        // GUARDAR EN SESIÓN (ahora con múltiples horarios)
        session()->put('reserva_temp', [
            'horarios' => $request->horarios, // Cambio: ahora es array
            'fecha_evento' => $request->fecha_evento,
            'num_personas' => $request->num_personas,
            'servicios' => $request->servicios ?? [],
            'observaciones' => $request->observaciones,
        ]);

        session()->put('reserva_costos', [
            'costo_espacio' => $costoEspacioTotal,
            'costo_servicios' => $costoServicios,
            'total' => $total,
        ]);

        // PASAR DATOS A LA VISTA
        return view('cliente.reservas.resumen', compact(
            'detallesEspacios',
            'servicios',
            'costoEspacioTotal',
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

        // ACTUALIZADO: Obtener múltiples horarios
        $horariosSeleccionados = Horario::with('espacio')
            ->whereIn('id', $reservaTemp['horarios'])
            ->get();

        $detallesEspacios = [];
        foreach ($horariosSeleccionados as $horario) {
            $espacio = $horario->espacio;
            $inicio = \Carbon\Carbon::parse($horario->hora_inicio);
            $fin = \Carbon\Carbon::parse($horario->hora_fin);
            $duracion = $inicio->diffInHours($fin);
            $costoEspacio = $duracion * $espacio->precio_hora;
            
            $detallesEspacios[] = [
                'espacio' => $espacio,
                'horario' => $horario,
                'duracion' => $duracion,
                'costo' => $costoEspacio,
            ];
        }
        
        $servicios = collect([]);
        if (!empty($reservaTemp['servicios'])) {
            $servicios = Servicio::whereIn('id', $reservaTemp['servicios'])->get();
        }

        return view('cliente.reservas.confirmar-pago', compact(
            'detallesEspacios',
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

        // ACTUALIZADO: Obtener múltiples horarios
        $horariosSeleccionados = Horario::with('espacio')
            ->whereIn('id', $reservaTemp['horarios'])
            ->get();

        $detallesEspacios = [];
        foreach ($horariosSeleccionados as $horario) {
            $espacio = $horario->espacio;
            $inicio = \Carbon\Carbon::parse($horario->hora_inicio);
            $fin = \Carbon\Carbon::parse($horario->hora_fin);
            $duracion = $inicio->diffInHours($fin);
            $costoEspacio = $duracion * $espacio->precio_hora;
            
            $detallesEspacios[] = [
                'espacio' => $espacio,
                'horario' => $horario,
                'duracion' => $duracion,
                'costo' => $costoEspacio,
            ];
        }
        
        $servicios = collect([]);
        if (!empty($reservaTemp['servicios'])) {
            $servicios = Servicio::whereIn('id', $reservaTemp['servicios'])->get();
        }

        $request = (object) $reservaTemp;

        return view('cliente.reservas.resumen', compact(
            'detallesEspacios',  // ← IMPORTANTE: debe ser este nombre
            'servicios',
            'request'
        ))->with([
            'costoEspacioTotal' => $costos['costo_espacio'],  // ← Fíjate que cambié el nombre
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
            $rules['yape_codigo'] = 'required|numeric|digits:6';
            $rules['yape_telefono'] = 'required|numeric|digits:9';
        } elseif ($metodoPago === 'tarjeta') {
            $rules['tarjeta_numero'] = 'required|numeric|min:13';
            $rules['tarjeta_nombre'] = 'required|string|max:100';
            $rules['tarjeta_expiracion'] = 'required|size:5';
            $rules['tarjeta_cvv'] = 'required|numeric|digits_between:3,4';
        }

        try {
            if (!empty($rules)) {
                $request->validate($rules, [
                    'yape_codigo.required' => 'El código de operación es obligatorio',
                    'yape_codigo.numeric' => 'Solo números',
                    'yape_codigo.digits' => 'Debe tener 6 dígitos',
                    'yape_telefono.required' => 'El teléfono es obligatorio',
                    'yape_telefono.numeric' => 'Solo números',
                    'yape_telefono.digits' => 'Debe tener 9 dígitos',
                    'tarjeta_numero.required' => 'El número de tarjeta es obligatorio',
                    'tarjeta_numero.numeric' => 'Solo números permitidos',
                    'tarjeta_numero.min' => 'Mínimo 13 dígitos',
                    'tarjeta_nombre.required' => 'El nombre es obligatorio',
                    'tarjeta_expiracion.required' => 'La fecha es obligatoria',
                    'tarjeta_expiracion.size' => 'Formato MM/AA',
                    'tarjeta_cvv.required' => 'El CVV es obligatorio',
                    'tarjeta_cvv.numeric' => 'Solo números',
                    'tarjeta_cvv.digits_between' => '3 o 4 dígitos',
                ]);
            }
        } catch (\Illuminate\Validation\ValidationException $e) {
            return back()->withErrors($e->validator)->withInput();
        }

        // Validaciones simples de simulación
        if ($metodoPago === 'yape') {
            if ($request->yape_codigo === '000000') {
                return back()->withInput()->with('error', '❌ Código inválido');
            }
            
            if (!str_starts_with($request->yape_telefono, '9')) {
                return back()->withInput()->with('error', '❌ El teléfono debe empezar con 9');
            }
        }

        if ($metodoPago === 'tarjeta') {
            if (preg_match('/^(\d{2})\/(\d{2})$/', $request->tarjeta_expiracion, $matches)) {
                $mes = (int)$matches[1];
                $anio = (int)$matches[2];
                $mesActual = (int)date('m');
                $anioActual = (int)date('y');
                
                if ($anio < $anioActual || ($anio == $anioActual && $mes < $mesActual)) {
                    return back()->withInput()->with('error', '❌ Tarjeta vencida');
                }
            } else {
                return back()->withInput()->with('error', '❌ Formato de fecha inválido. Use MM/AA');
            }
            
            if (substr($request->tarjeta_numero, -4) === '0000') {
                return back()->withInput()->with('error', '❌ Tarjeta rechazada');
            }
        }

        DB::beginTransaction();
        try {
            $reservasCreadas = [];
            
            // CALCULAR COSTO DE SERVICIOS UNA SOLA VEZ
            $servicios = collect([]);
            $costoServicios = 0;
            if (!empty($reservaTemp['servicios'])) {
                $servicios = Servicio::whereIn('id', $reservaTemp['servicios'])->get();
                $costoServicios = $servicios->sum('precio');
            }
            
            // Distribuir costo de servicios entre todos los horarios
            $costoServiciosPorReserva = count($reservaTemp['horarios']) > 0 
                ? $costoServicios / count($reservaTemp['horarios']) 
                : 0;
            
            // CREAR MÚLTIPLES RESERVAS (una por cada horario)
            foreach ($reservaTemp['horarios'] as $horarioId) {
                $horario = Horario::findOrFail($horarioId);
                
                if ($horario->estado !== 'disponible') {
                    DB::rollBack();
                    return back()->with('error', '❌ Uno de los horarios ya no está disponible');
                }

                // CALCULAR COSTO INDIVIDUAL DE ESTE ESPACIO
                $espacio = $horario->espacio;
                $inicio = \Carbon\Carbon::parse($horario->hora_inicio);
                $fin = \Carbon\Carbon::parse($horario->hora_fin);
                $duracion = $inicio->diffInHours($fin);
                $costoEspacio = $duracion * $espacio->precio_hora;
                
                // TOTAL = costo del espacio + parte proporcional de servicios
                $totalReserva = $costoEspacio + $costoServiciosPorReserva;

                $reserva = Reserva::create([
                    'user_id' => auth()->id(),
                    'espacio_id' => $horario->espacio_id,
                    'horario_id' => $horarioId,
                    'fecha_evento' => $reservaTemp['fecha_evento'],
                    'hora_inicio' => $horario->hora_inicio,
                    'hora_fin' => $horario->hora_fin,
                    'num_personas' => $reservaTemp['num_personas'],
                    'total' => $totalReserva, // ← COSTO REAL DE ESTE ESPACIO
                    'metodo_pago' => $metodoPago,
                    'estado' => 'confirmada',
                    'estado_pago' => $metodoPago == 'efectivo' ? 'pendiente' : 'pagado',
                    'observaciones' => $reservaTemp['observaciones'],
                    'servicios_adicionales' => $reservaTemp['servicios'],
                ]);

                $horario->marcarComoOcupado($reserva->id);
                $reservasCreadas[] = $reserva;
            }

            session()->forget(['reserva_temp', 'reserva_costos', 'reserva_metodo_pago']);

            DB::commit();

            $totalFinal = collect($reservasCreadas)->sum('total');

            $mensaje = match($metodoPago) {
                'efectivo' => '✅ Reserva confirmada. Paga S/.' . number_format($totalFinal, 2) . ' al llegar.',
                'yape' => '✅ Pago Yape procesado. Reserva confirmada por S/.' . number_format($totalFinal, 2),
                'tarjeta' => '✅ Pago procesado exitosamente. Reserva confirmada por S/.' . number_format($totalFinal, 2),
                default => '✅ Reserva confirmada.'
            };

            return redirect()->route('cliente.reservas.confirmacion', $reservasCreadas[0]->id)->with('success', $mensaje);
                
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->with('error', '❌ Error: ' . $e->getMessage());
        }
    }

    private function validarLuhn($numero)
    {
        $numero = preg_replace('/\D/', '', $numero);
        
        if (strlen($numero) < 13 || strlen($numero) > 19) {
            return false;
        }
        
        $suma = 0;
        $longitud = strlen($numero);
        $paridad = $longitud % 2;
        
        for ($i = 0; $i < $longitud; $i++) {
            $digito = (int)$numero[$i];
            
            if ($i % 2 == $paridad) {
                $digito *= 2;
                if ($digito > 9) {
                    $digito -= 9;
                }
            }
            
            $suma += $digito;
        }
        
        return ($suma % 10) == 0;
    }

    public function confirmacion(Reserva $reserva)
    {
        if ($reserva->user_id !== auth()->id()) {
            abort(403);
        }

        // BUSCAR TODAS LAS RESERVAS DEL MISMO GRUPO
        // (misma fecha_evento y creadas en el mismo minuto)
        $reservasGrupo = Reserva::where('user_id', auth()->id())
            ->where('fecha_evento', $reserva->fecha_evento)
            ->whereRaw('DATE_FORMAT(created_at, "%Y-%m-%d %H:%i") = ?', [
                $reserva->created_at->format('Y-m-d H:i')
            ])
            ->with('espacio', 'horario')
            ->get();

        $esMultiple = $reservasGrupo->count() > 1;
        $totalGrupo = $reservasGrupo->sum('total');

        return view('cliente.reservas.confirmacion', compact(
            'reserva',
            'reservasGrupo',
            'esMultiple',
            'totalGrupo'
        ));
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

    public function cancelarGrupo(Request $request)
{
    $request->validate([
        'reservas' => 'required|array',
        'reservas.*' => 'exists:reservas,id',
    ]);

    DB::beginTransaction();
    try {
        foreach ($request->reservas as $reservaId) {
            $reserva = Reserva::findOrFail($reservaId);
            
            if ($reserva->user_id !== auth()->id()) {
                abort(403);
            }

            if ($reserva->horario_id) {
                $horario = Horario::find($reserva->horario_id);
                if ($horario) {
                    $horario->liberar();
                }
            }

            $reserva->update(['estado' => 'cancelada']);
        }

        DB::commit();
        return back()->with('success', 'Reservas canceladas exitosamente');
        
    } catch (\Exception $e) {
        DB::rollBack();
        return back()->with('error', 'Error: ' . $e->getMessage());
    }
}
}