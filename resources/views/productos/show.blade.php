@extends('layouts.app')

@section('title', $producto->nombre)

@section('content')
<div class="container mt-4">
    <div class="card">
        <div class="card-header">
            <h1>{{ $producto->nombre }}</h1>
        </div>
        <div class="card-body">
            <p><strong>Descripción:</strong> {{ $producto->descripcion }}</p>
            <p><strong>Precio:</strong> ${{ number_format($producto->precio, 2) }}</p>
            <!-- Puedes agregar más detalles del producto aquí -->
        </div>
        <div class="card-footer">
            <a href="{{ route('productos.index') }}" class="btn btn-primary">Volver a la lista de productos</a>
        </div>
    </div>
</div>
@endsection

{{-- con imagen --}}
{{-- @extends('layouts.app')

@section('title', $producto->nombre)

@section('content')
    <div class="container">
        <h1>{{ $producto->nombre }}</h1>
        <p>{{ $producto->descripcion }}</p>
        <p>Precio: ${{ $producto->precio }}</p>
        <!-- Mostrar la imagen del producto -->
        <div>
            <img src="{{ asset('images/' . $producto->imagen) }}" alt="{{ $producto->nombre }}" class="img-fluid">
        </div>
    </div>
@endsection
 --}}