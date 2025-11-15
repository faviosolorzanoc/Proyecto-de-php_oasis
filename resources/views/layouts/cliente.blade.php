<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Sitio Campestre')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="{{ asset('css/cliente-styles.css') }}" rel="stylesheet">
    @yield('styles')
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark">
        <div class="container">
            <a class="navbar-brand" href="{{ route('cliente.home') }}">🏕️ Sitio Campestre Oasis</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('cliente.home') }}">🏠 Inicio</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('cliente.reservas') }}">🎉 Organizar Evento</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('cliente.mis-reservas') }}">📋 Mis Reservas</a>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown">
                            Más Información
                        </a>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="{{ route('cliente.servicios') }}">✨ Ver Servicios</a></li>
                            <li><a class="dropdown-item" href="{{ route('cliente.informacion') }}">ℹ️ Información</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item disabled text-muted" href="#">🍽️ Pedir Comida (Próximamente)</a></li>
                            <li><a class="dropdown-item disabled text-muted" href="#">📦 Mis Pedidos (Próximamente)</a></li>
                        </ul>
                    </li>
                    <li class="nav-item">
                        <form method="POST" action="{{ route('logout') }}" class="d-inline">
                            @csrf
                            <button type="submit" class="btn btn-outline-light btn-sm ms-2">🚪 Salir</button>
                        </form>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Content -->
    <div class="container mt-4">
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @yield('content')
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    @yield('scripts')
</body>
</html>