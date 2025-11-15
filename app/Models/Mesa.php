<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Mesa extends Model
{
    use HasFactory;

    protected $fillable = [
        'numero',
        'capacidad',
        'ubicacion',
        'estado',
    ];

    public function pedidos()
    {
        return $this->hasMany(Pedido::class);
    }
}