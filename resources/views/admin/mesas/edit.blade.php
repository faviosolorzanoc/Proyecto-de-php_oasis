@extends('layouts.admin')

@section('title', 'Editar Mesa')

@section('content')
<div class="mb-4">
    <h1 class="h2">Editar Mesa: {{ $mesa->numero }}</h1>
</div>

<div class="card">
    <div class="card-body">
        <form action="{{ route('admin.mesas.update', $mesa) }}" method="POST">
            @csrf
            @method('PUT')
            
            <div class="mb-3">
                <label for="numero" class="form-label">Número de Mesa *</label>
                <input type="text" class="form-control @error('numero') is-invalid @enderror" id="numero" name="numero" value="{{ old('numero', $mesa->numero) }}" required>
                @error('numero')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label for="capacidad" class="form-label">Capacidad (personas) *</label>
                <input type="number" class="form-control @error('capacidad') is-invalid @enderror" id="capacidad" name="capacidad" value="{{ old('capacidad', $mesa->capacidad) }}" required>
                @error('capacidad')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label for="ubicacion" class="form-label">Ubicación</label>
                <input type="text" class="form-control @error('ubicacion') is-invalid @enderror" id="ubicacion" name="ubicacion" value="{{ old('ubicacion', $mesa->ubicacion) }}">
                @error('ubicacion')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label for="estado" class="form-label">Estado *</label>
                <select class="form-select @error('estado') is-invalid @enderror" id="estado" name="estado" required>
                    <option value="disponible" {{ old('estado', $mesa->estado) == 'disponible' ? 'selected' : '' }}>Disponible</option>
                    <option value="ocupada" {{ old('estado', $mesa->estado) == 'ocupada' ? 'selected' : '' }}>Ocupada</option>
                    <option value="reservada" {{ old('estado', $mesa->estado) == 'reservada' ? 'selected' : '' }}>Reservada</option>
                </select>
                @error('estado')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-primary">Actualizar Mesa</button>
                <a href="{{ route('admin.mesas.index') }}" class="btn btn-secondary">Cancelar</a>
            </div>
        </form>
    </div>
</div>
@endsection