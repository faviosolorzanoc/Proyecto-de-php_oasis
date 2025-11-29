<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Espacio extends Model
{
    use HasFactory;

    protected $fillable = [
        'nombre',
        'descripcion',
        'capacidad',
        'precio_hora',
        'imagen',
        'disponible',
    ];

    protected $casts = [
        'disponible' => 'boolean',
        'precio_hora' => 'decimal:2',
    ];

    public function horarios()
    {
        return $this->hasMany(Horario::class);
    }

    public function horariosDisponibles()
    {
        return $this->hasMany(Horario::class)->where('estado', 'disponible');
    }
}