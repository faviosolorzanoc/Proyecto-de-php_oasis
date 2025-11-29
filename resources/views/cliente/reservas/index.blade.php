@extends('layouts.cliente')

@section('title', 'Organizar Evento')

@section('content')
<div class="reserva-header fade-in">
    <div class="container text-center">
        <h1 class="display-4 fw-bold mb-3">Organiza tu Evento Perfecto</h1>
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
                        </div>

                        <div class="alert alert-info mt-3">
                            <strong>💡 Siguiente paso:</strong> Te mostraremos los espacios disponibles según el número de personas que ingresaste.
                        </div>

                        <button type="submit" class="btn btn-primary btn-lg w-100 mt-4">
                            Ver Espacios Disponibles
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