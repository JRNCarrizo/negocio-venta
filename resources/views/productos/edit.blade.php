@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>Editar Producto</h1>
        <form action="{{ route('productos.update', $producto->id) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="form-group">
                <label for="nombre">Nombre:</label>
                <input type="text" name="nombre" class="form-control" value="{{ $producto->nombre }}" required>
            </div>
            <div class="form-group">
                <label for="descripcion">Descripción:</label>
                <textarea name="descripcion" class="form-control">{{ $producto->descripcion }}</textarea>
            </div>
            <div class="form-group">
                <label for="precio">Precio:</label>
                <input type="number" name="precio" class="form-control" value="{{ $producto->precio }}" step="0.01" required>
            </div>
            <div class="form-group">
                <label for="stock">Stock:</label>
                <input type="number" name="stock" class="form-control" value="{{ $producto->stock }}" required>
            </div>
            <div class="form-group">
                <label for="categoria">Categoría:</label>
                <input type="text" name="categoria" class="form-control" value="{{ $producto->categoria }}">
            </div>
            <button type="submit" class="btn btn-primary mt-3">Actualizar Producto</button>
        </form>
    </div>
@endsection
