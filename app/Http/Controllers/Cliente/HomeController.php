<?php

namespace App\Http\Controllers\Cliente;

use App\Http\Controllers\Controller;

class HomeController extends Controller
{
    public function index()
    {
        return view('cliente.home');
    }

    public function informacion()
    {
        return view('cliente.informacion');
    }
}