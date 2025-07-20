<?php

namespace App\Http\Controllers;

use App\Models\Cliente;
use Illuminate\Http\Request;

class VentasController extends Controller
{
    public function index()
    {
        return inertia('Ventas/Ventas', [
           'clientes' => Cliente::select(['id','nombre'])->get(),
        ]);
    }
}
