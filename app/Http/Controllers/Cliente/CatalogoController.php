<?php

namespace App\Http\Controllers\Cliente;

use App\Http\Controllers\Controller;
use App\Models\Producto;
use App\Models\Servicio;

class CatalogoController extends Controller
{
    public function servicios()
    {
        $servicios = Servicio::where('disponible', true)->get();
        return view('cliente.servicios', compact('servicios'));
    }

    public function productos()
    {
        $productos = Producto::where('disponible', true)->get();
        return view('cliente.productos', compact('productos'));
    }
}