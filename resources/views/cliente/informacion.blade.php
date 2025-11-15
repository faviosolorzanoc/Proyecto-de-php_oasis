@extends('layouts.cliente')

@section('title', 'Información')

@section('content')
<h1 class="mb-4" style="color: var(--color-primary);">Información del Sitio Campestre</h1>

<div class="row">
    <div class="col-md-6 mb-4">
        <div class="card shadow h-100">
            <div class="card-body">
                <h3 class="card-title" style="color: var(--color-secondary);">📍 Ubicación</h3>
                <p>Estamos ubicados en un hermoso entorno natural, rodeado de árboles y áreas verdes.</p>
                <p><strong>Dirección:</strong> Carretera Rural km 15, Zona Campestre</p>
                <p><strong>Teléfono:</strong> +51 999 888 777</p>
                <p><strong>Email:</strong> info@sitiocampestre.com</p>
            </div>
        </div>
    </div>

    <div class="col-md-6 mb-4">
        <div class="card shadow h-100">
            <div class="card-body">
                <h3 class="card-title" style="color: var(--color-secondary);">🕐 Horarios</h3>
                <p><strong>Lunes a Viernes:</strong> 9:00 AM - 6:00 PM</p>
                <p><strong>Sábados y Domingos:</strong> 8:00 AM - 8:00 PM</p>
                <p><strong>Feriados:</strong> 8:00 AM - 8:00 PM</p>
                <div class="alert alert-info mt-3">
                    <small>Se recomienda hacer reservaciones con anticipación para grupos grandes.</small>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-12 mb-4">
        <div class="card shadow">
            <div class="card-body">
                <h3 class="card-title" style="color: var(--color-secondary);">✨ Nuestras Instalaciones</h3>
                <div class="row mt-3">
                    <div class="col-md-4 mb-3">
                        <div class="text-center p-3 bg-light rounded">
                            <h4>🏊‍♂️</h4>
                            <h5>Piscinas</h5>
                            <p class="small">Piscina para adultos y niños con todas las medidas de seguridad</p>
                        </div>
                    </div>
                    <div class="col-md-4 mb-3">
                        <div class="text-center p-3 bg-light rounded">
                            <h4>⚽</h4>
                            <h5>Canchas Deportivas</h5>
                            <p class="small">Fútbol, vóley y básquet disponibles</p>
                        </div>
                    </div>
                    <div class="col-md-4 mb-3">
                        <div class="text-center p-3 bg-light rounded">
                            <h4>🍖</h4>
                            <h5>Zona BBQ</h5>
                            <p class="small">Parrillas equipadas y áreas techadas</p>
                        </div>
                    </div>
                    <div class="col-md-4 mb-3">
                        <div class="text-center p-3 bg-light rounded">
                            <h4>🎮</h4>
                            <h5>Área de Juegos</h5>
                            <p class="small">Juegos para niños de todas las edades</p>
                        </div>
                    </div>
                    <div class="col-md-4 mb-3">
                        <div class="text-center p-3 bg-light rounded">
                            <h4>🍽️</h4>
                            <h5>Restaurante</h5>
                            <p class="small">Comida casera y platos típicos</p>
                        </div>
                    </div>
                    <div class="col-md-4 mb-3">
                        <div class="text-center p-3 bg-light rounded">
                            <h4>🅿️</h4>
                            <h5>Estacionamiento</h5>
                            <p class="small">Amplio y seguro para todos los visitantes</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-12 mb-4">
        <div class="card shadow">
            <div class="card-body">
                <h3 class="card-title" style="color: var(--color-secondary);">📋 Normas del Sitio</h3>
                <ul class="mt-3">
                    <li>Mantener el orden y limpieza en todas las áreas</li>
                    <li>No está permitido el ingreso de mascotas</li>
                    <li>Respetar los horarios de uso de las instalaciones</li>
                    <li>Los niños deben estar supervisados por adultos en todo momento</li>
                    <li>No está permitido el consumo de bebidas alcohólicas en exceso</li>
                    <li>Cuidar y respetar las instalaciones y equipamiento</li>
                </ul>
            </div>
        </div>
    </div>
</div>
@endsection