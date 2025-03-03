@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>Lista de Productos</h1>

        <!-- Carrito de compras en la esquina superior derecha -->
        <div class="carrito-indicator" style="position: fixed; top: 20px; right: 20px; z-index: 1000;">
            <a href="{{ route('carrito.index') }}" class="btn btn-primary">
                <i class="fas fa-shopping-cart"></i> 
                <span class="badge bg-danger" id="carrito-count">
                    {{ count(session()->get('carrito', [])) }}
                </span>
            </a>
        </div>

        <a href="{{ route('productos.create') }}" class="btn btn-primary mb-3">Agregar Nuevo Producto</a>

        @if ($productos->isEmpty())
            <p>No hay productos disponibles.</p>
           
        @else
            <table class="table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nombre</th>
                        <th>Descripción</th>
                        <th>Precio</th>
                        <th>Stock</th>
                        <th>Imagen</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($productos as $producto)
                        <tr>
                            <td>{{ $producto->id }}</td>
                            <td>{{ $producto->nombre }}</td>
                            <td>{{ $producto->descripcion }}</td>
                            <td>{{ $producto->precio }}</td>
                            <td>{{ $producto->stock }}</td>
                            <td>
                                {{-- Imagen (si la tienes) --}}
                                {{-- <img src="{{ asset('storage/' . $producto->imagen_url) }}" alt="{{ $producto->nombre }}" width="50"> --}}
                            </td>
                            <td>
                                <!-- Botones de Editar y Eliminar -->
                                <a href="{{ route('productos.edit', $producto->id) }}" class="btn btn-warning btn-sm">Editar</a>
                                <form action="{{ route('productos.destroy', $producto->id) }}" method="POST" style="display:inline-block;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('¿Estás seguro de eliminar este producto?')">Eliminar</button>
                                </form>

                                <!-- Formulario para agregar al carrito -->
                                <form action="{{ route('carrito.agregar', $producto->id) }}" method="POST" style="display:inline-block;">
                                    @csrf
                                    
                                    <!-- Campo para seleccionar cantidad con límite en el stock -->
                                    <input type="number" name="cantidad" min="1" max="{{ $producto->stock }}" value="1" class="form-control form-control-sm" style="width: 60px; display: inline-block;">
                                    
                                    <button type="submit" class="btn btn-success btn-sm">Agregar al carrito</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endif
    </div>
@endsection
