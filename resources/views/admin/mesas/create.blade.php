@extends('layouts.admin')

@section('title', 'Nueva Mesa')

@section('content')
<div class="mb-4">
    <h1 class="h2">Crear Nueva Mesa</h1>
</div>

<div class="card">
    <div class="card-body">
        <form action="{{ route('admin.mesas.store') }}" method="POST">
            @csrf
            
            <div class="mb-3">
                <label for="numero" class="form-label">Número de Mesa *</label>
                <input type="text" class="form-control @error('numero') is-invalid @enderror" id="numero" name="numero" value="{{ old('numero') }}" required>
                @error('numero')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label for="capacidad" class="form-label">Capacidad (personas) *</label>
                <input type="number" class="form-control @error('capacidad') is-invalid @enderror" id="capacidad" name="capacidad" value="{{ old('capacidad') }}" required>
                @error('capacidad')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label for="ubicacion" class="form-label">Ubicación</label>
                <input type="text" class="form-control @error('ubicacion') is-invalid @enderror" id="ubicacion" name="ubicacion" value="{{ old('ubicacion') }}">
                @error('ubicacion')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-primary">Guardar Mesa</button>
                <a href="{{ route('admin.mesas.index') }}" class="btn btn-secondary">Cancelar</a>
            </div>
        </form>
    </div>
</div>
@endsection