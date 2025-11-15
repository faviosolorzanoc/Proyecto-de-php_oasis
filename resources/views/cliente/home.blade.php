@extends('layouts.cliente')

@section('title', 'Inicio - Sitio Campestre')

@section('styles')
<style>
    .hero-section {
        background: linear-gradient(135deg, var(--color-primary) 0%, var(--color-secondary) 100%);
        color: white;
        padding: 80px 0;
        border-radius: 0 0 50px 50px;
    }
    .option-card {
        transition: all 0.3s ease;
        border: 2px solid transparent;
        cursor: pointer;
    }
    .option-card:hover {
        transform: translateY(-10px);
        border-color: var(--color-primary);
        box-shadow: 0 10px 30px rgba(0,81,135,0.2);
    }
    .option-card.disabled {
        opacity: 0.6;
        cursor: not-allowed;
    }
    .option-card.disabled:hover {
        transform: none;
        border-color: transparent;
    }
    .option-icon {
        font-size: 4rem;
        margin-bottom: 20px;
    }
    .feature-box {
        background: var(--color-light);
        border-radius: 15px;
        padding: 30px;
        text-align: center;
        height: 100%;
        transition: all 0.3s ease;
    }
    .feature-box:hover {
        transform: translateY(-5px);
        box-shadow: 0 5px 15px rgba(0,0,0,0.1);
    }
</style>
@endsection

@section('content')
<!-- Hero Section -->
<div class="hero-section text-center mb-5">
    <div class="container">
        <h1 class="display-3 fw-bold mb-3">Bienvenido a Sitio Campestre Oasis</h1>
        <p class="lead fs-4">El lugar perfecto para celebrar tus eventos especiales</p>
        <p class="fs-5">Disfruta de naturaleza, diversión y momentos inolvidables</p>
    </div>
</div>

<div class="container">
    <!-- Opciones Principales -->
    <div class="text-center mb-5">
        <h2 class="mb-4" style="color: var(--color-primary);">¿Qué deseas hacer hoy?</h2>
        <p class="text-muted">Selecciona una opción para comenzar</p>
    </div>

    <div class="row g-4 mb-5">
        <!-- Opción 1: Organizar Evento (Principal - ACTIVO) -->
        <div class="col-md-6">
            <a href="{{ route('cliente.reservas') }}" class="text-decoration-none">
                <div class="card option-card h-100 shadow-sm">
                    <div class="card-body text-center p-5">
                        <div class="option-icon" style="color: var(--color-primary);">🎉</div>
                        <h3 class="card-title mb-3" style="color: var(--color-primary);">Organizar Evento</h3>
                        <p class="card-text text-muted">Reserva nuestros espacios para tu cumpleaños, reunión familiar o evento corporativo</p>
                        <span class="badge bg-success fs-6 mt-2">DISPONIBLE</span>
                    </div>
                </div>
            </a>
        </div>

        <!-- Opción 2: Ver Mis Reservas (ACTIVO) -->
        <div class="col-md-6">
            <a href="{{ route('cliente.mis-reservas') }}" class="text-decoration-none">
                <div class="card option-card h-100 shadow-sm">
                    <div class="card-body text-center p-5">
                        <div class="option-icon" style="color: var(--color-tertiary);">📋</div>
                        <h3 class="card-title mb-3" style="color: var(--color-tertiary);">Mis Reservas</h3>
                        <p class="card-text text-muted">Consulta el estado de tus reservas y eventos programados</p>
                        <span class="badge bg-primary fs-6 mt-2">DISPONIBLE</span>
                    </div>
                </div>
            </a>
        </div>

        <!-- Opción 3: Pedir Comida (DESHABILITADO) -->
        <div class="col-md-6">
            <div class="card option-card disabled h-100 shadow-sm" style="cursor: not-allowed;">
                <div class="card-body text-center p-5">
                    <div class="option-icon" style="color: var(--color-secondary);">🍽️</div>
                    <h3 class="card-title mb-3" style="color: var(--color-secondary);">Pedir Comida</h3>
                    <p class="card-text text-muted">Ordena deliciosos platillos y bebidas</p>
                    <span class="badge bg-warning text-dark fs-6 mt-2">🚧 PRÓXIMAMENTE</span>
                </div>
            </div>
        </div>

        <!-- Opción 4: Ver Mis Pedidos (DESHABILITADO) -->
        <div class="col-md-6">
            <div class="card option-card disabled h-100 shadow-sm" style="cursor: not-allowed;">
                <div class="card-body text-center p-5">
                    <div class="option-icon" style="color: var(--color-tertiary);">📦</div>
                    <h3 class="card-title mb-3" style="color: var(--color-tertiary);">Mis Pedidos</h3>
                    <p class="card-text text-muted">Revisa el historial de tus pedidos</p>
                    <span class="badge bg-warning text-dark fs-6 mt-2">🚧 PRÓXIMAMENTE</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Nuestros Espacios Disponibles -->
    <div class="mb-5">
        <h2 class="text-center mb-2" style="color: var(--color-primary);">Nuestros Espacios Disponibles</h2>
        <p class="text-center text-muted mb-5">Conoce las áreas que tenemos para tu evento</p>
        
        <div class="row g-4">
            @php
                $espacios = \App\Models\Espacio::where('disponible', true)->get();
            @endphp
            
            @foreach($espacios as $espacio)
                <div class="col-md-4">
                    <div class="card h-100 shadow-sm" style="border-radius: 15px; overflow: hidden;">
                        @if($espacio->imagen)
                            <img src="{{ $espacio->imagen }}" 
                                 class="card-img-top" 
                                 alt="{{ $espacio->nombre }}" 
                                 style="height: 220px; object-fit: cover;">
                        @else
                            <div class="card-img-top d-flex align-items-center justify-content-center" 
                                 style="height: 220px; background: linear-gradient(135deg, var(--color-light), var(--color-tertiary));">
                                <h1 style="font-size: 4rem;">🏛️</h1>
                            </div>
                        @endif
                        <div class="card-body">
                            <h5 class="card-title" style="color: var(--color-primary);">{{ $espacio->nombre }}</h5>
                            <p class="card-text text-muted small">{{ Str::limit($espacio->descripcion, 100) }}</p>
                            <div class="d-flex justify-content-between align-items-center mt-3">
                                <span class="badge bg-info">👥 {{ $espacio->capacidad }} personas</span>
                                <span class="text-primary fw-bold">${{ number_format($espacio->precio_hora, 2) }}/hora</span>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
            
            @if($espacios->isEmpty())
                <div class="col-12 text-center">
                    <p class="text-muted">No hay espacios disponibles en este momento</p>
                </div>
            @endif
        </div>
    </div>

    <!-- Servicios Complementarios -->
    <div class="mb-5">
        <h2 class="text-center mb-2" style="color: var(--color-primary);">Servicios Complementarios</h2>
        <p class="text-center text-muted mb-5">Extras opcionales para hacer tu evento más especial</p>
        
        <div class="row g-4">
            @php
                $servicios = \App\Models\Servicio::where('disponible', true)->take(6)->get();
            @endphp
            
            @foreach($servicios as $servicio)
                <div class="col-md-4">
                    <div class="feature-box">
                        @if($servicio->imagen)
                            <img src="{{ $servicio->imagen }}" 
                                 alt="{{ $servicio->nombre }}" 
                                 style="width: 80px; height: 80px; object-fit: cover; border-radius: 50%; margin-bottom: 15px;">
                        @else
                            <h2 class="mb-3">✨</h2>
                        @endif
                        <h5 style="color: var(--color-primary);">{{ $servicio->nombre }}</h5>
                        <p class="small text-muted">{{ Str::limit($servicio->descripcion, 80) }}</p>
                        <p class="fw-bold mb-0" style="color: var(--color-secondary);">${{ number_format($servicio->precio, 2) }}</p>
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    <!-- Características Generales (mantener las que ya estaban) -->
    <div class="mb-5">
        <h2 class="text-center mb-5" style="color: var(--color-primary);">¿Qué Ofrecemos?</h2>
        <div class="row g-4">
            <div class="col-md-4">
                <div class="feature-box">
                    <h2 class="mb-3">🏊‍♂️</h2>
                    <h5 style="color: var(--color-primary);">Piscinas</h5>
                    <p class="small text-muted">Piscinas amplias para adultos y niños con áreas de descanso</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="feature-box">
                    <h2 class="mb-3">⚽</h2>
                    <h5 style="color: var(--color-primary);">Canchas Deportivas</h5>
                    <p class="small text-muted">Fútbol, vóley y básquet en canchas profesionales</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="feature-box">
                    <h2 class="mb-3">🍖</h2>
                    <h5 style="color: var(--color-primary);">Zona BBQ</h5>
                    <p class="small text-muted">Parrillas equipadas en espacios techados</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="feature-box">
                    <h2 class="mb-3">🎮</h2>
                    <h5 style="color: var(--color-primary);">Área de Juegos</h5>
                    <p class="small text-muted">Juegos infantiles seguros y entretenidos</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="feature-box">
                    <h2 class="mb-3">🍽️</h2>
                    <h5 style="color: var(--color-primary);">Restaurante</h5>
                    <p class="small text-muted">Comida casera y platos típicos deliciosos</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="feature-box">
                    <h2 class="mb-3">🅿️</h2>
                    <h5 style="color: var(--color-primary);">Estacionamiento</h5>
                    <p class="small text-muted">Amplio y seguro para tu tranquilidad</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Especificaciones del Negocio -->
    <div class="card shadow-lg mb-5" style="border: none; border-radius: 20px;">
        <div class="card-body p-5">
            <h2 class="text-center mb-4" style="color: var(--color-primary);">Información del Establecimiento</h2>
            <div class="row">
                <div class="col-md-6 mb-3">
                    <h5 style="color: var(--color-secondary);">📍 Ubicación</h5>
                    <p>Carretera Rural km 15, Zona Campestre</p>
                </div>
                <div class="col-md-6 mb-3">
                    <h5 style="color: var(--color-secondary);">📞 Contacto</h5>
                    <p>Teléfono: +51 999 888 777<br>Email: info@sitiocampestre.com</p>
                </div>
                <div class="col-md-6 mb-3">
                    <h5 style="color: var(--color-secondary);">🕐 Horario</h5>
                    <p>Lun-Vie: 9:00 AM - 6:00 PM<br>Sáb-Dom: 8:00 AM - 8:00 PM</p>
                </div>
                <div class="col-md-6 mb-3">
                    <h5 style="color: var(--color-secondary);">👥 Capacidad</h5>
                    <p>Hasta 200 personas<br>Espacios adaptables según tu evento</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection