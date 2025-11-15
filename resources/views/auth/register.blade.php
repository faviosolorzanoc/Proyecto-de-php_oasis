@extends('layouts.app')

@section('title', 'Registrarse')

@section('styles')
<style>
    body {
        background: linear-gradient(135deg, var(--color-secondary) 0%, var(--color-tertiary) 100%);
        min-height: 100vh;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 40px 0;
    }
    
    .register-card {
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
    
    .register-header {
        background: linear-gradient(135deg, var(--color-secondary), var(--color-tertiary));
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
    
    .btn-register {
        background: linear-gradient(135deg, var(--color-secondary), var(--color-tertiary));
        border: none;
        padding: 12px;
        font-size: 1.1rem;
        font-weight: 600;
        border-radius: 10px;
        box-shadow: 0 4px 15px rgba(77,130,188,0.3);
        transition: all 0.3s ease;
    }
    
    .btn-register:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(77,130,188,0.4);
    }
</style>
@endsection

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card register-card">
                <div class="register-header">
                    <h1 class="mb-2">🏕️</h1>
                    <h2 class="mb-0">Crear Cuenta</h2>
                    <p class="mb-0">Únete a nuestra comunidad</p>
                </div>
                
                <div class="card-body p-5">
                    @if($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('register') }}">
                        @csrf
                        
                        <div class="mb-3">
                            <label for="name" class="form-label fw-bold">Nombre Completo</label>
                            <input type="text" class="form-control" id="name" name="name" value="{{ old('name') }}" placeholder="Juan Pérez" required autofocus>
                        </div>

                        <div class="mb-3">
                            <label for="email" class="form-label fw-bold">Correo Electrónico</label>
                            <input type="email" class="form-control" id="email" name="email" value="{{ old('email') }}" placeholder="juan@email.com" required>
                        </div>

                        <div class="mb-3">
                            <label for="telefono" class="form-label fw-bold">Teléfono (Opcional)</label>
                            <input type="text" class="form-control" id="telefono" name="telefono" value="{{ old('telefono') }}" placeholder="+51 999 888 777">
                        </div>

                        <div class="mb-3">
                            <label for="password" class="form-label fw-bold">Contraseña</label>
                            <input type="password" class="form-control" id="password" name="password" placeholder="Mínimo 8 caracteres" required>
                        </div>

                        <div class="mb-4">
                            <label for="password_confirmation" class="form-label fw-bold">Confirmar Contraseña</label>
                            <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" placeholder="Repite tu contraseña" required>
                        </div>

                        <button type="submit" class="btn btn-primary btn-register w-100 mb-3">Registrarse</button>

                        <div class="text-center">
                            <p class="mb-0">¿Ya tienes cuenta? <a href="{{ route('login') }}" style="color: var(--color-primary); font-weight: 600;">Inicia sesión aquí</a></p>
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