<?php

namespace App\Http\Controllers;

use App\Models\Entradas ;
use App\Models\EntradasDetalle;
use App\Models\Producto;
use Illuminate\Http\Request;
use Inertia\Inertia;

class EntradasController extends Controller
{
   public function index(){
        return Inertia::render('Entradas/Entradas', [
             'entradas' => Entradas::orderBy('created_at', 'desc')->paginate(5),
        ]);
   }
     public function create(){
          return Inertia::render('Entradas/Entradas', [
                 'entradas' => Entradas::orderBy('created_at', 'desc')->paginate(5),
                 'modo' => "create", //create-edit
                 'entrada' => new Entradas(), // Crear una nueva entrada
          ]);
    }
    public function edit(string $id){
        $entrada = Entradas::with('detalles.producto')->find($id);
          return Inertia::render('Entradas/Entradas', [
                 'entradas' => Entradas::orderBy('created_at', 'desc')->paginate(5),
                 'modo' => "edit", //create-edit
                 'entrada' => $entrada, // Editar una entrada existente
          ]);
    }
    public function update(Request $request, string $id){
      $request->validate([
            'detalles' => 'required|array|min:1', // Validar que hay al menos un detalle
            'detalles.*.producto_id' => 'required|exists:productos,id',
            'tipo' => 'required|string', // Tipo de entrada, puede ser compra o ajuste
            // Validar que el producto existe
        ]); // Minimo 1 de detalles, es una lista de productos
          $entrada = Entradas::with('detalles')->findOrFail($id);
          
          $entrada->update($request->all());
          foreach($entrada->detalles as $detalle) {
              Producto::where('id', $detalle->producto_id)
                  ->decrement('cantidad', $detalle->cantidad); // Decrementar cantidad del producto
          }
          $entrada->detalles()->delete(); // Eliminar detalles existentes
          $detalles = $request->input('detalles', []);
          foreach($detalles as $detalle) {
              EntradasDetalle::create([
                  'entrada_id' => $entrada->id,
                  'producto_id' => $detalle['producto_id'],
                  'cantidad' => $detalle['cantidad'],
                  'precio_unitario' => $detalle['precio_unitario'] ?? null, // opcional
              ]);
               Producto::where('id', $detalle['producto_id'])
                ->increment('cantidad', $detalle['cantidad']); // Actualizar cantidad del producto
          }
          return redirect()->route('entradas.index');
    }
    public function store(Request $request){
        $request->validate([
            'detalles' => 'required|array|min:1', // Validar que hay al menos un detalle
            'detalles.*.producto_id' => 'required|exists:productos,id',
            'tipo' => 'required|string', // Tipo de entrada, puede ser compra o ajuste
            // Validar que el producto existe
        ]); // Minimo 1 de detalles, es una lista de productos
        $detalles = $request->input('detalles');
        $entrada = Entradas::create([
            'referencia' => $request->input('referencia'),
            'tipo' => $request->input('tipo'),
            'observaciones' => $request->input('observaciones'),
        ]);
        foreach($detalles as $detalle) {
           EntradasDetalle::create([
                'entrada_id' => $entrada->id,
                'producto_id' => $detalle['producto_id'],
                'cantidad' => $detalle['cantidad'],
                'precio_unitario' => $detalle['precio_unitario'] ?? null, // opcional
            ]);
            Producto::where('id', $detalle['producto_id'])
                ->increment('cantidad', $detalle['cantidad']); // Actualizar cantidad del producto
        }
        
          return redirect()->route('entradas.index')->with('success', 'Entrada creada correctamente.');
    }
}
  
    