<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;

class Cliente extends Controller
{
   public function index( Request $request){
      if($request->filled('search')) {
         $clientes = \App\Models\Cliente::where('nombre', 'like', '%' . $request->search . '%')
            ->orWhere('telefono', 'like', '%' . $request->search . '%')
            ->orWhere('email', 'like', '%' . $request->search . '%')
            ->paginate(perPage: 5)
            ->withQueryString();
         return Inertia::render('Clientes/Clientes', [
            'clientes' => $clientes,
         ]);
      }
    return Inertia::render('Clientes/Clientes', [
       'clientes' => \App\Models\Cliente::paginate(perPage: 5),
    ]);
   }
   public function create(){
    return Inertia::render('Clientes/Clientes', [
       'clientes' => \App\Models\Cliente::paginate(perPage: 5),
         'modo' => "create", //create-edit
         'cliente' => new \App\Models\Cliente(), // Crear un nuevo cliente
    ]);
   }
   public function edit(string $id){
      return Inertia::render('Clientes/Clientes', [
       'clientes' => \App\Models\Cliente::paginate(perPage: 5),
         'modo' => "edit", //create-edit
         'cliente' => \App\Models\Cliente::find($id), // Editar un cliente existente
    ]);
   }
   public function update(Request $request, string $id){
    $cliente = \App\Models\Cliente::find($id);
      $deuda = $cliente->deuda;
      $pago = $request->input('pago', 0);
      if($pago>0) $request->merge(['deuda' => $deuda - $pago]);
      $cliente->update($request->all());
      return redirect()->route('clientes.index');
   }
   public function store(Request $request){
      $cliente = \App\Models\Cliente::create($request->all());
      return redirect()->route('clientes.index')->with('success', 'Cliente creado correctamente.');
   }
}
