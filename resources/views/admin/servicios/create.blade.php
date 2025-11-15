@extends('layouts.admin')

@section('title', 'Nuevo Servicio')

@section('content')
<div class="mb-4">
    <a href="{{ route('admin.servicios.index') }}" class="btn btn-secondary">
        ← Volver a la lista
    </a>
</div>

<div class="card fade-in">
    <div class="card-header">
        <h3 class="mb-0">➕ Crear Nuevo Servicio</h3>
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

        <form action="{{ route('admin.servicios.store') }}" method="POST">
            @csrf
            
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="nombre" class="form-label">Nombre del Servicio *</label>
                    <input type="text" 
                           class="form-control @error('nombre') is-invalid @enderror" 
                           id="nombre" 
                           name="nombre" 
                           value="{{ old('nombre') }}" 
                           placeholder="Ej: Decoración con Globos"
                           required>
                    @error('nombre')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6 mb-3">
                    <label for="precio" class="form-label">Precio *</label>
                    <div class="input-group">
                        <span class="input-group-text">$</span>
                        <input type="number" 
                               step="0.01" 
                               class="form-control @error('precio') is-invalid @enderror" 
                               id="precio" 
                               name="precio" 
                               value="{{ old('precio') }}" 
                               placeholder="0.00"
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
                              rows="4"
                              placeholder="Describe el servicio complementario que ofreces (decoración, catering, sonido, etc.)">{{ old('descripcion') }}</textarea>
                    @error('descripcion')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-12 mb-3">
                    <label for="imagen" class="form-label">URL de Imagen (Opcional)</label>
                    <input type="text" 
                           class="form-control @error('imagen') is-invalid @enderror" 
                           id="imagen" 
                           name="imagen" 
                           value="{{ old('imagen') }}"
                           placeholder="https://ejemplo.com/imagen.jpg">
                    <small class="text-muted">Puedes usar enlaces de imágenes desde internet</small>
                    @error('imagen')
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
                               {{ old('disponible', true) ? 'checked' : '' }}>
                        <label class="form-check-label" for="disponible">
                            Servicio disponible
                        </label>
                    </div>
                </div>
            </div>

            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-primary">
                    ✓ Guardar Servicio
                </button>
                <a href="{{ route('admin.servicios.index') }}" class="btn btn-secondary">
                    Cancelar
                </a>
            </div>
        </form>
    </div>
</div>
@endsection