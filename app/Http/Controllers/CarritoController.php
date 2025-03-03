<?php

namespace App\Http\Controllers;

use App\Models\Producto;
use App\Models\Carrito;
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
                    'stock' => $producto->stock, // Asegurarse de incluir el stock
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

    /**
     * Actualiza la cantidad de un producto en el carrito.
     */
    public function actualizar(Request $request, $id)
    {
        // Obtener el carrito de la sesión
        $carrito = session()->get('carrito', []);

        // Verificar si el producto está en el carrito
        if (isset($carrito[$id])) {
            // Actualizar la cantidad del producto en el carrito
            $carrito[$id]['cantidad'] = $request->input('cantidad');

            // Guardar el carrito actualizado en la sesión
            session()->put('carrito', $carrito);

            // Calcular el total del carrito
            $total = 0;
            foreach ($carrito as $producto) {
                $total += $producto['precio'] * $producto['cantidad'];
            }

            // Retornar una respuesta JSON con el nuevo total
            return response()->json(['success' => true, 'total' => $total]);
        }

        // Si el producto no se encuentra en el carrito, retornar un error
        return response()->json(['success' => false], 400);
    }


    /**
     * Vacía el carrito.
     */
    public function vaciar()
    {
        // Vaciar el carrito de la sesión
        session()->forget('carrito');
        
        // Redirigir a la página del carrito con un mensaje de éxito
        return redirect()->route('carrito.index')->with('success', 'Carrito vacío.');
    }

    /**
     * Elimina un producto del carrito.
     */
    public function eliminar($id)
{
    // Obtener el carrito de la sesión
    $carrito = session()->get('carrito', []);

    // Verificar si el producto está en el carrito
    if (isset($carrito[$id])) {
        // Obtener la cantidad del producto que se va a eliminar
        $cantidad = $carrito[$id]['cantidad'];

        // Obtener el producto de la base de datos
        $producto = Producto::findOrFail($id);

        // Regresar la cantidad eliminada al stock
        $producto->stock += $cantidad;
        $producto->save();

        // Eliminar el producto del carrito
        unset($carrito[$id]);

        // Guardar el carrito actualizado en la sesión
        session()->put('carrito', $carrito);

        // Redirigir con un mensaje de éxito
        return redirect()->route('carrito.index')->with('success', 'Producto eliminado del carrito y stock actualizado.');
    }

    // Si el producto no está en el carrito, redirigir con un mensaje de error
    return redirect()->route('carrito.index')->with('error', 'Producto no encontrado en el carrito.');
}



public function contarCarrito()
{
    // Si el carrito está en la sesión, contar la cantidad de productos
    $carrito = session()->get('carrito', []);

    // Sumar las cantidades de los productos en el carrito
    $cantidad = array_sum(array_column($carrito, 'cantidad'));

    return response()->json([
        'cantidad' => $cantidad ?? 0 // Si es NULL, devolver 0
    ]);
}


}
