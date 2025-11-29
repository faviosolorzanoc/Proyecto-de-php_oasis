<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Horario extends Model
{
    use HasFactory;

    protected $fillable = [
        'espacio_id',
        'fecha',
        'hora_inicio',
        'hora_fin',
        'estado',
        'reserva_id',
        'observaciones',
    ];

    protected $casts = [
        'fecha' => 'date',
    ];

    public function espacio()
    {
        return $this->belongsTo(Espacio::class);
    }

    public function reserva()
    {
        return $this->belongsTo(Reserva::class);
    }

    // Verificar si está disponible
    public function estaDisponible()
    {
        return $this->estado === 'disponible';
    }

    // Marcar como ocupado
    public function marcarComoOcupado($reserva_id)
    {
        $this->update([
            'estado' => 'ocupado',
            'reserva_id' => $reserva_id,
        ]);
    }

    // Liberar horario
    public function liberar()
    {
        $this->update([
            'estado' => 'disponible',
            'reserva_id' => null,
        ]);
    }
}