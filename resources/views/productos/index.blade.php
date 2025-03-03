@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>Lista de Productos</h1>

        <!-- Ícono del carrito con contador -->
        <div class="mb-3">
            <a href="{{ route('carrito.index') }}" class="btn btn-secondary">
                🛒 Carrito <span id="contador-carrito" class="badge bg-danger">0</span>
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
                                <form action="{{ route('carrito.agregar', $producto->id) }}" method="POST" style="display:inline-block;" id="agregar-carrito-form-{{ $producto->id }}">
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

<script>
    // Función para actualizar el contador del carrito
    function actualizarContadorCarrito() {
        fetch('{{ route('carrito.contar') }}')
            .then(response => response.json())
            .then(data => {
                console.log("Cantidad en el carrito:", data.cantidad);
                document.getElementById("contador-carrito").innerText = data.cantidad;
            })
            .catch(error => console.error("Error al obtener la cantidad del carrito:", error));
    }

    // Escuchar el evento de envío de los formularios de agregar al carrito
    document.querySelectorAll('[id^="agregar-carrito-form-"]').forEach(form => {
        form.addEventListener('submit', function(event) {
            event.preventDefault(); // Evitar el envío normal del formulario

            const formId = this.id.split('-').pop(); // Obtener ID del producto desde el formulario
            const cantidad = this.querySelector('input[name="cantidad"]').value;

            fetch(this.action, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({ cantidad: cantidad })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Actualizar el contador de productos en el carrito
                    actualizarContadorCarrito();
                } else {
                    alert("Hubo un error al agregar el producto al carrito.");
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert("Hubo un error al agregar el producto al carrito.");
            });
        });
    });

    // Llamar a la función de actualización del contador al cargar la página
    window.onload = actualizarContadorCarrito;
</script>
