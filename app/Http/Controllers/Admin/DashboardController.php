<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Pedido;
use App\Models\Producto;
use App\Models\Servicio;
use App\Models\User;

class DashboardController extends Controller
{
    public function index()
    {
        $totalClientes = User::where('role', 'cliente')->count();
        $totalProductos = Producto::count();
        $totalServicios = Servicio::count();
        $pedidosPendientes = Pedido::where('estado', 'pendiente')->count();
        $pedidosRecientes = Pedido::with('user')->latest()->take(5)->get();

        return view('admin.dashboard', compact(
            'totalClientes',
            'totalProductos',
            'totalServicios',
            'pedidosPendientes',
            'pedidosRecientes'
        ));
    }
}