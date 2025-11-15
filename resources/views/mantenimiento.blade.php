@extends('layouts.admin')

@section('title', 'En Mantenimiento')

@section('content')
<div class="maintenance-container">
    <div class="maintenance-icon">🚧</div>
    <h1 class="maintenance-title">Módulo en Mantenimiento</h1>
    <p class="maintenance-text">
        Esta sección está siendo mejorada para ofrecerte una mejor experiencia.<br>
        Estará disponible próximamente.
    </p>
    <a href="{{ route('admin.dashboard') }}" class="btn btn-primary btn-lg">
        Volver al Dashboard
    </a>
</div>
@endsection