@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>Carrito de Compras</h1>

        @if (empty($carrito))
            <p>No hay productos en el carrito.</p>
            <a href="{{ route('productos.index') }}" class="btn btn-primary">Volver a Productos</a>
        @else
            <table class="table">
                <thead>
                    <tr>
                        <th>Nombre</th>
                        <th>Precio</th>
                        <th>Cantidad</th>
                        <th>Total</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($carrito as $productoId => $producto)
                        <tr>
                            <td>{{ $producto['nombre'] }}</td>
                            <td>{{ $producto['precio'] }}</td>
                            <td>{{ $producto['cantidad'] }}</td>
                            <td>{{ $producto['cantidad'] * $producto['precio'] }}</td>
                            <td>
                                <form action="{{ route('carrito.eliminar', $productoId) }}" method="POST" style="display:inline-block;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm">Eliminar</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            <!-- Mostrar el total -->
            <div class="alert alert-info">
                <strong>Total:</strong> ${{ $total }}
            </div>

            <form action="{{ route('carrito.vaciar') }}" method="POST" style="display:inline-block;">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-warning">Vaciar carrito</button>
            </form>

            <a href="{{ route('productos.index') }}" class="btn btn-primary">Seguir comprando</a>
        @endif
    </div>
@endsection
