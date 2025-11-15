<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Servicio;
use App\Models\Producto;
use App\Models\Espacio;
use App\Models\Mesa;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Crear usuario administrador
        User::create([
            'name' => 'Administrador',
            'email' => 'admin@campestre.com',
            'password' => Hash::make('password'),
            'role' => 'administrador',
            'telefono' => '999888777',
        ]);

        // Crear usuario cliente
        User::create([
            'name' => 'Cliente Demo',
            'email' => 'cliente@campestre.com',
            'password' => Hash::make('password'),
            'role' => 'cliente',
            'telefono' => '999777666',
        ]);

        // Crear SERVICIOS (complementos/extras)
        Servicio::create([
            'nombre' => 'Decoración con Globos',
            'descripcion' => 'Decoración completa del espacio con globos temáticos',
            'precio' => 80.00,
            'disponible' => true,
        ]);

        Servicio::create([
            'nombre' => 'Servicio de Meseros',
            'descripcion' => 'Personal de atención durante todo el evento',
            'precio' => 120.00,
            'disponible' => true,
        ]);

        Servicio::create([
            'nombre' => 'Música y Sonido',
            'descripcion' => 'Equipo de sonido profesional con DJ',
            'precio' => 150.00,
            'disponible' => true,
        ]);

        Servicio::create([
            'nombre' => 'Fotografía y Video',
            'descripcion' => 'Cobertura profesional de tu evento',
            'precio' => 200.00,
            'disponible' => true,
        ]);

        Servicio::create([
            'nombre' => 'Servicio de Catering',
            'descripcion' => 'Buffet completo con variedad de platos',
            'precio' => 25.00,
            'disponible' => true,
        ]);

        // Crear ESPACIOS (lugares físicos)
        Espacio::create([
            'nombre' => 'Área de Piscina',
            'descripcion' => 'Zona de piscina con camastros y sombrillas, ideal para eventos de día',
            'capacidad' => 50,
            'precio_hora' => 30.00,
            'disponible' => true,
        ]);

        Espacio::create([
            'nombre' => 'Cancha Deportiva',
            'descripcion' => 'Cancha multiusos para fútbol, vóley y básquet',
            'capacidad' => 30,
            'precio_hora' => 25.00,
            'disponible' => true,
        ]);

        Espacio::create([
            'nombre' => 'Salón de Eventos',
            'descripcion' => 'Salón techado con mesas, sillas y aire acondicionado',
            'capacidad' => 100,
            'precio_hora' => 50.00,
            'disponible' => true,
        ]);

        Espacio::create([
            'nombre' => 'Zona BBQ',
            'descripcion' => 'Área de parrillas techada con mesas rústicas',
            'capacidad' => 40,
            'precio_hora' => 35.00,
            'disponible' => true,
        ]);

        Espacio::create([
            'nombre' => 'Jardín Exterior',
            'descripcion' => 'Amplio jardín con césped natural, perfecto para ceremonias',
            'capacidad' => 80,
            'precio_hora' => 40.00,
            'disponible' => true,
        ]);

        // Crear espacios
        Espacio::create([
            'nombre' => 'Piscina Principal',
            'descripcion' => 'Piscina grande para adultos',
            'capacidad' => 50,
            'precio_hora' => 15.00,
            'disponible' => true,
        ]);

        Espacio::create([
            'nombre' => 'Cancha de Vóley',
            'descripcion' => 'Cancha profesional de vóley',
            'capacidad' => 12,
            'precio_hora' => 20.00,
            'disponible' => true,
        ]);

        // Crear mesas
        for ($i = 1; $i <= 10; $i++) {
            Mesa::create([
                'numero' => 'M' . str_pad($i, 2, '0', STR_PAD_LEFT),
                'capacidad' => rand(4, 8),
                'ubicacion' => $i <= 5 ? 'Interior' : 'Exterior',
                'estado' => 'disponible',
            ]);
        }
    }
}