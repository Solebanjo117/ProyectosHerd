<?php

namespace App\Http\Controllers;

use App\Models\Producto;
use App\Models\Salidas;
use App\Models\SalidasDetalle;
use Illuminate\Http\Request;

class SalidasDetalleController extends Controller
{
    
    public function store(Request $request)
    {
         $request->validate([
            'detalles' => 'required|min:1',
            'detalles.*.producto_id' => 'required|exists:productos,id',
        ]);
       $formSalida = $request->input('formulario'); // Obtener el formulario enviado de Form a SalidasDetalle, con informacion de la salida a crear
       $salida = Salidas::create([
        'tipo' => $formSalida['tipo'],
        'cliente_id' => $formSalida['cliente_id'],
        'estado' => $formSalida['estado'],
        'referencia' => $formSalida['referencia'],
        'observaciones' => $formSalida['observaciones'],
       ]);
         $detalles = $request->input('detalles', []); // Obtener los detalles de la salida
        foreach ($detalles as $detalle) {
            SalidasDetalle::create([
                'salida_id' => $salida->id,
                'producto_id' => $detalle['producto_id'],
                'cantidad' => $detalle['cantidad'],
                'precio_unitario' => $detalle['precio_unitario'],
            ]);
            // Disminuir la cantidad del producto, al realizar una salida
            Producto::where('id', $detalle['producto_id'])
                ->decrement('cantidad', $detalle['cantidad']);
        }
        // Redirigir a la lista de salidas con un mensaje de éxito
        return redirect()->route('salidas.index')->with('success', 'Detalle de salida creado exitosamente.');
    }

  

    public function update(Request $request, $id)
    {
       
        $salida = Salidas::with('detalles')->find($id);
        $formulario = $request->input('formulario');
        $detalles = $request->input('detalles', []);
        $request->validate([
            'detalles' => 'required|min:1',
            'detalles.*.producto_id' => 'required|exists:productos,id',
        ]);
        //al borrarse detalles, se suman las cantidades de los productos eliminados
        $salida->update([
            'tipo' => $formulario['tipo'],
            'cliente_id' => $formulario['cliente_id'],
            'estado' => $formulario['estado'],
            'referencia' => $formulario['referencia'],
            'observaciones' => $formulario['observaciones'],
        ]);
        foreach ($salida->detalles as $detalle) {
            Producto::where('id', $detalle['producto_id'])
                ->increment('cantidad', $detalle['cantidad']);
        }
         $salida->detalles()->delete();
        foreach ($detalles as $detalle) {
            SalidasDetalle::create([
                'salida_id' => $salida->id,
                'producto_id' => $detalle['producto_id'],
                'cantidad' => $detalle['cantidad'],
                'precio_unitario' => $detalle['precio_unitario'],
            ]);
           Producto::where('id', $detalle['producto_id'])
                ->decrement('cantidad', $detalle['cantidad']); // Disminuir la cantidad del producto, al realizar una salida

        }
        return redirect()->route('salidas.index');
    }
}
