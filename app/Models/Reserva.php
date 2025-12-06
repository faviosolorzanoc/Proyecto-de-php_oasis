<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Reserva extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'espacio_id',
        'horario_id',
        'fecha_evento',
        'hora_inicio',
        'hora_fin',
        'num_personas',
        'total',
        'estado',
        'metodo_pago',
        'estado_pago',
        'observaciones',
        'servicios_adicionales',
    ];

    protected $casts = [
        'fecha_evento' => 'date',
        'total' => 'decimal:2',
        'servicios_adicionales' => 'array',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function espacio()
    {
        return $this->belongsTo(Espacio::class);
    }

    public function horario()
    {
        return $this->belongsTo(Horario::class);
    }

    // Método para obtener servicios desde el array JSON
    public function getServiciosAttribute()
    {
        if (!$this->servicios_adicionales || !is_array($this->servicios_adicionales)) {
            return collect([]);
        }

        // Cargar servicios desde la BD usando los IDs guardados
        return Servicio::whereIn('id', $this->servicios_adicionales)->get();
    }
}