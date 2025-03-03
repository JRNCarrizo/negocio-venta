<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductoController;
use App\Http\Controllers\CartController;


Route::get('/', function () {
    return view('welcome');
});



Route::resource('productos', ProductoController::class);
Route::get('/productos/{id}', [ProductoController::class, 'show'])->name('productos.show');




