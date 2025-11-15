<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin - Sitio Campestre')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="{{ asset('css/admin-styles.css') }}" rel="stylesheet">
    @yield('styles')
</head>
<body>
    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar -->
            <nav class="col-md-2 d-md-block sidebar">
                <div class="position-sticky">
                    <h4 class="text-white">Panel Admin</h4>
                    <hr class="text-white">
                    <ul class="nav flex-column">
                        <li class="nav-item">
                            <a href="{{ route('admin.dashboard') }}" class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                                📊 Dashboard
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('admin.servicios.index') }}" class="nav-link {{ request()->routeIs('admin.servicios.*') ? 'active' : '' }}">
                                ✨ Servicios Extras
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('admin.espacios.index') }}" class="nav-link {{ request()->routeIs('admin.espacios.*') ? 'active' : '' }}">
                                🏛️ Espacios Físicos
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('admin.productos.index') }}" class="nav-link {{ request()->routeIs('admin.productos.*') ? 'active' : '' }}">
                                🍽️ Productos
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('admin.mesas.index') }}" class="nav-link {{ request()->routeIs('admin.mesas.*') ? 'active' : '' }}">
                                🪑 Mesas
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('admin.pedidos.index') }}" class="nav-link {{ request()->routeIs('admin.pedidos.*') ? 'active' : '' }}">
                                📦 Pedidos
                            </a>
                        </li>
                        <li class="nav-item mt-4">
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="btn btn-danger w-100">Cerrar Sesión</button>
                            </form>
                        </li>
                    </ul>
                </div>
            </nav>

            <!-- Content -->
            <main class="col-md-10 ms-sm-auto px-md-4">
                <div class="pt-3 pb-2 mb-3">
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
            </main>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    @yield('scripts')
</body>
</html>