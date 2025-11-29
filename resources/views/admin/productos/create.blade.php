@extends('layouts.admin')

@section('title', 'Nuevo Producto')

@section('content')
<div class="mb-4">
    <h1 class="h2">Crear Nuevo Producto</h1>
</div>

<div class="card">
    <div class="card-body">
        <form action="{{ route('admin.productos.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            
            <div class="mb-3">
                <label for="nombre" class="form-label">Nombre del Producto *</label>
                <input type="text" class="form-control @error('nombre') is-invalid @enderror" id="nombre" name="nombre" value="{{ old('nombre') }}" required>
                @error('nombre')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label for="descripcion" class="form-label">Descripción</label>
                <textarea class="form-control @error('descripcion') is-invalid @enderror" id="descripcion" name="descripcion" rows="3">{{ old('descripcion') }}</textarea>
                @error('descripcion')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label for="categoria" class="form-label">Categoría *</label>
                <select class="form-select @error('categoria') is-invalid @enderror" id="categoria" name="categoria" required>
                    <option value="">Seleccione una categoría</option>
                    <option value="comida" {{ old('categoria') == 'comida' ? 'selected' : '' }}>Comida</option>
                    <option value="bebida" {{ old('categoria') == 'bebida' ? 'selected' : '' }}>Bebida</option>
                    <option value="snack" {{ old('categoria') == 'snack' ? 'selected' : '' }}>Snack</option>
                </select>
                @error('categoria')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label for="precio" class="form-label">Precio *</label>
                <input type="number" step="0.01" class="form-control @error('precio') is-invalid @enderror" id="precio" name="precio" value="{{ old('precio') }}" required>
                @error('precio')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label for="stock" class="form-label">Stock *</label>
                <input type="number" class="form-control @error('stock') is-invalid @enderror" id="stock" name="stock" value="{{ old('stock', 0) }}" required>
                @error('stock')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label for="imagen" class="form-label">Imagen del Producto</label>
                <input type="file" class="form-control @error('imagen') is-invalid @enderror" id="imagen" name="imagen" accept="image/*">
                @error('imagen')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
                <small class="form-text text-muted">Formatos permitidos: JPG, PNG, GIF. Tamaño máximo: 2MB</small>
            </div>

            <div class="mb-3 form-check">
                <input type="checkbox" class="form-check-input" id="disponible" name="disponible" value="1" checked>
                <label class="form-check-label" for="disponible">Disponible</label>
            </div>

            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-primary">Guardar Producto</button>
                <a href="{{ route('admin.productos.index') }}" class="btn btn-secondary">Cancelar</a>
            </div>
        </form>
    </div>
</div>
@endsection