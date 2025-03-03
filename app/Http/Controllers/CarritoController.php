<?php

namespace App\Http\Controllers;

use App\Models\Producto;
use Illuminate\Http\Request;

class CarritoController extends Controller
{
    /**
     * Muestra los productos en el carrito.
     */
    public function index()
    {
        // Obtener los productos del carrito desde la sesión
        $carrito = session()->get('carrito', []);
        
        // Calcular el total del carrito
        $total = 0;
        foreach ($carrito as $producto) {
            $total += $producto['precio'] * $producto['cantidad'];
        }
    
        // Retornar la vista del carrito con los productos y el total
        return view('carrito.index', compact('carrito', 'total'));
    }
    
    /**
     * Agrega un producto al carrito.
     */


public function agregar($id)
{
    // Obtener el producto de la base de datos
    $producto = Producto::findOrFail($id);

    // Obtener la cantidad solicitada
    $cantidad = request()->input('cantidad', 1);

    // Verificar si hay suficiente stock
    if ($producto->stock >= $cantidad) {
        // Si hay suficiente stock, agregar al carrito
        $carrito = session()->get('carrito', []);

        // Verificar si el producto ya está en el carrito
        if (isset($carrito[$id])) {
            // Si el producto ya está en el carrito, incrementar la cantidad
            $carrito[$id]['cantidad'] += $cantidad;
        } else {
            // Si el producto no está en el carrito, agregarlo
            $carrito[$id] = [
                'nombre' => $producto->nombre,
                'precio' => $producto->precio,
                'cantidad' => $cantidad,
            ];
        }

        // Actualizar el carrito en la sesión
        session()->put('carrito', $carrito);

        // Actualizar el stock del producto
        $producto->stock -= $cantidad;
        $producto->save();

        // Redirigir con un mensaje de éxito
        return back()->with('success', 'Producto agregado al carrito.');
    } else {
        // Si no hay suficiente stock
        return back()->with('error', 'No hay suficiente stock disponible.');
    }
}

    

    public function vaciar()
{
    // Vaciar el carrito de la sesión
    session()->forget('carrito');
    
    // Redirigir a la página del carrito con un mensaje de éxito
    return redirect()->route('carrito.index')->with('success', 'Carrito vacío.');
}

// En CarritoController.php

public function eliminar($id)
{
    // Obtener el carrito de la sesión
    $carrito = session()->get('carrito', []);

    // Verificar si el producto está en el carrito
    if (isset($carrito[$id])) {
        // Eliminar el producto del carrito
        unset($carrito[$id]);

        // Guardar el carrito actualizado en la sesión
        session()->put('carrito', $carrito);

        // Redirigir con un mensaje de éxito
        return redirect()->route('carrito.index')->with('success', 'Producto eliminado del carrito.');
    }

    // Si el producto no está en el carrito, redirigir con un mensaje de error
    return redirect()->route('carrito.index')->with('error', 'Producto no encontrado en el carrito.');
}


}
