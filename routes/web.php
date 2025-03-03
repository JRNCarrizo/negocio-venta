<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductoController;
use App\Http\Controllers\CarritoController;


Route::get('/', function () {
    return view('welcome');
});



Route::resource('productos', ProductoController::class);
Route::get('/productos/{id}', [ProductoController::class, 'show'])->name('productos.show');






Route::prefix('carrito')->group(function () {
    // Mostrar el carrito
    Route::get('/', [CarritoController::class, 'index'])->name('carrito.index');
    
    // Agregar producto al carrito
    Route::post('/agregar/{id}', [CarritoController::class, 'agregar'])->name('carrito.agregar');
    
    // Eliminar un producto del carrito
    Route::delete('/eliminar/{id}', [CarritoController::class, 'eliminar'])->name('carrito.eliminar');
    
    // Vaciar el carrito
    Route::delete('/vaciar', [CarritoController::class, 'vaciar'])->name('carrito.vaciar');
});

Route::patch('/carrito/actualizar/{id}', [CarritoController::class, 'actualizar'])->name('carrito.actualizar');

