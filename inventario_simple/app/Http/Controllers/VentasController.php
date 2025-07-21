<?php

namespace App\Http\Controllers;

use App\Models\Cliente;
use App\Models\Producto;
use App\Models\Salidas;
use App\Models\SalidasDetalle;
use Illuminate\Http\Request;

class VentasController extends Controller
{
    public function index()
    {
        return inertia('Ventas/Ventas', [
           'clientes' => Cliente::select(['id','nombre'])->get(),
        ]);
    }
    public function store(Request $request){
        $request->validate([
            'productos'=> 'array|min:1|required'
        ]);
        $salida = Salidas::create([
            'tipo'=>'Venta',
            'cliente_id'=>$request->input('cliente_id'),
            'estado'=>'Completado',
        ]);
        foreach($request->input('productos') as   $producto  ){
            SalidasDetalle::create([
                'salida_id'=> $salida->id,
                'producto_id'=>$producto['producto_id'],
                'cantidad'=>$producto['cantidad'],
                'precio_unitario'=>$producto['precio_unitario']
            ]);
            Producto::where('id', $producto['producto_id'])
                ->decrement('cantidad', $producto['cantidad']);
        }
        
        return inertia('Ventas/Ventas', [
           'clientes' => Cliente::select(['id','nombre'])->get(),
        ]);
    }
}
