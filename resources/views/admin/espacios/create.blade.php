@extends('layouts.admin')

@section('title', 'Nuevo Espacio')

@section('content')
<div class="mb-4">
    <a href="{{ route('admin.espacios.index') }}" class="btn btn-secondary">
        ← Volver a la lista
    </a>
</div>

<div class="card fade-in">
    <div class="card-header">
        <h3 class="mb-0">➕ Crear Nuevo Espacio</h3>
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

        <form action="{{ route('admin.espacios.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="nombre" class="form-label">Nombre del Espacio *</label>
                    <input type="text" 
                           class="form-control @error('nombre') is-invalid @enderror" 
                           id="nombre" 
                           name="nombre" 
                           value="{{ old('nombre') }}" 
                           placeholder="Ej: Piscina Principal"
                           required>
                    @error('nombre')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-3 mb-3">
                    <label for="capacidad" class="form-label">Capacidad (personas) *</label>
                    <input type="number" 
                           class="form-control @error('capacidad') is-invalid @enderror" 
                           id="capacidad" 
                           name="capacidad" 
                           value="{{ old('capacidad') }}" 
                           placeholder="Ej: 50"
                           min="1"
                           required>
                    @error('capacidad')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-3 mb-3">
                    <label for="precio_hora" class="form-label">Precio por Hora *</label>
                    <div class="input-group">
                        <span class="input-group-text">S/.</span>
                        <input type="number" 
                               step="0.01" 
                               class="form-control @error('precio_hora') is-invalid @enderror" 
                               id="precio_hora" 
                               name="precio_hora" 
                               value="{{ old('precio_hora') }}" 
                               placeholder="0.00"
                               required>
                        @error('precio_hora')
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
                              placeholder="Describe las características del espacio...">{{ old('descripcion') }}</textarea>
                    @error('descripcion')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-12 mb-3">
                    <label for="imagen" class="form-label">📷 Imagen del Espacio (Opcional)</label>
                    <input type="file" 
                           class="form-control @error('imagen') is-invalid @enderror" 
                           id="imagen" 
                           name="imagen"
                           accept="image/*">
                    <small class="text-muted">Formatos: JPG, JPEG, PNG, GIF. Máximo 2MB</small>
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
                            Espacio disponible
                        </label>
                    </div>
                </div>
            </div>

            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-primary">
                    ✓ Guardar Espacio
                </button>
                <a href="{{ route('admin.espacios.index') }}" class="btn btn-secondary">
                    Cancelar
                </a>
            </div>
        </form>
    </div>
</div>
@endsection