@extends('layouts.admin')

@section('title', 'Editar Servicio')

@section('content')
<div class="mb-4">
    <a href="{{ route('admin.servicios.index') }}" class="btn btn-secondary">
        ← Volver a la lista
    </a>
</div>

<div class="card fade-in">
    <div class="card-header">
        <h3 class="mb-0">✏️ Editar Servicio: {{ $servicio->nombre }}</h3>
    </div>
    <div class="card-body">
        @if($errors->any())
            <div class="alert alert-danger">
                <strong>¡Oops! Hay algunos errores:</strong>
                <ul class="mb-0 mt-2">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('admin.servicios.update', $servicio) }}" method="POST">
            @csrf
            @method('PUT')
            
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="nombre" class="form-label">Nombre del Servicio *</label>
                    <input type="text" 
                           class="form-control @error('nombre') is-invalid @enderror" 
                           id="nombre" 
                           name="nombre" 
                           value="{{ old('nombre', $servicio->nombre) }}" 
                           required>
                    @error('nombre')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6 mb-3">
                    <label for="precio" class="form-label">Precio *</label>
                    <div class="input-group">
                        <span class="input-group-text">S/.</span>
                        <input type="number" 
                               step="0.01" 
                               class="form-control @error('precio') is-invalid @enderror" 
                               id="precio" 
                               name="precio" 
                               value="{{ old('precio', $servicio->precio) }}" 
                               required>
                        @error('precio')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="col-md-12 mb-3">
                    <label for="descripcion" class="form-label">Descripción</label>
                    <textarea class="form-control @error('descripcion') is-invalid @enderror" 
                              id="descripcion" 
                              name="descripcion" 
                              rows="4">{{ old('descripcion', $servicio->descripcion) }}</textarea>
                    @error('descripcion')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-12 mb-4">
                    <div class="form-check form-switch">
                        <input class="form-check-input" 
                               type="checkbox" 
                               id="disponible" 
                               name="disponible" 
                               value="1" 
                               {{ old('disponible', $servicio->disponible) ? 'checked' : '' }}>
                        <label class="form-check-label" for="disponible">
                            Servicio disponible
                        </label>
                    </div>
                </div>
            </div>

            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-primary">
                    ✓ Actualizar Servicio
                </button>
                <a href="{{ route('admin.servicios.index') }}" class="btn btn-secondary">
                    Cancelar
                </a>
            </div>
        </form>
    </div>
</div>
@endsection