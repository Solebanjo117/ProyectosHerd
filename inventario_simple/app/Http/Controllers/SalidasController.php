<?php

namespace App\Http\Controllers;

use App\Models\Cliente;
use App\Models\Salidas;
use Illuminate\Http\Request;

class SalidasController extends Controller
{
    public function index()
    {
    $salidas = Salidas::select('id', 'estado', 'observaciones', 'tipo', 'cliente_id', 'updated_at')
    ->with('cliente:id,nombre')
    ->orderBy('created_at', 'desc')
    ->paginate(5);
    $salidas->getCollection()->each->append('updated_at_formatted');
        return inertia('Salidas/Salidas', [
            'salidas' => $salidas,
        ]);
    }
    public function edit(string $id)
    {
        $salida = Salidas::with(['detalles'])
            ->find($id);
        $salidas = Salidas::select('id', 'estado', 'observaciones', 'tipo', 'cliente_id', 'updated_at')
            ->with(['cliente:id,nombre','detalles'])
            ->orderBy('created_at', 'desc')
            ->paginate(5);
        $salidas->getCollection()->each->append('updated_at_formatted');
        return inertia('Salidas/Salidas', [
            'salida' => $salida,
            'salidas' => $salidas,
            'modo' => 'edit',
            'clientes' => Cliente::select('id', 'nombre')->orderBy('nombre')->get(),
        ]);
    }
    public function create()
    {
        $salidas = Salidas::select('id', 'estado', 'observaciones', 'tipo', 'cliente_id', 'updated_at')
            ->with(['cliente:id,nombre'])
            ->orderBy('created_at', 'desc')
            ->paginate(5);
            
        $salidas->getCollection()->each->append('updated_at_formatted');
        return inertia('Salidas/Salidas', [
            'modo' => 'create',
            'salidas' => $salidas,
            'salida' => new Salidas(),
            'clientes' => Cliente::select('id', 'nombre')->orderBy('nombre')->get(),
        ]);
    }
   
}
