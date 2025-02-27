@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>Agregar Nuevo Producto</h1>
        <form action="{{ route('productos.store') }}" method="POST">
            @csrf
            <div class="form-group">
                <label for="nombre">Nombre:</label>
                <input type="text" name="nombre" class="form-control" required>
            </div>
            <div class="form-group">
                <label for="descripcion">Descripción:</label>
                <textarea name="descripcion" class="form-control"></textarea>
            </div>
            <div class="form-group">
                <label for="precio">Precio:</label>
                <input type="number" name="precio" class="form-control" step="0.01" required>
            </div>
            <div class="form-group">
                <label for="stock">Stock:</label>
                <input type="number" name="stock" class="form-control" required>
            </div>
            <div class="form-group">
                <label for="categoria">Categoría:</label>
                <input type="text" name="categoria" class="form-control">
            </div>
            <button type="submit" class="btn btn-success mt-3">Guardar Producto</button>
        </form>
    </div>
@endsection
