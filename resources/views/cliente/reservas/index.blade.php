@extends('layouts.cliente')

@section('title', 'Organizar Evento')

@section('styles')
<style>
    .reserva-header {
        background: linear-gradient(135deg, var(--color-primary) 0%, var(--color-secondary) 100%);
        color: white;
        padding: 50px 0;
        border-radius: 0 0 30px 30px;
        margin-bottom: 40px;
    }
    .form-card {
        border-radius: 20px;
        box-shadow: 0 5px 20px rgba(0,0,0,0.1);
    }
</style>
@endsection

@section('content')
<div class="reserva-header">
    <div class="container text-center">
        <h1 class="display-4 fw-bold">Organiza tu Evento Perfecto</h1>
        <p class="lead">Completa el formulario para verificar disponibilidad</p>
    </div>
</div>

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card form-card">
                <div class="card-body p-5">
                    <h3 class="card-title text-center mb-4" style="color: var(--color-primary);">
                        Datos de tu Evento
                    </h3>

                    @if($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ route('cliente.reservas.verificar') }}" method="POST">
                        @csrf
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="fecha_evento" class="form-label">📅 Fecha del Evento *</label>
                                <input type="date" 
                                       class="form-control @error('fecha_evento') is-invalid @enderror" 
                                       id="fecha_evento" 
                                       name="fecha_evento" 
                                       value="{{ old('fecha_evento') }}"
                                       min="{{ date('Y-m-d') }}"
                                       required>
                                @error('fecha_evento')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="num_personas" class="form-label">👥 Número de Personas *</label>
                                <input type="number" 
                                       class="form-control @error('num_personas') is-invalid @enderror" 
                                       id="num_personas" 
                                       name="num_personas" 
                                       value="{{ old('num_personas') }}"
                                       min="1"
                                       required>
                                @error('num_personas')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="hora_inicio" class="form-label">🕐 Hora de Inicio *</label>
                                <input type="time" 
                                       class="form-control @error('hora_inicio') is-invalid @enderror" 
                                       id="hora_inicio" 
                                       name="hora_inicio" 
                                       value="{{ old('hora_inicio') }}"
                                       required>
                                @error('hora_inicio')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="hora_fin" class="form-label">🕐 Hora de Fin *</label>
                                <input type="time" 
                                       class="form-control @error('hora_fin') is-invalid @enderror" 
                                       id="hora_fin" 
                                       name="hora_fin" 
                                       value="{{ old('hora_fin') }}"
                                       required>
                                @error('hora_fin')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="alert alert-info mt-3">
                            <strong>💡 Nota:</strong> Te mostraremos los espacios disponibles según tu fecha, hora y cantidad de personas.
                        </div>

                        <button type="submit" class="btn btn-primary btn-lg w-100 mt-4">
                            Verificar Disponibilidad
                        </button>
                    </form>
                </div>
            </div>

            <!-- Información adicional -->
            <div class="card mt-4">
                <div class="card-body">
                    <h5 style="color: var(--color-primary);">📋 Información Importante</h5>
                    <ul class="mb-0">
                        <li>Las reservas deben hacerse con mínimo 2 días de anticipación</li>
                        <li>La capacidad varía según el espacio seleccionado</li>
                        <li>Puedes agregar servicios adicionales en el siguiente paso</li>
                        <li>Horario disponible: 8:00 AM - 8:00 PM</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection