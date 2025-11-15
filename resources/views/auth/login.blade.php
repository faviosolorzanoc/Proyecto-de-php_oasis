@extends('layouts.app')

@section('title', 'Iniciar Sesión')

@section('styles')
<style>
    body {
        background: linear-gradient(135deg, var(--color-primary) 0%, var(--color-tertiary) 100%);
        min-height: 100vh;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    
    .login-card {
        border-radius: 20px;
        box-shadow: 0 20px 60px rgba(0,0,0,0.3);
        overflow: hidden;
        animation: slideUp 0.5s ease-out;
    }
    
    @keyframes slideUp {
        from {
            opacity: 0;
            transform: translateY(30px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
    
    .login-header {
        background: linear-gradient(135deg, var(--color-primary), var(--color-secondary));
        color: white;
        padding: 30px;
        text-align: center;
    }
    
    .form-control {
        border-radius: 10px;
        padding: 12px 20px;
        border: 2px solid #e0e0e0;
        transition: all 0.3s ease;
    }
    
    .form-control:focus {
        border-color: var(--color-primary);
        box-shadow: 0 0 0 0.2rem rgba(0, 81, 135, 0.25);
    }
    
    .btn-login {
        background: linear-gradient(135deg, var(--color-primary), var(--color-secondary));
        border: none;
        padding: 12px;
        font-size: 1.1rem;
        font-weight: 600;
        border-radius: 10px;
        box-shadow: 0 4px 15px rgba(0,81,135,0.3);
        transition: all 0.3s ease;
    }
    
    .btn-login:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(0,81,135,0.4);
    }
</style>
@endsection

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-5">
            <div class="card login-card">
                <div class="login-header">
                    <h1 class="mb-2">🏕️</h1>
                    <h2 class="mb-0">Sitio Campestre Oasis</h2>
                    <p class="mb-0">Bienvenido de vuelta</p>
                </div>
                
                <div class="card-body p-5">
                    @if($errors->any())
                        <div class="alert alert-danger">
                            {{ $errors->first() }}
                        </div>
                    @endif

                    @if(session('error'))
                        <div class="alert alert-danger">
                            {{ session('error') }}
                        </div>
                    @endif

                    <form method="POST" action="{{ route('login') }}">
                        @csrf
                        
                        <div class="mb-4">
                            <label for="email" class="form-label fw-bold">Correo Electrónico</label>
                            <input type="email" class="form-control" id="email" name="email" value="{{ old('email') }}" placeholder="tu@email.com" required autofocus>
                        </div>

                        <div class="mb-4">
                            <label for="password" class="form-label fw-bold">Contraseña</label>
                            <input type="password" class="form-control" id="password" name="password" placeholder="••••••••" required>
                        </div>

                        <button type="submit" class="btn btn-primary btn-login w-100 mb-3">Ingresar</button>

                        <div class="text-center">
                            <p class="mb-0">¿No tienes cuenta? <a href="{{ route('register') }}" style="color: var(--color-primary); font-weight: 600;">Regístrate aquí</a></p>
                        </div>
                    </form>
                </div>
            </div>
            
            <div class="text-center mt-3 text-white">
                <small>© 2024 Sitio Campestre Oasis. Todos los derechos reservados.</small>
            </div>
        </div>
    </div>
</div>
@endsection