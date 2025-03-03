@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>Carrito de Compras</h1>

        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        @if (count(session()->get('carrito', [])) > 0)
            <table class="table">
                <thead>
                    <tr>
                        <th>Producto</th>
                        <th>Precio</th>
                        <th>Cantidad</th>
                        <th>Total</th>
                        <th>Acción</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach (session()->get('carrito', []) as $id => $producto)
                        <tr>
                            <td>{{ $producto['nombre'] }}</td>
                            <td id="precio_{{ $id }}">{{ $producto['precio'] }} $</td>
                            <td>
                                <!-- Campo para modificar la cantidad -->
                                <input 
                                    type="number" 
                                    name="cantidad" 
                                    value="{{ $producto['cantidad'] }}" 
                                    min="1" 
                                    max="{{ $producto['stock'] }}" 
                                    class="form-control cantidad" 
                                    data-id="{{ $id }}" 
                                    style="width: 70px;">
                            </td>
                            <td class="subtotal" data-id="{{ $id }}">
                                {{ $producto['precio'] * $producto['cantidad'] }} $
                            </td>
                            <td>
                                <!-- Botón para eliminar del carrito -->
                                <form action="{{ route('carrito.eliminar', $id) }}" method="POST" style="display:inline-block;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm">Eliminar</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            <!-- Total del carrito -->
            <div class="d-flex justify-content-between">
                <h3>Total:</h3>
                <h3 id="total">{{ $total }} $</h3>
            </div>

            <!-- Botón para cancelar la orden y vaciar el carrito -->
            <form action="{{ route('carrito.cancelar') }}" method="POST" style="display:inline-block;">
                @csrf
                <button type="submit" class="btn btn-danger">Cancelar Orden</button>
            </form>

            <a href="{{ route('productos.index') }}" class="btn btn-primary">Volver a productos</a>
        @else
            <p>No hay productos en el carrito.</p>
            <a href="{{ route('productos.index') }}" class="btn btn-primary">Volver a productos</a>
        @endif
    </div>

    <script>
        document.querySelectorAll('.cantidad').forEach(input => {
            input.addEventListener('input', function() {
                const id = this.getAttribute('data-id');
                const cantidad = parseInt(this.value);
                const precio = parseFloat(document.querySelector(`#precio_${id}`).textContent);
                
                // Actualizar el subtotal en el DOM
                const subtotalCell = document.querySelector(`.subtotal[data-id="${id}"]`);
                const subtotal = cantidad * precio;
                subtotalCell.textContent = subtotal.toFixed(2) + ' $';

                // Actualizar el total
                let total = 0;
                document.querySelectorAll('.subtotal').forEach(subtotalElem => {
                    total += parseFloat(subtotalElem.textContent);
                });
                document.getElementById('total').textContent = total.toFixed(2) + ' $';

                // Enviar la nueva cantidad al servidor para actualizar el carrito
                fetch(`{{ url('carrito/actualizar') }}/${id}`, {
                    method: 'PATCH',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify({ cantidad: cantidad })
                })
                .then(response => response.json())
                .then(data => {
                    if (!data.success) {
                        alert("Hubo un error al actualizar el carrito.");
                    }
                })
                .catch(error => console.error('Error:', error));
            });
        });
    </script>
@endsection
