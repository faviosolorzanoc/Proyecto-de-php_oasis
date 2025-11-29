@extends('layouts.admin')

@section('title', 'Editar Espacio')

@section('content')
<div class="mb-4">
    <a href="{{ route('admin.espacios.index') }}" class="btn btn-secondary">
        ← Volver a la lista
    </a>
</div>

<div class="card fade-in">
    <div class="card-header">
        <h3 class="mb-0">✏️ Editar Espacio: {{ $espacio->nombre }}</h3>
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

        <form action="{{ route('admin.espacios.update', $espacio) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="nombre" class="form-label">Nombre del Espacio *</label>
                    <input type="text" 
                           class="form-control @error('nombre') is-invalid @enderror" 
                           id="nombre" 
                           name="nombre" 
                           value="{{ old('nombre', $espacio->nombre) }}" 
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
                           value="{{ old('capacidad', $espacio->capacidad) }}" 
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
                               value="{{ old('precio_hora', $espacio->precio_hora) }}" 
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
                              rows="4">{{ old('descripcion', $espacio->descripcion) }}</textarea>
                    @error('descripcion')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-12 mb-3">
                    <label for="imagen" class="form-label">📷 Imagen del Espacio</label>
                    
                    @if($espacio->imagen)
                        <div class="mb-2">
                            <img src="{{ asset('storage/' . $espacio->imagen) }}" 
                                 alt="{{ $espacio->nombre }}" 
                                 class="img-thumbnail"
                                 style="max-width: 200px;">
                            <p class="text-muted small mb-0">Imagen actual</p>
                        </div>
                    @endif
                    
                    <input type="file" 
                           class="form-control @error('imagen') is-invalid @enderror" 
                           id="imagen" 
                           name="imagen"
                           accept="image/*">
                    <small class="text-muted">Formatos: JPG, JPEG, PNG, GIF. Máximo 2MB. Deja vacío para mantener la imagen actual.</small>
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
                               {{ old('disponible', $espacio->disponible) ? 'checked' : '' }}>
                        <label class="form-check-label" for="disponible">
                            Espacio disponible
                        </label>
                    </div>
                </div>
            </div>

            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-primary">
                    ✓ Actualizar Espacio
                </button>
                <a href="{{ route('admin.espacios.index') }}" class="btn btn-secondary">
                    Cancelar
                </a>
            </div>
        </form>
    </div>
</div>
@endsection