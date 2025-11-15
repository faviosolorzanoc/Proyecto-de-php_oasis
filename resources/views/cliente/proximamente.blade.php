@extends('layouts.cliente')

@section('title', 'Próximamente')

@section('styles')
<style>
    .proximamente-container {
        min-height: 60vh;
        display: flex;
        align-items: center;
        justify-content: center;
        text-align: center;
        padding: 60px 20px;
    }
    .proximamente-icon {
        font-size: 6rem;
        margin-bottom: 30px;
        animation: bounce 2s infinite;
    }
    @keyframes bounce {
        0%, 20%, 50%, 80%, 100% {
            transform: translateY(0);
        }
        40% {
            transform: translateY(-20px);
        }
        60% {
            transform: translateY(-10px);
        }
    }
</style>
@endsection

@section('content')
<div class="proximamente-container">
    <div>
        <div class="proximamente-icon">🚧</div>
        <h1 class="display-4 mb-3" style="color: var(--color-primary);">Próximamente</h1>
        <p class="lead text-muted mb-4">
            Esta funcionalidad estará disponible muy pronto.<br>
            Estamos trabajando para ofrecerte la mejor experiencia.
        </p>
        <div class="d-flex gap-3 justify-content-center flex-wrap">
            <a href="{{ route('cliente.home') }}" class="btn btn-primary btn-lg">
                Volver al Inicio
            </a>
            <a href="{{ route('cliente.reservas') }}" class="btn btn-success btn-lg">
                Organizar Evento
            </a>
        </div>
    </div>
</div>
@endsection